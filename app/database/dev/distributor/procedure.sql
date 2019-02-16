# Procedure, Function, and Trigger Distributor #

-- Procedure Tambah Distributor
    DROP PROCEDURE IF EXISTS p_tambah_distributor;
    delimiter //

    CREATE PROCEDURE p_tambah_distributor(
        in id_param varchar(50),
        in nama_param varchar(255),
        in alamat_param text,
        in no_telp_param varchar(50),
        in pemilik_param varchar(255),
        in status_param enum('AKTIF','NONAKTIF'),
        in created_by_param varchar(50)
    )
    BEGIN

        INSERT INTO distributor 
            (id, nama, alamat, no_telp, pemilik, status, created_by, modified_by)
        VALUES 
            (id_param, nama_param, alamat_param, no_telp_param, pemilik_param, 
            status_param, created_by_param, created_by_param);

    END //

    delimiter ;
-- End Procedure Tambah Distributor

-- Procedure Edit Distributor
    DROP PROCEDURE IF EXISTS p_edit_distributor;
    delimiter //

    CREATE PROCEDURE p_edit_distributor(
        in id_param varchar(50),
        in nama_param varchar(255),
        in alamat_param text,
        in no_telp_param varchar(50),
        in pemilik_param varchar(255),
        in status_param enum('AKTIF','NONAKTIF'),
        in modified_by_param varchar(50)
    )
    BEGIN

        UPDATE distributor SET
            nama = nama_param,
            alamat = alamat_param,
            no_telp = no_telp_param,
            pemilik = pemilik_param,
            status = status_param,
            modified_by = modified_by_param
        WHERE id = id_param;

    END //

    delimiter ;
-- End Procedure Edit Distributor

-- Procedure Delete Distributor
    DROP PROCEDURE IF EXISTS p_hapus_distributor;
    delimiter //

    CREATE PROCEDURE p_hapus_distributor(
        in id_param varchar(50)
    )
    BEGIN

        DELETE FROM distributor WHERE id = id_param;

    END //

    delimiter ;
-- End Procedure Delete Distributor

# End Procedure, Function, and Trigger Distributor #