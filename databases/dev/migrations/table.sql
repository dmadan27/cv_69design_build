# Database 69design-build_dev #
# 69design-build_dev #
# 1.1 #

# Local Development Only

-- Remove commentary in you want build database from zero
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
            level_id INT UNSIGNED DEFAULT NULL, -- fk level lookup
            status_id INT UNSIGNED DEFAULT NULL, -- fk active status lookup
            -- image text, -- optional, comentary if the image of user not contain in user table

            created_on DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            modified_on DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            created_by INT UNSIGNED DEFAULT NULL, -- who created first
            modified_by INT UNSIGNED DEFAULT NULL, -- who last edit

            CONSTRAINT pk_user_username PRIMARY KEY(username),
            CONSTRAINT fk_user_level_id FOREIGN KEY(level_id) REFERENCES level_lookup(id)
                ON DELETE RESTRICT ON UPDATE CASCADE,
            CONSTRAINT fk_user_status_id FOREIGN KEY(status_id) REFERENCES active_status_lookup(id)
                ON DELETE RESTRICT ON UPDATE CASCADE,
            CONSTRAINT fk_user_created_by FOREIGN KEY(created_by) REFERENCES user(username)
                ON DELETE SET NULL ON UPDATE CASCADE,
            CONSTRAINT fk_user_modified_by FOREIGN KEY(modified_by) REFERENCES user(username)
                ON DELETE SET NULL ON UPDATE CASCADE
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

            user_id INT UNSIGNED DEFAULT NULL, -- fk user
            menu_permission_id INT UNSIGNED DEFAULT NULL, -- fk menu detail

            created_on DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            modified_on DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

            CONSTRAINT pk_role_permission_id PRIMARY KEY(id),
            CONSTRAINT fk_role_permission_user_id FOREIGN KEY(user_id) REFERENCES user(id)
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
            description TEXT,

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

    -- Table Jenis Uang Lookup
    -- End Table Jenis Uang Lookup

    -- Table Jenis Operasional Lookup
    -- End Table Jenis Operasional Lookup

    -- Table Jenis Pembayaran Lookup
    -- End Table Jenis Pembayaran Lookup

    -- Table Status Lunas Lookup
    -- End Table Status Lunas Lookup

    -- Table Status Pengajuan Lookup
    -- End Table Status Pengajuan Lookup

    -- Table Kas Besar
        DROP TABLE IF EXISTS kas_besar;
        CREATE TABLE IF NOT EXISTS kas_besar(
            id VARCHAR(10) NOT NULL UNIQUE, -- pk

            nama VARCHAR(255),
            alamat TEXT,
            no_telp VARCHAR(20),
            email VARCHAR(50) UNIQUE, -- fk user
            foto TEXT,
            -- status enum('AKTIF', 'NONAKTIF'), -- status aktif kas besar
            status_id INT UNSIGNED DEFAULT NULL,

            created_on DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            modified_on DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            created_by VARCHAR(50), -- who created first
            modified_by VARCHAR(50), -- who last edit

            CONSTRAINT pk_kas_besar_id PRIMARY KEY(id),
            CONSTRAINT fk_kas_besar_email FOREIGN KEY(email) REFERENCES user(username)
                ON DELETE SET NULL ON UPDATE CASCADE,
            CONSTRAINT fk_kas_besar_status_id FOREIGN KEY(status_id) REFERENCES active_status_lookup(id)
                ON DELETE RESTRICT ON UPDATE CASCADE,
            CONSTRAINT fk_kas_besar_created_by FOREIGN KEY(created_by) REFERENCES user(username)
                ON DELETE SET NULL ON UPDATE CASCADE,
            CONSTRAINT fk_kas_besar_modified_by FOREIGN KEY(modified_by) REFERENCES user(username)
                ON DELETE SET NULL ON UPDATE CASCADE
        )ENGINE=InnoDb;
    -- End Table Kas Besar

    -- Table Kas Kecil
    -- End Table Kas Kecil

    -- Table Sub Kas Kecil
    -- End Table Sub Kas Kecil

    -- Table Token Mobile
    -- End Table Token Mobile

    -- Table Token Lupa Password
    -- End Table Token Lupa Password

    -- Table Bank
    -- End Table Bank

    -- Table Mutasi Bank
    -- End Table Mutasi Bank

    -- Table Mutasi Kas Kecil
    -- End Table Mutasi Kas Kecil

    -- Table Mutasi Sub Kas Kecil
    -- End Table Mutasi Sub Kas Kecil

    -- Table Distributor
    -- End Table Distributor

    -- Table Proyek
    -- End Table Proyek

    -- Table Detail Proyek
    -- End Table Detail Proyek

    -- Table Detail SKK Proyek
    -- End Table Detail SKK Proyek

    -- Table Operasional
    -- End Table Operasaional

    -- Table Operasional Proyek
    -- End Table Operasional Proyek

    -- Table Detail Operasional Proyek
    -- End Table Operasional Proyek

    -- Table Pengajuan Sub Kas Kecil
    -- End Table Pengajuan Sub Kas Kecil

    -- Table Detail Pengajuan Sub Kas Kecil
    -- End Table Pengajuan Sub Kas Kecil

    -- Table Bukti Laporan Pengajuan SKK
    -- End Table Bukti Laporan Pengajuan SKK

    -- Table Pengajuan Kas Kecil
    -- End Table Pengajuan Kas Kecil