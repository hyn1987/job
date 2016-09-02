/* 2016-03-30 */
CREATE TABLE `wallet_history` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `wallet_id` int(10) unsigned NOT NULL,
  `date` date NOT NULL,
  `balance` decimal(10,2) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


CREATE TABLE `contract_meters` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Contract ID',
  `last_total_mins` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Total minutes by last week',
  `last_total_amount` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT 'Total amount by last week',
  `last_mins` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT 'Minutes of last week',
  `last_amount` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT 'Amount of last week\r\n',
  `this_mins` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT 'Minutes this week\r\n',
  `this_amount` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT 'Amount of this week\r\n',
  `total_mins` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Total minutes by now\r\n',
  `total_amount` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT 'Total amounts by now\r\n',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

alter table `www.wawjob.com`.`user_profiles` 
   add column `last6_total_mins` int UNSIGNED DEFAULT '0' NOT NULL COMMENT 'Total minutes during last 6 months' after `share, 
   add column `total_mins` int UNSIGNED DEFAULT '0' NOT NULL COMMENT 'Total minutes by now' after `last6_total_mins`;

/* 2016-04-03 */
alter table `www.wawjob.com`.`user_profiles` 
   add column `metered_at` timestamp NULL COMMENT 'Last timestamp when total minutes were metered.' after `total_mins`;
  
ALTER TABLE `www.wawjob.com`.`user_profiles`     ADD COLUMN `total_score` DECIMAL(3,2) DEFAULT '0' NULL COMMENT 'Total score (not-available when NULL)' AFTER `total_mins`;

/* 2016-04-18 */
alter table `www.wawjob.com`.`user_profiles` 
   add column `availability` tinyint(1) UNSIGNED DEFAULT '0' NOT NULL COMMENT '0: > 30hrs, 1: 10~30 hrs, 2: Not Available' after `share`;