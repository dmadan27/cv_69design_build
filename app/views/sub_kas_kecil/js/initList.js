var skcTable = $("#skcTable").DataTable({
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
        url: BASE_URL + "sub-kas-kecil/get-list/",
        type: 'POST',
        data: {
        }
    },
    "columnDefs": [
        {
            "targets": [0, 7], // disable order di kolom 1 dan 3
            "orderable": false,
        }
    ],
    createdRow: function (row, data, dataIndex) {
        if (data[0]) { $('td:eq(0)', row).addClass('text-right'); }
        if (data[5]) { $('td:eq(5)', row).addClass('text-right'); }
        if (data[6]) { $('td:eq(6)', row).addClass('text-center'); }
        if ($(data[6]).text().toLowerCase() == "nonaktif") $(row).addClass('danger');
    }
});

$(document).ready(function () {

    // btn Export
    $('#exportExcel').on('click', function () {
        // if(this.value.trim() != "") 
        window.location.href = BASE_URL + 'export/sub-kas-kecil';
    });

    // event on click refresh table
    $('#refreshTable').on('click', function () {
        console.log('Button Refresh Table Sub Kas Kecil clicked...');
        refreshTable(skcTable, $(this));
    });

    // auto refresh every 1 minutes
    setInterval(function () {
        console.log('%cAutomatically refresh table..', 'color: blue; font-style: italic');
        skcTable.ajax.reload(null, false);
    }, 60000);

});

/**
*
*/
function getView(id) {
    window.location.href = BASE_URL + 'sub-kas-kecil/detail/' + id.toLowerCase();
}

/**
*
*/
function getDelete(id) {

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
            url: BASE_URL + 'sub-kas-kecil/delete/' + id.toLowerCase(),
            type: 'post',
            dataType: 'json',
            data: {},
            beforeSend: function () {
            },
            success: function (response) {
                console.log(response);
                if (response.success) {
                    swal("Pesan Berhasil", "Data Berhasil Dihapus", "success");
                    $("#skcTable").DataTable().ajax.reload();
                }
            },
            error: function (jqXHR, textStatus, errorThrown) { // error handling
                console.log(jqXHR, textStatus, errorThrown);
                swal("Pesan Gagal", "Terjadi Kesalahan Teknis, Silahkan Coba Kembali", "error");
            }
        })
    });

}