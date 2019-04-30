$(document).ready(function() {
    //Date picker
	$('#tgl_awal').datepicker({
		autoclose: true,
		format: "yyyy-mm-dd",
		todayHighlight: true,
		orientation:"bottom auto",
		todayBtn: true,
    });

	$('#tgl_akhir').datepicker({
		autoclose: true,
		format: "yyyy-mm-dd",
		todayHighlight: true,
		orientation:"bottom auto",
		todayBtn: true,
    });
    
    // validate if tgl_awal and akhir fill / have change
    
});

/**
 * 
 */
function resetForm() {
	$('#form_export').trigger('reset');
	$('.pesan').text('');
	$('.form-group').removeClass('has-success').removeClass('has-error');
}