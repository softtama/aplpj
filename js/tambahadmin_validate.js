// Form validation, submit without refreshing page with AJAX, jQuery
$(document).ready(function(e){
	
	$('#form-tambah-admin').validate({
		debug: false,
		onkeypress: true,
		rules: {
			'TADM-USERNAME'	: { required: true, minlength: 6, maxlength: 20, remote: {
				url: 'cek-username.php',
				type: "POST",
				data: {
					'TADM-USERNAME': function() {
						return $('#form-tambah-admin :input[name="TADM-USERNAME"]').val();
					}
				}
			}},
			'TADM-PASSWORD'	: { required: true, minlength: 8, maxlength: 16 },
			'TADM-KFPASSWD'	: { required: true, minlength: 8, maxlength: 16, equalTo: '#TADM-PASSWORD' },
			'TADM-NAMA'		: { required: true },
			'TADM-JABATAN'	: { required: true },
		},
		messages: {
			'TADM-USERNAME': {
				required: "Username dibutuhkan.",
				minlength: "Tidak boleh kurang dari 6 karakter.",
				maxlength: "Tidak boleh lebih dari 20 karakter.",
				remote: "Username sudah dipakai."
			},
			'TADM-PASSWORD': {
				required: "Password dibutuhkan.",
				minlength: "Tidak boleh kurang dari 8 karakter.",
				maxlength: "Tidak boleh lebih dari 16 karakter."
			},
			'TADM-KFPASSWD': {
				required: "Password konfirmasi dibutuhkan.",
				minlength: "Tidak boleh kurang dari 8 karakter.",
				maxlength: "Tidak boleh lebih dari 16 karakter.",
				equalTo: "Harus sama dengan password di atas."
			},
			'TADM-NAMA': "Nama lengkap dibutuhkan.",
			'TADM-JABATAN': "Jabatan dibutuhkan."
		}
	});
});