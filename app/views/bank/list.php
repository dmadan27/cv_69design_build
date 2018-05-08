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
	    	<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
	    	<li class="active">Bank</li>
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
									<!-- tambah -->
									<button type="button" class="btn btn-default btn-flat" id="tambah"><i class="fa fa-plus"></i> Tambah</button>
									<!-- export -->
									<button type="button" class="btn btn-success btn-flat" id="exportExcel"><i class="fa fa-file-excel-o"></i> Export Excel</button>
								</div>
							</div>
						</div>
					<!-- box body -->
					<div class="box-body">
						<input type="hidden" id="token" value="<?= $this->data['tokenCrsf']; ?>">
						<table id="bankTable" class="table table-bordered table-hover">
							<thead>
								<tr>
									<th>No</th>
									<th>Nama</th>
									<th>Saldo</th>
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

	<!-- load form -->
	<?php include_once('form.php'); ?>
</div>