CREATE TABLE IF NOT EXISTS `#__sdaprofiles_fitting_images`
(
    `sdaprofiles_fitting_image_id` int unsigned auto_increment comment 'Primary Key',
    `image` varchar(255) null,
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

insert into `#__sdaprofiles_fitting_images` (`image`) value ('media/com_sdaprofiles/images/alexb.png');
insert into `#__sdaprofiles_fitting_images` (`image`) value ('media/com_sdaprofiles/images/AlexE.png');
insert into `#__sdaprofiles_fitting_images` (`image`) value ('media/com_sdaprofiles/images/Arne.png');
insert into `#__sdaprofiles_fitting_images` (`image`) value ('media/com_sdaprofiles/images/kaiG.png');
insert into `#__sdaprofiles_fitting_images` (`image`) value ('media/com_sdaprofiles/images/Kerstin.png');
insert into `#__sdaprofiles_fitting_images` (`image`) value ('media/com_sdaprofiles/images/Soeren.png');
insert into `#__sdaprofiles_fitting_images` (`image`) value ('media/com_sdaprofiles/images/Winnie.png');
insert into `#__sdaprofiles_fitting_images` (`image`) value ('media/com_sdaprofiles/images/Sonnensegel.png');

alter table `#__sdaprofiles_fittings` drop column image;

alter table `#__sdaprofiles_fittings` modify `sdaprofiles_profile_id` int unsigned null comment 'foreign key to #__sdaprofiles_profiles';

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

insert into `#__sdaprofiles_fitting_types` (`title`,`needSpace`) values ('Lagerplane/ Sonnensegel', 1);
insert into `#__sdaprofiles_fitting_types` (`title`,`needSpace`) values ('Lager Dekoration', 1);
insert into `#__sdaprofiles_fitting_types` (`title`,`needSpace`) values ('Sonstiges', 0);

alter table `#__sdaprofiles_fittings` modify `type` int unsigned null;