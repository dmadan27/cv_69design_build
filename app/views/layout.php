<?php 
	Defined("BASE_PATH") or die("Dilarang Mengakses File Secara Langsung");
	$sess_welcome = isset($_SESSION['sess_welcome']) ? $_SESSION['sess_welcome'] : false;
	$sess_notif = isset($_SESSION['notif']) ? $_SESSION['notif'] : false;
	unset($_SESSION['sess_welcome']);
	unset($_SESSION['notif']);
?>

<!DOCTYPE html>
<html>
	<head>
		<title>Sistem Informasi Manajemen Arus Keuangan dan Proyek | 69 Design & Build</title>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
		<link rel="icon" href="<?= BASE_URL."assets/images/69design_icon.ico"; ?>" type='image/x-icon'>
		<!-- load css default -->
		<?php require_once "layout/css/css.php"; ?>
	</head>
	<body class="hold-transition skin-blue sidebar-mini">
		<div class="wrapper">
			
			<?php 
				echo $this->header;
				echo $this->sidebar;
				echo $this->content;
				echo $this->footer;
		  	?>
		  	
		</div>

		<!-- load default js -->
		<script type="text/javascript">
		    const BASE_URL = "<?php print BASE_URL; ?>";
			const BASE_API_MOBLIE = "<?php print BASE_API_MOBLIE; ?>";
			var urlParams = <?php echo json_encode($_GET, JSON_HEX_TAG);?>;
		    var level = "<?php print $_SESSION['sess_level']; ?>";

		    /**
			 * Function setNotif
			 * Base function untuk akses notfikasi toastr
			 * @param {object} notif
			 */
			function setNotif(notif){
				switch(notif.type){
					case 'success':
						toastr.success(notif.message, notif.title);
						break;
					case 'warning':
						toastr.warning(notif.message, notif.title);
						break;
					case 'error':
						toastr.error(notif.message, notif.title);
						break;
					default:
						toastr.info(notif.message, notif.title);
						break; 
				}
			}

			/**
			 * Function onChangeField
			 * Base function untuk setiap event onchange semua field yang ada di form
			 * @param {object} scope
			 */
			function onChangeField(scope) {
				if(scope.value !== ""){
					$('.field-'+scope.id).removeClass('has-error').addClass('has-success');
					$(".pesan-"+scope.id).text('');
				}
				else{
					$('.field-'+scope.id).removeClass('has-error').removeClass('has-success');
					$(".pesan-"+scope.id).text('');	
				}
			}
			
		</script>
		<?php 
			require_once "layout/js/js.php";
			if($sess_welcome){
		        ?>
				<script type="text/javascript">
					/**
					 * Init toastr selamat datang ke sistem
					 */
			    	$(document).ready(function(){
			    		toastr.success('Selamat Datang di SimakPro');
			    	});
			    </script>
		        <?php
		    }

		    if($sess_notif){
		    	?>
				<script type="text/javascript">
					/**
					 * Init toastr yang berasal dari session
					 */
		        	var sess_notif = <?php echo json_encode($sess_notif);?>;
			    	$(document).ready(function(){
			    		setNotif(sess_notif);
			    	});
			    </script>
		        <?php
		    }
		?>
	</body>
</html>