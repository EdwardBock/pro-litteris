<?php


namespace Palasthotel\ProLitteris\Model;


/**
 * @property string raw
 */
class _BaseAPIResponse {
	public function __construct(string $response) {
		$this->raw = $response;
	}
}
