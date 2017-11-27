<?php
class Photo extends Common {
	protected static $table_name = 'photos';
	protected static $db_fields = array('id', 'page_id', 'position', 'filename', 'type', 'size', 'caption');
	
	public $id;
	public $page_id;
	public $position;
	public $filename;
	public $type;
	public $size;
	public $caption;
	
	public static function get_photo_by_id($id) {
		$sql  = "SELECT * FROM " . self::$table_name;
		$sql .= " WHERE id = {$id}";
		$row = self::find_by_sql($sql);
		return ($row) ? array_shift($row) : false;
	}
	
	public static function find_all_photos_by_page_id($id) {
		$sql  = "SELECT * FROM " . self::$table_name;
		$sql .= " WHERE page_id = {$id}";
		return self::find_by_sql($sql);
	}
	
	
	
}