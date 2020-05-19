<?php


namespace Palasthotel\ProLitteris;


class Service {


	/**
	 * @param int $post_id
	 *
	 * @return bool|string
	 */
	public static function getUid($post_id){
		$response =  get_post_meta($post_id, Plugin::POST_META_PRO_LITTERIS_API_PIXEL_RESPONSE, true);
		if(is_string($response) && !empty($response)){
			$response = json_encode($response);
			if (
				is_object($response)
				&&
				isset($response->domain) &&
				! empty( $response->domain )
				&& is_array( $response->pixelUids )
				&&
				count( $response->pixelUids ) > 0
			) {
				return $response->pixelUids[0];
			}
		}
		// fallback hack
		$snippet = self::getSnippet($post_id);
		if(is_string($snippet)){
			$parts = explode("/na/", $snippet);
			if(count($parts) == 2) return $parts[1];
		}

		return false;
	}

	/**
	 * @param int $post_id
	 *
	 * @return string|\WP_Error
	 */
	public static function getSnippet($post_id){
		$error = get_post_meta($post_id, Plugin::POST_META_PRO_LITTERIS_ERROR, true);
		if($error instanceof \WP_Error) return $error;

		return get_post_meta( $post_id, Plugin::POST_META_PRO_LITTERIS, true );
	}

	/**
	 * @param int $post_id
	 * @param string $snippet
	 */
	public static function saveSnippet($post_id, $snippet){
		delete_post_meta($post_id, Plugin::POST_META_PRO_LITTERIS_ERROR);
		update_post_meta($post_id, Plugin::POST_META_PRO_LITTERIS, $snippet);
	}

	/**
	 * @param int $post_id
	 * @param \WP_Error $error
	 */
	public static function saveError($post_id, $error){
		update_post_meta($post_id, Plugin::POST_META_PRO_LITTERIS_ERROR, $error);
	}

	/**
	 * @param int $post_id
	 *
	 * @return string|\WP_Error
	 */
	public static function fetchAndSave($post_id){
		$response = self::fetch($post_id);
		if($response instanceof \WP_Error){
			self::saveError($post_id, $response);
		} else {
			self::saveSnippet($post_id, $response);
		}
		return $response;
	}

	/**
	 * @param string $path
	 * @param array $body
	 *
	 * @return string|\WP_Error
	 */
	public static function request($path, $body){
		if( !defined('PH_PRO_LITTERIS_CREDENTIALS') )
			return new \WP_Error(Plugin::ERROR_CODE_CONFIG, "Missing ProLitteris credentials");

		if( !defined('PH_PRO_LITTERIS_SYSTEM') )
			return new \WP_Error(Plugin::ERROR_CODE_CONFIG,"Missing ProLitteris system url");

		$headers = array(
			"Content-Type"  => "application/json; charset=utf-8",
			"Authorization" => "OWEN " . base64_encode( PH_PRO_LITTERIS_CREDENTIALS )
		);

		$args = array(
			"headers" => $headers,
			"body"    => json_encode( $body ),
			"timeout" => 30,
			"sslverify" => false,
		);
		$response = wp_remote_post(
			PH_PRO_LITTERIS_SYSTEM.$path,
			$args
		);
		if($response instanceof \WP_Error){
			return $response;
		}
		$body = wp_remote_retrieve_body(
			$response
		);
		return $body;
	}

	/**
	 * @return string|\WP_Error
	 */
	public static function fetch($post_id){

		$response = self::request("/rest/api/1/pixel", array( "amount" => 1 ));

		if($response instanceof \WP_Error) return $response;

		update_post_meta($post_id, Plugin::POST_META_PRO_LITTERIS_API_PIXEL_RESPONSE, $response);

		if ( ! empty( $response ) ) {

//			delete_post_meta($post_id, '_pro_litteris_response');

			$response = json_decode( $response );

			if(!empty($response->error)){
				return new \WP_Error(Plugin::ERROR_CODE_REQUEST, $response->error->message, $response);
			}

			if ( ! empty( $response->domain ) && is_array( $response->pixelUids ) && count( $response->pixelUids ) > 0 ) {
				$pro_litteris_url = "https://" . $response->domain . "/na/" . $response->pixelUids[0];


				return $pro_litteris_url;
			}

			return new \WP_Error(Plugin::ERROR_CODE_REQUEST, "Unbekannte Antwort: ".$response);

		}

		return new \WP_Error(Plugin::ERROR_CODE_REQUEST, "Leere Antwort bei der Anfrage an Service Zählpixel.", $response);
	}

	/**
	 * @param int $post_id
	 * @param array $message
	 *
	 * @return array|\WP_Error
	 */
	public static function pushMessage( $post_id, $message ) {
		$response = self::request("/rest/api/1/message", $message);
		if($response instanceof \WP_Error) return $response;
		$response = json_decode($response);
		if(isset($response->error) && !empty($response->error)){
			return new \WP_Error($response->error->code, $response->error->message);
		}
		return $response;
	}

	/**
	 * @param string $title
	 * @param string $plainTextBase64Encoded
	 * @param array $participants array with items build with buildParticipant
	 * @param string $pixelUid
	 *
	 * @return array
	 */
	public static function buildMessage($title, $plainTextBase64Encoded, $participants, $pixelUid){
		return array(
			"title" => $title,
			"messageText"=> array(
				"plainText" => $plainTextBase64Encoded,
			),
			"participants" => $participants,
			"pixelUid" => $pixelUid
		);
	}

	/**
	 * @param string $memberId
	 * @param string $internalIdentification
	 * @param string $participation AUTHOR || TRANSLATOR
	 * @param null|string $firstName
	 * @param null|string $surName
	 *
	 * @return array
	 */
	public static function buildParticipant($memberId, $internalIdentification, $participation, $firstName, $surName ){
		$participation = ($participation == "AUTHOR" || $participation == "TRANSLATOR")? $participation: "AUTHOR";

		return array(
			"memberId" => $memberId,
			"internalIdentification" => $internalIdentification,
			"participation" => $participation,
			"firstName" => $firstName,
			"surName" => $surName
		);
	}

	/**
	 * @param array $message
	 *
	 * @return bool
	 */
	public static function isMessageValid($message){

		if(!is_array($message)) return false;

		if(!isset($message["title"]) || empty($message["title"])) return false;

		if(
			!isset($message["messageText"]) || !is_array($message["messageText"])
			||
			!isset($message["messageText"]["plainText"]) || empty($message["messageText"]["plainText"])
			||
			strlen( base64_decode($message["messageText"]["plainText"]) ) < Plugin::PRO_LITTERIS_MIN_CHAR_COUNT
		) return false;

		if(!isset($message["pixelUid"]) || empty($message["pixelUid"])) return false;

		if(!isset($message["participants"]) || !is_array($message["participants"]) || count($message["participants"]) == 0) return false;

		foreach ($message["participants"] as $participant){
			if(!self::isParticipantValid($participant)) return false;
		}

		return true;
	}

	public static function isParticipantValid($participant){

		if(!isset($participant["memberId"]) || empty($participant["memberId"])) return false;
		if(!isset($participant["internalIdentification"]) || empty($participant["internalIdentification"])) return false;
		if(
			!isset($participant["participation"])
		   ||
			($participant["participation"] != "AUTHOR" && $participant["participation"] != "TRANSLATOR")
		) return false;

		if(!isset($participant["firstName"]) || empty($participant["firstName"])) return false;
		if(!isset($participant["surName"]) || empty($participant["surName"])) return false;

		return true;
	}



}