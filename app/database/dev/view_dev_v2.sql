# View Bank #

-- View mutasi bank export
    CREATE OR REPLACE VIEW v_mutasi_bank_export AS
    SELECT 
        m.id_bank 'ID BANK',
        b.nama 'BANK',
        m.tgl TANGGAL,
        m.uang_masuk 'UANG MASUK',
        m.uang_keluar 'UANG KELUAR',
        m.saldo SALDO,
        m.ket KETERANGAN
    FROM mutasi_bank m 
    JOIN bank b ON b.id = m.id_bank;
-- End mutasi bank export

# End View Bank #

# View Dashboard #

-- View proyek dashboard
    CREATE OR REPLACE VIEW v_proyek_dashboard AS
    SELECT detail_proyek.id_proyek, SUM(detail_proyek.total)  AS total, proyek.status AS status 
        FROM detail_proyek
        JOIN proyek ON proyek.id = detail_proyek.id_proyek
    GROUP BY detail_proyek.id_proyek;
-- End View proyek dashboard

# End View Dashboard #

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

# View Kas Besar #

-- View Export Kas Besar
    CREATE OR REPLACE VIEW v_export_kas_besar AS
    SELECT
        id ID, nama NAMA, alamat ALAMAT, no_telp 'NO. TELEPON',
        email EMAIL, status STATUS
    FROM kas_besar;
-- End View Export Kas Besar

# End View Kas Besar #

# View Kas Kecil #

-- View Export Kas Kecil
    CREATE OR REPLACE VIEW v_export_kas_kecil AS
    SELECT
        id ID, nama NAMA, alamat ALAMAT, no_telp 'NO. TELEPON',
        email EMAIL, status STATUS
    FROM kas_kecil;
-- End View Export Kas Kecil

-- View saldo kas kecil
    CREATE OR REPLACE VIEW v_saldo_kas_kecil_export AS
    SELECT 
        mskk.id, mskk.id_kas_kecil ID_KAS_KECIL, mskk.tgl TANGGAL, 
        mskk.uang_masuk 'UANG MASUK', mskk.uang_keluar 'UANG KELUAR',
        mskk.ket KETERANGAN
    FROM mutasi_saldo_kas_kecil mskk;

-- End View saldo kas kecil

# End View Kas Kecil #

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
            ELSE 'BELUM DIKERJAKAN' END
        ) status_laporan, pskk.status_laporan status_order
	FROM pengajuan_sub_kas_kecil pskk
	LEFT JOIN detail_pengajuan_sub_kas_kecil dpskk ON dpskk.id_pengajuan = pskk.id
	JOIN sub_kas_kecil skk ON skk.id = pskk.id_sub_kas_kecil
	JOIN proyek p ON p.id = pskk.id_proyek
	GROUP BY pskk.id;
-- End View Laporan Pengajuan Sub Kas Kecil

# End View Laporan Pengajuan Sub Kas Kecil #

# View Mutasi Saldo Kas Kecil #

-- View export saldo kas kecil
    CREATE OR REPLACE VIEW v_saldo_kas_kecil_export AS
    SELECT mskk.id, mskk.id_kas_kecil 'ID KAS KECIL', mskk.tgl TANGGAL, 
        mskk.uang_masuk 'UANG MASUK', mskk.uang_keluar 'UANG KELUAR',
        mskk.ket KETERANGAN
    FROM mutasi_saldo_kas_kecil mskk;
-- End View export saldo kas kecil

# End View Mutasi Saldo Kas Kecil #

# View Operasional #

-- View Operasional
	CREATE OR REPLACE VIEW v_operasional AS
	SELECT 
		op.id, op.tgl, op.nama, op.nominal, op.jenis, op.ket, b.id id_bank, b.nama nama_bank,
		kb.id id_kas_besar, kb.nama nama_kas_besar, kb.no_telp no_telp, kb.email email
	FROM operasional op
	JOIN bank b ON b.id = op.id_bank
	JOIN kas_besar kb ON kb.id = op.id_kas_besar;  
-- End View Operasional

-- View Operasional Export
	CREATE OR REPLACE VIEW v_operasional_export AS
	SELECT 
		op.id 'ID', op.tgl 'TANGGAL', op.nama 'NAMA', op.nominal 'NOMINAL', op.jenis 'JENIS', op.ket 'KETERANGAN', b.id 'ID BANK', b.nama 'BANK',
		kb.id 'ID KAS BESAR', kb.nama 'KAS BESAR', kb.no_telp 'NO TELP', kb.email 'EMAIL'
	FROM operasional op
	JOIN bank b ON b.id = op.id_bank
	JOIN kas_besar kb ON kb.id = op.id_kas_besar;
