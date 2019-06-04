<?php Defined("BASE_PATH") or die(ACCESS_DENIED); ?>

<div class="modal fade" id="modal-export-start-end-date">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<!-- header modal -->
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<h4 class="modal-export-title">Export</h4>
			</div>
			<form id="form_export" role="form">
				<input type="hidden" id="export-data">

				<!-- body modal -->
				<div class="modal-body">

					<!-- field Dari Tanggal -->
                    <div class="form-group field-tgl_awal has-feedback">
                        <label for="tgl-awal">Dari Tanggal</label>
                        <input type="text" name="tgl-awal" id="tgl-awal" class="form-control datepicker field" placeholder="Dari Tanggal">
                        <span class="help-block small pesan pesan-tgl_awal"></span>
					</div>
					
					<!-- field Sampai Tanggal -->
                    <div class="form-group field-tgl_akhir has-feedback">
                        <label for="tgl-akhir">Sampai Tanggal</label>
                        <input type="text" name="tgl-akhir" id="tgl-akhir" class="form-control datepicker field" placeholder="Sampai Tanggal">
                        <span class="help-block small pesan pesan-tgl_akhir"></span>
                    </div>

				</div>

				<!-- modal footer -->
				<div class="modal-footer">
					<button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal">Kembali</button>
					<button type="submit" id="btn-export-start-end-date" class="btn btn-success btn-flat">Ekspor</button>
				</div>
			</form>
		</div>
	</div>
</div>