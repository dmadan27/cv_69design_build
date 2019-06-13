/**
 * Menampilkan form export berdasarkan tahun dan bulan.
 * 
 * @param {string} title Setting header form.
 * @param {string} method Method export yang ingin dipanggil saat form disubmit.
 * @param {string} id Parameter id yang diperlukan oleh method export (opsional).
 * @param {functionCallback} onInitSubmit Aksi yang ingin dilakukan saat form disubmit namun aksi submit belum dilakukan (opsional).
 * @param {functionCallback} onSubmitSuccess Aksi yang ingin dilakukan saat aksi submit berhasil dieksekusi (opsional).
 * @param {functionCallback} onSubmitError Aksi yang ingin dilakukan saat aksi submit gagal (opsional). 
 * @param {functionCallback} onSubmitFinished Aksi yang ingin dilakukan saat aksi submit selesai dieksekusi (opsional). 
 */
function FormExportMonthsYear({method, id = "", onInitSubmit, onSubmitSuccess, onSubmitError, onSubmitFinished }) {

    $('#tahun').datepicker({
        autoclose: true,
        minViewMode: 2,
        format: 'yyyy',
        language: 'id',
        startDate: new Date(2017, 0, 1),
        endDate: new Date(),
    }).focus(function () {
        $($(".datepicker-switch")[2]).removeAttr('class').css("width", "145px").html("-- PILIH TAHUN --");
    }).on('changeDate', function (e) {
        $('#bulan').datepicker("destroy").val("");
        $('.field-tahun').removeClass('has-error');
        $('.pesan-tahun').html('');

        let yearPicked = $('#tahun').val();
        let yearNow = new Date().getFullYear();
        let startDate = new Date(yearNow, 0, 1);
        let endDate = new Date();

        if (yearPicked != yearNow) endDate = new Date(yearNow, 11, 31);

        $('#bulan').datepicker({
            autoclose: true,
            minViewMode: 1,
            format: 'MM',
            language: 'id',
            startDate: startDate,
            endDate: endDate,
        }).focus(function () {
            $($(".datepicker-switch")).removeAttr('class').css("width", "145px").html("-- PILIH BULAN --").on('click', function () {
                $('#bulan').val('').datepicker("hide").datepicker("setDate", new Date(0));
            });
        });
    });

    // Setting kondisi saat modal export ditutup.
	$('#modal-export-months-year').on('hidden.bs.modal', function () {
        $('#form-export-months-year').trigger('reset');
        $('#bulan').datepicker("destroy").val("");
        $('.field-tahun').removeClass('has-error');
        $('.pesan-tahun').html('');
	});

    $('#form-export-months-year').submit( async (e) => {
        e.preventDefault();

        if ($('#export-data').val() == method) {
            if (typeof onInitSubmit === "function" && onInitSubmit()) {
				onInitSubmit();
			}

            const tahun = $('#tahun').val().trim();
            const bulan = $('#bulan').data('datepicker').getFormattedDate('mm');            

            if (tahun != "") {
                const formData = new FormData();
                formData.append('tahun', tahun);
                formData.append('bulan', bulan);

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
						console.log("Log Export MonthsYear: " + error.message);
						swal("Pesan Gagal", "Terjadi Kesalahan Teknis, Silahkan Coba Kembali", "error");
					}

					if (typeof onSubmitError === "function" && onSubmitError(error)) {
						onSubmitError(error);
					}
                }
            } else {
                $('.field-tahun').addClass('has-error');
                $('.pesan-tahun').html('Tahun tidak boleh kosong.');
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
		$('#modal-export-months-year').modal();
	}

    // submit export detail
    // $('#btn-export-months-year').on('click', function () {

    //     if ($('#tahun').val().trim() != "") {
    //         $('#bulan').val($('#bulan').data('datepicker').getFormattedDate('mm'));
    //         $('#modal-export-months-year').modal('hide');
    //         $('#form-export-months-year').attr('method', 'POST').submit();
    //     } else {
    //         $('.field-tahun').addClass('has-error');
    //         $('.pesan-tahun').html('Tahun tidak boleh kosong.');
    //     }
    // });
}