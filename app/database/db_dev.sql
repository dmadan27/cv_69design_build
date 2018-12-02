# Database Sistem Informasi CV 69 Design & Build #
# 69design-build #
# Versi 1.0 for MariaDB #

# ============================== Tabel ============================== #

-- Tabel User
CREATE TABLE IF NOT EXISTS user(
	username varchar(50) NOT NULL UNIQUE, -- pk
	password text, -- hash bcypt
	level enum('OWNER', 'KAS BESAR', 'KAS KECIL', 'SUB KAS KECIL'), -- v1
	status enum('AKTIF', 'NONAKTIF'), -- status aktif username

	CONSTRAINT pk_user_username PRIMARY KEY(username)
)ENGINE=InnoDb;

-- Tabel Kas Besar
CREATE TABLE IF NOT EXISTS kas_besar(
	id varchar(10) NOT NULL UNIQUE, -- pk
	nama varchar(255),
	alamat text,
	no_telp varchar(20),
	email varchar(50) UNIQUE, -- fk user
	foto text,
	status enum('AKTIF', 'NONAKTIF'), -- status aktif kas besar

	CONSTRAINT pk_kas_besar_id PRIMARY KEY(id),
	CONSTRAINT fk_kas_besar_email FOREIGN KEY(email) REFERENCES user(username)
		ON DELETE SET NULL ON UPDATE CASCADE
)ENGINE=InnoDb;

-- Tabel Kas Kecil
CREATE TABLE IF NOT EXISTS kas_kecil(
	id varchar(10) NOT NULL UNIQUE, -- pk
	nama varchar(50), -- nama kas kecil
	alamat text,
	no_telp varchar(20),
	email varchar(50) UNIQUE, -- fk user
	foto text,
	saldo double(12,2), -- saldo kas kecil, default 0
	status enum('AKTIF', 'NONAKTIF'), -- status aktif kas kecil

	CONSTRAINT pk_kas_kecil_id PRIMARY KEY(id),
	CONSTRAINT fk_kas_kecil_email FOREIGN KEY(email) REFERENCES user(username)
		ON DELETE SET NULL ON UPDATE CASCADE
)ENGINE=InnoDb;

-- Tabel Sub Kas Kecil (logistik)
CREATE TABLE IF NOT EXISTS sub_kas_kecil(
	id varchar(10) NOT NULL UNIQUE, -- pk, id+increment, contoh: log001
	nama varchar(255),
	alamat text,
	no_telp varchar(20),
	email varchar(50), -- username
	foto text, -- simpan urlnya
	saldo double(12,2), -- saldo master, default 0
	status enum('AKTIF', 'NONAKTIF'), -- status aktif sub kas kecil

	CONSTRAINT pk_sub_kas_kecil_id PRIMARY KEY(id)
)ENGINE=InnoDb;

-- Tabel Token Mobile (Untuk Sub Kas Kecil)
CREATE TABLE IF NOT EXISTS token_mobile(
	id int NOT NULL AUTO_INCREMENT, -- pk
	username varchar(50), -- fk
	token text,
	tgl_buat datetime,
	tgl_exp datetime,

	CONSTRAINT pk_token_mobile PRIMARY KEY(id),
	CONSTRAINT fk_token_mobile_username FOREIGN KEY(username) REFERENCES user(username)
		ON DELETE RESTRICT ON UPDATE RESTRICT
)ENGINE=InnoDb;

-- Tabel Token Lupa Password (Untuk Sistem dan Mobile)
CREATE TABLE IF NOT EXISTS token_lupa_password(
	id int NOT NULL AUTO_INCREMENT, -- pk
	username varchar(50), -- fk
	token text,
	tgl_buat datetime,
	tgl_exp datetime,

	CONSTRAINT pk_token_lupa_password PRIMARY KEY(id),
	CONSTRAINT fk_token_lupa_password FOREIGN KEY(username) REFERENCES user(username)
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
	id_bank int, -- fk bank, khusus untuk pendataan uang dp
	cco double(12,2), -- change contract order
	progress int,
	status enum('SELESAI', 'BERJALAN'), -- status proyek

	CONSTRAINT pk_proyek_id PRIMARY KEY(id),
	CONSTRAINT fk_proyek_id_bank FOREIGN KEY(id_bank) REFERENCES bank(id)
		ON DELETE RESTRICT ON UPDATE CASCADE
)ENGINE=InnoDb;

-- Tabel Detail Proyek (detail pembayaran)
CREATE TABLE IF NOT EXISTS detail_proyek(
	id int NOT NULL AUTO_INCREMENT, -- pk
	id_proyek varchar(50), -- fk proyek
	id_bank int, -- fk bank
	tgl date,
	nama varchar(255), -- nama pembayaran
	total double(12,2), -- total angsuran

	CONSTRAINT pk_detail_proyek_id PRIMARY KEY(id),
	CONSTRAINT fk_detail_proyek_id_proyek FOREIGN KEY(id_proyek) REFERENCES proyek(id)
		ON DELETE RESTRICT ON UPDATE CASCADE,
	CONSTRAINT fk_detail_proyek_id_bank FOREIGN KEY(id_bank) REFERENCES bank(id)
		ON DELETE RESTRICT ON UPDATE CASCADE
)ENGINE=InnoDb;

