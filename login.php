<!-- =============================================== -->
<!-- =                                             = -->
<!-- =           Sistem Pengelolaan LPJ            = -->
<!-- =                                             = -->
<!-- =============================================== -->

<?php
session_start();
if (isset($_SESSION['login_session'])) {
	header("location:index.php");
}
?>

<!DOCTYPE html>
<!--[if lt IE 7 ]><html class='ie ie6' lang='en'> <![endif]-->
<!--[if IE 7 ]><html class='ie ie7' lang='en'> <![endif]-->
<!--[if IE 8 ]><html class='ie ie8' lang='en'> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--><html lang='en'> <!--<![endif]-->
<head>

	<!-- Basic Page Needs
  ================================================== -->
	<meta charset='utf-8'>
	<title>Login | Aplikasi Pengelolaan LPJ</title>
	<meta name='description' content=''>
	<meta name='author' content='Januar Muhtadiin, Rizki Pratama'>
    <meta http-equiv='X-UA-Compatible' content='IE=9'>

	<meta name='viewport' content='width=device-width, initial-scale=1, maximum-scale=1'>
	
	<link href='images/ico/favico.ico' rel='shortcut icon'>
	
	<link href='css/opensans400italic-400-300-600.css' rel='stylesheet' type='text/css'>
	<link href='css/creteround.css' rel='stylesheet' type='text/css'>
	
  	<link rel='stylesheet' href='css/reset.css'>
	<link rel='stylesheet' href='css/base.css'>
	<link rel='stylesheet' href='css/skeleton.css'>
	<link rel='stylesheet' href='css/layout.css'>

	<!--[if lt IE 9]>
		<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
	
	<script type='text/javascript' src='js/jquery.js'></script>
	<script type='text/javascript' src='js/validate.js'></script>
	
</head>
<body>
	<!-- Header section -->
	<header>
		<div class='container'>
			<div class='logo'>
				<img src='images/logo/logo.png'>
			</div>
		</div>
	</header>
	
	<div class='clear-narrow'></div>
	
	<!-- Content section -->
	<div class='container' style='min-height: 480px'>
		<div class='sub-content' id='login-atau-tambah-admin'>
			<div class='subtitle'>
				<div class='title'><h2>Login atau Tambah Admin</h2></div>
			</div>
			<div class='sub-container'>
				<marquee class='welcome' scrollamount='6'><h3>Selamat Datang di Aplikasi Pengelolaan Laporan Pertanggungjawaban Biro Pelayanan Sosial Dasar Sekretariat Daerah Provinsi Jawa Barat</h3></marquee>
			</div>
			<div class='sub-container'>
				<div class='cust-login login'>
					<?php if (isset($_GET['logged_in']) && $_GET['logged_in'] == 'error') { ?><p class='inlinemsg err'>Login error: username atau email dan password salah!</p><?php } ?>
					<?php if (isset($_GET['logged_out']) && $_GET['logged_out'] == 'success') { ?><p class='inlinemsg suc'>Anda telah berhasil logout dari aplikasi ini.</p><?php } ?>
					<?php if (isset($_GET['register']) && $_GET['register'] == 'success') { ?><p class='inlinemsg suc'>Anda telah mendaftarkan diri sebagai admin. Silakan login.</p><?php } ?>
					<h4>Login untuk Admin Terdaftar</h4>
					<p>Jika Anda memiliki akun terdaftar, silakan masukkan username dan password untuk login ke aplikasi ini.</p>
					<form id='form-login-admin' name='form-login-admin' action='cek-login.php' method='POST' style='margin: 20px 0;'>
						<table>
							<tbody>
								<tr>
									<td style='width: 150px;'><label for='FLA-USERNAME'>Username:</label></td>
									<td><input id='FLA-USERNAME' name='FLA-USERNAME' style='width: 250px;' type='text' placeholder='Username Anda.' /></td>
								</tr>
								<tr>
									<td><label for='FLA-PASSWORD'>Password:</label></td>
									<td><input id='FLA-PASSWORD' name='FLA-PASSWORD' style='width: 250px;' type='password' placeholder='Password akun Anda.' /></td>
								</tr>
								<tr>
									<td colspan='2' style='text-align: right;'><input id='submit-login-admin' name='submit-login-admin' class='for-form' type='submit' value='Login' /></td>
								</tr>
							</tbody>
						</table>
					</form>
				</div>
				<div class='cust-login register'>
					<h4>Tambah Admin</h4>
					<p>Jika Anda ingin mendaftarkan diri sebagai admin aplikasi ini, silakan klik tombol <b>Daftarkan Admin</b> untuk mengisi formulir yang dibutuhkan untuk melakukan pendaftarannya.</p>
					<div style='text-align: right;'><input id='FLA-REGISTER' name='FLA-REGISTER' class='for-form' type='button' value='Daftarkan Admin' onclick="javascript:location.href='register.php'" /></div>
				</div>
			</div>
		</div>
	</div>
	
	<div class='clear'></div>

	<!-- Footer section -->
	<footer>
		<?php include ("footer.php"); ?>
	</footer>
	
	<!-- Additional JS -->
	<script type='text/javascript' src='js/scrolltotop.js'></script>
	<script type='text/javascript' src='js/login_validate.js'></script>
</body>
</html>

