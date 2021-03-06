<?php Defined("BASE_PATH") or die(ACCESS_DENIED); ?>

<input type="hidden" name="id_kas_kecil" id="id_kas_kecil" value="<?= $_SESSION['sess_id']; ?>">
<div class="row">
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-4">
                <!-- field id pengajuan-->
                <div class="form-group field-id has-feedback">
                    <label for="id">ID Pengajuan Kas Kecil</label>
                    <input type="text" name="id" id="id_pengajuan" class="form-control field" disabled placeholder="">
                    <input type="hidden" name="id_kas_kecil" id="id_kas_kecil">
                    <span class="help-block small pesan pesan-id"></span>
                </div>
            </div>
            <div class="col-md-8">
                <!-- field nama -->
                <div class="form-group field-nama has-feedback">
                    <label for="nama">Nama Kebutuhan</label>
                    <input type="text" name="nama" id="nama_pengajuan" class="form-control field" placeholder="Masukan Nama">
                    <span class="help-block small pesan pesan-nama"></span>
                </div>
            </div>
        </div>

        <!-- field tgl -->
        <div class="form-group field-tgl has-feedback">
            <label for="tgl">Tanggal</label>
            <input type="text" name="tgl" id="tgl_pengajuan" class="form-control datepicker field" placeholder="Masukan Tanggal">
            <span class="help-block small pesan pesan-tgl"></span>
        </div>


        <div class="row">
            <div class="col-md-6">
                <!-- field Saldo terbaru -->
                <div class="form-group field-saldo has-feedback">
                    <label for="saldo">Sisa Saldo Kas Kecil:</label>
                    <div class="input-group">
                        <span class="input-group-addon">Rp.</span>
                        <input type="text" name="saldo" id="saldo_pengajuan" class="form-control field input-mask-uang" disabled>
                        <span class="input-group-addon">,00-</span>
                    </div>
                    <span class="help-block small pesan pesan-saldo"></span>
                </div>
            </div>
            <div class="col-md-6">
                <!-- field total -->
                <div class="form-group field-total has-feedback">
                    <label for="total">Total</label>
                    <div class="input-group">
                        <span class="input-group-addon">Rp.</span>
                        <input type="text" name="total" id="total_pengajuan" class="form-control field input-mask-uang" placeholder="Masukan Total">
                        <span class="input-group-addon">,00-</span>
                    </div>
                    <span class="help-block small pesan pesan-total"></span>
                </div>
            </div>
        </div>

        <div class="form-group field-status has-feedback">
            <label for="status">Status</label>
            <div class="input-group" style="width: 100% !important;">
                <select disabled style="width: 100% !important;" name="status" class="form-control field select2" id="status_pengajuan"></select>
            </div>
            <span class="help-block small pesan pesan-status"></span>
        </div>

        <div class="form-group field-ket has-feedback">
            <label for="ket"> Keterangan </label>
            <div class="input-group" style="width: 100% !important;">
                <textarea disabled name="ket" id="ket" class="form-control field" placeholder="Masukan Keterangan"></textarea>
            </div>
        </div>
        
    </div>
</div>