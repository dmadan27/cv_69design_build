$(document).ready(function(){
    
	var pengajuanKasKecilTable = $("#pengajuanKasKecilTable").DataTable({
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
            url: BASE_URL+"pengajuan-kas-kecil/get-list/",
            type: 'POST',
            data: {
                // "token_list" : $('#token_list').val().trim(),
            }
            
        }});

    // btn Export
    $('#exportExcel').on('click', function(){
        $('#modalTanggalExport').modal()
        console.log('Button exportExcel Clicked');
    });
       
});

    /**
    *
    */
    function export_excel() {

    console.log('Export Detail Clicked');

    var tgl_awal = $('#tgl_awal').val().trim();
    var tgl_akhir = $('#tgl_akhir').val().trim();

    if(tgl_awal == '' && tgl_akhir == ''){
        swal({
            type: 'error',
            title: 'Tanggal Tidak Boleh Kosong!',
        })
    } else if(tgl_awal == '' && tgl_akhir != ''){
        swal({
            type: 'error',
            title: 'Tanggal Awal Harus Diisi!',
            text: 'Isi atau kosongkan keduanya !'
        })
    } else if(tgl_awal != '' && tgl_akhir == ''){
        swal({
            type: 'error',
            title: 'Tanggal Akhir Harus Diisi!',
            text: 'Isi atau kosongkan keduanya !'
        })
    } else if(new Date(tgl_awal) > new Date(tgl_akhir)){
        swal({
            type: 'error',
            title: 'Kesalahan Input !',
            text: 'Tanggal Awal Melebihi Tanggal Akhir!'
        })
    }else {
        window.location.href = BASE_URL+'pengajuan-kas-kecil/export?tgl_awal=' + tgl_awal + '&tgl_akhir=' + tgl_akhir;
    }
}


/**
*
*/
function getView(id){
    // window.location.href = BASE_URL+'pengajuan-kas-kecil/detail/'+id;
    $('#modalView_PKK').modal();
    $.ajax({
        url: BASE_URL+'pengajuan-kas-kecil/detail/'+id.toLowerCase(),
        type: 'post',
        dataType: 'json',
        data: {},
        beforeSend: function(){

        },
        success: function(output){
            
                $('#modalView_PKK').modal();
                console.log('%cgetView Response:','',output);
                
                $('#res_id').html(output.id);
                $('#id').html(output.id_kas_kecil); 
                $('#kas_kecil').html(output.kas_kecil);     
                $('#tgl').html(output.tgl);   
                $('#nama').html(output.nama);   
                $('#total').html(output.total);
                $('#total_disetujui').html(output.total_disetujui);
                $('#status').html(output.status);            
        
        },
        error: function (jqXHR, textStatus, errorThrown){ // error handling
            console.log(jqXHR, textStatus, errorThrown);
        }
    })
}

/**
*
*/
function getDelete(id, token){
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
                url: BASE_URL+'pengajuan-kas-kecil/delete/'+id.toLowerCase(),
                type: 'post',
                dataType: 'json',
                data: {},
                beforeSend: function(){

                },
                success: function(output){
                    console.log(output);
                    if(output){
                        swal("Pesan Berhasil", "Data Berhasil Dihapus", "success");
                        $("#pengajuanKasKecilTable").DataTable().ajax.reload();
                    }
                },
                error: function (jqXHR, textStatus, errorThrown){ // error handling
                    console.log(jqXHR, textStatus, errorThrown);
                    swal("Pesan Gagal", "Terjadi Kesalahan Teknis, Silahkan Coba Kembali", "error");
                }
            })
        });
}
