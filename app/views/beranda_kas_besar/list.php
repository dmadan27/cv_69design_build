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
	    	<li class="active">Beranda Kas Besar</li>
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
									<!-- export -->
									<button type="button" class="btn btn-success btn-flat" id="exportExcel"><i class="fa fa-file-excel-o"></i> Export Excel</button>
								</div>
							</div>
						</div>
					</div>
					<!-- box body -->
					<div class="box-body">
						<div class="row">
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<input type="hidden" id="token_list" value="">
								<h3>PELAKSANAAN PROJECT BERJALAN DAN SELESAI</h3>
								<table id="berandaKasBesarTable" class="table table-bordered table-hover">
									<thead>
										<tr>
											<th class="text-center" style="width: 35px">No</th>
											<th class="text-center">ID</th>
											<th class="text-center">Pembangunan</th>
											<th class="text-center">Pemilik</th>
											<th class="text-center">Status</th>
											<th class="text-center">Total</th>
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


					
						<div class="col-lg-12 col-md-12 col-xs-12">
							<div class="box">
								<!-- Data Saldo Kas Kecil dan Sub Kas Kecil -->
								<div class="box-body">
									<div class="row">
										<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
											<input type="hidden" id="token_list" value="">
											<h3>Saldo Kas Kecil & Kas Sub.kas kecil (Logistic)</h3>
											<table id="saldoKKandSKKTable" class="table table-bordered table-hover">
												<thead>
													<tr>
														<th class="text-center">ID</th>
														<th class="text-center">Nama</th>
														<th class="text-center">Saldo</th>
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
</div>