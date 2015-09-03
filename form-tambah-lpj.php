<?php
	require "connect.php";
	
	$query    = "SELECT * FROM TB_M_BULAN";
	$result   = mysql_query($query);
	$bulan	  = array();
	while ($fetch = mysql_fetch_array($result)) { $bulan[] = $fetch; }
?>


<div class='sub-content' id='data-utama-lpj'>
	<div class='subtitle'>
		<div class='title'><h2>Form Tambah LPJ</h2></div>
	</div>
	<div class='sub-container'>
		<div class='cust-register'>
		<?php
		if (isset($_GET['save_data'])) {
			if ($_GET['save_data'] == 'error') {
		?>
			<p class='inlinemsg err'>Gagal menyimpan data. Silakan coba lagi nanti.</p>
		<?php
			} else if ($_GET['save_data'] == 'success') {
		?>
			<p class='inlinemsg suc'>Data berhasil disimpan. Halaman akan dialihkan ke Daftar LPJ...</p>
			<meta http-equiv='refresh' content='2; url=index.php?page=daftar-lpj'>
		<?php
			}
		}
		?>
			<form id='form-tambah-lpj' name='form-tambah-lpj' action='simpan-lpj.php' method='POST' style='margin-bottom: 20px;'>
				<h4>Data Utama LPJ</h4>
				<p>Isikan informasi data utama LPJ seperti nama kegiatan dan waktu kegiatan pada form di bawah ini.</p>
				<table style='margin-top: 20px;'>
					<tbody>
						<tr>
							<td><label for='KODERING-LPJ'>Kode Rekening</label></td>
							<td>: <input id='KODERING-LPJ' name='KODERING-LPJ' style='width: 400px;' type='text' placeholder='Masukkan kode rekening LPJ.' /></td>
						</tr>
						<tr>
							<td><label for='NAMA-KEGIATAN'>Nama Kegiatan</label></td>
							<td>: <input id='NAMA-KEGIATAN' name='NAMA-KEGIATAN' style='width: 400px;' type='text' placeholder='Masukkan nama kegiatan.' /></td>
						</tr>
						<tr>
							<td style='width: 200px;'><label for='BULAN-KEGIATAN'>Bulan Kegiatan</label></td>
							<td>: 
								<select id='BULAN-KEGIATAN' name='BULAN-KEGIATAN'>
									<option value='default'>-- Pilih Bulan --</option>
									<?php foreach ($bulan as $val) { ?>
									<option value='<?php echo $val['nomor'] ?>'><?php echo $val['nama_bulan'] ?></option> 
									<?php } ?>
								</select>
							</td>
						</tr>
						<tr>
							<td><label for='TAHUN-KEGIATAN'>Tahun Kegiatan:</label></td>
							<td>: <input id='TAHUN-KEGIATAN' name='TAHUN-KEGIATAN' style='width: 60px;' type='text' size='4' /></td>
						</tr>
						<tr>
							<td><label for='ORGANISASI'>Organisasi:</label></td>
							<td>: <input id='ORGANISASI' name='ORGANISASI' style='width: 400px;' type='text' placeholder='Masukkan nama organisasi pelaksana kegiatan.' /></td>
						</tr>
						<tr>
							<td><label for='UNIT-KERJA'>Unit Kerja:</label></td>
							<td>: <input id='UNIT-KERJA' name='UNIT-KERJA' style='width: 400px;' type='text' placeholder='Masukkan nama unit kerja.' /></td>
						</tr>
						<tr>
							<td><label for='PROGRAM'>Program:</label></td>
							<td>: <input id='PROGRAM' name='PROGRAM' style='width: 400px;' type='text' placeholder='Masukkan nama program kegiatan.' /></td>
						</tr>
						<tr>
							<td><label for='NO-DPPA'>No. DPPA</label></td>
							<td>: <input id='NO-DPPA' name='NO-DPPA' style='width: 400px;' type='text' placeholder='Masukkan nomor DPPA.' /></td>
						</tr>
						<tr>
							<td><label for='TGL-DPPA'>Tanggal DPPA</label></td>
							<td>: <input id='TGL-DPPA' name='TGL-DPPA' type='date' /></td>
						</tr>
						<tr>
							<td><input id='clear-form' name='clear-form' class='for-form' type='button' value='Clear' onclick="javascript:$('#form-tambah-lpj').each(function(){ this.reset(); });" /></td>
							<td><input id='submit-simpan-lpj' name='submit-simpan-lpj' class='for-form' type='submit' value='Tambah Data' /></td>
						</tr>
					</tbody>
				</table>
			</form>
		</div>
	</div>
</div>
<!-- JS -->
<script type='text/javascript' src='js/tambahlpj_validate.js'></script>