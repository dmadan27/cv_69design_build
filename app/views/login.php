<?php Defined("BASE_PATH") or die("Dilarang Mengakses File Secara Langsung"); ?>

<!DOCTYPE html>
<html>
	<head>
	  	<meta charset="utf-8">
	  	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	  	<title>Sistem Informasi CV. 69 Design & Build | Log in</title>
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
	  	<!-- iCheck -->
	  	<link rel="stylesheet" href="<?= BASE_URL."assets/plugins/iCheck/square/blue.css" ?>">

	  	<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
	  	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	  	<!--[if lt IE 9]>
	  	<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
	  	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	  	<![endif]-->

	  	<!-- Google Font -->
	  	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
	</head>
	<body class="hold-transition login-page">
		<div class="login-box">
		  	<div class="login-logo">
		    	<a href="<?= BASE_URL ?>"><b>Sistem Informasi CV. 69 Design & Build</b></a>
		  	</div>
		  	<!-- /.login-logo -->
		  	<div class="login-box-body">
		    	<?php
		    		include_once("login/form_login.php");
		    		include_once("login/form_lupa_password.php");
		    	?>
		  	</div>
		  	<!-- /.login-box-body -->
		</div>
		<!-- /.login-box -->

		<script type="text/javascript">
		    var BASE_URL = "<?php print BASE_URL; ?>";
		    var urlParams = <?php echo json_encode($_GET, JSON_HEX_TAG);?>;
		</script>
		<!-- jQuery 3 -->
		<script src="<?= BASE_URL."assets/bower_components/jquery/dist/jquery.min.js"; ?>"></script>
		<!-- Bootstrap 3.3.7 -->
		<script src="<?= BASE_URL."assets/bower_components/bootstrap/dist/js/bootstrap.min.js"; ?>"></script>
		<!-- iCheck -->
		<script src="<?= BASE_URL."assets/plugins/iCheck/icheck.min.js"; ?>"></script>

		<!-- js custom -->
		<script type="text/javascript">
			$(document).ready(function(){
				// init awal
				$('.form-lupa-password').fadeOut();

				$('#lupaPassword').on('click', function(){
					resetForm();
					$('.form-login').slideUp();
					$('.form-lupa-password').fadeIn();
				});

				$('#back_login').on('click', function(){
					resetForm();
					$('.form-lupa-password').slideUp();
					$('.form-login').slideDown();
				});

				// submit login
				$('#form_login').submit(function(e){
					e.preventDefault();
					submit_login();

					return false;
				});

				// submit lupa password
				$('#form_lupa_password').submit(function(e){
					e.preventDefault();
					submit_lupaPassword();

					return false;
				});

				// on change field
				// ============================ //				
			});
			
			/**
			*
			*/
			function submit_login(){
				$.ajax({
					url: BASE_URL+'login/',
					type: 'POST',
					dataType: 'json',
					data:{
						'user': $('#user').val().trim(),
						'pass': $('#pass').val().trim(),
					},
					beforeSend: function(){

					},
					success: function(output){
						console.log(output);
						if(output.status) document.location=BASE_URL;
						else{
							// set error
						}
					},
					error: function (jqXHR, textStatus, errorThrown) { // error handling
			            console.log(jqXHR, textStatus, errorThrown);
			        }
				})
			}

			/**
			*
			*/
			function submit_lupaPassword(){

			}

			/**
			*
			*/
			function resetForm(){
				// form login
				$('#form_login').trigger('reset');

				// form lupa password
				$('#form_lupa_password').trigger('reset');
			}			

		</script>
	</body>
</html>
