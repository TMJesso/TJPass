<?php
	require_once '../../includes/initialize.php';
	if (!$session->is_logged_in()) { redirect_to("login.php"); }
	
	$breadcrum = "Add Manual Crypt Information";
	$errors = array();
	$user = User::get_user_by_username("TJAdmin");
	if (isset($_POST["btn_submit"])) {
		$num = 10000;
		$workhorse = new Workhorse();
		$workhorse->username = $user->username;
		$workhorse->crypt_name = $base->prevent_injection($_POST["txt_cryp_user"]);
		$workhorse->crypt_security = $base->prevent_injection($_POST["txt_cryp_pass"]);
		$workhorse->descript = $base->prevent_injection($_POST["txt_descript"]);
		$workhorse->link = $base->prevent_injection($_POST["txt_link"]);
		if (empty($workhorse->link)) {
			$workhorse->link = "#";
		}
		$workhorse->link_order = $base->prevent_injection($_POST["txt_link_order"]);
		$workhorse->crypt_id = generate_random_id() . ($num+$workhorse->link_order);
		$workhorse->active = 1;
		if (empty($workhorse->crypt_name) || empty($workhorse->crypt_security) || empty($workhorse->descript) || empty($workhorse->link) || empty($workhorse->link_order)) {
			$errors['workhorse'] = 'There are Empty Fields detected that are required';
		}
		if ($errors) {
			$session->errors($errors);
			redirect_to("add_manual_crypt_info.php");
		} else {
			if ($workhorse->save()) {
				$session->message("Crypt_id: {$workhorse->crypt_id} <strong>Descript: {$workhorse->descript}</strong> successfully created! ");
				redirect_to("add_manual_crypt_info.php");
			} else {
				$errors["workhorse"] = "There was an unexpeced error and the record was not saved!";
				$session->errors($errors);
				redirect_to("add_manual_crypt_info.php");
			}
		}
	} else {
		$link = "";
		$link_order = Workhorse::get_last_link_order($user->username)+1;
	}
	
?>

<?php include_layout_template("admin_header.php"); ?>

	<div class="row">
		<div class="large-3 medium-3 columns">
			&nbsp;
		</div>
		<div class="large-6 medium-6 columns">
			<form data-abide novalidate action="add_manual_crypt_info.php" method="post">
				<div data-abide-error class="alert callout" style="display: none;">
					<p><i class="fi-alert"></i> There are some errors in your form.</p>
				</div>
				<label for="txt_username">Username
					<input type="text" name="txt_username" id="txt_username" value="<?php echo $user->username?>" disabled />
				</label>
				
				<label for="txt_crpt_user">Crypto Username
					<input type="hidden" id="txt_hidden_max_user" value="1" />
					<textarea name="txt_cryp_user" id="txt_cryp_user" placeholder="Enter your Username to store encrypted" onblur="genInfoWorkhorseUser();" required ></textarea>
					<span class="form-error">
						You must enter the username for this information!
					</span>
				</label>
				
				<label for="txt_cryp_pass">Crypto Pass
					<input type="hidden" id="txt_hidden_max_pass" value="1" />
					<textarea name="txt_cryp_pass" id="txt_cryp_pass" placeholder="Enter your Passcode to store encrypted" onblur="genInfoWorkhorsePass();" required></textarea>
					<span class="form-error">
						You must enter the passcode for this information
					</span>
				</label>
				
				<label for="txt_descript">Link Text
					<input type="text" name="txt_descript" id="txt_descript" value="" maxlength="75" placeholder="Describe this entry - maximum 40 characters" required />
					<span class="form-error">
						You must enter the Link Text here!
					</span>
				</label>
				
				<label for="txt_link">Link
					<textarea name="txt_link" id="txt_link" aria-describedby="link_help_text"placeholder="Where should I go when you click on me" required><?php echo $link; ?></textarea>
					<span class="form-error">
						You must enter the Link for this information! eg. http://google.com
					</span>
				</label>
				<p class="help-text" id="link_help_text">If left blank it will default to "#".</p>
				<label for="txt_link_order">Link Order
					<input type="number" name="txt_link_order" id="txt_link_order" min="0" value="<?php echo $link_order?>" placeholder="Where do you want me to place this?" required />
					<span class="form-error">
						You must enter the Link Order for this information!
					</span>
				</label>
				
				<div class="text-center">
					<input type="submit" name="btn_submit" id="btn_submit" class="button" value="Save" />
				</div>
			</form>
		</div>
		<div class="large-3 medium-3 columns">
			&nbsp;
		</div>
	</div>


<?php include_layout_template("admin_footer.php"); ?>