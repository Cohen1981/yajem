CREATE TABLE IF NOT EXISTS `#__sda_events` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `alias` varchar(400) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL DEFAULT '',
  `access` int(10) unsigned NOT NULL DEFAULT 0,
  `published` tinyint(1) NOT NULL DEFAULT 0,
  `publish_up` datetime,
  `publish_down` datetime,
  `title` varchar(255) NOT NULL DEFAULT '',
  `catid` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  INDEX `idx_access` (`access`),
  KEY `idx_catid` (`catid`),
  KEY `idx_state` (`published`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE=utf8mb4_unicode_ci;