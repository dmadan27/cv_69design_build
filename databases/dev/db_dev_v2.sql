# Database Sistem Informasi CV 69 Design & Build #
# 69design-build #
# Versi 2.0 for MariaDB #

# FOR LOCAL DEVELOPMENT #
	-- DROP DATABASE IF EXISTS `69design-build_dev`;
	-- CREATE DATABASE `69design-build_dev`;
	-- USE `69design-build_dev`;
# END FOR LOCAL DEVELOPMENT #

# ============================== Base Tabel ============================== #

-- Tabel User
	DROP TABLE IF EXISTS user;
	CREATE TABLE IF NOT EXISTS user(
		username varchar(50) NOT NULL UNIQUE, -- pk
		
		created_on datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
		modified_on datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
		created_by varchar(50), -- who created first
		modified_by varchar(50), -- who last edit

		password text, -- hash bcypt
		level enum('OWNER', 'KAS BESAR', 'KAS KECIL', 'SUB KAS KECIL'), -- v1
		status enum('AKTIF', 'NONAKTIF'), -- status aktif username

		CONSTRAINT pk_user_username PRIMARY KEY(username),
		CONSTRAINT fk_user_created_by FOREIGN KEY(created_by) REFERENCES user(username)
			ON DELETE SET NULL ON UPDATE CASCADE,
		CONSTRAINT fk_user_modified_by FOREIGN KEY(modified_by) REFERENCES user(username)
			ON DELETE SET NULL ON UPDATE CASCADE
	)ENGINE=InnoDb;
-- End Tabel USer

-- Tabel Menu (Module)
	DROP TABLE IF EXISTS menu;
	CREATE TABLE IF NOT EXISTS menu(
		id int NOT NULL AUTO_INCREMENT,

		created_on datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
		modified_on datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
		created_by varchar(50), -- who created first
		modified_by varchar(50), -- who last edit

		name varchar(255),
		table_name varchar(255),
		url varchar(255),
		class varchar(50),
		icon varchar(50),
		position tinyint,

		CONSTRAINT pk_menu_id PRIMARY KEY(id),
		CONSTRAINT fk_menu_created_by FOREIGN KEY(created_by) REFERENCES user(username)
			ON DELETE SET NULL ON UPDATE CASCADE,
		CONSTRAINT fk_menu_modified_by FOREIGN KEY(modified_by) REFERENCES user(username)
			ON DELETE SET NULL ON UPDATE CASCADE
	)ENGINE=InnoDb;
-- End Tabel Menu (Module)

-- Tabel Detail Menu
	DROP TABLE IF EXISTS menu_detail;
	CREATE TABLE IF NOT EXISTS menu_detail(
		id int NOT NULL AUTO_INCREMENT,

		created_on datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
		modified_on datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
		created_by varchar(50), -- who created first
		modified_by varchar(50), -- who last edit

		menu_id int, -- fk
		permission char(1),
		-- 1: Read, 2: Add, 3: Update, 4: Delete, 5: Update Status, 6: Export

		CONSTRAINT pk_menu_detail_id PRIMARY KEY(id),
		CONSTRAINT fk_menu_detail_menu_id FOREIGN KEY(menu_id) REFERENCES menu(id)
			ON DELETE RESTRICT ON UPDATE CASCADE,
		CONSTRAINT fk_menu_detail_created_by FOREIGN KEY(created_by) REFERENCES user(username)
			ON DELETE SET NULL ON UPDATE CASCADE,
		CONSTRAINT fk_menu_detail_modified_by FOREIGN KEY(modified_by) REFERENCES user(username)
			ON DELETE SET NULL ON UPDATE CASCADE
	)ENGINE=InnoDb;
-- End Tabel Detail Menu

-- Tabel Access Menu
	DROP TABLE IF EXISTS access_menu;
	CREATE TABLE IF NOT EXISTS access_menu (
		id int NOT NULL AUTO_INCREMENT,

		created_on datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
		modified_on datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
		created_by varchar(50), -- who created first
		modified_by varchar(50), -- who last edit

		-- level_id int, -- fk level lookup
		level enum('OWNER', 'KAS BESAR', 'KAS KECIL', 'SUB KAS KECIL'),
		menu_id int, -- fk menu

		CONSTRAINT pk_access_menu_id PRIMARY KEY(id),
		-- CONSTRAINT fk_access_menu_level_id FOREIGN KEY(level_id) REFERENCES level_lookup(id)
		-- 	ON DELETE RESTRICT ON UPDATE CASCADE,
		CONSTRAINT fk_access_menu_menu_id FOREIGN KEY(menu_id) REFERENCES menu(id)
			ON DELETE RESTRICT ON UPDATE CASCADE,
		CONSTRAINT fk_access_menu_created_by FOREIGN KEY(created_by) REFERENCES user(username)
			ON DELETE SET NULL ON UPDATE CASCADE,
		CONSTRAINT fk_access_menu_modified_by FOREIGN KEY(modified_by) REFERENCES user(username)
			ON DELETE SET NULL ON UPDATE CASCADE
	)ENGINE=InnoDb;
