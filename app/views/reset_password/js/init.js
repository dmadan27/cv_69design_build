$(document).ready(function(){
	// submit reset password
	$('#form_reset_password').submit(function(e){
		e.preventDefault();
		submit_reset_password();

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

	// visible on click
	$('.visible').on('click', function(){
		// jika awalnya active maka ubah type text - password
		if($(this).hasClass('active')){
			$(this).removeClass('active');
			$(this).html('<i class="fa fa-eye-slash"></i>');
			$(this).parent().parent().find('input').prop('type', 'password');
		}
		else{ // jika bukan maka type password - text
			$(this).addClass('active');
			$(this).html('<i class="fa fa-eye"></i>');
			$(this).parent().parent().find('input').prop('type', 'text');	
		}
	})
});

/**
*
*/
function submit_reset_password(){
	$.ajax({
		url: BASE_URL+'lupa-password/reset/?user='+urlParams.user+'&token='+urlParams.token,
		type: 'POST',
		dataType: 'json',
		data:{
			'password_baru': $('#password_baru').val().trim(),
			'password_konf': $('#password_konf').val().trim(),
		},
		beforeSend: function(){
			$('#submit_reset_password').prop('disabled', true);
			$('#submit_reset_password').prepend('<i class="fa fa-spin fa-refresh"></i> ');
		},
		success: function(output){
			console.log(output);
			if(output.status){
				swal(output.notif.title, output.notif.message, "success");
				setTimeout(function(){ 
	                 document.location=BASE_URL;
	            }, 2000);
			}
			else {
				$('#submit_reset_password').prop('disabled', false);
				$('#submit_reset_password').html($('#submit_reset_password').text());
				toastr.warning(output.notif.message, output.notif.title);
				setError(output.error);
			}
		},
		error: function (jqXHR, textStatus, errorThrown) { // error handling
            console.log(jqXHR, textStatus, errorThrown);
            swal("Pesan Gagal", "Terjadi Kesalahan Teknis, Silahkan Coba Kembali", "error");
            $('#submit_reset_password').prop('disabled', false);
			$('#submit_reset_password').html($('#submit_reset_password').text());
        }
	})
}

/**
*
*/
function resetForm(){
	$('#form_reset_password').trigger('reset');

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