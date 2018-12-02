# Database Sistem Informasi CV 69 Design & Build #
# Versi 1.0 Procedure #

# ============================ Procedure ============================ #

-- Procedure Tambah Data Kas Besar
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
	END//
	delimiter ;


-- Procedure Edit Data Kas Besar
	-- tanpa prosedur


-- Procedure Hapus Data Kas Besar
	delimiter //
	CREATE PROCEDURE hapus_kas_besar(
		in id_param varchar(10)
	)
	BEGIN
		DECLARE id_operasional_param int;
		DECLARE id_operasional_proyek_param varchar(50);
		DECLARE email_param varchar(50);

		-- get id operasional
		SELECT id INTO id_operasional_param FROM operasional WHERE id_kas_besar = id_param;

		-- get id operasional proyek
		SELECT id INTO id_operasional_proyek_param FROM operasional_proyek WHERE id_kas_besar = id_param;

		-- get email kas besar
		SELECT email INTO email_param FROM kas_besar WHERE id = id_param;

		-- 1. hapus operasional
		DELETE FROM operasional WHERE id = id_operasional_param;

		-- 2. hapus detail operasional proyek
		DELETE FROM detail_operasional_proyek WHERE id_operasional_proyek = id_operasional_proyek_param;

		-- 3. hapus operasional proyek
		DELETE FROM operasional_proyek WHERE id = id_operasional_proyek_param;

		-- 4. hapus kas besar
		DELETE FROM kas_besar WHERE id = id_param;

		-- 5. hapus user
		DELETE FROM user WHERE username = email_param;
	END//
	delimiter ;


-- Procedure Edit Status Kas Besar
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


-- Procedure Tambah Data Kas Kecil
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


-- Procedure Edit Data Kas Kecil
	-- tanpa prosedur


-- Procedure Hapus Data Kas Kecil
	delimiter //
	CREATE PROCEDURE hapus_kas_kecil(
		in id_param varchar(10)
	)
	BEGIN
		DECLARE id_pengajuan_param varchar(50);
		DECLARE email_param varchar(50);

		-- get id pengajuan sub kas kecil
		SELECT id INTO id_pengajuan_param FROM pengajuan_kas_kecil WHERE id_kas_kecil = id_param;

		-- get email kas kecil
		SELECT email INTO email_param FROM kas_kecil WHERE id = id_param;

		-- 1. hapus detail pengajuan kas kecil
		DELETE FROM detail_pengajuan_kas_kecil WHERE id_pengajuan = id_pengajuan_param;

		-- 2. hapus pengajuan kas kecil
		DELETE FROM pengajuan_kas_kecil WHERE id = id_pengajuan_param;

		-- 3. hapus kas kecil
		DELETE FROM kas_kecil WHERE id = id_param;

		-- 4. hapus user
		DELETE FROM user WHERE username = username_param;
	END//
	delimiter ;


-- Procedure Edit Status Kas Kecil
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


-- Procedure Tambah Data Sub Kas Kecil
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


-- Procedure Edit Data Sub Kas Kecil
	-- tanpa prosedur


-- Procedure Hapus Data Sub Kas Kecil
-- belum fix
	delimiter //
	CREATE PROCEDURE hapus_sub_kas_kecil(
		in id_param varchar(10)
	)
	BEGIN
		DECLARE id_pengajuan_param varchar(50);
		DECLARE email_param varchar(50);

		-- get id pengajuan sub kas kecil
		SELECT id INTO id_pengajuan_param FROM pengajuan_kas_kecil WHERE id_kas_kecil = id_param;

		-- get email kas kecil
		SELECT email INTO email_param FROM kas_kecil WHERE id = id_param;

		-- 1. hapus detail pengajuan sub kas kecil

		-- 2. hapus upload laporan pengajuan sub kas kecil

		-- 3. hapus pengajuan sub kas kecil

		-- 4. hapus logistik proyek

		-- 5. hapus sub kas kecil

		-- 6. hapus user
	END//
	delimiter ;


-- Procedure Edit Status Sub Kas Kecil
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


-- Procedure Tambah Data Proyek
	-- tanpa procedure


-- Procedure Edit Data Proyek
	-- tanpa procedure


