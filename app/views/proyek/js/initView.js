$(document).ready(function(){
    // tabel pengajuan skk
	var pengajuan_skkTable = $("#pengajuan_skkTable").DataTable({
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
            url: BASE_URL+"proyek/get-list-pengajuan-sub-kas-kecil/"+$('#id').val().trim(),
            type: 'POST',
            data: {}
        },
        "columnDefs": [
            {
                "targets":[0, 8],
                "orderable":false,
            }
        ],
        createdRow: function(row, data, dataIndex){
            // if($(data[7]).text().toLowerCase() == "selesai") $(row).addClass('danger');
            for(var i = 0; i < 7; i++){
                if(i == 0 || i == 5 || i == 6) $('td:eq('+i+')', row).addClass('text-right');
            }
        }
    });

    // tabel operasional proyek
    var operasional_proyekTable = $("#operasional_proyekTable").DataTable({
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
            url: BASE_URL+"proyek/get-list-operasional-proyek/"+$('#id').val().trim(),
            type: 'POST',
            data: {}
        },
        "columnDefs": [
            {
                "targets":[0, 8],
                "orderable":false,
            }
        ],
        createdRow: function(row, data, dataIndex){
            // if($(data[7]).text().toLowerCase() == "selesai") $(row).addClass('danger');
            for(var i = 0; i < 7; i++){
                if(i == 0 || i == 7) $('td:eq('+i+')', row).addClass('text-right');
            }
        }
    });

});