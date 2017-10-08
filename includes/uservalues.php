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
}