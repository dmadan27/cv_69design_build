$(document).ready(function() {

	init();

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
 * Method init
 */
function init() {
	$('.form-lupa-password').fadeOut();
}

/**
 * Method submit_login
 * Proses login ke sistem
 */
function submit_login() {
	$.ajax({
		url: BASE_URL+'login/',
		type: 'POST',
		dataType: 'json',
		data: {
			'username': $('#username').val().trim(),
			'password': $('#password').val().trim(),
		},
		beforeSend: function() {
			$('#submit_login').prop('disabled', true);
			$('#submit_login').prepend('<i class="fa fa-spin fa-refresh"></i> ');
		},
		success: function(response){
			console.log('%c Response submit_login: ', 'color: green; font-weight: bold', response);
			if(response.success) { document.location = BASE_URL; }
			else {
				$('#submit_login').prop('disabled', false);
				$('#submit_login').html($('#submit_login').text());
				toastr.warning(response.notif.message, response.notif.title);
				setError(response.error);
			}
		},
		error: function (jqXHR, textStatus, errorThrown) {
            console.log('%c Response Error submit_login: ', 'color: red; font-weight: bold', {
				jqXHR: jqXHR, 
				textStatus: textStatus, 
				errorThrown: errorThrown
			});

            swal("Pesan Gagal", "Terjadi Kesalahan Teknis, Silahkan Coba Kembali", "error");
            $('#submit_login').prop('disabled', false);
			$('#submit_login').html($('#submit_login').text());
        }
	})
}

/**
 * Method submit_lupaPassword
 * Proses lupa password sistem
 */
function submit_lupaPassword() {
	$.ajax({
		url: BASE_URL+'lupa-password/',
		type: 'POST',
		dataType: 'json',
		data:{
			'username': $('#email').val().trim()
		},
		beforeSend: function() {
			$('#submit_lupaPassword').prop('disabled', true);
			$('#submit_lupaPassword').prepend('<i class="fa fa-spin fa-refresh"></i> ');
		},
		success: function(response) {
			console.log('%c Response submit_lupaPassword: ', 'color: green; font-weight: bold', response);
			if(response.success) {
				swal(response.notif.title, response.notif.message, "success");
				$('#submit_lupaPassword').prop('disabled', false);
				$('#submit_lupaPassword').html($('#submit_lupaPassword').text());
				resetForm();
				$('.form-lupa-password').slideUp();
				$('.form-login').slideDown();
			}
			else {
				$('#submit_lupaPassword').prop('disabled', false);
				$('#submit_lupaPassword').html($('#submit_lupaPassword').text());
				toastr.warning(response.notif.message, response.notif.title);
				setError(response.error);
			}
		},
		error: function (jqXHR, textStatus, errorThrown) {
            console.log('%c Response Error submit_lupaPassword: ', 'color: red; font-weight: bold', {
				jqXHR: jqXHR, 
				textStatus: textStatus, 
				errorThrown: errorThrown
			});

            swal("Pesan Gagal", "Terjadi Kesalahan Teknis, Silahkan Coba Kembali", "error");
            $('#submit_lupaPassword').prop('disabled', false);
			$('#submit_lupaPassword').html($('#submit_lupaPassword').text());
        }
	})
}

/**
 * Method resetForm
 * Proses reset semua form, form login dan lupa password
 */
function resetForm() {
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
 * Function setError
 * Proses menampilkan pesan error di field-field yang terdapat kesalahan
 * @param {object} error 
 */
function setError(error) {
	$.each(error, function(index, item){
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