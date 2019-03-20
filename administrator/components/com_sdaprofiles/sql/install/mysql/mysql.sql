CREATE TABLE IF NOT EXISTS `#__sdaprofiles_profiles`
(
  `sdaprofiles_profile_id` int unsigned auto_increment,
  `users_user_id` int unsigned comment 'foreign key to joomla user #__users',
  `avatar` varchar(255) null,
  `address1` varchar(255) null,
  `address2` varchar(255) null,
  `city` varchar(255) null,
  `postal` int(10) null,
  `phone` varchar(20) null,
  `mobil` varchar(20) null,
  `dob` DATE null comment 'Date of birth',
  `enabled` tinyint(1) NOT NULL DEFAULT '1',
  `created_by` bigint(20) NOT NULL DEFAULT '0',
  `created_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` bigint(20) NOT NULL DEFAULT '0',
  `modified_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `locked_by` bigint(20) NOT NULL DEFAULT '0',
  `locked_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`sdaprofiles_profile_id`)
)
  ENGINE=InnoDB
  DEFAULT CHARSET=`utf8mb4` DEFAULT COLLATE=`utf8mb4_unicode_ci`;

CREATE TABLE IF NOT EXISTS `#__sdaprofiles_fittings`
(
  `sdaprofiles_fitting_id` int unsigned auto_increment comment 'Primary Key',
  `sdaprofiles_profile_id` int unsigned not null comment 'foreign key to #__sdaprofiles_profiles',
  `type` varchar(255) null,
  `detail` varchar(255) null,
  `lenght` int null,
  `width` int null,
  `enabled` tinyint(1) NOT NULL DEFAULT '1',
  `created_by` bigint(20) NOT NULL DEFAULT '0',
  `created_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` bigint(20) NOT NULL DEFAULT '0',
  `modified_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `locked_by` bigint(20) NOT NULL DEFAULT '0',
  `locked_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`sdaprofiles_fitting_id`)
)
  ENGINE=InnoDB
  DEFAULT CHARSET=`utf8mb4` DEFAULT COLLATE=`utf8mb4_unicode_ci`;