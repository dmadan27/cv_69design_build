<?php Defined("BASE_PATH") or die("Dilarang Mengakses File Secara Langsung"); 
		$o_proyek = $this->data['dataOperasionalProyek'];
		$dataHistoryPembelanjaan = $this->data['dataHistoryPembelanjaan'];
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
		<input type="hidden" id="id" value="<?= strtolower($dataHistoryPembelanjaan['id']); ?>">

		<div class="row">
			<div class="col-lg-12 col-md-12 col-xs-12">
				<div class="box">
					<div class="box-body">
						<div class="row">
							<div class="col-md-12">
								<div class="nav-tabs-custom">
									<ul class="nav nav-tabs">
										<li class="active"><a href="#tab_1" data-toggle="tab">Pengajuan Operasional Proyek</a></li>
										<li class=""><a href="#tab_detail_operasionalProyek" data-toggle="tab">Detail Operasional Proyek</a></li>
										<!-- <li class=""><a href="#tab_history_pembelian" data-toggle="tab">History Pembelian</a></li> -->
										
										
									</ul>
									<!-- tab content -->
									<div class="tab-content">
										<!-- Tab 1: Data Operasional Proyek -->
										<div class="tab-pane active" id="tab_1">
											<div class="row">
												<!-- data profil -->
													<!-- kiri -->
												<div class="col-md-4 col-xs-12">
													<fieldset>
														<!-- <legend style="font-size: 18px;">Data Pengajuan Operasional Proyek</legend> -->
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
																	<td><?=  $o_proyek['jenis_pembayaran'] ?></td>
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


																
															</table >	
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

										<!-- Tab 2 : Data Detail Operasional Proyek  -->
										<div class="tab-pane" id="tab_detail_operasionalProyek">
											<div class="row">
												<div class="col-md-12">
													<?php $id = $this->data['id_operasional_proyek']; ?>
													<button type="button" class="btn btn-success btn-flat" id="exportExcel"><i class="fa fa-file-excel-o"></i> Export Excel</button>
													<button type="button" class="btn btn-info btn-flat pull-right" id="refreshTable"><i class="fa fa-refresh"></i> Refresh</button>
												</div>
											</div>
											<hr>
											<div class="row">
												<div class="col-md-12">
													<!-- tabel detail operasional proyek -->
													<div class="table-responsive">
														<table id="detailOperasionalProyek" class="table table-bordered table-hover" style="width: 100%;">
															<thead>
																<tr>
																	<th class="text-right" style="width: 5%">No</th>
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

										<div class="modal fade" id="modalTanggalExport">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <!-- header modal -->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">Export Data Operasional Proyek ke Excel</h4>
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
                    <button type="button" id="submit_export" onclick="export_excel('<?= $id ?>')" class="btn btn-primary" value="tambah">Export Detail</button>
                </div>
            </form>

        </div>
    </div>
</div>

										<!-- Tab 3 : Data History Pembelian  -->
										<!-- <div class="tab-pane" id="tab_history_pembelian"> -->
											<!-- tabel history pembelian -->
											<!-- <div class="panel box box-primary">
												<div class="box-header with-border">
													<h6 class="box-title">
                      									<a data-toggle="collapse" data-parent="#accordion" href="#collapse_historyPembelian">
								                        	Data History Pembelian
								                      	</a>
								                    </h6>
												</div>
												<div id="collapse_historyPembelian" class="panel-collapse collapse in">
													<div class="box-body">
														<div class="form-group"> -->
															<!-- export -->
															<!-- <button type="button" class="btn btn-success btn-flat" id="exportExcel_history" onclick="export_history('<?php echo $id;?>')"><i class="fa fa-file-excel-o"></i> Export Excel</button>
														</div>
														<table id="historyPembelian" class="table table-bordered table-hover" style="width: 100%;">
															<thead> -->
																<!-- <tr>  -->
																	<!--<th class="text-right">No</th> -->
																	<!-- <th>ID</th>
																	<th>Tanggal</th>
																	<th>Nama</th>
																	<th>Total</th>
																	<th>Status Lunas</th>
																	<th>ID Distributor</th>
																	<th>Nama Distributor</th>
																	<th>Pemilik</th>
																</tr>
															</thead>
															<tbody>
															</tbody>
														</table>
													</div>
												</div>
											</div>

								
										</div> -->

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