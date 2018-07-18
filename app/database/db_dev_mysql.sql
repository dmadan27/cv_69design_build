# Database Sistem Informasi CV 69 Design & Build #
# Versi 1.0 for MySQL #
# ENGINE=InnoDb

# ============================== Tabel ============================== #

-- Tabel User
CREATE TABLE IF NOT EXISTS user(
	username varchar(50) NOT NULL UNIQUE, -- pk
	password text, -- hash bcypt
	level enum('KAS BESAR', 'KAS KECIL'), -- v1
	-- level enum('OWNER', 'KAS BESAR', 'KAS KECIL'), -- v2
	-- status enum('AKTIF', 'NONAKTIF'), -- status aktif username

	CONSTRAINT pk_user_username PRIMARY KEY(username)
)ENGINE=InnoDb;

-- Tabel Kas Besar
CREATE TABLE IF NOT EXISTS kas_besar(
	id varchar(10) NOT NULL UNIQUE, -- pk
	-- username varchar(25), -- fk user, , default username dan email sama
	nama varchar(255),
	alamat text,
	no_telp varchar(20),
	email varchar(50) UNIQUE, -- fk user
	foto text,
	status enum('AKTIF', 'NONAKTIF'),

	CONSTRAINT pk_kas_besar_id PRIMARY KEY(id),
	CONSTRAINT fk_kas_besar_email FOREIGN KEY(email) REFERENCES user(username)
		ON DELETE SET NULL ON UPDATE CASCADE
)ENGINE=InnoDb;

-- Tabel Kas Kecil
CREATE TABLE IF NOT EXISTS kas_kecil(
	id varchar(10) NOT NULL UNIQUE, -- pk
	-- username varchar(25), -- fk user, default username dan email sama
	nama varchar(50), -- nama kas kecil
	alamat text,
	no_telp varchar(20),
	email varchar(50) UNIQUE, -- fk user
	foto text,
	saldo double(12,2), -- saldo kas kecil, default 0
	status enum('AKTIF', 'NONAKTIF'), -- status kas kecil

	CONSTRAINT pk_kas_kecil_id PRIMARY KEY(id),
	CONSTRAINT fk_kas_kecil_email FOREIGN KEY(email) REFERENCES user(username)
		ON DELETE SET NULL ON UPDATE CASCADE
)ENGINE=InnoDb;

-- Tabel Sub Kas Kecil (logistik)
CREATE TABLE IF NOT EXISTS sub_kas_kecil(
	id varchar(10) NOT NULL UNIQUE, -- pk, id+increment, contoh: log-001
	nama varchar(255),
	alamat text,
	no_telp varchar(20),
	email varchar(50), -- username
	password text, -- pasword hash
	foto text, -- simpan urlnya
	saldo double(12,2), -- saldo master, default 0
	status enum('AKTIF', 'NONAKTIF'),

	CONSTRAINT pk_sub_kas_kecil_id PRIMARY KEY(id)
)ENGINE=InnoDb;

-- TABEL TOKEN SUB KAS KECIL (UNTUK MOBILE)
CREATE TABLE IF NOT EXISTS token_sub_kas_kecil(
	id int NOT NULL AUTO_INCREMENT,
	id_sub_kas_kecil varchar(10),
	token text,
	tgl_buat datetime,
	tgl_exp datetime,

	CONSTRAINT pk_token_sub_kas_kecil PRIMARY KEY(id),
	CONSTRAINT fk_token_sub_kas_kecil_sub_kas_kecil FOREIGN KEY(id_sub_kas_kecil) REFERENCES sub_kas_kecil(id)
		ON DELETE RESTRICT ON UPDATE RESTRICT
)ENGINE=InnoDb;

-- Tabel Proyek
CREATE TABLE IF NOT EXISTS proyek(
	id varchar(50) NOT NULL UNIQUE, -- pk, otomatis
	pemilik varchar(255),
	tgl date,
	pembangunan varchar(255), -- keterangan yg dibangun
	luas_area double(10,2),
	alamat text,
	kota varchar(100),
	estimasi smallint, -- estimasi waktu dalam bulan
	total double(12,2), -- total nilai rab
	dp double(12,2), -- dp
	cco double(12,2), -- change contract order
	status enum('SELESAI', 'BERJALAN'), -- status proyek

	CONSTRAINT pk_proyek_id PRIMARY KEY(id)
)ENGINE=InnoDb;

-- Tabel Detail Proyek (angsuran)
CREATE TABLE IF NOT EXISTS detail_proyek(
	id int NOT NULL AUTO_INCREMENT, -- pk
	id_proyek varchar(50), -- fk
	angsuran varchar(255), -- nama angsuran
	persentase char(3), -- persentase proyek
	total double(12,2), -- total angsuran
	status enum('LUNAS', 'BELUM DIBAYAR'), -- status angsuran

	CONSTRAINT pk_detail_proyek_id PRIMARY KEY(id),
	CONSTRAINT fk_detail_proyek_id_proyek FOREIGN KEY(id_proyek) REFERENCES proyek(id)
		ON DELETE RESTRICT ON UPDATE CASCADE
)ENGINE=InnoDb;

