# Database Sistem Informasi CV 69 Design & Build #
# Versi 1.1 View #

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


-- View Proyek
	-- View Get Sub Kas Kecil Proyek
	CREATE OR REPLACE VIEW v_get_skk_proyek AS
		SELECT
			lp.id, lp.id_proyek, skk.id id_skk, skk.nama
		FROM logistik_proyek lp
		JOIN sub_kas_kecil skk ON skk.id = lp.id_sub_kas_kecil;


    -- View get pengeluaran operasional proyek
    CREATE OR REPLACE VIEW v_get_pengeluaran_operasional_proyek AS
        SELECT 
            p.id id_proyek, SUM(dop.total) total, op.status status 
        FROM detail_operasional_proyek dop JOIN operasional_proyek op ON op.id = dop.id_operasional_proyek 
        JOIN proyek p ON p.id = op.id_proyek 
        GROUP BY p.id;

    -- View get pengeluaran sub kas kecil
    CREATE OR REPLACE VIEW v_get_pengeluaran_sub_kas_kecil AS
        SELECT 
            p.id AS id_proyek, SUM(dpskk.harga_asli) AS total 
        FROM detail_pengajuan_sub_kas_kecil dpskk 
        JOIN pengajuan_sub_kas_kecil pskk ON pskk.id = dpskk.id_pengajuan
        JOIN proyek p ON p.id = pskk.id_proyek
        GROUP BY p.id;

    -- View Logistik Proyek
        -- skc -> skk belum
    CREATE OR REPLACE VIEW v_proyek_logistik AS
        SELECT
            p.id id_proyek, p.pemilik, p.tgl, p.pembangunan, p.luas_area, p.alamat, p.kota, p.estimasi, p.total, p.dp, p.cco, p.status,
            lp.id id_logistik_proyek, skk.id id_sub_kas_kecil, skk.nama, skk.alamat alamat_skk, skk.no_telp, skk.email, skk.foto, skk.saldo, skk.status status_skk
        FROM proyek p
        JOIN logistik_proyek lp ON lp.id_proyek=p.id
        JOIN sub_kas_kecil skk ON skk.id=lp.id_sub_kas_kecil;

    -- View Export Proyek List
    CREATE OR REPLACE VIEW v_export_proyek_list AS
        SELECT 
            id ID, pemilik PEMILIK, tgl TANGGAL, pembangunan PEMBANGUNAN, luas_area AS 'LUAS AREA',
            alamat ALAMAT, kota KOTA, estimasi AS 'ESTIMASI (BULAN)', total AS 'TOTAL (Rp)',
            dp AS 'DP (Rp)', cco AS 'CCO (Rp)', progress AS 'PROGRESS (%)', status STATUS
        FROM proyek;
        -- WHERE tgl BETWEEN '1996-07-01' AND '1996-07-31'

    -- View Export Proyek view detail
        SELECT 
            -- proyek
            p.id AS 'ID PROYEK', p.pemilik PEMILIK, p.tgl TANGGAL, p.pembangunan PEMBANGUNAN, p.luas_area AS 'LUAS AREA',
            p.alamat ALAMAT, p.kota KOTA, p.estimasi AS 'ESTIMASI (BULAN)', p.total AS 'TOTAL (Rp)',
            p.dp AS 'DP (Rp)', p.cco AS 'CCO (Rp)', p.progress AS 'PROGRESS (%)', p.status STATUS,
            
            -- detail logistik proyek (skk)
            skk.id AS 'ID SUB KAS KECIL', skk.nama,
            
            -- detail proyek (pembayaran)
            dp.tgl AS 'TANGGAL PEMBAYARAN', dp.nama PEMBAYARAN, b.nama AS 'BANK', dp.total AS 'TOTAL PEMBAYARAN',
            (CASE WHEN dp.is_DP = '1' THEN 'YA' ELSE 'TIDAK' END) AS 'DP'
            
        FROM proyek p
        JOIN logistik_proyek lp ON lp.id_proyek = p.id
        JOIN sub_kas_kecil skk ON skk.id = lp.id_sub_kas_kecil
        JOIN detail_proyek dp ON dp.id_proyek = p.id
        JOIN bank b ON b.id = dp.id_bank;
        -- WHERE p.id = ''

-- View Operasional
CREATE OR REPLACE VIEW v_operasional_new AS
	SELECT op.id, op.tgl, op.nama, op.nominal, op.ket, b.id id_bank, b.nama nama_bank,
	   kb.id id_kas_besar, kb.nama nama_kas_besar, kb.no_telp no_telp, kb.email email
    FROM operasional op
    JOIN bank b ON b.id = op.id_bank
    JOIN kas_besar kb ON kb.id = op.id_kas_besar;  


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

-- View Detail Pengajuan Sub Kas Kecil

-- View Upload Laporan Pengajuan Sub Kas Kecil

-- View Pengajuan Kas Kecil
CREATE OR REPLACE VIEW v_pengajuan_kas_kecil AS
	SELECT
		pkk.id, pkk.nama, pkk.tgl, pkk.total, pkk.total_disetujui, pkk.status,
		kk.id id_kas_kecil, kk.nama nama_kas_kecil
	FROM pengajuan_kas_kecil pkk
	JOIN kas_kecil kk ON kk.id = pkk.id_kas_kecil;

-- View Pengajuan Kas Kecil (Export)
CREATE OR REPLACE VIEW v_pengajuan_kas_kecil_export AS
	SELECT
		pkk.id 'ID PENGAJUAN', pkk.nama 'PENGAJUAN', pkk.tgl 'TANGGAL', pkk.total 'TOTAL PENGAJUAN', pkk.total_disetujui 'TOTAL DISETUJUI',
		pkk.status 'STATUS', kk.id, kk.nama 'KAS KECIL'
	FROM pengajuan_kas_kecil pkk
	JOIN kas_kecil kk ON kk.id = pkk.id_kas_kecil;

