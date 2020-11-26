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
 * @property TrackingPixel pixel
 * @property Database database
 * @property Repository repository
 * @property API api
 * @property Schedule schedule
 * @property DashboardWidget dashboardWidget
 */
class Plugin {

	/**
	 * Domain for translation
	 */
	const DOMAIN = "pro-litteris";

	/**
	 * ids
	 */
	const DASHBOARD_WIDGET_ID = "pro_litteris_dashboard";

	/**
	 * Schedules
	 */
	const SCHEDULE_REFILL_PIXEL_POOL = "pro_litteris_schedule_refill_pixel_pool";

	/**
	 * filters
	 */
	const FILTER_POST_AUTHORS = "pro_litteris_post_authors";
	const FILTER_POST_TYPES = "pro_litteris_post_types";
	const FILTER_RENDER_PIXEL = "pro_litteris_render_pixel";

	/**
	 * Options
	 */
	const OPTION_PIXEL_POOL_SIZE = "_pro_litteris_pixel_pool_size";

	/**
	 * constants
	 */
	const PRO_LITTERIS_MIN_CHAR_COUNT = 2000;

	/**
	 * Meta fields
	 */
	const POST_META_PRO_LITTERIS_API_PIXEL_RESPONSE = "_pro_litteris_response";
	const POST_META_PRO_LITTERIS_PIXEL_URL = "pro-litteris";
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
	const ERROR_CODE_ASSIGN_PIXEL = 'pro-litteris-assigned-pixel';

	/**
	 * Plugin constructor
	 */
	private function __construct() {

		/**
		 * load translations
		 */
		load_plugin_textdomain(
			Plugin::DOMAIN,
			false,
			dirname( plugin_basename( __FILE__ ) ) . '/languages'
		);

		/**
		 * Base paths
		 */
		$this->path = plugin_dir_path( __FILE__ );
		$this->url  = plugin_dir_url( __FILE__ );

		require_once dirname( __FILE__ ) . "/vendor/autoload.php";

		// ----------------------------------------
		// all about data
		// ----------------------------------------
		$this->database   = new Database();
		$this->api        = new API();
		$this->repository = new Repository( $this );

		// ----------------------------------------
		// tasks
		// ----------------------------------------
		$this->schedule = new Schedule( $this );

		// ----------------------------------------
		// user interaction
		// ----------------------------------------
		$this->dashboardWidget = new DashboardWidget( $this );
		$this->metaBox         = new MetaBox( $this );
		$this->post            = new Post( $this );
		$this->postList        = new PostsTable( $this );
		$this->user            = new User( $this );
		$this->pixel           = new TrackingPixel( $this );


		// ----------------------------------------
		// ----------------------------------------

		register_activation_hook( __FILE__, array( $this, "activation" ) );
		register_deactivation_hook( __FILE__, array( $this, "deactivation" ) );

		if ( WP_DEBUG ) {
			$this->database->createTable();
		}
	}

	public function isEnabled() {
		return defined( 'PH_PRO_LITTERIS' ) && true === PH_PRO_LITTERIS;
	}

	public function hasConfig() {
		return defined( 'PH_PRO_LITTERIS_SYSTEM' ) && is_string( PH_PRO_LITTERIS_SYSTEM )
		       && defined( 'PH_PRO_LITTERIS_CREDENTIALS' ) && is_string( PH_PRO_LITTERIS_CREDENTIALS );
	}

	/**
	 * on plugin activation
	 */
	function activation() {
		$this->database->createTable();;
	}

	/**
	 * on plugin deactivation
	 */
	function deactivation() {
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
