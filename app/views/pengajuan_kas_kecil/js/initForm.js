$(document).ready(function () {
	init();
	// button tambah
	$('#tambah').on('click', function () {
		resetForm();
		generateID();

		$('#id_pengajuan').prop('disabled', true);
		$('#submit_pengajuan_kas_kecil').prop('value', 'action-add');
		$('#submit_pengajuan_kas_kecil').prop('disabled', false);
		$('#submit_pengajuan_kas_kecil').html('Simpan Data');

		$('#modalPengajuan_kasKecil').modal();
	});

	// on change field
	$('.field').on('change', function () {
		if (this.value !== "") {
			$('.field-' + this.id).removeClass('has-error').addClass('has-success');
			$(".pesan-" + this.id).text('');
		}
		else {
			$('.field-' + this.id).removeClass('has-error').removeClass('has-success');
			$(".pesan-" + this.id).text('');
		}
	});

	// submit pengajuan kas kecil
	$('#form_pengajuan_kas_kecil').submit(function (e) {
		e.preventDefault();
		submit();

		return false;
	});

});

/**
 * Function init
 * Proses inisialisasi saat onload page
 */
function init() {
	// Initialize Select2 Elements
	$('#status_pengajuan').select2({
		placeholder: "Pilih Status Pembayaran",
		allowClear: true
	});

	$('#id_bank_pengajuan').select2({
		placeholder: "Pilih Bank",
		allowClear: true
	});

	// input mask
	$('.input-mask-uang').inputmask({
		alias: 'currency',
		prefix: '',
		radixPoint: ',',
		digits: 0,
		groupSeparator: '.',
		clearMaskOnLostFocus: true,
		digitsOptional: false,
	});

	// Date picker
	$('.datepicker').datepicker({
		autoclose: true,
		format: "yyyy-mm-dd",
		todayHighlight: true,
		orientation: "bottom auto",
		todayBtn: true,
	});

	setStatus();
	setNamaBank();

	$('#id_pengajuan').prop('disabled', true);
	$('#id_bank_pengajuan').prop('disabled', true);
	$('#total_disetujui_pengajuan').prop('disabled', true);
	$('#submit_pengajuan_kas_kecil').prop('disabled', true);
	$('#ket').prop('disabled', true);

	// DISABLED 
	// $('#')/
}



/**
 * Fungsi getDataForm()
 * untuk mendapatkan semua value di field
 * return berupa object data
 */
function getDataForm() {
	var data = new FormData();

	if ($('#submit_pengajuan_kas_kecil').val() == "action-add") {

		var total = ($('#total_pengajuan').inputmask) ?
			(parseFloat($('#total_pengajuan').inputmask('unmaskedvalue')) ?
				parseFloat($('#total_pengajuan').inputmask('unmaskedvalue')) :
				$('#total_pengajuan').inputmask('unmaskedvalue')
			) : $('#total_pengajuan').val().trim();

		var id = $('#id_pengajuan').val().trim();
		var tgl = $('#tgl_pengajuan').val().trim();
		var nama = $('#nama_pengajuan').val().trim();

		console.log(nama)

		data.append('id', id); // id
		data.append('tgl', tgl); // tgl
		data.append('nama', nama); // nama pengajuan
		data.append('total', total); // total
		data.append('action', $('#submit_pengajuan_kas_kecil').val().trim()); // action

	} else if ($('#submit_pengajuan_kas_kecil').val() == "action-edit") {

		var id_bank = ($('#id_bank_pengajuan').val() != "" && $('#id_bank_pengajuan').val() != null) ? $('#id_bank_pengajuan').val().trim() : "";
		var status = ($('#status_pengajuan').val() != "" && $('#status_pengajuan').val() != null) ? $('#status_pengajuan').val().trim() : "";
		var ket = ((status == "1") || (status == "3")) ? $('#ket').val().trim() : "-";

		var total_disetujui = ($('#total_disetujui_pengajuan').inputmask) ?
			(parseFloat($('#total_disetujui_pengajuan').inputmask('unmaskedvalue')) ?
				parseFloat($('#total_disetujui_pengajuan').inputmask('unmaskedvalue')) :
				$('#total_disetujui_pengajuan').inputmask('unmaskedvalue')
			) : $('#total_disetujui_pengajuan').val().trim();

		var total = ($('#total_pengajuan').inputmask) ?
			(parseFloat($('#total_pengajuan').inputmask('unmaskedvalue')) ?
				parseFloat($('#total_pengajuan').inputmask('unmaskedvalue')) :
				$('#total_pengajuan').inputmask('unmaskedvalue')
			) : $('#total_pengajuan').val().trim();

		data.append('id', $('#id_pengajuan').val().trim()); // id
		data.append('id_kas_kecil', $('#id_kas_kecil').val().trim()); // id_kas_kecil
		data.append('tgl', $('#tgl_pengajuan').val().trim()); // tanggal
		data.append('nama', $('#nama_pengajuan').val().trim()); // nama pengajuan
		data.append('id_bank', id_bank); // id_bank
		data.append('total', total); // total
		data.append('total_disetujui', total_disetujui); // total_disetujui
		data.append('ket', ket);
		data.append('status', status); //status
		data.append('action', $('#submit_pengajuan_kas_kecil').val().trim()); // action

	}


	return data;
}

