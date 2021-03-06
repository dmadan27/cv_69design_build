<?php Defined("BASE_PATH") or die("Dilarang Mengakses File Secara Langsung"); ?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
	  	<h1>
	    	<?= $this->propertyPage['main']; ?>
	    	<small><?= $this->propertyPage['sub']; ?></small>
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
              <h3><?= $this->data['pending_pkk']; ?></h3>

              <h5>PENGAJUAN KAS KECIL PENDING</h5>
            </div>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-green">
            <div class="inner text-center">
              <h3><?= $this->data['acc_pkk']; ?></h3>

              <h5>PENGAJUAN KAS KECIL DISETUJUI</h5>
            </div>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-yellow">
            <div class="inner text-center">
              <h3><?= $this->data['jml_transaksi_masuk']; ?></h3>

              <h5>JML OPERASIONAL(UANG MASUK)</h5>
            </div>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-red">
            <div class="inner text-center">
            <h3><?= $this->data['jml_transaksi_keluar']; ?></h3>

              <h5>JML OPERASIONAL(UANG KELUAR)</h5>
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
              <h3 class="box-title">List Proyek Yang Sedang Berjalan</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <!-- <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button> -->
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
						<div class="table-responsive">
                <table id="listProyek" class="table table-bordered table-hover no-margin">
                  <thead>
                  <tr>
                    <th width="150px">ID Proyek</th>
                    <th class="text-right">Total Pemasukan</th>
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
              <h3 class="box-title">List Bank</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <!-- <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button> -->
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <div class="table-responsive">
                <table id="listBank" class="table table-bordered table-hover">
                  <thead>
                  <tr>
                    <th width="150px">Nama Bank</th>
                    <th class="text-right"> Jumlah Saldo</th>
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
		</div>


    <div class="row">
      <div class="col-xs-12 col-lg-12 col-md-12 col-sm-12">
         <!-- TABLE: LATEST ORDERS -->
         <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Kontra Bon Operasional Proyek</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <!-- <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button> -->
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
            <div class="table-responsive">
                <table id="listSisaKredit" class="table table-bordered table-hover no-margin">
                  <thead>
                  <tr>
                    <th width="150px">Nama Distributor</th>
                    <th class="text-right">Sisa Kredit</th>
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
    </div>

		<div class="row">
			<!-- <div class="col-xs-12 col-lg-6 col-md-6 col-sm-6"> -->
			<!-- <div class="box box-solid">
            <div class="box-header with-border">
              <h3 class="box-title">Collapsible Accordion</h3>
            </div>
            
            <div class="box-body">
              <div class="box-group">
                
                <div class="panel box box-primary"  id="accordion1">
                  <div class="box-header with-border">
                    <h4 class="box-title">
                      <a data-toggle="collapse" data-parent="#accordion1" href="#collapseOne">
                        Collapsible Group Item #1
                      </a>
                    </h4>
                  </div>
                  <div id="collapseOne" class="panel-collapse collapse in">
                    <div class="box-body">
                      Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3
                      wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum
                      eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla
                      assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred
                      nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer
                      farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus
                      labore sustainable VHS.
                    </div>
                  </div>
                </div>
                <div class="panel box box-danger"  id="accordion2">
                  <div class="box-header with-border">
                    <h4 class="box-title">
                      <a data-toggle="collapse" data-parent="#accordion2" href="#collapseTwo">
                        Collapsible Group Danger
                      </a>
                    </h4>
                  </div>
                  <div id="collapseTwo" class="panel-collapse collapse">
                    <div class="box-body">
                      Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3
                      wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum
                      eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla
                      assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred
                      nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer
                      farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus
                      labore sustainable VHS.
                    </div>
                  </div>
                </div>
                <div class="panel box box-success" id="accordion3">
                  <div class="box-header with-border">
                    <h4 class="box-title">
                      <a data-toggle="collapse" data-parent="#accordion3" href="#collapseThree">
                        Collapsible Group Success
                      </a>
                    </h4>
                  </div>
                  <div id="collapseThree" class="panel-collapse collapse">
                    <div class="box-body">
                      Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3
                      wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum
                      eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla
                      assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred
                      nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer
                      farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus
                      labore sustainable VHS.
                    </div>
                  </div>
                </div>
              </div>
            </div>
            
          </div> -->
          
			<!-- </div> -->
			<div class="col-xs-12 col-lg-12 col-md-12 col-sm-12">
				<!-- <div class="row"> -->
					<div class="col-xs-12 col-lg-3 col-md-3 col-sm-3">
						<div class="small-box bg-aqua">
							<div class="inner text-center">
              <h3><?= $this->data['user_aktif']; ?></h3>

              <h5>JML TOTAL USER AKTIF</h5>
							</div>
						</div>
					</div>
					<div class="col-xs-12 col-lg-3 col-md-3 col-sm-3">
						<div class="small-box bg-red">
							<div class="inner text-center">
              <h3><?= $this->data['pskk']; ?></h3>

              <h5>JML PENGAJUAN SUB KAS KECIL</h5>
							</div>
						</div>
					</div>
					<div class="col-xs-12 col-lg-3 col-md-3 col-sm-3">
						<div class="small-box bg-green">
							<div class="inner text-center">
              <h3><?= $this->data['jml_transaksi_kredit']; ?></h3>

              <h5>JML OPERASIONAL PROYEK KREDIT</h5>
							</div>
						</div>
					</div>
					<div class="col-xs-12 col-lg-3 col-md-3 col-sm-3">
						<div class="small-box bg-blue">
							<div class="inner text-center">
              <h3><?= $this->data['jml_transaksi_tunai']; ?></h3>

              <h5>JML OPERASIONAL PROYEK TUNAI</h5>
							</div>
						</div>
					</div>
				<!-- </div> -->
			</div>
		</div>

	</section>
	<!-- /.content -->
</div>