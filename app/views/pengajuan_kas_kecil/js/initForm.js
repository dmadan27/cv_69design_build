$(document).ready(function(){
	setStatus();
	$('#submit_pengajuan_kas_kecil').prop('disabled', true);
	
	// button tambah
	$('#tambah').on('click', function(){
			resetForm();
			// $('.field-saldo').css('display', 'block');
			// $('#submit_bank').prop('value', 'action-add');
			// $('#submit_bank').prop('disabled', false);
			// $('#submit_bank').html('Simpan Data');
			// // $('#token_form').val(this.value);
			$('#modalPengajuan_kasKecil').modal();	
	});

	// submit pengajuan kas kecil
	$('#form_pengajuan_kas_kecil').submit(function(e){
		e.preventDefault();
		submit(edit_view);

		return false;
	});

	//Date picker
    $('.datepicker').datepicker({
      autoclose: true,
      format: "yyyy-mm-dd",
      todayHighlight: true,
      orientation:"bottom auto",
      todayBtn: true,
    });

	// on change field
	// $('.field').on('change', function(){
	// 	if(this.value !== ""){
	// 		$('.field-'+this.id).removeClass('has-error').addClass('has-success');
	// 		$(".pesan-"+this.id).text('');
	// 	}
	// 	else{
	// 		$('.field-'+this.id).removeClass('has-error').removeClass('has-success');
	// 		$(".pesan-"+this.id).text('');	
	// 	}
	// });

});

/**
* Fungsi getDataForm()
* untuk mendapatkan semua value di field
* return berupa object data
*/
function getDataForm(){
	var data = new FormData();
	// var saldo = parseFloat($('#saldo').val().trim()) ? parseFloat($('#saldo').val().trim()) : $('#saldo').val().trim();

	if($('#submit_pengajuan_kas_kecil').val().trim().toLowerCase() == "action-edit") data.append('id', $('#id').val().trim());
	data.append('token', $('#token_form').val().trim());
	data.append('id', $('#id').val().trim()); // id
	// data.append('nama', $('#nama').val().trim()); // nama bank
	// data.append('saldo', saldo); // saldo awal
	data.append('status', $('#status').val().trim()); // status pengajuan kas kecil
	data.append('action', $('#submit_pengajuan_kas_kecil').val().trim()); // action

	return data;
}

/**
*
*/
function submit(edit_view){
	var data = getDataForm();

	$.ajax({
		url: BASE_URL+'pengajuan-kas-kecil/'+$('#submit_pengajuan_kas_kecil').val().trim()+'/',
		type: 'POST',
		dataType: 'json',
		data: data,
		contentType: false,
		cache: false,
		processData: false,
		beforeSend: function(){
			$('#submit_pengajuan_kas_kecil').prop('disabled', true);
			$('#submit_pengajuan_kas_kecil').prepend('<i class="fa fa-spin fa-refresh"></i> ');
		},
		success: function(output){
			console.log(output);
			if(!output.status) {
				$('#submit_pengajuan_kas_kecil').prop('disabled', false);
				$('#submit_pengajuan_kas_kecil').html($('#submit_pengajuan_kas_kecil').text());
				setError(output.error);
				toastr.warning(output.notif.message, output.notif.title);
			}
			else{
				toastr.success(output.notif.message, output.notif.title);
				resetForm();
				$("#modalPengajuan_kasKecil").modal('hide');
				if(!edit_view) $("#pengajuanKasKecilTable").DataTable().ajax.reload();
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
function getEdit(id, token){
	if(token != ""){
		resetForm();
		// $('.field-saldo').css('display', 'none');
		$('#submit_pengajuan_kas_kecil').prop('value', 'action-edit');
		$('#submit_pengajuan_kas_kecil').prop('disabled', false);
		$('#submit_pengajuan_kas_kecil').html('Edit Data');

		$.ajax({
			url: BASE_URL+'pengajuan-kas-kecil/edit/'+id.toLowerCase(),
			type: 'post',
			dataType: 'json',
			data: {"token_edit": token},
			beforeSend: function(){

			},
			success: function(output){
				if(output){
					$('#modalPengajuan_kasKecil').modal();
					$('#token_form').val(token);
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
		{value: "DISETUJUI", text: "DISETUJUI"},
		{value: "DIPERBAIKI", text: "DIPERBAIKI"},
		{value: "DITOLAK", text: "DITOLAK"},
		{value: "PENDING", text: "PENDING"},
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
	$('#form_pengajuan_kas_kecil').trigger('reset');

	// hapus semua pesan
	$('.pesan').text('');

	// hapus semua feedback
	$('.form-group').removeClass('has-success').removeClass('has-error');
}