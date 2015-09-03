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
?>


<div class='sub-content' id='data-utama-lpj'>
	<?php include ("menu-dokumen-lpj.php"); ?>
	<br><br>
	<div class='subtitle'>
		<div class='title'><h2>Form Upload Arsip Dokumen LPJ</h2></div>
	</div>
	<div class='sub-container'>
		<div class='cust-register'>
		<?php
		if (isset($_GET['upload_data'])) {
			if ($_GET['upload_data'] == 'error') {
		?>
			<p class='inlinemsg err'>Gagal upload arsip. Silakan coba lagi nanti.</p>
		<?php
			} else if ($_GET['upload_data'] == 'success') {
		?>
			<p class='inlinemsg suc'>Upload arsip berhasil dilakukan.</p>
		<?php
			}
		} else if (isset($_GET['file_exist']) && $_GET['file_exist'] == 'false') {
		?>
			<p class='inlinemsg err'>File arsip tidak ditemukan.</p>
		<?php
		}
		?>
			<form id='upload-arsip-lpj' name='upload-arsip-lpj' action='upload-arsip-lpj.php' method='POST' enctype='multipart/form-data'>
				<h4>Pilih File Arsip Dokumen LPJ</h4>
				<input id='lpj_id' name='lpj_id' type='hidden' value='<?php echo $lpj_id ?>' />
				<p>Pilih file dokumen LPJ yang telah discan dan diarsipkan ke dalam *.ZIP untuk dilampirkan di data LPJ ini.</p>
				<table style='margin-top: 20px;'>
					<tbody>
						<tr>
							<td style='width: 200px;'><label for='input-arsip-lpj'>File Arsip LPJ</label></td>
							<td>
								: <input id='input-arsip-lpj' name='input-arsip-lpj' type='file' />
							</td>
						</tr>
						<?php if ($allowedit) { ?>
						<tr>
							<td><input id='clear' name='clear' class='for-form' type='button' value='Reset' onclick="javascript:$('#upload-arsip-lpj').each(function(){ this.reset(); });" /></td>
							<td><input id='submit-upload-arsip-lpj' name='submit-upload-arsip-lpj' class='for-form' type='submit' value='Upload Arsip' /></td>
						</tr>
						<?php } ?>
					</tbody>
				</table>
				<br>
			</form>
		</div>
	</div>
</div>