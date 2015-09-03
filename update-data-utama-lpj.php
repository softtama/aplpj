<?php
	
	require "connect.php"; 
	
	if (!isset($_POST['submit-update-lpj'])) {
		header("location:index.php");
	}
	
	$lpj_id = $_POST['lpj_id'];
	$kodering = $_POST['KODERING-LPJ'];
	$nama_keg = $_POST['NAMA-KEGIATAN'];
	$waktu_keg = $_POST['BULAN-KEGIATAN']."-".$_POST['TAHUN-KEGIATAN'];
	$org = $_POST['ORGANISASI'];
	$unit_kerja = $_POST['UNIT-KERJA'];
	$prog = $_POST['PROGRAM'];
	$no_dppa = $_POST['NO-DPPA'];
	$tgl_dppa = $_POST['TGL-DPPA'];
	
	if (mysql_query("UPDATE TB_DATA_LPJ SET LPJ_KODERING='$kodering', LPJ_NAMA_KEG='$nama_keg', LPJ_WAKTU_KEG='$waktu_keg', LPJ_ORGANISASI='$org', LPJ_UNIT_KERJA='$unit_kerja', LPJ_PROGRAM='$prog', LPJ_NO_DPPA='$no_dppa', LPJ_TGL_DPPA='$tgl_dppa', LPJ_TGL_UPDATE=CURRENT_TIMESTAMP WHERE LPJ_ID='$lpj_id'")) {
		mysql_query("UPDATE TB_DATA_LPJ SET LPJ_TGL_UPDATE=CURRENT_TIMESTAMP WHERE LPJ_ID=$lpj_id");
		header("location:index.php?page=form-data-utama-lpj&lpj_id=".$lpj_id."&update_data=success");
	} else {
		header("location:index.php?page=form-data-utama-lpj&lpj_id=".$lpj_id."&update_data=error");
	}
	
?>