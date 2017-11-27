<?php

function log_data_verbose($input, $desc=""){
	$string_format = var_export($input, true);
	$file = fopen("\mylog.txt", "a");
	fwrite($file, "\n{\n" . var_export(date("h:i:s a m/d/Y"), true) . " From: " . $_SERVER["REQUEST_URI"] . "\n");
	fwrite($file, "( " . $string_format. " )");
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
	if ($hash) {
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
	$sec_val = $session->get_user_security($found_user->security);
	$clr_val = $session->get_user_clearance($found_user->clearance);
	if ($found_user) {
		$activity = "User ID: {$found_user->id} {$found_user->username} a {$sec_val->name} has passcode failure";
		Activity::user_log($found_user->id, $activity, $sec_val->name);
	} else {
		$activity = "Unknown username '{$username}'";
		Activity::user_log(0, $activity, "Unknown");
	}
}

/** returns the date in year-month-day 24h:min:sec format
 * 
 * @return string
 */
function date_now($num=0) {
	if ($num == 0) {
		return date(now_format($num), time());
	} elseif ($num == 1) {
		return strftime(now_format($num), time());
	}
}

function now_format($num=0) {
	//"m-d-Y H:i:s"
	if ($num == 0) {
		return "Y-m-d H:i:s";
	} elseif ($num == 1) {
		return "%Y-%m-%d %H:%M:%S";
	}
}

function set_required() {
	?>
	<h6 class="text-center small">All items in <required>RED</required> are required</h6>
	<?php 
}

function generate_random_id() {
	return chr(mt_rand(65, 90)) . chr(mt_rand(65, 90)) . chr(mt_rand(65, 90)) . "00";
}

function get_user_type($obj, $sec) {
	$val = $obj->get_user_security($sec);
	return $val->name;
}

function navigation() {
	global $session;
	?>
<?php $menus = Menu::find_all_by_security_visible($session->get_security(), $session->get_clearance(), true); // menu table ?>
<?php if (!$menus) { ?>
	<?php return; ?>
<?php } ?>
<div class="row">
	<div class="large-12 medium-12 columns">	
		<div class="top-bar" data-responsive-toggle="main_menu" data-hide-for="medium">
			<button class="menu-icon" type="button" data-toggle></button>
			<div class="title-bar-title text-left">&nbsp;&nbsp;Menu</div>
		</div>
		<div class="top-bar" id="main_menu">
			<div class="top-bar-left">
				<ul class="dropdown menu" data-dropdown-menu>
					<li class="menu-text" style="font-size: .83em;"><?php echo get_user_type($obj = new UserValues(), $session->get_security()); ?></li>
					<?php foreach($menus as $drop_menu) { 
						$submenu = Submenu::find_all_by_menu_id($drop_menu->menu_id, $session->get_security(), $session->get_clearance()); 
						//if ($drop_menu->admin_only == 0 || ($admin)) {
							if ($session->get_security() == 9) { ?>
								<li><a href="<?php echo $drop_menu->url; ?>" class="small button"><?php echo $drop_menu->link_text; ?></a>
								<?php if ($submenu) { ?>
									<ul class="menu vertical">
										<?php foreach ($submenu as $nextmenu) {
											//if ($nextmenu->admin_only==0 || ($admin)) { ?>
												<li><a href="<?php echo $nextmenu->url; ?>"><?php echo $nextmenu->link_text; ?></a></li>
											<?php //} ?>
										<?php } ?>
									</ul>
								<?php } ?>
								</li>
							<?php } else { ?>
								<li><a href="<?php echo $drop_menu->url; ?>" class="small button"><?php echo $drop_menu->link_text; ?></a>
									<?php if ($submenu) { ?>
										<ul class="menu vertical">
											<?php foreach ($submenu as $nextmenu) { ?>
												<?php //if ($nextmenu->admin_only == 0 || ($admin)) { ?>
													<li><a href="<?php echo $nextmenu->url; ?>"><?php echo $nextmenu->link_text; ?></a></li>
												<?php //} ?>
											<?php } ?>
										</ul>
									<?php } ?>
								</li>
							<?php } ?>
						<?php //} ?>
					<?php } ?>
				</ul>
			</div>
		</div>
	</div>
</div>
	<?php 
	
}

function content_navigation($subjects) {
    ?>
    <div class="row">
	<div class="large-1 medium-1 columns">
		&nbsp;
	</div>
	<div class="large-10 medium-10 columns">
		<div class="top-bar" data-responsive-toggle="subject_menu" data-hide-for="medium">
			<button class="menu-icon" type="button" data-toggle></button>
			<div class="title-bar-title text-left">&nbsp;&nbsp;Menu</div>
		</div>
		<div class="top-bar" id="subject_menu">
			<div class="top-bar-left">
				<ul class="dropdown menu" data-dropdown-menu>
					<li class="menu-text" style="font-size: .83em;">Subjects</li>
					<?php foreach ($subjects as $subject) { ?>
						<?php $pages = Page::get_all_pages_by_subject_id($subject->id); ?>
						<li><a href="add_edit_content.php?subject=<?php echo $subject->id;?>" class="editbutton"><?php echo hdent($subject->menu_name); ?></a>
							<?php if ($pages) { ?>
								<ul class="menu vertical">
									<?php foreach ($pages as $page) { ?>
										<li><a href="add_edit_content.php?page=<?php echo $page->id; ?>" class="small editbutton"><?php echo hdent($page->menu_name); ?></a></li>
									<?php } ?>
								</ul>
							<?php } ?>
						</li>
					<?php } ?>
				</ul>
			</div>
		</div>
	</div>
	<div class="large-1 medium-1 columns">
		&nbsp;
	</div>
	
</div>
    
    <?php 
}

function load_you_are_here($yourehere) {
	global $breadcrumbs, $session;
	$link_text = "broken";
	$submenu = Submenu::find_all_for_url($yourehere, $session->get_clearance(), $session->get_security());
	if ($submenu) {
		$sub = $submenu->submenu_id;
		$detail = $submenu->menu_id;
		$link_text = $submenu->link_text;
	} else {
		$sub = null;
		$menu = Menu::find_menu_detail_by_linktext($yourehere, $session->get_clearance(), $session->get_security());
		if ($menu) {
			$detail = $menu->menu_id;
			$link_text = $menu->link_text;
		} else {
			$detail = null;
		}
	}
	$breadcrumbs = admin_breadcrumbs($detail, $sub, $link_text);
	
}

// all regular breadcrumbs are used by this function
function admin_breadcrumbs($detail, $submenu, $breadcrum) {
	global $session;
	$home_menu = Menu::find_all_by_url("index.php", $session->get_clearance(), $session->get_security());
	$detail_menu = new Menu();
	$sub_menu = new Submenu();
	$output  = "<logged>{$session->get_full_name()}</logged> <access>" . get_access_level() . "</access>";
	$output .= "<ul class=\"breadcrumbs\">";
	$output .= "<li ";
	if (is_null($detail) && is_null($submenu)) {
		$output .= "class=\"disabled\">";
		$output .= "{$breadcrum}</li></ul>";
		return $output;
	} elseif (!is_null($detail) && is_null($submenu)) {
		$detail_menu = Menu::find_by_menu_id($detail);
		if ($breadcrum == "Home") {
			$output .= "<li class=\"disabled\">";
			$output .= "{$breadcrum}</li></ul>";
			return $output;
		} else {
			$output .= "><a href=\"{$home_menu->url}\" title=\"{$home_menu->link_text}\">{$home_menu->link_text}</a></li>";
			$output .= "<li class=\"disabled\" title=\"{$detail_menu->link_text}\">{$detail_menu->link_text}</li></ul>";
			return $output;
		}
		
		
	} elseif (!is_null($detail) && !is_null($submenu)) {
		$detail_menu = Menu::find_by_menu_id($detail);
		$sub_menu = Submenu::find_by_submenu_id($submenu);
		$output .= "><a href=\"{$home_menu->url}\" title=\"{$home_menu->link_text}\">{$home_menu->link_text}</a></li>";
		$output .= "<li><a href=\"{$detail_menu->url}\" title=\"{$detail_menu->link_text}\">{$detail_menu->link_text}</a></li>";
		$output .= "<li class=\"disabled\" title=\"{$sub_menu->link_text}\">{$sub_menu->link_text}</li></ul>";
		return $output;
	}
}

/** html enties encode
 *
 *
 * @param string $entities
 * @param int $ent default ENT_QUOTES
 * @return string
 */
function hent($entities, $ent=ENT_QUOTES) {
	return htmlentities($entities, $ent);
}

/** html entities decode
 *
 * @param string $entities
 * @param int $ent default = ENT_QUOTES
 * @return string
 */
function hdent($entities, $ent=ENT_QUOTES) {
	return html_entity_decode($entities, $ent);
}

/** urlencode
 *
 * @param string $code
 * @return string
 */
function ucode($code) {
	return urlencode($code);
}

/** urldecode
 *
 * @param string $code
 * @return string
 */
function udcode($code) {
	return urldecode($code);
}

function get_access_level() {
	global $session;
	$uservalue = UserValues::get_user_value_by_security($session->get_security());
	return $uservalue->name;
}

function get_script_name() {
	return substr($_SERVER['SCRIPT_FILENAME'],strrpos($_SERVER['SCRIPT_FILENAME'],'/')+1,strlen($_SERVER['SCRIPT_FILENAME']));
}

function ask_permission($obj, $who) {
	// delete subject
	// delete pages for subject
	// delete photos for pages if there are any
	switch ($who) {
		case 1: // subject
			$file_name = "delete_subject.php";
			break;
			
		case 2: // pages
			$file_name = "delete_page.php";
			break;
			
		default :
			$file_name = "404.php";
			break;
			
	}
	?>
	<?php include_layout_template('admin_login_header.php')?>
	<div class="row">
	<div class="large-12 medium-12 columns">
	<form data-abide novalidate action="<?php echo $file_name; ?>?sid=<?php echo $obj->id; ?>" method="post">
	<div data-abide-error class="alert callout" style="display: none;">
	<p><i class="fi-alert"></i> There are some errors in your form.</p>
	</div>
	<div class="warning callout text-center">
	<h3><required>Proceed with caution</required></h3>
	Once pages are removed they will be gone <underline>forever</underline><br>
	<required>and cannot be retreived</required>!
	</div>
	<label for="remove_pages"></label>
	<select name="remove_pages" id="remove_pages" required>
	<option value="">Remove all pages for <?php echo $obj->menu_name; ?> </option>
				<option value="1">Yes - I understand - REMOVE ALL PAGES and PHOTOS for <?php echo $obj->menu_name; ?></option>
				<option value="0">No - I want to keep the pages</option>
			</select>
			<span class="form-error">
				You must choose yes or no!
			</span>
			<div class="text-center">
				<input type="submit" name="submit_permission" class="button" value="Submit" />
			</div>
		</form>
	</div>
</div>
<?php include_layout_template("admin_footer.php");
	
}


?>

