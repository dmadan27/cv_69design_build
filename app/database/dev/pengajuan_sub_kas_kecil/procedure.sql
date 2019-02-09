	-- acc pengajuan sub kas kecil
	delimiter //
	CREATE PROCEDURE acc_pengajuan_sub_kas_kecil(
		in id_param varchar(50),
		in id_kas_kecil_param varchar(10),
		in tgl_param date,
		in dana_disetujui_param double(12,2),
		in status_param char(1)
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
		SELECT CONCAT('UANG KELUAR SEBESAR RP ', FORMAT(dana_disetujui_param, 2, 'de_DE'), 
			' UNTUK PENGAJUAN SUB KAS KECIL DI PROYEK (', id_proyek_param, ') - ', id_param, ': ',nama_param) INTO ket_kas_kecil_param;
		SELECT CONCAT('UANG MASUK SEBESAR RP ', FORMAT(dana_disetujui_param, 2, 'de_DE'), 
			' DARI PENGAJUAN ', id_param, ' - ', nama_param) INTO ket_sub_kas_kecil_param;

		-- update saldo kas kecil
		UPDATE kas_kecil SET saldo = (get_saldo_kas_kecil - dana_disetujui_param) WHERE id = id_kas_kecil_param;

		-- insert mutasi kas kecil
		INSERT INTO mutasi_saldo_kas_kecil 
			(id_kas_kecil, tgl, uang_masuk, uang_keluar, saldo, ket) 
		VALUES 
			(id_kas_kecil_param, tgl_param, 0, dana_disetujui_param, (get_saldo_kas_kecil - dana_disetujui_param), ket_kas_kecil_param);

		-- update pengajuan sub kas kecil
		UPDATE 
			pengajuan_sub_kas_kecil 
		SET 
			dana_disetujui = dana_disetujui_param, 
			status = '3',
			status_laporan = '0' 
		WHERE id = id_param;

		-- update saldo sub kas kecil
		UPDATE sub_kas_kecil SET saldo = (get_saldo_sub_kas_kecil + dana_disetujui_param) WHERE id = id_sub_kas_kecil_param;

		-- insert mutasi sub kas kecil
		INSERT INTO mutasi_saldo_sub_kas_kecil 
			(id_sub_kas_kecil, tgl, uang_masuk, uang_keluar, saldo, ket) 
		VALUES 
			(id_sub_kas_kecil_param, tgl_param, dana_disetujui_param, 0, (get_saldo_sub_kas_kecil + dana_disetujui_param), ket_sub_kas_kecil_param);

	END //
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

		-- insert mutasi awal
		INSERT INTO mutasi_saldo_sub_kas_kecil 
			()
		VALUES
			();
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