<?php
/**
 * Metabox for this posttype
 */

namespace Palasthotel\ProLitteris;

use Palasthotel\ProLitteris\Model\Pixel;
use WP_Error;
use WP_Post;

/**
 * @property Plugin plugin
 */
class MetaBox extends _Component {

	const ACTION_AJAX_SEND_MESSAGE = "litteris_send_message";

	function onCreate() {
		parent::onCreate();

		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ), 10, 2 );
		add_action( 'save_post', array($this, 'save_post'));

		add_action( 'wp_ajax_'.self::ACTION_AJAX_SEND_MESSAGE, array($this, 'ajax_send_message'));
	}

	/**
	 * Hooks into WordPress' add_meta_boxes function.
	 * Goes through screens (post types) and adds the meta box.
	 *
	 * @param $post_type
	 * @param $post
	 */
	public function add_meta_boxes( $post_type, $post ) {
		$title = __( 'ProLitteris', Plugin::DOMAIN );
		foreach ( $this->plugin->pixel->enabledPostTypes() as $screen ) {
			add_meta_box(
				'pro_litteris',
				$title,
				array( $this, 'render_meta_box' ),
				$screen,
				'advanced',
				'default'
			);
		}
	}

	/**
	 * @param WP_Post $post
	 */
	public function render_meta_box(WP_Post $post){

		$post_id = $post->ID;

		//------------------------------------------------------
		// requirements for a pixel
		//------------------------------------------------------
		$text = $this->plugin->post->getPostText($post_id);
		if(!$this->plugin->post->needsPixel($post_id, $text)){
			echo "<p>Zählpixel werden erst ab ".Plugin::PRO_LITTERIS_MIN_CHAR_COUNT." Zeichen abgerufen. Dieser Text zählt ".strlen($text). " Zeichen.";
			return;
		}

		//------------------------------------------------------
		// is pixel assigned or assignable?
		//------------------------------------------------------
		$pixel = $this->plugin->repository->getPostPixel($post_id, true);
		if($pixel instanceOf WP_Error){

			$error = $pixel->get_error_message(Plugin::ERROR_CODE_REQUEST);
			if(empty($error)) $error = $pixel->get_error_message();

			printf( "<p style='color: #8d0000;'>%s</p>", $error);
			return;

		}

		if( !($pixel instanceof Pixel) ){
			printf("<p style='color: #8d0000;'>Hmmm... something went really wrong if pixel is null here.</p>");
			return;
		}

		echo sprintf( '<p>%s</p>', $pixel->toUrl() );

		//------------------------------------------------------
		// content report
		//------------------------------------------------------
		echo "<h3>Meldung</h3>";

		$messageResponse = get_post_meta($post_id, Plugin::POST_META_PRO_LITTERIS_MESSAGE_RESPONSE, true);

		if(is_object($messageResponse)){
			$created = $messageResponse->createdAt;
			echo "<p>Inhalt wurde am $created gemeldet.</p>";
			return;
		}

		$message = null;
		try{
			$message = $this->plugin->post->getPostMessage($post_id);
		}catch (NoParticipantException $e){
			echo "<p>🚨 Es gibt keine validen Autoren für eine Meldung an ProLitteris.</p>";
			return;
		}

		if(!MessageUtils::isMessageValid($message)){
			echo "<p>🚨 Konnte keine valide Meldung bauen. Haben alle Autoren ProLitteris Mitgliedsnummern?</p>";
			return;
		}

		$pixelUid = $message["pixelUid"];
		echo "<p><strong>Pixel ID: </strong> $pixelUid</p>";

		echo "<p><strong>Titel:</strong> ".$message["title"]."</p>";

		$text = base64_decode($message["messageText"]["plainText"]);
		echo "<p><strong>Text ( ".strlen($text)." Zeichen):</strong><br/>";
		echo "<textarea readonly='readonly' style='width: 100%;' rows='10'>";
		echo $text;
		echo "</textarea></p>";

		foreach ($message["participants"] as $participant){
			$proLitterisId = $participant["memberId"];
			$systemId = $participant["internalIdentification"];
			$firstName = $participant["firstName"];
			$surName = $participant["surName"];
			$participation = $participant['participation'];
			echo "<p>";
			echo "Vorname: $firstName<br/>";
			echo "Nachname: $surName<br/>";
			echo "ProLitteris ID: $proLitterisId<br/>";
			echo "System ID: $systemId<br />";
			echo "Aufgabe: $participation";
			echo "</p>";

		}

		echo "<p class='warning'>⚠️Dies ist der Stand seit dem letzten Reload dieser Seite. Änderungen werden nicht direkt übernommen, sondern sind erst nach einem Reload verfügbar. ⚠️</p>";
		echo "<button class='button-primary' id='pro-litteres-send-message'>Meldung abschicken</button>";
		echo "<p id='pro-litteris-message-response' class='description'></p>";

		$param_action = "action=".self::ACTION_AJAX_SEND_MESSAGE;
		$param_post_id = "post_id=$post_id";
		?>
		<script>
			jQuery(function($){
				let sending = false;
				const $response = $("#pro-litteris-message-response");
				$("#pro-litteres-send-message").on("click", function(e){
					e.preventDefault();
					const $button = $(this);
					if(sending) return;
					sending = true;
					$button.prop('disabled', true);
					jQuery.get(ajaxurl+"?<?php echo $param_action ?>&<?php echo $param_post_id ?>", function(response){
						$button.prop('disabled', false);
						sending = false;
						console.log(response);
						if(response.success){
							$button.hide();
						}
						$response.text(JSON.stringify(response.data));
					});
				});
			});
		</script>
		<?php
	}

	/**
	 * @param $post_id
	 */
	public function save_post($post_id){

		// it not allowed to edit skip it!
		if(!current_user_can("edit_post", $post_id)) return;

		// If this is just a revision, don't do anything
		if ( wp_is_post_revision( $post_id ) ) {
			return;
		}

		$post_type = get_post_type( $post_id );

		// Wee need to do this only for few post types
		if ( ! $this->plugin->pixel->isEnabled($post_type)) {
			return;
		}

		$snippet = Service::getSnippet($post_id);

		// if we already got a snippet skip
		if(is_string($snippet) && !empty($snippet)){
			return;
		}

		// if we got an error, only fetch again if retry is activated
		if($snippet instanceof WP_Error ) return;

		// save cleaned up text stats
		$text = $this->plugin->post->getPostText($post_id);
		$needsPixel = $this->plugin->post->needsPixel($post_id, $text);
		$this->plugin->post->saveNeedsPixel($post_id, $needsPixel);

		if(!$needsPixel){
			return;
		}

		$this->plugin->post->savePostText($post_id, $text);

		// lets fetch a snippet
		$response = Service::fetch($post_id);
		if($response instanceof WP_Error){
			Service::saveError($post_id, $response);
		} else {
			Service::saveSnippet($post_id, $response);
		}

	}

	/**
	 * ajax call resolver
	 */
	public function ajax_send_message(){
		$post_id = intval($_GET["post_id"]);
		if(!current_user_can("edit_post", $post_id)) wp_die("No access");

		$message = [];
		try{
			$message = $this->plugin->post->getPostMessage($post_id);
		} catch (NoParticipantException $e){
			update_post_meta($post_id, Plugin::POST_META_PRO_LITTERIS_MESSAGE_ERROR, "no valid participant");
			wp_send_json_error(["message" => "no valid participant"]);
		}

		if(!MessageUtils::isMessageValid($message)){
			wp_send_json_error(array(
				"message" => "Message object is not valid",
				"object" => $message,
			));
		}

		$response = $this->plugin->api->pushMessage($message);

		if($response instanceof WP_Error) wp_send_json_error($response);

		update_post_meta($post_id, Plugin::POST_META_PRO_LITTERIS_MESSAGE_OBJECT, $message);
		update_post_meta($post_id, Plugin::POST_META_PRO_LITTERIS_MESSAGE_RESPONSE, $response);

		wp_send_json_success(array(
			"object" => $response,
		));
	}

}
