<?php
require_once '../../includes/initialize.php';
if (!$session->is_logged_in()) { redirect_to("login.php"); }
$errors = array();
if (isset($_GET['sid']) && !isset($_POST['submit_permission'])) {
	$subject_id = hent(ucode($_GET['sid']));
	$subject = Subject::find_subject_by_id($subject_id);
	$pages = Page::get_all_pages_by_subject_id($subject_id);
	if($pages) {
		ask_permission($subject, 1);
	} else {
		if ($subject->delete()) {
			$session->message("Subject " . $subject->menu_name . " has been removed from the system!");
			redirect_to('add_edit_content.php');
		}
	}
} elseif (isset($_GET['sid']) && isset($_POST['submit_permission'])) {
	if ($_POST['remove_pages'] == 1) {
		// permission granted
		$subject_id = hent(ucode($_GET['sid']));
		$subject = Subject::find_subject_by_id($subject_id);
		$pages = Page::get_all_pages_by_subject_id($subject_id);
		$delete_photos_ok = true;
		$delete_pages_ok = true;
		$delete_subject_ok = true;
		foreach ($pages as $page) {
			$photos = Photo::find_all_photos_by_page_id($page->id);
			foreach ($photos as $photo) {
				if (!$photo->delete()) {
					$delete_photos_ok = false;
					$errors['delete_photos'] = "Unable to delete Photo id:" . $photo->id;
				}
			}
			if ($delete_photos_ok) {
				if (!$page->delete()) {
					$delete_pages_ok = false;
					$errors['delete_pages'] = "Unable to delete Pages id: " . $page->id;
				}
			}
		}
		if ($delete_pages_ok) {
			if ($subject->delete()) {
				$session->message("Subject " . $subject->menu_name . " and all pages and any photos associated with it have all been removed!");
				redirect_to("add_edit_content.php");
			} else {
				$errors['delete_subject'] = "Unable to delete Subject id: " . $subject->id;
			}
		}
		if ($errors) {
			$session->errors($errors);
			redirect_to('add_edit_content.php');
		}
	}

}




?>
