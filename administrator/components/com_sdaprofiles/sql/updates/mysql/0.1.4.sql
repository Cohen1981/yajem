alter table `#__sdaprofiles_profiles`
    add `access` int(10) null;

alter table `#__sdaprofiles_profiles`
    add `groupProfile` tinyint(4) default 0;