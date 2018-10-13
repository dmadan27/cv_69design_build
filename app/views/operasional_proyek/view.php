<?php Defined("BASE_PATH") or die("Dilarang Mengakses File Secara Langsung"); 
		$o_proyek = $this->data['dataOperasionalProyek'];
?>
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
			<li><a href="<?= BASE_URL."operasional-proyek/" ?>">Operasional Proyek</a></li>
			<li class="active">Detail Operasional Proyek</li>
		</ol>
		<!-- end breadcrumb -->
	</section>
	<!-- Main content -->
	<section class="content container-fluid">
		<input type="hidden" id="id" value="<?= strtolower($o_proyek['id']); ?>">
		<div class="row">
			<div class="col-lg-12 col-md-12 col-xs-12">
				<div class="box">
					<div class="box-body">
						<div class="row">
							<div class="col-md-12">
								<div class="nav-tabs-custom">
									<ul class="nav nav-tabs">
										<li class="active"><a href="#tab_1" data-toggle="tab">Pengajuan Operasional Proyek</a></li>
										<li class=""><a href="#tab_history_pembelian" data-toggle="tab">History Pembelian</a></li>
										
									</ul>
									<!-- tab content -->
									<div class="tab-content">
										<!-- Tab 1: profil proyek -->
										<div class="tab-pane active" id="tab_1">
											<div class="row">
												<!-- data profil -->
													<!-- kiri -->
												<div class="col-md-4 col-xs-12">
													<fieldset>
														<legend style="font-size: 18px;">Data Pengajuan Operasional Proyek</legend>
															<table class="table table-hover">
																<tr>
																	<td><strong>ID Operasional Proyek</strong></td>
																	<td><?=  $o_proyek['id'] ?></td>
																</tr>
																<tr>
																	<td><strong>Tanggal Pengajuan</strong></td>
																	<td><?=  $o_proyek['tgl_pengajuan'] ?></td>
																</tr>
																<tr>
																	<td><strong>Nama Pengajuan</strong></td>
																	<td><?=  $o_proyek['nama_pengajuan'] ?></td>
																</tr>
																<tr>
																	<td><strong>Jenis Pengajuan</strong></td>
																	<td><?=  $o_proyek['jenis_pengajuan'] ?></td>
																</tr>
																<tr>
																	<td><strong>Total Pengajuan</strong></td>
																	<td><?=  $o_proyek['total_pengajuan'] ?></td>
																</tr>
																<tr>
																	<td><strong>Sisa Pengajuan</strong></td>
																	<td><?=  $o_proyek['sisa_pengajuan'] ?></td>
																</tr>
																<tr>
																	<td><strong>Status Pengajuan</strong></td>
																	<td><?=  $o_proyek['status_pengajuan'] ?></td>
																</tr>
																<tr>
																	<td><strong>Status Lunas</strong></td>
																	<td><?=  $o_proyek['status_lunas'] ?></td>
																</tr>
																<tr>
																	<td><strong>Keterangan</strong></td>
																	<td><?=  $o_proyek['keterangan'] ?></td>
																</tr>
																
																
															</table>
													</fieldset>
												</div>
													<!-- tengah -->
												<div class="col-md-4 col-xs-12">
													<fieldset>
														<legend style="font-size: 18px;">Proyek</legend>
															<table class="table table-hover">
																<tr>
																	<td><strong>ID Proyek</strong></td>
																	<td><?=  $o_proyek['id_proyek'] ?></td>
																</tr>
																<tr>
																	<td><strong>Pemilik Proyek</strong></td>
																	<td><?=  $o_proyek['pemilik_proyek'] ?></td>
																</tr>
																<tr>
																	<td><strong>Nama Pembangunan</strong></td>
																	<td><?=  $o_proyek['nama_pembangunan'] ?></td>
																</tr>
																<tr>
																	<td colspan="2"><legend style="font-size: 18px;">Bank</legend></td>
																</tr>
																
																<tr>
																	<td><strong>ID Bank</strong></td>
																	<td><?=  $o_proyek['id_bank'] ?></td>
																</tr>
																<tr>
																	<td><strong>Nama Bank</strong></td>
																	<td><?=  $o_proyek['nama_bank'] ?></td>
																</tr>
															</table>	
														</fieldset>
												</div>

													<!-- kanan -->
												<div class="col-md-4 col-xs-12">
													<fieldset>
														<legend style="font-size: 18px;">Kas Besar</legend>
															<table class="table table-hover">
																<tr>
																	<td><strong>ID Kas Besar</strong></td>
																	<td><?=  $o_proyek['id_kas_besar'] ?></td>
																</tr>
																<tr>
																	<td><strong>Nama Kas Besark</strong></td>
																	<td><?=  $o_proyek['nama_kas_besar'] ?></td>
																</tr>
																<tr>
																	<td colspan="2"><legend style="font-size: 18px;">Distributor</legend></td>
																</tr>
																<tr>
																	<td><strong>ID Distributor</strong></td>
																	<td><?=  $o_proyek['id_distributor'] ?></td>
																</tr>
																<tr>
																	<td><strong>Nama  Distributor</strong></td>
																	<td><?=  $o_proyek['nama_distributor'] ?></td>
																</tr>
															</table>	
														</fieldset>
												</div>
												
											</div>
											<div class="btn-group">
												<a href="<?= BASE_URL.'operasional-proyek/'; ?>" class="btn btn-md btn-flat btn-success" role="button">Kembali</a>
													
												</div>
										</div>
										<div class="tab-pane" id="tab_history_pembelian">
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>			
	</section>
	<!-- /.content -->

</div>