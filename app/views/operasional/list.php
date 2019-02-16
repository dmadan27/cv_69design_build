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
	    	<li class="active">Operasional</li>
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
												<button type="button" class="btn btn-default btn-flat" id="tambah"> <i class="fa fa-plus"></i> Tambah</button>
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
						<div class="row">
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<input type="hidden" id="token" value="">
								<table id="operasionalTable" class="table table-bordered table-hover">
									<thead>
										<tr>
											<th class="text-right" style="width: 35px">No</th>
											<th>Bank</th>
											<th>Tanggal</th>
											<th>Nama</th>
											<th class="text-right">Nominal</th>
											<th>Jenis</th>
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
	</section>
	<!-- /.content -->

	<!-- load form -->
	<?php include_once('form.php'); ?>
	<script type="text/javascript">
		var edit_view = false;
	</script>
</div>