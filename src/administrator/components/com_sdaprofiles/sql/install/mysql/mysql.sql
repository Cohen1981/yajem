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
  `length` DECIMAL(10,2) null,
  `width` DECIMAL(10,2) null,
  `standard` tinyint(1) null default 0,
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
insert into `#__sdaprofiles_fitting_types` (`title`,`needSpace`) values ('Schutzausrüstung', 0);
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

insert into `#__sdaprofiles_fitting_images` (`image`,`type`,`enabled`) value ('media/com_sdaprofiles/images/equipment/Zelt-A-klein-weiss.png',1,1);
insert into `#__sdaprofiles_fitting_images` (`image`,`type`,`enabled`) value ('media/com_sdaprofiles/images/equipment/Zelt-Doppelmast-bg.png',1,1);
insert into `#__sdaprofiles_fitting_images` (`image`,`type`,`enabled`) value ('media/com_sdaprofiles/images/equipment/Zelt-Doppelmast-Leinen.png',1,1);
insert into `#__sdaprofiles_fitting_images` (`image`,`type`,`enabled`) value ('media/com_sdaprofiles/images/equipment/Zelt-Doppelmast-weiss.png',1,1);
insert into `#__sdaprofiles_fitting_images` (`image`,`type`,`enabled`) value ('media/com_sdaprofiles/images/equipment/Zelt-Rund-gross-weiss.png',1,1);
insert into `#__sdaprofiles_fitting_images` (`image`,`type`,`enabled`) value ('media/com_sdaprofiles/images/equipment/Zelt-Rund-Leinen.png',1,1);
insert into `#__sdaprofiles_fitting_images` (`image`,`type`,`enabled`) value ('media/com_sdaprofiles/images/equipment/Zelt-Rund-Leinen.png',1,1);
insert into `#__sdaprofiles_fitting_images` (`image`,`description`,`type`,`enabled`) value ('media/com_sdaprofiles/images/equipment/Sonnensegel-5x5.png','Sonnensegel',1,1);