<?php Defined("BASE_PATH") or die("Dilarang Mengakses File Secara Langsung"); ?>

<!-- menu beranda -->
<li class=""><a href="<?= BASE_URL ?>"><i class="fa fa-link"></i> <span>Beranda</span></a></li>
<!-- menu data proyek -->
<li class=""><a href="<?= BASE_URL."proyek/"; ?>"><i class="fa fa-link"></i> <span>Data Proyek</span></a></li>
<!-- menu data pengajuan sub kas kecil -->
<li class="treeview">
	<a href="javascript:void(0)"><i class="fa fa-link"></i> <span>Data Pengajuan Sub Kas Kecil</span>
		<span class="pull-right-container">
  		<i class="fa fa-angle-left pull-right"></i>
		</span>
	</a>
	<ul class="treeview-menu">
		<li><a href="<?= BASE_URL."pengajuan-sub-kas-kecil/"; ?>">Data Pengajuan</a></li>
		<li><a href="<?= BASE_URL."pengajuan-sub-kas-kecil/laporan/"; ?>">Data Laporan Pengajuan</a></li>
	</ul>
</li>
<!-- menu data saldo kas kecil -->
<li class=""><a href="<?= BASE_URL."saldo/"; ?>"><i class="fa fa-link"></i> <span>Data Saldo</span></a></li>
<!-- menu data sub kas kecil -->
<li class=""><a href="<?= BASE_URL."sub-kas-kecil/"; ?>"><i class="fa fa-link"></i> <span>Data Sub Kas Kecil</span></a></li>