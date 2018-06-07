<?php Defined("BASE_PATH") or die("Dilarang Mengakses File Secara Langsung"); ?>

<div class="modal fade" id="modalPengajuanSKC">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- header modal -->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">Edit Status Pengajuan Sub Kas Kecil</h4>
            </div>
            <form id="form_pengajuan_skc" role="form">
                <input type="hidden" id="id">
                <input type="hidden" id="token_form">
                <!-- body modal -->
                <div class="modal-body">
                    <!-- status -->
                    <div class="form-group field-status has-feedback">
                        <label for="status">Status</label>
                        <select id="status" class="form-control field"></select>
                        <span class="help-block small pesan pesan-status"></span>
                    </div>  

                    <div class="data-pengajuan" style="display: none">
                        <!-- total pengajuan -->
                        <div class="form-group">
                            <label for="total">Total Pengajuan:</label>
                            <p id="total"></p>
                        </div>

                        <!-- sisa saldo -->
                        <div class="form-group">
                            <label for="saldo">Sisa Saldo Sub Kas Kecil:</label>
                            <p id="saldo"></p>       
                        </div>

                        <!-- Dana disetujui -->
                        <div class="form-group field-dana_disetujui has-feedback">
                            <label for="dana_disetujui">Dana yang disetujui</label>
                            <div class="input-group">
                                <span class="input-group-addon">Rp</span>
                                <input type="number" min="0" step="any" name="dana_disetujui" id="dana_disetujui" class="form-control field" placeholder="Masukkan Dana Yang Disetujui">
                                <span class="input-group-addon">.00</span>
                            </div>
                            <span class="help-block small pesan pesan-dana_disetujui"></span>
                        </div>
                    </div>

                </div>
                <!-- modal footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Kembali</button>
                    <button type="submit" id="submit_pengajuan_skc" class="btn btn-primary" value="action-edit-status">Kirim</button>
                </div>
            </form>
        </div>
    </div>
</div>