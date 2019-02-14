# Procedure, Function, and Trigger user #

-- Procedure Edit Status User
DROP PROCEDURE IF EXISTS p_edit_status_user;
delimiter //

CREATE PROCEDURE p_edit_status_user (
    in username_param varchar(50),
    in status_param enum('AKTIF', 'NONAKTIF'),
    in modified_by_param varchar(50)
)
BEGIN
    
    UPDATE user SET
        status = status_param,
        modified_by = modified_by_param
    WHERE username = username_param;

END //

delimiter ;
-- End Procedure Edit Status User

# End Procedure, Function, and Trigger user #