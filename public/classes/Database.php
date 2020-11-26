<?php


namespace Palasthotel\ProLitteris;

use wpdb;

/**
 * @property wpdb $wpdb
 * @property string table
 */
class Database {

	/**
	 * Database constructor.
	 */
	public function __construct() {
		global $wpdb;
		$this->wpdb = $wpdb;
		$this->table = $wpdb->prefix . "pro_litteris_pixel_pool";
	}

	/**
	 * @param $pixel
	 *
	 * @return bool|int
	 */
	public function addToPool($pixel){
		return $this->wpdb->insert(
			$this->table,
			[
				"pixel" => $pixel,
			],
			[ "%s"]
		);
	}

	/**
	 *
	 * @return string|null
	 */
	public function getPixel($post_id){
		return $this->wpdb->get_var(
			$this->wpdb->prepare("SELECT pixel from $this->table WHERE post_id = %d", $post_id)
		);
	}

	/**
	 * @param $post_id
	 *
	 * @return bool|int
	 */
	public function assignPixel($post_id){
		$result = $this->wpdb->query($this->wpdb->prepare("UPDATE $this->table SET post_id = %d WHERE pixel IN (
			SELECT pixel FROM $this->table WHERE post_id IS NULL LIMIT 1
		)", $post_id));
		return $result;
	}

	/**
	 * create tables if they do not exist
	 */
	function createTable() {
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		\dbDelta( "CREATE TABLE IF NOT EXISTS $this->table
			(
			pixel varchar(100) not null,
			post_id bigint(20) default null,
			primary key (pixel),
			key (post_id),
			unique key post_pixel ( pixel, post_id)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;" );
	}



}