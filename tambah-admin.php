<?php

if (isset($_POST['submit-tambah-admin'])) {
	$username = $_POST['TADM-USERNAME'];
	$nama = $_POST['TADM-NAMA'];
	$password = $_POST['TADM-PASSWORD'];
	$jabatan = $_POST['TADM-JABATAN'];
	
	require "connect.php";
	
	$query = "INSERT INTO TB_USER VALUES('$username', '$nama', '$jabatan', '$password')";
	if (mysql_query($query)) {
		header("location:login.php?register=success");
	} else {
		header("location:register.php?register=error");
	}
}

?>