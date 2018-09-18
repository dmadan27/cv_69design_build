$(document).ready(function(){
	var operasionalProyekTable = $("#operasionalProyekTable").DataTable({
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
            url: BASE_URL+"operasional-proyek/get-list/",
            type: 'POST',
             data: {
                "token_list" : $('#token_list').val().trim(),
            }
        },
        "columnDefs": [
            {
                "targets":[0, 6],
                "orderable":false,
            }
        ],
        createdRow: function(row, data, dataIndex){
            for(var i = 0; i < 7; i++){
                if(i == 0 || i == 5) $('td:eq('+i+')', row).addClass('text-right');
            }
        }
    });

    // btn tambah
    $('#tambah').on('click', function(){
        window.location.href = BASE_URL+'operasional-proyek/form/'

        // if(this.value.trim() != "") window.location.href = BASE_URL+'operasional-proyek/form/';
        // else swal("Pesan Gagal", "Terjadi Kesalahan Teknis, Silahkan Coba Kembali", "error");
    });

    // btn Export
    $('#exportExcel').on('click', function(){
        // if(this.value.trim() != "") 
            window.location.href = BASE_URL+'operasional-proyek/export/';
       
    });

});

/**
*
*/
function getView(id){
    window.location.href = BASE_URL+'operasional-proyek/detail/'+id;   
}

/**
*
*/
function getEdit(id){
    window.location.href = BASE_URL+'operasional-proyek/form/'+id;
   
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
				url: BASE_URL+'operasional-proyek/delete/'+id,
				type: 'post',
				dataType: 'json',
				data: {},
				beforeSend: function(){

				},
				success: function(output){
					console.log(output);
					if(output){
						swal("Pesan Berhasil", "Data Berhasil Dihapus", "success");
						$("#operasionalProyekTable").DataTable().ajax.reload();
					}
				},
				error: function (jqXHR, textStatus, errorThrown){ // error handling
		            console.log(jqXHR, textStatus, errorThrown);
                    swal("Pesan Gagal", "Terjadi Kesalahan Teknis, Silahkan Coba Kembali", "error");
		        }
			})
		});
	
}
