<?php require_once 'includes/initialize.php';?>
<?php 
	$prefix = DS . DS . "hcsf-iuk.org" . DS . "public";
	$link = DS . "";
?>
<?php include_layout_template("sf_header.php"); ?>
<div class="row">
	<div class="large-12 medium-12 columns">
		<p class="text-center"><strong>404!</strong><br/><large>There was an error!</large></p>
		<p class="text-center">I am sorry I cannot find the URL you requested.</p>
		<p class="text-center"><a href="<?php echo $prefix . $link; ?>" class="button" >Ok</a></p>
	</div>
</div>
<?php include_layout_template("sf_footer.php"); ?>
		