<?php

include ("functions.php");
if (isset($_GET['lpj_id']) && (get_status_lpj($_GET['lpj_id']) == '2' || get_status_lpj($_GET['lpj_id']) == '3')) {

	$lpj_id = $_GET['lpj_id'];
	
	require "connect.php";
	
	// Logo cover
	$logo = 'images/logo/prov_jabar.png';
	
	// Data Utama LPJ
	$query = "SELECT * FROM TB_DATA_LPJ WHERE LPJ_ID='$lpj_id'";
	$result = mysql_query($query);
	$data_lpj = mysql_fetch_array($result);
	
	$waktu_keg_lpj = $data_lpj['LPJ_WAKTU_KEG'];
	$waktu_kegiatan = explode("-", $data_lpj['LPJ_WAKTU_KEG']);
	$id_bulan = $waktu_kegiatan[0];
	
	$kode_rek_lpj   = $data_lpj['LPJ_KODERING'];
	$nama_kegiatan  = $data_lpj['LPJ_NAMA_KEG'];
	$bulan			= get_nama_bulan($id_bulan);
	$tahun 			= $waktu_kegiatan[1];
	$nama_organisasi= $data_lpj['LPJ_ORGANISASI'];
	$nama_unit_kerja= $data_lpj['LPJ_UNIT_KERJA'];
	$nama_program	= $data_lpj['LPJ_PROGRAM'];
	$nomor_dppa		= $data_lpj['LPJ_NO_DPPA'];
	$tgl_dppa		= intval(substr($data_lpj['LPJ_TGL_DPPA'], 8, 2)).' '.get_nama_bulan(substr($data_lpj['LPJ_TGL_DPPA'], 5, 2)).' '.substr($data_lpj['LPJ_TGL_DPPA'], 0, 4);
	$tgl_update		= date_format(date_create($data_lpj['LPJ_TGL_UPDATE']), 'j').' '.$bulan.' '.$tahun;
	
	// Data LPJ Belanja
	$query = "SELECT * FROM TB_LPJ_BNDHRA_PNGLUARAN_PMBNTU WHERE LPJ_ID='$lpj_id'";
	$result = mysql_query($query);
	$lpj_belanja = array();
	while ($fetch = mysql_fetch_array($result)) { $lpj_belanja[] = $fetch; }

	// Data Penandatangan LPJ
	$query = "SELECT * FROM TB_PENANDATANGAN_LPJ WHERE LPJ_ID='$lpj_id'";
	$result = mysql_query($query);
	$data_ptd = mysql_fetch_array($result);
	
	$penyerah_nip	= nip_format($data_ptd['PENYERAH']);
	$penyerah 		= get_nama_pegawai($data_ptd['PENYERAH']);
	$penerima_nip	= nip_format($data_ptd['PENERIMA']);
	$penerima 		= get_nama_pegawai($data_ptd['PENERIMA']);
	$kuasa_pgg_angg_nip	= nip_format($data_ptd['KUASA_PNGGUNA_ANGGRN']);
	$kuasa_pgg_angg = get_nama_pegawai($data_ptd['KUASA_PNGGUNA_ANGGRN']);
	$bendahara_pp_nip	= nip_format($data_ptd['BNDHRA_PNGLUARAN_PMBNTU']);
	$bendahara_pp 	= get_nama_pegawai($data_ptd['BNDHRA_PNGLUARAN_PMBNTU']);
	$pejabat_ptekkeg_nip	= nip_format($data_ptd['PJBT_PLKSN_TKNS_KEG']);
	$pejabat_ptekkeg= get_nama_pegawai($data_ptd['PJBT_PLKSN_TKNS_KEG']);
	
	include("lib/mpdf/mpdf.php");

	/**
	  *  Pembuatan mPDF
	  *
	  *  parameter
	  *  1: mode
	  *  2: paper size, in pre-defined or array of width and height in millimeters
	  *  3: default font size
	  *  4: default font
	  *  5,6,7,8: page margin (left, right, top, bottom)
	  *  9, 10: margin (header, footer)
	  */
	$mpdf = new mPDF('c','A4','','',24,16,24,24); 
	$mpdf->SetDisplayMode('fullpage');
	$mpdf->list_indent_first_level = 0;	// 1 or 0 - whether to indent the first level of a list

	/**
	  *  Halaman Cover
	  */
	$cover = '
	<div style="border:4px solid black; border-radius: 40px; padding: 10mm; height: 100%">
		<table width="100%">
			<tbody>
				<tr>
					<td><img src="'.$logo.'" width="90"></td>
				</tr>
				<tr>
					<td style="padding-top: 20px; font-size: 140%; font-weight: bold;">PEMERINTAH PROVINSI JAWA BARAT</td>
				</tr>
				<tr>
					<td style="font-size: 140%; font-weight: bold;">SEKRETARIAT DAERAH</td>
				</tr>
				<tr>
					<td style="font-size: 12pt;">Jalan Diponegoro No. 22 Tlp. (022) 4232448; 4233347; 4230963<br>Bandung 40115</td>
				</tr>
				<tr>
					<td style="height: 30mm;"></td>
				</tr>
				<tr>
					<td style="font-size: 140%; font-weight: bold;">LAPORAN PERTANGGUNG JAWABAN<br>(LPJ)</td>
				</tr>
				<tr>
					<td style="padding-top: 10px; font-size: 100%; font-weight: bold;">BULAN&nbsp;&nbsp;&nbsp;&nbsp;'.strtoupper($bulan).' '.$tahun.'</td>
				</tr>
				<tr>
					<td style="height: 10mm;"></td>
				</tr>
				<tr>
					<td style="font-size: 140%; font-weight: bold;">'.$nama_kegiatan.'</td>
				</tr>
				<tr>
					<td style="height: 70mm;"></td>
				</tr>
				<tr>
					<td style="font-size: 140%; font-weight: bold;">BIRO PELAYANAN SOSIAL DASAR<br>BAGIAN PENDIDIKAN DAN KEBUDAYAAN</td>
				</tr>
			</tbody>
		</table>
	</div>
	';

	/**
	  *  Halaman Laporan Pertanggung Jawaban Bendahara Pengeluaran Pemnbantu
	  */
	
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
		$tabel_bpp = '';
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
			
			$kodering = chunk_split($kodering, 2, ".");
			
			$tabel_bpp = $tabel_bpp.'<tr><td style="text-align: left;">'.$kodering.'</td>';
			$uraian = $fetch['URAIAN_NAMA'];
			$tabel_bpp = $tabel_bpp.'<td style="text-align: left;">'.$uraian.'</td>';
			
			if ($jml_angg != NULL && $jml_angg != 0) {$bpp_jml_angg= number_format($val['BPP_JML_ANGGRN'], 0, ',', '.'); } else { $bpp_jml_angg = '-'; }
			
			if ($sd_bln_lalu_ls_gaji != NULL && $sd_bln_lalu_ls_gaji != 0) {$bpp_sd_bln_lalu_ls_gaji= number_format($sd_bln_lalu_ls_gaji, 0, ',', '.'); } else { $bpp_sd_bln_lalu_ls_gaji = '-'; }
			if ($bln_ini_ls_gaji != NULL && $bln_ini_ls_gaji != 0) {$bpp_bln_ini_ls_gaji= number_format($bln_ini_ls_gaji, 0, ',', '.'); } else { $bpp_bln_ini_ls_gaji = '-'; }
			if ($sd_bln_ini_ls_gaji != NULL && $sd_bln_ini_ls_gaji != 0) {$bpp_sd_bln_ini_ls_gaji= number_format($sd_bln_ini_ls_gaji, 0, ',', '.'); } else { $bpp_sd_bln_ini_ls_gaji = '-'; }
			
			if ($sd_bln_lalu_ls_brg_jasa != NULL && $sd_bln_lalu_ls_brg_jasa != 0) {$bpp_sd_bln_lalu_ls_brg_jasa= number_format($sd_bln_lalu_ls_brg_jasa, 0, ',', '.'); } else { $bpp_sd_bln_lalu_ls_brg_jasa = '-'; }
			if ($bln_ini_ls_brg_jasa != NULL && $bln_ini_ls_brg_jasa != 0) {$bpp_bln_ini_ls_brg_jasa= number_format($bln_ini_ls_brg_jasa, 0, ',', '.'); } else { $bpp_bln_ini_ls_brg_jasa = '-'; }
			if ($sd_bln_ini_ls_brg_jasa != NULL && $sd_bln_ini_ls_brg_jasa != 0) {$bpp_sd_bln_ini_ls_brg_jasa= number_format($sd_bln_ini_ls_brg_jasa, 0, ',', '.'); } else { $bpp_sd_bln_ini_ls_brg_jasa = '-'; }
			
			if ($sd_bln_lalu_up_gu_tu != NULL && $sd_bln_lalu_up_gu_tu != 0) {$bpp_sd_bln_lalu_up_gu_tu= number_format($sd_bln_lalu_up_gu_tu, 0, ',', '.'); } else { $bpp_sd_bln_lalu_up_gu_tu = '-'; }
			if ($bln_ini_up_gu_tu != NULL && $bln_ini_up_gu_tu != 0) {$bpp_bln_ini_up_gu_tu= number_format($bln_ini_up_gu_tu, 0, ',', '.'); } else { $bpp_bln_ini_up_gu_tu = '-'; }
			if ($sd_bln_ini_up_gu_tu != NULL && $sd_bln_ini_up_gu_tu != 0) {$bpp_sd_bln_ini_up_gu_tu= number_format($sd_bln_ini_up_gu_tu, 0, ',', '.'); } else { $bpp_sd_bln_ini_up_gu_tu = '-'; }
			
			if ($jml_ls_dan_up != NULL && $jml_ls_dan_up != 0) {$bpp_jml_ls_dan_up= number_format($jml_ls_dan_up, 0, ',', '.'); } else { $bpp_jml_ls_dan_up = '-'; }
			if ($sisa_pg_angg != NULL && $sisa_pg_angg != 0) {$bpp_sisa_pg_angg= number_format($sisa_pg_angg, 0, ',', '.'); } else { $bpp_sisa_pg_angg = '-'; }
			
			$tabel_bpp = $tabel_bpp.'<td style="text-align: right;">'.$bpp_jml_angg.'</td>';
			$tabel_bpp = $tabel_bpp.'<td style="text-align: right;">'.$bpp_sd_bln_lalu_ls_gaji.'</td>';
			$tabel_bpp = $tabel_bpp.'<td style="text-align: right;">'.$bpp_bln_ini_ls_gaji.'</td>';
			$tabel_bpp = $tabel_bpp.'<td style="text-align: right;">'.$bpp_sd_bln_ini_ls_gaji.'</td>';
			$tabel_bpp = $tabel_bpp.'<td style="text-align: right;">'.$bpp_sd_bln_lalu_ls_brg_jasa.'</td>';
			$tabel_bpp = $tabel_bpp.'<td style="text-align: right;">'.$bpp_bln_ini_ls_brg_jasa.'</td>';
			$tabel_bpp = $tabel_bpp.'<td style="text-align: right;">'.$bpp_sd_bln_ini_ls_brg_jasa.'</td>';
			$tabel_bpp = $tabel_bpp.'<td style="text-align: right;">'.$bpp_sd_bln_lalu_up_gu_tu.'</td>';
			$tabel_bpp = $tabel_bpp.'<td style="text-align: right;">'.$bpp_bln_ini_up_gu_tu.'</td>';
			$tabel_bpp = $tabel_bpp.'<td style="text-align: right;">'.$bpp_sd_bln_ini_up_gu_tu.'</td>';
			$tabel_bpp = $tabel_bpp.'<td style="text-align: right;">'.$bpp_jml_ls_dan_up.'</td>';
			$tabel_bpp = $tabel_bpp.'<td style="text-align: right;">'.$bpp_sisa_pg_angg.'</td></tr>';
			
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
		}
		
		if ($tot_jml_angg != NULL && $tot_jml_angg != 0) $bpp_tot_jml_angg = number_format($tot_jml_angg, 0, ',', '.'); else $bpp_tot_jml_angg = '-';
		if ($tot_sd_bln_lalu_ls_gaji != NULL && $tot_sd_bln_lalu_ls_gaji != 0) $bpp_tot_sd_bln_lalu_ls_gaji = number_format($tot_sd_bln_lalu_ls_gaji, 0, ',', '.'); else $bpp_tot_sd_bln_lalu_ls_gaji = '-';
		if ($tot_bln_ini_ls_gaji != NULL && $tot_bln_ini_ls_gaji != 0) $bpp_tot_bln_ini_ls_gaji = number_format($tot_bln_ini_ls_gaji, 0, ',', '.'); else $bpp_tot_bln_ini_ls_gaji = '-';
		if ($tot_sd_bln_ini_ls_gaji != NULL && $tot_sd_bln_ini_ls_gaji != 0) $bpp_tot_sd_bln_ini_ls_gaji = number_format($tot_sd_bln_ini_ls_gaji, 0, ',', '.'); else $bpp_tot_sd_bln_ini_ls_gaji = '-';
		if ($tot_sd_bln_lalu_ls_brg_jasa != NULL && $tot_sd_bln_lalu_ls_brg_jasa != 0) $bpp_tot_sd_bln_lalu_ls_brg_jasa = number_format($tot_sd_bln_lalu_ls_brg_jasa, 0, ',', '.'); else $bpp_tot_sd_bln_lalu_ls_brg_jasa = '-';
		if ($tot_bln_ini_ls_brg_jasa != NULL && $tot_bln_ini_ls_brg_jasa != 0) $bpp_tot_bln_ini_ls_brg_jasa = number_format($tot_bln_ini_ls_brg_jasa, 0, ',', '.'); else $bpp_tot_bln_ini_ls_brg_jasa = '-';
		if ($tot_sd_bln_ini_ls_brg_jasa != NULL && $tot_sd_bln_ini_ls_brg_jasa != 0) $bpp_tot_sd_bln_ini_ls_brg_jasa = number_format($tot_sd_bln_ini_ls_brg_jasa, 0, ',', '.'); else $bpp_tot_sd_bln_ini_ls_brg_jasa = '-';
		if ($tot_sd_bln_lalu_up_gu_tu != NULL && $tot_sd_bln_lalu_up_gu_tu != 0) $bpp_tot_sd_bln_lalu_up_gu_tu = number_format($tot_sd_bln_lalu_up_gu_tu, 0, ',', '.'); else $bpp_tot_sd_bln_lalu_up_gu_tu = '-';
		if ($tot_bln_ini_up_gu_tu != NULL && $tot_bln_ini_up_gu_tu != 0) $bpp_tot_bln_ini_up_gu_tu = number_format($tot_bln_ini_up_gu_tu, 0, ',', '.'); else $bpp_tot_bln_ini_up_gu_tu = '-';
		if ($tot_sd_bln_ini_up_gu_tu != NULL && $tot_sd_bln_ini_up_gu_tu != 0) $bpp_tot_sd_bln_ini_up_gu_tu = number_format($tot_sd_bln_ini_up_gu_tu, 0, ',', '.'); else $bpp_tot_sd_bln_ini_up_gu_tu = '-';
		if ($tot_jml_ls_dan_up != NULL && $tot_jml_ls_dan_up != 0) $bpp_tot_jml_ls_dan_up = number_format($tot_jml_ls_dan_up, 0, ',', '.'); else $bpp_tot_jml_ls_dan_up = '-';
		if ($tot_sisa_pg_angg != NULL && $tot_sisa_pg_angg != 0) $bpp_tot_sisa_pg_angg = number_format($tot_sisa_pg_angg, 0, ',', '.'); else $bpp_tot_sisa_pg_angg = '-';
	}

	$lpjbelanja = '
	<div style="height: 100%">
		<table width="100%">
			<tbody>
				<tr>
					<th colspan="9">SEKRETARIAT DAERAH PROVINSI JAWA BARAT
						<br>
						LAPORAN PERTANGGUNG JAWABAN BENDAHARA PENGELUARAN PEMBANTU
						<br>
						(LPJ BELANJA)
					</th>
				</tr>
			</tbody>
		</table>
		<table class="left-top" width="100%">
			<tbody>
				<tr>
					<td style="font-weight: bold;">Organisasi</td>
					<td>:</td>
					<td style="font-weight: bold; width: 264px;">'.$nama_organisasi.'</td>
					<td style="font-weight: bold;" rowspan="2">Kegiatan</td>
					<td>:</td>
					<td style="font-weight: bold;" rowspan="2" colspan="4">'.$nama_kegiatan.'</td>
				</tr>
				<tr>
					<td style="font-weight: bold;">Kuasa Pengguna Anggaran</td>
					<td>:</td>
					<td style="font-weight: bold;">'.$kuasa_pgg_angg.'</td>
				</tr>
				<tr>
					<td style="font-weight: bold; width: 228px;">Bendahara Pengeluaran Pembantu</td>
					<td>:</td>
					<td style="font-weight: bold;">'.$bendahara_pp.'</td>
					<td style="font-weight: bold;">Tahun Anggaran</td>
					<td>:</td>
					<td style="font-weight: bold; width: 300px;">'.$tahun.'</td>
					<td style="font-weight: bold;">Bulan</td>
					<td>:</td>
					<td style="font-weight: bold;">'.$bulan.'</td>
				</tr>
			</tbody>
		</table>
		<table class="border" width="100%" style="border: 3px double black;">
			<thead >
				<tr>
					<th rowspan="2" width="10%">Kodering</th>
					<th rowspan="2" width="25%">Uraian</th>
					<th rowspan="2" width="9%">Jumlah<br>Anggaran</th>
					<th colspan="3" width="15%">LPJ - LS<br>Gaji</th>
					<th colspan="3" width="15%">LPJ - LS<br>Barang &amp; Jasa</th>
					<th colspan="3" width="15%">LPJ<br>UP/GU/TU</th>
					<th rowspan="2" width="9%">Jumlah<br>(LS+UP/GU/TU)</th>
					<th rowspan="2" width="9%">Sisa Pagu<br>Anggaran</th>
				</tr>
				<tr>
					<th>s/d<br>bulan<br>lalu</th>
					<th>bulan<br>ini</th>
					<th>s/d<br>bulan<br>ini</th>
					<th>s/d<br>bulan<br>lalu</th>
					<th>bulan<br>ini</th>
					<th>s/d<br>bulan<br>ini</th>
					<th>s/d<br>bulan<br>lalu</th>
					<th>bulan<br>ini</th>
					<th>s/d<br>bulan<br>ini</th>
				</tr>
				<tr>
					<td colspan="12" style="padding: 0.15mm;"></td>
				</tr>
				<tr style="background-color: #C0C0C0;">
					<td>1</td>
					<td>2</td>
					<td>3</td>
					<td>4</td>
					<td>5</td>
					<td>6 (4+5)</td>
					<td>7</td>
					<td>8</td>
					<td>9 (7+8)</td>
					<td>10</td>
					<td>11</td>
					<td>12 (10+11)</td>
					<td>13 (6+9+12)</td>
					<td>14 (3-13)</td>
				</tr>
			</thead>
			<tbody style="border: 3px double black;">
				<tr>
					<td style="text-align: left;" colspan="2">'.$kode_rek_lpj.'</td>
					<td style="text-align: left;"></td>
					<td style="text-align: left;"></td>
					<td style="text-align: left;"></td>
					<td style="text-align: left;"></td>
					<td style="text-align: left;"></td>
					<td style="text-align: left;"></td>
					<td style="text-align: left;"></td>
					<td style="text-align: left;"></td>
					<td style="text-align: left;"></td>
					<td style="text-align: left;"></td>
					<td style="text-align: left;"></td>
					<td style="text-align: left;"></td>
				</tr>
		'.$tabel_bpp.'
				<tr>
					<td style="text-align: left;"><b>Jumlah</b></td>
					<td></td>
					<td style="text-align: right;"><b>'.$bpp_tot_jml_angg.'</b></td>
					<td style="text-align: right;"><b>'.$bpp_tot_sd_bln_lalu_ls_gaji.'</b></td>
					<td style="text-align: right;"><b>'.$bpp_tot_bln_ini_ls_gaji.'</b></td>
					<td style="text-align: right;"><b>'.$bpp_tot_sd_bln_ini_ls_gaji.'</b></td>
					<td style="text-align: right;"><b>'.$bpp_tot_sd_bln_lalu_ls_brg_jasa.'</b></td>
					<td style="text-align: right;"><b>'.$bpp_tot_bln_ini_ls_brg_jasa.'</b></td>
					<td style="text-align: right;"><b>'.$bpp_tot_sd_bln_ini_ls_brg_jasa.'</b></td>
					<td style="text-align: right;"><b>'.$bpp_tot_sd_bln_lalu_up_gu_tu.'</b></td>
					<td style="text-align: right;"><b>'.$bpp_tot_bln_ini_up_gu_tu.'</b></td>
					<td style="text-align: right;"><b>'.$bpp_tot_sd_bln_ini_up_gu_tu.'</b></td>
					<td style="text-align: right;"><b>'.$bpp_tot_jml_ls_dan_up.'</b></td>
					<td style="text-align: right;"><b>'.$bpp_tot_sisa_pg_angg.'</b></td>
				</tr>
			</tbody>
		</table>
		<br>
		<table width="100%">
			<tbody>
				<tr>
					<td>Mengetahui/Menyetujui ;</td>
					<td>Bandung, '.$tgl_update.'</td>
				</tr>
				<tr>
					<th>Kuasa Pengguna Anggaran,</th>
					<th>Bendahara Pengeluaran Pembantu,</th>
				</tr>
				<tr>
					<th colspan="2" style="height: 20mm;"></th>
				</tr>
				<tr>
					<th><u>'.$kuasa_pgg_angg.'</u></th>
					<th><u>'.$bendahara_pp.'</u></th>
				</tr>
				<tr>
					<th>NIP. '.$kuasa_pgg_angg_nip.'</th>
					<th>NIP. '.$bendahara_pp_nip.'</th>
				</tr>
			</tbody>
		</table>
	</div>
	';
	
	/**
	  *  Halaman Buku Kas Umum
	  */
	  
	// Data Buku Kas Umum
	$query = "SELECT * FROM TB_BUKU_KAS_UMUM WHERE LPJ_ID='$lpj_id' ORDER BY BKU_NO_URUT";
	$result = mysql_query($query);
	$buku_kas_umum = array();
	$tabel_bku = '';
	$i = 0;
	$tmp_uraian = '';
	
	$jml_penerimaan = 0;
	$jml_pengeluaran = 0;
	$jml_pajak = array(0, 0, 0, 0);
	$data_pajak = array();
	$res = mysql_query("SELECT * FROM TB_M_PAJAK");
	while ($fetch = mysql_fetch_array($res)) { $data_pajak[] = $fetch['NAMA_PAJAK']; }
	
	while ($fetch = mysql_fetch_array($result)) { 
		$jml_penerimaan += $fetch['BKU_PENERIMAAN'];
		$jml_pengeluaran += $fetch['BKU_PENGELUARAN'];
		
		BACK_TO_START_WHILE:
		$buku_kas_umum['no_urut'] = $fetch['BKU_NO_URUT'];
		$buku_kas_umum['tgl'] = date_format(date_create($fetch['BKU_TGL_SUB_KEG']), 'd/m/Y');
		$buku_kas_umum['kode_rek'] = rtrim(chunk_split($fetch['BKU_KODE_REK'], 2, '.'), '.');
		$buku_kas_umum['uraian'] = $fetch['BKU_URAIAN'];
		$buku_kas_umum['atas_nama'] = get_nama_pegawai($fetch['BKU_ATAS_NAMA']);
		$buku_kas_umum['penerimaan'] = number_format($fetch['BKU_PENERIMAAN'], 0, ',', '.');
		$buku_kas_umum['pengeluaran'] = number_format($fetch['BKU_PENGELUARAN'], 0, ',', '.');
		
		if ($fetch['BKU_ATAS_NAMA'] != NULL && $fetch['BKU_ATAS_NAMA'] != '') {	// Memiliki atas nama
		
			$tabel_bku = $tabel_bku.'<tr><td style="text-align: center; font-size: 9pt;"></td>';
			$tabel_bku = $tabel_bku.'<td style="text-align: center; font-size: 9pt;"></td>';
			$tabel_bku = $tabel_bku.'<td style="text-align: left; font-size: 9pt;"></td>';
			$tabel_bku = $tabel_bku.'<td style="text-align: justify; font-size: 9pt;">'.$buku_kas_umum['uraian'].'</td>';
			$tabel_bku = $tabel_bku.'<td style="text-align: right; font-size: 9pt;"></td>';
			$tabel_bku = $tabel_bku.'<td style="text-align: right; font-size: 9pt;"></td></tr>';
			
			$tabel_bku = $tabel_bku.'<tr><td style="text-align: center; font-size: 9pt;">'.$buku_kas_umum['no_urut'].'</td>';
			$tabel_bku = $tabel_bku.'<td style="text-align: center; font-size: 9pt;">'.$buku_kas_umum['tgl'].'</td>';
			$tabel_bku = $tabel_bku.'<td style="text-align: center; font-size: 9pt;">'.$buku_kas_umum['kode_rek'].'</td>';
			if (empty($buku_kas_umum['atas_nama']) || $buku_kas_umum['atas_nama'] == NULL) $atas_nama = '';
			else $atas_nama = 'an. &nbsp;&nbsp;&nbsp;'.$buku_kas_umum['atas_nama'];
			$tabel_bku = $tabel_bku.'<td style="text-align: left; font-size: 9pt;">'.$atas_nama.'</td>';
			$tabel_bku = $tabel_bku.'<td style="text-align: right; font-size: 9pt;">'.$buku_kas_umum['penerimaan'].'</td>';
			$tabel_bku = $tabel_bku.'<td style="text-align: right; font-size: 9pt;">'.$buku_kas_umum['pengeluaran'].'</td></tr>';
			
			$tmp_uraian = $fetch['BKU_URAIAN'];
			
			while ($fetch = mysql_fetch_array($result)) {
			
				$jml_penerimaan += $fetch['BKU_PENERIMAAN'];
				$jml_pengeluaran += $fetch['BKU_PENGELUARAN'];
				
				if ($fetch['BKU_URAIAN'] == $tmp_uraian) {
					$buku_kas_umum['no_urut'] = $fetch['BKU_NO_URUT'];
					$buku_kas_umum['tgl'] = date_format(date_create($fetch['BKU_TGL_SUB_KEG']), 'd/m/Y');
					$buku_kas_umum['kode_rek'] = rtrim(chunk_split($fetch['BKU_KODE_REK'], 2, '.'), '.');
					$buku_kas_umum['uraian'] = $fetch['BKU_URAIAN'];
					$buku_kas_umum['atas_nama'] = get_nama_pegawai($fetch['BKU_ATAS_NAMA']);
					$buku_kas_umum['penerimaan'] = number_format($fetch['BKU_PENERIMAAN'], 0, ',', '.');
					$buku_kas_umum['pengeluaran'] = number_format($fetch['BKU_PENGELUARAN'], 0, ',', '.');
					
					$tabel_bku = $tabel_bku.'<tr><td style="text-align: center; font-size: 9pt;">'.$buku_kas_umum['no_urut'].'</td>';
					$tabel_bku = $tabel_bku.'<td style="text-align: center; font-size: 9pt;">'.$buku_kas_umum['tgl'].'</td>';
					$tabel_bku = $tabel_bku.'<td style="text-align: center; font-size: 9pt;">'.$buku_kas_umum['kode_rek'].'</td>';
					if (empty($buku_kas_umum['atas_nama']) || $buku_kas_umum['atas_nama'] == NULL) $atas_nama = '';
					else $atas_nama = 'an. &nbsp;&nbsp;&nbsp;'.$buku_kas_umum['atas_nama'];
					$tabel_bku = $tabel_bku.'<td style="text-align: left; font-size: 9pt;">'.$atas_nama.'</td>';
					$tabel_bku = $tabel_bku.'<td style="text-align: right; font-size: 9pt;">'.$buku_kas_umum['penerimaan'].'</td>';
					$tabel_bku = $tabel_bku.'<td style="text-align: right; font-size: 9pt;">'.$buku_kas_umum['pengeluaran'].'</td></tr>';
				} else {
					goto BACK_TO_START_WHILE;
				}	
			}
		} else {

			$tabel_bku = $tabel_bku.'<tr><td style="text-align: center; font-size: 9pt;">'.$buku_kas_umum['no_urut'].'</td>';
			$tabel_bku = $tabel_bku.'<td style="text-align: center; font-size: 9pt;">'.$buku_kas_umum['tgl'].'</td>';
			$tabel_bku = $tabel_bku.'<td style="text-align: center; font-size: 9pt;">'.$buku_kas_umum['kode_rek'].'</td>';
			$tabel_bku = $tabel_bku.'<td style="text-align: justify; font-size: 9pt;">'.$buku_kas_umum['uraian'].'</td>';
			$tabel_bku = $tabel_bku.'<td style="text-align: right; font-size: 9pt;">'.$buku_kas_umum['penerimaan'].'</td>';
			$tabel_bku = $tabel_bku.'<td style="text-align: right; font-size: 9pt;">'.$buku_kas_umum['pengeluaran'].'</td></tr>';
			
			if (!empty($fetch['BKU_PAJAK'])) {
				$pajak = explode("|", $fetch['BKU_PAJAK']);
				
				for ($i=0;$i<4;$i++) {
					if (!empty($pajak[$i])) {
						$tabel_bku = $tabel_bku.'<tr><td></td><td></td><td></td><td style="text-align: left; font-size: 9pt;">'.$data_pajak[$i].'</td><td style="text-align: right; font-size: 9pt;">'.number_format($pajak[$i], 0, ',', '.').'</td><td></td></tr>';

						$jml_penerimaan += floatval($pajak[$i]);
						$jml_pajak[$i] += floatval($pajak[$i]);
					}
				}
			}
			
		}
	}
	
	$tabel_bku_pajak = '';
	for ($i=0;$i<4;$i++) {
		if ($jml_pajak[$i] != 0) $jumlah_pajak =  number_format($jml_pajak[$i], 0, ',', '.');
		else $jumlah_pajak = '-'; 
		
		$tabel_bku_pajak = $tabel_bku_pajak.'<tr><td></td><td></td><td></td><td style="text-align: left; font-size: 9pt;"><b>'.$data_pajak[$i].'</b></td><td></td><td style="text-align: right; font-size: 9pt;"><b>'.$jumlah_pajak.'</b></td></tr>';
	
	}
	
	$bku ='
	<div style="height: 100%">
		<table width="100%">
			<tbody>
				<tr>
					<td colspan="9" style="font-size: 100%;">PEMERINTAH PROVINSI JAWA BARAT</td>
				</tr>
				<tr>
					<th colspan="9" style="font-size: 100%;">BUKU KAS UMUM</th>
				</tr>
			</tbody>
		</table>
		<p style="font-size:4px; line-height:1"></p>
		<table class="border" width="100%" style="border: 1px solid black;">
			<thead>
				<tr>
					<th width="2%">No.<br>Urut</th>
					<th width="12%">Tanggal</th>
					<th width="10%">Kode Rekening</th>
					<th width="40%">Uraian</th>
					<th width="13%">Penerimaan<br>Rp.</th>
					<th width="13%">Pengeluaran<br>Rp.</th>
				</tr>
				<tr style="background-color: #C0C0C0;">
					<td style="font-size: 9pt;">1</td>
					<td style="font-size: 9pt;">2</td>
					<td style="font-size: 9pt;">3</td>
					<td style="font-size: 9pt;">4</td>
					<td style="font-size: 9pt;">5</td>
					<td style="font-size: 9pt;">6</td>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td></td><td></td>
					<td style="text-align: center; font-size: 9pt;">'.$kode_rek_lpj.'</td>
					<td style="text-align: left; font-size: 9pt;">'.$nama_kegiatan.'</td><td></td><td></td>
				</tr>
				'.$tabel_bku.'
				<tr>
					<td style="text-align: left;"></td>
					<td style="text-align: left;"></td>
					<td style="text-align: left;"></td>
					<td style="text-align: left; font-size: 9pt;"><b>&nbsp;&nbsp;&nbsp;&nbsp;Jumlah</b></td>
					<td style="text-align: right; font-size: 9pt;"><b>'.number_format($jml_penerimaan, 0, ',', '.').'</b></td>
					<td style="text-align: right; font-size: 9pt;"><b>'.number_format($jml_pengeluaran, 0, ',', '.').'</b></td>
				</tr>
				'.$tabel_bku_pajak.'
				<tr>
					<td style="text-align: left;"></td>
					<td style="text-align: left;"></td>
					<td style="text-align: left;"></td>
					<td style="text-align: left; font-size: 9pt;"><b>&nbsp;&nbsp;&nbsp;&nbsp;Jumlah semua</b></td>
					<td style="text-align: right; font-size: 9pt;"><b>'.number_format($jml_penerimaan, 0, ',', '.').'</b></td>
					<td style="text-align: right; font-size: 9pt;"><b>'.number_format($jml_pengeluaran + array_sum($jml_pajak), 0, ',', '.').'</b></td>
				</tr>
				<tr>
					<td></td><td></td><td></td>
					<td style="text-align: left; font-size: 9pt;"><b>&nbsp;&nbsp;&nbsp;&nbsp;Sisa saldo</b></td><td></td>
					<td style="text-align: right; font-size: 9pt;"><b>'.number_format($jml_penerimaan - ($jml_pengeluaran + array_sum($jml_pajak)), 0, ',', '.').'</b></td>
				</tr>
				<tr>
					<td colspan="6" style="text-align: left; font-size: 10pt;">
					Pada hari ini ______ tanggal '.$tgl_update.', oleh kami didapat dalam Buku Kas sisa anggaran sebesar<br>
					Rp. &nbsp;&nbsp;&nbsp;&nbsp;<b>'.number_format($jml_penerimaan - ($jml_pengeluaran + array_sum($jml_pajak)), 0, ',', '.').'</b>
					</td>
				</tr>
			</tbody>
		</table>
		<br>
		<table width="100%">
			<tbody>
				<tr>
					<td>Mengetahui/Menyetujui ;</td>
					<td>Bandung, '.$tgl_update.'</td>
				</tr>
				<tr>
					<th>Kuasa Pengguna Anggaran,</th>
					<th>Bendahara Pengeluaran Pembantu,</th>
				</tr>
				<tr>
					<th colspan="2" style="height: 20mm;"></th>
				</tr>
				<tr>
					<th><u>'.$kuasa_pgg_angg.'</u></th>
					<th><u>'.$bendahara_pp.'</u></th>
				</tr>
				<tr>
					<th>NIP. '.$kuasa_pgg_angg_nip.'</th>
					<th>NIP. '.$bendahara_pp_nip.'</th>
				</tr>
			</tbody>
		</table>
	</div>
	';
	
	/**
	  *  Halaman Surat Pernyataan Pertanggung Jawaban Belanja Langsung
	  */
	
	$result = mysql_query("SELECT URAIAN_NAMA FROM TB_M_URAIAN WHERE KODE_REK = (SELECT KODE_REK FROM TB_LPJ_BNDHRA_PNGLUARAN_PMBNTU WHERE LPJ_ID=$lpj_id AND KODE_REK LIKE '0502__')");
	$jenis_bl = '';
	while ($fetch = mysql_fetch_array($result)) { $jenis_bl = $jenis_bl.ucwords(strtolower($fetch['URAIAN_NAMA'])).', '; }
	$jenis_bl = rtrim($jenis_bl, ', ');
	
	$query		= "SELECT * FROM tb_buku_kas_umum WHERE LPJ_ID='$lpj_id' AND BKU_KODE_REK LIKE '0502______' ORDER BY BKU_NO_URUT";
	$result		= mysql_query($query);
	$data_bku_sptjbl	= array();
	$tabel_sptjbl = '';
	$i = 0;
	while ($val = mysql_fetch_array($result)) {
		if ($val['BKU_PENGELUARAN'] != NULL && $val['BKU_PENGELUARAN'] != '') {
			$data_bku_sptjbl['no'] = $i+1;
			$data_bku_sptjbl['atasnama'] = get_nama_pegawai($val['BKU_ATAS_NAMA']);
			$data_bku_sptjbl['uraian'] = $val['BKU_URAIAN'];
			$data_bku_sptjbl['no_urut'] = $val['BKU_NO_URUT'];
			$data_bku_sptjbl['tgl'] = date_format(date_create($val['BKU_TGL_SUB_KEG']), 'd/m/Y');
			if (!empty($val['BKU_PENGELUARAN'])) $data_bku_sptjbl['rp'] = number_format($val['BKU_PENGELUARAN'], 0, ',', '.');
				else $data_bku_sptjbl['rp'] = '-';
			
			$tabel_sptjbl = $tabel_sptjbl.'<tr><td style="text-align: center; font-size: 9pt;">'.$data_bku_sptjbl['no'].'</td>';
			$tabel_sptjbl = $tabel_sptjbl.'<td style="text-align: left; font-size: 9pt;">'.$data_bku_sptjbl['atasnama'].'</td>';
			$tabel_sptjbl = $tabel_sptjbl.'<td style="text-align: justify; font-size: 9pt;">'.$data_bku_sptjbl['uraian'].'</td>';
			$tabel_sptjbl = $tabel_sptjbl.'<td style="text-align: center; font-size: 9pt;">'.$data_bku_sptjbl['no_urut'].'</td>';
			$tabel_sptjbl = $tabel_sptjbl.'<td style="text-align: center; font-size: 9pt;">'.$data_bku_sptjbl['tgl'].'</td>';
			$tabel_sptjbl = $tabel_sptjbl.'<td style="text-align: right; font-size: 9pt;">'.$data_bku_sptjbl['rp'].'</td></tr>';
			
			$i++;
		}
	}
	
	$sptj_bl ='
	<div style="height: 100%">
		<table width="100%">
			<tbody>
				<tr>
					<th colspan="9" style="font-size: 100%;">SURAT PERNYATAAN TANGGUNGJAWAB BELANJA LANGSUNG</th>
				</tr>
			</tbody>
		</table>
		<br>
		<table width="100%" class="left-top">
			<tbody>
				<tr>
					<td><b>1&nbsp;&nbsp;</b></td>
					<td>SKPD</td>
					<td>:</td>
					<td>'.strtoupper($nama_organisasi).'</td>
				</tr>
				<tr>
					<td><b>2&nbsp;&nbsp;</b></td>
					<td>Unit Kerja</td>
					<td>:</td>
					<td>'.strtoupper($nama_unit_kerja).'</td>
				</tr>
				<tr>
					<td><b>3&nbsp;&nbsp;</b></td>
					<td>Program</td>
					<td>:</td>
					<td>'.$nama_program.'</td>
				</tr>
				<tr>
					<td><b>4&nbsp;&nbsp;</b></td>
					<td>Kegiatan</td>
					<td>:</td>
					<td>'.$nama_kegiatan.'</td>
				</tr>
				<tr>
					<td><b>5&nbsp;&nbsp;</b></td>
					<td>No. dan Tgl. DPPA</td>
					<td>:</td>
					<td>'.$nomor_dppa.' dan '.$tgl_dppa.'</td>
				</tr>
				<tr>
					<td><b>6&nbsp;&nbsp;</b></td>
					<td>Jenis Belanja</td>
					<td>:</td>
					<td>'.$jenis_bl.'</td>
				</tr>
				<tr>
					<td><b>7&nbsp;&nbsp;</b></td>
					<td>Kode Rekening</td>
					<td>:</td>
					<td>'.$kode_rek_lpj.'</td>
				</tr>
			</tbody>
		</table>
		<table>
			<tr>
				<td style="text-align: left;">Yang bertandatangan dibawah ini Pengguna Anggaran/Kuasa Pengguna Anggaran  Kegiatan Verifikasi Bantuan Gubernur di Lingkungan Biro Pelayanan Sosial Dasar.</td>
			<tr>
		</table>
		<table class="border" width="100%" style="border: 3px double black;">
			<thead>
				<tr>
					<th rowspan="2" width="4%">No</th>
					<th rowspan="2" width="29%">Penerima</th>
					<th rowspan="2" width="45%">Uraian</th>
					<th colspan="2" width="12%">Bukti Kas</th>
					<th rowspan="2" width="10%">Rp.</th>
				</tr>
				<tr>
					<th width="2%">No</th>
					<th width="10%">Tanggal</th>
				</tr>
			</thead>
			<tbody style="border: 3px double black;">
				'.$tabel_sptjbl.'
			</tbody>
		</table>
		<table width="100%">
			<tr>
				<td style="text-align: justify;">Bukti-bukti asli belanja tersebut di atas disimpan sesuai dengan ketentuan yang diatur dalam Peraturan Menteri Dalam Negeri nomor 13 tahun 2006 Tentang Pedoman Pengelolaan Keuangan Daerah sebagai bukti pertanggungjawaban keuangan serta untuk keperluan pemeriksaan.
			</tr>
			<tr>
				<td style="text-align: left;">Demikian surat pernyataan ini dibuat dengan sebenarnya.</td>
			</tr>
		</table>
		<br><br>
		<table width="100%">
			<tbody>
				<tr>
					<td width="50%"></td>
					<td>Bandung, '.$tgl_update.'</td>
				</tr>
				<tr>
					<th></th>
					<th>Kuasa Pengguna Anggaran,</th>
				</tr>
				<tr>
					<th colspan="2" style="height: 20mm;"></th>
				</tr>
				<tr>
					<th></th>
					<th><u>'.$kuasa_pgg_angg.'</u></th>
				</tr>
				<tr>
					<th></th>
					<th>NIP. '.$kuasa_pgg_angg_nip.'</th>
				</tr>
			</tbody>
		</table>
	</div>
	';
	
	// LOAD a stylesheet
	$stylesheet = file_get_contents('lib/mpdf/stylesheet/dokumen_lpj.css');
	
	$mpdf->WriteHTML($stylesheet,1);	// The parameter 1 tells that this is css/style only and no body/html/text
	$mpdf->WriteHTML($cover,2);

	$mpdf->AddPage('L','','','','',10,10,10,10);
	$mpdf->WriteHTML($stylesheet,1);	// The parameter 1 tells that this is css/style only and no body/html/text
	$mpdf->WriteHTML($lpjbelanja,2);

	$mpdf->AddPage('P','','','','',10,10,10,10);
	$mpdf->WriteHTML($stylesheet,1);	// The parameter 1 tells that this is css/style only and no body/html/text
	$mpdf->WriteHTML($bku,2);

	$mpdf->AddPage('P','','','','',10,10,10,10);
	$mpdf->WriteHTML($stylesheet,1);	// The parameter 1 tells that this is css/style only and no body/html/text
	$mpdf->WriteHTML($sptj_bl,2);
	
	/**
	  *  Halaman Rekapitulasi Pengeluaran Per Rincian Objek
	  */
	$query = "SELECT DISTINCT BKU_KODE_REK
			FROM TB_BUKU_KAS_UMUM
			WHERE (BKU_KODE_REK)
			IN (SELECT KODE_REK FROM TB_LPJ_BNDHRA_PNGLUARAN_PMBNTU)";	// Mendapatkan kodering di tabel buku kas umum dari kodering di LPJ belanja
	$result = mysql_query($query);
	while ($fetch_r = mysql_fetch_array($result)) {
		$tabel_rekap = '';
		$bku_kr     = $fetch_r['BKU_KODE_REK'];
		
		$query		=  "SELECT 		*
					    FROM 		TB_BUKU_KAS_UMUM
						WHERE 		BKU_KODE_REK='$bku_kr'
						ORDER BY 	BKU_NO_URUT";
		$result_rkp	= mysql_query($query);
		
		// Get data rekap
		$tot_up_gu_tu = 0;
		$tot_jml = 0;
		while ($fetch_rkp = mysql_fetch_array($result_rkp)) {
			$data_rekap['no_urut'] 	= $fetch_rkp['BKU_NO_URUT'];
			$data_rekap['tgl'] 		= date_format(date_create($fetch_rkp['BKU_TGL_SUB_KEG']), 'd/m/Y');
			if (empty($fetch_rkp['BKU_ATAS_NAMA']) || $fetch_rkp['BKU_ATAS_NAMA'] == NULL) $atas_nama = '';
			else $atas_nama = ' an. '.get_nama_pegawai($fetch_rkp['BKU_ATAS_NAMA']);
			$data_rekap['uraian'] 	= $fetch_rkp['BKU_URAIAN'].$atas_nama;
			$data_rekap['ls'] 		= '';
			$data_rekap['up_gu_tu'] = number_format($fetch_rkp['BKU_PENGELUARAN'], 0, ',', '.');
			$data_rekap['jml'] 		= number_format($fetch_rkp['BKU_PENGELUARAN'], 0, ',', '.');
			
			// Hitung totalUP/GU/TU dan Jumlah Pengeluaran
			$tot_up_gu_tu += $fetch_rkp['BKU_PENGELUARAN'];
			$tot_jml += $fetch_rkp['BKU_PENGELUARAN'];
			
			$tabel_rekap = $tabel_rekap.'
			<tr>
				<td style="font-size: 9pt;">'.$data_rekap['no_urut'].'</td>
				<td style="font-size: 9pt;">'.$data_rekap['tgl'].'</td>
				<td style="text-align: justify; font-size: 9pt;">'.$data_rekap['uraian'].'</td>
				<td style="text-align: right; font-size: 9pt;">'.$data_rekap['ls'].'</td>
				<td style="text-align: right; font-size: 9pt;">'.$data_rekap['up_gu_tu'].'</td>
				<td style="text-align: right; font-size: 9pt;">'.$data_rekap['jml'].'</td>
			</tr>
			';
		}
		
		// Tabel HTML rekap
		$rekap = '
		<div style="height: 100%">
			<table width="100%">
				<tbody>
					<tr>
						<th colspan="9" style="font-size: 100%;">BUKU REKAPITULASI PENGELUARAN<br>PER RINCIAN OBJEK</th>
					</tr>
				</tbody>
			</table>
			<br>
			<table width="100%" class="left-top">
				<tbody>
					<tr>
						<td width="14%">SKPD</td>
						<td width="84%">: '.$nama_organisasi.'</td>
					</tr>
					<tr>
						<td>Unit Kerja</td>
						<td>: '.$nama_unit_kerja.'</td>
					</tr>
					<tr>
						<td>Kode Rekening</td>
						<td>: '.chunk_split($bku_kr, 2, ".").'</td>
					</tr>
					<tr>
						<td>Nama Rekening</td>
						<td>: '.get_nama_rek($bku_kr).'</td>
					</tr>
					<tr>
						<td>Tahun Anggaran</td>
						<td>: '.$tahun.'</td>
					</tr>
				</tbody>
			</table>
			<table class="border" width="100%" style="border: 3px double black;">
				<thead>
					<tr>
						<th rowspan="2" width="4%">No. Urut</th>
						<th rowspan="2" width="10%">Tanggal</th>
						<th rowspan="2" width="52%">Uraian</th>
						<th colspan="3" width="34%">Pengeluaran</th>
					</tr>
					<tr>
						<th width="10%">LS</th>
						<th width="12%">UP/GU/TU</th>
						<th width="12%">Jumlah</th>
					</tr>
					<tr style="background-color: #C0C0C0;">
						<td style="font-size: 9pt;">1</td>
						<td style="font-size: 9pt;">2</td>
						<td style="font-size: 9pt;">3</td>
						<td style="font-size: 9pt;">4</td>
						<td style="font-size: 9pt;">5</td>
						<td style="font-size: 9pt;">6</td>
					</tr>
				</thead>
				<tbody style="border: 3px double black;">
					'.$tabel_rekap.'
					<tr>
						<td style="text-align: left; font-size: 9pt;" colspan="3"><b>&nbsp;&nbsp;&nbsp;&nbsp;Jumlah</b></td>
						<td style="text-align: right; font-size: 9pt;">-</td>
						<td style="text-align: right; font-size: 9pt;">'.number_format($tot_up_gu_tu, 0, ',', '.').'</td>
						<td style="text-align: right; font-size: 9pt;">'.number_format($tot_jml, 0, ',', '.').'</td>
					</tr>
				</tbody>
			</table>
			<br>
			<table width="100%">
				<tbody>
					<tr>
						<td>Mengetahui/Menyetujui ;</td>
						<td>Bandung, '.$tgl_update.'</td>
					</tr>
					<tr>
						<th>Kuasa Pengguna Anggaran,</th>
						<th>Bendahara Pengeluaran Pembantu,</th>
					</tr>
					<tr>
						<th colspan="2" style="height: 20mm;"></th>
					</tr>
					<tr>
						<th><u>'.$kuasa_pgg_angg.'</u></th>
						<th><u>'.$bendahara_pp.'</u></th>
					</tr>
					<tr>
						<th>NIP. '.$kuasa_pgg_angg_nip.'</th>
						<th>NIP. '.$bendahara_pp_nip.'</th>
					</tr>
				</tbody>
			</table>
		</div>
		';
		
		$mpdf->AddPage('P','','','','',10,10,10,10);
		$mpdf->WriteHTML($stylesheet,1);	// The parameter 1 tells that this is css/style only and no body/html/text
		$mpdf->WriteHTML($rekap,2);
	}
	
	
	// Write file
	$mpdf->Output("dokumen-lpj/DOKUMEN_LPJ-ID-".$lpj_id.".pdf",'F');
	header("location:dokumen-lpj/DOKUMEN_LPJ-ID-".$lpj_id.".pdf");
} else {
	header("location:index.php");
}

?>