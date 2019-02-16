<?php Defined("BASE_PATH") or die("Dilarang Mengakses File Secara Langsung"); ?>

<div class="modal fade" id="modalPengajuanSKC">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- header modal -->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">Edit Status Pengajuan Sub Kas Kecil</h4>
            </div>
            <form id="form_pengajuan_skc" role="form">
                <input type="hidden" id="id_sub_kas_kecil">
                <!-- body modal -->
                <div class="modal-body">

                    <!-- ID dan sub kas kecil -->
                    <div class="row">
                        <!-- ID -->
                        <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                            <div class="form-group field-id has-feedback">
                                <label for="id">ID Pengajuan</label>
                                <input type="text" class="form-control field" id="id" placeholder="" readonly>
                                <span class="help-block small pesan pesan-id"></span>
                            </div>
                                
                        </div>
                        
                        <!-- sub kas kecil -->
                        <div class="col-xs-12 col-sm-8 col-md-8 col-lg-8">
                            <div class="form-group field-sub_kas_kecil has-feedback">
                                <label for="sub_kas_kecil">Sub Kas Kecil</label>
                                <input type="text" class="form-control field" id="sub_kas_kecil" placeholder="" readonly>
                                <span class="help-block small pesan pesan-sub_kas_kecil"></span>
                            </div>
                        </div>
                    </div>

                    <!-- tgl dan nama pengajuan -->
                    <div class="row">
                        <!-- tgl -->
                        <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                            <div class="form-group field-tgl has-feedback">
                                <label for="tgl">Tanggal</label>
                                <div class="input-group date">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" class="form-control datepicker field" id="tgl" placeholder="yyyy-mm-dd" readonly>
                                </div>
                                <span class="help-block small pesan pesan-tgl"></span>
                            </div>
                        </div>
                        
                        <!-- nama pengajuan -->
                        <div class="col-xs-12 col-sm-8 col-md-8 col-lg-8">
                            <div class="form-group field-nama_pengajuan has-feedback">
                                <label for="nama_pengajuan">Nama Pengajuan</label>
                                <input type="text" class="form-control field" id="nama_pengajuan" placeholder="" readonly>
                                <span class="help-block small pesan pesan-nama_pengajuan"></span>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- total -->
                        <div class="col-md-12">
                            <div class="form-group field-total has-feedback">
                                <label for="total">Total</label>
                                    <div class="input-group">
                                    <span class="input-group-addon">Rp.</span>
                                    <input type="text" class="form-control field input-mask-uang" id="total" readonly>
                                    <span class="input-group-addon">,00-</span>
                                </div>
                                <span class="help-block small pesan pesan-total"></span>
                            </div>
                        </div>

                        <!-- total pengajuan -->
                        <div class="col-md-12">
                            <div class="form-group field-total_pengajuan has-feedback">
                                <label for="total_pengajuan">Total Pengajuan</label>
                                    <div class="input-group">
                                    <span class="input-group-addon">Rp.</span>
                                    <input type="text" class="form-control field input-mask-uang" id="total_pengajuan" readonly>
                                    <span class="input-group-addon">,00-</span>
                                </div>
                                <span class="help-block small pesan pesan-total_pengajuan"></span>
                            </div>
                        </div>
                    </div>

                    <!-- status -->
                    <div class="form-group field-status_order has-feedback">
                        <label for="status_order">Status</label>
                        <select id="status_order" class="form-control field select2" style="width: 100%"></select>
                        <span class="help-block small pesan pesan-status_order"></span>
                    </div>

                    <div class="data-pengajuan" style="display: none">
                        <!-- sisa saldo dan dana disetujui -->
                        <div class="row">
                            <!-- saldo sub kas kecil -->
                            <div class="col-md-6">
                                <div class="form-group field-saldo_sub_kas_kecil has-feedback">
                                    <label for="saldo_sub_kas_kecil">Saldo Sub Kas Kecil</label>
                                     <div class="input-group">
                                        <span class="input-group-addon">Rp.</span>
                                        <input type="text" class="form-control field input-mask-uang" id="saldo_sub_kas_kecil" readonly>
                                        <span class="input-group-addon">,00-</span>
                                    </div>
                                    <span class="help-block small pesan pesan-saldo_sub_kas_kecil"></span>
                                </div>
                            </div>

                            <!-- sisa saldo kas kecil -->
                            <div class="col-md-6">
                                <div class="form-group field-sisa_saldo_sub_kas_kecil has-feedback">
                                    <label for="sisa_saldo_sub_kas_kecil">Sisa Saldo Sub Kas Kecil</label>
                                     <div class="input-group">
                                        <span class="input-group-addon">Rp.</span>
                                        <input type="text" class="form-control field input-mask-uang" id="sisa_saldo_sub_kas_kecil" readonly>
                                        <span class="input-group-addon">,00-</span>
                                    </div>
                                    <span class="help-block small pesan pesan-sisa_saldo_sub_kas_kecil"></span>
                                </div>
                            </div>
                        </div>

                        <!-- saldo kas kecil -->
                        <div class="form-group field-saldo_kas_kecil has-feedback">
                            <label for="saldo_kas_kecil">Saldo</label>
                            <div class="input-group">
                                <span class="input-group-addon">Rp.</span>
                                <input type="text" class="form-control field input-mask-uang" id="saldo_kas_kecil" readonly>
                                <span class="input-group-addon">,00-</span>
                            </div>
                            <span class="help-block small pesan pesan-saldo_kas_kecil"></span>
                        </div>
                        
                        <div class="form-group field-dana_disetujui has-feedback">
                            <label for="dana_disetujui">Dana yang disetujui</label>
                            <div class="input-group">
                                <span class="input-group-addon">Rp.</span>
                                <input type="text" class="form-control field input-mask-uang" id="dana_disetujui" placeholder="Masukkan dana yang disetujui">
                                <span class="input-group-addon">,00-</span>
                            </div>
                            <span class="help-block small pesan pesan-dana_disetujui"></span>
                        </div>
                    </div>

                </div>
                <!-- modal footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Kembali</button>
                    <button type="submit" id="submit_pengajuan_skc" class="btn btn-primary" value="action-edit">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>