<?php

require "connect.php";

if (isset($_POST['TADM-USERNAME'])) { // If a username has been submitted 
	$username = mysql_real_escape_string($_POST['TADM-USERNAME']); // Some clean up
	
	$check_username = mysql_query("SELECT USER_ID FROM TB_USER WHERE USER_ID='$username'");
	// Query to check if username is available or not

	if (mysql_num_rows($check_username) > 0) {
		echo "false";	// If there is a record match in the Database - Not Available
	} else {
		echo "true";	// No Record Found - username is available and can be registered
	}
}

?>