<?php


namespace Palasthotel\ProLitteris;


/**
 * @property Plugin plugin
 */
class PostsTable {

	/**
	 * PostList constructor.
	 *
	 * @param Plugin $plugin
	 */
	public function __construct($plugin) {
		$this->plugin = $plugin;
		add_filter( 'manage_posts_columns' , array($this, 'add_column') );
		add_action( 'manage_posts_custom_column' , array($this,'custom_columns'), 10, 2 );
	}

	public function add_column($columns){

		$newCols = array();
		$added = false;
		foreach ($columns as $key => $label){
			if( !$added && ($key == "comments" || $key == "date") ){
				$added = true;
				$newCols['pro-litteris'] = "ProLitteris";
			}
			$newCols[$key] = $label;
		}

		// if to any reason there is no comments or date column add it to the last position
		if($added == false){
			$newCols['pro-litteris'] = "ProLitteris";
		}

		return $newCols;
	}

	public function custom_columns($column, $post_id){
		if($column == 'pro-litteris'){

			if($this->plugin->post->isReported($post_id)){
				echo "<span title='Inhalt wurde bei ProLitteris gemeldet' style='cursor: help;'>✅</span>";
				return;
			}

			if(!$this->plugin->post->needsPixel($post_id)){
				echo "<span title='Es wird kein Pixel benötigt, weil der Text unter ".Plugin::PRO_LITTERIS_MIN_CHAR_COUNT." Zeichen hat.' style='cursor: help;'>⚪️</span>";
				return;
			}

			$db_value = Service::getSnippet($post_id);

			if($db_value instanceOf \WP_Error){
				$error = $db_value->get_error_message(Plugin::ERROR_CODE_REQUEST);
				echo "<span title='$error' style='cursor: help;'>🔴</span>";
				return;
			} else if( $db_value !== false && ! empty( $db_value ) ) {
				echo "<span title='Inhalt ist bereit für die Meldung bei ProLitteris' style='cursor: help;'>🔶</span>";
				return;
			}

			echo "<span title='Noch kein Zählpixel bei ProLitteris abgeholt' style='cursor: help;'>🔵</span>";
		}
	}
}