<?php
	Defined("BASE_PATH") or die("Dilarang Mengakses File Secara Langsung"); 
	$pengajuan = $this->data['data_pengajuan'];
	$detail = $this->data['data_detail'];
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
			<li><a href="<?= BASE_URL."pengajuan-sub-kas-kecil/" ?>">Pengajuan Sub Kas Kecil</a></li>
			<li class="active">Detail Data Pengajuan SKK</li>
		</ol>
		<!-- end breadcrumb -->
	</section>

	<!-- Main content -->
	<section class="content container-fluid">
		<input type="hidden" id="id" value="<?= strtolower($pengajuan['id']); ?>">
		<div class="row">
            <!-- Data pengajuan -->
            <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
				<div class="box">
                    <div class="box-header">
                        <h4 class="box-title">Data Pengajuan</h4>
                    </div>
					<div class="box-body">	
                        <!-- <?php 
                            echo "<pre>";
                            var_dump($this->data);
                            echo "</pre>";
                        ?> -->
                        <table class="table table-hover">
                            <!-- ID -->
                            <tr>
                                <td><strong>ID</strong></td>
                                <td><?= $pengajuan['id'] ?></td>
                            </tr>
                            <!-- SKK -->
                            <tr>
                                <td><strong>Sub Kas Kecil</strong></td>
                                <td><?= $pengajuan['skk'] ?></td>
                            </tr>

                            <!-- Tanggal -->
                            <tr>
                                <td><strong>Tanggal</strong></td>
                                <td><?= $pengajuan['tgl'] ?></td>
                            </tr>

                            <!-- Proyek -->
                            <tr>
                                <td><strong>Proyek</strong></td>
                                <td><?= $pengajuan['id_proyek'] ?></td>
                            </tr>

                            <!-- Pengajuan -->
                            <tr>
                                <td><strong>Pengajuan</strong></td>
                                <td style="word-wrap: break-word;min-width: 160px;max-width: 160px;"><?= $pengajuan['nama_pengajuan'] ?></td>
                            </tr>

                            <!-- Total -->
                            <tr>
                                <td><strong>Total Pengajuan</strong></td>
                                <td class="text-right"><?= $pengajuan['total'] ?></td>
                            </tr>

                            <!-- Dana disetujui -->
                            <tr>
                                <td><strong>Dana Disetujui</strong></td>
                                <td class="text-right"><?= $pengajuan['dana_disetujui'] ?></td>
                            </tr>

                            <!-- Status -->
                            <tr>
                                <td><strong>Status</strong></td>
                                <td><?= $pengajuan['status'] ?></td>
                            </tr>
                        </table>
                        </br>
                        <button class="btn btn-success btn-block" onclick="getEdit('<?= strtolower($pengajuan['id']); ?>')">Edit Status Pengajuan</button>	
					</div>
				</div>
			</div>

            <!-- Data Detail -->                            
            <div class="col-xs-12 col-sm-8 col-md-8 col-lg-8">
                <div class="box">
                    <div class="box-header">
                        <h4 class="box-title">Detail Pengajuan</h4>
                    </div>
                    <div class="box-body">
                        <div class="table-responsive">
                            <table id="table_detail" class="table table-hover table-border" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th class="text-right" style="width: 35px">No</th>
                                        <th>Nama</th>
                                        <th>Jenis</th>
                                        <th>Satuan</th>
                                        <th class="text-right">Qty</th>
                                        <th class="text-right">Harga</th>
                                        <th class="text-right">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        if(!empty($detail)) {
                                            foreach($detail as $row) {
                                                echo '<tr>';
                                                echo '<td>'.$row['no_urut'].'</td>';
                                                echo '<td>'.$row['nama'].'</td>';
                                                echo '<td>'.$row['jenis'].'</td>';
                                                echo '<td>'.$row['satuan'].'</td>';
                                                echo '<td>'.$row['qty'].'</td>';
                                                echo '<td>'.$row['harga'].'</td>';
                                                echo '<td>'.$row['subtotal'].'</td>';
                                                echo '</tr>';
                                            }
                                        }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
		</div>

		<!-- panel button -->
		<div class="row">
  			<div class="col-lg-12 col-md-12 col-xs-12">    				
  				<div class="box box-info"> 
                    <div class="box-body">
                    	<div class="row">
                    		<div class="col-md-12 col-xs-12">
                    			<div class="btn-group">
									<a href="<?= BASE_URL.'pengajuan-sub-kas-kecil/'; ?>" class="btn btn-default btn-flat btn-lg" role="button">Kembali</a>
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
    <?php include_once('form.php'); ?>
</div>