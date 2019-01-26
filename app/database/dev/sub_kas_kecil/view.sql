-- VIEW SUB KAS KECIL -> digunakan untuk mendapatkan informasi detail sub kas kecil
-- LEGEND : -> vp (VIEW PEMBANTU (tidak diakses oleh sistem, tapi diakses oleh view lain))

-- untuk mendapatkan estimasi pengeluaran yang mungkin dilakukan oleh sub kas kecil

CREATE OR REPLACE VIEW vp_estimasi_pengeluaran_skk AS
	SELECT
		pskk.id_sub_kas_kecil, SUM(dpskk.subtotal) estimasi_pengeluaran_saldo
	FROM detail_pengajuan_sub_kas_kecil dpskk
	RIGHT JOIN pengajuan_sub_kas_kecil pskk ON dpskk.id_pengajuan=pskk.id
	WHERE (pskk.status='3' OR pskk.status='4') AND (pskk.status_laporan IS NULL)
	GROUP BY pskk.id_sub_kas_kecil;

-- untuk mendapatkan informasi detai sub kas kecil
CREATE OR REPLACE VIEW v_sub_kas_kecil AS
	SELECT
		skk.id, skk.nama, skk.alamat, skk.no_telp, skk.email, skk.foto, skk.status,
		COALESCE(skk.saldo,0) saldo,
		COALESCE(veps.estimasi_pengeluaran_saldo,0) estimasi_pengeluaran_saldo,
		(COALESCE(skk.saldo,0)-COALESCE(veps.estimasi_pengeluaran_saldo,0)) sisa_saldo,
		tm.token
	FROM sub_kas_kecil skk
	LEFT JOIN vp_estimasi_pengeluaran_skk veps ON skk.id=veps.id_sub_kas_kecil
	LEFT JOIN token_mobile tm ON skk.email=tm.username;