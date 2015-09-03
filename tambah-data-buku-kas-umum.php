<?php

if (isset($_POST['submit-tambah-bku'])) {
	require "connect.php";
	
	$lpj_id = $_POST['lpj_id'];
	
	$no_urut = $_POST['BKU-NO-URUT'];
	$tgl = $_POST['BKU-TGL'];
	$kodering = $_POST['BKU-KODERING'];
	$uraian = $_POST['BKU-URAIAN'];
	$atas_nama = $_POST['BKU-ATASNAMA'];
	$penerimaan = $_POST['BKU-PENERIMAAN'];
	$pengeluaran = $_POST['BKU-PENGELUARAN'];
	$lampiran = $_POST['BKU-LAMPIRAN'];
	$pajak = $_POST['BKU-PAJAK-1']."|".$_POST['BKU-PAJAK-2']."|".$_POST['BKU-PAJAK-3']."|".$_POST['BKU-PAJAK-4'];
	
	define("UPLOAD_DIR", "./lampiran-bku/");

	if (!empty($_FILES["BKU-LAMPIRAN"])) {
		$bku_lampiran = $_FILES["BKU-LAMPIRAN"];
	 
		if ($bku_lampiran["error"] !== UPLOAD_ERR_OK) {
			header("location:index.php?page=form-data-buku-kas-umum&lpj_id=".$lpj_id."&upload_data=error");
		}
	 
		// ensure a safe filename
		$name = preg_replace("/[^A-Z0-9._-]/i", "_", $bku_lampiran["name"]);

		// Do overwrite an existing file
		$parts = pathinfo($name);
		$name = "BKU_LAMPIRAN-". $lpj_id . "-". $no_urut . "." . $parts["extension"];
		
		// preserve file from temporary directory
		$success = move_uploaded_file($bku_lampiran["tmp_name"],
			UPLOAD_DIR . $name);
		if (!$success) { 
			header("location:index.php?page=form-data-buku-kas-umum&lpj_id=".$lpj_id."&upload_data=error");
		} else {			
			$query = "INSERT INTO TB_BUKU_KAS_UMUM(BKU_NO_URUT, LPJ_ID, BKU_TGL_SUB_KEG, BKU_KODE_REK, BKU_URAIAN, BKU_ATAS_NAMA, BKU_PENERIMAAN, BKU_PENGELUARAN, BKU_PAJAK, BKU_LAMPIRAN)
					  VALUES($no_urut, $lpj_id, '$tgl', '$kodering', '$uraian', '$atas_nama', '$penerimaan', '$pengeluaran', '$pajak', '$name')";
			
			if (mysql_query($query)) {
				mysql_query("UPDATE TB_DATA_LPJ SET LPJ_TGL_UPDATE=CURRENT_TIMESTAMP WHERE LPJ_ID=$lpj_id");
				header("location:index.php?page=form-data-buku-kas-umum&lpj_id=".$lpj_id."&save_data=success");
			} else {
				header("location:index.php?page=form-data-buku-kas-umum&lpj_id=".$lpj_id."&save_data=error");
			}
		}
		
	} else {
		$query = "INSERT INTO TB_BUKU_KAS_UMUM(BKU_NO_URUT, LPJ_ID, BKU_TGL_SUB_KEG, BKU_KODE_REK, BKU_URAIAN, BKU_ATAS_NAMA, BKU_PENERIMAAN, BKU_PENGELUARAN, BKU_PAJAK)
				  VALUES($no_urut, $lpj_id, '$tgl', '$kodering', '$uraian', '$atas_nama', '$penerimaan', '$pengeluaran', '$pajak')";
		
		if (mysql_query($query)) {
			mysql_query("UPDATE TB_DATA_LPJ SET LPJ_TGL_UPDATE=CURRENT_TIMESTAMP WHERE LPJ_ID=$lpj_id");
			header("location:index.php?page=form-data-buku-kas-umum&lpj_id=".$lpj_id."&save_data=success&no_archive=true");
		} else {
			header("location:index.php?page=form-data-buku-kas-umum&lpj_id=".$lpj_id."&save_data=error&no_archive=true");
		}
	}
} else {
	header("location:index.php");
}

?>