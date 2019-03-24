<?php Defined("BASE_PATH") or die("Dilarang Mengakses File Secara Langsung"); ?>

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
	    	<li class="active">Sub Kas Kecil</li>
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
										if($_SESSION['sess_level'] === 'KAS BESAR' || $_SESSION['sess_level'] === 'KAS KECIL') {
											?>
											<!-- tambah -->
											<button type="button" class="btn btn-default btn-flat" id="tambah" value="<?= $this->data['token_add'] ?>"><i class="fa fa-plus"></i> Tambah</button>
											<?php	
										}
									?>
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
									<table id="skcTable" class="table table-bordered table-hover" style="width: 100%">
										<thead>
											<tr>
												<th class="text-right" style="width: 35px">No</th>
												<th>ID</th>
												<th>Nama</th>
												<th>No. Telepon</th>
												<th>Email</th>
												<th class="text-right">Saldo</th>
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
			</div>
		</div>			
	</section>
	<!-- /.content -->

	<!-- load form -->
	<?php include_once('form.php'); ?>
</div>