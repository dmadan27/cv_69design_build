<?php Defined("BASE_PATH") or die("Dilarang Mengakses File Secara Langsung"); ?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
	  	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	  	<title>Sistem Informasi CV. 69 Design & Build | Lockscreen</title>
	  	<!-- Tell the browser to be responsive to screen width -->
	  	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	  	<!-- Bootstrap 3.3.7 -->
	  	<link rel="stylesheet" href="<?= BASE_URL."assets/bower_components/bootstrap/dist/css/bootstrap.min.css"; ?>">
	  	<!-- Font Awesome -->
	  	<link rel="stylesheet" href="<?= BASE_URL."assets/bower_components/font-awesome/css/font-awesome.min.css"; ?>">
	  	<!-- Ionicons -->
	  	<link rel="stylesheet" href="<?= BASE_URL."assets/bower_components/Ionicons/css/ionicons.min.css"; ?>">
	  	<!-- sweet alert 2 -->
		<link rel="stylesheet" href="<?= BASE_URL."assets/bower_components/sweetalert/sweetalert.css"; ?>">
		<!-- toastr -->
		<link rel="stylesheet" href="<?= BASE_URL."assets/bower_components/toastr/build/toastr.min.css"; ?>">
	  	<!-- pace -->
	  	<link rel="stylesheet" type="text/css" href="<?= BASE_URL."assets/bower_components/PACE/themes/red/pace-theme-flash.css"; ?>">
	  	<!-- Theme style -->
	  	<link rel="stylesheet" href="<?= BASE_URL."assets/dist/css/AdminLTE.min.css"; ?>">

	  	<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
	  	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	  	<!--[if lt IE 9]>
	  	<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
	  	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	  	<![endif]-->

	  	<!-- Google Font -->
	  	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
	</head>
	<body class="hold-transition lockscreen">
		<div class="lockscreen-wrapper">
			<div class="lockscreen-logo">
				<a href="<?= BASE_URL ?>"><b>Sistem Informasi CV. 69 Design & Build</b></a>
			</div>
		</div>
		<!-- username -->
		<div class="lockscreen-name"><?= $_SESSION['sess_nama']; ?></div>
		
		<!-- START LOCK SCREEN ITEM -->
  		<div class="lockscreen-item">
    		<!-- lockscreen image -->
    		<div class="lockscreen-image">
      			<img src="<?= $_SESSION['sess_foto']; ?>" alt="User Image">
    		</div>
    		<!-- /.lockscreen-image -->

    		<!-- lockscreen credentials (contains the form) -->
    		<form class="lockscreen-credentials" id="form_lockscreen">
    			<input type="hidden" name="username" id="username" value="<?= $_SESSION['sess_email']; ?>">
      			<div class="input-group">
       		 		<input type="password" class="form-control" placeholder="password" name="password" id="password">
        			<div class="input-group-btn">
          				<button type="submit" class="btn" id="submit_lockscreen"><i class="fa fa-arrow-right text-muted"></i></button>
        			</div>
      			</div>
    		</form>
    		<!-- /.lockscreen credentials -->
  		</div>
  		<!-- /.lockscreen-item -->
  		<div class="help-block text-center">
    		Waktu Idle Anda Telah Habis, Silahkan Login Ulang.
  		</div>
	  	<div class="text-center">
	    	<a href="<?= BASE_URL."login"; ?>">Login Dengan Akun Yang Lain?</a>
	  	</div>

		<div class="lockscreen-footer text-center">
			<?= VERSION; ?>
			<br>
    		<strong>Sistem Informasi 69 Design & Build | Copyright &copy; <?php echo date("Y"); ?> <a href="<?= BASE_URL ?>">CV. 69 Design & Build</a>.</strong> All rights reserved. | Powered By <a href="https://adminlte.io/" target="_blank">AdminLTE v2.4.3</a>
  		</div>

		<script type="text/javascript">
		    var BASE_URL = "<?php print BASE_URL; ?>";
		    var urlParams = <?php echo json_encode($_GET, JSON_HEX_TAG);?>;
		</script>
		<!-- jQuery 3 -->
		<script src="<?= BASE_URL."assets/bower_components/jquery/dist/jquery.min.js"; ?>"></script>
		<!-- Bootstrap 3.3.7 -->
		<script src="<?= BASE_URL."assets/bower_components/bootstrap/dist/js/bootstrap.min.js"; ?>"></script>
		<!-- sweet alert 2 -->
		<script src="<?= BASE_URL."assets/bower_components/sweetalert/sweetalert.min.js"; ?>"></script>
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
		<!-- pace js -->
		<script type="text/javascript" src="<?= BASE_URL."assets/bower_components/PACE/pace.min.js"; ?>"></script>
		<script type="text/javascript">
			$(document).ajaxStart(function(){
				Pace.restart();
			});
		</script>
		<!-- js custom -->
		<script type="text/javascript" src="<?= BASE_URL."app/views/login/js/initLockscreen.js" ?>"></script>
	</body>
</html>