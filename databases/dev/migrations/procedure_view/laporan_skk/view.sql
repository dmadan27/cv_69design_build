# View Laporan Pengajuan Sub Kas Kecil #

-- View Laporan Pengajuan Sub Kas Kecil
	CREATE OR REPLACE VIEW v_laporan_pengajuan_sub_kas_kecil AS
	SELECT 
		pskk.id, pskk.id_sub_kas_kecil, skk.nama nama_skk, pskk.id_proyek, p.pemilik, p.pembangunan, p.kota,
		pskk.tgl_laporan tgl, pskk.nama nama_pengajuan, pskk.total, SUM(dpskk.harga_asli) total_asli, 
		(CASE 
            WHEN pskk.status_laporan = '1' THEN 'PENDING'
            WHEN pskk.status_laporan = '2' THEN 'PERBAIKI'
            WHEN pskk.status_laporan = '3' THEN 'DISETUJUI'
			WHEN pskk.status_laporan = '4' THEN 'DITOLAK'
            ELSE 'BELUM DIKERJAKAN' END
        ) status_laporan, pskk.status_laporan status_order, pskk.ket
	-- ) status_laporan, pskk.status_laporan status_order
	FROM pengajuan_sub_kas_kecil pskk
	LEFT JOIN detail_pengajuan_sub_kas_kecil dpskk ON dpskk.id_pengajuan = pskk.id
	JOIN sub_kas_kecil skk ON skk.id = pskk.id_sub_kas_kecil
	JOIN proyek p ON p.id = pskk.id_proyek
	GROUP BY pskk.id;
-- End View Laporan Pengajuan Sub Kas Kecil

# End View Laporan Pengajuan Sub Kas Kecil #