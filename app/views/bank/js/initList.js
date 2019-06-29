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
        if(data[0]) $('td:eq(0)', row).addClass('text-right');
        if(data[2]) $('td:eq(2)', row).addClass('text-right');
        if($(data[3]).text().toLowerCase() == "nonaktif") $(row).addClass('danger');
    }
});

const nofitAccessDenied = { title: 'Pesan Pemberitahuan', message: 'Akses Ditolak', type: 'warning' };

$(document).ready(function() {

    // btn Export
    $('#exportExcel').on('click', async function(){
        console.log('Button Export Excel Bank clicked...');
        
        $('.box').append('<div class="overlay"><i class="fa fa-refresh fa-spin"></i></div>');
        try {
            await Export.excel({
                method: 'bank',
            });
        } catch (error) {
            if (error.code == "InfoException") {
                swal("Pesan", error.message, "info");
            } else {
                console.log("Log Export Bank: " + error.message);
                swal("Pesan Gagal", "Terjadi Kesalahan Teknis, Silahkan Coba Kembali", "error");
            }
        }
        $('.box .overlay').remove();
    });

    // event on click refresh table
    $('#refreshTable').on('click', function() {
        console.log('Button Refresh Table Bank clicked...');
        refreshTable(bankTable, $(this));
    });

});


/**
 * Function getView
 * Proses request data detail bank
 * @param {string} id 
 */
function getView(id){
    console.log('Button View Bank clicked...');

	window.location.href = BASE_URL+'bank/detail/'+id.toLowerCase();
}

/**
 * Function getDelete
 * Proses request hapus data bank ke server
 * @param {string} id
 * @return {object} response
 */
function getDelete(id){
    console.log('Button Hapus Bank clicked...');

    if(LEVEL !== 'KAS BESAR') {
        setNotif(notifAccessDenied, 'swal')
        return
    }

	swal({
		title: "Pesan Konfirmasi",
		text: "Apakah Anda Yakin Akan Menghapus Data Ini !!",
		type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Ya, Hapus!",
        cancelButtonText: "Batal",
        closeOnConfirm: false,
	}, function() {
		$.ajax({
			url: BASE_URL+'bank/delete/'+id,
			type: 'post',
			dataType: 'json',
			data: {},
			beforeSend: function(){
			},
			success: function(response){
				console.log('Response getDelete Bank: ', response);
				if(response.success) { bankTable.ajax.reload(); }
                swal(response.notif.title, response.notif.message, response.notif.type);
			},
			error: function (jqXHR, textStatus, errorThrown){ // error handling
	            console.log('Response Error getDelete Bank', jqXHR, textStatus, errorThrown);
                swal("Pesan Gagal", "Terjadi Kesalahan Teknis, Silahkan Coba Kembali", "error");
                bankTable.ajax.reload();
	        }
		})
	});	
}