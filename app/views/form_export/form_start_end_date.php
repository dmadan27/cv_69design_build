<?php Defined("BASE_PATH") or die(ACCESS_DENIED); ?>

<div class="modal fade" id="modalExport">
	<div class="modal-dialog">
		<div class="modal-content">
			<!-- header modal -->
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
                <h4 class="modal-title">Export</h4>
			</div>
			<form id="form_export" role="form">
				<input type="hidden" id="id_for_export">
                <input type="hidden" id="exportType">
				<!-- body modal -->
				<div class="modal-body">

					<!-- Tanggal awal dan akhir -->
					<div class="form-group field-tgl_export has-feedback">
						<label>Tanggal Export</label>
						<div class="input-group">
							<input type="text" id="tgl_awal" class="form-control field datepicker" placeholder="Masukkan Tanggal Awal">
							<span class="input-group-addon">s.d</span>
							<input type="text" id="tgl_akhir" class="form-control field datepicker" placeholder="Masukkan Tanggal Akhir">
						</div>
						<span class="help-block small pesan pesan-tgl_export"></span>
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