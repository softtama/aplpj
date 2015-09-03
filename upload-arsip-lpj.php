<?php

if (!isset($_POST['submit-upload-arsip-lpj']))
{
	header("location:index.php");
}

$lpj_id = $_POST['lpj_id'];

define("UPLOAD_DIR", "arsip/");

if (!empty($_FILES["input-arsip-lpj"]))
{
	$input_arsip_lpj = $_FILES["input-arsip-lpj"];
 
	if ($input_arsip_lpj["error"] !== UPLOAD_ERR_OK)
    {
		header("location:index.php?page=form-upload-arsip-lpj&lpj_id=".$lpj_id."&upload_data=error");
	}
 
	// Ensure a safe filename
	$name = stripslashes($input_arsip_lpj["name"]);

	// Do overwrite an existing file
	$parts = pathinfo($name);
	$name = "ARSIP_LPJ-". $lpj_id . "." . $parts["extension"];
	
	// Preserve file from temporary directory
	$success = move_uploaded_file($input_arsip_lpj["tmp_name"], UPLOAD_DIR . $name);
	if (!$success)
    { 
		header("location:index.php?page=form-upload-arsip-lpj&lpj_id=".$lpj_id."&upload_data=error");
	}
    else
    {
		include ("functions.php");
		query_result("UPDATE TB_DATA_LPJ SET LPJ_ARSIP='$name' WHERE LPJ_ID='$lpj_id'", 'non_select', false);
		mysql_query("UPDATE TB_DATA_LPJ SET LPJ_TGL_UPDATE=CURRENT_TIMESTAMP WHERE LPJ_ID=$lpj_id");
		header("location:index.php?page=form-upload-arsip-lpj&lpj_id=".$lpj_id."&upload_data=success");
	}
	
}
else
{
	header("location:index.php?page=form-upload-arsip-lpj&lpj_id=".$lpj_id."&file_exist=false");
}

?>
