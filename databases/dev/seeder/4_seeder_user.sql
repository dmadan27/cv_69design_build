# Seeder User #

-- Password: ABCDE

-- Owner
INSERT INTO user (username, password, level, status) VALUES 
('owner1@69designbuild.com', '$2y$10$xGiq.6J6z9CUeze4B3oqAOquc6hXvYvZehkYV1brgWYrxjpoG5fGG', 'OWNER', 'AKTIF'),
('owner2@69designbuild.com', '$2y$10$xGiq.6J6z9CUeze4B3oqAOquc6hXvYvZehkYV1brgWYrxjpoG5fGG', 'OWNER', 'AKTIF');

-- INSERT INTO user (username, password, level, status) VALUES 
-- ('owner1@69designbuild.com', '$2y$10$xGiq.6J6z9CUeze4B3oqAOquc6hXvYvZehkYV1brgWYrxjpoG5fGG', 1, 1),
-- ('owner2@69designbuild.com', '$2y$10$xGiq.6J6z9CUeze4B3oqAOquc6hXvYvZehkYV1brgWYrxjpoG5fGG', 1, 1);

-- Kas Besar
CALL p_tambah_kas_besar ('KB001', 'KAS BESAR 1', NULL, NULL, 'kas_besar1@69designbuild.com', NULL, 'AKTIF', '$2y$10$xGiq.6J6z9CUeze4B3oqAOquc6hXvYvZehkYV1brgWYrxjpoG5fGG', 'KAS BESAR', NULL);
CALL p_tambah_kas_besar ('KB002', 'KAS BESAR 2', NULL, NULL, 'kas_besar2@69designbuild.com', NULL, 'AKTIF', '$2y$10$xGiq.6J6z9CUeze4B3oqAOquc6hXvYvZehkYV1brgWYrxjpoG5fGG', 'KAS BESAR', NULL);
CALL p_tambah_kas_besar ('KB003', 'KAS BESAR 3', NULL, NULL, 'kas_besar3@69designbuild.com', NULL, 'AKTIF', '$2y$10$xGiq.6J6z9CUeze4B3oqAOquc6hXvYvZehkYV1brgWYrxjpoG5fGG', 'KAS BESAR', NULL);

-- CALL p_tambah_kas_besar ('KB001', 'KAS BESAR 1', NULL, NULL, 'kas_besar1@69designbuild.com', NULL, 1, '$2y$10$xGiq.6J6z9CUeze4B3oqAOquc6hXvYvZehkYV1brgWYrxjpoG5fGG', 2, NULL);
-- CALL p_tambah_kas_besar ('KB002', 'KAS BESAR 2', NULL, NULL, 'kas_besar2@69designbuild.com', NULL, 1, '$2y$10$xGiq.6J6z9CUeze4B3oqAOquc6hXvYvZehkYV1brgWYrxjpoG5fGG', 2, NULL);
-- CALL p_tambah_kas_besar ('KB003', 'KAS BESAR 3', NULL, NULL, 'kas_besar3@69designbuild.com', NULL, 1, '$2y$10$xGiq.6J6z9CUeze4B3oqAOquc6hXvYvZehkYV1brgWYrxjpoG5fGG', 2, NULL);

-- Kas Kecil
CALL p_tambah_kas_kecil ('KK001', 'KAS KECIL 1', NULL, NULL, 'kas_kecil1@69designbuild.com', NULL, 100000, CURRENT_DATE, 'AKTIF', '$2y$10$xGiq.6J6z9CUeze4B3oqAOquc6hXvYvZehkYV1brgWYrxjpoG5fGG', 'KAS KECIL', NULL);
CALL p_tambah_kas_kecil ('KK002', 'KAS KECIL 2', NULL, NULL, 'kas_kecil2@69designbuild.com', NULL, 200000, CURRENT_DATE, 'AKTIF', '$2y$10$xGiq.6J6z9CUeze4B3oqAOquc6hXvYvZehkYV1brgWYrxjpoG5fGG', 'KAS KECIL', NULL);
CALL p_tambah_kas_kecil ('KK003', 'KAS KECIL 3', NULL, NULL, 'kas_kecil3@69designbuild.com', NULL, 300000, CURRENT_DATE, 'AKTIF', '$2y$10$xGiq.6J6z9CUeze4B3oqAOquc6hXvYvZehkYV1brgWYrxjpoG5fGG', 'KAS KECIL', NULL);
CALL p_tambah_kas_kecil ('KK004', 'KAS KECIL 4', NULL, NULL, 'kas_kecil4@69designbuild.com', NULL, 400000, CURRENT_DATE, 'AKTIF', '$2y$10$xGiq.6J6z9CUeze4B3oqAOquc6hXvYvZehkYV1brgWYrxjpoG5fGG', 'KAS KECIL', NULL);
CALL p_tambah_kas_kecil ('KK005', 'KAS KECIL 5', NULL, NULL, 'kas_kecil5@69designbuild.com', NULL, 500000, CURRENT_DATE, 'AKTIF', '$2y$10$xGiq.6J6z9CUeze4B3oqAOquc6hXvYvZehkYV1brgWYrxjpoG5fGG', 'KAS KECIL', NULL);

