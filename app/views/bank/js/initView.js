var mutasiBankTable = $("#mutasiBankTable").DataTable({
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
		url: BASE_URL+"bank/get-mutasi/"+$('#id').val().trim(),
		type: 'POST',
		data: {}
	},
	"columnDefs": [
		{
			"targets":[0, 4],
			"orderable":false,
		}
	],
	createdRow: function(row, data, dataIndex){
		for(var i = 0; i < 5; i++){
			if(i != 5) $('td:eq('+i+')', row).addClass('text-right');
		}
	}
});

const exportExcelMutasiBank = new FormExportStartEndDate({
	method: 'bank-detail-mutasi',
	id: document.getElementById('id').value,
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

$(document).ready(function() {

	init();

    // on click button edit
    $('#btn_edit').on('click', function(){
        console.log('Button Edit Bank Clicked...');

        getEdit($('#id').val());
    });

    // on click button delete
    $('#btn_delete').on('click', function(){
        console.log('Button Delete Bank Clicked...');

        getDelete($('#id').val());
    });

     // btn Export
     $('#exportExcel').on('click', function(){
		console.log('Button exportExcel Clicked');

		exportExcelMutasiBank.show({
            title: 'Export Data Mutasi Bank',
        });
    });

    // event on submit form bank
	$('#form_bank').submit(function(e){
		console.log('Submit Bank Clicked...');
		
		e.preventDefault();
		submit();

		return false;
	});

	// event on click refresh table
    $('#refreshTable').on('click', function() {
        console.log('Button Refresh Table Mutasi Bank clicked...');
        refreshTable(mutasiBankTable, $(this));
    });

});

/**
 * 
 */
function init() {
	setStatus();
}

/**
 * 
 * @param {string} id 
 */
function getEdit(id){
    console.log('edit clicked');
    resetForm();
	$('.field-saldo').css('display', 'none');
	$('#submit_bank').prop('value', 'action-edit');
	$('#submit_bank').prop('disabled', false);
	$('#submit_bank').html('Edit Data');

	$.ajax({
		url: BASE_URL+'bank/edit/'+id.toLowerCase(),
		type: 'post',
		dataType: 'json',
		data: {},
		beforeSend: function(){
		},
		success: function(response){
			console.log('%cResponse Get Edit Bank: ', 'color: green; font-weight: bold', response);
			setValue(response);
			$('#modalBank').modal();
		},
		error: function (jqXHR, textStatus, errorThrown){ // error handling
            console.log('Response Error getEdit Bank: ', jqXHR, textStatus, errorThrown);
			swal("Pesan Gagal", "Terjadi Kesalahan Teknis, Silahkan Coba Kembali", "error");
			$('#modalBank').modal('hide');
        }
	})
}

/**
 * Function getDataForm
 * Proses mendapatkan semua value di field
 * @return {FormData} data
 */
function getDataForm(){
    var data = new FormData();
    
    var saldo = ($('#saldo').inputmask) ? 
		( parseFloat($('#saldo').inputmask('unmaskedvalue')) ?
			parseFloat($('#saldo').inputmask('unmaskedvalue')) : 
			$('#saldo').inputmask('unmaskedvalue')
		) : $('#saldo').val().trim();
		
	if($('#submit_bank').val().trim().toLowerCase() == "action-edit") data.append('id', $('#id').val().trim());

    data.append('nama', $('#nama').val().trim()); // nama bank
    data.append('saldo', saldo); // saldo
	data.append('status', $('#status').val().trim()); // status bank
	data.append('action', $('#submit_bank').val().trim()); // action

	return data;
}

/**
 * Function submit
 * Proses submit data ke server baik saat add / edit
 * @return {object} response
 */
function submit(){
    console.log('submit clicked');
	var data = getDataForm();

	$.ajax({
		url: BASE_URL+'bank/'+$('#submit_bank').val().trim()+'/',
		type: 'POST',
		dataType: 'json',
		data: data,
		contentType: false,
		cache: false,
		processData: false,
		beforeSend: function(){
			$('#submit_bank').prop('disabled', true);
			$('#submit_bank').prepend('<i class="fa fa-spin fa-refresh"></i> ');
		},
		success: function(response){
			console.log('Response submit Bank: ', response);
			if(!response.success) {
				$('#submit_bank').prop('disabled', false);
				$('#submit_bank').html($('#submit_bank').text());
				setError(response.error);
				toastr.warning(response.notif.message, response.notif.title);
			}
			else{
				toastr.success(response.notif.message, response.notif.title);
				resetForm();
				$("#modalBank").modal('hide');
                $("#bankTable").DataTable().ajax.reload();
                setTimeout(function(){ 
                    location.reload()
                }, 1500);
			}
		},
		error: function (jqXHR, textStatus, errorThrown){ // error handling
			$("#modalBank").modal('hide');
            console.log('Response Error submit Bank: ', jqXHR, textStatus, errorThrown);
            swal("Pesan Gagal", "Terjadi Kesalahan Teknis, Silahkan Coba Kembali", "error");
        }
	})
}

/**
 * Function resetForm
 * Proses reset form
 */
function resetForm(){
	// trigger reset form
	$('#form_bank').trigger('reset');

	// hapus semua pesan
	$('.pesan').text('');

	// hapus semua feedback
	$('.form-group').removeClass('has-success').removeClass('has-error');
}

/**
 * Function setValue
 * Proses menampilkan value ke field-field yang dibutuhkan
 * @param {object} value 
 */
function setValue(value){
	$.each(value, function(index, item){
		item = (parseFloat(item)) ? (parseFloat(item)) : item;
		$('#'+index).val(item);
	});
}

/**
 * Function setStatus
 * Proses pengisian value pada select status
 */
function setStatus(){
	var status = [
		{value: "AKTIF", text: "AKTIF"},
		{value: "NONAKTIF", text: "NONAKTIF"},
	];

	$.each(status, function(index, item){
		var option = new Option(item.text, item.value);
		$("#status").append(option);
	});
}

/**
 * Function getDelete
 * @param {string} id 
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
			url: BASE_URL+'bank/delete/'+id,
			type: 'post',
			dataType: 'json',
			data: {},
			beforeSend: function(){
			},
			success: function(response){
				console.log('Response getDelete Bank: ', response);
				if(response.success){
                    setTimeout(function(){ 
                        window.location.href = BASE_URL+'bank/'; 
                   }, 1500);
                }
                swal(response.notif.title, response.notif.message, response.notif.type);
			},
			error: function (jqXHR, textStatus, errorThrown){ // error handling
	            console.log('Response Error getDelete Bank', jqXHR, textStatus, errorThrown);
                swal("Pesan Gagal", "Terjadi Kesalahan Teknis, Silahkan Coba Kembali", "error");
	        }
		})
	});
}

/**
 * 
 */
function back(){
    console.log('Button Back Bank Clicked...');

    window.location.href = BASE_URL+'bank/';
}