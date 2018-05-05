<?php Defined("BASE_PATH") or die("Dilarang Mengakses File Secara Langsung"); ?>

<!-- jQuery 3 -->
<script src="<?= BASE_URL."assets/bower_components/jquery/dist/jquery.min.js"; ?>"></script>
<!-- Bootstrap 3.3.7 -->
<script src="<?= BASE_URL."assets/bower_components/bootstrap/dist/js/bootstrap.min.js"; ?>"></script>
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