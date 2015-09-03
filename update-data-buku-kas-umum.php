<?php
if (isset($_POST['submit-update-bku'])) {
	require "connect.php";
	
	$lpj_id = $_POST['lpj_id'];
	$bku_id = $_POST['bku_id'];
	
	$no_urut = $_POST['BKU-NO-URUT'];
	$tgl = $_POST['BKU-TGL'];
	$kodering = $_POST['BKU-KODERING'];
	$uraian = $_POST['BKU-URAIAN'];
	$atas_nama = $_POST['BKU-ATASNAMA'];
	$penerimaan = $_POST['BKU-PENERIMAAN'];
	$pengeluaran = $_POST['BKU-PENGELUARAN'];
	$pajak = $_POST['BKU-PAJAK-1']."|".$_POST['BKU-PAJAK-2']."|".$_POST['BKU-PAJAK-3']."|".$_POST['BKU-PAJAK-4'];
	
	$query = "UPDATE TB_BUKU_KAS_UMUM SET BKU_NO_URUT='$no_urut', BKU_TGL_SUB_KEG='$tgl', BKU_KODE_REK='$kodering', BKU_URAIAN='$uraian', BKU_ATAS_NAMA='$atas_nama', BKU_PENERIMAAN='$penerimaan', BKU_PENGELUARAN='$pengeluaran', BKU_PAJAK='$pajak'
			  WHERE LPJ_ID=$lpj_id AND BKU_ID=$bku_id";
	
	if (mysql_query($query)) {
		mysql_query("UPDATE TB_DATA_LPJ SET LPJ_TGL_UPDATE=CURRENT_TIMESTAMP WHERE LPJ_ID=$lpj_id");
		header("location:index.php?page=form-edit-data-buku-kas-umum&lpj_id=".$lpj_id."&bku_id=".$bku_id."&update_data=success");
	} else {
		header("location:index.php?page=form-edit-data-buku-kas-umum&lpj_id=".$lpj_id."&bku_id=".$bku_id."&update_data=error");
	}
}
?>