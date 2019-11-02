--
-- galleries
--

CREATE TABLE IF NOT EXISTS `#__rsg2_galleries` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `alias` varchar(400) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL DEFAULT '',

  `description` text DEFAULT '' NOT NULL,

  `note` varchar(255) NOT NULL DEFAULT '',
  `published` tinyint(1) NOT NULL DEFAULT 0, 
  `checked_out` int(10) unsigned NOT NULL DEFAULT 0,
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00', 
  `params` text DEFAULT '' NOT NULL,

  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int(10) unsigned NOT NULL DEFAULT 0,
  `created_by_alias` varchar(255) NOT NULL DEFAULT '',
  `modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` int(10) unsigned NOT NULL DEFAULT 0, 

  `access` int(11) DEFAULT 0 NOT NULL,
  
  `hits` int(10) unsigned NOT NULL DEFAULT 0, 
  
--  `rtl` tinyint(4) NOT NULL DEFAULT 0,  
--  `language` char(7) NOT NULL DEFAULT '', 

--  `metakey` text NOT NULL,
--  `metadesc` text NOT NULL,
--  `metadata` text NOT NULL, 

--  `publish_up` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
--  `publish_down` datetime NOT NULL DEFAULT '0000-00-00 00:00:00', 

  `asset_id` int(11)  NOT NULL DEFAULT 0,
  `parent_id` int(11)  NOT NULL DEFAULT 0,
  `lft` int(11) DEFAULT 0 NOT NULL,
  `rgt` int(11) DEFAULT 0 NOT NULL,
  `level` integer DEFAULT 0 NOT NULL,
  `path` varchar(400) NOT NULL DEFAULT '',

  
  PRIMARY KEY (`id`),
  KEY `idx_access` (`access`),
  KEY `idx_checkout` (`checked_out`),
  KEY `idx_state` (`published`),
--  KEY `idx_catid` (`catid`),
--  KEY `idx_language` (`language`),
  KEY `idx_createdby` (`created_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE=utf8mb4_unicode_ci;

--
--  

--  `created_user_id` integer DEFAULT 0 NOT NULL,
--  `created_time` timestamp without time zone DEFAULT '1970-01-01 00:00:00' NOT NULL,
--  `modified_user_id` integer DEFAULT 0 NOT NULL,
--  `modified_time` timestamp without time zone DEFAULT '1970-01-01 00:00:00' NOT NULL,
--  `hits` integer DEFAULT 0 NOT NULL,
--  `language` varchar(7) DEFAULT '' NOT NULL,
--  `version` int(11) DEFAULT 1 NOT NULL,
--  `thumb_id` int(11) unsigned NOT NULL default '0',
