<?php Defined("BASE_PATH") or die("Dilarang Mengakses File Secara Langsung"); ?>

<!DOCTYPE html>
<html>
	<head>
	  	<meta charset="utf-8">
	  	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	  	<title><?= $error; ?></title>
	  	<!-- Tell the browser to be responsive to screen width -->
	  	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	  	<!-- Bootstrap 3.3.7 -->
	  	<link rel="stylesheet" href="<?= BASE_URL."assets/bower_components/bootstrap/dist/css/bootstrap.min.css"; ?>">
	  	<!-- Font Awesome -->
	  	<link rel="stylesheet" href="<?= BASE_URL."assets/bower_components/font-awesome/css/font-awesome.min.css"; ?>">
	  	<!-- Ionicons -->
	  	<link rel="stylesheet" href="<?= BASE_URL."assets/bower_components/Ionicons/css/ionicons.min.css"; ?>">
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
		<!-- Automatic element centering -->
		<div class="lockscreen-wrapper" style="padding-top: 10%">
		  	<div class="lockscreen-logo">
		    	<a href="../../index2.html"><b>Admin</b>LTE</a>
		  	</div>
		  	<!-- User name -->
		  	<div class="lockscreen-name">
		  		<h1 class="text-red"><?= $error; ?></h1>
		  		<h4><?= $pesanError; ?></h4>
		  	</div>

			<!-- START LOCK SCREEN ITEM -->
		  	<div class="lockscreen-item"></div>
		  	<!-- /.lockscreen-item -->
		  	
		  	<div class="text-center">
		    	<a href="<?= BASE_URL; ?>" class="btn bg-purple btn-flat margin">BACK TO HOME</a>
		  	</div>
		  	<div class="lockscreen-footer text-center">
		    	Copyright &copy; 2014-2016 <b><a href="https://adminlte.io" class="text-black">Almsaeed Studio</a></b><br>
		    	All rights reserved
		  	</div>
		</div>
		<!-- /.center -->

		<!-- jQuery 3 -->
		<script src="<?= BASE_URL."assets/bower_components/jquery/dist/jquery.min.js"; ?>"></script>
		<!-- Bootstrap 3.3.7 -->
		<script src="<?= BASE_URL."assets/bower_components/bootstrap/dist/js/bootstrap.min.js"; ?>"></script>
	</body>
</html>
