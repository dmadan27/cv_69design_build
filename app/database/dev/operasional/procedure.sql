
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
			(id_bank, id_kas_besar, tgl, nama, nominal, jenis, ket)
		VALUES
			(id_bank_param, id_kas_besar_param, tgl_param, nama_param, nominal_param, jenis_param, ket_param);

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


-- Procedure Edit Data Operasional masuk (FIX)
	delimiter //
	CREATE PROCEDURE edit_operasional_masuk(
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
			UPDATE bank SET saldo = (get_saldo_bank_lama + nominal_sebelum) WHERE id = id_bank_sebelum;

			-- insert mutasi bank lama
			INSERT INTO mutasi_bank 
				(id_bank, tgl, uang_masuk, uang_keluar, saldo, ket)
			VALUES 
				(id_bank_sebelum, tgl_param, nominal_sebelum, 0, (get_saldo_bank_lama + nominal_sebelum), ket_param);
		
		END IF;

			-- jika ada perubahan bank
			IF id_bank_sebelum != id_bank_param THEN

				-- get saldo bank lama
				SELECT saldo INTO get_saldo_bank_lama FROM bank WHERE id = id_bank_sebelum;
				
				-- normalisasi saldo bank sebelum
				UPDATE bank SET saldo = (get_saldo_bank_lama - nominal_sebelum) WHERE id = id_bank_sebelum;

				-- insert mutasi bank lama
				INSERT INTO mutasi_bank 
					(id_bank, tgl, uang_masuk, uang_keluar, saldo, ket)
				VALUES 
					(id_bank_sebelum, tgl_param, 0, nominal_sebelum, (get_saldo_bank_lama - nominal_sebelum), ket_param);

				-- get saldo bank baru
				SELECT saldo INTO get_saldo_bank FROM bank WHERE id = id_bank_param;

				-- update saldo bank baru
				UPDATE bank SET saldo = (get_saldo_bank + nominal_param) WHERE id = id_bank_param;

				-- insert mutasi bank baru
				INSERT INTO mutasi_bank 
					(id_bank, tgl, uang_masuk, uang_keluar, saldo, ket)
				VALUES 
					(id_bank_param, tgl_param, nominal_param, 0, (get_saldo_bank + nominal_param), ket_param);
			
			 -- jika ada perubahan nominal
			ELSE IF nominal_sebelum != nominal_param THEN

				IF nominal_param > nominal_sebelum THEN

					-- get saldo bank
					SELECT saldo INTO get_saldo_bank FROM bank WHERE id = id_bank_param;

					-- normalisasi saldo
					UPDATE bank SET saldo = (get_saldo_bank + (nominal_param - nominal_sebelum)) WHERE id = id_bank_param;

					-- insert mutasi
					INSERT INTO mutasi_bank 
						(id_bank, tgl, uang_masuk, uang_keluar, saldo, ket)
					VALUES 
						(id_bank_param, tgl_param, (nominal_param - nominal_sebelum), 0, (get_saldo_bank + (nominal_param - nominal_sebelum)), ket_param);
				
				ELSE IF nominal_param < nominal_sebelum THEN

					-- get saldo bank
					SELECT saldo INTO get_saldo_bank FROM bank WHERE id = id_bank_param;
				
					-- normalisasi saldo
					UPDATE bank SET saldo = (get_saldo_bank - (nominal_sebelum - nominal_param)) WHERE id = id_bank_param;

					-- insert mutasi
					INSERT INTO mutasi_bank 
						(id_bank, tgl, uang_masuk, uang_keluar, saldo, ket)
					VALUES 
						(id_bank_param, tgl_param, 0, (nominal_sebelum - nominal_param), (get_saldo_bank - (nominal_sebelum - nominal_param)), ket_param);

						END IF;

					END IF;

				END IF;

			END IF;

	-- update operasional
	UPDATE operasional 
	SET id_bank = id_bank_param, tgl = tgl_param, nama = nama_param, nominal = nominal_param, jenis = jenis_param, ket = ket_param
	WHERE id = id_param;

	END//
	delimiter ;


-- Procedure Edit Data Operasional keluar (FIX)
	delimiter //
	CREATE PROCEDURE edit_operasional_keluar(
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
			UPDATE bank SET saldo = (get_saldo_bank_lama - nominal_sebelum) WHERE id = id_bank_sebelum;

			-- insert mutasi bank lama
			INSERT INTO mutasi_bank 
				(id_bank, tgl, uang_masuk, uang_keluar, saldo, ket)
			VALUES 
				(id_bank_sebelum, tgl_param, 0, nominal_sebelum, (get_saldo_bank_lama - nominal_sebelum), ket_param);
		
		END IF;

			-- jika ada perubahan bank
			IF id_bank_sebelum != id_bank_param THEN

				-- get saldo bank lama
				SELECT saldo INTO get_saldo_bank_lama FROM bank WHERE id = id_bank_sebelum;
				
				-- normalisasi saldo bank sebelum
				UPDATE bank SET saldo = (get_saldo_bank_lama + nominal_sebelum) WHERE id = id_bank_sebelum;

				-- insert mutasi bank lama
				INSERT INTO mutasi_bank 
					(id_bank, tgl, uang_masuk, uang_keluar, saldo, ket)
				VALUES 
					(id_bank_sebelum, tgl_param, nominal_sebelum, 0, (get_saldo_bank_lama + nominal_sebelum), ket_param);

				-- get saldo bank baru
				SELECT saldo INTO get_saldo_bank FROM bank WHERE id = id_bank_param;

				-- update saldo bank baru
				UPDATE bank SET saldo = (get_saldo_bank - nominal_param) WHERE id = id_bank_param;

				-- insert mutasi bank baru
				INSERT INTO mutasi_bank 
					(id_bank, tgl, uang_masuk, uang_keluar, saldo, ket)
				VALUES 
					(id_bank_param, tgl_param, 0, nominal_param, (get_saldo_bank - nominal_param), ket_param);
			
			 -- jika ada perubahan nominal
			ELSE IF nominal_sebelum != nominal_param THEN

				IF nominal_param > nominal_sebelum THEN

					-- get saldo bank
					SELECT saldo INTO get_saldo_bank FROM bank WHERE id = id_bank_param;

					-- normalisasi saldo
					UPDATE bank SET saldo = (get_saldo_bank - (nominal_param - nominal_sebelum)) WHERE id = id_bank_param;

					-- insert mutasi
					INSERT INTO mutasi_bank 
						(id_bank, tgl, uang_masuk, uang_keluar, saldo, ket)
					VALUES 
						(id_bank_param, tgl_param, 0, (nominal_param - nominal_sebelum), (get_saldo_bank - (nominal_param - nominal_sebelum)), ket_param);
				
				ELSE IF nominal_param < nominal_sebelum THEN

					-- get saldo bank
					SELECT saldo INTO get_saldo_bank FROM bank WHERE id = id_bank_param;
				
					-- normalisasi saldo
					UPDATE bank SET saldo = (get_saldo_bank + (nominal_sebelum - nominal_param)) WHERE id = id_bank_param;

					-- insert mutasi
					INSERT INTO mutasi_bank 
						(id_bank, tgl, uang_masuk, uang_keluar, saldo, ket)
					VALUES 
						(id_bank_param, tgl_param, (nominal_sebelum - nominal_param), 0, (get_saldo_bank + (nominal_sebelum - nominal_param)), ket_param);

						END IF;

					END IF;

				END IF;

			END IF;

	-- update operasional
	UPDATE operasional 
	SET id_bank = id_bank_param, tgl = tgl_param, nama = nama_param, nominal = nominal_param, jenis = jenis_param, ket = ket_param
	WHERE id = id_param;

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
