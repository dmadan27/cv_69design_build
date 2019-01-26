CREATE OR REPLACE VIEW v_mutasi_bank_export AS
SELECT 
    mutasi_bank.id ID,
    mutasi_bank.id_bank,
    bank.nama 'BANK',
    mutasi_bank.tgl TANGGAL,
    mutasi_bank.uang_masuk 'UANG MASUK',
    mutasi_bank.uang_keluar 'UANG KELUAR',
    mutasi_bank.saldo SALDO,
    mutasi_bank.ket KETERANGAN
FROM mutasi_bank
JOIN bank ON bank.id = mutasi_bank.id_bank