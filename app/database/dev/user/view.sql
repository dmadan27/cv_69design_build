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
