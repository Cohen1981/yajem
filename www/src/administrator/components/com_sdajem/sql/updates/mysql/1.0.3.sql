CREATE TABLE IF NOT EXISTS `#__sdajem_fittings` (
	`id` int(10) unsigned NOT NULL auto_increment,
	`access` int(10) unsigned NOT NULL DEFAULT 0,
	`alias` varchar(400),
	`created` datetime,
	`created_by` int(10) unsigned,
	`created_by_alias` varchar(255),
	`published` tinyint(1) NOT NULL DEFAULT 1,
	`publish_up` datetime,
	`publish_down` datetime,
	`state` tinyint(3) NOT NULL DEFAULT 0,
	`ordering` int(11) NOT NULL DEFAULT 0,
	`title` VARCHAR(255) NOT NULL,
	`description` MEDIUMTEXT NULL,
	`length` DECIMAL(10,2) null,
  	`width` DECIMAL(10,2) null,
  	`standard` tinyint(1) null default 0,
  	`fittingType` int(10) unsigned,
  	`user_id` int(10) unsigned,
	PRIMARY KEY (`id`),
	KEY `idx_state` (`published`),
	KEY `idx_created_by` (`created_by`),
	KEY `idx_ftype` (`fittingType`)
) ENGINE=InnoDB
  DEFAULT CHARSET=`utf8mb4` DEFAULT COLLATE=`utf8mb4_unicode_ci`;

CREATE TABLE IF NOT EXISTS `#__sdajem_fittingtypes` (
	`id` int(10) unsigned NOT NULL auto_increment,
	`access` int(10) unsigned NOT NULL DEFAULT 0,
	`alias` varchar(400),
	`created` datetime,
	`created_by` int(10) unsigned,
	`created_by_alias` varchar(255),
	`published` tinyint(1) NOT NULL DEFAULT 1,
	`publish_up` datetime,
	`publish_down` datetime,
	`state` tinyint(3) NOT NULL DEFAULT 0,
	`ordering` int(11) NOT NULL DEFAULT 0,
	`title` VARCHAR(255) NOT NULL,
	`description` MEDIUMTEXT NULL,
  	`needSpace` tinyint(1) null default 0,
  	`image` VARCHAR(1024) NULL,
	PRIMARY KEY (`id`),
	KEY `idx_state` (`published`),
	KEY `idx_created_by` (`created_by`)
) ENGINE=InnoDB
  DEFAULT CHARSET=`utf8mb4` DEFAULT COLLATE=`utf8mb4_unicode_ci`;