<?php Defined("BASE_PATH") or die("Dilarang Mengakses File Secara Langsung"); ?>

<div class="modal fade" id="modalDistributor">
	<div class="modal-dialog">
		<div class="modal-content">
			<!-- header modal -->
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
                <h4 class="modal-title">Form Data Distributor</h4>
			</div>
			<form id="form_distributor" role="form">
				<input type="hidden" id="token_form">
				<!-- body modal -->
				<div class="modal-body">

					<!-- field id -->
					<div class="form-group field-id has-feedback">
						<label for="id">ID Distributor</label>
						<input type="text" name="id" id="id" class="form-control field" placeholder="">
						<span class="help-block small pesan pesan-id"></span>
					</div>

					<!-- field nama -->
					<div class="form-group field-nama has-feedback">
						<label for="nama">Nama Distributor</label>
						<input type="text" name="nama" id="nama" class="form-control field" placeholder="Masukkan Nama Distributor">
						<span class="help-block small pesan pesan-nama"></span>
					</div>

					<!-- Alamat -->
					<div class="form-group field-alamat has-feedback">
						<label for="alamat">Alamat</label>
		                <textarea name="alamat" id="alamat" class="form-control field" placeholder="Masukan Alamat Distributor"></textarea>
		                <span class="help-block small pesan pesan-alamat"></span>
					</div>

					<!-- jenis -->
					<div class="form-group field-jenis has-feedback">
						<label for="jenis">Jenis</label>
						<select id="jenis" class="form-control field"></select>
						<span class="help-block small pesan pesan-jenis"></span>
					</div>

					<!-- No Telepon -->
					<div class="form-group field-no_telp has-feedback">
						<label for="no_telp">No Telepon</label>
						<input type="text" name="no_telp" id="no_telp" class="form-control field" placeholder="Masukkan Nomor Telepon">
						<span class="help-block small pesan pesan-no_telp"></span>
					</div>


					<!-- Pemilik -->
					<div class="form-group field-pemilik has-feedback">
						<label for="pemilik">Pemilik</label>
						<input type="text" name="pemilik" id="pemilik" class="form-control field" placeholder="Masukkan Pemilik">
						<span class="help-block small pesan pesan-pemilik"></span>
					</div>

					<!-- status -->
					<div class="form-group field-status has-feedback">
						<label for="status">Status</label>
						<select id="status" class="form-control field"></select>
						<span class="help-block small pesan pesan-status"></span>
					</div>

					
				</div>
				<!-- modal footer -->
				<div class="modal-footer">
					<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Kembali</button>
	                <button type="submit" id="submit_distributor" class="btn btn-primary" value="tambah">Simpan Data</button>
				</div>
			</form>
		</div>
	</div>
</div>