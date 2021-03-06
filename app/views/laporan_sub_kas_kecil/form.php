<?php Defined("BASE_PATH") or die("Dilarang Mengakses File Secara Langsung"); ?>

<div class="modal fade" id="modalLaporanSKC">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- header modal -->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">Edit Status Laporan Pengajuan Sub Kas Kecil</h4>
            </div>
            <form id="form_laporan_skc" role="form">
                <input type="hidden" id="id_sub_kas_kecil">
                <!-- body modal -->
                <div class="modal-body">

                    <!-- status -->
                    <div class="form-group field-status_laporan has-feedback">
                        <label for="status_laporan">Status</label>
                        <select id="status_laporan" class="form-control field select2" style="width: 100%"></select>
                        <span class="help-block small pesan pesan-status_laporan"></span>
                    </div>

                    <!-- keterangan -->
                    <div class="form-group field-keterangan has-feedback data-keterangan">
                        <label for="keterangan">Keterangan</label>
                        <textarea class="form-control field" name="keterangan" id="keterangan" rows="3"></textarea>
                        <span class="help-block small pesan pesan-keterangan"></span>
                    </div>

                </div>
                <!-- modal footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Kembali</button>
                    <button type="submit" id="submit_laporan_skc" class="btn btn-primary" value="action-edit">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>