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
			<!-- Small boxes (Stat box) -->
			<div class="row">
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-aqua">
            <div class="inner text-center">
              <h3><?= $this->data['sum_acc_spkk']; ?></h3>

              <p>DANA SKK DISETUJUI</p>
            </div>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-green">
            <div class="inner text-center">
              <h3><?= $this->data['pending_spkk']; ?></h3>

              <p>PENGAJUAN SKK PENDING</p>
            </div>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-yellow">
            <div class="inner text-center">
            <h3><?= $this->data['sum_pending_spkk']; ?></h3>

            <p>DANA SKK PENDING</p>
            </div>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-red">
            <div class="inner text-center">
            <h3><?= $this->data['jml_transaksi_pkk']; ?></h3>

            <p>TRANSAKSI PENGAJUAN KAS KECIL</p>
            </div>
          </div>
        </div>
        <!-- ./col -->
      </div>
      <!-- /.row -->
		<div class="row">
			<div class="col-xs-12 col-lg-6 col-md-6 col-sm-6">
				 <!-- TABLE: LATEST ORDERS -->
				 <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">List Sub Kas Kecil </h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <!-- <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button> -->
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
						<div class="table-responsive">
                <table id="listSKK" class="table table-bordered table-hover">
                  <thead>
                  <tr>
                    <th>ID</th>
                    <th>Nama</th>
                    <th>Saldo</th>
                  </tr>
                  </thead>
                  <tbody></tbody>
                </table>
              </div>
              <!-- /.table-responsive -->
            </div>
            <!-- /.box-body -->
            <div class="box-footer clearfix">
              <a href="javascript:void(0)" class="btn btn-sm btn-default btn-flat pull-right">More Info</a>
            </div>
            <!-- /.box-footer -->
          </div>
          <!-- /.box -->
			</div>
			<div class="col-xs-12 col-lg-6 col-md-6 col-sm-6">
				 <!-- TABLE: LATEST ORDERS -->
				 <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">List Laporan Pengajuan Sub Kas Kecil Pending</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <!-- <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button> -->
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <div class="table-responsive">
              <table id="listLaporan" class="table table-bordered table-hover">
                  <thead>
                  <tr>
                    <th>ID</th>
                    <th>Total</th>
                  </tr>
                  </thead>
                  <tbody></tbody>
                </table>
              </div>
              <!-- /.table-responsive -->
            </div>
            <!-- /.box-body -->
            <div class="box-footer clearfix">
              <a href="javascript:void(0)" class="btn btn-sm btn-default btn-flat pull-right">More Info</a>
            </div>
            <!-- /.box-footer -->
          </div>
          <!-- /.box -->
			</div>	
	</section>
	<!-- /.content -->
</div>