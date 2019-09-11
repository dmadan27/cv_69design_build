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
        url: BASE_URL + "pengajuan-kas-kecil/get-list/",
        type: 'POST',
        data: {}
    },
    "columnDefs": [
        {
            "targets": [0, 7],
            "orderable": false,
        }
    ],
    createdRow: function (row, data, dataIndex) {
        if (data[0]) $('td:eq(0)', row).addClass('text-right');
        if (data[5]) $('td:eq(5)', row).addClass('text-right');
    }
});

// inisialisasi export
// export histori pengajuan
const exportPengajuanKasKecil = new FormExportStartEndDate({
    method: 'pengajuan-kas-kecil',
    onInitSubmit: () => {
        $('.box').append('<div class="overlay"><i class="fa fa-refresh fa-spin"></i></div>');
    },
    onSubmitSuccess: () => {
        $('#modal-export-start-end-date').modal('hide');
    },
    onSubmitFinished: () => {
        $('.box .overlay').remove();
    }
});

// end inisialisasi export

$(document).ready(function () {

    // btn Export
    $('#exportExcel').on('click', function () {
        console.log('Button exportExcel Clicked');
        exportPengajuanKasKecil.show({
            title: 'Export Data Pengajuan Kas Kecil',
        });
    });

    // event on click refresh table
    $('#refreshTable').on('click', function () {
        console.log('Button Refresh Table Pengajuan Kas Kecil clicked...');
        refreshTable(pengajuanKasKecilTable, $(this));
    });

    // auto refresh every 1 minutes
    // setInterval(function () {
    //     console.log('%cAutomatically refresh table..', 'color: blue; font-style: italic');
    //     pengajuanKasKecilTable.ajax.reload(null, false);
    // }, 60000);

});

/**
*
*/
function getView(id) {
    if(LEVEL === 'KAS BESAR' || LEVEL === 'KAS KECIL' || LEVEL === 'OWNER') {
        $.ajax({
            url: BASE_URL + 'pengajuan-kas-kecil/detail/' + id.toLowerCase(),
            type: 'post',
            dataType: 'json',
            data: {},
            beforeSend: function () {

            },
            success: function (output) {

                $('#modalView_PKK').modal();
                console.log('%cgetView Response:', '', output);

                $('#res_id').html(output.id);
                $('#id').html(output.id_kas_kecil);
                $('#kas_kecil').html(output.kas_kecil);
                $('#tgl').html(output.tgl);
                $('#nama').html(output.nama);
                $('#total').html(output.total);
                $('#total_disetujui').html(output.total_disetujui);
                $('#status').html(output.status);
                $('#alasan_perbaiki').html(output.ket || "-");
                
            },
            error: function (jqXHR, textStatus, errorThrown) { // error handling
                console.log(jqXHR, textStatus, errorThrown);
            }
        })   

        return;
    }

    setNotif(notifAccessDenied, 'swal');
}

/**
*
*/
function getDelete(id) {
    if(LEVEL !== 'KAS BESAR' || LEVEL !== 'KAS KECIL') {
        setNotif(notifAccessDenied, 'swal');
        return;
    }

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
            url: BASE_URL + 'pengajuan-kas-kecil/delete/' + id.toLowerCase(),
            type: 'post',
            dataType: 'json',
            data: {},
            beforeSend: function () {
                $('.box').append('<div class="overlay"><i class="fa fa-refresh fa-spin"></i></div>');
            },
            success: function (output) {
                console.log(output);

                $('.box .overlay').remove();
                if (output) {
                    swal("Pesan Berhasil", "Data Berhasil Dihapus", "success");
                    pengajuanKasKecilTable.ajax.reload();
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log('%c Response Error getDelete: ', 'color: red; font-weight: bold', {
                    jqXHR: jqXHR, 
                    textStatus: textStatus, 
                    errorThrown: errorThrown
                });

                $('.box .overlay').remove();
                pengajuanKasKecilTable.ajax.reload();

                swal("Pesan Gagal", "Terjadi Kesalahan Teknis, Silahkan Coba Kembali", "error");
            }
        })
    });
}
