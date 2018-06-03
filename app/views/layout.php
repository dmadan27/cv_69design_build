<?php 
	Defined("BASE_PATH") or die("Dilarang Mengakses File Secara Langsung");
	$sess_welcome = isset($_SESSION['sess_welcome']) ? $_SESSION['sess_welcome'] : false;
	unset($_SESSION['sess_welcome']); 
?>

<!DOCTYPE html>
<html>
	<head>
		<title>Sistem Informasi 69 Design & Build</title>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
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
		    var BASE_URL = "<?php print BASE_URL; ?>";
		    var urlParams = <?php echo json_encode($_GET, JSON_HEX_TAG);?>;
		</script>
		<?php 
			require_once "layout/js/js.php";
			if($sess_welcome){
		        ?>
		        <script type="text/javascript">
			    	$(document).ready(function(){
			    		toastr.success('Selamat Datang di Sistem Informasi 69 Design & Build');
			    	});
			    </script>
		        <?php
		    } 
		?>
	</body>
</html>