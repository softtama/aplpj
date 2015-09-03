<?php
	if(isset($_POST['submit-simpan-lpj'])) {
		session_start();
		
		if (!isset($_SESSION['login_session']['is_logged_in'])) {
			header("location:login.php");
		}
		
		require "connect.php"; 
		
		$user_id = $_SESSION['login_session']['user_id'];
		
			$kodering = $_POST['KODERING-LPJ'];
			$nama_keg = $_POST['NAMA-KEGIATAN'];
			$waktu_keg = $_POST['BULAN-KEGIATAN']."-".$_POST['TAHUN-KEGIATAN'];
			$org = $_POST['ORGANISASI'];
			$unit_kerja = $_POST['UNIT-KERJA'];
			$prog = $_POST['PROGRAM'];
			$no_dppa = $_POST['NO-DPPA'];
			$tgl_dppa = $_POST['TGL-DPPA'];
		
		if(mysql_query("INSERT INTO TB_DATA_LPJ(USER_ID, LPJ_KODERING, LPJ_NAMA_KEG, LPJ_WAKTU_KEG, LPJ_ORGANISASI, LPJ_UNIT_KERJA, LPJ_PROGRAM, LPJ_NO_DPPA, LPJ_TGL_DPPA, LPJ_TGL_BUAT, LPJ_TGL_UPDATE, LPJ_STATUS) VALUES('$user_id', '$kodering', '$nama_keg', '$waktu_keg', '$org', '$unit_kerja', '$prog', '$no_dppa', '$tgl_dppa', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, '1')")) {
			header("location:index.php?page=form-tambah-lpj&save_data=success");
		} else {
			header("location:index.php?page=form-tambah-lpj&save_data=error");
		}
	}
?>