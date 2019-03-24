<?php 
	Defined("BASE_PATH") or die("Dilarang Mengakses File Secara Langsung"); 
	$proyek = $this->data['data_proyek'];
	$skk = $this->data['data_skk'];
	$arus = $this->data['data_arus'];
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
			<li><a href="<?= BASE_URL."proyek/" ?>">Proyek</a></li>
			<li class="active">Detail Data Proyek</li>
		</ol>
		<!-- end breadcrumb -->
	</section>
	<!-- Main content -->
	<section class="content container-fluid">
		<input type="hidden" id="id" value="<?= strtolower($proyek['id']); ?>">

        <!-- profil, detail pembayaran dan detail logistik -->
		<div class="row">

			<!-- profil proyek -->
			<div class="col-md-4">
				<div class="box">
					<div class="box-header">
						<h4 class="box-title">Profil Proyek</h4>
					</div>
					<div class="box-body">
                        <table class="table table-hover">
                            <tr>
                                <td><strong>Id Proyek</strong></td>
                                <td><?= $proyek['id'] ?></td>
                            </tr>
                            <tr>
                                <td><strong>Pemilik</strong></td>
                                <td><?= $proyek['pemilik'] ?></td>
                            </tr>
                            <tr>
                                <td><strong>Tanggal</strong></td>
                                <td><?= $proyek['tgl'] ?></td>
                            </tr>
                            <tr>
                                <td><strong>Pembangunan</strong></td>
                                <td><?= $proyek['pembangunan'] ?></td>
                            </tr>
                            <tr>
                                <td><strong>Luas Area</strong></td>
                                <td class="text-right"><?= $proyek['luas_area'] ?> M<sup>2</sup></td>
                            </tr>
                            <tr>
                                <td><strong>Alamat</strong></td>
                                <td><?= $proyek['alamat'] ?></td>
                            </tr>
                            <tr>
                                <td><strong>Kota</strong></td>
                                <td><?= $proyek['kota'] ?></td>
                            </tr>
                            <tr>
                                <td><strong>Estimasi Pembangunan</strong></td>
                                <td><?= $proyek['estimasi'] ?></td>
                            </tr>
                            <tr>
                                <td><strong>Nilai RAB</strong></td>
                                <td class="text-right"><?= $proyek['total'] ?></td>
                            </tr>
                            <tr>
                                <td><strong>DP</strong></td>
                                <td class="text-right"><?= $proyek['dp'] ?></td>
                            </tr>
                            <tr>
                                <td><strong>CCO</strong></td>
                                <td class="text-right"><?= $proyek['cco'] ?></td>
                            </tr>
                            <tr>
                                <td><strong>Status</strong></td>
                                <td><?= $proyek['status'] ?></td>
                            </tr>
                            <tr>
                                <td><strong>Progress</strong>
                                </td>
                                <td class="text-right"><?= $proyek['progress']['text'] ?></td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <div class="progress">
                                        <div class="progress-bar progress-bar-primary progress-bar-striped active" role="progressbar" aria-valuenow="<?= $proyek['progress']['value'] ?>" aria-valuemin="0" aria-valuemax="100" <?= $proyek['progress']['style'] ?> >
                                            <span class="sr-only"><?= $proyek['progress']['text'] ?></span>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </table>
					</div>
				</div>
			</div>
			<!-- end profil proyek -->

			<!-- detail proyek -->
			<div class="col-md-8">
				<!-- detail pembayaran proyek -->
				<div class="row">
					<div class="col-md-12">
						<div class="box">
                            <div class="box-header with-border">
                                <!-- <div class="row">
                                    <div class="col-md-12"> -->
                                        <h4 class="box-title">Detail Pembayaran Proyek</h4>
                                    <!-- </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-md-12"> -->
                                        
                                    <!-- </div>
                                </div> -->
                            </div>
							<div class="box-body">
                                <div class="form-group">
                                    <button type="button" class="btn btn-success btn-flat" id="exportExcel_pembayaran"><i class="fa fa-file-excel-o"></i> Export Excel</button>
                                    <button type="button" class="btn btn-info btn-flat pull-right" id="refreshTable_pembayaran"><i class="fa fa-refresh"></i> Refresh</button>
                                </div>
								<div class="table-responsive">
									<table id="detail_pembayaran" class="table table-bordered table-hover" style="width: 100%;">
										<thead>
											<tr>
												<th class="text-right" style="width: 35px">No</th>
												<th>Tanggal</th>
												<th>Pembayaran</th>
												<th>Bank</th>
												<th>DP</th>
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

				<!-- detail logistik proyek -->
				<div class="row">
					<div class="col-md-12">
						<div class="box">
							<div class="box-header">
                                <h4 class="box-title">Detail Logistik Proyek (SKK)</h4>
							</div>
                            <div class="box-body">
                                <div class="table-responsive">
                                    <table id="detail_logistik" class="table table-bordered table-hover" style="width: 100%;">
                                        <thead>
                                            <tr>
                                                <th class="text-right" style="width: 35px">No</th>
                                                <th style="width: 200px">ID Sub Kas Kecil</th>
                                                <th>Nama</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                $no = 1;
                                                foreach($skk as $row){
                                                    echo "<tr>";
                                                    echo "<td class='text-right'>".$no++."</td>";
                                                    foreach($row as $value){
                                                        echo "<td>".$value."</td>";
                                                    }
                                                    echo "</tr>";
                                                }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
						</div>
					</div>
				</div>
			</div>
			<!-- end detail proyek -->

		</div>
        <!-- end profil, detail pembayaran dan detail logistik -->

        <!-- arus terment dan kas pelaksanaan project -->
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header">
                        <h4 class="box-title">Arus Terment dan Kas Pelaksanaan Project</h4>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <!-- arus terment project -->
                            <div class="col-md-6 col-xs-12">
                                <fieldset>
                                    <legend style="font-size: 18px;">Arus Terment Project</legend>
                                    <table class="table table-hover">
                                        <tr>
                                            <td><strong>Total Pelaksanaan Utama</strong></td>
                                            <td class="text-right"><strong><?= $arus['total_pelaksana_utama'] ?></strong></td>
                                        </tr>
                                        <tr>
                                            <td>Nilai RAB Kontrak</td>
                                            <td class="text-right"><?= $arus['nilai_rab'] ?></td>
                                        </tr>
                                        <tr>
                                            <td>CCO</td>
                                            <td class="text-right"><?= $arus['cco'] ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Nilai Terment diterima</strong></td>
                                            <td class="text-right"><strong><?= $arus['nilai_terment_diterima'] ?></strong></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Sisa Terment Project</strong></td>
                                            <td class="text-right"><strong><?= $arus['sisa_terment_project'] ?></strong></td>
                                        </tr>
                                    </table>
                                </fieldset>
                            </div>

                            <!-- arus kas pelaksanaan project -->
                            <div class="col-md-6 col-xs-12">
                                <fieldset>
                                    <legend style="font-size: 18px;">Arus Kas Pelaksanaan Project</legend>
                                    <table class="table table-hover">
                                        <tr>
                                            <td><strong>Nilai Terment Masuk</strong></td>
                                            <td class="text-right"><strong><?= $arus['nilai_terment_masuk'] ?></strong></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Total Pelaksanaan Project</strong></td>
                                            <td class="text-right"><strong><?= $arus['total_pelaksana_project'] ?></strong></td>
                                        </tr>
                                        <tr>
                                            <td>Keluaran Tunai</td>
                                            <td class="text-right"><?= $arus['keluaran_tunai'] ?></td>
                                        </tr>
                                        <tr>
                                            <td>Keluaran Kredit</td>
                                            <td class="text-right"><?= $arus['keluaran_kredit'] ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Saldo Kas Pelaksanaan</strong></td>
                                            <td class="text-right"><strong><?= $arus['saldo_kas_pelaksanaan'] ?></strong></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Selisih Terment Masuk - Keluran Tunai</strong></td>
                                            <td class="text-right"><strong><?= $arus['selisih'] ?></strong></td>
                                        </tr>
                                    </table>
                                </fieldset>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end arus terment dan kas pelaksanaan project -->
        
        <!-- data pengajuan sub kas kecil -->
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header with-border">
                        <!-- <div class="row">
                            <div class="col-md-12"> -->
                                <h4 class="box-title">Detail Pengajuan Sub Kas Kecil</h4>
                            <!-- </div>
                        </div>
                        <hr>
                        <div class="row"> -->
                            <!-- <div class="col-md-12"> -->
                                
                            <!-- </div>
                        </div> -->
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="form-group">
                            <button type="button" class="btn btn-success btn-flat" id="exportExcel_pengajuan"><i class="fa fa-file-excel-o"></i> Export Excel</button>
                            <button type="button" class="btn btn-info btn-flat pull-right" id="refreshTable_pengajuan"><i class="fa fa-refresh"></i> Refresh</button>
                        </div>
                        <div class="table-responsive">
                            <table id="pengajuan_skkTable" class="table table-bordered table-hover" style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th class="text-right" style="width: 35px">No</th>
                                        <th>ID</th>
                                        <th>Tanggal</th>
                                        <th>Pengajuan</th>
                                        <th>Sub Kas Kecil</th>
                                        <th class="text-right">Total Pengajuan</th>
                                        <th class="text-right">Dana Disetuji</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
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
        <!-- end data pengajuan sub kas kecil -->
        
        <!-- data operasional proyek -->
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header with-border">
                        <!-- <div class="row">
                            <div class="col-md-12"> -->
                                <h4 class="box-title">Detail Operasional Proyek</h4>
                            <!-- </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-12"> -->
                                
                            <!-- </div>
                        </div> -->
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class=form-group>
                            <button type="button" class="btn btn-success btn-flat" id="exportExcel_operasional"><i class="fa fa-file-excel-o"></i> Export Excel</button>
                            <button type="button" class="btn btn-info btn-flat pull-right" id="refreshTable_operasional"><i class="fa fa-refresh"></i> Refresh</button>
                        </div>
                        <div class="table-responsive">
                            <table id="operasional_proyekTable" class="table table-bordered table-hover" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th class="text-right" style="width: 35px">No</th>
                                        <th>ID</th>
                                        <th>Tanggal</th>
                                        <th>Nama Pengajuan</th>
                                        <th>Kas Besar</th>
                                        <th>Jenis Pembayaran</th>
                                        <th>Status</th>
                                        <th class="text-right">Total</th>
                                        <th>Aksi</th>
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
        <!-- end data operasional proyek -->

		<!-- panel button -->
		<div class="row">
  			<div class="col-lg-12 col-md-12 col-xs-12">    				
  				<div class="box box-info"> 
                    <div class="box-body">
                    	<div class="row">
                    		<div class="col-md-12 col-xs-12">
                    			<div class="btn-group">
									<a href="<?= BASE_URL.'proyek/'; ?>" class="btn btn-default btn-flat btn-lg" role="button">Kembali</a>
	                    			<?php
                                        if($_SESSION['sess_level'] === 'KAS BESAR') {
                                            ?>
                                            <a href="<?= BASE_URL.'proyek/form/'.strtolower($proyek['id']); ?>" class="btn btn-success btn-flat btn-lg" role="button">Edit</a>
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

</div>