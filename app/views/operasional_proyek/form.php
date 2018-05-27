<?php  ?>

<div class="modal fade" id="modalBank">
	<div class="modal-dialog">
		<div class="modal-content">
			<!-- header modal -->
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
                <h4 class="modal-title">Form Data Operasional Proyek</h4>
			</div>
			<form id="form_operasional_proyek" role="form">
				<input type="hidden" id="id">
				<input type="hidden" name="id_proyek">
				<!-- <input type="hidden" id="token_bank_edit"> -->
				<!-- body modal -->
				<div class="modal-body">
					<!-- field nama -->
					<div class="form-group field-nama has-feedback">
						<label for="id_proyek">ID Proyek</label>
						<select name="id_proyek" id="id_proyek" class="form-control">
							<option>Proyek A</option>
						</select>
						<span class="help-block small pesan pesan-id_proyek"></span>
					</div>

					<!-- saldo awal -->
					<div class="form-group field-tgl has-feedback">
						<label for="tgl">Tanggal</label>
						<div class="input-group">
							<span class="input-group-addon">Rp</span>
			                <div class="input-group date">
                                  <div class="input-group-addon">
                                      <i class="fa fa-calendar"></i>
                                  </div>
                                  <input type="text"  name="tgl" class="form-control pull-right field" id="datepicker">
                                  <span class="help-block small pesan pesan-tgl"></span>
                            </div>
			            </div>
					</div>

				</div>
				<!-- modal footer -->
				<div class="modal-footer">
					<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Kembali</button>
	                <button type="submit" id="submit_operasional_proyek" class="btn btn-primary" value="tambah">Simpan Data</button>
				</div>
			</form>
		</div>
	</div>
</div>