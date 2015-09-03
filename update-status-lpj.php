<?php
	
	session_start();
	
	if (!isset($_SESSION['login_session']['is_logged_in'])) {
		header("location:login.php");
	}
	
	if(!isset($_POST['submit-update-status-lpj'])) {
		header("location:index.php");
	}
	
	if(isset($_POST['submit-update-status-lpj'])) {
		$lpj_id = $_POST['lpj_id'];
		$status_lpj = $_POST['status-lpj'];
	}
	
	require "connect.php";
	
	if(mysql_query("UPDATE TB_DATA_LPJ SET LPJ_STATUS='$status_lpj' WHERE LPJ_ID='$lpj_id'")) {
		mysql_query("UPDATE TB_DATA_LPJ SET LPJ_TGL_UPDATE=CURRENT_TIMESTAMP WHERE LPJ_ID=$lpj_id");
		header("location:index.php?page=form-ubah-status-lpj&lpj_id=".$lpj_id."&update_data=success");
	} else {
		header("location:index.php?page=form-ubah-status-lpj&lpj_id=".$lpj_id."&update_data=error");
	}
	
?>