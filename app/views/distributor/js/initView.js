var historyPembelianDistributor = $("#historyPembelianDistributor").DataTable({
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
        url: BASE_URL+"distributor/get-history-distributor/"+$('#id').val(),
        type: 'POST',
        data: {},
    },
    "columnDefs": [
        {
            "targets":[0, 4], // disable order di kolom 1 dan 3
            "orderable":false,
        }
    ],
    createdRow: function(row, data, dataIndex){
        for(var i = 0; i < 5; i++){
            if(i != 5) $('td:eq('+i+')', row).addClass('text-right');
        }

        // console.log(data);
    }
});

$(document).ready(function(){

    // event on click refresh table
    $('#refreshTable').on('click', function() {
        console.log('Button Refresh Table Distributor clicked...');
        refreshTable(historyPembelianDistributor, $(this));
    });

    // auto refresh every 1 minutes
    setInterval( function () {
        console.log('%cAutomatically refresh table..', 'color: blue; font-style: italic');
        historyPembelianDistributor.ajax.reload(null, false);
    }, 60000 );
});