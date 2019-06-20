<?php Defined("BASE_PATH") or die("Dilarang Mengakses File Secara Langsung"); ?>

<div class="modal fade" id="modalUser">
	<div class="modal-dialog">
		<div class="modal-content">
			<!-- header modal -->
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
                <h4 class="modal-title">Form Data User</h4>
			</div>
			<form id="form_user" role="form">
				<input type="hidden" id="id">
				<input type="hidden" id="token_form">
				<!-- body modal -->
				<div class="modal-body">
					<!-- field username -->
					<div class="form-group field-username has-feedback">
						<label for="username">Username</label>
						<input type="text" name="username" id="username" class="form-control field" placeholder="Masukkan Username">
						<span class="help-block small pesan pesan-username"></span>
					</div>

					<!-- password -->
					<div class="form-group field-password has-feedback">
						<label for="password">Password</label>
			                <input type="password" name="password" id="password" class="form-control field" placeholder="Masukkan Password">
						<span class="help-block small pesan pesan-password"></span>
					</div>

					<!-- level -->
					<div class="form-group field-level has-feedback">
						<label for="level">Level</label>
						<select name="level" id="level" class="form-control field">
							<option value="">-- PILIH LEVEL --</option>
							<option value="KAS BESAR">KAS BESAR</option>
							<option value="KAS KECIL">KAS KECIL</option>
							<option value="SUB KAS KECIL">SUB KAS KECIL</option>
							<option value="OWNER">OWNER</option>
						</select>
						<span class="help-block small pesan pesan-status"></span>
					</div>					
				</div>
				<!-- modal footer -->
				<div class="modal-footer">
					<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Kembali</button>
	                <button type="submit" id="submit_user" class="btn btn-primary" value="tambah">Simpan Data</button>
				</div>
			</form>
		</div>
	</div>
</div>