-- End Tabel Access Menu

-- Tabel Role Permission
-- Tahap Pengembangan
	DROP TABLE IF EXISTS role_permission;
	CREATE TABLE IF NOT EXISTS role_permission(
		id int NOT NULL AUTO_INCREMENT,
		
		created_on datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
		modified_on datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
		created_by varchar(50), -- who created first
		modified_by varchar(50), -- who last edit

		user varchar(50), -- fk
		permission_id int, -- fk

		CONSTRAINT pk_role_permission_id PRIMARY KEY(id),
		CONSTRAINT fk_role_permission_user FOREIGN KEY(user) REFERENCES user(username)
			ON DELETE RESTRICT ON UPDATE CASCADE,
		CONSTRAINT fk_role_permission_permission_id FOREIGN KEY(permission_id) REFERENCES menu_detail(id)
			ON DELETE RESTRICT ON UPDATE CASCADE,
		CONSTRAINT fk_role_permission_created_by FOREIGN KEY(created_by) REFERENCES user(username)
			ON DELETE SET NULL ON UPDATE CASCADE,
		CONSTRAINT fk_role_permission_modified_by FOREIGN KEY(modified_by) REFERENCES user(username)
			ON DELETE SET NULL ON UPDATE CASCADE
	)ENGINE=InnoDb;
-- End Tabel Role Permission

-- Table Increment
	DROP TABLE IF EXISTS increment;
	CREATE TABLE IF NOT EXISTS increment(
		id int NOT NULL AUTO_INCREMENT,

		created_on datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
		modified_on datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
		created_by varchar(50), -- who created first
		modified_by varchar(50), -- who last edit

		menu_id int NOT NULL UNIQUE, -- nama tabel yang ingin ada increment
		mask varchar(255), -- format increment
		last_increment int,
		description text,

		CONSTRAINT pk_increment_id PRIMARY KEY(id),
		CONSTRAINT fk_increment_menu_id FOREIGN KEY(menu_id) REFERENCES menu(id)
			ON DELETE RESTRICT ON UPDATE CASCADE,
		CONSTRAINT fk_increment_created_by FOREIGN KEY(created_by) REFERENCES user(username)
			ON DELETE SET NULL ON UPDATE CASCADE,
		CONSTRAINT fk_increment_modified_by FOREIGN KEY(modified_by) REFERENCES user(username)
			ON DELETE SET NULL ON UPDATE CASCADE
	)ENGINE=InnoDb;
-- End Tabel Increment

-- Tabel Kas Besar
	DROP TABLE IF EXISTS kas_besar;
	CREATE TABLE IF NOT EXISTS kas_besar(
		id varchar(10) NOT NULL UNIQUE, -- pk

		created_on datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
		modified_on datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
		created_by varchar(50), -- who created first
		modified_by varchar(50), -- who last edit

		nama varchar(255),
		alamat text,
		no_telp varchar(20),
		email varchar(50) UNIQUE, -- fk user
		foto text,
		status enum('AKTIF', 'NONAKTIF'), -- status aktif kas besar

		CONSTRAINT pk_kas_besar_id PRIMARY KEY(id),
		CONSTRAINT fk_kas_besar_email FOREIGN KEY(email) REFERENCES user(username)
			ON DELETE SET NULL ON UPDATE CASCADE,
		CONSTRAINT fk_kas_besar_created_by FOREIGN KEY(created_by) REFERENCES user(username)
			ON DELETE SET NULL ON UPDATE CASCADE,
		CONSTRAINT fk_kas_besar_modified_by FOREIGN KEY(modified_by) REFERENCES user(username)
			ON DELETE SET NULL ON UPDATE CASCADE
	)ENGINE=InnoDb;
-- End Tabel Kas Besar

