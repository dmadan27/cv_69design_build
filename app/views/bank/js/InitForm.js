$(document).ready(function(){
	// button tambah
	$('#tambah').on('click', function(){
		$('.field-saldo').css('display', 'block');
		$('#submit_bank').prop('value', 'tambah');
		resetForm();
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
});

/**
*
*/
function getDataForm(){
	var data = new FormData();
	var saldo = parseFloat($('#saldo').val().trim()) ? parseFloat($('#saldo').val().trim()) : "";

	data.append('nama', $('#nama').val().trim()); // nama bank
	data.append('saldo', saldo); // saldo awal
	data.append('action', $('#submit_bank').val().trim()); // action

	return data;
}

/**
*
*/
function submit(){
	var data = getDataForm();

	$.ajax({
		url: BASE_URL+'bank/action-add/',
		type: 'POST',
		dataType: 'json',
		data: data,
		contentType: false,
		cache: false,
		processData: false,
		beforeSend: function(){
			$('#submit_bank').prop('disabled', true);
			$('#submit_bank').prepend('<i class="fa fa-spin fa-refresh"></i>&nbsp; ');
		},
		success: function(output){
			console.log(output);
			$('#submit_bank').prop('disabled', false);
			$('#submit_bank').html('Simpan Data');
			if(!output.status) {
				setError(output.error);
				toastr.warning(output.notif.message, output.notif.title);
			}
			else{
				toastr.success(output.notif.message, output.notif.title);
				resetForm();
				$("#modalBank").modal('hide');
				$("#bankTable").DataTable().ajax.reload();
			}
		},
		error: function (jqXHR, textStatus, errorThrown){ // error handling
            console.log(jqXHR, textStatus, errorThrown);
        }
	})
}

/**
*
*/
function getEdit(id){
	$('#modalBank').modal();
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
function resetForm(){
	// trigger reset form
	$('#form_bank').trigger('reset');

	// hapus semua pesan
	$('.pesan').text('');

	// hapus semua feedback
	$('.form-group').removeClass('has-success').removeClass('has-error');
}