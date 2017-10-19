<?php 
require_once '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'includes/initialize.php';
// connect to database
if (!$session->is_logged_in()) { redirect_to("login.php"); }
$breadcrum = "Display All";
// this file is to be run only once to setup the tables with the data inside this
// file and populate the tables with it
$user = User::get_user_by_username($session->get_user_id());
$username = $user->username;
$num = Workhorse::get_last_link_order($username);
$crypted = Workhorse::get_all_crypt_values_for_active_by_linkorder($username);

$load = false;
$items = Workhorse::get_all_crypt_values_for_active_by_descrpt($session->get_user_id());

if (isset($_POST["submit_item"])) {
	$this_item = Workhorse::get_crypt_value_by_crypt_id($_POST["select_display"]);
	if ($this_item) {
		$load = true;
	}
}
?>


<?php include_layout_template("admin_header.php"); ?>
<?php if (!$load) { ?>
<div class="row">
	<div class="large-3 medium-3 columns">
		&nbsp;
	</div>
	<div class="large-6 medium-6 columns">
		<form data-abide novalidate action="display_all.php" method="post">
			<div data-abide-error class="alert callout" style="display: none;">
				<p><i class="fi-alert"></i> There are some errors in your form.</p>
			</div>
			<label for="select_display">Select Item to Display
				<select name="select_display" id="select_display">
					<option value="">Select Item to Display</option>
					<?php foreach ($items as $item) { ?>
						<option value="<?php echo $item->crypt_id?>"><?php if ($item->link_order < 10) { $cont = "&nbsp;&nbsp;"; } elseif ($item->link_order < 100) { $cont = "&nbsp;"; } else { $cont = ""; } echo $cont . $item->link_order . ". " . $item->descript; ?></option>
					<?php } ?>
				</select>
			</label>
			
			<div class="text-center">
				<input type="submit" name="submit_item" id="submit_item" class="button" value="Get It!" >
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
			<table>
				<tr>
					<th>Website</th>
					<th>User Name</th>
					<th>Password</th>
				</tr>
				<tr>
				<tr>
					<td style="text-align:left;"><?php echo $this_item->link_order; ?>. <a href="<?php echo $this_item->link; ?>" target="_blank"><?php echo $this_item->descript; ?></a></td>
					<td><textarea rows="1" cols="10" id="txtUser<?php echo $this_item->link_order;?>" readonly ></textarea> <input type="button" class="button" id="get<?php echo $this_item->link_order; ?>" value="Get <?php echo $this_item->link_order; ?>" onclick="goDisplay('U<?php echo $this_item->link_order; ?>', '<?php echo $this_item->crypt_name;?>');" /></td>
					<td><textarea rows="1" cols="10" id="txtPass<?php echo $this_item->link_order; ?>" readonly ></textarea> <input type="button" class="button" id="con<?php echo $this_item->link_order; ?>" value="Con <?php echo $this_item->link_order; ?>" onclick="goDisplay('P<?php echo $this_item->link_order; ?>', '<?php echo $this_item->crypt_security; ?>');" /><input type="hidden" id="hide<?php echo $this_item->link_order; ?>" value="1" /></td>
				</tr>
			</table>
			<?php $current_script = substr($_SERVER["SCRIPT_NAME"], strrpos($_SERVER["SCRIPT_NAME"], DIRECTORY_SEPARATOR), strlen($_SERVER["SCRIPT_NAME"])); ?>
			<div class="text-center">
				<a href="<?php echo $_SERVER["SCRIPT_NAME"]; ?>" class="button">Done</a>
			</div>
	</div>
</div>
<?php } ?>

<?php include_layout_template("admin_footer.php"); ?>
