$(document).ready(function(){
	// init awal
	$('.form-lupa-password').fadeOut();

	$('#lupaPassword').on('click', function(){
		resetForm();
		$('.form-login').slideUp();
		$('.form-lupa-password').fadeIn();
	});

	$('#back_login').on('click', function(){
		resetForm();
		$('.form-lupa-password').slideUp();
		$('.form-login').slideDown();
	});

	// submit login
	$('#form_login').submit(function(e){
		e.preventDefault();
		submit_login();

		return false;
	});

	// submit lupa password
	$('#form_lupa_password').submit(function(e){
		e.preventDefault();
		submit_lupaPassword();

		return false;
	});

	// on change semua field
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
function submit_login(){
	$.ajax({
		url: BASE_URL+'login/',
		type: 'POST',
		dataType: 'json',
		data:{
			'username': $('#username').val().trim(),
			'password': $('#password').val().trim(),
		},
		beforeSend: function(){
			$('#submit_login').prop('disabled', true);
			$('#submit_login').prepend('<i class="fa fa-spin fa-refresh"></i> ');
		},
		success: function(output){
			console.log(output);
			if(output.status) document.location=BASE_URL;
			else {
				$('#submit_login').prop('disabled', false);
				$('#submit_login').html($('#submit_login').text());
				toastr.warning(output.notif.message, output.notif.title);
				setError(output.error);
			}
		},
		error: function (jqXHR, textStatus, errorThrown) { // error handling
            console.log(jqXHR, textStatus, errorThrown);
            swal("Pesan Gagal", "Terjadi Kesalahan Teknis, Silahkan Coba Kembali", "error");
            $('#submit_login').prop('disabled', false);
			$('#submit_login').html($('#submit_login').text());
        }
	})
}

/**
*
*/
function submit_lupaPassword(){
	$.ajax({
		url: BASE_URL+'lupa-password/',
		type: 'POST',
		dataType: 'json',
		data:{
			'username': $('#email').val().trim()
		},
		beforeSend: function(){
			$('#submit_lupaPassword').prop('disabled', true);
			$('#submit_lupaPassword').prepend('<i class="fa fa-spin fa-refresh"></i> ');
		},
		success: function(output){
			console.log(output);
			if(output.status){
				swal(output.notif.title, output.notif.message, "success");
				$('#submit_lupaPassword').prop('disabled', false);
				$('#submit_lupaPassword').html($('#submit_lupaPassword').text());
				resetForm();
				$('.form-lupa-password').slideUp();
				$('.form-login').slideDown();
			}
			else {
				$('#submit_lupaPassword').prop('disabled', false);
				$('#submit_lupaPassword').html($('#submit_lupaPassword').text());
				toastr.warning(output.notif.message, output.notif.title);
				setError(output.error);
			}
		},
		error: function (jqXHR, textStatus, errorThrown) { // error handling
            console.log(jqXHR, textStatus, errorThrown);
            swal("Pesan Gagal", "Terjadi Kesalahan Teknis, Silahkan Coba Kembali", "error");
            $('#submit_lupaPassword').prop('disabled', false);
			$('#submit_lupaPassword').html($('#submit_lupaPassword').text());
        }
	})
}

/**
*
*/
function resetForm(){
	// form login
	$('#form_login').trigger('reset');

	// form lupa password
	$('#form_lupa_password').trigger('reset');

	// hapus semua feedback
	$('.pesan').text('');

	// hapus semua pesan
	$('.form-group').removeClass('has-success').removeClass('has-error');
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