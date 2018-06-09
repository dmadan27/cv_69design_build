<?php 
  Defined("BASE_PATH") or die("Dilarang Mengakses File Secara Langsung");
  $form = ($this->data['action'] == 'action-add') ? 'Tambah Data' : 'Edit Data'; 
?>

<div class="content-wrapper">
    <section class="content-header">
        <h1>
          <?= $this->title['main']; ?>
        <small><?= $this->title['sub']; ?></small>
        </h1>
        <ol class="breadcrumb">
          <li><a href="<?= BASE_URL; ?>"><i class="fa fa-dashboard"></i> Home</a></li>
          <li><a href="<?= BASE_URL.'operasional-proyek/'; ?>">Proyek</a></li>
          <li class="active">Form Data Operasional Proyek</li>
        </ol>
    </section>
    <section class="content container-fluid">
      <form id="form_operasional_proyek" role="form">
        <input type="hidden" id="token_form" value="<?= $this->data['token_form']?>">
        
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
                            <div class="col-md-12 col-sm-6 col-xs-12">
                              <!-- ID -->
                              <div class="form-group field-id has-feedback">
                                        <label for="id">ID Operasional Proyek</label>
                                        <input type="text" class="form-control field" id="id" placeholder="" value="<?= $this->data['id']; ?>">
                                        <span class="help-block small pesan pesan-id"></span>
                                    </div>

                              <!-- ID Proyek -->
                                    <div class="form-group field-id_proyek has-feedback">
                                        <label for="id_proyek">ID Proyek</label>
                                        <select class="form-control select2 field" name="id_proyek" id="id_proyek"></select>
                                        <span class="help-block small pesan pesan-id_proyek"></span>
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

                                  <!-- Input Nama Pengajuan -->
                                    <div class="form-group field-nama has-feedback">
                                        <label for="nama">Nama</label>
                                        <input type="text" class="form-control field" id="nama" name="nama" placeholder="Masukan Nama Kebutuhan Operasional" value="<?= $this->data['nama']; ?>">
                                        <span class="help-block small pesan pesan-nama"></span>
                                    </div>


                                    <!-- Input Total -->
                                    <div class="form-group field-total has-feedback">
                                        <label for="total">Total</label>
                                        <div class="input-group" style="width: 100%">
                                          <input type="number" class="form-control field" id="total" name="total" placeholder="Masukan Total" min="0" step="any" value="<?= $this->data['total']; ?>">
                                        </div>
                                        <span class="help-block small pesan pesan-total"></span>
                                    </div>  
                            </div>

                            <!-- panel 2 -->
                          <!--   <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                              <!-- Input Kota -->
                           <!-- </div> -->
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
                                <div class="form-group">
                                          <button type="button" class="btn btn-success btn-flat " id="tambah_detail"><i class="fa fa-plus"></i> Tambah Detail</button>
                                      </div>
                                      <div class="row">
                                        <div class="col-md-12">
                                          <div class="table-responsive">
                                      <table id="detail_proyekTable" class="table table-bordered table-hover small">
                                        <thead>
                                          <tr>
                                            <th class="text-right" style="width: 15px">No</th>
                                            <th>Angsuran</th>
                                            <th class="text-center" style="width: 150px;">%</th>
                                            <th class="text-right">Total</th>
                                            <th>Status</th>
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
                          <div class="row">
                            <!-- panel 2 -->
                            <div class="col-md-12">
                              <fieldset>
                                <legend style="font-size: 18px;">Pemilihan Sub Kas Kecil</legend>
                                <div class="row">
                                  <div class="col-md-6">
                                    <div class="form-group field-skc has-feedback">
                                      <div class="input-group">
                                    <select class="form-control select2" style="width: 100%;" id="skc">
                                        </select>
                                      <span class="input-group-btn">
                                          <button type="button" class="btn btn-info btn-flat" id="tambah_skc"><i class="fa fa-plus"></i> </button>
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
                              <button class="btn btn-default btn-flat btn-lg" type="button">Kembali</button>
                              </div>
                            <div class="col-md-6 col-xs-12">
                              <div class="btn-group pull-right">
                                <button class="btn btn-default btn-flat btn-lg" type="button">Reset</button>
                                <button class="btn btn-primary btn-flat btn-lg" id="submit_operasional_proyek" 
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

