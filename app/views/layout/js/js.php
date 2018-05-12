<?php Defined("BASE_PATH") or die("Dilarang Mengakses File Secara Langsung"); ?>

<!-- jQuery 3 -->
<script src="<?= BASE_URL."assets/bower_components/jquery/dist/jquery.min.js"; ?>"></script>
<!-- Bootstrap 3.3.7 -->
<script src="<?= BASE_URL."assets/bower_components/bootstrap/dist/js/bootstrap.min.js"; ?>"></script>
<!-- sweet alert 2 -->
<script src="<?= BASE_URL."assets/bower_components/sweetalert2/dist/sweetalert2.all.min.js"; ?>"></script>
<!-- toastr -->
<script src="<?= BASE_URL."assets/bower_components/toastr/build/toastr.min.js"; ?>"></script>
<script type="text/javascript">
	// init toastr
    toastr.options = {
      	"closeButton": true,
      	"debug": false,
      	"newestOnTop": false,
      	"progressBar": true,
      	"positionClass": "toast-top-right",
      	"preventDuplicates": false,
      	"onclick": null,
      	"showDuration": "300",
      	"hideDuration": "1000",
      	"timeOut": "5000",
      	"extendedTimeOut": "1000",
      	"showEasing": "swing",
      	"hideEasing": "linear",
      	"showMethod": "fadeIn",
      	"hideMethod": "fadeOut"
    }
</script>
<!-- pace js-->
<script type="text/javascript" src="<?= BASE_URL."assets/bower_components/PACE/pace.min.js"; ?>"></script>
<!-- run pace js -->
<script type="text/javascript">
	$(document).ajaxStart(function(){
		Pace.restart();
	});
</script>
<?php $this->getJS(); ?>
<!-- AdminLTE App -->
<script src="<?= BASE_URL."assets/dist/js/adminlte.min.js"; ?>"></script>