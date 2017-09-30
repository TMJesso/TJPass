<?php

$errors=array();

function log_data_verbose($input, $desc=""){
	$string_format = var_export($input, true);
	$file = fopen("\mylog.txt", "a");
	fwrite($file, "\n{\n" . var_export(date("h:i:s a m/d/y"), true) . " From: " . $_SERVER["REQUEST_URI"] . "\n");
	fwrite($file, $string_format);
	if (!empty($desc)) {
		fwrite($file, "\n[ " . $desc . " ]\n }\n\n");
	} else {
		fwrite($file, "\n}\n\n");
	}
	fclose($file);
}

function include_layout_template($template="") {
	include(LAYOUT.$template);
}

function __autoload($class_name) {
	$filename = "initialize.php";
	$path = $_SERVER["DOCUMENT_ROOT"] . "/TJPass/includes/" . $filename;
}

/** password must contain both username and passcode to be validated
 * 
 * username concatinated with passcode
 * 
 * example "myusername"."mypasscode"

 * 
 * @param string $password
 * @return string
 */
function password_encrypt($password) {
	$hash = password_hash($password, PASSWORD_DEFAULT); // php7 built in blowfish encryption
	return $hash;
}

/** password must contain both the username and passcode to be 
 * validated correctly
 * 
 * format 'myusername'.'mypasscode'
 * @param string $password (must contain username and passcode)
 * @param string $existing_hash (existing encrypted passcode)
 * @return boolean
 */
function password_check($password, $existing_hash) {
	$hash = password_verify($password, $existing_hash); // php7 builtin password verification
	if ($hash === $existing_hash) {
		return true;
	} else {
		return false;
	}
}


function attempt_login($password, $found_user) {
	if ($found_user) {
		// found user, now check password
		if (password_check($password, $found_user->passcode)) {
			// password matches
			return $found_user;
		} else {
			// password does not match
			return false;
		}
	} else {
		// user not found
		return false;
	}
}

function redirect_to($location = null) {
	if (!is_null($location)) {
		header("Location: {$location}");
		exit;
	}
}

function output_message($message="") {
	if (!empty($message)) {
		return "<br/><div class=\"success callout text-center\"><h4>{$message}</h4></div>";
	} else {
		return "";
	}
}

function output_errors($errors="") {
	if (!empty($errors)) {
		return "<br/><div class=\"alert callout text-center\"><h4>{$errors}</h4></div>";
	} else {
		return "";
	}
}

function get_states() {
	$statenames = array(
			'AL'=>'ALABAMA',
			'AK'=>'ALASKA',
			'AZ'=>'ARIZONA',
			'AR'=>'ARKANSAS',
			'CA'=>'CALIFORNIA',
			'CO'=>'COLORADO',
			'CT'=>'CONNECTICUT',
			'DE'=>'DELAWARE',
			'FL'=>'FLORIDA',
			'GA'=>'GEORGIA',
			'GU'=>'GUAM GU',
			'HI'=>'HAWAII',
			'ID'=>'IDAHO',
			'IL'=>'ILLINOIS',
			'IN'=>'INDIANA',
			'IA'=>'IOWA',
			'KS'=>'KANSAS',
			'KY'=>'KENTUCKY',
			'LA'=>'LOUISIANA',
			'ME'=>'MAINE',
			'MD'=>'MARYLAND',
			'MA'=>'MASSACHUSETTS',
			'MI'=>'MICHIGAN',
			'MN'=>'MINNESOTA',
			'MS'=>'MISSISSIPPI',
			'MO'=>'MISSOURI',
			'MT'=>'MONTANA',
			'NE'=>'NEBRASKA',
			'NV'=>'NEVADA',
			'NH'=>'NEW HAMPSHIRE',
			'NJ'=>'NEW JERSEY',
			'NM'=>'NEW MEXICO',
			'NY'=>'NEW YORK',
			'NC'=>'NORTH CAROLINA',
			'ND'=>'NORTH DAKOTA',
			'OH'=>'OHIO',
			'OK'=>'OKLAHOMA',
			'OR'=>'OREGON',
			'PA'=>'PENNSYLVANIA',
			'RI'=>'RHODE ISLAND',
			'SC'=>'SOUTH CAROLINA',
			'SD'=>'SOUTH DAKOTA',
			'TN'=>'TENNESSEE',
			'TX'=>'TEXAS',
			'UT'=>'UTAH',
			'VT'=>'VERMONT',
			'VA'=>'VIRGINIA',
			'WA'=>'WASHINGTON',
			'WV'=>'WEST VIRGINIA',
			'WI'=>'WISCONSIN',
			'WY'=>'WYOMING',
	);
	return $statenames;
}

function login_failure($found_user, $username, $sheblon) {
	global $session;
	if ($found_user) {
		$activity = "User ID: {$found_user->id} {$found_user->username} a {$session->get_clearance($sheblon)} has passcode failure";
		Activity::user_log($found_user->id, $activity, $found_user->security);
	} else {
		$activity = "Unknown username '{$username}'";
		Activity::user_log(0, $activity, "Unknown");
	}
}

/** returns the date in year-month-day 24h:min:sec format
 * 
 * @return string
 */
function now() {
	return date(now_format());
}

function now_format() {
	//"m-d-Y H:i:s"
	return "Y-m-d H:i:s";
}





