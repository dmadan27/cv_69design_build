# View Pengajuan Sub Kas Kecil #

-- View Pengajuan Sub Kas Kecil v2
    CREATE OR REPLACE VIEW v_pengajuan_sub_kas_kecil_v2 AS
    SELECT
        pskk.id, pskk.id_sub_kas_kecil, skk.nama nama_skk, pskk.tgl,
        pskk.id_proyek, p.pemilik, p.pembangunan,
        pskk.nama nama_pengajuan, pskk.total, pskk.dana_disetujui,
        (CASE 
            WHEN pskk.status = '1' THEN 'PENDING'
            WHEN pskk.status = '2' THEN 'PERBAIKI'
            WHEN pskk.status = '3' THEN 'DISETUJUI'
            WHEN pskk.status = '4' THEN 'LANGSUNG'
            ELSE 'DITOLAK' END
        ) status,
        (CASE 
            WHEN pskk.status_laporan = '1' THEN 'PENDING'
            WHEN pskk.status_laporan = '2' THEN 'PERBAIKI'
            WHEN pskk.status_laporan = '3' THEN 'DISETUJUI'
            ELSE 'BELUM DIKERJAKAN' END
        ) status_laporan, 
        pskk.tgl_laporan, pskk.status status_order, pskk.status_laporan status_laporan_order
    FROM pengajuan_sub_kas_kecil pskk
    JOIN proyek p ON p.id = pskk.id_proyek
    JOIN sub_kas_kecil skk ON skk.id = pskk.id_sub_kas_kecil;
-- End View Pengajuan Sub Kas Kecil v2

-- View Pengajuan Sub Kas Kecil
    CREATE OR REPLACE VIEW v_pengajuan_sub_kas_kecil_full AS
	SELECT
		pskk.id id_pengajuan, pskk.id_sub_kas_kecil, skk.nama nama_skk, pskk.id_proyek, pskk.tgl,
        pskk.nama nama_pengajuan, pskk.total, pskk.dana_disetujui, 
        (CASE 
            WHEN pskk.status = '1' THEN 'PENDING'
            WHEN pskk.status = '2' THEN 'PERBAIKI'
            WHEN pskk.status = '3' THEN 'DISETUJUI'
            WHEN pskk.status = '4' THEN 'LANGSUNG'
            ELSE 'DITOLAK' END
        ) status, 
        pskk.status_laporan,
		dp.id id_detail, dp.nama nama_detail, dp.jenis, dp.satuan, dp.qty, dp.harga, dp.subtotal,
		dp.harga_asli, dp.sisa, p.pemilik, p.pembangunan, p.kota
	FROM pengajuan_sub_kas_kecil pskk
	JOIN detail_pengajuan_sub_kas_kecil dp ON dp.id_pengajuan = pskk.id
	JOIN proyek p ON p.id = pskk.id_proyek
    JOIN sub_kas_kecil skk ON skk.id = pskk.id_sub_kas_kecil;
-- End View Pengajuan Sub Kas Kecil

-- View Pengajuan Sub Kas Kecil Export
    CREATE OR REPLACE VIEW v_pengajuan_sub_kas_kecil_export AS
    SELECT 
        id `ID PENGAJUAN`, 
        nama_pengajuan `NAMA PENGAJUAN`,
        pemilik `PEMILIK PROYEK`,
        pembangunan `NAMA PEMBANGUNAN PROYEK`,
        DATE_FORMAT(tgl, "%d/%m/%Y") `TANGGAL PENGAJUAN`,
        total `DANA DIAJUKAN`,
        status `STATUS PENGAJUAN`,
        dana_disetujui `DANA DISETUJUI`
    FROM `v_pengajuan_sub_kas_kecil_v2`
    ORDER BY tgl DESC;
-- End View Pengajuan Sub Kas Kecil Export

# End View Pengajuan Sub Kas Kecil #
