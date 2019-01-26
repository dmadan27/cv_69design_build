-- Procedure Tambah Data Bank
    delimiter //
    
    CREATE TRIGGER tambah_bank AFTER INSERT ON bank FOR EACH ROW
    BEGIN

        INSERT INTO mutasi_bank 
            (id_bank, tgl, uang_masuk, uang_keluar, saldo, ket)
        VALUES 
            (NEW.id, CURRENT_DATE(), NEW.saldo, 0, NEW.saldo, 'SALDO AWAL');

    END //
		
    delimiter ;

-- Procedure Hapus Data Bank (FIX)
	delimiter //
	CREATE PROCEDURE hapus_bank(
		in id_param int
	)
	BEGIN
        -- hapus data operasional
        DELETE FROM operasional WHERE id_bank = id_param;

        -- hapus detail operasional proyek
        -- 1. hapus detail
        DELETE FROM detail_operasional_proyek WHERE id_bank = id_param;

        -- hapus operasional proyek
        -- ini harus di hapus di operasional proyek aja {tanpa procedure}
        -- DELETE FROM operasional_proyek WHERE id_bank = id_param;

        -- hapus detail pengajuan kas kecil
        -- DELETE FROM detail_pengajuan_kas_kecil WHERE id_pengajuan IN (
        --     SELECT id FROM pengajuan_kas_kecil WHERE id_bank = id_param
        -- );

		-- hapus detail proyek
		DELETE FROM detail_proyek WHERE id_bank = id_param;

        -- hapus pengajuan kas kecil
        DELETE FROM pengajuan_kas_kecil WHERE id_bank = id_param;

        -- hapus mutasi bank
        DELETE FROM mutasi_bank WHERE id_bank = id_param;

        -- hapus bank
        DELETE FROM bank WHERE id = id_param;
	END//
	delimiter ;
