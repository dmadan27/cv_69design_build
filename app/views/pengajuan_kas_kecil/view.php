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
			<li class="active">Detail Pengajuan Kas Kecil</li>
		</ol>
		<!-- end breadcrumb -->
	</section>
	<!-- Main content -->
	<section class="content container-fluid">
		<div class="row">
			
			<!-- panel profil image -->
			

			<!-- panel data profil -->
			<div class="col-md-12 col-xs-12">
				<div class="nav-tabs-custom">
					
					<ul class="nav nav-tabs">
              			<li class="active"><a href="#data-pkk" data-toggle="tab">Data Pengajuan Kas Kecil</a></li>
                        <!-- <li class=""><a href="#data-detailPengajuanSKK" data-toggle="tab">Data Detail Pengajuan Sub Kas Kecil</a></li> -->
              			
            		</ul>
            		
            		<div class="tab-content">
              			
              			<!-- Data Profil -->
              			<div class="active tab-pane" id="data-pkk">
                			<div class="row">
                				<div class="col-md-12">
                					<table class="table table-hover">
                						<!-- ID -->
                						<tr>
                							<td><strong>ID</strong></td>
                							<td><?= $this->data['id']; ?></td>
                						</tr>

                						<!-- ID Kas Kecil -->
                						<tr>
                							<td><strong>ID Kas Kecil</strong></td>
                							<td><?= $this->data['id_kas_kecil']; ?></td>
                						</tr>

										<!-- Nama Kas Kecil -->
                						<tr>
                							<td><strong>Kas Kecil</strong></td>
                							<td><?= $this->data['kas_kecil']; ?></td>
                						</tr>

                						<!-- TGL -->
                						<tr>
                							<td><strong>Tanggal Pengajuan</strong></td>
                							<td><?= $this->data['tgl']; ?></td>
                						</tr>

                						<!-- Nama -->
                						<tr>
                							<td><strong>Nama Pengajuan</strong></td>
                							<td><?= $this->data['nama']; ?></td>
                						</tr>
                						

                						<!-- Total -->
                						<tr>
                							<td><strong>Total Pengajuan</strong></td>
                							<td><?= $this->data['total']; ?></td>
                						</tr>

                						<!-- Status -->
                						<tr>
                							<td><strong>Status Pengajuan</strong></td>
                							<td><?= $this->data['status']; ?></td>
                						</tr>

                                        <!-- Status -->
                                        <tr>
                                            <td><strong>Keterangan Pengajuan</strong></td>
                                            <td><?= $this->data['ket']; ?></td>
                                        </tr>
                                        
                					</table>
                				</div>
                			</div>
              			</div>

                       

		            </div>
		            <!-- /.tab-content -->

				</div>
			</div>

		</div>
	</section>
	<!-- /.content -->
</div>