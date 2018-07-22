<?php Defined("BASE_PATH") or die("Dilarang Mengakses File Secara Langsung"); ?>

<div class="modal fade" id="modalBank">
	<div class="modal-dialog">
		<div class="modal-content">
			<!-- header modal -->
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
                <h4 class="modal-title">Form Data Bank</h4>
			</div>
			<form id="form_bank" role="form">
				<input type="hidden" id="id">
				<!-- body modal -->
				<div class="modal-body">
					
					<!-- field nama -->
					<div class="form-group field-nama has-feedback">
						<label for="nama">Nama Bank</label>
						<input type="text" name="nama" id="nama" class="form-control field" placeholder="Masukkan Nama Bank">
						<span class="help-block small pesan pesan-nama"></span>
					</div>

					<!-- saldo awal -->
					<div class="form-group field-saldo has-feedback">
						<label for="saldo">Saldo Awal</label>
						<div class="input-group">
							<span class="input-group-addon">Rp</span>
			                <input type="text" name="saldo" id="saldo" class="form-control field input-mask-uang" placeholder="Masukkan Saldo Awal">
			                <span class="input-group-addon">.00</span>
						</div>
						<span class="help-block small pesan pesan-saldo"></span>
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
	                <button type="submit" id="submit_bank" class="btn btn-primary" value="tambah">Simpan Data</button>
				</div>
			</form>
		</div>
	</div>
</div>