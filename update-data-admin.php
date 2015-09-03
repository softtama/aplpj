<?php
	session_start();
	
	if (!isset($_SESSION['login_session']['is_logged_in'])) {
		header("location:login.php");
	}
	
	if (!isset($_POST['submit-update-data-admin'])) {
		header("location:index.php");
	}
	
	require "connect.php"; 
	
	$user_id = $_SESSION['login_session']['user_id'];
		
	$nama = mysql_real_escape_string(stripslashes($_POST['EDA-NAMA']));
	$jabatan = $_POST['EDA-JABATAN'];
	
	if (empty($_POST['EDA-NWPASSWD'])) {
		if(mysql_query("UPDATE TB_USER SET USER_NAMA='$nama', USER_JABATAN='$jabatan' WHERE USER_ID='$user_id'")) {
			header("location:index.php?page=form-edit-data-admin&update_data=success");
		} else {
			header("location:index.php?page=form-edit-data-admin&update_data=error");
		}
	} else {
		$password = mysql_real_escape_string(stripslashes($_POST['EDA-NWPASSWD']));
		
		if(mysql_query("UPDATE TB_USER SET USER_NAMA='$nama', USER_PASSWORD='$password', USER_JABATAN='$jabatan' WHERE USER_ID='$user_id'")) {
			header("location:index.php?page=form-edit-data-admin&update_data=success&passwd=changed");
		} else {
			header("location:index.php?page=form-edit-data-admin&update_data=error");
		}
	}
?>