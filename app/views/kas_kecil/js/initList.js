var kasKecilTable = $("#kasKecilTable").DataTable({
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
        url: BASE_URL+"kas-kecil/get-list/",
        type: 'POST',
        data: {}
       
    },
    
});

$(document).ready(function() {

    // btn Export
    $('#exportExcel').on('click', async function(){
        // window.location.href = BASE_URL+'kas-kecil/export/';
        $('.box').append('<div class="overlay"><i class="fa fa-refresh fa-spin"></i></div>');
        try {
            await Export.excel({
                method: 'kas-kecil',
            });
        } catch (error) {
            if (error.code == "InfoException") {
                swal("Pesan", error.message, "info");
            } else {
                console.log("Log Export Sub Kas Kecil: " + error.message);
                swal("Pesan Gagal", "Terjadi Kesalahan Teknis, Silahkan Coba Kembali", "error");
            }
        }
        $('.box .overlay').remove();
    });

    // event on click refresh table
    $('#refreshTable').on('click', function() {
        console.log('Button Refresh Table Bank clicked...');
        refreshTable(kasKecilTable, $(this));
    });

    // auto refresh every 1 minutes
    setInterval( function () {
        console.log('%cAutomatically refresh table..', 'color: blue; font-style: italic');
        kasKecilTable.ajax.reload(null, false);
    }, 60000 );

});

function getView(id){
    window.location.href = BASE_URL+'kas-kecil/detail/'+id;
}

/**
*
*/
function getDelete(id){
    
    swal({
        title: "Pesan Konfirmasi",
        text: "Apakah Anda Yakin Akan Menghapus Data Ini !!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Ya, Hapus!",
        cancelButtonText: "Batal",
        closeOnConfirm: false,
    }, function(){
        $.ajax({
            url: BASE_URL+'kas-kecil/delete/'+id.toLowerCase(),
            type: 'post',
            dataType: 'json',
            data: {},
            beforeSend: function(){
            },
            success: function(response){
                console.log(response);
                if(response.success){
                    swal("Pesan Berhasil", "Data Berhasil Dihapus", "success");
                    $("#kasKecilTable").DataTable().ajax.reload();
                }
            },
            error: function (jqXHR, textStatus, errorThrown){ // error handling
                console.log(jqXHR, textStatus, errorThrown);
                swal("Pesan Gagal", "Terjadi Kesalahan Teknis, Silahkan Coba Kembali", "error");
            }
        })
    });
    
}