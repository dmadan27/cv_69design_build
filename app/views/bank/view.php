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
				 	 	<!-- Nama Bank -->
					  	<h3><?= $this->data['nama'] ?></h3>
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
							<button id="btn_edit" type="button" class="btn btn-success btn-flat" title="Edit Data"><i class="fa fa-pencil"></i></button>
							<!-- hapus -->
							<button id="btn_delete" type="button" class="btn btn-danger btn-flat" title="Hapus Data"><i class="fa fa-trash"></i></button>
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
								<div class="table-responsive">
									<table id="mutasiBankTable" class="table table-bordered table-hover" style="width:100%">
										<thead>
											<tr>
												<th class="text-right">No</th>
												<th>Tanggal</th>
												<th class="text-right">Uang Masuk</th>
												<th class="text-right">Uang Keluar</th>
												<th class="text-right">Saldo</th>
												<th>Keterangan</th>
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

	<div class="modal fade" id="modalTanggalExport">
		<div class="modal-dialog modal-md">
			<div class="modal-content">
				<!-- header modal -->
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<h4 class="modal-title">Export Data Mutasi Bank ke Excel</h4>
				</div>
				
				<form id="form_tanggal_export" role="form">
					
					<!-- body modal -->
					<div class="modal-body">
						<!-- field Dari Tanggal -->
						<div class="form-group field-tgl_awal has-feedback">
							<label for="tgl_awal">Dari Tanggal</label>
							<input type="text" name="tgl_awal" id="tgl_awal" class="form-control datepicker field" placeholder="Dari Tanggal">
							<span class="help-block small pesan pesan-tgl_awal"></span>
						</div>
						<!-- field Sampai Tanggal -->
						<div class="form-group field-tgl_akhir has-feedback">
							<label for="tgl_akhir">Sampai Tanggal</label>
							<input type="text" name="tgl_akhir" id="tgl_akhir" class="form-control datepicker field" placeholder="Sampai Tanggal">
							<span class="help-block small pesan pesan-tgl_akhir"></span>
						</div>
						
					</div>
				
					<!-- modal footer -->
					<div class="modal-footer">
						<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Kembali</button>
						<button type="button" id="submit_export" onclick="export_excel(<?=$this->data['id_bank']?>)" class="btn btn-primary" value="tambah">Export Detail</button>
					</div>
				</form>

			</div>
		</div>
	</div>

	<!-- load form -->
	<?php include_once('form.php'); ?>
</div>