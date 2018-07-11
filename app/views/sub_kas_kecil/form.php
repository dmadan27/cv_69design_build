<?php Defined("BASE_PATH") or die("Dilarang Mengakses File Secara Langsung"); ?>

<div class="modal fade" id="modalSkc">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<!-- header modal -->
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
                <h4 class="modal-title">Form Data Sub Kas Kecil</h4>
			</div>
			<form id="form_skc" role="form" enctype="multipart/form-data">
				<input type="hidden" id="token_form">
				<!-- body modal -->
				<div class="modal-body">
					<div class="row">
						<!-- panel 1: data peribadi -->
						<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
							<fieldset>
								<legend style="font-size: 18px">Data Pribadi</legend>
								<!-- field ID -->
								<div class="form-group field-id has-feedback">
									<label for="id">ID</label>
									<input type="text" name="id" id="id" class="form-control field" placeholder="Masukkan ID Sub Kas Kecil">
									<span class="help-block small pesan pesan-id"></span>
								</div>

								<!-- field nama -->
								<div class="form-group field-nama has-feedback">
									<label for="nama">Nama Sub Kas Kecil</label>
									<input type="text" name="nama" id="nama" class="form-control field" placeholder="Masukkan Nama Sub Kas Kecil">
									<span class="help-block small pesan pesan-nama"></span>
								</div>

								<!-- field alamat -->
								<div class="form-group field-alamat has-feedback">
									<label for="alamat">Alamat</label>
									<textarea id="alamat" name="alamat" class="form-control field" placeholder="Masukkan Alamat"></textarea>
									<span class="help-block small pesan pesan-alamat"></span>
								</div>

								<!-- field no. telp -->
								<div class="form-group field-no_telp has-feedback">
									<label for="no_telp">No. Telepon</label>
									<input type="text" name="no_telp" id="no_telp" class="form-control field" placeholder="Masukkan No. Telepon">
									<span class="help-block small pesan pesan-no_telp"></span>
								</div>

								<!-- field foto -->
								<div class="form-group field-foto has-feedback">
									<label for="foto">Foto</label>
									<input type="file" id="foto" class="form-control field" />
									<span class="help-block small pesan pesan-foto"></span>
								</div>
							</fieldset>
						</div>
						<!-- panel 2: data akun -->
						<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
							<fieldset>
								<legend style="font-size: 18px">Data Akun</legend>
								<!-- field email -->
								<div class="form-group field-email has-feedback">
									<label for="email">Email</label>
									<input type="email" name="email" id="email" class="form-control field" placeholder="Masukkan Email">
									<span class="help-block small pesan pesan-email"></span>
								</div>

								<!-- field password -->
								<div class="form-group field-password has-feedback">
									<label for="password">Password</label>
									<input type="password" name="password" id="password" class="form-control field" placeholder="Masukkan Password">
									<span class="help-block small pesan pesan-password"></span>
								</div>

								<!-- field confirm password -->
								<div class="form-group field-konf_password has-feedback">
									<label for="konf_password">Konfirmasi Password</label>
									<input type="password" name="konf_password" id="konf_password" class="form-control field" placeholder="Masukkan Konfirmasi Password">
									<span class="help-block small pesan pesan-konf_password"></span>
								</div>

								<!-- saldo awal -->
								<div class="form-group field-saldo has-feedback">
									<label for="saldo">Saldo Awal</label>
									<div class="input-group">
										<span class="input-group-addon">Rp</span>
						                <input type="text"  name="saldo" id="saldo" class="form-control field input-mask-uang" placeholder="Masukkan Saldo Awal">
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
							</fieldset>
						</div>
					</div>							
				</div>
				<!-- modal footer -->
				<div class="modal-footer">
					<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Kembali</button>
	                <button type="submit" id="submit_skc" class="btn btn-primary" value="tambah">Simpan Data</button>
				</div>
			</form>
		</div>
	</div>
</div>