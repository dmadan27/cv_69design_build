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
			<li class="active">Profil</li>
		</ol>
		<!-- end breadcrumb -->
	</section>
	<!-- Main content -->
	<section class="content container-fluid">
		<div class="row">
			
			<!-- panel profil image -->
			<div class="col-md-3 col-xs-12">
				<div class="box box-primary">
					<div class="box-body box-profile">
						<a href="<?= $this->data['foto']; ?>" class="image-popup" title="<?= $this->data['nama']; ?>">
							<img class="profile-user-img img-responsive img-circle" src="<?= $this->data['foto']; ?>" alt="User profile picture">
						</a>
						<h3 class="profile-username text-center"><?= $this->data['nama']; ?></h3>
              			<p class="text-muted text-center"></p>
              			<ul class="list-group list-group-unbordered">
		                	<li class="list-group-item">
		                  		<b>Email</b> <a class="pull-right"><?= $this->data['email']; ?></a>
		                	</li>
		                	<li class="list-group-item">
		                  		<b>Saldo</b> <a class="pull-right"><?= $this->data['saldo']; ?></a>
		                	</li>
		                	<li class="list-group-item">
		                 	 	<b>Status</b> <a class="pull-right"><?= $this->data['status']; ?></a>
		                	</li>
		              	</ul>
		              	
		              	<a href="<?= BASE_URL.'sub-kas-kecil'; ?>" class="btn btn-default btn-block" role="button"><b>Kembali</b></a>
					</div>
				</div>
			</div>

			<!-- panel data profil -->
			<div class="col-md-9 col-xs-12">
				<div class="nav-tabs-custom">
					
					<ul class="nav nav-tabs">
              			<li class="active"><a href="#data-profil" data-toggle="tab">Data Profil</a></li>
              			<li><a href="#data-mutasi" data-toggle="tab">Data Mutasi</a></li>
              			<li><a href="#data-pengajuan" data-toggle="tab">History Pengajuan</a></li>
              			
            		</ul>
            		
            		<div class="tab-content">
              			
              			<!-- Data Profil -->
              			<div class="active tab-pane" id="data-profil">
                			<div class="row">
                				<div class="col-md-12">
                					<table class="table table-hover">
                						<!-- ID -->
                						<tr>
                							<td><strong>ID</strong></td>
                							<td><?= $this->data['id']; ?></td>
                						</tr>

                						<!-- Nama -->
                						<tr>
                							<td><strong>Nama</strong></td>
                							<td><?= $this->data['nama']; ?></td>
                						</tr>

                						<!-- Alamat -->
                						<tr>
                							<td><strong>Alamat</strong></td>
                							<td><?= $this->data['alamat']; ?></td>
                						</tr>

                						<!-- No. Telepon -->
                						<tr>
                							<td><strong>No. Telepon</strong></td>
                							<td><?= $this->data['no_telp']; ?></td>
                						</tr>

                						<!-- Email -->
                						<tr>
                							<td><strong>Email</strong></td>
                							<td><?= $this->data['email']; ?></td>
                						</tr>
										
										<!-- Saldo -->
                						<tr>
                							<td><strong>Saldo</strong></td>
                							<td><?= $this->data['saldo']; ?></td>
                						</tr>


                						<!-- Status -->
                						<tr>
                							<td><strong>Status</strong></td>
                							<td><?= $this->data['status']; ?></td>
                						</tr>
                					</table>
                				</div>
                			</div>
              			</div>

              			<!-- Data Mutasi Sub Kas Kecil -->
              			<div class="tab-pane" id="data-mutasi">
              				<div class="row">
                				<div class="col-md-12">
                					<div class="box">
										<!-- box header -->
										<div class="box-header with-border">
											<h3 class="box-title">Data Mutasi Sub Kas Kecil</h3>
											<!-- export -->
											<button type="button" class="pull-right btn btn-success btn-flat" id="exportExcel"><i class="fa fa-file-excel-o"></i> Export Excel</button>	
										</div>
										<!-- box body -->
										<div class="box-body">
											<div class="row">
												<input type="hidden" id="id" value="<?= $this->data['id']?>">
												<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
													<!-- <input type="hidden" id="token_view" value="<?= $this->data['token']['view']; ?>"> -->
													<table id="mutasiSubKasKecilTable" class="table table-bordered table-hover">
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

              			<!-- Data History Pengajuan -->
              			<div class="tab-pane" id="data-pengajuan">
              				<div class="row">
                				<div class="col-md-12">
                					<div class="box">
										<!-- box header -->
										<div class="box-header with-border">
											<h3 class="box-title">Data History Pengajuan Sub Kas Kecil</h3>
											<!-- export -->
											<button type="button" class="pull-right btn btn-success btn-flat" id="exportExcel"><i class="fa fa-file-excel-o"></i> Export Excel</button>	
										</div>
										<!-- box body -->
										<div class="box-body">
											<div class="row">
												<input type="hidden" id="id" value="<?= $this->data['id']?>">
												<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
													<!-- <input type="hidden" id="token_view" value="<?= $this->data['token']['view']; ?>"> -->
													<table id="pengajuanSubKasKecilTable" class="table table-bordered table-hover">
														<thead>
															<tr>
																<th class="text-right">No</th>
																<th>Tanggal</th>
																<th class="text-right">Total</th>
																<th class="text-right">Dana Disetujui</th>
																<th>Status</th>
																<th>Status Laporan</th>
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
              			
	              		<!-- /.tab-pane -->

		            </div>
		            <!-- /.tab-content -->

				</div>
			</div>

		</div>
	</section>
	<!-- /.content -->

	<?php 
		// include('form.php');
		// include('form_foto.php');
	?>

</div>