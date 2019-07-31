# Database 69design-build_dev #
# 69design-build_dev #
# 2.1 #

# Local Development Only

-- Remove commentary if you want build database from zero
# DROP DATABASE IF EXISTS `69design-build_dev`;
# CREATE DATABASE `69design-build_dev`;
# USE `69design-build_dev`;

# End Local Development Only

-- TABLE FOR APPLICATION, DO NOT CHANGE OR REMOVE --

    -- Table level lookup
        DROP TABLE IF EXISTS level_lookup;
        CREATE TABLE IF NOT EXISTS level_lookup (
            id INT UNSIGNED NOT NULL AUTO_INCREMENT,

            name VARCHAR(255) NOT NULL,
            description TEXT DEFAULT NULL,

            created_on DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            modified_on DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

            CONSTRAINT pk_level_lookup_id PRIMARY KEY(id)
        )ENGINE=InnoDb;
    -- End Table level lookup

    -- Table active status lookup
        DROP TABLE IF EXISTS active_status_lookup;
        CREATE TABLE IF NOT EXISTS active_status_lookup (
            id INT UNSIGNED NOT NULL AUTO_INCREMENT,

            name VARCHAR(255) NOT NULL,
            description TEXT DEFAULT NULL,

            created_on DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            modified_on DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

            CONSTRAINT pk_active_status_lookup_id PRIMARY KEY(id)
        )ENGINE=InnoDb;
    -- End Table active status lookup

    -- Table permission lookup
        DROP TABLE IF EXISTS permission_lookup;
        CREATE TABLE IF NOT EXISTS permission_lookup (
            id INT UNSIGNED NOT NULL AUTO_INCREMENT,

            name VARCHAR(255) NOT NULL,
            description TEXT DEFAULT NULL,

            created_on DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            modified_on DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

            CONSTRAINT pk_permission_lookup_id PRIMARY KEY(id)
        )ENGINE=InnoDb;
    -- End Table permission lookup

    -- Table User
        DROP TABLE IF EXISTS user;
        CREATE TABLE IF NOT EXISTS user (
            -- id INT UNSIGNED NOT NULL AUTO_INCREMENT,

            username VARCHAR(50) NOT NULL UNIQUE,
            password TEXT NOT NULL,
            -- name VARCHAR(255), -- optional, comentary if the name of user not contain in user table
            -- image text, -- optional, comentary if the image of user not contain in user table

            level enum('OWNER', 'KAS BESAR', 'KAS KECIL', 'SUB KAS KECIL'), -- v1
		    status enum('AKTIF', 'NONAKTIF'), -- status aktif username
            -- level_id INT UNSIGNED DEFAULT NULL, -- fk level lookup
            -- status_id INT UNSIGNED DEFAULT NULL, -- fk active status lookup

            created_on DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            modified_on DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            created_by VARCHAR(50) DEFAULT NULL, -- who created first
            modified_by VARCHAR(50) DEFAULT NULL, -- who last edit
            -- created_by INT UNSIGNED DEFAULT NULL, -- who created first
            -- modified_by INT UNSIGNED DEFAULT NULL, -- who last edit

            CONSTRAINT pk_user_username PRIMARY KEY(username),
            -- CONSTRAINT pk_user_user_id PRIMARY KEY(id),
            -- CONSTRAINT fk_user_level_id FOREIGN KEY(level_id) REFERENCES level_lookup(id)
            --     ON DELETE RESTRICT ON UPDATE CASCADE,
            -- CONSTRAINT fk_user_status_id FOREIGN KEY(status_id) REFERENCES active_status_lookup(id)
            --     ON DELETE RESTRICT ON UPDATE CASCADE,
            CONSTRAINT fk_user_created_by FOREIGN KEY(created_by) REFERENCES user(username)
                ON DELETE SET NULL ON UPDATE CASCADE,
            CONSTRAINT fk_user_modified_by FOREIGN KEY(modified_by) REFERENCES user(username)
                ON DELETE SET NULL ON UPDATE CASCADE
            -- CONSTRAINT fk_user_created_by FOREIGN KEY(created_by) REFERENCES user(id)
            --     ON DELETE SET NULL ON UPDATE CASCADE,
            -- CONSTRAINT fk_user_modified_by FOREIGN KEY(modified_by) REFERENCES user(id)
            --     ON DELETE SET NULL ON UPDATE CASCADE
        )ENGINE=InnoDb;
    -- End Table user

    -- Tabel Menu
        DROP TABLE IF EXISTS menu;
        CREATE TABLE IF NOT EXISTS menu(
            id INT UNSIGNED NOT NULL AUTO_INCREMENT,

            name VARCHAR(255) NOT NULL,
            table_name VARCHAR(255) DEFAULT NULL,
            url VARCHAR(255) NOT NULL,
            class VARCHAR(50) DEFAULT NULL,
            icon VARCHAR(50) DEFAULT NULL,
            position TINYINT UNSIGNED DEFAULT NULL,

            created_on DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            modified_on DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

            CONSTRAINT pk_menu_id PRIMARY KEY(id)
        )ENGINE=InnoDb;
    -- End Table Menu

    -- Tabel Menu detail
        DROP TABLE IF EXISTS menu_detail;
        CREATE TABLE IF NOT EXISTS menu_detail(
            id INT UNSIGNED NOT NULL AUTO_INCREMENT,

            menu_id INT UNSIGNED DEFAULT NULL, -- fk menu
            permission_id INT UNSIGNED DEFAULT NULL, -- fk permission lookup

            created_on DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            modified_on DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

            CONSTRAINT pk_menu_detail_id PRIMARY KEY(id),
            CONSTRAINT fk_menu_detail_menu_id FOREIGN KEY(menu_id) REFERENCES menu(id)
                ON DELETE RESTRICT ON UPDATE CASCADE,
            CONSTRAINT fk_menu_detail_permission_id FOREIGN KEY(permission_id) REFERENCES permission_lookup(id)
                ON DELETE RESTRICT ON UPDATE CASCADE
        )ENGINE=InnoDb;
    -- End Table Menu detail

    -- Tabel Access Menu
        DROP TABLE IF EXISTS access_menu;
        CREATE TABLE IF NOT EXISTS access_menu (
            id INT UNSIGNED NOT NULL AUTO_INCREMENT,

            level_id INT UNSIGNED DEFAULT NULL, -- fk level lookup
            menu_id INT UNSIGNED DEFAULT NULL, -- fk menu

            created_on DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            modified_on DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

            CONSTRAINT pk_access_menu_id PRIMARY KEY(id),
            CONSTRAINT fk_access_menu_level_id FOREIGN KEY(level_id) REFERENCES level_lookup(id)
                ON DELETE RESTRICT ON UPDATE CASCADE,
            CONSTRAINT fk_access_menu_menu_id FOREIGN KEY(menu_id) REFERENCES menu(id)
                ON DELETE RESTRICT ON UPDATE CASCADE
        )ENGINE=InnoDb;
    -- End Table Access Menu

    -- Tabel Role Permission
        DROP TABLE IF EXISTS role_permission;
        CREATE TABLE IF NOT EXISTS role_permission(
            id INT UNSIGNED NOT NULL AUTO_INCREMENT,

            username VARCHAR(50) DEFAULT NULL,
            -- user_id INT UNSIGNED DEFAULT NULL, -- fk user
            menu_permission_id INT UNSIGNED DEFAULT NULL, -- fk menu detail

            created_on DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            modified_on DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

            CONSTRAINT pk_role_permission_id PRIMARY KEY(id),
            -- CONSTRAINT fk_role_permission_user_id FOREIGN KEY(user_id) REFERENCES user(id)
            --     ON DELETE RESTRICT ON UPDATE CASCADE,
            CONSTRAINT fk_role_permission_username FOREIGN KEY(username) REFERENCES user(username)
                ON DELETE RESTRICT ON UPDATE CASCADE,
            CONSTRAINT fk_role_permission_menu_permission_id FOREIGN KEY(menu_permission_id) REFERENCES menu_detail(id)
                ON DELETE RESTRICT ON UPDATE CASCADE
        )ENGINE=InnoDb;
    -- End Table Role Permission

    -- Table Increment
        DROP TABLE IF EXISTS increment;
        CREATE TABLE IF NOT EXISTS increment(
            id INT UNSIGNED NOT NULL AUTO_INCREMENT,

            menu_id INT UNSIGNED NOT NULL UNIQUE, -- fk menu
            mask VARCHAR(255) DEFAULT NULL, -- format increment
            last_increment INT UNSIGNED DEFAULT 0,
            description TEXT DEFAULT NULL,

            created_on DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            modified_on DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

            CONSTRAINT pk_increment_id PRIMARY KEY(id),
            CONSTRAINT fk_increment_menu_id FOREIGN KEY(menu_id) REFERENCES menu(id)
                ON DELETE RESTRICT ON UPDATE CASCADE
        )ENGINE=InnoDb;
    -- End Table Increment

-- END TABLE FOR APPLICATION, DO NOT CHANGE OR REMOVE --

