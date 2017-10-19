<?php

class UserValues extends Common {
	protected static $table_name = 'user_values';
	protected static $db_fields = array('id', 'security', 'name');
	
	public $id;
	
	public $security;
	
	public $name;
	
	public static function get_all_user_values($sec) {
		$sql  = "SELECT * FROM " . self::$table_name;
		$sql .= " WHERE security >= {$sec}";
		$sql .= " ORDER BY security";
		return self::find_by_sql($sql);
	}
	
	public static function get_user_value_by_security($sec) {
		$sql  = "SELECT * FROM " . self::$table_name;
		$sql .= " WHERE security = {$sec}";
		$sql .= " LIMIT 1";
		$row = self::find_by_sql($sql);
		return array_shift($row);
	}
	
	public function get_user_security($sec) {
		$sql  = "SELECT * FROM " . self::$table_name;
		$sql .= " WHERE security = {$sec}";
		$sql .= " LIMIT 1";
		$row = self::find_by_sql($sql);
		return array_shift($row);
	}
	
	private function gen_user_values() {
		global $base;
		$sql  = "INSERT IGNORE INTO " . self::$table_name . " (security, name) VALUES ";
		$sql .= "(0, 'Admin'), ";
		$sql .= "(1, 'President'), ";
		$sql .= "(2, 'Human Resources'), ";
		$sql .= "(3, 'Department'), ";
		$sql .= "(4, 'Supervisor'), ";
		$sql .= "(5, 'Foreman'), ";
		$sql .= "(6, 'Leader'), ";
		$sql .= "(7, 'Operator'), ";
		$sql .= "(8, 'Registered User'), ";
		$sql .= "(9, 'Public')";
		$base->query($sql);
		
	}
	
	public static function call_gen_user_values() {
		$obj = new self;
		$obj->gen_user_values();
	}
	
	public static function find_all_user_values() {
		$sql  = "SELECT * FROM " . self::$table_name;
		$sql .= " ORDER BY security";
		return self::find_by_sql($sql);
	}
}