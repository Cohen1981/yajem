CREATE TABLE IF NOT EXISTS `#__sdaprofiles_profiles`
(
  `profileId` int unsigned auto_increment,
  `usersUserId` int unsigned comment 'foreign key to joomla user #__users',
  `avatar` varchar(255) null,
  `address1` varchar(255) null,
  `address2` varchar(255) null,
  `city` varchar(255) null,
  `postal` int(10) null,
  `phone` varchar(20) null,
  `mobil` varchar(20) null,
  `dob` DATE null comment 'Date of birth',
  PRIMARY KEY (`profileId`)
)
  ENGINE=InnoDB
  DEFAULT CHARSET=`utf8mb4` DEFAULT COLLATE=`utf8mb4_unicode_ci`;

CREATE TABLE IF NOT EXISTS `#__sdaprofiles_fittings`
(
  `fittingId` int unsigned auto_increment comment 'Primary Key',
  `sdaprofilesProfileId` int unsigned not null comment 'foreign key to #__sdaprofiles_profiles',
  `type` varchar(255) null,
  `detail` varchar(255) null,
  `lenght` int null,
  `width` int null,
  PRIMARY KEY (`fittingId`)
)
  ENGINE=InnoDB
  DEFAULT CHARSET=`utf8mb4` DEFAULT COLLATE=`utf8mb4_unicode_ci`;