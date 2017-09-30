<?php
require_once(LIB_PATH.DS.'database.php');
class Activity extends Common {
	protected static $table_name = "user_log";
	protected static $db_fields = array('id', 'user_id', 'user_type', 'date_stamp', 'activity');
	
	public $id;
	public $user_id;
	public $user_type;
	public $date_stamp;
	public $activity;

	public static function find_by_user_id($id) {
		$sql  = "SELECT * FROM " . self::$table_name;
		$sql .= " WHERE user_id = {$id}";
	}
	
	public static function user_log($id, $activity, $user_type) {
		$obj = new self;
		$obj->user_id = $id;
		$obj->activity = $activity;
		$obj->user_type = $user_type;
		$obj->date_stamp = strftime("%Y-%m-%d %H:%M:%S", strtotime(now(), time()));
		$obj->save();
	}
	
	private function now() {
		return date($this->now_format());
	}
	
	private function now_format() {
		//     "m-d-Y H:i:s"
		return "m-d-Y H:i:s";
	}
	
}
	
	
	