-- Tabel Logistik Proyek
CREATE TABLE IF NOT EXISTS logistik_proyek(
	id int NOT NULL AUTO_INCREMENT, -- pk
	id_proyek varchar(50), -- fk proyek
	id_sub_kas_kecil varchar(10), -- fk sub kas kecil

	CONSTRAINT pk_logistik_proyek_id PRIMARY KEY(id),
	CONSTRAINT fk_logistik_proyek_id_proyek FOREIGN KEY(id_proyek) REFERENCES proyek(id)
		ON DELETE RESTRICT ON UPDATE CASCADE,
	CONSTRAINT fk_logistik_proyek_id_sub_kas_kecil FOREIGN KEY(id_sub_kas_kecil) REFERENCES sub_kas_kecil(id)
		ON DELETE RESTRICT ON UPDATE CASCADE
)ENGINE=InnoDb;

-- Tabel Bank
CREATE TABLE IF NOT EXISTS bank(
	id int NOT NULL AUTO_INCREMENT, -- pk
	nama varchar(255), -- nama bank / jenis bank, Bank BCA, Giro BCA, Mandiri, dll
	saldo double(12,2), -- saldo bank
	status enum('AKTIF', 'NONAKTIF'),

	CONSTRAINT pk_bank_id PRIMARY KEY(id)
)ENGINE=InnoDb;

-- Tabel Operasional (Pengeluaran di luar proyek)
-- NOTE --
	/* Penambahan di foreign key bank untuk pendataan dana yang keluar */
CREATE TABLE IF NOT EXISTS operasional(
	id int NOT NULL AUTO_INCREMENT, -- pk
	id_bank int,
	tgl date,
	nama varchar(255),
	nominal double(12,2),
	ket text,

	CONSTRAINT pk_opersional_id PRIMARY KEY(id),
	-- Penambahan FK ID Bank
	CONSTRAINT fk_operasional_id_bank FOREIGN KEY(id_bank) REFERENCES bank(id)
		ON DELETE RESTRICT ON UPDATE CASCADE
	-- end of Penambahan 
)ENGINE=InnoDb;

-- Tabel Mutasi Bank
CREATE TABLE IF NOT EXISTS mutasi_bank(
	id int NOT NULL AUTO_INCREMENT, -- pk
	id_bank int, -- fk bank
	tgl date,
	uang_masuk double(12,2),
	uang_keluar double(12,2),
	saldo double(12,2), -- saldo per tanggal
	ket text,

	CONSTRAINT pk_mutasi_bank_id PRIMARY KEY(id),
	CONSTRAINT fk_mutasi_bank_id_bank FOREIGN KEY(id_bank) REFERENCES bank(id)
		ON DELETE RESTRICT ON UPDATE CASCADE
)ENGINE=InnoDb;

-- Tabel Mutasi Saldo kas kecil
CREATE TABLE IF NOT EXISTS mutasi_saldo_kas_kecil(
	id int NOT NULL AUTO_INCREMENT, -- pk
	id_kas_kecil varchar(10), -- fk kas kecil
	tgl date,
	uang_masuk double(12,2),
	uang_keluar double(12,2),
	saldo double(12,2), -- saldo saat pada mutasi per tanggal
	ket text,

	CONSTRAINT pk_mutasi_saldo_kas_kecil_id PRIMARY KEY(id),
	CONSTRAINT fk_mutasi_saldo_kas_kecil_id_kas_kecil FOREIGN KEY(id_kas_kecil) REFERENCES kas_kecil(id)
		ON DELETE RESTRICT ON UPDATE CASCADE
)ENGINE=InnoDb;

-- Tabel Mutasi Saldo sub kas kecil
CREATE TABLE IF NOT EXISTS mutasi_saldo_sub_kas_kecil(
	id int NOT NULL AUTO_INCREMENT, -- pk
	id_sub_kas_kecil varchar(10), -- fk sub kas kecil
	tgl date,
	uang_masuk double(12,2),
	uang_keluar double(12,2),
	saldo double(12,2), -- saldo saat pada mutasi per tanggal
	ket text,

	CONSTRAINT pk_mutasi_saldo_sub_kas_kecil_id PRIMARY KEY(id),
	CONSTRAINT fk_mutasi_saldo_sub_kas_kecil_id_sub_kas_kecil FOREIGN KEY(id_sub_kas_kecil) REFERENCES sub_kas_kecil(id)
		ON DELETE RESTRICT ON UPDATE CASCADE
)ENGINE=InnoDb;

