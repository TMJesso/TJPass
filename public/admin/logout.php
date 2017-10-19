<?php
require_once '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'includes/initialize.php';
$session->logout();
redirect_to("login.php");
?>