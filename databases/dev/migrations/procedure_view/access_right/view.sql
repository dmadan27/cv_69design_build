# View Access Right #

-- View access menu
CREATE OR REPLACE VIEW v_access_menu AS
SELECT
    am.id,
    am.level_id, ll.name level_name,
    am.menu_id, m.name menu_name, m.url, m.class, m.icon, m.position
FROM access_menu am
JOIN level_lookup ll ON ll.id = am.level_id
JOIN menu m ON m.id = am.menu_id;
-- End view access menu

# End View Access Right #