-- Tabel Kas Kecil
	DROP TABLE IF EXISTS kas_kecil;
	CREATE TABLE IF NOT EXISTS kas_kecil(
		id varchar(10) NOT NULL UNIQUE, -- pk

		created_on datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
		modified_on datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
		created_by varchar(50), -- who created first
		modified_by varchar(50), -- who last edit

		nama varchar(50), -- nama kas kecil
		alamat text,
		no_telp varchar(20),
		email varchar(50) UNIQUE, -- fk user
		foto text,
		saldo double(12,2), -- saldo kas kecil, default 0
		status enum('AKTIF', 'NONAKTIF'), -- status aktif kas kecil

		CONSTRAINT pk_kas_kecil_id PRIMARY KEY(id),
		CONSTRAINT fk_kas_kecil_email FOREIGN KEY(email) REFERENCES user(username)
			ON DELETE SET NULL ON UPDATE CASCADE,
		CONSTRAINT fk_kas_kecil_created_by FOREIGN KEY(created_by) REFERENCES user(username)
			ON DELETE SET NULL ON UPDATE CASCADE,
		CONSTRAINT fk_kas_kecil_modified_by FOREIGN KEY(modified_by) REFERENCES user(username)
			ON DELETE SET NULL ON UPDATE CASCADE
	)ENGINE=InnoDb;
-- End Tabel Kas Kecil

-- Tabel Sub Kas Kecil (logistik)
	DROP TABLE IF EXISTS sub_kas_kecil;
	CREATE TABLE IF NOT EXISTS sub_kas_kecil(
		id varchar(10) NOT NULL UNIQUE, -- pk, id+increment, contoh: log001
		
		created_on datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
		modified_on datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
		created_by varchar(50), -- who created first
		modified_by varchar(50), -- who last edit
		
		nama varchar(255),
		alamat text,
		no_telp varchar(20),
		email varchar(50), -- username
		foto text, -- simpan urlnya
		saldo double(12,2), -- saldo master, default 0
		status enum('AKTIF', 'NONAKTIF'), -- status aktif sub kas kecil

		CONSTRAINT pk_sub_kas_kecil_id PRIMARY KEY(id),
		CONSTRAINT fk_sub_kas_kecil_email FOREIGN KEY(email) REFERENCES user(username)
			ON DELETE SET NULL ON UPDATE CASCADE,
		CONSTRAINT fk_sub_kas_kecil_created_by FOREIGN KEY(created_by) REFERENCES user(username)
			ON DELETE SET NULL ON UPDATE CASCADE,
		CONSTRAINT fk_sub_kas_kecil_modified_by FOREIGN KEY(modified_by) REFERENCES user(username)
			ON DELETE SET NULL ON UPDATE CASCADE
	)ENGINE=InnoDb;
-- End Tabel Sub Kas Kecil

-- Tabel Token Mobile (Untuk Sub Kas Kecil)
	DROP TABLE IF EXISTS token_mobile;
	CREATE TABLE IF NOT EXISTS token_mobile(
		id int NOT NULL AUTO_INCREMENT, -- pk

		created_on datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
		modified_on datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
		created_by varchar(50), -- who created first
		modified_by varchar(50), -- who last edit

		username varchar(50), -- fk
		token text,
		tgl_buat datetime,
		tgl_exp datetime,

		CONSTRAINT pk_token_mobile PRIMARY KEY(id),
		CONSTRAINT fk_token_mobile_username FOREIGN KEY(username) REFERENCES user(username)
			ON DELETE SET NULL ON UPDATE RESTRICT,
		CONSTRAINT fk_token_mobile_created_by FOREIGN KEY(created_by) REFERENCES user(username)
			ON DELETE SET NULL ON UPDATE CASCADE,
		CONSTRAINT fk_token_mobile_modified_by FOREIGN KEY(modified_by) REFERENCES user(username)
			ON DELETE SET NULL ON UPDATE CASCADE
	)ENGINE=InnoDb;
-- End Tabel Token Mobile

-- Tabel Token Lupa Password (Untuk Sistem dan Mobile)
	DROP TABLE IF EXISTS token_lupa_password;
	CREATE TABLE IF NOT EXISTS token_lupa_password(
		id int NOT NULL AUTO_INCREMENT, -- pk

		created_on datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
		modified_on datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
		created_by varchar(50), -- who created first
		modified_by varchar(50), -- who last edit

		username varchar(50), -- fk
		token text,
		tgl_buat datetime,
		tgl_exp datetime,

		CONSTRAINT pk_token_lupa_password PRIMARY KEY(id),
		CONSTRAINT fk_token_lupa_password FOREIGN KEY(username) REFERENCES user(username)
			ON DELETE RESTRICT ON UPDATE RESTRICT,
		CONSTRAINT fk_token_lupa_password_created_by FOREIGN KEY(created_by) REFERENCES user(username)
			ON DELETE SET NULL ON UPDATE CASCADE,
		CONSTRAINT fk_token_lupa_password_modified_by FOREIGN KEY(modified_by) REFERENCES user(username)
			ON DELETE SET NULL ON UPDATE CASCADE
	)ENGINE=InnoDb;
