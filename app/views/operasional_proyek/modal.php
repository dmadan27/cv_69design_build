<?php Defined("BASE_PATH") or die("Dilarang Mengakses File Secara Langsung"); ?>

<div class="modal fade" id="modalDetailOperasional">
    <div class="modal-dialog modal-lg">
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

                            <!-- field Tanggal -->
                            <div class="form-group field-tgl_detail has-feedback">
                              <label for="tgl_detail">Tanggal</label>
                              <input type="text" name="tgl" id="tgl_detail" class="form-control datepicker field" placeholder="Masukan Tanggal">
                              <span class="help-block small pesan pesan-tgl_detail"></span>
                            </div>

                            <!-- Satuan -->
                           <!--  <div class="form-group field-satuan_detail has-feedback">
                                <label for="nama_detail">Satuan</label>
                                <input type="text" class="form-control field" name="satuan" id="satuan_detail" placeholder="Masukan Satuan" >
                                <span class="help-block small pesan pesan-satuan_detail"></span>
                            </div> -->

                            <!-- Input qty -->
                            <!-- <div class="form-group field-qty_detail has-feedback">
                                <label for="qty_detail">Kuantiti</label>
                                <div class="input-group" style="width: 100%">
                                    <input type="number" class="form-control field" id="qty_detail" name="qty" placeholder="Masukan Kuantiti" min="0" step="any" >
                                </div>
                                <span class="help-block small pesan pesan-qty_detail"></span>
                            </div> -->

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

                        <!-- DISABLED kolom -->
                        <div class="col-md-6 col-xs-12">
                            
                            <!-- Subtotal -->
                            <!-- <div class="form-group field-sub_total_detail has-feedback">
                                <label for="sub_total_detail">Sub Total</label>
                                <div class="input-group">
                                    <span class="input-group-addon">Rp.</span>
                                    <input type="text" class="form-control field input-mask-uang" id="sub_total_detail" name="subtotal" placeholder="Masukan Subtotal" >
                                    <span class="input-group-addon">,00-</span>
                                </div>
                                <span class="help-block small pesan pesan-sub_total_detail"></span>
                            </div> -->

                            <!-- Status -->
                           <!--  <div class="form-group field-status_detail has-feedback">
                                <label for="status_detail">Status</label>
                                <select class="form-control select2" name="status" id="status_detail">
                                    <option value="TUNAI">TUNAI</option>
                                    <option value="KREDIT">KREDIT</option>
                                </select>
                               
                                <span class="help-block small pesan pesan-status_detail"></span>
                            </div>
 -->
                            <!-- Harga Asli -->
                            <!-- <div class="form-group field-harga_asli_detail has-feedback">
                                <label for="harga_asli_detail">Harga Asli</label>
                                <div class="input-group">
                                    <span class="input-group-addon">Rp.</span>
                                    <input type="text" class="form-control field input-mask-uang" id="harga_asli_detail" name="harga_asli" placeholder="Masukan Harga Asli" >
                                    <span class="input-group-addon">,00-</span>
                                </div>
                                <span class="help-block small pesan pesan-harga_asli_detail"></span>
                            </div> -->

                            <!-- Input sisa -->
                            <!-- <div class="form-group field-sisa_detail has-feedback">
                                <label for="sisa_detail">Sisa</label>
                                <div class="input-group">
                                    <span class="input-group-addon">Rp.</span>
                                    <input type="text" class="form-control field  input-mask-uang" id="sisa_detail" name="sisa" placeholder="Masukan Sisa" >
                                    <span class="input-group-addon">,00-</span>
                                </div>
                                <span class="help-block small pesan pesan-sisa_detail"></span>
                            </div> -->


                            <!-- Input status lunas -->
                            <!-- <div class="form-group field-status_lunas_detail has-feedback">
                                <label for="status_lunas_detail">Status Lunas</label>
                                <select name="status_lunas" id="status_lunas_detail" class="form-control field">
                                    <option value="LUNAS">LUNAS</option>
                                    <option value="BELUM LUNAS">BELUM LUNAS</option>
                                
                                </select>
                                <span class="help-block small pesan pesan-status_lunas_detail"></span>
                            </div> -->

                        <!-- </div> END OF DISABLED KOLOM-->

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