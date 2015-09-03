<?php

if (isset($_POST['submit-setting'])) {
	require "connect.php";
	
	$lpj_id   = $_POST['lpj_id'];
	$penyerah = $_POST['NIP-1'];
	$penerima = $_POST['NIP-2'];
	$kuasa_pa = $_POST['NIP-3'];
	$bndhr_pp = $_POST['NIP-4'];
	$pjbt_ptk = $_POST['NIP-5'];
	
	$query = "SELECT COUNT(*) AS num_rows FROM TB_PENANDATANGAN_LPJ WHERE LPJ_ID='$lpj_id'";
	$assoc = mysql_fetch_assoc(mysql_query($query));
	$count = $assoc['num_rows'];
	
	if ($count == 0) { // Not exist
		$query = "INSERT INTO TB_PENANDATANGAN_LPJ VALUES('$lpj_id','$penyerah','$penerima','$kuasa_pa','$bndhr_pp','$pjbt_ptk')";
		if (mysql_query($query)) {
			mysql_query("UPDATE TB_DATA_LPJ SET LPJ_TGL_UPDATE=CURRENT_TIMESTAMP WHERE LPJ_ID=$lpj_id");
			header("location:index.php?page=form-data-penandatangan-lpj&lpj_id=".$lpj_id."&update_data=success");
		} else {
			header("location:index.php?page=form-data-penandatangan-lpj&lpj_id=".$lpj_id."&update_data=error");
		}
	} else {	// Exist in the table
		$query = "UPDATE TB_PENANDATANGAN_LPJ SET PENYERAH='$penyerah',PENERIMA='$penerima',KUASA_PNGGUNA_ANGGRN='$kuasa_pa',BNDHRA_PNGLUARAN_PMBNTU='$bndhr_pp',PJBT_PLKSN_TKNS_KEG='$pjbt_ptk' WHERE LPJ_ID='$lpj_id'";
		if (mysql_query($query)) {
			mysql_query("UPDATE TB_DATA_LPJ SET LPJ_TGL_UPDATE=CURRENT_TIMESTAMP WHERE LPJ_ID=$lpj_id");
			header("location:index.php?page=form-data-penandatangan-lpj&lpj_id=".$lpj_id."&update_data=success");
		} else {
			header("location:index.php?page=form-data-penandatangan-lpj&lpj_id=".$lpj_id."&update_data=error");
		}
	}
}

?>