<?php Defined("BASE_PATH") or die("Dilarang Mengakses File Secara Langsung"); ?>

<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			<?= $this->propertyPage['main']; ?>
			<small><?= $this->propertyPage['sub']; ?></small>
		</h1>
		<!-- breadcrumb -->
		<ol class="breadcrumb">
			<li><a href="<?= BASE_URL ?>"><i class="fa fa-dashboard"></i> Home</a></li>
			<li class="active">Profil</li>
		</ol>
		<!-- end breadcrumb -->
	</section>
	<!-- Main content -->
	<section class="content container-fluid">
		<div class="row">
			
			<!-- panel profil image -->
			<div class="col-md-3 col-xs-12">
				<div class="box box-primary">
					<div class="box-body box-profile">
						<a href="<?= $this->data['foto']; ?>" class="image-popup" title="<?= $this->data['nama']; ?>">
							<img class="profile-user-img img-responsive img-circle" src="<?= $this->data['foto']; ?>" alt="User profile picture">
						</a>
						<h3 class="profile-username text-center"><?= $this->data['nama']; ?></h3>
              			<p class="text-muted text-center"><?= $this->data['level']; ?></p>
              			<ul class="list-group list-group-unbordered">
              				<?php 
              					if($this->data['saldo']){
              						?>
              						<li class="list-group-item">
				                  		<b>Saldo</b> <a class="pull-right"><?= $this->data['saldo']; ?></a>
				                	</li>
              						<?php
              					}
              				?>
		                	<li class="list-group-item">
		                  		<b>Email</b> <a class="pull-right"><?= $this->data['email']; ?></a>
		                	</li>
		                	<li class="list-group-item">
		                 	 	<b>Status</b> <a class="pull-right"><?= $this->data['status']; ?></a>
		                	</li>
		              	</ul>
		              	<button class="btn btn-primary btn-block" type="button" id="btn_edit"><b>Edit Profil</b></button>
		              	<button class="btn btn-success btn-block" id="edit_foto" title="Edit Foto"><i class="fa fa-camera"></i> Edit Foto</button>
		              	<a href="<?= BASE_URL; ?>" class="btn btn-default btn-block" role="button"><b>Kembali</b></a>
					</div>
				</div>
			</div>

			<!-- panel data profil -->
			<div class="col-md-9 col-xs-12">
				<div class="nav-tabs-custom">
					
					<ul class="nav nav-tabs">
              			<li class="active"><a href="#data-profil" data-toggle="tab">Data Profil</a></li>
              			<li><a href="#ganti-password" data-toggle="tab">Ganti Passsword</a></li>
            		</ul>
            		
            		<div class="tab-content">
              			
              			<!-- Data Profil -->
              			<div class="active tab-pane" id="data-profil">
                			<div class="row">
                				<div class="col-md-12">
                					<table class="table table-hover">
                						<!-- ID -->
                						<tr>
                							<td><strong>ID</strong></td>
                							<td><?= $this->data['id']; ?></td>
                						</tr>

                						<!-- Nama -->
                						<tr>
                							<td><strong>Nama</strong></td>
                							<td><?= $this->data['nama']; ?></td>
                						</tr>

                						<!-- Alamat -->
                						<tr>
                							<td><strong>Alamat</strong></td>
                							<td><?= $this->data['alamat']; ?></td>
                						</tr>

                						<!-- No. Telepon -->
                						<tr>
                							<td><strong>No. Telepon</strong></td>
                							<td><?= $this->data['no_telp']; ?></td>
                						</tr>

                						<!-- Email -->
                						<tr>
                							<td><strong>Email</strong></td>
                							<td><?= $this->data['email']; ?></td>
                						</tr>

                						<!-- Status -->
                						<tr>
                							<td><strong>Status</strong></td>
                							<td><?= $this->data['status']; ?></td>
                						</tr>
                					</table>
                				</div>
                			</div>
              			</div>
              			
              			<!-- Ganti Password -->
	              		<div class="tab-pane" id="ganti-password">
	                		<div class="row">
	                			<div class="col-md-12">
	                				<form id="form_ganti_password" class="form-horizontal">
	                					<!-- Password Lama -->
	                					<div class="form-group field-password_lama has-feedback">
	                						<label for="password_lama" class="col-sm-2 control-label">Password Lama</label>
	                						<div class="col-sm-10">
	                							<input type="password" id="password_lama" class="form-control field" placeholder="Masukkan Password Lama">
	                							<span class="help-block small pesan pesan-password_lama"></span>
	                						</div>
	                					</div>

	                					<!-- Password Baru -->
	                					<div class="form-group field-password_baru has-feedback">
	                						<label for="password_baru" class="col-sm-2 control-label">Password Baru</label>
	                						<div class="col-sm-10">
	                							<input type="password" id="password_baru" class="form-control field" placeholder="Masukkan Password Baru">
	                							<span class="help-block small pesan pesan-password_baru"></span>
	                						</div>
	                					</div>

	                					<!-- Konfirmasi Password -->
	                					<div class="form-group field-password_konf has-feedback">
	                						<label for="password_konf" class="col-sm-2 control-label">Konfirmasi Password</label>
	                						<div class="col-sm-10">
	                							<input type="password" id="password_konf" class="form-control field" placeholder="Masukkan Konfrimasi Password">
	                							<span class="help-block small pesan pesan-password_konf"></span>
	                						</div>
	                					</div>

	                					<!-- button submit -->
	                					<div class="form-group">
	                						<div class="col-sm-offset-2 col-sm-10">
					                      		<button id="submit_ganti_password" type="submit" class="btn btn-danger">Ganti Password</button>
					                    	</div>
	                					</div>
	                				</form>
	                			</div>
	                		</div>
	              		</div>
	              		<!-- /.tab-pane -->

		            </div>
		            <!-- /.tab-content -->

				</div>
			</div>

		</div>
	</section>
	<!-- /.content -->

	<?php 
		include('form.php');
		include('form_foto.php');
	?>

</div>