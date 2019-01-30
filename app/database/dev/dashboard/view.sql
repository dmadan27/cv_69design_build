CREATE OR REPLACE VIEW v_proyek_dashboard AS
    SELECT detail_proyek.id_proyek, SUM(detail_proyek.total)  AS total, proyek.status AS status 
        FROM detail_proyek
        JOIN proyek ON proyek.id = detail_proyek.id_proyek
    GROUP BY detail_proyek.id_proyek