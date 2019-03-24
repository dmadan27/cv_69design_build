# Seeder Menu #

INSERT INTO menu (
    name, table_name, url, class, icon, position
) VALUES 
('Beranda', NULL, 'home', 'menu-home', 'fa fa-dashboard', 1),
('Bank', 'bank', 'bank', 'menu-bank', 'fa fa-bank', 2),
('Ditributor', 'distributor', 'distributor', 'menu-distributor', 'fa fa-truck', 3),
('Proyek', 'proyek', 'proyek', 'menu-proyek', 'fa fa-cubes', 4),
('Operasional Proyek', 'operasional_proyek', 'operasional-proyek', 'menu-operasional-proyek', 'fa fa-usd', 5),
('Operasional', 'operasional', 'operasional', 'menu-operasional', 'fa fa-usd', 6),
('Pengajuan Kas Kecil', 'pengajuan_kas_kecil', 'pengajuan-kas-kecil', 'menu-pengajuan-kas-kecil', 'fa-pencil-square-o', 7),
('Pengajuan Sub Kas Kecil', 'pengajuan_sub_kas_kecil', 'pengajuan-sub-kas-kecil', 'menu-pengajuan-sub-kas-kecil', 'fa-pencil-square-o', 8),
('Laporan Pengajuan SKK', 'pengajuan_sub_kas_kecil', 'laporan-sub-kas-kecil', 'menu-laporan-sub-kas-kecil', 'fa fa-check-square-o', 9),
('Kas Besar', 'kas_besar', 'kas-besar', 'menu-kas-besar', 'fa fa-user-plus', 10),
('Kas Kecil', 'kas_kecil', 'kas-kecil', 'menu-kas-kecil', 'fa fa-user', 11),
('Sub Kas Kecil', 'sub_kas_kecil', 'sub-kas-kecil', 'menu-sub-kas-kecil', 'fa fa-users', 12),
('User', 'user', 'user', 'menu-user', 'fa fa-users', 13),
('Profile', NULL, NULL, NULL, NULL, NULL);

# End Seeder Menu #