<?php

if (!isset($_GET['lpj_id']))
{
	header("location:index.php");
}

$lpj_id = $_GET['lpj_id'];

define("UPLOAD_DIR", "arsip/");

include("functions.php");
$name = query_result("SELECT LPJ_ARSIP FROM TB_DATA_LPJ WHERE LPJ_ID='$lpj_id'", 'select', false);
$name = $name['LPJ_ARSIP'];

if (file_exists(UPLOAD_DIR . $name))
{   
    $delete = unlink(UPLOAD_DIR . $name);
 
    if ($delete == true)
    {
        $query = query_result("UPDATE TB_DATA_LPJ SET LPJ_ARSIP=NULL WHERE LPJ_ID='$lpj_id'", 'non_select', false);
        
        if ($query)
        {
            header("location:index.php?page=cari-arsip-lpj&lpj_id=".$lpj_id."&delete_arsip=success");
        }
        else
        {
            header("location:index.php?page=cari-arsip-lpj&lpj_id=".$lpj_id."&delete_arsip=error");
        }
    }
    else
    {
        header("location:index.php?page=cari-arsip-lpj&lpj_id=".$lpj_id."&delete_arsip=error");
    }
}
else
{
	header("location:index.php?page=cari-arsip-lpj&lpj_id=".$lpj_id."&delete_arsip=error");
}

?>