<?php Defined("BASE_PATH") or die("Dilarang Mengakses File Secara Langsung"); ?>

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
			<li><a href="<?= BASE_URL."bank/" ?>">Bank</a></li>
			<li class="active">Detail Data Bank</li>
		</ol>
		<!-- end breadcrumb -->
	</section>
	<!-- Main content -->
	<section class="content container-fluid">
		<div class="row">
			<!-- panel info data bank -->
			<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
				<input type="hidden" id="id" value="<?=$this->data['id_bank']?>">
				<div class="box box-widget widget-user-2">
					<div class="widget-user-header bg-aqua">
						<!-- <div class="widget-user-image">
							<img class="img-circle" src="../dist/img/user7-128x128.jpg" alt="User Avatar">
			  			</div> -->
				 	 	<!-- /.widget-user-image -->
					  	<!-- <h3 class="widget-user-username">BANK ANU</h3> -->
					  	<h3><?= $this->data['nama'] ?></h3>
					  	<!-- <h5 class="widget-user-desc">Lead Developer</h5> -->
					</div>
					<div class="box-footer no-padding">
						<ul class="nav nav-stacked">
							<li><a href="javascript:void(0)"><strong>Saldo </strong><span class="pull-right"><?= $this->data['saldo'] ?></span></a></li>
                			<li><a href="javascript:void(0)"><strong>Status </strong><div class="pull-right"><?= $this->data['status']; ?></div></a></li>
						</ul>
					</div>
					<div class="box-footer text-center">
						<div class="btn-group">
							<!-- edit -->
							<button onclick="getEdit('<?=$this->data["id_bank"]?>', '<?=$this->data["token"]["edit"]?>')" type="button" class="btn btn-success btn-flat" title="Edit Data"><i class="fa fa-pencil"></i></button>
							<!-- hapus -->
							<button onclick="getDelete('<?=$this->data["id_bank"]?>', '<?=$this->data["token"]["delete"]?>')" type="button" class="btn btn-danger btn-flat" title="Hapus Data"><i class="fa fa-trash"></i></button>
							<!-- kembali -->
							<button onclick="back()" type="button" class="btn btn-info btn-flat" title="Kembali"><i class="fa fa-reply"></i></button>
						</div>
					</div>
				</div>
			</div>
			<!-- panel info tabel mutasi bank -->
			<div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
				<div class="box">
					<!-- box header -->
					<div class="box-header with-border">
						<h3 class="box-title">Data Mutasi Bank</h3>
						<!-- export -->
						<button type="button" class="pull-right btn btn-success btn-flat" id="exportExcel"><i class="fa fa-file-excel-o"></i> Export Excel</button>	
					</div>
					<!-- box body -->
					<div class="box-body">
						<div class="row">
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<input type="hidden" id="token_view" value="<?= $this->data['token']['view']; ?>">
								<table id="mutasiBankTable" class="table table-bordered table-hover">
									<thead>
										<tr>
											<th class="text-center">No</th>
											<th class="text-center">Tanggal</th>
											<th class="text-center">Uang Masuk</th>
											<th class="text-center">Uang Keluar</th>
											<th class="text-center">Saldo</th>
											<th class="text-center">Keterangan</th>
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

</div>