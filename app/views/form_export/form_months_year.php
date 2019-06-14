<?php Defined("BASE_PATH") or die("Dilarang Mengakses File Secara Langsung"); ?>

<div class="modal fade" id="modal-export-months-year">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title modal-export-title">Ekspor Data</h4>
            </div>

            <form id="form-export-months-year" role="form" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="hidden" id="export-data" name="export-data">

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
                    <button type="submit" id="btn-export-months-year" class="btn btn-flat btn-success">Ekspor</button>
                </div>

            </form>
        </div>
    </div>
</div> 