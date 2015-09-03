<?php
if (!isset($_GET['lpj_id']) && !isset($_GET['bpp_id'])) {
	header("location:index.php");
}

	require "connect.php";
	
	$lpj_id = $_GET['lpj_id'];
	$bpp_id = $_GET['bpp_id'];
	
	if(mysql_query("DELETE FROM TB_LPJ_BNDHRA_PNGLUARAN_PMBNTU WHERE LPJ_ID=$lpj_id AND BPP_ID=$bpp_id")) {
		mysql_query("UPDATE TB_DATA_LPJ SET LPJ_TGL_UPDATE=CURRENT_TIMESTAMP WHERE LPJ_ID=$lpj_id");
		header("location:index.php?page=form-data-lpj-belanja&lpj_id=".$lpj_id."&delete_bpp=success");
	} else {
		header("location:index.php?page=form-data-lpj-belanja&lpj_id=".$lpj_id."&delete_bpp=error");
	}
?>