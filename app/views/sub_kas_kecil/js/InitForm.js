$(document).ready(function(){
	setStatus();
 	var foto = $('#foto').dropify();
	// $('#submit_skc').prop('disabled', true);

	// btn tambah
	$('#tambah').on('click', function(){
		if(this.value.trim() != ""){
			// resetForm();
			$('.field-saldo').css('display', 'block');
			$('#submit_skc').prop('value', 'action-add');
			// $('#submit_skc').prop('disabled', false);
			$('#submit_skc').html('Simpan Data');
			$('#token_form').val(this.value);
			$('#modalSkc').modal();
		}
		else swal("Pesan Gagal", "Terjadi Kesalahan Teknis, Silahkan Coba Kembali", "error");
	});

	// submit skc
	$('#form_skc').submit(function(e){
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

	foto.on('dropify.afterClear', function(event, element) {
        $('.field-foto').removeClass('has-error').removeClass('has-success');
		$(".pesan-foto").text('');
    });

});

/**
*
*/
function getDataForm(){
	var data = new FormData();
	var saldo = parseFloat($('#saldo').val().trim()) ? parseFloat($('#saldo').val().trim()) : $('#saldo').val().trim();

	data.append('token', $('#token_form').val().trim());
	data.append('id', $('#id').val().trim()); // id
	data.append('nama', $('#nama').val().trim()); // nama
	data.append('alamat', $('#alamat').val().trim()); // alamat
	data.append('no_telp', $('#no_telp').val().trim()); // no_telp
	data.append('foto', $("#foto")[0].files[0]); // foto
	data.append('email', $('#email').val().trim()); // email
	data.append('password', $('#password').val().trim()); // password
	data.append('konf_password', $('#konf_password').val().trim()); // konf_password
	data.append('saldo', saldo); // saldo awal
	data.append('status', $('#status').val().trim()); // status
	data.append('action', $('#submit_skc').val().trim()); // action

	return data;
}

/**
*
*/
function submit(edit_view){
	var data = getDataForm();

	$.ajax({
		url: BASE_URL+'sub-kas-kecil/'+$('#submit_skc').val().trim()+'/',
		type: 'POST',
		dataType: 'json',
		data: data,
		contentType: false,
		cache: false,
		processData: false,
		beforeSend: function(){
			$('#submit_skc').prop('disabled', true);
			$('#submit_skc').prepend('<i class="fa fa-spin fa-refresh"></i> ');
		},
		success: function(output){
			console.log(output);
			if(!output.status) {
				$('#submit_skc').prop('disabled', false);
				$('#submit_skc').html($('#submit_skc').text());
				setError(output.error);
				toastr.warning(output.notif.message, output.notif.title);
			}
			else{
				toastr.success(output.notif.message, output.notif.title);
				resetForm();
				$("#modalSkc").modal('hide');
				if(!edit_view) $("#skcTable").DataTable().ajax.reload();
				else {
					setTimeout(function(){ 
						location.reload(); 
					}, 1000);
				}
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
function getEdit(id, token){
	if(token != ""){
		resetForm();
		$('.field-saldo').css('display', 'none');
		$('.field-foto').css('display', 'none');
		$('#submit_skc').prop('value', 'action-edit');
		$('#submit_skc').prop('disabled', false);
		$('#submit_skc').html('Edit Data');

		$.ajax({
			url: BASE_URL+'sub-kas-kecil/edit/'+id,
			type: 'post',
			dataType: 'json',
			data: {"token_edit": token},
			beforeSend: function(){

			},
			success: function(output){
				if(output){
					$('#modalSkc').modal();
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
	$('#form_skc').trigger('reset');

	// hapus semua pesan
	$('.pesan').text('');

	// hapus semua feedback
	$('.form-group').removeClass('has-success').removeClass('has-error');
}