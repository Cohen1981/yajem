ALTER TABLE `#__sdajem_mailings`
  add   `access` INT(10) NULL,
  add  `enabled` TINYINT(4) NOT NULL COMMENT 'like state or published',
  add  `locked_by` INT(10) NULL COMMENT 'like checked_out',
  add  `locked_on` DATETIME NULL COMMENT 'like checked_out_time',
  add  `hits` INT(10) UNSIGNED,
  add  `ordering` INT NULL COMMENT 'joomla standard field for saving manuel ordering.',
  add  `created_on` DATETIME NULL COMMENT 'joomla standard: holds the creation timestamp.',
  add  `created_by` INT NOT NULL COMMENT 'joomla standard: holds the creator of the item. Foreign key to #__users',
  add  `modified_on` DATETIME NULL COMMENT 'Joomla standard: holds the timestamp of the last modification',
  add  `modified_by` INT NULL COMMENT 'Joomla standard: holds the modifier of the item. Foreign key to #__users';

ALTER TABLE `#__sdajem_attachments`
  add   `access` INT(10) NULL,
  add  `enabled` TINYINT(4) NOT NULL COMMENT 'like state or published',
  add  `locked_by` INT(10) NULL COMMENT 'like checked_out',
  add  `locked_on` DATETIME NULL COMMENT 'like checked_out_time',
  add `hits` INT(10) UNSIGNED,
  add `ordering` INT NULL COMMENT 'joomla standard field for saving manuel ordering.',
  add `created_on` DATETIME NULL COMMENT 'joomla standard: holds the creation timestamp.',
  add `created_by` INT NOT NULL COMMENT 'joomla standard: holds the creator of the item. Foreign key to #__users',
  add `modified_on` DATETIME NULL COMMENT 'Joomla standard: holds the timestamp of the last modification',
  add `modified_by` INT NULL COMMENT 'Joomla standard: holds the modifier of the item. Foreign key to #__users';

ALTER TABLE `#__sdajem_comments`
  add   `access` INT(10) NULL,
  add `enabled` TINYINT(4) NOT NULL COMMENT 'like state or published',
  add `locked_by` INT(10) NULL COMMENT 'like checked_out',
  add `locked_on` DATETIME NULL COMMENT 'like checked_out_time',
  add `hits` INT(10) UNSIGNED,
  add `ordering` INT NULL COMMENT 'joomla standard field for saving manuel ordering.',
  add `created_on` DATETIME NULL COMMENT 'joomla standard: holds the creation timestamp.',
  add `created_by` INT NOT NULL COMMENT 'joomla standard: holds the creator of the item. Foreign key to #__users',
  add `modified_on` DATETIME NULL COMMENT 'Joomla standard: holds the timestamp of the last modification',
  add `modified_by` INT NULL COMMENT 'Joomla standard: holds the modifier of the item. Foreign key to #__users';

ALTER TABLE `#__sdajem_attendees`
  add   `access` INT(10) NULL,
  add `enabled` TINYINT(4) NOT NULL COMMENT 'like state or published',
  add `locked_by` INT(10) NULL COMMENT 'like checked_out',
  add `locked_on` DATETIME NULL COMMENT 'like checked_out_time',
  add `hits` INT(10) UNSIGNED,
  add `ordering` INT NULL COMMENT 'joomla standard field for saving manuel ordering.',
  add `created_on` DATETIME NULL COMMENT 'joomla standard: holds the creation timestamp.',
  add `created_by` INT NOT NULL COMMENT 'joomla standard: holds the creator of the item. Foreign key to #__users',
  add `modified_on` DATETIME NULL COMMENT 'Joomla standard: holds the timestamp of the last modification',
  add `modified_by` INT NULL COMMENT 'Joomla standard: holds the modifier of the item. Foreign key to #__users';

ALTER TABLE `#__sdajem_categories`
  add   `access` INT(10) NULL,
  add `enabled` TINYINT(4) NOT NULL COMMENT 'like state or published',
  add `locked_by` INT(10) NULL COMMENT 'like checked_out',
  add `locked_on` DATETIME NULL COMMENT 'like checked_out_time',
  add `hits` INT(10) UNSIGNED,
  add `ordering` INT NULL COMMENT 'joomla standard field for saving manuel ordering.',
  add `created_on` DATETIME NULL COMMENT 'joomla standard: holds the creation timestamp.',
  add `created_by` INT NOT NULL COMMENT 'joomla standard: holds the creator of the item. Foreign key to #__users',
  add `modified_on` DATETIME NULL COMMENT 'Joomla standard: holds the timestamp of the last modification',
  add `modified_by` INT NULL COMMENT 'Joomla standard: holds the modifier of the item. Foreign key to #__users';

UPDATE `#__sdajem_categories` SET `modified_on` = CURRENT_DATE;
UPDATE `#__sdajem_attendees` SET `modified_on` = CURRENT_DATE;
UPDATE `#__sdajem_comments` SET `modified_on` = CURRENT_DATE;
UPDATE `#__sdajem_attachments` SET `modified_on` = CURRENT_DATE;
UPDATE `#__sdajem_mailings` SET `modified_on` = CURRENT_DATE;