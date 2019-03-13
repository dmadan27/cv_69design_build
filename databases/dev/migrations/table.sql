# Database YOUR DATABASE #
# YOUR DATABASE #
# YOUR VERSION #

# Local Development Only

-- Remove commentary in you want build database from zero
# DROP DATABASE IF EXISTS `timesheet`;
# CREATE DATABASE `timesheet`;
# USE `timesheet`;

# End Local Development Only

-- TABLE FOR APPLICATION, DO NOT CHANGE OR REMOVE --

    -- Table level lookup
        DROP TABLE IF EXISTS level_lookup;
        CREATE TABLE IF NOT EXISTS level_lookup (
            id INT UNSIGNED NOT NULL AUTO_INCREMENT,

            name VARCHAR(255) NOT NULL,
            description TEXT DEFAULT NULL,

            created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

            CONSTRAINT pk_level_lookup_id PRIMARY KEY(id)
        )ENGINE=InnoDb;
    -- End Table level lookup

    -- Table active status lookup
        DROP TABLE IF EXISTS active_status_lookup;
        CREATE TABLE IF NOT EXISTS active_status_lookup (
            id INT UNSIGNED NOT NULL AUTO_INCREMENT,

            name VARCHAR(255) NOT NULL,
            description TEXT DEFAULT NULL,

            created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

            CONSTRAINT pk_active_status_lookup_id PRIMARY KEY(id)
        )ENGINE=InnoDb;
    -- End Table active status lookup

    -- Table permission lookup
        DROP TABLE IF EXISTS permission_lookup;
        CREATE TABLE IF NOT EXISTS permission_lookup (
            id INT UNSIGNED NOT NULL AUTO_INCREMENT,

            name VARCHAR(255) NOT NULL,
            description TEXT DEFAULT NULL,

            created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

            CONSTRAINT pk_permission_lookup_id PRIMARY KEY(id)
        )ENGINE=InnoDb;
    -- End Table permission lookup

    -- Table User
        DROP TABLE IF EXISTS user;
        CREATE TABLE IF NOT EXISTS user (
            id INT UNSIGNED NOT NULL AUTO_INCREMENT,

            username VARCHAR(50) NOT NULL UNIQUE,
            password TEXT NOT NULL,
            -- name VARCHAR(255), -- optional, comentary if the name of user not contain in user table
            level_id INT UNSIGNED DEFAULT NULL, -- fk level lookup
            status_id INT UNSIGNED DEFAULT NULL, -- fk active status lookup
            -- image text, -- optional, comentary if the image of user not contain in user table

            created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            created_by INT UNSIGNED DEFAULT NULL, -- who created first
            updated_by INT UNSIGNED DEFAULT NULL, -- who last edit

            CONSTRAINT pk_user_username PRIMARY KEY(id),
            CONSTRAINT fk_user_level_id FOREIGN KEY(level_id) REFERENCES level_lookup(id)
                ON DELETE RESTRICT ON UPDATE CASCADE,
            CONSTRAINT fk_user_status_id FOREIGN KEY(status_id) REFERENCES active_status_lookup(id)
                ON DELETE RESTRICT ON UPDATE CASCADE,
            CONSTRAINT fk_user_created_by FOREIGN KEY(created_by) REFERENCES user(id)
                ON DELETE SET NULL ON UPDATE CASCADE,
            CONSTRAINT fk_user_updated_by FOREIGN KEY(updated_by) REFERENCES user(id)
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

            created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

            CONSTRAINT pk_menu_id PRIMARY KEY(id)
        )ENGINE=InnoDb;
    -- End Table Menu

    -- Tabel Menu detail
        DROP TABLE IF EXISTS menu_detail;
        CREATE TABLE IF NOT EXISTS menu_detail(
            id INT UNSIGNED NOT NULL AUTO_INCREMENT,

            menu_id INT UNSIGNED DEFAULT NULL, -- fk menu
            permission_id INT UNSIGNED DEFAULT NULL, -- fk permission lookup

            created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

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

            created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

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

            created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

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
    
    -- Table Contact
        DROP TABLE IF EXISTS contact;
        CREATE TABLE IF NOT EXISTS contact(
            id INT UNSIGNED NOT NULL AUTO_INCREMENT,

            user_id INT UNSIGNED DEFAULT NULL, -- fk user
            name VARCHAR(255) NOT NULL,
            firstname VARCHAR(255) DEFAULT NULL,
            lastname VARCHAR(255) DEFAULT NULL,
            birthplace VARCHAR(255) DEFAULT NULL,
            birthdate DATE DEFAULT NULL,
            address TEXT DEFAULT NULL,
            position VARCHAR(255) DEFAULT NULL,
            image TEXT DEFAULT NULL,
            status_id INT UNSIGNED DEFAULT NULL, -- fk active status lookup

            created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            modified_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            created_by INT UNSIGNED DEFAULT NULL, -- who created first
            updated_by INT UNSIGNED DEFAULT NULL, -- who last edit

            CONSTRAINT pk_contact_id PRIMARY KEY(id),
            CONSTRAINT fk_contact_user_id FOREIGN KEY(user_id) REFERENCES user(id)
                ON DELETE SET NULL ON UPDATE CASCADE,
            CONSTRAINT fk_contact_status_id FOREIGN KEY(status_id) REFERENCES active_status_lookup(id)
                ON DELETE RESTRICT ON UPDATE CASCADE,
            CONSTRAINT fk_contact_created_by FOREIGN KEY(created_by) REFERENCES user(id)
                ON DELETE SET NULL ON UPDATE CASCADE,
            CONSTRAINT fk_contact_updated_by FOREIGN KEY(updated_by) REFERENCES user(id)
                ON DELETE SET NULL ON UPDATE CASCADE
        )ENGINE=InnoDb;
    -- End Table Contact

    -- Table approve status lookup
        DROP TABLE IF EXISTS approve_status_lookup;
        CREATE TABLE IF NOT EXISTS approve_status_lookup(
            id INT UNSIGNED NOT NULL AUTO_INCREMENT,

            name VARCHAR(255) NOT NULL,
            description TEXT DEFAULT NULL,

            created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            modified_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            created_by INT UNSIGNED DEFAULT NULL, -- who created first
            updated_by INT UNSIGNED DEFAULT NULL, -- who last edit

            CONSTRAINT pk_approve_status_lookup_id PRIMARY KEY(id),
            CONSTRAINT fk_approve_status_lookup_created_by FOREIGN KEY(created_by) REFERENCES user(id)
                ON DELETE SET NULL ON UPDATE CASCADE,
            CONSTRAINT fk_approve_status_lookup_updated_by FOREIGN KEY(updated_by) REFERENCES user(id)
                ON DELETE SET NULL ON UPDATE CASCADE
        )ENGINE=InnoDb;
    -- End Table approve status lookup
    
    -- Table category lookup
        DROP TABLE IF EXISTS category_lookup;
        CREATE TABLE IF NOT EXISTS category_lookup(
            id INT UNSIGNED NOT NULL AUTO_INCREMENT,

            name VARCHAR(255) NOT NULL,
            description TEXT DEFAULT NULL,

            created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            modified_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            created_by INT UNSIGNED DEFAULT NULL, -- who created first
            updated_by INT UNSIGNED DEFAULT NULL, -- who last edit

            CONSTRAINT pk_category_lookup_id PRIMARY KEY(id),
            CONSTRAINT fk_category_lookup_created_by FOREIGN KEY(created_by) REFERENCES user(id)
                ON DELETE SET NULL ON UPDATE CASCADE,
            CONSTRAINT fk_category_lookup_updated_by FOREIGN KEY(updated_by) REFERENCES user(id)
                ON DELETE SET NULL ON UPDATE CASCADE
        )ENGINE=InnoDb;
    -- End Table category lookup

    -- Table project
        DROP TABLE IF EXISTS project;
        CREATE TABLE IF NOT EXISTS project(
            id INT UNSIGNED NOT NULL AUTO_INCREMENT,

            name VARCHAR(255) NOT NULL,
            status_id INT UNSIGNED DEFAULT NULL, -- fk active status lookup
            description TEXT DEFAULT NULL,

            created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            created_by INT UNSIGNED DEFAULT NULL, -- who created first
            updated_by INT UNSIGNED DEFAULT NULL, -- who last edit

            CONSTRAINT pk_project_id PRIMARY KEY(id),
            CONSTRAINT fk_project_status_id FOREIGN KEY(status_id) REFERENCES active_status_lookup(id)
                ON DELETE RESTRICT ON UPDATE CASCADE,
            CONSTRAINT fk_project_created_by FOREIGN KEY(created_by) REFERENCES user(id)
                ON DELETE SET NULL ON UPDATE CASCADE,
            CONSTRAINT fk_project_updated_by FOREIGN KEY(updated_by) REFERENCES user(id)
                ON DELETE SET NULL ON UPDATE CASCADE
        )ENGINE=InnoDb;
    -- End Table project

    -- Table timesheet
        DROP TABLE IF EXISTS timesheet;
        CREATE TABLE IF NOT EXISTS timesheet(
            id INT UNSIGNED NOT NULL AUTO_INCREMENT,

            date DATE NOT NULL,
            contact_id INT UNSIGNED DEFAULT NULL, -- fk contact
            total FLOAT(8,2) DEFAULT 0, -- total in hour
            approve_status_id INT UNSIGNED DEFAULT NULL, -- fk approve status lookup

            created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            created_by INT UNSIGNED DEFAULT NULL, -- who created first
            updated_by INT UNSIGNED DEFAULT NULL, -- who last edit

            CONSTRAINT pk_timesheet_id PRIMARY KEY(id),
            CONSTRAINT fk_timesheet_contact_id FOREIGN KEY(contact_id) REFERENCES contact(id)
                ON DELETE RESTRICT ON UPDATE CASCADE,
            CONSTRAINT fk_timesheet_approve_status_id FOREIGN KEY(approve_status_id) REFERENCES approve_status_lookup(id)
                ON DELETE RESTRICT ON UPDATE CASCADE,
            CONSTRAINT fk_timesheet_created_by FOREIGN KEY(created_by) REFERENCES user(id)
                ON DELETE SET NULL ON UPDATE CASCADE,
            CONSTRAINT fk_timesheet_updated_by FOREIGN KEY(updated_by) REFERENCES user(id)
                ON DELETE SET NULL ON UPDATE CASCADE
        )ENGINE=InnoDb;
    -- End Table timesheet

    -- Table timesheet detail
        DROP TABLE IF EXISTS timesheet_detail;
        CREATE TABLE IF NOT EXISTS timesheet_detail(
            id INT UNSIGNED NOT NULL AUTO_INCREMENT,

            timesheet_id INT UNSIGNED DEFAULT NULL, -- fk timesheet
            activity TEXT DEFAULT NULL,
            category_id INT UNSIGNED DEFAULT NULL, -- fk category lookup
            project_id INT UNSIGNED DEFAULT NULL, -- fk project
            start DATETIME NOT NULL,
            due DATETIME DEFAULT NULL,
            subtotal FLOAT(8,2) DEFAULT 0,

            created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            created_by INT UNSIGNED DEFAULT NULL, -- who created first
            updated_by INT UNSIGNED DEFAULT NULL, -- who last edit

            CONSTRAINT pk_timesheet_detail_id PRIMARY KEY(id),
            CONSTRAINT fk_timesheet_detail_timesheet_id FOREIGN KEY(timesheet_id) REFERENCES timesheet(id)
                ON DELETE RESTRICT ON UPDATE CASCADE,
            CONSTRAINT fk_timesheet_detail_category_id FOREIGN KEY(category_id) REFERENCES category_lookup(id)
                ON DELETE RESTRICT ON UPDATE CASCADE,
            CONSTRAINT fk_timesheet_detail_project_id FOREIGN KEY(project_id) REFERENCES project(id)
                ON DELETE RESTRICT ON UPDATE CASCADE,
            CONSTRAINT fk_timesheet_detail_created_by FOREIGN KEY(created_by) REFERENCES user(id)
                ON DELETE SET NULL ON UPDATE CASCADE,
            CONSTRAINT fk_timesheet_detail_updated_by FOREIGN KEY(updated_by) REFERENCES user(id)
                ON DELETE SET NULL ON UPDATE CASCADE
        )ENGINE=InnoDb;
    -- End Table timesheet detail

    -- Table approve history
        DROP TABLE IF EXISTS approve_history;
        CREATE TABLE IF NOT EXISTS approve_history(
            id INT UNSIGNED NOT NULL AUTO_INCREMENT,

            timesheet_id INT UNSIGNED DEFAULT NULL, -- fk timesheet
            date DATETIME DEFAULT CURRENT_TIMESTAMP,
            approve_status_id INT UNSIGNED DEFAULT NULL,
            notes TEXT DEFAULT NULL,

            created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            created_by INT UNSIGNED DEFAULT NULL, -- who created first
            updated_by INT UNSIGNED DEFAULT NULL, -- who last edit

            CONSTRAINT pk_approve_history_id PRIMARY KEY(id),
            CONSTRAINT fk_approve_history_timesheet_id FOREIGN KEY(timesheet_id) REFERENCES timesheet(id)
                ON DELETE RESTRICT ON UPDATE CASCADE,
            CONSTRAINT fk_approve_history_created_by FOREIGN KEY(created_by) REFERENCES user(id)
                ON DELETE SET NULL ON UPDATE CASCADE,
            CONSTRAINT fk_approve_history_updated_by FOREIGN KEY(updated_by) REFERENCES user(id)
                ON DELETE SET NULL ON UPDATE CASCADE
        )ENGINE=InnoDb;
    -- End Table approve history

    -- Table login history
        DROP TABLE IF EXISTS login_history;
        CREATE TABLE IF NOT EXISTS login_history(
            id INT UNSIGNED NOT NULL AUTO_INCREMENT,

            user_id INT UNSIGNED DEFAULT NULL,
            date DATETIME DEFAULT CURRENT_TIMESTAMP,
            ip_address VARCHAR(255) DEFAULT NULL,

            created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

            CONSTRAINT pk_login_history_id PRIMARY KEY(id),
            CONSTRAINT fk_login_history_user_id FOREIGN KEY(user_id) REFERENCES user(id)
                ON DELETE RESTRICT ON UPDATE CASCADE
        )ENGINE=InnoDb;
    -- End Table login history