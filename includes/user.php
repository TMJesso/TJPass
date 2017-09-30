<?php
class User extends Common {
	protected static $table_name = 'user';
	protected static $db_fields = array('id', 'username', 'passcode', 'date_creat', 'last_update', 'terminate_access', 'pass_count', 'fname', 'lname', 'phone', 'email', 'address', 'city', 'state', 'zip', 'security', 'clearance');
	
	public $id;
	
	public $username;
	
	public $passcode;
	
	public $date_create = "0000-00-00 00:00:00";
	
	public $last_update = "0000-00-00 00:00:00";
	
	public $terminate_access = false;
	
	public $pass_count = 0;
	
	public $fname;
	
	public $lname;
	
	public $phone;
	
	public $email;
	
	public $address;
	
	public $city;
	
	public $state;
	
	public $zip;
	
	public $security = 9;
	
	public $clearance = 9;
	
	
	private function generate_admin_record() {
		$obj = new self;
		$obj->username = "TJAdmin";
		$passcode = "What is this";
		$obj->passcode = password_encrypt($obj->username . $passcode);
		$obj->date_create = now();
		$obj->last_update = "0000-00-00 00:00:00";
		$obj->terminate_access = 0;
		$obj->pass_count = 0;
		$obj->fname = "Theral";
		$obj->lname = "Jessop";
		$obj->phone = "7654502009";
		$obj->email = "admin@tjpass.com";
		$obj->address = "PO Box 411";
		$obj->city = "Kokomo";
		$obj->state = "IN";
		$obj->zip = "46903";
		$obj->security = 0;
		$obj->clearance = 0;
		$obj->save();
	}
	
	public static function gen_admin() {
		$this->generate_admin_record();
	}
	
	public static function get_user_by_username($username) {
		$sql  = "SELECT * FROM " . self::$table_name;
		$sql .= " WHERE BINARY username = '{$username}'";
		$row = self::find_by_sql($sql);
		return array_shift($row);
	}
	
}