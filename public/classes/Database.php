<?php


namespace Palasthotel\ProLitteris;

use Palasthotel\ProLitteris\Model\Pixel;
use wpdb;

/**
 * @property wpdb $wpdb
 * @property string table
 * @property string tableResponses
 */
class Database {

	/**
	 * Database constructor.
	 */
	public function __construct() {
		global $wpdb;
		$this->wpdb = $wpdb;
		$this->table = $wpdb->prefix . "pro_litteris_pixel_pool";
		$this->tableResponses = $wpdb->prefix . "pro_litteris_api_responses";
	}

	/**
	 * @return int
	 */
	public function countAvailablePixels() {
		return intval($this->wpdb->get_var("SELECT count(uid) FROM $this->table WHERE post_id IS NULL"));
	}

	/**
	 *
	 * @param Pixel $pixel
	 *
	 * @return bool|int
	 */
	public function add(Pixel $pixel){
		return $this->wpdb->insert(
			$this->table,
			[
				"uid" => $pixel->uid,
				"uid_domain" => $pixel->domain,
				"post_id" => $pixel->post_id,
			],
			[ "%s"]
		);
	}

	/**
	 *
	 * @param int|string $post_id
	 *
	 * @return Pixel|null
	 */
	public function getPixel( $post_id ){
		$row = $this->wpdb->get_row(
			$this->wpdb->prepare("SELECT uid, uid_domain, post_id from $this->table WHERE post_id = %d", $post_id)
		);
		if(!is_object($row) || !isset($row->uid)) return null;

		return $this->rowToPixel($row);
	}

	/**
	 * @param $post_id
	 *
	 * @return bool|int
	 */
	public function assignPixel($post_id){
		return $this->wpdb->query($this->wpdb->prepare("UPDATE $this->table SET post_id = %d WHERE uid IN (
			SELECT * FROM (SELECT uid FROM $this->table WHERE post_id IS NULL LIMIT 1) as tmp
		)", $post_id));
	}

	/**
	 * @param mixed $row
	 *
	 * @return Model\Pixel
	 */
	private function rowToPixel($row){
		return Model\Pixel::build($row->uid_domain, $row->uid, isset($row->post_id) ? $row->post_id:null);
	}

	/**
	 * @param string $response
	 *
	 * @param string $message
	 *
	 * @return bool|int
	 */
	public function addAPIResponse(string $response, string $message = ""){
		return $this->wpdb->insert(
			$this->tableResponses,
			[
				"response" => $response,
				"message" => $message,
				"requested" => time(),
			]
		);
	}

	/**
	 * create tables if they do not exist
	 */
	function createTable() {
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

		\dbDelta( "CREATE TABLE IF NOT EXISTS $this->table
			(
			uid varchar(100) not null,
			uid_domain varchar(100) not null,
			post_id bigint(20) default null,
			primary key (uid),
			key (uid_domain),
			key (post_id),
			unique key post_pixel ( uid, post_id)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;" );

		\dbDelta( "CREATE TABLE IF NOT EXISTS $this->tableResponses
			(
			id bigint(20) unsigned auto_increment,
			response TEXT NOT NULL,
			requested bigint(20) NOT NULL,
			message TEXT NOT NULL,
			primary key (id),
			key (requested)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;" );
	}
}
