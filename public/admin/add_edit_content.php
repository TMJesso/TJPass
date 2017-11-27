<?php
require_once '../../includes/initialize.php';

if (!$session->is_logged_in()) { redirect_to("login.php"); }
$load_subject = false;
$load_page = false;
if (isset($_GET['subject']) && !isset($_POST['btn_subject_submit'])) {
	$load_subject = true;
	$subject = Subject::find_subject_by_id(hent(ucode($_GET['subject'])));
	
	
} elseif (isset($_GET['page']) && !isset($_POST['btn_page_submit'])) {
	$load_page = true;
	
} elseif (isset($_POST['btn_subject_submit']) && (isset($_GET['subject']) || isset($_GET['newsub']))) {
	// save subject
	if(isset($_GET['subject'])) { 
		$subject_id = hent(ucode($_GET['subject']));
		$subject = Subject::find_subject_by_id($subject_id);
	} else {
		$subject = new Subject();
	}
	
	$menu_name = hent($_POST['txt_menu_name']);
	$url = hent($_POST['txt_url']);
	$position = hent($_POST['select_position']);
	$visible = (isset($_POST['chk_box_visible'])) ? hent($_POST['chk_box_visible']) : false;
	$subject->menu_name = $menu_name;
	$subject->url = $url;
	$subject->position = $position;
	$subject->visible = $visible;
	if ($subject->save()) {
		$session->message("Subject '" . $menu_name . "' was successfully saved!");
		redirect_to('add_edit_content.php');
	} else {
		$errors['subject'] = "Unable to save '{$menu_name}' at this time!";
		$session->errors($errors);
		redirect_to('add_edit_content.php');
	}
} elseif (isset($_POST['btn_page_submit']) && isset($_GET['page'])) {
	
} elseif (isset($_GET['newsub'])) {
	$load_subject = true;
	$subject = new Subject();
	$user = User::get_user_by_id($session->get_user_id());
	$subject->username = $user->username;
	$subject->position = 0;
	unset($user);
	
}

$subjects = Subject::get_all_subject_by_position();

?>
<?php include_layout_template("admin_header.php"); ?>
<div class="row">
	<div class="large-2 medium-2 columns">
		&nbsp;
	</div>
	<div class="large-10 medium-10 columns">
		<h3 class="text-center">Select Menu below to edit</h3>
	</div>
	<div class="large-2 medium-2 columns">
		<br>
		<a href="add_edit_content.php?newsub" class="button">+ Add Subject</a>
	</div>
</div>
<?php content_navigation($subjects);?>
<?php if ($load_subject && !$load_page) { ?>

<!-- Edit Subject -->

<div class="row">
	<div class="large-3 medium-3 columns">
		&nbsp;
	</div>
	<div class="large-6 medium-6 columns">
		<?php $off = true; ?>
		<h4 class="text-center">Edit Subject <?php echo hdent($subject->menu_name);?></h4>
		<form data-abide novalidate action="add_edit_content.php?<?php if (isset($_GET['newsub'])) { echo "newsub"; } else { echo "subject=" . $subject->id; } ?>" method="post">
			<div data-abide-error class="alert callout" style="display: none;">
				<p><i class="fi-alert"></i> There are some errors in your form.</p>
			</div>
			<?php if (!$off) { ?>
			<label for="txt_username">Subject for user
				<input type="text" name="txt_username" id="txt_username" value="<?php echo hdent($subject->username); ?>" disabled />
			</label>
			<?php } ?>
			
			<label for="txt_menu_name">Menu Name <required>*</required>
				<input type="text" name="txt_menu_name" id="txt_menu_name" value="<?php echo hdent($subject->menu_name); ?>" required />
				<span class="form-error">
					You must enter a Menu Name
				</span>
			</label>
			<label>URL (optional)
				<input type="text" name="txt_url" id="txt_url" value="<?php echo hdent($subject->url); ?>" />
			</label>
			
			<label for="select_position">Position <required>*</required>
				<select name="select_position" id="select_position" required>
					<option value="">Select a position</option>
					<?php $subjects = Subject::get_all_subject_by_position(); ?>
					<?php for ($x = 1; $x <= 25; $x++) { ?>
						<?php unset($current); $current = array(); ?>
						<?php foreach ($subjects as $subj) { ?>
							<?php if ($subj->position == $x) { ?>
								<?php $current = $subj; ?>
							<?php } ?>
						<?php } ?>
						<option value="<?php echo $x; ?>" <?php if ($x == $subject->position) { ?> selected <?php } ?>><?php echo $x; if ($x == $subject->position) { ?> (current position)<?php } elseif (!empty($current)) { echo " (taken by ". $current->menu_name . ")"; } ?></option>
					<?php } ?>
				</select>
				<span class="form-error">
					You must select a postion ...
				</span>
			</label>
			
			<?php if (!$off) { ?>
			<label for="select_level">Level
				<select name="select_level" id="select_level" required aria-describedby="sel_level">
					<option value="">Select a level</option>
					<?php for ($x = 1; $x < 3; $x++) { ?>
						<option value="<?php echo $x;?>" <?php if ($x == $subject->level) { ?> selected <?php } ?>><?php echo $x; if ($x == $subject->level) { ?> (current level)<?php } ?></option>
					<?php } ?>
				</select>
				<span class="form-error">
					You must select a level ...
				</span>
			</label>
			<p class="help-text" id="sel_level">Level determines whether there is a space between this subject and next subject</p>
			<?php } ?>
			
			<fieldset class="callout">
				<legend><?php echo hdent($subject->menu_name);?> is currently set to <?php if ($subject->visible) { ?><strong>Visible</strong><?php } else { ?><strong>Invisible</strong><?php } ?></legend>
				<label for="chk_box_visible">Is <?php echo hdent($subject->menu_name); ?> visible?<br>
					<input type="checkbox" name="chk_box_visible" id="chk_box_visible" value="1" <?php if ($subject->visible) { ?> checked <?php } ?> /> Check for yes
				</label>
			</fieldset>
			
		<div class="text-center">
			<input type="submit" class="button" name="btn_subject_submit" id="btn_subject_submit" value="Save" />
			<input type="reset" class="button" name="btn_subject_reset" id="btn_subject_reset" value="Cancel" />
			<a href="delete_subject.php?sid=<?php echo $subject->id;?>" class="button">Delete <?php echo $subject->menu_name;?></a>
			
		</div>
		</form>
	</div>
	<div class="large-3 medium-3 columns">
		&nbsp;
	</div>
	
</div>
<?php } elseif (!$load_subject && $load_page) { ?>

<!-- Edit Page -->

<?php } else { ?>
<div class="row">
	<div class="large-12 medium-12 columns">
		<h5 class="text-center">Please choose a menu item from the 'Subject' menu</h5>
	</div>
</div>
<?php } ?>
<?php include_layout_template("admin_footer.php"); ?>

