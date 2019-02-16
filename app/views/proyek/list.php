<?php Defined("BASE_PATH") or die(ACCESS_DENIED); ?>

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
	    	<li><a href="javascript:void(0)"><i class="fa fa-dashboard"></i> Home</a></li>
	    	<li class="active">Data Proyek</li>
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
									<?php
										if($_SESSION['sess_level'] === 'KAS BESAR') {
											?>
											<!-- tambah -->
											<button type="button" class="btn btn-default btn-flat" id="tambah"><i class="fa fa-plus"></i> Tambah</button>
											<?php
										}
									?>
									<!-- export -->
									<button type="button" class="btn btn-success btn-flat" id="exportExcel"><i class="fa fa-file-excel-o"></i> Export Excel</button>
								</div>
							</div>
						</div>
					</div>
					<!-- box body -->
					<div class="box-body">
						<div class="table-responsive">
							<table id="proyekTable" class="table table-bordered table-hover" style="width:100%">
								<thead>
									<tr>
										<th class="text-right" style="width: 35px">No</th>
										<th>ID</th>
										<th>Pemilik</th>
										<th>Tanggal</th>
										<th>Pembangunan</th>
										<th>Kota</th>
										<th class="text-right">Total</th>
										<th>Progress</th>
										<th>Status</th>
										<th>Aksi</th>
									</tr>
								</thead>
								<tbody></tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>			
	</section>
	<!-- /.content -->
</div>