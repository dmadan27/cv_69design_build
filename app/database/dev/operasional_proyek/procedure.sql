	-- ================================================================= --
	-- Operasional Proyek -- Versi 24 Januari 2019 -- START --
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
		IN ket_param text,
		IN ket_mutasi_param text
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
				(id_bank_param, tgl_param, 0, total_param, (get_saldo - total_param),  ket_mutasi_param);
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
		IN ket_mutasi_kondisi_param text
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
				(get_bank_sebelum, tgl_param, get_total_sebelum, 0, (get_saldo_bank_lama + get_total_sebelum), ket_mutasi_masuk_param);

			-- get saldo bank baru
			SELECT saldo INTO get_saldo_bank_baru FROM bank WHERE id = id_bank_param;

			-- update saldo bank baru
			UPDATE bank SET saldo = (get_saldo_bank_baru - total_param) WHERE id = id_bank_param;

			-- insert mutasi bank baru
			INSERT INTO mutasi_bank 
				(id_bank, tgl, uang_masuk, uang_keluar, saldo, ket)
			VALUES 
				(id_bank_param, tgl_param, 0, total_param, (get_saldo_bank_baru - total_param), ket_mutasi_keluar_param);
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
						(id_bank_param, tgl_param, 0, (total_param - get_total_sebelum), (get_saldo_bank_baru - (total_param - get_total_sebelum)), ket_mutasi_kondisi_param);
				ELSE
					IF total_param < get_total_sebelum THEN

						-- normalisasi saldo
						UPDATE bank SET saldo = (get_saldo_bank_baru + (get_total_sebelum - total_param)) WHERE id = id_bank_param;

						-- insert mutasi
						INSERT INTO mutasi_bank 
							(id_bank, tgl, uang_masuk, uang_keluar, saldo, ket)
						VALUES 
							(id_bank_param, tgl_param, (get_total_sebelum - total_param), 0, (get_saldo_bank_baru + (get_total_sebelum - total_param)), ket_mutasi_kondisi_param);
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
		UPDATE operasional_proyek 
		SET id_distributor = id_distributor_param, tgl = tgl_param, nama = nama_param, jenis = jenis_param, total = total_param, sisa = sisa_param, status = status_param, status_lunas = status_lunas_param, ket = ket_param 
		WHERE id = id_param;

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

			-- Insert Into Operasional Proyek
			INSERT INTO detail_operasional_proyek
			(id_operasional_proyek, id_bank, nama, tgl, total)
			VALUES
			(id_param, id_bank_param, nama_param, tgl_param, total_param);

			-- Catat Mutasi
			INSERT INTO mutasi_bank
			(id_bank, tgl, uang_masuk, uang_keluar, saldo, ket)
			VALUES
			(id_bank_param, tgl_param, 0, total_param, (get_saldo - total_param),  ket_mutasi_param);
		
		END IF;

	END//
	delimiter ;

	-- Procedure Edit Data Operasional Proyek Belum Lunas (FIXED)
	delimiter //
	CREATE PROCEDURE edit_operasional_proyek_BelumLunas(
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
		IN ket_mutasi_param text
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
			(get_id_bank, tgl_param, total_detail, 0, (get_saldo + total_detail),  ket_mutasi_param);

			-- Update table operasional proyek
			UPDATE operasional_proyek 
			SET id_distributor = id_distributor_param, tgl = tgl_param, nama = nama_param, jenis = jenis_param, total = total_param, sisa = sisa_param, status = status_param, status_lunas = status_lunas_param, ket = ket_param 
			WHERE id = id_param;

		ELSE 

			-- Update table operasional proyek
			UPDATE operasional_proyek 
			SET id_distributor = id_distributor_param, tgl = tgl_param, nama = nama_param, jenis = jenis_param, total = total_param, sisa = sisa_param, status = status_param, status_lunas = status_lunas_param, ket = ket_param 
			WHERE id = id_param;
		
		END IF;

	END//
	delimiter ;

	-- Procedure Edit Data Operasional Jenis Pembayaran Kredit (FIXED)
	delimiter //
	CREATE PROCEDURE edit_operasional_proyek_kredit(
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
		IN ket_param text
	)

	BEGIN
		DECLARE get_saldo double(12,2);
		DECLARE get_id_bank int;
		DECLARE get_sisa double(12,2);
		
		-- Update table operasional proyek
		UPDATE operasional_proyek 
		SET id_distributor = id_distributor_param, tgl = tgl_param, nama = nama_param, jenis = jenis_param, total = total_param, status = status_param, status_lunas = status_lunas_param, ket = ket_param 
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
		IN ket_mutasi_param text,
		IN ket_mutasi_masuk_param text,
		IN ket_mutasi_keluar_param text,
		IN ket_mutasi_kondisi_param text
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
				(get_bank_sebelum, tgl_detail_param, get_total_sebelum, 0, (get_saldo_bank_lama + get_total_sebelum), ket_mutasi_masuk_param);

			-- get saldo bank baru
			SELECT saldo INTO get_saldo FROM bank WHERE id = id_bank_param;

			-- update saldo bank baru
			UPDATE bank SET saldo = (get_saldo - total_detail_param) WHERE id = id_bank_param;

			-- insert mutasi bank baru
			INSERT INTO mutasi_bank 
				(id_bank, tgl, uang_masuk, uang_keluar, saldo, ket)
			VALUES 
				(id_bank_param, tgl_detail_param, 0, total_detail_param, (get_saldo - total_detail_param), ket_mutasi_keluar_param);
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
						(id_bank_param, tgl_detail_param, 0, (total_detail_param - get_total_sebelum), (get_saldo - (total_detail_param - get_total_sebelum)), ket_mutasi_kondisi_param);
				
				ELSE IF total_detail_param < get_total_sebelum THEN

					-- get saldo bank
					SELECT saldo INTO get_saldo FROM bank WHERE id = id_bank_param;

					-- normalisasi saldo
					UPDATE bank SET saldo = (get_saldo + (get_total_sebelum - total_detail_param)) WHERE id = id_bank_param;

					-- insert mutasi
					INSERT INTO mutasi_bank 
						(id_bank, tgl, uang_masuk, uang_keluar, saldo, ket)
					VALUES 
						(id_bank_param, tgl_detail_param, (get_total_sebelum - total_detail_param), 0, (get_saldo + (get_total_sebelum - total_detail_param)), ket_mutasi_kondisi_param);
					
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
	-- Operasional Proyek -- Versi 24 Januari 2019 -- END --
	-- ================================================================= --