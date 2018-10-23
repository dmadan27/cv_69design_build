$(document).ready(function(){
	setStatus();
	$('#submit_bank').prop('disabled', true);

	// button tambah
	$('#tambah').on('click', function(){
		resetForm();
		$('.field-saldo').css('display', 'block');
		$('#submit_bank').prop('value', 'action-add');
		$('#submit_bank').prop('disabled', false);
		$('#submit_bank').html('Simpan Data');
		$('#modalBank').modal();
	});

	// submit bank
	$('#form_bank').submit(function(e){
		e.preventDefault();
		submit();

		return false;
	});

	// on change field
	$('.field').on('change', function(){
		if(this.value !== ""){
			$('.field-'+this.id).removeClass('has-error').addClass('has-success');
			$(".pesan-"+this.id).text('');
		}
		else{
			$('.field-'+this.id).removeClass('has-error').removeClass('has-success');
			$(".pesan-"+this.id).text('');	
		}
	});

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

});

/**
 * Function getDataForm
 * Proses mendapatkan semua value di field
 * @return {FormData} data
 */
function getDataForm(){
	var data = new FormData();

	var saldo = ($('#saldo').inputmask) ? 
		( parseFloat($('#saldo').inputmask('unmaskedvalue')) ?
			parseFloat($('#saldo').inputmask('unmaskedvalue')) : 
			$('#saldo').inputmask('unmaskedvalue')
		) : $('#saldo').val().trim();
		
	if($('#submit_bank').val().trim().toLowerCase() == "action-edit") data.append('id', $('#id').val().trim());

	data.append('nama', $('#nama').val().trim()); // nama bank
	data.append('saldo', saldo); // saldo awal
	data.append('status', $('#status').val().trim()); // status bank
	data.append('action', $('#submit_bank').val().trim()); // action

	return data;
}

/**
 * Function submit
 * Proses submit data ke server baik saat add / edit
 * @return {object} response
 */
function submit(){
	var data = getDataForm();

	$.ajax({
		url: BASE_URL+'bank/'+$('#submit_bank').val().trim()+'/',
		type: 'POST',
		dataType: 'json',
		data: data,
		contentType: false,
		cache: false,
		processData: false,
		beforeSend: function(){
			$('#submit_bank').prop('disabled', true);
			$('#submit_bank').prepend('<i class="fa fa-spin fa-refresh"></i> ');
		},
		success: function(response){
			console.log('Response submit Bank: ', response);
			if(!response.status) {
				$('#submit_bank').prop('disabled', false);
				$('#submit_bank').html($('#submit_bank').text());
				setError(response.error);
				toastr.warning(response.notif.message, response.notif.title);
			}
			else{
				toastr.success(response.notif.message, response.notif.title);
				resetForm();
				$("#modalBank").modal('hide');
				$("#bankTable").DataTable().ajax.reload();
			}
		},
		error: function (jqXHR, textStatus, errorThrown){ // error handling
			$("#modalBank").modal('hide');
            console.log('Response Error submit Bank: ', jqXHR, textStatus, errorThrown);
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
function getEdit(id){
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
		beforeSend: function(){
		},
		success: function(response){
			if('Response getEdit Bank: ', response){
				$('#modalBank').modal();
				setValue(response);
			}	
		},
		error: function (jqXHR, textStatus, errorThrown){ // error handling
            console.log('Response Error getEdit Bank: ', jqXHR, textStatus, errorThrown);
            swal("Pesan Gagal", "Terjadi Kesalahan Teknis, Silahkan Coba Kembali", "error");
        }
	})
	
}

/**
 * Function setError
 * Proses menampilkan pesan error di field-field yang terdapat kesalahan 
 * @param {object} error 
 */
function setError(error){
	$.each(error, function(index, item){
		// console.log(index);

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
 * Function setValue
 * Proses menampilkan value ke field-field yang dibutuhkan
 * @param {object} value 
 */
function setValue(value){
	$.each(value, function(index, item){
		item = (parseFloat(item)) ? (parseFloat(item)) : item;
		$('#'+index).val(item);
	});
}

/**
 * Function setStatus
 * Proses pengisian value pada select status
 */
function setStatus(){
	var status = [
		{value: "AKTIF", text: "AKTIF"},
		{value: "NONAKTIF", text: "NONAKTIF"},
	];

	$.each(status, function(index, item){
		var option = new Option(item.text, item.value);
		$("#status").append(option);
	});
}

/**
 * Function resetForm
 * Proses reset form
 */
function resetForm(){
	// trigger reset form
	$('#form_bank').trigger('reset');

	// hapus semua pesan
	$('.pesan').text('');

	// hapus semua feedback
	$('.form-group').removeClass('has-success').removeClass('has-error');
}