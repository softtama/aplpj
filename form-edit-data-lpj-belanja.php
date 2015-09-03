<?php
	require "connect.php";
	require "functions.php";
	
	if (!isset($_GET['lpj_id']) && !isset($_GET['bpp_id'])) {
		header("location:index.php?page=home");
	}
	
	$lpj_id = $_GET['lpj_id'];
	$nama_kegiatan = get_nama_lpj($lpj_id);
	
	$bpp_id = $_GET['bpp_id'];

	$kodering_belanja = "0502";

	$query       = "SELECT * FROM TB_M_URAIAN WHERE KODE_REK LIKE '$kodering_belanja%'";
	$result      = mysql_query($query);
	$data_uraian = array();
	while ($fetch = mysql_fetch_array($result)) {
		$data_uraian[] = $fetch;
	}
	
	$query = "SELECT * FROM TB_LPJ_BNDHRA_PNGLUARAN_PMBNTU WHERE LPJ_ID='$lpj_id' AND BPP_ID='$bpp_id'";
	$result= mysql_query($query);
	$bpp   = mysql_fetch_array($result);
?>


<div class='sub-content' id='data-utama-lpj'>
	<?php include ("menu-dokumen-lpj.php"); ?>
	<br><br>
	<div class='subtitle'>
		<div class='title'><h2>Edit Data LPJ Bendahara Pengeluaran Pembantu (LPJ Belanja)</h2></div>
	</div>
	<div class='sub-container'>
		<div class='cust-register'>
		<?php
		if (isset($_GET['update_data'])) {
			if ($_GET['update_data'] == 'error') {
		?>
			<p class='inlinemsg err'>Gagal mengpudate data. Silakan coba lagi nanti.</p>
		<?php
			} else if ($_GET['update_data'] == 'success') {
		?>
			<p class='inlinemsg suc'>Data berhasil diupdate.</p>
		<?php
			}
		}
		?>
			<form id='edit-lpj-belanja' name='edit-lpj-belanja' action='update-data-lpj-belanja.php' method='POST'>
				<table>
					<tbody>
						<tr style='display: none; visibility: hidden;'>
							<td>LPJ ID</td>
							<td><input id='lpj_id' name='lpj_id' type='text' value='<?php echo $lpj_id ?>' readonly /></td>
						</tr>
						<tr style='display: none; visibility: hidden;'>
							<td>BPP ID</td>
							<td><input id='bpp_id' name='bpp_id' type='text' value='<?php echo $bpp_id ?>' readonly /></td>
						</tr>
						<tr>
							<td><label for='LPJBL-KODERING'><b>Kode Rekening/Uraian</b></label></td>
							<td>
								: <select id='LPJBL-KODERING' name='LPJBL-KODERING'>
									<option value='default'>-- Pilih Kode Rekening/Uraian --</option>
							<?php
								foreach ($data_uraian as $val) {
									$kode_rek		= $val['KODE_REK'];
									$kode_rek_titik = rtrim(chunk_split($val['KODE_REK'], 2, "."), ".");
									$nama_uraian	= $val['URAIAN_NAMA'];
							?>
									<option value='<?php echo $kode_rek ?>' <?php if ($bpp['KODE_REK'] == $kode_rek) echo 'selected' ?>><?php echo $kode_rek_titik." / ".$nama_uraian ?></option>
							<?php
								}
							?>
								</select>
							</td>
						</tr>
						<tr>
							<td style='width: 200px;'><label for='LPJBL-JML-ANGGARAN'>Jumlah Anggaran</label></td>
							<td>
								: Rp&nbsp;&nbsp;&nbsp;<input id='LPJBL-JML-ANGGARAN' class='number bold' name='LPJBL-JML-ANGGARAN' type='text' value='<?php echo $bpp['BPP_JML_ANGGRN'] ?>' style='width:200px;' />
							</td>
						</tr>
						<tr>
							<td style='width: 200px;'><label for='LPJBL-LPJ-LS-GAJI'>LPJ - LS Gaji</label></td>
							<td>
								: Rp&nbsp;&nbsp;&nbsp;<input id='LPJBL-LPJ-LS-GAJI' class='JUMLAHKAN number' name='LPJBL-LPJ-LS-GAJI' type='text' value='<?php echo $bpp['BPP_LPJ_LS_GAJI'] ?>' style='width:200px;' />
							</td>
						</tr>
						<tr>
							<td style='width: 200px;'><label for='LPJBL-LPJ-LS-BARANG-DAN-JASA'>LPJ - LS Barang dan Jasa</label></td>
							<td>
								: Rp&nbsp;&nbsp;&nbsp;<input id='LPJBL-LPJ-LS-BARANG-DAN-JASA' class='JUMLAHKAN number' name='LPJBL-LPJ-LS-BARANG-DAN-JASA' type='text' value='<?php echo $bpp['BPP_LPJ_LS_BRNG_JASA'] ?>' style='width:200px;' />
							</td>
						</tr>
						<tr>
							<td style='width: 200px;'><label for='LPJBL-LPJ-UP-GU-TU'>LPJ UP/GU/TU</label></td>
							<td>
								: Rp&nbsp;&nbsp;&nbsp;<input id='LPJBL-LPJ-UP-GU-TU' class='JUMLAHKAN number' name='LPJBL-LPJ-UP-GU-TU' type='text' value='<?php echo $bpp['BPP_LPJ_UP_GU_TU'] ?>' style='width:200px;' />
							</td>
						</tr>
						<tr>
							<td></td>
							<td><input id='LPJBL-SUBMIT' name='LPJBL-SUBMIT' class='for-form' type='submit' value='Update Data' /></td>
						</tr>
					</tbody>
				</table>
				<br>
			</form>
		</div>
	</div>
</div>

<!-- Additional JS -->
<script type='text/javascript' src='js/jquery.number.js'></script>
<script type='text/javascript'>
	$('.number').number(true);
</script>