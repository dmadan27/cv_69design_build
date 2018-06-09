$(document).ready(function(){
    setStatus();
    $('#submit_pengajuan_skc').prop('disabled', true);

	// submit pengajuan
	$('#form_pengajuan_skc').submit(function(e){
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

	$('#status').on('change', function(){
		if(this.value == "DISETUJUI") $('.data-pengajuan').slideDown();
		else $('.data-pengajuan').slideUp();
	});

});

/**
*
*/
function getDataForm(){
	var data = new FormData();
	var dana_disetujui = parseFloat($('#dana_disetujui').val().trim()) ? 
		parseFloat($('#dana_disetujui').val().trim()) : 
		$('#dana_disetujui').val().trim();

	data.append('token', $('#token_form').val().trim());
	data.append('id', $('#id').val().trim());
	data.append('status', $('#status').val().trim()); // status
	data.append('action', $('#submit_pengajuan_skc').val().trim()); // action
	data.append('dana_disetujui', dana_disetujui); // dana disetujui
	
	return data;
}

/**
*
*/
function submit(edit_view){
	var data = getDataForm();

	$.ajax({
		url: BASE_URL+'pengajuan-sub-kas-kecil/'+$('#submit_pengajuan_skc').val().trim()+'/',
		type: 'POST',
		dataType: 'json',
		data: data,
		contentType: false,
		cache: false,
		processData: false,
		beforeSend: function(){
			$('#submit_pengajuan_skc').prop('disabled', true);
			$('#submit_pengajuan_skc').prepend('<i class="fa fa-spin fa-refresh"></i> ');
		},
		success: function(output){
			console.log(output);
			if(!output.status){
				$('#submit_pengajuan_skc').prop('disabled', false);
				$('#submit_pengajuan_skc').html($('#submit_pengajuan_skc').text());
				setError(output.error);
				toastr.warning(output.notif.message, output.notif.title);
			}
			else{
				var dataNotif = {
					title: '',
					body: '',
					id_pengajuan: $('#id').val().toUpperCase(),
					status: $('#status').val(),
				};

				switch($('#status').val()){
					case 'DISETUJUI':
						dataNotif.title = 'Pengajuan Disetujui';
						dataNotif.body = 'Pengajuan dengan ID: '+dataNotif.id_pengajuan+' telah disetujui.';
						break;

					case 'PERBAIKI':
						dataNotif.title = 'Pengajuan Diperbaiki';
						dataNotif.body = 'Pengajuan dengan ID: '+dataNotif.id_pengajuan+' harap segera diperbaiki.';
						break;

					case 'DITOLAK':
						dataNotif.title = 'Pengajuan Ditolak !!';
						dataNotif.body = 'Pengajuan dengan ID: '+dataNotif.id_pengajuan+' ditolak, harap membuat pengajuan yang baru';
						break;
				}

				if($('#status').val() != 'PENDING') sendNotif(dataNotif);

				toastr.success(output.notif.message, output.notif.title);

				if(!edit_view) $("#pengajuan_sub_kas_kecilTable").DataTable().ajax.reload();
				else {
					setTimeout(function(){ 
						location.reload(); 
					}, 1000);
				}

				resetForm();
				$("#modalPengajuanSKC").modal('hide');
			}
		},
		error: function (jqXHR, textStatus, errorThrown){ // error handling
			$("#modalPengajuanSKC").modal('hide');
            console.log(jqXHR, textStatus, errorThrown);
            swal("Pesan Gagal", "Terjadi Kesalahan Teknis, Silahkan Coba Kembali", "error");
        }
	})
}

/**
*
*/
function sendNotif(data){
	console.log(data);
	$.ajax({
        headers: {

            // setting token firebase
            "Authorization": "key=AAAAlOkhnSY:APA91bEqHj4gSO-lEqIeQz4g0ABtrhnSa6w8zDWMlXno50jkyJt5VwrfiC91uXv55yM560VyV4QaL9JG7XBktaX1IeMPJeNB7wDaFtI_Z3d2Qk6tnUeV0lYVxtvbF94fw_7rQqk8foLO",
            "Content-Type": "application/json",
        },
        url: 'https://fcm.googleapis.com/fcm/send',
        type: 'POST',
        dataType: 'json',
        data: JSON.stringify({

            // kirim notifikasi ke semua user yang men-subscribe tipe news
            "to" : "/topics/"+$('#id_sub_kas_kecil').val(),

            // setting pesan yang ingin ditampilkan
            "notification" : {

                // mengirimkan judul notifikasi
                "title": data.title,

                // mengirimkan isi dari notifikasi
                "body" : data.body,

                // menjalankan suara notifikasi pada saat notif masuk
                "sound": "default"
            },
            // pengiriman data custom
            data : {
                "id_pengajuan": data.id_pengajuan,
                "status": data.status
            },

            // setting prioritas pengiriman notifikasi
            "priority": "high"
        }),
    })
    .done(function(data) {
        console.log('Pesan berhasil dikirim dengan kode: '+data.message_id);
    })
    .fail(function(error) {
        console.log('Terjadi kesalahan:\n'+error.responseText);
    });
}

/**
*
*/
function getEditStatus(id, token){
    if(token.trim() != ""){
    	resetForm();
    	$('#submit_pengajuan_skc').prop('disabled', false);
    	$('#token_form').val(token);
        $.ajax({
            url: BASE_URL+'pengajuan-sub-kas-kecil/edit-status/'+id,
            type: 'post',
            dataType: 'json',
            data: {"token_edit_status": token},
            beforeSend: function(){},
            success: function(output){
                console.log(output);
                $('#modalPengajuanSKC').modal();
                $('#id').val(id);
                $('#id_sub_kas_kecil').val(output.dataPengajuan.id_sub_kas_kecil);
                $('#total').text(output.total);
                $('#saldo').text(output.saldo);
                $('#status').val(output.dataPengajuan.status);
                $('#dana_disetujui').val(output.dataPengajuan.total);
            },
            error: function (jqXHR, textStatus, errorThrown){ // error handling
                console.log(jqXHR, textStatus, errorThrown);
                swal("Pesan Gagal", "Terjadi Kesalahan Teknis, Silahkan Coba Kembali", "error");
                $('#modalPengajuanSKC').modal('hide');
            }
        })
    }
    else swal("Pesan Gagal", "Terjadi Kesalahan Teknis, Silahkan Coba Kembali", "error");
}

/**
*
*/
function setStatus(){
	var status = [
		{value: "PENDING", text: "PENDING"},
		{value: "DISETUJUI", text: "DISETUJUI"},
		{value: "PERBAIKI", text: "PERBAIKI"},
		{value: "DITOLAK", text: "DITOLAK"},
	];

	$.each(status, function(index, item){
		var option = new Option(item.text, item.value);
		$("#status").append(option);
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
	$('#form_pengajuan_skc').trigger('reset');

	// hapus semua pesan
	$('.pesan').text('');

	// hapus semua feedback
	$('.form-group').removeClass('has-success').removeClass('has-error');

	$('#total').text('');
    $('#saldo').text('');

    $('#status').val('PENDING');
    $('.data-pengajuan').slideUp();
}
