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
	<title>Tambah Admin | Aplikasi Pengelolaan LPJ</title>
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
		
		<nav id='account-nav'>
			<div class='container'>
				<div>
					<ul class='mainMenu right'>
						<li><a href='login.php' title='Login'>Login</a></li>
						<li>|</li>
						<li><a href='register.php' title='Login'>Tambah Admin</a></li>
					</ul>
				</div>
			</div>
		</nav>
	</header>
	
	<div class='clear'></div>
	
	<!-- Content section -->
	<div class='container' style='min-height: 480px'>
		<div class='sub-content' id='tambah-admin'>
			<div class='subtitle'>
				<div class='title'><h2>Tambah Admin</h2></div>
			</div>
			<div class='sub-container'>
				<div class='cust-register'>
				<?php
				if (isset($_GET['register'])) {
					if ($_GET['register'] == 'error') {
				?>
					<p class='inlinemsg err'>Gagal mendaftarkan admin. Silakan coba lagi nanti.</p>
				<?php
					}
				}
				?>
					<form id='form-tambah-admin' name='form-tambah-admin' action='tambah-admin.php' method='POST' style='margin-bottom: 20px;'>
						<h4>Informasi Login</h4>
						<p>Isikan informasi login pada form di bawah ini.</p>
						<table style='margin-top: 20px;'>
							<tbody>
								<tr>
									<td><label for='TADM-USERNAME'>Username:</label></td>
									<td><input id='TADM-USERNAME' name='TADM-USERNAME' style='width: 300px;' type='text' placeholder='Masukkan username yang unik.' /></td>
								</tr>
								<tr>
									<td style='width: 200px;'><label for='TADM-PASSWORD'>Password:</label></td>
									<td><input id='TADM-PASSWORD' name='TADM-PASSWORD' style='width: 300px;' type='password' placeholder='Password harus memiliki panjang 8-16 karakter.' /></td>
								</tr>
								<tr>
									<td><label for='TADM-KFPASSWD'>Konfirmasi Password:</label></td>
									<td><input id='TADM-KFPASSWD' name='TADM-KFPASSWD' style='width: 300px;' type='password' placeholder='Masukkan password yang sama.' /></td>
								</tr>
							</tbody>
						</table>
						<div class='clear separator' style='width: 800px;'></div>
						<h4>Informasi Admin</h4>
						<p>Isikan informasi personal admin pada form di bawah ini.</p>
						<table style='margin-top: 20px;'>
							<tbody>
								<tr>
									<td style='width: 200px;'><label for='TADM-NAMA'>Nama Lengkap:</label></td>
									<td><input id='TADM-NAMA' name='TADM-NAMA' style='width: 300px;' type='text' placeholder='Masukkan nama lengkap Anda.' /></td>
								</tr>
								<tr>
									<td><label for='TADM-JABATAN'>Jabatan:</label></td>
									<td><input id='TADM-JABATAN' name='TADM-JABATAN' style='width: 300px;' type='text' placeholder='Masukkan jabatan Anda.' /></td>
								</tr>
								<tr>
									<td><input id='clear' name='clear' class='for-form' type='button' value='Reset' onclick="javascript:$('#form-tambah-admin').each(function(){ this.reset(); });" /></td>
									<td><input id='submit-tambah-admin' name='submit-tambah-admin' class='for-form' type='submit' value='Tambah Admin' /></td>
								</tr>
							</tbody>
						</table>
					</form>
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
	<script type='text/javascript' src='js/tambahadmin_validate.js'></script>
</body>
</html>