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

	$query     = "SELECT * FROM TB_M_STATUS_LPJ";
	$result    = mysql_query($query);
	$data_stat = array();
	while ($fetch = mysql_fetch_array($result)) { $data_stat[] = $fetch; }

	$query    = "SELECT LPJ_STATUS FROM TB_DATA_LPJ WHERE LPJ_ID='$lpj_id'";
	$result   = mysql_query($query);
	$data_lpj = mysql_fetch_array($result);
?>


<div class='sub-content' id='data-utama-lpj'>
	<?php include ("menu-dokumen-lpj.php"); ?>
	<br><br>
	<div class='subtitle'>
		<div class='title'><h2>Form Ubah Status Dokumen LPJ</h2></div>
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
			<p class='inlinemsg suc'>Data berhasil diupdate.</p>
		<?php
			}
		}
		?>
			<form id='ubah-status-lpj' name='ubah-status-lpj' action='update-status-lpj.php' method='POST'>
				<h4>Status Dokumen LPJ</h4>
				<input id='lpj_id' name='lpj_id' type='hidden' value='<?php echo $lpj_id ?>' />
				<p>Keterangan Status: <br>
					<b>1 - Dalam Pengerjaan</b>: Dokumen LPJ masih dalam tahap pengerjaan.<br>
					<b>2 - Selesai</b>: Dokumen LPJ sudah selesai dibuat (final) tetapi belum ditandatangani.<br>
					<b>3 - Selesai dan Tertandatangani</b>: Dokumen LPJ sudah selesai dibuat (final) dan sudah ditandatangani oleh semua pihak yang terkait.
				</p>
				<table style='margin-top: 20px;'>
					<tbody>
						<tr>
							<td style='width: 200px;'><label for='status-lpj'>Status Dokumen</label></td>
							<td>
								: <select <?php if(!$allowedit) echo 'disabled' ?> id='status-lpj' name='status-lpj'>
									<option value='default'>-- Pilih --</option>
									<?php foreach ($data_stat as $val) { ?>
									<option value='<?php echo $val['STATUS_KODE'] ?>' <?php if ($data_lpj['LPJ_STATUS'] == $val['STATUS_KODE']) echo "selected" ?>><?php echo $val['STATUS_NAMA'] ?></option>
									<?php } ?>
								</select>
							</td>
						</tr>
						<?php if($allowedit) { ?> 
						<tr>
							<td><input id='clear' name='clear' class='for-form' type='button' value='Reset' onclick="javascript:$('#ubah-status-lpj').each(function(){ this.reset(); });" /></td>
							<td><input id='submit-update-status-lpj' name='submit-update-status-lpj' class='for-form' type='submit' value='Update Data' /></td>
						</tr>
						<?php } ?>
					</tbody>
				</table>
				<br>
			</form>
		</div>
	</div>
</div>