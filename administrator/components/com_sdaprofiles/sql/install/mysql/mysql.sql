CREATE TABLE IF NOT EXISTS `#__sdaprofiles_profiles`
(
  `sdaprofiles_profile_id` int unsigned auto_increment,
  `users_user_id` int unsigned comment 'foreign key to joomla user #__users',
  `groupProfile` tinyint unsigned default 0,
  `defaultGroup` tinyint(1) unsigned default 0,
  `userName` varchar(255) null,
  `avatar` varchar(255) null,
  `address1` varchar(255) null,
  `address2` varchar(255) null,
  `city` varchar(255) null,
  `postal` int(10) null,
  `phone` varchar(20) null,
  `mobil` varchar(20) null,
  `dob` DATE null comment 'Date of birth',
  `mailOnNew` tinyint null,
  `mailOnEdited` tinyint null,
  `enabled` tinyint(1) NOT NULL DEFAULT '1',
  `created_by` bigint(20) NOT NULL DEFAULT '0',
  `created_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` bigint(20) NOT NULL DEFAULT '0',
  `modified_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `locked_by` bigint(20) NOT NULL DEFAULT '0',
  `locked_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `access` int(10) null,
  PRIMARY KEY (`sdaprofiles_profile_id`)
)
  ENGINE=InnoDB
  DEFAULT CHARSET=`utf8mb4` DEFAULT COLLATE=`utf8mb4_unicode_ci`;

CREATE TABLE IF NOT EXISTS `#__sdaprofiles_fittings`
(
  `sdaprofiles_fitting_id` int unsigned auto_increment comment 'Primary Key',
  `sdaprofiles_profile_id` int unsigned not null comment 'foreign key to #__sdaprofiles_profiles',
  `type` varchar(255) null,
  `sdaprofiles_fitting_image_id` int unsigned null,
  `detail` varchar(255) null,
  `length` int null,
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

CREATE TABLE IF NOT EXISTS `#__sdaprofiles_fitting_types`
(
  `sdaprofiles_fitting_type_id` int unsigned auto_increment comment 'Primary Key',
  `title` varchar(255) null,
  `needSpace` tinyint(1) unsigned DEFAULT '0',
  `enabled` tinyint(1) NOT NULL DEFAULT '1',
  `created_by` bigint(20) NOT NULL DEFAULT '0',
  `created_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` bigint(20) NOT NULL DEFAULT '0',
  `modified_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `locked_by` bigint(20) NOT NULL DEFAULT '0',
  `locked_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`sdaprofiles_fitting_type_id`)
)
  ENGINE=InnoDB
  DEFAULT CHARSET=`utf8mb4` DEFAULT COLLATE=`utf8mb4_unicode_ci`;

insert into `#__sdaprofiles_fitting_types` (`title`,`needSpace`) values ('Zelte', 1);
insert into `#__sdaprofiles_fitting_types` (`title`,`needSpace`) values ('Lager Dekoration', 1);
insert into `#__sdaprofiles_fitting_types` (`title`,`needSpace`) values ('Sonstiges', 0);

CREATE TABLE IF NOT EXISTS `#__sdaprofiles_fitting_images`
(
  `sdaprofiles_fitting_image_id` int unsigned auto_increment comment 'Primary Key',
  `image` varchar(1024) null,
  `description` VARCHAR(1024) null,
  `type`  int unsigned null,
  `enabled` tinyint(1) NOT NULL DEFAULT '1',
  `created_by` bigint(20) NOT NULL DEFAULT '0',
  `created_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` bigint(20) NOT NULL DEFAULT '0',
  `modified_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `locked_by` bigint(20) NOT NULL DEFAULT '0',
  `locked_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`sdaprofiles_fitting_image_id`)
)
  ENGINE=InnoDB
  DEFAULT CHARSET=`utf8mb4` DEFAULT COLLATE=`utf8mb4_unicode_ci`;

insert into `#__sdaprofiles_fitting_images` (`image`) values ('images/Ausruestung/Zelt-Rund-sw.png');
insert into `#__sdaprofiles_fitting_images` (`image`) values ('images/Ausruestung/Zelt-Rund-Leinen.png');
insert into `#__sdaprofiles_fitting_images` (`image`) values ('images/Ausruestung/Zelt-Rund-gross-weiss.png');
insert into `#__sdaprofiles_fitting_images` (`image`) values ('images/Ausruestung/Zelt-Doppelmast-weiss.png');
insert into `#__sdaprofiles_fitting_images` (`image`) values ('images/Ausruestung/Zelt-Doppelmast-Leinen.png');
insert into `#__sdaprofiles_fitting_images` (`image`) values ('images/Ausruestung/Zelt-Doppelmast-bg.png');
insert into `#__sdaprofiles_fitting_images` (`image`) values ('images/Ausruestung/Zelt-A-klein-weiss.png');
insert into `#__sdaprofiles_fitting_images` (`image`) values ('images/Ausruestung/Waffenstaender.png');
insert into `#__sdaprofiles_fitting_images` (`image`) values ('images/Ausruestung/Sonnensegel-5x5.png');
insert into `#__sdaprofiles_fitting_images` (`image`) values ('images/Ausruestung/Feuerstelle.png');
insert into `#__sdaprofiles_fitting_images` (`image`) values ('images/Ausruestung/Feuerloescher.png');
insert into `#__sdaprofiles_fitting_images` (`image`) values ('images/Ausruestung/ersteHilfe.png');
insert into `#__sdaprofiles_fitting_images` (`image`) values ('images/Ausruestung/Altar.png');