$(document).ready(function () {
	//Date picker
	$('#tgl-awal').datepicker({
		autoclose: true,
		format: "yyyy-mm-dd",
		todayHighlight: true,
		orientation: "bottom auto",
		todayBtn: true,
	});

	$('#tgl-akhir').datepicker({
		autoclose: true,
		format: "yyyy-mm-dd",
		todayHighlight: true,
		orientation: "bottom auto",
		todayBtn: true,
	});

	$('#modal-export-start-end-date').on('hidden.bs.modal', function () {
		resetForm();
	});

	$('#btn-export-start-end-date').on('click', function (e) {
		e.preventDefault();

		const tglAwal = $('#tgl-awal').val().trim();
		const tglAkhir = $('#tgl-akhir').val().trim();

		// Validasi tanggal awal dan tanggal akhir
		if (validateTanggal(tglAwal, tglAkhir)) {
			try {
				const data = JSON.parse($('#export-data').val().trim());
				getExport(tglAwal, tglAkhir, data);
			} catch (error) {
				console.log("Error JSON Parse: " + error.message);
				swal("Pesan Gagal", "Terjadi Kesalahan Teknis, Silahkan Coba Kembali", "error");
			}
		}
	});


});

/**
 * Validasi tanggal awal dan akhir.
 */
function validateTanggal(tglAwal, tglAkhir) {
	let valid = false;

	if (tglAwal == '' && tglAkhir == '') {
		swal({
			type: 'error',
			title: 'Tanggal Tidak Boleh Kosong!',
		})
	} else if (tglAwal == '' && tglAkhir != '') {
		swal({
			type: 'error',
			title: 'Tanggal Awal Harus Diisi!',
			text: 'Isi atau kosongkan keduanya !'
		})
	} else if (tglAwal != '' && tglAkhir == '') {
		swal({
			type: 'error',
			title: 'Tanggal Akhir Harus Diisi!',
			text: 'Isi atau kosongkan keduanya !'
		})
	} else if (new Date(tglAwal) > new Date(tglAkhir)) {
		swal({
			type: 'error',
			title: 'Kesalahan Input !',
			text: 'Tanggal Awal Melebihi Tanggal Akhir!'
		})
	} else {
		valid = true;
	}

	return valid;
}

/**
 * Mendapatkan file excel yang ingin di ekspor
 */
function getExport(tglAwal, tglAkhir, data) {
	try {
		const id = data.id || "";
		const url = BASE_URL + 'export/' + data.method + "/" + id;

		$.ajax({
			type: "POST",
			url: url,
			data: {
				"tgl_awal": tglAwal,
				"tgl_akhir": tglAkhir,
			},
			dataType: "JSON",
			beforeSend: function () {
				console.log('Loading render file excel..');
				$('.box').append('<div class="overlay"><i class="fa fa-refresh fa-spin"></i></div>');
			},
			success: function (response) {
				console.log('%cResponse getExport: ', 'color: blue; font-weight: bold', response);
				$('.box .overlay').remove();
				$('#modal-export-start-end-date').modal('hide');
				if (response.success) {
					let $a = $("<a>");
					$a.attr("href", response.file);
					$("body").append($a);
					$a.attr("download", response.filename);
					$a[0].click();
					$a.remove();
				} else {
					swal("Pesan", response.message, "info");
				}
			},
			error: function (jqXHR, textStatus, errorThrown) { // error handling
				console.log('%cResponse Error Export ' + data.exportController, 'color: red; font-weight: bold', { jqXHR, textStatus, errorThrown });
				swal("Pesan Gagal", "Terjadi Kesalahan Teknis, Silahkan Coba Kembali", "error");
				$('.box .overlay').remove();
			},
		});
	} catch (error) {
		console.log("Error Export, Object Not Found: " + error.message);
		swal("Pesan Gagal", "Terjadi Kesalahan Teknis, Silahkan Coba Kembali", "error");
	}
}

/**
 * 
 */
function resetForm() {
	$('#form_export').trigger('reset');
	$('.pesan').text('');
	$('.form-group').removeClass('has-success').removeClass('has-error');
}