-- End Tabel Token Lupa Password

-- Tabel Bank
	DROP TABLE IF EXISTS bank;
	CREATE TABLE IF NOT EXISTS bank(
		id int NOT NULL AUTO_INCREMENT, -- pk

		created_on datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
		modified_on datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
		created_by varchar(50), -- who created first
		modified_by varchar(50), -- who last edit

		nama varchar(255), -- nama bank / jenis bank, Bank BCA, Giro BCA, Mandiri, dll
		saldo double(12,2), -- saldo bank
		status enum('AKTIF', 'NONAKTIF'), -- status aktif bank

		CONSTRAINT pk_bank_id PRIMARY KEY(id),
		CONSTRAINT fk_bank_created_by FOREIGN KEY(created_by) REFERENCES user(username)
			ON DELETE SET NULL ON UPDATE CASCADE,
		CONSTRAINT fk_bank_modified_by FOREIGN KEY(modified_by) REFERENCES user(username)
			ON DELETE SET NULL ON UPDATE CASCADE
	)ENGINE=InnoDb;
-- End Tabel Bank

-- Tabel Proyek
	DROP TABLE IF EXISTS proyek;
	CREATE TABLE IF NOT EXISTS proyek(
		id varchar(50) NOT NULL UNIQUE, -- pk, otomatis

		created_on datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
		modified_on datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
		created_by varchar(50), -- who created first
		modified_by varchar(50), -- who last edit

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
		progress int,
		status enum('SELESAI', 'BERJALAN'), -- status proyek

		CONSTRAINT pk_proyek_id PRIMARY KEY(id),
		CONSTRAINT fk_proyek_created_by FOREIGN KEY(created_by) REFERENCES user(username)
			ON DELETE SET NULL ON UPDATE CASCADE,
		CONSTRAINT fk_proyek_modified_by FOREIGN KEY(modified_by) REFERENCES user(username)
			ON DELETE SET NULL ON UPDATE CASCADE
	)ENGINE=InnoDb;
-- End Tabel Proyek

-- Tabel Detail Proyek (detail pembayaran)
	DROP TABLE IF EXISTS detail_proyek;
	CREATE TABLE IF NOT EXISTS detail_proyek(
		id int NOT NULL AUTO_INCREMENT, -- pk

		created_on datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
		modified_on datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
		created_by varchar(50), -- who created first
		modified_by varchar(50), -- who last edit

		id_proyek varchar(50), -- fk proyek
		id_bank int, -- fk bank
		tgl date,
		nama varchar(255), -- nama pembayaran
		total double(12,2), -- total angsuran
		is_DP char(1), -- check DP atau bukan

		CONSTRAINT pk_detail_proyek_id PRIMARY KEY(id),
		CONSTRAINT fk_detail_proyek_id_proyek FOREIGN KEY(id_proyek) REFERENCES proyek(id)
			ON DELETE RESTRICT ON UPDATE CASCADE,
		CONSTRAINT fk_detail_proyek_id_bank FOREIGN KEY(id_bank) REFERENCES bank(id)
			ON DELETE RESTRICT ON UPDATE CASCADE,
		CONSTRAINT fk_detail_proyek_created_by FOREIGN KEY(created_by) REFERENCES user(username)
			ON DELETE SET NULL ON UPDATE CASCADE,
		CONSTRAINT fk_detail_proyek_modified_by FOREIGN KEY(modified_by) REFERENCES user(username)
			ON DELETE SET NULL ON UPDATE CASCADE
	)ENGINE=InnoDb;
-- End Tabel Detail Proyek

