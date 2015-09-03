<?php
	require "connect.php";
	require "functions.php";
	
	if (!isset($_GET['lpj_id'])) {
		header("location:index.php?page=home");
	}
	
	$lpj_id = $_GET['lpj_id'];
	$nama_kegiatan = get_nama_lpj($lpj_id);
	
	$kodering_belanja = "0502";
	
	$query       = "SELECT * FROM tb_data_lpj WHERE LPJ_ID='$lpj_id'";
	$result      = mysql_query($query);
	$data_uraian = array();
	while ($fetch = mysql_fetch_array($result)) {
		$data_uraian[] = $fetch;
	}
	
	$query		= "SELECT * FROM tb_buku_kas_umum WHERE LPJ_ID='$lpj_id' AND BKU_KODE_REK LIKE '$kodering_belanja%' ORDER BY BKU_NO_URUT";
	$result		= mysql_query($query);
	$data_bku_sptjbl	= array();
	while ($fetch = mysql_fetch_array($result)) {
		$data_bku_sptjbl[] = $fetch;
	}
?>


<div class='sub-content' id='data-utama-lpj'>
	<?php include ("menu-dokumen-lpj.php"); ?>
	<br><br>
	<div class='subtitle'>
		<div class='title'><h2>Surat Pertanggungjawaban Belanja Langsung</h2></div>
	</div>
	<div class='sub-container'>
		<div class='cust-register'>
			<form id='input-sptj-bl' name='input-sptj-bl' action='tambah-data-sptj-bl.php' method='POST'>
				<table>
					<tbody>
					<?php
						foreach ($data_uraian as $val) {
					?>
						<tr>
							<td style='width: 200px;'><label><b>SKPD</b></label></td>
							<td>: <input name='SPTJBL-SKPD' type='text' value='<?php echo $val['LPJ_ORGANISASI']; ?>' style='width:300px;' readonly /></td>
						</tr>
						<tr>
							<td style='width: 200px;'><label><b>Unit Kerja</b></label></td>
							<td>: <input name='SPTJBL-UNIT-KERJA' type='text' value='<?php echo $val['LPJ_UNIT_KERJA']; ?>' style='width:300px;' readonly /></td>
						</tr>
						<tr>
							<td style='width: 200px;'><label><b>Program</b></label></td>
							<td>: <input name='SPTJBL-PROGRAM' type='text' value='<?php echo $val['LPJ_PROGRAM']; ?>' style='width:400px;' readonly /></td>
						</tr>
						<tr>
							<td style='width: 200px;'><label><b>Kegiatan</b></label></td>
							<td>: <input name='SPTJBL-KEGIATAN' type='text' value='<?php echo $val['LPJ_NAMA_KEG']; ?>' style='width:500px;' readonly /></td>
						</tr>
						<?php } ?>
						<tr>
							<td style='width: 200px;'><label><b>No. dan Tgl. DPPA</b></label></td>
							<td>: <input name='SPTJBL-NO-DPPA' type='text' value='<?php echo $val['LPJ_NO_DPPA']; ?> ;' style='width:110px;' readonly />
								&nbsp;<input name='SPTJBL-TGL-DPPA' type='date' value='<?php echo $val['LPJ_TGL_DPPA']; ?>' style='width:150px;' readonly />
							</td>
						</tr>
						<tr>
							<td style='width: 200px;'><label><b>Jenis Belanja</b></label></td>
							<td>: <input name='SPTJBL-JENIS-BL' type='text' value='<?php 

							$result = mysql_query("SELECT URAIAN_NAMA FROM TB_M_URAIAN WHERE KODE_REK = (SELECT KODE_REK FROM TB_LPJ_BNDHRA_PNGLUARAN_PMBNTU WHERE LPJ_ID=$lpj_id AND KODE_REK LIKE '0502__')");
							while ($fetch = mysql_fetch_array($result)) { echo ucwords(strtolower($fetch['URAIAN_NAMA'])).", "; }
							
							?>' style='width:400px;' readonly /></td>
						</tr>
						<tr>
							<td style='width: 200px;'><label><b>Kode Rekening</b></label></td>
							<td>: <input name='SPTJBL-KODERING' type='text' value='<?php echo $val['LPJ_KODERING']; ?>' style='width:200px;' readonly /></td>
						</tr>
						<!--tr>
							<td><input id='clear' name='clear' class='for-form' type='button' value='Reset' onclick="javascript:$('#input-sptj-bl').each(function(){ this.reset(); });" /></td>
							<td><input id='submit-tambah-data-sptjbl' name='submit-tambah-data-sptjbl' class='for-form' type='submit' value='Tambah Data' /></td>
						</tr-->
					</tbody>
				</table>
				<br>
			</form>
		</div>
	</div>
	<br><br>
	<div class='subtitle'>
		<div class='title'><h2>Tabel Data Surat Pertanggungjawaban Belanja Langsung</h2></div>
	</div>
	<div class='sub-container'>
		<div class='cust-register'>
		<?php
			$i=1;
			if (count($data_bku_sptjbl) > 0) {
		?>
			<table border='1' id='table' class='table data'>
				<thead>
					<tr>
						<th rowspan='2'>No.</th>
						<th rowspan='2'>Penerima</th>
						<th rowspan='2'>Uraian</th>
						<th colspan='2'>Bukti Kas</th>
						<th rowspan='2'>Rp.</th>
					</tr>
					<tr>
						<th>No. Urut</th>
						<th>Tanggal</th>
					</tr>
				</thead>
				<tbody>
			<?php
			
				foreach ($data_bku_sptjbl as $val) {
			?>
					<tr>
						<td><?php echo $i; ?></td>
						<td><?php echo get_nama_pegawai($val['BKU_ATAS_NAMA']) ?></td>
						<td><?php echo $val['BKU_URAIAN'] ?></td>
						<td class='center'><?php echo $val['BKU_NO_URUT'] ?></td>
						<td><?php echo date_format(date_create($val['BKU_TGL_SUB_KEG']), 'd/m/Y') ?></td>
						<td style='text-align: right;'><?php if (!empty($val['BKU_PENGELUARAN'])) echo number_format($val['BKU_PENGELUARAN'], 0, ',', '.') ?></td>
					</tr>
			<?php
				$i=$i+1;
				}
			} else {
			?>
					<p>Data Surat Pernyataan Tanggung Jawab Belanja Langsung kosong!</p>
			<?php
			}
			?>
				</tbody>
			</table>			
		</div>
	</div>
</div>
