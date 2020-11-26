<?php


namespace Palasthotel\ProLitteris\Model;


use Palasthotel\ProLitteris\Plugin;
use WP_Error;

class FetchPixelsResponse extends _BaseAPIResponse {

	/**
	 * @var WP_Error|null
	 */
	var $error = null;

	/**
	 * @var object|null
	 */
	var $data = null;

	/**
	 * @var Pixel[]
	 */
	var $pixels = [];

	public function __construct( string $response ) {
		parent::__construct( $response );

		$this->data = json_decode( $response );

		if ( ! empty( $response->error ) ) {
			$this->error = new WP_Error( Plugin::ERROR_CODE_REQUEST, $response->error->message);
			return;
		}

		if ( empty( $this->data->domain ) || !is_array( $this->data->pixelUids ) || count( $this->data->pixelUids ) <= 0 ) {
			$this->error =  new WP_Error( Plugin::ERROR_CODE_REQUEST, "Unbekannte Antwort: " . $response );
			return;
		}

		$domain = $this->data->domain;
		$this->pixels = array_map(function($uid) use ($domain) {
			return Pixel::build($domain,$uid);
		}, $this->data->pixelUids);
	}
}
