CREATE OR REPLACE VIEW v_pengajuan_kas_kecil 
	AS
		SELECT pkk.id, pkk.id_kas_kecil, kk.nama, pkk.tgl,
			pkk.nama pkk.total, pkk.status
		FROM pengajuan_kas_kecil pkk 
			JOIN
				kas_kecil kk ON pkk.id_kas_kecil = kk.id;