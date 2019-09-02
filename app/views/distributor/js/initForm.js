$(document).ready(function() {
	init();

	// button tambah
	$('#tambah').on('click', function() {
		onClickAdd();
	});

	// submit bank
	$('#form_distributor').submit(function(e) {
		e.preventDefault();
		submit();

		return false;
	});

	// on change field
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
	
	$('#id').prop('disabled', true);
	$('#submit_distributor').prop('disabled', true);
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

		$('#submit_distributor').prop('value', 'action-add');
		$('#submit_distributor').prop('disabled', false);
		$('#submit_distributor').html('Simpan Data');
		$('#modalDistributor').modal();
	});
}

/**
* Fungsi getDataForm()
* untuk mendapatkan semua value di field
* return berupa object data
*/
function getDataForm() {
	var data = new FormData();
	let status = ($('#status').val() != "" && $('#status').val() != null) ? $('#status').val().trim() : "";

	if($('#submit_distributor').val().trim().toLowerCase() == "action-edit"){
		// data.append('id', $('#id').val().trim());
		data.append('nama', $('#nama').val().trim()); // nama distributor
		data.append('alamat', $('#alamat').val().trim()); // alamat distributor
		data.append('no_telp', $('#no_telp').val().trim()); // no_telp distributor
		data.append('pemilik', $('#pemilik').val().trim()); // pemilik distributor
		data.append('status', $('#status').val().trim()); // status distributor
	} 
	// data.append('id', $('#id').val().trim());
		
	// if($('#submit_bank').val().trim().toLowerCase() == "action-edit") data.append('id', $('#id').val().trim());
	data.append('id', $('#id').val().trim());
	data.append('nama', $('#nama').val().trim()); // nama distributor
	data.append('alamat', $('#alamat').val().trim()); // alamat distributor
	data.append('no_telp', $('#no_telp').val().trim()); // no_telp distributor
	data.append('pemilik', $('#pemilik').val().trim()); // pemilik distributor
	data.append('status', status); // status distributor
	data.append('action', $('#submit_distributor').val().trim()); // action

	return data;
}

/**
*
*/
function submit() {
	var data = getDataForm();

	$.ajax({
		url: BASE_URL+'distributor/'+$('#submit_distributor').val().trim()+'/',
		type: 'POST',
		dataType: 'json',
		data: data,
		contentType: false,
		cache: false,
		processData: false,
		beforeSend: function() {
			$('#submit_distributor').prop('disabled', true);
			$('#submit_distributor').prepend('<i class="fa fa-spin fa-refresh"></i> ');
		},
		success: function(response) {
			console.log('%c Response submit Distributor: ', logStyle.success, response);

			if(!response.status) {
				$('#submit_distributor').prop('disabled', false);
				$('#submit_distributor').html($('#submit_distributor').text());
				setError(response.error);
			}
			else {
				resetForm();
				$("#modalDistributor").modal('hide');
				$("#distributorTable").DataTable().ajax.reload();
			}
			setNotif(response.notif);
		},
		error: function (jqXHR, textStatus, errorThrown) {
			$("#modalDistributor").modal('hide');
			console.log('%c Response Error submit: ', logStyle.error, {
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
function getEdit(id) {
	console.log('Button Edit Distributor Clicked...');

	resetForm();
	
	$('#submit_distributor').prop('value', 'action-edit');
	$('#submit_distributor').prop('disabled', false);
	$('#submit_distributor').html('Edit Data');

	$.ajax({
		url: BASE_URL+'distributor/edit/'+id.toLowerCase(),
		type: 'post',
		dataType: 'json',
		data: {},
		beforeSend: function() {
		},
		success: function(response) {
			console.log('%c Response getEdit Distributor: ', logStyle.success, response);

			if(response) {
				$('#modalDistributor').modal();
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
	$.each(error, function(index, item) {
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
	$.each(value, function(index, item) {
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
	$('#form_distributor').trigger('reset');
	$('#status').val(null).trigger('change');
	
	// hapus semua pesan
	$('.pesan').text('');

	// hapus semua feedback
	$('.form-group').removeClass('has-success').removeClass('has-error');
}

/**
 * Method getLastIncrement
 */
function getLastIncrement(callback) {
	$.ajax({
		url: BASE_URL+'distributor/get-increment/',
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