<?php Defined("BASE_PATH") or die("Dilarang Mengakses File Secara Langsung"); ?>

<div class="modal fade" id="modalOperasional">
  <div class="modal-dialog">
    <div class="modal-content">
      <!-- header modal -->
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
                <h4 class="modal-title">Form Data Operasional (Di Luar Proyek)</h4>
      </div>
      <form id="form_operasional" role="form">
        <input type="hidden" id="id">
        <input type="hidden" id="token_form">
        <!-- body modal -->
        <div class="modal-body">
          <div class="row">
            <!-- panel kiri -->
            <div class="col-md-12">
                <!-- field ID Bank -->
                <div class="form-group field-id_bank has-feedback">
                  <label for="id">ID Bank</label>
                    <select class="form-control field select2" name="id_bank" id="id_bank"> 
                    </select>
                  <span class="help-block small pesan pesan-id_bank"></span>
                </div>
                
                <!-- field Tanggal -->
                <div class="form-group field-tgl has-feedback">
                  <label for="tgl">Tanggal</label>
                  <input type="text" name="tgl" id="tgl" class="form-control datepicker field" placeholder="Masukan Tanggal">
                  <span class="help-block small pesan pesan-tgl"></span>
                </div>
            

            <!-- panel kanan -->
            <!-- <div class="col-md-6"> -->

              <!-- field Nama -->
                <div class="form-group field-nama has-feedback">
                  <label for="nama">Nama</label>
                  <input type="text" name="nama" id="nama" placeholder="Masukan Nama Kebutuhan" class="form-control field">
                  <span class="help-block small pesan pesan-nama"></span>
                </div>
                
                  <!-- field  Nominal -->
                <div class="form-group field-nominal has-feedback">
                  <label for="nominal">Nominal</label>
                  <div class="input-group">
                    <span class="input-group-addon">Rp</span>
                      <input type="number" min="0" step="any" name="nominal" id="nominal" class="form-control field" placeholder="Masukkan Nominal">
                      <span class="input-group-addon">.00</span>
                    </div>
                  <span class="help-block small pesan pesan-nominal"></span>
                </div>

                <!-- keterangan -->
                <div class="form-group field-ket has-feedback">
                  <label for="ket">Keterangan</label>
                  <textarea class="form-control field" id="ket" name="ket" placeholder="Masukan Keterangan"></textarea>
                  <span class="help-block small pesan pesan-ket"></span>
                </div>
            </div>
          </div>
        </div>
        <!-- modal footer -->
        <div class="modal-footer">
          <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Kembali</button>
                  <button type="submit" id="submit_operasional" class="btn btn-primary" value="tambah">Simpan Data</button>
        </div>
      </form>
    </div>
  </div>
</div>