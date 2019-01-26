    -- ================================================================= --
	-- Operasional Proyek -- Versi 24 Januari 2019 -- END --
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
	-- Operasional Proyek -- Versi 24 Januari 2019 -- END --
	-- ================================================================= --