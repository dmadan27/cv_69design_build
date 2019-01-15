$(document).ready(function(){
	init();

	// event on click button tambah
	$('#tambah').on('click', function(){
		
		resetForm();
		$('.field-saldo').css('display', 'block');
		$('.field-password').css('display', 'block');
		$('.field-email').css('display', 'block');
		$('.field-password_confirm').css('display', 'block');
		$('.field-foto').css('display', 'block');

		generateID();
		$('#submit_kas_besar').prop('value', 'action-add');
		$('#submit_kas_besar').prop('disabled', false);
		$('#submit_kas_besar').html('Simpan Data');	
		$('#modalKasBesar').modal();

	});

	// event on submit
	$('#form_kas_besar').submit(function(e){
		e.preventDefault();
		submit();

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

	// event on change field foto
	var foto = $('#foto').dropify();
	foto.on('dropify.afterClear', function(event, element) {
        $('.field-foto').removeClass('has-error').removeClass('has-success');
		$(".pesan-foto").text('');
    });
});

/**
 * Function init
 * Proses inisialisasi saat onload page
 */
function init() {
	$('#submit_kas_besar').prop('disabled', true);
	$('#id').prop('disabled', true);

	setStatus();
}

/**
 * Fungsi getDataForm()
 * untuk mendapatkan semua value di field
 * @return {object} data
 */
function getDataForm(){
	var data = new FormData();
	var status = ($('#status').val() != "" && $('#status').val() != null) ? $('#status').val().trim() : "";

	if($('#submit_kas_besar').val().trim().toLowerCase() == "action-add"){
		data.append('foto', $('#foto')[0].files[0]); //foto
		data.append('email', $('#email').val().trim()); // email kas besar
		data.append('password', $('#password').val().trim()); // password kas besar
		data.append('password_confirm', $('#password_confirm').val().trim()); // password kas besar
	}

	if($('#submit_kas_besar').val().trim().toLowerCase() == "action-edit"){
		data.append('id', $('#id').val().trim()); // id kas besar
		data.append('nama', $('#nama').val().trim()); // nama kas besar
		data.append('alamat', $('#alamat').val().trim()); // alamat kas besar
		data.append('no_telp', $('#no_telp').val().trim()); // no_telp kas besar
		data.append('email', $('#email').val().trim()); // email kas besar
		data.append('status', $('#status').val().trim()); // status kas besar
	} 
	 
	data.append('id', $('#id').val().trim()); // id kas besar
	data.append('nama', $('#nama').val().trim()); // nama kas besar
	data.append('alamat', $('#alamat').val().trim()); // alamat kas besar
	data.append('no_telp', $('#no_telp').val().trim()); // no_telp kas besar
	data.append('status', status); // status kas besar
	data.append('action', $('#submit_kas_besar').val().trim()); // action

	return data;
}

/**
 * Function submit
 * Proses submit data ke server baik saat add / edit
 * @return {object} response
 */
function submit(){
	var data = getDataForm();

	$.ajax({
		url: BASE_URL+'kas-besar/'+$('#submit_kas_besar').val().trim()+'/',
		type: 'POST',
		dataType: 'json',
		data: data,
		contentType: false,
		cache: false,
		processData: false,
		beforeSend: function(){
			$('#submit_kas_besar').prop('disabled', true);
			$('#submit_kas_besar').prepend('<i class="fa fa-spin fa-refresh"></i> ');
		},
		success: function(response){
			console.log(response);
			if(!response.success) {
				$('#submit_kas_besar').prop('disabled', false);
				$('#submit_kas_besar').html($('#submit_kas_besar').text());

				setError(response.error);
			}
			else{
				resetForm();
				$("#modalKasBesar").modal('hide');
				$("#kasBesarTable").DataTable().ajax.reload();
			}

			setNotif(response.notif);
		},
		error: function (jqXHR, textStatus, errorThrown){ // error handling
            console.log(jqXHR, textStatus, errorThrown);
			swal("Pesan Gagal", "Terjadi Kesalahan Teknis, Silahkan Coba Kembali", "error");
			$('#submit_kas_besar').prop('disabled', false);
			$('#submit_kas_besar').html($('#submit_kas_besar').text());
        }
	})
}

/**
*
*/
function getEdit(id){
	resetForm();
	$('.field-password').css('display', 'none');
	$('.field-email').css('display', 'none');
	$('.field-password_confirm').css('display', 'none');
	$('.field-foto').css('display', 'none');
	$('#submit_kas_besar').prop('value', 'action-edit');
	$('#submit_kas_besar').prop('disabled', false);
	$('#submit_kas_besar').html('Edit Data');

	$.ajax({
		url: BASE_URL+'kas-besar/edit/'+id.toLowerCase(),
		type: 'post',
		dataType: 'json',
		data: {},
		beforeSend: function(){

		},
		success: function(output){
			if(output){
				$('#modalKasBesar').modal();
				setValue(output);
			}	
		},
		error: function (jqXHR, textStatus, errorThrown){ // error handling
			console.log(jqXHR, textStatus, errorThrown);
		}
	})
}

/**
 * Function setError
 * Proses menampilkan pesan error di field-field yang terdapat kesalahan 
 * @param {object} error 
 */
function setError(error){
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

/**
 * Function setValue
 * Proses pengisian value di field2 saat proses edit
 * @param {object} value 
 */
function setValue(value){
	$.each(value, function(index, item){
		item = (parseFloat(item)) ? (parseFloat(item)) : item;
		$('#'+index).val(item);
	});
}

/**
 * Function setStatus
 * Proses pengisian select status di form kas besar
 */
function setStatus() {
	var status = [
		{value: "AKTIF", text: "AKTIF"},
		{value: "NONAKTIF", text: "NONAKTIF"},
	];

	$.each(status, function(index, item){
		var option = new Option(item.text, item.value);
		$("#status").append(option).trigger('change');
	});
	$('#status').val(null).trigger('change');
}

/**
 * Function resetForm
 * Proses reset form kas besar
 */
function resetForm(){
	// trigger reset form
	$('#form_kas_besar').trigger('reset');

	// hapus semua pesan
	$('.pesan').text('');

	// hapus semua feedback
	$('.form-group').removeClass('has-success').removeClass('has-error');

	// reset field foto
	var foto = $('#foto').dropify();
	foto = foto.data('dropify');
	foto.resetPreview();
	foto.clearElement();
}

/**
 * Function generateID
 * Proses request ID kas besar ke server
 * @return {object} response
 */
function generateID(){
	$.ajax({
		url: BASE_URL+'kas-besar/get-last-id/',
		type: 'post',
		dataType: 'json',
		data: {},
		beforeSend: function(){},
		success: function(response){
			console.log('%cResponse generateID Kas Besar: ', 'color: blue; font-style: italic', response);
			$('#id').val(response);	
		},
		error: function (jqXHR, textStatus, errorThrown){
            console.log('%cResponse Error generateID Kas Besar: ', 'color: red; font-style: italic', jqXHR, textStatus, errorThrown);
            swal("Pesan Gagal", "Terjadi Kesalahan Teknis, Silahkan Coba Kembali", "error");
        }
	})
}