<?php
class Submenu extends Common {
	protected static $table_name = "submenu";
	protected static $db_fields = array('id', 'submenu_id', 'menu_id', 'url', 'link_text', 'position', 'visible', 'security', 'clearance', 'not_logged_in');
	
	public $id;
	public $submenu_id;
	public $menu_id;
	public $url;
	public $link_text;
	public $position;
	public $visible;
	public $security;
	public $clearance;
	
	/** this will allow a menu to be displayed 
	 * 
	 * when not logged in under special circumstances
	 * 
	 * @var boolean default 0
	 */
	public $not_logged_in;
	
	public static function find_all_for_url($urlf, $clr, $sec) {
		$sql  = "SELECT * FROM " . self::$table_name;
		$sql .= " WHERE url = '{$url}'";
		$sql .= " AND security = {$sec}";
		$sql .= " AND clearance = {$clr}";
		$sql .= " AND visible";
		$sql .= " AND NOT not_logged_in";
		$sql .= " LIMIT 1";
		$row = self::find_by_sql($sql);
		return ($row) ? array_shift($row) : false;
	}
	
	public static function find_all_by_menu_id($id, $sec, $clr, $logic=true) {
		$sql  = "SELECT * FROM " . self::$table_name;
		$sql .= " WHERE menu_id = '{$id}'";
		$sql .= " AND security >= {$sec}";
		$sql .= " AND clearance >= {$clr}";
		if ($logic) {
			$sql .= " AND visible";
		}
		$sql .= " AND NOT not_logged_in";
		$sql .= " ORDER BY position";
		return self::find_by_sql($sql);
	}
	
	public static function find_all_by_menu_id_position($id, $position) {
		$sql  = "SELECT * FROM " . self::$table_name;
		$sql .= " WHERE menu_id = '{$id}'";
		$sql .= " AND position = {$position}";
		$row = self::find_by_sql($sql);
		return ($row) ? array_shift($row): false;
	}
	
	public static function find_by_submenu_id($submenu_id) {
		$sql  = "SELECT * FROM " . self::$table_name;
		$sql .= " WHERE submenu_id = '{$submenu_id}'";
		$sql .= " LIMI 1";
		$row = self::find_by_sql($sql);
		return array_shift($row);
	}

	public static function find_all_by_id_for_menu($id) {
		$sql  = "SELECT * FROM " . self::$table_name;
		$sql .= " WHERE menu_id = ";
		$sql .= " (SELECT menu_id FROM menu";
		$sql .= " WHERE id = {$id})";
		$sql .= " ORDER BY position";
		return self::find_by_sql($sql);
	}
	
	public static function find_by_id($id) {
		$sql  = "SELECT * FROM " . self::$table_name;
		$sql .= " WHERE id = {$id}";
		$sql .= " LIMIT 1";
		$row = self::find_by_sql($sql);
		return array_shift($row);
	}
}


?>
