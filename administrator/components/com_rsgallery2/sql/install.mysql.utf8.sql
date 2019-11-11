--
-- galleries
--

CREATE TABLE IF NOT EXISTS `#__rsg2_galleries` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `alias` varchar(400) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL DEFAULT '',
  `description` text NOT NULL,
  `note` varchar(255) NOT NULL DEFAULT '',  
  `thumb_id` int(11) unsigned NOT NULL default '0',
  `params` text NOT NULL,
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
  `asset_id` int(11)  NOT NULL DEFAULT 0,
  `access` int(10) unsigned NOT NULL DEFAULT 0,

--  `rtl` tinyint(4) NOT NULL DEFAULT 0,  
--  `language` char(7) NOT NULL DEFAULT '', 

--  `metakey` text NOT NULL,
--  `metadesc` text NOT NULL,
--  `metadata` text NOT NULL, 

--  `publish_up` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
--  `publish_down` datetime NOT NULL DEFAULT '0000-00-00 00:00:00', 

--  KEY `idx_catid` (`catid`),
--  KEY `idx_language` (`language`),

  PRIMARY KEY (`id`),
  KEY `idx_access` (`access`),  
  KEY `idx_checkout` (`checked_out`),
  KEY `idx_state` (`published`),
  KEY `idx_left_right` (`lft`, `rgt`), 
  KEY `idx_createdby` (`created_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE=utf8mb4_unicode_ci;

--
-- images / files
--

CREATE TABLE IF NOT EXISTS `#__rsg2_images` (
  `id` serial NOT NULL,
  `name` varchar(255) NOT NULL default '',
  `alias` varchar(255) NOT NULL DEFAULT '',
  `description` text NOT NULL,
  
  `gallery_id` int(9) unsigned NOT NULL default '0',
  `title` varchar(255) NOT NULL default '',
  `date` datetime NOT NULL default '0000-00-00 00:00:00',

  `params` text NOT NULL,
  
  `rating` int(10) unsigned NOT NULL default '0',
  `votes` int(10) unsigned NOT NULL default '0',
  `comments` int(10) unsigned NOT NULL default '0',
  
  `published` tinyint(1) NOT NULL default '1',
  `hits` int(11) unsigned NOT NULL default '0',
  
  `checked_out` int(10) unsigned NOT NULL DEFAULT 0,
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00', 
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int(10) unsigned NOT NULL DEFAULT 0,
  `created_by_alias` varchar(255) NOT NULL DEFAULT '',
  `modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` int(10) unsigned NOT NULL DEFAULT 0, 

  `ordering` int(9) unsigned NOT NULL default '0',
  `approved` tinyint(1) unsigned NOT NULL default '1',

  `access` int(10) NOT NULL DEFAULT 0,   
  `asset_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'FK to the #__assets table.',
  
  PRIMARY KEY  (`id`),
#  UNIQUE KEY `UK_name` (`name`),
#  KEY `id` (`id`)
  KEY `idx_access` (`access`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE=utf8mb4_unicode_ci;


--
-- acl permissions
--

#CREATE TABLE IF NOT EXISTS `#__rsgallery2_acl` (
#  `id` serial NOT NULL,
#  `gallery_id` int(11) NOT NULL,
#  `parent_id` int(11) NOT NULL default '0',
#  `public_view` tinyint(1) NOT NULL default '1',
#  `public_up_mod_img` tinyint(1) NOT NULL default '0',
#  `public_del_img` tinyint(1) NOT NULL default '0',
#  `public_create_mod_gal` tinyint(1) NOT NULL default '0',
#  `public_del_gal` tinyint(1) NOT NULL default '0',
#  `public_vote_view` tinyint( 1 ) NOT NULL default '1',
#  `public_vote_vote` tinyint( 1 ) NOT NULL default '0',
#  `registered_view` tinyint(1) NOT NULL default '1',
#  `registered_up_mod_img` tinyint(1) NOT NULL default '1',
#  `registered_del_img` tinyint(1) NOT NULL default '0',
#  `registered_create_mod_gal` tinyint(1) NOT NULL default '1',
#  `registered_del_gal` tinyint(1) NOT NULL default '0',
#  `registered_vote_view` tinyint( 1 ) NOT NULL default '1',
#  `registered_vote_vote` tinyint( 1 ) NOT NULL default '1',
#  PRIMARY KEY  (`id`)
#) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE=utf8mb4_unicode_ci;


--
-- comments
--

#CREATE TABLE IF NOT EXISTS `#__rsgallery2_comments` (
#  `id` serial NOT NULL,
#  `user_id` int(11) NOT NULL,
#  `user_name` varchar(100) NOT NULL,
#  `user_ip` varchar(50) NOT NULL default '0.0.0.0',
#  `parent_id` int(11) NOT NULL default '0',
#  `item_id` int(11) NOT NULL,
#  `item_table` varchar(50) default NULL,
#  `datetime` datetime NOT NULL,
#  `subject` varchar(100) default NULL,
#  `comment` text NOT NULL,
#  `published` tinyint(1) NOT NULL default '1',
#--- ToDo: `checked_out` int(11) default NULL,
#  `checked_out` int(11) NOT NULL default '0',
#--- ToDo: `checked_out_time` datetime default NULL,
#  `checked_out_time` datetime NOT NULL default '0000-00-00 00:00:00',
#  `ordering` int(11) NOT NULL,
#  `params` text,
#  `hits` int(11) NOT NULL,
#  PRIMARY KEY  (`id`)
#) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE=utf8mb4_unicode_ci;

--
-- configuration
--

#CREATE TABLE IF NOT EXISTS `#__rsgallery2_config` (
#  `id` serial NOT NULL,
#  `name` text NOT NULL,
#  `value` text NOT NULL,
# PRIMARY KEY `id` (`id`)
#) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE=utf8mb4_unicode_ci;


--
-- Media query / Image resolutions
--

#CREATE TABLE IF NOT EXISTS `#__rsg2_media_break` (
#  `id` serial NOT NULL,
#
#  `image_id` int(9) unsigned NOT NULL default '0',
#  'breakpoint' double
#
##http://www.w3.org/TR/css3-mediaqueries/
#
##Media type ‘aural’, ‘braille’, ‘handheld’, ‘print’, ‘projection’, ‘screen’, ‘tty’, ‘tv’
#
## breakpopints: width, 
##	Viewport-Breite (z.B.: Der zur Verfügung stehende Platz innerhalb des Browserfensters)
## Beispiel: @media handheld and (min-width: 20em) { ... }heightViewport-Höhe (z.B.: Der zur Verfügung stehende Platz innerhalb des Browserfensters)
## Beispiel: @media screen and (max-height: 700px) { … } device-widthBreite des Mediums (Smartphone-Bildschirm, Monitorgröße etc. )
## Beispiel: @media screen and (device-width: 800px) { … }device-heightHöhe des Mediums (Smartphone-Bildschirm, Monitorgröße etc.)
## Beispiel: @media screen and (device-height: 400px) { … }orientationBeschreibt ob ein Gerät im Querformat (landscape) oder im Hochformat gehalten wird (portrait).
#
#
#
#  PRIMARY KEY  (`id`),
#  UNIQUE KEY `UK_name` (`name`),
#  KEY `id` (`id`)
#)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE=utf8mb4_unicode_ci;


