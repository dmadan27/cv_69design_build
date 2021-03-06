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