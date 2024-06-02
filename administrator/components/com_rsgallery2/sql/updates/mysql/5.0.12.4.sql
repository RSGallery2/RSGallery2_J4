#
# upgrade db to RSGallery2 J4x++ 5.0.12
#

# datetime: DEFAULT '0000-00-00 00:00:00' brings error in J!4 mmysql

ALTER TABLE IF EXISTS `#__rsgallery2_galleries` MODIFY `checked_out_time` datetime NOT NULL;
ALTER TABLE IF EXISTS `#__rsgallery2_galleries` MODIFY `date` datetime NOT NULL;

ALTER TABLE IF EXISTS `#__rsgallery2_files` MODIFY `checked_out_time` datetime NOT NULL;
ALTER TABLE IF EXISTS `#__rsgallery2_files` MODIFY `date` datetime NOT NULL;

ALTER TABLE IF EXISTS `#__rsgallery2_comments` MODIFY `checked_out_time` datetime NOT NULL;
ALTER TABLE IF EXISTS `#__rsgallery2_comments` MODIFY `datetime` datetime NOT NULL;


UPDATE IF EXISTS `#__rsgallery2_galleries` SET `checked_out_time` = '1980-01-01 00:00:00' WHERE `checked_out_time` = '0000-00-00 00:00:00';
UPDATE IF EXISTS `#__rsgallery2_galleries` SET `date` = '1980-01-01 00:00:00' WHERE `date` = '0000-00-00 00:00:00';

UPDATE IF EXISTS `#__rsgallery2_files` SET `checked_out_time` = '1980-01-01 00:00:00' WHERE `checked_out_time` = '0000-00-00 00:00:00';
UPDATE IF EXISTS `#__rsgallery2_files` SET `date` = '1980-01-01 00:00:00' WHERE `date` = '0000-00-00 00:00:00';

UPDATE IF EXISTS `#__rsgallery2_comments` SET `checked_out_time` = '1980-01-01 00:00:00' WHERE `checked_out_time` = '0000-00-00 00:00:00';
UPDATE IF EXISTS `#__rsgallery2_comments` SET `datetime` = '1980-01-01 00:00:00' WHERE `datetime` = '0000-00-00 00:00:00';


