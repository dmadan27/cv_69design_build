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
	    	<li><a href="javascript:void(0)"><i class="fa fa-dashboard"></i> Home</a></li>
	    	<li class="active">></i> Data Pengajuan Sub Kas Kecil</a></li>
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
									<!-- export -->
									<button type="button" class="btn btn-success btn-flat" id="exportExcel"><i class="fa fa-file-excel-o"></i> Export Excel</button>
								</div>
								<button type="button" class="btn btn-info btn-flat pull-right" id="refreshTable"><i class="fa fa-refresh"></i> Refresh</button>
							</div>
						</div>
					</div>
					<!-- box body -->
					<div class="box-body">
						<table id="laporan_pengajuan_sub_kas_kecilTable" class="table table-bordered table-hover" style="width:100%">
							<thead>
								<tr>
									<th class="text-right" style="width: 35px">No</th>
									<th>ID</th>
									<th>Tanggal</th>
									<th>Sub Kas Kecil</th>
									<th>Id Proyek</th>
									<th>Pengajuan</th>
									<th class="text-right">Total</th>
									<th class="text-right">Total Asli</th>
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
	</section>
	<!-- /.content -->
</div>