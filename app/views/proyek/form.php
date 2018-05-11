<?php Defined("BASE_PATH") or die("Dilarang Mengakses File Secara Langsung"); ?>

<div class="content-wrapper">
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
  
              <section class="content">
                <div class="row box box-primary">
                      <div class="box">
                        <div class="box-header">
                            <label>Tambah Data Proyek</label>
                        </div>
                          <form action="" method="" enctype="">
                          
                                        <div class="col-md-6">
                                           <div class="form-group">
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

                                            <div class="clearfix">
                                              
                                            </div>

                                        </div>

                                        <div class="col-md-6">
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

                                            <div class="form-group">
                                              <button type="submit" name="" id="" class="btn btn-default pull-left">Reset</button>
                                             <button type="submit" name="" id="" class="btn btn-primary">Tambah</button>
                                            </div>

                          </form>  
                                              
                                            
                                        </div>

                                                      
                      </div>
                </div>
              </section>


              <section class="content">
                <div class="row box box-primary">
                      <div class="box">
                        <div class="box-header">
                            <label>Detail Proyek</label>
                        </div>
                          <form action="" method="" enctype="">
                          
                                        <div class="col-md-6">
                                           <div class="form-group field-id-proyek">
                                                <label for="pemilik">ID Proyek</label>
                                                <select name="" class="form-control field" id="id_proyek">
                                                  
                                                  <option value="">A</option>
                                                  <option value="">A</option>
                                                  <option value="">A</option>
                                                  
                                                </select>
                                                <span class="help-block small pesan pesan-id-proyek"></span>

                                            </div>

                                            <div class="form-group field-angsuran">
                                                <label for="angsuran">Angsuran</label>
                                                <input type="text" class="form-control field" id="angsuran" placeholder="Jumlah Angsuran">
                                                <span class="help-block small pesan pesan-angsuran"></span>

                                            </div>

                                            <!-- Input Pembangunan -->
                                            <div class="form-group field-persentase">
                                              <label for="persentase">Persentase</label>
                                              <input type="text" class="form-control field" id="persentase" name="persentase" placeholder="Persentase Pembangunan">
                                              <span class="help-block small pesan pesan-persentase"></span>
                                            </div>


                                            <!-- Input Luas Area -->
                                            <div class="form-group" field-total">
                                              <label for="total">Total</label>
                                              <input type="text" class="form-control field" id="total" name="total" placeholder="Total">
                                              <span class="help-block small pesan pesan-total"></span>
                                            </div>

                                             <div class="form-group">
                                              <button type="submit" name="" id="" class="btn btn-default pull-left">Reset</button>
                                             <button type="submit" name="" id="" class="btn btn-primary">Tambah</button>
                                            </div>
  


                                         

                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group"><label>Pemilihan Sub Kas Kecil</label></div>
                                            <!-- Input Kota -->
                                            <div class="form-group field-id_sub_kas_kecil">
                                              <label for="id_sub_kas_kecil">Sub Kas Kecil</label>
                                                 <select name="id_sub_kas_kecil" class="form-control field" id="id_sub_kas_kecil">
                                                    <option>SKK-001 Jaka</option>
                                                    <option>SKK-002 Fajar</option>
                                                   
                                                 </select>
                                              <span class="help-block small pesan pesan-kota"></span>
                                            </div>  

                                             <div class="form-group">
                                              <button type="submit" name="" id="" class="btn btn-default pull-left">Reset</button>
                                             <button type="submit" name="" id="" class="btn btn-primary">Tambah</button>
                                            </div>
  

                                            
                                        </div>



                          </form>                
                      </div>
                </div>
              </section>

              


</div>
