<?php Defined("BASE_PATH") or die("Dilarang Mengakses File Secara Langsung"); ?>

<div class="modal fade" id="modalFoto">
	<div class="modal-dialog">
		<div class="modal-content">
			<!-- header modal -->
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
                <h4 class="modal-title">Edit Foto</h4>
			</div>
			<form id="form_edit_foto" role="form" enctype="multipart/form-data">
				<!-- body modal -->
				<div class="modal-body">
					<div class="form-group field-foto has-feedback">
						<label for="foto">Foto</label>
						<input type="file" id="foto" class="form-control field"/>
						<span class="help-block small pesan pesan-foto"></span>
					</div>
				</div>
				<!-- modal footer -->
				<div class="modal-footer">
					<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Kembali</button>
					<button type="button" id="delete_foto" class="btn btn-danger" value="hapus-foto">Hapus Foto</button>
	                <button type="submit" id="submit_edit_foto" class="btn btn-primary" value="edit-foto">Ganti Foto</button>
				</div>
			</form>
		</div>
	</div>
</div>