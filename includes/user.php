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
	
	/**
	 * 0 - Tier 0 (Sun)
	 *
	 * 1 - Tier 1 (Stellar)
	 *
	 * 2 - Tier 2 (Orbital)
	 *
	 * 3 - Tier 3 (Heavens)
	 *
	 * 4 - Tier 4 (Atmosphereic)
	 *
	 * 5 - Tier 5 (Stratis)
	 *
	 * 6 - Tier 6 (Accumulas)
	 *
	 * 7 - Tier 7 (National)
	 *
	 * 8 - Tier 8 (Regional)
	 *
	 * 9 - Tier 9 (Station) - (LOWEST SECURITY CLEARANCE)
	 *
	 * @var integer size 1
	 * @default 9
	 */
	public $security = 9;

	/**
	 * used to set clearance level access to menus and
	 *
	 * certain functionality within the system
	 *
	 * 0 - Owner
	 *
	 * 1 - Board
	 *
	 * 2 - President
	 *
	 * 3 - Finanace
	 *
	 * 4 - Marketing
	 *
	 * 5 - Human Resources
	 *
	 * 6 - Accounting
	 *
	 * 7 - IT
	 *
	 * 8 - Department
	 *
	 * 9 - Individual
	 *
	 * @var integer size 1
	 * @default 9
	 */
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
	
	public static function get_all_users($sec=9, $clr=9) {
		$sql  = "SELECT * FROM " . self::$table_name;
		$sql .= " WHERE security >= {$sec}";
		$sql .= " AND clearance >= {$clr}";
		return self::find_by_sql($sql);
	}
	
	public function get_name() {
		return $this->fname . " " . $this->lname;
	}
	
	public function get_reverse_name() {
		return $this->lname . ", " . $this->fname;
	}
}