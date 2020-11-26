<?php


namespace Palasthotel\ProLitteris;


use Palasthotel\Grid\WordPress\_Component;

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

	public function poolUpdate(){
		// TODO: check if pool has enough pixels, if not fetch new ones
	}

}