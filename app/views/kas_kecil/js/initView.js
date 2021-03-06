var mutasiKasKecilTable = $("#mutasiKasKecilTable").DataTable({
    "language": {
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
    "lengthMenu": [10, 25, 75, 100],
    "pageLength": 10,
    order: [],
    processing: true,
    serverSide: true,
    ajax: {
        url: BASE_URL + "kas-kecil/get-mutasi/" + $('#id').val().trim(),
        type: 'POST',
        data: {
            // "token_view" : $('#token_view').val().trim(),
            // "id" : $('#id').val().trim(),
        }
    },
    "columnDefs": [
        {
            "targets": [0, 4], // disable order di kolom 1 dan 3
            "orderable": false,
        }
    ],
    createdRow: function (row, data, dataIndex) {
        for (var i = 0; i < 5; i++) {
            if (i != 5) $('td:eq(' + i + ')', row).addClass('text-right');
        }

        // console.log(data);
    }
});

var pengajuanKasKecilTable = $("#pengajuanKasKecilTable").DataTable({
    "language": {
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
    "lengthMenu": [10, 25, 75, 100],
    "pageLength": 10,
    order: [],
    processing: true,
    serverSide: true,
    ajax: {
        url: BASE_URL + "kas-kecil/get-history-pengajuan/" + $('#id').val().trim(),
        type: 'POST',
        data: {
            // "token_view" : $('#token_view').val().trim(),
            // "id" : $('#id').val().trim(),
        }
    },
    "columnDefs": [
        {
            "targets": [0, 4], // disable order di kolom 1 dan 3
            "orderable": false,
        }
    ],
    createdRow: function (row, data, dataIndex) {
        for (var i = 0; i < 5; i++) {
            if (i != 5) $('td:eq(' + i + ')', row).addClass('text-right');
        }
        // console.log(data);
    }
});

// inisialisasi export
// export detail kas kecil
const exportDetailKasKecil = new FormExportMonthsYear({
    method: 'kas-kecil-detail',
    id: $('#id').val().trim(),
    onInitSubmit: () => {
        $('.box').append('<div class="overlay"><i class="fa fa-refresh fa-spin"></i></div>');
    },
    onSubmitSuccess: () => {
        $('#modal-export-months-year').modal("hide");
    },
    onSubmitFinished: () => {
        $('.box .overlay').remove();
    }
});

// export mutasi
const exportMutasi = new FormExportStartEndDate({
    method: 'kas-kecil-detail-mutasi',
    id: $('#id').val().trim(),
    onSubmitSuccess: () => {
        $('#modal-export-start-end-date').modal("hide");
    }
});

// export histori pengajuan
const exportPengajuan = new FormExportStartEndDate({
    method: 'kas-kecil-detail-pengajuan',
    id: $('#id').val().trim(),
    onSubmitSuccess: () => {
        $('#modal-export-start-end-date').modal("hide");
    }
});

// end inisialisasi export

$(document).ready(function () {

    $('.image-popup').magnificPopup({
        type: 'image',
        closeOnContentClick: true,
        closeBtnInside: false,
        fixedContentPos: true,
        mainClass: 'mfp-no-margins mfp-with-zoom', // class to remove default margin from left and right side
        image: {
            verticalFit: true
        },
        zoom: {
            enabled: true,
            duration: 300 // don't foget to change the duration also in CSS
        }
    });

    // event on click refresh table
    $('#refreshTable_mutasi').on('click', function () {
        console.log('Button Refresh Table Bank clicked...');
        refreshTable(mutasiKasKecilTable, $(this));
    });

    $('#refreshTable_pengajuan').on('click', function () {
        console.log('Button Refresh Table Bank clicked...');
        refreshTable(pengajuanKasKecilTable, $(this));
    });

    // auto refresh every 1 minutes
    setInterval(function () {
        console.log('%cAutomatically refresh table..', 'color: blue; font-style: italic');
        mutasiKasKecilTable.ajax.reload(null, false);
        pengajuanKasKecilTable.ajax.reload(null, false);
    }, 60000);

    // event export data
    $('#exportExcel_detail').on('click', () => {
        console.log('Button Export Detail Kas Kecil clicked...');
        exportDetailKasKecil.show({
            title: "Export Detail Kas Kecil",
            type: 'kas-kecil-detail',
        });
    });

    $('#exportExcel_mutasi').on('click', () => {
        console.log('Button Export Mutasi clicked...');
        exportMutasi.show({
            title: "Export Mutasi Kas Kecil",
            type: 'kas-kecil-detail-mutasi',
        });
    });

    $('#exportExcel_pengajuan').on('click', () => {
        console.log('Button Export Histori Pengajuan clicked...');
        exportPengajuan.show({
            title: "Export Histori Pengajuan Kas Kecil",
            type: 'kas-kecil-detail-pengajuan',
        });
    });
});

/**
*
*/
function getDelete(id, token) {
    if (token.trim() != "") {
        swal({
            title: "Pesan Konfirmasi",
            text: "Apakah Anda Yakin Akan Menghapus Data Ini !!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Ya, Hapus!",
            cancelButtonText: "Batal",
            closeOnConfirm: false,
        }, function () {
            $.ajax({
                url: BASE_URL + 'kas-kecil/delete/' + id,
                type: 'post',
                dataType: 'json',
                data: { "token_delete": token },
                beforeSend: function () {

                },
                success: function (output) {
                    console.log(output);
                    if (output) {
                        swal("Pesan Berhasil", "Data Berhasil Dihapus", "success");
                        setTimeout(function () {
                            window.location.href = BASE_URL + 'bank/';
                        }, 1500);
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) { // error handling
                    console.log(jqXHR, textStatus, errorThrown);
                    swal("Pesan Gagal", "Terjadi Kesalahan Teknis, Silahkan Coba Kembali", "error");
                }
            })
        });
    }
    else swal("Pesan Gagal", "Terjadi Kesalahan Teknis, Silahkan Coba Kembali", "error");
}

/**
*
*/
function back() {
    window.location.href = BASE_URL + 'kas-kecil/';
}