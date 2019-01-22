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