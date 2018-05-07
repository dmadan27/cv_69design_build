<?php Defined("BASE_PATH") or die("Dilarang Mengakses File Secara Langsung"); ?>


  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        General Form Elements
        <small>Preview</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Forms</a></li>
        <li class="active">General Elements</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <!-- left column -->
        <div class="col-md-6">
          <!-- general form elements -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Data Proyek</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form role="form" id="form_proyek">
              <div class="box-body">

              	<!-- Input Pemilik -->
                <div class="form-group field-pemilik">
                  <label for="pemilik">Pemilik</label>
                  <input type="text" class="form-control field" id="pemilik" placeholder="Pemilik Proyek">
                  <span class="help-block small pesan pesan-pemilik"></span>
                </div>


                <div class="form-group field-tanggal">
                  <label for="tanggal">Tanggal</label>
                  <div class="input-group date">
                  	<div class="input-group-addon">
                    	<i class="fa fa-calendar"></i>
                  	</div>
                  		<input type="text"  name="tanggal" class="form-control pull-right field" id="datepicker">
                  		 <span class="help-block small pesan pesan-tanggal"></span>
                  </div>


                </div>


                <!-- Input Pembangunan -->
                <div class="form-group field-pembangunan">
                  <label for="pembangunan">Pembangunan</label>
                  <input type="text" class="form-control field" id="pembangunan" name="pembangunan" placeholder="Nama Pembangunan">
                  <span class="help-block small pesan pesan-pembangunan"></span>
                </div>


                <!-- Input Luas Area -->
                <div class="form-group field-luas_area">
                  <label for="luas_area">Luas Area</label>
                  <input type="text" class="form-control field" id="luas_area" name="luas_area" placeholder="Luas Area">
                  <span class="help-block small pesan pesan-luas_area"></span>
                </div>


                 <!-- Input Alamat -->
                <div class="form-group field-alamat">
                  <label for="alamat">Alamat</label>
                  <textarea class="form-control field" id="alamat" name="alamat" placeholder="Alamat"></textarea>
                  <span class="help-block small pesan pesan-alamat"></span>
                </div>


              </div>
              <!-- /.box-body -->     
             
          </div>
      </div>


<!-- Kolom Kanan -->
      <div class="col-md-6">
          <!-- general form elements -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Data Proyek Lanjutan</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
           
              <div class="box-body">

              	<!-- Input Kota -->
                <div class="form-group field-kota">
                  <label for="kota">Kota</label>
                  	 <input type="text" class="form-control field" id="kota" name="kota" placeholder="Kota">
                  <span class="help-block small pesan pesan-kota"></span>
                </div>


                  <!-- Input Estimasi (Bulan) -->
                <div class="form-group field-kota">
                  <label for="estimasi">Estimasi (Bulan)</label>
                  	 <input type="text" class="form-control field" id="estimasi" name="estimasi" placeholder="Estimasi Pengerjaan (Bulan)">
                  <span class="help-block small pesan pesan-estimasi"></span>
                </div>

                  <!-- Nilai RAB -->
                <div class="form-group field-total">
                  <label for="nilai-total">Total RAB</label>
                  	 <input type="text" class="form-control field" id="total" name="total" placeholder="Total RAB">
                  <span class="help-block small pesan pesan-total"></span>
                </div>

                  <!-- Input DP-->
                <div class="form-group field-dp">
                  <label for="dp">DP</label>
                  	 <input type="text" class="form-control field" id="dp" name="dp" placeholder="DP">
                  <span class="help-block small pesan pesan-dp"></span>
                </div>

                  <!-- Input CCO-->
                <div class="form-group field-cco">
                  <label for="cco">CCO</label>
                  	 <input type="text" class="form-control field" id="cco" name="cco" placeholder="Change Contract Order">
                  <span class="help-block small pesan pesan-cco"></span>
                </div>


               
              </div>
              <!-- /.box-body -->     
              <div class="box-footer">
                <button type="submit" class="btn btn-primary">Tambah</button>
                <button type="reset" class="btn btn">Reset</button>
              </div>

            </form>

          </div>
          <!-- /.box -->
    	</section>
    	<!-- /.content -->
    </div>
  <!-- /.content-wrapper -->