-- PUT YOUR TABLE IN HERE --
    
    -- Table Status Proyek Lookup
    -- End Table Proyek Status Lookup

    -- Table Jenis Uang Lookup (uang masuk - uang keluar)
    -- End Table Jenis Uang Lookup

    -- Table Jenis Operasional Lookup (teknis - nonteknis)
    -- End Table Jenis Operasional Lookup

    -- Table Jenis Pembayaran Lookup (cash - kredit)
    -- End Table Jenis Pembayaran Lookup

    -- Table Status Lunas Lookup
    -- End Table Status Lunas Lookup

    -- Table Status Pengajuan Lookup
    -- End Table Status Pengajuan Lookup

    -- Table Kas Besar
        DROP TABLE IF EXISTS owner;
        CREATE TABLE IF NOT EXISTS owner(
            id VARCHAR(10) NOT NULL UNIQUE, -- pk

            nama VARCHAR(255) DEFAULT NULL,
            alamat TEXT DEFAULT NULL,
            no_telp VARCHAR(20) DEFAULT NULL,
            email VARCHAR(50) DEFAULT NULL UNIQUE, -- fk user
            foto TEXT DEFAULT NULL,
            status ENUM('AKTIF', 'NONAKTIF') DEFAULT NULL, -- status aktif kas besar
            -- status_id INT UNSIGNED DEFAULT NULL,

            created_on DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            modified_on DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            created_by VARCHAR(50) DEFAULT NULL, -- who created first
            modified_by VARCHAR(50) DEFAULT NULL, -- who last edit

            CONSTRAINT pk_owner_id PRIMARY KEY(id),
            CONSTRAINT fk_owner_email FOREIGN KEY(email) REFERENCES user(username)
                ON DELETE SET NULL ON UPDATE CASCADE,
            -- CONSTRAINT fk_owner_status_id FOREIGN KEY(status_id) REFERENCES active_status_lookup(id)
            --     ON DELETE RESTRICT ON UPDATE CASCADE,
            CONSTRAINT fk_owner_created_by FOREIGN KEY(created_by) REFERENCES user(username)
                ON DELETE SET NULL ON UPDATE CASCADE,
            CONSTRAINT fk_owner_modified_by FOREIGN KEY(modified_by) REFERENCES user(username)
                ON DELETE SET NULL ON UPDATE CASCADE
        )ENGINE=InnoDb;
    -- End Table Kas Besar

    -- Table Kas Besar
        DROP TABLE IF EXISTS kas_besar;
        CREATE TABLE IF NOT EXISTS kas_besar(
            id VARCHAR(10) NOT NULL UNIQUE, -- pk

            nama VARCHAR(255) DEFAULT NULL,
            alamat TEXT DEFAULT NULL,
            no_telp VARCHAR(20) DEFAULT NULL,
            email VARCHAR(50) DEFAULT NULL UNIQUE, -- fk user
            foto TEXT DEFAULT NULL,
            status ENUM('AKTIF', 'NONAKTIF') DEFAULT NULL, -- status aktif kas besar
            -- status_id INT UNSIGNED DEFAULT NULL,

            created_on DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            modified_on DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            created_by VARCHAR(50) DEFAULT NULL, -- who created first
            modified_by VARCHAR(50) DEFAULT NULL, -- who last edit

            CONSTRAINT pk_kas_besar_id PRIMARY KEY(id),
            CONSTRAINT fk_kas_besar_email FOREIGN KEY(email) REFERENCES user(username)
                ON DELETE SET NULL ON UPDATE CASCADE,
            -- CONSTRAINT fk_kas_besar_status_id FOREIGN KEY(status_id) REFERENCES active_status_lookup(id)
            --     ON DELETE RESTRICT ON UPDATE CASCADE,
            CONSTRAINT fk_kas_besar_created_by FOREIGN KEY(created_by) REFERENCES user(username)
                ON DELETE SET NULL ON UPDATE CASCADE,
            CONSTRAINT fk_kas_besar_modified_by FOREIGN KEY(modified_by) REFERENCES user(username)
                ON DELETE SET NULL ON UPDATE CASCADE
        )ENGINE=InnoDb;
    -- End Table Kas Besar

    -- Table Kas Kecil
        DROP TABLE IF EXISTS kas_kecil;
        CREATE TABLE IF NOT EXISTS kas_kecil(
            id VARCHAR(10) NOT NULL UNIQUE, -- pk

            nama VARCHAR(50) DEFAULT NULL, -- nama kas kecil
            alamat TEXT DEFAULT NULL,
            no_telp VARCHAR(20) DEFAULT NULL,
            email VARCHAR(50) DEFAULT NULL UNIQUE, -- fk user
            foto TEXT DEFAULT NULL,
            saldo DOUBLE(12,2) DEFAULT 0, -- saldo kas kecil, default 0
            status ENUM('AKTIF', 'NONAKTIF') DEFAULT NULL, -- status aktif kas kecil
            -- status_id INT UNSIGNED DEFAULT NULL,

            created_on DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            modified_on DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            created_by VARCHAR(50) DEFAULT NULL, -- who created first
            modified_by VARCHAR(50) DEFAULT NULL, -- who last edit

            CONSTRAINT pk_kas_kecil_id PRIMARY KEY(id),
            CONSTRAINT fk_kas_kecil_email FOREIGN KEY(email) REFERENCES user(username)
                ON DELETE SET NULL ON UPDATE CASCADE,
            -- CONSTRAINT fk_kas_kecil_status_id FOREIGN KEY(status_id) REFERENCES active_status_lookup(id)
            --     ON DELETE RESTRICT ON UPDATE CASCADE,
            CONSTRAINT fk_kas_kecil_created_by FOREIGN KEY(created_by) REFERENCES user(username)
                ON DELETE SET NULL ON UPDATE CASCADE,
            CONSTRAINT fk_kas_kecil_modified_by FOREIGN KEY(modified_by) REFERENCES user(username)
                ON DELETE SET NULL ON UPDATE CASCADE
        )ENGINE=InnoDb;
    -- End Table Kas Kecil

    -- Table Sub Kas Kecil
        DROP TABLE IF EXISTS sub_kas_kecil;
        CREATE TABLE IF NOT EXISTS sub_kas_kecil(
            id VARCHAR(10) NOT NULL UNIQUE, -- pk, id+increment, contoh: log001
            
            nama VARCHAR(255) DEFAULT NULL,
            alamat TEXT DEFAULT NULL,
            no_telp VARCHAR(20) DEFAULT NULL,
            email VARCHAR(50) DEFAULT NULL UNIQUE, -- username
            foto TEXT DEFAULT NULL, -- simpan urlnya
            saldo DOUBLE(12,2) DEFAULT 0, -- saldo master, default 0
            status ENUM('AKTIF', 'NONAKTIF') DEFAULT NULL, -- status aktif sub kas kecil
            -- status_id INT UNSIGNED DEFAULT NULL,

            created_on DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            modified_on DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            created_by VARCHAR(50) DEFAULT NULL, -- who created first
            modified_by VARCHAR(50) DEFAULT NULL, -- who last edit

            CONSTRAINT pk_sub_kas_kecil_id PRIMARY KEY(id),
            CONSTRAINT fk_sub_kas_kecil_email FOREIGN KEY(email) REFERENCES user(username)
                ON DELETE SET NULL ON UPDATE CASCADE,
            -- CONSTRAINT fk_sub_kas_kecil_status_id FOREIGN KEY(status_id) REFERENCES active_status_lookup(id)
            --     ON DELETE RESTRICT ON UPDATE CASCADE,
            CONSTRAINT fk_sub_kas_kecil_created_by FOREIGN KEY(created_by) REFERENCES user(username)
                ON DELETE SET NULL ON UPDATE CASCADE,
            CONSTRAINT fk_sub_kas_kecil_modified_by FOREIGN KEY(modified_by) REFERENCES user(username)
                ON DELETE SET NULL ON UPDATE CASCADE
        )ENGINE=InnoDb;
    -- End Table Sub Kas Kecil

    -- Table Token Mobile
        DROP TABLE IF EXISTS token_mobile;
        CREATE TABLE IF NOT EXISTS token_mobile(
            id INT UNSIGNED NOT NULL AUTO_INCREMENT, -- pk

            username VARCHAR(50) DEFAULT NULL, -- fk
            token TEXT DEFAULT NULL,
            tgl_buat DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            tgl_exp DATETIME NOT NULL,

            created_on DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            modified_on DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            created_by VARCHAR(50) DEFAULT NULL, -- who created first
            modified_by VARCHAR(50) DEFAULT NULL, -- who last edit

            CONSTRAINT pk_token_mobile PRIMARY KEY(id),
            CONSTRAINT fk_token_mobile_username FOREIGN KEY(username) REFERENCES user(username)
                ON DELETE SET NULL ON UPDATE RESTRICT,
            CONSTRAINT fk_token_mobile_created_by FOREIGN KEY(created_by) REFERENCES user(username)
                ON DELETE SET NULL ON UPDATE CASCADE,
            CONSTRAINT fk_token_mobile_modified_by FOREIGN KEY(modified_by) REFERENCES user(username)
                ON DELETE SET NULL ON UPDATE CASCADE
        )ENGINE=InnoDb;
    -- End Table Token Mobile

    -- Table Token Lupa Password
        DROP TABLE IF EXISTS token_lupa_password;
        CREATE TABLE IF NOT EXISTS token_lupa_password(
            id INT UNSIGNED NOT NULL AUTO_INCREMENT, -- pk

            username VARCHAR(50) DEFAULT NULL, -- fk
            token TEXT DEFAULT NULL,
            tgl_buat DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            tgl_exp DATETIME NOT NULL,

            created_on DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            modified_on DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            created_by VARCHAR(50) DEFAULT NULL, -- who created first
            modified_by VARCHAR(50) DEFAULT NULL, -- who last edit

            CONSTRAINT pk_token_lupa_password PRIMARY KEY(id),
            CONSTRAINT fk_token_lupa_password FOREIGN KEY(username) REFERENCES user(username)
                ON DELETE RESTRICT ON UPDATE RESTRICT,
            CONSTRAINT fk_token_lupa_password_created_by FOREIGN KEY(created_by) REFERENCES user(username)
                ON DELETE SET NULL ON UPDATE CASCADE,
            CONSTRAINT fk_token_lupa_password_modified_by FOREIGN KEY(modified_by) REFERENCES user(username)
                ON DELETE SET NULL ON UPDATE CASCADE
        )ENGINE=InnoDb;
    -- End Table Token Lupa Password

    -- Table Bank
        DROP TABLE IF EXISTS bank;
        CREATE TABLE IF NOT EXISTS bank(
            id INT UNSIGNED NOT NULL AUTO_INCREMENT, -- pk

            nama VARCHAR(255) DEFAULT NULL, -- nama bank / jenis bank, Bank BCA, Giro BCA, Mandiri, dll
            saldo DOUBLE(12,2) DEFAULT 0, -- saldo bank
            status ENUM('AKTIF', 'NONAKTIF') DEFAULT NULL, -- status aktif bank
            -- status_id INT UNSIGNED DEFAULT NULL,

            created_on DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            modified_on DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            created_by VARCHAR(50) DEFAULT NULL, -- who created first
            modified_by VARCHAR(50) DEFAULT NULL, -- who last edit

            CONSTRAINT pk_bank_id PRIMARY KEY(id),
            -- CONSTRAINT fk_bank_status_id FOREIGN KEY(status_id) REFERENCES active_status_lookup(id)
            --     ON DELETE RESTRICT ON UPDATE CASCADE,
            CONSTRAINT fk_bank_created_by FOREIGN KEY(created_by) REFERENCES user(username)
                ON DELETE SET NULL ON UPDATE CASCADE,
            CONSTRAINT fk_bank_modified_by FOREIGN KEY(modified_by) REFERENCES user(username)
                ON DELETE SET NULL ON UPDATE CASCADE
        )ENGINE=InnoDb;
    -- End Table Bank

    -- Table Mutasi Bank
        DROP TABLE IF EXISTS mutasi_bank;
        CREATE TABLE IF NOT EXISTS mutasi_bank(
            id INT UNSIGNED NOT NULL AUTO_INCREMENT, -- pk

            id_bank INT UNSIGNED DEFAULT NULL, -- fk bank
            tgl DATE DEFAULT NULL,
            uang_masuk DOUBLE(12,2) UNSIGNED DEFAULT 0,
            uang_keluar DOUBLE(12,2) UNSIGNED DEFAULT 0,
            saldo DOUBLE(12,2) DEFAULT 0, -- saldo per tanggal
            ket TEXT DEFAULT NULL,

            created_on DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            modified_on DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            created_by VARCHAR(50) DEFAULT NULL, -- who created first
            modified_by VARCHAR(50) DEFAULT NULL, -- who last edit

            CONSTRAINT pk_mutasi_bank_id PRIMARY KEY(id),
            CONSTRAINT fk_mutasi_bank_id_bank FOREIGN KEY(id_bank) REFERENCES bank(id)
                ON DELETE SET NULL ON UPDATE CASCADE,
            CONSTRAINT fk_mutasi_bank_created_by FOREIGN KEY(created_by) REFERENCES user(username)
                ON DELETE SET NULL ON UPDATE CASCADE,
            CONSTRAINT fk_mutasi_bank_modified_by FOREIGN KEY(modified_by) REFERENCES user(username)
                ON DELETE SET NULL ON UPDATE CASCADE
        )ENGINE=InnoDb;
    -- End Table Mutasi Bank

    -- Table Mutasi Kas Kecil
        DROP TABLE IF EXISTS mutasi_saldo_kas_kecil;
        CREATE TABLE IF NOT EXISTS mutasi_saldo_kas_kecil(
            id INT UNSIGNED NOT NULL AUTO_INCREMENT, -- pk

            id_kas_kecil VARCHAR(10) DEFAULT NULL, -- fk kas kecil
            tgl DATE DEFAULT NULL,
            uang_masuk DOUBLE(12,2) UNSIGNED DEFAULT 0,
            uang_keluar DOUBLE(12,2) UNSIGNED DEFAULT 0,
            saldo DOUBLE(12,2) DEFAULT 0, -- saldo saat pada mutasi per tanggal
            ket TEXT DEFAULT NULL,

            created_on DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            modified_on DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            created_by VARCHAR(50) DEFAULT NULL, -- who created first
            modified_by VARCHAR(50) DEFAULT NULL, -- who last edit

            CONSTRAINT pk_mutasi_saldo_kas_kecil_id PRIMARY KEY(id),
            CONSTRAINT fk_mutasi_saldo_kas_kecil_id_kas_kecil FOREIGN KEY(id_kas_kecil) REFERENCES kas_kecil(id)
                ON DELETE SET NULL ON UPDATE CASCADE,
            CONSTRAINT fk_mutasi_saldo_kas_kecil_created_by FOREIGN KEY(created_by) REFERENCES user(username)
                ON DELETE SET NULL ON UPDATE CASCADE,
            CONSTRAINT fk_mutasi_saldo_kas_kecil_modified_by FOREIGN KEY(modified_by) REFERENCES user(username)
                ON DELETE SET NULL ON UPDATE CASCADE
        )ENGINE=InnoDb;
    -- End Table Mutasi Kas Kecil

    -- Table Mutasi Sub Kas Kecil
        DROP TABLE IF EXISTS mutasi_saldo_sub_kas_kecil;
        CREATE TABLE IF NOT EXISTS mutasi_saldo_sub_kas_kecil(
            id INT UNSIGNED NOT NULL AUTO_INCREMENT, -- pk

            id_sub_kas_kecil VARCHAR(10) DEFAULT NULL, -- fk sub kas kecil
            tgl DATE DEFAULT NULL,
            uang_masuk DOUBLE(12,2) UNSIGNED DEFAULT 0,
            uang_keluar DOUBLE(12,2) UNSIGNED DEFAULT 0,
            saldo DOUBLE(12,2) DEFAULT 0, -- saldo saat pada mutasi per tanggal
            ket TEXT DEFAULT NULL,

            created_on DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            modified_on DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            created_by VARCHAR(50) DEFAULT NULL, -- who created first
            modified_by VARCHAR(50) DEFAULT NULL, -- who last edit

            CONSTRAINT pk_mutasi_saldo_sub_kas_kecil_id PRIMARY KEY(id),
            CONSTRAINT fk_mutasi_saldo_sub_kas_kecil_id_sub_kas_kecil FOREIGN KEY(id_sub_kas_kecil) REFERENCES sub_kas_kecil(id)
                ON DELETE SET NULL ON UPDATE CASCADE,
            CONSTRAINT fk_mutasi_saldo_sub_kas_kecil_modified_by FOREIGN KEY(modified_by) REFERENCES user(username)
                ON DELETE SET NULL ON UPDATE CASCADE
        )ENGINE=InnoDb;
    -- End Table Mutasi Sub Kas Kecil

    -- Table Distributor
        DROP TABLE IF EXISTS distributor;
        CREATE TABLE IF NOT EXISTS distributor(
            id VARCHAR(50) NOT NULL UNIQUE, -- primary key

            nama VARCHAR(255) DEFAULT NULL, -- nama distributor
            alamat TEXT DEFAULT NULL, -- alamat distributor
            no_telp VARCHAR(25) DEFAULT NULL, -- telpon 
            pemilik VARCHAR(255) DEFAULT NULL, -- pemilik
            status ENUM('AKTIF','NONAKTIF') DEFAULT NULL,
            -- status_id INT UNSIGNED DEFAULT NULL,

            created_on DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            modified_on DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            created_by VARCHAR(50) DEFAULT NULL, -- who created first
            modified_by VARCHAR(50) DEFAULT NULL, -- who last edit

            CONSTRAINT pk_distributor_id PRIMARY KEY(id),
            -- CONSTRAINT fk_distributor_status_id FOREIGN KEY(status_id) REFERENCES active_status_lookup(id)
            --     ON DELETE RESTRICT ON UPDATE CASCADE,
            CONSTRAINT fk_distributor_created_by FOREIGN KEY(created_by) REFERENCES user(username)
                ON DELETE SET NULL ON UPDATE CASCADE,
            CONSTRAINT fk_distributor_modified_by FOREIGN KEY(modified_by) REFERENCES user(username)
                ON DELETE SET NULL ON UPDATE CASCADE
        )ENGINE=InnoDb;
    -- End Table Distributor

    -- Table Proyek
        DROP TABLE IF EXISTS proyek;
        CREATE TABLE IF NOT EXISTS proyek(
            id VARCHAR(50) NOT NULL UNIQUE, -- pk, otomatis

            pemilik VARCHAR(255) DEFAULT NULL,
            tgl DATE DEFAULT NULL,
            pembangunan VARCHAR(255) DEFAULT NULL, -- keterangan yg dibangun
            luas_area DOUBLE(10,2) UNSIGNED DEFAULT NULL,
            alamat TEXT DEFAULT NULL,
            kota VARCHAR(100) DEFAULT NULL,
            estimasi SMALLINT UNSIGNED DEFAULT NULL, -- estimasi waktu dalam bulan
            total DOUBLE(12,2) UNSIGNED DEFAULT 0, -- total nilai rab
            dp DOUBLE(12,2) UNSIGNED DEFAULT 0, -- dp
            cco DOUBLE(12,2) UNSIGNED DEFAULT 0, -- change contract order
            progress TINYINT UNSIGNED DEFAULT 0,
            status ENUM('SELESAI', 'BERJALAN') DEFAULT NULL, -- status proyek
            -- status_id INT UNSIGNED DEFAULT NULL,

            created_on DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            modified_on DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            created_by VARCHAR(50) DEFAULT NULL, -- who created first
            modified_by VARCHAR(50) DEFAULT NULL, -- who last edit

            CONSTRAINT pk_proyek_id PRIMARY KEY(id),
            -- CONSTRAINT fk_proyek_status_id FOREIGN KEY(status_id) REFERENCES proyek_status_lookup(id)
            --     ON DELETE RESTRICT ON UPDATE CASCADE,
            CONSTRAINT fk_proyek_created_by FOREIGN KEY(created_by) REFERENCES user(username)
                ON DELETE SET NULL ON UPDATE CASCADE,
            CONSTRAINT fk_proyek_modified_by FOREIGN KEY(modified_by) REFERENCES user(username)
                ON DELETE SET NULL ON UPDATE CASCADE
        )ENGINE=InnoDb;
    -- End Table Proyek

    -- Table Detail Proyek - detail pembayaran
        DROP TABLE IF EXISTS detail_proyek;
        CREATE TABLE IF NOT EXISTS detail_proyek(
            id INT UNSIGNED NOT NULL AUTO_INCREMENT, -- pk

            id_proyek VARCHAR(50) DEFAULT NULL, -- fk proyek
            id_bank INT UNSIGNED DEFAULT NULL, -- fk bank
            tgl DATE DEFAULT NULL,
            nama VARCHAR(255) DEFAULT NULL, -- nama pembayaran
            total DOUBLE(12,2) UNSIGNED DEFAULT 0, -- total angsuran
            is_DP CHAR(1) DEFAULT '0', -- check DP atau bukan (1: DP, 0: Bukan)

            created_on DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            modified_on DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            created_by VARCHAR(50) DEFAULT NULL, -- who created first
            modified_by VARCHAR(50) DEFAULT NULL, -- who last edit

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
    -- End Table Detail Proyek

    -- Table Detail SKK Proyek
        DROP TABLE IF EXISTS logistik_proyek;
        CREATE TABLE IF NOT EXISTS logistik_proyek(
        -- DROP TABLE IF EXISTS detail_skk_proyek;
        -- CREATE TABLE IF NOT EXISTS detail_skk_proyek(
            id INT UNSIGNED NOT NULL AUTO_INCREMENT, -- pk

            id_proyek VARCHAR(50) DEFAULT NULL, -- fk proyek
            id_sub_kas_kecil VARCHAR(10) DEFAULT NULL, -- fk sub kas kecil\

            created_on DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            modified_on DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            created_by VARCHAR(50) DEFAULT NULL, -- who created first
            modified_by VARCHAR(50) DEFAULT NULL, -- who last edit

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
    -- End Table Detail SKK Proyek

    -- Table Operasional
        DROP TABLE IF EXISTS operasional;
        CREATE TABLE IF NOT EXISTS operasional(
            id INT UNSIGNED NOT NULL AUTO_INCREMENT, -- pk

            id_bank INT UNSIGNED DEFAULT NULL, -- fk bank
            id_kas_besar VARCHAR(10) DEFAULT NULL,
            tgl DATE DEFAULT NULL,
            nama VARCHAR(255) DEFAULT NULL,
            nominal DOUBLE(12,2) UNSIGNED DEFAULT 0,
            jenis ENUM('UANG MASUK', 'UANG KELUAR') DEFAULT NULL,
            -- jenis_id INT UNSIGNED DEFAULT NULL,
            ket TEXT DEFAULT NULL,

            created_on DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            modified_on DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            created_by VARCHAR(50) DEFAULT NULL, -- who created first
            modified_by VARCHAR(50) DEFAULT NULL, -- who last edit

            CONSTRAINT pk_opersional_id PRIMARY KEY(id),
            CONSTRAINT fk_operasional_id_bank FOREIGN KEY(id_bank) REFERENCES bank(id)
                ON DELETE RESTRICT ON UPDATE CASCADE,
            CONSTRAINT fk_operasional_id_kas_besar FOREIGN KEY(id_kas_besar) REFERENCES kas_besar(id)
                ON DELETE RESTRICT ON UPDATE CASCADE,
            -- CONSTRAINT fk_operasional_jenis_id FOREIGN KEY(jenis_id) REFERENCES jenis_uang_lookup(id)
            --     ON DELETE RESTRICT ON UPDATE CASCADE,
            CONSTRAINT fk_operasional_created_by FOREIGN KEY(created_by) REFERENCES user(username)
                ON DELETE SET NULL ON UPDATE CASCADE,
            CONSTRAINT fk_operasional_modified_by FOREIGN KEY(modified_by) REFERENCES user(username)
                ON DELETE SET NULL ON UPDATE CASCADE
        )ENGINE=InnoDb;
    -- End Table Operasaional

    -- Table Operasional Proyek
        DROP TABLE IF EXISTS operasional_proyek;
        CREATE TABLE IF NOT EXISTS operasional_proyek(
            id VARCHAR(50) NOT NULL UNIQUE,

            id_proyek VARCHAR(50) DEFAULT NULL, -- fk proyek
            id_kas_besar VARCHAR(10) DEFAULT NULL, -- fk kas besar
            id_distributor VARCHAR(10) DEFAULT NULL, -- fk distributor
            tgl DATE DEFAULT NULL,
            nama VARCHAR(50) DEFAULT NULL, -- nama operasional
            jenis ENUM('TEKNIS', 'NON-TEKNIS') DEFAULT NULL, -- jenis operasional,
            -- jenis_id INT UNSIGNED DEFAULT NULL,
            total DOUBLE(12,2) UNSIGNED DEFAULT 0, -- total operasional
            sisa DOUBLE(12,2) UNSIGNED DEFAULT 0, -- sisa jika bayar secara cicil, default 0
            status ENUM('TUNAI', 'KREDIT') DEFAULT NULL, -- T: Tunai, K: Kredit
            -- jenis_pembayaran_id INT UNSIGNED DEFAULT NULL,
            status_lunas ENUM('LUNAS', 'BELUM LUNAS') DEFAULT NULL, -- L: Lunas, B: Belum Lunas
            -- status_id INT UNSIGNED DEFAULT NULL,
            ket TEXT DEFAULT NULL,

            created_on DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            modified_on DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            created_by VARCHAR(50) DEFAULT NULL, -- who created first
            modified_by VARCHAR(50) DEFAULT NULL, -- who last edit

            CONSTRAINT pk_operasional_proyek_id PRIMARY KEY(id),
            CONSTRAINT fk_operasional_proyek_id_proyek FOREIGN KEY(id_proyek) REFERENCES proyek(id)
                ON DELETE RESTRICT ON UPDATE CASCADE,
            CONSTRAINT fk_operasional_proyek_id_kas_besar FOREIGN KEY(id_kas_besar) REFERENCES kas_besar(id)
                ON DELETE RESTRICT ON UPDATE CASCADE,
            CONSTRAINT fk_operasional_proyek_id_distributor FOREIGN KEY(id_distributor) REFERENCES distributor(id)
                ON DELETE SET NULL ON UPDATE CASCADE,
            -- CONSTRAINT fk_operasional_jenis_id FOREIGN KEY(jenis_id) REFERENCES jenis_operasional_lookup(id)
            --     ON DELETE RESTRICT ON UPDATE CASCADE,
            -- CONSTRAINT fk_operasional_jenis_pembayaran_id FOREIGN KEY(jenis_pembayaran_id) REFERENCES jenis_pembayaran_lookup(id)
            --     ON DELETE RESTRICT ON UPDATE CASCADE,
            -- CONSTRAINT fk_operasional_status_id FOREIGN KEY(status_id) REFERENCES status_lunas_lookup(id)
            --     ON DELETE RESTRICT ON UPDATE CASCADE,
            CONSTRAINT fk_operasional_proyek_created_by FOREIGN KEY(created_by) REFERENCES user(username)
                ON DELETE SET NULL ON UPDATE CASCADE,
            CONSTRAINT fk_operasional_proyek_modified_by FOREIGN KEY(modified_by) REFERENCES user(username)
                ON DELETE SET NULL ON UPDATE CASCADE
        )ENGINE=InnoDb;
    -- End Table Operasional Proyek

    -- Table Detail Operasional Proyek
        DROP TABLE IF EXISTS detail_operasional_proyek;
        CREATE TABLE IF NOT EXISTS detail_operasional_proyek(
            id INT UNSIGNED NOT NULL AUTO_INCREMENT, -- pk

            id_operasional_proyek VARCHAR(50) DEFAULT NULL, -- fk operasional proyek
            id_bank INT UNSIGNED DEFAULT NULL,  -- fk bank 
            nama VARCHAR(255) DEFAULT NULL, -- nama angsuran (angsuran ke-n)
            tgl DATE DEFAULT NULL, -- tanggl angsuran
            total DOUBLE(12,2) UNSIGNED DEFAULT 0, -- total angsuran

            created_on DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            modified_on DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            created_by VARCHAR(50) DEFAULT NULL, -- who created first
            modified_by VARCHAR(50) DEFAULT NULL, -- who last edit

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
    -- End Table Operasional Proyek

    -- Table Pengajuan Sub Kas Kecil
        DROP TABLE IF EXISTS pengajuan_sub_kas_kecil;
        CREATE TABLE IF NOT EXISTS pengajuan_sub_kas_kecil(
            id VARCHAR(50) NOT NULL UNIQUE, -- pk, id+proyek+sub_kas_kecil+increment

            id_sub_kas_kecil VARCHAR(10) DEFAULT NULL, -- fk sub kecil
            id_proyek VARCHAR(50) DEFAULT NULL, -- fk proyek
            -- id_kas_kecil VARCHAR(10) DEFAULT NULL, -- fk kas kecil yang menyetujui
            tgl DATE DEFAULT NULL, -- tgl pengajuan
            tgl_laporan DATE DEFAULT NULL, -- tgl laporan
            nama VARCHAR(50) DEFAULT NULL,
            total DOUBLE(12,2) UNSIGNED DEFAULT 0, -- total pengajuan
            dana_disetujui DOUBLE(12,2) UNSIGNED DEFAULT 0, -- dana yg disetujui, default 0 atau sama dengan total
            status CHAR(1) DEFAULT '1', -- status pengajuan, default 1: 'pending'
                            -- 1: 'PENDING', 2: 'PERBAIKI', 3: 'DISETUJUI', 4: 'LANGSUNG', 5: 'DITOLAK'
            -- status_pengajuan_id INT UNSIGNED DEFAULT NULL,
            status_laporan CHAR(1) DEFAULT NULL, -- status laporan, default set null
                            -- 0: 'BELUM DIKERJAKAN', 1: 'PENDING', 2: 'PERBAIKI', 3: 'DISETUJUI', 4: 'DITOLAK'
            -- status_laporan_id INT UNSIGNED DEFAULT NULL,

            created_on DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            modified_on DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            created_by VARCHAR(50) DEFAULT NULL, -- who created first
            modified_by VARCHAR(50) DEFAULT NULL, -- who last edit

            CONSTRAINT pk_pengajuan_sub_kas_kecil_id PRIMARY KEY(id),
            CONSTRAINT fk_pengajuan_sub_kas_kecil_id_sub_kas_kecil FOREIGN KEY(id_sub_kas_kecil) REFERENCES sub_kas_kecil(id)
                ON DELETE RESTRICT ON UPDATE CASCADE,
            CONSTRAINT fk_pengajuan_sub_kas_kecil_id_proyek FOREIGN KEY(id_proyek) REFERENCES proyek (id)
                ON DELETE RESTRICT ON UPDATE CASCADE,
            -- CONSTRAINT fk_pengajuan_sub_kas_kecil_id_kas_kecil FOREIGN KEY(id_kas_kecil) REFERENCES kas_kecil(id)
            --     ON DELETE RESTRICT ON UPDATE CASCADE,
            -- CONSTRAINT fk_pengajuan_sub_kas_kecil_status_pengajuan_id FOREIGN KEY(status_pengajuan_id) REFERENCES status_pengajuan_lookup(id)
            --     ON DELETE RESTRICT ON UPDATE CASCADE,
            -- CONSTRAINT fk_pengajuan_sub_kas_kecil_status_laporan_id FOREIGN KEY(status_laporan_id) REFERENCES status_pengajuan_lookup(id)
            --     ON DELETE RESTRICT ON UPDATE CASCADE,
            CONSTRAINT fk_pengajuan_sub_kas_kecil_created_by FOREIGN KEY(created_by) REFERENCES user(username)
                ON DELETE SET NULL ON UPDATE CASCADE,
            CONSTRAINT fk_pengajuan_sub_kas_kecil_modified_by FOREIGN KEY(modified_by) REFERENCES user(username)
                ON DELETE SET NULL ON UPDATE CASCADE
        )ENGINE=InnoDb;
    -- End Table Pengajuan Sub Kas Kecil

    -- Table Detail Pengajuan Sub Kas Kecil
        DROP TABLE IF EXISTS detail_pengajuan_sub_kas_kecil;
        CREATE TABLE IF NOT EXISTS detail_pengajuan_sub_kas_kecil(
            id INT UNSIGNED NOT NULL AUTO_INCREMENT, -- pk

            id_pengajuan VARCHAR(50) DEFAULT NULL, -- fk pengajuan sub kas kecil
            nama VARCHAR(255) DEFAULT NULL, -- nama barang/bahan
            jenis CHAR(1) DEFAULT NULL, -- jenis pengajuan, T: 'TEKNIS', N: 'NON-TEKNIS'
            -- jenis_id INT UNSIGNED DEFAULT NULL,
            satuan VARCHAR(50) DEFAULT NULL, -- satuan barang/bahan
            qty SMALLINT UNSIGNED DEFAULT 1, -- jumlah barang/bahan
            harga DOUBLE(12,2) UNSIGNED DEFAULT 0, -- harga satuan per barang/bahan
            subtotal DOUBLE(12,2) UNSIGNED DEFAULT 0, -- total per detail pengajuan
            harga_asli DOUBLE(12,2) UNSIGNED DEFAULT 0,
            sisa DOUBLE(12,2) UNSIGNED DEFAULT 0,

            created_on DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            modified_on DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            created_by VARCHAR(50) DEFAULT NULL, -- who created first
            modified_by VARCHAR(50) DEFAULT NULL, -- who last edit

            CONSTRAINT pk_detail_pengajuan_sub_kas_kecil_id PRIMARY KEY(id),
            CONSTRAINT fk_detail_pengajuan_sub_kas_kecil_id_pengajuan FOREIGN KEY(id_pengajuan) REFERENCES pengajuan_sub_kas_kecil(id)
                ON DELETE RESTRICT ON UPDATE CASCADE,
            -- CONSTRAINT fk_operasional_jenis_id FOREIGN KEY(jenis_id) REFERENCES jenis_operasional_lookup(id)
            --     ON DELETE RESTRICT ON UPDATE CASCADE,
            CONSTRAINT fk_detail_pengajuan_sub_kas_kecil_created_by FOREIGN KEY(created_by) REFERENCES user(username)
                ON DELETE SET NULL ON UPDATE CASCADE,
            CONSTRAINT fk_detail_pengajuan_sub_kas_kecil_modified_by FOREIGN KEY(modified_by) REFERENCES user(username)
                ON DELETE SET NULL ON UPDATE CASCADE
        )ENGINE=InnoDb;
    -- End Table Pengajuan Sub Kas Kecil

    -- Table Bukti Laporan Pengajuan SKK
        DROP TABLE IF EXISTS upload_laporan_pengajuan_sub_kas_kecil;
        CREATE TABLE IF NOT EXISTS upload_laporan_pengajuan_sub_kas_kecil(
        -- DROP TABLE IF EXISTS bukti_laporan_pengajuan_skk;
        -- CREATE TABLE IF NOT EXISTS bukti_laporan_pengajuan_skk(
            id INT UNSIGNED NOT NULL AUTO_INCREMENT, -- pk

            id_pengajuan VARCHAR(50) DEFAULT NULL, -- fk pengajuan sub kas kecil
            foto TEXT DEFAULT NULL,

            created_on DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            modified_on DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            created_by VARCHAR(50) DEFAULT NULL, -- who created first
            modified_by VARCHAR(50) DEFAULT NULL, -- who last edit

            CONSTRAINT pk_upload_laporan_pengajuan_sub_kas_kecil PRIMARY KEY(id),
            CONSTRAINT fk_upload_laporan_pengajuan_sub_kas_kecil_id_pengajuan FOREIGN KEY(id_pengajuan) REFERENCES pengajuan_sub_kas_kecil(id)
                ON DELETE RESTRICT ON UPDATE CASCADE,
            CONSTRAINT fk_upload_laporan_pengajuan_sub_kas_kecil_created_by FOREIGN KEY(created_by) REFERENCES user(username)
                ON DELETE SET NULL ON UPDATE CASCADE,
            CONSTRAINT fk_upload_laporan_pengajuan_sub_kas_kecil_modified_by FOREIGN KEY(modified_by) REFERENCES user(username)
                ON DELETE SET NULL ON UPDATE CASCADE
        )ENGINE=InnoDb;
    -- End Table Bukti Laporan Pengajuan SKK

    -- Table Pengajuan Kas Kecil
        DROP TABLE IF EXISTS pengajuan_kas_kecil;
        CREATE TABLE IF NOT EXISTS pengajuan_kas_kecil(
            id VARCHAR(50) NOT NULL UNIQUE, -- pk

            id_kas_kecil VARCHAR(10) DEFAULT NULL, -- fk kas kecil
            -- id_kas_besar VARCHAR(10) DEFAULT NULL, -- fk kas besar
            id_bank INT UNSIGNED DEFAULT NULL, -- fk bank
            tgl DATE DEFAULT NULL,
            nama VARCHAR(50) DEFAULT NULL, -- nama pengajuan
            total DOUBLE(12,2) UNSIGNED DEFAULT 0, -- total pengajuan ke kas besar
            status CHAR(1) DEFAULT '0', -- status pengajuan, default 'pending'
                        -- 0: 'PENDING', 1: 'PERBAIKI', 2: 'DISETUJUI', 3: 'DITOLAK'
            total_disetujui DOUBLE(12,2) UNSIGNED DEFAULT 0,

            created_on DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            modified_on DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            created_by VARCHAR(50) DEFAULT NULL, -- who created first
            modified_by VARCHAR(50) DEFAULT NULL, -- who last edit

            CONSTRAINT pk_pengajuan_kas_kecil_id PRIMARY KEY(id),
            CONSTRAINT fk_pengajuan_kas_kecil_id_kas_kecil FOREIGN KEY(id_kas_kecil) REFERENCES kas_kecil(id)
                ON DELETE RESTRICT ON UPDATE CASCADE,
            CONSTRAINT fk_pengajuan_kas_kecil_id_bank FOREIGN KEY(id_bank) REFERENCES bank(id)
                ON DELETE RESTRICT ON UPDATE CASCADE,
            CONSTRAINT fk_pengajuan_kas_kecil_modified_by FOREIGN KEY(modified_by) REFERENCES user(username)
                ON DELETE SET NULL ON UPDATE CASCADE
        )ENGINE=InnoDb;
    -- End Table Pengajuan Kas Kecil



# View Access Right #

-- View access menu
CREATE OR REPLACE VIEW v_access_menu AS
SELECT
    am.id,
    am.level_id, ll.name level_name,
    am.menu_id, m.name menu_name, m.url, m.class, m.icon, m.position
FROM access_menu am
JOIN level_lookup ll ON ll.id = am.level_id
JOIN menu m ON m.id = am.menu_id;
-- End view access menu

# End View Access Right #

# Procedure, Function, and Trigger Bank #

-- Procedure Tambah Bank
    DROP PROCEDURE IF EXISTS p_tambah_bank;
    delimiter //

    CREATE PROCEDURE p_tambah_bank(
        in nama_param varchar(255),
        in saldo_param double(12,2),
        in status_param enum('AKTIF', 'NONAKTIF'),
        in created_by_param varchar(50)
    )
    BEGIN

        INSERT INTO bank 
            (nama, saldo, status, created_by, modified_by) 
        VALUES (nama_param, saldo_param, status_param, created_by_param, created_by_param);

    END //

    delimiter ;
-- End Procedure Tambah Bank

-- Trigger Tambah Bank (After Insert)
    DROP TRIGGER IF EXISTS t_after_insert_tambah_bank;
    delimiter //
    
    CREATE TRIGGER t_after_insert_tambah_bank AFTER INSERT ON bank FOR EACH ROW
    BEGIN

        INSERT INTO mutasi_bank 
            (id_bank, tgl, uang_masuk, uang_keluar, saldo, ket, created_by, modified_by)
        VALUES 
            (NEW.id, CURRENT_DATE(), NEW.saldo, 0, NEW.saldo, 'SALDO AWAL', NEW.created_by, NEW.modified_by);

    END //
		
    delimiter ;
-- End Trigger Tambah Bank (After Insert)

-- Procedure Edit Bank
    DROP PROCEDURE IF EXISTS p_edit_bank;
    delimiter //

    CREATE PROCEDURE p_edit_bank(
        in id_param int,
        in nama_param varchar(255),
        in status_param enum('AKTIF', 'NONAKTIF'),
        in modified_by_param varchar(50)
    )
    BEGIN

        UPDATE bank SET
            nama = nama_param,
            status = status_param,
            modified_by = modified_by_param
        WHERE id = id_param;

    END //

    delimiter ;
-- End Procedure Edit Bank

-- Procedure Delete Bank
    DROP PROCEDURE IF EXISTS p_hapus_bank;
    delimiter //

	CREATE PROCEDURE p_hapus_bank(
		in id_param int
	)
	BEGIN
        -- hapus data operasional
        DELETE FROM operasional WHERE id_bank = id_param;

        -- hapus detail operasional proyek
        DELETE FROM detail_operasional_proyek WHERE id_bank = id_param;

		-- hapus detail proyek
		DELETE FROM detail_proyek WHERE id_bank = id_param;

        -- hapus pengajuan kas kecil
        DELETE FROM pengajuan_kas_kecil WHERE id_bank = id_param;

        -- hapus mutasi bank
        DELETE FROM mutasi_bank WHERE id_bank = id_param;

        -- hapus bank
        DELETE FROM bank WHERE id = id_param;
        
	END //

	delimiter ;
-- End Procedure Delete Bank

# End Procedure, Function, and Trigger Bank #

# View Access Bank #

-- View mutasi bank export
    CREATE OR REPLACE VIEW v_mutasi_bank_export AS
    SELECT 
        m.id_bank id_bank,
        b.nama 'BANK',
        m.tgl TANGGAL,
        m.uang_masuk 'UANG MASUK',
        m.uang_keluar 'UANG KELUAR',
        m.saldo SALDO,
        m.ket KETERANGAN
    FROM mutasi_bank m 
    JOIN bank b ON b.id = m.id_bank;
-- End mutasi bank export

# End View Bank #

# View Dashboard #

-- View proyek dashboard
    CREATE OR REPLACE VIEW v_proyek_dashboard AS
    SELECT detail_proyek.id_proyek, SUM(detail_proyek.total)  AS total, proyek.status AS status 
        FROM detail_proyek
        JOIN proyek ON proyek.id = detail_proyek.id_proyek
    GROUP BY detail_proyek.id_proyek;
-- End View proyek dashboard

# End View Dashboard #

# View List Proyek #

-- View proyek dashboard
    CREATE OR REPLACE VIEW v_proyek_list AS
        SELECT id, pemilik, tgl, pembangunan, kota, SUM(total+cco) AS 'total', progress, status
            FROM proyek
-- End View proyek list

# End View Proyek List #



# Procedure, Function, and Trigger Distributor #

-- Procedure Tambah Distributor
    DROP PROCEDURE IF EXISTS p_tambah_distributor;
    delimiter //

    CREATE PROCEDURE p_tambah_distributor(
        in id_param varchar(50),
        in nama_param varchar(255),
        in alamat_param text,
        in no_telp_param varchar(50),
        in pemilik_param varchar(255),
        in status_param enum('AKTIF','NONAKTIF'),
        in created_by_param varchar(50)
    )
    BEGIN

        INSERT INTO distributor 
            (id, nama, alamat, no_telp, pemilik, status, created_by, modified_by)
        VALUES 
            (id_param, nama_param, alamat_param, no_telp_param, pemilik_param, 
            status_param, created_by_param, created_by_param);

    END //

    delimiter ;
-- End Procedure Tambah Distributor

-- Procedure Edit Distributor
    DROP PROCEDURE IF EXISTS p_edit_distributor;
    delimiter //

    CREATE PROCEDURE p_edit_distributor(
        in id_param varchar(50),
        in nama_param varchar(255),
        in alamat_param text,
        in no_telp_param varchar(50),
        in pemilik_param varchar(255),
        in status_param enum('AKTIF','NONAKTIF'),
        in modified_by_param varchar(50)
    )
    BEGIN

        UPDATE distributor SET
            nama = nama_param,
            alamat = alamat_param,
            no_telp = no_telp_param,
            pemilik = pemilik_param,
            status = status_param,
            modified_by = modified_by_param
        WHERE id = id_param;

    END //

    delimiter ;
-- End Procedure Edit Distributor

-- Procedure Delete Distributor
    DROP PROCEDURE IF EXISTS p_hapus_distributor;
    delimiter //

    CREATE PROCEDURE p_hapus_distributor(
        in id_param varchar(50)
    )
    BEGIN

        DELETE FROM distributor WHERE id = id_param;

    END //

    delimiter ;
-- End Procedure Delete Distributor

# End Procedure, Function, and Trigger Distributor #

# View Distributor #

-- View history distributor
    CREATE OR REPLACE VIEW v_history_distributor AS
    SELECT 
        d.id, d.nama nama_distributor, d.pemilik pemilik, 
        opr.tgl, opr.id id_operasional_proyek, opr.nama nama_operasional, opr.jenis,
        opr.total, opr.status jenis_pembayaran, opr.status_lunas status
    FROM distributor d 
    JOIN operasional_proyek opr ON d.id = opr.id_distributor
    WHERE d.id = opr.id_distributor;
-- End View history distributor

-- View Export Distributor
    CREATE OR REPLACE VIEW v_export_distributor AS
    SELECT
        id ID, nama NAMA, alamat ALAMAT, no_telp 'NO. TELEPON',
        pemilik PEMILIK, status STATUS
    FROM distributor;
-- End View Distributor

# End View Distributor #

# Procedure, Function, and Trigger Kas Besar #

-- Procedure Tambah Data Kas Besar
	DROP PROCEDURE IF EXISTS p_tambah_kas_besar;
    delimiter //
	
	CREATE PROCEDURE p_tambah_kas_besar(
		in id_param varchar(10),
		in nama_param varchar(50),
		in alamat_param text,
		in no_telp_param varchar(20),
		in email_param varchar(50),
		in foto_param text,
		in status_param varchar(20),
		in password_param text,
		in level_param varchar(20),
		in created_by_param varchar(50)
	)
	BEGIN

		-- 1. insert ke user
		INSERT INTO user
			(username, password, level, status, created_by, modified_by)
		VALUES
			(email_param, password_param, level_param, 'AKTIF', created_by_param, created_by_param);

		-- insert ke kas besar
		INSERT INTO kas_besar
			(id, nama, alamat, no_telp, email, foto, status, created_by, modified_by)
		VALUES
			(id_param, nama_param, alamat_param, no_telp_param,
				email_param, foto_param, status_param, created_by_param, created_by_param);

	END //
	
	delimiter ;
-- End Procedure Tambah Data Kas Besar

-- Procedure Edit Kas Besar
	DROP PROCEDURE IF EXISTS p_edit_kas_besar;
	delimiter //

	CREATE PROCEDURE p_edit_kas_besar (
		in id_param varchar(10),
		in nama_param varchar(50),
		in alamat_param text,
		in no_telp_param varchar(20),
		in status_param enum('AKTIF', 'NONAKTIF'),
		in modified_by_param varchar(50)
	)
	BEGIN

		UPDATE kas_besar SET
			nama = nama_param,
			alamat = alamat_param,
			no_telp = no_telp_param,
			status = status_param,
			modified_by = modified_by_param
		WHERE id = id_param;

	END //

	delimiter ;
-- End Procedure Kas Besar

-- Procedure Edit Status Kas Besar
    DROP PROCEDURE IF EXISTS p_edit_status_kas_besar;
	delimiter //
	
	CREATE PROCEDURE p_edit_status_kas_besar(
		in id_param varchar(10),
		in status_param varchar(20),
		in modified_by_param varchar(50)
	)
	BEGIN
		DECLARE status_lama_param varchar(20);
		DECLARE email_param varchar(50);

		-- get status kas besar yg lama
		SELECT status INTO status_lama_param FROM kas_besar WHERE id = id_param;
		-- get email kas besar untuk update user
		SELECT email INTO email_param FROM kas_besar WHERE id = id_param;

		-- jika ada perubahan status
		IF status_param != status_lama_param THEN
			-- update kas besar
			-- set status
			UPDATE kas_besar SET 
				status = status_param,
				modified_by = modified_by_param
			WHERE id = id_param;

			-- jika status berubah menjadi nonaktif
			IF status_param = 'NONAKTIF' THEN
				UPDATE user SET 
					status = 'NONAKTIF',
					modified_by = modified_by_param 
				WHERE username = email_param;
			ELSE -- jika status berubah menjadi aktif
				UPDATE user 
					SET status = 'AKTIF',
					modified_by = modified_by_param
				WHERE username = email_param;
			END IF;

		END IF;

	END//

	delimiter ;
-- End Procedure Edit Status Kas Besar

-- Procedure Hapus Data Kas Besar
	DROP PROCEDURE IF EXISTS p_hapus_kas_besar;
	delimiter //
	
	CREATE PROCEDURE p_hapus_kas_besar(
		in id_param varchar(10)
	)
	BEGIN

		DECLARE email_param varchar(50);

		-- get email kas besar
		SELECT email INTO email_param FROM kas_besar WHERE id = id_param;

		-- 1. hapus operasional
		DELETE FROM operasional WHERE id_kas_besar = id_param;

		-- 2. hapus detail operasional proyek
		DELETE FROM detail_operasional_proyek WHERE id_operasional_proyek IN (
			SELECT id FROM operasional_proyek WHERE id_kas_besar = id_param
		);

		-- 3. hapus operasional proyek
		DELETE FROM operasional_proyek WHERE id_kas_besar = id_param;

		-- 4. hapus kas besar
		DELETE FROM kas_besar WHERE id = id_param;

		-- 5. hapus user
		DELETE FROM user WHERE username = email_param;
	END//

	delimiter ;
-- End Procedure Hapus Data Kas Besar

# End Procedure, Function, and Trigger Kas Besar #

# View Kas Besar #

-- View Export Kas Besar
    CREATE OR REPLACE VIEW v_export_kas_besar AS
    SELECT
        id ID, nama NAMA, alamat ALAMAT, no_telp 'NO. TELEPON',
        email EMAIL, status STATUS
    FROM kas_besar;
-- End View Export Kas Besar

# End View Kas Besar #

# Procedure, Function, and Trigger Kas Kecil #

-- Procedure Tambah Data Kas Kecil
	DROP PROCEDURE IF EXISTS p_tambah_kas_kecil;
	delimiter //

	CREATE PROCEDURE p_tambah_kas_kecil(
		in id_param varchar(10),
		in nama_param varchar(50),
		in alamat_param text,
		in no_telp_param varchar(20),
		in email_param varchar(50),
		in foto_param text,
		in saldo_param double(12,2),
		in tgl_param date,
		in status_param varchar(20),
		in password_param text,
		in level_param varchar(20),
		in created_by_param varchar(50)
	)
	BEGIN

		-- 1. insert ke user
		INSERT INTO user
			(username, password, level, status, created_by, modified_by)
		VALUES
			(email_param,password_param,level_param, 'AKTIF', created_by_param, created_by_param);

		-- insert ke kas kecil
		INSERT INTO kas_kecil
			(id, nama, alamat, no_telp, email, foto, saldo, status, created_by, modified_by)
		VALUES
			(id_param, nama_param, alamat_param, no_telp_param,
			email_param, foto_param, saldo_param, status_param, created_by_param, created_by_param);

		-- insert mutasi awal
		INSERT INTO mutasi_saldo_kas_kecil 
			(id_kas_kecil, tgl, uang_masuk, uang_keluar, saldo, ket, created_by, modified_by)
		VALUES
			(id_param, tgl_param, saldo_param, 0, saldo_param, 'SALDO AWAL', created_by_param, created_by_param);
	
	END //

	delimiter ;
-- End Procedure Tambah Data Kas Kecil

-- Procedure Edit Data Kas Kecil
	DROP PROCEDURE IF EXISTS p_edit_kas_kecil;
	delimiter //

	CREATE PROCEDURE p_edit_kas_kecil(
		in id_param varchar(10),
		in nama_param varchar(50),
		in alamat_param text,
		in no_telp_param varchar(20),
		in status_param enum('AKTIF', 'NONAKTIF'),
		in modified_by_param varchar(50)
	)
	BEGIN

		UPDATE kas_kecil SET
			nama = nama_param,
			alamat = alamat_param,
			no_telp = no_telp_param,
			status = status_param,
			modified_by = modified_by_param
		WHERE id = id_param;

	END //

	delimiter ;
-- End Procedure Edit Data Kas Kecil

-- Procedure Hapus Data Kas Kecil
	DROP PROCEDURE IF EXISTS p_hapus_kas_kecil;
	delimiter //

	CREATE PROCEDURE p_hapus_kas_kecil(
		in id_param varchar(10)
	)
	BEGIN
	
		DECLARE email_param varchar(50);

		-- get email kas kecil
		SELECT email INTO email_param FROM kas_kecil WHERE id = id_param;

		-- hapus pengajuan kas kecil
		DELETE FROM pengajuan_kas_kecil WHERE id_kas_kecil = id_param;

		-- hapus kas kecil
		DELETE FROM kas_kecil WHERE id = id_param;

		-- hapus user
		DELETE FROM user WHERE username = email_param;
	
	END//

	delimiter ;
-- End Procedure Hapus Data Kas Kecil

-- Procedure Edit Status Kas Kecil
	DROP PROCEDURE IF EXISTS p_edit_status_kas_kecil;
	delimiter //

	CREATE PROCEDURE p_edit_status_kas_kecil(
		in id_param varchar(10),
		in status_param varchar(20),
		in modified_by_param varchar(50)
	)
	BEGIN
		DECLARE status_lama_param varchar(20);
		DECLARE email_param varchar(50);

		-- get status kas besar yg lama
		SELECT status INTO status_lama_param FROM kas_kecil WHERE id = id_param;
		-- get email kas besar untuk update user
		SELECT email INTO email_param FROM kas_kecil WHERE id = id_param;

		-- jika ada perubahan status
		IF status_param != status_lama_param THEN
			-- update kas besar
			-- set status
			UPDATE kas_kecil SET 
				status = status_param,
				modified_by = modified_by_param 
			WHERE id = id_param;

			-- jika status berubah menjadi nonaktif
			IF status_param = 'NONAKTIF' THEN
				UPDATE user SET 
					status = 'NONAKTIF',
					modified_by = modified_by_param
				WHERE username = email_param;
			ELSE -- jika status berubah menjadi aktif
				UPDATE user SET 
					status = 'AKTIF',
					modified_by = modified_by_param
				WHERE username = email_param;
			END IF;

		END IF;

	END //

	delimiter ;
-- End Procedure Edit Status Kas Kecil

# End Procedure, Function, and Trigger Kas Kecil #



# View Kas Kecil #

-- View Export Kas Kecil
    CREATE OR REPLACE VIEW v_export_kas_kecil AS
    SELECT
        id ID, nama NAMA, alamat ALAMAT, no_telp 'NO. TELEPON',
        email EMAIL, status STATUS
    FROM kas_kecil;
-- End View Export Kas Kecil

-- View saldo kas kecil
    CREATE OR REPLACE VIEW v_saldo_kas_kecil_export AS
    SELECT 
        mskk.id, mskk.id_kas_kecil ID_KAS_KECIL, mskk.tgl TANGGAL, 
        mskk.uang_masuk 'UANG MASUK', mskk.uang_keluar 'UANG KELUAR',
        mskk.ket KETERANGAN
    FROM mutasi_saldo_kas_kecil mskk;

-- End View saldo kas kecil

# End View Kas Kecil #

# Procedure, Function, and Trigger Laporan Pengajuan Sub Kas Kecil #

-- Procedure Pengajuan Laporan Sub Kas Kecil
    DROP PROCEDURE IF EXISTS p_pengajuan_laporan_sub_kas_kecil;
    delimiter //
    
    CREATE PROCEDURE p_pengajuan_laporan_sub_kas_kecil(
        in id_pengajuan_param varchar(50),
        in id_sub_kas_kecil_param varchar(10),
        in tgl_param date,
        in sum_pengajuan_laporan_param double(12,2), -- sum harga asli di laporan
        in ket_param text,
        in modified_by_param varchar(50)
    )
    BEGIN
        DECLARE get_saldo double(12,2);

        -- get saldo sub kas kecil
        SELECT 
            saldo 
        INTO get_saldo 
        FROM sub_kas_kecil WHERE id = id_sub_kas_kecil_param;

        -- tambah tabel mutasi saldo sub kas kecil
        INSERT INTO mutasi_saldo_sub_kas_kecil
            (id_sub_kas_kecil, tgl, uang_masuk, uang_keluar, saldo, ket, created_by, modified_by)
        VALUES (
            id_sub_kas_kecil_param, 
            tgl_param, 
            0, 
            sum_pengajuan_laporan_param, 
            (get_saldo-sum_pengajuan_laporan_param), 
            ket_param,
            modified_by_param, modified_by_param
        );

        -- update saldo sub kas kecil
        UPDATE sub_kas_kecil 
        SET 
            saldo = (get_saldo-sum_pengajuan_laporan_param),
            modified_by = modified_by_param 
        WHERE id = id_sub_kas_kecil_param;

        -- update status_laporan (PENDING) dan tgl_laporan pengajuan sub kas kecil
        UPDATE pengajuan_sub_kas_kecil 
        SET 
            status_laporan = "1", 
            tgl_laporan = tgl_param,
            modified_by = modified_by_param
        WHERE id = id_pengajuan_param;

    END //

    delimiter ;
-- End Procedure Pengajuan Laporan Sub Kas Kecil

-- Procedure ganti status laporan dari pending ke perbaiki
-- BELUM FIX
    DROP PROCEDURE IF EXISTS p_ganti_status_perbaiki_laporan_sub_kas_kecil;
    delimiter //
    
    CREATE PROCEDURE p_ganti_status_perbaiki_laporan_sub_kas_kecil(
        IN id_pengajuan_param varchar(50),
        IN id_sub_kas_kecil_param varchar(10),
        IN tgl_mutasi_param date,
        IN modified_by_param varchar(50)
    )
    BEGIN

        DECLARE get_total_harga_asli double(12,2);
        DECLARE get_saldo_terbaru double(12,2);
        
        -- mendapatkan total harga asli laporan
        SELECT
            SUM(harga_asli)
        INTO get_total_harga_asli
        FROM detail_pengajuan_sub_kas_kecil WHERE id_pengajuan=id_pengajuan_param;

        -- mendapatkan saldo terbaru
        SELECT
            saldo
        INTO get_saldo_terbaru
        FROM v_sub_kas_kecil WHERE id=id_sub_kas_kecil_param;

        -- tambah mutasi sub kas kecil
        INSERT INTO mutasi_saldo_sub_kas_kecil (
            id_sub_kas_kecil, tgl, uang_masuk, uang_keluar, saldo, ket, created_by, modified_by
        ) VALUES (
            id_sub_kas_kecil_param,
            tgl_mutasi_param,
            get_total_harga_asli,
            0,
            (get_saldo_terbaru+get_total_harga_asli),
            CONCAT('PERUBAHAN STATUS LAPORAN KE PERBAIKI ', id_pengajuan_param),
            modified_by_param, modified_by_param
        );

        -- update saldo sub kas kecil
        UPDATE sub_kas_kecil
        SET
            saldo = (get_saldo_terbaru+get_total_harga_asli),
            modified_by = modified_by_param
        WHERE id = id_sub_kas_kecil_param;

        -- update status_laporan (PERBAIKI)
        UPDATE pengajuan_sub_kas_kecil
        SET 
            status_laporan = '2',
            modified_by = modified_by_param
        WHERE id = id_pengajuan_param;

    END//
    
    delimiter ;
-- End Procedure ganti status laporan dari pending ke perbaiki

-- Procedure edit_laporan_sub_kas_kecil v2
-- BELUM FIX
    DROP PROCEDURE IF EXISTS p_edit_laporan_sub_kas_kecil;
    delimiter //

    CREATE PROCEDURE p_edit_laporan_sub_kas_kecil (
        IN id_pengajuan_param varchar(50),
        IN id_sub_kas_kecil_param varchar(10),
        IN tgl_mutasi_param date,
        IN modified_by_param varchar(50)
    )
    BEGIN

        DECLARE get_total_harga_asli double(12,2);
        DECLARE get_saldo_terbaru double(12,2);

        -- mendapatkan total harga asli
        SELECT
            SUM(harga_asli)
        INTO get_total_harga_asli
        FROM detail_pengajuan_sub_kas_kecil WHERE id_pengajuan=id_pengajuan_param;

        -- mendapatkan saldo terbaru
        SELECT
            saldo
        INTO get_saldo_terbaru
        FROM v_sub_kas_kecil WHERE id=id_sub_kas_kecil_param;

        -- tambah mutasi
        INSERT INTO mutasi_saldo_sub_kas_kecil (
            id_sub_kas_kecil, tgl, uang_masuk, uang_keluar, saldo, ket, created_by, modified_by
        ) VALUES (
            id_sub_kas_kecil_param,
            tgl_mutasi_param,
            0,
            get_total_harga_asli,
            (get_saldo_terbaru-get_total_harga_asli),
            CONCAT('PENGAJUAN PERBAIKAN LAPORAN ', id_pengajuan_param),
            modified_by_param, modified_by_param
        );

        -- update saldo sub kas kecil
        UPDATE sub_kas_kecil
        SET
            saldo = (get_saldo_terbaru-get_total_harga_asli)
        WHERE id = id_sub_kas_kecil_param;

        -- update status_laporan (PENDING) dan tgl_laporan pengajuan sub kas kecil
        UPDATE pengajuan_sub_kas_kecil
        SET 
            status_laporan = '1',
            tgl_laporan = tgl_mutasi_param,
            modified_by = modified_by_param
        WHERE id = id_pengajuan_param;

    END //

    delimiter ;
-- End Procedure edit_laporan_sub_kas_kecil v2

# End Procedure, Function, and Trigger Laporan Pengajuan Sub Kas Kecil #

# View Laporan Pengajuan Sub Kas Kecil #

-- View Laporan Pengajuan Sub Kas Kecil
	CREATE OR REPLACE VIEW v_laporan_pengajuan_sub_kas_kecil AS
	SELECT 
		pskk.id, pskk.id_sub_kas_kecil, skk.nama nama_skk, pskk.id_proyek, p.pemilik, p.pembangunan, p.kota,
		pskk.tgl_laporan tgl, pskk.nama nama_pengajuan, pskk.total, SUM(dpskk.harga_asli) total_asli, 
		(CASE 
            WHEN pskk.status_laporan = '1' THEN 'PENDING'
            WHEN pskk.status_laporan = '2' THEN 'PERBAIKI'
            WHEN pskk.status_laporan = '3' THEN 'DISETUJUI'
			WHEN pskk.status_laporan = '4' THEN 'DITOLAK'
            ELSE 'BELUM DIKERJAKAN' END
        ) status_laporan, pskk.status_laporan status_order
	-- ) status_laporan, pskk.status_laporan status_order
	FROM pengajuan_sub_kas_kecil pskk
	LEFT JOIN detail_pengajuan_sub_kas_kecil dpskk ON dpskk.id_pengajuan = pskk.id
	JOIN sub_kas_kecil skk ON skk.id = pskk.id_sub_kas_kecil
	JOIN proyek p ON p.id = pskk.id_proyek
	GROUP BY pskk.id;
-- End View Laporan Pengajuan Sub Kas Kecil

# End View Laporan Pengajuan Sub Kas Kecil #

# Procedure, Function, and Trigger Mutasi Saldo Kas Kecil #

# End Procedure, Function, and Trigger Mutasi Saldo Kas Kecil #

# View Mutasi Saldo Kas Kecil #

-- View export saldo kas kecil
    CREATE OR REPLACE VIEW v_saldo_kas_kecil_export AS
    SELECT mskk.id, mskk.id_kas_kecil 'ID KAS KECIL', mskk.tgl TANGGAL, 
        mskk.uang_masuk 'UANG MASUK', mskk.uang_keluar 'UANG KELUAR',
        mskk.ket KETERANGAN
    FROM mutasi_saldo_kas_kecil mskk;
-- End View export saldo kas kecil

# End View Mutasi Saldo Kas Kecil #

# Procedure, Function, and Trigger Mutasi Saldo Sub Kas Kecil #

# End Procedure, Function, and Trigger Mutasi Saldo Sub Kas Kecil #

# View Mutasi Saldo Sub Kas Kecil #

-- View export mutasi saldo sub kas kecil
    CREATE OR REPLACE VIEW v_mutasi_saldo_sub_kas_kecil_export AS
    SELECT 
        id ID,
        id_sub_kas_kecil `ID SUB KAS KECIL`,
        tgl TANGGAL, 
        uang_masuk `UANG MASUK`, 
        uang_keluar `UANG KELUAR`, 
        saldo SALDO, 
        ket KETERANGAN
    FROM mutasi_saldo_sub_kas_kecil
    ORDER BY id DESC;
-- End View export mutasi saldo sub kas kecil

# End View Mutasi Saldo Sub Kas Kecil #

# Procedure, Function, and Trigger Operasional #

-- Procedure Tambah Operasional
	DROP PROCEDURE IF EXISTS p_tambah_operasional;
	delimiter //
	
	CREATE PROCEDURE p_tambah_operasional(
		in id_bank_param int, -- id bank
		in id_kas_besar_param varchar(10), -- id kas besar
		in tgl_param date,  -- tanggal operasional
		in nama_param varchar(255), -- nama operasional
		in nominal_param double(12,2), -- nominal operasional,
		in jenis_param enum('UANG MASUK', 'UANG KELUAR'),
		in ket_param text, -- ket operasional
		in ket_mutasi_param text,
		in created_by_param varchar(50)
	)
	BEGIN
        
		DECLARE get_saldo double(12,2);

		-- 1. insert ke tabel operasional
		INSERT into operasional
			(id_bank, id_kas_besar, tgl, nama, nominal, jenis, ket, created_by, modified_by)
		VALUES
			(id_bank_param, id_kas_besar_param, tgl_param, nama_param, nominal_param, 
			jenis_param, ket_param, created_by_param, created_by_param);

		-- 2. ambil saldo terahir
		SELECT saldo INTO get_saldo FROM bank WHERE id= id_bank_param;

		IF jenis_param = 'UANG MASUK' THEN

			-- 3. insert mutasi operasional
			INSERT into mutasi_bank
				(id_bank, tgl, uang_masuk, uang_keluar, saldo, ket, created_by, modified_by)
			VALUES
				(id_bank_param, tgl_param, nominal_param, 0, (get_saldo + nominal_param), 
				ket_mutasi_param, created_by_param, created_by_param);

			-- 4. update saldo bank
			UPDATE bank SET 
				saldo = (get_saldo + nominal_param),
				modified_by = created_by_param 
			WHERE id = id_bank_param;
		
		ELSE

			-- 3. insert mutasi operasional
			INSERT into mutasi_bank
				(id_bank, tgl, uang_masuk, uang_keluar, saldo, ket, created_by, modified_by)
			VALUES
				(id_bank_param, tgl_param, 0, nominal_param, (get_saldo - nominal_param), 
				ket_mutasi_param, created_by_param, created_by_param);

			-- 4. update saldo bank
			UPDATE bank SET 
				saldo = (get_saldo - nominal_param),
				modified_by = created_by_param 
			WHERE  id = id_bank_param;
		END IF;

	END //

	delimiter ;
-- End Procedure Tambah Operasional

-- Procedure Edit Operasional Masuk
	DROP PROCEDURE IF EXISTS p_edit_operasional_masuk;
	delimiter //

	CREATE PROCEDURE p_edit_operasional_masuk(
		in id_param int,
		in id_bank_param int, -- id bank
		in tgl_param date,  -- tanggal operasional
		in nama_param varchar(255), -- nama operasional
		in nominal_param double(12,2), -- nominal operasional,
		in jenis_param enum('UANG MASUK', 'UANG KELUAR'),
		in ket_param text, -- ket operasional
		in ket_mutasi_param text,
		in ket_bank_masuk_param text,
		in ket_bank_keluar_param text,
		in ket_saldo_change_param text,
		in modified_by_param varchar(50)
	)
	BEGIN
		DECLARE id_bank_sebelum int;
		DECLARE nominal_sebelum double(12,2);
		DECLARE get_saldo_bank double(12,2);
		DECLARE get_saldo_bank_lama double(12,2);
		DECLARE jenis_sebelum varchar(25);
		
		-- get jenis sebelum
		SELECT jenis INTO jenis_sebelum FROM operasional WHERE id = id_param;
		-- get bank sebelum
		SELECT id_bank INTO id_bank_sebelum FROM operasional WHERE id = id_param;
		-- get nominal sebelum
		SELECT nominal INTO nominal_sebelum FROM operasional WHERE id = id_param;

		-- jika terdeteksi perubahan jenis
		IF jenis_sebelum = 'UANG KELUAR' THEN

			-- get saldo bank lama
			SELECT saldo INTO get_saldo_bank_lama FROM bank WHERE id = id_bank_sebelum;
			
			-- normalisasi saldo bank sebelum
			UPDATE bank SET 
				saldo = (get_saldo_bank_lama + nominal_sebelum),
				modified_by = modified_by_param
			WHERE id = id_bank_sebelum;

			-- insert mutasi bank lama
			INSERT INTO mutasi_bank 
				(id_bank, tgl, uang_masuk, uang_keluar, saldo, ket, created_by, modified_by)
			VALUES 
				(id_bank_sebelum, tgl_param, nominal_sebelum, 0, (get_saldo_bank_lama + nominal_sebelum), 
				ket_mutasi_param, modified_by_param, modified_by_param);
		
		END IF;

		-- jika ada perubahan bank
		IF id_bank_sebelum != id_bank_param THEN

			-- get saldo bank lama
			SELECT saldo INTO get_saldo_bank_lama FROM bank WHERE id = id_bank_sebelum;
			
			-- normalisasi saldo bank sebelum
			UPDATE bank SET 
				saldo = (get_saldo_bank_lama - nominal_sebelum),
				modified_by = modified_by_param
			WHERE id = id_bank_sebelum;

			-- insert mutasi bank lama
			INSERT INTO mutasi_bank 
				(id_bank, tgl, uang_masuk, uang_keluar, saldo, ket, created_by, modified_by)
			VALUES 
				(id_bank_sebelum, tgl_param, 0, nominal_sebelum, (get_saldo_bank_lama - nominal_sebelum), 
				ket_bank_keluar_param, modified_by_param, modified_by_param);

			-- get saldo bank baru
			SELECT saldo INTO get_saldo_bank FROM bank WHERE id = id_bank_param;

			-- update saldo bank baru
			UPDATE bank SET 
				saldo = (get_saldo_bank + nominal_param),
				modified_by = modified_by_param 
			WHERE id = id_bank_param;

			-- insert mutasi bank baru
			INSERT INTO mutasi_bank 
				(id_bank, tgl, uang_masuk, uang_keluar, saldo, ket, created_by, modified_by)
			VALUES 
				(id_bank_param, tgl_param, nominal_param, 0, (get_saldo_bank + nominal_param), 
				ket_bank_masuk_param, modified_by_param, modified_by_param);
		
		-- jika ada perubahan nominal
		ELSE IF nominal_sebelum != nominal_param THEN

			IF nominal_param > nominal_sebelum THEN

				-- get saldo bank
				SELECT saldo INTO get_saldo_bank FROM bank WHERE id = id_bank_param;

				-- normalisasi saldo
				UPDATE bank SET 
					saldo = (get_saldo_bank + (nominal_param - nominal_sebelum)),
					modified_by = modified_by_param 
				WHERE id = id_bank_param;

				-- insert mutasi
				INSERT INTO mutasi_bank 
					(id_bank, tgl, uang_masuk, uang_keluar, saldo, ket, created_by, modified_by)
				VALUES 
					(id_bank_param, tgl_param, (nominal_param - nominal_sebelum), 0, (get_saldo_bank + (nominal_param - nominal_sebelum)), 
					ket_saldo_change_param, modified_by_param, modified_by_param);
			
			ELSE IF nominal_param < nominal_sebelum THEN

				-- get saldo bank
				SELECT saldo INTO get_saldo_bank FROM bank WHERE id = id_bank_param;
			
				-- normalisasi saldo
				UPDATE bank SET 
					saldo = (get_saldo_bank - (nominal_sebelum - nominal_param)),
					modified_by = modified_by_param 
				WHERE id = id_bank_param;

				-- insert mutasi
				INSERT INTO mutasi_bank 
					(id_bank, tgl, uang_masuk, uang_keluar, saldo, ket, created_by, modified_by)
				VALUES 
					(id_bank_param, tgl_param, 0, (nominal_sebelum - nominal_param), (get_saldo_bank - (nominal_sebelum - nominal_param)), 
					ket_saldo_change_param, modified_by_param, modified_by_param);

					END IF;

				END IF;

			END IF;

		END IF;

		-- update operasional
		UPDATE operasional SET 
			id_bank = id_bank_param, tgl = tgl_param, nama = nama_param, nominal = nominal_param, 
			jenis = jenis_param, ket = ket_param, modified_by = modified_by_param
		WHERE id = id_param;

	END //

	delimiter ;
-- End Procedure Edit Operasional Masuk

-- Procedure Edit Operasional Keluar
	DROP PROCEDURE IF EXISTS p_edit_operasional_keluar;
	delimiter //
	CREATE PROCEDURE p_edit_operasional_keluar(
		in id_param int,
		in id_bank_param int, -- id bank
		in tgl_param date,  -- tanggal operasional
		in nama_param varchar(255), -- nama operasional
		in nominal_param double(12,2), -- nominal operasional,
		in jenis_param enum('UANG MASUK', 'UANG KELUAR'),
		in ket_param text, -- ket operasional
		in ket_mutasi_param text,
		in ket_bank_masuk_param text,
		in ket_bank_keluar_param text,
		in ket_saldo_change_param text,
		in modified_by_param varchar(50)
	)
	BEGIN
		DECLARE id_bank_sebelum int;
		DECLARE nominal_sebelum double(12,2);
		DECLARE get_saldo_bank double(12,2);
		DECLARE get_saldo_bank_lama double(12,2);
		DECLARE jenis_sebelum varchar(25);
		
		-- get jenis sebelum
		SELECT jenis INTO jenis_sebelum FROM operasional WHERE id = id_param;
		-- get bank sebelum
		SELECT id_bank INTO id_bank_sebelum FROM operasional WHERE id = id_param;
		-- get nominal sebelum
		SELECT nominal INTO nominal_sebelum FROM operasional WHERE id = id_param;

		-- jika terdeteksi perubahan jenis
		IF jenis_sebelum = 'UANG MASUK' THEN

			-- get saldo bank lama
			SELECT saldo INTO get_saldo_bank_lama FROM bank WHERE id = id_bank_sebelum;
			
			-- normalisasi saldo bank sebelum
			UPDATE bank SET 
				saldo = (get_saldo_bank_lama - nominal_sebelum),
				modified_by = modified_by_param
			WHERE id = id_bank_sebelum;

			-- insert mutasi bank lama
			INSERT INTO mutasi_bank 
				(id_bank, tgl, uang_masuk, uang_keluar, saldo, ket, created_by, modified_by)
			VALUES 
				(id_bank_sebelum, tgl_param, 0, nominal_sebelum, (get_saldo_bank_lama - nominal_sebelum), 
				ket_mutasi_param, modified_by_param, modified_by_param);
		
		END IF;

		-- jika ada perubahan bank
		IF id_bank_sebelum != id_bank_param THEN

			-- get saldo bank lama
			SELECT saldo INTO get_saldo_bank_lama FROM bank WHERE id = id_bank_sebelum;
			
			-- normalisasi saldo bank sebelum
			UPDATE bank SET 
				saldo = (get_saldo_bank_lama + nominal_sebelum),
				modified_by = modified_by_param 
			WHERE id = id_bank_sebelum;

			-- insert mutasi bank lama
			INSERT INTO mutasi_bank 
				(id_bank, tgl, uang_masuk, uang_keluar, saldo, ket, created_by, modified_by)
			VALUES 
				(id_bank_sebelum, tgl_param, nominal_sebelum, 0, (get_saldo_bank_lama + nominal_sebelum), 
				ket_bank_masuk_param, modified_by_param, modified_by_param);

			-- get saldo bank baru
			SELECT saldo INTO get_saldo_bank FROM bank WHERE id = id_bank_param;

			-- update saldo bank baru
			UPDATE bank SET 
				saldo = (get_saldo_bank - nominal_param),
				modified_by = modified_by_param
			WHERE id = id_bank_param;

			-- insert mutasi bank baru
			INSERT INTO mutasi_bank 
				(id_bank, tgl, uang_masuk, uang_keluar, saldo, ket, created_by, modified_by)
			VALUES 
				(id_bank_param, tgl_param, 0, nominal_param, (get_saldo_bank - nominal_param), 
				ket_bank_keluar_param, modified_by_param, modified_by_param);
		
			-- jika ada perubahan nominal
		ELSE IF nominal_sebelum != nominal_param THEN

			IF nominal_param > nominal_sebelum THEN

				-- get saldo bank
				SELECT saldo INTO get_saldo_bank FROM bank WHERE id = id_bank_param;

				-- normalisasi saldo
				UPDATE bank SET 
					saldo = (get_saldo_bank - (nominal_param - nominal_sebelum)) ,
					modified_by = modified_by_param
				WHERE id = id_bank_param;

				-- insert mutasi
				INSERT INTO mutasi_bank 
					(id_bank, tgl, uang_masuk, uang_keluar, saldo, ket, created_by, modified_by)
				VALUES 
					(id_bank_param, tgl_param, 0, (nominal_param - nominal_sebelum), (get_saldo_bank - (nominal_param - nominal_sebelum)), 
					ket_saldo_change_param, modified_by_param, modified_by_param);
			
			ELSE IF nominal_param < nominal_sebelum THEN

				-- get saldo bank
				SELECT saldo INTO get_saldo_bank FROM bank WHERE id = id_bank_param;
			
				-- normalisasi saldo
				UPDATE bank SET 
					saldo = (get_saldo_bank + (nominal_sebelum - nominal_param)),
					modified_by = modified_by_param
				WHERE id = id_bank_param;

				-- insert mutasi
				INSERT INTO mutasi_bank 
					(id_bank, tgl, uang_masuk, uang_keluar, saldo, ket, created_by, modified_by)
				VALUES 
					(id_bank_param, tgl_param, (nominal_sebelum - nominal_param), 0, (get_saldo_bank + (nominal_sebelum - nominal_param)), 
					ket_saldo_change_param, modified_by_param, modified_by_param);

					END IF;

				END IF;

			END IF;

		END IF;

		-- update operasional
		UPDATE operasional SET 
			id_bank = id_bank_param, tgl = tgl_param, nama = nama_param, nominal = nominal_param, 
			jenis = jenis_param, ket = ket_param, modified_by = modified_by_param
		WHERE id = id_param;

	END //

	delimiter ;
-- End Procedure Edit Operasional Keluar

-- Procedure Hapus Operasional
	DROP PROCEDURE IF EXISTS p_hapus_operasional;
	delimiter //

	CREATE PROCEDURE p_hapus_operasional(
		id_param int,
		tgl_param date,
		ket_param text,
		modified_by_param varchar(50)
	)
	BEGIN
        
		DECLARE id_bank_param int;
		DECLARE nominal_param double(12,2);
		DECLARE jenis_param varchar(25);
		DECLARE get_saldo double(12,2);

		-- get id bank
		SELECT id_bank INTO id_bank_param FROM operasional WHERE id = id_param;
		-- get jenis
		SELECT jenis INTO jenis_param FROM operasional WHERE id = id_param;
		-- get nominal
		SELECT nominal INTO nominal_param FROM operasional WHERE id = id_param;

		-- get saldo
		SELECT saldo INTO get_saldo FROM bank WHERE id = id_bank_param;

		IF jenis_param = 'UANG MASUK' THEN

			-- 1. insert mutasi
			INSERT INTO mutasi_bank 
				(id_bank, tgl, uang_masuk, uang_keluar, saldo, ket, created_by, modified_by)
			VALUES 
				(id_bank_param, tgl_param, 0, nominal_param, (get_saldo - nominal_param), 
				ket_param, modified_by_param, modified_by_param);

			-- 2. update saldo
			UPDATE bank SET 
				saldo = (get_saldo - nominal_param),
				modified_by = modified_by_param
			WHERE id = id_bank_param;

		ELSE

			-- 1. insert mutasi
			INSERT INTO mutasi_bank 
				(id_bank, tgl, uang_masuk, uang_keluar, saldo, ket, created_by, modified_by)
			VALUES 
				(id_bank_param, tgl_param, nominal_param, 0, (get_saldo + nominal_param), 
				ket_param, modified_by_param, modified_by_param);

			-- 2. update saldo
			UPDATE bank SET 
				saldo = (get_saldo + nominal_param),
				modified_by = modified_by_param
			WHERE id = id_bank_param;

		END IF;

		-- 3. hapus data operasional
		DELETE FROM operasional WHERE id = id_param;

	END //

	delimiter ;
-- End Procedure Hapus Operasional

# End Procedure, Function, and Trigger Operasional #

# View Operasional #

-- View Operasional
	CREATE OR REPLACE VIEW v_operasional AS
	SELECT 
		op.id , op.tgl, op.nama, op.nominal, op.jenis, op.ket, b.id id_bank, b.nama nama_bank,
		kb.id id_kas_besar, kb.nama nama_kas_besar, kb.no_telp no_telp, kb.email email
	FROM operasional op
	JOIN bank b ON b.id = op.id_bank
	JOIN kas_besar kb ON kb.id = op.id_kas_besar;  
-- End View Operasional

-- View Operasional Export
	CREATE OR REPLACE VIEW v_operasional_export AS
	SELECT 
		op.id 'ID', op.tgl 'TANGGAL', op.nama 'NAMA', op.nominal 'NOMINAL', op.jenis 'JENIS', op.ket 'KETERANGAN', b.id 'ID BANK', b.nama 'BANK',
		kb.id 'ID KAS BESAR', kb.nama 'KAS BESAR', kb.no_telp 'NO TELP', kb.email 'EMAIL'
	FROM operasional op
	JOIN bank b ON b.id = op.id_bank
	JOIN kas_besar kb ON kb.id = op.id_kas_besar;
-- End View Operasional Export

# End View Operasional #

# Procedure, Function, and Trigger Operasional Proyek #

-- Procedure Tambah Operasional Proyek Tunai Lunas
	DROP PROCEDURE IF EXISTS p_tambah_operasional_proyek_tunailunas;
	delimiter //

	CREATE PROCEDURE p_tambah_operasional_proyek_tunailunas(
		IN id_param varchar(50),
		IN id_proyek_param varchar(50),
		IN id_bank_param int,
		IN id_kas_besar_param varchar(10),
		IN id_distributor_param varchar(50),
		IN tgl_param date,
		IN nama_param varchar(50),
		IN jenis_param varchar(50),
		IN total_param double(12,2),
		IN sisa_param double(12,2),
		IN status_param enum('TUNAI','KREDIT'),
		IN status_lunas_param enum('LUNAS','BELUM LUNAS'),
		IN ket_param text,
		IN ket_mutasi_param text,
		IN created_by_param varchar(50)
	)

	BEGIN
		DECLARE get_saldo double(12,2);

		-- 1. insert ke operasional proyek
		INSERT INTO operasional_proyek
			(id, id_proyek, id_kas_besar, id_distributor, tgl, nama, jenis, 
			total, sisa, status, status_lunas, ket, created_by, modified_by)
		VALUES
			(id_param, id_proyek_param, id_kas_besar_param, id_distributor_param, tgl_param, 
			nama_param, jenis_param, total_param, sisa_param, status_param, status_lunas_param, 
			ket_param, created_by_param, created_by_param);

		-- 2. insert ke detail operasional proyek
		INSERT INTO detail_operasional_proyek
			(id_operasional_proyek, id_bank, nama, tgl, total, created_by, modified_by)
		VALUES
			(id_param, id_bank_param, nama_param, tgl_param, total_param, created_by_param, created_by_param);

		-- 3. ambil saldo terakhir
		SELECT saldo INTO get_saldo FROM bank WHERE id = id_bank_param;

		-- 4. update saldo
		UPDATE bank SET 
			saldo = (get_saldo - total_param),
			modified_by = created_by_param 
		WHERE id = id_bank_param;

		-- 5. insert mutasi
		INSERT INTO mutasi_bank
			(id_bank, tgl, uang_masuk, uang_keluar, saldo, ket, created_by, modified_by)
		VALUES
			(id_bank_param, tgl_param, 0, total_param, (get_saldo - total_param), 
			ket_mutasi_param, created_by_param, created_by_param);
	END //
	
	delimiter ;
-- End Procedure Tambah Operasional Proyek Tunai Lunas

-- Procedure Tambah Operasional Proyek Tunai Belum Lunas
	DROP PROCEDURE IF EXISTS p_tambah_operasional_proyek_tunaiblmlunas;
	delimiter //
	
	CREATE PROCEDURE p_tambah_operasional_proyek_tunaiblmlunas(
		IN id_param varchar(50),
		IN id_proyek_param varchar(50),
		IN id_bank_param int,
		IN id_kas_besar_param varchar(10),
		IN id_distributor_param varchar(50),
		IN tgl_param date,
		IN nama_param varchar(50),
		IN jenis_param varchar(50),
		IN total_param double(12,2),
		IN sisa_param double(12,2),
		IN status_param enum('TUNAI','KREDIT'),
		IN status_lunas_param enum('LUNAS','BELUM LUNAS'),
		IN ket_param text,
		IN created_by_param varchar(50)
	)

	BEGIN

		-- 1. insert ke operasional proyek
		INSERT INTO operasional_proyek
			(id, id_proyek, id_kas_besar, id_distributor, tgl, nama, jenis, total, 
			sisa, status, status_lunas, ket, created_by, modified_by)
		VALUES
			(id_param, id_proyek_param, id_kas_besar_param, id_distributor_param, tgl_param, nama_param, 
			jenis_param, total_param, sisa_param, status_param, status_lunas_param, 
			ket_param, created_by_param, created_by_param);
			
	END //

	delimiter ;
-- End Procedure Tambah Operasional Proyek Tunai Belum Lunas

-- Procedure Tambah Operasional Proyek Kredit
	DROP PROCEDURE IF EXISTS p_tambah_operasional_proyek_kredit;
	delimiter //

	CREATE PROCEDURE p_tambah_operasional_proyek_kredit(
		IN id_param varchar(50),
		IN id_proyek_param varchar(50),
		IN id_bank_param int,
		IN id_kas_besar_param varchar(10),
		IN id_distributor_param varchar(50),
		IN tgl_param date,
		IN nama_param varchar(50),
		IN jenis_param varchar(50),
		IN total_param double(12,2),
		IN sisa_param double(12,2),
		IN status_param enum('TUNAI','KREDIT'),
		IN status_lunas_param enum('LUNAS','BELUM LUNAS'),
		IN ket_param text,
		IN created_by_param varchar(50)
	)

	BEGIN
		DECLARE get_saldo double(12,2);

		-- 1. insert ke operasional proyek
		INSERT INTO operasional_proyek
			(id, id_proyek, id_kas_besar, id_distributor, tgl, nama, jenis, total, sisa, 
			status, status_lunas, ket, created_by, modified_by)
		VALUES
			(id_param, id_proyek_param, id_kas_besar_param, id_distributor_param, tgl_param, nama_param, 
			jenis_param, total_param, sisa_param, status_param, status_lunas_param, ket_param,
			created_by_param, created_by_param);

	END //

	delimiter ;
-- End Procedure Tambah Operasional Proyek Kredit

-- Procedure Tambah Detail Operasional Proyek Untuk Kondisi Kredit
	DROP PROCEDURE IF EXISTS p_tambah_detail_operasional_proyek_kredit;
	delimiter //

	CREATE PROCEDURE p_tambah_detail_operasional_proyek_kredit(
		IN id_param varchar(50),
		IN id_bank_param int,
		IN tgl_param date,
		IN nama_param varchar(50),
		IN total_detail_param double(12,2),
		IN ket_param text,
		IN created_by_param varchar(50)
	)

	BEGIN
		DECLARE get_saldo double(12,2);

			-- ambil saldo terakhir
			SELECT saldo INTO get_saldo FROM bank WHERE id = id_bank_param;

			-- update saldo
			UPDATE bank SET 
				saldo = ( get_saldo - total_detail_param ),
				modified_by = created_by_param
			WHERE id = id_bank_param;

			-- insert into detail
			INSERT INTO detail_operasional_proyek
				(id_operasional_proyek, id_bank, nama, tgl, total, created_by, modified_by)
			VALUES
				(id_param, id_bank_param, nama_param, tgl_param, total_detail_param,
				created_by_param, created_by_param);

			-- insert mutasi
			INSERT INTO mutasi_bank
				(id_bank, tgl, uang_masuk, uang_keluar, saldo, ket, created_by, modified_by)
			VALUES
				(id_bank_param, tgl_param, 0, total_detail_param, (get_saldo - total_detail_param), 
				ket_param, created_by_param, created_by_param);
			
	END //

	delimiter ;
-- End Tambah Detail Operasional Proyek Untuk Kondisi Kredit

-- Procedure Edit Operasional Proyek Lunas
	DROP PROCEDURE IF EXISTS p_edit_operasional_proyek;
	delimiter //

	CREATE PROCEDURE p_edit_operasional_proyek(
		IN id_param varchar(50),
		IN id_detail_param varchar(50),
		IN id_proyek_param varchar(50),
		IN id_bank_param int,
		IN id_distributor_param varchar(50),
		IN tgl_param date,
		IN nama_param varchar(50),
		IN jenis_param varchar(50),
		IN total_param double(12,2),
		IN sisa_param double(12,2),
		IN status_param enum('TUNAI','KREDIT'),
		IN status_lunas_param enum('LUNAS','BELUM LUNAS'),
		IN ket_param text,
		IN ket_mutasi_param text,
		IN ket_mutasi_masuk_param text,
		IN ket_mutasi_keluar_param text,
		IN ket_mutasi_kondisi_param text,
		IN modified_by_param varchar(50)
	)

	BEGIN
		DECLARE get_saldo double(12,2);
		DECLARE get_bank_sebelum int;
		DECLARE get_id_bank int;
		DECLARE jumlah_detail int;
		DECLARE get_total_sebelum double(12,2);
		DECLARE get_status_sebelum varchar(50);
		DECLARE get_saldo_bank_lama double(12,2);
		DECLARE get_saldo_bank_baru double(12,2);

		-- get id_bank dan total sebelum diedit
		SELECT id_bank INTO get_bank_sebelum FROM detail_operasional_proyek WHERE id = id_detail_param;
		SELECT total INTO get_total_sebelum FROM detail_operasional_proyek WHERE id = id_detail_param;

		-- jika ada perubahan di bank
		IF get_bank_sebelum != id_bank_param THEN
			
			-- get saldo bank lama
			SELECT saldo INTO get_saldo_bank_lama FROM bank WHERE id = get_bank_sebelum;
			
			-- normalisasi saldo bank sebelum
			UPDATE bank SET 
				saldo = (get_saldo_bank_lama + get_total_sebelum),
				modified_by = modified_by_param
			WHERE id = get_bank_sebelum;

			-- insert mutasi bank lama
			INSERT INTO mutasi_bank 
				(id_bank, tgl, uang_masuk, uang_keluar, saldo, ket, created_by, modified_by)
			VALUES 
				(get_bank_sebelum, tgl_param, get_total_sebelum, 0, (get_saldo_bank_lama + get_total_sebelum), 
				ket_mutasi_masuk_param, modified_by_param, modified_by_param);

			-- get saldo bank baru
			SELECT saldo INTO get_saldo_bank_baru FROM bank WHERE id = id_bank_param;

			-- update saldo bank baru
			UPDATE bank SET 
				saldo = (get_saldo_bank_baru - total_param),
				modified_by = modified_by_param
			WHERE id = id_bank_param;

			-- insert mutasi bank baru
			INSERT INTO mutasi_bank 
				(id_bank, tgl, uang_masuk, uang_keluar, saldo, ket, created_by, modified_by)
			VALUES 
				(id_bank_param, tgl_param, 0, total_param, (get_saldo_bank_baru - total_param), 
				ket_mutasi_keluar_param, modified_by_param, modified_by_param);
		ELSE
			-- jika bank sama
			-- jika ada perubahan di total
			IF get_total_sebelum != total_param THEN
				-- get saldo bank
				SELECT saldo INTO get_saldo_bank_baru FROM bank WHERE id = id_bank_param;
				
				IF total_param > get_total_sebelum THEN

					-- normalisasi saldo
					UPDATE bank SET 
						saldo = (get_saldo_bank_baru - (total_param - get_total_sebelum)),
						modified_by = modified_by_param
					WHERE id = id_bank_param;

					-- insert mutasi
					INSERT INTO mutasi_bank 
						(id_bank, tgl, uang_masuk, uang_keluar, saldo, ket, created_by, modified_by)
					VALUES 
						(id_bank_param, tgl_param, 0, (total_param - get_total_sebelum), (get_saldo_bank_baru - (total_param - get_total_sebelum)), 
						ket_mutasi_kondisi_param, modified_by_param, modified_by_param);
				ELSE
					IF total_param < get_total_sebelum THEN

						-- normalisasi saldo
						UPDATE bank SET 
							saldo = (get_saldo_bank_baru + (get_total_sebelum - total_param)),
							modified_by = modified_by_param
						WHERE id = id_bank_param;

						-- insert mutasi
						INSERT INTO mutasi_bank 
							(id_bank, tgl, uang_masuk, uang_keluar, saldo, ket, created_by, modified_by)
						VALUES 
							(id_bank_param, tgl_param, (get_total_sebelum - total_param), 0, (get_saldo_bank_baru + (get_total_sebelum - total_param)), 
							ket_mutasi_kondisi_param, modified_by_param, modified_by_param);
					END IF;

				END IF;
		
			END IF;

		END IF;

		-- Get Status Sebelum
		SELECT status INTO get_status_sebelum FROM operasional_proyek WHERE id = id_param;

		-- Cek apakah ada detail atau tidak
		-- Untuk menentukan apakah ini data perubahan dari belum lunas atau bukan
		SELECT COUNT(id) INTO jumlah_detail FROM detail_operasional_proyek WHERE id = id_detail_param;

		-- Update table operasional proyek
		UPDATE operasional_proyek SET 
			id_distributor = id_distributor_param, tgl = tgl_param, nama = nama_param, jenis = jenis_param, 
			total = total_param, sisa = sisa_param, status = status_param, status_lunas = status_lunas_param, 
			ket = ket_param, modified_by = modified_by_param
		WHERE id = id_param;

		-- Jika Detail Ada, Maka
		IF (jumlah_detail > 0) THEN
		
			-- Update Table Detail Operasional Proyek
			UPDATE detail_operasional_proyek SET 
				id_bank = id_bank_param, nama = nama_param, tgl = tgl_param, total = total_param,
				modified_by = modified_by_param
			WHERE id = id_detail_param;
		
		ELSE 

			-- Ambil saldo terakhir
			SELECT saldo INTO get_saldo FROM bank WHERE id = id_bank_param;

			-- Update saldo
			UPDATE bank SET 
				saldo = ( get_saldo - total_param ),
				modified_by = modified_by_param
			WHERE id = id_bank_param;

			-- Delete Table Detail Operasional Proyek
			DELETE FROM detail_operasional_proyek WHERE id_operasional_proyek = id_param;

			-- Insert Into Operasional Proyek
			INSERT INTO detail_operasional_proyek
				(id_operasional_proyek, id_bank, nama, tgl, total, created_by, modified_by)
			VALUES
				(id_param, id_bank_param, nama_param, tgl_param, total_param, modified_by_param, modified_by_param);

			-- Catat Mutasi
			INSERT INTO mutasi_bank
				(id_bank, tgl, uang_masuk, uang_keluar, saldo, ket, created_by, modified_by)
			VALUES
				(id_bank_param, tgl_param, 0, total_param, (get_saldo - total_param), 
				ket_mutasi_param, modified_by_param, modified_by_param);
		
		END IF;

	END //

	delimiter ;
-- End Procedure Edit Operasional Proyek Lunas

-- Procedure Edit Operasional Proyek Belum Lunas
	DROP PROCEDURE IF EXISTS p_edit_operasional_proyek_BelumLunas;
	delimiter //

	CREATE PROCEDURE p_edit_operasional_proyek_BelumLunas(
		IN id_param varchar(50),
		IN id_proyek_param varchar(50),
		IN id_distributor_param varchar(50),
		IN tgl_param date,
		IN nama_param varchar(50),
		IN jenis_param varchar(50),
		IN total_param double(12,2),
		IN sisa_param double(12,2),
		IN status_param enum('TUNAI','KREDIT'),
		IN status_lunas_param enum('LUNAS','BELUM LUNAS'),
		IN ket_param text,
		IN ket_mutasi_param text,
		IN modified_by_param varchar(50)
	)

	BEGIN
		DECLARE get_saldo double(12,2);
		DECLARE get_id_bank int;
		DECLARE jumlah_detail int;
		DECLARE total_detail double(12,2);

		-- Cek apakah ada detail atau tidak
		-- Untuk menentukan apakah ini data perubahan dari lunas atau bukan
		SELECT COUNT(id) INTO jumlah_detail FROM detail_operasional_proyek WHERE id_operasional_proyek = id_param;

		-- Jika Detail Ada, Maka
		IF (jumlah_detail > 0) THEN

			-- Get id_bank
			SELECT DISTINCT(id_bank) INTO get_id_bank FROM detail_operasional_proyek WHERE id_operasional_proyek = id_param;

			-- Get Total
			SELECT total INTO total_detail FROM detail_operasional_proyek WHERE id_operasional_proyek = id_param;

			-- Ambil saldo terakhir
			SELECT saldo INTO get_saldo FROM bank WHERE id = get_id_bank;

			-- Update saldo
			UPDATE bank SET 
				saldo = ( get_saldo + total_detail ),
				modified_by = modified_by_param
			WHERE id = get_id_bank;

			-- Delete Table Detail Operasional Proyek
			DELETE FROM detail_operasional_proyek WHERE id_operasional_proyek = id_param;

			-- Catat Mutasi
			INSERT INTO mutasi_bank
				(id_bank, tgl, uang_masuk, uang_keluar, saldo, ket, created_by, modified_by)
			VALUES
				(get_id_bank, tgl_param, total_detail, 0, (get_saldo + total_detail), 
				ket_mutasi_param, modified_by_param, modified_by_param);

			-- Update table operasional proyek
			UPDATE operasional_proyek SET 
				id_distributor = id_distributor_param, tgl = tgl_param, nama = nama_param, jenis = jenis_param, 
				total = total_param, sisa = sisa_param, status = status_param, status_lunas = status_lunas_param, 
				ket = ket_param, modified_by = modified_by_param 
			WHERE id = id_param;

		ELSE 

			-- Update table operasional proyek
			UPDATE operasional_proyek SET 
				id_distributor = id_distributor_param, tgl = tgl_param, nama = nama_param, 
				jenis = jenis_param, total = total_param, sisa = sisa_param, status = status_param, status_lunas = status_lunas_param, 
				ket = ket_param, modified_by = modified_by_param 
			WHERE id = id_param;
		
		END IF;

	END //

	delimiter ;
-- End Procedure Edit Operasional Proyek Belum Lunas

-- Procedure Edit Operasional Jenis Pembayaran Kredit
	DROP PROCEDURE IF EXISTS p_edit_operasional_proyek_kredit;
	delimiter //
	
	CREATE PROCEDURE p_edit_operasional_proyek_kredit(
		IN id_param varchar(50),
		IN id_proyek_param varchar(50),
		IN id_distributor_param varchar(50),
		IN tgl_param date,
		IN nama_param varchar(50),
		IN jenis_param varchar(50),
		IN total_param double(12,2),
		IN sisa_param double(12,2),
		IN status_param enum('TUNAI','KREDIT'),
		IN status_lunas_param enum('LUNAS','BELUM LUNAS'),
		IN ket_param text,
		IN modified_by_param varchar(50)
	)

	BEGIN
		-- DECLARE get_saldo double(12,2);
		-- DECLARE get_id_bank int;
		-- DECLARE get_sisa double(12,2);
		
		-- Update table operasional proyek
		UPDATE operasional_proyek SET 
			id_distributor = id_distributor_param, tgl = tgl_param, nama = nama_param, 
			jenis = jenis_param, total = total_param, status = status_param, status_lunas = status_lunas_param, 
			ket = ket_param, modified_by = modified_by_param
		WHERE id = id_param;

	END //

	delimiter ;
-- End Procedure Edit Operasional Jenis Pembayaran Kredit

-- Procedure Hapus Data Operasional Proyek
	DROP PROCEDURE IF EXISTS p_hapus_operasional_proyek_versi2;
	delimiter //

	CREATE PROCEDURE p_hapus_operasional_proyek_versi2(
		IN id_param varchar(50),
		IN total_param double(12,2),
		IN tgl_param date,
		IN ket_param text,
		IN modified_by_param varchar(50)
	)

	BEGIN
		-- deklarasi ambil saldo terakhir 
		DECLARE get_saldo double(12,2);
		DECLARE get_id_bank int;

		-- get id_bank
		SELECT DISTINCT(id_bank) INTO get_id_bank FROM detail_operasional_proyek where id_operasional_proyek = id_param;

		-- ambil saldo terakhir
		SELECT saldo INTO get_saldo FROM bank WHERE id = get_id_bank;

		-- update saldo ke semula
		UPDATE bank SET 
			saldo = (get_saldo + total_param),
			modified_by = modified_by_param
		WHERE id = get_id_bank;

		-- insert mutasi (setelah perubahan)
		INSERT INTO mutasi_bank 
			(id_bank, tgl, uang_masuk, uang_keluar, saldo, ket, created_by, modified_by)
		VALUES
			(get_id_bank, tgl_param, total_param, 0, (get_saldo + total_param), 
			ket_param, modified_by_param, modified_by_param);

		-- hapus detail operasional proyek
		DELETE FROM detail_operasional_proyek where id_operasional_proyek IN
			(SELECT id FROM operasional_proyek where id = id_param);
		
		-- hapus operasional proyek
		DELETE  FROM operasional_proyek where id = id_param;

	END //

	delimiter ;
-- End Procedure Hapus Data Operasional Proyek

-- Procedure Update Data Detail Operasional
	DROP PROCEDURE IF EXISTS p_edit_detail_operasional_proyek;
	delimiter //

	CREATE PROCEDURE p_edit_detail_operasional_proyek(
		IN id_operasional_proyek_param varchar(50),
		IN id_detail_param varchar(50),
		IN id_bank_param varchar(50),
		IN tgl_detail_param date,
		IN nama_detail_param varchar(50),
		IN total_detail_param double(12,2),
		IN ket_mutasi_param text,
		IN ket_mutasi_masuk_param text,
		IN ket_mutasi_keluar_param text,
		IN ket_mutasi_kondisi_param text,
		IN modified_by_param varchar(50)
	)
	BEGIN
		DECLARE get_saldo double(12,2);
		DECLARE get_id_bank int;
		DECLARE get_bank_sebelum int;
		DECLARE get_total_sebelum double(12,2);
		DECLARE get_saldo_bank_lama double(12,2);
		DECLARE get_saldo_bank_baru double(12,2);

		-- get id_bank dan total sebelum diedit
		SELECT id_bank INTO get_bank_sebelum FROM detail_operasional_proyek WHERE id = id_detail_param;
		SELECT total INTO get_total_sebelum FROM detail_operasional_proyek WHERE id = id_detail_param;

		-- jika ada perubahan di bank
		IF get_bank_sebelum != id_bank_param THEN
			
			-- get saldo bank lama
			SELECT saldo INTO get_saldo_bank_lama FROM bank WHERE id = get_bank_sebelum;
			
			-- normalisasi saldo bank sebelum
			UPDATE bank SET 
				saldo = (get_saldo_bank_lama + get_total_sebelum),
				modified_by = modified_by_param
			WHERE id = get_bank_sebelum;

			-- insert mutasi bank lama
			INSERT INTO mutasi_bank 
				(id_bank, tgl, uang_masuk, uang_keluar, saldo, ket, created_by, modified_by)
			VALUES 
				(get_bank_sebelum, tgl_detail_param, get_total_sebelum, 0, (get_saldo_bank_lama + get_total_sebelum), 
				ket_mutasi_masuk_param, modified_by_param, modified_by_param);

			-- get saldo bank baru
			SELECT saldo INTO get_saldo FROM bank WHERE id = id_bank_param;

			-- update saldo bank baru
			UPDATE bank SET 
				saldo = (get_saldo - total_detail_param),
				modified_by = modified_by_param 
			WHERE id = id_bank_param;

			-- insert mutasi bank baru
			INSERT INTO mutasi_bank 
				(id_bank, tgl, uang_masuk, uang_keluar, saldo, ket, created_by, modified_by)
			VALUES 
				(id_bank_param, tgl_detail_param, 0, total_detail_param, (get_saldo - total_detail_param), 
				ket_mutasi_keluar_param, modified_by_param, modified_by_param);
		ELSE
			-- jika bank sama
			-- jika ada perubahan di total
			IF get_total_sebelum != total_detail_param THEN
				
				IF total_detail_param > get_total_sebelum THEN

					-- get saldo bank
					SELECT saldo INTO get_saldo FROM bank WHERE id = id_bank_param;

					-- normalisasi saldo
					UPDATE bank SET 
						saldo = (get_saldo - (total_detail_param - get_total_sebelum)),
						modified_by = modified_by_param 
					WHERE id = id_bank_param;

					-- insert mutasi
					INSERT INTO mutasi_bank 
						(id_bank, tgl, uang_masuk, uang_keluar, saldo, ket, created_by, modified_by)
					VALUES 
						(id_bank_param, tgl_detail_param, 0, (total_detail_param - get_total_sebelum), (get_saldo - (total_detail_param - get_total_sebelum)), 
						ket_mutasi_kondisi_param, modified_by_param, modified_by_param);
				
				ELSE IF total_detail_param < get_total_sebelum THEN

					-- get saldo bank
					SELECT saldo INTO get_saldo FROM bank WHERE id = id_bank_param;

					-- normalisasi saldo
					UPDATE bank SET 
						saldo = (get_saldo + (get_total_sebelum - total_detail_param)),
						modified_by = modified_by_param 
					WHERE id = id_bank_param;

					-- insert mutasi
					INSERT INTO mutasi_bank 
						(id_bank, tgl, uang_masuk, uang_keluar, saldo, ket, created_by, modified_by)
					VALUES 
						(id_bank_param, tgl_detail_param, (get_total_sebelum - total_detail_param), 0, (get_saldo + (get_total_sebelum - total_detail_param)), 
						ket_mutasi_kondisi_param, modified_by_param, modified_by_param);
					
					END IF;

				END IF;
		
			END IF;

		END IF;

		-- Update table detail operasional proyek
		UPDATE detail_operasional_proyek SET 
			id_bank = id_bank_param, nama = nama_detail_param, tgl = tgl_detail_param, total = total_detail_param,
			modified_by = modified_by_param
		WHERE id = id_detail_param;

	END //

	delimiter ;	
-- End Procedure Update Data Detail Operasional

-- Hapus Operasional Proyek Tunai Belum Lunas
	DROP PROCEDURE IF EXISTS p_hapus_operasional_proyek_tunai_blmlunas;
	delimiter //
	
	CREATE PROCEDURE p_hapus_operasional_proyek_tunai_blmlunas(
		IN id_param varchar(50)
	)
	BEGIN
		-- hapus operasional proyek
		DELETE  FROM operasional_proyek where id = id_param;
	END //

	delimiter ;
-- End Hapus Operasional Proyek Tunai Belum Lunas

-- Hapus Operasional Proyek Jenis Pembayaran Kredit
	DROP PROCEDURE IF EXISTS p_hapus_operasional_proyek_kredit;
	delimiter //
	
	CREATE PROCEDURE p_hapus_operasional_proyek_kredit(
		IN id_param varchar(50)
	)
	BEGIN
		-- hapus detail operasional proyek
		DELETE FROM detail_operasional_proyek WHERE id_operasional_proyek IN
			( SELECT id FROM operasional_proyek WHERE id = id_param );
		-- hapus operasional proyek
		DELETE FROM operasional_proyek WHERE id = id_param;
	END //

	delimiter ;
-- End Hapus Operasional Proyek Jenis Pembayaran Kredit

-- Procedure Pencatatan Mutasi Bank Setelah Operasional Proyek Kredit Dihapus
	DROP PROCEDURE IF EXISTS p_hapus_operasional_proyek_kredit_catatMutasi;
	delimiter //
	
	CREATE PROCEDURE p_hapus_operasional_proyek_kredit_catatMutasi(
		IN id_param varchar(50),
		IN id_bank_param varchar(50),
		IN total_detail_param double(12,2),
		IN tgl_param date,
		IN ket_param text,
		IN modified_by_param varchar(50)
	)

	BEGIN
		DECLARE get_saldo double(12,2);

		-- ambil saldo terakhir
		SELECT saldo INTO get_saldo FROM bank WHERE id = id_bank_param;

		-- update saldo ke semula
		UPDATE bank SET 
			saldo = (get_saldo + total_detail_param),
			modified_by = modified_by_param 
		WHERE id = id_bank_param;

		-- insert mutasi 
		INSERT INTO mutasi_bank 
			(id_bank, tgl, uang_masuk, uang_keluar, saldo, ket, created_by, modified_by)
		VALUES
			(id_bank_param, tgl_param, total_detail_param, 0, (get_saldo + total_detail_param), 
			ket_param, modified_by_param, modified_by_param);

	END //
	
	delimiter ;
-- End Procedure Pencatatan Mutasi Bank Setelah Operasional Proyek Kredit Dihapus

# End Procedure, Function, and Trigger Operasional Proyek #

# View Operasional Proyek #

-- View Operasional Proyek
	CREATE OR REPLACE VIEW v_operasional_proyek AS
	SELECT 
		opr.id, pr.id id_proyek, pr.pemilik pemilik_proyek, pr.pembangunan nama_pembangunan,
		kb.id id_kas_besar, kb.nama nama_kas_besar, 
		dst.id id_distributor, dst.nama nama_distributor, opr.tgl tgl_operasional, 
		opr.nama nama_operasional, opr.jenis jenis_operasional, opr.total total, opr.sisa sisa_operasional, 
		opr.status jenis_pembayaran,  opr.status_lunas status_lunas, opr.ket keterangan
	FROM operasional_proyek opr
	JOIN proyek pr ON pr.id = opr.id_proyek 
	JOIN kas_besar kb ON kb.id = opr.id_kas_besar
	LEFT JOIN distributor dst ON dst.id = opr.id_distributor;
	-- LEFT JOIN detail_operasional_proyek dopr ON dopr.id_operasional_proyek = opr.id
	-- LEFT JOIN bank b ON b.id = dopr.id_bank;
-- End View Operasional Proyek

-- View Operasional Proyek (Export Excel)
	CREATE OR REPLACE VIEW v_operasional_proyek_export AS
	SELECT 
		opr.id 'ID OPERASIONAL PROYEK', pr.id 'ID PROYEK', pr.pemilik 'PEMILIK', pr.pembangunan 'PROYEK',
		kb.id 'ID KAS BESAR', kb.nama 'KAS BESAR', 
		dst.id 'ID DISTRIBUTOR', dst.nama 'DISTRIBUTOR', opr.tgl 'TANGGAL', 
		opr.nama 'NAMA OPERASIONAL', opr.jenis 'JENIS OPERASIONAL', opr.total 'TOTAL OPERASIONAL', opr.sisa 'SISA PEMBAYARAN', 
		opr.status 'JENIS PEMBAYARAN',  opr.status_lunas 'STATUS PEMBAYARAN', opr.ket 'KETERANGAN'
	FROM operasional_proyek opr
	JOIN proyek pr ON pr.id = opr.id_proyek 
	JOIN kas_besar kb ON kb.id = opr.id_kas_besar
	LEFT JOIN distributor dst ON dst.id = opr.id_distributor;
-- End View Operasional Proyek (Export Excel)

-- View Detail Operasional Proyek
	CREATE OR REPLACE VIEW v_detail_operasional_proyek AS
	SELECT  detail_operasional_proyek.id, detail_operasional_proyek.id_operasional_proyek,
		bank.nama AS 'nama_bank', detail_operasional_proyek.nama, detail_operasional_proyek.tgl,
		detail_operasional_proyek.total
	FROM detail_operasional_proyek 
	JOIN bank ON bank.id = detail_operasional_proyek.id_bank;
-- End View Detail Operasional Proyek

-- View Detail Operasional Proyek Export
	CREATE OR REPLACE VIEW v_detail_operasional_proyek_export AS
	SELECT  detail_operasional_proyek.id 'ID DETAIL', detail_operasional_proyek.id_operasional_proyek 'ID',
		bank.nama AS 'BANK', detail_operasional_proyek.nama 'DETAIL OPERASIONAL', detail_operasional_proyek.tgl 'TANGGAL',
		detail_operasional_proyek.total 'TOTAL'
	FROM detail_operasional_proyek 
	JOIN bank ON bank.id = detail_operasional_proyek.id_bank;
-- End View Detail Operasional Proyek Export

-- View Detail Operasional Proyek Export v2
	CREATE OR REPLACE VIEW v_export_detail_operasional_proyek AS
	SELECT
		-- operasional proyek
		op.id `ID OPERASIONAL PROYEK`, op.nama `OPERASIONAL`, op.id_proyek `ID PROYEK`, op.tgl `TANGGAL OPERASIONAL`,

		-- detail operasional proyek
		dop.nama `DETAIL OPERASIONAL`, dop.tgl `TANGGAL DETAIL`, b.nama `BANK`, dop.total `TOTAL`
	FROM operasional_proyek op
	JOIN detail_operasional_proyek dop ON dop.id_operasional_proyek = op.id
	JOIN bank b ON b.id = dop.id_bank;
-- End View Detail Operasional Proyek Export v2

/* VIEW HISTORY PEMBELIAN
Kebutuhan untuk melihat data pembelian di 'DISTRIBUTOR' dari setiap pengajuan Operasional Proyek
*/
	CREATE OR REPLACE VIEW v_history_pembelian_operasionalProyek AS
	SELECT
		opr.id, opr.tgl, opr.nama, opr.total, opr.status_lunas,
		d.id ID_DISTRIBUTOR, d.nama NAMA_DISTRIBUTOR, d.pemilik
	FROM operasional_proyek opr 
	LEFT JOIN distributor d ON opr.id_distributor = d.id;
-- End View History Pembelian

/* VIEW EXPORT HISTORY PEMBELIAN
	Kebutuhan untuk export data history pembelian ke Excel
*/
	CREATE OR REPLACE VIEW v_history_pembelian_operasionalProyek_export AS
	SELECT
		opr.id 'ID', opr.tgl 'TANGGAL', opr.nama 'NAMA OPERASIONAL', opr.total 'TOTAL', opr.status_lunas 'STATUS PEMBAYARAN',
		d.id 'ID DISTRIBUTOR', d.nama 'NAMA DISTRIBUTOR', d.pemilik 'PEMILIK'
	FROM operasional_proyek opr 
	LEFT JOIN distributor d ON opr.id_distributor = d.id;
-- End View Export history pembelian

# End View Operasional Proyek #

# Procedure, Function, and Trigger Pengajuan Kas Kecil #

-- Tambah Data Pengajuan Kas Kecil
	DROP PROCEDURE IF EXISTS p_tambah_pengajuan_kas_kecil;
	delimiter //
	
	CREATE PROCEDURE p_tambah_pengajuan_kas_kecil(
		IN id_param varchar(50),
		IN id_kas_kecil_param varchar(10),
		IN tgl_param date,
		IN nama_param varchar(50),
		IN total_param double(12,2),
		IN status_param char(1),
		IN created_by_param varchar(50)
	)

	BEGIN

		-- insert ke pengajuan kas kecil
		INSERT into pengajuan_kas_kecil 
			(id, id_kas_kecil, tgl, nama, total, status, created_by, modified_by)
		VALUES
			(id_param, id_kas_kecil_param, tgl_param, nama_param, total_param, status_param, 
			created_by_param, created_by_param);
			
	END //

	delimiter ;
-- End Procedure Tambah Pengajuan Kas Kecil

-- Procedure Acc Pengajuan Kas Kecil
	DROP PROCEDURE IF EXISTS p_acc_pengajuan_kas_kecil;
	delimiter //
	
	CREATE PROCEDURE p_acc_pengajuan_kas_kecil(
		IN id_param varchar(50),
		IN id_kas_kecil_param varchar(50),
		IN tgl_param date,
		IN id_bank_param int,
		IN total_disetujui_param double(12,2),
		IN ket_kas_kecil_param text,
		IN ket_param text,
		IN status_param char(1),
		IN modified_by_param varchar(50)
	)
	BEGIN
		DECLARE saldo_kas_kecil double(12,2);
		DECLARE saldo_bank double(12,2);

		-- Get Saldo Kas Kecil
		SELECT saldo INTO saldo_kas_kecil FROM kas_kecil WHERE id = id_kas_kecil_param;

		-- Get Saldo Bank
		SELECT saldo INTO saldo_bank FROM bank WHERE id = id_bank_param;
		
		-- Pemindahan Saldo Dari Bank ke Saldo Kas Kecil
		UPDATE kas_kecil SET 
			saldo = saldo + total_disetujui_param,
			modified_by = modified_by_param 
		WHERE id = id_kas_kecil_param;

		UPDATE bank SET 
			saldo = saldo - total_disetujui_param,
			modified_by = modified_by_param 
		WHERE id = id_bank_param;
		
		-- Pencatatan Mutasi Kas Kecil
		INSERT INTO mutasi_saldo_kas_kecil
			(id_kas_kecil, tgl, uang_masuk, uang_keluar, saldo, ket, created_by, modified_by)
		VALUES
			(id_kas_kecil_param, tgl_param, total_disetujui_param, 0, (saldo_kas_kecil + total_disetujui_param), 
			ket_kas_kecil_param, modified_by_param, modified_by_param);

		-- Pencatatan Mutasi Bank
		INSERT INTO mutasi_bank
			(id_bank, tgl, uang_masuk, uang_keluar, saldo, ket, created_by, modified_by)
		VALUES
			(id_bank_param, tgl_param, 0, total_disetujui_param, (saldo_bank - total_disetujui_param), 
			ket_param, modified_by_param, modified_by_param); 

		-- Update Table Pengajuan Kas Kecil
		UPDATE pengajuan_kas_kecil SET 
			id_bank = id_bank_param, total_disetujui = total_disetujui_param, status = status_param,
			modified_by = modified_by_param
		WHERE id = id_param;

	END //

	delimiter ;
-- End Procedure Acc Pengajuan Kas Kecil

# Procedure, Function, and Trigger Pengajuan Kas Kecil #

# View Pengajuan Kas Kecil #

-- View Pengajuan Kas Kecil
	CREATE OR REPLACE VIEW v_pengajuan_kas_kecil AS
	SELECT
		pkk.id, pkk.nama, pkk.tgl, pkk.total, pkk.total_disetujui, pkk.status,
		kk.id id_kas_kecil, kk.nama nama_kas_kecil
	FROM pengajuan_kas_kecil pkk
	JOIN kas_kecil kk ON kk.id = pkk.id_kas_kecil;
-- End View Pengajuan Kas Kecil

-- View Pengajuan Kas Kecil (Export)
	CREATE OR REPLACE VIEW v_pengajuan_kas_kecil_export AS
	SELECT
		pkk.id 'ID PENGAJUAN', pkk.nama 'PENGAJUAN', pkk.tgl 'TANGGAL', pkk.total 'TOTAL PENGAJUAN', pkk.total_disetujui 'TOTAL DISETUJUI',
		pkk.status 'STATUS', kk.id, kk.nama 'KAS KECIL'
	FROM pengajuan_kas_kecil pkk
	JOIN kas_kecil kk ON kk.id = pkk.id_kas_kecil;
-- End View Pengajuan Kas Kecil (Export)

# End View Pengajuan Kas Kecil #

# Procedure, Function, and Trigger Pengajuan Sub Kas Kecil #

-- Procedure acc pengajuan sub kas kecil
	DROP PROCEDURE IF EXISTS p_acc_pengajuan_sub_kas_kecil;
	delimiter //
	
	CREATE PROCEDURE p_acc_pengajuan_sub_kas_kecil(
		in id_param varchar(50),
		in id_kas_kecil_param varchar(10),
		in tgl_param date,
		in dana_disetujui_param double(12,2),
		in status_param char(1),
		in modified_by_param varchar(50)
	)
	BEGIN

		DECLARE id_sub_kas_kecil_param varchar(10);
		DECLARE id_proyek_param varchar(50);
		DECLARE nama_param varchar(255);
		DECLARE get_saldo_kas_kecil double(12,2);
		DECLARE get_saldo_sub_kas_kecil double(12,2);
		DECLARE ket_kas_kecil_param text;
		DECLARE ket_sub_kas_kecil_param text;

		-- get id sub kas kecil
		SELECT id_sub_kas_kecil INTO id_sub_kas_kecil_param FROM pengajuan_sub_kas_kecil WHERE id = id_param;

		-- get id proyek dan nama pengajuan
		SELECT id_proyek INTO id_proyek_param FROM pengajuan_sub_kas_kecil WHERE id = id_param;
		SELECT nama INTO nama_param FROM pengajuan_sub_kas_kecil WHERE id = id_param;

		-- get saldo kas kecil dan saldo sub kas kecil
		SELECT saldo INTO get_saldo_kas_kecil FROM kas_kecil WHERE id = id_kas_kecil_param;
		SELECT saldo INTO get_saldo_sub_kas_kecil FROM sub_kas_kecil WHERE id = id_sub_kas_kecil_param;

		-- set keterangan mutasi kas kecil dan sub kas kecil
		SELECT CONCAT('PERSETUJUAN PENGAJUAN SUB KAS KECIL DI PROYEK (', id_proyek_param, ') - ', id_param, ': ',nama_param) INTO ket_kas_kecil_param;
		SELECT CONCAT('PERSETUJUAN PENGAJUAN ', id_param, ': ', nama_param) INTO ket_sub_kas_kecil_param;

		-- update saldo kas kecil
		UPDATE kas_kecil SET 
			saldo = (get_saldo_kas_kecil - dana_disetujui_param),
			modified_by = modified_by_param 
		WHERE id = id_kas_kecil_param;

		-- insert mutasi kas kecil
		INSERT INTO mutasi_saldo_kas_kecil 
			(id_kas_kecil, tgl, uang_masuk, uang_keluar, saldo, ket, created_by, modified_by) 
		VALUES 
			(id_kas_kecil_param, tgl_param, 0, dana_disetujui_param, (get_saldo_kas_kecil - dana_disetujui_param), 
			ket_kas_kecil_param, modified_by_param, modified_by_param);

		-- update pengajuan sub kas kecil
		UPDATE pengajuan_sub_kas_kecil 
		SET 
			dana_disetujui = dana_disetujui_param, 
			status = '3',
			status_laporan = '0',
			tgl_laporan = tgl_param,
			modified_by = modified_by_param
		WHERE id = id_param;

		-- update saldo sub kas kecil
		UPDATE sub_kas_kecil SET 
			saldo = (get_saldo_sub_kas_kecil + dana_disetujui_param),
			modified_by = modified_by_param 
		WHERE id = id_sub_kas_kecil_param;

		-- insert mutasi sub kas kecil
		INSERT INTO mutasi_saldo_sub_kas_kecil 
			(id_sub_kas_kecil, tgl, uang_masuk, uang_keluar, saldo, ket, created_by, modified_by) 
		VALUES 
			(id_sub_kas_kecil_param, tgl_param, dana_disetujui_param, 0, (get_saldo_sub_kas_kecil + dana_disetujui_param), 
			ket_sub_kas_kecil_param, modified_by_param, modified_by_param);

	END //

	delimiter ;
-- End Procedure acc pengajuan sub kas kecil

# End Procedure, Function, and Trigger Pengajuan Sub Kas Kecil #

# View Pengajuan Sub Kas Kecil #

-- View Pengajuan Sub Kas Kecil v2
    CREATE OR REPLACE VIEW v_pengajuan_sub_kas_kecil_v2 AS
    SELECT
        pskk.id, pskk.id_sub_kas_kecil, skk.nama nama_skk, pskk.tgl,
        pskk.id_proyek, p.pemilik, p.pembangunan,
        pskk.nama nama_pengajuan, pskk.total, pskk.dana_disetujui,
        (CASE 
            WHEN pskk.status = '1' THEN 'PENDING'
            WHEN pskk.status = '2' THEN 'PERBAIKI'
            WHEN pskk.status = '3' THEN 'DISETUJUI'
            WHEN pskk.status = '4' THEN 'LANGSUNG'
            ELSE 'DITOLAK' END
        ) status,
        (CASE 
            WHEN pskk.status_laporan = '1' THEN 'PENDING'
            WHEN pskk.status_laporan = '2' THEN 'PERBAIKI'
            WHEN pskk.status_laporan = '3' THEN 'DISETUJUI'
            ELSE 'BELUM DIKERJAKAN' END
        ) status_laporan, 
        pskk.tgl_laporan, pskk.status status_order, pskk.status_laporan status_laporan_order
    FROM pengajuan_sub_kas_kecil pskk
    JOIN proyek p ON p.id = pskk.id_proyek
    JOIN sub_kas_kecil skk ON skk.id = pskk.id_sub_kas_kecil;
-- End View Pengajuan Sub Kas Kecil v2

-- View Pengajuan Sub Kas Kecil
    CREATE OR REPLACE VIEW v_pengajuan_sub_kas_kecil_full AS
	SELECT
		pskk.id id_pengajuan, pskk.id_sub_kas_kecil, skk.nama nama_skk, pskk.id_proyek, pskk.tgl,
        pskk.nama nama_pengajuan, pskk.total, pskk.dana_disetujui, 
        (CASE 
            WHEN pskk.status = '1' THEN 'PENDING'
            WHEN pskk.status = '2' THEN 'PERBAIKI'
            WHEN pskk.status = '3' THEN 'DISETUJUI'
            WHEN pskk.status = '4' THEN 'LANGSUNG'
            ELSE 'DITOLAK' END
        ) status, 
        pskk.status_laporan,
		dp.id id_detail, dp.nama nama_detail, dp.jenis, dp.satuan, dp.qty, dp.harga, dp.subtotal,
		dp.harga_asli, dp.sisa, p.pemilik, p.pembangunan, p.kota
	FROM pengajuan_sub_kas_kecil pskk
	JOIN detail_pengajuan_sub_kas_kecil dp ON dp.id_pengajuan = pskk.id
	JOIN proyek p ON p.id = pskk.id_proyek
    JOIN sub_kas_kecil skk ON skk.id = pskk.id_sub_kas_kecil;
-- End View Pengajuan Sub Kas Kecil

-- View Pengajuan Sub Kas Kecil Export
    CREATE OR REPLACE VIEW v_pengajuan_sub_kas_kecil_export AS
    SELECT 
        id `ID PENGAJUAN`, 
        nama_pengajuan `NAMA PENGAJUAN`,
        pemilik `PEMILIK PROYEK`,
        pembangunan `NAMA PEMBANGUNAN PROYEK`,
        DATE_FORMAT(tgl, "%d/%m/%Y") `TANGGAL PENGAJUAN`,
        total `DANA DIAJUKAN`,
        status `STATUS PENGAJUAN`,
        dana_disetujui `DANA DISETUJUI`
    FROM `v_pengajuan_sub_kas_kecil_v2`
    ORDER BY tgl DESC;
-- End View Pengajuan Sub Kas Kecil Export

-- View Export Pengajuan Sub Kas Kecil
    CREATE OR REPLACE VIEW v_pengajuan_sub_kas_kecil_export_v2 AS
    SELECT 
        id `ID PENGAJUAN`, id_sub_kas_kecil `ID SUB KAS KECIL`, nama_skk `SUB KAS KECIL`, nama_pengajuan `NAMA PENGAJUAN`,
        id_proyek `ID PROYEK`, pemilik `PEMILIK PROYEK`, pembangunan `NAMA PEMBANGUNAN PROYEK`,
        tgl `TANGGAL PENGAJUAN`, total `DANA DIAJUKAN`, status `STATUS PENGAJUAN`, dana_disetujui `DANA DISETUJUI`
        -- , CONCAT(id_kas_kecil, ' - ', nama_kas_kecil) `DISETUJUI OLEH`
    FROM `v_pengajuan_sub_kas_kecil_v2`
    ORDER BY tgl DESC;
-- End Export View Pengajuan Sub Kas Kecil

-- View Export Detail Pengajuan Sub Kas Kecil
    CREATE OR REPLACE VIEW v_export_detail_pengajuan_skk AS
    SELECT
        pskk.id `ID PENGAJUAN`, pskk.id_sub_kas_kecil `ID SUB KAS KECIL`, pskk.nama_skk `SUB KAS KECIL`, 
        pskk.nama_pengajuan `NAMA PENGAJUAN`, pskk.tgl `TANGGAL PENGAJUAN`,
        dskk.nama `NAMA BARANG/BAHAN`,
        (CASE 
            WHEN dskk.jenis = 'T' THEN 'TEKNIS' 
            ELSE 'NON-TEKNIS'
        END) `JENIS`,
        dskk.satuan `SATUAN BARANG`, dskk.qty `QTY`, dskk.harga `HARGA SATUAN`, 
        dskk.subtotal `SUBTOTAL`, dskk.harga_asli `SUBTOTAL ASLI`, dskk.sisa `SISA`
    FROM detail_pengajuan_sub_kas_kecil dskk
    JOIN v_pengajuan_sub_kas_kecil_v2 pskk ON pskk.id = dskk.id_pengajuan;
-- End View Export Detail Pengajuan Sub Kas Kecil

# End View Pengajuan Sub Kas Kecil #


# Procedure, Function, and Trigger Proyek #

-- Procedure Tambah Proyek
	DROP PROCEDURE IF EXISTS p_tambah_proyek;
	delimiter //

	CREATE PROCEDURE p_tambah_proyek(
		in id_param varchar(50),
		in pemilik_param varchar(255),
		in tgl_param date,
		in pembangunan_param varchar(255),
		in luas_area_param double(10,2),
		in alamat_param text,
		in kota_param varchar(100),
		in estimasi_param smallint,
		in total_param double(12,2),
		in dp_param double(12,2),
		in cco_param double(12,2),
		in progress_param TINYINT,
		in status_param enum('SELESAI', 'BERJALAN'),
		in created_by_param varchar(50)
	)
	BEGIN

		INSERT INTO proyek 
			(id, pemilik, tgl, pembangunan, luas_area, alamat, kota, 
			estimasi, total, dp, cco, progress, status, created_by, modified_by)
		VALUES 
			(id_param, pemilik_param, tgl_param, pembangunan_param, luas_area_param, alamat_param, kota_param, 
			estimasi_param, total_param, dp_param, cco_param, progress_param, status_param, created_by_param, created_by_param);

	END //

	delimiter;
-- End Procedure Tambah Proyek

-- Procedure Edit Proyek
	DROP PROCEDURE IF EXISTS p_edit_proyek;
	delimiter //

	CREATE PROCEDURE p_edit_proyek(
		in id_param varchar(50),
		in pemilik_param varchar(255),
		in tgl_param date,
		in pembangunan_param varchar(255),
		in luas_area_param double(10,2),
		in alamat_param text,
		in kota_param varchar(100),
		in estimasi_param smallint,
		in total_param double(12,2),
		in dp_param double(12,2),
		in cco_param double(12,2),
		in progress_param int,
		in status_param enum('SELESAI', 'BERJALAN'),
		in modified_by_param varchar(50)
	)
	BEGIN

		UPDATE proyek SET 
			pemilik = pemilik_param, tgl = tgl_param, pembangunan = pembangunan_param, luas_area = luas_area_param,
			alamat = alamat_param, kota = kota_param, estimasi = estimasi_param, total = total_param,
			dp = dp_param, cco = cco_param, progress = progress_param, status = status_param, modified_by = modified_by_param
		WHERE id = id_param;

	END //

	delimiter;
-- End Procedure Edit Proyek

-- Procedure Hapus Data Proyek
	-- terdapat bug, jika menghapus data proyek langsung maka saldo tidak akan bisa dinormalisasi
	DROP PROCEDURE IF EXISTS p_hapus_proyek;
	delimiter //

	CREATE PROCEDURE p_hapus_proyek(
		in id_param varchar(50)
	)
	BEGIN

		-- 1. hapus semua data upload laporan
		DELETE FROM upload_laporan_pengajuan_sub_kas_kecil WHERE id_pengajuan IN (
			SELECT id FROM pengajuan_sub_kas_kecil WHERE id_proyek = id_param
		);

		-- 2. hapus semua data detail pengajuan sub kas kecil
		DELETE FROM detail_pengajuan_sub_kas_kecil WHERE id_pengajuan IN (
			SELECT id FROM pengajuan_sub_kas_kecil WHERE id_proyek = id_param
		);

		-- DELETE FROM detail_pengajuan_kas_kecil WHERE id_pengajuan_sub_kas_kecil IN (
		-- 	SELECT id FROM pengajuan_sub_kas_kecil WHERE id_proyek = id_param
		-- );

		-- 3. hapus semua data pengajuan sub kas kecil
		DELETE FROM pengajuan_sub_kas_kecil WHERE id_proyek = id_param;

		-- 4. hapus semua data detail operasional proyek
		DELETE FROM detail_operasional_proyek WHERE id_operasional_proyek IN (
			SELECT id FROM operasional_proyek WHERE id_proyek = id_param
		);

		-- 5. hapus semua data operasional proyek
		DELETE FROM operasional_proyek WHERE id_proyek = id_param;

		-- 6. hapus data logistik proyek (skk)
		DELETE FROM logistik_proyek WHERE id_proyek = id_param;

		-- 7. hapus data detail proyek
		DELETE FROM detail_proyek WHERE id_proyek = id_param;

		-- 8. hapus proyek
		DELETE FROM proyek WHERE id = id_param;

	END //

	delimiter ;
-- End Procedure Hapus Proyek

-- Procedure Tambah Detail SKK Proyek
	DROP PROCEDURE IF EXISTS p_tambah_detail_skk_proyek;
	delimiter //

	CREATE PROCEDURE p_tambah_detail_skk_proyek(
		in id_proyek_param varchar(50),
		in id_sub_kas_kecil_param varchar(10),
		in created_by_param varchar(50)
	)
	BEGIN

		INSERT INTO logistik_proyek 
			(id_proyek, id_sub_kas_kecil, created_by, modified_by) 
		VALUES 
			(id_proyek_param, id_sub_kas_kecil_param, created_by_param, created_by_param);

	END //

	delimiter ;
-- End Procedure Tambah Detail SKK Proyek

-- Procedure Edit Detail SKK Proyek
	DROP PROCEDURE IF EXISTS p_edit_detail_skk_proyek;
	delimiter //

	CREATE PROCEDURE p_edit_detail_skk_proyek(
		in id_param int,
		in id_proyek_param varchar(50),
		in id_sub_kas_kecil_param varchar(10),
		in modified_by_param varchar(50)
	)
	BEGIN

		UPDATE logistik_proyek SET
			id_proyek = id_proyek_param, id_sub_kas_kecil = id_sub_kas_kecil_param,
			modified_by = modified_by_param
		WHERE id = id_param;

	END //

	delimiter ;
-- End Procedure Edit Detail SKK Proyek

-- Procedure Hapus Detail SKK Proyek
	DROP PROCEDURE IF EXISTS p_hapus_detail_skk_proyek;
	delimiter //

	CREATE PROCEDURE p_hapus_detail_skk_proyek(
		in id_param int
	)
	BEGIN

		DELETE FROM logistik_proyek WHERE id = id_param;

	END //

	delimiter ;
-- End Procedure hapus Detail SKK Proyek

-- Procedure Tambah Data Detail Proyek
	DROP PROCEDURE IF EXISTS p_tambah_detail_proyek;
	delimiter //

	CREATE PROCEDURE p_tambah_detail_proyek(
		id_proyek_param varchar(50),
		id_bank_param int,
		tgl_param date,
		nama_param varchar(255),
		total_param double(12,2),
		is_DP_param char(1),
		ket_param text,
		created_by_param varchar(50)
	)
	BEGIN
		DECLARE get_saldo double(12,2);

		-- 1. insert detail proyek
		INSERT INTO detail_proyek 
			(id_proyek, id_bank, tgl, nama, total, is_DP, created_by, modified_by) 
		VALUES 
			(id_proyek_param, id_bank_param, tgl_param, nama_param, total_param, is_DP_param, created_by_param, created_by_param);

		-- 2. get saldo bank terakhir
		SELECT saldo INTO get_saldo FROM bank WHERE id = id_bank_param;

		-- 3. Update Saldo bank
		UPDATE bank SET 
			saldo = (get_saldo + total_param),
			modified_by = created_by_param 
		WHERE id = id_bank_param;

		-- 4. tambah mutasi bank
		INSERT INTO mutasi_bank 
			(id_bank, tgl, uang_masuk, uang_keluar, saldo, ket, created_by, modified_by)
		VALUES 
			(id_bank_param, tgl_param, total_param, 0, (get_saldo + total_param), 
			ket_param, created_by_param, created_by_param);

	END //

	delimiter ;
-- End Procedure Tambah Detail Proyek

-- Procedure Edit Data Detail Proyek
	DROP PROCEDURE IF EXISTS p_edit_detail_proyek;
	delimiter //
	
	CREATE PROCEDURE p_edit_detail_proyek(
		id_param int,
		id_proyek_param varchar(50),
		id_bank_param int,
		tgl_param date,
		nama_param varchar(255),
		total_param double(12,2),
		is_DP_param char(1),
		modified_by_param varchar(50)
	)
	BEGIN
		DECLARE nama_bank_baru varchar(50);
		DECLARE nama_bank_lama varchar(50);

		DECLARE get_bank_sebelum int;
		DECLARE get_total_sebelum double(12,2);

		DECLARE get_saldo_bank_lama double(12,2);
		DECLARE get_saldo_bank_baru double(12,2);

		DECLARE ket_param text;
		DECLARE ket_bank_lama_param text;
		DECLARE ket_bank_baru_param text;

		-- get id_bank dan total sebelum diedit
		SELECT id_bank INTO get_bank_sebelum FROM detail_proyek WHERE id = id_param;
		SELECT total INTO get_total_sebelum FROM detail_proyek WHERE id = id_param;

		-- update detail
		UPDATE detail_proyek SET
			id_bank = id_bank_param, tgl = tgl_param, nama = nama_param, total = total_param,
			is_DP = is_DP_param, modified_by = modified_by_param
		WHERE id = id_param;

		-- jika ada perubahan di bank
		IF get_bank_sebelum != id_bank_param THEN
			
			-- get saldo bank lama
			SELECT saldo INTO get_saldo_bank_lama FROM bank WHERE id = get_bank_sebelum;
			
			-- normalisasi saldo bank sebelum
			UPDATE bank SET 
				saldo = (get_saldo_bank_lama - get_total_sebelum),
				modified_by = modified_by_param
			WHERE id = get_bank_sebelum;

			-- get nama bank baru dan lama
			SELECT nama INTO nama_bank_baru FROM bank WHERE id = id_bank_param;
			SELECT nama INTO nama_bank_lama FROM bank WHERE id = id_bank_sebelum;

			-- set keterangan mutasi bank lama
			SELECT CONCAT('UANG KELUAR SEBESAR RP ', FORMAT(get_total_sebelum, 2, 'de_DE'), 
				' DIKARENAKAN TRANSAKSI DI PROYEK (', id_proyek_param, ') - ', nama_param, 
				' MELAKUKAN PERGANTIAN BANK KE ', nama_bank_baru) INTO ket_bank_lama_param;

			-- insert mutasi bank lama
			INSERT INTO mutasi_bank 
				(id_bank, tgl, uang_masuk, uang_keluar, saldo, ket, created_by, modified_by)
			VALUES 
				(get_bank_sebelum, tgl_param, 0, get_total_sebelum, (get_saldo_bank_lama - get_total_sebelum), 
				ket_bank_lama_param, modified_by_param, modified_by_param);

			-- get saldo bank baru
			SELECT saldo INTO get_saldo_bank_baru FROM bank WHERE id = id_bank_param;

			-- update saldo bank baru
			UPDATE bank SET 
				saldo = (get_saldo_bank_baru + total_param),
				modified_by = modified_by_param 
			WHERE id = id_bank_param;

			-- set keterangan mutasi bank baru
			SELECT CONCAT('UANG MASUK SEBESAR RP ', FORMAT(total_param, 2, 'de_DE'), 
				' DIKARENAKAN TRANSAKSI DI PROYEK (', id_proyek_param, ') - ', nama_param, 
				' MELAKUKAN PERGANTIAN BANK YANG SEBELUMNYA ', nama_bank_lama) INTO ket_bank_baru_param;

			-- insert mutasi bank baru
			INSERT INTO mutasi_bank 
				(id_bank, tgl, uang_masuk, uang_keluar, saldo, ket, created_by, modified_by)
			VALUES 
				(id_bank_param, tgl_param, total_param, 0, (get_saldo_bank_baru + total_param), 
				ket_bank_baru_param, modified_by_param, modified_by_param);
		ELSE
			-- jika bank sama
			-- jika ada perubahan di total
			IF get_total_sebelum != total_param THEN
				-- get saldo bank
				SELECT saldo INTO get_saldo_bank_baru FROM bank WHERE id = id_bank_param;

				-- normalisasi saldo
				UPDATE bank SET 
					saldo = (get_saldo_bank_baru + (total_param - get_total_sebelum)),
					modified_by = modified_by_param
				WHERE id = id_bank_param;
				
				IF total_param > get_total_sebelum THEN
					-- set keterangan mutasi
					SELECT CONCAT('UANG MASUK SEBESAR RP ', FORMAT((total_param - get_total_sebelum), 2, 'de_DE'), 
						' DIKARENAKAN ADANYA PERUBAHAN DATA DI PROYEK (', id_proyek_param, ') - ', nama_param) INTO ket_param;
					
					-- insert mutasi
					INSERT INTO mutasi_bank 
						(id_bank, tgl, uang_masuk, uang_keluar, saldo, ket, created_by, modified_by)
					VALUES 
						(id_bank_param, tgl_param, (total_param - get_total_sebelum), 0, (get_saldo_bank_baru + (total_param - get_total_sebelum)), 
						ket_param, modified_by_param, modified_by_param);
				ELSE
					IF total_param < get_total_sebelum THEN
						-- set keterangan mutasi
						SELECT CONCAT('UANG KELUAR SEBESAR RP ', FORMAT((get_total_sebelum - total_param), 2, 'de_DE'), 
							' DIKARENAKAN ADANYA PERUBAHAN DATA DI PROYEK (', id_proyek_param, ') - ', nama_param) INTO ket_param;
						
						-- insert mutasi
						INSERT INTO mutasi_bank 
							(id_bank, tgl, uang_masuk, uang_keluar, saldo, ket, created_by, modified_by)
						VALUES 
							(id_bank_param, tgl_param, 0, (get_total_sebelum - total_param), (get_saldo_bank_baru + (total_param - get_total_sebelum)), 
							ket_param, modified_by_param, modified_by_param);
					END IF;

				END IF;
		
			END IF;

		END IF;

	END //

	delimiter ;
-- End Procedure Edit Data Detail Proyek

-- Procedure Hapus Data Detail Proyek
	DROP PROCEDURE IF EXISTS p_hapus_detail_proyek;
	delimiter //
	
	CREATE PROCEDURE p_hapus_detail_proyek(
		id_param int,
		tgl_param date,
		modified_by_param varchar(50)
	)
	BEGIN
		DECLARE get_id_bank int;
		DECLARE get_id_proyek_param varchar(50);
		DECLARE get_saldo double(12,2);
		DECLARE get_tgl date;
		DECLARE get_nama varchar(255);
		DECLARE get_total double(12,2);
		DECLARE ket_param text;
		
		-- get data detail sebelum dihapus 
		SELECT id_proyek INTO get_id_proyek_param FROM detail_proyek WHERE id = id_param;
		SELECT id_bank INTO get_id_bank FROM detail_proyek WHERE id = id_param;
		SELECT tgl INTO get_tgl FROM detail_proyek WHERE id = id_param;
		SELECT nama INTO get_nama FROM detail_proyek WHERE id = id_param;
		SELECT total INTO get_total FROM detail_proyek WHERE id = id_param;

		-- set keterangan mutasi
		SELECT CONCAT('UANG KELUAR SEBESAR RP ', FORMAT((get_saldo - get_total), 2, 'de_DE'), 
			' DIKARENAKAN ADANYA PENGHAPUSAN DATA DI PROYEK (', get_id_proyek_param, ') - ', get_nama) INTO ket_param;

		-- get saldo terakhir
		SELECT saldo INTO get_saldo FROM bank WHERE id = get_id_bank;

		-- update saldo
		UPDATE bank SET 
			saldo = (get_saldo - get_total),
			modified_by = modified_by_param 
		WHERE id = get_id_bank;

		-- insert mutasi
		INSERT INTO mutasi_bank 
			(id_bank, tgl, uang_masuk, uang_keluar, saldo, ket, created_by, modified_by)
		VALUES 
			(get_id_bank, tgl_param, 0, get_total, (get_saldo - get_total), 
			ket_param, modified_by_param, modified_by_param);

		-- hapus detail
		DELETE FROM detail_proyek WHERE id = id_param;

	END //

	delimiter ;
-- End Procedure Hapus Data Detail Proyek

# End Procedure, Function, and Trigger Proyek #

# View Proyek #

-- View detail pembayaran proyek
    CREATE OR REPLACE VIEW v_detail_pembayaran_proyek AS
    SELECT
        dp.id, dp.id_proyek, dp.tgl, dp.nama, dp.total,
        dp.id_bank, b.nama nama_bank, dp.is_DP,
        (CASE WHEN dp.is_DP = '1' THEN 'YA' ELSE 'TIDAK' END) as DP
    FROM detail_proyek dp
    JOIN bank b ON b.id = dp.id_bank;
-- End View detail pembayaran proyek

-- View Get Sub Kas Kecil Proyek
    CREATE OR REPLACE VIEW v_get_skk_proyek AS
    SELECT
        lp.id, lp.id_proyek, skk.id id_skk, skk.nama
    FROM logistik_proyek lp
    JOIN sub_kas_kecil skk ON skk.id = lp.id_sub_kas_kecil;
-- End View Get Sub Kas Kecil Proyek

-- View get pengeluaran operasional proyek
    CREATE OR REPLACE VIEW v_get_pengeluaran_operasional_proyek AS
    SELECT 
        p.id id_proyek, SUM(dop.total) total, op.status status 
    FROM detail_operasional_proyek dop JOIN operasional_proyek op ON op.id = dop.id_operasional_proyek 
    JOIN proyek p ON p.id = op.id_proyek 
    GROUP BY p.id;
-- End View get pengeluran operasional proyek

-- View get pengeluaran sub kas kecil
    CREATE OR REPLACE VIEW v_get_pengeluaran_sub_kas_kecil AS
    SELECT 
        p.id AS id_proyek, SUM(dpskk.harga_asli) AS total 
    FROM detail_pengajuan_sub_kas_kecil dpskk 
    JOIN pengajuan_sub_kas_kecil pskk ON pskk.id = dpskk.id_pengajuan
    JOIN proyek p ON p.id = pskk.id_proyek
    GROUP BY p.id;
-- End View get pengeluaran sub kas kecil

-- View Logistik Proyek
    -- skc -> skk belum
    CREATE OR REPLACE VIEW v_proyek_logistik AS
    SELECT
        p.id id_proyek, p.pemilik, p.tgl, p.pembangunan, p.luas_area, p.alamat, p.kota, p.estimasi, p.total, p.dp, p.cco, p.status,
        lp.id id_logistik_proyek, skk.id id_sub_kas_kecil, skk.nama, skk.alamat alamat_skk, skk.no_telp, skk.email, skk.foto, skk.saldo, skk.status status_skk
    FROM proyek p
    JOIN logistik_proyek lp ON lp.id_proyek=p.id
    JOIN sub_kas_kecil skk ON skk.id=lp.id_sub_kas_kecil;
-- End View Logistik Proyek

-- View Export Proyek List
    CREATE OR REPLACE VIEW v_export_proyek_list AS
    SELECT 
        id ID, pemilik PEMILIK, tgl TANGGAL, pembangunan PEMBANGUNAN, luas_area AS 'LUAS AREA',
        alamat ALAMAT, kota KOTA, estimasi AS 'ESTIMASI (BULAN)', total AS 'TOTAL (Rp)',
        dp AS 'DP (Rp)', cco AS 'CCO (Rp)', progress AS 'PROGRESS (%)', status STATUS
    FROM proyek;
    -- WHERE tgl BETWEEN '1996-07-01' AND '1996-07-31'
-- End View Export Proyek Lits

-- View Export Proyek view detail
    CREATE OR REPLACE VIEW v_export_proyek_detail_full AS
    SELECT 
        -- proyek
        p.id AS 'ID PROYEK', p.pemilik PEMILIK, p.tgl TANGGAL, p.pembangunan PEMBANGUNAN, p.luas_area AS 'LUAS AREA',
        p.alamat ALAMAT, p.kota KOTA, p.estimasi AS 'ESTIMASI (BULAN)', p.total AS 'TOTAL (Rp)',
        p.dp AS 'DP (Rp)', p.cco AS 'CCO (Rp)', p.progress AS 'PROGRESS (%)', p.status STATUS,
        
        -- detail logistik proyek (skk)
        skk.id AS 'ID SUB KAS KECIL', skk.nama,
        
        -- detail proyek (pembayaran)
        dp.tgl AS 'TANGGAL PEMBAYARAN', dp.nama PEMBAYARAN, b.nama AS 'BANK', dp.total AS 'TOTAL PEMBAYARAN',
        (CASE WHEN dp.is_DP = '1' THEN 'YA' ELSE 'TIDAK' END) AS 'DP'
        
    FROM proyek p
    JOIN logistik_proyek lp ON lp.id_proyek = p.id
    JOIN sub_kas_kecil skk ON skk.id = lp.id_sub_kas_kecil
    JOIN detail_proyek dp ON dp.id_proyek = p.id
    JOIN bank b ON b.id = dp.id_bank;
    -- WHERE p.id = ''
-- End View Export View Detail

-- View Export Proyek detail pembayaran
    CREATE OR REPLACE VIEW v_export_proyek_detail_pembayaran AS
    SELECT 
        -- proyek
        p.id AS 'ID PROYEK', p.pemilik PEMILIK, p.tgl TANGGAL, p.pembangunan PEMBANGUNAN, 
        
        -- detail proyek (pembayaran)
        dp.tgl AS 'TANGGAL PEMBAYARAN', dp.nama PEMBAYARAN, b.nama AS 'BANK', dp.total AS 'TOTAL PEMBAYARAN',
        (CASE WHEN dp.is_DP = '1' THEN 'YA' ELSE 'TIDAK' END) AS 'DP'
        
    FROM proyek p
    JOIN detail_proyek dp ON dp.id_proyek = p.id
    JOIN bank b ON b.id = dp.id_bank;
-- End View Export Proyek detail pembayaran

-- View Export Proyek logistik (SKK)
    CREATE OR REPLACE VIEW v_export_proyek_logistik_skk AS
    SELECT 
        -- proyek
        p.id AS 'ID PROYEK', p.pemilik PEMILIK, p.tgl TANGGAL, p.pembangunan PEMBANGUNAN, 
        
        -- detail logistik proyek (skk)
        skk.id AS 'ID SUB KAS KECIL', skk.nama NAMA
        
    FROM proyek p
    JOIN logistik_proyek lp ON lp.id_proyek = p.id
    JOIN sub_kas_kecil skk ON skk.id = lp.id_sub_kas_kecil;
-- End View Export Proyek logistik (SKK)

# End View Proyek #

# Procedure, Function, and Trigger Sub Kas Kecil #

-- Procedure Tambah Data Sub Kas Kecil
	DROP PROCEDURE IF EXISTS p_tambah_sub_kas_kecil;
	delimiter //

	CREATE PROCEDURE p_tambah_sub_kas_kecil(
		in id_param varchar(10),
		in nama_param varchar(50),
		in alamat_param text,
		in no_telp_param varchar(20),
		in email_param varchar(50),
		in foto_param text,
		in saldo_param double(12,2),
		in tgl_param date,
		in status_param varchar(20),
		in password_param text,
		in level_param varchar(20),
		in created_by_param varchar(50)
	)
	BEGIN

		-- 1. insert ke user
		INSERT INTO user
			(username, password, level, status, created_by, modified_by)
		VALUES
			(email_param, password_param, level_param, 'AKTIF', created_by_param, created_by_param);

		-- insert ke sub kas kecil
		INSERT INTO sub_kas_kecil
			(id, nama, alamat, no_telp, email, foto, saldo, status, created_by, modified_by)
		VALUES
			(id_param, nama_param, alamat_param, no_telp_param,
			email_param, foto_param, saldo_param, status_param, created_by_param, created_by_param);

		-- insert mutasi awal
		INSERT INTO mutasi_saldo_sub_kas_kecil 
			(id_sub_kas_kecil, tgl, uang_masuk, uang_keluar, saldo, ket, created_by, modified_by)
		VALUES
			(id_param, tgl_param, saldo_param, 0, saldo_param, 'SALDO AWAL', 
			created_by_param, created_by_param);

	END //

	delimiter ;
-- End Procedure Tambah Sub Kas Kecil

-- Procedure Edit Sub Kas Kecil
	DROP PROCEDURE IF EXISTS p_edit_sub_kas_kecil;
	delimiter //

	CREATE PROCEDURE p_edit_sub_kas_kecil(
		in id_param varchar(10),
		in nama_param varchar(50),
		in alamat_param text,
		in no_telp_param varchar(20),
		in status_param enum('AKTIF', 'NONAKTIF'),
		in modified_by_param varchar(50)
	)
	BEGIN

		UPDATE sub_kas_kecil SET
			nama = nama_param,
			alamat = alamat_param,
			no_telp = no_telp_param,
			status = status_param,
			modified_by = modified_by_param
		WHERE id = id_param;

	END //

	delimiter ;
-- End Procedure Edit Sub Kas Kecil

-- Procedure Hapus Data Sub Kas Kecil
	DROP PROCEDURE IF EXISTS p_hapus_sub_kas_kecil;
	delimiter //

	CREATE PROCEDURE p_hapus_sub_kas_kecil(
		in id_param varchar(10)
	)
	BEGIN

		DECLARE email_param varchar(50);

		-- get email kas kecil
		SELECT email INTO email_param FROM sub_kas_kecil WHERE id = id_param;

		-- 1. hapus detail pengajuan sub kas kecil
		DELETE FROM detail_pengajuan_sub_kas_kecil WHERE id_pengajuan IN (
			SELECT id FROM pengajuan_sub_kas_kecil WHERE id_sub_kas_kecil = id_param
		);

		-- 2. hapus upload laporan pengajuan sub kas kecil
		DELETE FROM upload_laporan_pengajuan_sub_kas_kecil WHERE id_pengajuan IN (
			SELECT id FROM pengajuan_sub_kas_kecil WHERE id_sub_kas_kecil = id_param
		);

		-- DELETE FROM detail_pengajuan_kas_kecil WHERE id_pengajuan_sub_kas_kecil IN (
		-- 	SELECT id FROM pengajuan_sub_kas_kecil WHERE id_sub_kas_kecil = id_param
		-- );

		-- 3. hapus pengajuan sub kas kecil
		DELETE FROM pengajuan_sub_kas_kecil WHERE id_sub_kas_kecil = id_param;

		-- 4. hapus sub kas kecil
		DELETE FROM sub_kas_kecil WHERE id = id_param;

		-- 5. hapus user
		DELETE FROM user WHERE username = email_param;

	END //

	delimiter ;
-- End Procedure Hapus Sub Kas Kecil

-- Procedure Edit Status Sub Kas Kecil
	DROP PROCEDURE IF EXISTS p_edit_status_sub_kas_kecil;
	delimiter //
	
	CREATE PROCEDURE p_edit_status_sub_kas_kecil(
		in id_param varchar(10),
		in status_param varchar(20),
		in modified_by_param varchar(50)
	)
	BEGIN
		DECLARE status_lama_param varchar(20);
		DECLARE email_param varchar(50);

		-- get status kas besar yg lama
		SELECT status INTO status_lama_param FROM kas_kecil WHERE id = id_param;
		-- get email kas besar untuk update user
		SELECT email INTO email_param FROM kas_kecil WHERE id = id_param;

		-- jika ada perubahan status
		IF status_param != status_lama_param THEN
			-- update kas besar
			-- set status
			UPDATE kas_kecil SET 
				status = status_param, modified_by = modified_by_param 
			WHERE id = id_param;

			-- jika status berubah menjadi nonaktif
			IF status_param = 'NONAKTIF' THEN
				UPDATE user SET 
					status = 'NONAKTIF', modified_by = modified_by_param 
				WHERE username = email_param;
			ELSE -- jika status berubah menjadi aktif
				UPDATE user SET 
					status = 'AKTIF', modified_by = modified_by_param 
				WHERE username = email_param;
			END IF;

		END IF;

	END//
	delimiter ;
-- End Procedure Edit Status Sub Kas Kecil

# End Procedure, Function, and Trigger Sub Kas Kecil #

# View Sub Kas Kecil #

-- VIEW SUB KAS KECIL -> digunakan untuk mendapatkan informasi detail sub kas kecil
-- LEGEND : -> vp (VIEW PEMBANTU (tidak diakses oleh sistem, tapi diakses oleh view lain))

-- View Estimasi pengeluaran sub kas kecil
    -- untuk mendapatkan estimasi pengeluaran yang mungkin dilakukan oleh sub kas kecil
    CREATE OR REPLACE VIEW vp_estimasi_pengeluaran_skk AS
    SELECT 
	    id_sub_kas_kecil, sum(total) estimasi_pengeluaran_saldo
    FROM pengajuan_sub_kas_kecil
    WHERE (status=3 or status=4) AND (status_laporan=0 OR status_laporan=2)
    GROUP BY id_sub_kas_kecil;
-- End View Estiamsi pengeluaran sub kas kecil

-- View pembantu dana pengajuan sub kas kecil
    CREATE OR REPLACE VIEW vp_total_dana_pengajuan_skk as
    SELECT
        id_sub_kas_kecil, sum(total) as total
    FROM
        pengajuan_sub_kas_kecil
    WHERE
        status = 1
    GROUP BY id_sub_kas_kecil;
-- End View pembantu dana pengajuan sub kas kecil

-- View Sub Kas Kecil
    -- untuk mendapatkan informasi detai sub kas kecil
    CREATE OR REPLACE VIEW v_sub_kas_kecil AS
	SELECT
        skk.id, skk.nama, skk.alamat, skk.no_telp, skk.email, skk.foto, skk.status,
        COALESCE(skk.saldo,0) saldo,
        COALESCE(veps.estimasi_pengeluaran_saldo,0) estimasi_pengeluaran_saldo,
        (COALESCE(skk.saldo,0)-COALESCE(veps.estimasi_pengeluaran_saldo,0)) sisa_saldo,
        COALESCE(vptdp.total,0) total_pengajuan_pending
    FROM sub_kas_kecil skk
    LEFT JOIN vp_estimasi_pengeluaran_skk veps ON skk.id=veps.id_sub_kas_kecil
    LEFT JOIN vp_total_dana_pengajuan_skk vptdp ON skk.id=vptdp.id_sub_kas_kecil;
-- End View Sub Kas Kecil

-- View Sub Kas Kecil Export
    CREATE OR REPLACE VIEW v_sub_kas_kecil_export AS
    SELECT 
        id ID, nama NAMA, alamat ALAMAT, no_telp `NO TELP`, email EMAIL, saldo SALDO, status STATUS 
    FROM sub_kas_kecil; 
-- View End Sub Kas Kecil Export

# End View Sub Kas Kecil #

# Procedure, Function, and Trigger user #

-- Procedure Edit Status User
    DROP PROCEDURE IF EXISTS p_edit_status_user;
    delimiter //

    CREATE PROCEDURE p_edit_status_user (
        in username_param varchar(50),
        in status_param enum('AKTIF', 'NONAKTIF'),
        in modified_by_param varchar(50)
    )
    BEGIN
        
        UPDATE user SET
            status = status_param,
            modified_by = modified_by_param
        WHERE username = username_param;

    END //

    delimiter ;
-- End Procedure Edit Status User

# End Procedure, Function, and Trigger user #

# View User #
-- View User
	-- view untuk semua user yang terdapat di sistem
	CREATE OR REPLACE VIEW v_all_user AS

	SELECT
		u.username, o.nama, u.status, u.level 
	FROM user u 
	JOIN owner o ON u.username = o.email

	UNION

	SELECT
		u.username, kb.nama, u.status, u.level 
	FROM user u 
	JOIN kas_besar kb ON u.username = kb.email

	UNION

	SELECT
		u.username, kk.nama, u.status, u.level 
	FROM user u 
	JOIN kas_kecil kk ON u.username = kk.email

	UNION

	SELECT
		u.username, skk.nama, u.status, u.level 
	FROM user u 
	JOIN sub_kas_kecil skk ON u.username = skk.email;
-- End View User

-- View user owner
	CREATE OR REPLACE VIEW v_user_owner AS
	SELECT
		u.username, o.id, o.nama, o.alamat, o.no_telp, o.email, o.foto, o.status
	FROM user u
	JOIN owner o ON o.email = u.username;
-- End View user owner

-- View user kas besar
	CREATE OR REPLACE VIEW v_user_kas_besar AS
	SELECT
		u.username, kb.id, kb.nama, kb.alamat, kb.no_telp, kb.email, kb.foto, kb.status
	FROM user u
	JOIN kas_besar kb ON kb.email = u.username;
-- End View user kas besar

-- View user kas kecil
	CREATE OR REPLACE VIEW v_user_kas_kecil AS
	SELECT
		u.username, kk.id, kk.nama, kk.alamat, kk.no_telp, kk.email, kk.foto, kk.status,
		kk.saldo
	FROM user u
	JOIN kas_kecil kk ON kk.email = u.username;
-- End View user kas kecil

-- View user sub kas kecil
	CREATE OR REPLACE VIEW v_user_sub_kas_kecil AS
	SELECT
		u.username, skk.id, skk.nama, skk.alamat, skk.no_telp, skk.email, skk.foto, skk.status,
		skk.saldo
	FROM user u
	JOIN sub_kas_kecil skk ON skk.email = u.username;
-- End View user sub kas kecil

# End View User #

# Seeder level lookup #

INSERT INTO level_lookup (
    id, name, description
) VALUES 
(1, 'OWNER', NULL),
(2, 'KAS BESAR', NULL),
(3, 'KAS KECIL', NULL),
(4, 'SUB KAS KECIL', NULL);

# End Seeder level lookup #

# Seeder active status lookup #

INSERT INTO active_status_lookup (
    id, name, description
) VALUES 
(1, 'AKTIF', NULL),
(2, 'NONAKTIF', NULL);

# End Seeder active status lookup #

# Seeder Permission lookup #

INSERT INTO permission_lookup (
    id, name, description
) VALUES 
(1, 'CREATE', NULL),
(2, 'READ', NULL),
(3, 'UPDATE', NULL),
(4, 'DELETE', NULL),
(5, 'UPDATE STATUS', NULL),
(6, 'UPDATE IMAGE', NULL),
(7, 'UPDATE PASSWORD', NULL),
(8, 'RESET PASSWORD', NULL),
(9, 'EXPORT', NULL);

# End Seeder Permission lookup #

# Seeder User #

-- Password: ABCDE

-- Owner
INSERT INTO user (username, password, level, status) VALUES 
('owner1@69designbuild.com', '$2y$10$xGiq.6J6z9CUeze4B3oqAOquc6hXvYvZehkYV1brgWYrxjpoG5fGG', 'OWNER', 'AKTIF'),
('owner2@69designbuild.com', '$2y$10$xGiq.6J6z9CUeze4B3oqAOquc6hXvYvZehkYV1brgWYrxjpoG5fGG', 'OWNER', 'AKTIF');

INSERT INTO owner (id, nama, email, status)
VALUES 
('OW001', 'OWNER 1', 'owner1@69designbuild.com', 'AKTIF'),
('OW002', 'OWNER 2', 'owner2@69designbuild.com', 'AKTIF');

-- INSERT INTO user (username, password, level, status) VALUES 
-- ('owner1@69designbuild.com', '$2y$10$xGiq.6J6z9CUeze4B3oqAOquc6hXvYvZehkYV1brgWYrxjpoG5fGG', 1, 1),
-- ('owner2@69designbuild.com', '$2y$10$xGiq.6J6z9CUeze4B3oqAOquc6hXvYvZehkYV1brgWYrxjpoG5fGG', 1, 1);

-- Kas Besar
CALL p_tambah_kas_besar ('KB001', 'KAS BESAR 1', NULL, NULL, 'kas_besar1@69designbuild.com', NULL, 'AKTIF', '$2y$10$xGiq.6J6z9CUeze4B3oqAOquc6hXvYvZehkYV1brgWYrxjpoG5fGG', 'KAS BESAR', NULL);
CALL p_tambah_kas_besar ('KB002', 'KAS BESAR 2', NULL, NULL, 'kas_besar2@69designbuild.com', NULL, 'AKTIF', '$2y$10$xGiq.6J6z9CUeze4B3oqAOquc6hXvYvZehkYV1brgWYrxjpoG5fGG', 'KAS BESAR', NULL);
CALL p_tambah_kas_besar ('KB003', 'KAS BESAR 3', NULL, NULL, 'kas_besar3@69designbuild.com', NULL, 'AKTIF', '$2y$10$xGiq.6J6z9CUeze4B3oqAOquc6hXvYvZehkYV1brgWYrxjpoG5fGG', 'KAS BESAR', NULL);

-- CALL p_tambah_kas_besar ('KB001', 'KAS BESAR 1', NULL, NULL, 'kas_besar1@69designbuild.com', NULL, 1, '$2y$10$xGiq.6J6z9CUeze4B3oqAOquc6hXvYvZehkYV1brgWYrxjpoG5fGG', 2, NULL);
-- CALL p_tambah_kas_besar ('KB002', 'KAS BESAR 2', NULL, NULL, 'kas_besar2@69designbuild.com', NULL, 1, '$2y$10$xGiq.6J6z9CUeze4B3oqAOquc6hXvYvZehkYV1brgWYrxjpoG5fGG', 2, NULL);
-- CALL p_tambah_kas_besar ('KB003', 'KAS BESAR 3', NULL, NULL, 'kas_besar3@69designbuild.com', NULL, 1, '$2y$10$xGiq.6J6z9CUeze4B3oqAOquc6hXvYvZehkYV1brgWYrxjpoG5fGG', 2, NULL);

-- Kas Kecil
CALL p_tambah_kas_kecil ('KK001', 'KAS KECIL 1', NULL, NULL, 'kas_kecil1@69designbuild.com', NULL, 100000, CURRENT_DATE, 'AKTIF', '$2y$10$xGiq.6J6z9CUeze4B3oqAOquc6hXvYvZehkYV1brgWYrxjpoG5fGG', 'KAS KECIL', NULL);
CALL p_tambah_kas_kecil ('KK002', 'KAS KECIL 2', NULL, NULL, 'kas_kecil2@69designbuild.com', NULL, 200000, CURRENT_DATE, 'AKTIF', '$2y$10$xGiq.6J6z9CUeze4B3oqAOquc6hXvYvZehkYV1brgWYrxjpoG5fGG', 'KAS KECIL', NULL);
CALL p_tambah_kas_kecil ('KK003', 'KAS KECIL 3', NULL, NULL, 'kas_kecil3@69designbuild.com', NULL, 300000, CURRENT_DATE, 'AKTIF', '$2y$10$xGiq.6J6z9CUeze4B3oqAOquc6hXvYvZehkYV1brgWYrxjpoG5fGG', 'KAS KECIL', NULL);
CALL p_tambah_kas_kecil ('KK004', 'KAS KECIL 4', NULL, NULL, 'kas_kecil4@69designbuild.com', NULL, 400000, CURRENT_DATE, 'AKTIF', '$2y$10$xGiq.6J6z9CUeze4B3oqAOquc6hXvYvZehkYV1brgWYrxjpoG5fGG', 'KAS KECIL', NULL);
CALL p_tambah_kas_kecil ('KK005', 'KAS KECIL 5', NULL, NULL, 'kas_kecil5@69designbuild.com', NULL, 500000, CURRENT_DATE, 'AKTIF', '$2y$10$xGiq.6J6z9CUeze4B3oqAOquc6hXvYvZehkYV1brgWYrxjpoG5fGG', 'KAS KECIL', NULL);

-- CALL p_tambah_kas_kecil ('KK001', 'KAS KECIL 1', NULL, NULL, 'kas_kecil1@69designbuild.com', NULL, 100000, CURRENT_DATE, 1, '$2y$10$xGiq.6J6z9CUeze4B3oqAOquc6hXvYvZehkYV1brgWYrxjpoG5fGG', 3, NULL);
-- CALL p_tambah_kas_kecil ('KK002', 'KAS KECIL 2', NULL, NULL, 'kas_kecil2@69designbuild.com', NULL, 200000, CURRENT_DATE, 1, '$2y$10$xGiq.6J6z9CUeze4B3oqAOquc6hXvYvZehkYV1brgWYrxjpoG5fGG', 3, NULL);
-- CALL p_tambah_kas_kecil ('KK003', 'KAS KECIL 3', NULL, NULL, 'kas_kecil3@69designbuild.com', NULL, 300000, CURRENT_DATE, 1, '$2y$10$xGiq.6J6z9CUeze4B3oqAOquc6hXvYvZehkYV1brgWYrxjpoG5fGG', 3, NULL);
-- CALL p_tambah_kas_kecil ('KK004', 'KAS KECIL 4', NULL, NULL, 'kas_kecil4@69designbuild.com', NULL, 400000, CURRENT_DATE, 1, '$2y$10$xGiq.6J6z9CUeze4B3oqAOquc6hXvYvZehkYV1brgWYrxjpoG5fGG', 3, NULL);
-- CALL p_tambah_kas_kecil ('KK005', 'KAS KECIL 5', NULL, NULL, 'kas_kecil5@69designbuild.com', NULL, 500000, CURRENT_DATE, 1, '$2y$10$xGiq.6J6z9CUeze4B3oqAOquc6hXvYvZehkYV1brgWYrxjpoG5fGG', 3, NULL);

-- Sub Kas Kecil
CALL p_tambah_sub_kas_kecil ('SKK001', 'SUB KAS KECIL 1', NULL, NULL, 'sub_kas_kecil1@69designbuild.com', NULL, 50000, CURRENT_DATE, 'AKTIF', '$2y$10$xGiq.6J6z9CUeze4B3oqAOquc6hXvYvZehkYV1brgWYrxjpoG5fGG', 'SUB KAS KECIL', NULL);
CALL p_tambah_sub_kas_kecil ('SKK002', 'SUB KAS KECIL 2', NULL, NULL, 'sub_kas_kecil2@69designbuild.com', NULL, 75000, CURRENT_DATE, 'AKTIF', '$2y$10$xGiq.6J6z9CUeze4B3oqAOquc6hXvYvZehkYV1brgWYrxjpoG5fGG', 'SUB KAS KECIL', NULL);
CALL p_tambah_sub_kas_kecil ('SKK003', 'SUB KAS KECIL 3', NULL, NULL, 'sub_kas_kecil3@69designbuild.com', NULL, 100000, CURRENT_DATE, 'AKTIF', '$2y$10$xGiq.6J6z9CUeze4B3oqAOquc6hXvYvZehkYV1brgWYrxjpoG5fGG', 'SUB KAS KECIL', NULL);
CALL p_tambah_sub_kas_kecil ('SKK004', 'SUB KAS KECIL 4', NULL, NULL, 'sub_kas_kecil4@69designbuild.com', NULL, 125000, CURRENT_DATE, 'AKTIF', '$2y$10$xGiq.6J6z9CUeze4B3oqAOquc6hXvYvZehkYV1brgWYrxjpoG5fGG', 'SUB KAS KECIL', NULL);
CALL p_tambah_sub_kas_kecil ('SKK005', 'SUB KAS KECIL 5', NULL, NULL, 'sub_kas_kecil5@69designbuild.com', NULL, 150000, CURRENT_DATE, 'AKTIF', '$2y$10$xGiq.6J6z9CUeze4B3oqAOquc6hXvYvZehkYV1brgWYrxjpoG5fGG', 'SUB KAS KECIL', NULL);

-- CALL p_tambah_sub_kas_kecil ('SKK001', 'SUB KAS KECIL 1', NULL, NULL, 'sub_kas_kecil1@69designbuild.com', NULL, 50000, CURRENT_DATE, 1, '$2y$10$xGiq.6J6z9CUeze4B3oqAOquc6hXvYvZehkYV1brgWYrxjpoG5fGG', 4, NULL);
-- CALL p_tambah_sub_kas_kecil ('SKK002', 'SUB KAS KECIL 2', NULL, NULL, 'sub_kas_kecil2@69designbuild.com', NULL, 75000, CURRENT_DATE, 1, '$2y$10$xGiq.6J6z9CUeze4B3oqAOquc6hXvYvZehkYV1brgWYrxjpoG5fGG', 4, NULL);
-- CALL p_tambah_sub_kas_kecil ('SKK003', 'SUB KAS KECIL 3', NULL, NULL, 'sub_kas_kecil3@69designbuild.com', NULL, 100000, CURRENT_DATE, 1, '$2y$10$xGiq.6J6z9CUeze4B3oqAOquc6hXvYvZehkYV1brgWYrxjpoG5fGG', 4, NULL);
-- CALL p_tambah_sub_kas_kecil ('SKK004', 'SUB KAS KECIL 4', NULL, NULL, 'sub_kas_kecil4@69designbuild.com', NULL, 125000, CURRENT_DATE, 1, '$2y$10$xGiq.6J6z9CUeze4B3oqAOquc6hXvYvZehkYV1brgWYrxjpoG5fGG', 4, NULL);
-- CALL p_tambah_sub_kas_kecil ('SKK005', 'SUB KAS KECIL 5', NULL, NULL, 'sub_kas_kecil5@69designbuild.com', NULL, 150000, CURRENT_DATE, 1, '$2y$10$xGiq.6J6z9CUeze4B3oqAOquc6hXvYvZehkYV1brgWYrxjpoG5fGG', 4, NULL);

# End Seeder User #

# Seeder Menu #

INSERT INTO menu (
    id, name, table_name, url, class, icon, position
) VALUES 
(1, 'Beranda', NULL, 'home', 'menu-home', 'fa fa-dashboard', 1),
(2, 'Bank', 'bank', 'bank', 'menu-bank', 'fa fa-bank', 2),
(3, 'Ditributor', 'distributor', 'distributor', 'menu-distributor', 'fa fa-truck', 3),
(4, 'Proyek', 'proyek', 'proyek', 'menu-proyek', 'fa fa-cubes', 4),
(5, 'Operasional Proyek', 'operasional_proyek', 'operasional-proyek', 'menu-operasional-proyek', 'fa fa-usd', 5),
(6, 'Operasional', 'operasional', 'operasional', 'menu-operasional', 'fa fa-usd', 6),
(7, 'Pengajuan Kas Kecil', 'pengajuan_kas_kecil', 'pengajuan-kas-kecil', 'menu-pengajuan-kas-kecil', 'fa fa-pencil-square-o', 7),
(8, 'Pengajuan Sub Kas Kecil', 'pengajuan_sub_kas_kecil', 'pengajuan-sub-kas-kecil', 'menu-pengajuan-sub-kas-kecil', 'fa fa-pencil-square-o', 8),
(9, 'Laporan Pengajuan SKK', 'pengajuan_sub_kas_kecil', 'laporan-sub-kas-kecil', 'menu-laporan-sub-kas-kecil', 'fa fa-check-square-o', 9),
(10, 'Saldo Kas Kecil', 'mutasi_saldo_kas_kecil', 'saldo-kas-kecil', 'menu-saldo-kas-kecil', 'fa fa-usd', 10),
(11, 'Kas Besar', 'kas_besar', 'kas-besar', 'menu-kas-besar', 'fa fa-user-plus', 11),
(12, 'Kas Kecil', 'kas_kecil', 'kas-kecil', 'menu-kas-kecil', 'fa fa-user', 12),
(13, 'Sub Kas Kecil', 'sub_kas_kecil', 'sub-kas-kecil', 'menu-sub-kas-kecil', 'fa fa-users', 13),
(14, 'User', 'user', 'user', 'menu-user', 'fa fa-users', 14),
(15, 'Profile', NULL, NULL, NULL, NULL, NULL);

# End Seeder Menu #

# Seeder Detail Menu #

# End Seeder Detail Menu #

# Seeder Access Menu #

INSERT INTO access_menu (
    id, level_id, menu_id
)
VALUES 

-- Owner
(1, 1, 1),
(2, 1, 2),
(3, 1, 3),
(4, 1, 4),
(5, 1, 5),
(6, 1, 6),
(7, 1, 7),
(8, 1, 8),
(9, 1, 9),
(10, 1, 10),
(11, 1, 11),
(12, 1, 12),
(13, 1, 13),
(14, 1, 14),

-- Kas Besar
(15, 2, 1),
(16, 2, 2),
(17, 2, 3),
(18, 2, 4),
(19, 2, 5),
(20, 2, 6),
(21, 2, 7),
(22, 2, 11),
(23, 2, 12),
(24, 2, 13),
(25, 2, 14),

-- Kas Kecil
(26, 3, 1),
(27, 3, 4),
(28, 3, 7),
(29, 3, 8),
(30, 3, 9),
(31, 3, 10),
(32, 3, 12),
(33, 3, 13),
(34, 3, 14);

# End Seeder Access Menu #

# Seeder Role Permission #

# End Seeder Role Permission #

# Seeder Increment #

INSERT INTO increment (id, menu_id, mask, last_increment) VALUES 
(1, 3, 'DIS-0001', 6),
(2, 4, 'PRY-yyyy-0001', 6),
(3, 5, 'OPR-yyyy-{PRY0001}-0001', 0),
(4, 6, 'OPR-yyyy-0001', 0),
(5, 7, 'PKK-yyyy-0001', 0),
(6, 8, 'PSKK-yyyy-0001', 0),
(7, 10, 'KB-001', 4),
(8, 11, 'KK-001', 6),
(9, 12, 'SKK-001', 6);

# End Seeder Increment #

# Seeder Bank #

CALL p_tambah_bank ('BCA', 57500750, 'AKTIF', NULL);
CALL p_tambah_bank ('MANDIRI', 7952500, 'AKTIF', NULL);
CALL p_tambah_bank ('BNI', 11440000, 'AKTIF', NULL);
CALL p_tambah_bank ('BRI', 850000, 'AKTIF', NULL);
CALL p_tambah_bank ('CIMB NIAGA', 27828000, 'AKTIF', NULL);

-- CALL p_tambah_bank ('BCA', 57500750, 1, NULL);
-- CALL p_tambah_bank ('MANDIRI', 7952500, 1, NULL);
-- CALL p_tambah_bank ('BNI', 11440000, 1, NULL);
-- CALL p_tambah_bank ('BRI', 850000, 1, NULL);
-- CALL p_tambah_bank ('CIMB NIAGA', 27828000, 1, NULL);

# End Seeder Bank #

# Seeder Proyek #

-- Proyek
CALL p_tambah_proyek ('PRY20170001', 'UDIN TEMPEST', '2017-04-01', 'RUMAH TINGKAT 2', 120, 'Padalarang', 'Cianjur', 0, '', '', '', '', 'BERJALAN', NULL);
-- CALL p_tambah_proyek ('PRY20170002', 'MULI IMRON', '2017-06-01', 'KOSTAN 10 KAMAR', 0, NULL, NULL, 0, '', '', '', '', '', NULL);
-- CALL p_tambah_proyek ('PRY20170003', 'BIMO MURE', '2017-09-01', 'APARTEMENT 100 KAMAR', 0, NULL, NULL, 0, '', '', '', '', '', NULL);
-- CALL p_tambah_proyek ('PRY20180001', 'JAKA PRATAMA', '2018-01-01', 'LAPANGAN FUTSAL', 0, NULL, NULL, 0, '', '', '', '', '', NULL);
-- CALL p_tambah_proyek ('PRY20180002', 'FAJAR CAHYO', '2018-03-01', 'PETERNAKAN MODERN', 0, NULL, NULL, 0, '', '', '', '', '', NULL);
-- CALL p_tambah_proyek ('PRY20180003', 'AINA NEVA', '2018-05-01', 'KANTOR 2 LANTAI', 0, NULL, NULL, 0, '', '', '', '', '', NULL);
-- CALL p_tambah_proyek ('PRY20180004', 'SERA INDRI', '2018-05-01', 'RUKO', 0, NULL, NULL, 0, '', '', '', '', '', NULL);
-- CALL p_tambah_proyek ('PRY20190001', 'RAMADAN SAPUTRA', '2019-01-01', 'RUMAH LANTAI 1', 0, NULL, NULL, 0, '', '', '', '', '', NULL);
-- CALL p_tambah_proyek ('PRY20190002', 'LILI SYARIPUDIN', '2019-01-01', 'RENOVASI RUMAH', 0, NULL, NULL, 0, '', '', '', '', '', NULL);
-- CALL p_tambah_proyek ('PRY20190003', 'RIFKA DIANA', '2019-02-01', 'RUKO', 0, NULL, NULL, 0, '', '', '', '', '', NULL);
-- CALL p_tambah_proyek ('PRY20190004', 'DWI PUTRI', '2019-03-01', 'KOSTAN 15 KAMAR', 0, NULL, NULL, 0, '', '', '', '', '', NULL);
-- CALL p_tambah_proyek ('PRY20190005', 'ABQORI GUNAWAN', CURRENT_DATE, 'RUMAH TINGKAT 3', 0, NULL, NULL, 0, '', '', '', '', '', NULL);

-- -- SKK Proyek
CALL p_tambah_detail_skk_proyek ('PRY20170001','SKK001','kas_besar1@69designbuild.com');
-- CALL p_tambah_detail_skk_proyek ('PRY20170001');

-- CALL p_tambah_detail_skk_proyek ('PRY20170002');
-- CALL p_tambah_detail_skk_proyek ('PRY20170002');
-- CALL p_tambah_detail_skk_proyek ('PRY20170002');
-- CALL p_tambah_detail_skk_proyek ('PRY20170002');

-- CALL p_tambah_detail_skk_proyek ('PRY20170003');

-- CALL p_tambah_detail_skk_proyek ('PRY20180001');
-- CALL p_tambah_detail_skk_proyek ('PRY20180001');

-- CALL p_tambah_detail_skk_proyek ('PRY20180002');

-- CALL p_tambah_detail_skk_proyek ('PRY20180003');

-- CALL p_tambah_detail_skk_proyek ('PRY20180004');
-- CALL p_tambah_detail_skk_proyek ('PRY20180004');

-- CALL p_tambah_detail_skk_proyek ('PRY20190001');
-- CALL p_tambah_detail_skk_proyek ('PRY20190001');
-- CALL p_tambah_detail_skk_proyek ('PRY20190001');

-- CALL p_tambah_detail_skk_proyek ('PRY20190002');

-- CALL p_tambah_detail_skk_proyek ('PRY20190003');

-- CALL p_tambah_detail_skk_proyek ('PRY20190004');
-- CALL p_tambah_detail_skk_proyek ('PRY20190004');
-- CALL p_tambah_detail_skk_proyek ('PRY20190004');
-- CALL p_tambah_detail_skk_proyek ('PRY20190004');

-- CALL p_tambah_detail_skk_proyek ('PRY20190005');
-- CALL p_tambah_detail_skk_proyek ('PRY20190005');

-- -- Detail Proyek
CALL p_tambah_detail_proyek ('PRY20170001',1,'2019-06-17 21:22:46','DP',1000,'1','hehe','kas_besar1@69designbuild.com');
-- CALL p_tambah_detail_proyek ('PRY20170001');

-- CALL p_tambah_detail_proyek ('PRY20170002');
-- CALL p_tambah_detail_proyek ('PRY20170002');
-- CALL p_tambah_detail_proyek ('PRY20170002');
-- CALL p_tambah_detail_proyek ('PRY20170002');

-- CALL p_tambah_detail_proyek ('PRY20170003');
-- CALL p_tambah_detail_proyek ('PRY20170003');
-- CALL p_tambah_detail_proyek ('PRY20170003');

-- CALL p_tambah_detail_proyek ('PRY20180001');
-- CALL p_tambah_detail_proyek ('PRY20180001');
-- CALL p_tambah_detail_proyek ('PRY20180001');

-- CALL p_tambah_detail_proyek ('PRY20180002');
-- CALL p_tambah_detail_proyek ('PRY20180002');

-- CALL p_tambah_detail_proyek ('PRY20180003');
-- CALL p_tambah_detail_proyek ('PRY20180003');
-- CALL p_tambah_detail_proyek ('PRY20180003');
-- CALL p_tambah_detail_proyek ('PRY20180003');

-- CALL p_tambah_detail_proyek ('PRY20180004');
-- CALL p_tambah_detail_proyek ('PRY20180004');

-- CALL p_tambah_detail_proyek ('PRY20190001');
-- CALL p_tambah_detail_proyek ('PRY20190001');
-- CALL p_tambah_detail_proyek ('PRY20190001');
-- CALL p_tambah_detail_proyek ('PRY20190001');
-- CALL p_tambah_detail_proyek ('PRY20190001');

-- CALL p_tambah_detail_proyek ('PRY20190002');
-- CALL p_tambah_detail_proyek ('PRY20190002');
-- CALL p_tambah_detail_proyek ('PRY20190002');

-- CALL p_tambah_detail_proyek ('PRY20190003');
-- CALL p_tambah_detail_proyek ('PRY20190003');
-- CALL p_tambah_detail_proyek ('PRY20190003');
-- CALL p_tambah_detail_proyek ('PRY20190003');

-- CALL p_tambah_detail_proyek ('PRY20190004');
-- CALL p_tambah_detail_proyek ('PRY20190004');

# End Seeder Proyek #

