var mutasiSubKasKecilTable = $("#mutasiSubKasKecilTable").DataTable({
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
        url: BASE_URL+"sub-kas-kecil/get-mutasi/"+$('#id').val().trim(),
        type: 'POST',
        data: {
            // "token_view" : $('#token_view').val().trim(),
            // "id" : $('#id').val().trim(),
        }
    },
    "columnDefs": [
        {
            "targets":[0, 4], // disable order di kolom 1 dan 3
            "orderable":false,
        }
    ],
    createdRow: function(row, data, dataIndex){
        for(var i = 0; i < 5; i++){
            if(i != 5) $('td:eq('+i+')', row).addClass('text-right');
        }

        // console.log(data);
    }
});

var pengajuanSubKasKecilTable = $("#pengajuanSubKasKecilTable").DataTable({
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
        url: BASE_URL+"sub-kas-kecil/get-history-pengajuan/"+$('#id').val().trim(),
        type: 'POST',
        data: {
            // "token_view" : $('#token_view').val().trim(),
            // "id" : $('#id').val().trim(),
        }
    },
    "columnDefs": [
        {
            "targets":[0, 5], // disable order di kolom 1 dan 3
            "orderable":false,
        }
    ],
    createdRow: function(row, data, dataIndex){
        for(var i = 0; i < 5; i++){
            if(i != 5) $('td:eq('+i+')', row).addClass('text-right');
        }
        // console.log(data);
    }
});

// inisialisasi export
// export detail sub kas kecil
const exportSubKasKecil = new FormExportMonthsYear({
    method: 'sub-kas-kecil-detail',
    id: $('#id').val().trim(),
    onInitSubmit: () => {        
        console.log('before export detail skk');
        // $('.box-pengajuan_skk').append('<div class="overlay"><i class="fa fa-refresh fa-spin"></i></div>');
    },
    onSubmitSuccess: () => {
        $('#modal-export-months-year').modal('hide');
    },
    onSubmitFinished: () => {
        console.log('after export detail skk');
        // $('.box-pengajuan_skk .overlay').remove();
    }
});

// export mutasi
const exportMutasi = new FormExportStartEndDate({
    method: 'skk-detail-mutasi',
    id: $('#id').val().trim(),
    onInitSubmit: () => {        
        console.log('before export mutasi');
        // $('.box-pengajuan_skk').append('<div class="overlay"><i class="fa fa-refresh fa-spin"></i></div>');
    },
    onSubmitSuccess: () => {
        $('#modal-export-start-end-date').modal('hide');
    },
    onSubmitFinished: () => {
        console.log('after export mutasi');
        // $('.box-pengajuan_skk .overlay').remove();
    }
});

// export histori pengajuan
const exportHistoriPengajuan = new FormExportStartEndDate({
    method: 'skk-detail-pengajuan',
    id: $('#id').val().trim(),
    onInitSubmit: () => {        
        console.log('before export histori pengajuan');
        // $('.box-pengajuan_skk').append('<div class="overlay"><i class="fa fa-refresh fa-spin"></i></div>');
    },
    onSubmitSuccess: () => {
        $('#modal-export-start-end-date').modal('hide');
    },
    onSubmitFinished: () => {
        console.log('after export histori pengajuan');
        // $('.box-pengajuan_skk .overlay').remove();
    }
});
// end inisialisasi export

$(document).ready(function() {

    // event on click refresh table
    $('#refreshTable_mutasi').on('click', function() {
        console.log('Button Refresh Table Mutasi Sub Kas Kecil clicked...');
        refreshTable(mutasiSubKasKecilTable, $(this));
    });

    $('#refreshTable_pengajuan').on('click', function() {
        console.log('Button Refresh Table Pengajuan Sub Kas Kecil clicked...');
        refreshTable(pengajuanSubKasKecilTable, $(this));
    });

    // auto refresh every 1 minutes
    setInterval( function () {
        console.log('%cAutomatically refresh table..', 'color: blue; font-style: italic');
        mutasiSubKasKecilTable.ajax.reload(null, false);
        pengajuanSubKasKecilTable.ajax.reload(null, false);
    }, 60000 );

    // menampilkan form export mutasi
    $('#exportExcel_mutasi').on('click', () => {
        exportMutasi.show({
            title: 'Export Mutasi Sub Kas Kecil',
            type: 'skk-detail-mutasi',
        });
    });

    // menampilkan form export pengajuan
    $('#exportExcel_pengajuan').on('click', () => {
        exportHistoriPengajuan.show({
            title: 'Export Histori Pengajuan Sub Kas Kecil',
            type: 'skk-detail-pengajuan',
        });
    });

    // menampilkan form export detail
    $('#export-detail').on('click', function () {
        exportSubKasKecil.show({
            title: 'Export Detail Data Sub Kas Kecil',
            type: 'sub-kas-kecil-detail',
        });
    });

});

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
            url: BASE_URL+'sub-kas-kecil/delete/'+id,
            type: 'post',
            dataType: 'json',
            data: {},
            beforeSend: function(){

            },
            success: function(output){
                console.log(output);
                if(output){
                    swal("Pesan Berhasil", "Data Berhasil Dihapus", "success");
                    setTimeout(function(){ 
                            window.location.href = BASE_URL+'sub-kas-kecil/'; 
                    }, 1500);
                }
            },
            error: function (jqXHR, textStatus, errorThrown){ // error handling
                console.log(jqXHR, textStatus, errorThrown);
                swal("Pesan Gagal", "Terjadi Kesalahan Teknis, Silahkan Coba Kembali", "error");
            }
        })
    });
}

/**
*
*/
function back(){
    window.location.href = BASE_URL+'sub-kas-kecil/';
}

/**
*
*/
function getView(){

}