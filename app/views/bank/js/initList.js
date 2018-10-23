$(document).ready(function(){
	var bankTable = $("#bankTable").DataTable({
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
        "lengthMenu": [ 10, 25, 75, 100 ],
        "pageLength": 10,
        order: [],
        processing: true,
        serverSide: true,
        ajax: {
            url: BASE_URL+"bank/get-list/",
            type: 'POST',
            data: {}
        },
        "columnDefs": [
            {
                "targets":[0, 4],
                "orderable":false,
            }
        ],
        createdRow: function(row, data, dataIndex){
        	if($(data[3]).text().toLowerCase() == "nonaktif") $(row).addClass('danger');
        	for(var i = 0; i < 5; i++){
                if(i == 0 || i == 2) $('td:eq('+i+')', row).addClass('text-right');
        	}
        }
    });

    // btn Export
    $('#exportExcel').on('click', function(){
        getExport();
    });

});

/**
 * Function getExport
 * Proses request get data untuk di export ke excel
 */
function getExport(){
    window.location.href = BASE_URL+'bank/export/';
}

/**
 * Function getView
 * Proses request data detail bank
 * @param {string} id 
 */
function getView(id){
	window.location.href = BASE_URL+'bank/detail/'+id.toLowerCase();
}

/**
 * Function getDelete
 * Proses request hapus data ke server
 * @param {string} id
 * @return {object} response
 */
function getDelete(id){
	swal({
		title: "Pesan Konfirmasi",
		text: "Apakah Anda Yakin Akan Menghapus Data Ini !!",
		type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Ya, Hapus!",
        cancelButtonText: "Batal",
        closeOnConfirm: false,
	}, function(){
		$.ajax({
			url: BASE_URL+'bank/delete/'+id.toLowerCase(),
			type: 'post',
			dataType: 'json',
			data: {},
			beforeSend: function(){
			},
			success: function(response){
				console.log('Response getDelete Bank: ', response);
				if(response.success){ $("#bankTable").DataTable().ajax.reload(); }
                swal(response.notif.title, response.notif.message, response.notif.type);
			},
			error: function (jqXHR, textStatus, errorThrown){ // error handling
	            console.log('Response Error getDelete Bank', jqXHR, textStatus, errorThrown);
                swal("Pesan Gagal", "Terjadi Kesalahan Teknis, Silahkan Coba Kembali", "error");
                $("#bankTable").DataTable().ajax.reload();
	        }
		})
	});	
}