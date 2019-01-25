
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
		id_proyek_param varchar(50),
		id_bank_param int,
		tgl_param date,
		nama_param varchar(255),
		total_param double(12,2),
		is_DP_param char(1)
		-- ket_param text
		-- tgl_real date
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

			-- get nama bank baru dan lama
			SELECT nama INTO nama_bank_baru FROM bank WHERE id = id_bank_param;
			SELECT nama INTO nama_bank_lama FROM bank WHERE id = id_bank_sebelum;

			-- set keterangan mutasi bank lama
			SELECT CONCAT('UANG KELUAR SEBESAR RP ', FORMAT(get_total_sebelum, 2, 'de_DE'), 
				' DIKARENAKAN TRANSAKSI DI PROYEK (', id_proyek_param, ') - ', nama_param, 
				' MELAKUKAN PERGANTIAN BANK KE ', nama_bank_baru) INTO ket_bank_lama_param;

			-- insert mutasi bank lama
			INSERT INTO mutasi_bank 
				(id_bank, tgl, uang_masuk, uang_keluar, saldo, ket)
			VALUES 
				(get_bank_sebelum, tgl_param, 0, get_total_sebelum, (get_saldo_bank_lama - get_total_sebelum), ket_bank_lama_param);

			-- get saldo bank baru
			SELECT saldo INTO get_saldo_bank_baru FROM bank WHERE id = id_bank_param;

			-- update saldo bank baru
			UPDATE bank SET saldo = (get_saldo_bank_baru + total_param) WHERE id = id_bank_param;

			-- set keterangan mutasi bank baru
			SELECT CONCAT('UANG MASUK SEBESAR RP ', FORMAT(total_param, 2, 'de_DE'), 
				' DIKARENAKAN TRANSAKSI DI PROYEK (', id_proyek_param, ') - ', nama_param, 
				' MELAKUKAN PERGANTIAN BANK YANG SEBELUMNYA ', nama_bank_lama) INTO ket_bank_baru_param;

			-- insert mutasi bank baru
			INSERT INTO mutasi_bank 
				(id_bank, tgl, uang_masuk, uang_keluar, saldo, ket)
			VALUES 
				(id_bank_param, tgl_param, total_param, 0, (get_saldo_bank_baru + total_param), ket_bank_baru_param);
		ELSE
			-- jika bank sama
			-- jika ada perubahan di total
			IF get_total_sebelum != total_param THEN
				-- get saldo bank
				SELECT saldo INTO get_saldo_bank_baru FROM bank WHERE id = id_bank_param;

				-- normalisasi saldo
				UPDATE bank SET saldo = (get_saldo_bank_baru + (total_param - get_total_sebelum)) WHERE id = id_bank_param;
				
				IF total_param > get_total_sebelum THEN
					-- set keterangan mutasi
					SELECT CONCAT('UANG MASUK SEBESAR RP ', FORMAT((total_param - get_total_sebelum), 2, 'de_DE'), 
						' DIKARENAKAN ADANYA PERUBAHAN DATA DI PROYEK (', id_proyek_param, ') - ', nama_param) INTO ket_param;
					
					-- insert mutasi
					INSERT INTO mutasi_bank 
						(id_bank, tgl, uang_masuk, uang_keluar, saldo, ket)
					VALUES 
						(id_bank_param, tgl_param, (total_param - get_total_sebelum), 0, (get_saldo_bank_baru + (total_param - get_total_sebelum)), ket_param);
				ELSE
					IF total_param < get_total_sebelum THEN
						-- set keterangan mutasi
						SELECT CONCAT('UANG KELUAR SEBESAR RP ', FORMAT((get_total_sebelum - total_param), 2, 'de_DE'), 
							' DIKARENAKAN ADANYA PERUBAHAN DATA DI PROYEK (', id_proyek_param, ') - ', nama_param) INTO ket_param;
						
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
		tgl_param date
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
