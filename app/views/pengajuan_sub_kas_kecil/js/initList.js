$(document).ready(function(){
	var pengajuan_sub_kas_kecilTable = $("#pengajuan_sub_kas_kecilTable").DataTable({
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
            url: BASE_URL+"pengajuan-sub-kas-kecil/get-list/",
            type: 'POST',
            data: {
                "token_list" : $('#token_list').val().trim(),
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
            // for(var i = 0; i < 9; i++){
            //     if(i == 0 || i == 6) $('td:eq('+i+')', row).addClass('text-right');
            // }
        }
    });

    setInterval(function(){
    	pengajuan_sub_kas_kecilTable.ajax.reload(null, false);
    }, 10000);
});