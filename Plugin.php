<?php

/**
 * Plugin Name: ProLitteris
 * Description: Integration of prolitteris.ch services.
 * Version: 1.1.0
 * Author: Palasthotel <rezeption@palasthotel.de> (Edward Bock)
 * Author URI: https://palasthotel.de
 */

namespace Palasthotel\ProLitteris;

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

/**
 * @property string path
 * @property string url
 * @property MetaBox $metaBox
 * @property PostsTable postList
 * @property Post post
 * @property User user
 * @property Pixel pixel
 */
class Plugin {

	/**
	 * Domain for translation
	 */
	const DOMAIN = "pro-litteris";

	const FILTER_POST_AUTHORS = "pro_litteris_post_authors";
	const FILTER_POST_TYPES = "pro_litteris_post_types";
	const FILTER_RENDER_PIXEL= "pro_litteris_render_pixel";

	/**
	 * constants
	 */
	const PRO_LITTERIS_MIN_CHAR_COUNT = 2000;

	/**
	 * Meta fields
	 */
	const POST_META_PRO_LITTERIS_API_PIXEL_RESPONSE = "_pro_litteris_response";
	const POST_META_PRO_LITTERIS = "pro-litteris";
	const POST_META_PRO_LITTERIS_ERROR = "pro-litteris-error";
	const POST_META_PRO_LITTERIS_RAW_TEXT = "_pro_litteris_raw_text";
	const POST_META_PRO_LITTERIS_NOT_NEEDED = "_pro_litteris_not_needed";
	const POST_META_PRO_LITTERIS_MESSAGE_OBJECT = "_pro_litteris_message_object";
	const POST_META_PRO_LITTERIS_MESSAGE_ERROR = "_pro_litteris_message_error";
	const POST_META_PRO_LITTERIS_MESSAGE_RESPONSE = "_pro_litteris_message_response";
	const POST_META_PRO_LITTERIS_MESSAGE_SENT_DATE = "_pro_litteris_message_sent_date";

	const USER_META_PRO_LITTERIS_ID = "_pro_litteris_id";
	const USER_META_PRO_LITTERIS_NAME = "_pro_litteris_name";
	const USER_META_PRO_LITTERIS_SURNAME = "_pro_litteris_surname";

	/**
	 * error codes
	 */
	const ERROR_CODE_CONFIG = 'pro-litteris-config-error';
	const ERROR_CODE_REQUEST = 'pro-litteris-request-error';

	/**
	 * Plugin constructor
	 */
	private function __construct() {

		/**
		 * Base paths
		 */
		$this->path = plugin_dir_path( __FILE__ );
		$this->url  = plugin_dir_url( __FILE__ );

		//do stuff from this plugin only if activated in config, only on production
		if ( defined( 'PH_PRO_LITTERIS' ) && PH_PRO_LITTERIS ) {

			require_once dirname( __FILE__ ) . "/vendor/autoload.php";

			$this->metaBox  = new MetaBox( $this );
			$this->post     = new Post( $this );
			$this->postList = new PostsTable( $this );
			$this->user     = new User( $this );
			$this->pixel    = new Pixel( $this );

		}
	}

	public function is_valid_snippet( $snippet ) {
		// if is error, skip
		if ( $snippet instanceof \WP_Error ) {
			return false;
		}

		// try one time only if is not error
		if ( empty( $snippet ) ) {
			$snippet = Service::fetchAndSave( get_the_ID() );

		}

		// if there is nothing to add skip it
		if ( empty( $snippet ) || $snippet instanceof \WP_Error ) {
			return false;
		}

		return true;
	}


	/**
	 * @var Plugin $instance
	 */
	private static $instance;

	/**
	 * @return Plugin
	 */
	public static function instance() {
		if ( Plugin::$instance === null ) {
			Plugin::$instance = new Plugin();
		}

		return Plugin::$instance;
	}
}

Plugin::instance();

require_once dirname( __FILE__ ) . "/cli/wp-cli.php";