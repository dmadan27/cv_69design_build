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
	    	<li><a href="<?= BASE_URL ?>"><i class="fa fa-dashboard"></i> Home</a></li>
	    	<li><a href="#"><i class="fa fa-dashboard"></i> Saldo Kas Kecil</a></li>
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
							<div class="col-md-12 col-sm-12 col-xs-12 text-right">
								&nbsp;
							</div>
						</div>
						<!-- panel button -->
						<div class="row">
							<div class="col-md-9 col-sm-9 col-xs-9">
								<div class="btn-group">
									<!-- tambah -->
									<!-- <a href="<?= BASE_URL."" ?>" class="btn btn-default btn-flat" role="button"><i class="fa fa-plus"></i> Tambah</a> -->
									<!-- export -->
									<button type="button" class="btn btn-success btn-flat" id="exportExcel"><i class="fa fa-file-excel-o"></i> Export Excel</button>
								</div>
							</div>
							<div class="col-md-3 col-sm-3 col-xs-3">
								<h3 class="box-title" style="margin-top:10px;"><strong>Saldo:  <?php echo $this->data['saldo']; ?> ,-</strong></h3>
							</div>
						</div>
					</div>
					<!-- box body -->
					<div class="box-body">
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<table id="mutasi_saldo_kas_kecilTable" class="table table-bordered table-hover">
									<thead>
										<tr>
											<th>No</th>
											<th>Tanggal</th>
											<th>Uang Masuk</th>
											<th>Uang Keluar</th>
											<th>Saldo</th>
											<th>Ket</th>
										</tr>
									</thead>
									<tbody></tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>			
	</section>
	<!-- /.content -->
</div>

<div class="modal fade" id="modalTanggalExport">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <!-- header modal -->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">Export Data Mutasi Saldo Kas Kecil ke Excel</h4>
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
                    <button type="button" id="submit_export" onclick="export_excel()" class="btn btn-primary" value="tambah">Export Detail</button>
                </div>
            </form>

        </div>
    </div>
</div>