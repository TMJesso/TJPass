<?php
require_once '../../includes/initialize.php';
if (!$session->is_logged_in()) { redirect_to("login.php"); }

$load = false;
$loadsubmenu = false;
$loadedit = false;
if (isset($_POST["submit_menu"])) {
	$load = false;
	$security = UserValues::find_all_user_values();
	$clearance = UserAccess::find_all_user_access();
	if ($_POST["select_menu"] == "new" && !isset($_POST["chk_box_edit"])) {
		$loadsubmenu = false;
		$newmenu = new Menu();
		$newmenu->clearance = $session->get_clearance();
		$newmenu->security = $session->get_security();
		$num = Menu::find_max_id();
		$newmenu->menu_id = generate_random_id() . ($num + 1);
		$newmenu->not_logged_in = 0;
		$newmenu->visible = 1;
		
	} elseif (isset($_POST["chk_box_edit"])) {
		$loadsubmenu = false;
		$newmenu = Menu::find_by_id($_POST["select_menu"]);
		$loadedit = true;
	} elseif (isset($_POST["submit_add_menu"])) { 
		if ($_POST["submit_add_menu"] == "Edit Menu") {
			$menu = Menu::find_by_id($_POST["select_menu"]);
			$menu->clearance = $_POST["select_clearance"];
			$menu->security = $_POST["select_security"];
			$menu->not_logged_in = (isset($_POST["chk_not_logged_in"]) ? $_POST["chk_not_logged_in"] : 0);
			$menu->url = $base->prevent_injection($_POST["txt_url"]);
			$menu->link_text = $base->prevent_injection($_POST["txt_link_text"]);
			$menu->menu_order = $_POST["select_menu_order"];
			$menu->visible = (isset($_POST["chk_box_visible"]) ? $_POST["chk_box_visible"] : 0);
			if ($menu->save()) {
				$session->message("{$menu->link_text} was successfully changed!");
				redirect_to("add_edit_menu.php");
			} else {
				$errors["Edit_Menu"] = "There was an error saving {$menu->link_text}";
				$session->errors($errors);
				redirect_to("add_edit_menu.php");
			}
		}
	} else {
		$load = true;
		$loadsubmenu = true;
		$loadedit=false;
		$mid = $_POST["select_menu"];
		$menu = Menu::find_by_id($mid);
		$has_menu = Submenu::find_all_by_menu_id($menu->menu_id, $session->get_security(), $session->get_clearance(), true);
		$submenus = Submenu::find_all_by_id_for_menu($menu->id);
		
	
// 		$loadsubmenu = true;
// 		$submenu = new Submenu();
// 		$menu = Menu::find_by_id($_POST["select_menu"]);
// 		$has_menu = Submenu::find_all_by_menu_id($menu->menu_id, $session->get_security(), $session->get_clearance(), true);
// 		$submenu->clearance = $menu->clearance;
// 		$submenu->security = $menu->security;
// 		$submenu->menu_id = $menu->menu_id;
// 		$submenu->visible = 1;
// 		$num = Submenu::find_max_id() + 1000;
// 		$submenu->submenu_id = generate_random_id() . ($num + 1);
	}
	
} elseif (isset($_POST["submit_select_submenu"])) { 
	$load = false;
	$security = UserValues::find_all_user_values();
	$clearance = UserAccess::find_all_user_access();
	if ($_POST["select_submenu"] == "new" && !isset($_POST["chk_box_subedit"])) { 
		$loadsubmenu = true;
		$loadedit = false;
		$menu = Menu::find_by_id($base->prevent_injection($_GET["mid"]));
		//$has_menu = Submenu::find_all_by_menu_id($menu->menu_id, $session->get_security(), $session->get_clearance(), true);
		$submenu = new Submenu();
		$submenu->clearance = $menu->clearance;
		$submenu->security = $menu->security;
		$num = Submenu::find_max_id() + 1000;
		$submenu->submenu_id = generate_random_id() . ($num + 1);
		$submenu->visible = 1;
		$submenu->menu_id = $menu->menu_id;
	} elseif (isset($_POST["chk_box_subedit"])) {
		$loadsubmenu = true;
		$loadedit = true;
		$menu = Menu::find_by_id($base->prevent_injection($_GET["mid"]));
		//$has_menu = Submenu::find_all_by_menu_id($menu->menu_id, $session->get_security(), $session->get_clearance(), true);
		$submenu = Submenu::find_by_id($_POST["select_submenu"]);
	}
	
	
// 	$submenu = new Submenu();
// 	$submenu->id = null;
// 	$submenu->submenu_id = $_POST["hidden_submenu_id"];
// 	$submenu->menu_id = $_POST["hidden_menu_id"];
// 	$submenu->url = $base->prevent_injection($_POST["txt_url"]);
// 	$submenu->link_text = $base->prevent_injection($_POST["txt_link_text"]);
// 	$submenu->position = $_POST["select_position"];
// 	$submenu->visible = (isset($_POST["chk_box_visible"])) ? $_POST["chk_box_visible"] : 0;
// 	$submenu->security = $_POST["select_security"];
// 	$submenu->clearance = $_POST["select_clearance"];
// 	if ($submenu->save()) {
// 		$session->message("Submenu item {$submenu->link_text} was successfully saved!");
// 		redirect_to("add_edit_menu.php");
// 	} else {
// 		$errors["submenu"] = "There was an error saving Submenu item {$submenu->link_text}";
// 		$session->errors($errors);
// 		redirect_to("add_edit_menu.php");
// 	}
} elseif (isset($_GET["mid"])) {
	
} else {
	$menus = Menu::find_all_by_security_for_menus($session->get_security());
	$load = true;
	$loadsubmenu = false;
}

