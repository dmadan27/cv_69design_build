<?php Defined("BASE_PATH") or die("Dilarang Mengakses File Secara Langsung"); ?>

<div class="modal fade" id="modal-export">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title modal-export-title">Ekspor Data Sub Kas Kecil</h4>
            </div>

            <form id="form-export" role="form" enctype="multipart/form-data">
                <div class="modal-body">

                    <input type="hidden" id="id" name="id" value="<?= $this->data['id']; ?>">
                    <input type="hidden" id="nama" name="nama" value="<?= $this->data['nama']; ?>">

                    <div class="form-group field-tahun">
                        <label for="tahun">Tahun</label>
                        <div class="input-group tahun">
                            <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                            </div>
                            <input type="text" id="tahun" name="tahun" class="form-control field" placeholder="-- Pilih Tahun --" autocomplete="off">
                        </div>
                        <span class="help-block small pesan pesan-tahun"></span>
                    </div>

                    <div class="form-group field-bulan">
                        <label for="bulan">Bulan</label>
                        <div class="input-group bulan">
                            <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                            </div>
                            <input type="text" id="bulan" name="bulan" class="form-control field" placeholder="-- Pilih Bulan --" autocomplete="off">
                        </div>
                        <span class="help-block small pesan pesan-bulan"></span>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-flat btn-default pull-left" data-dismiss="modal">Batal</button>
                    <button type="button" id="btn-export-form" class="btn btn-flat btn-success" value="ekspor">Ekspor Excel</button>
                </div>

            </form>
        </div>
    </div>
</div> 