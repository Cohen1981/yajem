alter table `#__sdajem_events`
    add `useFittings` TINYINT(1) UNSIGNED NULL DEFAULT 0;

alter table `#__sdajem_events`
    add `fittingProfile` int unsigned null;

alter table `#__sdajem_attendees`
    modify `users_user_id` int unsigned null comment 'Foreign Key to j_users';

alter table `#__sdajem_attendees`
    add `sdaprofiles_profile_id` int unsigned null;