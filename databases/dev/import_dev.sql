# Database Sistem Informasi CV 69 Design & Build #
# Testing SQL #

-- password ABCDE

-- insert tabel kas besar
CALL p_tambah_kas_besar('KB001', 'KAS BESAR', 'SUKABUMI', '081234567890', 'kas_besar@69designbuild.com', '', 'AKTIF', '$2y$10$xGiq.6J6z9CUeze4B3oqAOquc6hXvYvZehkYV1brgWYrxjpoG5fGG', 'KAS BESAR', NULL);
CALL p_tambah_kas_besar('KB002', 'DODIK', 'SUKABUMI', '087822678678', 'dodik@69designbuild.com', '', 'AKTIF', '$2y$10$xGiq.6J6z9CUeze4B3oqAOquc6hXvYvZehkYV1brgWYrxjpoG5fGG', 'KAS BESAR', NULL);

-- insert tabel kas kecil
CALL p_tambah_kas_kecil('KK001', 'KAS KECIL', 'SUKABUMI', '081234567890', 'kas_kecil@69designbuild.com', '', 1500000, CURDATE(), 'AKTIF', '$2y$10$xGiq.6J6z9CUeze4B3oqAOquc6hXvYvZehkYV1brgWYrxjpoG5fGG', 'KAS KECIL', NULL);
CALL p_tambah_kas_kecil('KK002', 'AGUNG', 'SUKABUMI', '087812345678', 'agung@designbuild.com', '', 1000000, CURDATE(), 'AKTIF', '$2y$10$xGiq.6J6z9CUeze4B3oqAOquc6hXvYvZehkYV1brgWYrxjpoG5fGG', 'KAS KECIL', NULL);

-- Insert Sub Kas Kecil
CALL p_tambah_sub_kas_kecil('LOG001', 'UJANG', 'SUKABUMI', '081234567890', 'ujang@designbuild.com', '', 100000, CURDATE(), 'AKTIF', '$2y$10$xGiq.6J6z9CUeze4B3oqAOquc6hXvYvZehkYV1brgWYrxjpoG5fGG', 'SUB KAS KECIL', NULL);
CALL p_tambah_sub_kas_kecil('LOG002', 'ASEP', 'SUKABUMI', '081234567890', 'asep@designbuild.com', '', 100000, CURDATE(), 'AKTIF', '$2y$10$xGiq.6J6z9CUeze4B3oqAOquc6hXvYvZehkYV1brgWYrxjpoG5fGG', 'SUB KAS KECIL', NULL);
CALL p_tambah_sub_kas_kecil('LOG003', 'JAKA', 'SUKABUMI', '081234567890', 'jaka@designbuild.com', '', 100000, CURDATE(), 'AKTIF', '$2y$10$xGiq.6J6z9CUeze4B3oqAOquc6hXvYvZehkYV1brgWYrxjpoG5fGG', 'SUB KAS KECIL', NULL);
CALL p_tambah_sub_kas_kecil('LOG004', 'FAJAR', 'SUKABUMI', '081234567890', 'fajar@designbuild.com', '', 100000, CURDATE(), 'AKTIF', '$2y$10$xGiq.6J6z9CUeze4B3oqAOquc6hXvYvZehkYV1brgWYrxjpoG5fGG', 'SUB KAS KECIL', NULL);
CALL p_tambah_sub_kas_kecil('LOG005', 'HENDRO', 'SUKABUMI', '081234567890', 'hendro@designbuild.com', '', 100000, CURDATE(), 'AKTIF', '$2y$10$xGiq.6J6z9CUeze4B3oqAOquc6hXvYvZehkYV1brgWYrxjpoG5fGG', 'SUB KAS KECIL', NULL);

-- Hapus Operasional Proyek
-- ini testing data aja ya ikutin format ini aja  (perlu di revisi)
CALL hapus_operasional_proyek(
'OPRY-PRY20180001-0001','PRY20180001', 2 , 'KB001', 'DIS0002','2018-10-21','MASJAKA GANTENG','TEKNIS', 10.00, 10.00, 'TUNAI','LUNAS','HEHE');



-- =======================================================================================
-- ===============================VVV Tambah Proyek VVV===================================

