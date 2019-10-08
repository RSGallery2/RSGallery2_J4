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
 