-- Tabel Logistik Proyek (Skk Proyek)
	DROP TABLE IF EXISTS logistik_proyek;
	CREATE TABLE IF NOT EXISTS logistik_proyek(
		id int NOT NULL AUTO_INCREMENT, -- pk

		created_on datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
		modified_on datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
		created_by varchar(50), -- who created first
		modified_by varchar(50), -- who last edit

		id_proyek varchar(50), -- fk proyek
		id_sub_kas_kecil varchar(10), -- fk sub kas kecil

		CONSTRAINT pk_logistik_proyek_id PRIMARY KEY(id),
		CONSTRAINT fk_logistik_proyek_id_proyek FOREIGN KEY(id_proyek) REFERENCES proyek(id)
			ON DELETE RESTRICT ON UPDATE CASCADE,
		CONSTRAINT fk_logistik_proyek_id_sub_kas_kecil FOREIGN KEY(id_sub_kas_kecil) REFERENCES sub_kas_kecil(id)
			ON DELETE RESTRICT ON UPDATE CASCADE,
		CONSTRAINT fk_logistik_proyek_created_by FOREIGN KEY(created_by) REFERENCES user(username)
			ON DELETE SET NULL ON UPDATE CASCADE,
		CONSTRAINT fk_logistik_proyek_modified_by FOREIGN KEY(modified_by) REFERENCES user(username)
			ON DELETE SET NULL ON UPDATE CASCADE
	)ENGINE=InnoDb;
-- End Tabel Logistik Proyek

-- Tabel Operasional (Pengeluaran di luar proyek)
	DROP TABLE IF EXISTS operasional;
	CREATE TABLE IF NOT EXISTS operasional(
		id int NOT NULL AUTO_INCREMENT, -- pk

		created_on datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
		modified_on datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
		created_by varchar(50), -- who created first
		modified_by varchar(50), -- who last edit

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
			ON DELETE RESTRICT ON UPDATE CASCADE,
		CONSTRAINT fk_operasional_created_by FOREIGN KEY(created_by) REFERENCES user(username)
			ON DELETE SET NULL ON UPDATE CASCADE,
		CONSTRAINT fk_operasional_modified_by FOREIGN KEY(modified_by) REFERENCES user(username)
			ON DELETE SET NULL ON UPDATE CASCADE
	)ENGINE=InnoDb;
-- End Tabel Operasional

-- Tabel Mutasi Bank
	DROP TABLE IF EXISTS mutasi_bank;
	CREATE TABLE IF NOT EXISTS mutasi_bank(
		id int NOT NULL AUTO_INCREMENT, -- pk

		created_on datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
		modified_on datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
		created_by varchar(50), -- who created first
		modified_by varchar(50), -- who last edit

		id_bank int, -- fk bank
		tgl date,
		uang_masuk double(12,2),
		uang_keluar double(12,2),
		saldo double(12,2), -- saldo per tanggal
		ket text,

		CONSTRAINT pk_mutasi_bank_id PRIMARY KEY(id),
		CONSTRAINT fk_mutasi_bank_id_bank FOREIGN KEY(id_bank) REFERENCES bank(id)
			ON DELETE SET NULL ON UPDATE CASCADE,
		CONSTRAINT fk_mutasi_bank_created_by FOREIGN KEY(created_by) REFERENCES user(username)
			ON DELETE SET NULL ON UPDATE CASCADE,
		CONSTRAINT fk_mutasi_bank_modified_by FOREIGN KEY(modified_by) REFERENCES user(username)
			ON DELETE SET NULL ON UPDATE CASCADE
	)ENGINE=InnoDb;
-- End Tabel Mutasi Bank

-- Tabel Mutasi Saldo kas kecil
	DROP TABLE IF EXISTS mutasi_saldo_kas_kecil;
	CREATE TABLE IF NOT EXISTS mutasi_saldo_kas_kecil(
		id int NOT NULL AUTO_INCREMENT, -- pk

		created_on datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
		modified_on datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
		created_by varchar(50), -- who created first
		modified_by varchar(50), -- who last edit

		id_kas_kecil varchar(10), -- fk kas kecil
		tgl date,
		uang_masuk double(12,2),
		uang_keluar double(12,2),
		saldo double(12,2), -- saldo saat pada mutasi per tanggal
		ket text,

		CONSTRAINT pk_mutasi_saldo_kas_kecil_id PRIMARY KEY(id),
		CONSTRAINT fk_mutasi_saldo_kas_kecil_id_kas_kecil FOREIGN KEY(id_kas_kecil) REFERENCES kas_kecil(id)
			ON DELETE SET NULL ON UPDATE CASCADE,
		CONSTRAINT fk_mutasi_saldo_kas_kecil_created_by FOREIGN KEY(created_by) REFERENCES user(username)
			ON DELETE SET NULL ON UPDATE CASCADE,
		CONSTRAINT fk_mutasi_saldo_kas_kecil_modified_by FOREIGN KEY(modified_by) REFERENCES user(username)
			ON DELETE SET NULL ON UPDATE CASCADE
	)ENGINE=InnoDb;
