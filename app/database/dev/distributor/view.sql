CREATE OR REPLACE VIEW v_history_distributor AS
    SELECT d.id , d.nama NAMA_DISTRIBUTOR, d.pemilik PEMILIK_DISTRIBUTOR, 
        opr.id  ID_OPERASIONAL_PROYEK, opr.nama NAMA_KEBUTUHAN
    FROM distributor d 
    JOIN operasional_proyek opr ON d.id = 	opr.id_distributor
    WHERE d.id = opr.id_distributor;