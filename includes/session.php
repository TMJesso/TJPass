<?php
class Session {
	
	private $logged_in = false;
	
	private $user_id;
	
	private $name;
	
	public $message;
	
	private $security = 9;

	private $clearance = 9;
	
	public $errors;
	
	/**
	 * Default 30 minutes
	 *
	 * @var integer
	 */
	private $activity_timeout = 3600;  // 1800 is 30 minutes
	
	private $last_activity;
	
	function __construct() {
		session_start();
		$this->check_message();
		$this->check_errors();
		//$this->check_data();
		$this->check_login();
	}
	
	/** check to see if the user is logged in
	 *
	 * @return boolean
	 */
	public function is_logged_in($sheblon=9) {
		if ($sheblon != $this->security && $this->logged_in) {
			if ($this->logged_in) {
				if ($this->check_idleness()) {
					$this->message("You have been logged out!");
					$this->logout();
				} else {
					$this->message("You have been logged out for 30 minutes of inactivity!");
					redirect_to("login.php");
				}
			}
		} else {
			//$this->message("You have been logged out!");
			//$this->logout();
		}
		return $this->logged_in;
	}
	
	/**
	 * Must be in seconds
	 *
	 * Default is 1800 seconds which is 30 minutes
	 *
	 * To calculate time in from minutes to seconds
	 *
	 * Minutes * 60 = seconds
	 *
	 * To calculate time from hours to seconds
	 *
	 * First convert to minutes then to seconds
	 *
	 * hours * 60 = minutes; minutes * 60 = seconds or
	 *
	 * hours * 60 * 60 = seconds
	 *
	 * Example: 1.5 hours * 60 minutes per hour * 60 seconds per minute = 5400 seconds
	 *
	 * @param integer $time
	 */
	public function set_activity_timeout($time) {
		if (is_numeric($time)) {
			$this->activity_timeout = $time;
			return true;
		} else {
			return false;
		}
	}
	
	public function get_user_id() {
		return $this->user_id;
	}
	
	private function check_idleness() {
		if (isset($this->last_activity)) {
			if (time() - $this->last_activity > $this->activity_timeout) {
				return false;
			} else {
				$_SESSION['last_activity'] = time();
			}
		} else {
			$_SESSION['last_activity'] = time();
		}
		return true;
	}
	
	public function get_last_activity() {
		return $this->last_activity;
	}
	
	/** after user has entered valid username and passcode they will be logged in
	 *
	 * $user is the user object that contains all the user information
	 *
	 * $sheblon contains the security value of the user logged in
	 *
	 * see security comment in this class
	 *
	 * @param object $user
	 * @param int $sheblon
	 */
	public function login($user, $sheblon) {
		// database should find user based on username/password
		if ($user) {
			$this->user_id = $_SESSION['user_id'] = (int)$user->id;
			$this->name = $_SESSION['name'] = $this->fullname($user);
			$this->security = $_SESSION['security'] = $sheblon;
			$this->clearance = $_SESSION['clearance'] = $user->clearance;
			$this->last_activity = $_SESSION['last_activity'] = time();
			if(isset($user->master)) {
				$_SESSION['admin_master'] = $user->master;
				$this->admin_master = ($_SESSION['admin_master']  == 1) ? true : false;
			} else {
				$this->admin_master = $_SESSION['admin_master'] = false;
			}
			$activity  = ($this->admin_master) ? "" : "User ID: " . $this->user_id . " ";
			$activity .= ($this->admin_master) ? "" : $this->get_clearance($sheblon) . " ";
			$activity  .= ($this->admin_master) ? "Admin Master Login" : "Non-Master Login";
			Activity::user_log($user->id, $activity, $this->get_clearance($sheblon));
			$this->logged_in = true;
		} else {
			$this->logged_in = false;
		}
	}
	
	/** checks login status
	 *
	 * return true if logged in
	 * false otherwise
	 *
	 * @return boolean
	 */
	function logged_in() {
		return $this->logged_in;
	}
	
	/** when a user logs on they will have a logout button
	 *
	 * this will log them out and set the user variables to their default state
	 *
	 * and will display the appropriate message in the user_log table
	 *
	 *
	 */
	public function logout() {
		if (isset($this->user_id)) {
			$activity = ($this->admin_master) ? "" : "User ID: " . $this->user_id . " ";
			$activity .= ($this->admin_master) ? "Admin Master" : $this->get_clearance($this->security);
			$activity .= " Logged Out";
			Activity::user_log($this->user_id, $activity, $this->get_clearance($this->security));
		}
		unset($_SESSION['user_id']);
		unset($this->user_id);
		unset($this->name);
		unset($this->logged_in);
		unset($this->last_activity);
		unset($_SESSION['last_activity']);
		unset($_SESSION['admin_master']);
		$this->logged_in = false;
	}
	
	