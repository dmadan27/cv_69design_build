$(document).ready(function () {
	$('#submit_proyek').prop('value', 'action-add');
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
			// $('#submit_proyek').prop('disabled', true)
		},
		success: function(output){
			console.log(output);
		},
		error: function(jqXHR, textStatus, errorThrown){
			console.log(jqXHR, textStatus, errorThrown);
		}

	})


}
