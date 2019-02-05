alter table `#__yajem_events` modify `locationId` int unsigned null comment 'Foreign key to #__yajem_locations';
alter table `#__yajem_events` add `access` int unsigned null;