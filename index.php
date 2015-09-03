<!-- =============================================== -->
<!-- =                                             = -->
<!-- =           Sistem Pengelolaan LPJ            = -->
<!-- =                                             = -->
<!-- =============================================== -->

<?php
session_start();
if (!isset($_SESSION['login_session'])) {
	header("location:login.php");
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
	<title>Aplikasi Pengelolaan LPJ</title>
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
		
		<nav id='main-menu-nav'>
			<div class='container'>
				<div class='menu-nav'>
					<ul class='mainMenu right'>
						<li><a href='./' title='Home'>Home</a></li>
						<li><a href='index.php?page=daftar-lpj' title='Daftar LPJ'>Daftar LPJ</a></li>
						<li><a href='index.php?page=form-tambah-lpj' title='Buat Dokumen LPJ Baru'>Buat Dokumen LPJ Baru</a></li>
						<li><a href='index.php?page=cari-arsip-lpj' title='Pencarian Arsip LPJ'>Pencarian Arsip LPJ</a></li>
					</ul>
				</div>
                <div class='menu-nav right'>
					<ul class='mainMenu right'>
                        <li><?php if (!isset($_SESSION['login_session'])) { } else { ?><a href='index.php?page=form-edit-data-admin' title='Edit data admin ini'>Halo, <?php echo $_SESSION['login_session']['user_id']; ?></a><?php } ?></li>
						<li><?php if (!isset($_SESSION['login_session'])) { } else { ?><a href='logout.php' title='Logout dari aplikasi'>Logout</a><?php } ?></li>
					</ul>
				</div>
			</div>
		</nav>
		
	</header>
	
	<div class='clear'></div>
	
	<!-- Content section -->
	<div id='main-content' class='container' style='min-height: 480px'>
	<?php
	
	if (isset($_GET['page'])) {
		include($_GET['page'].".php");
	} else {
		include("home.php");
	}
	?>
	</div>
	
	<div class='clear'></div>

	<!-- Footer section -->
	<footer>
		<?php include ("footer.php"); ?>
	</footer>
	
	<!-- Additional JS -->
	<script type='text/javascript' src='js/fixedmenunav.js'></script>
	<script type='text/javascript' src='js/scrolltotop.js'></script>
</body>
</html>