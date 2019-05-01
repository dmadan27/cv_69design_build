# View Operasional Proyek #

-- View Operasional Proyek
	CREATE OR REPLACE VIEW v_operasional_proyek AS
	SELECT 
		opr.id, pr.id id_proyek, pr.pemilik pemilik_proyek, pr.pembangunan nama_pembangunan,
		kb.id id_kas_besar, kb.nama nama_kas_besar, 
		dst.id id_distributor, dst.nama nama_distributor, opr.tgl tgl_operasional, 
		opr.nama nama_operasional, opr.jenis jenis_operasional, opr.total total, opr.sisa sisa_operasional, 
		opr.status jenis_pembayaran,  opr.status_lunas status_lunas, opr.ket keterangan
	FROM operasional_proyek opr
	JOIN proyek pr ON pr.id = opr.id_proyek 
	JOIN kas_besar kb ON kb.id = opr.id_kas_besar
	LEFT JOIN distributor dst ON dst.id = opr.id_distributor;
	-- LEFT JOIN detail_operasional_proyek dopr ON dopr.id_operasional_proyek = opr.id
	-- LEFT JOIN bank b ON b.id = dopr.id_bank;
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

-- View Detail Operasional Proyek Export v2
	CREATE OR REPLACE VIEW v_export_detail_operasional_proyek AS
	SELECT
		-- operasional proyek
		op.id `ID OPERASIONAL PROYEK`, op.nama `OPERASIONAL`, op.id_proyek `ID PROYEK`, op.tgl `TANGGAL OPERASIONAL`,

		-- detail operasional proyek
		dop.nama `DETAIL OPERASIONAL`, dop.tgl `TANGGAL DETAIL`, b.nama `BANK`, dop.total `TOTAL`
	FROM operasional_proyek op
	JOIN detail_operasional_proyek dop ON dop.id_operasional_proyek = op.id
	JOIN bank b ON b.id = dop.id_bank;
-- End View Detail Operasional Proyek Export v2

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