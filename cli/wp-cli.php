<?php


namespace Palasthotel\ProLitteris;


if ( ! defined( 'WP_CLI' ) || ! WP_CLI ) {
	return;
}

class CLI {

	/**
	 * Fetch pixel for post without one
	 *
	 * ## OPTIONS
	 *
	 * <post_type>
	 * : Post type.
	 *
	 *
	 * [--months=<month>]
	 * : number of month to fetch
	 * ---
	 * default: 3
	 * ---
	 *
	 * ## EXAMPLES
	 *
	 *     wp pro-litteris fetch post --month=1
	 *
	 * @when after_wp_load
	 */
	public function fetch($args, $assoc_args){
		list( $post_type ) = $args;
		if(empty($post_type)){
			\WP_CLI::error("No post type selected");
			return;
		}
		$months = $assoc_args['months'];
		\WP_CLI::log( "Fetching pixels $post_type of last $months months!" );

		/**
		 * @var \wpdb $wpdb
		 */
		global $wpdb;
		$where_time = " post_date > DATE_SUB(NOW(), INTERVAL $months MONTH) ";
		$queryReduceScope = "SELECT ID FROM $wpdb->posts WHERE $where_time AND post_type = '$post_type'";

		ob_start();
		?>
		SELECT ID FROM <?= $wpdb->posts ?> WHERE
		ID NOT IN (
			SELECT post_id FROM <?= $wpdb->postmeta ?> WHERE meta_key = '<?= Plugin::POST_META_PRO_LITTERIS ?>' AND post_id IN ( <?= $queryReduceScope; ?>	)
		)
		AND ID NOT IN (
			SELECT post_id FROM <?= $wpdb->postmeta ?> WHERE meta_key = '<?= Plugin::POST_META_PRO_LITTERIS_NOT_NEEDED; ?>' AND post_id IN ( <?= $queryReduceScope; ?>	)
		)
		AND <?= $where_time; ?>
		AND post_type = '<?= $post_type; ?>'
		AND post_status = "publish"
		AND length(post_content) > <?= intval(Plugin::PRO_LITTERIS_MIN_CHAR_COUNT/2); ?>
		ORDER BY ID DESC
		<?php
		$needLitterisCheckQuery = ob_get_contents();
		ob_end_clean();

		$num = $wpdb->get_var("SELECT count(ID) FROM $wpdb->posts WHERE ID IN ($needLitterisCheckQuery)");
		\WP_CLI::log( "Found $num contents!" );

		$pluginPost = Plugin::instance()->post;
		$ids = $wpdb->get_col($needLitterisCheckQuery);
		$idsCount = count($ids);
		if($idsCount < 1){
			\WP_CLI::success( "No pixels needed!" );
			return;
		}

		$progress = \WP_CLI\Utils\make_progress_bar( 'Fetching pixels', $idsCount-1 );

		foreach ($ids as $post_id){
			$text = $pluginPost->getPostText($post_id);
			$needsPixel = $pluginPost->needsPixel($post_id, $text);
			$pluginPost->saveNeedsPixel($post_id, $needsPixel);
			if(!$needsPixel){
				$progress->tick();
				continue;
			}

			// lets fetch a snippet
			$response = Service::fetch($post_id);
			if($response instanceof \WP_Error){
				Service::saveError($post_id, $response);
				\WP_CLI::error( "Could not get pixel for $post_id ".$response->get_error_message(Plugin::ERROR_CODE_REQUEST) );
			} else {
				Service::saveSnippet($post_id, $response);
			}
			$progress->tick();

		}


		\WP_CLI::success( "Fetched pixels for $post_type of last $months months!" );
	}

