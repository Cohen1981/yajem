CREATE TABLE IF NOT EXISTS `#__yajem_locations` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Id of teh location',
  `catid` INT NOT NULL COMMENT 'Foreign key to #__categories',
  `published` TINYINT(4) NOT NULL COMMENT 'joomla standard: holds the publishing state information.',
  `ordering` INT NULL COMMENT 'joomla standard field for saving manuel ordering.',
  `created` DATETIME NULL COMMENT 'joomla standard: holds the creation timestamp.',
  `created_by` INT NOT NULL COMMENT 'joomla standard: holds the creator of the item. Foreign key to #__users',
  `modified` DATETIME NULL COMMENT 'Joomla standard: holds the timestamp of the last modification',
  `modified_by` INT NULL COMMENT 'Joomla standard: holds the modifier of the item. Foreign key to #__users',
  `title` VARCHAR(255) NOT NULL COMMENT 'the location title',
  `alias` VARCHAR(255) NULL COMMENT 'Joomla standard: alias field',
  `description` MEDIUMTEXT NULL COMMENT 'Optional description of the location',
  `url` VARCHAR(200) NULL COMMENT 'Optional url/ link to location website',
  `street` VARCHAR(50) NULL COMMENT 'street and number',
  `postalCode` VARCHAR(20) NULL,
  `city` VARCHAR(50) NULL,
  `stateAddress` VARCHAR(50) NULL,
  `country` VARCHAR(2) NULL,
  `latlng` VARCHAR(50) NULL COMMENT 'Used to save lat and lng for Google Maps',
  `contactid` INT NULL COMMENT 'Optional foreign key to #__contact_details.',
  `image` VARCHAR(1024) NULL COMMENT 'Optional teaser image',
  PRIMARY KEY (`id`),
  INDEX `idx_loc_catid` (catid ASC),
  INDEX `idx_loc_conid` (contactid ASC))
  ENGINE=InnoDB
  DEFAULT CHARSET=`utf8mb4` DEFAULT COLLATE=`utf8mb4_unicode_ci`;

