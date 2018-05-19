<?php Defined("BASE_PATH") or die("Dilarang Mengakses File Secara Langsung"); ?>

<div class="form-login">
	<p class="login-box-msg">Silahkan Login untuk Masuk Ke Sistem</p>
	<form id="form_login">
		<div class="form-group field-username has-feedback">
			<input type="text" id="username" name="username" class="form-control field" placeholder="Email">
			<span class="glyphicon glyphicon-envelope form-control-feedback"></span>
			<span class="help-block small pesan pesan-username"></span>
		</div>
		<div class="form-group field-password has-feedback">
			<input type="password" id="password" name="password" class="form-control field" placeholder="Password">
			<span class="glyphicon glyphicon-lock form-control-feedback"></span>
			<span class="help-block small pesan pesan-password"></span>
		</div>
		<div class="row">
			<div class="col-md-12">
				<button type="submit" id="submit_login" class="btn btn-primary btn-block btn-flat">Login</button>
			</div>
		</div>
	</form>
	<hr>
	<!-- lupa password -->
	<a href="javascript:void(0)" id="lupaPassword"><i class="fa fa-lock"></i> Lupa Password ?</a><br>
</div>
	