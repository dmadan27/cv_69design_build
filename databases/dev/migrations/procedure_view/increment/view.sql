# View Increment #

-- View Increment
    CREATE OR REPLACE VIEW v_increment AS
    SELECT
        i.id, i.menu_id, m.name menu_name, m.table_name,
        i.mask, i.last_increment, i.description
    FROM increment i
    JOIN menu m ON m.id = i.menu_id;
-- End View Increment

# End View Increment #