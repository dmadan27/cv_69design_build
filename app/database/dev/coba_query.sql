-- MENDAPATKAN DETAIL INFORMASI SUB KAS KECIL ================================================================
SELECT
	skk.id, skk.nama, skk.alamat, skk.no_telp, skk.email, skk.foto, skk.status,
	COALESCE(msskk.saldo,0) saldo,
	COALESCE(estimasi.estimasi_saldo_laporan) estimasi_saldo_pengeluaran,
	(COALESCE(msskk.saldo,0) - COALESCE(estimasi.estimasi_saldo_laporan)) sisa_saldo,
	tm.token
FROM sub_kas_kecil skk
-- mendapatkan data mutasi terakhir per id_sub_kas_kecil
LEFT JOIN (
	SELECT
		MAX(id) id, id_sub_kas_kecil
	FROM mutasi_saldo_sub_kas_kecil
	GROUP BY id_sub_kas_kecil
) AS saldo_terbaru ON skk.id=saldo_terbaru.id_sub_kas_kecil
-- mendapatkan saldo terbaru dari tabel mutasi sub kas kecil
LEFT JOIN mutasi_saldo_sub_kas_kecil msskk ON (msskk.id_sub_kas_kecil=skk.id) AND (msskk.id=saldo_terbaru.id)
-- mendapatkan estimasi saldo laporan dari tabel detail pengajuan sub kas kecil dan pengajuan sub kas kecil
LEFT JOIN (
	SELECT
		pskk.id_sub_kas_kecil, SUM(dpskk.subtotal) estimasi_saldo_laporan
	FROM detail_pengajuan_sub_kas_kecil dpskk
	RIGHT JOIN pengajuan_sub_kas_kecil pskk ON dpskk.id_pengajuan=pskk.id
	WHERE (pskk.status='3' OR pskk.status='4') AND (pskk.status_laporan IS NULL)
	GROUP BY pskk.id_sub_kas_kecil
) AS estimasi ON skk.id=estimasi.id_sub_kas_kecil
-- mendapatkan token dari tabel token mobile
LEFT JOIN token_mobile tm ON skk.email=tm.username;
-- ==============================================================================================================

-- MEMBUAT VIEW PENGAJUAN SUB KAS KECIL =========================================================================
SELECT 
	pskk.id, pskk.id_sub_kas_kecil, pskk.id_proyek, pskk.tgl, pskk.nama,
    COALESCE(SUM(dpskk.subtotal),0) biaya_pengajuan,
    COALESCE(pskk.dana_disetujui,0) dana_disetujui,
    pskk.status status_pengajuan,
    COALESCE(SUM(dpskk.harga_asli),0) biaya_laporan,
    pskk.status_laporan
FROM pengajuan_sub_kas_kecil pskk
JOIN detail_pengajuan_sub_kas_kecil dpskk ON pskk.id=dpskk.id_pengajuan
GROUP BY pskk.id
-- ==============================================================================================================