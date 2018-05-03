$(document).ready(function(){
	var tabelProyek = $("#tabelProyek").DataTable({
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
        "lengthMenu": [ 10, 25, 50, 100 ],
        "pageLength": 10,
        // order: [],
        // processing: true,
        // serverSide: true,
        // ajax: {
        //     url: BASE_URL+"proyek/get-list/",
        //     type: 'POST',
        //     data: {
        //         "token" : $("#tokenCrsf"),
        //     }
        // },
        "columnDefs": [
            {
                "targets":[0, 6], // disable order di kolom 1 dan 3
                "orderable":false,
            }
        ],
    });
});