/**
 * 
 */
function submit() {
	var data = getDataForm();

	$.ajax({
		url: BASE_URL + 'pengajuan-kas-kecil/' + $('#submit_pengajuan_kas_kecil').val().trim() + '/',
		type: 'POST',
		dataType: 'json',
		data: data,
		contentType: false,
		cache: false,
		processData: false,
		beforeSend: function () {
			$('#submit_pengajuan_kas_kecil').prop('disabled', true);
			$('#submit_pengajuan_kas_kecil').prepend('<i class="fa fa-spin fa-refresh"></i> ');
		},
		success: function (output) {
			console.log(output);
			if (!output.status) {
				$('#submit_pengajuan_kas_kecil').prop('disabled', false);
				$('#submit_pengajuan_kas_kecil').html($('#submit_pengajuan_kas_kecil').text());
				setError(output.error);
				toastr.warning(output.notif.message, output.notif.title);
			}
			else {
				toastr.success(output.notif.message, output.notif.title);
				resetForm();
				$("#modalPengajuan_kasKecil").modal('hide');
				$("#pengajuanKasKecilTable").DataTable().ajax.reload();
			}
		},
		error: function (jqXHR, textStatus, errorThrown) { // error handling
			console.log(jqXHR, textStatus, errorThrown);
			swal("Pesan Gagal", "Terjadi Kesalahan Teknis, Silahkan Coba Kembali", "error");
		}
	})
}

/**
 * 
 * @param {*} id  
 */
function getEdit(id) {
	// if(token != ""){
	resetForm();
	// $('.field-saldo').css('display', 'none');
	$('#submit_pengajuan_kas_kecil').prop('value', 'action-edit');
	$('#submit_pengajuan_kas_kecil').prop('disabled', false);
	$('#submit_pengajuan_kas_kecil').html('Edit Data');

	$.ajax({
		url: BASE_URL + 'pengajuan-kas-kecil/edit/' + id.toLowerCase(),
		type: 'post',
		dataType: 'json',
		data: {},
		beforeSend: function () {

		},
		success: function (output) {
			console.log('%cgetEdit Response:', '', output);

			$('#status_pengajuan').on('change', function () {
				// DISETUJUI
				if ($('#status_pengajuan').val() == "2") {
					$('#id_bank_pengajuan').prop('disabled', false);
					$('#total_disetujui_pengajuan').prop('disabled', false);
					$('#ket').prop('disabled', false);

					var total_disetujui = output.total - output.saldo;
					$('#total_disetujui_pengajuan').val(total_disetujui);

				}
				// DIPERBAIKI
				else if ($('#status_pengajuan').val() == "1") {

					// kunci kolom ket jika form dibuka oleh kas kecil
					if ($('#level').val() == "KAS BESAR") {
						$('#id_bank_pengajuan').prop('disabled', true);
						$('#total_disetujui_pengajuan').prop('disabled', true);
						$('#id_bank_pengajuan').val(null).trigger('change');
						$('#total_disetujui_pengajuan').val(null).trigger('change');

						$('#ket').prop('disabled', false);
					} else {
						$('#ket').prop('disabled', true);
					}
				}
				else {
					$('#id_bank_pengajuan').prop('disabled', true);
					$('#id_bank_pengajuan').val(null).trigger('change');
					$('#total_disetujui_pengajuan').prop('disabled', true);
					$('#total_disetujui_pengajuan').val(null).trigger('change');
					$('#ket').prop('disabled', true);
					$('#ket').val(null).trigger('change');
				}
			});

			if (output) {
				$('#modalPengajuan_kasKecil').modal();
				// $('#token_form').val(token);
				setValue(output);
			}
		},
		error: function (jqXHR, textStatus, errorThrown) { // error handling
			console.log(jqXHR, textStatus, errorThrown);
		}
	})
	// }
	// else swal("Pesan Gagal", "Terjadi Kesalahan Teknis, Silahkan Coba Kembali", "error");
}

