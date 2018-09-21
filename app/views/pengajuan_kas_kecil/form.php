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
				<input type="hidden" id="id">
				<!-- body modal -->
				<div class="modal-body">
					<!-- field id pengajuan kas kecil -->
					<div class="form-group field-id has-feedback">
						<label for="id">ID</label>
						<input type="text" name="id" id="id" class="form-control field" placeholder="">
						<span class="help-block small pesan pesan-id"></span>
					</div>

					<!-- status -->
					<div class="form-group field-status has-feedback">
						<label for="status">Status</label>

			                <select id="status" name="status" class="form-control field">
			                </select>
			                <span class="help-block small pesan pesan-saldo"></span>
						</div>
						
					</div>					
				
				<!-- modal footer -->
				<div class="modal-footer">
					<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Kembali</button>
	                <button type="submit" id="submit_pengajuan_kas_kecil" class="btn btn-primary" value="tambah">Simpan Data</button>
				</div>
				
			</form>
		</div>
	</div>
</div>