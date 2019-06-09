<?php Defined("BASE_PATH") or die(ACCESS_DENIED); ?>

<div class="modal fade" id="modal-export-start-end-date">
	<div class="modal-dialog">
		<div class="modal-content">
			<!-- header modal -->
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
                <h4 class="modal-title modal-export-title">Export</h4>
			</div>
			<form id="form-export-start-end-date" role="form">
				<input type="hidden" id="export-data">

				<!-- body modal -->
				<div class="modal-body">

					<!-- Tanggal awal dan akhir -->
					<div class="form-group field-tgl_export has-feedback">
						<label>Tanggal Export</label>
						<div class="input-group">
							<input type="text" id="tgl-awal" class="form-control field datepicker" placeholder="Masukkan Tanggal Awal" autocomplete="off">
							<span class="input-group-addon">s.d</span>
							<input type="text" id="tgl-akhir" class="form-control field datepicker" placeholder="Masukkan Tanggal Akhir" autocomplete="off">
						</div>
						<span class="help-block small pesan pesan-tgl_export"></span>
					</div>
						
				</div>
				<!-- modal footer -->
				<div class="modal-footer">
					<button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal">Kembali</button>
	                <button type="submit" id="btn-export-start-end-date" class="btn btn-primary btn-flat">Export</button>
				</div>
			</form>
		</div>
	</div>
</div>