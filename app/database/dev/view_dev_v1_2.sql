    -- ================================================================= --
	-- View Pengajuan Kas Kecil -- Versi 25 Januari 2019 -- START --
	-- ================================================================= --

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

    -- ================================================================= --
	-- View Pengajuan Kas Kecil -- Versi 25 Januari 2019 -- END --
	-- ================================================================= --

    
    -- ================================================================= --
	-- View Operasional -- Versi 25 Januari 2019 -- START --
	-- ================================================================= --

    -- View Operasional
	CREATE OR REPLACE VIEW v_operasional AS
		SELECT op.id , op.tgl, op.nama, op.nominal, op.jenis, op.ket, b.id id_bank, b.nama nama_bank,
		kb.id id_kas_besar, kb.nama nama_kas_besar, kb.no_telp no_telp, kb.email email
		FROM operasional op
		JOIN bank b ON b.id = op.id_bank
		JOIN kas_besar kb ON kb.id = op.id_kas_besar;  

	-- View Operasional Export
	CREATE OR REPLACE VIEW v_operasional_export AS
		SELECT op.id 'ID', op.tgl 'TANGGAL', op.nama 'NAMA', op.nominal 'NOMINAL', op.jenis 'JENIS', op.ket 'KETERANGAN', b.id 'ID BANK', b.nama 'BANK',
		kb.id 'ID KAS BESAR', kb.nama 'KAS BESAR', kb.no_telp 'NO TELP', kb.email 'EMAIL'
		FROM operasional op
		JOIN bank b ON b.id = op.id_bank
		JOIN kas_besar kb ON kb.id = op.id_kas_besar;  

	-- ================================================================= --
	-- View Operasional -- Versi 25 Januari 2019 -- END --
	-- ================================================================= --

    -- ================================================================= --
	-- View Operasional Proyek -- Versi 24 Januari 2019 -- START --
	-- ================================================================= --

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
		LEFT JOIN distributor dst ON dst.id = opr.id_distributor;

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

	/* VIEW HISTORY PEMBELIAN
		Kebutuhan untuk melihat data pembelian di 'DISTRIBUTOR' dari setiap pengajuan Operasional Proyek
	*/
	CREATE OR REPLACE VIEW v_history_pembelian_operasionalProyek AS
		SELECT
			opr.id, opr.tgl, opr.nama, opr.total, opr.status_lunas,
			d.id ID_DISTRIBUTOR, d.nama NAMA_DISTRIBUTOR, d.pemilik
		FROM operasional_proyek opr 
		LEFT JOIN distributor d ON opr.id_distributor = d.id;

	/* VIEW EXPORT HISTORY PEMBELIAN
		Kebutuhan untuk export data history pembelian ke Excel
	*/
	CREATE OR REPLACE VIEW v_history_pembelian_operasionalProyek_export AS
		SELECT
			opr.id 'ID', opr.tgl 'TANGGAL', opr.nama 'NAMA OPERASIONAL', opr.total 'TOTAL', opr.status_lunas 'STATUS PEMBAYARAN',
			d.id 'ID DISTRIBUTOR', d.nama 'NAMA DISTRIBUTOR', d.pemilik 'PEMILIK'
		FROM operasional_proyek opr 
		LEFT JOIN distributor d ON opr.id_distributor = d.id;

    -- ================================================================= --
	-- View Operasional Proyek -- Versi 24 Januari 2019 -- END --
	-- ================================================================= --

    -- ================================================================= --
	-- View Proyek -- START --
	-- ================================================================= --
        
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

    -- ================================================================= --
	-- View Proyek -- END --
	-- ================================================================= --

    -- ================================================================= --
	-- View Pengajuan Sub Kas Kecil -- START --
	-- ================================================================= --

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

    -- ================================================================= --
	-- View Pengajuan Sub Kas Kecil -- END --
	-- ================================================================= --

    -- ================================================================= --
	-- View Laporan Pengajuan Sub Kas Kecil -- START --
	-- ================================================================= --

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

    -- ================================================================= --
	-- View Laporan Pengajuan Sub Kas Kecil -- END --
	-- ================================================================= --

    -- ================================================================= --
	-- View Distributor -- START --
	-- ================================================================= --

    CREATE OR REPLACE VIEW v_history_distributor AS
    SELECT d.id , d.nama NAMA_DISTRIBUTOR, d.pemilik PEMILIK_DISTRIBUTOR, 
        opr.id  ID_OPERASIONAL_PROYEK, opr.nama NAMA_KEBUTUHAN
    FROM distributor d 
    JOIN operasional_proyek opr ON d.id = 	opr.id_distributor
    WHERE d.id = opr.id_distributor;

    -- ================================================================= --
	-- View Distributor -- END --
	-- ================================================================= --

    -- ================================================================= --
	-- View Sub Kas Kecil -- START --
	-- ================================================================= --

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

    -- ================================================================= --
	-- View Sub Kas Kecil -- END --
	-- ================================================================= --

    -- ================================================================= --
	-- View User -- START --
	-- ================================================================= --

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

    -- ================================================================= --
	-- View User -- END --
	-- ================================================================= --

	-- ================================================================= --
	-- View Mutasi Saldo Kas Kecil -- START --
	-- ================================================================= --

	CREATE OR REPLACE VIEW v_saldo_kas_kecil_export AS
    SELECT mskk.id , mskk.id_kas_kecil ID_KAS_KECIL, mskk.tgl TANGGAL, 
        mskk.uang_masuk  'UANG MASUK', mskk.uang_keluar 'UANG KELUAR',
        mskk.ket KETERANGAN
    FROM mutasi_saldo_kas_kecil mskk;

	-- ================================================================= --
	-- View Mutasi Saldo Kas Kecil -- END --
	-- ================================================================= --

	-- ================================================================= --
	-- View Bank -- START --
	-- ================================================================= --

	CREATE OR REPLACE VIEW v_mutasi_bank_export AS
	SELECT 
		mutasi_bank.id ID,
		mutasi_bank.id_bank,
		bank.nama 'BANK',
		mutasi_bank.tgl TANGGAL,
		mutasi_bank.uang_masuk 'UANG MASUK',
		mutasi_bank.uang_keluar 'UANG KELUAR',
		mutasi_bank.saldo SALDO,
		mutasi_bank.ket KETERANGAN
	FROM mutasi_bank
	JOIN bank ON bank.id = mutasi_bank.id_bank;

	-- ================================================================= --
	-- View Bank -- END --
	-- ================================================================= --

	-- ================================================================= --
	-- View Dashboard -- START --
	-- ================================================================= --

	-- View proyek untuk dashboard
	CREATE OR REPLACE VIEW v_proyek_dashboard AS
	SELECT detail_proyek.id_proyek, SUM(detail_proyek.total)  AS total, proyek.status AS status 
		FROM detail_proyek
		JOIN proyek ON proyek.id = detail_proyek.id_proyek
	GROUP BY detail_proyek.id_proyek;

	-- ================================================================= --
	-- View Bank -- END --
	-- ================================================================= --