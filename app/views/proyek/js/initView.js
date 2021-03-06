// init datatable
// tabel detail pembayaran
var detail_pembayaranTable = $("#detail_pembayaran").DataTable({
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
    "order": [],
    "processing": true,
    "serverSide": true,
    "ajax": {
        url: BASE_URL + "proyek/get-list-detail-pembayaran/" + $('#id').val().trim(),
        type: 'POST',
        data: {}
    },
    "columns": [
        {
            className: 'text-right',
            orderable: false,
            data: 'no_urut'
        },
        { data: 'tgl' },
        { data: 'nama' },
        { data: 'nama_bank' },
        {
            data: 'DP',
            render: function (data) {
                var status_dp = '';
                console.log(data);

                if (data == 'YA') { status_dp = '<span class="label label-success">' + data + '</span>'; }
                else { status_dp = '<span class="label label-primary">' + data + '</span>'; }

                return status_dp;
            }
        },
        {
            className: 'text-right',
            data: 'total',
        }
    ]
});

// tabel detail logistik skk
var detail_logistikTable = $("#detail_logistik").DataTable({
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
    "pageLength": 10
});

// tabel pengajuan skk
var pengajuan_skkTable = $("#pengajuan_skkTable").DataTable({
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
        url: BASE_URL + "proyek/get-list-pengajuan-sub-kas-kecil/" + $('#id').val().trim(),
        type: 'POST',
        data: {}
    },
    "columnDefs": [
        {
            "targets": [0, 8],
            "orderable": false,
        }
    ],
    createdRow: function (row, data, dataIndex) {
        if(data[0]) $('td:eq(0)', row).addClass('text-right');
        if(data[4]) $('td:eq(4)', row).addClass('text-right');
        if(data[5]) $('td:eq(5)', row).addClass('text-right');
        if(data[6]) $('td:eq(6)', row).addClass('text-right');
    }
});

// tabel operasional proyek
var operasional_proyekTable = $("#operasional_proyekTable").DataTable({
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
        url: BASE_URL + "proyek/get-list-operasional-proyek/" + $('#id').val().trim(),
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
        if(data[0]) $('td:eq(0)', row).addClass('text-right');
        if(data[6]) $('td:eq(6)', row).addClass('text-right');
    }
});
// end init datatable

// init export start end date
// Export Pengajuan
const exportPengajuan = new FormExportStartEndDate({
    method: 'proyek-detail-pengajuan-skk',
    id: $('#id').val().trim(),
    onInitSubmit: () => {        
        $('.box-pengajuan_skk').append('<div class="overlay"><i class="fa fa-refresh fa-spin"></i></div>');
    },
    onSubmitSuccess: () => {
        $('#modal-export-start-end-date').modal('hide');
    },
    onSubmitFinished: () => {
        $('.box-pengajuan_skk .overlay').remove();
    }
});

// Export Operasional
const exportOperasional = new FormExportStartEndDate({
    method: 'proyek-detail-operasional-proyek',
    id: $('#id').val().trim(),
    onInitSubmit: () => {        
        $('.box-operasional_proyek').append('<div class="overlay"><i class="fa fa-refresh fa-spin"></i></div>');
    },
    onSubmitSuccess: () => {
        $('#modal-export-start-end-date').modal('hide');
    },
    onSubmitFinished: () => {
        $('.box-operasional_proyek .overlay').remove();
    }
});
// init export start end date

$(document).ready(function () {
    // event on click refresh table
    $('#refreshTable_pembayaran').on('click', function () {
        console.log('Button Refresh Table Detail Proyek pembayaran clicked...');
        refreshTable(detail_pembayaranTable, $(this));
    });

    $('#refreshTable_pengajuan').on('click', function () {
        console.log('Button Refresh Table Detail Pengajuan SKK Proyek clicked...');
        refreshTable(pengajuan_skkTable, $(this));
    });

    $('#refreshTable_operasional').on('click', function () {
        console.log('Button Refresh Table Detail Operasional Proyek clicked...');
        refreshTable(operasional_proyekTable, $(this));
    });
    // end event on click refresh table

    // event on click export
    $('#exportExcel_pembayaran').on('click', async function () {
        $('.box-detail_pembayaran').append('<div class="overlay"><i class="fa fa-refresh fa-spin"></i></div>');

        try {
            await Export.excel({
                method: "proyek-detail-pembayaran",
                id: $('#id').val().trim(),
            });
        } catch (error) {
            if (error.code == "InfoException") {
                swal("Pesan", error.message, "info");
            } else {
                console.log("Log Export Excel Error: " + error.message);
                swal("Pesan Gagal", "Terjadi Kesalahan Teknis, Silahkan Coba Kembali", "error");
            }
        }

        $('.box-detail_pembayaran .overlay').remove();
    });

    $('#exportExcel_pengajuan').on('click', function () {
        exportPengajuan.show({
            title: 'Export Detail Pengajuan Sub Kas Kecil',
            type: 'proyek-detail-pengajuan-skk',
        });
    });

    $('#exportExcel_operasional').on('click', function () {
        exportOperasional.show({
            title: 'Export Detail Operasional Proyek',
            type: 'proyek-detail-operasional-proyek',
        });
    });
    // end event on click export
});

/**
 * 
 */
function getView_pengajuanSKK(id) {
    console.log('%cButton View Pengajuan SKK clicked...', 'font-style: italic');

    window.location.href = BASE_URL + 'pengajuan-sub-kas-kecil/detail/' + id;
}

/**
 * 
 */
function getView_operasionalProyek(id) {
    console.log('%cButton View Operasional Proyek clicked...', 'font-style: italic');

    window.location.href = BASE_URL + 'operasional-proyek/detail/' + id;
}