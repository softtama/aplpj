// Form validation, submit without refreshing page with AJAX, jQuery
$(document).ready(function(e){
	
	$('#form-edit-data-admin').validate({
		debug: false,
		onkeypress: true,
		rules: {
			'EDA-PASSWORD'	: { required: true, minlength: 8, maxlength: 16, remote: {
				url: 'cek-password.php',
				type: "POST",
				data: {
					'EDA-PASSWORD': function() {
						return $('#form-edit-data-admin :input[name="EDA-PASSWORD"]').val();
					}
				}
			}},
			'EDA-NWPASSWD'	: { minlength: 8, maxlength: 16 },
			'EDA-KFPASSWD'	: { minlength: 8, maxlength: 16, equalTo: '#EDA-NWPASSWD' },
			'EDA-NAMA'		: { required: true },
			'EDA-JABATAN'	: { required: true }
		},
		messages: {
			'EDA-PASSWORD': {
				required: "Password dibutuhkan.",
				minlength: "Tidak boleh kurang dari 8 karakter.",
				maxlength: "Tidak boleh lebih dari 16 karakter.",
				remote: "Password salah!"
			},
			'EDA-NWPASSWD': {
				required: "Password dibutuhkan.",
				minlength: "Tidak boleh kurang dari 8 karakter.",
				maxlength: "Tidak boleh lebih dari 16 karakter."
			},
			'EDA-KFPASSWD': {
				minlength: "Tidak boleh kurang dari 8 karakter.",
				maxlength: "Tidak boleh lebih dari 16 karakter.",
				equalTo: "Harus sama dengan password di atas."
			},
			'EDA-NAMA': "Nama lengkap dibutuhkan.",
			'EDA-JABATAN': "Jabatan dibutuhkan."
		}
	});
});