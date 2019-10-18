var ket_pengajuan_skk = "";
var table_detail = $("#table_detail").DataTable({
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
	"lengthMenu": [ 5, 10, 25, 50 ],
	"pageLength": 5,
	order: [],
	processing: true,
	serverSide: true,
	ajax: {
		url: `${BASE_URL}pengajuan-sub-kas-kecil/get-list-detail/${$('#id').val().trim()}`,
		type: 'POST',
		data: {}
	},
	"columnDefs": [
		{
			"targets":[0],
			"orderable":false,
		}
	],
	createdRow: function(row, data, dataIndex){
		if(data[0]) $('td:eq(0)', row).addClass('text-right');
		if(data[4]) $('td:eq(4)', row).addClass('text-right');
		if(data[5]) $('td:eq(5)', row).addClass('text-right');
		if(data[6]) $('td:eq(6)', row).addClass('text-right');
	}
});

$(document).ready(function() {

    init();

	// event on submit form pengajuan skk
	$('#form_pengajuan_skc').submit(function(e){
		e.preventDefault();
		submit();

		return false;
	});

	// event on change field
	$('.field').on('change', function(){
		onChangeField(this);
	});

	$('#status_order').on('change', function(){
		$('#submit_pengajuan_skc').slideDown();
		if(this.value === "3") { 
			$('.data-keterangan').slideUp();
			$('.data-pengajuan').slideDown(); 
		} else { 
			$('.data-pengajuan').slideUp(); 
			$('.data-keterangan').slideDown();
			$('#keterangan').attr("readonly", false);

			if (this.value === "1") {
				$('#keterangan').attr("readonly", true);
				$('#keterangan').val(ket_pengajuan_skk);
				$('#submit_pengajuan_skc').slideUp();
			}
		}
	});

	$('#refreshTable').on('click', function () {
        console.log('Button Refresh Table Detail Pengajuan Sub Kas Kecil clicked...');
        refreshTable(table_detail, $(this));
    });
});

/**
 * 
 */
function init() {
	$('#status_order').select2({
    	placeholder: "Pilih Status Pengajuan",
		allowClear: true
	});

	// input mask
	$('.input-mask-uang').inputmask({ 
		alias : 'currency',
		prefix: '', 
		radixPoint: ',',
		digits: 0,
		groupSeparator: '.', 
		clearMaskOnLostFocus: true, 
		digitsOptional: false,
	});

	$('#submit_pengajuan_skc').prop('disabled', true);

	setStatus();
}

/**
 * 
 */
function getDataForm(){
	var data = new FormData();

	var dana_disetujui = ($('#dana_disetujui').inputmask) ? 
		( parseFloat($('#dana_disetujui').inputmask('unmaskedvalue')) ?
			parseFloat($('#dana_disetujui').inputmask('unmaskedvalue')) : 
			$('#dana_disetujui').inputmask('unmaskedvalue')
		) : $('#dana_disetujui').val().trim();

	var status = ($('#status_order').val() != "" && $('#status_order').val() != null) ? $('#status_order').val().trim() : "";

	data.append('id', $('#id').val().trim());
	data.append('id_skk', $('#id_sub_kas_kecil').val().trim()); // id sub kas kecil
	data.append('status', status); // status
	data.append('action', $('#submit_pengajuan_skc').val().trim()); // action
	data.append('dana_disetujui', dana_disetujui); // dana disetujui
	data.append('keterangan', $('#keterangan').val().trim() || "-"); // keterangan
	
	return data;
}

/**
 * 
 */
function submit(){
	var data = getDataForm();

	$.ajax({
		url: BASE_URL+'pengajuan-sub-kas-kecil/'+$('#submit_pengajuan_skc').val().trim()+'/',
		type: 'POST',
		dataType: 'json',
		data: data,
		contentType: false,
		cache: false,
		processData: false,
		beforeSend: function(){
			$('#submit_pengajuan_skc').prop('disabled', true);
			$('#submit_pengajuan_skc').prepend('<i class="fa fa-spin fa-refresh"></i> ');
		},
		success: function(response){
			console.log('%cResponse submit Pengajuan Sub Kas Kecil:', 'color: green; font-weight: bold', response);
			if(!response.success){ setError(response.error); }
			else{
				var status = ($('#status_order').val() != "" && $('#status_order').val() != null) ? $('#status_order').val().trim() : "";
				var dataNotif = {
					title: '',
					body: '',
					id_pengajuan: $('#id').val().toUpperCase(),
					status: status,
				};

				switch(status){
					case '3':
						dataNotif.title = 'Pengajuan Disetujui';
						dataNotif.body = 'Pengajuan dengan ID: '+dataNotif.id_pengajuan+' telah disetujui.';
						break;

					case '2':
						dataNotif.title = 'Pengajuan Diperbaiki';
						dataNotif.body = 'Pengajuan dengan ID: '+dataNotif.id_pengajuan+' harap segera diperbaiki.';
						break;

					case '5':
						dataNotif.title = 'Pengajuan Ditolak !!';
						dataNotif.body = 'Pengajuan dengan ID: '+dataNotif.id_pengajuan+' ditolak, harap membuat pengajuan yang baru';
						break;
				}

				if(status != '1') sendNotif(dataNotif);

				$("#pengajuan_sub_kas_kecilTable").DataTable().ajax.reload();

				$("#modalPengajuanSKC").modal('hide');
				resetForm();
			}
			setNotif(response.notif);
			$('#submit_pengajuan_skc').prop('disabled', false);
			$('#submit_pengajuan_skc').html($('#submit_pengajuan_skc').text());
		},
		error: function (jqXHR, textStatus, errorThrown) {
			console.log('%cError Response submit Pengajuan Sub Kas Kecil:', 'color: red; font-weight: bold', jqXHR, textStatus, errorThrown);
			$("#modalPengajuanSKC").modal('hide');
			$('#submit_pengajuan_skc').prop('disabled', false);
			$('#submit_pengajuan_skc').html($('#submit_pengajuan_skc').text());
            swal("Pesan Gagal", "Terjadi Kesalahan Teknis, Silahkan Coba Kembali", "error");
        }
	})
}

