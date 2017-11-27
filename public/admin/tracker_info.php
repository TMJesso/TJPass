<?php
require_once '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'initialize.php';

if (!$session->is_logged_in()) { redirect_to("login.php"); }
// TODO Add menu selection automatic
$breadcrum = "Tracker Information";

?>
<?php include_layout_template("admin_header.php"); ?>
<div class="row">
	<div class="large-3 medium-3 columns">
		&nbsp;
	</div>
	<div class="large-6 medium-6 columns">
		<p class="text-center primary">Welcome to <myname>Jessop Computer Services</myname></p>
		
	</div>
	<div class="large-3 medium-3 columns">
		&nbsp;
	</div>
</div>






<?php include_layout_template("admin_footer.php"); ?>
