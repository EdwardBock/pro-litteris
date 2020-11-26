<?php

namespace Palasthotel\ProLitteris;

use WP_Error;

class API {

	/**
	 * @param int $amount
	 *
	 * @return string[]|WP_Error
	 */
	public function fetch( $amount = 20 ) {

		$response = $this->request( "/rest/api/1/pixel", array( "amount" => $amount ) );

		if ( $response instanceof WP_Error ) {
			return $response;
		}

		if ( empty( $response ) ) {
			return new WP_Error(
				Plugin::ERROR_CODE_REQUEST,
				"Leere Antwort bei der Anfrage an Service Zählpixel.",
				$response
			);
		}

		$response = json_decode( $response );

		if ( ! empty( $response->error ) ) {
			return new WP_Error( Plugin::ERROR_CODE_REQUEST, $response->error->message, $response );
		}

		if ( empty( $response->domain ) || !is_array( $response->pixelUids ) || count( $response->pixelUids ) <= 0 ) {
			return new WP_Error( Plugin::ERROR_CODE_REQUEST, "Unbekannte Antwort: " . $response );
		}

		return $response->pixelUids;
	}

	/**
	 * @param string $path
	 * @param array $body
	 *
	 * @return string|WP_Error
	 */
	private function request( $path, $body ) {

		if ( ! defined( 'PH_PRO_LITTERIS_CREDENTIALS' ) ) {
			return new WP_Error( Plugin::ERROR_CODE_CONFIG, "Missing ProLitteris credentials" );
		}

		if ( ! defined( 'PH_PRO_LITTERIS_SYSTEM' ) ) {
			return new WP_Error( Plugin::ERROR_CODE_CONFIG, "Missing ProLitteris system url" );
		}

		$headers = array(
			"Content-Type"  => "application/json; charset=utf-8",
			"Authorization" => "OWEN " . base64_encode( PH_PRO_LITTERIS_CREDENTIALS )
		);

		$args     = array(
			"headers"   => $headers,
			"body"      => json_encode( $body ),
			"sslverify" => false,
		);
		$response = wp_remote_post(
			PH_PRO_LITTERIS_SYSTEM . $path,
			$args
		);
		if ( $response instanceof WP_Error ) {
			return $response;
		}
		$body = wp_remote_retrieve_body(
			$response
		);

		return $body;
	}

}