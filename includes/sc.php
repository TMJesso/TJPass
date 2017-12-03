<?php
class Star extends Common {
	protected static $table_name = 'sc';
	protected static $db_fields = array('id', 'sc_id', 'code', 'effect');

	public $id;
	public $sc_id;
	public $code;
	public $effect;
	
	public static function get_sc_by_id($id) {
		$sql  = "SELECT * FROM " . self::$table_name;
		$sql .= " WHERE id = {$id}";
		$row = self::find_by_sql($sql);
		return ($row) ? array_shift($row) : false;
	}
	
	public static function get_sc_by_scid($id) {
		$sql  = "SELECT * FROM " . self::$table_name;
		$sql .= " WHERE sc_id = '{$id}'";
		$row = self::find_by_sql($sql);
		return ($row) ? array_shift($row) : false;
	}
	
	public static function find_all_sc_by_code() {
		$sql  = "SELECT * FROM " . self::$table_name;
		$sql .= " ORDER BY code";
		return self::find_by_sql($sql);
	}
	
	public static function count_sc() {
		global $base;
		$sql  = "SELECT COUNT(*) FROM " . self::$table_name;
		$results = $base->query($sql);
		$row = $base->fetch_array($results);
		return array_shift($row);
	}
}



?>
