// Form validation, submit without refreshing page with AJAX, jQuery
$(document).ready(function(e){
	
	$('#form-login-admin').validate({
		debug: false,
		onkeypress: true,
		rules: {
			'FLA-USERNAME'	: { required: true },
			'FLA-PASSWORD'	: { required: true, minlength: 8, maxlength: 16 }
		},
		errorPlacement: function(error, element) {}	// What to do if form is invalid
	});
});