-- Procedure Hapus Data Proyek
	delimiter //
	CREATE PROCEDURE pengajuproyek(
		in id_param varchar(50)
	)
	BEGIN
		DECLARE id_pengajuan_param varchar(50);
		DECLARE id_operasional_proyek_param varchar(50);

		-- get id_pengajuan
		SELECT id INTO id_pengajuan_param FROM pengajuan_sub_kas_kecil WHERE id_proyek = id_param;

		-- get id_operasional_proyek
		SELECT id INTO id_operasional_proyek_param FROM operasional_proyek WHERE id_proyek = id_param;

		-- 1. hapus semua data upload laporan
		DELETE FROM upload_laporan_pengajuan_sub_kas_kecil WHERE id_pengajuan = id_pengajuan_param;

		-- 2. hapus semua data detail pengajuan sub kas kecil
		DELETE FROM detail_pengajuan_sub_kas_kecil WHERE id_pengajuan = id_pengajuan_param;

		-- 3. hapus semua data pengajuan sub kas kecil
		DELETE FROM pengajuan_sub_kas_kecil WHERE id = id_pengajuan_param;

		-- 4. hapus semua data detail operasional proyek
		DELETE FROM detail_operasional_proyek WHERE id_operasional_proyek = id_operasional_proyek_param;

		-- 5. hapus semua data operasional proyek
		DELETE FROM operasional_proyek WHERE id = id_operasional_proyek_param;

		-- 6. hapus data logistik proyek
		DELETE FROM logistik_proyek WHERE id_proyek = id_param;

		-- 7. hapus data detail proyek
		DELETE FROM detail_proyek WHERE id_proyek = id_param;

		-- 8. hapus proyek
		DELETE FROM proyek WHERE id = id_param;

	END//
	delimiter ;


-- Procedure Tambah Data Bank
	-- tanpa procedure


-- Procedure Edit Data Bank
	-- tanpa procedure


-- Procedure Hapus Data Bank
	delimiter //
	CREATE PROCEDURE hapus_bank(
		in id_param int
	)
	BEGIN
        -- hapus data operasional
        DELETE FROM operasional WHERE id_bank = id_param;

        -- hapus detail operasional proyek
        DELETE FROM detail_operasional_proyek WHERE id_operasional_proyek IN (
            SELECT id FROM operasional_proyek WHERE id_bank = id_param
        );

        -- hapus operasional proyek
        DELETE FROM operasional_proyek WHERE id_bank = id_param;

        -- hapus detail pengajuan kas kecil
        DELETE FROM detail_pengajuan_kas_kecil WHERE id_pengajuan IN (
            SELECT id FROM pengajuan_kas_kecil WHERE id_bank = id_param
        );

        -- hapus pengajuan kas kecil
        DELETE FROM pengajuan_kas_kecil WHERE id_bank = id_param;

        -- hapus mutasi bank
        DELETE FROM mutasi_bank WHERE id_bank = id_param;

        -- hapus bank
        DELETE FROM bank WHERE id = id_param;
	END//
	delimiter ;


-- Procedure Tambah Data Pengajuan Kas Kecil
	
	-- FROM JAKA
	-- harus d review bersama yah ^_^

	--FLOW
	-- 1. insert ke tabel pengajuan kas kecil
	-- 2. insert ke detail pengajuan kas_kecil
	delimiter //
	CREATE PROCEDURE tambah_pengajuan_kas_kecil(
		IN id_param varchar(50),
		IN id_kas_kecil_param varchar(10),
		IN id_bank_param int,
		IN tgl_param date,
		IN nama_param varchar(50),
		IN total_param double(12,2),
		IN status_param enum('DISETUJUI','PERBAIKI','DITOLAK','PENDING'),
		IN id_pengajuan_sub_kas_kecil_param varchar(50)
	)
	BEGIN

		-- insert ke pengajuan kas kecil
			
			INSERT into pengajuan_kas_kecil 
			(id, id_kas_kecil, id_bank, tgl, nama, total, status)
				VALUES
			(id_param, id_kas_kecil_param, id_bank_param, tgl_param, nama_param, total_param, status_param);

		-- insert ke detail pengajuan kas kecil

			INSERT INTO detail_pengajuan_kas_kecil
			(id_pengajuan, id_pengajuan_sub_kas_kecil)
				VALUES
			(id_param, id_pengajuan_sub_kas_kecil_param);		
			
	END//
	delimiter ;

