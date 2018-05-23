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
						<div class="row">
							<h3 class="box-title"></h3>
						</div>
						<!-- panel button -->
						<div class="row">
							<div class="col-md-12 col-sm-12 col-xs-12">
								<div class="btn-group">
									<!-- tambah -->
									<button type="button" class="btn btn-default btn-flat" id="tambah" value="<?= $this->data['token_add'] ?>"><i class="fa fa-plus"></i> Tambah</button>
									<!-- export -->
									<button type="button" class="btn btn-success btn-flat" id="exportExcel"><i class="fa fa-file-excel-o"></i> Export Excel</button>
								</div>
							</div>
						</div>
					</div>
					<!-- box body -->
					<div class="box-body">
						<table id="kas_kecilTable" class="table table-bordered table-hover">
							<thead>
								<tr>
									<th>ID</th>
									<th>Nama</th>
									<th>Alamat</th>
									<th>No Telp</th>
									<th>Email</th>
									<th>Saldo</th>
									
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