-- View Operasional Proyek
CREATE OR REPLACE VIEW v_operasional_proyek AS
	SELECT 
        opr.id, pr.id id_proyek, pr.pemilik pemilik_proyek, pr.pembangunan nama_pembangunan,
	    kb.id id_kas_besar, kb.nama nama_kas_besar, 
	    dst.id id_distributor, dst.nama nama_distributor, opr.tgl tgl_pengajuan, 
	    opr.nama nama_pengajuan, opr.jenis jenis_pengajuan, opr.total total_pengajuan, opr.sisa sisa_pengajuan, 
	    opr.status jenis_pembayaran,  opr.status_lunas status_lunas, opr.ket keterangan,
	    dopr.id_bank, b.nama nama_bank, dopr.nama nama_detail, dopr.tgl tgl_detail, dopr.total total_detail
    FROM operasional_proyek opr
    JOIN proyek pr ON pr.id = opr.id_proyek 
    JOIN kas_besar kb ON kb.id = opr.id_kas_besar
    LEFT JOIN distributor dst ON dst.id = opr.id_distributor
    LEFT JOIN detail_operasional_proyek dopr ON dopr.id_operasional_proyek = opr.id
	LEFT JOIN bank b ON b.id = dopr.id_bank; 

-- View Operasional Proyek (Export Excel)
CREATE OR REPLACE VIEW v_operasional_proyek_export AS
	SELECT 
        opr.id 'ID OPERASIONAL PROYEK', pr.id 'ID PROYEK', pr.pemilik 'PEMILIK', pr.pembangunan 'PROYEK',
	    kb.id 'ID KAS BESAR', kb.nama 'KAS BESAR', 
	    dst.id 'ID DISTRIBUTOR', dst.nama 'DISTRIBUTOR', opr.tgl 'TANGGAL', 
	    opr.nama 'NAMA OPERASIONAL', opr.jenis 'JENIS OPERASIONAL', opr.total 'TOTAL OPERASIONAL', opr.sisa 'SISA PEMBAYARAN', 
	    opr.status 'JENIS PEMBAYARAN',  opr.status_lunas 'STATUS PEMBAYARAN', opr.ket 'KETERANGAN'
    FROM operasional_proyek opr
    JOIN proyek pr ON pr.id = opr.id_proyek 
    JOIN kas_besar kb ON kb.id = opr.id_kas_besar
    LEFT JOIN distributor dst ON dst.id = opr.id_distributor

-- View Detail Operasional Proyek
CREATE OR REPLACE VIEW v_detail_operasional_proyek AS
SELECT  detail_operasional_proyek.id, detail_operasional_proyek.id_operasional_proyek,
	bank.nama AS 'nama_bank', detail_operasional_proyek.nama, detail_operasional_proyek.tgl,
	detail_operasional_proyek.total
  FROM detail_operasional_proyek 
  JOIN bank ON bank.id = detail_operasional_proyek.id_bank;

-- View Detail Operasional Proyek Export
CREATE OR REPLACE VIEW v_detail_operasional_proyek_export AS
SELECT  detail_operasional_proyek.id 'ID DETAIL', detail_operasional_proyek.id_operasional_proyek 'ID',
	bank.nama AS 'BANK', detail_operasional_proyek.nama 'DETAIL OPERASIONAL', detail_operasional_proyek.tgl 'TANGGAL',
	detail_operasional_proyek.total 'TOTAL'
  FROM detail_operasional_proyek 
  JOIN bank ON bank.id = detail_operasional_proyek.id_bank;

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
    LEFT JOIN distributor d ON opr.id_distributor = d.id;

/* VIEW HISTORY PEMBELIAN
	LEGEND : Kebutuhan untuk export data history pembelian ke Excel
*/
CREATE OR REPLACE VIEW v_history_pembelian_operasionalProyek_export AS
    SELECT
        opr.id 'ID', opr.tgl 'TANGGAL', opr.nama 'NAMA OPERASIONAL', opr.total 'TOTAL', opr.status_lunas 'STATUS PEMBAYARAN',
        d.id 'ID DISTRIBUTOR', d.nama 'NAMA DISTRIBUTOR', d.pemilik 'PEMILIK'
	FROM operasional_proyek opr 
    LEFT JOIN distributor d ON opr.id_distributor = d.id;

/*  History Distributor di menu 'DATA DISTRIBUTOR '
		
		Status : harus masih di review
*/

CREATE OR REPLACE VIEW v_history_distributor AS
    SELECT d.id , d.nama NAMA_DISTRIBUTOR, d.pemilik PEMILIK_DISTRIBUTOR, 
        opr.id  ID_OPERASIONAL_PROYEK, opr.nama NAMA_KEBUTUHAN
    FROM distributor d 
    JOIN operasional_proyek opr ON d.id = 	opr.id_distributor
    WHERE d.id = opr.id_distributor;


/* BERANDA KAS BESAR VIEW 
	by : Jaka Pratama
*/
CREATE OR REPLACE VIEW v_saldo_kaskecil_and_subkaskecil AS
			select id,nama, saldo from kas_kecil

			union all

			select id,nama, saldo from sub_kas_kecil;


	CREATE OR REPLACE VIEW v_proyek_berjalan_selesai AS
			select id,  pembangunan, pemilik, status, total from proyek where status = 'BERJALAN'

			UNION all

			SELECT id,  pembangunan, pemilik, status, total from proyek where status = 'SELESAI';

/* END BERANDA KAS BESAR VIEW*/