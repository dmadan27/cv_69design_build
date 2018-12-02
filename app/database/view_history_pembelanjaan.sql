
/*
	id operasional proyek
	tgl_operasinal proyek
	nama kebutuhan oper. proyek
	total
	status_lunas
	id distributor
	nama distributor
	pemilik distributor

*/

CREATE OR REPLACE VIEW v_history_pembelian_operasionalProyek AS
SELECT
	 opr.id, opr.tgl, opr.nama, opr.total, opr.status_lunas,
		 d.id ID_DISTRIBUTOR, d.nama NAMA_DISTRIBUTOR, d.pemilik
	FROM operasional_proyek opr 
		JOIN	
			distributor d
				ON opr.id_distributor = d.id