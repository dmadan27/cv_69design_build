<?php 
	Defined("BASE_PATH") or die("Dilarang Mengakses File Secara Langsung"); 
	$operasionalProyek = $this->data['data_operasionalProyek'];
?>

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
			<li><a href="<?= BASE_URL."operasional-proyek/" ?>">Operasional Proyek</a></li>
			<li class="active">Detail Operasional Proyek</li>
		</ol>
		<!-- end breadcrumb -->
	</section>
	<!-- Main content -->
	<section class="content container-fluid">
		<input type="hidden" id="id" value="<?= strtolower($operasionalProyek['id']); ?>">

		<!-- operasional proyek dan detail operasional proyek -->
		<div class="row">

			<!-- operasional proyek -->
			<div class="col-md-4">
				<div class="box">
					<div class="box-header">
						<h4 class="box-title">Operasional Proyek</h4>
					</div>
					<div class="box-body">
                        <table class="table table-hover">
                            <tr>
                                <td><strong>Id</strong></td>
                                <td><?= $operasionalProyek['id'] ?></td>
                            </tr>
                            <tr>
                                <td><strong>Proyek</strong></td>
                                <td><?= $operasionalProyek['id_proyek'].' - '.$operasionalProyek['nama_pembangunan'] ?></td>
                            </tr>
                            <tr>
                                <td><strong>Tanggal</strong></td>
                                <td><?= $operasionalProyek['tgl_operasional'] ?></td>
                            </tr>
							<tr>
                                <td><strong>Operasional</strong></td>
                                <td><?= $operasionalProyek['nama_operasional'] ?></td>
                            </tr>
                            <tr>
                                <td><strong>Kas Besar</strong></td>
                                <td><?= $operasionalProyek['id_kas_besar'].' - '.$operasionalProyek['nama_kas_besar'] ?></td>
                            </tr>
                            <tr>
                                <td><strong>Distributor</strong></td>
                                <td><?= $operasionalProyek['id_distributor'].' - '.$operasionalProyek['nama_distributor'] ?></td>
                            </tr>
                            <tr>
                                <td><strong>Jenis Operasional</strong></td>
                                <td><?= $operasionalProyek['jenis_operasional'] ?></td>
                            </tr>
                            <tr>
                                <td><strong>Jenis Pembayaran</strong></td>
                                <td><?= $operasionalProyek['jenis_pembayaran'] ?></td>
                            </tr>
                            <tr>
                                <td><strong>Total</strong></td>
                                <td class="text-right"><?= $operasionalProyek['total'] ?></td>
                            </tr>
							<?php
								if($operasionalProyek['jenis_pembayaran'] == 'KREDIT') {
									?>
									<tr>
										<td><strong>Sisa Pembayaran</strong></td>
										<td class="text-right"><?= $operasionalProyek['sisa_operasional'] ?></td>
									</tr>
									<?php
								}
							?>
                            <tr>
                                <td><strong>Status Lunas</strong></td>
                                <td><?= $operasionalProyek['status_lunas'] ?></td>
                            </tr>
							<tr>
                                <td><strong>Keterangan</strong></td>
                                <td><?= $operasionalProyek['keterangan'] ?></td>
                            </tr>
                        </table>
					</div>
				</div>
			</div>
			<!-- end operasional proyek -->

			<!-- detail operasional proyek -->
			<div class="col-md-8">
				<div class="row">
					<div class="col-md-12">
						<div class="box box-detail_operasionalProyek">
                            <div class="box-header with-border">
                                <h4 class="box-title">Detail Pembayaran Proyek</h4>
                            </div>
							<div class="box-body">
                                <div class="form-group">
                                    <button type="button" class="btn btn-success btn-flat" id="exportExcel"><i class="fa fa-file-excel-o"></i> Export Excel</button>
                                    <button type="button" class="btn btn-info btn-flat pull-right" id="refreshTable"><i class="fa fa-refresh"></i> Refresh</button>
                                </div>
								<div class="table-responsive">
									<table id="detailOperasionalProyek" class="table table-bordered table-hover" style="width: 100%;">
										<thead>
											<tr>
												<th class="text-right" style="width: 35px">No</th>
												<th>Tanggal</th>
												<th>Pembayaran</th>
												<th>Bank</th>
												<th class="text-right">Total</th>
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
			<!-- end detail operasional proyek -->

		</div>
		<!-- end operasional proyek dan detail operasional proyek -->

		<!-- panel button -->
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
                                            <a href="<?= BASE_URL.'operasional-proyek/form/'.strtolower($operasionalProyek['id']); ?>" class="btn btn-success btn-flat btn-lg" role="button">Edit</a>
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
		<!-- end panel button -->
	</section>
</div>