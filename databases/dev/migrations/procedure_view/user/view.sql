# View User #
-- View User
	-- view untuk semua user yang terdapat di sistem
	CREATE OR REPLACE VIEW v_all_user AS

	SELECT
		u.username, o.nama, u.status, u.level 
	FROM user u 
	JOIN owner o ON u.username = o.email

	UNION

	SELECT
		u.username, kb.nama, u.status, u.level 
	FROM user u 
	JOIN kas_besar kb ON u.username = kb.email

	UNION

	SELECT
		u.username, kk.nama, u.status, u.level 
	FROM user u 
	JOIN kas_kecil kk ON u.username = kk.email

	UNION

	SELECT
		u.username, skk.nama, u.status, u.level 
	FROM user u 
	JOIN sub_kas_kecil skk ON u.username = skk.email;
-- End View User

-- View user owner
	CREATE OR REPLACE VIEW v_user_owner AS
	SELECT
		u.username, o.id, o.nama, o.alamat, o.no_telp, o.email, o.foto, o.status
	FROM user u
	JOIN owner o ON o.email = u.username;
-- End View user owner

-- View user kas besar
	CREATE OR REPLACE VIEW v_user_kas_besar AS
	SELECT
		u.username, kb.id, kb.nama, kb.alamat, kb.no_telp, kb.email, kb.foto, kb.status
	FROM user u
	JOIN kas_besar kb ON kb.email = u.username;
-- End View user kas besar

-- View user kas kecil
	CREATE OR REPLACE VIEW v_user_kas_kecil AS
	SELECT
		u.username, kk.id, kk.nama, kk.alamat, kk.no_telp, kk.email, kk.foto, kk.status
	FROM user u
	JOIN kas_kecil kk ON kk.email = u.username;
-- End View user kas kecil

-- View user sub kas kecil
	CREATE OR REPLACE VIEW v_user_sub_kas_kecil AS
	SELECT
		u.username, skk.id, skk.nama, skk.alamat, skk.no_telp, skk.email, skk.foto, skk.status
	FROM user u
	JOIN sub_kas_kecil skk ON skk.email = u.username;
-- End View user sub kas kecil

# End View User #