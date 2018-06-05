$(document).ready(function(){
	load_notif();

	setInterval(function(){
		load_notif();
	}, 10000);
});

/**
*
*/
function load_notif(){
	$.ajax({
		url: BASE_URL+'pengajuan-sub-kas-kecil/get-notif/',
		dataType: 'json',
		berofeSend: function(){},
		success: function(data){
			// console.log(data);
			if(data.jumlah > 1){
				$('.label-jumlah').html(data.jumlah);
				$('.label-notif').html(data.text);
				$('#data-notif').html(data.data);
			}
		},
		error: function (jqXHR, textStatus, errorThrown){ // error handling
            console.log(jqXHR, textStatus, errorThrown);
            swal("Pesan Gagal", "Terjadi Kesalahan Teknis Pada Notifikasi, Silahkan Coba Kembali", "error");
        }
	})
}