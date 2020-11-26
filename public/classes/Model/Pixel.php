<?php


namespace Palasthotel\ProLitteris\Model;


/**
 * @property string domain
 * @property string uid
 * @property int|null post_id
 */
class Pixel {

	/**
	 * Pixel constructor.
	 *
	 * @param string $domain
	 * @param string $uid
	 * @param int|string|null $post_id
	 */
	private function __construct(string $domain, string $uid, $post_id) {
		$this->domain = $domain;
		$this->uid = $uid;
		$this->post_id = $post_id;
	}

	public static function build(string $domain, string $uid, $post_id = null){
		return new self($domain, $uid, $post_id);
	}

	public function toUrl(){
		return "https://".$this->domain."/na/".$this->uid;
	}
}
