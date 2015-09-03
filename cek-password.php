<?php

session_start();
$username = $_SESSION['login_session']['user_id'];

require "connect.php";

if (isset($_POST['EDA-PASSWORD'])) { // If current password has been typed
	$eda_password = mysql_real_escape_string($_POST['EDA-PASSWORD']); // Some clean up
	
	$check_crpasswd = mysql_query("SELECT * FROM TB_USER WHERE USER_ID='$username' AND USER_PASSWORD='$eda_password'");
	// Query to check if email is available or not

	if (mysql_num_rows($check_crpasswd) > 0) {
		echo "true";	// If there is a record match in the Database - Current Password is valid
	} else {
		echo "false";	// No Record Found - Current Password is invalid
	}
}

?>