-- Procedure Edit Data Pengajuan Kas Kecil

	-- 1. select data pengejuan kas kecil (mana yang mau di edit)
	-- 2. update data pengajuan kas kecil
	-- 3. update detail pengajuan kas kecil


-- Procedure Hapus Data Pengajuan Kas Kecil

	-- 1. select data pengajuan kas kecil (mana yang mau di hapus)
	-- 2. delete data pengajuan kas kecil
	-- 3. delete data detail pengajuan kas kecil

	-- FROM JAKA
	-- need review bareng ^_^
	delimiter //
	CREATE PROCEDURE hapus_pengajuan_kas_kecil(
		IN id_param varchar(50)
	)
	BEGIN

		-- hapus detail pengajuan kas kecil
		DELETE FROM detail_pengajuan_kas_kecil where id_pengajuan = id_param; 

		-- hapus pengajuan kas kecil
		DELETE FROM pengajuan_kas_kecil where id = id_param;

	END//
	delimiter ;


-- Procedure Edit Status Data Pengajuan Kas Kecil
-- belum fix
	delimiter //
	CREATE PROCEDURE acc_pengajuan_kas_kecil(

	)
	BEGIN

	END//
	delimiter ;


-- Procedure Tambah Data Pengajuan Sub Kas Kecil
	-- tanpa procedure


-- Procedure Edit Data Pengajuan Sub Kas Kecil
	-- tanpa procedure


-- Procedure Hapus Data Pengajuan Sub Kas Kecil
	delimiter //
	CREATE PROCEDURE hapus_pengajuan_sub_kas_kecil(
		in id_param varchar(50)
	)
	BEGIN
		-- 1. hapus upload laporan sub kas kecil
		DELETE FROM upload_laporan_pengajuan_sub_kas_kecil WHERE id_pengajuan = id_param;

		-- 2. hapus data detail pengajuan sub kas kecil
		DELETE FROM detail_pengajuan_sub_kas_kecil WHERE id_pengajuan = id_param;

		-- 3. hapus data pengajuan sub kas kecil
		DELETE FROM pengajuan_sub_kas_kecil WHERE id = id_param;
	END//
	delimiter ;


-- Procedure Edit Status Data Pengajuan Sub Kas Kecil (Status Disetujui)
	delimiter //
	CREATE PROCEDURE acc_pengajuan_sub_kas_kecil(
		in id_param varchar(50),
		in id_kas_kecil_param varchar(10),
		-- in id_sub_kas_kecil_param varchar(10),
		in tgl_param date,
		in dana_disetujui_param double(12,2),
		in status_param varchar(15),
		in ket_kas_kecil_param text,
		in ket_sub_kas_kecil_param text
	)
	BEGIN
		DECLARE get_saldo_kas_kecil double(12,2);
		DECLARE get_saldo_sub_kas_kecil double(12,2);
		DECLARE id_sub_kas_kecil_param varchar(10);

		-- get saldo kas kecil
		SELECT saldo INTO get_saldo_kas_kecil FROM kas_kecil WHERE id = id_kas_kecil_param;

		-- get id sub kas kecil
		SELECT id_sub_kas_kecil INTO id_sub_kas_kecil_param FROM pengajuan_sub_kas_kecil WHERE id = id_param;

		-- get saldo sub kas kecil
		SELECT saldo INTO get_saldo_sub_kas_kecil FROM sub_kas_kecil WHERE id = id_sub_kas_kecil_param;

		-- 1. update tabel kas_kecil
		-- update saldo
		UPDATE kas_kecil
			SET saldo = get_saldo_kas_kecil-dana_disetujui_param
			WHERE id = id_kas_kecil_param;

		-- 2. insert tabel mutasi kas_kecil
		INSERT INTO mutasi_saldo_kas_kecil
			(id_kas_kecil, tgl, uang_masuk, uang_keluar, saldo, ket)
		VALUES
			(id_kas_kecil_param, tgl_param, 0, dana_disetujui_param,
				(get_saldo_kas_kecil-dana_disetujui_param), ket_kas_kecil_param);

		-- 3. update tabel pengajuan_sub_kas_kecil
		-- update dana_disetujui dan status
		UPDATE pengajuan_sub_kas_kecil
			SET dana_disetujui = dana_disetujui_param, status = status_param
			WHERE id = id_param;

		-- 4. update tabel sub_kas_kecil
		-- update saldo
		UPDATE sub_kas_kecil
			SET saldo = get_saldo_sub_kas_kecil+dana_disetujui_param
			WHERE id = id_sub_kas_kecil_param;

		-- 5. insert tabel mutasi_saldo_sub_kas_kecil
		INSERT INTO mutasi_saldo_sub_kas_kecil
			(id_sub_kas_kecil, tgl, uang_masuk, uang_keluar, saldo, ket)
		VALUES
			(id_sub_kas_kecil_param, tgl_param, dana_disetujui_param, 0,
				(get_saldo_sub_kas_kecil+dana_disetujui_param), ket_sub_kas_kecil_param);

	END//
	delimiter ;


