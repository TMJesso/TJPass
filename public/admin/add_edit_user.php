<?php
require_once '../../includes/initialize.php';
if (!$session->is_logged_in()) { redirect_to("login.php"); }
$breadcrum = "Add / Edit User";
$users = User::get_all_users(0, 0);
if (!$users) {
	UserValues::call_gen_user_values();
	UserAccess::call_gen_user_access();
	User::gen_admin();
	$users = User::get_all_users(0, 0);
}
$load_getuser = true;
if (isset($_POST["submit_user"])) {
	$load_getuser = false;
	$user_id = $_POST["select_user"];
	if ($user_id == "add") {
		$username = "";
		$terminate = 0;
		$fname = "";
		$lname = "";
		$phone = "";
		$email = "";
		$address = "";
		$city = "";
		$state = "";
		$zip = "";
		$security = 9;
		$clearance = 9;
		$securities = UserValues::get_all_user_values($security);
		$clearances = UserAccess::get_all_user_access($clearance);
	} else {
		$user = User::get_user_by_id((int)$user_id);
		$username = $user->username;
		$terminate = $user->terminate_access;
		$fname = $user->fname;
		$lname = $user->lname;
		$phone = $user->phone;
		$email = $user->email;
		$address = $user->address;
		$city = $user->city;
		$state = $user->state;
		$zip = $user->zip;
		$security = $user->security;
		$clearance = $user->clearance;
		$securities = UserValues::get_all_user_values($user->security);
		$clearances = UserAccess::get_all_user_access($user->clearance);
	}
	
} elseif (isset($_POST["button_add_user"])) {
	if (isset($_GET["uid"])) {
		$user = User::get_user_by_id($base->prevent_injection($_GET["uid"]));
		$user->last_update = now();
	} else {
		$user = new User();
		$user->date_create = now();
	}
	$username = $base->prevent_injection($_POST["txt_username"]);
	$passcode = password_encrypt($username.$base->prevent_injection($_POST["txt_passcode"]));
	$terminate = (isset($_POST["chk_terminate"])) ? (int) $_POST["chk_terminate"] : 0;
	$fname = $base->prevent_injection($_POST["txt_fname"]);
	$lname = $base->prevent_injection($_POST["txt_lname"]);
	$phone = $base->prevent_injection($_POST["txt_phone"]);
	$email = $base->prevent_injection($_POST["txt_email"]);
	$address = (isset($_POST["txt_address"])) ? $base->prevent_injection($_POST["txt_address"]) : "";
	$city = $base->prevent_injection($_POST["txt_city"]);
	$state = $_POST["select_state"];
	$zip = $base->prevent_injection($_POST["txt_zip"]);
	$security = $_POST["select_security"];
	$clearance = $_POST["select_clearance"];
	$user->address = $address;
	$user->city = $city;
	$user->clearance = $clearance;
	$user->email = $email;
	$user->fname = $fname;
	$user->lname = $lname;
	$user->passcode = $passcode;
	$user->phone = $phone;
	$user->security = $security;
	$user->state = $state;
	$user->terminate_access = $terminate;
	$user->username = $username;
	$user->zip = $zip;
	if ($user->save()) {
		$session->message("User " . $user->get_name() . " was successfully saved");
		redirect_to("add_edit_user.php");
	} else {
		$data = array("username"=>$username, "address"=>$address, "city"=>$city, "state"=>$state, "zip"=>$zip, "fname"=>$fname, "lname"=>$lname, "phone"=>$phone, "security"=>$security, "clearance"=>$clearance, "terminate"=>$terminate, "email"=>$email);
		$session->data($data);
		$errors["user"] = "There was an error saving user " . $user->get_name();
		redirect_to("add_edit_user.php?data");
	}
	
} elseif (isset($_GET["data"])) {
	$load_getuser = false;
	$data = $session->data();
	if ($data) {
		$username = $data["username"];
		$terminate = $data["terminate"];
		$fname = $data["fname"];
		$lname = $data["lname"];
		$phone = $data["phone"];
		$email = $data["email"];
		$address = $data["address"];
		$city = $data["city"];
		$state = $data["state"];
		$zip = $data["zip"];
		$security = $data["security"];
		$clearance = $data["clearance"];
	} else {
		$username = "";
		$terminate = 0;
		$fname = "";
		$lname = "";
		$phone = "";
		$email = "";
		$address = "";
		$city = "";
		$state = "";
		$zip = "";
		$security = 9;
		$clearance = 9;
	}
	$securities = UserValues::get_all_user_values($security);
	$clearances = UserAccess::get_all_user_access($clearance);
	
}

?>

