$(document).ready(function(){
    var listProyek = $('#listProyek').DataTable({
        'paging'      : true,
        'lengthChange': false,
        'searching'   : false,
        'ordering'    : true,
        'info'        : true,
        'autoWidth'   : false,
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
        "pageLength": 10,
        order: [],
        processing: true,
        serverSide: true,
        ajax: {
            url: BASE_URL+"home/get-proyek-list/",
            type: 'POST',
            data: {}
        },
        "columnDefs": [
            {
                "targets":[0, 1],
                "orderable":false,
            }
        ],
        createdRow: function(row, data, dataIndex){
        	if($(data[3]).text().toLowerCase() == "nonaktif") $(row).addClass('danger');
        	for(var i = 0; i < 5; i++){
                if(i == 1) $('td:eq('+i+')', row).addClass('text-right');
                if(i == 0) $('td:eq('+i+')', row).addClass('text-left');
        	}
        }
    });

    var listBank = $('#listBank').DataTable({
        'paging'      : true,
        'lengthChange': false,
        'searching'   : false,
        'ordering'    : true,
        'info'        : true,
        'autoWidth'   : false,
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
        "pageLength": 10,
        order: [],
        processing: true,
        serverSide: true,
        ajax: {
            url: BASE_URL+"home/get-bank-list/",
            type: 'POST',
            data: {}
        },
        "columnDefs": [
            {
                "targets":[0, 1],
                "orderable":false,
            }
        ],
        createdRow: function(row, data, dataIndex){
        	if($(data[3]).text().toLowerCase() == "nonaktif") $(row).addClass('danger');
        	for(var i = 0; i < 5; i++){
                if(i == 1) $('td:eq('+i+')', row).addClass('text-right');
                if(i == 0) $('td:eq('+i+')', row).addClass('text-left');
        	}
        }
    });
})