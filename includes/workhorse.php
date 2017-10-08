<?php
class Workhorse extends Common {
	protected static $table_name = "crypt_values";
	protected static $db_fields = array('id', 'username', 'crypt_id', 'crypt_name', 'crypt_security', 'descript', 'link', 'link_order', 'active');
	
	public $id;
	public $username;
	public $crypt_id;
	public $crypt_name;
	public $crypt_security;
	public $descript;
	public $link;
	public $link_order;
	public $active = 1;
	
	// this method was designed only for add_manual_crypt_info.php
	// it should never be used anywhere else
	public static function get_last_link_order($user="") {
		global $base;
		$sql  = "SELECT max(link_order) FROM " . self::$table_name;
		$sql .= " WHERE BINARY username = '{$user}'";
		$results = $base->query($sql);
		$row = $base->fetch_array($results);
		return array_shift($row);
	}
	
	
	
}