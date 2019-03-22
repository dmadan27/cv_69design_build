$(document).ready(function () {
	setStatus();
	var foto = $('#foto').dropify();
	$('#submit_skc').prop('disabled', true);
	$('#id').prop('disabled', true);

	// btn tambah
	$('#tambah').on('click', function () {
		resetForm();
		$('.field-saldo').css('display', 'block');
		$('#submit_skc').prop('value', 'action-add');
		$('#submit_skc').prop('disabled', false);
		$('#submit_skc').html('Simpan Data');
		// $('#token_form').val(this.value);
		generateID();
		$('#modalSkc').modal();
	});

	// submit skc
	$('#form_skc').submit(function (e) {
		e.preventDefault();
		submit();
		// save_foto();

		return false;
	});

	// on change field
	$('.field').on('change', function () {
		if (this.value !== "") {
			$('.field-' + this.id).removeClass('has-error').addClass('has-success');
			$(".pesan-" + this.id).text('');
		}
		else {
			$('.field-' + this.id).removeClass('has-error').removeClass('has-success');
			$(".pesan-" + this.id).text('');
		}
	});

	foto.on('dropify.afterClear', function (event, element) {
		$('.field-foto').removeClass('has-error').removeClass('has-success');
		$(".pesan-foto").text('');
	});

	// input mask
	$('.input-mask-uang').inputmask({
		alias: 'currency',
		prefix: '',
		radixPoint: ',',
		digits: 0,
		groupSeparator: '.',
		clearMaskOnLostFocus: true,
		digitsOptional: false,
	});

});

/**
 * 
 */
function getDataForm() {
	var data = new FormData();
	// var saldo = parseFloat($('#saldo').val().trim()) ? parseFloat($('#saldo').val().trim()) : $('#saldo').val().trim();

	var saldo = ($('#saldo').inputmask) ?
		(parseFloat($('#saldo').inputmask('unmaskedvalue')) ?
			parseFloat($('#saldo').inputmask('unmaskedvalue')) :
			$('#saldo').inputmask('unmaskedvalue')
		) : $('#saldo').val().trim();

	if ($('#submit_skc').val().trim().toLowerCase() == 'action-add') {
		data.append('foto', $("#foto")[0].files[0]); // foto
		data.append('password', $('#password').val().trim()); // password kas kecil
		data.append('password_confirm', $('#password_confirm').val().trim()); // password kas kecil
		data.append('saldo', saldo); // saldo awal	
	}

	if ($('#submit_skc').val().trim().toLowerCase() == 'action-edit') {
		data.append('nama', $('#nama').val().trim()); // nama
		data.append('alamat', $('#alamat').val().trim()); // alamat
		data.append('no_telp', $('#no_telp').val().trim()); // no_telp
		data.append('email', $('#email').val().trim()); // email
		data.append('saldo', saldo);
		data.append('password', ''); // password kas kecil
		data.append('password_confirm', ''); // password kas kecil
		data.append('status', $('#status').val().trim()); // status

	}

	// data.append('token', $('#token_form').val().trim());
	data.append('id', $('#id').val().trim()); // id
	data.append('nama', $('#nama').val().trim()); // nama
	data.append('alamat', $('#alamat').val().trim()); // alamat
	data.append('no_telp', $('#no_telp').val().trim()); // no_telp
	data.append('email', $('#email').val().trim()); // email
	data.append('status', $('#status').val().trim()); // status
	data.append('action', $('#submit_skc').val().trim()); // action

	return data;
}

/**
*
*/
function submit() {
	var data = getDataForm();

	$.ajax({
		url: BASE_URL + 'sub-kas-kecil/' + $('#submit_skc').val().trim() + '/',
		type: 'POST',
		dataType: 'json',
		data: data,
		contentType: false,
		cache: false,
		processData: false,
		beforeSend: function () {
			$('#submit_skc').prop('disabled', true);
			$('#submit_skc').prepend('<i class="fa fa-spin fa-refresh"></i> ');
		},
		success: function (response) {
			console.log(response);
			if (!response.success) {
				$('#submit_skc').prop('disabled', false);
				$('#submit_skc').html($('#submit_skc').text());
				setError(response.error);
			}
			else {
				resetForm();
				$("#modalSkc").modal('hide');
				$("#skcTable").DataTable().ajax.reload();
			}
			toastr.warning(response.notif.message, response.notif.title);
		},
		error: function (jqXHR, textStatus, errorThrown) { // error handling
			console.log(jqXHR, textStatus, errorThrown);
			swal("Pesan Gagal", "Terjadi Kesalahan Teknis, Silahkan Coba Kembali", "error");
			$('#submit_skc').prop('disabled', false);
			$('#submit_skc').html($('#submit_skc').text());
		}
	});
}

