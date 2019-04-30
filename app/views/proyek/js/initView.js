// init datatable
    // tabel detail pembayaran
    var detail_pembayaranTable = $("#detail_pembayaran").DataTable({
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
        "order": [],
        "processing": true,
        "serverSide": true,
        "ajax": {
            url: BASE_URL+"proyek/get-list-detail-pembayaran/"+$('#id').val().trim(),
            type: 'POST',
            data: {}
        },
        "columns": [
            {
                className: 'text-right',
                orderable: false,
                data: 'no_urut'
            },
            {data: 'tgl'},
            {data: 'nama'},
            {data: 'nama_bank'},
            {
                data: 'DP',
                render: function(data) {
                    var status_dp = '';
                    console.log(data);

                    if(data == 'YA') { status_dp = '<span class="label label-success">'+data+'</span>'; }
                    else { status_dp = '<span class="label label-primary">'+data+'</span>'; }

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
        "pageLength": 10
    });

    // tabel pengajuan skk
    var pengajuan_skkTable = $("#pengajuan_skkTable").DataTable({
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
            url: BASE_URL+"proyek/get-list-pengajuan-sub-kas-kecil/"+$('#id').val().trim(),
            type: 'POST',
            data: {}
        },
        "columnDefs": [
            {
                "targets":[0, 8],
                "orderable":false,
            }
        ],
        createdRow: function(row, data, dataIndex){
            // if($(data[7]).text().toLowerCase() == "selesai") $(row).addClass('danger');
            for(var i = 0; i < 7; i++){
                if(i == 0 || i == 5 || i == 6) $('td:eq('+i+')', row).addClass('text-right');
            }
        }
    });

    // tabel operasional proyek
    var operasional_proyekTable = $("#operasional_proyekTable").DataTable({
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
            url: BASE_URL+"proyek/get-list-operasional-proyek/"+$('#id').val().trim(),
            type: 'POST',
            data: {}
        },
        "columnDefs": [
            {
                "targets":[0, 8],
                "orderable":false,
            }
        ],
        createdRow: function(row, data, dataIndex){
            // if($(data[7]).text().toLowerCase() == "selesai") $(row).addClass('danger');
            for(var i = 0; i < 7; i++){
                if(i == 0 || i == 7) $('td:eq('+i+')', row).addClass('text-right');
            }
        }
    });
// end init datatable

$(document).ready(function() {
    // event on click refresh table
        $('#refreshTable_pembayaran').on('click', function() {
            console.log('Button Refresh Table Detail Proyek pembayaran clicked...');
            refreshTable(detail_pembayaranTable, $(this));
        });

        $('#refreshTable_pengajuan').on('click', function() {
            console.log('Button Refresh Table Detail Pengajuan SKK Proyek clicked...');
            refreshTable(pengajuan_skkTable, $(this));
        });

        $('#refreshTable_operasional').on('click', function() {
            console.log('Button Refresh Table Detail Operasional Proyek clicked...');
            refreshTable(operasional_proyekTable, $(this));
        });
    // end event on click refresh table

    // event on click export
        $('#exportExcel_pembayaran').on('click', function() {
            $('#exportType').val('detail_pembayaran');
            getExport();
        });

        $('#exportExcel_pengajuan').on('click', function() {
            showModalExport('pengajuan_skk');
        });

        $('#exportExcel_operasional').on('click', function() {
            showModalExport('operasional_proyek');
        });
    // end event on click export

    // on submit export
    $('#form_export').on('submit', function(e) {
        e.preventDefault();
        
        // jika salah satu kosong
        if($('#tgl_awal').val().trim() != "" && $('#tgl_akhir').val().trim() != "") {
            // jika tgl awal / akhir tidak sesuai
            // if() {

            // }
            // else {
            //     getExport();
            // }
            getExport();
        }
        else {
            $('.field-tgl_export').addClass('has-error');
            $('.pesan-tgl_export').html('Tanggal Awal atau Tanggal Akhir tidak boleh kosong');
        }

        return false;
    });
});

/**
 * 
 */
function showModalExport(type) {
    resetForm();
    $('#exportType').val(type);
    $('#modalExport').modal();
}

/**
 * 
 */
function getExport() {
    var notif = {title: 'Pesan Pemberitahuan', message: 'Akses Ditolak', type: 'warning'};
    if(LEVEL === 'KAS BESAR' || LEVEL === 'OWNER') {
        var url = '';
        var data = {};
        var type = $('#exportType').val().trim();

        if(type == 'detail_pembayaran') {
            url = BASE_URL+'export/proyek-detail-pembayaran/'+$('#id').val().trim();
        }
        else if(type == 'pengajuan_skk') {
            url = BASE_URL+'export/proyek-detail-pengajuan-skk/'+$('#id').val().trim();
            data.tgl_awal = $('#tgl_awal').val();
            data.tgl_akhir = $('#tgl_akhir').val();
        }
        else if(type == 'operasional_proyek') {
            url = BASE_URL+'export/proyek-detail-operasional-proyek/'+$('#id').val().trim();
            data.tgl_awal = $('#tgl_awal').val();
            data.tgl_akhir = $('#tgl_akhir').val();
        }
        else {
            setNotif(notif, 'swal');
            return;
        }

        $.ajax({
            url: url,
            type: 'POST',
            dataType: 'JSON',
            data: data,
            beforeSend: function(){
                console.log('Loading render file excel..');
                $('.box box-'+type).append('<div class="overlay"><i class="fa fa-refresh fa-spin"></i></div>');
            },
            success: function(response) {
                console.log('%cResponse getExport Proyek: ', 'color: blue; font-weight: bold', response);
                $('.box box-'+type+' .overlay').remove();
                $('#modalExport').modal('hide');
                if(response.success) {
                    var $a = $("<a>");
                    $a.attr("href",response.file);
                    $("body").append($a);
                    $a.attr("download", response.filename);
                    $a[0].click();
                    $a.remove();   
                }
                else { swal("Pesan", response.message, "info"); }
            },
            error: function (jqXHR, textStatus, errorThrown){ // error handling
                console.log('%cResponse Error getExport Proyek', 'color: red; font-weight: bold', {jqXHR, textStatus, errorThrown});
                swal("Pesan Gagal", "Terjadi Kesalahan Teknis, Silahkan Coba Kembali", "error");
                $('.box box-'+type+' .overlay').remove();
                $('#modalExport').modal('hide');
            }
        })
    }
    else {
        setNotif(notif, 'swal');
    }
}

/**
 * 
 */
function getView_pengajuanSKK(id) {
    console.log('%cButton View Pengajuan SKK clicked...', 'font-style: italic');
    
    window.location.href = BASE_URL+'pengajuan-sub-kas-kecil/detail/'+id;
}

/**
 * 
 */
function getView_operasionalProyek(id) {
    console.log('%cButton View Operasional Proyek clicked...', 'font-style: italic');
    
    window.location.href = BASE_URL+'operasional-proyek/detail/'+id;
}