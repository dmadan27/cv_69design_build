<?php Defined("BASE_PATH") or die("Dilarang Mengakses File Secara Langsung"); ?>

<!-- menu beranda -->
<li class="menu-beranda"><a href="<?= BASE_URL ?>"><i class="fa fa-link"></i> <span>Beranda</span></a></li>
<!-- menu data proyek -->
<li class="menu-proyek"><a href="<?= BASE_URL."proyek/"; ?>"><i class="fa fa-link"></i> <span>Data Proyek</span></a></li>
<!-- menu data pengajuan kas kecil -->
<li class="menu-pengajuan-kas-kecil"><a href="<?= BASE_URL."pengajuan-kas-kecil/"; ?>"><i class="fa fa-link"></i> <span>Data Pengajuan Kas Kecil</span></a></li>
<!-- menu data pengajuan sub kas kecil -->
<li class="treeview menu-pengajuan-sub-kas-kecil">
	<a href="javascript:void(0)"><i class="fa fa-link"></i> <span>Data Pengajuan Sub Kas Kecil</span>
		<span class="pull-right-container">
  		<i class="fa fa-angle-left pull-right"></i>
		</span>
	</a>
	<ul class="treeview-menu">
		<li class="menu-pengajuan"><a href="<?= BASE_URL."pengajuan-sub-kas-kecil/"; ?>"><i class="fa fa-circle-o"></i> Data Pengajuan</a></li>
		<li class="menu-laporan"><a href="<?= BASE_URL."laporan-sub-kas-kecil/"; ?>"><i class="fa fa-circle-o"></i> Data Laporan Pengajuan</a></li>
	</ul>
</li>
<!-- menu data saldo kas kecil -->
<li class="menu-saldo-kas-kecil"><a href="<?= BASE_URL."saldo-kas-kecil/"; ?>"><i class="fa fa-link"></i> <span>Data Saldo</span></a></li>
<!-- menu data sub kas kecil -->
<li class="menu-sub-kas-kecil"><a href="<?= BASE_URL."sub-kas-kecil/"; ?>"><i class="fa fa-link"></i> <span>Data Sub Kas Kecil</span></a></li>