<?php include_layout_template("admin_header.php")?>
<?php if ($load_getuser) { ?>
<div class="row">
	<div class="large-3 medium-3 columns">
		&nbsp;
	</div>
	<div class="large-6 medium-6 columns">
		<?php set_required();?>
		<form data-abide novalidate action="add_edit_user.php" method="post">
			<div data-abide-error class="alert callout" style="display: none;">
				<p><i class="fi-alert"></i> There are some errors in your form.</p>
			</div>
			<label for="select_user"><required>Select a user to edit or leave blank to add new user</required>
				<select name="select_user" id="select_user" required>
					<option value="add">Add new User</option>
					<?php foreach ($users as $user) { ?>
						<option value="<?php echo $user->id;?>"><?php echo $user->get_name();?></option>
					<?php } ?>
				</select>
			</label>
			<div class="text-center">
				<input type="submit" class="button" name="submit_user" id="submit_user" value="Submit" />
			</div>
		</form>
	</div>
	<div class="large-3 medium-3 columns">
		&nbsp;
	</div>
</div>
<?php } else { ?>
<div class="row">
	<div class="large-12 medium-12 columns">
		<?php set_required();?>
		<form data-abide novalidate action="add_edit_user.php<?php if ($user_id != "add") { echo "?uid=".$user_id; } ?>" method="post">
			<div data-abide-error class="alert callout" style="display: none;">
				<p><i class="fi-alert"></i> There are some errors in your form.</p>
			</div>
<!-- left side -->
			<div class="large-6 medium-6 columns">
<!-- Username -->
					<label for="txt_username"><required>Username</required>
						<input type="text" name="txt_username" id="txt_username" maxlength="20" placeholder="Maximum of 20 characters" value="<?php echo $username; ?>" required />
						<span class="form-error">
							You must enter your username...
						</span>
					</label>
<!-- Passcode -->
					<label for="txt_passcode"><required>Passcode</required>
						<input type="password" name="txt_passcode" id="txt_passcode" maxlength="22" placeholder="Maximum of 22 characters" required />
						<span class="form-error">
							You must enter your passcode...
						</span>
					</label>
<!-- Terminate -->
					<fieldset class="callout">
						<legend>Check to terminate access for this user</legend>
						<input type="checkbox" name="chk_terminate" id="chk_terminate" value="1" <?php if ($terminate == 1) { ?>checked<?php } ?> />Terminate access
					</fieldset>
<!-- Security & Clerance -->					
					<fieldset class="callout">
						<legend><required>Security Clearance Values</required></legend>
						<label for="select_security"><required>Security Values</required>
							<select name="select_security" id="select_security" required>
								<option value="">Select Security value</option>
								<?php foreach ($securities as $sec) { ?>
									<option value="<?php echo $sec->security;?>" <?php if ($sec->security == $security) { ?>selected <?php } ?>><?php echo $sec->security . ". " . $sec->name; ?></option>
								<?php } ?>								
							</select>
							<span class="form-error">
								You must select a Security value...
							</span>
						</label>
						
						<label for="select_clearance"><required>Clearance Values</required>
							<select name="select_clearance" id="select_clearance" required>
								<option value="">Select Clearance value</option>
								<?php foreach ($clearances as $clr) { ?>
									<option value="<?php echo $clr->clearance; ?>" <?php if ($clr->clearance == $clearance) { ?> selected <?php } ?>><?php echo $clr->clearance . ". " . $clr->name; ?></option>
								<?php }?>
							</select>
							<span class="form-error">
								You must select a Clearance value...
							</span>
						</label>
					</fieldset>
					
					
			</div>
		<!-- right side -->
			<div class="large-6 medium-6 columns">
<!-- Name -->
				<fieldset class="callout">
					<legend><required>Name</required></legend>
					<label for="txt_fname"><required>First Name</required>
						<input type="text" name="txt_fname" id="txt_fname" maxlength="20" placeholder="Maximum of 20 characters" value="<?php echo $fname; ?>" required />
						<span class="form-error">
							You must enter your First Name...
						</span>
					</label>
					<label for="txt_lname"><required>Last Name</required>
						<input type="text" name="txt_lname" id="txt_lname" maxlength="20" placeholder="Maximum of 20 characters" value="<?php echo $lname; ?>" required />
						<span class="form-error">
							You must enter your Last Name...
						</span>
					</label>
				</fieldset>
<!-- Address -->
				<fieldset class="callout">
					<legend><required>Address</required></legend>
					<label for="txt_address">Street or Mailing Address
						<input type="text" name="txt_address" id="txt_address" maxlength="35" placeholder="Maximum of 35 characters" value="<?php echo $address; ?>" />
					</label>
					<label for="txt_city"><required>City</required>
						<input type="text" name="txt_city" id="txt_city" maxlength="25" placeholder="Maximum of 25 characters" value="<?php echo $city; ?>" required />
						<span class="form-error">
							You must enter your City...
						</span>
					</label>
					
					<label for="select_state"><required>State</required>
						<select name="select_state" id="select_state" title="Select the State" required>
							<option value="">Select State</option>
							<?php $states = get_states();
							foreach ($states as $key => $kstate): ?>
								<option value="<?php echo $key; ?>" <?php if ($key==$state) { ?> selected <?php }?>><?php echo $kstate; ?></option>
							<?php endforeach; ?>
						</select>
						<span class="form-error">
							You must select your State...
						</span>
					</label>
					
					<label for="txt_zip"><required>Zip Code</required>
						<input type="text" name="txt_zip" id="txt_zip" value="<?php echo $zip; ?>" maxlength="5" placeholder="Maximum of 5 characters" pattern="number" required />
						<span class="form-error">
							You must enter your Zip Code...
						</span>
					</label>
				</fieldset>
<!-- Contact -->
				<fieldset class="callout">
					<legend><required>Contact</required></legend>
					<label for="txt_phone"><required>Phone</required>
						<input type="text" name="txt_phone" id="txt_phone" maxlength="" placeholder="(999) 999-9999" value="<?php echo $phone; ?>" />
						<span class="form-error">
							You must enter your Phone # (999) 999-9999 ...
						</span>
					</label>
					<label for="txt_email"><required>Email Address</required>
						<input type="email" name="txt_email" id="txt_email" maxlength="" placeholder="sample@sample.com Maximum 40 characters" value="<?php echo $email; ?>" />
						<span class="form-error">
							You must enter your Email address example: sample@sample.com ...
						</span>
					</label>
				</fieldset>
			</div>
			<div class="row">
				<div class="large-12 medium-12 columns text-center">
					<?php set_required();?>
					<input type="submit" name="button_add_user" id="button_add_user" class="button" value="Submit" />
				</div>
			</div>
		</form>
	</div>
</div>
<?php } // end if ?>

<?php include_layout_template("admin_footer.php"); ?>
