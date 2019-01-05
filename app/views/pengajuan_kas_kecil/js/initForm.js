$(document).ready(function(){
	init();

	// button tambah
	$('#tambah').on('click', function(){
		resetForm();
		generateID();

		$('#id').prop('disabled', true);
		// $('.field-saldo').css('display', 'block');
		// $('#submit_bank').prop('value', 'action-add');
		// $('#submit_bank').prop('disabled', false);
		// $('#submit_bank').html('Simpan Data');
		$('#submit_pengajuan_kas_kecil').prop('value', 'action-add');
		$('#submit_pengajuan_kas_kecil').prop('disabled', false);
		$('#submit_pengajuan_kas_kecil').html('Simpan Data');
			
		$('#modalPengajuan_kasKecil').modal();	
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

	// submit pengajuan kas kecil
	$('#form_pengajuan_kas_kecil').submit(function(e){
		e.preventDefault();
		submit(edit_view);

		return false;
	});

});

/**
 * Function init
 * Proses inisialisasi saat onload page
 */
function init() {
	// Initialize Select2 Elements
	$('#status').select2({
    	placeholder: "Pilih Status Pengajuan",
		allowClear: true
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

	// Date picker
    $('.datepicker').datepicker({
      autoclose: true,
      format: "yyyy-mm-dd",
      todayHighlight: true,
      orientation:"bottom auto",
      todayBtn: true,
    });
	
	setnamaKasKecil();
	setNamaBank();
	setIDPengajuanSubKasKecil();
	setStatus();
	$('#status').prop('disabled', true);
	$('#submit_pengajuan_kas_kecil').prop('disabled', true);
}

/**
 * Fungsi getDataForm()
 * untuk mendapatkan semua value di field
 * return berupa object data
 */
function getDataForm(){
	var data = new FormData();
	var total = ($('#total').inputmask) ? 
		( parseFloat($('#total').inputmask('unmaskedvalue')) ?
			parseFloat($('#total').inputmask('unmaskedvalue')) : 
			$('#total').inputmask('unmaskedvalue')
		) : $('#total').val().trim();

	if($('#submit_pengajuan_kas_kecil').val().trim().toLowerCase() == "action-edit") data.append('id', $('#id').val().trim());
	// data.append('token', $('#token_form').val().trim());
	data.append('id', $('#id').val().trim()); // id
	data.append('id_kas_kecil', $('#id_kas_kecil').val().trim()); // id kas kecil
	data.append('id_bank', $('#id_bank').val().trim()); // id bank
	data.append('tgl', $('#tgl').val().trim()); // tgl
	data.append('nama', $('#nama').val().trim()); // nama pengajuan
	data.append('total', total); // total
	data.append('status', $('#status').val().trim()); // status pengajuan kas kecil
	data.append('id_pengajuan_sub_kas_kecil', $('#id_pengajuan_sub_kas_kecil').val().trim()); // ID Pengajuan sub kas kecil
	data.append('action', $('#submit_pengajuan_kas_kecil').val().trim()); // action

	return data;
}

/**
 * 
 */
function submit(){
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
 * @param {*} id  
 */
function getEdit(id){
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
 * @param {*} error 
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
 * @param {*} value 
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
function generateID(){
	$.ajax({
		url: BASE_URL+'pengajuan-kas-kecil/get-last-id/',
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

/**
 * 
 */
function setStatus(){
	var status = [
		{value: "1", text: "PENDING"},
		{value: "2", text: "PERBAIKI"},
		{value: "3", text: "DISETUJUI"},
		{value: "4", text: "DITOLAK"},
	];

	$.each(status, function(index, item){
		var option = new Option(item.text, item.value);
		$("#status").append(option);
	});
	$('#status').val('1').trigger('change');
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