-- CALL p_tambah_kas_kecil ('KK001', 'KAS KECIL 1', NULL, NULL, 'kas_kecil1@69designbuild.com', NULL, 100000, CURRENT_DATE, 1, '$2y$10$xGiq.6J6z9CUeze4B3oqAOquc6hXvYvZehkYV1brgWYrxjpoG5fGG', 3, NULL);
-- CALL p_tambah_kas_kecil ('KK002', 'KAS KECIL 2', NULL, NULL, 'kas_kecil2@69designbuild.com', NULL, 200000, CURRENT_DATE, 1, '$2y$10$xGiq.6J6z9CUeze4B3oqAOquc6hXvYvZehkYV1brgWYrxjpoG5fGG', 3, NULL);
-- CALL p_tambah_kas_kecil ('KK003', 'KAS KECIL 3', NULL, NULL, 'kas_kecil3@69designbuild.com', NULL, 300000, CURRENT_DATE, 1, '$2y$10$xGiq.6J6z9CUeze4B3oqAOquc6hXvYvZehkYV1brgWYrxjpoG5fGG', 3, NULL);
-- CALL p_tambah_kas_kecil ('KK004', 'KAS KECIL 4', NULL, NULL, 'kas_kecil4@69designbuild.com', NULL, 400000, CURRENT_DATE, 1, '$2y$10$xGiq.6J6z9CUeze4B3oqAOquc6hXvYvZehkYV1brgWYrxjpoG5fGG', 3, NULL);
-- CALL p_tambah_kas_kecil ('KK005', 'KAS KECIL 5', NULL, NULL, 'kas_kecil5@69designbuild.com', NULL, 500000, CURRENT_DATE, 1, '$2y$10$xGiq.6J6z9CUeze4B3oqAOquc6hXvYvZehkYV1brgWYrxjpoG5fGG', 3, NULL);

-- Sub Kas Kecil
CALL p_tambah_sub_kas_kecil ('SKK001', 'SUB KAS KECIL 1', NULL, NULL, 'sub_kas_kecil1@69designbuild.com', NULL, 50000, CURRENT_DATE, 'AKTIF', '$2y$10$xGiq.6J6z9CUeze4B3oqAOquc6hXvYvZehkYV1brgWYrxjpoG5fGG', 'SUB KAS KECIL', NULL);
CALL p_tambah_sub_kas_kecil ('SKK002', 'SUB KAS KECIL 2', NULL, NULL, 'sub_kas_kecil2@69designbuild.com', NULL, 75000, CURRENT_DATE, 'AKTIF', '$2y$10$xGiq.6J6z9CUeze4B3oqAOquc6hXvYvZehkYV1brgWYrxjpoG5fGG', 'SUB KAS KECIL', NULL);
CALL p_tambah_sub_kas_kecil ('SKK003', 'SUB KAS KECIL 3', NULL, NULL, 'sub_kas_kecil3@69designbuild.com', NULL, 100000, CURRENT_DATE, 'AKTIF', '$2y$10$xGiq.6J6z9CUeze4B3oqAOquc6hXvYvZehkYV1brgWYrxjpoG5fGG', 'SUB KAS KECIL', NULL);
CALL p_tambah_sub_kas_kecil ('SKK004', 'SUB KAS KECIL 4', NULL, NULL, 'sub_kas_kecil4@69designbuild.com', NULL, 125000, CURRENT_DATE, 'AKTIF', '$2y$10$xGiq.6J6z9CUeze4B3oqAOquc6hXvYvZehkYV1brgWYrxjpoG5fGG', 'SUB KAS KECIL', NULL);
CALL p_tambah_sub_kas_kecil ('SKK005', 'SUB KAS KECIL 5', NULL, NULL, 'sub_kas_kecil5@69designbuild.com', NULL, 150000, CURRENT_DATE, 'AKTIF', '$2y$10$xGiq.6J6z9CUeze4B3oqAOquc6hXvYvZehkYV1brgWYrxjpoG5fGG', 'SUB KAS KECIL', NULL);

-- CALL p_tambah_sub_kas_kecil ('SKK001', 'SUB KAS KECIL 1', NULL, NULL, 'sub_kas_kecil1@69designbuild.com', NULL, 50000, CURRENT_DATE, 1, '$2y$10$xGiq.6J6z9CUeze4B3oqAOquc6hXvYvZehkYV1brgWYrxjpoG5fGG', 4, NULL);
-- CALL p_tambah_sub_kas_kecil ('SKK002', 'SUB KAS KECIL 2', NULL, NULL, 'sub_kas_kecil2@69designbuild.com', NULL, 75000, CURRENT_DATE, 1, '$2y$10$xGiq.6J6z9CUeze4B3oqAOquc6hXvYvZehkYV1brgWYrxjpoG5fGG', 4, NULL);
-- CALL p_tambah_sub_kas_kecil ('SKK003', 'SUB KAS KECIL 3', NULL, NULL, 'sub_kas_kecil3@69designbuild.com', NULL, 100000, CURRENT_DATE, 1, '$2y$10$xGiq.6J6z9CUeze4B3oqAOquc6hXvYvZehkYV1brgWYrxjpoG5fGG', 4, NULL);
-- CALL p_tambah_sub_kas_kecil ('SKK004', 'SUB KAS KECIL 4', NULL, NULL, 'sub_kas_kecil4@69designbuild.com', NULL, 125000, CURRENT_DATE, 1, '$2y$10$xGiq.6J6z9CUeze4B3oqAOquc6hXvYvZehkYV1brgWYrxjpoG5fGG', 4, NULL);
-- CALL p_tambah_sub_kas_kecil ('SKK005', 'SUB KAS KECIL 5', NULL, NULL, 'sub_kas_kecil5@69designbuild.com', NULL, 150000, CURRENT_DATE, 1, '$2y$10$xGiq.6J6z9CUeze4B3oqAOquc6hXvYvZehkYV1brgWYrxjpoG5fGG', 4, NULL);

# End Seeder User #