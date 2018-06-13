<?php Defined("BASE_PATH") or die("Dilarang Mengakses File Secara Langsung"); ?>

<div class="modal fade" id="modalKasBesar">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <!-- header modal -->
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
                <h4 class="modal-title">Form Data Kas Besar</h4>
      </div>
      <form id="form_kas_besar" role="form" enctype="multipart/form-data">

        <input type="hidden" id="token_form">
        <!-- body modal -->
        <div class="modal-body">
          <div class="row">
            <!-- panel kiri -->
            <div class="col-md-6">
              <fieldset>
                <legend style="font-size: 18px">Data Pribadi</legend>
                   <!-- field id -->
                      <div class="form-group field-id has-feedback">
                        <label for="id">ID Kas Besar</label>
                        <input type="text" name="id" id="id" class="form-control field" placeholder="">
                        <span class="help-block small pesan pesan-id"></span>
                      </div>

                      <!-- field nama -->
                      <div class="form-group field-nama has-feedback">
                        <label for="nama">Nama</label>
                        <input type="text" name="nama" id="nama" class="form-control field" placeholder="Masukan Nama">
                        <span class="help-block small pesan pesan-nama"></span>
                      </div>
                      
                      <!-- field alamat -->
                      <div class="form-group field-alamat has-feedback">
                        <label for="alamat">Alamat</label>
                        <textarea class="form-control field" id="alamat" name="alamat" placeholder="Masukan Alamat"></textarea>
                        <span class="help-block small pesan pesan-alamat"></span>
                      </div>

                        <!-- field no_telp -->
                      <div class="form-group field-no_telp has-feedback">
                        <label for="no_telp">No. Telepon</label>
                        <input type="text" name="no_telp" id="no_telp" class="form-control field" placeholder="Masukan Nomor Telepon">
                        <span class="help-block small pesan pesan-no_telp"></span>
                      </div>

                      <!-- foto -->
                      <div class="form-group field-foto has-feedback">
                        <label for="foto">Foto</label>
                        <input type="file" name="foto" id="foto" class="form-control field">
                        <span class="help-block small pesan pesan-foto"></span>
                      </div>
              </fieldset>
               
            </div>

            <!-- panel kanan -->
            <div class="col-md-6">
              <fieldset>
                 <legend style="font-size: 18px">Data Akun</legend>
                    <!-- field email -->
                      <div class="form-group field-email has-feedback">
                        <label for="email">Email</label>
                        <input type="text" name="email" id="email" class="form-control field" placeholder="Masukan Email">
                        <span class="help-block small pesan pesan-email"></span>
                      </div>

                      <!-- field password -->
                      <div class="form-group field-password has-feedback">
                        <label for="password">Password</label>
                        <input type="password" name="password" id="password" class="form-control field" placeholder="Masukan Password">
                        <span class="help-block small pesan pesan-password"></span>
                      </div>

                      <!-- field konfirmasi password -->
                      <div class="form-group field-password_confirm has-feedback">
                        <label for="password_confirm">Konfirmasi Password</label>
                        <input type="password" name="password_confirm" id="password_confirm" class="form-control field" placeholder="Masukan Konfirmasi Password">
                        <span class="help-block small pesan pesan-password_confirm"></span>
                      </div>

                        <!-- saldo -->
                      <div class="form-group field-saldo has-feedback">
                        <label for="saldo">Saldo</label>
                        <div class="input-group">
                          <span class="input-group-addon">Rp</span>
                          <input type="number" min="0" step="any" name="saldo" id="saldo" class="form-control field" placeholder="Masukan Saldo">
                          <span class="input-group-addon">.00</span>
                        </div>
                        <span class="help-block small pesan pesan-saldo"></span>
                      </div>

                       <!-- status -->
                      <div class="form-group field-status has-feedback">
                        <label for="status">Status</label>
                        <select name="status" class="form-control field" id="status">
                        </select>
                        <span class="help-block small pesan pesan-status"></span>
                      </div>
              </fieldset>
               
            </div>
          </div>
        </div>  <!--end of row ROOT-->

        <!-- modal footer -->
        <div class="modal-footer">
          <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Kembali</button>
                  <button type="submit" id="submit_kas_besar" class="btn btn-primary" value="tambah">Simpan Data</button>
        </div>
      </form>
    </div>
  </div>
</div>