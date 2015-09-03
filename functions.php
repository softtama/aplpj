<?php

/*
 *	query_result($query, $query_type, $is_loop);
 *	A function to return the value of the result of the executed SQL query.
 *	
 *	Parameters:
 *	- $query		: string
 *		MySQL query string which you want to be executed.
 *	- $query_type	: string
 *		Query operation type. Supported value for this parameter are:
 *		- 'select'		: Used for query that uses the SELECT statement
 *		- 'non_select'	: Used for query that uses non-SELECT statement, e.g.: INSERT, UPDATE, DELETE
 *	- $is_loop	: boolean
 *		Boolean value to choose the way of value assignment. This parameter is used
 *		if and only if the $query_type param is set to 'select'.
 *		- true	: Assign values of the executed SQL query result in an array variable.
 *		- false	: Assign value of the executed SQL query result in a variable.
 *
 *	Return values:
 *	- $retval	: Can be a set of rows of data, a row of data, boolean true, or boolean false
 *	- false		: Whatever the $query_type, the return value is always false unless the 
 *				  $query_type parameter is set to 'select' or 'non_select'
 */ 
function query_result($query, $query_type, $is_loop) {
	switch ($query_type) {
		case 'select':
			switch ($is_loop) {
				case true:
					require "connect.php";
					
					$result = mysql_query($query);
					$retval = array();
					while ($fetch = mysql_fetch_array($result)) { $retval[] = $fetch; }
					
					return $retval;
				break;
				
				case false:
					require "connect.php";
					
					$result = mysql_query($query);
					$retval = mysql_fetch_array($result);
					
					return $retval;
				break;
					
				default:
					return false;
				break;
			}
		break;
		
		case 'non_select':			
			require "connect.php";
			
			$retval = mysql_query($query);
			
			return $retval;
		break;
		
		default:
			return false;
		break;
	}
}

// Get nama Dokumen LPJ berdasarkan ID LPJ
function get_nama_lpj($id_lpj) {

	$data_lpj = query_result("SELECT LPJ_NAMA_KEG FROM TB_DATA_LPJ WHERE LPJ_ID='$id_lpj'", 'select', false);
	return $data_lpj['LPJ_NAMA_KEG'];

}

function get_nama_pegawai($nip) {
	
	$data_peg = query_result("SELECT PEG_NAMA FROM TB_M_PEGAWAI WHERE PEG_NIP='$nip'", 'select', false);
	return $data_peg['PEG_NAMA'];
	
}

function get_nama_pengelola_lpj($lpj_id) {

	$data_lpj = query_result("SELECT USER_ID FROM TB_DATA_LPJ WHERE LPJ_ID='$lpj_id'", 'select', false);
	return get_nama_admin($data_lpj['USER_ID']);
	
}

function get_username_pengelola_lpj($lpj_id) {

	$data_lpj = query_result("SELECT USER_ID FROM TB_DATA_LPJ WHERE LPJ_ID='$lpj_id'", 'select', false);
	return $data_lpj['USER_ID'];

}

function get_nama_admin($user_id) {

	$data_admin = query_result("SELECT USER_NAMA FROM TB_USER WHERE USER_ID='$user_id'", 'select', false);
	return $data_admin['USER_NAMA'];

}

function nip_format($nip) {

	return substr($nip, 0, 8)." ".substr($nip, 8, 6)." ".substr($nip, 14, 1)." ".substr($nip, 15, 3);

}

function get_nama_rek($kodering) {

	$data_rek = query_result("SELECT * FROM TB_M_URAIAN WHERE KODE_REK='$kodering'", 'select', false);
	return $data_rek['URAIAN_NAMA'];
	
}

function get_waktu_keg_lpj($lpj_id) {

	$waktu_keg = query_result("SELECT LPJ_WAKTU_KEG FROM TB_DATA_LPJ WHERE LPJ_ID='$lpj_id'", 'select', false);
	return $waktu_keg['LPJ_WAKTU_KEG'];

}

function get_nama_bulan($no_bln) {

	$data_bulan = query_result("SELECT * FROM TB_M_BULAN WHERE nomor='$no_bln'", 'select', false);
	return $data_bulan['nama_bulan'];

}

function get_status_lpj($lpj_id) {

	$data_lpj = query_result("SELECT LPJ_STATUS FROM TB_DATA_LPJ WHERE LPJ_ID='$lpj_id'", 'select', false);
	return $data_lpj['LPJ_STATUS'];
	
}

function get_nama_status_lpj($stat_kd) {

	$data_stat = query_result("SELECT STATUS_NAMA FROM TB_M_STATUS_LPJ WHERE STATUS_KODE='$stat_kd'", 'select', false);
	return $data_stat['STATUS_NAMA'];
	
}

function is_pengelola_lpj($lpj_Id, $user_id) {
	
	$query = "SELECT * FROM TB_DATA_LPJ WHERE LPJ_ID='$lpj_Id' AND USER_ID='$user_id'";
	$num_rows = mysql_num_rows(mysql_query($query));
	$ispengelolalpj = ($num_rows == 1);
	return $ispengelolalpj;

}

function get_total_bln_lalu($nomor_item_spj, $kode_rek, $waktu_keg) {
	require "connect.php";
	
	switch ($nomor_item_spj) {
		case 1:
			$kolom = "BPP_LPJ_LS_GAJI";
		break;
		
		case 2:
			$kolom = "BPP_LPJ_LS_BRNG_JASA";
		break;
		
		case 3:
			$kolom = "BPP_LPJ_UP_GU_TU";
		break;
		
		default:
			$kolom = "BPP_LPJ_LS_GAJI";
		break;
	}
	
	$query = "
		SELECT
			SUM(".$kolom.")
		AS
			TOT_BLN_LALU
		FROM
			TB_LPJ_BNDHRA_PNGLUARAN_PMBNTU
		WHERE
			KODE_REK='$kode_rek'
			AND
			LPJ_ID IN (
				SELECT LPJ_ID
				FROM TB_DATA_LPJ
				WHERE LPJ_WAKTU_KEG<'$waktu_keg')
	" or die(mysql_error());
	$result = mysql_query($query);
	$assoc = mysql_fetch_assoc($result);
	return $assoc['TOT_BLN_LALU'];
	
}

?>
