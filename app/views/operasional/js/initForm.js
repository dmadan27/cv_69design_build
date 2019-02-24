$(document).ready(function(){

	// button tambah
	$('#tambah').on('click', function(){
		resetForm();
		// $("#jenis").val("UANG MASUK").trigger('change');
		$('#tgl').prop('disabled', false);
		$('#submit_operasional').prop('value', 'action-add');
		$('#submit_operasional').prop('disabled', false);
		$('#submit_operasional').html('Simpan Data');
		$('#modalOperasional').modal();

	});

	// submit operasional
	$('#form_operasional').submit(function(e){
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

	init();

});

/**
 * 
 */
function init() {
	$('#submit_operasional').prop('disabled', true);

	$('#id_bank').select2({
		placeholder: "Pilih Bank",
		allowClear : true,
	});

	$('#jenis').select2({
		placeholder: "Pilih Jenis Operasional",
		allowClear : true,
	});

	// input mask
    $('.input-mask-uang').inputmask({ 
    	alias : 'currency',
    	prefix: '', 
    	radixPoint: ',',
    	digits: 0,
    	groupSeparator: '.', 
    	clearMaskOnLostFocus: true, 
    	digitsOptional: false,
	});
	
	//Date picker
    $('.datepicker').datepicker({
		autoclose: true,
		format: "yyyy-mm-dd",
		todayHighlight: true,
		orientation:"bottom auto",
		todayBtn: true,
	});
	
	setIdBank();
	setJenis();
}

/**
* Fungsi getDataForm()
* untuk mendapatkan semua value di field
* return berupa object data
*/
function getDataForm(){
	var data = new FormData();
	// var nominal = parseFloat($('#nominal').val().trim()) ? parseFloat($('#nominal').val().trim()) : $('#nominal').val().trim();

	var nominal = ($('#nominal').inputmask) ? 
		( parseFloat($('#nominal').inputmask('unmaskedvalue')) ?
			parseFloat($('#nominal').inputmask('unmaskedvalue')) : 
			$('#nominal').inputmask('unmaskedvalue')
		) : $('#nominal').val().trim();

	var jenis = ($('#jenis').val() != "" && $('#jenis').val() != null) ? $('#jenis').val().trim() : "";
	var bank = ($('#id_bank').val() != "" && $('#id_bank').val() != null) ? $('#id_bank').val().trim() : "";
		
	if($('#submit_operasional').val().trim().toLowerCase() == "action-edit"){
		data.append('id', $('#id').val().trim());
		data.append('id_bank',bank); // id_bank
		data.append('tgl', $('#tgl').val().trim()); // tgl operasional
		data.append('nama', $('#nama').val().trim()); // nama operasional
		data.append('nominal', nominal); // nominal operasional
		data.append('jenis', jenis); // jenis operasional
		data.append('ket', $('#ket').val().trim()); // ket operasional
	} else {
		data.append('id_bank',bank); // id_bank
		data.append('tgl', $('#tgl').val().trim()); // tgl operasional
		data.append('nama', $('#nama').val().trim()); // nama operasional
		data.append('nominal', nominal); // nominal operasional
		data.append('jenis', jenis); // jenis operasional
		data.append('ket', $('#ket').val().trim()); // ket operasional
		data.append('action', $('#submit_operasional').val().trim()); // action
	}

	

	return data;
}

/**
*
*/
function submit(){
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
			setNotif(output.notif);
			if(!output.status) {
				$('#submit_operasional').prop('disabled', false);
				$('#submit_operasional').html($('#submit_operasional').text());
				setError(output.error);
			}
			else{
				resetForm();
				$("#modalOperasional").modal('hide');
				$("#operasionalTable").DataTable().ajax.reload();
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
function getEdit(id){
	resetForm();
	$('.field-id').css('display', 'none');
	$('.field-tgl').css('display', true);
	$('#tgl').prop('disabled', true);
	
	$('#submit_operasional').prop('value', 'action-edit');
	$('#submit_operasional').prop('disabled', false);
	$('#submit_operasional').html('Edit Data');

	$.ajax({
		url: BASE_URL+'operasional/edit/'+id.toLowerCase(),
		type: 'post',
		dataType: 'json',
		data: {},
		beforeSend: function(){

		},
		success: function(output){
			if(output){
				$('#modalOperasional').modal();
				console.log('Response getEdit:','',output)
				
				//setValueonForm
				$('#id').val(output.id);
				$('#id_bank').val(output.id_bank).trigger('change');
				$('#tgl').val(output.tgl);
				$('#nama').val(output.nama);
				$('#nominal').val(output.nominal);
				$('#jenis').val(output.jenis).trigger('change');
				$('#ket').val(output.ket);
			}	
		},
		error: function (jqXHR, textStatus, errorThrown){ // error handling
			console.log(jqXHR, textStatus, errorThrown);
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
function resetForm(){
	// trigger reset form
	$('#form_operasional').trigger('reset');

	$('#id_bank').val(null).trigger('change');

	// hapus semua pesan
	$('.pesan').text('');

	// hapus semua feedback
	$('.form-group').removeClass('has-success').removeClass('has-error');
}

/**
*
*/
function setIdBank(){
	$.ajax({
		url: BASE_URL+'operasional/get-bank',
		type: 'post',
		dataType: 'json',
		beforeSend: function(){},
		success: function(data){
			console.log(data);
			$.each(data, function(index, item){
				var newOption = new Option(item.text, item.id);
				$('#id_bank').append(newOption).trigger('change');
			});
			$('#id_bank').val(null).trigger('change');
		},
		error: function (jqXHR, textStatus, errorThrown){ // error handling
            console.log(jqXHR, textStatus, errorThrown);
            swal("Pesan Gagal", "Terjadi Kesalahan Teknis, Silahkan Coba Kembali", "error");
        }
	});
}

/**
*
*/
function setJenis() {
	var jenis = [
		{value: "UANG MASUK", text: "UANG MASUK"},
		{value: "UANG KELUAR", text: "UANG KELUAR"},
	];

	$.each(jenis, function(index, item){
		var option = new Option(item.text, item.value);
		$("#jenis").append(option);
	});
	$('#jenis').val(null).trigger('change');
}