-- End View Operasional Export

# End View Operasional #

# View Operasional Proyek #

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
-- End View Operasional Proyek

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
	LEFT JOIN distributor dst ON dst.id = opr.id_distributor;
-- End View Operasional Proyek (Export Excel)

-- View Detail Operasional Proyek
	CREATE OR REPLACE VIEW v_detail_operasional_proyek AS
	SELECT  detail_operasional_proyek.id, detail_operasional_proyek.id_operasional_proyek,
		bank.nama AS 'nama_bank', detail_operasional_proyek.nama, detail_operasional_proyek.tgl,
		detail_operasional_proyek.total
	FROM detail_operasional_proyek 
	JOIN bank ON bank.id = detail_operasional_proyek.id_bank;
-- End View Detail Operasional Proyek

-- View Detail Operasional Proyek Export
	CREATE OR REPLACE VIEW v_detail_operasional_proyek_export AS
	SELECT  detail_operasional_proyek.id 'ID DETAIL', detail_operasional_proyek.id_operasional_proyek 'ID',
		bank.nama AS 'BANK', detail_operasional_proyek.nama 'DETAIL OPERASIONAL', detail_operasional_proyek.tgl 'TANGGAL',
		detail_operasional_proyek.total 'TOTAL'
	FROM detail_operasional_proyek 
	JOIN bank ON bank.id = detail_operasional_proyek.id_bank;
-- End View Detail Operasional Proyek Export

/* VIEW HISTORY PEMBELIAN
Kebutuhan untuk melihat data pembelian di 'DISTRIBUTOR' dari setiap pengajuan Operasional Proyek
*/
	CREATE OR REPLACE VIEW v_history_pembelian_operasionalProyek AS
	SELECT
		opr.id, opr.tgl, opr.nama, opr.total, opr.status_lunas,
		d.id ID_DISTRIBUTOR, d.nama NAMA_DISTRIBUTOR, d.pemilik
	FROM operasional_proyek opr 
	LEFT JOIN distributor d ON opr.id_distributor = d.id;
-- End View History Pembelian

/* VIEW EXPORT HISTORY PEMBELIAN
	Kebutuhan untuk export data history pembelian ke Excel
*/
	CREATE OR REPLACE VIEW v_history_pembelian_operasionalProyek_export AS
	SELECT
		opr.id 'ID', opr.tgl 'TANGGAL', opr.nama 'NAMA OPERASIONAL', opr.total 'TOTAL', opr.status_lunas 'STATUS PEMBAYARAN',
		d.id 'ID DISTRIBUTOR', d.nama 'NAMA DISTRIBUTOR', d.pemilik 'PEMILIK'
	FROM operasional_proyek opr 
	LEFT JOIN distributor d ON opr.id_distributor = d.id;
-- End View Export history pembelian

# End View Operasional Proyek #

# View Pengajuan Kas Kecil #

-- View Pengajuan Kas Kecil
	CREATE OR REPLACE VIEW v_pengajuan_kas_kecil AS
	SELECT
		pkk.id, pkk.nama, pkk.tgl, pkk.total, pkk.total_disetujui, pkk.status,
		kk.id id_kas_kecil, kk.nama nama_kas_kecil
	FROM pengajuan_kas_kecil pkk
	JOIN kas_kecil kk ON kk.id = pkk.id_kas_kecil;
-- End View Pengajuan Kas Kecil

-- View Pengajuan Kas Kecil (Export)
	CREATE OR REPLACE VIEW v_pengajuan_kas_kecil_export AS
	SELECT
		pkk.id 'ID PENGAJUAN', pkk.nama 'PENGAJUAN', pkk.tgl 'TANGGAL', pkk.total 'TOTAL PENGAJUAN', pkk.total_disetujui 'TOTAL DISETUJUI',
		pkk.status 'STATUS', kk.id, kk.nama 'KAS KECIL'
	FROM pengajuan_kas_kecil pkk
	JOIN kas_kecil kk ON kk.id = pkk.id_kas_kecil;
-- End View Pengajuan Kas Kecil (Export)

# End View Pengajuan Kas Kecil #

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

# End View Pengajuan Sub Kas Kecil #

# View Proyek #

