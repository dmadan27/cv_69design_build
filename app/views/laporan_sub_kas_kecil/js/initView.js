$(document).ready(function(){
    var table_detail = $("#table_detail").DataTable({
        "language" : {
            "lengthMenu": "Tampilkan _MENU_ data/page",
            "zeroRecords": "Data Tidak Ada",
            "info": "Menampilkan _START_ s.d _END_ dari _TOTAL_ data",
            "infoEmpty": "Menampilkan 0 s.d 0 dari 0 data",
            "search": "Pencarian:",
            "loadingRecords": "Loading...",
            "processing": "Processing...",
            "paginate": {
                "first": "Pertama",
                "last": "Terakhir",
                "next": "Selanjutnya",
                "previous": "Sebelumnya"
            }
        },
        "lengthMenu": [ 5, 10, 25, 50 ],
        "pageLength": 5,
        order: [],
        // processing: true,
        // serverSide: true,
        // ajax: {
        //     url: BASE_URL+"laporan-sub-kas-kecil/get-list/",
        //     type: 'POST',
        //     data: {}
        // },
        "columnDefs": [
            {
                "targets":[0],
                "orderable":false,
            }
        ],
        // createdRow: function(row, data, dataIndex){
        //     if($(data[8]).text().toLowerCase() == "ditolak") { $(row).addClass('danger'); }
        //     for(var i = 0; i < 10; i++){
        //         if(i == 0 || i == 6 || i == 7) { $('td:eq('+i+')', row).addClass('text-right'); }
        //     }
        // }
    });

    init();
});

/**
 * Method init
 */
function init() {
    $('.image-popup').magnificPopup({
		type: 'image',
		closeOnContentClick: true,
		closeBtnInside: false,
		fixedContentPos: true,
		mainClass: 'mfp-no-margins mfp-with-zoom', // class to remove default margin from left and right side
		image: {
			verticalFit: true
		},
		zoom: {
			enabled: true,
			duration: 300 // don't foget to change the duration also in CSS
		}
    });
    
    $('#status_order').select2({
    	placeholder: "Pilih Status Pengajuan",
		allowClear: true
	});

	setStatus();
}

/**
 * 
 * @param {string} id
 */
function getEdit(id){
	resetForm();
	$('#submit_laporan_skc').prop('disabled', false);

	$.ajax({
		url: BASE_URL+'laporan-sub-kas-kecil/edit/'+id.toLowerCase(),
		type: 'post',
		dataType: 'json',
		data: {},
		beforeSend: function(){},
		success: function(response){
			console.log('%cResponse Get Edit Laporan Pengajuan Sub Kas Kecil:', 'color: green; font-weight: bold', response);
			setValue(response.data);
			$('#modalLaporanSKC').modal();
		},
		error: function (jqXHR, textStatus, errorThrown){ // error handling
			console.log('%cError Response Get Edit Laporan Pengajuan Sub Kas Kecil:', 'color: red; font-weight: bold', jqXHR, textStatus, errorThrown);
			swal("Pesan Gagal", "Terjadi Kesalahan Teknis, Silahkan Coba Kembali", "error");
			$('#modalLaporanSKC').modal('hide');
		}
	});
}

/**
 * 
 */
function setStatus() {
    var status = [
		{value: "1", text: "PENDING"},
		{value: "2", text: "PERBAIKI"},
		{value: "3", text: "DISETUJUI"},
		{value: "4", text: "DITOLAK"},
	];

	$.each(status, function(index, item){
		var option = new Option(item.text, item.value);
		$("#status_order").append(option).trigger('change');
	});
	$('#status_order').val(null).trigger('change');
}

/**
 * 
 */
function setValue(value) {	
	$('#id').val(value.id);
	$('#status_order').val(value.status_order).trigger('change');
}

/**
 * 
 */
function resetForm(){
	// trigger reset form
	$('#form_laporan_skc').trigger('reset');

	// hapus semua pesan
	$('.pesan').text('');

	// hapus semua feedback
	$('.form-group').removeClass('has-success').removeClass('has-error');

    $('#status').val(null).trigger('change');
}
