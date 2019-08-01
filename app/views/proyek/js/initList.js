var proyekTable = $("#proyekTable").DataTable({
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
        url: BASE_URL + "proyek/get-list/",
        type: 'POST',
        data: {}
    },
    "columnDefs": [
        {
            "targets": [0, 9],
            "orderable": false,
        }
    ],
    createdRow: function (row, data, dataIndex) {
        if (data[0]) $('td:eq(0)', row).addClass('text-right');
        if (data[6]) $('td:eq(6)', row).addClass('text-right');
        if ($(data[8]).text().toLowerCase() == "selesai") $(row).addClass('danger');
    }
});

// init export start end date
const exportExcelProyek = new FormExportStartEndDate({
    method: 'proyek',
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
// end init export start end date

$(document).ready(function () {

    // btn tambah
    $('#tambah').on('click', function () {
        console.log('%cButton Tambah Proyek clicked...', 'font-style: italic');
        add();
    });

    // btn Export
    $('#exportExcel').on('click', function () {
        console.log('%cButton Export Excel Proyek clicked...', 'font-style: italic');
        exportExcelProyek.show({
            title: 'Export Data Proyek',
        });
    });

    // event on click refresh table
    $('#refreshTable').on('click', function () {
        console.log('Button Refresh Table Proyek clicked...');
        refreshTable(proyekTable, $(this));
    });
});

/**
 * Function getView
 * @param {string} id
 */
function getView(id) {
    console.log('%cButton View Proyek clicked...', 'font-style: italic');

    window.location.href = BASE_URL + 'proyek/detail/' + id;
}

/**
 * Function getEdit
 * @param {string} id 
 */
function getEdit(id) {
    console.log('%cButton Edit Proyek clicked...', 'font-style: italic');

    window.location.href = BASE_URL + 'proyek/form/' + id;
}

/**
 * Function getDelete
 * Proses request hapus data proyek ke server
 * @param {string} id
 * @return {object} response
 */
function getDelete(id) {
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
    }, function () {
        $.ajax({
            url: BASE_URL + 'proyek/delete/' + id.toLowerCase(),
            type: 'post',
            dataType: 'json',
            data: {},
            beforeSend: function () {
                $('.box').append('<div class="overlay"><i class="fa fa-refresh fa-spin"></i></div>');
            },
            success: function (response) {
                $('.box .overlay').remove();
                console.log('%cResponse getDelete Proyek: ', 'color: green; font-weight: bold', response);
                if (response.success) { proyekTable.ajax.reload(null, false); }
                swal(response.notif.title, response.notif.message, response.notif.type);
            },
            error: function (jqXHR, textStatus, errorThrown) { // error handling
                $('.box .overlay').remove();
                console.log('%cResponse Error getDelete Proyek', 'color: red; font-weight: bold', jqXHR, textStatus, errorThrown);
                swal("Pesan Gagal", "Terjadi Kesalahan Teknis, Silahkan Coba Kembali", "error");
                proyekTable.ajax.reload(null, false);
            }
        })
    });
}

/**
 * 
 */
function add() {
    if (LEVEL === 'KAS BESAR') {
        window.location.href = BASE_URL + 'proyek/form/';
    }
    else {
        setNotif(nofitAccessDenied, 'swal');
    }
}