<?php
class User extends Common {
	protected static $table_name = 'user';
	protected static $db_fields = array('id', 'username', 'passcode', 'date_create', 'last_update', 'terminate_access', 'pass_count', 'fname', 'lname', 'phone', 'email', 'address', 'city', 'state', 'zip', 'security', 'clearance');
	
	public $id;
	
	public $username;
	
	public $passcode;
	
	public $date_create;
	
	public $last_update;
	
	public $terminate_access;
	
	public $pass_count;
	
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
		global $base, $session;
		$sql  = "SELECT COUNT(*) FROM " . self::$table_name;
		$result = $base->query($sql);
		$row = $base->fetch_array($result);
		$num = array_shift($row);
		if ($num == 0) {
			$obj = new self;
			$obj->username = "TJAdmin";
			$passcode = "3C2015-iuk";
			$password = $obj->username . $passcode;
			$obj->passcode = password_encrypt($password);
			log_data_verbose(date_now(1), "Date Now function(1)");
			$obj->date_create = date_now(1);
			$obj->last_update = "0000-00-00 00:00:00";
			$obj->terminate_access = 0;
			$obj->pass_count = 0;
			$obj->fname = "Theral";
			$obj->lname = "Jessop";
			$obj->phone = "7654502009";
			$obj->email = "admin@theraljessop.net";
			$obj->address = "PO Box 411";
			$obj->city = "Kokomo";
			$obj->state = "IN";
			$obj->zip = "46903";
			$obj->security = 0;
			$obj->clearance = 0;
			if ($obj->save()) {
				$session->message("Admin user {$obj->username} successfully created");
				return true;
			} else {
				$errors["Admin"] = "Admin user {$obj->username} was NOT created due to an unforseen error";
				$session->errors($errors);
				return false;
			}
		}
	}
	
	public static function gen_admin() {
		$obj = new self;
		$obj->generate_admin_record();
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
	
	public static function get_user_by_id($id) {
		$sql  = "SELECT * FROM " . self::$table_name;
		$sql .= " WHERE id = {$id}";
		$sql .= " LIMIT 1";
		$row = self::find_by_sql($sql);
		return array_shift($row);
	}

}