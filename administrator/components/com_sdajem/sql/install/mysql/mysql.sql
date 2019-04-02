CREATE TABLE IF NOT EXISTS `#__sdajem_categories` (
  `sdajem_category_id` int unsigned not null auto_increment,
  `title` varchar(255) not null,
  `type` varchar(255) null,
  primary key (`sdajem_categorie_id`)
)
  ENGINE=InnoDB
  DEFAULT CHARSET=`utf8mb4` DEFAULT COLLATE=`utf8mb4_unicode_ci`;

CREATE TABLE IF NOT EXISTS `#__sdajem_locations` (
  `sdajem_location_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `sdajem_categorie_id` INT NOT NULL COMMENT 'Foreign key to #__categories',
  `title` VARCHAR(255) NOT NULL COMMENT 'the location title',
  `slug` VARCHAR(255) NULL COMMENT 'Joomla standard: alias field',
  `description` MEDIUMTEXT NULL COMMENT 'Optional description of the location',
  `url` VARCHAR(200) NULL COMMENT 'Optional url/ link to location website',
  `street` VARCHAR(50) NULL COMMENT 'street and number',
  `postalCode` VARCHAR(20) NULL,
  `city` VARCHAR(50) NULL,
  `stateAddress` VARCHAR(50) NULL,
  `country` VARCHAR(2) NULL,
  `latlng` VARCHAR(50) NULL COMMENT 'Used to save lat and lng for Google Maps',
  `contactId` INT NULL COMMENT 'Optional foreign key to #__contact_details.',
  `image` VARCHAR(1024) NULL COMMENT 'Optional teaser image',
  `access` INT(10) NULL,
  `enabled` TINYINT(4) NOT NULL COMMENT 'like state or published',
  `locked_by` INT(10) NULL COMMENT 'like checked_out',
  `locked_on` DATETIME NULL COMMENT 'like checked_out_time',
  `hits` INT(10) UNSIGNED,
  `ordering` INT NULL COMMENT 'joomla standard field for saving manuel ordering.',
  `created_on` DATETIME NULL COMMENT 'joomla standard: holds the creation timestamp.',
  `created_by` INT NOT NULL COMMENT 'joomla standard: holds the creator of the item. Foreign key to #__users',
  `modified_on` DATETIME NULL COMMENT 'Joomla standard: holds the timestamp of the last modification',
  `modified_by` INT NULL COMMENT 'Joomla standard: holds the modifier of the item. Foreign key to #__users',
  PRIMARY KEY (`sdajem_location_id`),
  INDEX `idx_loc_catid` (sdajem_categorie_id ASC),
  INDEX `idx_loc_conid` (contactId ASC))
  ENGINE=InnoDB
  DEFAULT CHARSET=`utf8mb4` DEFAULT COLLATE=`utf8mb4_unicode_ci`;

CREATE TABLE IF NOT EXISTS `#__sdajem_events` (
  `sdajem_event_id` INT UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Id of the Event',
  sdajem_category_id INT UNSIGNED NOT NULL COMMENT 'Foreign key to com_categories',
  `title` VARCHAR(255) NOT NULL COMMENT 'The event title',
  `slug` VARCHAR(255) NOT NULL COMMENT 'Joomla standard: alias field',
  `description` MEDIUMTEXT NULL COMMENT 'Optional description of the event',
  `url` VARCHAR(255) NULL COMMENT 'Optional url. Use Case: Planing of visiting a third party event.',
  `image` VARCHAR(1024) NULL COMMENT 'Optional image as teaser.',
  `sdajem_location_id` INT UNSIGNED NULL COMMENT 'Foreign key to #__sdajem_locations',
  `hostId` INT NULL COMMENT 'Optional foreign_key to #__contact_details.\nUse Case: Visiting third party event. The Contact Information of the event hoster goes here. ',
  `organizerId` INT NULL COMMENT 'Optional foreign_key #__contact_details.\nUse Case: Visiting third party event. Here goes the organizer/ applicant',
  `startDateTime` DATETIME NULL COMMENT 'Starting date of the event',
  `endDateTime` DATETIME NULL COMMENT 'End date of the event',
  `allDayEvent` TINYINT(1) UNSIGNED NULL COMMENT 'Optional: Flag for all day event. 0=uses start and end time\n1=all day event.',
  `useRegistration` TINYINT(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT 'Use regitration for this event. 1=true, 0=false',
  `registerUntil` DATE NULL COMMENT 'Date until a registration is allowed',
  `registrationLimit` INT NULL COMMENT 'Limit of available accommodations. 0=No Limit',
  `useWaitingList` TINYINT(1) NULL COMMENT 'use of waiting List in case of limitation. 0=false, 1=true',
  `eventStatus` TINYINT(1) NULL DEFAULT 0 COMMENT  'Optional status of event. 0=open,1=confirmed,2=cancelled',
  `access` INT(10) NULL,
  `enabled` TINYINT(4) NOT NULL COMMENT 'like state or published',
  `locked_by` INT(10) NULL COMMENT 'like checked_out',
  `locked_on` DATETIME NULL COMMENT 'like checked_out_time',
  `hits` INT(10) UNSIGNED,
  `ordering` INT NULL COMMENT 'joomla standard field for saving manuel ordering.',
  `created_on` DATETIME NULL COMMENT 'joomla standard: holds the creation timestamp.',
  `created_by` INT NOT NULL COMMENT 'joomla standard: holds the creator of the item. Foreign key to #__users',
  `modified_on` DATETIME NULL COMMENT 'Joomla standard: holds the timestamp of the last modification',
  `modified_by` INT NULL COMMENT 'Joomla standard: holds the modifier of the item. Foreign key to #__users',
  PRIMARY KEY (`sdajem_event_id`),
  INDEX `idx_event_catid` (sdajem_category_id ASC),
  INDEX `idx_event_locid` (`sdajem_location_id` ASC),
  INDEX `idx_event_hostid` (`hostId` ASC),
  INDEX `idx_event_orgid` (`organizerId` ASC))
  ENGINE=InnoDB
  DEFAULT CHARSET=`utf8mb4` DEFAULT COLLATE=`utf8mb4_unicode_ci`;

CREATE TABLE IF NOT EXISTS `#__sdajem_attendees` (
  `sdajem_attendee_id` INT UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Primary Key',
  `sdajem_event_id` INT UNSIGNED NOT NULL COMMENT 'Foreign Key to #__yajame_events',
  `users_user_id` INT UNSIGNED NOT NULL COMMENT 'Foreign Key to #__users',
  `status` TINYINT(1) NOT NULL DEFAULT 0 COMMENT 'Status: 0=no reply, 2=attending, 3=not attending',
  PRIMARY KEY (`sdajem_attendee_id`),
  INDEX `idx_att_event` (`sdajem_event_id` ASC),
  INDEX `idx_att_user` (`users_user_id` ASC ))
  ENGINE=InnoDB
  DEFAULT CHARSET=`utf8mb4` DEFAULT COLLATE=`utf8mb4_unicode_ci`;

CREATE TABLE IF NOT EXISTS `#__sdajem_comments` (
  `sdajem_comment_id` INT UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Primary Key',
  `users_user_id` INT UNSIGNED NOT NULL COMMENT 'Foreign Key to #__users',
  `sdajem_event_id` INT UNSIGNED NOT NULL COMMENT 'Foreign Key to #__yajame_events',
  `comment` MEDIUMTEXT NOT NULL ,
  `timestamp` DATETIME,
  PRIMARY KEY (`sdajem_comment_id`),
  INDEX `idx_comment_time` (`timestamp` DESC))
  ENGINE=InnoDB
  DEFAULT CHARSET=`utf8mb4` DEFAULT COLLATE=`utf8mb4_unicode_ci`;

CREATE TABLE IF NOT EXISTS `#__sdajem_attachments`
(
  `sdajem_attachment_id` int unsigned auto_increment comment 'Primary Key',
  `sdajem_event_id` int unsigned null comment 'Foreign Key to #__yajame_events',
  `sdajem_location_id` int unsigned null comment 'Foreign Key to #__yajame_locations',
  `file` varchar(1024) not null,
  `title` varchar(255) null,
  `description` mediumtext null,
  PRIMARY KEY (`sdajem_attachment_id`),
  INDEX `idx_event_id` (`sdajem_event_id` ASC),
  INDEX `idx_location_id` (`sdajem_location_id` ASC)
)
ENGINE=InnoDB
DEFAULT CHARSET=`utf8mb4` DEFAULT COLLATE=`utf8mb4_unicode_ci`;