# View Kas Kecil #

    -- View Export Kas Kecil
        CREATE OR REPLACE VIEW v_export_kas_kecil AS
        SELECT
            id ID, nama NAMA, alamat ALAMAT, no_telp 'NO. TELEPON',
            email EMAIL, status STATUS
        FROM kas_kecil;
    -- End View Export Kas Kecil

    -- View saldo kas kecil
        CREATE OR REPLACE VIEW v_saldo_kas_kecil_export AS
        SELECT 
            mskk.id, mskk.id_kas_kecil ID_KAS_KECIL, mskk.tgl TANGGAL, 
            mskk.uang_masuk 'UANG MASUK', mskk.uang_keluar 'UANG KELUAR',
            mskk.ket KETERANGAN
        FROM mutasi_saldo_kas_kecil mskk;

    -- End View saldo kas kecil

# End View Kas Kecil #