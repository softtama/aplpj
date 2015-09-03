<?php

if (!isset($_GET['lpj_id'])) {
	header("location:index.php");
}

$lpj_id = $_GET['lpj_id'];
include("functions.php");
$data_lpj = query_result("SELECT LPJ_ARSIP FROM TB_DATA_LPJ WHERE LPJ_ID='$lpj_id'", 'select', false);
$arsip_lpj = $data_lpj['LPJ_ARSIP'];
header("location:arsip/".$arsip_lpj);

?>