-- End Tabel Mutasi Saldo Kas Kecil

-- Tabel Mutasi Saldo sub kas kecil
	DROP TABLE IF EXISTS mutasi_saldo_sub_kas_kecil;
	CREATE TABLE IF NOT EXISTS mutasi_saldo_sub_kas_kecil(
		id int NOT NULL AUTO_INCREMENT, -- pk

		created_on datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
		modified_on datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
		created_by varchar(50), -- who created first
		modified_by varchar(50), -- who last edit

		id_sub_kas_kecil varchar(10), -- fk sub kas kecil
		tgl date,
		uang_masuk double(12,2),
		uang_keluar double(12,2),
		saldo double(12,2), -- saldo saat pada mutasi per tanggal
		ket text,

		CONSTRAINT pk_mutasi_saldo_sub_kas_kecil_id PRIMARY KEY(id),
		CONSTRAINT fk_mutasi_saldo_sub_kas_kecil_id_sub_kas_kecil FOREIGN KEY(id_sub_kas_kecil) REFERENCES sub_kas_kecil(id)
			ON DELETE SET NULL ON UPDATE CASCADE,
		CONSTRAINT fk_mutasi_saldo_sub_kas_kecil_modified_by FOREIGN KEY(modified_by) REFERENCES user(username)
			ON DELETE SET NULL ON UPDATE CASCADE
	)ENGINE=InnoDb;
-- End Tabel Mutasi Saldo sub kas kecil

-- Tabel Pengajuan Sub Kas Kecil
	DROP TABLE IF EXISTS pengajuan_sub_kas_kecil;
	CREATE TABLE IF NOT EXISTS pengajuan_sub_kas_kecil(
		id varchar(50) NOT NULL UNIQUE, -- pk, id+proyek+sub_kas_kecil+increment

		created_on datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
		modified_on datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
		created_by varchar(50), -- who created first
		modified_by varchar(50), -- who last edit

		id_sub_kas_kecil varchar(10), -- fk sub kecil
		id_proyek varchar(50), -- fk proyek
		tgl date, -- tgl pengajuan
		tgl_laporan date, -- tgl laporan
		nama varchar(50),
		total double(12,2), -- total pengajuan
		dana_disetujui double(12,2), -- dana yg disetujui, default 0 atau sama dengan total
		status char(1), -- status pengajuan, default 1: 'pending'
						-- 1: 'PENDING', 2: 'PERBAIKI', 3: 'DISETUJUI', 4: 'LANGSUNG', 5: 'DITOLAK'
		status_laporan char(1), -- status laporan, default set null
						-- 0: 'BELUM DIKERJAKAN', 1: 'PENDING', 2: 'PERBAIKI', 3: 'DISETUJUI', 4: 'DITOLAK'

		CONSTRAINT pk_pengajuan_sub_kas_kecil_id PRIMARY KEY(id),
		CONSTRAINT fk_pengajuan_sub_kas_kecil_id_sub_kas_kecil FOREIGN KEY(id_sub_kas_kecil) REFERENCES sub_kas_kecil(id)
			ON DELETE RESTRICT ON UPDATE CASCADE,
		CONSTRAINT fk_pengajuan_sub_kas_kecil_id_proyek FOREIGN KEY(id_proyek) REFERENCES proyek (id)
			ON DELETE RESTRICT ON UPDATE CASCADE,
		CONSTRAINT fk_pengajuan_sub_kas_kecil_created_by FOREIGN KEY(created_by) REFERENCES user(username)
			ON DELETE SET NULL ON UPDATE CASCADE,
		CONSTRAINT fk_pengajuan_sub_kas_kecil_modified_by FOREIGN KEY(modified_by) REFERENCES user(username)
			ON DELETE SET NULL ON UPDATE CASCADE
	)ENGINE=InnoDb;
-- End Tabel Pengajuan Sub Kas Kecil

