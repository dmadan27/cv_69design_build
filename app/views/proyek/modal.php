<?php Defined("BASE_PATH") or die("Dilarang Mengakses File Secara Langsung"); ?>

<div class="modal fade" id="modalDetail">
	<div class="modal-dialog">
		<div class="modal-content">
			<!-- header modal -->
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
                <h4 class="modal-title">Form Data Detail Pembayaran Proyek</h4>
			</div>
			<form id="form_detail" role="form">
				<input type="hidden" id="id_detail">
				<!-- body modal -->
				<div class="modal-body">
					<!-- tgl dan nama pembayaran -->
					<div class="row">
						<div class="col-md-4 col-sm-4 col-xs-12">
							<!-- field tgl -->
							<div class="form-group field-tgl_detail has-feedback">
								<label for="tgl_detail">Tanggal</label>
								<input type="text" name="tgl_detail" id="tgl_detail" class="form-control field datepicker" placeholder="Masukkan Tanggal Pembayaran">
								<span class="help-block small pesan pesan-tgl_detail"></span>
							</div>
						</div>
						<div class="col-md-8 col-sm-8 col-xs-12">
							<!-- field nama pembayaran -->
							<div class="form-group field-nama_detail has-feedback">
								<label for="nama_detail">Nama Pembayaran</label>
								<input type="text" name="nama_detail" id="nama_detail" class="form-control field" placeholder="Masukkan Nama Pembayaran">
								<span class="help-block small pesan pesan-nama_detail"></span>
							</div>
						</div>
					</div>

					<!-- bank dan total -->
					<div class="row">
						<div class="col-md-4 col-sm-4 col-xs-12">
							<!-- Id Bank -->
							<div class="form-group field-id_bank has-feedback">
								<label for="id_bank">Bank</label>
								<select id="id_bank" class="form-control field select2" style="width: 100%;"></select>
								<span class="help-block small pesan pesan-id_bank"></span>
							</div>
							
						</div>
						<div class="col-md-8 col-md-8 col-xs-12">
							<!-- total -->
							<div class="form-group field-total_detail has-feedback">
								<label for="total_detail">Total</label>
								<div class="input-group">
									<span class="input-group-addon">Rp</span>
									<input type="text" name="total_detail" id="total_detail" class="form-control field input-mask-uang" placeholder="Masukkan Total">
									<span class="input-group-addon">.00</span>
								</div>
								<span class="help-block small pesan pesan-total_detail"></span>
							</div>
							
						</div>
					</div>

				</div>
				<!-- modal footer -->
				<div class="modal-footer">
					<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Kembali</button>
	                <button type="submit" id="submit_detail" class="btn btn-primary" value="tambah">Tambah Detail Pembayaran</button>
				</div>
			</form>
		</div>
	</div>
</div>