# View Distributor #

-- View history distributor
    CREATE OR REPLACE VIEW v_history_distributor AS
    SELECT 
        d.id, d.nama nama_distributor, d.pemilik pemilik, 
        opr.tgl, opr.id id_operasional_proyek, opr.nama nama_operasional, opr.jenis,
        opr.total, opr.status jenis_pembayaran, opr.status_lunas status
    FROM distributor d 
    JOIN operasional_proyek opr ON d.id = opr.id_distributor
    WHERE d.id = opr.id_distributor;
-- End View history distributor

-- View Export Distributor
    CREATE OR REPLACE VIEW v_export_distributor AS
    SELECT
        id ID, nama NAMA, alamat ALAMAT, no_telp 'NO. TELEPON',
        pemilik PEMILIK, status STATUS
    FROM distributor;
-- End View Distributor

# End View Distributor #