<?php
	require_once '../../includes/initialize.php';
	$breadcrum = "Add Manual Crypto Information";
	
	$user = User::get_user_by_username("TJAdmin");
	if (isset($_POST["btn_submit"])) {
		$num = 10000;
		$workhorse = new Workhorse();
		$workhorse->username = $user->username;
		$workhorse->crypt_name = $base->prevent_injection($_POST["txt_cryp_user"]);
		$workhorse->crypt_security = $base->prevent_injection($_POST["txt_cryp_pass"]);
		$workhorse->descript = $base->prevent_injection($_POST["txt_descript"]);
		$workhorse->link = $base->prevent_injection($_POST["txt_link"]);
		$workhorse->link_order = $base->prevent_injection($_POST["txt_link_order"]);
		$workhorse->crypt_id = chr(mt_rand(65, 90)) . chr(mt_rand(65, 90)) . chr(mt_rand(65, 90)) . "00" . ($num+$workhorse->link_order);
		$workhorse->active = 1;
		log_data_verbose($workhorse, "Adding record using workhorse");
		if ($workhorse->save()) {
			$session->message("Crypt_id: {$workhorse->crypt_id} <strong>Descript: {$workhorse->descript}</strong> successfully created! ");
			redirect_to("add_manual_crypt_info.php");
		} else {
			$errors["workhorse"] = "There was an unexpeced error and the record was not saved!";
			$session->errors($errors);
			redirect_to("add_manual_crypt_info.php");
		}
	} else {
		$link = "";
		$link_order = Workhorse::get_last_link_order()+1;
	}
	
?>

<?php include_layout_template("admin_header.php"); ?>
<div class="grid-x grid-padding-x">
	<div class="large-12 medium-12 cell">
		<h3 class="text-center"><?php echo $breadcrum; ?></h3>
	</div>
	<div class="large-3 medium-3 cell">
		&nbsp;
	</div>
	<div class="large-6 medium-6 cell">
		<form data-abide novalidate action="add_manual_crypt_info.php" method="post">
			<div data-abide-error class="alert callout" style="display: none;">
				<p><i class="fi-alert"></i> There are some errors in your form.</p>
			</div>
			<label for="txt_username">Username</label>
			<input type="text" name="txt_username" id="txt_username" value="<?php echo $user->username?>" disabled />
			
			<label for="txt_crpt_user">Crypto Username</label>
			<input type="hidden" id="txt_hidden_max_user" value="1" />
			<textarea name="txt_cryp_user" id="txt_cryp_user" placeholder="Enter your Username to store encrypted" onblur="genInfoWorkhorseUser();"></textarea>
			
			<label for="txt_cryp_pass">Crypto Pass</label>
			<input type="hidden" id="txt_hidden_max_pass" value="1" />
			<textarea name="txt_cryp_pass" id="txt_cryp_pass" placeholder="Enter your Passcode to store encrypted" onblur="genInfoWorkhorsePass();"></textarea>
			
			<label for="txt_descript">Description</label>
			<input type="text" name="txt_descript" id="txt_descript" value="" maxlength="75" placeholder="Describe this entry - maximum 40 characters" />
			
			<label for="txt_link">Link</label>
			<input type="text" name="txt_link" id="txt_link" value="<?php echo $link; ?>" maxlength="255" placeholder="Where should I go when you click on me" />
			
			<label for="txt_link_order">Link Order</label>
			<input type="number" name="txt_link_order" id="txt_link_order" min="0" value="<?php echo $link_order?>" placeholder="Where do you want me to place this?" />
			
			<div class="text-center">
				<input type="submit" name="btn_submit" id="btn_submit" class="button" value="Save" />
			</div>
		</form>
	</div>
	<div class="large-3 medium-3 cell">
		&nbsp;
	</div>
</div>


<?php include_layout_template("admin_footer.php"); ?>