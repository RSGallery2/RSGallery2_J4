--
-- upgrade db to RSGallery2 J4x++ 5.0.12
--

-- datetime: DEFAULT '0000-00-00 00:00:00' brings error in J!4 mmysql


-- IF EXISTS(SELECT table_name
--             FROM INFORMATION_SCHEMA.TABLES
--            WHERE table_schema = '<your databasename>'
--              AND table_name LIKE '%yourtable%')
-- THEN
-- ...
-- ENDIF;

IF EXISTS(SELECT table_name
    FROM INFORMATION_SCHEMA.TABLES
    WHERE table_name LIKE '#__rsgallery2_galleries')
THEN
    ALTER TABLE #__rsgallery2_galleries MODIFY checked_out_time datetime NOT NULL;
ENDIF;

