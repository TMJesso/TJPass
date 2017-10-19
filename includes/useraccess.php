<?php
class UserAccess extends Common {
	protected static $table_name = 'access_values';
	protected static $db_fields = array('id', 'clearance', 'name');
	
	public $id;
	
	public $clearance;
	
	public $name;
	
	public static function get_all_user_access($clr) {
		$sql  = "SELECT * FROM " . self::$table_name;
		$sql .= " WHERE clearance >= {$clr}";
		$sql .= " ORDER BY clearance";
		return self::find_by_sql($sql);
	}

	public static function get_user_access_by_clearance($clr) {
		$sql  = "SELECT * FROM " . self::$table_name;
		$sql .= " WHERE clearance = '{$clr}'";
		$sql .= " LIMIT 1";
		$row = self::find_by_sql($sql);
		return array_shift($row);
		
	}
	
	private function gen_user_access() {
		global $base;
		$sql  = "INSERT IGNORE INTO " . self::$table_name . " (clearance, name) VALUES ";
		$sql .= "(0, 'Classified'), ";
		$sql .= "(1, 'Top Secret'), ";
		$sql .= "(2, 'Secret'), ";
		$sql .= "(3, 'High-High'), ";
		$sql .= "(4, 'High-Medium'), ";
		$sql .= "(5, 'High-Low'), ";
		$sql .= "(6, 'Medium-High'), ";
		$sql .= "(7, 'Medium-Medium'), ";
		$sql .= "(8, 'Medium-Low'), ";
		$sql .= "(9, 'Low')";
		$base->query($sql);
		
	}
	
	public static function call_gen_user_access() {
		$obj = new self;
		$obj->gen_user_access();
	}
	
	public static function find_all_user_access() {
		$sql  = "SELECT * FROM " . self::$table_name;
		$sql .= " ORDER BY clearance";
		return self::find_by_sql($sql);
	}
}