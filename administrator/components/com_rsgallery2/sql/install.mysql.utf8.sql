--
-- galleries
--

CREATE TABLE IF NOT EXISTS `#__rsg2_galleries` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `alias` varchar(400) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL DEFAULT '',
  `description` text DEFAULT '' NOT NULL,
  `note` varchar(255) NOT NULL DEFAULT '',  
  `params` text DEFAULT '' NOT NULL,
  `thumb_id` int(11) unsigned NOT NULL default '0',

  `published` tinyint(1) NOT NULL DEFAULT 0, 
  `hits` int(10) unsigned NOT NULL DEFAULT 0, 
  
  `checked_out` int(10) unsigned NOT NULL DEFAULT 0,
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00', 
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int(10) unsigned NOT NULL DEFAULT 0,
  `created_by_alias` varchar(255) NOT NULL DEFAULT '',
  `modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` int(10) unsigned NOT NULL DEFAULT 0, 

  `lft` int(11) DEFAULT 0 NOT NULL,
  `rgt` int(11) DEFAULT 0 NOT NULL,
  `level` integer DEFAULT 0 NOT NULL,
  `path` varchar(400) NOT NULL DEFAULT '',
  `parent_id` int(11)  NOT NULL DEFAULT 0,
  `access` int(10) NOT NULL DEFAULT 0, 
  
  `asset_id` int(11)  NOT NULL DEFAULT 0,
  
--  `rtl` tinyint(4) NOT NULL DEFAULT 0,  
--  `language` char(7) NOT NULL DEFAULT '', 

--  `metakey` text NOT NULL,
--  `metadesc` text NOT NULL,
--  `metadata` text NOT NULL, 

--  `publish_up` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
--  `publish_down` datetime NOT NULL DEFAULT '0000-00-00 00:00:00', 
  `access` int(10) unsigned NOT NULL DEFAULT 0, 
  
  PRIMARY KEY (`id`),
--  KEY `idx_access` (`access`),
  KEY `idx_checkout` (`checked_out`),
  KEY `idx_state` (`published`),
	KEY `idx_left_right` (`lft`, `rgt`), 
--  KEY `idx_catid` (`catid`),
--  KEY `idx_language` (`language`),
  KEY `idx_createdby` (`created_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE=utf8mb4_unicode_ci;

--
--  

