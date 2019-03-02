<?php Defined("BASE_PATH") or die("Dilarang Mengakses File Secara Langsung"); ?>

<div class="modal fade" id="modalExport">
	<div class="modal-dialog">
		<div class="modal-content">
			<!-- header modal -->
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
                <h4 class="modal-title">Form Data Detail Pembayaran Proyek</h4>
			</div>
			<form id="form_export" role="form">
				<input type="hidden" id="id">
				<!-- body modal -->
				<div class="modal-body">
					<!-- tgl awal dan tgl akhir -->
					<div class="row">
						<!-- field tgl awal -->
						<div class="col-md-6">
							<div class="form-group field-tgl_awal has-feedback">
								<label for="tgl_awal">Tanggal</label>
								<input type="text" id="tgl_awal" class="form-control field datepicker" placeholder="Masukkan Tanggal Awal">
								<span class="help-block small pesan pesan-tgl_awal"></span>
							</div>
						</div>

						<!-- field tgl akhir -->
						<div class="col-md-6">
							<div class="form-group field-tgl_akhir has-feedback">
								<label for="tgl_akhir">Tanggal</label>
								<input type="text" id="tgl_akhir" class="form-control field datepicker" placeholder="Masukkan Tanggal Akhir">
								<span class="help-block small pesan pesan-tgl_akhir"></span>
							</div>
						</div>
					</div>

				</div>
				<!-- modal footer -->
				<div class="modal-footer">
					<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Kembali</button>
	                <button type="submit" id="submit_export" class="btn btn-primary">Export</button>
				</div>
			</form>
		</div>
	</div>
</div>