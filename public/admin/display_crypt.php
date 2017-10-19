<?php 
require_once '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'includes/initialize.php';
// connect to database
if (!$session->is_logged_in()) { redirect_to("login.php"); }
$breadcrum = "Display Crypt";
// this file is to be run only once to setup the tables with the data inside this
// file and populate the tables with it
$user = User::get_user_by_username($session->get_user_id());
$username = $user->username;
$num = Workhorse::get_last_link_order($username);
$crypted = Workhorse::get_all_crypt_values_for_active_by_linkorder($username);
?>


<?php include_layout_template("admin_header.php"); ?>
<!-- <div class="row"> -->
<!-- 	<div class="large-12 medium-12 columns"> -->
<!-- 		<div class="text-center"> -->
<!-- 			<input type="text" id="openId" size="100" value="" readonly /><br/><br/> -->
<!-- 			<input type="reset" class="button" value="Reset" id="btnReset" onclick="initReset();" /><a class="button" id="btnInfo" href="lists/genInfo.html" target="_blank">Gen Info</a><a class="button" id="btnRetrieve" href="lists/getInfo.html" target="_blank">Get Info</a><a class = "button" id="btnWrkDays" href="http://calendar-12.com/working_days/2015" target="_blank"title="Find the number of working days in 2015">Working days in 2015</a> -->
<!-- 		</div> -->
<!-- 	</div> -->
<!-- </div> -->
<div class="row">
	<div class="large-12 medium-12 columns">
			<table>
				<tr>
					<th>Website</th>
					<th>User Name</th>
					<th>Password</th>
				</tr>
				<tr>
			<?php foreach ($crypted as $crypt) { ?>
				<tr>
					<td style="text-align:left;"><?php echo $crypt->link_order; ?>. <a href="<?php echo $crypt->link; ?>" target="_blank"><?php echo $crypt->descript; ?></a></td>
					<td><textarea rows="1" cols="10" id="txtUser<?php echo $crypt->link_order;?>" readonly ></textarea> <input type="button" class="button" id="get<?php echo $crypt->link_order; ?>" value="Get <?php echo $crypt->link_order; ?>" onclick="goDisplay('U<?php echo $crypt->link_order; ?>', '<?php echo $crypt->crypt_name;?>');" /></td>
					<td><textarea rows="1" cols="10" id="txtPass<?php echo $crypt->link_order; ?>" readonly ></textarea> <input type="button" class="button" id="con<?php echo $crypt->link_order; ?>" value="Con <?php echo $crypt->link_order; ?>" onclick="goDisplay('P<?php echo $crypt->link_order; ?>', '<?php echo $crypt->crypt_security; ?>');" /><input type="hidden" id="hide<?php echo $crypt->link_order; ?>" value="1" /></td>
				</tr>
			<?php } ?>
			</table>
	</div>
</div>
		<input type="hidden" value="<?php echo $num; ?>" id="txtNumber" />
		<footer style="font-size:1.3em;">
			<br />
			<p>
				Copyright 2017 <script>var genName="2219v2ib2s6f38RNsUq32529642wzn2DPVQ2huy33129W8BX7ZPt7Y26qEH330373vi689xjN9PvfAR3342u4aD3h7X49wYB7JK32917u693Fe4F7aQPdip33244s34A9ZTno6HDF8x2969W8BX7ZPt7Y26qEH3222D2bhQ4oQR9r696mg3303pLJ76G34gab97CpW3345W3b67ce8FW3sv9Pp33459W8BX7ZPt7Y26qEH33339W8BX7ZPt7Y26qEH3336";document.write(getPass(genName));</script> All Rights Reserved
			</p>
		<br /><br />
		</footer>
	</body>
</html>
<?php include_layout_template("admin_footer.php"); ?>
