<?php
require_once '../../includes/initialize.php';
$breadcrum = "User Login";
if ($session->is_logged_in()) { redirect_to("index.php"); }

if (isset($_POST["button_submit"])) {
	$username = $base->prevent_injection($_POST["txt_username"]);
	$passcode = $base->prevent_injection($_POST["txt_passcode"]);
	$found_user = User::get_user_by_username($username);
	if ($found_user) {
		$password = trim($username) . trim($passcode);
		$authorized = attempt_login($password, $found_user);
		if ($authorized) {
			$session->login($authorized);
			redirect_to("index.php");
		} else {
			login_failure($found_user, $username, $found_user->security);
			$errors["login"] = "Username and/or password combination incorrect... please try again!";
			$session->errors($errors);
			redirect_to("login.php");
		}
	}
} else {
		if (User::gen_admin()) { 
			redirect_to("login.php");
		}
	}

?>

<?php include_layout_template('admin_header.php'); ?>
<div class="row">
	<div class="large-3 medium-3 columns">
		&nbsp;
	</div>
	<div class="large-6 medium-6 columns">
		<form data-abide novalidate action="login.php" method="post">
			<div class="row">
				<div class="columns">
					<div data-abide-error class="alert callout" style="display: none;">
						<p><i class="fi-alert"></i> There are some errors in your form.</p>
					</div>
				</div>
			</div>
			<label for="txt_username">Username <required>*</required>
				<input type="text" name="txt_username" id="txt_username" value="" placeholder="Appleseed - Username is case sensitive" required >
				<span class="form-error">
					You must enter a Username...
				</span>
			</label>
			<label for="txt_passcode">Passcode <required>*</required>
				<input type="password" name="txt_passcode" id="txt_passcode" value="" placeholder="Number, upper, lower and must be at least 8 characters" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" required aria-describedby="desc_passcode" >
				<span class="form-error">
					Must contain at least one number, one uppercase letter, one lowercase letter, and be at least 8 characters e.g. Ab1cdefg!
				</span>
			</label>
			<p class="help-text text-center" id="desc_passcode">At least one uppercase, lowercase, one number, and at least 8 characters long!</p>
			
			<?php set_required(); ?>
			<div class="text-center">
				<input type="submit" name="button_submit" id="button_submit" value="Submit" class="button">
			</div>
			
		</form>
	</div>
	<div class="large-3 medium-3 columns">
		&nbsp;
	</div>
</div>






<?php include_layout_template('admin_footer.php'); ?>



