<?php
require_once '../../includes/initialize.php';
if (!$session->is_logged_in()) { redirect_to("login.php"); }

$load = false;
$loadsubmenu = false;
if (isset($_POST["submit_menu"])) {
	$load = false;
	$menu = Menu::find_by_id($_POST["select_menu"]);
	$has_menu = Submenu::find_all_by_menu_id($menu->menu_id, $session->get_security(), $session->get_clearance(), true);
	$submenu = new Submenu();
	$submenu->clearance = $menu->clearance;
	$submenu->security = $menu->security;
	$submenu->menu_id = $menu->menu_id;
	$submenu->visible = 1;
	$num = Submenu::find_max_id() + 1000;
	$submenu->submenu_id = generate_random_id() . ($num + 1);
	$security = UserValues::find_all_user_values();
	$clearance = UserAccess::find_all_user_access();
	
} elseif (isset($_POST["submit_submenu"])) { 
	$submenu = new Submenu();
	$submenu->id = null;
	$submenu->submenu_id = $_POST["hidden_submenu_id"];
	$submenu->menu_id = $_POST["hidden_menu_id"];
	$submenu->url = $base->prevent_injection($_POST["txt_url"]);
	$submenu->link_text = $base->prevent_injection($_POST["txt_link_text"]);
	$submenu->position = $_POST["select_position"];
	$submenu->visible = (isset($_POST["chk_box_visible"])) ? $_POST["chk_box_visible"] : 0;
	$submenu->security = $_POST["select_security"];
	$submenu->clearance = $_POST["select_clearance"];
	log_data_verbose($submenu, "Submenu");
	if ($submenu->save()) {
		$session->message("{$submenu->link_text} was successfully saved!");
		redirect_to("add_edit_menu.php");
	} else {
		$errors["submenu"] = "There was an error saving {$submenu->link_text}";
		$session->errors($errors);
		redirect_to("add_edit_menu.php");
	}
} elseif (isset($_GET["mid"])) {
	
} else {
	$menus = Menu::find_all_by_security($session->get_security());
	$load = true;
	$loadsubmenu = false;
}

?>

<?php include_layout_template("admin_header.php"); ?>
<?php if ($load && !$loadsubmenu) { ?>
<div class="row">
	<div class="large-3 medium-3 columns">
		&nbsp;
	</div>
	<div class="large-6 medium-6 columns">
		<form data-abide novalidate action="add_edit_menu.php" method="post">
			<div data-abide-error class="alert callout" style="display: none;">
				<p><i class="fi-alert"></i> There are some errors in your form.</p>
			</div>
			<label for="select_menu">Choose a menu to add submenu for
				<select name="select_menu" id="select_menu" required>
					<option value="">Select a menu</option>
					<?php foreach ($menus as $menu) { ?>
						<option value="<?php echo $menu->id; ?>"><?php echo $menu->link_text; ?></option>
					<?php } ?>
				</select>
				<span class="form-error">
					Please choose a menu item to add a submenu for...
				</span>
			</label>
			<div class="text-center">
				<input type="submit" class="button" name="submit_menu" id="submit_menu" value="Submit" >
			</div>
		</form>
	</div>
	<div class="large-3 medium-3 columns">
		&nbsp;
	</div>
</div>
<?php } elseif (!$load && !$loadsubmenu) { ?>
<div class="row">
	<div class="large-3 medium-3 columns">
		&nbsp;
	</div>
	<div class="large-6 medium-6 columns">
		<form data-abide novalidate action="add_edit_menu.php<?php echo "?mid=".$menu->id;?>" method="post">
			<div data-abide-error class="alert callout" style="display: none;">
				<p><i class="fi-alert"></i> There are some errors in your form.</p>
			</div>
			<label for="txt_menu_link">For menu item
				<input type="text" id="txt_menu_link" value="<?php echo $menu->link_text;?>" disabled >
			</label>
			<label for="txt_submenu_id">Unique ID
				<input type="text" id="txt_submenu_id" value="<?php echo $submenu->submenu_id; ?>" disabled >
				<input type="hidden" name="hidden_submenu_id" value="<?php echo $submenu->submenu_id; ?>" >
				<input type="hidden" name="hidden_menu_id" value="<?php echo $menu->menu_id; ?>" >
			</label>
			<label for="txt_url">URL
				<input type="text" name="txt_url" id="txt_url" value="" maxlength="50" placeholder="Maximum 50 characters" required >
				<span class="form-error">
					You must enter the URL for <?php echo $menu->link_text; ?> submenu item...
				</span>
			</label>
			<label for="txt_link_text">Link Text
				<input type="text" name="txt_link_text" id="txt_link_text" value="" maxlength="50" placeholder="Maximum 50 characters" required >
				<span class="form-error">
					You must enter the Link Text for <?php echo $menu->link_text; ?> submenu item...
				</span>
			</label>
			<label for="select_position">Position
				<select name="select_position" id="select_position" required>
					<option value="">Select the position</option>
					<?php for ($x = 0; $x <=10; $x++) { ?>
						<?php $has_menu = Submenu::find_all_by_menu_id_position($menu->menu_id, $x); ?>
						<?php if ($has_menu) { ?>
							<option value="<?php echo $x;?>" <?php if ($x == $has_menu->position) { ?> disabled <?php } ?>><?php echo $x . " has been used";?></option>
						<?php } else { ?>
							<option value="<?php echo $x; ?>"><?php echo $x;?></option>
						<?php }?>
					<?php } ?>
				</select>
				<span class="form-error">
					Select the position for <?php echo $menu->link_text; ?> submenu item...
				</span>
			</label>
			<fieldset class="callout">
				<legend>Visibility - Is this submenu item of <?php echo $menu->link_text;?> visible?</legend>
				<label for="chk_box_visible">
					<input type="checkbox" name="chk_box_visible" id="chk_box_visible" <?php if ($submenu->visible) { ?> checked <?php } ?> value="1" >Check if Yes 
				</label>
			</fieldset>
			<label for="select_security">User Type
				<select name="select_security" id="select_security" required>
					<option value="">Select User Type</option>
					<?php foreach ($security as $sec) { ?>
						<option value="<?php echo $sec->security; ?>"><?php echo $sec->security . ". " . $sec->name; ?></option>
					<?php }?>
				</select>
				<span class="form-error">
					You must choose the User Type ...
				</span>
			</label>
			<label for="select_clearance">Clearance Level
				<select name="select_clearance" id="select_clearance" required>
					<option value="">Select Clearance Level</option>
					<?php foreach ($clearance as $clr) { ?>
						<option value="<?php echo $clr->clearance; ?>"><?php echo $clr->clearance . ". " .$clr->name; ?></option>
					<?php } ?>
				</select>
			</label>
			
			
			<div class="text-center">
				<input type="submit" name="submit_submenu" class="button" value="Submit" >
			</div>
		</form>
	</div>
	<div class="large-3 medium-3 columns">
		&nbsp;
	</div>
</div>

<?php } ?>

<?php include_layout_template("admin_footer.php"); ?>