	/**
	 * Send report message for contents
	 *
	 * ## OPTIONS
	 *
	 * [--limit=<number>]
	 * : number of posts to report
	 * ---
	 * default: 0
	 * ---
	 *
	 * [--errors=<type>]
	 * : should we retry errors or not?
	 * ---
	 * default: ignore
	 *   - ignore
	 *   - retry
	 * ---
	 *
	 * ## EXAMPLES
	 *
	 *     wp pro-litteris report post --limit=20 --ingore-errors=false
	 *
	 * @when after_wp_load
	 */
	public function report($args, $assoc_args){
		global $wpdb;

		$limit = $assoc_args["limit"];
		$retryErrors = ($assoc_args["errors"] == "retry");

		$selectPixels = "SELECT post_id FROM $wpdb->postmeta WHERE meta_key = '".Plugin::POST_META_PRO_LITTERIS."'";
		$selectReports = "SELECT post_id FROM $wpdb->postmeta WHERE meta_key = '".Plugin::POST_META_PRO_LITTERIS_MESSAGE_RESPONSE."'";
		$selectErrors = "SELECT post_id FROM $wpdb->postmeta WHERE meta_key = '".Plugin::POST_META_PRO_LITTERIS_MESSAGE_ERROR."'";

		if($retryErrors){
			$query = "SELECT ID FROM $wpdb->posts WHERE ID IN ($selectPixels) AND ID NOT IN ($selectReports)";
		} else {
			$query = "SELECT ID FROM $wpdb->posts WHERE ID IN ($selectPixels) AND ID NOT IN ($selectReports) AND ID NOT IN ($selectErrors)";
		}

		if($limit > 0) $query.= " LIMIT $limit";

		$post_ids = $wpdb->get_col($query);

		if(count($post_ids) <=0){
			\WP_CLI::success("No contents to report left.");
			return;
		}

		$progress = \WP_CLI\Utils\make_progress_bar( 'Reporting', count($post_ids) );

		$results = new \stdClass();
		$results->noParticipant = 0;
		$results->invalid = 0;
		$results->responseError = 0;
		$results->success = 0;

		foreach ($post_ids as $index =>  $post_id){

			try{
				$message = Plugin::instance()->post->getPostMessage($post_id);
			} catch (NoParticipantException $e){
				update_post_meta($post_id, Plugin::POST_META_PRO_LITTERIS_MESSAGE_ERROR, "no valid participant");
				$results->noParticipant++;
				$progress->tick();
				continue;
			}

			if(!Service::isMessageValid($message)){
				update_post_meta($post_id, Plugin::POST_META_PRO_LITTERIS_MESSAGE_ERROR, "Internal: Message not valid.");
				$results->invalid++;
				$progress->tick();
				continue;
			}

			$response = Service::pushMessage($post_id, $message);

			if($response instanceof \WP_Error){
				$results->responseError++;
				update_post_meta($post_id, Plugin::POST_META_PRO_LITTERIS_MESSAGE_ERROR, $response);
				\WP_CLI::warning($response->get_error_message());
			} else {
				delete_post_meta($post_id, Plugin::POST_META_PRO_LITTERIS_MESSAGE_ERROR);
				update_post_meta($post_id, Plugin::POST_META_PRO_LITTERIS_MESSAGE_OBJECT, $message);
				update_post_meta($post_id, Plugin::POST_META_PRO_LITTERIS_MESSAGE_RESPONSE, $response);
			}
			$progress->tick();
		}

		\WP_CLI::success(sprintf("Reported: %s", json_encode($results)));

	}

	/**
	 * Get numbers for pro litteris
	 *
	 * ## EXAMPLES
	 *
	 *     wp pro-litteris stats
	 *
	 * @when after_wp_load
	 */
	public function stats(){
		/**
		 * @var \wpdb $wpdb
		 */
		global $wpdb;

		$stats = [
			[
				"meta_key" => Plugin::POST_META_PRO_LITTERIS_ERROR,
				"label" => "Errors fetch pixel",
			],
			[
				"meta_key" => Plugin::POST_META_PRO_LITTERIS,
				"label" => "Pixels",
			],
			[
				"meta_key" => Plugin::POST_META_PRO_LITTERIS_MESSAGE_ERROR,
				"label" => "Errors report message",
			],
			[
				"meta_key" => Plugin::POST_META_PRO_LITTERIS_MESSAGE_RESPONSE,
				"label" => "Reported",
			]
		];

		$stats = array_map(function($stat) use ( $wpdb ) {
			$num = $wpdb->get_var(
				$wpdb->prepare(
					"SELECT count(meta_id) FROM $wpdb->postmeta WHERE meta_key = %s",
					$stat["meta_key"]
				)
			);
			return $stat+["value" =>  $num];
		}, $stats);


		\WP_CLI\Utils\format_items( 'table',
			array_map(function($stat){
				return [
					"Stat" => $stat["label"],
					"Count" => $stat["value"],
				];
			}, $stats),
			[ 'Stat', 'Count' ]
		);
	}

}


\WP_CLI::add_command(
	"pro-litteris",
	__NAMESPACE__."\CLI",
	array(
		'shortdesc' => 'Pro Service commands.',
	)
);