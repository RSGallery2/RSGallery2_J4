---
--- galleries
---

CREATE TABLE IF NOT EXISTS `#__rsgallery2_galleries` (
  `id` int(11) NOT NULL auto_increment,
  `parent` int(11) NOT NULL default 0,
  `name` varchar(255) NOT NULL default '',
  `alias` varchar(255) NOT NULL DEFAULT '',
  `description` text NOT NULL,
  `published` tinyint(1) NOT NULL default '0',
  `checked_out` int(11) unsigned NOT NULL default '0',
  `checked_out_time` datetime NOT NULL default '0000-00-00 00:00:00',
  `ordering` int(11) NOT NULL default '0',
  `date` datetime NOT NULL default '0000-00-00 00:00:00',
  `hits` int(11) NOT NULL default '0',
  `params` text NOT NULL,
  `user` tinyint(4) NOT NULL default '0',
  `uid` int(11) unsigned NOT NULL default '0',
  `allowed` varchar(100) NOT NULL default '0',
  `thumb_id` int(11) unsigned NOT NULL default '0',
  `asset_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'FK to the #__assets table.',
  `access` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

---
--- images
---

CREATE TABLE IF NOT EXISTS `#__rsgallery2_files` (
  `id` int(9) unsigned NOT NULL auto_increment,
  `name` varchar(255) NOT NULL default '',
  `alias` varchar(255) NOT NULL DEFAULT '',
  `descr` text,
  `gallery_id` int(9) unsigned NOT NULL default '0',
  `title` varchar(255) NOT NULL default '',
  `hits` int(11) unsigned NOT NULL default '0',
  `date` datetime NOT NULL default '0000-00-00 00:00:00',
  `rating` int(10) unsigned NOT NULL default '0',
  `votes` int(10) unsigned NOT NULL default '0',
  `comments` int(10) unsigned NOT NULL default '0',
  `published` tinyint(1) NOT NULL default '1',
  `checked_out` int(11) NOT NULL default '0',
  `checked_out_time` datetime NOT NULL default '0000-00-00 00:00:00',
  `ordering` int(9) unsigned NOT NULL default '0',
  `approved` tinyint(1) unsigned NOT NULL default '1',
  `userid` int(10) NOT NULL,
  `params` text NOT NULL,
  `asset_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'FK to the #__assets table.',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `UK_name` (`name`),
  KEY `id` (`id`)
)ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

---
--- comments
---

CREATE TABLE IF NOT EXISTS `#__rsgallery2_comments` (
  `id` int(11) NOT NULL auto_increment,
  `user_id` int(11) NOT NULL,
  `user_name` varchar(100) NOT NULL,
  `user_ip` varchar(50) NOT NULL default '0.0.0.0',
  `parent_id` int(11) NOT NULL default '0',
  `item_id` int(11) NOT NULL,
  `item_table` varchar(50) default NULL,
  `datetime` datetime NOT NULL,
  `subject` varchar(100) default NULL,
  `comment` text NOT NULL,
  `published` tinyint(1) NOT NULL default '1',
--- ToDo: `checked_out` int(11) default NULL,
  `checked_out` int(11) NOT NULL default '0',
--- ToDo: `checked_out_time` datetime default NULL,
  `checked_out_time` datetime NOT NULL default '0000-00-00 00:00:00',
  `ordering` int(11) NOT NULL,
  `params` text,
  `hits` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

---
--- configuration
---

CREATE TABLE IF NOT EXISTS `#__rsgallery2_config` (
  `id` int(9) unsigned NOT NULL auto_increment,
  `name` text NOT NULL,
  `value` text NOT NULL,
 PRIMARY KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


---
--- acl permissions
---

CREATE TABLE IF NOT EXISTS `#__rsgallery2_acl` (
  `id` int(11) NOT NULL auto_increment,
  `gallery_id` int(11) NOT NULL,
  `parent_id` int(11) NOT NULL default '0',
  `public_view` tinyint(1) NOT NULL default '1',
  `public_up_mod_img` tinyint(1) NOT NULL default '0',
  `public_del_img` tinyint(1) NOT NULL default '0',
  `public_create_mod_gal` tinyint(1) NOT NULL default '0',
  `public_del_gal` tinyint(1) NOT NULL default '0',
  `public_vote_view` tinyint( 1 ) NOT NULL default '1',
  `public_vote_vote` tinyint( 1 ) NOT NULL default '0',
  `registered_view` tinyint(1) NOT NULL default '1',
  `registered_up_mod_img` tinyint(1) NOT NULL default '1',
  `registered_del_img` tinyint(1) NOT NULL default '0',
  `registered_create_mod_gal` tinyint(1) NOT NULL default '1',
  `registered_del_gal` tinyint(1) NOT NULL default '0',
  `registered_vote_view` tinyint( 1 ) NOT NULL default '1',
  `registered_vote_vote` tinyint( 1 ) NOT NULL default '1',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


--
-- Table structure for table `#__categories`
--

CREATE TABLE IF NOT EXISTS "#__categoriesTmp" (
  "id" serial NOT NULL,
  "asset_id" bigint DEFAULT 0 NOT NULL,
  "parent_id" integer DEFAULT 0 NOT NULL,
  "lft" bigint DEFAULT 0 NOT NULL,
  "rgt" bigint DEFAULT 0 NOT NULL,
  "level" integer DEFAULT 0 NOT NULL,
  "path" varchar(255) DEFAULT '' NOT NULL,
  "extension" varchar(50) DEFAULT '' NOT NULL,
  "title" varchar(255) DEFAULT '' NOT NULL,
  "alias" varchar(255) DEFAULT '' NOT NULL,
  "note" varchar(255) DEFAULT '' NOT NULL,
  "description" text DEFAULT '' NOT NULL,
  "published" smallint DEFAULT 0 NOT NULL,
  "checked_out" bigint DEFAULT 0 NOT NULL,
  "checked_out_time" timestamp without time zone DEFAULT '1970-01-01 00:00:00' NOT NULL,
  "access" bigint DEFAULT 0 NOT NULL,
  "params" text DEFAULT '' NOT NULL,
  "metadesc" varchar(1024) DEFAULT '' NOT NULL,
  "metakey" varchar(1024) DEFAULT '' NOT NULL,
  "metadata" varchar(2048) DEFAULT '' NOT NULL,
  "created_user_id" integer DEFAULT 0 NOT NULL,
  "created_time" timestamp without time zone DEFAULT '1970-01-01 00:00:00' NOT NULL,
  "modified_user_id" integer DEFAULT 0 NOT NULL,
  "modified_time" timestamp without time zone DEFAULT '1970-01-01 00:00:00' NOT NULL,
  "hits" integer DEFAULT 0 NOT NULL,
  "language" varchar(7) DEFAULT '' NOT NULL,
  "version" bigint DEFAULT 1 NOT NULL,
  PRIMARY KEY ("id")
);
CREATE INDEX "#__categories_cat_idx" ON "#__categories" ("extension", "published", "access");
CREATE INDEX "#__categories_idx_access" ON "#__categories" ("access");
CREATE INDEX "#__categories_idx_checkout" ON "#__categories" ("checked_out");
CREATE INDEX "#__categories_idx_path" ON "#__categories" ("path");
CREATE INDEX "#__categories_idx_left_right" ON "#__categories" ("lft", "rgt");
CREATE INDEX "#__categories_idx_alias" ON "#__categories" ("alias");
CREATE INDEX "#__categories_idx_language" ON "#__categories" ("language");
 