-- Tabel Pengajuan Sub Kas Kecil
CREATE TABLE IF NOT EXISTS pengajuan_sub_kas_kecil(
	id varchar(50) NOT NULL UNIQUE, -- pk, id+proyek+sub_kas_kecil+increment
	id_sub_kas_kecil varchar(10), -- fk sub kecil
	id_proyek varchar(50), -- fk proyek
	tgl date, -- tgl dan jam
	total double(12,2), -- total pengajuan
	dana_disetujui double(12,2), -- dana yg disetujui, default 0 atau sama dengan total
	status enum('DISETUJUI', 'PERBAIKI', 'DITOLAK', 'PENDING', 'LANGSUNG'), -- status pengajuan, default 'pending'
	status_laporan enum('DISETUJUI', 'PERBAIKI', 'PENDING'), -- status laporan, default set null

	CONSTRAINT pk_pengajuan_sub_kas_kecil_id PRIMARY KEY(id),
	CONSTRAINT fk_pengajuan_sub_kas_kecil_id_sub_kas_kecil FOREIGN KEY(id_sub_kas_kecil) REFERENCES sub_kas_kecil(id)
		ON DELETE RESTRICT ON UPDATE CASCADE,
	CONSTRAINT fk_pengajuan_sub_kas_kecil_id_proyek FOREIGN KEY(id_proyek) REFERENCES proyek (id)
		ON DELETE RESTRICT ON UPDATE CASCADE
)ENGINE=InnoDb;

-- Tabel Detail Pengajuan Sub Kas Kecil
CREATE TABLE IF NOT EXISTS detail_pengajuan_sub_kas_kecil(
	id int NOT NULL AUTO_INCREMENT, -- pk
	id_pengajuan varchar(50), -- fk pengajuan sub kas kecil
	nama varchar(255), -- nama barang/bahan
	jenis enum('TEKNIS', 'NON-TEKNIS'), -- jenis pengajuan
	satuan varchar(50), -- satuan barang/bahan
	qty int, -- jumlah barang/bahan
	harga double(12,2), -- harga satuan per barang/bahan
	subtotal double(12,2), -- total per detail pengajuan
	status enum('TUNAI', 'KREDIT'), -- status barang/bahan dibeli secara tunai/kredit, default 'tunai'
	harga_asli double(12,2),
	sisa double(12,2),
	status_lunas enum('LUNAS', 'BELUM LUNAS'), -- status pembayaran barang/bahan

	CONSTRAINT pk_detail_pengajuan_sub_kas_kecil_id PRIMARY KEY(id),
	CONSTRAINT fk_detail_pengajuan_sub_kas_kecil_id_pengajuan FOREIGN KEY(id_pengajuan) REFERENCES pengajuan_sub_kas_kecil(id)
		ON DELETE RESTRICT ON UPDATE CASCADE
)ENGINE=InnoDb;

-- Tabel Laporan Pengajuan Sub Kas Kecil
-- CREATE TABLE laporan_pengajuan_sub_kas_kecil(
-- 	id int NOT NULL AUTO_INCREMENT, -- pk
-- 	id_detail_pengajuan int, -- fk detail pengajuan sub kas kecil
-- 	harga_asli double(12,2), -- harga asli
-- 	sisa double(12,2), -- sisa perdetail

-- 	CONSTRAINT pk_laporan_pengajuan_sub_kas_kecil_id PRIMARY KEY(id),
-- 	CONSTRAINT fk_laporan_pengajuan_sub_kas_kecil_id_detail_pengajuan FOREIGN KEY(id_detail_pengajuan) REFERENCES detail_pengajuan_sub_kas_kecil(id)
-- 		ON DELETE RESTRICT ON UPDATE CASCADE
-- );

-- Tabel Upload Laporan Pengajuan Sub Kas Kecil
CREATE TABLE IF NOT EXISTS upload_laporan_pengajuan_sub_kas_kecil(
	id int NOT NULL AUTO_INCREMENT, -- pk
	id_pengajuan varchar(50), -- fk pengajuan sub kas kecil
	foto text,

	CONSTRAINT pk_upload_laporan_pengajuan_sub_kas_kecil PRIMARY KEY(id),
	CONSTRAINT fk_upload_laporan_pengajuan_sub_kas_kecil_id_pengajuan FOREIGN KEY(id_pengajuan) REFERENCES pengajuan_sub_kas_kecil(id)
		ON DELETE RESTRICT ON UPDATE CASCADE
)ENGINE=InnoDb;

