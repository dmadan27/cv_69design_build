/**
 * Menampilkan form export berdasarkan tanggal awal dan akhir.
 * 
 * @param {string} title Setting header form.
 * @param {string} method Method export yang ingin dipanggil saat form disubmit.
 * @param {string} id Parameter id yang diperlukan oleh method export (opsional).
 * @param {functionCallback} onInitSubmit Aksi yang ingin dilakukan saat form disubmit namun aksi submit belum dilakukan (opsional).
 * @param {functionCallback} onSubmitSuccess Aksi yang ingin dilakukan saat aksi submit berhasil dieksekusi (opsional).
 * @param {functionCallback} onSubmitError Aksi yang ingin dilakukan saat aksi submit gagal (opsional). 
 * @param {functionCallback} onSubmitFinished Aksi yang ingin dilakukan saat aksi submit selesai dieksekusi (opsional). 
 */
function FormExportStartEndDate({method, id = "", onInitSubmit, onSubmitSuccess, onSubmitError, onSubmitFinished }) {
	$('#export-data').val("");

	// Setting datepicker tgl-awal.
	$('#tgl-awal').datepicker({
		autoclose: true,
		format: "yyyy-mm-dd",
		language: "id",
		todayHighlight: true,
		orientation: "bottom auto",
		todayBtn: true,
		endDate: new Date(),
	});

	// Setting datepicker tgl-akhir.
	$('#tgl-akhir').datepicker({
		autoclose: true,
		format: "yyyy-mm-dd",
		language: "id",
		todayHighlight: true,
		orientation: "bottom auto",
		todayBtn: true,
		endDate: new Date(),
	});

	// Setting kondisi saat modal export ditutup.
	$('#modal-export-start-end-date').on('hidden.bs.modal', function () {
		resetForm();
		$('#export-data').val("");
	});

	// Aksi saat form export dieksekusi.
	$('#form-export-start-end-date').submit(async (e) => {
		e.preventDefault();

		if ($('#export-data').val() == method) {
			if (typeof onInitSubmit === "function" && onInitSubmit()) {
				onInitSubmit();
			}

			const tglAwal = $('#tgl-awal').val().trim();
			const tglAkhir = $('#tgl-akhir').val().trim();

			if (validateTanggal(tglAwal, tglAkhir)) {
				const formData = new FormData();
				formData.append('tgl_awal', tglAwal);
				formData.append('tgl_akhir', tglAkhir);

				try {
					await Export.excel({
						method: method,
						id: id,
						body: formData,
					});

					if (typeof onSubmitSuccess === "function" && onSubmitSuccess()) {
						onSubmitSuccess();
					}
				} catch (error) {
					if (error.code == "InfoException") {
						swal("Pesan", error.message, "info");
					} else {
						console.log("Log Export StartEndDate: " + error.message);
						swal("Pesan Gagal", "Terjadi Kesalahan Teknis, Silahkan Coba Kembali", "error");
					}

					if (typeof onSubmitError === "function" && onSubmitError(error)) {
						onSubmitError(error);
					}
				}
			}

			if (typeof onSubmitFinished === "function" && onSubmitFinished()) {
				onSubmitFinished();
			}
		}
	});

	/**
	 * Method untuk menampilan modal FormExportStartEndDate.
	 * 
	 * @param {string} title Judul modal.
	 * @param {string} type Mengidentifikasi data yang ingin diexport 
	 * 					(harus diisi dengan nama method saat inisialisasi 
	 * 					jika FormExportStartEndDate diiplementasikan lebih dari satu kali dalam satu halaman).
	 */
	this.show = ({ title, type = method }) => {
		$('.modal-export-title').html(title);
		$('#export-data').val(type);
		$('#modal-export-start-end-date').modal();
	}
	this.hide = () => $('#modal-export-start-end-date').modal('hide');
}

/**
 * Validasi data tanggal yang dimassukan sudah valid (terisi).
 * 
 * @param {string} tglAwal Tanggal awal data export dengan format (yyyy-mm-dd).
 * @param {string} tglAkhir Tanggal akhir data export dengan format (yyyy-mm-dd).
 * @return {boolean} Status valid tglAwal dan tglAkhir.
 */
function validateTanggal(tglAwal, tglAkhir) {
	cleanError();
	let valid = false;

	if (tglAwal == '' && tglAkhir == '') {
		$('.field-tgl_export').addClass('has-error');
		$('.pesan-tgl_export').text('Tanggal awal dan tanggal akhir harus diisi.');
	} else if (tglAwal == '' && tglAkhir != '') {
		$('.field-tgl_export').addClass('has-error');
		$('.pesan-tgl_export').text('Tanggal awal harus diisi.');
	} else if (tglAwal != '' && tglAkhir == '') {
		$('.field-tgl_export').addClass('has-error');
		$('.pesan-tgl_export').text('Tanggal akhir harus diisi.');
	} else if (new Date(tglAwal) > new Date(tglAkhir)) {
		$('.field-tgl_export').addClass('has-error');
		$('.pesan-tgl_export').text('Tanggal awal tidak boleh melebihi tanggal akhir.');
	} else {
		valid = true;
	}

	return valid;
}

/**
 * Reset kondisi form ke default.
 * 
 */
function resetForm() {
	$('#form-export-start-end-date').trigger('reset');
	cleanError();
}

/**
 * Membersihkan pesan error yang terlihat.
 * 
 */
function cleanError() {
	$('.pesan').text('');
	$('.form-group').removeClass('has-success').removeClass('has-error');
}