/**
 * 
 * @param {object} data 
 */
function sendNotif(data){
	console.log(data);
	$.ajax({
        headers: {

            // setting token firebase
            "Authorization": "key=AAAAlOkhnSY:APA91bEqHj4gSO-lEqIeQz4g0ABtrhnSa6w8zDWMlXno50jkyJt5VwrfiC91uXv55yM560VyV4QaL9JG7XBktaX1IeMPJeNB7wDaFtI_Z3d2Qk6tnUeV0lYVxtvbF94fw_7rQqk8foLO",
            "Content-Type": "application/json",
        },
        url: 'https://fcm.googleapis.com/fcm/send',
        type: 'POST',
        dataType: 'json',
        data: JSON.stringify({

            // kirim notifikasi ke semua user yang men-subscribe tipe news
            "to" : "/topics/"+$('#id_sub_kas_kecil').val(),

            // setting pesan yang ingin ditampilkan
            "notification" : {

                // mengirimkan judul notifikasi
                "title": data.title,

                // mengirimkan isi dari notifikasi
                "body" : data.body,

                // menjalankan suara notifikasi pada saat notif masuk
                "sound": "default"
            },
            // pengiriman data custom
            data : {
                "id_pengajuan": data.id_pengajuan,
                "status": data.status
            },

            // setting prioritas pengiriman notifikasi
            "priority": "high"
        }),
    })
    .done(function(data) {
        console.log('Pesan berhasil dikirim dengan kode: '+data.message_id);
    })
    .fail(function(error) {
        console.log('Terjadi kesalahan:\n'+error.responseText);
    });
}

/**
 * 
 * @param {string} id
 */
function getEdit(id){
	resetForm();
	$('#submit_pengajuan_skc').prop('disabled', false);

	$.ajax({
		url: BASE_URL+'pengajuan-sub-kas-kecil/edit/'+id.toLowerCase(),
		type: 'post',
		dataType: 'json',
		data: {},
		beforeSend: function(){},
		success: function(response){
			console.log('%cResponse Get Edit Pengajuan Sub Kas Kecil:', 'color: green; font-weight: bold', response);
			setValue(response.data);
			$('#modalPengajuanSKC').modal();
		},
		error: function (jqXHR, textStatus, errorThrown){ // error handling
			console.log('%cError Response Get Edit Pengajuan Sub Kas Kecil:', 'color: red; font-weight: bold', jqXHR, textStatus, errorThrown);
			swal("Pesan Gagal", "Terjadi Kesalahan Teknis, Silahkan Coba Kembali", "error");
			$('#modalPengajuanSKC').modal('hide');
		}
	});
}

/**
 * 
 */
function setStatus(){
	var status = [
		{value: "1", text: "PENDING"},
		{value: "2", text: "PERBAIKI"},
		{value: "3", text: "DISETUJUI"},
		{value: "5", text: "DITOLAK"},
	];

	$.each(status, function(index, item){
		var option = new Option(item.text, item.value);
		$("#status_order").append(option).trigger('change');
	});
	$('#status_order').val(null).trigger('change');
}

/**
 * 
 * @param {*} error 
 */
function setError(error){
	$.each(error, function(index, item){
		if(item != ""){
			$('.field-'+index).removeClass('has-success').addClass('has-error');
			$('.pesan-'+index).text(item);
		}
		else{
			$('.field-'+index).removeClass('has-error').addClass('has-success');
			$('.pesan-'+index).text('');	
		}
    });
}

/**
 * 
 */
function setValue(value) {	
	ket_pengajuan_skk = value.ket;

	$('#id').val(value.id);
	$('#id_sub_kas_kecil').val(value.id_sub_kas_kecil);
	$('#sub_kas_kecil').val(value.id_sub_kas_kecil + " - " + value.nama_skk);
	$('#tgl').val(value.tgl);
	$('#nama_pengajuan').val(value.nama_pengajuan);
	$('#total').val(value.total);
	$('#saldo_sub_kas_kecil').val(value.saldo_sub_kas_kecil);
	$('#saldo_kas_kecil').val(value.saldo_kas_kecil);
	$('#dana_disetujui').val(value.dana_disetujui);
	$('#status_order').val(value.status_order).trigger('change');
}

/**
 * 
 */
function resetForm(){
	// trigger reset form
	$('#form_pengajuan_skc').trigger('reset');

	// hapus semua pesan
	$('.pesan').text('');

	// hapus semua feedback
	$('.form-group').removeClass('has-success').removeClass('has-error');

	$('#total').text('');
    $('#saldo').text('');

    $('#status_order').val(null).trigger('change');
    $('.data-pengajuan').slideUp();
}
