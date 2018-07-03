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

	// button edit profil
	$('#btn_edit').on('click', function(){
		// $('#modalProfil').modal();
		getEdit();
	});

	// submit edit profil
	$('#form_edit_profil').on('submit', function(e){
		e.preventDefault();
		submit_profil();

		return false;
	});

	// button edit foto
	$('#edit_foto').on('click', function(){

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