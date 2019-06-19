var operasionalProyekTable = $("#operasionalProyekTable").DataTable({
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
        url: BASE_URL+"operasional-proyek/get-list/",
        type: 'POST',
         data: {
        }
    },
    "columnDefs": [
        {
            "targets":[0, 8],
            "orderable":false,
        }
    ],
    createdRow: function(row, data, dataIndex){
        if(data[0]) $('td:eq(0)', row).addClass('text-right');
        if(data[7]) $('td:eq(7)', row).addClass('text-right');
    }
});

// inisialisasi export
const exportOperasioalProyek = new FormExportStartEndDate({
    method: 'operasional-proyek',
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

$(document).ready(function(){
    
      //Date picker
	$('#tgl_awal').datepicker({
		autoclose: true,
		format: "yyyy-mm-dd",
		todayHighlight: true,
		orientation:"bottom auto",
		todayBtn: true,
	  });

	  //Date picker
	$('#tgl_akhir').datepicker({
		autoclose: true,
		format: "yyyy-mm-dd",
		todayHighlight: true,
		orientation:"bottom auto",
		todayBtn: true,
	  });

    // btn tambah
    $('#tambah').on('click', function(){
        window.location.href = BASE_URL+'operasional-proyek/form/'

        // if(this.value.trim() != "") window.location.href = BASE_URL+'operasional-proyek/form/';
        // else swal("Pesan Gagal", "Terjadi Kesalahan Teknis, Silahkan Coba Kembali", "error");
    });

    // btn Export
    $('#exportExcel').on('click', function(){
        // if(this.value.trim() != "") 
        // $('#modalTanggalExport').modal()         
        console.log('Button exportExcel Clicked');
        exportOperasioalProyek.show({
            title: 'Export Data Operasional Proyek',
        });      
    });

    // event on click refresh table
    $('#refreshTable').on('click', function() {
        console.log('Button Refresh Table Operasional Proyek clicked...');
        refreshTable(operasionalProyekTable, $(this));
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
    } else {
    window.location.href = BASE_URL+'operasional-proyek/export?tgl_awal=' + tgl_awal + '&tgl_akhir=' + tgl_akhir;
    }
}

/**
*
*/
function getView(id){
    console.log('%cButton View Opr Proyek clicked...', 'font-style: italic');
    window.location.href = BASE_URL+'operasional-proyek/detail/'+id;   
}

/**
*
*/
function getEdit(id){
    console.log('%cButton Edit Opr Proyek clicked...', 'font-style: italic');
    window.location.href = BASE_URL+'operasional-proyek/form/'+id;
   
}

/**
*
*/
function getDelete(id){
    console.log('%cButton Hapus Operasional Proyek Proyek clicked...', 'font-style: italic');
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
            url: BASE_URL+'operasional-proyek/delete/'+id,
            type: 'post',
            dataType: 'json',
            data: {},
            beforeSend: function(){

            },
            success: function(output){
                if(output.success){ 
                    console.log('%cResponse getDelete Operasional Proyek: ', 'color: green; font-weight: bold', output);
                    swal("Pesan Berhasil", "Data Berhasil Dihapus", "success");
                    $("#operasionalProyekTable").DataTable().ajax.reload(); 
                }
                
                
            },
            error: function (jqXHR, textStatus, errorThrown){ // error handling
                console.log(jqXHR, textStatus, errorThrown);
                swal("Pesan Gagal", "Terjadi Kesalahan Teknis, Silahkan Coba Kembali", "error");
            }
        })
    });
	
}
