# Database Sistem Informasi CV 69 Design & Build #
# Versi 1.0 View #

-- View User
	-- view untuk semua user yang terdapat di sistem
	CREATE OR REPLACE VIEW v_user AS
		SELECT
			u.username, kb.nama, u.status, u.level FROM user u JOIN kas_besar kb  ON u.username=kb.email

		UNION

		SELECT
			u.username, kk.nama, u.status, u.level FROM user u JOIN kas_kecil kk  ON u.username=kk.email

		UNION

		SELECT
			u.username, skk.nama, u.status, u.level FROM user u JOIN sub_kas_kecil skk  ON u.username=skk.email;

-- View Kas Besar

-- View Kas Kecil

-- View Sub Kas Kecil

-- View Proyek
	-- View Get Sub Kas Kecil Proyek
	CREATE OR REPLACE VIEW v_get_skk_proyek AS
		SELECT
			lp.id, lp.id_proyek, skk.id id_skk, skk.nama
		FROM logistik_proyek lp
		JOIN sub_kas_kecil skk ON skk.id = lp.id_sub_kas_kecil;


-- View Detail Proyek

-- View Logistik Proyek
	-- skc -> skk belum
CREATE OR REPLACE VIEW v_proyek_logistik AS
	SELECT
    	p.id id_proyek, p.pemilik, p.tgl, p.pembangunan, p.luas_area, p.alamat, p.kota, p.estimasi, p.total, p.dp, p.cco, p.status,
        lp.id id_logistik_proyek, skk.id id_sub_kas_kecil, skk.nama, skk.alamat alamat_skk, skk.no_telp, skk.email, skk.foto, skk.saldo, skk.status status_skk
    FROM proyek p

    JOIN logistik_proyek lp ON lp.id_proyek=p.id
    JOIN sub_kas_kecil skk ON skk.id=lp.id_sub_kas_kecil;

-- View Bank

-- View Operasional
CREATE OR REPLACE VIEW v_operasional AS
	SELECT
		o.id, o.tgl, o.nama, o.nominal, o.ket, b.id id_bank, b.nama nama_bank
	FROM operasional o
	JOIN bank b ON b.id = o.id_bank;

-- View Operasional (updated)
CREATE OR REPLACE VIEW v_operasional_new AS
	SELECT op.id, op.tgl, op.nama, op.nominal, op.ket, b.id id_bank, b.nama nama_bank,
	   kb.id id_kas_besar, kb.nama nama_kas_besar, kb.no_telp no_telp,kb.email email
	  		FROM operasional op
	  			JOIN bank b ON b.id = op.id_bank
	  				JOIN kas_besar kb ON kb.id = op.id_kas_besar;  

-- View Mutasi Bank

-- View Mutasi Saldo Kas Kecil

-- View Mutasi Saldo Sub Kas Kecil

-- View Pengajuan Sub Kas Kecil
CREATE OR REPLACE VIEW v_pengajuan_sub_kas_kecil_full AS
	SELECT
		pskk.id id_pengajuan, pskk.id_sub_kas_kecil, pskk.id_proyek, pskk.tgl,
		pskk.total, pskk.dana_disetujui, pskk.status, pskk.status_laporan,
		dp.id id_detail, dp.nama, dp.jenis, dp.satuan, dp.qty, dp.harga, dp.subtotal,
		dp.harga_asli, dp.sisa, p.pemilik, p.pembangunan, p.kota
	FROM pengajuan_sub_kas_kecil pskk
	JOIN detail_pengajuan_sub_kas_kecil dp ON dp.id_pengajuan = pskk.id
	JOIN proyek p ON p.id = pskk.id_proyek;

-- View Detail Pengajuan Sub Kas Kecil

-- View Upload Laporan Pengajuan Sub Kas Kecil

-- View Pengajuan Kas Kecil
CREATE OR REPLACE VIEW v_pengajuan_kas_kecil AS
	SELECT
		pkk.id, pkk.nama, pkk.tgl, pkk.total, pkk.status,
		kk.id id_kas_kecil, kk.nama nama_kas_kecil
	FROM pengajuan_kas_kecil pkk
	JOIN kas_kecil kk ON kk.id = pkk.id_kas_kecil;

-- View Pengajuan Kas Kecil_rev2 (harus d review dulu)
CREATE OR REPLACE VIEW v_pengajuan_kas_kecil_rev2 AS
	SELECT
		pkk.id, pkk.nama, pkk.tgl, pkk.total, pkk.status,
		kk.id id_kas_kecil, kk.nama nama_kas_kecil,
		pskk.id_pengajuan_sub_kas_kecil
	FROM pengajuan_kas_kecil pkk
	JOIN kas_kecil kk ON kk.id = pkk.id_kas_kecil
	JOIN detail_pengajuan_kas_kecil pskk ON pkk.id = pskk.id_pengajuan_sub_kas_kecil;

-- View Detail Pengajuan Kas Kecil

-- View Operasional Proyek
CREATE OR REPLACE VIEW v_operasional_proyek AS
	SELECT opr.id , pr.id id_proyek, pr.pemilik pemilik_proyek, pr.pembangunan nama_pembangunan,
	  kb.id id_kas_besar, kb.nama nama_kas_besar, 
	  dst.id id_distributor, dst.nama nama_distributor, opr.tgl tgl_pengajuan, 
	  opr.nama nama_pengajuan, opr.jenis jenis_pengajuan, opr.total total_pengajuan, opr.sisa sisa_pengajuan, 
	  opr.status status_pengajuan,  opr.status_lunas status_lunas, opr.ket keterangan
	   FROM operasional_proyek opr
	   	JOIN proyek pr ON pr.id = opr.id_proyek 
   			JOIN kas_besar kb ON kb.id = opr.id_kas_besar
   				JOIN distributor dst ON dst.id = opr.id_distributor; 


-- View Detail Operasional Proyek

--

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

-- ========================================================================================

-- VIEW PENGAJUAN SUB KAS KECIL -> digunakan untuk mendapatkan info pengajuan sub kas kecil
CREATE OR REPLACE VIEW v_pengajuan_sub_kas_kecil AS
	SELECT 
		pskk.id, pskk.id_sub_kas_kecil, pskk.id_proyek, pskk.tgl, pskk.nama,
		COALESCE(SUM(dpskk.subtotal),0) biaya_pengajuan,
		COALESCE(pskk.dana_disetujui,0) dana_disetujui,
		pskk.status status_pengajuan,
		COALESCE(SUM(dpskk.harga_asli),0) biaya_laporan,
		pskk.status_laporan
	FROM pengajuan_sub_kas_kecil pskk
	JOIN detail_pengajuan_sub_kas_kecil dpskk ON pskk.id=dpskk.id_pengajuan
	GROUP BY pskk.id;
-- ========================================================================================


/* VIEW HISTORY PEMBELIAN
	LEGEND : Kebutuhan untuk melihat data pembelian di 'DISTRIBUTOR' dari setiap pengajuan Operasional Proyek
*/
CREATE OR REPLACE VIEW v_history_pembelian_operasionalProyek AS
SELECT
	 opr.id, opr.tgl, opr.nama, opr.total, opr.status_lunas,
		 d.id ID_DISTRIBUTOR, d.nama NAMA_DISTRIBUTOR, d.pemilik
	FROM operasional_proyek opr 
		JOIN	
			distributor d
				ON opr.id_distributor = d.id