<?php
if (!isset($_GET['lpj_id'])) {
	header("location:index.php");
}

	require "connect.php";
	
	$lpj_id = $_GET['lpj_id'];
	
	//mysql_query("DELETE FROM TB_PENANDATANGAN_LPJ WHERE LPJ_ID=$lpj_id");
	//mysql_query("DELETE FROM TB_LPJ_BNDHRA_PNGLUARAN_PMBNTU WHERE LPJ_ID=$lpj_id");
	//mysql_query("DELETE FROM TB_BUKU_KAS_UMUM WHERE LPJ_ID=$lpj_id");
	
    // Hapus dalam program hanya mengubah status LPJ menjadi TIDAK AKTIF
    // sehingga LPJ tidak akan ditampilkan di aplikasi
	if(mysql_query("UPDATE TB_DATA_LPJ SET AKTIF=FALSE WHERE LPJ_ID=$lpj_id")) {
		header("location:index.php?page=daftar-lpj&delete_lpj=success");
	} else {
		header("location:index.php?page=daftar-lpj&delete_lpj=error");
	}
?>