-- Procedure Edit Data Laporan Pengajuan Sub Kas Kecil
C

-- Procedure Hapus Data Laporan Pengajuan Sub Kas Kecil


-- Procedure Edit Status Data Laporan Pengajuan Sub Kas Kecil (Status Disetujui)
-- belum fix
	delimiter //
	CREATE PROCEDURE acc_laporan_sub_kas_kecil(
		in id_pengajuan_param varchar(50),
		in id_sub_kas_kecil_param varchar(10),
		in tgl_param date,
		in sum_pengajuan_laporan_param double(12,2), -- sum harga asli di laporan
		in ket_param text
	)
	BEGIN
		DECLARE get_saldo double(12,2);

		-- get saldo sub kas kecil
		SELECT saldo INTO get_saldo FROM sub_kas_kecil WHERE id = id_sub_kas_kecil_param;

		-- 1. update tabel pengajuan sub kas kecil
		-- update status_laporan
		UPDATE pengajuan_sub_kas_kecil SET status_laporan = "DISETUJUI" WHERE id = id_pengajuan_param;

		-- 2. update tabel sub kas kecil
		-- update saldo
		UPDATE sub_kas_kecil SET saldo = (get_saldo-sum_pengajuan_laporan_param) WHERE id = id_sub_kas_kecil_param;

		-- 3. insert tabel mutasi saldo sub kas kecil
		INSERT INTO mutasi_saldo_sub_kas_kecil
			(id_sub_kas_kecil, tgl, uang_masuk, uang_keluar, saldo, ket)
		VALUES
			(id_sub_kas_kecil_param, tgl_param, 0, sum_pengajuan_laporan_param, (get_saldo-sum_pengajuan_laporan_param), ket_param);

	END//
	delimiter ;


-- Procedure Tambah Data Operasional Proyek
	delimiter //

	CREATE PROCEDURE tambah_operasional_proyek(
		IN id_param varchar(50),
		IN id_proyek_param varchar(50),
		IN id_bank_param int,
		IN id_kas_besar_param varchar(10),
		IN tgl_param date,
		IN nama_param varchar(50),
		IN total_param double(12,2),
		IN ket_param text
	)
	BEGIN
		DECLARE get_saldo double(12,2);


		-- 1. insert ke operasional proyek
		INSERT INTO operasional_proyek
			(id, id_proyek, id_bank, id_kas_besar, tgl, nama, total)
			VALUES
			(id_param, id_proyek_param, id_bank_param, id_kas_besar_param, tgl_param, nama_param, total_param );

		-- 2. ambil saldo terakhir
		SELECT saldo INTO get_saldo FROM bank WHERE id = id_bank_param;

		-- 3. update saldo
		UPDATE  bank SET saldo = ( get_saldo - total_param ) WHERE id = id_bank_param;


		-- 4. insert mutasi
		INSERT INTO mutasi_bank
			(id_bank, tgl, uang_masuk, uang_keluar, saldo, ket)
			VALUES
			(id_bank_param, tgl_param, 0, total_param, (get_saldo - total_param),  ket_param);

	END //

	delimiter ;

