CREATE OR REPLACE VIEW v_saldo_kas_kecil_export AS
    SELECT mskk.id , mskk.id_kas_kecil ID_KAS_KECIL, mskk.tgl TANGGAL, 
        mskk.uang_masuk  'UANG MASUK', mskk.uang_keluar 'UANG KELUAR',
        mskk.ket KETERANGAN
    FROM mutasi_saldo_kas_kecil mskk