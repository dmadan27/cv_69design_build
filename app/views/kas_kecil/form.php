<?php Defined("BASE_PATH") or die("Dilarang Mengakses File Secara Langsung"); ?>

<div class="modal fade" id="modalKasKecil">
  <div class="modal-dialog">
    <div class="modal-content">
      <!-- header modal -->
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
                <h4 class="modal-title">Form Data Kas Kecil</h4>
      </div>
      <form id="form_kas_kecil" role="form">
        <input type="hidden" id="id">
        <input type="hidden" id="token_form">
        <!-- body modal -->
        <div class="modal-body">
          <div class="row">
            <!-- panel kiri -->
            <div class="col-md-6">
                <!-- field id -->
                <div class="form-group field-id has-feedback">
                  <label for="id">ID Kas Kecil</label>
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
            </div>

            <!-- panel kanan -->
            <div class="col-md-6">
               <!-- email -->
                <div class="form-group field-email has-feedback">
                  <label for="email">Email</label>
                  <input type="text" name="email" id="email" class="form-control field" placeholder="Masukan Email">
                  <span class="help-block small pesan pesan-email"></span>
                </div>

                <!-- foto -->
                <div class="form-group field-foto has-feedback">
                  <label for="foto">Foto</label>
                  <input type="text" name="foto" id="foto" class="form-control field">
                  <span class="help-block small pesan pesan-foto"></span>
                </div>

                 <!-- saldo -->
                <div class="form-group field-saldo has-feedback">
                  <label for="saldo">Saldo</label>
                  <input type="text" name="saldo" id="saldo" class="form-control field" placeholder="Masukan Saldo">
                  <span class="help-block small pesan pesan-saldo"></span>
                </div>

                 <!-- status -->
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
        <!-- modal footer -->
        <div class="modal-footer">
          <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Kembali</button>
                  <button type="submit" id="submit_kas_kecil" class="btn btn-primary" value="tambah">Simpan Data</button>
        </div>
      </form>
    </div>
  </div>
</div>