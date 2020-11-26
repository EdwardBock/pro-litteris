<?php


namespace Palasthotel\ProLitteris;

use Palasthotel\ProLitteris\Model\Pixel;
use WP_Error;

/**
 * @property Database database
 * @property API api
 */
class Repository extends _Component {

	public function onCreate() {
		parent::onCreate();
		$this->api = new API();
		$this->database = new Database();
	}

	/**
	 * @param int $desiredPoolSize
	 *
	 * @return int|WP_Error
	 */
	public function refillPixelPool( int $desiredPoolSize ) {
		$size = $this->database->countAvailablePixels();
		$neededPixelsCount = $desiredPoolSize - $size;

		if($neededPixelsCount <= 0) return 0;


		$response = $this->api->fetchPixels($neededPixelsCount);

		if($response instanceof WP_Error){
			$this->database->addAPIResponse(
				"",
				$response->get_error_message()
			);
			return $response;
		}

		if($response->error instanceof WP_Error){
			$this->database->addAPIResponse(
				$response->raw,
				$response->error->get_error_message()
			);
			return $response->error;
		}

		// protocol
		$this->database->addAPIResponse($response->raw);

		$count = 0;
		foreach ($response->pixels as $pixel){
			$result = $this->database->add($pixel);
			if($result){
				$count+=$result;
			}
		}

		return $count;

	}

	/**
	 * assign a pixel from pool to post
	 * @param int|string $post_id
	 *
	 * @return Pixel|WP_Error
	 */
	public function assignPixel($post_id){
		$pixelUrl = $this->getPostPixelUrl($post_id);
		if(!empty($pixelUrl)) return new WP_Error(
			Plugin::ERROR_CODE_ASSIGN_PIXEL,
			"Post $post_id already has the pixel $pixelUrl"
		);

		$this->database->assignPixel($post_id);
		$pixel = $this->database->getPixel($post_id);

		update_post_meta($post_id, Plugin::POST_META_PRO_LITTERIS_PIXEL_URL, $pixel->toUrl());

		if(empty($pixel)) return new WP_Error(
			Plugin::ERROR_CODE_ASSIGN_PIXEL,
			"Could not assign a pixel to post $post_id."
		);

		return $pixel;
	}

	/**
	 * @param int|string $post_id
	 *
	 * @param bool $autoAssign
	 *
	 * @return Pixel|WP_Error|null
	 */
	public function getPostPixel($post_id, $autoAssign = false){
		$pixel = $this->database->getPixel($post_id);
		if(null !== $pixel || !$autoAssign) return $pixel;

		return $this->assignPixel($post_id);
	}

	/**
	 * @param string|int $post_id
	 *
	 * @return string|false
	 */
	public function getPostPixelUrl($post_id){
		return get_post_meta( $post_id, Plugin::POST_META_PRO_LITTERIS_PIXEL_URL, true );
	}

}
