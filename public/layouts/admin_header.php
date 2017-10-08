<?php
global $session, $breadcrum;

?>
<!DOCTYPE html>
<!-- this is my list of sites and their control structures
Created: October 20, 2014
Last Update:  February 3, 2015
Updated: September 27, 2017
	// 24236243582364836483690362436663696365436303648360036723606367236723606368436123582368436543384361836543582363036483276359436663654
	// 24934973357381235043336381235393735375637003357335033433336

-->
<html>
	<head>
		<meta content="text/html; charset=utf-8" http-equiv="Content-Type">
		<link href="<?php echo CSS_PATH."foundation-icons.css"; ?>" rel="stylesheet" type="text/css" />
		<link href="<?php echo CSS_PATH."foundation.css"; ?>" rel="stylesheet" type="text/css" />
		<link href="<?php echo CSS_PATH."app.css"; ?>" rel="stylesheet" type="text/css" />
		<script src="<?php echo JS_PATH."app.js"; ?>"></script>
		<script type="text/javascript">/*first and last*/var gentitle="221f762qC7ZLWCM74rW32529oEq929MEkPfD26t3312xd4a783kVB4bA3Md3303876WvUNpk8Y8TF4V3342pLJ76G34gab97CpW3291W3b67ce8FW3sv9Pp33249v2ib2s6f38RNsUq2969oEq929MEkPfD26t32229oEq929MEkPfD26t330326W6GPbzj43b8aKC33454s34A9ZTno6HDF8x33459W8BX7ZPt7Y26qEH33334s34A9ZTno6HDF8x3336";var txtname=getPass(gentitle);document.write('<title>'+txtname+' :: PRIVATE Menu page</title>');</script>
		<title>hello</title>
	</head>
	<body>
		<div class="row">
			<div class="large-12 medium-12 columns">
				<h1 class="text-center">TJ Password Tracker</h1>
			</div>
			<div class="large-3 medium-3 columns">
				&nbsp;
			</div>
			<div class="large-6 medium-6 columns text-center">
				<?php echo html_entity_decode(output_message($session->message)); ?>
				<?php echo output_errors($session->errors);?>
			<!-- Do not remove Error and Message Section -->
			</div>
			<div class="large-3 medium-3 columns">
				&nbsp;
			</div>
			<div class="large-12 medium-12 columns">
				<h3 class="text-center"><?php echo $breadcrum; ?></h3>
			</div>
		</div>
	