-- Tabel Logistik Proyek (Skk Proyek)
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
	status enum('AKTIF', 'NONAKTIF'), -- status aktif bank

	CONSTRAINT pk_bank_id PRIMARY KEY(id)
)ENGINE=InnoDb;

-- Tabel Operasional (Pengeluaran di luar proyek)
CREATE TABLE IF NOT EXISTS operasional(
	id int NOT NULL AUTO_INCREMENT, -- pk
	id_bank int, -- fk bank
	id_kas_besar varchar(10),
	tgl date,
	nama varchar(255),
	nominal double(12,2),
	jenis enum('UANG MASUK', 'UANG KELUAR'),
	ket text,

	CONSTRAINT pk_opersional_id PRIMARY KEY(id),
	CONSTRAINT fk_operasional_id_bank FOREIGN KEY(id_bank) REFERENCES bank(id)
		ON DELETE RESTRICT ON UPDATE CASCADE,
	CONSTRAINT fk_operasional_id_kas_besar FOREIGN KEY(id_kas_besar) REFERENCES kas_besar(id)
		ON DELETE RESTRICT ON UPDATE CASCADE
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
		ON DELETE SET NULL ON UPDATE CASCADE
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
		ON DELETE SET NULL ON UPDATE CASCADE
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
		ON DELETE SET NULL ON UPDATE CASCADE
)ENGINE=InnoDb;

-- Tabel Pengajuan Sub Kas Kecil
CREATE TABLE IF NOT EXISTS pengajuan_sub_kas_kecil(
	id varchar(50) NOT NULL UNIQUE, -- pk, id+proyek+sub_kas_kecil+increment
	id_sub_kas_kecil varchar(10), -- fk sub kecil
	id_proyek varchar(50), -- fk proyek
	tgl date, -- tgl dan jam
	nama varchar(50),
	total double(12,2), -- total pengajuan
	dana_disetujui double(12,2), -- dana yg disetujui, default 0 atau sama dengan total
	status char(1), -- status pengajuan, default 1: 'pending'
	-- 1: 'PENDING', 2: 'PERBAIKI', 3: 'DISETUJUI', 4: 'LANGSUNG', 5: 'DITOLAK'
	status_laporan char(1), -- status laporan, default set null
	-- 1: 'PENDING', 2: 'PERBAIKI', 3: 'DISETUJUI',

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
	jenis char(1), -- jenis pengajuan, T: 'TEKNIS', N: 'NON-TEKNIS'
	satuan varchar(50), -- satuan barang/bahan
	qty int, -- jumlah barang/bahan
	harga double(12,2), -- harga satuan per barang/bahan
	subtotal double(12,2), -- total per detail pengajuan
	-- status enum('TUNAI', 'KREDIT'), -- status barang/bahan dibeli secara tunai/kredit, default 'tunai'
	harga_asli double(12,2),
	sisa double(12,2),
	-- status_lunas enum('LUNAS', 'BELUM LUNAS'), -- status pembayaran barang/bahan

	CONSTRAINT pk_detail_pengajuan_sub_kas_kecil_id PRIMARY KEY(id),
	CONSTRAINT fk_detail_pengajuan_sub_kas_kecil_id_pengajuan FOREIGN KEY(id_pengajuan) REFERENCES pengajuan_sub_kas_kecil(id)
		ON DELETE RESTRICT ON UPDATE CASCADE
)ENGINE=InnoDb;

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
	id_kas_kecil varchar(10), -- fk kas kecil
	tgl date,
	nama varchar(50), -- nama pengajuan
	total double(12,2), -- total pengajuan ke kas besar
	status char(1), -- status pengajuan, default 'pending'
	-- 1: 'PENDING', 2: 'PERBAIKI', 3: 'DISETUJUI', 4: 'DITOLAK'
	id_bank int, -- fk bank

	CONSTRAINT pk_pengajuan_kas_kecil_id PRIMARY KEY(id),
	CONSTRAINT fk_pengajuan_kas_kecil_id_kas_kecil FOREIGN KEY(id_kas_kecil) REFERENCES kas_kecil(id)
		ON DELETE RESTRICT ON UPDATE CASCADE,
	CONSTRAINT fk_pengajuan_kas_kecil_id_bank FOREIGN KEY(id_bank) REFERENCES bank(id)
		ON DELETE RESTRICT ON UPDATE CASCADE
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

