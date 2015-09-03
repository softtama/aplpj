<?php require "functions.php"; ?>
<div class='sub-content' id='home-panel'>
	<div class='subtitle'>
		<div class='title'><h2>Home Panel</h2></div>
	</div>
	<div class='sub-container noflex'>
		<marquee class='welcome' scrollamount='6'><h3><?php echo get_nama_admin($_SESSION['login_session']['user_id']) ?>, Selamat Datang di Aplikasi Pengelolaan Laporan Pertanggungjawaban Biro Pelayanan Sosial Dasar Sekretariat Daerah Provinsi Jawa Barat</h3></marquee>
	</div>
	<div class='sub-container'>
		<div class='panel'>
			<div class='panel-row top'>
				<div class='panel-col color-1 left'>
					<a class='panel-link' href='index.php?page=daftar-lpj&status_lpj=1'>
						<div class='panel-image'><img src='images/res/document.png' /></div>
						<div class='panel-title'>Daftar LPJ Dalam Pengerjaan</div>
					</a>
				</div>
				<div class='panel-col color-2 left'>
					<a class='panel-link' href='index.php?page=daftar-lpj&status_lpj=2'>
						<div class='panel-image'><img src='images/res/document.png' /></div>
						<div class='panel-title'>Daftar LPJ Selesai</div>
					</a>
				</div>
				<div class='panel-col color-3'>
					<a class='panel-link' href='index.php?page=daftar-lpj&status_lpj=3'>
						<div class='panel-image'><img src='images/res/document.png' /></div>
						<div class='panel-title'>Daftar LPJ Selesai &amp; Ditandatangani</div>
					</a>
				</div>
			</div>
			<div class='panel-row'>
				<div class='panel-col color-4 left'>
					<a class='panel-link' href='index.php?page=form-tambah-lpj'>
						<div class='panel-image'><img src='images/res/add-document.png' /></div>
						<div class='panel-title'>Buat Dokumen LPJ Baru</div>
					</a>
				</div>
				<div class='panel-col color-5 left'>
					<a class='panel-link' href='index.php?page=cari-arsip-lpj'>
						<div class='panel-image'><img src='images/res/search.png' /></div>
						<div class='panel-title'>Pencarian Arsip Dokumen LPJ</div>
					</a>
				</div>
				<div class='panel-col color-6'>
					<a class='panel-link' href='index.php?page=form-edit-data-admin'>
						<div class='panel-image'><img src='images/res/admin.png' /></div>
						<div class='panel-title'>Edit Data Admin</div>
					</a>
				</div>
			</div>
		</div>
	</div>
</div>