<?php Defined("BASE_PATH") or die("Dilarang Mengakses File Secara Langsung"); ?>

<div class="modal fade" id="modalDetail">
	<div class="modal-dialog">
		<div class="modal-content">
			<!-- header modal -->
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
                <h4 class="modal-title">Form Data Detail Proyek</h4>
			</div>
			<form id="form_detail" role="form">
				<input type="hidden" id="id">
				<!-- body modal -->
				<div class="modal-body">
					<div class="row">
						<div class="col-md-8 col-xs-8">
							<!-- field angsuran -->
							<div class="form-group field-angsuran has-feedback">
								<label for="angsuran">Angsuran</label>
								<input type="text" name="angsuran" id="angsuran" class="form-control field" placeholder="Masukkan Angsuran">
								<span class="help-block small pesan pesan-angsuran"></span>
							</div>
						</div>
						<div class="col-md-4 col-xs-4">
							<!-- persentase -->
							<div class="form-group field-persentase has-feedback">
								<label for="persentase">Persentase</label>
								<div class="input-group">
					                <input type="number" min="0" max="100" step="any" name="persentase" id="persentase" class="form-control field" placeholder="0-100%">
					                <span class="input-group-addon">%</span>
								</div>
								<span class="help-block small pesan pesan-persentase"></span>
							</div>
						</div>
					</div>

					<!-- total -->
					<div class="form-group field-total_detail has-feedback">
						<label for="total_detail">Total</label>
						<div class="input-group">
							<span class="input-group-addon">Rp</span>
			                <input type="number" min="0" step="any" name="total_detail" id="total_detail" class="form-control field" placeholder="Masukkan Total">
			                <span class="input-group-addon">.00</span>
						</div>
						<span class="help-block small pesan pesan-total"></span>
					</div>

					<!-- status -->
					<div class="form-group field-status_detail has-feedback">
						<label for="status_detail">Status</label>
						<select id="status_detail" class="form-control field"></select>
						<span class="help-block small pesan pesan-status_detail"></span>
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