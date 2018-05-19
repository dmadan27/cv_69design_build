$(document).ready(function () {
	generateID();
	setStatus();
	$('#submit_proyek').prop('value', 'action-add');
	$('#id').prop('disabled', true);
    //Initialize Select2 Elements
    $('.select2').select2(); 

 	//Date picker
    $('.datepicker').datepicker({
      autoclose: true,
      format: "yyyy-mm-dd",
      todayHighlight: true,
      orientation:"bottom auto",
      todayBtn: true,
    });
 	
    // tambah detail
    $('#tambah_detail').on('click', function(){
    	$('#modalDetail').modal();
    });

    // Submit Proyek
    $('#form_proyek').submit(function(e){
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
*
*/
function getDataForm(){
	var data = new FormData();
	var luas_area = parseFloat($('#luas_area').val().trim()) ? parseFloat($('#luas_area').val().trim()) : $('#luas_area').val().trim();
	var estimasi= parseFloat($('#estimasi').val().trim()) ? parseFloat($('#estimasi').val().trim()) : $('#estimasi').val().trim();
	var total= parseFloat($('#total').val().trim()) ? parseFloat($('#total').val().trim()) : $('#total').val().trim();
	var dp= parseFloat($('#dp').val().trim()) ? parseFloat($('#dp').val().trim()) : $('#dp').val().trim();
	var cco = parseFloat($('#cco').val().trim()) ? parseFloat($('#cco').val().trim()) : $('#cco').val().trim();

	data.append('token', $('#token_form').val().trim());
	data.append('id', $('#id').val().trim());
	data.append('pemilik', $('#pemilik').val().trim());
	data.append('tgl', $('#tgl').val().trim());
	data.append('pembangunan', $('#pembangunan').val().trim());
	data.append('luas_area', $('#luas_area').val().trim());
	data.append('alamat', $('#alamat').val().trim());
	data.append('kota', $('#kota').val().trim());
	data.append('estimasi', $('#estimasi').val().trim());
	data.append('total', $('#total').val().trim());
	data.append('dp', $('#dp').val().trim());
	data.append('cco', $('#cco').val().trim());
	data.append('status', $('#status').val().trim());
	
	
	// data.append('')
	// data.append('')
	data.append('action', $('#submit_proyek').val().trim());

	return data;



}

/**
* function proses
*/
function submit(){
	var data = getDataForm();
	$.ajax({
		url: BASE_URL+'proyek/'+$('#submit_proyek').val().trim()+'/',
		type: 'POST',
		dataType: 'json',
		data: data,
		contentType: false,
		cache :false,
		processData: false,
		beforeSend: function(){
			$('#submit_proyek').prop('disabled', true);
			$('#submit_proyek').prepend('<i class="fa fa-spin fa-refresh"></i> ');
		},
		success: function(output){
			console.log(output);
			if(!output.status){
				$('#submit_proyek').prop('disabled', false);
				$('#submit_proyek').html($('#submit_proyek').text());
				setError(output.error);
				toastr.warning(output.notif.message, output.notif.title);
			}
			else window.location.href = BASE_URL+'proyek/';
		},
		error: function(jqXHR, textStatus, errorThrown){
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
function setStatus(){
	var status = [
		{value: "BERJALAN", text: "BERJALAN"},
		{value: "SELESAI", text: "SELESAI"},
	];

	$.each(status, function(index, item){
		var option = new Option(item.text, item.value);
		$("#status").append(option);
	});
}

/**
*
*/
function generateID(){
	$.ajax({
		url: BASE_URL+'proyek/get-last-id/',
		// type: 'post',
		// dataType: 'json',
		// data: {"token_edit": token},
		beforeSend: function(){

		},
		success: function(output){
			// if(output){
			// 	$('#modalSkc').modal();
			// 	$('#token_form').val(token);
			// 	setValue(output);
			// }
			$('#id').val(output);	
		},
		error: function (jqXHR, textStatus, errorThrown){ // error handling
            console.log(jqXHR, textStatus, errorThrown);
        }
	})
}

/**
*
*/
function resetForm(){

}

/**
*
*/
function submit_modal(){

}

/**
*
*/
function

/**
*
*/
function resetModal(){

}