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
	        <li class="active">Kas Kecil</li>
      	</ol>
    </section>
    <section class="content container-fluid">
  		<form id="form_kas_kecil" role="form">
  			<input type="hidden" id="token_form" value="<?= $this->data['token_add']?>">
  			
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
                        			<!-- ID -->
                        			<div class="form-group field-id has-feedback">
                                        <label for="id">ID Kas Kecil</label>
                                        <input type="text" class="form-control field" id="id" placeholder="">
                                        <span class="help-block small pesan pesan-id"></span>
                                    </div>

                        			<!-- Nama -->
                                   	<div class="form-group field-nama has-feedback">
                                        <label for="nama">Nama</label>
                                        <input type="text" class="form-control field" id="nama" placeholder="Masukan Nama Kas Kecil">
                                        <span class="help-block small pesan pesan-nama"></span>
                                    </div>

                                    <!-- Alamat -->
                                    <div class="form-group field-alamat has-feedback">
                                      	<label for="alamat">Alamat</label>
                                      	 <textarea name="alamat" class="form-control" placeholder="Masukan Alamat"></textarea>
                                      	<span class="help-block small pesan pesan-alamat"></span>
                                	</div>

                                	<!-- No Telp -->
                                    <div class="form-group field-no_telp has-feedback">
                                      	<label for="no_telp">Nomor Telepon</label>
                                      	<input type="text" class="form-control field" id="no_telp" name="no_telp" placeholder="Masukan Nomor Telepon">
                                      	<span class="help-block small pesan pesan-no_telp"></span>
                                    </div>


                                    


                                   
                        		</div>

                        		<!-- panel 2 -->
                        		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                              <!-- Input Email -->
                                    <div class="form-group field-email has-feedback">
                                        <label for="email">Email</label>
                                        
                                          <input type="text" class="form-control field" id="email" name="email" placeholder="Masukan Email">
                                        <span class="help-block small pesan pesan-email"></span>
                                    </div>  

                               <!-- Input Foto -->
                                    <div class="form-group field-foto has-feedback">
                                        <label for="foto">Foto</label>
                                        <input type="file" name="foto" class="form-control field">
                                        <span class="help-block small pesan pesan-foto"></span>
                                    </div>

                        			<!-- Input Saldo -->
                                    <div class="form-group field-saldo has-feedback">
                                      	<label for="saldo">Saldo</label>
                                     	<input type="text" class="form-control field" id="saldo" name="saldo" placeholder="Masukan Saldo">
                                      	<span class="help-block small pesan pesan-saldo"></span>
                                    </div>  

                                     <!-- Input Status Kas Kecil -->
                                    <div class="form-group field-status has-feedback">
                                      <label for="status">Status</label>
                                         <select name="status" class="form-control field" id="status">
                                           <option value="AKTIF">AKTIF</option>
                                           <option value="NONAKTIF">NONAKTIF</option>
                                         </select>
                                      <span class="help-block small pesan pesan-status"></span>
                                    </div>
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
	                        			<button class="btn btn-primary btn-flat btn-lg" id="submit_kas_kecil" type="submit" value="<?= $this->data['action'];?>">Submit</button>
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