/**
 * 
 */
function save_foto() {
	var data = new FormData();
	data.append('id', $('#id').val().trim()); // id
	data.append('foto', $("#foto")[0].files[0]); // foto

	$.ajax({
		url: BASE_API_MOBILE + 'form/update_foto_profil/',
		type: 'POST',
		dataType: 'json',
		data: data,
		contentType: false,
		cache: false,
		processData: false,
		beforeSend: function () {
			$('#submit_skc').prop('disabled', true);
			$('#submit_skc').prepend('<i class="fa fa-spin fa-refresh"></i> ');
		},
		success: function (response) {
			// console.log(response);
			// if(!response.success) {
			// 	$('#submit_skc').prop('disabled', false);
			// 	$('#submit_skc').html($('#submit_skc').text());
			// 	setError(response.error);
			// }
			// else{
			// 	resetForm();
			// 	$("#modalSkc").modal('hide');
			// 	$("#skcTable").DataTable().ajax.reload();
			// }
			// toastr.warning(response.notif.message, response.notif.title);

			console.log('%cResponse save foto: ', 'color: green; font-weight: bold', response);
		},
		error: function (jqXHR, textStatus, errorThrown) { // error handling
			console.log(jqXHR, textStatus, errorThrown);
			swal("Pesan Gagal", "Terjadi Kesalahan Teknis, Silahkan Coba Kembali", "error");
			$('#submit_skc').prop('disabled', false);
			$('#submit_skc').html($('#submit_skc').text());
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
	$('.field-password_confirm').css('display', 'none');
	$('.field-foto').css('display', 'none');
	$('#submit_skc').prop('value', 'action-edit');
	$('#submit_skc').prop('disabled', false);
	$('#submit_skc').html('Edit Data');

	$.ajax({
		url: BASE_URL + 'sub-kas-kecil/edit/' + id.toLowerCase(),
		type: 'post',
		dataType: 'json',
		data: {},
		beforeSend: function () {

		},
		success: function (output) {
			if (output) {
				$('#modalSkc').modal();
				// $('#token_form').val(token);
				setValue(output);
			}
		},
		error: function (jqXHR, textStatus, errorThrown) { // error handling
			console.log(jqXHR, textStatus, errorThrown);
		}
	});
}

/**
*
*/
function setError(error) {
	$.each(error, function (index, item) {
		console.log(index);

		if (item != "") {
			$('.field-' + index).removeClass('has-success').addClass('has-error');
			$('.pesan-' + index).text(item);
		}
		else {
			$('.field-' + index).removeClass('has-error').addClass('has-success');
			$('.pesan-' + index).text('');
		}
	});
}

/**
*
*/
function setValue(value) {
	$.each(value, function (index, item) {
		item = (parseFloat(item)) ? (parseFloat(item)) : item;
		$('#' + index).val(item);
	});
}

/**
*
*/
function setStatus() {
	var status = [
		{ value: "AKTIF", text: "AKTIF" },
		{ value: "NONAKTIF", text: "NONAKTIF" },
	];

	$.each(status, function (index, item) {
		var option = new Option(item.text, item.value);
		$("#status").append(option);
	});
}

/**
*
*/
function generateID() {
	$.ajax({
		url: BASE_URL + 'sub-kas-kecil/get-last-id/',
		// type: 'post',
		// dataType: 'json',
		// data: {"token_edit": token},
		beforeSend: function () {

		},
		success: function (output) {
			// if(output){
			// 	$('#modalSkc').modal();
			// 	$('#token_form').val(token);
			// 	setValue(output);
			// }
			$('#id').val(output);
		},
		error: function (jqXHR, textStatus, errorThrown) { // error handling
			console.log(jqXHR, textStatus, errorThrown);
		}
	})
}

/**
*
*/
function resetForm() {
	// trigger reset form
	$('#form_skc').trigger('reset');

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