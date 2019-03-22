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
        data: {}
    },
    "columnDefs": [
        {
            "targets":[0, 7],
            "orderable":false,
        }
    ],
    createdRow: function(row, data, dataIndex){
        for(var i = 0; i < 10; i++){
            if(i == 0 || i == 6 || i == 7) $('td:eq('+i+')', row).addClass('text-right');
        }
    }
});

$(document).ready(function(){

    // btn Export
    // menampilkan form export pengajuan sub kas kecil
	$('#exportExcel').on('click', function () {

        // option
        $('.modal-export-title').html('Ekspor Data Pengajuan Sub Kas Kecil');
        $('#form-export-months-year').attr('action', BASE_URL + 'export/pengajuan-sub-kas-kecil');

        // wajib
        $('#tahun').val('').attr('readonly', true);
        $('#bulan').val('').attr('readonly', true).datepicker("destroy");
        $('#modal-export-months-year').modal();
    });

    // event on click refresh table
    $('#refreshTable').on('click', function() {
        console.log('Button Refresh Table Pengajuan SKK clicked...');
        refreshTable(pengajuan_sub_kas_kecilTable, $(this));
    });

    // auto refresh every 1 minutes
    setInterval( function () {
        console.log('%cAutomatically refresh table..', 'color: blue; font-style: italic');
        pengajuan_sub_kas_kecilTable.ajax.reload(null, false);
    }, 60000 );
});

/**
 * 
 * @param {*} id 
 */
function getView(id){
    window.location.href = BASE_URL+'pengajuan-sub-kas-kecil/detail/'+id.toLowerCase();
}

/**
 * 
 * @param {*} id 
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
            url: BASE_URL+'pengajuan-sub-kas-kecil/delete/'+id.toLowerCase(),
            type: 'post',
            dataType: 'json',
            data: {},
            beforeSend: function(){
            },
            success: function(output){
                console.log(output);
                if(output){
                    swal("Pesan Berhasil", "Data Berhasil Dihapus", "success");
                    $("#pengajuan_sub_kas_kecilTable").DataTable().ajax.reload();
                }
            },
            error: function (jqXHR, textStatus, errorThrown){ // error handling
                console.log(jqXHR, textStatus, errorThrown);
                swal("Pesan Gagal", "Terjadi Kesalahan Teknis, Silahkan Coba Kembali", "error");
            }
        })
    });
}