-- Tabel Detail Pengajuan Sub Kas Kecil
	DROP TABLE IF EXISTS detail_pengajuan_sub_kas_kecil;
	CREATE TABLE IF NOT EXISTS detail_pengajuan_sub_kas_kecil(
		id int NOT NULL AUTO_INCREMENT, -- pk

		created_on datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
		modified_on datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
		created_by varchar(50), -- who created first
		modified_by varchar(50), -- who last edit

		id_pengajuan varchar(50), -- fk pengajuan sub kas kecil
		nama varchar(255), -- nama barang/bahan
		jenis char(1), -- jenis pengajuan, T: 'TEKNIS', N: 'NON-TEKNIS'
		satuan varchar(50), -- satuan barang/bahan
		qty int, -- jumlah barang/bahan
		harga double(12,2), -- harga satuan per barang/bahan
		subtotal double(12,2), -- total per detail pengajuan
		harga_asli double(12,2),
		sisa double(12,2),

		CONSTRAINT pk_detail_pengajuan_sub_kas_kecil_id PRIMARY KEY(id),
		CONSTRAINT fk_detail_pengajuan_sub_kas_kecil_id_pengajuan FOREIGN KEY(id_pengajuan) REFERENCES pengajuan_sub_kas_kecil(id)
			ON DELETE RESTRICT ON UPDATE CASCADE,
		CONSTRAINT fk_detail_pengajuan_sub_kas_kecil_created_by FOREIGN KEY(created_by) REFERENCES user(username)
			ON DELETE SET NULL ON UPDATE CASCADE,
		CONSTRAINT fk_detail_pengajuan_sub_kas_kecil_modified_by FOREIGN KEY(modified_by) REFERENCES user(username)
			ON DELETE SET NULL ON UPDATE CASCADE
	)ENGINE=InnoDb;
-- End Tabel Detail Pengajuan Sub Kas Kecil

-- Tabel Upload Laporan Pengajuan Sub Kas Kecil
	DROP TABLE IF EXISTS upload_laporan_pengajuan_sub_kas_kecil;
	CREATE TABLE IF NOT EXISTS upload_laporan_pengajuan_sub_kas_kecil(
		id int NOT NULL AUTO_INCREMENT, -- pk

		created_on datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
		modified_on datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
		created_by varchar(50), -- who created first
		modified_by varchar(50), -- who last edit

		id_pengajuan varchar(50), -- fk pengajuan sub kas kecil
		foto text,

		CONSTRAINT pk_upload_laporan_pengajuan_sub_kas_kecil PRIMARY KEY(id),
		CONSTRAINT fk_upload_laporan_pengajuan_sub_kas_kecil_id_pengajuan FOREIGN KEY(id_pengajuan) REFERENCES pengajuan_sub_kas_kecil(id)
			ON DELETE RESTRICT ON UPDATE CASCADE,
		CONSTRAINT fk_upload_laporan_pengajuan_sub_kas_kecil_created_by FOREIGN KEY(created_by) REFERENCES user(username)
			ON DELETE SET NULL ON UPDATE CASCADE,
		CONSTRAINT fk_upload_laporan_pengajuan_sub_kas_kecil_modified_by FOREIGN KEY(modified_by) REFERENCES user(username)
			ON DELETE SET NULL ON UPDATE CASCADE
	)ENGINE=InnoDb;
-- End Tabel Upload Laporan Pengajuan Sub Kas Kecil

-- Tabel Pengajuan Kas Kecil (pengajuan uang ke kas besar)
	DROP TABLE IF EXISTS pengajuan_kas_kecil;
	CREATE TABLE IF NOT EXISTS pengajuan_kas_kecil(
		id varchar(50) NOT NULL UNIQUE, -- pk

		created_on datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
		modified_on datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
		created_by varchar(50), -- who created first
		modified_by varchar(50), -- who last edit

		id_kas_kecil varchar(10), -- fk kas kecil
		id_bank int, -- fk bank
		tgl date,
		nama varchar(50), -- nama pengajuan
		total double(12,2), -- total pengajuan ke kas besar
		status char(1), -- status pengajuan, default 'pending'
		-- 0: 'PENDING', 1: 'PERBAIKI', 2: 'DISETUJUI', 3: 'DITOLAK'
		total_disetujui double(12,2),

		CONSTRAINT pk_pengajuan_kas_kecil_id PRIMARY KEY(id),
		CONSTRAINT fk_pengajuan_kas_kecil_id_kas_kecil FOREIGN KEY(id_kas_kecil) REFERENCES kas_kecil(id)
			ON DELETE RESTRICT ON UPDATE CASCADE,
		CONSTRAINT fk_pengajuan_kas_kecil_id_bank FOREIGN KEY(id_bank) REFERENCES bank(id)
			ON DELETE RESTRICT ON UPDATE CASCADE,
		CONSTRAINT fk_pengajuan_kas_kecil_modified_by FOREIGN KEY(modified_by) REFERENCES user(username)
			ON DELETE SET NULL ON UPDATE CASCADE
	)ENGINE=InnoDb;
