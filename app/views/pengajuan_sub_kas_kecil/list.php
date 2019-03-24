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
	    	<li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
	    	<li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
	    	<li class="active">Here</li>
	  	</ol>
	  	<!-- end breadcrumb -->

	</section>
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
									<button type="button" class="btn btn-success btn-flat" id="exportExcel"><i class="fa fa-file-excel-o"></i> Export Excel</button>
								</div>
								<button type="button" class="btn btn-info btn-flat pull-right" id="refreshTable"><i class="fa fa-refresh"></i> Refresh</button>
							</div>
						</div>
					</div>
					<!-- box body -->
					<div class="box-body">
						<div class="table-responsive">
							<table id="pengajuan_sub_kas_kecilTable" class="table table-bordered table-hover" style="width: 100%">
								<thead>
									<tr>
										<th class="text-right" style="width: 5%">No</th>
										<th>ID</th>
										<th>Tanggal</th>
										<th>Sub Kas Kecil</th>
										<th>Proyek</th>
										<th>Pengajuan</th>
										<th class="text-right">Total</th>
										<th class="text-right">Dana Disetujui</th>
										<th>Status</th>
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
	</section>
	<!-- /.content -->
	<?php include_once(__DIR__.'/../form_export/form_months_year.php'); ?>
	<?php include_once('form.php'); ?>
</div>