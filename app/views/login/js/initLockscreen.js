$(document).ready(function(){
	// submit lockscreen
	$('#form_lockscreen').submit(function(e){
		e.preventDefault();
		submit_lockscreen();

		return false;
	});
});

/**
*
*/
function submit_lockscreen(){
	$.ajax({
		url: BASE_URL+'login/lockscreen/?callback='+urlParams.callback,
		type: 'POST',
		dataType: 'json',
		data:{
			'username': $('#username').val().trim(),
			'password': $('#password').val().trim(),
		},
		beforeSend: function(){},
		success: function(output){
			console.log(output);
			if(output.status){
				if(output.callback) document.location=output.callback;
				else document.location=BASE_URL;
			}
			else {
				toastr.warning(output.notif.message, output.notif.title);
			}
		},
		error: function (jqXHR, textStatus, errorThrown) { // error handling
            console.log(jqXHR, textStatus, errorThrown);
            swal("Pesan Gagal", "Terjadi Kesalahan Teknis, Silahkan Coba Kembali", "error");
        }
	})
}