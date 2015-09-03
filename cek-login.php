<?php

	if(!isset($_POST['submit-login-admin'])) {
		header("location:index.php");
	}

	session_start();
	include_once("connect.php");

	$user_id = $_POST['FLA-USERNAME'];
	$user_pass = $_POST['FLA-PASSWORD'];
	
	// To protect MySQL injection
	$user_id = stripslashes($user_id);
	$user_pass = stripslashes($user_pass);
	$user_id = mysql_real_escape_string($user_id);
	$user_pass = mysql_real_escape_string($user_pass);
	$result = mysql_query("SELECT * FROM TB_USER WHERE USER_ID='$user_id' AND USER_PASSWORD='$user_pass'");

	if (mysql_num_rows($result) == 1) {
		$_SESSION['login_session']['is_logged_in'] = true;
		$_SESSION['login_session']['user_id'] = $user_id;
		header("location:index.php");
	} else {
		header("location:login.php?logged_in=error");
	}
	
?>