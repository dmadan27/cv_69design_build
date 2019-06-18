var distributorTable = $("#distributorTable").DataTable({
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
        url: BASE_URL+"distributor/get-list/",
        type: 'POST',
        data: {}
    },
    "columnDefs": [
        {
            "targets":[0, 4], // disable order di kolom 1 dan 3
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

$(document).ready(function(){

    // btn Export
    $('#exportExcel').on('click', async function(){
        $('.box').append('<div class="overlay"><i class="fa fa-refresh fa-spin"></i></div>');
        try {
            await Export.excel({
                method: 'distributor',
            });
        } catch (error) {
            if (error.code == "InfoException") {
                swal("Pesan", error.message, "info");
            } else {
                console.log("Log Export Sub Kas Kecil: " + error.message);
                swal("Pesan Gagal", "Terjadi Kesalahan Teknis, Silahkan Coba Kembali", "error");
            }
        }
        $('.box .overlay').remove();
    });

    // event on click refresh table
    $('#refreshTable').on('click', function() {
        console.log('Button Refresh Table Distributor clicked...');
        refreshTable(distributorTable, $(this));
    });

});

/**
*
*/
function getView(id){
	window.location.href = BASE_URL+'distributor/detail/'+id.toLowerCase();
}

/**
*
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
			url: BASE_URL+'distributor/delete/'+id.toLowerCase(),
			type: 'post',
			dataType: 'json',
			data: {},
			beforeSend: function(){

			},
			success: function(output){
				console.log(output);
				if(output){
					swal("Pesan Berhasil", "Data Berhasil Dihapus", "success");
					$("#distributorTable").DataTable().ajax.reload();
				}
			},
			error: function (jqXHR, textStatus, errorThrown){ // error handling
	            console.log(jqXHR, textStatus, errorThrown);
                swal("Pesan Gagal", "Terjadi Kesalahan Teknis, Silahkan Coba Kembali", "error");
	        }
		})
	});
	
}

