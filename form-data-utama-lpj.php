<?php
	require "connect.php";
	require "functions.php";
	
	if (!isset($_GET['lpj_id'])) {
		header("location:index.php?page=home");
	}
	
	$lpj_id = $_GET['lpj_id'];
	
	if (is_pengelola_lpj($lpj_id, $_SESSION['login_session']['user_id'])) {
		$allowedit = true;
	} else { $allowedit = false; }
	
	$nama_kegiatan = get_nama_lpj($lpj_id);

	$query    = "SELECT * FROM TB_DATA_LPJ WHERE LPJ_ID='$lpj_id'";
	$result   = mysql_query($query);
	$data_lpj = mysql_fetch_array($result);

	$query    = "SELECT * FROM TB_M_BULAN";
	$result   = mysql_query($query);
	$bulan	  = array();
	while ($fetch = mysql_fetch_array($result)) { $bulan[] = $fetch; }
?>


<div class='sub-content' id='data-utama-lpj'>
	<?php include ("menu-dokumen-lpj.php"); ?>
	<br><br>
	<div class='subtitle'>
		<div class='title'><h2>Form Data Utama LPJ</h2></div>
	</div>
	<div class='sub-container'>
		<div class='cust-register'>
		<?php
		if (isset($_GET['update_data'])) {
			if ($_GET['update_data'] == 'error') {
		?>
			<p class='inlinemsg err'>Gagal update data. Silakan coba lagi nanti.</p>
		<?php
			} else if ($_GET['update_data'] == 'success') {
		?>
			<p class='inlinemsg suc'>Data berhasil disimpan.</p>
		<?php
			}
		}
		?>
			<form id='form-data-utama-lpj' name='form-data-utama-lpj' action='update-data-utama-lpj.php' method='POST'>
				<h4>Edit Data Utama</h4>
				<input id='lpj_id' name='lpj_id' type='hidden' value='<?php echo $lpj_id ?>' />
				<p>Isikan informasi data utama LPJ seperti nama kegiatan dan waktu kegiatan pada form di bawah ini.</p>
				<table style='margin-top: 20px;'>
					<tbody>
						<tr>
							<td><label for='KODERING-LPJ'>Kode Rekening</label></td>
							<td>: <input <?php if (!$allowedit) echo 'disabled' ?> id='KODERING-LPJ' name='KODERING-LPJ' style='width: 400px;' type='text' placeholder='Masukkan nama kegiatan.' value='<?php echo $data_lpj['LPJ_KODERING'] ?>' /></td>
						</tr>
						<tr>
							<td><label for='NAMA-KEGIATAN'>Nama Kegiatan</label></td>
							<td>: <input <?php if (!$allowedit) echo 'disabled' ?> id='NAMA-KEGIATAN' name='NAMA-KEGIATAN' style='width: 400px;' type='text' placeholder='Masukkan nama kegiatan.' value='<?php echo $data_lpj['LPJ_NAMA_KEG'] ?>' /></td>
						</tr>
						<tr>
							<td style='width: 200px;'><label for='BULAN-KEGIATAN'>Bulan Kegiatan</label></td>
							<td>
								: <select <?php if (!$allowedit) echo 'disabled' ?> id='BULAN-KEGIATAN' name='BULAN-KEGIATAN' style='width:100px;'>
									<option value='default'>-- Pilih Bulan --</option>
									<?php foreach ($bulan as $bulan) { ?>
									<option value='<?php echo $bulan['nomor'] ?>' <?php if ($bulan['nomor']==substr($data_lpj['LPJ_WAKTU_KEG'],0,2)) echo "selected" ?>><?php echo $bulan['nama_bulan'] ?></option> 
									<?php } ?>
								</select>
							</td>
						</tr>
						<tr>
							<td><label for='TAHUN-KEGIATAN'>Tahun Kegiatan</label></td>
							<td>: <input <?php if (!$allowedit) echo 'disabled' ?> id='TAHUN-KEGIATAN' name='TAHUN-KEGIATAN' style='width: 60px;' type='text' size='4' value='<?php echo substr($data_lpj['LPJ_WAKTU_KEG'], 3, 4) ?>' /></td>
						</tr>
						<tr>
							<td><label for='ORGANISASI'>Organisasi</label></td>
							<td>: <input <?php if (!$allowedit) echo 'disabled' ?> id='ORGANISASI' name='ORGANISASI' style='width: 400px;' type='text' size='4' placeholder='Masukkan nama organisasi pelaksana kegiatan.' value='<?php echo $data_lpj['LPJ_ORGANISASI'] ?>' /></td>
						</tr>
						<tr>
							<td><label for='UNIT-KERJA'>Unit Kerja</label></td>
							<td>: <input <?php if (!$allowedit) echo 'disabled' ?> id='UNIT-KERJA' name='UNIT-KERJA' style='width: 400px;' type='text' size='4' placeholder='Masukkan nama unit kerja.' value='<?php echo $data_lpj['LPJ_UNIT_KERJA'] ?>' /></td>
						</tr>
						<tr>
							<td><label for='PROGRAM'>Program</label></td>
							<td>: <input <?php if (!$allowedit) echo 'disabled' ?> id='PROGRAM' name='PROGRAM' style='width: 400px;' type='text' size='4' placeholder='Masukkan nama program kegiatan.' value='<?php echo $data_lpj['LPJ_PROGRAM'] ?>' /></td>
						</tr>
						<tr>
							<td><label for='NO-DPPA'>No. DPPA</label></td>
							<td>: <input <?php if (!$allowedit) echo 'disabled' ?> id='NO-DPPA' name='NO-DPPA' style='width: 400px;' type='text' placeholder='Masukkan nomor DPPA.' value='<?php echo $data_lpj['LPJ_NO_DPPA'] ?>' /></td>
						</tr>
						<tr>
							<td><label for='TGL-DPPA'>Tanggal DPPA</label></td>
							<td>: <input <?php if (!$allowedit) echo 'disabled' ?> id='TGL-DPPA' name='TGL-DPPA' type='date' value='<?php echo $data_lpj['LPJ_TGL_DPPA'] ?>' /></td>
						</tr>
						<?php if ($allowedit) { ?>
						<tr>
							<td><input id='clear' name='clear' class='for-form' type='button' value='Reset' onclick="javascript:$('#form-data-utama-lpj').each(function(){ this.reset(); });" /></td>
							<td><input id='submit-update-lpj' name='submit-update-lpj' class='for-form' type='submit' value='Update Data' /></td>
						</tr>
						<?php } ?>
					</tbody>
				</table>
			</form>
		</div>
	</div>
</div>