CREATE TABLE IF NOT EXISTS `#__yajem_events` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Id of the Event',
  `catid` INT UNSIGNED NOT NULL COMMENT 'Foreign key to com_categories',
  `published` TINYINT(4) NOT NULL COMMENT 'joomla standard: holds the publishing state information.',
  `ordering` INT NULL COMMENT 'joomla standard field for saving manuel ordering.',
  `created` DATETIME NOT NULL COMMENT 'joomla standard: holds the creation timestamp.',
  `createdBy` INT NOT NULL COMMENT 'joomla standard: holds the creator of the item. Foreign key to #__users',
  `modified` DATETIME NOT NULL COMMENT 'Joomla standard: holds the timestamp of the last modification',
  `modifiedBy` INT NOT NULL COMMENT 'Joomla standard: holds the modifier of the item. Foreign key to #__users',
  `title` VARCHAR(255) NOT NULL COMMENT 'The event title',
  `alias` VARCHAR(255) NOT NULL COMMENT 'Joomla standard: alias field',
  `description` MEDIUMTEXT NULL COMMENT 'Optional description of the event',
  `url` VARCHAR(255) NULL COMMENT 'Optional url. Use Case: Planing of visiting a third party event.',
  `image` VARCHAR(1024) NULL COMMENT 'Optional image as teaser.',
  `locationId` INT UNSIGNED NULL COMMENT 'Foreign key to #__yajem_locations',
  `useHost` TINYINT(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT 'If global config alows the usage of hosts this is the field where the information for the event is stored',
  `hostId` INT NULL COMMENT 'Optional foreign_key to #__contact_details.\nUse Case: Visiting third party event. The Contact Information of the event hoster goes here. ',
  `useOrganizer` TINYINT UNSIGNED NOT NULL DEFAULT 0 COMMENT 'If global config alows the usage of organizer this is the field where the information for the event is stored',
  `organizerId` INT NULL COMMENT 'Optional foreign_key #__contact_details.\nUse Case: Visiting third party event. Here goes the organizer/ applicant',
  `startDateTime` DATETIME NULL COMMENT 'Starting date of the event',
  `endDateTime` DATETIME NULL COMMENT 'End date of the event',
  `startDate` DATE NULL COMMENT 'Starting date of the event',
  `endDate` DATE NULL COMMENT 'End date of the event',
  `allDayEvent` TINYINT(1) UNSIGNED NULL COMMENT 'Optional: Flag for all day event. 0=uses start and end time\n1=all day event.',
  `useRegistration` TINYINT(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT 'Use regitration for this event. 1=true, 0=false',
  `useRegisterUntil` TINYINT(1) UNSIGNED NOT NULL DEFAULT 0,
  `registerUntil` DATE NULL COMMENT 'Date until a registration is allowed',
  `registrationLimit` INT NULL COMMENT 'Limit of available accommodations. 0=No Limit',
  `useWaitingList` TINYINT(1) NULL COMMENT 'use of waiting List in case of limitation. 0=false, 1=true',
  `useInvitation` TINYINT(1) NULL COMMENT 'Use Invitation. 0=false, 1=true',
  `eventStatus` TINYINT(1) NULL DEFAULT 0 COMMENT  'Optional status of event. 0=open,1=confirmed,2=cancelled',
  `access` INT UNSIGNED NULL,
  PRIMARY KEY (`id`),
  INDEX `idx_event_catid` (`catid` ASC),
  INDEX `idx_event_locid` (`locationId` ASC),
  INDEX `idx_event_hostid` (`hostId` ASC),
  INDEX `idx_event_orgid` (`organizerId` ASC))
  ENGINE=InnoDB
  DEFAULT CHARSET=`utf8mb4` DEFAULT COLLATE=`utf8mb4_unicode_ci`;

CREATE TABLE IF NOT EXISTS `#__yajem_attendees` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Primary Key',
  `eventId` INT UNSIGNED NOT NULL COMMENT 'Foreign Key to #__yajame_events',
  `userId` INT UNSIGNED NOT NULL COMMENT 'Foreign Key to #__users',
  `status` TINYINT(1) NOT NULL DEFAULT 0 COMMENT 'Status: 0=no reply, 2=attending, 3=not attending',
  PRIMARY KEY (`id`),
  INDEX `idx_att_event` (`eventId` ASC),
  INDEX `idx_att_user` (`userId` ASC ))
  ENGINE=InnoDB
  DEFAULT CHARSET=`utf8mb4` DEFAULT COLLATE=`utf8mb4_unicode_ci`;

CREATE TABLE IF NOT EXISTS `#__yajem_comments` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Primary Key',
  `userId` INT UNSIGNED NOT NULL COMMENT 'Foreign Key to #__users',
  `eventId` INT UNSIGNED NOT NULL COMMENT 'Foreign Key to #__yajame_events',
  `comment` MEDIUMTEXT NOT NULL ,
  `timestamp` DATETIME,
  PRIMARY KEY (`id`),
  INDEX `idx_comment_time` (`timestamp` DESC))
  ENGINE=InnoDB
  DEFAULT CHARSET=`utf8mb4` DEFAULT COLLATE=`utf8mb4_unicode_ci`;

CREATE TABLE IF NOT EXISTS `#__yajem_attachments`
(
  `id` int unsigned auto_increment comment 'Primary Key',
  `eventId` int unsigned null comment 'Foreign Key to #__yajame_events',
  `locationId` int unsigned null comment 'Foreign Key to #__yajame_locations',
  `file` varchar(1024) not null,
  `title` varchar(255) null,
  `description` mediumtext null,
  PRIMARY KEY (`id`),
  INDEX `idx_event_id` (`eventId` ASC),
  INDEX `idx_location_id` (`locationId` ASC)
)
ENGINE=InnoDB
DEFAULT CHARSET=`utf8mb4` DEFAULT COLLATE=`utf8mb4_unicode_ci`;