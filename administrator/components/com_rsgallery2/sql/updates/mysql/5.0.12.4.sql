# IF EXISTS(SELECT table_name
#     FROM INFORMATION_SCHEMA.TABLES
#     WHERE table_schema = 'joomla4x'
#              AND table_name LIKE gsqft_rsgallery2_galleries)
# THEN
#     ALTER TABLE gsqft_rsgallery2_galleries MODIFY checked_out_time datetime NOT NULL;
# ENDIF;

IF EXISTS(SELECT table_name
    FROM INFORMATION_SCHEMA.TABLES
    WHERE table_schema = 'joomla4x'
             AND table_name = 'gsqft_rsgallery2_galleries')
BEGIN
    ALTER TABLE 'gsqft_rsgallery2_galleries' MODIFY checked_out_time datetime NOT NULL;
END;

