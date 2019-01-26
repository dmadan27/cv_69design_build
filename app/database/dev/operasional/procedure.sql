	-- ================================================================= --
	-- Operasional -- Versi 25 Januari 2019 -- START --
	-- ================================================================= --

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
		in ket_mutasi_param text,
		in ket_bank_masuk_param text,
		in ket_bank_keluar_param text,
		in ket_saldo_change_param text
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
				(id_bank_sebelum, tgl_param, nominal_sebelum, 0, (get_saldo_bank_lama + nominal_sebelum), ket_mutasi_param);
		
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
					(id_bank_sebelum, tgl_param, 0, nominal_sebelum, (get_saldo_bank_lama - nominal_sebelum), ket_bank_keluar_param);

				-- get saldo bank baru
				SELECT saldo INTO get_saldo_bank FROM bank WHERE id = id_bank_param;

				-- update saldo bank baru
				UPDATE bank SET saldo = (get_saldo_bank + nominal_param) WHERE id = id_bank_param;

				-- insert mutasi bank baru
				INSERT INTO mutasi_bank 
					(id_bank, tgl, uang_masuk, uang_keluar, saldo, ket)
				VALUES 
					(id_bank_param, tgl_param, nominal_param, 0, (get_saldo_bank + nominal_param), ket_bank_masuk_param);
			
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
						(id_bank_param, tgl_param, (nominal_param - nominal_sebelum), 0, (get_saldo_bank + (nominal_param - nominal_sebelum)), ket_saldo_change_param);
				
				ELSE IF nominal_param < nominal_sebelum THEN

					-- get saldo bank
					SELECT saldo INTO get_saldo_bank FROM bank WHERE id = id_bank_param;
				
					-- normalisasi saldo
					UPDATE bank SET saldo = (get_saldo_bank - (nominal_sebelum - nominal_param)) WHERE id = id_bank_param;

					-- insert mutasi
					INSERT INTO mutasi_bank 
						(id_bank, tgl, uang_masuk, uang_keluar, saldo, ket)
					VALUES 
						(id_bank_param, tgl_param, 0, (nominal_sebelum - nominal_param), (get_saldo_bank - (nominal_sebelum - nominal_param)), ket_saldo_change_param);

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
		in ket_mutasi_param text,
		in ket_bank_masuk_param text,
		in ket_bank_keluar_param text,
		in ket_saldo_change_param text
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
				(id_bank_sebelum, tgl_param, 0, nominal_sebelum, (get_saldo_bank_lama - nominal_sebelum), ket_mutasi_param);
		
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
					(id_bank_sebelum, tgl_param, nominal_sebelum, 0, (get_saldo_bank_lama + nominal_sebelum), ket_bank_masuk_param);

				-- get saldo bank baru
				SELECT saldo INTO get_saldo_bank FROM bank WHERE id = id_bank_param;

				-- update saldo bank baru
				UPDATE bank SET saldo = (get_saldo_bank - nominal_param) WHERE id = id_bank_param;

				-- insert mutasi bank baru
				INSERT INTO mutasi_bank 
					(id_bank, tgl, uang_masuk, uang_keluar, saldo, ket)
				VALUES 
					(id_bank_param, tgl_param, 0, nominal_param, (get_saldo_bank - nominal_param), ket_bank_keluar_param);
			
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
						(id_bank_param, tgl_param, 0, (nominal_param - nominal_sebelum), (get_saldo_bank - (nominal_param - nominal_sebelum)), ket_saldo_change_param);
				
				ELSE IF nominal_param < nominal_sebelum THEN

					-- get saldo bank
					SELECT saldo INTO get_saldo_bank FROM bank WHERE id = id_bank_param;
				
					-- normalisasi saldo
					UPDATE bank SET saldo = (get_saldo_bank + (nominal_sebelum - nominal_param)) WHERE id = id_bank_param;

					-- insert mutasi
					INSERT INTO mutasi_bank 
						(id_bank, tgl, uang_masuk, uang_keluar, saldo, ket)
					VALUES 
						(id_bank_param, tgl_param, (nominal_sebelum - nominal_param), 0, (get_saldo_bank + (nominal_sebelum - nominal_param)), ket_saldo_change_param);

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

	-- ================================================================= --
	-- Operasional -- Versi 25 Januari 2019 -- END --
	-- ================================================================= --