-- Insert Proyek
-- Proyek 1 - PRY2018001 (Berjalan)
INSERT INTO proyek
	(id, pemilik, tgl, pembangunan, luas_area, alamat, kota, estimasi, total, dp, cco, status)
VALUES
	('PRY20180001', 'JAJANG', '2018-01-01', 'RUMAH 10 LANTAI', 1000, 'BANDUNG', 'BANDUNG', 12, 100000000, 50000000, 0, 'BERJALAN');

-- Insert Detail Proyek - PRY2018001 (Berjalan)
-- INSERT INTO detail_proyek
-- 	(id, id_proyek, angsuran, persentase, total, status)
-- VALUES
-- 	(null,'PRY20180001', 'ANGSURAN KE-1', '20', 10000000, 'BELUM DIBAYAR'),
-- 	(null,'PRY20180001', 'ANGSURAN KE-2', '50', 10000000, 'BELUM DIBAYAR'),
-- 	(null,'PRY20180001', 'ANGSURAN KE-3', '100', 30000000, 'BELUM DIBAYAR');

-- Insert logisitik proyek (logistik yang menagani proyek) - PRY2018001
INSERT INTO logistik_proyek (id_proyek, id_sub_kas_kecil) VALUES ('PRY20180001', 'LOG001');
INSERT INTO logistik_proyek (id_proyek, id_sub_kas_kecil) VALUES ('PRY20180001', 'LOG002');
INSERT INTO logistik_proyek (id_proyek, id_sub_kas_kecil) VALUES ('PRY20180001', 'LOG004');

-- Insert Proyek
-- Proyek 2 - PRY2018002 (Berjalan)
INSERT INTO proyek
	(id, pemilik, tgl, pembangunan, luas_area, alamat, kota, estimasi, total, dp, cco, status)
VALUES
	('PRY20180002', 'Asirwanda Mustofa', '2018-01-01', 'RUMAH 10 LANTAI', 1000, 'Jl. Laksmiwati', 'BANDUNG', 12, 100000000, 50000000, 0, 'BERJALAN');

-- Insert Detail Proyek - PRY2018002 (Berjalan)
-- INSERT INTO detail_proyek
-- 	(id,id_proyek, angsuran, persentase, total, status)
-- VALUES
-- 	(null,'PRY20180002', 'ANGSURAN KE-1', '20', 10000000, 'BELUM DIBAYAR'),
-- 	(null,'PRY20180002', 'ANGSURAN KE-2', '50', 10000000, 'BELUM DIBAYAR');

-- Insert logisitik proyek (logistik yang menagani proyek) - PRY2018002
INSERT INTO logistik_proyek (id_proyek, id_sub_kas_kecil) VALUES ('PRY20180002', 'LOG001');
INSERT INTO logistik_proyek (id_proyek, id_sub_kas_kecil) VALUES ('PRY20180002', 'LOG003');
INSERT INTO logistik_proyek (id_proyek, id_sub_kas_kecil) VALUES ('PRY20180002', 'LOG005');


-- =======================================================================================
-- =====================VVV Pengajuan Sub Kas Kecil VVV===================================

-- Insert Pengajuan Sub Kas Kecil
-- PGSKK-PRY2018001-LOG001-0001
INSERT INTO pengajuan_sub_kas_kecil
	(id, id_sub_kas_kecil, id_proyek, tgl, total, dana_disetujui, status, status_laporan)
VALUES
	('PGSKK-PRY20180001-LOG001-0001', 'LOG001', 'PRY20180001', '2018-07-31', 15500, 0, '1', null);

-- Insert Detail Pengajuan
-- PGSKK-PRY2018001-LOG001-0001
INSERT INTO detail_pengajuan_sub_kas_kecil
	(id_pengajuan, nama, jenis, satuan, qty, harga, subtotal)
VALUES
	('PGSKK-PRY20180001-LOG001-0001', 'SEMEN', 'T', 'KARUNG', 10, 1000, 10000),
	('PGSKK-PRY20180001-LOG001-0001', 'BATU BATA', 'T', 'PCS', 100, 500, 5000),
	('PGSKK-PRY20180001-LOG001-0001', 'SEKOP', 'T', 'PCS', 1, 500, 500);


-- =======================================================================================
-- ======================VVV Laporan Sub Kas Kecil VVV====================================
