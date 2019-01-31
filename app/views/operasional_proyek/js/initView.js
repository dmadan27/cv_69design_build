$(document).ready(function(){

      //Date picker
	$('#tgl_awal').datepicker({
		autoclose: true,
		format: "yyyy-mm-dd",
		todayHighlight: true,
		orientation:"bottom auto",
		todayBtn: true,
	  });

	  //Date picker
	$('#tgl_akhir').datepicker({
		autoclose: true,
		format: "yyyy-mm-dd",
		todayHighlight: true,
		orientation:"bottom auto",
		todayBtn: true,
	  });

	// tabel history pembelian
	var historyPembelian = $("#historyPembelian").DataTable({
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
            url: BASE_URL+"operasional-proyek/get-list-history-pembelian/"+$('#id').val().trim(),
            type: 'POST',
            data: {

            }
        },
        "columnDefs": [
            {
                "targets":[0, 7],
                "orderable":false,
            }
        ],
        createdRow: function(row, data, dataIndex){
            // if($(data[7]).text().toLowerCase() == "selesai") $(row).addClass('danger');
            for(var i = 0; i < 7; i++){
                if(i == 0 || i == 5) $('td:eq('+i+')', row).addClass('text-right');
            }
        }
    });


    var detailOperasionalProyek = $("#detailOperasionalProyek").DataTable({
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
            url: BASE_URL+"operasional-proyek/get-detail-operasional-proyek/"+$('#id').val().trim(),
            type: 'POST',
            data: {

            }
        },
        "columnDefs": [
            {
                "targets":[0, 3],
                "orderable":false,
            }
        ],
        createdRow: function(row, data, dataIndex){
            // if($(data[7]).text().toLowerCase() == "selesai") $(row).addClass('danger');
            for(var i = 0; i < 7; i++){
                if(i == 0 || i == 5) $('td:eq('+i+')', row).addClass('text-right');
            }
        }
    });

    // btn Export
    $('#exportExcel').on('click', function(){
        // if(this.value.trim() != "") 
        $('#modalTanggalExport').modal()         
        console.log('Button exportExcel Clicked');
    });

});

/**
*
*/
function export_excel(id) {
   
    console.log('Export Detail Clicked');

    var tgl_awal = $('#tgl_awal').val().trim();
    var tgl_akhir = $('#tgl_akhir').val().trim();

    if(tgl_awal == '' && tgl_akhir == ''){
        swal({
            type: 'error',
            title: 'Tanggal Tidak Boleh Kosong!',
        })
    } else if(tgl_awal == '' && tgl_akhir != ''){
        swal({
            type: 'error',
            title: 'Tanggal Awal Harus Diisi!',
        })
    } else if(tgl_awal != '' && tgl_akhir == ''){
        swal({
            type: 'error',
            title: 'Tanggal Akhir Harus Diisi!',
        })
    } else if(new Date(tgl_awal) > new Date(tgl_akhir)){
        swal({
            type: 'error',
            title: 'Kesalahan Input !',
        })
    } else {
    window.location.href = BASE_URL+'operasional-proyek/export-detail?tgl_awal=' + tgl_awal + '&tgl_akhir=' + tgl_akhir + '&id=' + id;
    }
}

// function export_detail(id) {
//     console.log(id);
//     window.location.href = BASE_URL+'operasional-proyek/export-detail/?id=' + id;
// }

// function export_history(id) {
//     console.log(id);
//     window.location.href = BASE_URL+'operasional-proyek/export-history/?id=' + id;
// }