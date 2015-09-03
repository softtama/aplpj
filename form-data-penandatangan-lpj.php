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

	$query    = "SELECT PEG_NIP,PEG_NAMA FROM TB_M_PEGAWAI ORDER BY PEG_NAMA";
	$result   = mysql_query($query);
	$data_peg = array();
	while ($fetch = mysql_fetch_array($result)) {
		$data_peg[] = $fetch;
	}

	$query    = "SELECT * FROM TB_PENANDATANGAN_LPJ WHERE LPJ_ID='$lpj_id'";
	$result   = mysql_query($query);
	$data_ptd = mysql_fetch_array($result);
?>


<div class='sub-content' id='data-utama-lpj'>
	<?php include ("menu-dokumen-lpj.php"); ?>
	<br><br>
	<div class='subtitle'>
		<div class='title'><h2>Form Setting Penandatangan LPJ</h2></div>
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
			<form id='penandatangan-lpj' name='penandatangan-lpj' action='simpan-data-penandatangan-lpj.php' method='POST'>
				<h4>Penyerah</h4>
				<input id='lpj_id' name='lpj_id' type='hidden' value='<?php echo $lpj_id ?>' />
				<table style='margin-top: 20px;'>
					<tbody>
						<tr>
							<td><label for='NIP-1'>NIP</label></td>
							<td>: <input id='NIP-1' name='NIP-1' style='width: 300px;' type='text' value='<?php echo $data_ptd['PENYERAH'] ?>' readonly /></td>
						</tr>
						<tr>
							<td style='width: 200px;'><label for='NAMA-1'>Nama Pegawai</label></td>
							<td>
								: <select <?php if(!$allowedit) echo 'disabled' ?> id='NAMA-1' name='NAMA-1' style='width:200px;'>
									<option value=''>-- Pilih --</option>
									<?php foreach ($data_peg as $val) { ?>
									<option value='<?php echo $val['PEG_NIP'] ?>' <?php if ($val['PEG_NIP'] == $data_ptd['PENYERAH']) { echo "selected"; } ?>><?php echo $val['PEG_NAMA'] ?></option>
									<?php } ?>
								</select>
							</td>
						</tr>
					</tbody>
				</table>
				<br>
				<h4>Penerima</h4>
				<table style='margin-top: 20px;'>
					<tbody>
						<tr>
							<td><label for='NIP-2'>NIP</label></td>
							<td>: <input id='NIP-2' name='NIP-2' style='width: 300px;' type='text' value='<?php echo $data_ptd['PENERIMA'] ?>' readonly /></td>
						</tr>
						<tr>
							<td style='width: 200px;'><label for='NAMA-2'>Nama Pegawai</label></td>
							<td>
								: <select <?php if(!$allowedit) echo 'disabled' ?> id='NAMA-2' name='NAMA-2' style='width:200px;'>
									<option value=''>-- Pilih --</option>
									<?php foreach ($data_peg as $val) { ?>
									<option value='<?php echo $val['PEG_NIP'] ?>' <?php if ($val['PEG_NIP'] == $data_ptd['PENERIMA']) { echo "selected"; } ?>><?php echo $val['PEG_NAMA'] ?></option>
									<?php } ?>
								</select>
							</td>
						</tr>
					</tbody>
				</table>
				<br>
				<h4>Kuasa Pengguna Anggaran</h4>
				<table style='margin-top: 20px;'>
					<tbody>
						<tr>
							<td><label for='NIP-3'>NIP</label></td>
							<td>: <input id='NIP-3' name='NIP-3' style='width: 300px;' type='text' value='<?php echo $data_ptd['KUASA_PNGGUNA_ANGGRN'] ?>' readonly /></td>
						</tr>
						<tr>
							<td style='width: 200px;'><label for='NAMA-3'>Nama Pegawai</label></td>
							<td>
								: <select <?php if(!$allowedit) echo 'disabled' ?> id='NAMA-3' name='NAMA-3' style='width:200px;'>
									<option value=''>-- Pilih --</option>
									<?php foreach ($data_peg as $val) { ?>
									<option value='<?php echo $val['PEG_NIP'] ?>' <?php if ($val['PEG_NIP'] == $data_ptd['KUASA_PNGGUNA_ANGGRN']) { echo "selected"; } ?>><?php echo $val['PEG_NAMA'] ?></option>
									<?php } ?>
								</select>
							</td>
						</tr>
					</tbody>
				</table>
				<br>
				<h4>Bendahara Pengeluaran Pembantu</h4>
				<table style='margin-top: 20px;'>
					<tbody>
						<tr>
							<td><label for='NIP-4'>NIP</label></td>
							<td>: <input id='NIP-4' name='NIP-4' style='width: 300px;' type='text' value='<?php echo $data_ptd['BNDHRA_PNGLUARAN_PMBNTU'] ?>' readonly /></td>
						</tr>
						<tr>
							<td style='width: 200px;'><label for='NAMA-4'>Nama Pegawai</label></td>
							<td>
								: <select <?php if(!$allowedit) echo 'disabled' ?> id='NAMA-4' name='NAMA-4' style='width:200px;'>
									<option value=''>-- Pilih --</option>
									<?php foreach ($data_peg as $val) { ?>
									<option value='<?php echo $val['PEG_NIP'] ?>' <?php if ($val['PEG_NIP'] == $data_ptd['BNDHRA_PNGLUARAN_PMBNTU']) { echo "selected"; } ?>><?php echo $val['PEG_NAMA'] ?></option>
									<?php } ?>
								</select>
							</td>
						</tr>
					</tbody>
				</table>
				<br>
				<h4>Pejabat Pelaksana Teknis Kegiatan</h4>
				<table style='margin-top: 20px;'>
					<tbody>
						<tr>
							<td><label for='NIP-5'>NIP</label></td>
							<td>: <input id='NIP-5' name='NIP-5' style='width: 300px;' type='text' value='<?php echo $data_ptd['PJBT_PLKSN_TKNS_KEG'] ?>' readonly /></td>
						</tr>
						<tr>
							<td style='width: 200px;'><label for='NAMA-5'>Nama Pegawai</label></td>
							<td>
								: <select <?php if(!$allowedit) echo 'disabled' ?> id='NAMA-5' name='NAMA-5' style='width:200px;'>
									<option value=''>-- Pilih --</option>
									<?php foreach ($data_peg as $val) { ?>
									<option value='<?php echo $val['PEG_NIP'] ?>' <?php if ($val['PEG_NIP'] == $data_ptd['PJBT_PLKSN_TKNS_KEG']) { echo "selected"; } ?>><?php echo $val['PEG_NAMA'] ?></option>
									<?php } ?>
								</select>
							</td>
						</tr>
						<?php if($allowedit) { ?>
						<tr>
							<td><input id='clear' name='clear' class='for-form' type='button' value='Reset' onclick="javascript:$('#penandatangan-lpj').each(function(){ this.reset(); });" /></td>
							<td><input id='submit-setting' name='submit-setting' class='for-form' type='submit' value='Update Data' /></td>
						</tr>
						<?php } ?>
					</tbody>
				</table>
				<br>
			</form>
		</div>
	</div>
</div>

<!-- Additional JS -->
<script type='text/javascript'>
	$("#NAMA-1").change(function() { $("#NIP-1").val($(this).val()); });
	$("#NAMA-2").change(function() { $("#NIP-2").val($(this).val()); });
	$("#NAMA-3").change(function() { $("#NIP-3").val($(this).val()); });
	$("#NAMA-4").change(function() { $("#NIP-4").val($(this).val()); });
	$("#NAMA-5").change(function() { $("#NIP-5").val($(this).val()); });
</script>