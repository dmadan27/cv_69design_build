$(document).ready(function(){
	setStatus();
	
	$('#submit_kas_besar').prop('disabled', true);
	$('#id').prop('disabled', true);

	// button tambah
	$('#tambah').on('click', function(){
		
			resetForm();
			$('.field-saldo').css('display', 'block');
			$('.field-password').css('display', 'block');
			$('.field-email').css('display', 'block');
			$('.field-password_confirm').css('display', 'block');
			$('.field-foto').css('display', 'block');
			// $('#token_form').val(this.value);
			generateID();
			$('#submit_kas_besar').prop('value', 'action-add');
			$('#submit_kas_besar').prop('disabled', false);
			$('#submit_kas_besar').html('Simpan Data');	
			$('#modalKasBesar').modal();
		
		
	});

	// submit kas besar
	$('#form_kas_besar').submit(function(e){
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

	var foto = $('#foto').dropify();
	foto.on('dropify.afterClear', function(event, element) {
        $('.field-foto').removeClass('has-error').removeClass('has-success');
		$(".pesan-foto").text('');
    });



});

/**
* Fungsi getDataForm()
* untuk mendapatkan semua value di field
* return berupa object data
*/
function getDataForm(){
	var data = new FormData();
	// var saldo = parseFloat($('#saldo').val().trim()) ? parseFloat($('#saldo').val().trim()) : $('#saldo').val().trim();

	 if($('#submit_kas_besar').val().trim().toLowerCase() == "action-add"){
	 	data.append('foto', $('#foto')[0].files[0]); //foto
	 	data.append('email', $('#email').val().trim()); // email kas besar
		data.append('password', $('#email').val().trim()); // email kas besar
	 	// data.append('saldo',saldo); //saldo awal
	 }

	 if($('#submit_kas_besar').val().trim().toLowerCase() == "action-edit"){
	 	data.append('id', $('#id').val().trim()); // id kas besar
		data.append('nama', $('#nama').val().trim()); // nama kas besar
		data.append('alamat', $('#alamat').val().trim()); // alamat kas besar
		data.append('no_telp', $('#no_telp').val().trim()); // no_telp kas besar
		data.append('email', $('#email').val().trim()); // email kas besar
		data.append('status', $('#status').val().trim()); // status kas besar
		// data.append('saldo',saldo); //saldo awal
	 } 
	 
	// data.append('token', $('#token_form').val().trim());
	data.append('id', $('#id').val().trim()); // id kas besar
	data.append('nama', $('#nama').val().trim()); // nama kas besar
	data.append('alamat', $('#alamat').val().trim()); // alamat kas besar
	data.append('no_telp', $('#no_telp').val().trim()); // no_telp kas besar
	data.append('status', $('#status').val().trim()); // status kas besar
	data.append('action', $('#submit_kas_besar').val().trim()); // action

	return data;
}

/**
*
*/
function submit(edit_view){
	var data = getDataForm();

	$.ajax({
		url: BASE_URL+'kas-besar/'+$('#submit_kas_besar').val().trim()+'/',
		type: 'POST',
		dataType: 'json',
		data: data,
		contentType: false,
		cache: false,
		processData: false,
		beforeSend: function(){
			$('#submit_kas_besar').prop('disabled', true);
			$('#submit_kas_besar').prepend('<i class="fa fa-spin fa-refresh"></i> ');
		},
		success: function(output){
			console.log(output);
			if(!output.status) {
				$('#submit_kas_besar').prop('disabled', false);
				$('#submit_kas_besar').html($('#submit_kas_besar').text());
				setError(output.error);
				toastr.warning(output.notif.message, output.notif.title);
			}
			else{
				toastr.success(output.notif.message, output.notif.title);
				resetForm();
				$("#modalKasBesar").modal('hide');
				if(!edit_view) $("#kasBesarTable").DataTable().ajax.reload();
				else {
					setTimeout(function(){ 
						location.reload(); 
					}, 1000);
				}
			}
		},
		error: function (jqXHR, textStatus, errorThrown){ // error handling
            console.log(jqXHR, textStatus, errorThrown);
            swal("Pesan Gagal", "Terjadi Kesalahan Teknis, Silahkan Coba Kembali", "error");
        }
	})
}

/**
*
*/
function getEdit(id){
	if(token != ""){
		resetForm();
		// $('.field-saldo').css('display', 'none');
		$('.field-password').css('display', 'none');
		$('.field-email').css('display', 'none');
		$('.field-password_confirm').css('display', 'none');
		$('.field-foto').css('display', 'none');
		$('#submit_kas_besar').prop('value', 'action-edit');
		$('#submit_kas_besar').prop('disabled', false);
		$('#submit_kas_besar').html('Edit Data');

		$.ajax({
			url: BASE_URL+'kas-besar/edit/'+id.toLowerCase(),
			type: 'post',
			dataType: 'json',
			data: {},
			beforeSend: function(){

			},
			success: function(output){
				if(output){
					$('#modalKasBesar').modal();
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
	$('#form_kas_besar').trigger('reset');

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
*
*/
function generateID(){
	$.ajax({
		url: BASE_URL+'kas-besar/get-last-id/',
		type: 'post',
		dataType: 'json',
		data: {},
		beforeSend: function(){},
		success: function(output){
			$('#id').val(output);	
		},
		error: function (jqXHR, textStatus, errorThrown){ // error handling
            console.log(jqXHR, textStatus, errorThrown);
            swal("Pesan Gagal", "Terjadi Kesalahan Teknis, Silahkan Coba Kembali", "error");
        }
	})
}