?>

<?php include_layout_template("admin_header.php"); ?>
<?php if ($load && !$loadsubmenu) { // choose add or edit a menu ?>
<div class="row">
	<div class="large-3 medium-3 columns">
		&nbsp;
	</div>
	<div class="large-6 medium-6 columns">
		<form data-abide novalidate action="add_edit_menu.php" method="post">
			<div data-abide-error class="alert callout" style="display: none;">
				<p><i class="fi-alert"></i> There are some errors in your form.</p>
			</div>
			<label for="select_menu">Choose a Menu item
				<select name="select_menu" id="select_menu" required>
					<option value="">Select a menu</option>
					<option value="new">Add new Menu</option>
					<?php foreach ($menus as $menu) { ?>
						<option value="<?php echo $menu->id; ?>"><?php echo $menu->menu_order . ". " . $menu->link_text; ?></option>
					<?php } ?>
				</select>
				<span class="form-error">
					Please choose a menu item to add or edit...
				</span>
			</label>
			<fieldset class="callout">
				<legend>Edit this menu?</legend>
				<label for="chk_box_edit">
					<input type="checkbox" name="chk_box_edit" id="chk_box_edit" value="1" >Check if Yes
				</label>
			</fieldset>
			<div class="text-center">
				<input type="submit" class="button" name="submit_menu" id="submit_menu" value="Select" >
			</div>
		</form>
	</div>
	<div class="large-3 medium-3 columns">
		&nbsp;
	</div>
</div>
<?php } elseif (!$load && $loadsubmenu) { // add or edit submenu ?>
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
				<input type="text" name="txt_url" id="txt_url" value="<?php echo $submenu->url; ?>" maxlength="50" placeholder="Maximum 50 characters" required >
				<span class="form-error">
					You must enter the URL for <?php echo $menu->link_text; ?> submenu item...
				</span>
			</label>
			<label for="txt_link_text">Link Text
				<input type="text" name="txt_link_text" id="txt_link_text" value="<?php echo $submenu->link_text; ?>" maxlength="50" placeholder="Maximum 50 characters" required >
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
							<option value="<?php echo $x;?>" <?php if ($x == $has_menu->position && !$loadedit) { ?> disabled <?php } ?> <?php if ($loadedit && $x == $submenu->position) { ?> selected <?php } ?>><?php echo $x . " used by " . $has_menu->link_text;?></option>
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
						<option value="<?php echo $sec->security; ?>" <?php if ($loadedit && $sec->security == $submenu->security) { $current = " (currently selected)"; ?> selected <?php } else { $current = ""; } ?>><?php echo $sec->security . ". " . $sec->name . $current; ?></option>
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
						<option value="<?php echo $clr->clearance; ?>" <?php if ($loadedit && $clr->clearance == $submenu->clearance) { $current = " (currently selected)"; ?> selected <?php } else { $current = ""; } ?>><?php echo $clr->clearance . ". " .$clr->name . $current; ?></option>
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

<?php } elseif (!$load && !$loadsubmenu) { // Add or Edit menu item ?>
	<div class="row">
		<div class="large-3 medium-3 columns">
			&nbsp;
		</div>
		<div class="large-6 medium-6 columns">
			<form data-abide novalidate action="add_edit_menu.php<?php if ($loadedit) { echo "?mid={$newmenu->id}"; } ?>" method="post">
				<div data-abide-error class="alert callout" style="display: none;">
					<p><i class="fi-alert"></i> There are some errors in your form.</p>
				</div>

				<label for="txt_menu_id">Unique Menu Id
					<input type="text" name="txt_menu_id" id="txt_menu_id" value="<?php echo $newmenu->menu_id; ?>" disabled >
				</label>

				<label for="txt_url">URL (where do I go)
					<input type="text" name="txt_url" id="txt_url" value="<?php echo $newmenu->url;?>" maxlength="50" placeholder="50 characters maximum" required >
					<span class="form-error">
						You must enter the URL e.g. sample.php ...
					</span>
				</label>

				<label for="txt_link_text">Link Text (What should I display for this link?)
					<input type="text" name="txt_link_text" id="txt_link_text" value="<?php echo $newmenu->link_text; ?>" maxlength="20" placeholder="20 characters Maximum" required >
					<span class="form-error">
						You must enter the Text that will be displayed for this link ...
					</span>
				</label>

				<label for="select_menu_order">Menu Order (What order should I display this link in?)
					<select name="select_menu_order" id="select_menu_order" required>
						<option value="">Select the menu order</option>
						<?php for ($x = 0; $x <=12; $x++) { ?>
							<?php $has_menu = Menu::find_all_by_menu_order($x); ?>
							<?php if ($has_menu) { ?>
								<option value="<?php echo $x;?>" <?php if ($x == $has_menu->menu_order && !$loadedit) { ?> disabled <?php } ?> <?php if ($loadedit && $x == $newmenu->menu_order) { ?> selected <?php } ?>><?php echo $x . " used by " . $has_menu->link_text  ;?></option>
							<?php } else { ?>
								<option value="<?php echo $x; ?>"><?php echo $x;?></option>
							<?php } ?>
						<?php } ?>
					</select>
					<span class="form-error">
						Select the menu order for this menu item...
					</span>
				</label>
				
				<fieldset class="callout">
					<legend>Visibility - Is this menu item visible?</legend>
					<label for="chk_box_visible">
						<input type="checkbox" name="chk_box_visible" id="chk_box_visible" <?php if ($newmenu->visible) { ?> checked <?php } ?> value="1" >Check if Yes 
					</label>
				</fieldset>

				<label for="select_security">User Type (Who should be able to see this menu?)
					<select name="select_security" id="select_security" required>
						<option value="">Select User Type</option>
						<?php foreach ($security as $sec) { ?>
							<option value="<?php echo $sec->security; ?>" <?php if ($sec->security == $session->get_security()) { ?> selected <?php } ?>><?php echo $sec->security . ". " . $sec->name; ?></option>
						<?php }?>
					</select>
					<span class="form-error">
						You must choose the User Type ...
					</span>
				</label>
				
				<label for="select_clearance">Clearance Level (Should this menu be restricted even further?)
					<select name="select_clearance" id="select_clearance" required>
						<option value="">Select Clearance Level</option>
						<?php foreach ($clearance as $clr) { ?>
							<option value="<?php echo $clr->clearance; ?>" <?php if ($clr->clearance == $session->get_clearance()) { ?> selected <?php } ?>><?php echo $clr->clearance . ". " .$clr->name; ?></option>
						<?php } ?>
					</select>
				</label>
				
				<fieldset class="callout">
					<legend>Should this be displayed if user is not logged in?</legend>
					<label for="chk_not_logged_on">
						<input type="checkbox" name="chk_not_logged_on" id="chk_not_logged_on" value="1" <?php if ($newmenu->not_logged_in) { ?> checked <?php } ?>>Check if Yes
					</label>
				</fieldset>
				
				<div class="text-center">
					<input type="submit" name="submit_add_menu" id="submit_add_menu" class="button" value="<?php if ($loadedit) { ?>Edit<?php } else { ?>Add<?php }?> Menu" >
				</div>
			</form>
		</div>
		<div class="large-3 medium-3 columns">
			&nbsp;
		</div>
	</div>
<?php } elseif ($load && $loadsubmenu) { // choose add or edit submenu?>
<div class="row">
	<div class="large-3 medium-3 columns">
		&nbsp;
	</div>
	<div class="large-6 medium-6 columns">
		<form data-abide novalidate action="add_edit_menu.php?mid=<?php echo $mid; ?>" method="post">
			<div data-abide-error class="alert callout" style="display: none;">
				<p><i class="fi-alert"></i> There are some errors in your form.</p>
			</div>
			<label for="select_menu">Choose a menu to add submenu for
				<select name="select_submenu" id="select_submenu" required>
					<option value="">Select a submenu</option>
					<option value="new">Add new Submenu</option>
					<?php foreach ($submenus as $submenu) { ?>
						<option value="<?php echo $submenu->id; ?>"><?php echo $submenu->position . ". " . $submenu->link_text; ?></option>
					<?php } ?>
				</select>
				<span class="form-error">
					Please choose a submenu item to add or edit...
				</span>
			</label>
			<fieldset class="callout">
				<legend>Edit this submenu?</legend>
				<label for="chk_box_subedit">
					<input type="checkbox" name="chk_box_subedit" id="chk_box_subedit" value="1" >Check if Yes
				</label>
			</fieldset>
			<div class="text-center">
				<input type="submit" class="button" name="submit_select_submenu" id="submit_select_submenu" value="Select" >
			</div>
		</form>
	</div>
	<div class="large-3 medium-3 columns">
		&nbsp;
	</div>
</div>

<?php } ?>
<?php include_layout_template("admin_footer.php"); ?>


