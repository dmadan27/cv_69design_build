<?php Defined("BASE_PATH") or die("Dilarang Mengakses File Secara Langsung"); ?>

<div class="form-login">
	<p class="login-box-msg">Silahkan Masukkan Password Baru Anda</p>
	<form id="form_reset_password">
		<div class="form-group field-password_baru has-feedback">
			<div class="input-group">
				<input type="password" id="password_baru" name="password_baru" class="form-control field" placeholder="Masukkan Password Baru">
				<!-- <span class="glyphicon glyphicon-lock form-control-feedback"></span> -->
				<span class="input-group-addon"><a class="visible" role='button'><i class="fa fa-eye-slash"></i></a></span>
			</div>
			<span class="help-block small pesan pesan-password_baru"></span>
		</div>
		<div class="form-group field-password_konf has-feedback">
			<div class="input-group">
				<input type="password" id="password_konf" name="password_konf" class="form-control field" placeholder="Masukkan Konfirmasi Password">
				<!-- <span class="glyphicon glyphicon-lock form-control-feedback"></span> -->
				<span class="input-group-addon"><a class="visible" role='button'><i class="fa fa-eye-slash"></i></a></span>
			</div>
				
			<span class="help-block small pesan pesan-password_konf"></span>
		</div>
		<div class="row">
			<div class="col-md-12">
				<button type="submit" id="submit_reset_password" class="btn btn-primary btn-block btn-flat">Reset Password</button>
			</div>
		</div>
	</form>
	<hr>
	<a href="<?= BASE_URL; ?>"><i class="fa fa-lock"></i> Klik Disini Untuk Login</a><br>
</div>
	