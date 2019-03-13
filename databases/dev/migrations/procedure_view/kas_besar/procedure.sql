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