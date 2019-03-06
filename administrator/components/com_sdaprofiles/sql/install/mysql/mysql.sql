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
  PRIMARY KEY (`sdaprofiles_fitting_id`)
)
  ENGINE=InnoDB
  DEFAULT CHARSET=`utf8mb4` DEFAULT COLLATE=`utf8mb4_unicode_ci`;