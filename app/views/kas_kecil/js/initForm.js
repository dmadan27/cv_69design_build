$(document).ready(function() {
	init();

	// button tambah
	$('#tambah').on('click', function() {
		onClickAdd();
	});

	// submit kas kecil
	$('#form_kas_kecil').submit(function(e) {
		e.preventDefault();
		submit();

		return false;
	});

	// on change field
	$('.field').on('change', function() {
		if(this.value !== ""){
			$('.field-'+this.id).removeClass('has-error').addClass('has-success');
			$(".pesan-"+this.id).text('');
		}
		else{
			$('.field-'+this.id).removeClass('has-error').removeClass('has-success');
			$(".pesan-"+this.id).text('');	
		}
	});

	var foto = $('#foto').dropify();
	foto.on('dropify.afterClear', function(event, element) {
        $('.field-foto').removeClass('has-error').removeClass('has-success');
		$(".pesan-foto").text('');
    });
});

/**
 * Function init
 * Proses inisialisasi saat onload page
 */
function init() {
	$('#status').select2({
    	placeholder: "Pilih Status",
		allowClear: true
	});
	setStatus();
	
	$('#submit_kas_kecil').prop('disabled', true);
	$('#id').prop('disabled', true);

	// input mask
    $('.input-mask-uang').inputmask({ 
    	alias : 'currency',
    	prefix: '', 
    	radixPoint: ',',
    	digits: 0,
    	groupSeparator: '.', 
    	clearMaskOnLostFocus: true, 
    	digitsOptional: false,
	});
}

/**
 * 
 */
function onClickAdd() {
	resetForm();

	getLastIncrement(function(response) {
		if(response.success) {
			$('#id').val(response.data);
		}

		$('.field-saldo').css('display', 'block');
		$('.field-password').css('display', 'block');
		$('.field-email').css('display', 'block');
		$('.field-password_confirm').css('display', 'block');
		$('.field-foto').css('display', 'block');

		$('#submit_kas_kecil').prop('value', 'action-add');
		$('#submit_kas_kecil').prop('disabled', false);
		$('#submit_kas_kecil').html('Simpan Data');
		$('#modalKasKecil').modal();
	});
}

/**
* Fungsi getDataForm()
* untuk mendapatkan semua value di field
* return berupa object data
*/
function getDataForm() {
	var data = new FormData();
	var status = ($('#status').val() != "" && $('#status').val() != null) ? $('#status').val().trim() : "";
	// var saldo = parseFloat($('#saldo').val().trim()) ? parseFloat($('#saldo').val().trim()) : $('#saldo').val().trim();

	var saldo = ($('#saldo').inputmask) ? 
		( parseFloat($('#saldo').inputmask('unmaskedvalue')) ?
			parseFloat($('#saldo').inputmask('unmaskedvalue')) : 
			$('#saldo').inputmask('unmaskedvalue')
		) : $('#saldo').val().trim();

	 if($('#submit_kas_kecil').val().trim().toLowerCase() == "action-add"){
	 	data.append('foto', $('#foto')[0].files[0]); //foto
	 	data.append('email', $('#email').val().trim()); // email kas kecil
		data.append('password', $('#password').val().trim()); // password kas kecil
		data.append('password_confirm', $('#password_confirm').val().trim()); // password kas kecil
	 	data.append('saldo', saldo); //saldo awal
	 }

	 if($('#submit_kas_kecil').val().trim().toLowerCase() == "action-edit"){
	 	data.append('id', $('#id').val().trim()); // id kas kecil
		data.append('nama', $('#nama').val().trim()); // nama kas kecil
		data.append('alamat', $('#alamat').val().trim()); // alamat kas kecil
		data.append('no_telp', $('#no_telp').val().trim()); // no_telp kas kecil
		data.append('email', $('#email').val().trim()); // email kas kecil
		data.append('status', status); // status kas kecil
		data.append('saldo', saldo); //saldo awal
		data.append('password', ''); // password kas kecil
		data.append('password_confirm', ''); // password kas kecil
	 } 
	 

	data.append('id', $('#id').val().trim()); // id kas kecil
	data.append('nama', $('#nama').val().trim()); // nama kas kecil
	data.append('alamat', $('#alamat').val().trim()); // alamat kas kecil
	data.append('no_telp', $('#no_telp').val().trim()); // no_telp kas kecil
	data.append('status', status); // status kas kecil
	data.append('action', $('#submit_kas_kecil').val().trim()); // action

	return data;
}

