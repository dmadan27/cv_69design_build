<?php
	Defined("BASE_PATH") or die("Dilarang Mengakses File Secara Langsung"); 
	$laporan = $this->data['data_laporan'];
    $detail = $this->data['data_detail'];
    $bukti_laporan = $this->data['data_bukti_laporan'];
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
			<li><a href="<?= BASE_URL."laporan-sub-kas-kecil/" ?>">Laporan Pengajuan SKK</a></li>
			<li class="active">Detail Laporan Pengajuan SKK</li>
		</ol>
		<!-- end breadcrumb -->
	</section>

	<!-- Main content -->
	<section class="content container-fluid">
		<input type="hidden" id="id" value="<?= strtolower($laporan['id']); ?>">
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
                                <td><?= $laporan['id'] ?></td>
                            </tr>
                            <!-- SKK -->
                            <tr>
                                <td><strong>Sub Kas Kecil</strong></td>
                                <td><?= $laporan['skk'] ?></td>
                            </tr>

                            <!-- Tanggal -->
                            <tr>
                                <td><strong>Tanggal</strong></td>
                                <td><?= $laporan['tgl'] ?></td>
                            </tr>

                            <!-- Proyek -->
                            <tr>
                                <td><strong>Proyek</strong></td>
                                <td><?= $laporan['id_proyek'] ?></td>
                            </tr>

                            <!-- Pengajuan -->
                            <tr>
                                <td><strong>Pengajuan</strong></td>
                                <td><?= $laporan['nama_pengajuan'] ?></td>
                            </tr>

                            <!-- Total -->
                            <tr>
                                <td><strong>Total Pengajuan</strong></td>
                                <td class="text-right"><?= $laporan['total'] ?></td>
                            </tr>

                            <!-- Total Asli -->
                            <tr>
                                <td><strong>Total Asli</strong></td>
                                <td class="text-right"><?= $laporan['total_asli'] ?></td>
                            </tr>

                            <!-- Status -->
                            <tr>
                                <td><strong>Status</strong></td>
                                <td><?= $laporan['status'] ?></td>
                            </tr>
                        </table>
                        <?php
                            if($this->data['action']) {
                                ?>
                                </br>
                                <button class="btn btn-success btn-block" onclick="getEdit('<?= strtolower($laporan['id']); ?>')">Edit Status Pengajuan</button>
                                <?php
                            }
                        ?>	
					</div>
				</div>
			</div>

            <!-- Data Detail -->                            
            <div class="col-xs-12 col-sm-8 col-md-8 col-lg-8">
                <div class="box">
                    <div class="box-header">
                        <h4 class="box-title">Detail Pengajuan</h4>
                        <button type="button" class="btn btn-info btn-flat pull-right" id="refreshTable"><i class="fa fa-refresh"></i> Refresh</button>
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
                                        <th class="text-right">Subtotal Asli</th>
                                        <th class="text-right">Sisa</th>
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
                                                echo '<td>'.$row['subtotal_asli'].'</td>';
                                                echo '<td>'.$row['sisa'].'</td>';
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

        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header">
                        <h4 class="box-title">Bukti Laporan</h4>
                    </div>
                    <div class="box-body">
                        <?php
                            if(!empty($bukti_laporan)) {
                                $i = 0;
                                foreach($bukti_laporan as $item) {
                                    $i++;
                                    echo '<a href="'.$item['foto'].'" class="image-popup" title="Bukti Laporan "'.$i.'>';
                                    echo '<img src="'.$item['foto'].'" class="profile-user-img margin">';
                                    echo '</a>';
                                }
                            }
                            else {
                                echo '<p class="text-center">Tidak Ada Bukti Laporan</p>';
                            }
                        ?>
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
</div>
<script>
    var status_laporan = "<?= $laporan['status_order']; ?>";
</script>
<?php include_once('form.php'); ?>