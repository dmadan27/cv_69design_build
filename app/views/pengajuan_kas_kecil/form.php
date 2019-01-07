<?php Defined("BASE_PATH") or die("Dilarang Mengakses File Secara Langsung"); ?>

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
                    $level = $_SESSION['sess_level'];
                    if($level == "KAS BESAR"){
                ?>

                    <div class="row">
						<div class="col-md-12">
                            <!-- field id pengajuan-->
                            <div class="form-group field-id has-feedback">
                                <label for="id">ID Pengajuan Kas Kecil</label>
                                <input type="text" name="id" id="id" class="form-control field" disabled placeholder="">
                                <span class="help-block small pesan pesan-id"></span>
                            </div>

                            <!-- field tgl -->
                            <div class="form-group field-tgl has-feedback">
                                <label for="tgl">Tanggal</label>
                                <input type="text" name="tgl" id="tgl" class="form-control datepicker field" placeholder="Masukan Tanggal" disabled>
                                <span class="help-block small pesan pesan-tgl"></span>
                            </div>

                            <!-- field nama -->
                            <div class="form-group field-nama has-feedback">
                                <label for="nama">Nama Kebutuhan</label>
                                    <input type="text" name="nama" id="nama" class="form-control field" placeholder="Masukan Nama" disabled>
                                <span class="help-block small pesan pesan-nama"></span>
                            </div>

                            <!-- field Saldo terbaru -->
                            <div class="form-group">
                                <label for="saldo">Sisa Saldo Kas Kecil:</label>
                                <p id="saldo"></p>       
                            </div>

                            <!-- field total -->
                            <div class="form-group field-total has-feedback">
                                <label for="total">Total</label>
                                <div class="input-group">
                                    <span class="input-group-addon">Rp.</span>
                                    <input disabled type="text" name="total" id="total" class="form-control field input-mask-uang" placeholder="Masukan Total">
                                    <span class="input-group-addon">,00-</span>
                                </div>
                                <span class="help-block small pesan pesan-total"></span>
                            </div>

                            <!-- status -->
                            <div class="form-group field-status has-feedback">
                                <label for="status">Status</label>
                                <div class="input-group">
                                    <select disabled name="status" class="form-control field select2" id="status"></select>
                                </div>
                                <span class="help-block small pesan pesan-status"></span>
                            </div>

                        </div>
					</div>

                <?php } else if($level == "KAS KECIL") { ?>

					<div class="row">
						<div class="col-md-12">
                            <!-- field id pengajuan-->
                            <div class="form-group field-id has-feedback">
                                <label for="id">ID Pengajuan Kas Kecil</label>
                                <input type="text" name="id" id="id" class="form-control field" placeholder="">
                                <span class="help-block small pesan pesan-id"></span>
                            </div>

                            <!-- field tgl -->
                            <div class="form-group field-tgl has-feedback">
                                <label for="tgl">Tanggal</label>
                                <input type="text" name="tgl" id="tgl" class="form-control datepicker field" placeholder="Masukan Tanggal">
                                <span class="help-block small pesan pesan-tgl"></span>
                            </div>

                            <!-- field nama -->
                            <div class="form-group field-nama has-feedback">
                                <label for="nama">Nama Kebutuhan</label>
                                    <input type="text" name="nama" id="nama" class="form-control field" placeholder="Masukan Nama">
                                <span class="help-block small pesan pesan-nama"></span>
                            </div>

                            <!-- field Saldo terbaru -->
                            <div class="form-group">
                                <label for="saldo">Sisa Saldo Kas Kecil:</label>
                                <p id="saldo"></p>       
                            </div>

                            <!-- field total -->
                            <div class="form-group field-total has-feedback">
                                <label for="total">Total</label>
                                <div class="input-group">
                                    <span class="input-group-addon">Rp.</span>
                                    <input type="text" name="total" id="total" class="form-control field input-mask-uang" placeholder="Masukan Total">
                                    <span class="input-group-addon">,00-</span>
                                </div>
                                <span class="help-block small pesan pesan-total"></span>
                            </div>

                            <!-- status -->
                            <div class="form-group field-status has-feedback">
                                <label for="status">Status</label>
                                <div class="input-group">
                                    <select name="status" class="form-control field select2" id="status"></select>
                                </div>
                                <span class="help-block small pesan pesan-status"></span>
                            </div>

                        </div>
					</div>
                
                <?php } ?>
				
					<!-- modal footer -->
					<div class="modal-footer">
						<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Kembali</button>
		                <button type="submit" id="submit_pengajuan_kas_kecil" class="btn btn-primary" value="tambah">Simpan Data</button>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>