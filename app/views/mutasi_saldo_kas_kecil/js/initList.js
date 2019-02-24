var mutasi_saldo_kas_kecilTable = $("#mutasi_saldo_kas_kecilTable").DataTable({
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
        url: BASE_URL+"saldo-kas-kecil/get-list/",
        type: 'POST',
        data: {}
    },
    "columnDefs": [
        {
            "targets":[0, 5],
            "orderable":false,
        }
    ],
    createdRow: function(row, data, dataIndex) {
        // console.log({row, data, dataIndex});
        if(data[0]) { $('td:eq(0)', row).addClass('text-right'); }
        if(data[2]) { $('td:eq(2)', row).addClass('text-right'); }
        if(data[3]) { $('td:eq(3)', row).addClass('text-right'); }
        if(data[4]) { $('td:eq(4)', row).addClass('text-right'); }
    }
});

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

     // btn Export
     $('#exportExcel').on('click', function(){
        // if(this.value.trim() != "") 
        $('#modalTanggalExport').modal()         
        console.log('Button exportExcel Clicked');
    });

    // event on click refresh table
    $('#refreshTable').on('click', function() {
        console.log('Button Refresh Table Saldo Kas Kecil clicked...');
        refreshTable(mutasi_saldo_kas_kecilTable, $(this));
    });

    // auto refresh every 1 minutes
    setInterval( function () {
        console.log('%cAutomatically refresh table..', 'color: blue; font-style: italic');
        mutasi_saldo_kas_kecilTable.ajax.reload(null, false);
    }, 60000 );

});

/**
*
*/
function export_excel() {
   
    console.log('Export Detail Clicked');

    var tgl_awal = $('#tgl_awal').val().trim();
    var tgl_akhir = $('#tgl_akhir').val().trim();

    if(tgl_awal == '' && tgl_akhir == ''){
        swal({
            type: 'error',
            title: 'Tanggal Harus Diisi!',
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
    } else {
    window.location.href = BASE_URL+'saldo-kas-kecil/export?tgl_awal=' + tgl_awal + '&tgl_akhir=' + tgl_akhir;
    }
}