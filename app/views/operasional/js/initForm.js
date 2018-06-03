$(document).ready(function(){
	// setStatus();
	$('#submit_operasional').prop('disabled', true);

	// button tambah
	$('#tambah').on('click', function(){
		if(this.value.trim() != "initList.js"){
			resetForm();
			// $('.field-saldo').css('display', 'block');
			$('#submit_operasional').prop('value', 'action-add');
			$('#submit_operasional').prop('disabled', false);
			$('#submit_operasional').html('Simpan Data');
			$('#token_form').val(this.value);
			$('#modalOperasional').modal();
		}
		else swal("Pesan Gagal", "Terjadi Kesalahan Teknis, Silahkan Coba Kembali", "error");
	});

	// submit operasional
	$('#form_operasional').submit(function(e){
		e.preventDefault();
		// submit(edit_view);

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
* Fungsi getDataForm()
* untuk mendapatkan semua value di field
* return berupa object data
*/
function getDataForm(){
	var data = new FormData();
	// var saldo = parseFloat($('#saldo').val().trim()) ? parseFloat($('#saldo').val().trim()) : $('#saldo').val().trim();

	if($('#submit_operasional').val().trim().toLowerCase() == "action-edit") data.append('id', $('#id').val().trim());

	data.append('token', $('#token_form').val().trim());
	data.append('id', $('#id').val().trim()); // id operasional
	data.append('tgl', $('#tgl').val().trim()); // tgl operasional
	data.append('nama', $('#nama').val().trim()); // nama operasional
	data.append('nominal', $('#nominal').val().trim()); // nominal operasional
	data.append('ket', $('#ket').val().trim()); // ket operasional
	data.append('action', $('#submit_operasional').val().trim()); // action

	return data;
}

/**
*
*/
function submit(edit_view){
	var data = getDataForm();

	$.ajax({
		url: BASE_URL+'operasional/'+$('#submit_operasional').val().trim()+'/',
		type: 'POST',
		dataType: 'json',
		data: data,
		contentType: false,
		cache: false,
		processData: false,
		beforeSend: function(){
			$('#submit_operasional').prop('disabled', true);
			$('#submit_operasional').prepend('<i class="fa fa-spin fa-refresh"></i> ');
		},
		success: function(output){
			console.log(output);
			if(!output.status) {
				$('#submit_operasional').prop('disabled', false);
				$('#submit_operasional').html($('#submit_operasional').text());
				setError(output.error);
				toastr.warning(output.notif.message, output.notif.title);
			}
			else{
				toastr.success(output.notif.message, output.notif.title);
				resetForm();
				$("#modalOperasional").modal('hide');
				if(!edit_view) $("#operasionalTable").DataTable().ajax.reload();
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
		$('#submit_operasional').prop('value', 'action-edit');
		$('#submit_operasional').prop('disabled', false);
		$('#submit_operasional').html('Edit Data');

		$.ajax({
			url: BASE_URL+'operasional/edit/'+id.toLowerCase(),
			type: 'post',
			dataType: 'json',
			data: {"token_edit": token},
			beforeSend: function(){

			},
			success: function(output){
				if(output){
					$('#modalOperasional').modal();
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
	// $.each(error, function(index, item){
	// 	console.log(index);

	// 	if(item != ""){
	// 		$('.field-'+index).removeClass('has-success').addClass('has-error');
	// 		$('.pesan-'+index).text(item);
	// 	}
	// 	else{
	// 		$('.field-'+index).removeClass('has-error').addClass('has-success');
	// 		$('.pesan-'+index).text('');	
	// 	}
	// });
}

/**
*
*/
function setValue(value){
	// $.each(value, function(index, item){
	// 	item = (parseFloat(item)) ? (parseFloat(item)) : item;
	// 	$('#'+index).val(item);
	// });
}

/**
*
*/
function setStatus(){
	// var status = [
	// 	{value: "AKTIF", text: "AKTIF"},
	// 	{value: "NONAKTIF", text: "NONAKTIF"},
	// ];

	// $.each(status, function(index, item){
	// 	var option = new Option(item.text, item.value);
	// 	$("#status").append(option);
	// });
}

/**
*
*/
function resetForm(){
	// trigger reset form
	$('#form_operasional').trigger('reset');

	// hapus semua pesan
	$('.pesan').text('');

	// hapus semua feedback
	$('.form-group').removeClass('has-success').removeClass('has-error');
}