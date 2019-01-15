<?php Defined("BASE_PATH") or die("Dilarang Mengakses File Secara Langsung"); ?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
	  	<h1>
	    	<?= $this->title['main']; ?>
	    	<small><?= $this->title['sub']; ?></small>
	  	</h1>
	  	<!-- breadcrumb -->
	  	<ol class="breadcrumb">
	    	<li><a href="<?= BASE_URL ?>"><i class="fa fa-dashboard"></i> Home</a></li>
	    	<li class="active">Pengajuan Kas Kecil</li>
	  	</ol>
	  	<!-- end breadcrumb -->
	</section>

	<?php 
		$level = $_SESSION['sess_level'];
	?>

	<!-- Main content -->
	<section class="content container-fluid">
		<div class="row">
			<div class="col-lg-12 col-md-12 col-xs-12">
				<div class="box">
					<!-- box header -->
					<div class="box-header">
						<div class="row">
							<h3 class="box-title"></h3>
						</div>
						<!-- panel button -->
						<div class="row">
						<?php if($level == "KAS KECIL" ){ ?>
							<div class="col-md-12 col-sm-12 col-xs-12">
								<div class="btn-group">
									<!-- tambah -->
									<button type="button" class="btn btn-default btn-flat" id="tambah"><i class="fa fa-plus"></i> Tambah</button>
									<!-- export -->
									<button type="button" class="btn btn-success btn-flat" id="exportExcel"><i class="fa fa-file-excel-o"></i> Export Excel</button>
								</div>
							</div>
						<?php } else if($level == "KAS BESAR"){?>
							<div class="col-md-12 col-sm-12 col-xs-12">
								<div class="btn-group">
									<!-- export -->
									<button type="button" class="btn btn-success btn-flat" id="exportExcel"><i class="fa fa-file-excel-o"></i> Export Excel</button>
								</div>
							</div>
							<?php } ?>
						</div>
					</div>
					<!-- box body -->
					<div class="box-body">
						<div class="row">
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<table id="pengajuanKasKecilTable" class="table table-bordered table-hover" style="width: 100%;">
									<thead>
										<tr>
											<th class="text-center" style="width: 35px">No</th>
											<th class="text-center">ID Pengajuan</th>
											<th class="text-center">Kas Kecil</th>
											<th class="text-center">Tanggal</th>
											<th class="text-center">Nama Pengajuan</th>
											<th class="text-center">Total</th>
											<th class="text-center">Status Pengajuan</th>
											<th class="text-center">Aksi</th>
										</tr>
									</thead>
									<tbody>
									</tbody>
								</table>	
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>			
	</section>
	<!-- /.content -->
	<!-- load form -->
<?php include_once('form.php'); ?>
</div>