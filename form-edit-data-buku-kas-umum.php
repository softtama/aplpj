<?php
	require "connect.php";
	require "functions.php";
	
	if (!isset($_GET['lpj_id']) && !isset($_GET['bku_id'])) {
		header("location:index.php?page=home");
	}
	
	$lpj_id = $_GET['lpj_id'];
	$nama_kegiatan = get_nama_lpj($lpj_id);
	
	$bku_id = $_GET['bku_id'];
	
	$kodering_belanja = "0502";

	$query       = "SELECT * FROM TB_M_URAIAN WHERE KODE_REK LIKE '$kodering_belanja%'";
	$result      = mysql_query($query);
	$data_uraian = array();
	while ($fetch = mysql_fetch_array($result)) {
		$data_uraian[] = $fetch;
	}
	
	$query		= "SELECT PEG_NIP,PEG_NAMA FROM TB_M_PEGAWAI";
	$result		= mysql_query($query);
	$data_peg	= array();
	while ($fetch = mysql_fetch_array($result)) {
		$data_peg[] = $fetch;
	}
	
	$query = "SELECT LPJ_KODERING,LPJ_NAMA_KEG FROM TB_DATA_LPJ WHERE LPJ_ID='$lpj_id'";
	$result = mysql_query($query);
	$fetch = mysql_fetch_array($result);
	$kodering_lpj = $fetch['LPJ_KODERING'];
	$nama_keg = $fetch['LPJ_NAMA_KEG'];
	
	$query  = "SELECT * FROM TB_BUKU_KAS_UMUM WHERE LPJ_ID='$lpj_id' AND BKU_ID='$bku_id'";
	$result = mysql_query($query);
	$bku    = mysql_fetch_array($result);
	
	$pajak = explode("|", $bku['BKU_PAJAK']);
?>


