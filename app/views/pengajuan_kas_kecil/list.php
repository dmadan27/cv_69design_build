<?php Defined("BASE_PATH") or die(ACCESS_DENIED); ?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
	  	<h1>
	    	<?= $this->propertyPage['main']; ?>
	    	<small><?= $this->propertyPage['sub']; ?></small>
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
							<div class="col-md-12 col-sm-12 col-xs-12">
								<div class="btn-group">
									<?php if($level == "KAS KECIL" ){ ?>
									<!-- tambah -->
									<button type="button" class="btn btn-default btn-flat" id="tambah"><i class="fa fa-plus"></i> Tambah</button>	
									<?php } ?>
									<!-- export -->
									<button type="button" class="btn btn-success btn-flat" id="exportExcel"><i class="fa fa-file-excel-o"></i> Export Excel</button>
								</div>
								<button type="button" class="btn btn-info btn-flat pull-right" id="refreshTable"><i class="fa fa-refresh"></i> Refresh</button>
							</div>
						</div>
					</div>
					<!-- box body -->
					<div class="box-body">
						<div class="row">
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<div class="table-responsive">
									<table id="pengajuanKasKecilTable" class="table table-bordered table-hover" style="width: 100%;">
										<thead>
											<tr>
												<th class="text-right" style="width: 5%">No</th>
												<th>ID Pengajuan</th>
												<th>Kas Kecil</th>
												<th>Tanggal</th>
												<th>Pengajuan</th>
												<th class="text-right">Total</th>
												<th>Status Pengajuan</th>
												<th>Aksi</th>
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
		</div>			
	</section>
	<!-- /.content -->
	<!-- load form -->
	<?php include_once('form.php'); ?>
	<?php include_once('modal.php'); ?>
	<?php include_once(__DIR__.'/../form_export/form_start_end_date.php'); ?>
</div>