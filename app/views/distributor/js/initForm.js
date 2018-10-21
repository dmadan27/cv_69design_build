$(document).ready(function(){
	// set status
	setStatus();
	// set jenis HERE !!
	//setJenis();

	
	$('#id').prop('disabled', true);
	// $('#nama').prop('disabled', true);



	$('#submit_distributor').prop('disabled', true);
	

	// button tambah
	$('#tambah').on('click', function(){
		resetForm();
		
		generateID();
		$('#submit_distributor').prop('value', 'action-add');
		$('#submit_distributor').prop('disabled', false);
		$('#submit_distributor').html('Simpan Data');
		$('#modalDistributor').modal();
	});

	// submit bank
	$('#form_distributor').submit(function(e){
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



});

/**
* Fungsi getDataForm()
* untuk mendapatkan semua value di field
* return berupa object data
*/
function getDataForm(){
	var data = new FormData();

	if($('#submit_distributor').val().trim().toLowerCase() == "action-edit"){
		// data.append('id', $('#id').val().trim());
		data.append('nama', $('#nama').val().trim()); // nama distributor
		data.append('alamat', $('#alamat').val().trim()); // alamat distributor
		data.append('no_telp', $('#no_telp').val().trim()); // no_telp distributor
		data.append('pemilik', $('#pemilik').val().trim()); // pemilik distributor
		data.append('status', $('#status').val().trim()); // status distributor
	} 
	// data.append('id', $('#id').val().trim());
		
	// if($('#submit_bank').val().trim().toLowerCase() == "action-edit") data.append('id', $('#id').val().trim());
	data.append('id', $('#id').val().trim());
	data.append('nama', $('#nama').val().trim()); // nama distributor
	data.append('alamat', $('#alamat').val().trim()); // alamat distributor
	data.append('no_telp', $('#no_telp').val().trim()); // no_telp distributor
	data.append('pemilik', $('#pemilik').val().trim()); // pemilik distributor
	data.append('status', $('#status').val().trim()); // status distributor
	data.append('action', $('#submit_distributor').val().trim()); // action

	

	return data;
}

/**
*
*/
function submit(edit_view){
	var data = getDataForm();

	$.ajax({
		url: BASE_URL+'distributor/'+$('#submit_distributor').val().trim()+'/',
		type: 'POST',
		dataType: 'json',
		data: data,
		contentType: false,
		cache: false,
		processData: false,
		beforeSend: function(){
			$('#submit_distributor').prop('disabled', true);
			$('#submit_distributor').prepend('<i class="fa fa-spin fa-refresh"></i> ');
		},
		success: function(output){
			console.log(output);
			if(!output.status) {
				$('#submit_distributor').prop('disabled', false);
				$('#submit_distributor').html($('#submit_distributor').text());
				setError(output.error);
				toastr.warning(output.notif.message, output.notif.title);
			}
			else{
				toastr.success(output.notif.message, output.notif.title);
				resetForm();
				$("#modalDistributor").modal('hide');
				$("#distributorTable").DataTable().ajax.reload();
			}
		},
		error: function (jqXHR, textStatus, errorThrown){ // error handling
			$("#modalDistributor").modal('hide');
            console.log(jqXHR, textStatus, errorThrown);
            swal("Pesan Gagal", "Terjadi Kesalahan Teknis, Silahkan Coba Kembali", "error");
        }
	})
}

/**
*
*/
function getEdit(id){
	resetForm();
	
	$('#submit_distributor').prop('value', 'action-edit');
	$('#submit_distributor').prop('disabled', false);
	$('#submit_distributor').html('Edit Data');

	$.ajax({
		url: BASE_URL+'distributor/edit/'+id.toLowerCase(),
		type: 'post',
		dataType: 'json',
		data: {},
		beforeSend: function(){
		},
		success: function(output){
			if(output){
				$('#modalDistributor').modal();
				setValue(output);
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
		{value: "AKTIF", text: "AKTIF"},
		{value: "NONAKTIF", text: "NONAKTIF"},
	];

	$.each(status, function(index, item){
		var option = new Option(item.text, item.value);
		$("#status").append(option);
	});
}

/**
*
*/
function setJenis(){
	var jenis = [
		{value: "TEKNIS", text: "TEKNIS"},
		{value: "NONTEKNIS", text: "NONTEKNIS"},
	];

	$.each(jenis, function(index, item){
		var option = new Option(item.text, item.value);
		$("#jenis").append(option);
	});
}




/**
*
*/
function resetForm(){
	// trigger reset form
	$('#form_distributor').trigger('reset');

	// hapus semua pesan
	$('.pesan').text('');

	// hapus semua feedback
	$('.form-group').removeClass('has-success').removeClass('has-error');
}

/**
*
*/
function generateID(){
	$.ajax({
		url: BASE_URL+'distributor/get-last-id/',
		type: 'post',
		dataType: 'json',
		data: {},
		beforeSend: function(){},
		success: function(output){
			$('#id').val(output);	
		},
		error: function (jqXHR, textStatus, errorThrown){ // error handling
            console.log(jqXHR, textStatus, errorThrown);
            swal("Pesan Gagal", "Terjadi Kesalahan Teknis, Silahkan Coba Kembali", "error");
        }
	})
}