<div class='sub-content' id='data-utama-lpj'>
	<?php include ("menu-dokumen-lpj.php"); ?>
	<br><br>
	<div class='subtitle'>
		<div class='title'><h2>Edit Data Buku Kas Umum</h2></div>
	</div>
	<div class='sub-container'>
		<div class='cust-register'>
		<?php if (isset($_GET['file_exist']) && $_GET['file_exist'] == 'false') { ?><p class='inlinemsg err'>File lampiran tidak ada, data tidak disimpan. Silakan coba lagi nanti.</p><?php } ?>
		<?php if (isset($_GET['upload_data']) && $_GET['upload_data'] == 'error') { ?><p class='inlinemsg err'>Gagal mengupload lampiran, data tidak disimpan. Silakan coba lagi nanti.</p><?php } ?>
		<?php if (isset($_GET['update_data']) && $_GET['update_data'] == 'error') { ?><p class='inlinemsg err'>Gagal mengupdate data. Silakan coba lagi nanti.</p><?php } ?>
		<?php if (isset($_GET['update_data']) && $_GET['update_data'] == 'success') { ?><p class='inlinemsg suc'>Data berhasil diupdate.</p><?php } ?>
		
			<form id='edit-bku' name='edit-bku' action='update-data-buku-kas-umum.php' method='POST' enctype='multipart/form-data'>
				<table>
					<tbody>
						<tr style='display: none; visibility: hidden;'>
							<td style='width: 200px;'><label for='lpj_id'><b>LPJ ID:</b></label></td>
							<td>: <input id='lpj_id' name='lpj_id' type='text' value='<?php echo $lpj_id ?>' style='width:40px;' /></td>
						</tr>
						<tr style='display: none; visibility: hidden;'>
							<td style='width: 200px;'><label for='bku_id'><b>BKU ID:</b></label></td>
							<td>: <input id='bku_id' name='bku_id' type='text' value='<?php echo $bku_id ?>' style='width:40px;' /></td>
						</tr>
						<tr>
							<td style='width: 200px;'><label for='BKU-NO-URUT'><b>Nomor Urut</b></label></td>
							<td>: <b><input id='BKU-NO-URUT' name='BKU-NO-URUT' type='text' value='<?php echo $bku['BKU_NO_URUT'] ?>' style='width:40px;' /></b></td>
						</tr>
						<tr>
							<td style='width: 200px;'><label for='BKU-TGL'>Tanggal</label></td>
							<td>: <input id='BKU-TGL' name='BKU-TGL' type='date' value='<?php echo $bku['BKU_TGL_SUB_KEG'] ?>' style='width:150px;' /></td>
						</tr>
						<tr>
							<td style='width: 200px;'><label for='BKU-KODERING'>Kode Rekening/Uraian</label></td>
							<td>
								: <select id='BKU-KODERING' name='BKU-KODERING'>
									<option value=''>-- Pilih Kode Rekening/Uraian --</option>
							<?php
								foreach ($data_uraian as $val) {
									$kode_rek		= $val['KODE_REK'];
									$kode_rek_titik = rtrim(chunk_split($val['KODE_REK'], 2, "."), ".");
									$nama_uraian	= $val['URAIAN_NAMA'];
							?>
									<option value='<?php echo $kode_rek ?>' <?php if ($bku['BKU_KODE_REK'] == $kode_rek) echo 'selected' ?>><?php echo $kode_rek_titik." / ".$nama_uraian ?></option>
							<?php
								}
							?>
								</select>
							</td>
						</tr>
						<tr>
							<td style='width: 200px;'><label for='BKU-URAIAN'>Uraian</label></td>
							<td>: <input id='BKU-URAIAN' name='BKU-URAIAN' type='text' value='<?php echo $bku['BKU_URAIAN'] ?>' style='width:400px;' /></td>
						</tr>
						<tr>
							<td style='width: 200px;'><label for='BKU-ATASNAMA'>Atas Nama</label></td>
							<td>: 
								<select id='BKU-ATASNAMA' name='BKU-ATASNAMA' style='width:300px;'>
									<option value=''>-- Pilih Pegawai --</option>
									<?php foreach ($data_peg as $val) { ?>
									<option value='<?php echo $val['PEG_NIP'] ?>' <?php if ($bku['BKU_ATAS_NAMA'] == $val['PEG_NIP']) echo 'selected' ?>> <?php echo $val['PEG_NAMA'] ?> </option>
									<?php } ?>
								</select>
							</td>
						</tr>
						<tr>
							<td style='width: 200px;'><label for='BKU-PENERIMAAN'><b>Penerimaan</b></label></td>
							<td>: Rp&nbsp;&nbsp;&nbsp;<input id='BKU-PENERIMAAN' class='number bold' name='BKU-PENERIMAAN' type='text' value='<?php if ($bku['BKU_PENERIMAAN'] != 0) echo $bku['BKU_PENERIMAAN'] ?>' style='width:200px;' /></td>
						</tr>
						<tr>
							<td style='width: 200px;'><label for='BKU-PENGELUARAN'><b>Pengeluaran</b></label></td>
							<td>: Rp&nbsp;&nbsp;&nbsp;<input id='BKU-PENGELUARAN' class='number bold' name='BKU-PENGELUARAN' type='text' value='<?php if ($bku['BKU_PENGELUARAN'] != 0) echo $bku['BKU_PENGELUARAN'] ?>' style='width:200px;' /></td>
						</tr>
						<tr>
							<td></td>
							<td>: <label for='BKU-PAJAK-1'>PPN</label> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: Rp&nbsp;&nbsp;&nbsp;<input id='BKU-PAJAK-1' class='number bold' name='BKU-PAJAK-1' type='text' value='<?php if (!empty($pajak[0])) echo $pajak[0] ?>' style='width:200px;' /></td>
						</tr>
						<tr>
							<td></td>
							<td>: <label for='BKU-PAJAK-2'>PPh Psl. 21</label> : Rp&nbsp;&nbsp;&nbsp;<input id='BKU-PAJAK-2' class='number bold' name='BKU-PAJAK-2' type='text' value='<?php if (!empty($pajak[1])) echo $pajak[1] ?>' style='width:200px;' /></td>
						</tr>
						<tr>
							<td></td>
							<td>: <label for='BKU-PAJAK-3'>PPh Psl. 22</label> : Rp&nbsp;&nbsp;&nbsp;<input id='BKU-PAJAK-3' class='number bold' name='BKU-PAJAK-3' type='text' value='<?php if (!empty($pajak[2])) echo $pajak[2] ?>' style='width:200px;' /></td>
						</tr>
						<tr>
							<td></td>
							<td>: <label for='BKU-PAJAK-4'>PPh Psl. 23</label> : Rp&nbsp;&nbsp;&nbsp;<input id='BKU-PAJAK-4' class='number bold' name='BKU-PAJAK-4' type='text' value='<?php if (!empty($pajak[3])) echo $pajak[3] ?>' style='width:200px;' /></td>
						</tr>
						<tr>
							<td><input id='clear' name='clear' class='for-form' type='button' value='Reset' onclick="javascript:$('#edit-bku').each(function(){ this.reset(); });" /></td>
							<td><input id='submit-update-bku' name='submit-update-bku' class='for-form' type='submit' value='Update Data' /></td>
						</tr>
					</tbody>
				</table>
				<br>
			</form>
		</div>
	</div>
</div>

<!-- JS -->
<script type='text/javascript' src='js/jquery.number.js'></script>
<script type='text/javascript'>
	//$('.number').number(true);
</script>
