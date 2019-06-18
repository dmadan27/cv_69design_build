var operasionalTable = $("#operasionalTable").DataTable({
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
        url: BASE_URL+"operasional/get-list/",
        type: 'POST',
        data: {}
    },
    "columnDefs": [
        {
            "targets":[0, 5],
            "orderable":false,
        }
    ],
    createdRow: function(row, data, dataIndex){
        for(var i = 0; i < 6; i++){
            if(i == 0 || i == 4) $('td:eq('+i+')', row).addClass('text-right');
        }
    }
});

// inisialisasi export
// export operasioan
const exportOperasional = new FormExportStartEndDate({
    method: 'operasional',
    onInitSubmit: () => {        
        $('.box').append('<div class="overlay"><i class="fa fa-refresh fa-spin"></i></div>');
    },
    onSubmitSuccess: () => {
        $('#modal-export-start-end-date').modal('hide');
    },
    onSubmitFinished: () => {
        $('.box .overlay').remove();
    }
});

// end inisialisasi export

$(document).ready(function() {

    // btn Export
    $('#exportExcel').on('click', async function(){
        // if(this.value.trim() != "") 
        // $('#modalTanggalExport').modal()         
        console.log('Button exportExcel Clicked');
        exportOperasional.show({
            title: 'Export Data Operasional',
        });
    });

    // event on click refresh table
    $('#refreshTable').on('click', function() {
        console.log('Button Refresh Table Operasional clicked...');
        refreshTable(operasionalTable, $(this));
    });

});

/**
*
*/
function export_excel() {
   
    console.log('Export Detail Clicked');

    var tgl_awal = $('#tgl_awal').val().trim();
    var tgl_akhir = $('#tgl_akhir').val().trim();

    if(tgl_awal == '' && tgl_akhir != ''){
        swal({
            type: 'error',
            title: 'Tanggal Awal Harus Diisi!',
            text: 'Isi atau kosongkan keduanya !'
        })
    } else if(tgl_awal != '' && tgl_akhir == ''){
        swal({
            type: 'error',
            title: 'Tanggal Akhir Harus Diisi!',
            text: 'Isi atau kosongkan keduanya !'
        })
    } else {
    window.location.href = BASE_URL+'operasional/export?tgl_awal=' + tgl_awal + '&tgl_akhir=' + tgl_akhir;
    }
}

/**
*
*/
function getView(id){
	window.location.href = BASE_URL+'operasional/detail/'+id.toLowerCase();
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
				url: BASE_URL+'operasional/delete/'+id.toLowerCase(),
				type: 'post',
				dataType: 'json',
				data: {},
				beforeSend: function(){

				},
				success: function(output){
					console.log(output);
					if(output){
						swal("Pesan Berhasil", "Data Berhasil Dihapus", "success");
						$("#operasionalTable").DataTable().ajax.reload();
					}
				},
				error: function (jqXHR, textStatus, errorThrown){ // error handling
		            console.log(jqXHR, textStatus, errorThrown);
                    swal("Pesan Gagal", "Terjadi Kesalahan Teknis, Silahkan Coba Kembali", "error");
		        }
			})
		});
	
   
}
