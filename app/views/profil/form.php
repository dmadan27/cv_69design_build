<?php Defined("BASE_PATH") or die("Dilarang Mengakses File Secara Langsung"); ?>

<div class="modal fade" id="modalProfil">
	<div class="modal-dialog">
		<div class="modal-content">
			<!-- header modal -->
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
                <h4 class="modal-title">Form Data Profil</h4>
			</div>
			<form id="form_edit_profil" role="form">
				<!-- body modal -->
				<div class="modal-body">
					<!-- field nama -->
					<div class="form-group field-nama has-feedback">
						<label for="nama">Nama</label>
						<input type="text" name="nama" id="nama" class="form-control field" placeholder="Masukkan Nama Anda">
						<span class="help-block small pesan pesan-nama"></span>
					</div>

					<!-- field alamat -->
					<div class="form-group field-alamat has-feedback">
						<label for="alamat">Alamat</label>
						<textarea id="alamat" class="form-control field" placeholder="Masukkan Alamat Anda"></textarea>
						<span class="help-block small pesan pesan-alamat"></span>
					</div>

					<!-- field no_telp -->
					<div class="form-group field-no_telp has-feedback">
						<label for="no_telp">No. Telepon</label>
						<input type="text" name="no_telp" id="no_telp" class="form-control field" placeholder="Masukkan No. Telepon Anda">
						<span class="help-block small pesan pesan-no_telp"></span>
					</div>

				</div>
				<!-- modal footer -->
				<div class="modal-footer">
					<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Kembali</button>
	                <button type="submit" id="submit_edit_profil" class="btn btn-primary" value="action-edit">Simpan Data</button>
				</div>
			</form>
		</div>
	</div>
</div>