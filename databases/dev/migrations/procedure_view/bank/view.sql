# View Access Bank #

-- View mutasi bank export
    CREATE OR REPLACE VIEW v_mutasi_bank_export AS
    SELECT 
        m.id_bank id_bank,
        b.nama 'BANK',
        m.tgl TANGGAL,
        m.uang_masuk 'UANG MASUK',
        m.uang_keluar 'UANG KELUAR',
        m.saldo SALDO,
        m.ket KETERANGAN
    FROM mutasi_bank m 
    JOIN bank b ON b.id = m.id_bank;
-- End mutasi bank export

# End View Bank #