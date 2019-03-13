<?php 
	Defined("BASE_PATH") or die(ACCESS_DENIED); 
	$sess_welcome = isset($_SESSION['sess_welcome']) ? $_SESSION['sess_welcome'] : false;
	$sess_notif = isset($_SESSION['notif']) ? $_SESSION['notif'] : false;
	unset($_SESSION['sess_welcome']);
	unset($_SESSION['notif']);
?>

<!DOCTYPE html>
<html>
	<head>

		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
		<link rel="icon" href="<?= BASE_URL."assets/images/69design_icon.ico"; ?>" type='image/x-icon'>
		<title><?= $this->title; ?></title>

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
		<script>
		    const BASE_URL = "<?php print BASE_URL; ?>";
			const BASE_API_MOBILE = "<?php print BASE_API_MOBILE; ?>";
			const USER_ID = "<?php print $_SESSION['sess_id']; ?>";
            const LEVEL = "<?php print $_SESSION['sess_level']; ?>";
			var urlParams = <?php echo json_encode($_GET, JSON_HEX_TAG);?>;
		</script>

		<?php 
			require_once "layout/js/initJs.php";
			?>
                <!-- <script>
                    $(document).ready(function() {
                        setActiveMenu($(location).attr("href").split('/'), LEVEL);
                    });
                </script> -->
            <?php
			if($sess_welcome){
		        ?>
				<script>
					/**
					 * Init toastr selamat datang ke sistem
					 */
			    	$(document).ready(function(){
						var notifWelcome = {type: 'success', title: '', message: 'Selamat Datang di SimakPro'};
						setNotif(notifWelcome, 'toastr');
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