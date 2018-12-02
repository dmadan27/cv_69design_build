<?php Defined("BASE_PATH") or die("Dilarang Mengakses File Secara Langsung"); ?>

<div class="modal fade" id="modalPengajuan_kasKecil">
	<div class="modal-dialog modal-lg">
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

					<div class="row">
						<div class="col-md-6">
							  <fieldset>
				                   <!-- field id pengajuan-->
				                      <div class="form-group field-id has-feedback">
				                        <label for="id">ID Pengajuan Kas Kecil</label>
				                        <input type="text" name="id" id="id" class="form-control field" placeholder="">
				                        <span class="help-block small pesan pesan-id"></span>
				                      </div>

				                     <!-- id kas kecil  -->
				                      <div class="form-group field-id_kas_kecil has-feedback">
				                        <label for="id_kas_kecil">ID Kas Kecil</label>
				                        <select class="form-control select2" name="id_kas_kecil" id="id_kas_kecil" style="width: 100%" placeholder="">
				                        </select>
				                        <span class="help-block small pesan pesan-id_kas_kecil"></span>
				                      </div>

				                      <!-- field ID Bank -->
						                <div class="form-group field-id_bank has-feedback">
						                  <label for="id">ID Bank</label>
						                    <select class="form-control field select2" name="id_bank" id="id_bank" style="width: 100%;"> 
						                    </select>
						                  <span class="help-block small pesan pesan-id_bank"></span>
						                </div>

				                      <!-- field tgl -->
				                      <div class="form-group field-tgl has-feedback">
				                        <label for="tgl">Tanggal</label>
				                        <input type="text" name="tgl" id="tgl" class="form-control datepicker field" placeholder="Masukan Tanggal">
				                        <span class="help-block small pesan pesan-tgl"></span>
				                      </div>
				              </fieldset>
						</div>

						<div class="col-md-6">
							  <fieldset>
				                     <!-- field nama -->
				                      <div class="form-group field-nama has-feedback">
				                        <label for="nama">Nama Kebutuhan</label>
				                         <input type="text" name="nama" id="nama" class="form-control field" placeholder="Masukan Nama">
				                        <span class="help-block small pesan pesan-nama"></span>
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
				                        <select name="status" class="form-control field" id="status">
				                        </select>
				                        <span class="help-block small pesan pesan-status"></span>
				                      </div>
				              </fieldset>
						</div>
					</div>	

					<!-- Kolom Pemilihan Pengajuan Sub Kas Kecil   -->
					<div class="row">
						<div class="col-md-12">

							 <!-- field ID Pengajuan Sub Kas Kecil -->
		                      <div class="form-group field-id_pengajuan_sub_kas_kecil has-feedback">
		                        <label for="id_pengajuan_sub_kas_kecil">Pengajuan Sub Kas Kecil</label>
									<select class="form-control field select2" name="id_pengajuan_sub_kas_kecil" id="id_pengajuan_sub_kas_kecil" style="width: 100%;"> 
									</select>
		                        <span class="help-block small pesan pesan-id_pengajuan_sub_kas_kecil"></span>
		                      </div>

						</div>
					</div>
				
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