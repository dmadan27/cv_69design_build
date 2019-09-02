$(document).ready(function() {
	init();

	// event on click button tambah
	$('#tambah').on('click', function() {
		console.log('Button Tambah Bank Clicked...');
		onClickAdd();
	});

	// event on submit form bank
	$('#form_bank').submit(function(e) {
		console.log('Submit Bank Clicked...');
		
		e.preventDefault();
		submit();

		return false;
	});

	// event on change field
	$('.field').on('change', function() {
		onChangeField(this);
	});
});

/**
 * 
 */
function init() {
	$('#status').select2({
    	placeholder: "Pilih Status",
		allowClear: true
	});

	setStatus();
	
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
	
	$('#submit_bank').prop('disabled', true);
}

/**
 * 
 */
function onClickAdd() {
	if(LEVEL !== 'KAS BESAR') {
		setNotif(notifAccessDenied, 'swal')
		return
	}

	resetForm();
	$('.field-saldo').css('display', 'block');
	$('#submit_bank').prop('value', 'action-add');
	$('#submit_bank').prop('disabled', false);
	$('#submit_bank').html('Simpan Data');
	$('#modalBank').modal({backdrop: 'static'});
}

/**
 * Function getDataForm
 * Proses mendapatkan semua value di field
 * @return {FormData} data
 */
function getDataForm() {
	var data = new FormData();

	var saldo = ($('#saldo').inputmask) ? 
		( parseFloat($('#saldo').inputmask('unmaskedvalue')) ?
			parseFloat($('#saldo').inputmask('unmaskedvalue')) : 
			$('#saldo').inputmask('unmaskedvalue')
		) : $('#saldo').val().trim();
		
	if($('#submit_bank').val().trim().toLowerCase() == "action-edit") data.append('id', $('#id').val().trim());

	let status = ($('#status').val() != "" && $('#status').val() != null) ? $('#status').val().trim() : "";
	
	data.append('nama', $('#nama').val().trim()); // nama bank
	data.append('saldo', saldo); // saldo awal
	data.append('status', status); // status bank
	data.append('action', $('#submit_bank').val().trim()); // action

	return data;
}

/**
 * Function submit
 * Proses submit data ke server baik saat add / edit
 * @return {object} response
 */
function submit() {
	var data = getDataForm();

	$.ajax({
		url: BASE_URL+'bank/'+$('#submit_bank').val().trim()+'/',
		type: 'POST',
		dataType: 'json',
		data: data,
		contentType: false,
		cache: false,
		processData: false,
		beforeSend: function() {
			$('#submit_bank').prop('disabled', true);
			$('#submit_bank').prepend('<i class="fa fa-spin fa-refresh"></i> ');
		},
		success: function(response) {
			console.log('%c Response submit Bank: ', logStyle.success, response);
			if(!response.success) {
				$('#submit_bank').prop('disabled', false);
				$('#submit_bank').html($('#submit_bank').text());
				setError(response.error);
			}
			else {
				resetForm();
				$("#modalBank").modal('hide');
				$("#bankTable").DataTable().ajax.reload();
			}
			setNotif(response.notif);
		},
		error: function (jqXHR, textStatus, errorThrown) {
			$("#modalBank").modal('hide');
			console.log('%c Response Error submit Bank: ', logStyle.error, {
				jqXHR: jqXHR, 
				textStatus: textStatus, 
				errorThrown: errorThrown
			});
            swal("Pesan Gagal", "Terjadi Kesalahan Teknis, Silahkan Coba Kembali", "error");
        }
	})
}

/**
 * Function getEdit
 * Proses request get data bank untuk proses edit
 * @param {string} id
 * @return {object} response
 */
function getEdit(id) {
	console.log('Button Edit Bank Clicked...');

	resetForm();
	$('.field-saldo').css('display', 'none');
	$('#submit_bank').prop('value', 'action-edit');
	$('#submit_bank').prop('disabled', false);
	$('#submit_bank').html('Edit Data');

	$.ajax({
		url: BASE_URL+'bank/edit/'+id.toLowerCase(),
		type: 'post',
		dataType: 'json',
		data: {},
		beforeSend: function() {
		},
		success: function(response) {
			console.log('%c Response Get Edit Bank: ', logStyle.success, response);
			setValue(response);
			$('#modalBank').modal({backdrop: 'static'});
		},
		error: function (jqXHR, textStatus, errorThrown) {
            console.log('%c Response Error getEdit Bank: ', logStyle.error, {
				jqXHR: jqXHR, 
				textStatus: textStatus, 
				errorThrown: errorThrown
			});
			swal("Pesan Gagal", "Terjadi Kesalahan Teknis, Silahkan Coba Kembali", "error");
			$('#modalBank').modal('hide');
        }
	})
	
}

/**
 * Function setError
 * Proses menampilkan pesan error di field-field yang terdapat kesalahan 
 * @param {object} error 
 */
function setError(error) {
	$.each(error, function(index, item) {
		if(item != ""){
			$('.field-'+index).removeClass('has-success').addClass('has-error');
			$('.pesan-'+index).text(item);
		}
		else {
			$('.field-'+index).removeClass('has-error').addClass('has-success');
			$('.pesan-'+index).text('');	
		}
	});
}

/**
 * Function setValue
 * Proses menampilkan value ke field-field yang dibutuhkan
 * @param {object} value 
 */
function setValue(value) {
	$.each(value, function(index, item) {
		item = (parseFloat(item)) ? (parseFloat(item)) : item;
		if(index == 'status') {
			$('#'+index).val(item).trigger('change');
		} 
		else {
			$('#'+index).val(item);
		}
	});
}

/**
 * Function setStatus
 * Proses pengisian value pada select status
 */
function setStatus() {
	var status = [
		{value: "AKTIF", text: "AKTIF"},
		{value: "NONAKTIF", text: "NONAKTIF"},
	];

	$.each(status, function(index, item) {
		var option = new Option(item.text, item.value);
		$("#status").append(option).trigger('change');
	});

	$('#status').val(null).trigger('change');
}

/**
 * Function resetForm
 * Proses reset form
 */
function resetForm() {
	// trigger reset form
	$('#form_bank').trigger('reset');
	$('#status').val(null).trigger('change');

	// hapus semua pesan
	$('.pesan').text('');

	// hapus semua feedback
	$('.form-group').removeClass('has-success').removeClass('has-error');
}