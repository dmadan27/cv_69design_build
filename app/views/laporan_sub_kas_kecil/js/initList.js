var laporan_pengajuan_sub_kas_kecilTable = $("#laporan_pengajuan_sub_kas_kecilTable").DataTable({
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
        url: BASE_URL+"laporan-sub-kas-kecil/get-list/",
        type: 'POST',
        data: {}
    },
    "columnDefs": [
        {
            "targets":[0, 9],
            "orderable":false,
        }
    ],
    createdRow: function(row, data, dataIndex){
        if($(data[8]).text().toLowerCase() == "ditolak") { $(row).addClass('danger'); }
        for(var i = 0; i < 10; i++){
            if(i == 0 || i == 6 || i == 7) { $('td:eq('+i+')', row).addClass('text-right'); }
        }
    }
});

$(document).ready(function(){

	// btn Export
    $('#exportExcel').on('click', function(){
        console.log('%cButton Export Excel Proyek clicked...', 'font-style: italic');
        getExport();
    });

    // event on click refresh table
    $('#refreshTable').on('click', function() {
        console.log('Button Refresh Table Laporan SKK clicked...');
        refreshTable(laporan_pengajuan_sub_kas_kecilTable, $(this));
    });

    // auto refresh every 1 minutes
    setInterval( function () {
        console.log('%cAutomatically refresh table..', 'color: blue; font-style: italic');
        laporan_pengajuan_sub_kas_kecilTable.ajax.reload(null, false);
    }, 60000 );
});

/**
 * 
 */
function getExport() {

}

/**
 * 
 */
function getEdit(id) {
    window.location.href = BASE_URL+'laporan-sub-kas-kecil/detail/'+id.toLowerCase();
}

