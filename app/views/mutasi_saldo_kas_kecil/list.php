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
	    	<li><a href="#"> Saldo Kas Kecil</a></li>
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
							<div class="col-md-12">
								<!-- ganti jd ajax -->
								<h4 class="box-title pull-right"><strong>Saldo:  <?php echo $this->data['saldo']; ?></strong></h4>
							</div>
						</div>
						<hr>
						<!-- panel button -->
						<div class="row">
							<div class="col-md-12">
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
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<div class="table-responsive">
								<table id="mutasi_saldo_kas_kecilTable" class="table table-bordered table-hover" style="width: 100%">
									<thead>
										<tr>
											<th class="text-right" style="width: 5%">No</th>
											<th>Tanggal</th>
											<th class="text-right">Uang Masuk</th>
											<th class="text-right">Uang Keluar</th>
											<th class="text-right">Saldo</th>
											<th style="width: 40%">Keterangan</th>
										</tr>
									</thead>
									<tbody></tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>			
	</section>
	<!-- /.content -->
</div>

<?php 
	include_once(__DIR__.'/../form_export/form_start_end_date.php');
?>