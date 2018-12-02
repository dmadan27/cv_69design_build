-- bank
-- operasional proyek
-- detail operasional proyek
-- operasional
-- mutasi bank
-- pengajuan sub kas kecil
-- detail pengajuan sub kas kecil

-- hapus operasional
-- hapus pengajuan sub kas kecil - detail pengajuan sub kas kecil
-- hapus operasional proyek - detail operasional proyek
-- hapus mutasi bank
-- hapus bank

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