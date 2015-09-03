<?php
	require "connect.php";
	require "functions.php";
	
	setlocale(LC_MONETARY, 'id_ID');
	
	if (!isset($_GET['lpj_id'])) {
		header("location:index.php?page=home");
	}
	
	$lpj_id = $_GET['lpj_id'];
	
	if (is_pengelola_lpj($lpj_id, $_SESSION['login_session']['user_id'])) {
		$allowedit = true;
	} else { $allowedit = false; }
	
	$nama_kegiatan = get_nama_lpj($lpj_id);
	
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
?>


<div class='sub-content' id='data-utama-lpj'>
	<?php include ("menu-dokumen-lpj.php"); ?>
	<br><br>
	<?php if ($allowedit) { ?>
	<div class='subtitle'>
		<div class='title'><h2>Input Data Buku Kas Umum</h2></div>
	</div>
	<div class='sub-container'>
		<div class='cust-register'>
		<?php if (isset($_GET['file_exist']) && $_GET['file_exist'] == 'false') { ?><p class='inlinemsg err'>File lampiran tidak ada, data tidak disimpan. Silakan coba lagi nanti.</p><?php } ?>
		<?php if (isset($_GET['upload_data']) && $_GET['upload_data'] == 'error') { ?><p class='inlinemsg err'>Gagal mengupload lampiran, data tidak disimpan. Silakan coba lagi nanti.</p><?php } ?>
		<?php if (isset($_GET['save_data']) && $_GET['save_data'] == 'error') { ?><p class='inlinemsg err'>Gagal menyimpan data. Silakan coba lagi nanti.</p><?php } ?>
		<?php if (isset($_GET['save_data']) && $_GET['save_data'] == 'success') { ?><p class='inlinemsg suc'>Data berhasil disimpan.</p><?php } ?>
		<?php
		if (isset($_GET['delete_bku'])) {
			if ($_GET['delete_bku'] == 'error') {
		?>
			<p class='inlinemsg err'>Gagal menghapus data. Silakan coba lagi nanti.</p>
		<?php
			} else if ($_GET['delete_bku'] == 'success') {
		?>
			<p class='inlinemsg suc'>Data berhasil dihapus.</p>
		<?php
			}
		}
		?>
			<form id='input-bku' name='input-bku' action='tambah-data-buku-kas-umum.php' method='POST' enctype='multipart/form-data'>
				<table>
					<tbody>
						<tr style='display: none; visibility: hidden;'>
							<td style='width: 200px;'><label for='lpj_id'><b>LPJ ID:</b></label></td>
							<td>: <input id='lpj_id' name='lpj_id' type='text' value='<?php echo $lpj_id ?>' style='width:40px;' /></td>
						</tr>
						<tr>
							<td style='width: 200px;'><label for='BKU-NO-URUT'><b>Nomor Urut</b></label></td>
							<td>: <b><input id='BKU-NO-URUT' name='BKU-NO-URUT' type='text' value='' style='width:40px;' /></b></td>
						</tr>
						<tr>
							<td style='width: 200px;'><label for='BKU-TGL'>Tanggal</label></td>
							<td>: <input id='BKU-TGL' name='BKU-TGL' type='date' value='' style='width:150px;' /></td>
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
									<option value='<?php echo $kode_rek ?>'><?php echo $kode_rek_titik." / ".$nama_uraian ?></option>
							<?php
								}
							?>
								</select>
							</td>
						</tr>
						<tr>
							<td style='width: 200px;'><label for='BKU-URAIAN'>Uraian</label></td>
							<td>: <input id='BKU-URAIAN' name='BKU-URAIAN' type='text' value='Dibayar belanja [Uraian belanja] tgl. [Tanggal belanja]' style='width:400px;' /></td>
						</tr>
						<tr>
							<td style='width: 200px;'><label for='BKU-ATASNAMA'>Atas Nama</label></td>
							<td>: 
								<select id='BKU-ATASNAMA' name='BKU-ATASNAMA' style='width:300px;'>
									<option value=''>-- Pilih Pegawai --</option>
									<?php foreach ($data_peg as $val) { ?>
									<option value='<?php echo $val['PEG_NIP'] ?>'> <?php echo $val['PEG_NAMA'] ?> </option>
									<?php } ?>
								</select>
							</td>
						</tr>
						<tr>
							<td style='width: 200px;'><label for='BKU-PENERIMAAN'><b>Penerimaan</b></label></td>
							<td>: Rp&nbsp;&nbsp;&nbsp;<input id='BKU-PENERIMAAN' class='number bold' name='BKU-PENERIMAAN' type='text' value='' style='width:200px;' /></td>
						</tr>
						<tr>
							<td style='width: 200px;'><label for='BKU-PENGELUARAN'><b>Pengeluaran</b></label></td>
							<td>: Rp&nbsp;&nbsp;&nbsp;<input id='BKU-PENGELUARAN' class='number bold' name='BKU-PENGELUARAN' type='text' value='' style='width:200px;' /></td>
						</tr>
						<tr>
							<td></td>
							<td>: <label for='BKU-PAJAK-1'>PPN</label> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: Rp&nbsp;&nbsp;&nbsp;<input id='BKU-PAJAK-1' class='number bold' name='BKU-PAJAK-1' type='text' value='' style='width:200px;' /></td>
						</tr>
						<tr>
							<td></td>
							<td>: <label for='BKU-PAJAK-2'>PPh Psl. 21</label> : Rp&nbsp;&nbsp;&nbsp;<input id='BKU-PAJAK-2' class='number bold' name='BKU-PAJAK-2' type='text' value='' style='width:200px;' /></td>
						</tr>
						<tr>
							<td></td>
							<td>: <label for='BKU-PAJAK-3'>PPh Psl. 22</label> : Rp&nbsp;&nbsp;&nbsp;<input id='BKU-PAJAK-3' class='number bold' name='BKU-PAJAK-3' type='text' value='' style='width:200px;' /></td>
						</tr>
						<tr>
							<td></td>
							<td>: <label for='BKU-PAJAK-4'>PPh Psl. 23</label> : Rp&nbsp;&nbsp;&nbsp;<input id='BKU-PAJAK-4' class='number bold' name='BKU-PAJAK-4' type='text' value='' style='width:200px;' /></td>
						</tr>
						<tr>
							<td style='width: 200px;'><label for='BKU-LAMPIRAN'><b>Lampiran</b></label></td>
							<td>: <input id='BKU-LAMPIRAN' name='BKU-LAMPIRAN' type='file' /></td>
						</tr>
						<tr>
							<td><input id='clear' name='clear' class='for-form' type='button' value='Reset' onclick="javascript:$('#input-bku').each(function(){ this.reset(); });" /></td>
							<td><input id='submit-tambah-bku' name='submit-tambah-bku' class='for-form' type='submit' value='Tambah Data' /></td>
						</tr>
					</tbody>
				</table>
				<br>
			</form>
		</div>
	</div>
	<br><br>
	<?php } ?>
	<div class='subtitle'>
		<div class='title'><h2>Tabel Data Buku Kas Umum</h2></div>
	</div>
	<div class='sub-container'>
		<div class='cust-register'>
		<?php
			$query = "SELECT * FROM tb_buku_kas_umum WHERE LPJ_ID='$lpj_id' ORDER BY BKU_NO_URUT";
			$result = mysql_query($query);
			if (mysql_num_rows($result) > 0) {
		?>
			<table id='table' class='table data'>
				<thead>
					<tr>
						<th>No. Urut</th>
						<th>Tanggal</th>
						<th>Kode Rekening</th>
						<th>Uraian</th>
						<th>Atas Nama</th>
						<th>Penerimaan</th>
						<th>Pengeluaran</th>
						<th width='80'>Perintah</th>
				</thead>
				<tbody>
					<tr>
						<td></td>
						<td></td>
						<td><?php echo $kodering_lpj ?></td>
						<td><?php echo $nama_keg ?></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
			<?php
				$jml_penerimaan = 0;
				$jml_pengeluaran = 0;
				$jml_pajak = array(0, 0, 0, 0);
				
				$data_pajak = array();
				$res = mysql_query("SELECT * FROM TB_M_PAJAK");
				while ($fetch = mysql_fetch_array($res)) { $data_pajak[] = $fetch['NAMA_PAJAK']; }
				
				while ($val = mysql_fetch_array($result)) {
					if (!empty($val['BKU_ATAS_NAMA'])) {
						$nip = $val['BKU_ATAS_NAMA'];
						if (!empty($nip)) {
							$fetch = mysql_fetch_array(mysql_query("SELECT PEG_NAMA FROM TB_M_PEGAWAI WHERE PEG_NIP='$nip'"));
							$nama_peg = $fetch['PEG_NAMA'];
						}
					}
			?>
					<tr>
						<td><?php echo $val['BKU_NO_URUT'] ?></td>
						<td><?php echo date_format(date_create($val['BKU_TGL_SUB_KEG']), 'd/m/Y') ?></td>
						<td><?php echo $val['BKU_KODE_REK'] ?></td>
						<td><?php echo $val['BKU_URAIAN'] ?></td>
						<td><?php if (!empty($val['BKU_ATAS_NAMA'])) echo $nama_peg ?></td>
						<td style='text-align: right;'><?php if ($val['BKU_PENERIMAAN'] != NULL && $val['BKU_PENERIMAAN'] != 0) echo number_format($val['BKU_PENERIMAAN'], 0, ',', '.'); $jml_penerimaan += $val['BKU_PENERIMAAN']; ?></td>
						<td style='text-align: right;'><?php if ($val['BKU_PENGELUARAN'] != NULL && $val['BKU_PENGELUARAN'] != 0) echo number_format($val['BKU_PENGELUARAN'], 0, ',', '.'); $jml_pengeluaran += $val['BKU_PENGELUARAN'] ?></td>
						<td style='text-align: center;'><?php if ($val['BKU_KODE_REK'] != NULL) { ?><a class='ico ico-Detail' href='index.php?page=form-data-rekapitulasi&lpj_id=<?php echo $lpj_id ?>&bku_kr=<?php echo $val['BKU_KODE_REK'] ?>&bku_tahun=<?php echo substr($val['BKU_TGL_SUB_KEG'],0,4) ?>' title='Lihat data rekapitulasi pengeluaran per rincian objek untuk uraian ini'></a><?php } ?>&nbsp;
						<?php if($allowedit) { ?><a class='ico ico-Edit' href='index.php?page=form-edit-data-buku-kas-umum&lpj_id=<?php echo $lpj_id ?>&bku_id=<?php echo $val['BKU_ID'] ?>' title='Edit data Buku Kas Umum ini'></a><?php } else { ?><a class='ico ico-Edit disabled' title='Edit tidak dapat dilakukan'></a><?php } ?>&nbsp;
						<?php if($allowedit) { ?><a class='ico ico-Delete' href='hapus-data-buku-kas-umum.php?lpj_id=<?php echo $lpj_id ?>&bku_id=<?php echo $val['BKU_ID'] ?>' title='Hapus data Buku Kas Umum ini'></a><?php } else { ?><a class='ico ico-Delete disabled' title='Hapus tidak dapat dilakukan'></a><?php } ?></td>
					</tr>
			<?php
					if (!empty($val['BKU_PAJAK'])) {
						$pajak = explode("|", $val['BKU_PAJAK']);
						
						for ($i=0;$i<4;$i++) {
							if (!empty($pajak[$i])) {
			?>
					<tr>
						<td></td><td></td><td></td>
						<td><?php echo $data_pajak[$i] ?></td>
						<td></td>
						<td style='text-align: right;'>
							<?php echo number_format($pajak[$i], 0, ',', '.'); 
							$jml_penerimaan += floatval($pajak[$i]);
							$jml_pajak[$i] += floatval($pajak[$i]); ?>
						</td>
						<td></td><td></td>
					</tr>
			<?php
							}
						}
					}
				}
			?>
					<tr>
						<td></td><td></td><td></td>
						<td><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Jumlah</b></td><td></td>
						<td style='text-align: right;'><b><?php echo number_format($jml_penerimaan, 0, ',', '.') ?></b></td>
						<td style='text-align: right;'><b><?php echo number_format($jml_pengeluaran, 0, ',', '.') ?></b></td>
						<td></td>
					</tr>
				<?php
				for ($i=0;$i<4;$i++) {
				?>
					<tr>
						<td></td><td></td><td></td>
						<td><b><?php echo $data_pajak[$i] ?></b></td><td></td>
						<td></td>
						<td style='text-align: right;'><b><?php if ($jml_pajak[$i] != 0) { echo number_format($jml_pajak[$i], 0, ',', '.');} else { echo '-'; } ?></b></td>
						<td></td>
					</tr>
				<?php
				}
				?>
					<tr>
						<td></td><td></td><td></td>
						<td><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Jumlah semua</b></td><td></td>
						<td style='text-align: right;'><b><?php echo number_format($jml_penerimaan, 0, ',', '.') ?></b></td>
						<td style='text-align: right;'><b><?php echo number_format($jml_pengeluaran + array_sum($jml_pajak), 0, ',', '.') ?></b></td>
						<td></td>
					</tr>
					<tr>
						<td></td><td></td><td></td>
						<td><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Sisa saldo</b></td><td></td><td></td>
						<td style='text-align: right;'><b><?php echo number_format($jml_penerimaan - ($jml_pengeluaran + array_sum($jml_pajak)), 0, ',', '.') ?></b></td>
						<td></td>
					</tr>
				</tbody>
			</table>
		<?php
			} else {
		?>
			<p>Data Buku Kas Umum kosong!</p>
		<?php
			}
		?>
		</div>
	</div>
</div>

<!-- JS -->
<script type='text/javascript' src='js/jquery.number.js'></script>
<script type='text/javascript'>
	$('.number').number(true);
</script>
