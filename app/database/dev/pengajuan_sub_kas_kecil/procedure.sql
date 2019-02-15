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
		SELECT CONCAT('UANG KELUAR SEBESAR RP ', FORMAT(dana_disetujui_param, 2, 'de_DE'), 
			' UNTUK PENGAJUAN SUB KAS KECIL DI PROYEK (', id_proyek_param, ') - ', id_param, ': ',nama_param) INTO ket_kas_kecil_param;
		SELECT CONCAT('UANG MASUK SEBESAR RP ', FORMAT(dana_disetujui_param, 2, 'de_DE'), 
			' DARI PENGAJUAN ', id_param, ' - ', nama_param) INTO ket_sub_kas_kecil_param;

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
			status_laporan = '1',
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