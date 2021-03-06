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
                
                <!-- body modal -->
                <div class="modal-body">
                    <div class="row">
                        <!-- panel kiri -->
                        <div class="col-md-12">
                            <!-- field Tanggal -->
                            <div class="form-group field-tgl has-feedback">
                                <label for="tgl">Tanggal</label>
                                <input type="text" name="tgl" id="tgl" class="form-control datepicker field" placeholder="Masukan Tanggal">
                                <span class="help-block small pesan pesan-tgl"></span>
                            </div>
                            
                            <!-- field Nama -->
                            <div class="form-group field-nama has-feedback">
                                <label for="nama">Nama</label>
                                <input type="text" name="nama" id="nama" placeholder="Masukan Nama Kebutuhan" class="form-control field">
                                <span class="help-block small pesan pesan-nama"></span>
                            </div>

                            <!-- field ID Bank -->
                            <div class="form-group field-id_bank has-feedback">
                                <label for="id_bank">ID Bank</label>
                                <select class="form-control field" id="id_bank" style="width: 100%;"></select>
                                <span class="help-block small pesan pesan-id_bank"></span>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <!-- Jenis Operasional -->
                                    <div class="form-group field-jenis has-feedback">
                                        <label for="id">Jenis Pengeluaran</label>
                                        <select class="form-control field select2" name="jenis" id="jenis" style="width: 100%;"></select>
                                        <span class="help-block small pesan pesan-jenis"></span>
                                    </div>
                                </div>

                                <div class="col-md-8">
                                    <!-- field  Nominal -->
                                    <div class="form-group field-nominal has-feedback">
                                        <label for="nominal">Nominal</label>
                                        <div class="input-group">
                                            <span class="input-group-addon">Rp</span>
                                            <input type="text" name="nominal" id="nominal" class="form-control field input-mask-uang" placeholder="Masukkan Nominal">
                                            <span class="input-group-addon">.00</span>
                                        </div>
                                        <span class="help-block small pesan pesan-nominal"></span>
                                    </div>
                                </div>
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