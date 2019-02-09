$(document).ready(function(){
	$('.image-popup').magnificPopup({
		type: 'image',
		closeOnContentClick: true,
		closeBtnInside: false,
		fixedContentPos: true,
		mainClass: 'mfp-no-margins mfp-with-zoom', // class to remove default margin from left and right side
		image: {
			verticalFit: true
		},
		zoom: {
			enabled: true,
			duration: 300 // don't foget to change the duration also in CSS
		}
	});

	// button edit profil, // submit edit profil
	$('#btn_edit').on('click', function(){
		getEdit();
	});
	$('#form_edit_profil').on('submit', function(e){
		e.preventDefault();
		submit_profil();

		return false;
	});

	// button edit foto
	$('#edit_foto').on('click', function(){
		$('#modalFoto').modal();
	});
	$('#form_edit_foto').on('submit', function(e){
		e.preventDefault();
		submit_foto();

		return false;
	});

	// button hapus foto
	$('#delete_foto').on('click', function(){
		delete_foto();
	});

	// submit ganti password
	$('#form_ganti_password').on('submit', function(e){
		e.preventDefault();
		submit_ganti_password();

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

    $('#modalFoto').on('hidden.bs.modal', function (e){
    	clearDropify();
    });
});

/**
 * 
 */
function getEdit(){
	resetForm();
	$.ajax({
		url: BASE_URL+'profil/edit/',
		type: 'post',
		dataType: 'json',
		beforeSend: function(){

		},
		success: function(output){
			console.log(output);
			if(output){
				$('#modalProfil').modal();
				setValue(output);
			}
			else{

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
function submit_profil(){
	var data = {
		'nama' : $('#nama').val().trim(),
		'alamat' : $('#alamat').val().trim(),
		'no_telp' : $('#no_telp').val().trim(),
	};

	$.ajax({
		url: BASE_URL+'profil/'+$('#submit_edit_profil').val().trim()+'/',
		type: 'POST',
		dataType: 'json',
		data: data,
		beforeSend: function(){
			$('#submit_edit_profil').prop('disabled', true);
			$('#submit_edit_profil').prepend('<i class="fa fa-spin fa-refresh"></i> ');
		},
		success: function(output){
			console.log(output);
			$('#submit_edit_profil').prop('disabled', false);
			$('#submit_edit_profil').html($('#submit_edit_profil').text());
			
			if(!output.status) {	
				setError(output.error);
				toastr.warning(output.notif.message, output.notif.title);
			}
			else{
				toastr.success(output.notif.message, output.notif.title);
				resetForm();
				$("#modalProfil").modal('hide');
				setTimeout(function(){ location.reload(); }, 1000);
			}
		},
		error: function (jqXHR, textStatus, errorThrown){ // error handling
            console.log(jqXHR, textStatus, errorThrown);
            swal("Pesan Gagal", "Terjadi Kesalahan Teknis, Silahkan Coba Kembali", "error");

            $("#modalProfil").modal('hide');
            $('#submit_edit_profil').prop('disabled', false);
			$('#submit_edit_profil').html($('#submit_edit_profil').text());
        }
	})
}

/**
 * 
 */
function submit_foto(){
	var data = new FormData();
	data.append('foto', $("#foto")[0].files[0]);

	$.ajax({
		url: BASE_URL+'profil/'+$('#submit_edit_foto').val().trim(),
		type: 'POST',
		dataType: 'json',
		data: data,
		contentType: false,
		cache: false,
		processData: false,
		beforeSend: function(){
			$('#delete_foto').prop('disabled', true);
			$('#submit_edit_foto').prop('disabled', true);
			$('#submit_edit_foto').prepend('<i class="fa fa-spin fa-refresh"></i> ');
		},
		success: function(output){
			console.log(output);
			
			if(!output.status){
				setError(output.error);
				toastr.warning(output.notif.message, output.notif.title);
			}
			else{
				toastr.success(output.notif.message, output.notif.title);
				$("#modalFoto").modal('hide');
				setTimeout(function(){ location.reload();}, 1500);
			}

			$('#delete_foto').prop('disabled', false);
			$('#submit_edit_foto').prop('disabled', false);
			$('#submit_edit_foto').html($('#submit_edit_foto').text());
		},
		error: function (jqXHR, textStatus, errorThrown){ // error handling
            console.log(jqXHR, textStatus, errorThrown);
            swal("Pesan Gagal", "Terjadi Kesalahan Teknis, Silahkan Coba Kembali", "error");
            $('#delete_foto').prop('disabled', false);
            $('#submit_edit_foto').prop('disabled', false);
			$('#submit_edit_foto').html($('#submit_edit_foto').text());
        }
	})
}

/**
 * 
 */
function delete_foto(){
	swal({
		title: "Pesan Konfirmasi",
		text: "Apakah Anda Yakin Akan Menghapus Foto Profil !!",
		type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Ya, Hapus!",
        cancelButtonText: "Batal",
        closeOnConfirm: false,
	}, function(){
		$.ajax({
			url: BASE_URL+'profil/'+$('#delete_foto').val().trim(),
			type: 'post',
			dataType: 'json',
			// data: {"token_delete": token},
			beforeSend: function(){
				$('#submit_edit_foto').prop('disabled', true);
				$('#delete_foto').prop('disabled', true);
				$('#delete_foto').prepend('<i class="fa fa-spin fa-refresh"></i> ');
			},
			success: function(output){
				console.log(output);
				swal(output.notif.title, output.notif.message, output.notif.type);
				
				if(output){
					$("#modalFoto").modal('hide');
					setTimeout(function(){ location.reload();}, 1500);
				}

				$('#submit_edit_foto').prop('disabled', false);
				$('#delete_foto').prop('disabled', false);
				$('#delete_foto').html($('#delete_foto').text());
			},
			error: function (jqXHR, textStatus, errorThrown){ // error handling
	            console.log(jqXHR, textStatus, errorThrown);
                swal("Pesan Gagal", "Terjadi Kesalahan Teknis, Silahkan Coba Kembali", "error");
                $('#submit_edit_foto').prop('disabled', false);
				$('#delete_foto').prop('disabled', false);
				$('#delete_foto').html($('#delete_foto').text());
	        }
		})
	});
}

/**
 * 
 */
function submit_ganti_password(){
	$.ajax({
		url: BASE_URL+'profil/ganti-password/',
		type: 'POST',
		dataType: 'json',
		data: {
			'password_lama': $('#password_lama').val().trim(),
			'password_baru': $('#password_baru').val().trim(),
			'password_konf': $('#password_konf').val().trim(),
		},
		beforeSend: function(){
			$('#submit_ganti_password').prop('disabled', true);
			$('#submit_ganti_password').prepend('<i class="fa fa-spin fa-refresh"></i> ');
		},
		success: function(output){
			console.log(output);
			if(!output.status) {
				$('#submit_ganti_password').prop('disabled', false);
				$('#submit_ganti_password').html($('#submit_ganti_password').text());
				setError(output.error);
				toastr.warning(output.notif.message, output.notif.title);
			}
			else{
				swal(output.notif.title, output.notif.message, "success");
				$('#submit_ganti_password').prop('disabled', false);
				$('#submit_ganti_password').html($('#submit_ganti_password').text());
				resetForm_ganti_password();
			}
		},
		error: function (jqXHR, textStatus, errorThrown){ // error handling
            console.log(jqXHR, textStatus, errorThrown);
            swal("Pesan Gagal", "Terjadi Kesalahan Teknis, Silahkan Coba Kembali", "error");
            $('#submit_ganti_password').prop('disabled', false);
			$('#submit_ganti_password').html($('#submit_ganti_password').text());
        }
	})
}

/**
 * 
 * @param {*} value 
 */
function setValue(value){
	$.each(value, function(index, item){
		item = (parseFloat(item)) ? (parseFloat(item)) : item;
		$('#'+index).val(item);
	});
}

/**
 * 
 * @param {*} error 
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
	$('#form_edit_profil').trigger('reset');

	// hapus semua pesan
	$('#form_edit_profil .pesan').text('');

	// hapus semua feedback
	$('#form_edit_profil .form-group').removeClass('has-success').removeClass('has-error');
}

/**
 * 
 */
function resetForm_ganti_password(){
	$('#form_ganti_password').trigger('reset');

	// hapus semua pesan
	$('#form_ganti_password .pesan').text('');

	// hapus semua feedback
	$('#form_ganti_password .form-group').removeClass('has-success').removeClass('has-error');
}

/**
 * 
 */
function clearDropify(){
	var foto = $('#foto').dropify();
	foto = foto.data('dropify');
	foto.resetPreview();
	foto.clearElement();
}