-- Procedure Tambah data Operasional Proyek New
	delimeter //
	CREATE PROCEDURE tambah_operasional_proyek_new(
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
				(id, id_proyek, id_bank, id_kas_besar, id_distributor, tgl, nama, jenis, total, sisa, status, status_lunas, ket)
				VALUES
				(id_param, id_proyek_param, id_bank_param, id_kas_besar_param, id_distributor_param, tgl_param, nama_param, jenis_param, total_param, sisa_param, status_param, status_lunas_param, ket_param );

			-- 2. ambil saldo terakhir
			SELECT saldo INTO get_saldo FROM bank WHERE id = id_bank_param;

			-- 3. update saldo
			UPDATE  bank SET saldo = ( get_saldo - total_param ) WHERE id = id_bank_param;


			-- 4. insert mutasi
			INSERT INTO mutasi_bank
				(id_bank, tgl, uang_masuk, uang_keluar, saldo, ket)
				VALUES
				(id_bank_param, tgl_param, 0, total_param, (get_saldo - total_param),  ket_param);
	END//
	delimeter ;


-- Procedure Edit Data Operasional Proyek



-- Procedure Hapus Data Operasional Proyek
	delimeter //
	CREATE PROCEDURE hapus_operasional_proyek_versi2(
		IN id_param varchar(50),
		IN tgl_param date,
		IN ket_param text
	)

	BEGIN
		-- deklarasi ambil saldo terakhir 
		DECLARE get_saldo double(12,2);
		DECLARE get_id_bank int;
		DECLARE get_total double(12,2);

		-- get id_bank
		SELECT id INTO get_id_bank FROM bank where id = get_id_bank;

		-- get total
		SELECT total INTO get_total FROM operasional_proyek where id = id_param;

		-- ambil saldo terahir
		SELECT saldo INTO get_saldo FROM bank WHERE id = get_id_bank;

		-- update saldo ke semula
		UPDATE bank SET saldo = (get_saldo + get_total) WHERE id = get_id_bank;

		-- insert mutasi (setelah perubahan)
		INSERT INTO mutasi_bank 
			(id_bank, tgl, uang_masuk, uang_keluar, saldo, ket)
		VALUES
			(get_id_bank, tgl_param, get_total, 0, (get_saldo + get_total), ket_param);

		-- hapus detail operasional proyek
		DELETE FROM detail_operasional_proyek where id_operasional_proyek IN
			(SELECT id FROM operasional_proyek where id = id_param);
		
		-- hapus operasional proyek
		DELETE  FROM operasional_proyek where id = id_param;
	END//
	delimeter ;


-- Procedure Tambah Data Operasional
	delimiter //
	CREATE PROCEDURE tambah_operasional(
		in id_bank_param int, -- id bank
		in id_kas_besar_param varchar(10), -- id kas besar
		in tgl_param date,  -- tanggal operasional
		in nama_param varchar(255), -- nama operasional
		in nominal_param double(12,2), -- nominal operasioana
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

		-- 3. insert mutasi operasional
		INSERT into mutasi_bank
		(id_bank, tgl, uang_masuk, uang_keluar, saldo, ket)
		VALUES
		(id_bank_param, tgl_param, 0, nominal_param, (get_saldo - nominal_param), nama_param, ket_mutasi_param);

		-- 4. update saldo bank
		UPDATE bank SET saldo = (get_saldo - nominal_param) WHERE  id = id_bank_param;

	END//
	delimiter ;


-- Procedure Edit Data Operasional


-- Procedure Hapus Data Operasional
	delimiter //
	CREATE PROCEDURE hapus_operasional(
		id_param int,
		tgl_param date,
		ket_param text
	)
	BEGIN
		DECLARE id_bank_param int;
		DECLARE nominal_param double(12,2);
		DECLARE get_saldo double(12,2);

		-- get id bank
		SELECT id_bank INTO id_bank_param FROM operasional WHERE id = id_param;

		-- get nominal
		SELECT nominal INTO nominal_param FROM operasional WHERE id = id_param;

		-- get saldo
		SELECT saldo INTO get_saldo FROM bank WHERE id = id_bank_param;


		-- 1. insert mutasi
		INSERT INTO mutasi_bank (id_bank, tgl, uang_masuk, uang_keluar, saldo, ket)
		VALUES (id_bank_param, tgl_param, nominal_param, 0, (get_saldo + nominal_param), ket_param);

		-- 2. update saldo
		UPDATE bank SET saldo = (get_saldo + nominal_param) WHERE id = id_bank_param;

		-- 3. hapus data operasional
		DELETE FROM operasional WHERE id = id_param;

	END//
	delimiter ;

-- Procedure Pengajuan Laporan Sub Kas Kecil
	delimiter //
	CREATE PROCEDURE pengajuan_laporan_sub_kas_kecil(
		in id_pengajuan_param varchar(50),
		in id_sub_kas_kecil_param varchar(10),
		in tgl_param date,
		in sum_pengajuan_laporan_param double(12,2), -- sum harga asli di laporan
		in ket_param text
	)
	BEGIN
		DECLARE get_saldo double(12,2);

		-- get saldo sub kas kecil
		SELECT saldo INTO get_saldo FROM sub_kas_kecil WHERE id = id_sub_kas_kecil_param;

		-- 1. update tabel pengajuan sub kas kecil
		-- update status_laporan
		UPDATE pengajuan_sub_kas_kecil SET status_laporan = "1" WHERE id = id_pengajuan_param;

		-- 2. update tabel sub kas kecil
		-- update saldo
		UPDATE sub_kas_kecil SET saldo = (get_saldo-sum_pengajuan_laporan_param) WHERE id = id_sub_kas_kecil_param;

		-- 3. insert tabel mutasi saldo sub kas kecil
		INSERT INTO mutasi_saldo_sub_kas_kecil
			(id_sub_kas_kecil, tgl, uang_masuk, uang_keluar, saldo, ket)
		VALUES
			(id_sub_kas_kecil_param, tgl_param, 0, sum_pengajuan_laporan_param, (get_saldo-sum_pengajuan_laporan_param), ket_param);

	END//
	delimiter ;


-- ===================================================================================
-- Procedure edit_laporan_sub_kas_kecil
-- belum fix
delimeter //
CREATE PROCEDURE edit_laporan_sub_kas_kecil (
    IN id_pengajuan_param varchar(50),
    IN id_sub_kas_kecil_param varchar(10),
    IN biaya_laporan_baru_param double(19,2),
    IN tgl_mutasi_param date
)
BEGIN
    DECLARE get_selisih_biaya_laporan double(19,2);
    DECLARE get_saldo_sub_kas_kecil double(12,2);
    DECLARE get_uang_masuk double(12,2);
    DECLARE get_uang_keluar double(12,2);
    
    -- mendapatkan selisih biaya laporan
    SELECT 
        (biaya_laporan - biaya_laporan_baru_param) 
    INTO get_selisih_biaya_laporan 
    FROM v_pengajuan_sub_kas_kecil WHERE id=id_pengajuan_param;

    -- mendapatkan saldo sub_kas_kecil
    SELECT
        saldo
    INTO get_saldo_sub_kas_kecil
    FROM v_sub_kas_kecil WHERE id=id_sub_kas_kecil_param;

    -- update status_laporan pengajuan (PENDING)
    UPDATE pengajuan_sub_kas_kecil
    SET 
        status_laporan='1'
    WHERE id=id_pengajuan_param;    

    -- mendapatkan uang masuk
    IF get_selisih_biaya_laporan>=0 THEN SET get_uang_masuk = get_selisih_biaya_laporan;
    ELSE SET get_uang_masuk = 0;
    END IF;

    -- mendapatkan uang keluar
    IF get_selisih_biaya_laporan<0 THEN SET get_uang_keluar = (get_selisih_biaya_laporan*(-1));
    ELSE SET get_uang_keluar = 0;
    END IF;

    -- insert mutasi
    INSERT INTO mutasi_saldo_sub_kas_kecil (
        id_sub_kas_kecil, tgl, uang_masuk, uang_keluar, saldo, ket
    ) VALUES (
        id_sub_kas_kecil_param,
        tgl_mutasi_param,
        get_uang_masuk,
        get_uang_keluar,
        (get_saldo_sub_kas_kecil+get_selisih_biaya_laporan),
        CONCAT('PENGAJUAN PERBAIKAN LAPORAN ', id_pengajuan_param)
    );

    -- update saldo sub kas kecil
    UPDATE sub_kas_kecil
    SET
        saldo=(get_saldo_sub_kas_kecil + get_selisih_biaya_laporan)
    WHERE id=id_sub_kas_kecil_param;


END//
delimeter; 


# =================================================================== #
