-- Procedure Pengajuan Laporan Sub Kas Kecil
delimiter //
CREATE PROCEDURE pengajuan_laporan_sub_kas_kecil(
    in id_pengajuan_param varchar(50),
    in id_sub_kas_kecil_param varchar(10),
    in tgl_param date,
    in sum_pengajuan_laporan_param double(12,2), -- sum harga asli di laporan
    in ket_param text
)
BEGIN
    DECLARE get_saldo double(12,2);

    -- get saldo sub kas kecil
    SELECT saldo INTO get_saldo FROM sub_kas_kecil WHERE id = id_sub_kas_kecil_param;

    -- 1. update tabel pengajuan sub kas kecil
    -- update status_laporan
    UPDATE pengajuan_sub_kas_kecil SET status_laporan = "1" WHERE id = id_pengajuan_param;

    -- 2. update tabel sub kas kecil
    -- update saldo
    UPDATE sub_kas_kecil SET saldo = (get_saldo-sum_pengajuan_laporan_param) WHERE id = id_sub_kas_kecil_param;

    -- 3. insert tabel mutasi saldo sub kas kecil
    INSERT INTO mutasi_saldo_sub_kas_kecil
        (id_sub_kas_kecil, tgl, uang_masuk, uang_keluar, saldo, ket)
    VALUES
        (id_sub_kas_kecil_param, tgl_param, 0, sum_pengajuan_laporan_param, (get_saldo-sum_pengajuan_laporan_param), ket_param);

END//
delimiter ;

-- Procedure ganti status laporan dari pending ke perbaiki
-- BELUM FIX
delimiter //
CREATE PROCEDURE ganti_status_perbaiki_laporan_sub_kas_kecil(
    IN id_pengajuan_param varchar(50),
    IN id_sub_kas_kecil_param varchar(10),
    IN tgl_mutasi_param date
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
        id_sub_kas_kecil, tgl, uang_masuk, uang_keluar, saldo, ket
    ) VALUES (
        id_sub_kas_kecil_param,
        tgl_mutasi_param,
        get_total_harga_asli,
        0,
        (get_saldo_terbaru+get_total_harga_asli),
		CONCAT('PERUBAHAN STATUS LAPORAN KE PERBAIKI ', id_pengajuan_param)
    );

    -- update saldo sub kas kecil
    UPDATE sub_kas_kecil
    SET
        saldo=(get_saldo_terbaru+get_total_harga_asli)
    WHERE id=id_sub_kas_kecil_param;

    -- update status_laporan (PERBAIKI)
    UPDATE pengajuan_sub_kas_kecil
    SET 
        status_laporan='2'
    WHERE id=id_pengajuan_param;

END//
delimiter ;

-- Procedure edit_laporan_sub_kas_kecil v2
-- BELUM FIX
delimiter //
CREATE PROCEDURE edit_laporan_sub_kas_kecil (
    IN id_pengajuan_param varchar(50),
    IN id_sub_kas_kecil_param varchar(10),
    IN tgl_mutasi_param date
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
        id_sub_kas_kecil, tgl, uang_masuk, uang_keluar, saldo, ket
    ) VALUES (
        id_sub_kas_kecil_param,
        tgl_mutasi_param,
        0,
        get_total_harga_asli,
        (get_saldo_terbaru-get_total_harga_asli),
		CONCAT('PENGAJUAN PERBAIKAN LAPORAN ', id_pengajuan_param)
    );

    -- update saldo sub kas kecil
    UPDATE sub_kas_kecil
    SET
        saldo=(get_saldo_terbaru-get_total_harga_asli)
    WHERE id=id_sub_kas_kecil_param;

    -- update status_laporan pengajuan (PENDING)
    UPDATE pengajuan_sub_kas_kecil
    SET 
        status_laporan='1'
    WHERE id=id_pengajuan_param;

END//
delimiter ;