<?php Defined("BASE_PATH") or die(ACCESS_DENIED); ?>

<div class="modal fade" id="modalPengajuan_kasKecil">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- header modal -->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">Form Data Pengajuan Kas Kecil</h4>
            </div>
            <form id="form_pengajuan_kas_kecil" role="form">
                <!-- body modal -->
                <div class="modal-body">
                
                    <?php
                        if ($_SESSION['sess_level'] == "KAS BESAR") {
                            include_once('form_kas_besar.php');
                        }
                        else if($_SESSION['sess_level'] == "KAS KECIL") {
                            include_once('form_kas_kecil.php');
                        }
                    ?>

                    <!-- modal footer -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal">Kembali</button>
                        <button type="submit" id="submit_pengajuan_kas_kecil" class="btn btn-primary btn-flat" value="tambah">Simpan Data</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>