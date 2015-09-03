<?php
if (!isset($_POST['LPJBL-SUBMIT'])) {
	header("location:index.php");
}
	require "connect.php";
	
	$lpj_id = $_POST['lpj_id'];
	$bpp_id = $_POST['bpp_id'];
	
	$kodering = $_POST['LPJBL-KODERING'];
	$jml_anggaran = intval(str_replace(".", "", $_POST['LPJBL-JML-ANGGARAN']));
	$ls_gaji = intval(str_replace(".", "", $_POST['LPJBL-LPJ-LS-GAJI']));
	$ls_brg_jasa = intval(str_replace(".", "", $_POST['LPJBL-LPJ-LS-BARANG-DAN-JASA']));
	$up_gu_tu = intval(str_replace(".", "", $_POST['LPJBL-LPJ-UP-GU-TU']));
	
	$query = "UPDATE TB_LPJ_BNDHRA_PNGLUARAN_PMBNTU SET KODE_REK='$kodering', BPP_JML_ANGGRN='$jml_anggaran', BPP_LPJ_LS_GAJI='$ls_gaji', BPP_LPJ_LS_BRNG_JASA='$ls_brg_jasa', BPP_LPJ_UP_GU_TU='$up_gu_tu'
			  WHERE LPJ_ID=$lpj_id AND BPP_ID=$bpp_id";
	if (mysql_query($query)) {
		mysql_query("UPDATE TB_DATA_LPJ SET LPJ_TGL_UPDATE=CURRENT_TIMESTAMP WHERE LPJ_ID=$lpj_id");
		header("location:index.php?page=form-edit-data-lpj-belanja&lpj_id=".$lpj_id."&bpp_id=".$bpp_id."&update_data=success");
	} else {
		header("location:index.php?page=form-edit-data-lpj-belanja&lpj_id=".$lpj_id."&bpp_id=".$bpp_id."&update_data=error");
	}
?>