-- Tabe Distributor / Toko / Supplier
CREATE TABLE IF NOT EXISTS distributor(
	id varchar(50) NOT NULL UNIQUE, -- primary key
	nama varchar(50), -- nama distributor
	alamat text, -- alamat distributor
	-- jenis varchar(50), -- jenis distributor
	no_telp varchar(25), -- telpon 
	pemilik varchar(50), -- pemilik
	status enum('AKTIF','NONAKTIF'),

	CONSTRAINT pk_id PRIMARY KEY(id)
)ENGINE=InnoDb;

-- Tabel Operasional Proyek (operasional proyek yang dilakukan langsung oleh kas besar)
CREATE TABLE IF NOT EXISTS operasional_proyek(
	id varchar(50) NOT NULL UNIQUE,
	id_proyek varchar(50), -- fk proyek
	id_bank int, -- fk bank
	id_kas_besar varchar(10), -- fk kas besar
	id_distributor varchar(10), -- fk distributor
	tgl date,
	nama varchar(50), -- nama operasional
	jenis enum('TEKNIS', 'NON-TEKNIS'), -- jenis operasional,
	total double(12,2), -- total operasional
	sisa double(12,2), -- sisa jika bayar secara cicil, default 0
	status enum('TUNAI', 'KREDIT'), -- T: Tunai, K: Kredit
	status_lunas enum('LUNAS', 'BELUM LUNAS'), -- L: Lunas, B: Belum Lunas
	ket text,

	CONSTRAINT pk_operasional_proyek_id PRIMARY KEY(id),
	CONSTRAINT fk_operasional_proyek_id_proyek FOREIGN KEY(id_proyek) REFERENCES proyek(id)
		ON DELETE RESTRICT ON UPDATE CASCADE,
	CONSTRAINT fk_operasional_proyek_id_bank FOREIGN KEY(id_bank) REFERENCES bank(id)
		ON DELETE RESTRICT ON UPDATE CASCADE,
	CONSTRAINT fk_operasional_proyek_id_kas_besar FOREIGN KEY(id_kas_besar) REFERENCES kas_besar(id)
		ON DELETE RESTRICT ON UPDATE CASCADE,
	CONSTRAINT fk_operasional_proyek_id_distributor FOREIGN KEY(id_distributor) REFERENCES distributor(id)
		ON DELETE RESTRICT ON UPDATE CASCADE
)ENGINE=InnoDb;

-- Tabel Detail Operasional Proyek
CREATE TABLE IF NOT EXISTS detail_operasional_proyek(

)ENGINE=InnoDb;


-- Tabel Detail Operasional Proyek_temp
CREATE TABLE IF NOT EXISTS detail_operasional_proyek_temp(
 	id int NOT NULL AUTO_INCREMENT, -- pk
 	id_operasional_proyek varchar(50), -- fk operasional proyek
 	id_bank int,  --fk bank 
 	nama varchar(255), -- nama angsuran (angsuran ke-n)
 	tgl date, -- tanggl angsuran
 	total double(12,2), -- total angsuran

 	CONSTRAINT pk_detail_operasional_proyek PRIMARY KEY(id),
 	CONSTRAINT fk_detail_operasional_proyek_id_bank_from_bank FOREIGN KEY(id_bank) REFERENCES bank(id)
 		ON DELETE RESTRICT ON UPDATE CASCADE,
 	CONSTRAINT fk_detail_operasional_proyek_id_operasional FOREIGN KEY(id_operasional_proyek) REFERENCES operasional_proyek(id)
		ON DELETE RESTRICT ON UPDATE CASCADE
)ENGINE=InnoDb;

-- Opsional (tergantung permintaan {apakah akan di data detail nya/ tidak})
-- CREATE TABLE IF NOT EXISTS detail_operasional_proyek(
-- 	id int NOT NULL AUTO_INCREMENT, -- pk
-- 	id_operasional_proyek varchar(50), -- fk operasional proyek
-- 	nama varchar(255), -- nama barang/bahan
-- 	jenis enum('TEKNIS', 'NON-TEKNIS'), -- jenis pengajuan
-- 	satuan varchar(50), -- satuan barang/bahan
-- 	qty int, -- jumlah barang/bahan
-- 	harga double(12,2), -- harga satuan per barang/bahan
-- 	subtotal double(12,2), -- total per detail pengajuan
-- 	status enum('TUNAI', 'KREDIT'), -- status barang/bahan dibeli secara tunai/kredit, default 'tunai'
-- 	harga_asli double(12,2),
-- 	sisa double(12,2),
-- 	status_lunas enum('LUNAS', 'BELUM LUNAS'), -- status pembayaran barang/bahan

-- 	CONSTRAINT pk_detail_operasional_proyek PRIMARY KEY(id),
-- 	CONSTRAINT fk_detail_operasional_proyek_id_operasional FOREIGN KEY(id_operasional_proyek) REFERENCES operasional_proyek(id)
-- 		ON DELETE RESTRICT ON UPDATE CASCADE
-- );

# =================================================================== #
