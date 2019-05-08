/* KOPIERVORLAGE STANDARDFELDER
 ******************************************************************
    `enabled` tinyint(1) NOT NULL DEFAULT '1',
    `created_by` bigint(20) NOT NULL DEFAULT '0',
    `created_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
    `modified_by` bigint(20) NOT NULL DEFAULT '0',
    `modified_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
    `locked_by` bigint(20) NOT NULL DEFAULT '0',
    `locked_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
    `access` int(10) null,
 *******************************************************************
 */

CREATE TABLE IF NOT EXISTS `` (
    `enabled` tinyint(1) NOT NULL DEFAULT '1',
    `created_by` bigint(20) NOT NULL DEFAULT '0',
    `created_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
    `modified_by` bigint(20) NOT NULL DEFAULT '0',
    `modified_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
    `locked_by` bigint(20) NOT NULL DEFAULT '0',
    `locked_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
    `access` int(10) null,
    PRIMARY KEY (``)
    )
    ENGINE=InnoDB
    DEFAULT CHARSET=`utf8mb4` DEFAULT COLLATE=`utf8mb4_unicode_ci`;