<?php
require_once LIB_PATH . 'initialize.php';
// 
class Session {
	
	private $logged_in = false;
	
	private $user_id;
	
	private $name;
	
	private $security = 9;

	private $clearance = 9;
	
	public $data;
	
	public $message;
	
	public $err;
	
	/**
	 * Default 30 minutes
	 *
	 * @var integer
	 */
	private $activity_timeout = 1800;  // 1800 is 30 minutes has been increased to 1 hour
	
	private $last_activity;
	
	function __construct() {
		session_start();
		$this->check_message();
		$this->check_errors();
		$this->check_data();
		$this->check_login();
		//$this->check_last_activity();
		//$this->check_login();
	}
	
	/** check to see if the user is logged in
	 *
	 * @return boolean
	 */
	public function is_logged_in() {
// 		log_data_verbose($_SESSION, "Session Variable");
// 		log_data_verbose($this->logged_in, "This Logged In");
// 		// log_data_verbose(session_id(), "Session ID");
//		log_data_verbose(session_name() . "=\"".session_id() . "\"", "Session Name & ID");
		if ($this->logged_in) {
			if (!$this->check_idleness()) {
				$this->message("You have been logged out for 30 minutes of inactivity!");
				$this->logout();
				redirect_to("login.php");
			}
		}
		return $this->logged_in();
	}
	
