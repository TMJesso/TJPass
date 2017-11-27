<?php

?>

<div class="row">
	<div class="large-12 medium-12 columns">
		<form data-abide novalidate action="delete_subject.php" method="post">
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
				<option value="">Remove all pages for <?php echo $subject->menu_name; ?> </option>
				<option value="1">Yes - I understand - REMOVE ALL PAGES and PHOTOS for <?php echo $subject->menu_name; ?></option>
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
<?php include_layout_template("sf_login_footer.php"); ?>
