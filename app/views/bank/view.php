<?php Defined("BASE_PATH") or die("Dilarang Mengakses File Secara Langsung"); ?>

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
			<li><a href="<?= BASE_URL."bank/" ?>">Bank</a></li>
			<li class="active">Detail Data Bank</li>
		</ol>
		<!-- end breadcrumb -->
	</section>
	<!-- Main content -->
	<section class="content container-fluid">
		<div class="row">
			<!-- panel info data bank -->
			<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
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
				</div>
			</div>
			<!-- panel info tabel mutasi bank -->
			<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
				<div class="box box-mutasi">
					<!-- box header -->
					<div class="box-header with-border">
						<div class="row">
							<div class="col-md-12"><h3 class="box-title">Data Mutasi Bank</h3></div>
						</div>
						<hr>
						<div class="row">
							<div class="col-md-12">
								<!-- export -->
								<button type="button" class="btn btn-success btn-flat" id="exportExcel"><i class="fa fa-file-excel-o"></i> Export Excel</button>
								<button type="button" class="btn btn-info btn-flat pull-right" id="refreshTable"><i class="fa fa-refresh"></i> Refresh</button>
							</div>
						</div>
					</div>
					<!-- box body -->
					<div class="box-body">
						<div class="row">
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<div class="table-responsive">
									<table id="mutasiBankTable" class="table table-bordered table-hover" style="width:100%">
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

		<!-- panel button aksi -->
		<div class="row">
  			<div class="col-lg-12 col-md-12 col-xs-12">    				
  				<div class="box box-info"> 
                    <div class="box-body">
                    	<div class="row">
                    		<div class="col-md-12 col-xs-12">
                    			<div class="btn-group">
									<button class="btn btn-default btn-flat btn-lg" onclick="goBack()">Kembali</button>
	                    			<?php
                                        if($_SESSION['sess_level'] === 'KAS BESAR') {
                                            ?>
											<!-- edit -->
											<button id="btn_edit" type="button" class="btn btn-success btn-flat btn-lg" title="Edit Data">Edit</button>
                                            <?php
                                        }
                                    ?>
                                </div>		
                       		</div>
                    	</div>
                    </div>
                    <div class="clearfix"></div>
                </div>
  			</div>
		</div>
	</section>
	<!-- /.content -->

	<!-- load form -->
	<?php include_once('form.php'); ?>
	<?php include_once(__DIR__.'/../form_export/form_start_end_date.php'); ?>
</div>