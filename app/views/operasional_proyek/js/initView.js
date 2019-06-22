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
        url: BASE_URL+"operasional-proyek/get-list-detail/"+$('#id').val().trim(),
        type: 'POST',
        data: {}
    },
    "columnDefs": [
        {
            "targets":[0, 4],
            "orderable":false,
        }
    ],
    createdRow: function(row, data, dataIndex){
        if(data[0]) $('td:eq(0)', row).addClass('text-right');
        if(data[4]) $('td:eq(4)', row).addClass('text-right');
    }
});

$(document).ready(function() {

    // btn Export
    $('#exportExcel').on('click', async function(){
        console.log('Button exportExcel Clicked');

        $('.box-detail_operasionalProyek').append('<div class="overlay"><i class="fa fa-refresh fa-spin"></i></div>');

        try {
            await Export.excel({
                method: "operasional-proyek-detail",
                id: $('#id').val().trim(),
            });
        } catch (error) {
            if (error.code == "InfoException") {
                swal("Pesan", error.message, "info");
            } else {
                console.log("Log Export Excel Error: " + error.message);
                swal("Pesan Gagal", "Terjadi Kesalahan Teknis, Silahkan Coba Kembali", "error");
            }
        }

        $('.box-detail_operasionalProyek .overlay').remove();
    });

    // event on click refresh table
    $('#refreshTable').on('click', function() {
        console.log('Button Refresh Table Detail Operasional Proyek clicked...');
        refreshTable(detailOperasionalProyek, $(this));
    });
});