# Procedure, Function, and Trigger Pengajuan Kas Kecil #

-- Tambah Data Pengajuan Kas Kecil
	DROP PROCEDURE IF EXISTS p_tambah_pengajuan_kas_kecil;
	delimiter //
	
	CREATE PROCEDURE p_tambah_pengajuan_kas_kecil(
		IN id_param varchar(50),
		IN id_kas_kecil_param varchar(10),
		IN tgl_param date,
		IN nama_param varchar(50),
		IN total_param double(12,2),
		IN status_param char(1),
		IN created_by_param varchar(50)
	)

	BEGIN

		-- insert ke pengajuan kas kecil
		INSERT into pengajuan_kas_kecil 
			(id, id_kas_kecil, tgl, nama, total, status, created_by, modified_by)
		VALUES
			(id_param, id_kas_kecil_param, tgl_param, nama_param, total_param, status_param, 
			created_by_param, created_by_param);
			
	END //

	delimiter ;
-- End Procedure Tambah Pengajuan Kas Kecil

-- Procedure Acc Pengajuan Kas Kecil
	DROP PROCEDURE IF EXISTS p_acc_pengajuan_kas_kecil;
	delimiter //
	CREATE PROCEDURE p_acc_pengajuan_kas_kecil(
		IN id_param varchar(50),
		IN id_kas_kecil_param varchar(50),
		IN tgl_param date,
		IN id_bank_param int,
		IN total_disetujui_param double(12,2),
		IN ket_kas_kecil_param text,
		IN ket_param text,
		IN status_param char(1),
		IN modified_by_param varchar(50)
	)
	BEGIN
		DECLARE saldo_kas_kecil double(12,2);
		DECLARE saldo_bank double(12,2);

		-- Get Saldo Kas Kecil
		SELECT saldo INTO saldo_kas_kecil FROM kas_kecil WHERE id = id_kas_kecil_param;

		-- Get Saldo Bank
		SELECT saldo INTO saldo_bank FROM bank WHERE id = id_bank_param;
		
		-- Pemindahan Saldo Dari Bank ke Saldo Kas Kecil
		UPDATE kas_kecil SET 
			saldo = saldo + total_disetujui_param,
			modified_by = modified_by_param 
		WHERE id = id_kas_kecil_param;

		UPDATE bank SET 
			saldo = saldo - total_disetujui_param,
			modified_by = modified_by_param 
		WHERE id = id_bank_param;
		
		-- Pencatatan Mutasi Kas Kecil
		INSERT INTO mutasi_saldo_kas_kecil
			(id_kas_kecil, tgl, uang_masuk, uang_keluar, saldo, ket, created_by, modified_by)
		VALUES
			(id_kas_kecil_param, tgl_param, total_disetujui_param, 0, (saldo_kas_kecil + total_disetujui_param), 
			ket_kas_kecil_param, modified_by_param, modified_by_param);

		-- Pencatatan Mutasi Bank
		INSERT INTO mutasi_bank
			(id_bank, tgl, uang_masuk, uang_keluar, saldo, ket, created_by, modified_by)
		VALUES
			(id_bank_param, tgl_param, 0, total_disetujui_param, (saldo_bank - total_disetujui_param), 
			ket_param, modified_by_param, modified_by_param); 

		-- Update Table Pengajuan Kas Kecil
		UPDATE pengajuan_kas_kecil SET 
			id_bank = id_bank_param, total_disetujui = total_disetujui_param, status = status_param,
			modified_by = modified_by_param
		WHERE id = id_param;

	END //

	delimiter ;
-- End Procedure Acc Pengajuan Kas Kecil

# Procedure, Function, and Trigger Pengajuan Kas Kecil #