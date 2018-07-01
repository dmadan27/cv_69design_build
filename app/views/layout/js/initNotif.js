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
	var url = ((level.toLowerCase() == 'kas kecil')) 
		? 'pengajuan-sub-kas-kecil/get-notif/' : 'pengajuan-kas-kecil/get-notif/';

	$.ajax({
		url: BASE_URL+url,
		type: 'POST',
		dataType: 'json',
		data:{'timeout': 'no'},
		berofeSend: function(){},
		success: function(data){
			$('#view-all').attr('href', data.view_all);
			// console.log(data);
			if(data.jumlah > 0){
				$('.label-jumlah').html(data.jumlah);
				$('.label-notif').html(data.text);
				$('#data-notif').html(data.data);
			}
			else{
				$('.label-notif').html(data.text);
				$('.label-jumlah').html('');
				$('#data-notif').html('');
			}
		},
		error: function (jqXHR, textStatus, errorThrown){ // error handling
            console.log(jqXHR, textStatus, errorThrown);
            swal("Pesan Gagal", "Terjadi Kesalahan Teknis Pada Notifikasi, Silahkan Coba Kembali", "error");
            setTimeout(function(){ 
                 location.reload();
            }, 1500);
        }
	})
}