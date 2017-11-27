<?php
class Menu extends Common {
	protected static $table_name = 'menu';
	protected static $db_fields = array('id', 'menu_id', 'url', 'link_text', 'menu_order', 'visible', 'security', 'clearance', 'not_logged_in');
	
	public $id;
	public $menu_id;
	public $url;
	public $link_text;
	public $menu_order;
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
	
	/** 
	 * 
	 * @param int $id
	 * @return Object Menu
	 */
	public static function find_by_id($id) {
		$sql  = "SELECT * FROM " . self::$table_name;
		$sql .= " WHERE id = {$id}";
		$sql .= " LIMIT 1";
		$row = self::find_by_sql($sql);
		return array_shift($row);
	}
	
	public static function find_by_menu_id($menu_id) {
		$sql  = "SELECT * FROM " . self::$table_name;
		$sql .= " WHERE menu_id = '{$menu_id}'";
		$sql .= " AND NOT not_logged_in";
		$row = self::find_by_sql($sql);
		return array_shift($row);
	}
	
	/**
	 * 
	 * @param integer $sec
	 * @return Object Menu
	 */
	public static function find_all_by_security($sec) {
		$sql  = "SELECT * FROM " . self::$table_name;
		$sql .= " WHERE security = {$sec}";
		$sql .= " AND NOT not_logged_in";
		$sql .= " ORDER BY menu_order";
		return self::find_by_sql($sql);
	}
	
	public static function find_all_by_security_for_menus($sec) {
		$sql  = "SELECT * FROM " . self::$table_name;
		$sql .= " WHERE security >= {$sec}";
		$sql .= " AND NOT not_logged_in";
		$sql .= " ORDER BY menu_order";
		return self::find_by_sql($sql);
	}
	
	/**
	 * 
	 * @param integer $sec
	 * @param integer $clr
	 * @param boolean $logic - default true
	 * @return Object Menu
	 */
	public static function find_all_by_security_visible($sec, $clr, $logic=true) {
		$sql  = "SELECT * FROM " . self::$table_name;
		$sql .= " WHERE security >= {$sec}";
		$sql .= " AND clearance >= {$clr}";
		if ($logic) { 
			$sql .= " AND visible";
		}
		$sql .= " AND NOT not_logged_in";
		$sql .= " ORDER BY menu_order";
		return self::find_by_sql($sql);
	}
	
	public static function find_all_by_url($url, $clr, $sec) {
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

	public static function find_all_by_menu_order($order) {
		$sql  = "SELECT * FROM " . self::$table_name;
		$sql .= " WHERE menu_order = {$order}";
		$row = self::find_by_sql($sql);
		return ($row) ? array_shift($row): false;
		
	}
}


?>
