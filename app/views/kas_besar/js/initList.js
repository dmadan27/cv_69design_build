var kasBesarTable = $("#kasBesarTable").DataTable({
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
        url: BASE_URL+"kas-besar/get-list/",
        type: 'POST',
        data: {}
    },
    "columnDefs": [
        {
            "targets":[0, 5],
            "orderable":false,
        }
    ],
    createdRow: function(row, data, dataIndex){
    }
});

$(document).ready(function(){

    // btn Export
    $('#exportExcel').on('click', function(){
        // if(this.value.trim() != "") 
        window.location.href = BASE_URL+'kas-besar/export/';
    });

    // event on click refresh table
    $('#refreshTable').on('click', function() {
        console.log('Button Refresh Table Bank clicked...');
        refreshTable(kasBesarTable, $(this));
    });

});

/**
 * 
 * @param {string} id 
 */
function getView(id){
	window.location.href = BASE_URL+'kas-besar/detail/'+id.toLowerCase();
}

/**
 * 
 * @param {string} id 
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
            url: BASE_URL+'kas-besar/delete/'+id.toLowerCase(),
            type: 'post',
            dataType: 'json',
            data: {},
            beforeSend: function(){
            },
            success: function(response){
                console.log("%cResponse getDelete: ", "color: green; font-weight: bold", response);
                if(response.success){
                    swal("Pesan Berhasil", "Data Berhasil Dihapus", "success");
                    $("#kasBesarTable").DataTable().ajax.reload();
                }
            },
            error: function (jqXHR, textStatus, errorThrown){
                console.log("%cResponse Error getDelete: ", "color: red; font-weight: bold", jqXHR, textStatus, errorThrown);
                swal("Pesan Gagal", "Terjadi Kesalahan Teknis, Silahkan Coba Kembali", "error");
            }
        })
    });
	
}
