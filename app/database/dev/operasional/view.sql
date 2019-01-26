
    -- ================================================================= --
	-- Operasional -- Versi 25 Januari 2019 -- START --
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
	-- Operasional -- Versi 25 Januari 2019 -- END --
	-- ================================================================= --