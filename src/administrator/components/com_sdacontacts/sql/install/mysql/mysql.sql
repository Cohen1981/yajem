create table if not exists `#__sdacontacts_contacts`
(
  `sdacontacts_contact_id` int auto_increment,
  `title` VARCHAR(255) NOT NULL COMMENT 'the Contact title',
  `contactPerson` VARCHAR(255) NULL Comment 'Name of Contact Person',
  `slug` VARCHAR(255) NULL COMMENT 'Joomla standard: alias field',
  `con_position` varchar(255) null,
  `address` text null,
  `city` varchar(100) null,
  `state` varchar(100) null,
  `country` varchar(100) null,
  `postcode` varchar(100) null,
  `telephone` varchar(255) null,
  `fax` varchar(255) null,
  `misc` mediumtext null,
  `image` varchar(255) null,
  `email` varchar(255) null,
  `catid` int default 0 not null,
  `access` int unsigned default 0 not null,
  `mobile` varchar(255) default '' not null,
  `webpage` varchar(255) default '' not null,
  `enabled` tinyint(1) NOT NULL DEFAULT '1',
  `created_by` bigint(20) NOT NULL DEFAULT '0',
  `created_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` bigint(20) NOT NULL DEFAULT '0',
  `modified_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `locked_by` bigint(20) NOT NULL DEFAULT '0',
  `locked_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`sdacontacts_contact_id`)
)
  ENGINE=InnoDB
  DEFAULT CHARSET=`utf8mb4` DEFAULT COLLATE=`utf8mb4_unicode_ci`;

