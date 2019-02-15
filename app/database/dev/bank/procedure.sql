# Procedure, Function, and Trigger Bank #

-- Procedure Tambah Bank
    DROP PROCEDURE IF EXISTS p_tambah_bank;
    delimiter //

    CREATE PROCEDURE p_tambah_bank(
        in nama_param varchar(255),
        in saldo_param double(12,2),
        in status_param enum('AKTIF', 'NONAKTIF'),
        in created_by_param varchar(50)
    )
    BEGIN

        INSERT INTO bank 
            (nama, saldo, status, created_by, modified_by) 
        VALUES (nama_param, saldo_param, status_param, created_by_param, created_by_param);

    END //

    delimiter ;
-- End Procedure Tambah Bank

-- Trigger Tambah Bank (After Insert)
    DROP TRIGGER t_after_insert_tambah_bank;
    delimiter //
    
    CREATE TRIGGER t_after_insert_tambah_bank AFTER INSERT ON bank FOR EACH ROW
    BEGIN

        INSERT INTO mutasi_bank 
            (id_bank, tgl, uang_masuk, uang_keluar, saldo, ket, created_by, modified_by)
        VALUES 
            (NEW.id, CURRENT_DATE(), NEW.saldo, 0, NEW.saldo, 'SALDO AWAL', NEW.created_by, NEW.modified_by);

    END //
		
    delimiter ;
-- End Trigger Tambah Bank (After Insert)

-- Procedure Edit Bank
    DROP PROCEDURE IF EXISTS p_edit_bank;
    delimiter //

    CREATE PROCEDURE p_edit_bank(
        in id_param int,
        in nama_param varchar(255),
        in status_param enum('AKTIF', 'NONAKTIF'),
        in modified_by_param varchar(50)
    )
    BEGIN

        UPDATE bank SET
            nama = nama_param,
            status = status_param,
            modified_by = modified_by_param
        WHERE id = id_param;

    END //

    delimiter ;
-- End Procedure Edit Bank

-- Procedure Delete Bank
    DROP PROCEDURE IF EXISTS p_hapus_bank;
    delimiter //

	CREATE PROCEDURE p_hapus_bank(
		in id_param int
	)
	BEGIN
        -- hapus data operasional
        DELETE FROM operasional WHERE id_bank = id_param;

        -- hapus detail operasional proyek
        DELETE FROM detail_operasional_proyek WHERE id_bank = id_param;

		-- hapus detail proyek
		DELETE FROM detail_proyek WHERE id_bank = id_param;

        -- hapus pengajuan kas kecil
        DELETE FROM pengajuan_kas_kecil WHERE id_bank = id_param;

        -- hapus mutasi bank
        DELETE FROM mutasi_bank WHERE id_bank = id_param;

        -- hapus bank
        DELETE FROM bank WHERE id = id_param;
        
	END //

	delimiter ;
-- End Procedure Delete Bank

# End Procedure, Function, and Trigger Bank #