CREATE TABLE IF NOT EXISTS `#__sdaprofile_fitting_type`(
  `id` int unsigned auto_increment,
  `alias` varchar(400) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL DEFAULT '',
  `access` int(10) unsigned NOT NULL DEFAULT 0,
  `published` tinyint(1) NOT NULL DEFAULT 0,
  `publish_up` datetime,
  `publish_down` datetime,
  `checked_out` int(10) unsigned NOT NULL DEFAULT 0,
  `checked_out_time` datetime,
  `state` tinyint(3) NOT NULL DEFAULT 0,
  `ordering` int(11) NOT NULL DEFAULT 0,
  `language` char(7) NOT NULL DEFAULT '*',
  `title` varchar(255) null,
  `needSpace` tinyint(1) unsigned DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=`utf8mb4` DEFAULT COLLATE=`utf8mb4_unicode_ci`;

insert into `#__sdaprofile_fitting_type` (`title`,`needSpace`) values ('Zelte', 1);
insert into `#__sdaprofile_fitting_type` (`title`,`needSpace`) values ('Lager Dekoration', 1);
insert into `#__sdaprofile_fitting_type` (`title`,`needSpace`) values ('Schutzausrüstung', 0);
insert into `#__sdaprofile_fitting_type` (`title`,`needSpace`) values ('Sonstiges', 0);

CREATE TABLE IF NOT EXISTS `#__sdaprofile_fitting_images`
(
  `id` int unsigned auto_increment,
  `image` varchar(1024) null,
  `description` VARCHAR(1024) null,
  `type`  int unsigned null,
  `alias` varchar(400) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL DEFAULT '',
  `access` int(10) unsigned NOT NULL DEFAULT 0,
  `published` tinyint(1) NOT NULL DEFAULT 0,
  `publish_up` datetime,
  `publish_down` datetime,
  `checked_out` int(10) unsigned NOT NULL DEFAULT 0,
  `checked_out_time` datetime,
  `state` tinyint(3) NOT NULL DEFAULT 0,
  `ordering` int(11) NOT NULL DEFAULT 0,
  `language` char(7) NOT NULL DEFAULT '*',
  PRIMARY KEY (`id`)
)
  ENGINE=InnoDB
  DEFAULT CHARSET=`utf8mb4` DEFAULT COLLATE=`utf8mb4_unicode_ci`;

insert into `#__sdaprofile_fitting_images` (`image`,`type`,`published`) value ('media/com_sdaprofile/images/equipment/Zelt-A-klein-weiss.png',1,1);
insert into `#__sdaprofile_fitting_images` (`image`,`type`,`published`) value ('media/com_sdaprofile/images/equipment/Zelt-Doppelmast-bg.png',1,1);
insert into `#__sdaprofile_fitting_images` (`image`,`type`,`published`) value ('media/com_sdaprofile/images/equipment/Zelt-Doppelmast-Leinen.png',1,1);
insert into `#__sdaprofile_fitting_images` (`image`,`type`,`published`) value ('media/com_sdaprofile/images/equipment/Zelt-Doppelmast-weiss.png',1,1);
insert into `#__sdaprofile_fitting_images` (`image`,`type`,`published`) value ('media/com_sdaprofile/images/equipment/Zelt-Rund-gross-weiss.png',1,1);
insert into `#__sdaprofile_fitting_images` (`image`,`type`,`published`) value ('media/com_sdaprofile/images/equipment/Zelt-Rund-Leinen.png',1,1);
insert into `#__sdaprofile_fitting_images` (`image`,`type`,`published`) value ('media/com_sdaprofile/images/equipment/Zelt-Rund-Leinen.png',1,1);
insert into `#__sdaprofile_fitting_images` (`image`,`description`,`type`,`published`) value ('media/com_sdaprofile/images/equipment/Sonnensegel-5x5.png','Sonnensegel',1,1);

CREATE TABLE IF NOT EXISTS `#__sdaprofile_fittings` (
  `id` int unsigned auto_increment,
  `alias` varchar(400) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL DEFAULT '',
  `access` int(10) unsigned NOT NULL DEFAULT 0,
  `published` tinyint(1) NOT NULL DEFAULT 0,
  `publish_up` datetime,
  `publish_down` datetime,
  `checked_out` int(10) unsigned NOT NULL DEFAULT 0,
  `checked_out_time` datetime,
  `state` tinyint(3) NOT NULL DEFAULT 0,
  `ordering` int(11) NOT NULL DEFAULT 0,
  `language` char(7) NOT NULL DEFAULT '*',
  `user_id` int unsigned not null,
  `type` int unsigned NOT NULL,
  `detail` varchar(255) null,
  `length` DECIMAL(10,2) null,
  `width` DECIMAL(10,2) null,
  `standard` tinyint(1) null default 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=`utf8mb4` DEFAULT COLLATE=`utf8mb4_unicode_ci`;