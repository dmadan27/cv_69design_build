<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
	  	<h1>
	    	<?= $this->title['main']; ?>
	    	<small><?= $this->title['sub']; ?></small>
	  	</h1>
	  	<!-- breadcrumb -->
	  	<ol class="breadcrumb">
	    	<li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
	    	<li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
	    	<li class="active">Here</li>
	  	</ol>
	  	<!-- end breadcrumb -->

	</section>
	<!-- Main content -->
	<section class="content container-fluid">
		<div class="row">
			<div class="col-lg-12 col-md-12 col-xs-12">
				<div class="box">
					<!-- box header -->
					<div class="box-header">
						<h3 class="box-title">Data Laporan Pengajuan Sub Kas Kecil</h3>
					</div>
					<!-- box body -->
					<div class="box-body">
						<table id="laporan_pengajuan_sub_kas_kecilTable" class="table table-bordered table-hover">
							<thead>
								<tr>
									<th>ID Pengajuan</th>
									<th>Nama</th>
									<th>Jenis</th>
									<th>Satuan</th>
									<th>Qty</th>
									<th>Harga</th>
									<th>Harga Asli</th>
									<th>Status</th>
									<th>Status Lunas</th>					
								</tr>
							</thead>
							<tbody>
								<?php
									foreach ($this->data as $key => $value) {
										echo "<tr>";
										foreach($value as $row){
											echo "<td>".$row."</td>";
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
	</section>
	<!-- /.content -->
</div>