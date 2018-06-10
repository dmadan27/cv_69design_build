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
        
        <!-- panel tambah data operasional proyek -->
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
                                        <select class="form-control select2" name="id_proyek" id="id_proyek"></select>
                                        <span class="help-block small pesan pesan-id_proyek"></span>
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

                                  <!-- Input Nama Pengajuan -->
                                    <div class="form-group field-nama has-feedback">
                                        <label for="nama">Nama</label>
                                        <input type="text" class="form-control field" id="nama" name="nama" placeholder="Masukan Nama Kebutuhan Operasional" >
                                        <span class="help-block small pesan pesan-nama"></span>
                                    </div>


                                    <!-- Input Total -->
                                    <div class="form-group field-total has-feedback">
                                        <label for="total">Total</label>
                                        <div class="input-group" style="width: 100%">
                                         <div class="input-group">
                                                <span class="input-group-addon">Rp.</span>
                                                <input type="text" class="form-control field" id="sub_total_detail" name="subtotal" placeholder="Masukan Subtotal" >
                                                <span class="input-group-addon">,00-</span>
                                          </div>
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
                          <div class="box-title">Detail Operasional Proyek</div>
                            <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse">
                          <i class="fa fa-minus"></i>
                        </button>
                    </div>
                        </div>
                        <div class="box-body">
                   
                            

                          <!-- Tabel Detail Operasional Proyek -->
                          <div class="row">
                            <!-- panel 1 -->
                            <div class="col-md-12">
                              <fieldset>
                                <div class="form-group">
                                          <button type="button" class="btn btn-success btn-flat " id="btn_tambahDetail"><i class="fa fa-plus"></i> Tambah Detail</button>
                                      </div>
                                      <div class="row">
                                        <div class="col-md-12">
                                          <div class="table-responsive">
                                      <table id="detail_OperasionalproyekTable" class="table table-bordered table-hover small">
                                        <thead>
                                          <tr>
                                            <th class="" style="width: 15px">No</th>
                                            <th>Nama</th>
                                            <th class="" style="width: 150px;">Jenis</th>
                                            <th class="">Satuan</th>
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

                            <!-- MODAL DETAIL OPERASIONAL  -->
                            <div class="modal fade" id="modalDetailOperasionalKaskecil">
                              <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                  <div class="modal-header">
                                  	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                  		<span aria-hidden="true">&times;</span>
                                  	</button>
                                  	<h4 class="modal-title">Form Tambah Data Operasional Proyek
                                  	</h4>
                                  </div>
                                  <div class="modal-body">
                                  	<form id="form_operasional_proyek" role="form">
                                  		<div class="row">
												            <div class="col-lg-12 col-md-12 col-xs-12">           
												              <div class="box box-info"> 
												                        <div class="box-header">
												                          <div class="box-title">Detail Operasional Proyek</div>
												                            <div class="box-tools pull-right">
												                        
												                    </div>
										                        </div>
										                        <div class="box-body">
										                          <div class="row">
                            <!-- panel 1  (Kiri) Detail Operasional Proyek-->
                            <div class="col-md-6">
                              <fieldset>
                                      <div class="row">
                                        <div class="col-md-12">
                                            <!-- Nama -->
                                            <div class="form-group field-nama_detail has-feedback">
                                                <label for="nama_detail">Nama</label>
                                                <input type="text" class="form-control field" id="nama_detail" name="nama" placeholder="" >
                                                <span class="help-block small pesan pesan-nama_detail"></span>
                                            </div>

                                            <!-- Jenis -->
                                            <div class="form-group field-jenis_detail has-feedback">
                                                <label for="jenis_detail">Jenis</label>
                                                <div class="form-group">
                                                	<input type="radio" class="form-control flat-red field" name="jenis" id="jenis_detail">
                                                <input type="radio" class="form-control flat-red field" name="jenis" id="jenis_detail">

                                                </div>
                                                
                                                
                                                <span class="help-block small pesan pesan-jenis_detail"></span>
                                            </div>

                                            <!-- Satuan -->
                                            <div class="form-group field-satuan_detail has-feedback">
                                                <label for="nama_detail">Satuan</label>
                                                <input type="text" class="form-control field" name="satuan" id="satuan_detail" placeholder="Masukan Satuan" >
                                                <span class="help-block small pesan pesan-satuan_detail"></span>
                                            </div>

                                          <!-- Input qty -->
                                            <div class="form-group field-qty_detail has-feedback">
                                                <label for="qty_detail">Kuantiti</label>
                                                <div class="input-group" style="width: 100%">
			                                      		<input type="number" class="form-control field" id="qty_detail" name="qty" placeholder="Masukan Kuantiti" min="0" step="any" >
			                                      		</div>
                                                <span class="help-block small pesan pesan-qty_detail"></span>
                                            </div>


                                            <!-- Input harga -->
                                            <div class="form-group field-harga_detail has-feedback">
                                                <label for="harga_detail">Harga</label>
                                                <div class="input-group" style="width: 100%">
                                                 <div class="input-group">
                                                <span class="input-group-addon">Rp.</span>
                                                <input type="text" class="form-control field" id="harga_detail" name="harga" placeholder="Masukan Harga" >
                                                <span class="input-group-addon">,00-</span>
                                              </div>
                                                </div>
                                                <span class="help-block small pesan pesan-harga_detail"></span>
                                            </div>  
                                        </div>
                                      </div>
                              </fieldset>
                            </div>

                            <!-- panel 2  (Kanan) Detail Operasional Proyek-->
                            <div class="col-md-6">
                              <fieldset>
                                      <div class="row">
                                        <div class="col-md-12">
                                            <!-- Subtotal -->
                                            <div class="form-group field-sub_total_detail has-feedback">
                                                <label for="sub_total_detail">Sub Total</label>
                                                <div class="input-group">
                                                <span class="input-group-addon">Rp.</span>
                                                <input type="text" class="form-control field" id="sub_total_detail" name="subtotal" placeholder="Masukan Subtotal" >
                                                <span class="input-group-addon">,00-</span>
                                              </div>
                                                <span class="help-block small pesan pesan-sub_total_detail"></span>
                                            </div>

                                            <!-- Status -->
                                            <div class="form-group field-status_detail has-feedback">
                                                <label for="status_detail">Status</label>
                                                <select class="form-control select2" name="status" id="status_detail"></select>
                                                <span class="help-block small pesan pesan-status_detail"></span>
                                            </div>

                                            <!-- Harga Asli -->
                                            <div class="form-group field-harga_asli_detail has-feedback">
                                                <label for="harga_asli_detail">Harga Asli</label>
                                                <div class="input-group">
                                                <span class="input-group-addon">Rp.</span>
                                                <input type="text" class="form-control field" id="harga_asli_detail" name="harga_asli" placeholder="Masukan Harga Asli" >
                                                <span class="input-group-addon">,00-</span>
                                              </div>
                                                <span class="help-block small pesan pesan-harga_asli_detail"></span>
                                            </div>

                                            <!-- Input sisa -->
                                            <div class="form-group field-sisa_detail has-feedback">
                                                <label for="sisa_detail">Sisa</label>
                                                <div class="input-group">
                                                <span class="input-group-addon">Rp.</span>
                                                <input type="text" class="form-control field" id="sisa_detail" name="sisa" placeholder="" >
                                                <span class="input-group-addon">,00-</span>
                                              </div>
                                                <span class="help-block small pesan pesan-sisa_detail"></span>
                                            </div>


                                            <!-- Input status lunas -->
                                            <div class="form-group field-status_lunas_detail has-feedback">
                                                <label for="status_lunas_detail">Status Lunas</label>
                                               <select name="status_lunas" id="status_lunas_detail" class="form-control field">
                                               </select>
                                                <span class="help-block small pesan pesan-status_lunas_detail"></span>
                                            </div>  
                                        </div>
                                      </div>
                              </fieldset>
                            </div>
                          </div>
                                  	</form>
                                  </div>
                                  <div class="modal-footer">
                                  	<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Kembali</button>
                                  	<button type="submit" id="submit_detailOperasional" class="btn btn-primary" value="tambah">
                                  		Submit
                                  	</button>
                                  	
                                  </div>

                                </div>
                              </div>
                            </div>
                            <!-- END OF MODAL -->


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

