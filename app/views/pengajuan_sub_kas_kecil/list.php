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
									<!-- <a href="<?= BASE_URL."proyek/form/" ?>" class="btn btn-default btn-flat" role="button"><i class="fa fa-plus"></i> Tambah</a> -->
									<!-- export -->
									<button type="button" class="btn btn-success btn-flat" id="exportExcel"><i class="fa fa-file-excel-o"></i> Export Excel</button>
								</div>
							</div>
						</div>
					</div>
					<!-- box body -->
					<div class="box-body">
						<input type="hidden" id="token_list" value="<?= $this->data['token_list']; ?>">
						<table id="pengajuan_sub_kas_kecilTable" class="table table-bordered table-hover">
							<thead>
								<tr>
									<th class="text-right" style="width: 15px">No</th>
									<th>ID</th>
									<th>ID Sub Kas Kecil</th>
									<th>ID Proyek</th>
									<!-- <th>Nama Proyek</th> -->
									<th>Tanggal</th>
									<th class="text-right">Total</th>
									<th class="text-right">Dana Disetujui</th>
									<th>Status Pengajuan</th>
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
	</section>
	<!-- /.content -->
	<?php include_once('form.php'); ?>
	<script type="text/javascript">
		var edit_view = false;
	</script>
</div>