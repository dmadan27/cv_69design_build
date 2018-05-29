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
    	console.log(listDetail);
    	setStatusDetail();
    	$('#modalDetail').modal();
    });

    // submit detail proyek
 	$('#submit_detail').on('click', function(){
    	addDetail();
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
function addDetail(){
	var index = indexDetail++;

	var persentase = parseFloat($('#persentase').val().trim()) ? parseFloat($('#persentase').val().trim()) : $('#persentase').val().trim();
	var total_detail = parseFloat($('#total_detail').val().trim()) ? parseFloat($('#total_detail').val().trim()) : $('#total_detail').val().trim();

	var data = {
		index: index,
		angsuran: $('#angsuran').val().trim(),
		persentase: persentase,
		total_detail: total_detail,
		status_detail: $('#status_detail').val().trim(),
	};

	validDetail(data);

	console.log('Index : '+index);
	console.log('Index Utama: '+indexDetail);


}

/**
*
*/
function validDetail(data){
	$.ajax({
		url: BASE_URL+'proyek/action-add-detail/',
		type: 'post',
		dataType: 'json',
		data: data,
		beforeSend: function(){

		},
		success: function(output){
			console.log(output);
			if(output.status){
				// tambah data ke tabel
				listDetail.push(data);

				$('#detail_proyekTable > tbody:last-child').append(
					'<tr>'+
						'<td></td>'+ // no
						'<td>'+data.angsuran+'</td>'+ // angsuran
						'<td>'+data.persentase+'</td>'+ // persentase
						'<td>'+data.total_detail+'</td>'+ // total
						'<td>'+data.status_detail+'</td>'+ // status
						'<td></td>'+ // aksi
					'</tr>'
				);

				console.log(listDetail);
			}
			else{
				// decrement index utama
				indexDetail -= 1;
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
function setStatusDetail(){
	var status = [
		{value: "BELUM DIBAYAR", text: "BELUM DIBAYAR"},
		{value: "LUNAS", text: "LUNAS"},
	];

	$.each(status, function(index, item){
		var option = new Option(item.text, item.value);
		$("#status_detail").append(option);
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


/**
*
*/
function resetModal(){

}