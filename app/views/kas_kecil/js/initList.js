$(document).ready(function(){
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

    // btn Export
    $('#exportExcel').on('click', function(){
        // if(this.value.trim() != "") 
            window.location.href = BASE_URL+'kas-kecil/export/';
       
    });
        

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
                success: function(output){
                    console.log(output);
                    if(output){
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