-- View Get Sub Kas Kecil Proyek
    CREATE OR REPLACE VIEW v_get_skk_proyek AS
    SELECT
        lp.id, lp.id_proyek, skk.id id_skk, skk.nama
    FROM logistik_proyek lp
    JOIN sub_kas_kecil skk ON skk.id = lp.id_sub_kas_kecil;
-- End View Get Sub Kas Kecil Proyek

-- View get pengeluaran operasional proyek
    CREATE OR REPLACE VIEW v_get_pengeluaran_operasional_proyek AS
    SELECT 
        p.id id_proyek, SUM(dop.total) total, op.status status 
    FROM detail_operasional_proyek dop JOIN operasional_proyek op ON op.id = dop.id_operasional_proyek 
    JOIN proyek p ON p.id = op.id_proyek 
    GROUP BY p.id;
-- End View get pengeluran operasional proyek

-- View get pengeluaran sub kas kecil
    CREATE OR REPLACE VIEW v_get_pengeluaran_sub_kas_kecil AS
    SELECT 
        p.id AS id_proyek, SUM(dpskk.harga_asli) AS total 
    FROM detail_pengajuan_sub_kas_kecil dpskk 
    JOIN pengajuan_sub_kas_kecil pskk ON pskk.id = dpskk.id_pengajuan
    JOIN proyek p ON p.id = pskk.id_proyek
    GROUP BY p.id;
-- End View get pengeluaran sub kas kecil

-- View Logistik Proyek
    -- skc -> skk belum
    CREATE OR REPLACE VIEW v_proyek_logistik AS
    SELECT
        p.id id_proyek, p.pemilik, p.tgl, p.pembangunan, p.luas_area, p.alamat, p.kota, p.estimasi, p.total, p.dp, p.cco, p.status,
        lp.id id_logistik_proyek, skk.id id_sub_kas_kecil, skk.nama, skk.alamat alamat_skk, skk.no_telp, skk.email, skk.foto, skk.saldo, skk.status status_skk
    FROM proyek p
    JOIN logistik_proyek lp ON lp.id_proyek=p.id
    JOIN sub_kas_kecil skk ON skk.id=lp.id_sub_kas_kecil;
-- End View Logistik Proyek

-- View Export Proyek List
    CREATE OR REPLACE VIEW v_export_proyek_list AS
    SELECT 
        id ID, pemilik PEMILIK, tgl TANGGAL, pembangunan PEMBANGUNAN, luas_area AS 'LUAS AREA',
        alamat ALAMAT, kota KOTA, estimasi AS 'ESTIMASI (BULAN)', total AS 'TOTAL (Rp)',
        dp AS 'DP (Rp)', cco AS 'CCO (Rp)', progress AS 'PROGRESS (%)', status STATUS
    FROM proyek;
    -- WHERE tgl BETWEEN '1996-07-01' AND '1996-07-31'
-- End View Export Proyek Lits

-- View Export Proyek view detail
    CREATE OR REPLACE VIEW v_export_proyek_detail AS
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
-- End View Export View Detail

# End View Proyek #

# View Sub Kas Kecil #

-- VIEW SUB KAS KECIL -> digunakan untuk mendapatkan informasi detail sub kas kecil
-- LEGEND : -> vp (VIEW PEMBANTU (tidak diakses oleh sistem, tapi diakses oleh view lain))

-- View Estimasi pengeluaran sub kas kecil
    -- untuk mendapatkan estimasi pengeluaran yang mungkin dilakukan oleh sub kas kecil
    CREATE OR REPLACE VIEW vp_estimasi_pengeluaran_skk AS
    SELECT 
	    id_sub_kas_kecil, sum(total) estimasi_pengeluaran_saldo
    FROM pengajuan_sub_kas_kecil
    WHERE (status=3 or status=4) AND (status_laporan!=3 && status_laporan!=4)
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

# View User #
-- View User
	-- view untuk semua user yang terdapat di sistem
	CREATE OR REPLACE VIEW v_user AS
	SELECT
		u.username, kb.nama, u.status, u.level 
	FROM user u 
	JOIN kas_besar kb ON u.username = kb.email

	UNION

	SELECT
		u.username, kk.nama, u.status, u.level 
	FROM user u 
	JOIN kas_kecil kk ON u.username = kk.email

	UNION

	SELECT
		u.username, skk.nama, u.status, u.level 
	FROM user u 
	JOIN sub_kas_kecil skk ON u.username = skk.email;
-- End View User

# End View User #