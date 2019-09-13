# Procedure, Function, and Trigger Increment #

-- Function get increment
    DROP FUNCTION IF EXISTS f_get_increment;
    delimiter //
    CREATE FUNCTION f_get_increment(table_name_param varchar(255)) RETURNS int 
    DETERMINISTIC
    BEGIN
        DECLARE table_id_param int;
        DECLARE last_increment_param int;

        SELECT id INTO table_id_param FROM menu WHERE table_name = table_name_param LIMIT 1;
        SELECT last_increment INTO last_increment_param FROM increment WHERE menu_id = table_id_param;

        UPDATE increment SET last_increment = (last_increment_param + 1) WHERE menu_id = table_id_param;

        RETURN (last_increment_param + 1);
    END //
    delimiter ;
-- End Function get increment

# End Procedure, Function, and Trigger Increment #