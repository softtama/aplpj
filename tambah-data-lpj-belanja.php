<?php
if (!isset($_POST['LPJBL-SUBMIT'])) {
	header("location:index.php");
}
	require "connect.php";
	
	$lpj_id = $_POST['ID-LPJ'];
	
	
	$kodering = $_POST['LPJBL-KODERING'];
	$jml_anggaran = intval(str_replace(".", "", $_POST['LPJBL-JML-ANGGARAN']));
	$ls_gaji = intval(str_replace(".", "", $_POST['LPJBL-LPJ-LS-GAJI']));
	$ls_brg_jasa = intval(str_replace(".", "", $_POST['LPJBL-LPJ-LS-BARANG-DAN-JASA']));
	$up_gu_tu = intval(str_replace(".", "", $_POST['LPJBL-LPJ-UP-GU-TU']));
	
	$query = "INSERT INTO TB_LPJ_BNDHRA_PNGLUARAN_PMBNTU(LPJ_ID, KODE_REK, BPP_JML_ANGGRN, BPP_LPJ_LS_GAJI, BPP_LPJ_LS_BRNG_JASA, BPP_LPJ_UP_GU_TU)
			  VALUES('$lpj_id','$kodering','$jml_anggaran','$ls_gaji','$ls_brg_jasa','$up_gu_tu')";
	if (mysql_query($query)) {
		mysql_query("UPDATE TB_DATA_LPJ SET LPJ_TGL_UPDATE=CURRENT_TIMESTAMP WHERE LPJ_ID=$lpj_id");
		header("location:index.php?page=form-data-lpj-belanja&lpj_id=".$lpj_id."&save_data=success");
	} else {
		header("location:index.php?page=form-data-lpj-belanja&lpj_id=".$lpj_id."&save_data=error");
	}
?>