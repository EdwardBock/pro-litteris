<?php


namespace Palasthotel\ProLitteris;


class Options {

	public static function setPixelPoolSize(int $size){
		if($size < 0) return false;
		return update_option(Plugin::OPTION_PIXEL_POOL_SIZE, $size);
	}

	public static function getPixelPoolSize(){
		return intval(get_option(Plugin::OPTION_PIXEL_POOL_SIZE, 20));
	}
}