/**
 * 
 * @param {*} error 
 */
function setError(error) {
	$.each(error, function (index, item) {
		console.log(index);

		if (item != "") {
			$('.field-' + index).removeClass('has-success').addClass('has-error');
			$('.pesan-' + index).text(item);
		}
		else {
			$('.field-' + index).removeClass('has-error').addClass('has-success');
			$('.pesan-' + index).text('');
		}
	});
}

/**
 * 
 * @param {*} value 
 */
function setValue(value) {
	$.each(value, function (index, item) {
		item = (parseFloat(item)) ? (parseFloat(item)) : item;
		$('#' + index).val(item);
		$('#id_pengajuan').val(value.id);
		$('#id_kas_kecil').val(value.id_kas_kecil);
		$('#tgl_pengajuan').val(value.tgl);
		$('#nama_pengajuan').val(value.nama);
		$('#total_pengajuan').val(value.total);
		$('#saldo_pengajuan').val(value.saldo);
		$('#status_pengajuan').val(value.status).trigger('change');
		$('#ket').val(value.ket);

	});
}

/**
 * 
 */
function generateID() {
	$.ajax({
		url: BASE_URL + 'pengajuan-kas-kecil/get-last-id/',
		type: 'post',
		dataType: 'json',
		data: {},
		beforeSend: function () { },
		success: function (output) {
			$('#id_pengajuan').val(output);
		},
		error: function (jqXHR, textStatus, errorThrown) { // error handling
			console.log(jqXHR, textStatus, errorThrown);
			swal("Pesan Gagal", "Terjadi Kesalahan Teknis, Silahkan Coba Kembali", "error");
		}
	})
}

/**
 * 
 */
function setNamaBank() {
	$.ajax({
		url: BASE_URL + 'operasional-proyek/get-nama-bank',
		type: 'post',
		dataType: 'json',
		beforeSend: function () { },
		success: function (response) {
			console.log('%cResponse setNamaBank Operasional Proyek: ', 'font-style: italic', response);
			$.each(response, function (index, item) {
				var newOption = new Option(item.text, item.id);
				$('#id_bank_pengajuan').append(newOption).trigger('change');
			});
			$('#id_bank_pengajuan').val(null).trigger('change');

		},
		error: function (jqXHR, textStatus, errorThrown) {
			console.log('%cResponse Error setBank Proyek: ', 'color: red; font-style: italic', jqXHR, textStatus, errorThrown);
			swal("Pesan Gagal", "Terjadi Kesalahan Teknis, Silahkan Coba Kembali", "error");
		}
	})
}

/**
 * 
 */
function setStatus() {
	var status = [
		{ value: "0", text: "PENDING" },
		{ value: "1", text: "PERBAIKI" },
		{ value: "2", text: "DISETUJUI" },
		{ value: "3", text: "DITOLAK" },
	];

	$.each(status, function (index, item) {
		var option = new Option(item.text, item.value);
		$("#status_pengajuan").append(option);
	});
	$("#status_pengajuan").val("0").trigger('change');

}

/**
*
*/
function resetForm() {
	// trigger reset form
	$('#form_pengajuan_kas_kecil').trigger('reset');

	// hapus semua pesan
	$('.pesan').text('');

	// hapus semua feedback
	$('.form-group').removeClass('has-success').removeClass('has-error');
}