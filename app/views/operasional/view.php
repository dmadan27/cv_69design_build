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
			<li><a href="<?= BASE_URL."operasional/" ?>">Operasional</a></li>
			<li class="active">Detail Operasional</li>
		</ol>
		<!-- end breadcrumb -->
	</section>
	<!-- Main content -->
	<section class="content container-fluid">
		<div class="row">
			<div class="col-lg-12 col-md-12 col-xs-12">
				<div class="box">
					<div class="box-body">
						<div class="row">
							<div class="col-md-12">
								<div class="nav-tabs-custom">
									<ul class="nav nav-tabs">
										<li class="active"><a href="#tab_1" data-toggle="tab">Pengajuan Operasional</a></li>
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
														<legend style="font-size: 18px;">Data Pengajuan Operasional</legend>
															<table class="table table-hover">
																<tr>
																	<td><strong>ID Bank</strong></td>
																	<td><?=  $this->data['id_bank'] ?></td>
																</tr>
																<tr>
																	<td><strong>ID Kas Besar</strong></td>
																	<td><?=  $this->data['id_kas_besar'] ?></td>
																</tr>
																<tr>
																	<td><strong>Tanggal</strong></td>
																	<td><?=  $this->data['tgl'] ?></td>
																</tr>
																<tr>
																	<td><strong>Nama</strong></td>
																	<td><?=  $this->data['nama'] ?></td>
																</tr>
																<tr>
																	<td><strong>Nominal</strong></td>
																	<td><?=  $this->data['nominal'] ?></td>
																</tr>
																<tr>
																	<td><strong>Jenis Operasional</strong></td>
																	<td><?=  $this->data['jenis'] ?></td>
																</tr>
																<tr>
																	<td><strong>Keterangan</strong></td>
																	<td><?=  $this->data['ket'] ?></td>
																</tr>
															</table>
													</fieldset>
												</div>

												<!-- tengah -->
												<div class="col-md-4 col-xs-12">
													<fieldset>
														<legend style="font-size: 18px;">Melalui Bank</legend>
															<table class="table table-hover">
																<tr>
																	<td><strong>ID Bank</strong></td>
																	<td><?=  $this->data['id_bank'] ?></td>
																</tr>
																<tr>
																	<td><strong>Nama Bank</strong></td>
																	<td><?=  $this->data['nama_bank'] ?></td>
																</tr>
																
															</table>
													</fieldset>
												</div>

												<!-- kanan -->
												<div class="col-md-4 col-xs-12">
													<fieldset>
														<legend style="font-size: 18px;">Melalui Kas Besar </legend>
															<table class="table table-hover">
																<tr>
																	<td><strong>ID Kas Besar</strong></td>
																	<td><?=  $this->data['id_kas_besar'] ?></td>
																</tr>
																<tr>
																	<td><strong>Nama Kas Besar</strong></td>
																	<td><?=  $this->data['nama_kas_besar'] ?></td>
																</tr>
																<tr>
																	<td><strong>No Telepon</strong></td>
																	<td><?=  $this->data['no_telp'] ?></td>
																</tr>
																<tr>
																	<td><strong>Email</strong></td>
																	<td><?=  $this->data['email'] ?></td>
																</tr>
															</table>
													</fieldset>
												</div>


											</div>
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
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>			
	</section>
	<!-- /.content -->

</div>