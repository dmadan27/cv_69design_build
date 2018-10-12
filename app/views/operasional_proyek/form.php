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
          <li><a href="<?= BASE_URL.'operasional-proyek/'; ?>">Operasional Proyek</a></li>
          <li class="active">Form Data Operasional Proyek</li>
        </ol>
    </section>
    <section class="content container-fluid">
      <form id="form_operasional_proyek" role="form">
        <!-- <input type="hidden" id="token_form" value="<?= $this->data['token_form']?>"> -->
        
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
                        		<div class="col-md-6 col-sm-6 col-xs-12">
	                            	<!-- ID -->
		                                    <div class="form-group field-id has-feedback">
		                                        <label for="id">ID Operasional Proyek</label>
		                                        <input type="text" class="form-control field" id="id" placeholder="" value="<?= $this->data['id']; ?>">
		                                        <span class="help-block small pesan pesan-id"></span>
		                                    </div>

		                              <!-- ID Proyek -->
		                                    <div class="form-group field-id_proyek has-feedback">
		                                        <label for="id_proyek">ID Proyek</label>
                                            <select class="form-control select2" name="id_proyek" id="id_proyek">
                                            </select>
		                                        <span class="help-block small pesan pesan-id_proyek"></span>
		                                    </div>
		                              <!-- ID Bank -->
		                                    <div class="form-group field-id_bank has-feedback">
		                                        <label for="id_bank">ID Bank</label>
		                                        <select class="form-control select2" name="id_bank" id="id_bank"></select>
		                                        <span class="help-block small pesan pesan-id_bank"></span>
		                                    </div>

		                              <!-- ID Kas Besar -->
		                                    <div class="form-group field-id_kas_besar has-feedback">
		                                        <label for="id_kas_besar">ID Kas Besar</label>
		                                        <select class="form-control select2" name="id_kas_besar" id="id_kas_besar"></select>
		                                        <span class="help-block small pesan pesan-id_kas_besar"></span>
		                                    </div>

		                              <!-- ID Distributor -->
		                                    <div class="form-group field-id_distributor has-feedback">
		                                        <label for="id_distributor">ID Distributor</label>
		                                        <select class="form-control select2" name="id_distributor" id="id_distributor"></select>
		                                        <span class="help-block small pesan pesan-id_distributor"></span>
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
		                        </div>

		                        <div class="col-lg-6 col-md-6 col-xs-12">
								                   <!-- Input Nama Pengajuan -->
			                                    <div class="form-group field-nama has-feedback">
			                                        <label for="nama">Nama</label>
			                                        <input type="text" class="form-control field" id="nama" name="nama" placeholder="Masukan Nama Kebutuhan Operasional" value="<?= $this->data['nama'];?> ">
			                                        <span class="help-block small pesan pesan-nama"></span>
			                                    </div>

			                              <!-- Input Jenis -->
			                                    <div class="form-group field-jenis has-feedback">
			                                        <label for="jenis">Jenis</label>
			                                        <select name="jenis" class="form-control field select2" id="jenis"></select>
			                                        <span class="help-block small pesan pesan-jenis"></span>
			                                    </div>


			                              <!-- Input Total -->
			                                    <div class="form-group field-total has-feedback">
			                                        <label for="total">Total</label>
			                                        <div class="input-group" style="width: 100%">
			                                         <div class="input-group">
			                                                <span class="input-group-addon">Rp.</span>
			                                                <input type="text" class="form-control field input-mask-uang" id="total" name="total" placeholder="Masukan Total Pengajuan" value="<?= $this->data['total']; ?>" >
			                                                <span class="input-group-addon">,00-</span>
			                                          </div>
			                                        </div>
			                                        <span class="help-block small pesan pesan-total"></span>
			                                    </div>

	                                    <!-- Input Sisa -->
			                                    <div class="form-group field-sisa has-feedback">
			                                        <label for="sisa">Sisa</label>
			                                        <div class="input-group" style="width: 100%">
			                                         <div class="input-group">
			                                                <span class="input-group-addon">Rp.</span>
			                                                <input type="text" class="form-control field input-mask-uang" id="sisa" name="sisa" placeholder="" value="<?= $this->data['sisa']; ?>">
			                                                <span class="input-group-addon">,00-</span>
			                                          </div>
			                                        </div>
			                                        <span class="help-block small pesan pesan-sisa"></span>
			                                    </div>

			                           	<!-- Input Status -->
			                                    <div class="form-group field-status has-feedback">
			                                        <label for="status">Status</label>
			                                        <select name="status" class="form-control field select2" id="status"></select>
			                                        <span class="help-block small pesan pesan-status"></span>
			                                    </div>

			                            <!-- Input Status Lunas -->
			                                    <div class="form-group field-status_lunas has-feedback">
			                                        <label for="status_lunas">Status Lunas</label>
			                                        <select name="status_lunas" class="form-control field select2" id="status_lunas"></select>
			                                        <span class="help-block small pesan pesan-status_lunas"></span>
			                                    </div>

                                  <!-- Input Keterangan -->
                                          <div class="form-group field-ket has-feedback">
                                              <label for="ket">keterangan</label>
                                              <textarea class="form-control field" id="ket" name="ket" placeholder="Masukan Keterangan" value=""><?= $this->data['ket'];?></textarea>
                                              <span class="help-block small pesan pesan-ket"></span>
                                          </div>

                                </div>          
                        		<div class="clearfix"></div>
                    	</div>
                    </div>
                </div>
            </div>
        </div>
            
         <!--    Panel Detail dan sub kas kecil -->
            <!-- <div class="row">
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
                          Tabel Detail Operasional Proyek -->
                          <!-- <div class="row"> -->
                            <!-- panel 1 -->
                            <!-- <div class="col-md-12">
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
                                            <th>Jenis</th>
                                            <th>Satuan</th>
                                            <th>Qty</th>
                                            <th>Harga</th>
                                            <th>Sub Total</th>
                                            <th>Status</th>
                                            <th>Harga Asli</th>
                                            <th>Sisa</th>
                                            <th>Status Lunas</th>
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
             </div> -->
          <!-- </div> -->

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




</script>