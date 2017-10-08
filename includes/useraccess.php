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
}