# Procedure, Function, and Trigger Laporan Pengajuan Sub Kas Kecil #

-- Procedure Pengajuan Laporan Sub Kas Kecil
    DROP PROCEDURE IF EXISTS p_pengajuan_laporan_sub_kas_kecil;
    delimiter //
    
    CREATE PROCEDURE p_pengajuan_laporan_sub_kas_kecil(
        in id_pengajuan_param varchar(50),
        in id_sub_kas_kecil_param varchar(10),
        in tgl_param date,
        in sum_pengajuan_laporan_param double(12,2), -- sum harga asli di laporan
        in ket_param text,
        in modified_by_param varchar(50)
    )
    BEGIN
        DECLARE get_saldo double(12,2);

        -- get saldo sub kas kecil
        SELECT 
            saldo 
        INTO get_saldo 
        FROM sub_kas_kecil WHERE id = id_sub_kas_kecil_param;

        -- tambah tabel mutasi saldo sub kas kecil
        INSERT INTO mutasi_saldo_sub_kas_kecil
            (id_sub_kas_kecil, tgl, uang_masuk, uang_keluar, saldo, ket, created_by, modified_by)
        VALUES (
            id_sub_kas_kecil_param, 
            tgl_param, 
            0, 
            sum_pengajuan_laporan_param, 
            (get_saldo-sum_pengajuan_laporan_param), 
            ket_param,
            modified_by_param, modified_by_param
        );

        -- update saldo sub kas kecil
        UPDATE sub_kas_kecil 
        SET 
            saldo = (get_saldo-sum_pengajuan_laporan_param),
            modified_by = modified_by_param 
        WHERE id = id_sub_kas_kecil_param;

        -- update status_laporan (PENDING) dan tgl_laporan pengajuan sub kas kecil
        UPDATE pengajuan_sub_kas_kecil 
        SET 
            status_laporan = "1", 
            tgl_laporan = tgl_param,
            modified_by = modified_by_param
        WHERE id = id_pengajuan_param;

    END //

    delimiter ;
-- End Procedure Pengajuan Laporan Sub Kas Kecil

-- Procedure ganti status laporan dari pending ke perbaiki
-- BELUM FIX
    DROP PROCEDURE IF EXISTS p_ganti_status_perbaiki_laporan_sub_kas_kecil;
    delimiter //
    
    CREATE PROCEDURE p_ganti_status_perbaiki_laporan_sub_kas_kecil(
        IN id_pengajuan_param varchar(50),
        IN id_sub_kas_kecil_param varchar(10),
        IN tgl_mutasi_param date,
        IN ket_param text,
        IN modified_by_param varchar(50)
    )
    BEGIN

        DECLARE get_total_harga_asli double(12,2);
        DECLARE get_saldo_terbaru double(12,2);
        
        -- mendapatkan total harga asli laporan
        SELECT
            SUM(harga_asli)
        INTO get_total_harga_asli
        FROM detail_pengajuan_sub_kas_kecil WHERE id_pengajuan=id_pengajuan_param;

        -- mendapatkan saldo terbaru
        SELECT
            saldo
        INTO get_saldo_terbaru
        FROM v_sub_kas_kecil WHERE id=id_sub_kas_kecil_param;

        -- tambah mutasi sub kas kecil
        INSERT INTO mutasi_saldo_sub_kas_kecil (
            id_sub_kas_kecil, tgl, uang_masuk, uang_keluar, saldo, ket, created_by, modified_by
        ) VALUES (
            id_sub_kas_kecil_param,
            tgl_mutasi_param,
            get_total_harga_asli,
            0,
            (get_saldo_terbaru+get_total_harga_asli),
            CONCAT('PERUBAHAN STATUS LAPORAN KE PERBAIKI ', id_pengajuan_param),
            modified_by_param, modified_by_param
        );

        -- update saldo sub kas kecil
        UPDATE sub_kas_kecil
        SET
            saldo = (get_saldo_terbaru+get_total_harga_asli),
            modified_by = modified_by_param
        WHERE id = id_sub_kas_kecil_param;

        -- update status_laporan (PERBAIKI)
        UPDATE pengajuan_sub_kas_kecil
        SET 
            status_laporan = '2',
            ket = ket_param,
            modified_by = modified_by_param
        WHERE id = id_pengajuan_param;

    END//
    
    delimiter ;
-- End Procedure ganti status laporan dari pending ke perbaiki

-- Procedure edit_laporan_sub_kas_kecil v2
-- BELUM FIX
    DROP PROCEDURE IF EXISTS p_edit_laporan_sub_kas_kecil;
    delimiter //

    CREATE PROCEDURE p_edit_laporan_sub_kas_kecil (
        IN id_pengajuan_param varchar(50),
        IN id_sub_kas_kecil_param varchar(10),
        IN tgl_mutasi_param date,
        IN modified_by_param varchar(50)
    )
    BEGIN

        DECLARE get_total_harga_asli double(12,2);
        DECLARE get_saldo_terbaru double(12,2);

        -- mendapatkan total harga asli
        SELECT
            SUM(harga_asli)
        INTO get_total_harga_asli
        FROM detail_pengajuan_sub_kas_kecil WHERE id_pengajuan=id_pengajuan_param;

        -- mendapatkan saldo terbaru
        SELECT
            saldo
        INTO get_saldo_terbaru
        FROM v_sub_kas_kecil WHERE id=id_sub_kas_kecil_param;

        -- tambah mutasi
        INSERT INTO mutasi_saldo_sub_kas_kecil (
            id_sub_kas_kecil, tgl, uang_masuk, uang_keluar, saldo, ket, created_by, modified_by
        ) VALUES (
            id_sub_kas_kecil_param,
            tgl_mutasi_param,
            0,
            get_total_harga_asli,
            (get_saldo_terbaru-get_total_harga_asli),
            CONCAT('PENGAJUAN PERBAIKAN LAPORAN ', id_pengajuan_param),
            modified_by_param, modified_by_param
        );

        -- update saldo sub kas kecil
        UPDATE sub_kas_kecil
        SET
            saldo = (get_saldo_terbaru-get_total_harga_asli)
        WHERE id = id_sub_kas_kecil_param;

        -- update status_laporan (PENDING) dan tgl_laporan pengajuan sub kas kecil
        UPDATE pengajuan_sub_kas_kecil
        SET 
            status_laporan = '1',
            tgl_laporan = tgl_mutasi_param,
            modified_by = modified_by_param
        WHERE id = id_pengajuan_param;

    END //

    delimiter ;
-- End Procedure edit_laporan_sub_kas_kecil v2

# End Procedure, Function, and Trigger Laporan Pengajuan Sub Kas Kecil #