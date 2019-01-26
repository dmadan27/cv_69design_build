    
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