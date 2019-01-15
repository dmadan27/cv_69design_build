<?php Defined("BASE_PATH") or die("Dilarang Mengakses File Secara Langsung"); ?>

<div class="modal fade" id="modalDetailOperasional">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <!-- header modal -->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">Form Data Detail Operasional Proyek</h4>
            </div>
            
            <form id="form_detail_operasional_proyek" role="form">
                <input type="hidden" id="id_detail">
                
                <!-- body modal -->
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12 col-xs-12">

                            <!-- Nama -->
                            <div class="form-group field-nama_detail has-feedback">
                                <label for="nama_detail">Nama</label>
                                <input type="text" class="form-control field" id="nama_detail" name="nama" placeholder="Masukan Nama Kebutuhan" >
                                <span class="help-block small pesan pesan-nama_detail"></span>
                            </div>

                            <!-- ID Bank -->
                            <div class="form-group field-id_bank has-feedback">
								<label for="id_bank">Bank</label>
								<select id="id_bank" class="form-control field select2" style="width: 100%;"></select>
								<span class="help-block small pesan pesan-id_bank"></span>
							</div>

                            <!-- field Tanggal -->
                            <div class="form-group field-tgl_detail has-feedback">
                              <label for="tgl_detail">Tanggal</label>
                              <input type="text" name="tgl" id="tgl_detail" class="form-control datepicker field" placeholder="Masukan Tanggal">
                              <span class="help-block small pesan pesan-tgl_detail"></span>
                            </div>

                            <!-- Input Total -->
                            <div class="form-group field-total_detail has-feedback">
                                <label for="total_detail">Total</label>
                                <div class="input-group" style="width: 100%">
                                    <div class="input-group">
                                        <span class="input-group-addon">Rp.</span>
                                        <input type="text" class="form-control field input-mask-uang" id="total_detail" name="total" placeholder="Masukan Total" >
                                        <span class="input-group-addon">,00-</span>
                                    </div>
                                </div>
                                <span class="help-block small pesan pesan-total_detail"></span>
                            </div>

                        </div>

                    </div>

                </div>
            
                <!-- modal footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Kembali</button>
                    <button type="submit" id="submit_detail" class="btn btn-primary" value="tambah">Tambah Detail</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalTanggalExport">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <!-- header modal -->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">Export Data Operasional Proyek ke Excel</h4>
            </div>
            
            <form id="form_tanggal_export" role="form">
                
                <!-- body modal -->
                <div class="modal-body">
                    <!-- field Dari Tanggal -->
                    <div class="form-group field-tgl_awal has-feedback">
                        <label for="tgl_awal">Dari Tanggal</label>
                        <input type="text" name="tgl_awal" id="tgl_awal" class="form-control datepicker field" placeholder="Dari Tanggal">
                        <span class="help-block small pesan pesan-tgl_awal"></span>
                    </div>
                    <!-- field Sampai Tanggal -->
                    <div class="form-group field-tgl_akhir has-feedback">
                        <label for="tgl_akhir">Sampai Tanggal</label>
                        <input type="text" name="tgl_akhir" id="tgl_akhir" class="form-control datepicker field" placeholder="Sampai Tanggal">
                        <span class="help-block small pesan pesan-tgl_akhir"></span>
                    </div>
                    
                </div>
            
                <!-- modal footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Kembali</button>
                    <button type="button" id="submit_export" onclick="export_excel()" class="btn btn-primary" value="tambah">Export Detail</button>
                </div>
            </form>

        </div>
    </div>
</div>