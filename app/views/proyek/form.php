<?php 
	Defined("BASE_PATH") or die("Dilarang Mengakses File Secara Langsung");
	$form = ($this->data['action'] == 'action-add') ? 'Tambah Data' : 'Edit Data'; 
?>

<div class="content-wrapper">
  	<section class="content-header">
      	<h1>
        	<?= $this->propertyPage['main']; ?>
	    	<small><?= $this->propertyPage['sub']; ?></small>
      	</h1>
      	<ol class="breadcrumb">
        	<li><a href="<?= BASE_URL; ?>"><i class="fa fa-dashboard"></i> Home</a></li>
	        <li><a href="<?= BASE_URL.'proyek/'; ?>">Proyek</a></li>
	        <li class="active">Form Data Proyek</li>
      	</ol>
    </section>
    <section class="content container-fluid">
  		<form id="form_proyek" role="form">
  			<!-- panel tambah data proyek -->
  			<div class="row">
      			<div class="col-lg-12 col-md-12 col-xs-12">    				
      				<div class="box box-info"> 
                        <div class="box-header">
                            <div class="box-title"><?= $form; ?></div>
                         	<div class="box-tools pull-right">
                				<button type="button" class="btn btn-box-tool" data-widget="collapse">
                					<i class="fa fa-minus"></i>
                				</button>
          					</div>
                        </div>
                        <div class="box-body">
                        	<div class="row">
                        		<!-- panel 1 -->
                        		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        			<!-- ID -->
                        			<div class="form-group field-id has-feedback">
                                        <label for="id">ID Proyek</label>
                                        <input type="text" class="form-control field" id="id" placeholder="" value="<?= $this->data['id']; ?>">
                                        <span class="help-block small pesan pesan-id"></span>
                                    </div>

                        			<!-- Pemilik -->
                                   	<div class="form-group field-pemilik has-feedback">
                                        <label for="pemilik">Pemilik</label>
                                        <input type="text" class="form-control field" id="pemilik" placeholder="Masukan Pemilik Proyek" value="<?= $this->data['pemilik']; ?>">
                                        <span class="help-block small pesan pesan-pemilik"></span>
                                    </div>

                                    <!-- Tgl -->
                                    <div class="form-group field-tgl has-feedback">
                                      	<label for="tgl">Tanggal</label>
                                      	<div class="input-group date">
                                        	<div class="input-group-addon">
                                          		<i class="fa fa-calendar"></i>
                                        	</div>
                                          	<input type="text" name="tgl" class="form-control datepicker field" id="tgl" placeholder="yyyy-mm-dd" value="<?= $this->data['tgl']; ?>">
                                      	</div>
                                      	<span class="help-block small pesan pesan-tgl"></span>
                                	</div>

                                	<!-- Input Pembangunan -->
                                    <div class="form-group field-pembangunan has-feedback">
                                      	<label for="pembangunan">Pembangunan</label>
                                      	<input type="text" class="form-control field" id="pembangunan" name="pembangunan" placeholder="Masukan Nama Pembangunan" value="<?= $this->data['pembangunan']; ?>">
                                      	<span class="help-block small pesan pesan-pembangunan"></span>
                                    </div>

                                    <!-- Input Luas Area -->
                                    <div class="form-group field-luas_area has-feedback">
                                      	<label for="luas_area">Luas Area</label>
                                      	<div class="input-group">
                                      		<input type="number" class="form-control field" id="luas_area" name="luas_area" placeholder="Masukan Luas Area" min="0" step="any" value="<?= $this->data['luas_area']; ?>">
                                      		<span class="input-group-addon">M<sup>2</sup></span>
                                      	</div>                                      	
                                      	<span class="help-block small pesan pesan-luas_area"></span>
                                    </div>  

                                    <!-- Input Alamat -->
                                    <div class="form-group field-alamat has-feedback">
                                      	<label for="alamat">Alamat</label>
                                      	<textarea class="form-control field" id="alamat" name="alamat" placeholder="Masukan Alamat"><?= $this->data['alamat']; ?></textarea>
                                      	<span class="help-block small pesan pesan-alamat"></span>
                                    </div>
                        		</div>

                        		<!-- panel 2 -->
                        		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        			<!-- Input Kota -->
                                    <div class="form-group field-kota has-feedback">
                                      	<label for="kota">Kota</label>
                                     	<input type="text" class="form-control field" id="kota" name="kota" placeholder="Masukan Kota" value="<?= $this->data['kota']; ?>">
                                      	<span class="help-block small pesan pesan-kota"></span>
                                    </div>  

                                 	<!-- Input Estimasi (Bulan) -->
                                    <div class="form-group field-estimasi has-feedback">
                                      	<label for="estimasi">Estimasi (Bulan)</label>
                                     	<input type="number" min="0" step="1" class="form-control field" id="estimasi" name="estimasi" placeholder="Masukan Estimasi Pengerjaan (Bulan)" value="<?= $this->data['estimasi']; ?>">
                                      	<span class="help-block small pesan pesan-estimasi"></span>
                                    </div>

                                  	<!-- Nilai RAB -->
                                    <div class="form-group field-total has-feedback">
                                      	<label for="total">Total RAB</label>
                                      	<div class="input-group">
                                      		<span class="input-group-addon">Rp.</span>
                                      		<input type="text" class="form-control field input-mask-uang" id="total" name="total" placeholder="Masukan Total RAB" value="<?= $this->data['total']; ?>">
                                      		<span class="input-group-addon">,00-</span>
                                      	</div>
                                      	<span class="help-block small pesan pesan-total"></span>
                                    </div>

                                  	<!-- Input DP-->
                                    <div class="form-group field-dp has-feedback">
                                      	<label for="dp">DP</label>
                                       	<div class="input-group">
                                      		<span class="input-group-addon">Rp.</span>
                                      		<input type="text" class="form-control field input-mask-uang" id="dp" name="dp" placeholder="Masukkan DP" value="<?= $this->data['dp']; ?>">
                                      		<span class="input-group-addon">,00-</span>
                                      	</div>
                                 	 	<span class="help-block small pesan pesan-dp"></span>
                                    </div>

                                  	<!-- Input CCO-->
                                    <div class="form-group field-cco has-feedback">
                                      	<label for="cco">CCO</label>
                                      	<div class="input-group">
                                      		<span class="input-group-addon">Rp.</span>
                                      		<input type="text" class="form-control field input-mask-uang" id="cco" name="cco" placeholder="Masukan Change Contract Order" value="<?= $this->data['cco']; ?>">
                                      		<span class="input-group-addon">,00-</span>
                                      	</div>
                                      	<span class="help-block small pesan pesan-cco"></span>
                                    </div>

                                    <!-- Status -->
                        			<div class="form-group field-status has-feedback">
                                        <label for="status">Status Proyek</label>           
                                        <select class="form-control field select2" id="status"></select>
                                        <span class="help-block small pesan pesan-status"></span>
                                    </div>

                                    <!-- Progress -->
                        			<div class="form-group field-progress has-feedback margin">
                        				<label for="status">Progress Proyek</label>
                        				<input id="progress" type="text" class="slider form-control field" data-slider-value="<?= $this->data['progress']; ?>" data-slider-min="0" data-slider-max="100" data-slider-id="blue">
                        				<span class="help-block small pesan pesan-progress"></span>
                        			</div>
                        		</div>
                        	</div>
                        </div>
                        <div class="clearfix"></div>
                    </div>
      			</div>
      		</div>
            
            <!-- Panel Detail dan sub kas kecil -->
            <div class="row">
      			<div class="col-lg-12 col-md-12 col-xs-12">    				
      				<div class="box box-info"> 
                        <div class="box-header">
                        	<div class="box-title">Detail Proyek</div>
                            <div class="box-tools pull-right">
                				<button type="button" class="btn btn-box-tool" data-widget="collapse">
                					<i class="fa fa-minus"></i>
                				</button>
          					</div>
                        </div>
                        <div class="box-body">
						<div class="row">
                        		<!-- panel 1 -->
                        		<div class="col-md-12">
		                        	<fieldset>
			                        	<legend style="font-size: 14px; font-weight: 700;">Pemilihan Sub Kas Kecil</legend>
			                        	<div class="row">
			                        		<div class="col-md-6">
			                        			<div class="form-group field-skk has-feedback">
			                        				<div class="input-group">
										                <select class="form-control select2" style="width: 100%;" id="skk">
			                        					</select>
									                    <span class="input-group-btn">
									                      	<button type="button" class="btn btn-info btn-flat" id="tambah_skk"><i class="fa fa-plus"></i> </button>
									                    </span>
										             </div>
					                        	
		                        				</div>
			                        		</div>
			                        	</div>
					                        	
                        				<table id="sub_kas_kecilTable" class="table table-bordered table-hover small">
	                        				<thead>
	                        					<tr>
	                        						<th class="text-right" style="width: 15px">No</th>
	                        						<th>ID</th>
	                        						<th>Nama</th>
	                        						<th>Aksi</th>
	                        					</tr>
	                        				</thead>
	                        				<tbody></tbody>
	                        			</table>
			                        </fieldset>
                        		</div>
                        	</div>
							<div class="row">
                        		<!-- panel 2 -->
                        		<div class="col-md-12">
                        			<fieldset>
									<legend style="font-size: 14px; font-weight: 700;">Pembayaran Proyek</legend>
			                        	<div class="form-group">
	                                        <button type="button" class="btn btn-success btn-flat " id="tambah_detail"><i class="fa fa-plus"></i> Tambah Detail Pembayaran</button>
	                                    </div>
	                                    <div class="row">
	                                    	<div class="col-md-12">
	                                    		<div class="table-responsive">
			                        				<table id="detail_proyekTable" class="table table-bordered table-hover small">
				                        				<thead>
				                        					<tr>
				                        						<th class="text-right" style="width: 15px">No</th>
																<th>Tanggal</th>
				                        						<th>Pembayaran</th>
																<th>Bank</th>
				                        						<th class="text-right">Total</th>
				                        						<th>Aksi</th>
				                        					</tr>
				                        				</thead>
				                        				<tbody></tbody>
				                        			</table>
			                        			</div>
	                                    	</div>	
	                                    </div>	
			                        </fieldset>
                        		</div>

                        	</div>
                        </div>
                        <div class="clearfix"></div>
                    </div>
      			</div>
      		</div>

  		 	<div class="row">
      			<div class="col-lg-12 col-md-12 col-xs-12">    				
      				<div class="box box-info"> 
                        <div class="box-body">
                        	<div class="row">
                        		<div class="col-md-6 col-xs-12">
                        			<a href="<?= BASE_URL.'proyek/'; ?>" class="btn btn-default btn-flat btn-lg" role="button">Kembali</a>
                           		</div>
                        		<div class="col-md-6 col-xs-12">
                        			<div class="btn-group pull-right">
	                        			<button class="btn btn-default btn-flat btn-lg" id="btn_reset" type="button">Reset</button>
	                        			<button class="btn btn-primary btn-flat btn-lg" id="submit_proyek" 
	                        			type="submit" value="<?= $this->data['action'];?>">Submit</button>
                        			</div>	
                        		</div>
                        	</div>
                        </div>
                        <div class="clearfix"></div>
                    </div>
      			</div>
      		</div>
      	</form>
	</section>
</div>

<?php include_once('form_detail_pembayaran.php'); ?>
<script type="text/javascript">
	var listDetail = [];
	var indexDetail = 0;

	var listSkk = [];
	var indexSkk = 0;

	var checkDP = false;

	var statusProyek = "<?= $this->data['status']; ?>";
</script>