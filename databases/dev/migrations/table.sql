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
            ket TEXT DEFAULT NULL,

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