	/**
	 * Must be in seconds
	 *
	 * Default is 1800 seconds which is 30 minutes
	 *
	 * To calculate time-in from minutes to seconds
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
			if ((time() - $this->last_activity) > $this->activity_timeout) {
				return false;
			} else {
				$this->last_activity = $_SESSION['last_activity'] = time();
			}
		} else {
			$this->last_activity = $_SESSION['last_activity'] = time();
		}
		return true;
	}
	
	public function get_security() {
		return $this->security;
	}
	
	public function get_clearance() {
		return $this->clearance;
	}
	
	public function get_last_activity() {
		return $this->last_activity;
	}
	
	public function get_full_name() {
		return $this->name;
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
	public function login($user) {
		// database should find user based on username/password
		if ($user) {
			$this->user_id = $_SESSION['user_id'] = $user->username;
			$this->name = $_SESSION['name'] = $this->fullname($user);
			$this->security = $_SESSION['security'] = $user->security;
			$this->clearance = $_SESSION['clearance'] = $user->clearance;
			$this->last_activity = $_SESSION['last_activity'] = time();
			$ses_clr = $this->get_user_clearance($user->clearance);
			$ses_sec = $this->get_user_security($user->security);
			$activity  = "User-ID: " . $this->user_id . " ";
			$activity .= "Security: " . $ses_sec->name . " ";
			$activity .= "Clearance: " . $ses_clr->name . " ";
			$activity  .= "{$ses_sec->name} Login (time): (" . time() . ")";
			Activity::user_log($user->id, $activity, $ses_sec->name);
			$this->logged_in = $_SESSION["logged_in"] = true;
			
		} else {
			$this->logged_in = false;
		}
	}
	
	public function fullname($obj) {
		return $obj->get_name(); //"{$obj->fname} {$obj->lname}";
	}
	
	public function get_user_security($sec) {
		$security = UserValues::get_user_value_by_security($sec);
		return $security;
	}
	
	public function get_user_clearance($clr) {
		$clearance = UserAccess::get_user_access_by_clearance($clr);
		return $clearance;
	}
	
	/** checks login status
	 *
	 * return true if logged in
	 * false otherwise
	 *
	 * @return boolean
	 */
	public function logged_in() {
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
			$ses_sec = $this->get_user_security($this->security);
			$ses_clr = $this->get_user_clearance($this->clearance);
			$activity  = "User ID: " . $this->user_id . " ";
			$activity .= "Security: {$ses_sec->name} ";
			$activity .= "Clearance: {$ses_clr->name} ";
			$activity .= " Logged Out";
			Activity::user_log($this->user_id, $activity, $ses_sec->name);
		}
		unset($_SESSION['user_id']);
		unset($_SESSION["name"]);
		unset($_SESSION["security"]);
		unset($_SESSION["clearance"]);
		unset($_SESSION["logged_in"]);
		unset($_SESSION['last_activity']);
		unset($this->user_id);
		unset($this->name);
		unset($this->security);
		unset($this->clearance);
		unset($this->logged_in);
		unset($this->last_activity);
	}
	
	/** message is used to display any success messages or messages needing to
	 *
	 * be displayed to the user when interacting with this application. The message
	 *
	 * uses the session variable
	 *
	 * $msg is the message to be displayed to the user
	 *
	 * and this will also clear the message from the session
	 *
	 * @param string $msg
	 * @return string
	 */
	public function message($msg="") {
		if (!empty($msg)) {
			// then this is "set message"
			// make sure you understand why $this->message=$msg wouldn't work
			$_SESSION["message"] = $msg;
		} else {
			// then this is "get message"
			return $this->message;
		}
	}
	
	public function errors($error=array()) {
		if (!empty($error)) {
			// then this is "set error"
			$_SESSION["errors"] = $error;
		} else {
			// then this is "get error"
			return $this->err;
		}
	}
	
	public function data($data=array()) {
		if (!empty($data)) {
			// then this is "set data"
			$_SESSION["data"] = $data;
		} else {
			// then this is "get data"
			return $this->data;
		}
	}
	
	private function check_last_activity() {
		// is there last_activity integer stored in the session?
		if (isset($_SESSION['last_activity'])) {
			// add it as an attribute and erase the stored version
			$this->last_activity = $_SESSION['last_activity'];
			unset($_SESSION['last_activity']);
		} else {
			$this->last_activity = 0;
		}
	}
	
	private function check_message() {
		// is there a message stored in the session?
		if (isset($_SESSION['message'])) {
			// add it as an attribute and erase the stored version
			$this->message = htmlentities($_SESSION['message']);
			unset($_SESSION['message']);
		} else {
			$this->message = "";
		}
	}
	
	private function check_data() {
		// is there data stored in the session?
		if (isset($_SESSION['data'])) {
			// add it as an attribute and erase the stored version
			$this->data = $_SESSION['data'];
			unset($_SESSION['data']);
		} else {
			$this->data = "";
		}
	}
	
	private function check_errors() {
		// is there an error stored in the session?
		if (isset($_SESSION["errors"])) {
			// add it as an attribute and erase the stored version
			$this->err = $this->form_errors($_SESSION["errors"]);
			unset($_SESSION["errors"]);
		} else {
			$this->err = "";
		}
	}
	
	private function form_errors($errors=array()) {
		$output = "";
		if (!empty($errors)) {
			$output .= "<div class=\"alert callout text-center\">";
			//if ()
			$output .= "Possible errors or notices:";
			$output .= "<ul>";
			foreach ($errors as $key => $err) {
				$output .= "<li>{$err}</li>";
			}
			$output .= "</ul>";
			$output .= "</div>";
		}
		return $output;
	}
	
	private function check_login() {
		if (isset($_SESSION["logged_in"])) {
			if ($_SESSION["logged_in"]) {
				$this->logged_in = $_SESSION["logged_in"];
			}
		}
		if (isset($_SESSION["user_id"])) {
			$this->user_id = $_SESSION["user_id"];
		}
		if (isset($_SESSION["name"])) {
			$this->name = $_SESSION["name"];
		}
		if (isset($_SESSION["security"])) {
			$this->security = $_SESSION["security"];
		}
		if (isset($_SESSION["clearance"])) {
			$this->clearance = $_SESSION["clearance"];
		}
		if (isset($_SESSION["last_activity"])) {
			$this->last_activity = $_SESSION["last_activity"];
		}
	}
	
}

$session = new Session();
$message = $session->message();
$errors = $session->errors();
$data = $session->data();


	