<?php

if (!isset($_GET['lpj_id'])) header("location:index.php");

?>
<!-- Menu Dokumen LPJ -->
	<div class='subtitle'>
		<div class='title'><h2>Menu Dokumen LPJ</h2></div>
	</div>
	<div class='sub-container noflex'>
		<div class='cust-register' style='float: none; display: block; padding: 0px 10px; margin: 15px 0px;'>
			<table style='width: 100%;'>
				<tr>
					<td style='width: 200px;'><b>ID Dokumen</b></td>
					<td>: <b><?php echo $_GET['lpj_id'] ?></b></td>
				</tr>
				<tr><td colspan='2'>&nbsp;</td></tr>
				<tr>
					<td><b>Nama Dokumen</b></td>
					<td>: <b><?php echo $nama_kegiatan ?></b></td>
				</tr>
				<tr><td colspan='2'>&nbsp;</td></tr>
				<tr>
					<td><b>Pengelola Dokumen</b></td>
					<td>: <b><?php echo get_nama_pengelola_lpj($_GET['lpj_id']) ?>  (<?php echo get_username_pengelola_lpj($_GET['lpj_id']) ?>)</b></td>
				</tr>
			</table>
		</div>
		<div class='clear separator sub'></div>
		<table>
			<tr>
				<td width="500" style='vertical-align: top;'>
					<ul>
						<li><a href='index.php?page=form-data-utama-lpj&lpj_id=<?php echo $lpj_id ?>'>Form Data Utama LPJ</a></li>
						<li><a href='index.php?page=form-data-lpj-belanja&lpj_id=<?php echo $lpj_id ?>'>Form LPJ Bendahara Pengeluaran Pembantu (LPJ Belanja)</a></li>
						<li><a href='index.php?page=form-data-buku-kas-umum&lpj_id=<?php echo $lpj_id ?>'>Form Buku Kas Umum</a></li>
						<li><a href='index.php?page=form-data-sptj-bl&lpj_id=<?php echo $lpj_id ?>'>Form Surat Pertanggungjawaban Belanja Langsung</a></li>
					</ul>
				</td>
				<td style='vertical-align: top;'>
					<ul>
						<li><a href='index.php?page=form-data-penandatangan-lpj&lpj_id=<?php echo $lpj_id ?>'>Setting Penandatangan LPJ</a></li>
						<li><a href='index.php?page=form-ubah-status-lpj&lpj_id=<?php echo $lpj_id ?>'>Ubah Status Dokumen LPJ</a></li>
						<li><a href='index.php?page=form-upload-arsip-lpj&lpj_id=<?php echo $lpj_id ?>'>Upload Arsip LPJ</a></li>
					</ul>
				</td>
			</tr>
		</table>
	</div>