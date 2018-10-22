<?php Defined("BASE_PATH") or die("Dilarang Mengakses File Secara Langsung"); ?>

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
	    	<li><a href="javascript:void(0)"><i class="fa fa-dashboard"></i> Home</a></li>
	    	<li class="active">Beranda</li>
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
						<h3 class="box-title"></h3>
					</div>
					<!-- box body -->
					<div class="box-body">
						<div class="row">
							<div class="col-md-4">
					          <!-- Widget: user widget style 1 -->
					          <div class="box box-widget widget-user">
					            <!-- Add the bg color to the header using any of the bg-* classes -->
					            <div class="widget-user-header bg-aqua">
					              <span class="info-box-number">Jumlah Pengajuan dari Kas Kecil :</span>
					            </div>
					            <div class="box-footer no-padding">
					              <ul class="nav nav-stacked">
					                <li><a href="#">Disetujui <span class="pull-right badge bg-blue">31</span></a></li>
					                <li><a href="#">Perbaiki <span class="pull-right badge bg-aqua">5</span></a></li>
					                <li><a href="#">Ditolak <span class="pull-right badge bg-green">12</span></a></li>
					                <li><a href="#">Pending <span class="pull-right badge bg-red">842</span></a></li>
					              </ul>
					            </div>
					          </div>
					          <!-- /.widget-user -->
					        </div>

					        <div class="col-md-4">
					          <!-- Widget: user widget style 1 -->
					          <div class="box box-widget widget-user">
					            <!-- Add the bg color to the header using any of the bg-* classes -->
					            <div class="widget-user-header bg-aqua">
					              <span class="info-box-number">Statistik Data Pengguna </span>
					            </div>
					            <div class="box-footer no-padding">
					              <ul class="nav nav-stacked">
					                <li><a href="#">Kas Besar <span class="pull-right badge bg-blue">31</span></a></li>
					                <li><a href="#">Kas Kecil <span class="pull-right badge bg-aqua">5</span></a></li>
					                <li><a href="#">Sub Kas Kecil <span class="pull-right badge bg-green">12</span></a></li>
					              </ul>
					            </div>
					          </div>
					          <!-- /.widget-user -->
					        </div>

					        <div class="col-md-4">
					          <!-- Widget: user widget style 1 -->
					          <div class="box box-widget widget-user">
					            <!-- Add the bg color to the header using any of the bg-* classes -->
					            <div class="widget-user-header bg-aqua">
					              <span class="info-box-number">Statistik Data Proyek </span>
					            </div>
					            <div class="box-footer no-padding">
					              <ul class="nav nav-stacked">
					                <li><a href="#">Berjalan <span class="pull-right badge bg-blue">31</span></a></li>
					                <li><a href="#">Selesai <span class="pull-right badge bg-aqua">5</span></a></li>
					              </ul>
					            </div>
					          </div>
					          <!-- /.widget-user -->
					        </div>

					        
						</div>
					</div>
				</div>
			</div>
		</div>			
	</section>
	<!-- /.content -->
</div>