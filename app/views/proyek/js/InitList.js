$(document).ready(function(){
	var proyekTable = $("#proyekTable").DataTable({
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
            url: BASE_URL+"proyek/get-list/",
            type: 'POST',
            data: {
                "token_list" : $('#token_list').val().trim(),
            }
        },
        "columnDefs": [
            {
                "targets":[0, 8],
                "orderable":false,
            }
        ],
        // createdRow: function(row, data, dataIndex){
        //     if($(data[3]).text().toLowerCase() == "nonaktif") $(row).addClass('danger');
        //     for(var i = 0; i < 5; i++){
        //         if(i != 1 && i != 2) $('td:eq('+i+')', row).addClass('text-center'); 
        //         if(i == 2) $('td:eq('+i+')', row).addClass('text-right'); // rata kanan untuk data saldo
        //     }
        // }
    });

    $('#tambah').on('click', function(){
        if(this.value.trim() != ""){
            window.location.href = BASE_URL+'proyek/form/';
        }
    });

});