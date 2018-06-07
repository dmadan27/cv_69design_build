$(document).ready(function(){
	setStatus();
	$('#submit_bank').prop('disabled', true);

	// button tambah
	$('#tambah').on('click', function(){
		if(this.value.trim() != ""){
			resetForm();
			$('.field-saldo').css('display', 'block');
			$('#submit_bank').prop('value', 'action-add');
			$('#submit_bank').prop('disabled', false);
			$('#submit_bank').html('Simpan Data');
			$('#token_form').val(this.value);
			$('#modalBank').modal();
		}
		else swal("Pesan Gagal", "Terjadi Kesalahan Teknis, Silahkan Coba Kembali", "error");
	});

	// submit bank
	$('#form_bank').submit(function(e){
		e.preventDefault();
		submit(edit_view);

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

});

/**
* Fungsi getDataForm()
* untuk mendapatkan semua value di field
* return berupa object data
*/
function getDataForm(){
	var data = new FormData();
	var saldo = parseFloat($('#saldo').val().trim()) ? parseFloat($('#saldo').val().trim()) : $('#saldo').val().trim();

	if($('#submit_bank').val().trim().toLowerCase() == "action-edit") data.append('id', $('#id').val().trim());

	data.append('token', $('#token_form').val().trim());
	data.append('nama', $('#nama').val().trim()); // nama bank
	data.append('saldo', saldo); // saldo awal
	data.append('status', $('#status').val().trim()); // status bank
	data.append('action', $('#submit_bank').val().trim()); // action

	return data;
}

/**
*
*/
function submit(edit_view){
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
		success: function(output){
			console.log(output);
			if(!output.status) {
				$('#submit_bank').prop('disabled', false);
				$('#submit_bank').html($('#submit_bank').text());
				setError(output.error);
				toastr.warning(output.notif.message, output.notif.title);
			}
			else{
				toastr.success(output.notif.message, output.notif.title);
				resetForm();
				$("#modalBank").modal('hide');
				if(!edit_view) $("#bankTable").DataTable().ajax.reload();
				else {
					setTimeout(function(){ 
						location.reload(); 
					}, 1000);
				}
			}
		},
		error: function (jqXHR, textStatus, errorThrown){ // error handling
			$("#modalBank").modal('hide');
            console.log(jqXHR, textStatus, errorThrown);
            swal("Pesan Gagal", "Terjadi Kesalahan Teknis, Silahkan Coba Kembali", "error");
        }
	})
}

/**
*
*/
function getEdit(id, token){
	if(token != ""){
		resetForm();
		$('.field-saldo').css('display', 'none');
		$('#submit_bank').prop('value', 'action-edit');
		$('#submit_bank').prop('disabled', false);
		$('#submit_bank').html('Edit Data');

		$.ajax({
			url: BASE_URL+'bank/edit/'+id.toLowerCase(),
			type: 'post',
			dataType: 'json',
			data: {"token_edit": token},
			beforeSend: function(){

			},
			success: function(output){
				if(output){
					$('#modalBank').modal();
					$('#token_form').val(token);
					setValue(output);
				}	
			},
			error: function (jqXHR, textStatus, errorThrown){ // error handling
	            console.log(jqXHR, textStatus, errorThrown);
	        }
		})
	}
	else swal("Pesan Gagal", "Terjadi Kesalahan Teknis, Silahkan Coba Kembali", "error");
}

/**
*
*/
function setError(error){
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
function setValue(value){
	$.each(value, function(index, item){
		item = (parseFloat(item)) ? (parseFloat(item)) : item;
		$('#'+index).val(item);
	});
}

/**
*
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
*
*/
function resetForm(){
	// trigger reset form
	$('#form_bank').trigger('reset');

	// hapus semua pesan
	$('.pesan').text('');

	// hapus semua feedback
	$('.form-group').removeClass('has-success').removeClass('has-error');
}