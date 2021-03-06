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

# Procedure, Function, and Trigger Mutasi Saldo Kas Kecil #

# End Procedure, Function, and Trigger Mutasi Saldo Kas Kecil #

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
		modified_by varchar(50)
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
		in progress_param int,
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

	delimiter ;
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

	delimiter ;
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