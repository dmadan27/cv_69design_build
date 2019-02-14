# View Kas Besar #

    -- View Export Kas Besar
        CREATE OR REPLACE VIEW v_export_kas_besar AS
        SELECT
            id ID, nama NAMA, alamat ALAMAT, no_telp 'NO. TELEPON',
            email EMAIL, status STATUS
        FROM kas_besar;
    -- End View Export Kas Besar

# End View Kas Besar #