CREATE TABLE IF NOT EXISTS `#__sdajem_mailings`
(
    `sdajem_mailing_id` int unsigned auto_increment comment 'Primary Key',
    `sdajem_event_id` int unsigned null comment 'Foreign Key to #__yajame_events',
    `users_user_id` int unsigned null comment 'Foreign Key to user',
    `subscribed` tinyint unsigned null,
    PRIMARY KEY (`sdajem_mailing_id`),
    INDEX `idx_event_id` (`sdajem_event_id` ASC),
    INDEX `idx_location_id` (`users_user_id` ASC)
)
    ENGINE=InnoDB
    DEFAULT CHARSET=`utf8mb4` DEFAULT COLLATE=`utf8mb4_unicode_ci`;