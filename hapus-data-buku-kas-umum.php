<?php
if (!isset($_GET['lpj_id']) && !isset($_GET['bku_id'])) {
	header("location:index.php");
}

	require "connect.php";
	
	$lpj_id = $_GET['lpj_id'];
	$bku_id = $_GET['bku_id'];
	
	if(mysql_query("DELETE FROM TB_BUKU_KAS_UMUM WHERE LPJ_ID=$lpj_id AND BKU_ID=$bku_id")) {
		mysql_query("UPDATE TB_DATA_LPJ SET LPJ_TGL_UPDATE=CURRENT_TIMESTAMP WHERE LPJ_ID=$lpj_id");
		header("location:index.php?page=form-data-buku-kas-umum&lpj_id=".$lpj_id."&delete_bku=success");
	} else {
		header("location:index.php?page=form-data-buku-kas-umum&lpj_id=".$lpj_id."&delete_bku=error");
	}
?>