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