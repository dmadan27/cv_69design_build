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
	    	<li><a href="#"><i class="fa fa-dashboard"></i> Saldo Kas Kecil</a></li>
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
							<div class="col-md-12 col-sm-12 col-xs-12 text-right">
								<h3 class="box-title"><strong>Saldo: Rp. ,-</strong></h3>
							</div>
						</div>
						<!-- panel button -->
						<div class="row">
							<div class="col-md-12 col-sm-12 col-xs-12">
								<div class="btn-group">
									<!-- tambah -->
									<!-- <a href="<?= BASE_URL."" ?>" class="btn btn-default btn-flat" role="button"><i class="fa fa-plus"></i> Tambah</a> -->
									<!-- export -->
									<button type="button" class="btn btn-success btn-flat" id="exportExcel"><i class="fa fa-file-excel-o"></i> Export Excel</button>
								</div>
							</div>
						</div>
					</div>
					<!-- box body -->
					<div class="box-body">
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<table id="mutasi_saldo_kas_kecilTable" class="table table-bordered table-hover">
									<thead>
										<tr>
											<th>No</th>
											<th>Tanggal</th>
											<th>Uang Masuk</th>
											<th>Uang Keluar</th>
											<th>Saldo</th>
											<th>Ket</th>
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