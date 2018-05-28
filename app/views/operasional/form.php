<?php  ?>

<div class="content-wrapper">
  	<section class="content-header">
      	<h1>
        	General Form Elements
        	<small>Preview</small>
      	</h1>
      	<ol class="breadcrumb">
        	<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
	        <li><a href="#">Forms</a></li>
	        <li class="active">Operasional</li>
      	</ol>
    </section>
    <section class="content container-fluid">
  		<form id="form_proyek" role="form">
  			<!-- <input type="hidden" id="token_form" value="<?= $this->data['token_add']?>"> -->
  			
  			<!-- panel tambah data proyek -->
  			<div class="row">
      			<div class="col-lg-12 col-md-12 col-xs-12">    				
      				<div class="box box-info"> 
                        <div class="box-header">
                            <div class="box-title">Tambah Data</div>
                        </div>
                        <div class="box-body">
                        	<div class="row">
                        		<!-- panel 1 -->
                        		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                              <!-- ID Operasional-->
                              <div class="form-group field-id has-feedback">
                                        <label for="id">ID</label>
                                        <input type="text" class="form-control field" id="id" placeholder="">
                                        <span class="help-block small pesan pesan-id"></span>
                                    </div>

                        			<!-- ID Bank -->
                        			<div class="form-group field-id has-feedback">
                                        <label for="id">ID Bank</label>
                                       <select name="id_bank" id="id_bank" class="form-control field">
                                         <option value="BNI">BNI</option>
                                       </select>
                                        <span class="help-block small pesan pesan-id"></span>
                                    </div>

                        			<!-- Pemilik -->
                                   	<div class="form-group field-pemilik has-feedback">
                                        <label for="pemilik">Pemilik</label>
                                        <input type="text" class="form-control field" id="pemilik" placeholder="Masukan Pemilik Proyek">
                                        <span class="help-block small pesan pesan-pemilik"></span>
                                    </div>

                                    <!-- Tgl -->
                                    <div class="form-group field-tgl has-feedback">
                                      	<label for="tgl">Tanggal</label>
                                      	<div class="input-group date">
                                        	<div class="input-group-addon">
                                          		<i class="fa fa-calendar"></i>
                                        	</div>
                                          	<input type="text" name="tgl" class="form-control datepicker field" id="tgl" placeholder="yyyy-mm-dd">
                                      	</div>
                                      	<span class="help-block small pesan pesan-tgl"></span>
                                	</div>

                                	<!-- Input Pembangunan -->
                                    <div class="form-group field-pembangunan has-feedback">
                                      	<label for="pembangunan">Pembangunan</label>
                                      	<input type="text" class="form-control field" id="pembangunan" name="pembangunan" placeholder="Masukan Nama Pembangunan">
                                      	<span class="help-block small pesan pesan-pembangunan"></span>
                                    </div>


                                    <!-- Input Luas Area -->
                                    <div class="form-group field-luas_area has-feedback">
                                      	<label for="luas_area">Luas Area</label>
                                      	<div class="input-group">
                                      		<input type="number" class="form-control field" id="luas_area" name="luas_area" placeholder="Masukan Luas Area" min="0" step="any">
                                      		<span class="input-group-addon">M<sup>2</sup></span>
                                      	</div>
                                      	
                                      	<span class="help-block small pesan pesan-luas_area"></span>
                                    </div>  


                                    <!-- Input Alamat -->
                                    <div class="form-group field-alamat has-feedback">
                                      	<label for="alamat">Alamat</label>
                                      	<textarea class="form-control field" id="alamat" name="alamat" placeholder="Masukan Alamat"></textarea>
                                      	<span class="help-block small pesan pesan-alamat"></span>
                                    </div>
                        		</div>

                        		<!-- panel 2 -->
                        		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        			<!-- Input Kota -->
                                    <div class="form-group field-kota has-feedback">
                                      	<label for="kota">Kota</label>
                                     	<input type="text" class="form-control field" id="kota" name="kota" placeholder="Masukan Kota">
                                      	<span class="help-block small pesan pesan-kota"></span>
                                    </div>  

                                     <!-- Input Estimasi (Bulan) -->
                                    <div class="form-group field-kota has-feedback">
                                      <label for="estimasi">Estimasi (Bulan)</label>
                                         <input type="number" min="0" step="1" class="form-control field" id="estimasi" name="estimasi" placeholder="Masukan Estimasi Pengerjaan (Bulan)">
                                      <span class="help-block small pesan pesan-estimasi"></span>
                                    </div>

                                      <!-- Nilai RAB -->
                                    <div class="form-group field-total has-feedback">
                                      <label for="total">Total RAB</label>
                                      <div class="input-group">
                                      		<span class="input-group-addon">Rp.</span>
                                      		<input type="number" min="0" step="any" class="form-control field" id="total" name="total" placeholder="Masukan Total RAB">
                                      		<span class="input-group-addon">,00-</span>
                                      	</div>
                                         
                                      <span class="help-block small pesan pesan-total"></span>
                                    </div>

                                      <!-- Input DP-->
                                    <div class="form-group field-dp has-feedback">
                                      <label for="dp">DP</label>
                                       <div class="input-group">
                                      		<span class="input-group-addon">Rp.</span>
                                      		<input type="number" min="0" step="any" class="form-control field" id="dp" name="dp" placeholder="Masukkan DP">
                                      		<span class="input-group-addon">,00-</span>
                                      	</div>
                                         
                                      <span class="help-block small pesan pesan-dp"></span>
                                    </div>

                                      <!-- Input CCO-->
                                    <div class="form-group field-cco has-feedback">
                                      <label for="cco">CCO</label>
                                      <div class="input-group">
                                      		<span class="input-group-addon">Rp.</span>
                                      		<input type="number" min="0" step="any" class="form-control field" id="cco" name="cco" placeholder="Masukan Change Contract Order">
                                      		<span class="input-group-addon">,00-</span>
                                      	</div>
                                         
                                      <span class="help-block small pesan pesan-cco"></span>
                                    </div>

                                    <!-- Status -->
                        			<div class="form-group field-status has-feedback">
                                        <label for="status">Status Proyek</label>                     
                                        <select class="form-control field" id="status"></select>
                                        <span class="help-block small pesan pesan-status"></span>
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
                            <!-- <div class="box-title">Tambah Data</div> -->
                        </div>
                        <div class="box-body">
                        	<div class="row">
                        		<!-- panel 1 -->
                        		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        			<fieldset>
			                        	<legend style="font-size: 18px;">Detail Proyek</legend>
			                        	<div class="form-group pull-right">
	                                        <button type="button" class="btn btn-success btn-flat " id="tambah_detail"><i class="fa fa-plus"></i> Tambah Detail</button>
	                                    </div>
	                        			
	                        			<table class="table table-bordered table-hover small">
	                        				<thead>
	                        					<tr>
	                        						<th>No</th>
	                        						<th>Angsuran</th>
	                        						<th>Persentase</th>
	                        						<th>Total</th>
	                        						<th>Status</th>
	                        						<th>Aksi</th>
	                        					</tr>
	                        				</thead>
	                        			</table>
			                        </fieldset>
                        		</div>

                        		<!-- panel 2 -->
                        		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        			<!-- <div class="box-header">
			                            <div class="box-title">Tambah Data</div>
			                        </div> -->
			                        <fieldset>
			                        	<legend style="font-size: 18px;">Pemilihan Sub Kas Kecil</legend>
			                        	<div class="form-group">
	                        				 <div class="input-group">
								                <select class="form-control select2" name="">
		                        					<option>Jaka P</option>
		                        					<option>Jaka P</option>
	                        					</select>
								                    <span class="input-group-btn">
								                      	<button type="button" class="btn btn-info btn-flat"><i class="fa fa-plus"></i> </button>
								                    </span>
								             </div>
                        				</div>
                        				<table class="table table-bordered table-hover small">
	                        				<thead>
	                        					<tr>
	                        						<th>No</th>
	                        						<th>ID</th>
	                        						<th>Nama</th>
	                        						<th>Aksi</th>
	                        					</tr>
	                        				</thead>
	                        			</table>
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
                        		<div class="col-md-6 col-xs-6">
                        			<button class="btn btn-default btn-flat btn-lg" type="button">Kembali</button>
                           		</div>
                        		<div class="col-md-6 col-xs-6 ">
                        			<div class="btn-group pull-right">
	                        			<button class="btn btn-default btn-flat btn-lg" type="button">Reset</button>
	                        			<button class="btn btn-primary btn-flat btn-lg" id="submit_proyek" type="submit" value="<?= $this->data['action'];?>">Submit</button>
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

