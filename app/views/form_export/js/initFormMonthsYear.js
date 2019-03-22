$(document).ready(function () {

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

        var yearPicked = $('#tahun').val();
        var yearNow = new Date().getFullYear();
        var startDate = new Date(yearNow, 0, 1);
        var endDate = new Date();

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

    // submit export detail
    $('#btn-export-months-year').on('click', function () {

        if ($('#tahun').val().trim() != "") {
            $('#bulan').val($('#bulan').data('datepicker').getFormattedDate('mm'));
            $('#modal-export-months-year').modal('hide');
            $('#form-export-months-year').attr('method', 'POST').submit();
        } else {
            $('.field-tahun').addClass('has-error');
            $('.pesan-tahun').html('Tahun tidak boleh kosong.');
        }
    });
});