<?php


namespace Palasthotel\ProLitteris;


use WP_Error;

if ( ! defined( 'WP_CLI' ) || ! WP_CLI ) {
	return;
}

class CLI {

	/**
	 * Refill pixel pool
	 *
	 * ## OPTIONS
	 *
	 * [--to=<size>]
	 * : refill to pool size
	 * ---
	 * default: -1
	 * ---
	 *
	 * ## EXAMPLES
	 *
	 *     wp pro-litteris refillPool post --to=10
	 *
	 * @when after_wp_load
	 */
	public function refillPool($args, $assoc_args){
		$size = isset($assoc_args["to"]) ? intval($assoc_args["to"]) : -1;
		$size = $size < 0 ? Options::getPixelPoolSize() : $size;

		$plugin = Plugin::instance();

		if(!$plugin->isEnabled()){
			\WP_CLI::error("ProLitteris is not enabled in config.");
			exit;
		}
		if(!$plugin->hasConfig()){
			\WP_CLI::error("ProLitteris is missing config.");
			exit;
		}

		$result = $plugin->repository->refillPixelPool($size);

		if($result instanceof WP_Error){
			\WP_CLI::error($result);
			exit;
		}

		if($result < 1){
			\WP_CLI::success( "Pixel pool seems to be full already!" );
			exit;
		}

		\WP_CLI::success( "Added $result pixel to pixel pool!" );
	}

	/**
	 * Report contents
	 *
	 * ## EXAMPLES
	 *
	 *     wp pro-litteris reportContents
	 *
	 * @when after_wp_load
	 */
	public function reportContents($args, $assoc_args){

		\WP_CLI::log( "report it" );
		Plugin::instance()->repository->autoMessages();

		\WP_CLI::success( "Reported!" );

	}

}


\WP_CLI::add_command(
	"pro-litteris",
	__NAMESPACE__."\CLI",
	array(
		'shortdesc' => 'ProLitteris commands.',
	)
);
