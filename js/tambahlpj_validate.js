// Form validation, submit without refreshing page with AJAX, jQuery
$(document).ready(function(e){
	// Adding rule for select field
	$.validator.addMethod("isNotEqual", function(value, element, arg){
		return arg != value;
	}, "");
	
	$('#form-tambah-lpj').validate({
		debug: false,
		onkeypress: true,
		rules: {
			'KODERING-LPJ'	: { required: true },
			'NAMA-KEGIATAN'	: { required: true },
			'BULAN-KEGIATAN': { isNotEqual: 'default' },
			'TAHUN-KEGIATAN': { required: true, number: true },
			'ORGANISASI'	: { required: true },
			'UNIT-KERJA'	: { required: true },
			'PROGRAM'		: { required: true },
			'NO-DPPA'		: { required: true },
			'TGL-DPPA'		: { required: true }
		},
		messages: {
			'KODERING-LPJ'	: "Kode rekening LPJ dibutuhkan.",
			'NAMA-KEGIATAN'	: "Nama kegiatan dibutuhkan.",
			'BULAN-KEGIATAN': "Bulan kegiatan dibutuhkan.",
			'TAHUN-KEGIATAN': {
				required: "Tahun kegiatan dibutuhkan.",
				number	: "Tahun harus dalam format nomor."
			},
			'ORGANISASI': "Nama organisasi dibutuhkan.",
			'UNIT-KERJA': "Nama unit kerja dibutuhkan.",
			'PROGRAM'	: "Nama program dibutuhkan.",
			'NO-DPPA'	: "Nomor DPPA dibutuhkan.",
			'TGL-DPPA'	: "Tanggal DPPA dibutuhkan."
		}
	});
});