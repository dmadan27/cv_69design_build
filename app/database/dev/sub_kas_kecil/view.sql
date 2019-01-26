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