-- Tabel Pengajuan Kas Kecil (pengajuan uang ke kas besar)
CREATE TABLE IF NOT EXISTS pengajuan_kas_kecil(
	id varchar(50) NOT NULL UNIQUE, -- pk
	id_kas_kecil varchar(10),
	tgl date,
	nama varchar(50), -- nama pengajuan
	total double(12,2), -- total pengajuan ke kas besar
	status enum('DISETUJUI', 'PERBAIKI', 'DITOLAK', 'PENDING'), -- status pengajuan, default 'pending'

	CONSTRAINT pk_pengajuan_kas_kecil_id PRIMARY KEY(id),
	CONSTRAINT fk_pengajuan_kas_kecil_id_kas_kecil FOREIGN KEY(id_kas_kecil) REFERENCES kas_kecil(id)
)ENGINE=InnoDb;

-- Tabel Detail Pengajuan Kas Kecil
CREATE TABLE IF NOT EXISTS detail_pengajuan_kas_kecil(
	id int NOT NULL AUTO_INCREMENT, -- pk
	id_pengajuan varchar(50), -- fk pengajuan kas kecil
	id_pengajuan_sub_kas_kecil varchar(50), -- fk pengajuan sub kas kecil yg masih pending

	CONSTRAINT pk_detail_pengajuan_kas_kecil_id PRIMARY KEY(id),
	CONSTRAINT fk_detail_pengajuan_kas_kecil_id_pengajuan FOREIGN KEY(id_pengajuan) REFERENCES pengajuan_kas_kecil(id)
		ON DELETE RESTRICT ON UPDATE CASCADE,
	CONSTRAINT fk_detail_pengajuan_kas_kecil_id_pengajuan_sub_kas_kecil FOREIGN KEY(id_pengajuan_sub_kas_kecil) REFERENCES pengajuan_sub_kas_kecil(id)
		ON DELETE RESTRICT ON UPDATE CASCADE
)ENGINE=InnoDb;

-- Tabel Operasional Proyek (operasional proyek yang dilakukan langsung oleh kas besar)
CREATE TABLE IF NOT EXISTS operasional_proyek(
	id varchar(50) NOT NULL UNIQUE,
	id_proyek varchar(50), -- fk
	tgl date,
	nama varchar(50),
	total double(12,2),

	CONSTRAINT pk_operasional_proyek_id PRIMARY KEY(id),
	CONSTRAINT fk_operasional_proyek_id_proyek FOREIGN KEY(id_proyek) REFERENCES proyek(id)
		ON DELETE RESTRICT ON UPDATE CASCADE
)ENGINE=InnoDb;

-- Opsional (tergantung permintaan {apakah akan di data detail nya/ tidak})
CREATE TABLE IF NOT EXISTS detail_operasional_proyek(
	id varchar(50) NOT NULL UNIQUE,
	id_operasional_proyek varchar(50),
	nama varchar(255), -- nama barang/bahan
	jenis enum('TEKNIS', 'NON-TEKNIS'), -- jenis pengajuan
	satuan varchar(50), -- satuan barang/bahan
	qty int, -- jumlah barang/bahan
	harga double(12,2), -- harga satuan per barang/bahan
	subtotal double(12,2), -- total per detail pengajuan
	status enum('TUNAI', 'KREDIT'), -- status barang/bahan dibeli secara tunai/kredit, default 'tunai'
	harga_asli double(12,2),
	sisa double(12,2),
	status_lunas enum('LUNAS', 'BELUM LUNAS'), -- status pembayaran barang/bahan


	CONSTRAINT pk_detail_operasional_proyek PRIMARY KEY(id),
	CONSTRAINT fk_detail_operasional_proyek_id_operasional FOREIGN KEY(id_operasional_proyek) REFERENCES operasional_proyek(id)
)ENGINE=InnoDb;

-- Tabel User Owner v1
-- CREATE TABLE owner(
-- 	id varchar(10) NOT NULL UNIQUE,
-- 	nama varchar(255),
-- 	alamat text,
-- 	no_telp varchar(20),
-- 	email varchar(50) UNIQUE,
-- 	foto text,
-- 	password text,
-- 	status enum('AKTIF', 'NONAKTIF'), -- status kas kecil

-- 	CONSTRAINT pk_owner_id PRIMARY KEY(id)
-- );

-- Tabel User Owner v2
-- CREATE TABLE owner(
-- 	id varchar(10) NOT NULL UNIQUE, -- pk
-- 	username varchar(25), -- fk user
-- 	nama varchar(255),
-- 	alamat text,
-- 	no_telp varchar(20),
-- 	email varchar(50) UNIQUE,
-- 	foto text,
-- 	status enum('AKTIF', 'NONAKTIF'), -- status kas kecil

-- 	CONSTRAINT pk_owner_id PRIMARY KEY(id),
-- 	CONSTRAINT fk_owner_username FOREIGN KEY(username) REFERENCES user(username)
-- 		ON DELETE RESTRICT ON UPDATE CASCADE
-- );

# =================================================================== #
