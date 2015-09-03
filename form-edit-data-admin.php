<?php
	require "connect.php";
	require "functions.php";
	
	$username = $_SESSION['login_session']['user_id'];
	$data_adm = query_result("SELECT * FROM TB_USER WHERE USER_ID='$username'", 'select', false);
?>


<div class='sub-content' id='edit-data-admin'>
	<div class='subtitle'>
		<div class='title'><h2>Form Edit Data Admin</h2></div>
	</div>
	<div class='sub-container'>
		<div class='cust-register'>
		<?php
		if (isset($_GET['update_data'])) {
			if ($_GET['update_data'] == 'error') {
		?>
			<p class='inlinemsg err'>Gagal update data. Silakan coba lagi nanti.</p>
		<?php
			} else if ($_GET['update_data'] == 'success') {
				if (isset($_GET['passwd']) && $_GET['passwd'] == 'changed') {
		?>
			<p class='inlinemsg suc'>Data admin berhasil diupdate. Sistem akan melakukan logout otomatis dalam 2 detik...</p>
			<meta http-equiv='refresh' content='2; url=logout.php'>
		<?php
				} else {
		?>
			<p class='inlinemsg suc'>Data berhasil diupdate.</p>
		<?php
				}
			}
		}
		?>
			<form id='form-edit-data-admin' name='form-edit-data-admin' action='update-data-admin.php' method='POST'>
				<h4>Informasi Login</h4>
				<p>Isikan informasi login pada form di bawah ini.</p>
				<table style='margin-top: 20px;'>
					<tbody>
						<tr>
							<td><label for='EDA-USERNAME'>Username:</label></td>
							<td><input id='EDA-USERNAME' name='EDA-USERNAME' style='width: 300px;' type='text' placeholder='Masukkan username yang unik.' value='<?php echo $data_adm['USER_ID'] ?>' readonly /></td>
						</tr>
						<tr>
							<td style='width: 200px;'><label for='EDA-PASSWORD'>Password:</label></td>
							<td><input id='EDA-PASSWORD' name='EDA-PASSWORD' style='width: 300px;' type='password' placeholder='Isi password jika Anda ingin mengedit data admin.' /></td>
						</tr>
					</tbody>
				</table>
				<br>
				<p>Isikan field di bawah ini <b>jika Anda ingin mengganti password</b> akun Anda.</p>
				<table style='margin-top: 20px;'>
					<tbody>
						<tr>
							<td style='width: 200px;'><label for='EDA-NWPASSWD'>Password Baru:</label></td>
							<td><input id='EDA-NWPASSWD' name='EDA-NWPASSWD' style='width: 300px;' type='password' placeholder='Password harus memiliki panjang 8-16 karakter.' /></td>
						</tr>
						<tr>
							<td><label for='EDA-KFPASSWD'>Konfirmasi Password Baru:</label></td>
							<td><input id='EDA-KFPASSWD' name='EDA-KFPASSWD' style='width: 300px;' type='password' placeholder='Masukkan password yang sama.' /></td>
						</tr>
					</tbody>
				</table>
				<div class='clear separator' style='width: 800px;'></div>
				<h4>Informasi Admin</h4>
				<p>Isikan informasi personal admin pada form di bawah ini.</p>
				<table style='margin-top: 20px;'>
					<tbody>
						<tr>
							<td style='width: 200px;'><label for='EDA-NAMA'>Nama Lengkap:</label></td>
							<td><input id='EDA-NAMA' name='EDA-NAMA' style='width: 300px;' type='text' placeholder='Masukkan nama lengkap Anda.' value='<?php echo $data_adm['USER_NAMA'] ?>' /></td>
						</tr>
						<tr>
							<td><label for='EDA-JABATAN'>Jabatan:</label></td>
							<td><input id='EDA-JABATAN' name='EDA-JABATAN' style='width: 300px;' type='text' placeholder='Masukkan jabatan Anda.' value='<?php echo $data_adm['USER_JABATAN'] ?>' /></td>
						</tr>
						<tr>
							<td><input id='clear' name='clear' class='for-form' type='button' value='Reset' onclick="javascript:$('#form-edit-data-admin').each(function(){ this.reset(); });" /></td>
							<td><input id='submit-update-data-admin' name='submit-update-data-admin' class='for-form' type='submit' value='Update Data Admin' /></td>
						</tr>
					</tbody>
				</table>
			</form>
		</div>
	</div>
</div>

<!-- JS -->
<script type='text/javascript' src='js/editadmin_validate.js'></script>