-- End Tabel Pengajuan Kas Kecil

-- Tabel Distributor / Toko / Supplier
	DROP TABLE IF EXISTS distributor;
	CREATE TABLE IF NOT EXISTS distributor(
		id varchar(50) NOT NULL UNIQUE, -- primary key

		created_on datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
		modified_on datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
		created_by varchar(50), -- who created first
		modified_by varchar(50), -- who last edit

		nama varchar(255), -- nama distributor
		alamat text, -- alamat distributor
		no_telp varchar(25), -- telpon 
		pemilik varchar(255), -- pemilik
		status enum('AKTIF','NONAKTIF'),

		CONSTRAINT pk_distributor_id PRIMARY KEY(id),
		CONSTRAINT fk_distributor_created_by FOREIGN KEY(created_by) REFERENCES user(username)
			ON DELETE SET NULL ON UPDATE CASCADE,
		CONSTRAINT fk_distributor_modified_by FOREIGN KEY(modified_by) REFERENCES user(username)
			ON DELETE SET NULL ON UPDATE CASCADE
	)ENGINE=InnoDb;
-- End Tabel Distributor

-- Tabel Operasional Proyek (operasional proyek yang dilakukan langsung oleh kas besar)
	DROP TABLE IF EXISTS operasional_proyek;
	CREATE TABLE IF NOT EXISTS operasional_proyek(
		id varchar(50) NOT NULL UNIQUE,

		created_on datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
		modified_on datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
		created_by varchar(50), -- who created first
		modified_by varchar(50), -- who last edit

		id_proyek varchar(50), -- fk proyek
		-- id_bank int, -- fk bank
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
		CONSTRAINT fk_operasional_proyek_id_kas_besar FOREIGN KEY(id_kas_besar) REFERENCES kas_besar(id)
			ON DELETE RESTRICT ON UPDATE CASCADE,
		CONSTRAINT fk_operasional_proyek_id_distributor FOREIGN KEY(id_distributor) REFERENCES distributor(id)
			ON DELETE SET NULL ON UPDATE CASCADE,
		CONSTRAINT fk_operasional_proyek_created_by FOREIGN KEY(created_by) REFERENCES user(username)
			ON DELETE SET NULL ON UPDATE CASCADE,
		CONSTRAINT fk_operasional_proyek_modified_by FOREIGN KEY(modified_by) REFERENCES user(username)
			ON DELETE SET NULL ON UPDATE CASCADE
	)ENGINE=InnoDb;
-- End Tabel Operasional Proyek

-- Tabel Detail Operasional Proyek
	DROP TABLE IF EXISTS detail_operasional_proyek;
	CREATE TABLE IF NOT EXISTS detail_operasional_proyek(
		id int NOT NULL AUTO_INCREMENT, -- pk
		
		created_on datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
		modified_on datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
		created_by varchar(50), -- who created first
		modified_by varchar(50), -- who last edit

		id_operasional_proyek varchar(50), -- fk operasional proyek
		id_bank int,  -- fk bank 
		nama varchar(255), -- nama angsuran (angsuran ke-n)
		tgl date, -- tanggl angsuran
		total double(12,2), -- total angsuran

		CONSTRAINT pk_detail_operasional_proyek PRIMARY KEY(id),
		CONSTRAINT fk_detail_operasional_proyek_id_bank_from_bank FOREIGN KEY(id_bank) REFERENCES bank(id)
			ON DELETE RESTRICT ON UPDATE CASCADE,
		CONSTRAINT fk_detail_operasional_proyek_id_operasional FOREIGN KEY(id_operasional_proyek) REFERENCES operasional_proyek(id)
			ON DELETE RESTRICT ON UPDATE CASCADE,
		CONSTRAINT fk_detail_operasional_proyek_created_by FOREIGN KEY(created_by) REFERENCES user(username)
			ON DELETE SET NULL ON UPDATE CASCADE,
		CONSTRAINT fk_detail_operasional_proyek_modified_by FOREIGN KEY(modified_by) REFERENCES user(username)
			ON DELETE SET NULL ON UPDATE CASCADE
	)ENGINE=InnoDb;
-- End Tabel Detail Operasional Proyek

# ============================ End Base Tabel ============================ #