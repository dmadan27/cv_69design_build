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
							<!-- <img class="profile-user-img img-responsive img-circle" src="<?= $this->data['foto']; ?>" alt="User profile picture"> -->
						</a>
						<h3 class="profile-username text-center"><?= $this->data['nama']; ?></h3>
              			<p class="text-muted text-center"></p>
              			<ul class="list-group list-group-unbordered">
              				<?php 
              					             				?>
		                	<li class="list-group-item">
		                  		<b>Pemilik</b> <a class="pull-right"><?= $this->data['pemilik']; ?></a>
		                	</li>
		                	<li class="list-group-item">
		                 	 	<b>Status</b> <a class="pull-right"><?= $this->data['status']; ?></a>
		                	</li>
		              	</ul>
		              	
		              	
		              	<a href="<?= BASE_URL.'distributor';  ?>" class="btn btn-default btn-block" role="button"><b>Kembali</b></a>
					</div>
				</div>
			</div>

			<!-- panel data profil -->
			<div class="col-md-9 col-xs-12">
				<div class="nav-tabs-custom">
					
					<ul class="nav nav-tabs">
              			<li class="active"><a href="#data-profil" data-toggle="tab">Data Profil</a></li>
                        <li class=""><a href="#data-historypembelian" data-toggle="tab">Data History Pembelian</a></li>
              			
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

                						<!-- Pemilik -->
                						<tr>
                							<td><strong>Pemilik</strong></td>
                							<td><?= $this->data['pemilik']; ?></td>
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

                        <!-- Data History Pembelian -->
                        <div class="tab-pane" id="data-historypembelian">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="box-body">
                                        <div class="form-group">
                                            <!-- export -->
                                            <button type="button" class="btn btn-success btn-flat" id="exportExcel_pengajuan"><i class="fa fa-file-excel-o"></i> Export Excel</button>
                                        </div>
                                            <table id="historyPembelianDistributor" class="table table-bordered table-hover" style="width: 100%;">
                                                <thead>
                                                    <tr>
                                                        <!-- <th class="text-right">No</th> -->
                                                        <th>ID</th>
                                                        <th>NAMA TOKO</th>
                                                        <th>PEMILIK</th>
                                                        <th>ID OPERASIONAL PROYEK</th>
                                                        <th>KEBUTUHAN </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
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

	

</div>