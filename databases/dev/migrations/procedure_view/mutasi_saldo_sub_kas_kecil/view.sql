# View Mutasi Saldo Sub Kas Kecil #

-- View export mutasi saldo sub kas kecil
    CREATE OR REPLACE VIEW v_mutasi_saldo_sub_kas_kecil_export AS
    SELECT 
        id ID,
        id_sub_kas_kecil `ID SUB KAS KECIL`,
        tgl TANGGAL, 
        uang_masuk `UANG MASUK`, 
        uang_keluar `UANG KELUAR`, 
        saldo SALDO, 
        ket KETERANGAN
    FROM mutasi_saldo_sub_kas_kecil
    ORDER BY id DESC;
-- End View export mutasi saldo sub kas kecil

# End View Mutasi Saldo Sub Kas Kecil #