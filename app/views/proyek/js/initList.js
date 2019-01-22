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
            data: {}
        },
        "columnDefs": [
            {
                "targets":[0, 9],
                "orderable":false,
            }
        ],
        createdRow: function(row, data, dataIndex){
            if($(data[8]).text().toLowerCase() == "selesai") $(row).addClass('danger');
            for(var i = 0; i < 10; i++){
                if(i == 0 || i == 6) $('td:eq('+i+')', row).addClass('text-right');
            }
        }
    });

    // btn tambah
    $('#tambah').on('click', function(){
        console.log('%cButton Tambah Proyek clicked...', 'font-style: italic');
        window.location.href = BASE_URL+'proyek/form/';
    });

     // btn Export
    $('#exportExcel').on('click', function(){
        console.log('%cButton Export Excel Proyek clicked...', 'font-style: italic');
        getExport();
    });

});

/**
 * Function getView
 * @param {string} id
 */
function getView(id){
    console.log('%cButton View Proyek clicked...', 'font-style: italic');
    
    window.location.href = BASE_URL+'proyek/detail/'+id;
}

/**
 * Function getEdit
 * @param {string} id 
 */
function getEdit(id){
    console.log('%cButton Edit Proyek clicked...', 'font-style: italic');

    window.location.href = BASE_URL+'proyek/form/'+id;
}

/**
 * Function getDelete
 * Proses request hapus data proyek ke server
 * @param {string} id
 * @return {object} response
 */
function getDelete(id){
    console.log('%cButton Hapus Proyek clicked...', 'font-style: italic');

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
            url: BASE_URL+'proyek/delete/'+id.toLowerCase(),
            type: 'post',
            dataType: 'json',
            data: {},
            beforeSend: function(){
                // tampilkan loading
            },
            success: function(response){
                // stop loading

                console.log('%cResponse getDelete Proyek: ', 'color: green; font-weight: bold', response);
                if(response.success){ $("#proyekTable").DataTable().ajax.reload(); }
                swal(response.notif.title, response.notif.message, response.notif.type);
            },
            error: function (jqXHR, textStatus, errorThrown){ // error handling
                // stop loading
                
                console.log('%cResponse Error getDelete Proyek', 'color: red; font-weight: bold', jqXHR, textStatus, errorThrown);
                swal("Pesan Gagal", "Terjadi Kesalahan Teknis, Silahkan Coba Kembali", "error");
                $("#proyekTable").DataTable().ajax.reload();
            }
        })
    });
}

/**
 * 
 */
function getExport(){
    
}