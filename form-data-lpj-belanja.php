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
	$waktu_keg_lpj = get_waktu_keg_lpj($lpj_id);
	
	$kodering_belanja = "0502";

	$query       = "SELECT * FROM TB_M_URAIAN WHERE KODE_REK LIKE '$kodering_belanja%'";
	$result      = mysql_query($query);
	$data_uraian = array();
	while ($fetch = mysql_fetch_array($result)) {
		$data_uraian[] = $fetch;
	}
?>


<div class='sub-content' id='data-utama-lpj'>
	<?php include ("menu-dokumen-lpj.php"); ?>
	<br><br>
	<?php if($allowedit) { ?>
	<div class='subtitle'>
		<div class='title'><h2>Input Data LPJ Bendahara Pengeluaran Pembantu (LPJ Belanja)</h2></div>
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
			<p class='inlinemsg suc'>Data berhasil disimpan.</p>
		<?php
			}
		}
		if (isset($_GET['delete_bpp'])) {
			if ($_GET['delete_bpp'] == 'error') {
		?>
			<p class='inlinemsg err'>Gagal menghapus data. Silakan coba lagi nanti.</p>
		<?php
			} else if ($_GET['delete_bpp'] == 'success') {
		?>
			<p class='inlinemsg suc'>Data berhasil dihapus.</p>
		<?php
			}
		}
		?>
			<form id='input-lpj-belanja' name='input-lpj-belanja' action='tambah-data-lpj-belanja.php' method='POST'>
				<table>
					<tbody>
						<tr style='display: none; visibility: hidden;'>
							<td>ID LPJ</td>
							<td><input id='ID-LPJ' name='ID-LPJ' type='text' value='<?php echo $lpj_id ?>' readonly /></td>
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
									<option value='<?php echo $kode_rek ?>'><?php echo $kode_rek_titik." / ".$nama_uraian ?></option>
							<?php
								}
							?>
								</select>
							</td>
						</tr>
						<tr>
							<td style='width: 200px;'><label for='LPJBL-JML-ANGGARAN'>Jumlah Anggaran</label></td>
							<td>
								: Rp&nbsp;&nbsp;&nbsp;<input id='LPJBL-JML-ANGGARAN' class='number bold' name='LPJBL-JML-ANGGARAN' type='text' value='' style='width:200px;' />
							</td>
						</tr>
						<tr>
							<td style='width: 200px;'><label for='LPJBL-LPJ-LS-GAJI'>LPJ - LS Gaji</label></td>
							<td>
								: Rp&nbsp;&nbsp;&nbsp;<input id='LPJBL-LPJ-LS-GAJI' class='JUMLAHKAN number' name='LPJBL-LPJ-LS-GAJI' type='text' value='' style='width:200px;' />
							</td>
						</tr>
						<tr>
							<td style='width: 200px;'><label for='LPJBL-LPJ-LS-BARANG-DAN-JASA'>LPJ - LS Barang dan Jasa</label></td>
							<td>
								: Rp&nbsp;&nbsp;&nbsp;<input id='LPJBL-LPJ-LS-BARANG-DAN-JASA' class='JUMLAHKAN number' name='LPJBL-LPJ-LS-BARANG-DAN-JASA' type='text' value='' style='width:200px;' />
							</td>
						</tr>
						<tr>
							<td style='width: 200px;'><label for='LPJBL-LPJ-UP-GU-TU'>LPJ UP/GU/TU</label></td>
							<td>
								: Rp&nbsp;&nbsp;&nbsp;<input id='LPJBL-LPJ-UP-GU-TU' class='JUMLAHKAN number' name='LPJBL-LPJ-UP-GU-TU' type='text' value='' style='width:200px;' />
							</td>
						</tr>
						<tr>
							<td></td>
							<td><input id='LPJBL-SUBMIT' name='LPJBL-SUBMIT' class='for-form' type='submit' value='Tambah Data' /></td>
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
		<div class='title'><h2>Tabel Data LPJ Bendahara Pengeluaran Pembantu (LPJ Belanja)</h2></div>
	</div>
	<div class='sub-container h-scrollbar'>
		<div class='cust-register'>
		<?php
			$tot_jml_angg = 0;
						
			$tot_sd_bln_lalu_ls_gaji = 0;
			$tot_bln_ini_ls_gaji = 0;
			$tot_sd_bln_ini_ls_gaji = 0;
			
			$tot_sd_bln_lalu_ls_brg_jasa = 0;
			$tot_bln_ini_ls_brg_jasa = 0;
			$tot_sd_bln_ini_ls_brg_jasa = 0;
			
			$tot_sd_bln_lalu_up_gu_tu = 0;
			$tot_bln_ini_up_gu_tu = 0;
			$tot_sd_bln_ini_up_gu_tu = 0;
			
			$tot_jml_ls_dan_up = 0;
			$tot_sisa_pg_angg = 0;
			
			$query = "SELECT * FROM TB_LPJ_BNDHRA_PNGLUARAN_PMBNTU WHERE LPJ_ID='$lpj_id' ORDER BY KODE_REK";
			$result = mysql_query($query);
			if (mysql_num_rows($result) > 0) {
		?>
			<table border='1' class='table nowrap'>
				<thead>
					<tr>
						<th rowspan='2'>Kodering</th>
						<th rowspan='2'>Uraian</th>
						<th rowspan='2'>Jumlah Anggaran</th>
						<th colspan='3'>LPJ - LS Gaji</th>
						<th colspan='3'>LPJ - LS Barang &amp; Jasa</th>
						<th colspan='3'>LPJ UP/GU/TU</th>
						<th rowspan='2'>Jumlah (LS+UP/GU/TU)</th>
						<th rowspan='2'>Sisa Pagu Anggaran</th>
						<th rowspan='2' colspan='2'>Operasi</th>
					</tr>
					<tr>
						<th>s/d bulan lalu</th>
						<th>bulan ini</th>
						<th>s/d bulan ini</th>
						<th>s/d bulan lalu</th>
						<th>bulan ini</th>
						<th>s/d bulan ini</th>
						<th>s/d bulan lalu</th>
						<th>bulan ini</th>
						<th>s/d bulan ini</th>
					</tr>
				</thead>
				<tbody>
			<?php
				while ($val = mysql_fetch_array($result)) {
					$kodering = $val['KODE_REK'];
					$query = "SELECT URAIAN_NAMA FROM TB_M_URAIAN WHERE KODE_REK='$kodering'";
					$result_u = mysql_query($query);
					$fetch = mysql_fetch_array($result_u);
					
					$jml_angg = $val['BPP_JML_ANGGRN'];
					
					$sd_bln_lalu_ls_gaji = get_total_bln_lalu(1, $kodering, $waktu_keg_lpj);
					$bln_ini_ls_gaji = $val['BPP_LPJ_LS_GAJI'];
					$sd_bln_ini_ls_gaji = $sd_bln_lalu_ls_gaji + $bln_ini_ls_gaji;
					
					$sd_bln_lalu_ls_brg_jasa = get_total_bln_lalu(2, $kodering, $waktu_keg_lpj);
					$bln_ini_ls_brg_jasa = $val['BPP_LPJ_LS_BRNG_JASA'];
					$sd_bln_ini_ls_brg_jasa = $sd_bln_lalu_ls_brg_jasa + $bln_ini_ls_brg_jasa;
					
					$sd_bln_lalu_up_gu_tu = get_total_bln_lalu(3, $kodering, $waktu_keg_lpj);
					$bln_ini_up_gu_tu = $val['BPP_LPJ_UP_GU_TU'];
					$sd_bln_ini_up_gu_tu = $sd_bln_lalu_up_gu_tu + $bln_ini_up_gu_tu;
					
					$jml_ls_dan_up = $sd_bln_ini_ls_gaji + $sd_bln_ini_ls_brg_jasa + $sd_bln_ini_up_gu_tu;
					
					$sisa_pg_angg = $jml_angg - $jml_ls_dan_up;
					
					$kodering = rtrim(chunk_split($kodering, 2, "."), ".");
					$uraian = $fetch['URAIAN_NAMA'];
					
					if (strlen($val['KODE_REK']) == 6) {
						$tot_jml_angg += $jml_angg;
						
						$tot_sd_bln_lalu_ls_gaji += $sd_bln_lalu_ls_gaji;
						$tot_bln_ini_ls_gaji += $bln_ini_ls_gaji;
						$tot_sd_bln_ini_ls_gaji += $sd_bln_ini_ls_gaji;
						
						$tot_sd_bln_lalu_ls_brg_jasa += $sd_bln_lalu_ls_brg_jasa;
						$tot_bln_ini_ls_brg_jasa += $bln_ini_ls_brg_jasa;
						$tot_sd_bln_ini_ls_brg_jasa += $sd_bln_ini_ls_brg_jasa;
						
						$tot_sd_bln_lalu_up_gu_tu += $sd_bln_lalu_up_gu_tu;
						$tot_bln_ini_up_gu_tu += $bln_ini_up_gu_tu;
						$tot_sd_bln_ini_up_gu_tu += $sd_bln_ini_up_gu_tu;
						
						$tot_jml_ls_dan_up += $jml_ls_dan_up;
						$tot_sisa_pg_angg += $sisa_pg_angg;
					}
			?>
					<tr>
						<td><?php echo $kodering; ?></td>
						<td><?php echo $uraian; ?></td>
						<!-- Jumlah Anggaran -->
						<td style='text-align: right;'><?php if ($jml_angg != NULL && $jml_angg != 0) { echo number_format($jml_angg, 0, ',', '.'); } else { echo '-'; } ?></td>
						<!-- LPJ - LS Gaji -->
						<td style='text-align: right;'><?php if ($sd_bln_lalu_ls_gaji != NULL && $sd_bln_lalu_ls_gaji != 0) { echo number_format($sd_bln_lalu_ls_gaji, 0, ',', '.'); } else { echo '-'; } ?></td>
						<td style='text-align: right;'><?php if ($bln_ini_ls_gaji != NULL && $bln_ini_ls_gaji != 0) { echo number_format($bln_ini_ls_gaji, 0, ',', '.'); } else { echo '-'; } ?></td>
						<td style='text-align: right;'><?php if ($sd_bln_ini_ls_gaji != NULL && $sd_bln_ini_ls_gaji != 0) { echo number_format($sd_bln_ini_ls_gaji, 0, ',', '.'); } else { echo '-'; } ?></td>
						<!-- LPJ - LS Barang dan Jasa -->
						<td style='text-align: right;'><?php if ($sd_bln_lalu_ls_brg_jasa != NULL && $sd_bln_lalu_ls_brg_jasa != 0) { echo number_format($sd_bln_lalu_ls_brg_jasa, 0, ',', '.'); } else { echo '-'; } ?></td>
						<td style='text-align: right;'><?php if ($bln_ini_ls_brg_jasa != NULL && $bln_ini_ls_brg_jasa != 0) { echo number_format($bln_ini_ls_brg_jasa, 0, ',', '.'); } else { echo '-'; } ?></td>
						<td style='text-align: right;'><?php if ($sd_bln_ini_ls_brg_jasa != NULL && $sd_bln_ini_ls_brg_jasa != 0) { echo number_format($sd_bln_ini_ls_brg_jasa, 0, ',', '.'); } else { echo '-'; } ?></td>
						<!-- LPJ - UP/GU/TU -->
						<td style='text-align: right;'><?php if ($sd_bln_lalu_up_gu_tu != NULL && $sd_bln_lalu_up_gu_tu != 0) { echo number_format($sd_bln_lalu_up_gu_tu, 0, ',', '.'); } else { echo '-'; } ?></td>
						<td style='text-align: right;'><?php if ($bln_ini_up_gu_tu != NULL && $bln_ini_up_gu_tu != 0) { echo number_format($bln_ini_up_gu_tu, 0, ',', '.'); } else { echo '-'; } ?></td>
						<td style='text-align: right;'><?php if ($sd_bln_ini_up_gu_tu != NULL && $sd_bln_ini_up_gu_tu != 0) { echo number_format($sd_bln_ini_up_gu_tu, 0, ',', '.'); } else { echo '-'; } ?></td>
						<!-- Jumlah (LS + UP/GU/TU) -->
						<td style='text-align: right;'><?php if ($jml_ls_dan_up != NULL && $jml_ls_dan_up != 0) { echo number_format($jml_ls_dan_up, 0, ',', '.'); } else { echo '-'; } ?></td>
						<!-- Sisa Pagu Anggaran -->
						<td style='text-align: right;'><?php if ($sisa_pg_angg != NULL && $sisa_pg_angg != 0) { echo number_format($sisa_pg_angg, 0, ',', '.'); } else { echo '-'; } ?></td>
						<td><?php if($allowedit) { ?><a href='index.php?page=form-edit-data-lpj-belanja&lpj_id=<?php echo $lpj_id ?>&bpp_id=<?php echo $val['BPP_ID'] ?>'>Edit</a><?php } else { ?>Edit<?php } ?></td>
						<td><?php if($allowedit) { ?><a href='hapus-data-lpj-belanja.php?lpj_id=<?php echo $lpj_id ?>&bpp_id=<?php echo $val['BPP_ID'] ?>'>Hapus</a><?php } else { ?>Hapus<?php } ?></td>
					</tr>
			<?php
				}
			?>
					<tr>
						<td><b>Jumlah</b></td>
						<td></td>
						<td style='text-align: right;'><b><?php if ($tot_jml_angg != NULL && $tot_jml_angg != 0) echo number_format($tot_jml_angg, 0, ',', '.'); else echo '-'; ?></b></td>
						<td style='text-align: right;'><b><?php if ($tot_sd_bln_lalu_ls_gaji != NULL && $tot_sd_bln_lalu_ls_gaji != 0) echo number_format($tot_sd_bln_lalu_ls_gaji, 0, ',', '.'); else echo '-'; ?></b></td>
						<td style='text-align: right;'><b><?php if ($tot_bln_ini_ls_gaji != NULL && $tot_bln_ini_ls_gaji != 0) echo number_format($tot_bln_ini_ls_gaji, 0, ',', '.'); else echo '-'; ?></b></td>
						<td style='text-align: right;'><b><?php if ($tot_sd_bln_ini_ls_gaji != NULL && $tot_sd_bln_ini_ls_gaji != 0) echo number_format($tot_sd_bln_ini_ls_gaji, 0, ',', '.'); else echo '-'; ?></b></td>
						<td style='text-align: right;'><b><?php if ($tot_sd_bln_lalu_ls_brg_jasa != NULL && $tot_sd_bln_lalu_ls_brg_jasa != 0) echo number_format($tot_sd_bln_lalu_ls_brg_jasa, 0, ',', '.'); else echo '-'; ?></b></td>
						<td style='text-align: right;'><b><?php if ($tot_bln_ini_ls_brg_jasa != NULL && $tot_bln_ini_ls_brg_jasa != 0) echo number_format($tot_bln_ini_ls_brg_jasa, 0, ',', '.'); else echo '-'; ?></b></td>
						<td style='text-align: right;'><b><?php if ($tot_sd_bln_ini_ls_brg_jasa != NULL && $tot_sd_bln_ini_ls_brg_jasa != 0) echo number_format($tot_sd_bln_ini_ls_brg_jasa, 0, ',', '.'); else echo '-'; ?></b></td>
						<td style='text-align: right;'><b><?php if ($tot_sd_bln_lalu_up_gu_tu != NULL && $tot_sd_bln_lalu_up_gu_tu != 0) echo number_format($tot_sd_bln_lalu_up_gu_tu, 0, ',', '.'); else echo '-'; ?></b></td>
						<td style='text-align: right;'><b><?php if ($tot_bln_ini_up_gu_tu != NULL && $tot_bln_ini_up_gu_tu != 0) echo number_format($tot_bln_ini_up_gu_tu, 0, ',', '.'); else echo '-'; ?></b></td>
						<td style='text-align: right;'><b><?php if ($tot_sd_bln_ini_up_gu_tu != NULL && $tot_sd_bln_ini_up_gu_tu != 0) echo number_format($tot_sd_bln_ini_up_gu_tu, 0, ',', '.'); else echo '-'; ?></b></td>
						<td style='text-align: right;'><b><?php if ($tot_jml_ls_dan_up != NULL && $tot_jml_ls_dan_up != 0) echo number_format($tot_jml_ls_dan_up, 0, ',', '.'); else echo '-'; ?></b></td>
						<td style='text-align: right;'><b><?php if ($tot_sisa_pg_angg != NULL && $tot_sisa_pg_angg != 0) echo number_format($tot_sisa_pg_angg, 0, ',', '.'); else echo '-'; ?></b></td>
						<td></td><td></td>
					</tr>
				</tbody>
			</table>
		<?php
			} else {
		?>
			<p>Data LPJ Bendahara Pengeluaran Pembantu kosong!</p>
		<?php
			}
		?>
		</div>
	</div>
</div>

<!-- Additional JS -->
<script type='text/javascript' src='js/jquery.number.js'></script>
<script type='text/javascript'>
	$('.number').number(true);
</script>