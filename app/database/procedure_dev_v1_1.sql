# Database Sistem Informasi CV 69 Design & Build #
# Procedure Version 1.1 #

# ============================ Procedure ============================ #

-- Procedure Tambah Data Kas Besar (FIX)
    delimiter //
	CREATE PROCEDURE tambah_kas_besar(
		in id_param varchar(10),
		in nama_param varchar(50),
		in alamat_param text,
		in no_telp_param varchar(20),
		in email_param varchar(50),
		in foto_param text,
		in status_param varchar(20),
		in password_param text,
		in level_param varchar(20)
	)
	BEGIN

		-- 1. insert ke user
		INSERT INTO user
			(username, password, level, status)
		VALUES
			(email_param, password_param, level_param, 'AKTIF');

		-- insert ke kas besar
		INSERT INTO kas_besar
			(id, nama, alamat, no_telp, email, foto, status)
		VALUES
			(id_param, nama_param, alamat_param, no_telp_param,
				email_param, foto_param, status_param);
	END //
	delimiter ;


-- Procedure Hapus Data Kas Besar (FIX)
	delimiter //
	CREATE PROCEDURE hapus_kas_besar(
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


-- Procedure Edit Status Kas Besar (FIX)
	delimiter //
	CREATE PROCEDURE edit_status_kas_besar(
		in id_param varchar(10),
		in status_param varchar(20)
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
			UPDATE kas_besar SET status = status_param WHERE id = id_param;

			-- jika status berubah menjadi nonaktif
			IF status_param = 'NONAKTIF' THEN
				UPDATE user SET status = 'NONAKTIF' WHERE username = email_param;
			ELSE -- jika status berubah menjadi aktif
				UPDATE user SET status = 'AKTIF' WHERE username = email_param;
			END IF;

		END IF;

	END//

	delimiter ;


-- Procedure Tambah Data Kas Kecil (FIX)
	delimiter //
	CREATE PROCEDURE tambah_kas_kecil(
		in id_param varchar(10),
		in nama_param varchar(50),
		in alamat_param text,
		in no_telp_param varchar(20),
		in email_param varchar(50),
		in foto_param text,
		in saldo_param double(12,2),
		in status_param varchar(20),
		in password_param text,
		in level_param varchar(20)
	)
	BEGIN

		-- 1. insert ke user
		INSERT INTO user
			(username,password,level,status)
		VALUES
			(email_param,password_param,level_param, 'AKTIF');

		-- insert ke kas kecil
		INSERT INTO kas_kecil
			(id,nama,alamat,no_telp,email,foto,saldo,status)
		VALUES
			(id_param,nama_param,alamat_param,no_telp_param,
				email_param,foto_param,saldo_param,status_param);
	END//
	delimiter ;


-- Procedure Hapus Data Kas Kecil (FIX)
	delimiter //
	CREATE PROCEDURE hapus_kas_kecil(
		in id_param varchar(10)
	)
	BEGIN
	
		DECLARE email_param varchar(50);

		-- get email kas kecil
		SELECT email INTO email_param FROM kas_kecil WHERE id = id_param;

		-- 1. hapus detail pengajuan kas kecil
		-- DELETE FROM detail_pengajuan_kas_kecil WHERE id_pengajuan IN (
		-- 	SELECT id FROM pengajuan_kas_kecil WHERE id_kas_kecil = id_param
		-- );

		-- 2. hapus pengajuan kas kecil
		DELETE FROM pengajuan_kas_kecil WHERE id_kas_kecil = id_param;

		-- 3. hapus kas kecil
		DELETE FROM kas_kecil WHERE id = id_param;

		-- 4. hapus user
		DELETE FROM user WHERE username = email_param;
	END//
	delimiter ;


-- Procedure Edit Status Kas Kecil (FIX)
	delimiter //
	CREATE PROCEDURE edit_status_kas_kecil(
		in id_param varchar(10),
		in status_param varchar(20)
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
			UPDATE kas_kecil SET status = status_param WHERE id = id_param;

			-- jika status berubah menjadi nonaktif
			IF status_param = 'NONAKTIF' THEN
				UPDATE user SET status = 'NONAKTIF' WHERE username = email_param;
			ELSE -- jika status berubah menjadi aktif
				UPDATE user SET status = 'AKTIF' WHERE username = email_param;
			END IF;

		END IF;

	END//
	delimiter ;


-- Procedure Tambah Data Sub Kas Kecil (FIX)
	delimiter //
	CREATE PROCEDURE tambah_sub_kas_kecil(
		in id_param varchar(10),
		in nama_param varchar(50),
		in alamat_param text,
		in no_telp_param varchar(20),
		in email_param varchar(50),
		in foto_param text,
		in saldo_param double(12,2),
		in status_param varchar(20),
		in password_param text,
		in level_param varchar(20)
	)
	BEGIN

		-- 1. insert ke user
		INSERT INTO user
			(username,password,level,status)
		VALUES
			(email_param,password_param,level_param,'AKTIF');

		-- insert ke sub kas kecil
		INSERT INTO sub_kas_kecil
			(id,nama,alamat,no_telp,email,foto,saldo,status)
		VALUES
			(id_param,nama_param,alamat_param,no_telp_param,
				email_param,foto_param,saldo_param,status_param);
	END//
	delimiter ;


-- Procedure Hapus Data Sub Kas Kecil (FIX)
	delimiter //
	CREATE PROCEDURE hapus_sub_kas_kecil(
		in id_param varchar(10)
	)
	BEGIN

		DECLARE email_param varchar(50);

		-- get email kas kecil
		SELECT email INTO email_param FROM kas_kecil WHERE id = id_param;

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

	END//
	delimiter ;


-- Procedure Edit Status Sub Kas Kecil (FIX)
	delimiter //
	CREATE PROCEDURE edit_status_sub_kas_kecil(
		in id_param varchar(10),
		in status_param varchar(20)
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
			UPDATE kas_kecil SET status = status_param WHERE id = id_param;

			-- jika status berubah menjadi nonaktif
			IF status_param = 'NONAKTIF' THEN
				UPDATE user SET status = 'NONAKTIF' WHERE username = email_param;
			ELSE -- jika status berubah menjadi aktif
				UPDATE user SET status = 'AKTIF' WHERE username = email_param;
			END IF;

		END IF;

	END//
	delimiter ;


-- Procedure Hapus Data Proyek (FIX)
	-- terdapat bug, jika menghapus data proyek langsung maka saldo tidak akan bisa dinormalisasi
	delimiter //
	CREATE PROCEDURE hapus_proyek(
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

	END//
	delimiter ;


-- Procedure Tambah Data Detail Proyek (FIX)
	delimiter //
	CREATE PROCEDURE tambah_detail_proyek(
		id_proyek_param varchar(50),
		id_bank_param int,
		tgl_param date,
		nama_param varchar(255),
		total_param double(12,2),
		is_DP_param char(1),
		ket_param text
	)
	BEGIN
		DECLARE get_saldo double(12,2);

		-- 1. insert detail proyek
		INSERT INTO detail_proyek 
			(id_proyek, id_bank, tgl, nama, total, is_DP) 
		VALUES 
			(id_proyek_param, id_bank_param, tgl_param, nama_param, total_param, is_DP_param);

		-- 2. get saldo bank terakhir
		SELECT saldo INTO get_saldo FROM bank WHERE id = id_bank_param;

		-- 3. Update Saldo bank
		UPDATE bank SET saldo = (get_saldo + total_param) WHERE id = id_bank_param;

		-- 4. tambah mutasi bank
		INSERT INTO mutasi_bank 
			(id_bank, tgl, uang_masuk, uang_keluar, saldo, ket)
		VALUES 
			(id_bank_param, tgl_param, total_param, 0, (get_saldo + total_param),  ket_param);

	END//
	delimiter ;
	

-- Procedure Edit Data Detail Proyek (FIX)
	delimiter //
	CREATE PROCEDURE edit_detail_proyek(
		id_param int,
		id_bank_param int,
		tgl_param date,
		nama_param varchar(255),
		total_param double(12,2),
		is_DP_param char(1),
		ket_param text
		-- tgl_real date
	)
	BEGIN
		DECLARE get_bank_sebelum int;
		DECLARE get_total_sebelum double(12,2);

		DECLARE get_saldo_bank_lama double(12,2);
		DECLARE get_saldo_bank_baru double(12,2);

		-- get id_bank dan total sebelum diedit
		SELECT id_bank INTO get_bank_sebelum FROM detail_proyek WHERE id = id_param;
		SELECT total INTO get_total_sebelum FROM detail_proyek WHERE id = id_param;

		-- update detail
		UPDATE detail_proyek SET
			id_bank = id_bank_param,
			tgl = tgl_param,
			nama = nama_param,
			total = total_param,
			is_DP = is_DP_param
		WHERE id = id_param;

		-- jika ada perubahan di bank
		IF get_bank_sebelum != id_bank_param THEN
			
			-- get saldo bank lama
			SELECT saldo INTO get_saldo_bank_lama FROM bank WHERE id = get_bank_sebelum;
			
			-- normalisasi saldo bank sebelum
			UPDATE bank SET saldo = (get_saldo_bank_lama - get_total_sebelum) WHERE id = get_bank_sebelum;

			-- insert mutasi bank lama
			INSERT INTO mutasi_bank 
				(id_bank, tgl, uang_masuk, uang_keluar, saldo, ket)
			VALUES 
				(get_bank_sebelum, tgl_param, 0, get_total_sebelum, (get_saldo_bank_lama - get_total_sebelum), ket_param);

			-- get saldo bank baru
			SELECT saldo INTO get_saldo_bank_baru FROM bank WHERE id = id_bank_param;

			-- update saldo bank baru
			UPDATE bank SET saldo = (get_saldo_bank_baru + total_param) WHERE id = id_bank_param;

			-- insert mutasi bank baru
			INSERT INTO mutasi_bank 
				(id_bank, tgl, uang_masuk, uang_keluar, saldo, ket)
			VALUES 
				(id_bank_param, tgl_param, total_param, 0, (get_saldo_bank_baru + total_param), ket_param);
		ELSE
			-- jika bank sama
			-- jika ada perubahan di total
			IF get_total_sebelum != total_param THEN
				-- get saldo bank
				SELECT saldo INTO get_saldo_bank_baru FROM bank WHERE id = id_bank_param;

				-- normalisasi saldo
				UPDATE bank SET saldo = (get_saldo_bank_baru + (total_param - get_total_sebelum)) WHERE id = id_bank_param;
				
				IF total_param > get_total_sebelum THEN
					-- insert mutasi
					INSERT INTO mutasi_bank 
						(id_bank, tgl, uang_masuk, uang_keluar, saldo, ket)
					VALUES 
						(id_bank_param, tgl_param, (total_param - get_total_sebelum), 0, (get_saldo_bank_baru + (total_param - get_total_sebelum)), ket_param);
				ELSE
					IF total_param < get_total_sebelum THEN
						-- insert mutasi
						INSERT INTO mutasi_bank 
							(id_bank, tgl, uang_masuk, uang_keluar, saldo, ket)
						VALUES 
							(id_bank_param, tgl_param, 0, (get_total_sebelum - total_param), (get_saldo_bank_baru + (total_param - get_total_sebelum)), ket_param);
					END IF;

				END IF;
		
			END IF;

		END IF;

	END//
	delimiter ;


-- Procedure Hapus Data Detail Proyek (FIX)
	delimiter //
	CREATE PROCEDURE hapus_detail_proyek(
		id_param int,
		tgl_param date,
		ket_param text
	)
	BEGIN
		DECLARE get_id_bank int;
		DECLARE get_saldo double(12,2);
		DECLARE get_tgl date;
		DECLARE get_nama varchar(255);
		DECLARE get_total double(12,2);
		DECLARE get_is_DP char(1);

		-- get data detail sebelum dihapus 
		SELECT id_bank INTO get_id_bank FROM detail_proyek WHERE id = id_param;
		SELECT tgl INTO get_tgl FROM detail_proyek WHERE id = id_param;
		SELECT nama INTO get_nama FROM detail_proyek WHERE id = id_param;
		SELECT total INTO get_total FROM detail_proyek WHERE id = id_param;
		SELECT is_DP INTO get_is_DP FROM detail_proyek WHERE id = id_param;
		
		-- get saldo terakhir
		SELECT saldo INTO get_saldo FROM bank WHERE id = get_id_bank;

		-- update saldo
		UPDATE bank SET saldo = (get_saldo - get_total) WHERE id = get_id_bank;

		-- insert mutasi
		INSERT INTO mutasi_bank 
			(id_bank, tgl, uang_masuk, uang_keluar, saldo, ket)
		VALUES 
			(get_id_bank, tgl_param, 0, get_total, (get_saldo - get_total), ket_param);

		-- hapus detail
		DELETE FROM detail_proyek WHERE id = id_param;

	END//
	delimiter ;


-- Procedure Hapus Data Bank (FIX)
	delimiter //
	CREATE PROCEDURE hapus_bank(
		in id_param int
	)
	BEGIN
        -- hapus data operasional
        DELETE FROM operasional WHERE id_bank = id_param;

        -- hapus detail operasional proyek
        -- 1. hapus detail
        DELETE FROM detail_operasional_proyek WHERE id_bank = id_param;

        -- hapus operasional proyek
        -- ini harus di hapus di operasional proyek aja {tanpa procedure}
        -- DELETE FROM operasional_proyek WHERE id_bank = id_param;

        -- hapus detail pengajuan kas kecil
        -- DELETE FROM detail_pengajuan_kas_kecil WHERE id_pengajuan IN (
        --     SELECT id FROM pengajuan_kas_kecil WHERE id_bank = id_param
        -- );

		-- hapus detail proyek
		DELETE FROM detail_proyek WHERE id_bank = id_param;

        -- hapus pengajuan kas kecil
        DELETE FROM pengajuan_kas_kecil WHERE id_bank = id_param;

        -- hapus mutasi bank
        DELETE FROM mutasi_bank WHERE id_bank = id_param;

        -- hapus bank
        DELETE FROM bank WHERE id = id_param;
	END//
	delimiter ;


-- Procedure Tambah Data Operasional (FIX)
	delimiter //
	CREATE PROCEDURE tambah_operasional(
		in id_bank_param int, -- id bank
		in id_kas_besar_param varchar(10), -- id kas besar
		in tgl_param date,  -- tanggal operasional
		in nama_param varchar(255), -- nama operasional
		in nominal_param double(12,2), -- nominal operasional,
		in jenis_param enum('UANG MASUK', 'UANG KELUAR'),
		in ket_param text, -- ket operasional
		in ket_mutasi_param text
	)
	BEGIN
        
		DECLARE get_saldo double(12,2);

		-- 1. insert ke tabel operasional
		INSERT into operasional
			(id_bank, id_kas_besar, tgl, nama, nominal, ket)
		VALUES
			(id_bank_param, id_kas_besar_param, tgl_param, nama_param, nominal_param, ket_param);

		-- 2. ambil saldo terahir
		SELECT saldo INTO get_saldo FROM bank WHERE id= id_bank_param;

		IF jenis_param = 'UANG MASUK' THEN

			-- 3. insert mutasi operasional
			INSERT into mutasi_bank
				(id_bank, tgl, uang_masuk, uang_keluar, saldo, ket)
			VALUES
				(id_bank_param, tgl_param, nominal_param, 0, (get_saldo + nominal_param), ket_mutasi_param);

			-- 4. update saldo bank
			UPDATE bank SET saldo = (get_saldo + nominal_param) WHERE  id = id_bank_param;
		
		ELSE

			-- 3. insert mutasi operasional
			INSERT into mutasi_bank
				(id_bank, tgl, uang_masuk, uang_keluar, saldo, ket)
			VALUES
				(id_bank_param, tgl_param, 0, nominal_param, (get_saldo - nominal_param), ket_mutasi_param);

			-- 4. update saldo bank
			UPDATE bank SET saldo = (get_saldo - nominal_param) WHERE  id = id_bank_param;
		END IF;

	END//
	delimiter ;


-- Procedure Edit Data Operasional
	-- masih tahap pengerjaan
	delimiter //
	CREATE PROCEDURE edit_operasional(
		in id_param int,
		in id_bank_param int, -- id bank
		in tgl_param date,  -- tanggal operasional
		in nama_param varchar(255), -- nama operasional
		in nominal_param double(12,2), -- nominal operasional,
		in jenis_param enum('UANG MASUK', 'UANG KELUAR'),
		in ket_param text, -- ket operasional
		in ket_mutasi_param text
	)
	BEGIN
		DECLARE id_bank_sebelum int;
		DECLARE nominal_sebelum double(12,2);
		DECLARE jenis_sebelum varchar(25);
		
		-- get jenis sebelum
		SELECT jenis INTO jenis_sebelum FROM operasional WHERE id = id_param;
		-- get bank sebelum
		SELECT id_bank INTO id_bank_sebelum FROM operasional WHERE id = id_param;
		-- get nominal sebelum
		SELECT nominal INTO nominal_sebelum FROM operasional WHERE id = id_param;

		-- update operasional
		UPDATE operasional SET WHERE id = id_param;

		-- jika ada perubahan jenis
		IF jenis_sebelum != jenis_param THEN
		
			-- jika ada perubahan bank
			IF id_bank_sebelum != id_bank_param THEN
			
			ELSE

				-- jika ada perubahan nominal
				IF nominal_sebelum != nominal_param THEN

					IF nominal_param > nominal_sebelum THEN
					ELSE
						IF nominal_param < nominal_sebelum THEN
						END IF;
					END IF;

				END IF;

			END IF;
		
		-- jika tidak ada perubahan
		ELSE

			-- jika ada perubahan nominal
			IF nominal_sebelum != nominal_param THEN

				IF nominal_param > nominal_sebelum THEN
				ELSE
					IF nominal_param < nominal_sebelum THEN
					END IF;
				END IF;

			END IF;

		END IF;

	END//
	delimiter ;


-- Procedure Hapus Data Operasional (FIX)
	delimiter //
	CREATE PROCEDURE hapus_operasional(
		id_param int,
		tgl_param date,
		ket_param text
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
				(id_bank, tgl, uang_masuk, uang_keluar, saldo, ket)
			VALUES 
				(id_bank_param, tgl_param, 0, nominal_param, (get_saldo - nominal_param), ket_param);

			-- 2. update saldo
			UPDATE bank SET saldo = (get_saldo - nominal_param) WHERE id = id_bank_param;

		ELSE

			-- 1. insert mutasi
			INSERT INTO mutasi_bank 
				(id_bank, tgl, uang_masuk, uang_keluar, saldo, ket)
			VALUES 
				(id_bank_param, tgl_param, nominal_param, 0, (get_saldo + nominal_param), ket_param);

			-- 2. update saldo
			UPDATE bank SET saldo = (get_saldo + nominal_param) WHERE id = id_bank_param;

		END IF;

		-- 3. hapus data operasional
		DELETE FROM operasional WHERE id = id_param;

	END//
	delimiter ;


-- Procedure Edit Status Data Pengajuan Kas Kecil Menjadi Disetujui
	delimiter //
	CREATE PROCEDURE acc_pengajuan_kas_kecil(
		id_param int,
		
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
				(id_bank, tgl, uang_masuk, uang_keluar, saldo, ket)
			VALUES 
				(id_bank_param, tgl_param, 0, nominal_param, (get_saldo - nominal_param), ket_param);

			-- 2. update saldo
			UPDATE bank SET saldo = (get_saldo - nominal_param) WHERE id = id_bank_param;

		ELSE

			-- 1. insert mutasi
			INSERT INTO mutasi_bank 
				(id_bank, tgl, uang_masuk, uang_keluar, saldo, ket)
			VALUES 
				(id_bank_param, tgl_param, nominal_param, 0, (get_saldo + nominal_param), ket_param);

			-- 2. update saldo
			UPDATE bank SET saldo = (get_saldo + nominal_param) WHERE id = id_bank_param;

		END IF;

		-- 3. hapus data operasional
		DELETE FROM operasional WHERE id = id_param;

	END//
	delimiter ;

	-- ================================================================= --
	-- Operasional Proyek -- Versi 08 Januari 2019 -- START --
	-- ================================================================= --

	-- Procedure Tambah data Operasional Proyek Tunai Lunas (FIXED)
	delimiter //
	CREATE PROCEDURE tambah_operasional_proyek_tunailunas(
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
		IN ket_param text
	)

	BEGIN
		DECLARE get_saldo double(12,2);

			-- 1. insert ke operasional proyek
			INSERT INTO operasional_proyek
				(id, id_proyek, id_kas_besar, id_distributor, tgl, nama, jenis, total, sisa, status, status_lunas, ket)
				VALUES
				(id_param, id_proyek_param, id_kas_besar_param, id_distributor_param, tgl_param, nama_param, jenis_param, total_param, sisa_param, status_param, status_lunas_param, ket_param );

			-- 2. insert ke detail operasional proyek
			INSERT INTO detail_operasional_proyek
				(id_operasional_proyek, id_bank, nama, tgl, total)
				VALUES
				(id_param, id_bank_param, nama_param, tgl_param, total_param);

			-- 3. ambil saldo terakhir
			SELECT saldo INTO get_saldo FROM bank WHERE id = id_bank_param;

			-- 4. update saldo
			UPDATE  bank SET saldo = ( get_saldo - total_param ) WHERE id = id_bank_param;

			-- 5. insert mutasi
			INSERT INTO mutasi_bank
				(id_bank, tgl, uang_masuk, uang_keluar, saldo, ket)
				VALUES
				(id_bank_param, tgl_param, 0, total_param, (get_saldo - total_param),  ket_param);
	END//
	delimiter ;

	-- Procedure Tambah data Operasional Proyek Tunai Belum Lunas (FIXED)
	delimiter //
	CREATE PROCEDURE tambah_operasional_proyek_tunaiblmlunas(
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
		IN ket_param text
	)

	BEGIN

		-- 1. insert ke operasional proyek
		INSERT INTO operasional_proyek
			(id, id_proyek, id_kas_besar, id_distributor, tgl, nama, jenis, total, sisa, status, status_lunas, ket)
			VALUES
			(id_param, id_proyek_param, id_kas_besar_param, id_distributor_param, tgl_param, nama_param, jenis_param, total_param, sisa_param, status_param, status_lunas_param, ket_param );
			
	END//
	delimiter ;

	-- Procedure Tambah data Operasional Proyek Kredit (FIXED)
	delimiter //
	CREATE PROCEDURE tambah_operasional_proyek_kredit(
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
		IN ket_param text
	)

	BEGIN
		DECLARE get_saldo double(12,2);

			-- 1. insert ke operasional proyek
			INSERT INTO operasional_proyek
				(id, id_proyek, id_kas_besar, id_distributor, tgl, nama, jenis, total, sisa, status, status_lunas, ket)
				VALUES
				(id_param, id_proyek_param, id_kas_besar_param, id_distributor_param, tgl_param, nama_param, jenis_param, total_param, sisa_param, status_param, status_lunas_param, ket_param );

	END//
	delimiter ;

	-- Tambah Detail Operasional Proyek Untuk Kondisi Kredit (FIXED)
	delimiter //
	CREATE PROCEDURE tambah_detail_operasional_proyek_kredit(
		IN id_param varchar(50),
		IN id_bank_param int,
		IN tgl_param date,
		IN nama_param varchar(50),
		IN total_detail_param double(12,2),
		IN ket_param text
	)

	BEGIN
		DECLARE get_saldo double(12,2);

			-- ambil saldo terakhir
			SELECT saldo INTO get_saldo FROM bank WHERE id = id_bank_param;

			-- update saldo
			UPDATE  bank SET saldo = ( get_saldo - total_detail_param ) WHERE id = id_bank_param;

			-- insert into detail
			INSERT INTO detail_operasional_proyek
			(id_operasional_proyek, id_bank, nama, tgl, total)
			VALUES
			(id_param, id_bank_param, nama_param, tgl_param, total_detail_param);

			-- insert mutasi
			INSERT INTO mutasi_bank
			(id_bank, tgl, uang_masuk, uang_keluar, saldo, ket)
			VALUES
			(id_bank_param, tgl_param, 0, total_detail_param, (get_saldo - total_detail_param),  ket_param);

			
			
	END//
	delimiter ;

	-- Procedure Edit Data Operasional Proyek Lunas (FIXED)
	delimiter //
		CREATE PROCEDURE edit_operasional_proyek(
			IN id_param varchar(50),
			IN id_detail_param varchar(50),
			IN id_proyek_param varchar(50),
			IN id_bank_param int,
			IN tgl_param date,
			IN nama_param varchar(50),
			IN jenis_param varchar(50),
			IN total_param double(12,2),
			IN sisa_param double(12,2),
			IN status_param enum('TUNAI','KREDIT'),
			IN status_lunas_param enum('LUNAS','BELUM LUNAS'),
			IN ket_param text
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
				UPDATE bank SET saldo = (get_saldo_bank_lama + get_total_sebelum) WHERE id = get_bank_sebelum;

				-- insert mutasi bank lama
				INSERT INTO mutasi_bank 
					(id_bank, tgl, uang_masuk, uang_keluar, saldo, ket)
				VALUES 
					(get_bank_sebelum, tgl_param, get_total_sebelum, 0, (get_saldo_bank_lama + get_total_sebelum), ket_param);

				-- get saldo bank baru
				SELECT saldo INTO get_saldo_bank_baru FROM bank WHERE id = id_bank_param;

				-- update saldo bank baru
				UPDATE bank SET saldo = (get_saldo_bank_baru - total_param) WHERE id = id_bank_param;

				-- insert mutasi bank baru
				INSERT INTO mutasi_bank 
					(id_bank, tgl, uang_masuk, uang_keluar, saldo, ket)
				VALUES 
					(id_bank_param, tgl_param, 0, total_param, (get_saldo_bank_baru - total_param), ket_param);
			ELSE
				-- jika bank sama
				-- jika ada perubahan di total
				IF get_total_sebelum != total_param THEN
					-- get saldo bank
					SELECT saldo INTO get_saldo_bank_baru FROM bank WHERE id = id_bank_param;
					
					IF total_param > get_total_sebelum THEN

						-- normalisasi saldo
						UPDATE bank SET saldo = (get_saldo_bank_baru - (total_param - get_total_sebelum)) WHERE id = id_bank_param;

						-- insert mutasi
						INSERT INTO mutasi_bank 
							(id_bank, tgl, uang_masuk, uang_keluar, saldo, ket)
						VALUES 
							(id_bank_param, tgl_param, 0, (total_param - get_total_sebelum), (get_saldo_bank_baru - (total_param - get_total_sebelum)), ket_param);
					ELSE
						IF total_param < get_total_sebelum THEN

							-- normalisasi saldo
							UPDATE bank SET saldo = (get_saldo_bank_baru + (get_total_sebelum - total_param)) WHERE id = id_bank_param;

							-- insert mutasi
							INSERT INTO mutasi_bank 
								(id_bank, tgl, uang_masuk, uang_keluar, saldo, ket)
							VALUES 
								(id_bank_param, tgl_param, (get_total_sebelum - total_param), 0, (get_saldo_bank_baru + (get_total_sebelum - total_param)), ket_param);
						END IF;

					END IF;
			
				END IF;

			END IF;

			-- Get Status Sebelum
			SELECT status INTO get_status_sebelum FROM operasional_proyek WHERE id = id_param;

			-- Update table operasional proyek
			UPDATE operasional_proyek 
			SET tgl = tgl_param, nama = nama_param, jenis = jenis_param, total = total_param, sisa = sisa_param, status = status_param, status_lunas = status_lunas_param, ket = ket_param 
			WHERE id = id_param;

			-- Cek apakah ada detail atau tidak
			-- Untuk menentukan apakah ini data perubahan dari belum lunas atau bukan
			SELECT COUNT(id) INTO jumlah_detail FROM detail_operasional_proyek WHERE id = id_detail_param;

			-- Jika Detail Ada, Maka
			IF (jumlah_detail > 0) THEN
			
				-- Update Table Detail Operasional Proyek
				UPDATE detail_operasional_proyek
				SET id_bank = id_bank_param, nama = nama_param, tgl = tgl_param, total = total_param
				WHERE id = id_detail_param;
			
			ELSE 

				-- Ambil saldo terakhir
				SELECT saldo INTO get_saldo FROM bank WHERE id = id_bank_param;

				-- Update saldo
				UPDATE  bank SET saldo = ( get_saldo - total_param ) WHERE id = id_bank_param;

				-- Delete Table Detail Operasional Proyek
				DELETE FROM detail_operasional_proyek WHERE id_operasional_proyek = id_param;

				-- Catat Mutasi
				INSERT INTO mutasi_bank
				(id_bank, tgl, uang_masuk, uang_keluar, saldo, ket)
				VALUES
				(id_bank_param, tgl_param, 0, total_param, (get_saldo - total_param),  ket_param);

				-- Insert Into Operasional Proyek
				INSERT INTO detail_operasional_proyek
				(id_operasional_proyek, id_bank, nama, tgl, total)
				VALUES
				(id_param, id_bank_param, nama_param, tgl_param, total_param);
			
			END IF;

	END//
	delimiter ;

	-- Procedure Edit Data Operasional Proyek Belum Lunas (FIXED)
	delimiter //
		CREATE PROCEDURE edit_operasional_proyek_BelumLunas(
			IN id_param varchar(50),
			IN id_proyek_param varchar(50),
			IN tgl_param date,
			IN nama_param varchar(50),
			IN jenis_param varchar(50),
			IN total_param double(12,2),
			IN sisa_param double(12,2),
			IN status_param enum('TUNAI','KREDIT'),
			IN status_lunas_param enum('LUNAS','BELUM LUNAS'),
			IN ket_param text
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
					UPDATE  bank SET saldo = ( get_saldo + total_detail ) WHERE id = get_id_bank;

					-- Delete Table Detail Operasional Proyek
					DELETE FROM detail_operasional_proyek WHERE id_operasional_proyek = id_param;

					-- Catat Mutasi
					INSERT INTO mutasi_bank
					(id_bank, tgl, uang_masuk, uang_keluar, saldo, ket)
					VALUES
					(get_id_bank, tgl_param, total_detail, 0, (get_saldo + total_detail),  ket_param);

					-- Update table operasional proyek
					UPDATE operasional_proyek 
					SET tgl = tgl_param, nama = nama_param, jenis = jenis_param, total = total_param, sisa = sisa_param, status = status_param, status_lunas = status_lunas_param, ket = ket_param 
					WHERE id = id_param;

				ELSE 

					-- Update table operasional proyek
					UPDATE operasional_proyek 
					SET tgl = tgl_param, nama = nama_param, jenis = jenis_param, total = total_param, sisa = sisa_param, status = status_param, status_lunas = status_lunas_param, ket = ket_param 
					WHERE id = id_param;
				
				END IF;

	END//
	delimiter ;

	-- Procedure Edit Data Operasional Jenis Pembayaran Kredit (FIXED)
	delimiter //
		CREATE PROCEDURE edit_operasional_proyek_kredit(
			IN id_param varchar(50),
			IN id_proyek_param varchar(50),
			IN tgl_param date,
			IN nama_param varchar(50),
			IN jenis_param varchar(50),
			IN total_param double(12,2),
			IN sisa_param double(12,2),
			IN status_param enum('TUNAI','KREDIT'),
			IN status_lunas_param enum('LUNAS','BELUM LUNAS'),
			IN total_param double(12,2),
			IN ket_param text
		)

		BEGIN
			DECLARE get_saldo double(12,2);
			DECLARE get_id_bank int;
			DECLARE get_sisa double(12,2);
			
			-- Update table operasional proyek
			UPDATE operasional_proyek 
			SET tgl = tgl_param, nama = nama_param, jenis = jenis_param, total = total_param, sisa = sisa_param, status = status_param, status_lunas = status_lunas_param, ket = ket_param 
			WHERE id = id_param;

	END//
	delimiter ;

	-- Procedure Update Data Detail Operasional (FIXED)
	delimiter //
	CREATE PROCEDURE edit_detail_operasional_proyek(
		IN id_operasional_proyek_param varchar(50),
		IN id_detail_param varchar(50),
		IN id_bank_param varchar(50),
		IN tgl_detail_param date,
		IN nama_detail_param varchar(50),
		IN total_detail_param double(12,2),
		IN ket_param text
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
			UPDATE bank SET saldo = (get_saldo_bank_lama + get_total_sebelum) WHERE id = get_bank_sebelum;

			-- insert mutasi bank lama
			INSERT INTO mutasi_bank 
				(id_bank, tgl, uang_masuk, uang_keluar, saldo, ket)
			VALUES 
				(get_bank_sebelum, tgl_detail_param, get_total_sebelum, 0, (get_saldo_bank_lama + get_total_sebelum), ket_param);

			-- get saldo bank baru
			SELECT saldo INTO get_saldo FROM bank WHERE id = id_bank_param;

			-- update saldo bank baru
			UPDATE bank SET saldo = (get_saldo - total_detail_param) WHERE id = id_bank_param;

			-- insert mutasi bank baru
			INSERT INTO mutasi_bank 
				(id_bank, tgl, uang_masuk, uang_keluar, saldo, ket)
			VALUES 
				(id_bank_param, tgl_detail_param, 0, total_detail_param, (get_saldo - total_detail_param), ket_param);
		ELSE
			-- jika bank sama
			-- jika ada perubahan di total
			IF get_total_sebelum != total_detail_param THEN
				
				IF total_detail_param > get_total_sebelum THEN

					-- get saldo bank
					SELECT saldo INTO get_saldo FROM bank WHERE id = id_bank_param;

					-- normalisasi saldo
					UPDATE bank SET saldo = (get_saldo - (total_detail_param - get_total_sebelum)) WHERE id = id_bank_param;

					-- insert mutasi
					INSERT INTO mutasi_bank 
						(id_bank, tgl, uang_masuk, uang_keluar, saldo, ket)
					VALUES 
						(id_bank_param, tgl_detail_param, 0, (total_detail_param - get_total_sebelum), (get_saldo - (total_detail_param - get_total_sebelum)), ket_param);
				
				ELSE IF total_detail_param < get_total_sebelum THEN

					-- get saldo bank
					SELECT saldo INTO get_saldo FROM bank WHERE id = id_bank_param;

					-- normalisasi saldo
					UPDATE bank SET saldo = (get_saldo + (get_total_sebelum - total_detail_param)) WHERE id = id_bank_param;

					-- insert mutasi
					INSERT INTO mutasi_bank 
						(id_bank, tgl, uang_masuk, uang_keluar, saldo, ket)
					VALUES 
						(id_bank_param, tgl_detail_param, (get_total_sebelum - total_detail_param), 0, (get_saldo + (get_total_sebelum - total_detail_param)), ket_param);
					
					END IF;

				END IF;
		
			END IF;

		END IF;

		-- Update table detail operasional proyek
		UPDATE detail_operasional_proyek 
			SET id_bank = id_bank_param, nama = nama_detail_param, tgl = tgl_detail_param, total = total_detail_param
		WHERE id = id_detail_param;

	END//
	delimiter ;	

	-- Procedure Hapus Data Operasional Proyek (FIXED)
	delimiter //
	CREATE PROCEDURE hapus_operasional_proyek_versi2(
		IN id_param varchar(50),
		IN total_param double(12,2),
		IN tgl_param date,
		IN ket_param text
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
		UPDATE bank SET saldo = (get_saldo + total_param) WHERE id = get_id_bank;

		-- insert mutasi (setelah perubahan)
		INSERT INTO mutasi_bank 
			(id_bank, tgl, uang_masuk, uang_keluar, saldo, ket)
		VALUES
			(get_id_bank, tgl_param, total_param, 0, (get_saldo + total_param), ket_param);

		-- hapus detail operasional proyek
		DELETE FROM detail_operasional_proyek where id_operasional_proyek IN
			(SELECT id FROM operasional_proyek where id = id_param);
		
		-- hapus operasional proyek
		DELETE  FROM operasional_proyek where id = id_param;
	END//
	delimiter ;

	-- Hapus Operasional Proyek Tunai Belum Lunas (FIXED)
	delimiter //
	CREATE PROCEDURE hapus_operasional_proyek_tunai_blmlunas(
		IN id_param varchar(50)
	)
	BEGIN
		-- hapus operasional proyek
		DELETE  FROM operasional_proyek where id = id_param;
	END//
	delimiter ;

	-- Hapus Operasional Proyek Jenis Pembayaran Kredit (FIXED)
	delimiter //
	CREATE PROCEDURE hapus_operasional_proyek_kredit(
		IN id_param varchar(50)
	)
	BEGIN
		-- hapus detail operasional proyek
		DELETE FROM detail_operasional_proyek WHERE id_operasional_proyek IN
			( SELECT id FROM operasional_proyek WHERE id = id_param );
		-- hapus operasional proyek
		DELETE FROM operasional_proyek WHERE id = id_param;
	END//
	delimiter ;

	-- Procedure Pencatatan Mutasi Bank Setelah Operasional Proyek Kredit Dihapus (FIXED)
	delimiter //
	CREATE PROCEDURE hapus_operasional_proyek_kredit_catatMutasi(
		IN id_param varchar(50),
		IN id_bank_param varchar(50),
		IN total_detail_param double(12,2),
		IN tgl_param date,
		IN ket_param text
	)

	BEGIN
		DECLARE get_saldo double(12,2);

		-- ambil saldo terakhir
		SELECT saldo INTO get_saldo FROM bank WHERE id = id_bank_param;

		-- update saldo ke semula
		UPDATE bank SET saldo = (get_saldo + total_detail_param) WHERE id = id_bank_param;

		-- insert mutasi 
		INSERT INTO mutasi_bank 
			(id_bank, tgl, uang_masuk, uang_keluar, saldo, ket)
		VALUES
			(id_bank_param, tgl_param, total_detail_param, 0, (get_saldo + total_detail_param), ket_param);

	END//
	delimiter ;

	-- ================================================================= --
	-- Operasional Proyek -- Versi 08 Januari 2019 -- END --
	-- ================================================================= --

	-- ================================================================= --
	-- Pengajuan Kas Kecil -- Versi 08 Januari 2019 -- START --
	-- ================================================================= --

	-- Tambah Data Pengajuan Kas Kecil (FIXED) 
	delimiter //
	CREATE PROCEDURE tambah_pengajuan_kas_kecil(
		IN id_param varchar(50),
		IN id_kas_kecil_param varchar(10),
		IN tgl_param date,
		IN nama_param varchar(50),
		IN total_param double(12,2),
		IN status_param enum('DISETUJUI','PERBAIKI','DITOLAK','PENDING')
	)

	BEGIN

		-- insert ke pengajuan kas kecil
			INSERT into pengajuan_kas_kecil 
			(id, id_kas_kecil, tgl, nama, total, status)
				VALUES
			(id_param, id_kas_kecil_param, tgl_param, nama_param, total_param, status_param);
			
	END//
	delimiter ;

	-- Procedure Acc Pengajuan Kas Kecil (FIXED)
	delimiter //
	CREATE PROCEDURE acc_pengajuan_kas_kecil(
		IN id_param varchar(50),
		IN id_kas_kecil_param varchar(50),
		IN tgl_param date,
		IN id_bank_param int,
		IN total_disetujui_param double(12,2),
		IN ket_kas_kecil_param text,
		IN ket_param text,
		IN status_param enum('DISETUJUI','PERBAIKI','DITOLAK','PENDING')
	)
	BEGIN
		DECLARE saldo_kas_kecil double(12,2);
		DECLARE saldo_bank double(12,2);

		-- Get Saldo Kas Kecil
		SELECT saldo INTO saldo_kas_kecil FROM kas_kecil WHERE id = id_kas_kecil_param;

		-- Get Saldo Bank
		SELECT saldo INTO saldo_bank FROM bank WHERE id = id_bank_param;
		
		-- Pemindahan Saldo Dari Bank ke Saldo Kas Kecil
		UPDATE kas_kecil SET saldo = saldo + total_disetujui_param WHERE id = id_kas_kecil_param;
		UPDATE bank SET saldo = saldo - total_disetujui_param WHERE id = id_bank_param;
		
		-- Pencatatan Mutasi Kas Kecil
		INSERT INTO mutasi_saldo_kas_kecil
			(id_kas_kecil, tgl, uang_masuk, uang_keluar, saldo, ket)
		VALUES
			(id_kas_kecil_param, tgl_param, total_disetujui_param, 0, (saldo_kas_kecil + total_disetujui_param), ket_kas_kecil_param);

		-- Pencatatan Mutasi Bank
		INSERT INTO mutasi_bank
			(id_bank, tgl, uang_masuk, uang_keluar, saldo, ket)
		VALUES
			(id_bank_param, tgl_param, 0, total_disetujui_param, (saldo_bank - total_disetujui_param),  ket_param); 

		-- Update Table Pengajuan Kas Kecil
		UPDATE pengajuan_kas_kecil
			SET id_bank = id_bank_param, total_disetujui = total_disetujui_param, status = status_param
		WHERE id = id_param;

	END//
	delimiter ;

	-- ================================================================= --
	-- Pengajuan Kas Kecil -- Versi 08 Januari 2019 -- END --
	-- ================================================================= --
	
# =================================================================== #