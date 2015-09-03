<?php
	require "connect.php";
	require "functions.php";
	
	if (!isset($_GET['bku_kr'])&&!isset($_GET['lpj_id'])) {
		header("location:index.php?page=home");
	}
	
	$bku_kr = $_GET['bku_kr'];
	$lpj_id = $_GET['lpj_id'];
	$bku_tahun = $_GET['bku_tahun'];
	
	$nama_kegiatan = get_nama_lpj($lpj_id);
	
	$kodering_belanja = "0502";
	
	$query       = "SELECT * FROM tb_data_lpj WHERE LPJ_ID='$lpj_id'";
	$result      = mysql_query($query);
	$data_lpj    = array();
	while ($fetch = mysql_fetch_array($result)) {
		$data_lpj[] = $fetch;
	}
?>


<div class='sub-content' id='data-utama-lpj'>
	<?php include ("menu-dokumen-lpj.php"); ?>
	<br><br>
	<div class='subtitle'>
		<div class='title'><h2>Rekapitulasi</h2></div>
	</div>
	<div class='sub-container'>
		<div class='cust-register'>
			<form id='input-lpj-belanja' name='input-lpj-belanja' action='tambah-data-lpj-belanja.php' method='POST'>
				<table>
					<tbody>
					<?php
						foreach ($data_lpj as $val) {
					?>
						<tr>
							<td style='width: 200px;'><label><b>SKPD</b></label></td>
							<td>: <input id='LPJBL-JML-ANGGARAN' class='number bold' name='LPJBL-JML-ANGGARAN' type='text' value='<?php echo $val['LPJ_ORGANISASI']; ?>' style='width:300px;' readonly /></td>
						</tr>
						<tr>
							<td style='width: 200px;'><label><b>Unit Kerja</b></label></td>
							<td>: <input id='LPJBL-JML-ANGGARAN' class='number bold' name='LPJBL-JML-ANGGARAN' type='text' value='<?php echo $val['LPJ_UNIT_KERJA']; ?>' style='width:300px;' readonly /></td>
						</tr>
					<?php } ?>
						<tr>
							<td style='width: 200px;'><label><b>Kode Rekening</b></label></td>
							<td>: <input id='LPJBL-JML-ANGGARAN' class='number bold' name='LPJBL-JML-ANGGARAN' type='text' value='<?php echo $bku_kr; ?>' style='width:150px;' readonly /></td>
						</tr>
					<?php   $result		= mysql_query("SELECT URAIAN_NAMA FROM tb_m_uraian WHERE KODE_REK='$bku_kr'");
							while ($ab = mysql_fetch_array($result)) {
								$kr = $ab['URAIAN_NAMA'];
							} 
					?>
						<tr>
							<td style='width: 200px;'><label><b>Nama Rekening</b></label></td>
							<td>: <input  type='text' value='<?php echo $kr; ?>' style='width:500px;' readonly /></td>
						</tr>
						<tr>
							<td style='width: 200px;'><label><b>Tahun Anggaran</b></label></td>
							<td>: <input id='LPJBL-JML-ANGGARAN' class='number bold' name='LPJBL-JML-ANGGARAN' type='text' value='<?php echo $bku_tahun; ?>' style='width:150px;' readonly /></td>
						</tr>
					</tbody>
				</table>
				<br>
			</form>
		</div>
	</div>
	<br><br>
	<div class='subtitle'>
		<div class='title'><h2>Tabel Data Rekapitulasi Pengeluaran Per Rincian Objek</h2></div>
	</div>
	<div class='sub-container'>
		<div class='cust-register'>
			<table border='1' class='table' width='100%'>
				<thead>
					<tr>
						<th rowspan='2'>No Urut</th>
						<th rowspan='2'>Tanggal</th>
						<th rowspan='2'>Uraian</th>
						<th colspan='3'>Pengeluaran</th>
					</tr>
					<tr>
						<th>LS</th>
						<th>UP/GU/TU</th>
						<th>Jumlah</th>
					</tr>
				</thead>
				<tbody>
			<?php
				$query		= "SELECT BKU_NO_URUT,BKU_TGL_SUB_KEG,BKU_PENGELUARAN,BKU_URAIAN,BKU_ATAS_NAMA,
									  BPP_LPJ_LS_GAJI,BPP_LPJ_LS_BRNG_JASA,BPP_LPJ_UP_GU_TU
										FROM 
									  TB_BUKU_KAS_UMUM,TB_LPJ_BNDHRA_PNGLUARAN_PMBNTU 
									    WHERE TB_LPJ_BNDHRA_PNGLUARAN_PMBNTU.LPJ_ID=$lpj_id AND TB_BUKU_KAS_UMUM.BKU_KODE_REK='$bku_kr' AND TB_LPJ_BNDHRA_PNGLUARAN_PMBNTU.KODE_REK='$bku_kr' 
										ORDER BY TB_BUKU_KAS_UMUM.BKU_NO_URUT";
				$result		= mysql_query($query);
				while ($td = mysql_fetch_array($result)) {
			?>
					<tr>
						<td><?php echo $td['BKU_NO_URUT']; ?></td>
						<td><?php echo date_format(date_create($td['BKU_TGL_SUB_KEG']), 'd/m/Y'); ?></td>
						<td><?php echo $td['BKU_URAIAN'].' an. '.get_nama_pegawai($td['BKU_ATAS_NAMA']); ?></td>
						<td><?php echo $td['BPP_LPJ_LS_GAJI'] + $td['BPP_LPJ_LS_BRNG_JASA']; ?></td>
						<td><?php echo number_format($td['BKU_PENGELUARAN'], 0, ',', '.'); ?></td>
						<td><?php echo number_format($td['BKU_PENGELUARAN'], 0, ',', '.'); ?></td>
					</tr>
			<?php
				}
			?>
				</tbody>
			</table>

		</div>
	</div>
</div>