/**
*
*/
function submit() {
	var data = getDataForm();

	$.ajax({
		url: BASE_URL+'kas-kecil/'+$('#submit_kas_kecil').val().trim()+'/',
		type: 'POST',
		dataType: 'json',
		data: data,
		contentType: false,
		cache: false,
		processData: false,
		beforeSend: function() {
			$('#submit_kas_kecil').prop('disabled', true);
			$('#submit_kas_kecil').prepend('<i class="fa fa-spin fa-refresh"></i> ');
		},
		success: function(response) {
			console.log('%c Response submit Kas Kecil: ', logStyle.success, response);
			if(!response.success) {
				$('#submit_kas_kecil').prop('disabled', false);
				$('#submit_kas_kecil').html($('#submit_kas_kecil').text());
				
				setError(response.error);
			}
			else {
				resetForm();
				$("#modalKasKecil").modal('hide');
				$("#kasKecilTable").DataTable().ajax.reload();
			}
			setNotif(response.notif);
		},
		error: function (jqXHR, textStatus, errorThrown) {
            console.log('%c Response Error submit: ', logStyle.error, {
				jqXHR: jqXHR, 
				textStatus: textStatus, 
				errorThrown: errorThrown
			});
            swal("Pesan Gagal", "Terjadi Kesalahan Teknis, Silahkan Coba Kembali", "error");
            $('#submit_kas_kecil').prop('disabled', false);
			$('#submit_kas_kecil').html($('#submit_kas_kecil').text());
        }
	})
}

/**
*
*/
function getEdit(id) {
	resetForm();
	$('.field-saldo').css('display', 'none');
	$('.field-password').css('display', 'none');
	$('.field-email').css('display', 'none');
	$('.field-password_confirm').css('display', 'none');
	$('.field-foto').css('display', 'none');
	$('#submit_kas_kecil').prop('value', 'action-edit');
	$('#submit_kas_kecil').prop('disabled', false);
	$('#submit_kas_kecil').html('Edit Data');

	$.ajax({
		url: BASE_URL+'kas-kecil/edit/'+id.toLowerCase(),
		type: 'post',
		dataType: 'json',
		data: {},
		beforeSend: function() {
		},
		success: function(response) {
			if(response) {
				$('#modalKasKecil').modal();
				setValue(response);
			}	
		},
		error: function (jqXHR, textStatus, errorThrown) {
            console.log('%c Response Error getEdit: ', logStyle.error, {
				jqXHR: jqXHR, 
				textStatus: textStatus, 
				errorThrown: errorThrown
			});
            swal("Pesan Gagal", "Terjadi Kesalahan Teknis, Silahkan Coba Kembali", "error");
        }
	})
}

/**
*
*/
function setError(error) {
	$.each(error, function(index, item){
		console.log(index);

		if(item != ""){
			$('.field-'+index).removeClass('has-success').addClass('has-error');
			$('.pesan-'+index).text(item);
		}
		else{
			$('.field-'+index).removeClass('has-error').addClass('has-success');
			$('.pesan-'+index).text('');	
		}
	});
}

/**
*
*/
function setValue(value) {
	$.each(value, function(index, item){
		item = (parseFloat(item) && index != 'no_telp') ? (parseFloat(item)) : item;
		$('#'+index).val(item);
	});
}

/**
*
*/
function setStatus() {
	var status = [
		{value: "AKTIF", text: "AKTIF"},
		{value: "NONAKTIF", text: "NONAKTIF"},
	];

	$.each(status, function(index, item){
		var option = new Option(item.text, item.value);
		$("#status").append(option).trigger('change');
	});

	$('#status').val(null).trigger('change');
}

/**
*
*/
function resetForm() {
	// trigger reset form
	$('#form_kas_kecil').trigger('reset');
	$('#status').val(null).trigger('change');

	// hapus semua pesan
	$('.pesan').text('');

	// hapus semua feedback
	$('.form-group').removeClass('has-success').removeClass('has-error');

	// reset field foto
	var foto = $('#foto').dropify();
	foto = foto.data('dropify');
	foto.resetPreview();
	foto.clearElement();
}


/**
 * Method getLastIncrement
 */
function getLastIncrement(callback) {
	$.ajax({
		url: BASE_URL+'kas-kecil/get-increment/',
		type: 'post',
		dataType: 'json',
		data: {},
		beforeSend: function() {
		},
		success: function(response) {
			console.log('%c Response getLastIncrement: ', logStyle.success, response);
			
			callback({
				success: true,
				data: response
			});	
		},
		error: function (jqXHR, textStatus, errorThrown) {
            console.log('%c Response Error getLastIncrement: ', logStyle.error, {
				jqXHR: jqXHR, 
				textStatus: textStatus, 
				errorThrown: errorThrown
			});

			swal("Pesan Gagal", "Terjadi Kesalahan Teknis, Silahkan Coba Kembali", "error");

			callback({success: false});
        }
	})
}