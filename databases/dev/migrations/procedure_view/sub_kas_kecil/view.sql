# View Sub Kas Kecil #

-- VIEW SUB KAS KECIL -> digunakan untuk mendapatkan informasi detail sub kas kecil
-- LEGEND : -> vp (VIEW PEMBANTU (tidak diakses oleh sistem, tapi diakses oleh view lain))

-- View Estimasi pengeluaran sub kas kecil
    -- untuk mendapatkan estimasi pengeluaran yang mungkin dilakukan oleh sub kas kecil
    CREATE OR REPLACE VIEW vp_estimasi_pengeluaran_skk AS
    SELECT 
	    id_sub_kas_kecil, sum(total) estimasi_pengeluaran_saldo
    FROM pengajuan_sub_kas_kecil
    WHERE (status=3 or status=4) AND (status_laporan=0 OR status_laporan=2)
    GROUP BY id_sub_kas_kecil;
-- End View Estiamsi pengeluaran sub kas kecil

-- View pembantu dana pengajuan sub kas kecil
    CREATE OR REPLACE VIEW vp_total_dana_pengajuan_skk as
    SELECT
        id_sub_kas_kecil, sum(total) as total
    FROM
        pengajuan_sub_kas_kecil
    WHERE
        status = 1
    GROUP BY id_sub_kas_kecil;
-- End View pembantu dana pengajuan sub kas kecil

-- View Sub Kas Kecil
    -- untuk mendapatkan informasi detai sub kas kecil
    CREATE OR REPLACE VIEW v_sub_kas_kecil AS
	SELECT
        skk.id, skk.nama, skk.alamat, skk.no_telp, skk.email, skk.foto, skk.status,
        COALESCE(skk.saldo,0) saldo,
        COALESCE(veps.estimasi_pengeluaran_saldo,0) estimasi_pengeluaran_saldo,
        (COALESCE(skk.saldo,0)-COALESCE(veps.estimasi_pengeluaran_saldo,0)) sisa_saldo,
        COALESCE(vptdp.total,0) total_pengajuan_pending
    FROM sub_kas_kecil skk
    LEFT JOIN vp_estimasi_pengeluaran_skk veps ON skk.id=veps.id_sub_kas_kecil
    LEFT JOIN vp_total_dana_pengajuan_skk vptdp ON skk.id=vptdp.id_sub_kas_kecil;
-- End View Sub Kas Kecil

# End View Sub Kas Kecil #