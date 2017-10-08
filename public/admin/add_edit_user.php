<?php
require_once '../../includes/initialize.php';
$breadcrum = "Add / Edit User";
$users = User::get_all_users(0, 0);




?>

<?php include_layout_template("admin_header.php")?>

<div class="row">
	<div class="large-3 medium-3 columns">
		&nbsp;
	</div>
	<div class="large-6 medium-6 columns">
		<form data-abide novalidate action="add_edit_user.php" method="post">
			<div data-abide-error class="alert callout" style="display: none;">
				<p><i class="fi-alert"></i> There are some errors in your form.</p>
			</div>
			<label for="select_user">Select a user to edit or leave blank to add new user
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

<div class="row">
	<div class="large-12 medium-12 columns">
		<form data-abide novalidate action="add_edit_user.php" method="post">
			<div data-abide-error class="alert callout" style="display: none;">
				<p><i class="fi-alert"></i> There are some errors in your form.</p>
			</div>
<!-- left side -->
			<div class="large-6 medium-6 columns">
<!-- Username -->
					<label for="txt_username">Username
						<input type="text" name="txt_username" id="txt_username" maxlength="20" placeholder="Maximum of 20 characters" required />
						<span class="form-error">
							You must enter your username...
						</span>
					</label>
<!-- Passcode -->
					<label for="txt_passcode">Passcode
						<input type="text" name="txt_passcode" id="txt_passcode" maxlength="22" placeholder="Maximum of 22 characters" required />
						<span class="form-error">
							You must enter your passcode...
						</span>
					</label>
<!-- Terminate -->
					<fieldset class="callout">
						<legend>Check to terminate access for this user</legend>
						<input type="checkbox" name="chk_terminate" id="chk_terminate" value="1" />Terminate access
					</fieldset>
					
					
			</div>
		<!-- right side -->
			<div class="large-6 medium-6 columns">
<!-- Name -->
				<fieldset class="callout">
					<legend>Name</legend>
					<label for="txt_fname">First Name
						<input type="text" name="txt_fname" id="txt_fname" maxlength="20" placeholder="Maximum of 20 characters" required />
					</label>
					<label for="txt_lname">Last Name
						<input type="text" name="txt_lname" id="txt_lname" maxlength="20" placeholder="Maximum of 20 characters" required />
					</label>
				</fieldset>
				
			</div>
			<div class="row text-center">
				<input type="submit" name="button_add_user" id="button_add_user" class="button" value="Submit" />
			</div>
		</form>
	</div>
</div>

<?php include_layout_template("admin_footer.php"); ?>
