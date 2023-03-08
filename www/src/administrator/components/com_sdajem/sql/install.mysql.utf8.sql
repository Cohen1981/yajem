/*
Standard for all tables
CREATE TABLE IF NOT EXISTS `#__sdajem_xxx` (
	`id` int(10) unsigned NOT NULL auto_increment,
	`access` int(10) unsigned NOT NULL DEFAULT 0,
	`alias` varchar(400),
	`created` datetime,
	`created_by` int(10) unsigned,
	`created_by_alias` varchar(255),
	`checked_out` int(10) unsigned DEFAULT NULL,
	`checked_out_time` datetime,
	`published` tinyint(1) NOT NULL DEFAULT 0,
	`publish_up` datetime,
	`publish_down` datetime,
	`state` tinyint(3) NOT NULL DEFAULT 0,
	`ordering` int(11) NOT NULL DEFAULT 0,
	`language` char(7) NOT NULL DEFAULT '*',
	PRIMARY KEY (`id`),
	KEY `idx_state` (`published`),
	KEY `idx_created_by` (`created_by`),
	KEY `idx_language` (`language`)
*/

CREATE TABLE IF NOT EXISTS `#__sdajem_events` (
	`id` int(10) unsigned NOT NULL auto_increment,
	`access` int(10) unsigned NOT NULL DEFAULT 0,
	`alias` varchar(400),
	`created` datetime,
	`created_by` int(10) unsigned,
	`created_by_alias` varchar(255),
	`checked_out` int(10) unsigned DEFAULT NULL,
	`checked_out_time` datetime,
	`published` tinyint(1) NOT NULL DEFAULT 0,
	`publish_up` datetime,
	`publish_down` datetime,
	`state` tinyint(3) NOT NULL DEFAULT 0,
	`ordering` int(11) NOT NULL DEFAULT 0,
	`language` char(7) NOT NULL DEFAULT '*',
	`title` VARCHAR(255) NOT NULL COMMENT 'The event title',
	`description` MEDIUMTEXT NULL COMMENT 'Optional description of the event',
	`url` VARCHAR(255) NULL COMMENT 'Optional url. Use Case: Planing of visiting a third party event.',
	`image` VARCHAR(1024) NULL COMMENT 'Optional image as teaser.',
	`sdajem_location_id` INT UNSIGNED NULL COMMENT 'Foreign key to #__sdajem_locations',
	`hostId` INT NULL COMMENT 'Optional foreign_key to #__contact_details.\nUse Case: Visiting third party event. The Contact Information of the event hoster goes here. ',
	`organizerId` INT NULL COMMENT 'Optional foreign_key #__contact_details.\nUse Case: Visiting third party event. Here goes the organizer/ applicant',
	`startDateTime` DATETIME NULL COMMENT 'Starting date of the event',
	`endDateTime` DATETIME NULL COMMENT 'End date of the event',
	`allDayEvent` TINYINT(1) UNSIGNED NULL COMMENT 'Optional: Flag for all day event. 0=uses start and end time\n1=all day event.',
	`eventStatus` TINYINT(1) NULL DEFAULT 0 COMMENT  'Optional status of event. 0=open,1=confirmed,2=cancelled',
	`eventCancelled` TINYINT(1) NULL DEFAULT 0 COMMENT 'for marking events as cancelled by host',
	`catid` INT(10) unsigned,
	`params` text,
	`svg` BLOB NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_state` (`published`),
  KEY `idx_organizer` (`organizerId`),
  KEY `idx_location` (`sdajem_location_id`),
  KEY `idx_created_by` (`created_by`),
  KEY `idx_catid` (`catid`),
  KEY `idx_language` (`language`)
) ENGINE=InnoDB
  DEFAULT CHARSET=`utf8mb4` DEFAULT COLLATE=`utf8mb4_unicode_ci`;

CREATE TABLE IF NOT EXISTS `#__sdajem_locations` (
	`id` int(10) unsigned NOT NULL auto_increment,
	`access` int(10) unsigned NOT NULL DEFAULT 0,
	`alias` varchar(400),
	`created` datetime,
	`created_by` int(10) unsigned,
	`created_by_alias` varchar(255),
	`checked_out` int(10) unsigned DEFAULT NULL,
	`checked_out_time` datetime,
	`published` tinyint(1) NOT NULL DEFAULT 0,
	`publish_up` datetime,
	`publish_down` datetime,
	`state` tinyint(3) NOT NULL DEFAULT 0,
	`ordering` int(11) NOT NULL DEFAULT 0,
	`language` char(7) NOT NULL DEFAULT '*',
	`title` VARCHAR(255) NOT NULL COMMENT 'the location title',
	`description` MEDIUMTEXT NULL COMMENT 'Optional description of the location',
  	`url` VARCHAR(200) NULL COMMENT 'Optional url/ link to location website',
  	`street` VARCHAR(50) NULL COMMENT 'street and number',
  	`postalCode` VARCHAR(20) NULL,
  	`city` VARCHAR(50) NULL,
  	`stateAddress` VARCHAR(50) NULL,
  	`country` VARCHAR(50) NULL,
  	`latlng` VARCHAR(50) NULL COMMENT 'Used to save lat and lng for Google Maps',
  	`contactId` INT NULL COMMENT 'Optional foreign key to #__contact_details.',
  	`image` VARCHAR(1024) NULL COMMENT 'Optional teaser image',
  	`catid` INT(10) unsigned,
	PRIMARY KEY (`id`),
	KEY `idx_state` (`published`),
	KEY `idx_created_by` (`created_by`),
	KEY `idx_language` (`language`),
	KEY `idx_catid` (`catid`)
) ENGINE=InnoDB
  DEFAULT CHARSET=`utf8mb4` DEFAULT COLLATE=`utf8mb4_unicode_ci`;

CREATE TABLE IF NOT EXISTS `#__sdajem_attendings` (
	`id` int(10) unsigned NOT NULL auto_increment,
	`access` int(10) unsigned NOT NULL DEFAULT 0,
	`alias` varchar(400),
	`created` datetime,
	`created_by` int(10) unsigned,
	`created_by_alias` varchar(255),
	`state` tinyint(3) NOT NULL DEFAULT 0,
	`ordering` int(11) NOT NULL DEFAULT 0,
	`event_id` INT UNSIGNED NOT NULL COMMENT 'Foreign Key to #__yajame_events',
  	`users_user_id` INT UNSIGNED NULL COMMENT 'Foreign Key to #__users',
  	`status` TINYINT(1) NOT NULL DEFAULT 0,
	PRIMARY KEY (`id`),
	KEY `idx_created_by` (`created_by`)
) ENGINE=InnoDB
  DEFAULT CHARSET=`utf8mb4` DEFAULT COLLATE=`utf8mb4_unicode_ci`;

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