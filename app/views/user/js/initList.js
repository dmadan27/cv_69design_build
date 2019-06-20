const UserTable = $("#UserTable").DataTable({
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
        url: BASE_URL + "user/get-list/",
        type: 'POST',
        data: {}
    },
    "columnDefs": [
        {
            "targets": [0, 5], // disable order di kolom 1 dan 3
            "orderable": false,
        }
    ],
    createdRow: function (row, data, dataIndex) {
        if ($(data[4]).text().toLowerCase() == "nonaktif") $(row).addClass('danger');
        for (var i = 0; i < 5; i++) {
            if (i == 0) $('td:eq(' + i + ')', row).addClass('text-right');
        }
    }
});

$(document).ready(function () {
    $('#exportExcel').on('click', async () => {
        console.log('Export User Clikced...');
        $('.box').append('<div class="overlay"><i class="fa fa-refresh fa-spin"></i></div>');
        try {
            await Export.excel({
                method: 'user',
            });
        } catch (error) {
            if (error.code == "InfoException") {
                swal("Pesan", error.message, "info");
            } else {
                console.log("Log Export User: " + error.message);
                swal("Pesan Gagal", "Terjadi Kesalahan Teknis, Silahkan Coba Kembali", "error");
            }
        }
        $('.box .overlay').remove();
    });
});

/**
*
*/
async function getView(username, level) {
    console.log(username + " " + level);
    const formData = new FormData();
    formData.append('username', username);
    formData.append('level', level);

    $('.box').append('<div class="overlay"><i class="fa fa-refresh fa-spin"></i></div>');

    await fetch(BASE_URL + 'user/get-link-detail/', {
        method: 'POST',
        body: formData,
    }).then(async (res) => {
        try {
            const data = await res.clone().json();
            if (data.success) {
                window.location.href = data.link;
            } else {
                swal("Pesan", data.message, "info");
            }
        } catch (error) {
            console.log(error);

            const log = await res.clone().text();
            console.log(log);
            swal("Pesan Gagal", "Terjadi Kesalahan Teknis, Silahkan Coba Kembali", "error");
        }
    }).catch((error) => {
        console.log(error);
        swal("Pesan Gagal", "Terjadi Kesalahan Teknis, Silahkan Coba Kembali", "error");
    });

    $('.box .overlay').remove();
}

/**
*
*/
function getDelete(id, token) {

}

function getEdit(username, level) {    
    console.log(username + " " + level);
    FormUser.show({
        username: username,
        level: level,
    });
}