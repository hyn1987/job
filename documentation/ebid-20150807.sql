/*
SQLyog Enterprise - MySQL GUI v8.12 
MySQL - 5.0.22-community-nt : Database - ebid
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

CREATE DATABASE /*!32312 IF NOT EXISTS*/`ebid` /*!40100 DEFAULT CHARACTER SET utf8 */;

USE `ebid`;

/*Table structure for table `agency` */

DROP TABLE IF EXISTS `agency`;

CREATE TABLE `agency` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `agency_name` varchar(120) default NULL COMMENT 'Agency 이름',
  `owner` int(11) default NULL COMMENT '소유자의 아이디. users.id',
  `state` tinyint(1) default '1' COMMENT 'Agency상태. 1:Active, 0:Disabled',
  `note` text,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC COMMENT='Agency정보를 보관하는 테블이다.';

/*Data for the table `agency` */

insert  into `agency`(`id`,`agency_name`,`owner`,`state`,`note`) values (1,'Green Solar',1,1,'Green + Solar ?');
insert  into `agency`(`id`,`agency_name`,`owner`,`state`,`note`) values (2,'Internet Donkey',2,1,'당나귀처럼 일하자!');
insert  into `agency`(`id`,`agency_name`,`owner`,`state`,`note`) values (3,'PHP Elite',3,1,'Only PHP elits here!\r\nWe welcome for those who has expert skills on PHP.');

/*Table structure for table `agency_user` */

DROP TABLE IF EXISTS `agency_user`;

CREATE TABLE `agency_user` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `agency_id` int(11) default NULL COMMENT 'Agencdy 아이디. agency.id',
  `user_id` int(11) default NULL COMMENT '소속된 사용자 아이디. users.id',
  `is_removed` tinyint(1) default '0' COMMENT '삭제됬는가? 0:아니, 1:예',
  `state` tinyint(2) default '0' COMMENT '0:승인대기, 1:승인됨, 4:부결',
  `req_note` text COMMENT '가입요청문',
  `role` tinyint(1) default '0' COMMENT '권한. 0:일반, 1:관리자',
  `type` tinyint(1) default '0' COMMENT '배속형태. 0:부분종속, 1:완전종속',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `NewIndex1` (`agency_id`,`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC COMMENT='Agency와 사용자의 배속관계를 반영한다.';

/*Data for the table `agency_user` */

insert  into `agency_user`(`id`,`agency_id`,`user_id`,`is_removed`,`state`,`req_note`,`role`,`type`) values (1,1,2,0,1,'I love it!',0,0);
insert  into `agency_user`(`id`,`agency_id`,`user_id`,`is_removed`,`state`,`req_note`,`role`,`type`) values (2,2,1,0,1,'다시 가입시켜 주세요.',0,1);

/*Table structure for table `base_cat` */

DROP TABLE IF EXISTS `base_cat`;

CREATE TABLE `base_cat` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `cat_name` varchar(60) default NULL COMMENT '과제분류 이름',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `base_cat` */

insert  into `base_cat`(`id`,`cat_name`) values (1,'PHP');
insert  into `base_cat`(`id`,`cat_name`) values (2,'HTML');
insert  into `base_cat`(`id`,`cat_name`) values (3,'HTML5');
insert  into `base_cat`(`id`,`cat_name`) values (4,'javascript');
insert  into `base_cat`(`id`,`cat_name`) values (5,'CSS');
insert  into `base_cat`(`id`,`cat_name`) values (6,'CSS 3.0');
insert  into `base_cat`(`id`,`cat_name`) values (7,'jQuery');
insert  into `base_cat`(`id`,`cat_name`) values (8,'Wordpress');
insert  into `base_cat`(`id`,`cat_name`) values (9,'Magento');
insert  into `base_cat`(`id`,`cat_name`) values (10,'Joomla!');
insert  into `base_cat`(`id`,`cat_name`) values (11,'Ecommerce');
insert  into `base_cat`(`id`,`cat_name`) values (12,'Zend');
insert  into `base_cat`(`id`,`cat_name`) values (13,'CakePHP');
insert  into `base_cat`(`id`,`cat_name`) values (14,'Translate');
insert  into `base_cat`(`id`,`cat_name`) values (15,'Icon Design');
insert  into `base_cat`(`id`,`cat_name`) values (16,'Web Design');
insert  into `base_cat`(`id`,`cat_name`) values (17,'Voice');
insert  into `base_cat`(`id`,`cat_name`) values (18,'Typing');
insert  into `base_cat`(`id`,`cat_name`) values (19,'VisualBasic');
insert  into `base_cat`(`id`,`cat_name`) values (20,'C, C++, VC');
insert  into `base_cat`(`id`,`cat_name`) values (21,'C#');
insert  into `base_cat`(`id`,`cat_name`) values (22,'J2EE');
insert  into `base_cat`(`id`,`cat_name`) values (23,'iPhone');
insert  into `base_cat`(`id`,`cat_name`) values (24,'Android');
insert  into `base_cat`(`id`,`cat_name`) values (25,'Java');
insert  into `base_cat`(`id`,`cat_name`) values (26,'Unity');

/*Table structure for table `base_country` */

DROP TABLE IF EXISTS `base_country`;

CREATE TABLE `base_country` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `name` varchar(60) default NULL COMMENT '나라명',
  `code` varchar(5) default NULL COMMENT '짧은 이름',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `base_country` */

insert  into `base_country`(`id`,`name`,`code`) values (1,'D.P.R Korea','KP');
insert  into `base_country`(`id`,`name`,`code`) values (2,'China','CN');

/*Table structure for table `base_label` */

DROP TABLE IF EXISTS `base_label`;

CREATE TABLE `base_label` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `type` tinyint(4) default NULL COMMENT '류형',
  `type_value` int(11) default NULL COMMENT '류형값',
  `type_label` varchar(120) default NULL COMMENT '류형라벨',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `label` (`type`,`type_label`),
  UNIQUE KEY `type_value` (`type`,`type_value`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC COMMENT='상수들에 대한 라벨을 보관한다.';

/*Data for the table `base_label` */

insert  into `base_label`(`id`,`type`,`type_value`,`type_label`) values (1,0,0,'Freelancer');
insert  into `base_label`(`id`,`type`,`type_value`,`type_label`) values (2,0,1,'Buyer');
insert  into `base_label`(`id`,`type`,`type_value`,`type_label`) values (3,0,2,'Admin');
insert  into `base_label`(`id`,`type`,`type_value`,`type_label`) values (4,1,0,'Not Verified');
insert  into `base_label`(`id`,`type`,`type_value`,`type_label`) values (5,1,1,'Verified');
insert  into `base_label`(`id`,`type`,`type_value`,`type_label`) values (6,1,2,'Financial Suspend');
insert  into `base_label`(`id`,`type`,`type_value`,`type_label`) values (7,1,3,'Suspend');
insert  into `base_label`(`id`,`type`,`type_value`,`type_label`) values (8,2,0,'Only 4 Invited');
insert  into `base_label`(`id`,`type`,`type_value`,`type_label`) values (9,2,1,'Cost Limit');
insert  into `base_label`(`id`,`type`,`type_value`,`type_label`) values (10,2,2,'Country Limit');
insert  into `base_label`(`id`,`type`,`type_value`,`type_label`) values (11,2,3,'Work Time Limit');
insert  into `base_label`(`id`,`type`,`type_value`,`type_label`) values (12,3,0,'$');
insert  into `base_label`(`id`,`type`,`type_value`,`type_label`) values (13,3,1,'$/hr');
insert  into `base_label`(`id`,`type`,`type_value`,`type_label`) values (14,3,2,'$/week');
insert  into `base_label`(`id`,`type`,`type_value`,`type_label`) values (15,3,3,'$/month');
insert  into `base_label`(`id`,`type`,`type_value`,`type_label`) values (16,3,4,'$/year');
insert  into `base_label`(`id`,`type`,`type_value`,`type_label`) values (17,4,0,'Not Started');
insert  into `base_label`(`id`,`type`,`type_value`,`type_label`) values (18,4,1,'Started');
insert  into `base_label`(`id`,`type`,`type_value`,`type_label`) values (19,4,2,'Finished');
insert  into `base_label`(`id`,`type`,`type_value`,`type_label`) values (20,4,3,'Pause');
insert  into `base_label`(`id`,`type`,`type_value`,`type_label`) values (21,4,4,'Suspend');
insert  into `base_label`(`id`,`type`,`type_value`,`type_label`) values (22,5,0,'In Draft');
insert  into `base_label`(`id`,`type`,`type_value`,`type_label`) values (23,5,1,'Posted');
insert  into `base_label`(`id`,`type`,`type_value`,`type_label`) values (24,5,4,'Deleted');

/*Table structure for table `base_timezone` */

DROP TABLE IF EXISTS `base_timezone`;

CREATE TABLE `base_timezone` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `timezone_value` int(11) default NULL COMMENT '시간대 값',
  `timezone_label` varchar(60) default NULL COMMENT '시간대 이름',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `base_timezone` */

insert  into `base_timezone`(`id`,`timezone_value`,`timezone_label`) values (1,9,'(UTC +09:00) Pyongyang');
insert  into `base_timezone`(`id`,`timezone_value`,`timezone_label`) values (2,8,'(UTC +08:00) Beijing, Chongqing, HongKong, Urumqi');

/*Table structure for table `bid` */

DROP TABLE IF EXISTS `bid`;

CREATE TABLE `bid` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `task_id` int(11) default NULL COMMENT '과제 아이디. task.id',
  `developer` int(11) default NULL COMMENT '개발자 아이디. users.id',
  `cost` double default NULL COMMENT '입찰가격',
  `created_on` datetime default NULL COMMENT '창조날자',
  `last_modified` datetime default NULL COMMENT '최종변경날자',
  `bid_note` text COMMENT '입찰문',
  `is_won` tinyint(1) default '0' COMMENT '성공하였는가? 1:예, 0:아니',
  `is_canceled` tinyint(1) default '0' COMMENT '취소하였는가? 1:예, 0:아니',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `NewIndex1` (`task_id`,`developer`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC COMMENT='입찰자료들을 보관한다.';

/*Data for the table `bid` */

/*Table structure for table `file_list` */

DROP TABLE IF EXISTS `file_list`;

CREATE TABLE `file_list` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `label` varchar(85) default NULL COMMENT '파일의 현시이름',
  `name` varchar(85) default NULL COMMENT '파일체계에서의 실지파일이름',
  `uploaded_on` datetime default NULL COMMENT '올리적재 날자',
  `uploader` int(11) default NULL COMMENT '올리적재한 사람',
  `ref_id` int(11) default NULL COMMENT '어디에 달린 파일인가를 가리킨다. ref_type=0 일때 task.id, ref_type=1일때 bid.id, ref_type=2일때 inbox.id',
  `ext` varchar(8) default NULL COMMENT '확장자',
  `content_type` varchar(145) default NULL COMMENT '파일형태',
  `ref_type` tinyint(4) default '0' COMMENT '어떤형태에 달렸는가. 0:과제, 1:입찰, 2:메세지',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `file_list` */

insert  into `file_list`(`id`,`label`,`name`,`uploaded_on`,`uploader`,`ref_id`,`ext`,`content_type`,`ref_type`) values (5,'IE_03.PNG','/uploaded/attached/1/IE_03.PNG','2015-07-06 15:03:46',1,1,'.PNG','image/png',0);
insert  into `file_list`(`id`,`label`,`name`,`uploaded_on`,`uploader`,`ref_id`,`ext`,`content_type`,`ref_type`) values (4,'firefox.png','/uploaded/attached/1/firefox.png','2015-07-06 15:03:46',1,1,'.png','image/png',0);
insert  into `file_list`(`id`,`label`,`name`,`uploaded_on`,`uploader`,`ref_id`,`ext`,`content_type`,`ref_type`) values (12,'8cT5Q.tiff','/uploaded/attached/1/8cT5Q.tiff','2015-07-06 15:58:17',1,1,'.tiff','image/tiff',0);
insert  into `file_list`(`id`,`label`,`name`,`uploaded_on`,`uploader`,`ref_id`,`ext`,`content_type`,`ref_type`) values (24,'screenshots.rar','/uploaded/attached/2/job-attached.rar','2015-07-21 11:49:59',3,2,'.rar','application/x-rar-compressed',0);
insert  into `file_list`(`id`,`label`,`name`,`uploaded_on`,`uploader`,`ref_id`,`ext`,`content_type`,`ref_type`) values (25,'psds.rar','/uploaded/attached/2/job-attached-0.rar','2015-07-21 11:49:59',3,2,'.rar','application/x-rar-compressed',0);

/*Table structure for table `inbox` */

DROP TABLE IF EXISTS `inbox`;

CREATE TABLE `inbox` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `from` int(11) default NULL COMMENT '송신자 아이디. user.id',
  `to` int(11) default NULL COMMENT '수신자 아이디. user.id',
  `message` text COMMENT '메세지',
  `parent_id` int(11) default '0' COMMENT '어미아이디. inbox.id',
  `is_ticket` tinyint(1) default '0' COMMENT '티케트인가? 1:예, 0:아니',
  `created_on` datetime default NULL COMMENT '창조날자',
  `task_id` int(11) default NULL COMMENT '과제아이디. task.id',
  `is_draft` tinyint(1) default '0' COMMENT '초고인가? 1:예, 0:아니',
  `is_read` tinyint(1) default '0' COMMENT '읽었는가? 1:예, 0:아니',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `inbox` */

/*Table structure for table `task` */

DROP TABLE IF EXISTS `task`;

CREATE TABLE `task` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `task_name` varchar(250) default NULL COMMENT '과제제목',
  `task_note` text COMMENT '설명',
  `task_state` tinyint(1) default '0' COMMENT '과제상태. 0:시작전, 1:진행중, 2:완료, 3:정지, 4:Suspend',
  `created_by` int(11) default NULL COMMENT '고객 아이디. user.id',
  `created_on` datetime default NULL COMMENT '창조날자',
  `last_modified` datetime default NULL COMMENT '최종편집날자',
  `last_bid_modified` datetime default NULL COMMENT '최종입찰변경날자',
  `task_type` tinyint(1) default '0' COMMENT '과제종류. 0:고정가격, 1:시간당, 2:주당, 3:월당, 4:년당',
  `cost` double default '0' COMMENT '가격',
  `developer` int(11) default '0' COMMENT '입찰에 성공한 개발자 아이디',
  `paid` double default '0' COMMENT '지불액',
  `total_hours` double default '0' COMMENT '작업시간(주,월,년)',
  `post_state` tinyint(1) default '0' COMMENT '0:초고, 1:게제됨, 4:삭제됨',
  `category` varchar(50) default NULL COMMENT '프로젝트 분류목록. 반점으로 구분된 아이디렬',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC COMMENT='과제들을 보관한다.';

/*Data for the table `task` */

insert  into `task`(`id`,`task_name`,`task_note`,`task_state`,`created_by`,`created_on`,`last_modified`,`last_bid_modified`,`task_type`,`cost`,`developer`,`paid`,`total_hours`,`post_state`,`category`) values (1,'Urgent!!! Skilled PHP Developer needed! we highly recommend you to bid if you think you are good php developer, please go on','We recently has got an issue\r\n\'Developer Issue\'\r\n\\n\r\n&lt;div&gt;&lt;drai&gt;\r\n&quot;\r\n?\\',0,3,'2015-07-03 18:48:57','2015-07-15 17:39:08','2015-07-03 18:48:57',0,42,0,0,0,1,',5,2,3,7,1,');
insert  into `task`(`id`,`task_name`,`task_note`,`task_state`,`created_by`,`created_on`,`last_modified`,`last_bid_modified`,`task_type`,`cost`,`developer`,`paid`,`total_hours`,`post_state`,`category`) values (2,'Very skilled HTML builder needed!','We have a site to build with HTML &amp; CSS from the design we have.\r\nI will provide you the design and you will have to deliver me with good result.\r\nI do not ask just pixel perfect, but the result must look very alike with the design I provide.\r\n\r\nPlease attach your sample work together if possible so that I can be sure about your skill better.\r\n\r\nThank you.',0,3,'2015-07-06 16:16:12','2015-07-21 11:49:59','2015-07-06 16:16:12',1,38,0,0,0,1,',5,2,3,7,');

/*Table structure for table `task_limit` */

DROP TABLE IF EXISTS `task_limit`;

CREATE TABLE `task_limit` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `limit_type` tinyint(1) default '0' COMMENT '제한류형. 0:초청제한, 1:가격제한, 2:작업리력제한, 3:국적제한',
  `ref_id` int(11) default '0',
  `limit_value1` double default NULL COMMENT '제한값1',
  `limit_value2` double default NULL COMMENT '제한값2',
  `task_id` int(11) default NULL COMMENT '과제 아이디',
  `is_removed` tinyint(1) default '0' COMMENT '삭제되였는가? 0:아니, 1:예',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `job-limit` (`limit_type`,`ref_id`,`task_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC COMMENT='과제제한들을 보관한다. ';

/*Data for the table `task_limit` */

insert  into `task_limit`(`id`,`limit_type`,`ref_id`,`limit_value1`,`limit_value2`,`task_id`,`is_removed`) values (1,0,0,0,0,1,0);
insert  into `task_limit`(`id`,`limit_type`,`ref_id`,`limit_value1`,`limit_value2`,`task_id`,`is_removed`) values (2,1,0,38,50,1,0);
insert  into `task_limit`(`id`,`limit_type`,`ref_id`,`limit_value1`,`limit_value2`,`task_id`,`is_removed`) values (3,2,0,250,0,1,0);
insert  into `task_limit`(`id`,`limit_type`,`ref_id`,`limit_value1`,`limit_value2`,`task_id`,`is_removed`) values (4,3,0,1,0,1,0);
insert  into `task_limit`(`id`,`limit_type`,`ref_id`,`limit_value1`,`limit_value2`,`task_id`,`is_removed`) values (5,0,0,0,0,2,0);
insert  into `task_limit`(`id`,`limit_type`,`ref_id`,`limit_value1`,`limit_value2`,`task_id`,`is_removed`) values (6,3,0,1,0,2,0);

/*Table structure for table `task_log` */

DROP TABLE IF EXISTS `task_log`;

CREATE TABLE `task_log` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `task_id` int(11) default NULL COMMENT '과제 아이디. task.id',
  `developer` int(11) default NULL COMMENT '개발자 아이디. user.id',
  `created_on` datetime default NULL COMMENT '등록날자',
  `work_hours` double default NULL COMMENT '작업시간',
  `rate` double default NULL COMMENT '시간당 Rate',
  `m_year` int(11) default NULL COMMENT '년',
  `m_month` tinyint(4) default NULL COMMENT '월',
  `m_date` tinyint(4) default NULL COMMENT '일',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `developer_log` (`task_id`,`developer`,`m_year`,`m_month`,`m_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC COMMENT='시간당 과제에 대한 지불리력이다.';

/*Data for the table `task_log` */

/*Table structure for table `trans` */

DROP TABLE IF EXISTS `trans`;

CREATE TABLE `trans` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `from` int(11) default NULL COMMENT '송신자 아이디',
  `to` int(11) default NULL COMMENT '수신자 아이디',
  `amount` double default NULL COMMENT '액수',
  `task_id` int(11) default NULL COMMENT '과제 아이디. task.id',
  `pay_type` tinyint(1) default '0' COMMENT '지불류형. 0:지불, 1:상금, 2:Refund',
  `created_on` datetime default NULL COMMENT '창조날자',
  `is_settled` tinyint(1) default '0' COMMENT '완결되였는가? 1:예, 0:아니',
  `trans_type` int(11) default NULL COMMENT '거래류형. 0:내부, 1:입/출금',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC COMMENT='자금류통상황을 보관한다.';

/*Data for the table `trans` */

/*Table structure for table `user_info` */

DROP TABLE IF EXISTS `user_info`;

CREATE TABLE `user_info` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `user_id` int(11) default NULL COMMENT '사용자 아이디. user.id',
  `first_name` varchar(40) default NULL COMMENT '이름',
  `middle_name` varchar(40) default NULL COMMENT '중간 이름',
  `last_name` varchar(40) default NULL COMMENT '성',
  `gender` tinyint(1) default '0' COMMENT '성별',
  `country` varchar(10) default NULL COMMENT '국적. 나라아이디',
  `timezone` int(11) default NULL COMMENT '시간대 값',
  `timezone_id` int(11) default NULL COMMENT '시간대 아이디. base_timezone.id',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `user` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `user_info` */

insert  into `user_info`(`id`,`user_id`,`first_name`,`middle_name`,`last_name`,`gender`,`country`,`timezone`,`timezone_id`) values (1,1,'JongHyok','','Ri',1,'KP',9,1);
insert  into `user_info`(`id`,`user_id`,`first_name`,`middle_name`,`last_name`,`gender`,`country`,`timezone`,`timezone_id`) values (2,2,'영명','','김',1,'KP',9,1);
insert  into `user_info`(`id`,`user_id`,`first_name`,`middle_name`,`last_name`,`gender`,`country`,`timezone`,`timezone_id`) values (3,3,'Andrew','Mc','Henrieh',1,'KP',8,2);

/*Table structure for table `user_pref` */

DROP TABLE IF EXISTS `user_pref`;

CREATE TABLE `user_pref` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `user_id` int(11) default NULL COMMENT '사용자 아이디. user.id',
  `cat_id` int(11) default NULL COMMENT '프로젝트 캐테고리 아이디. base_cat.id',
  `is_removed` tinyint(1) default '0' COMMENT '삭제되였는가? 0:아니, 1:예',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `user-cat` (`user_id`,`cat_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `user_pref` */

/*Table structure for table `user_profile` */

DROP TABLE IF EXISTS `user_profile`;

CREATE TABLE `user_profile` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `title` varchar(60) default NULL COMMENT '소개제목',
  `note` text COMMENT '소개글',
  `education` text COMMENT '교육',
  `last_work` text COMMENT '최종직업',
  `skill` text COMMENT '자질설명',
  `user_id` int(11) default NULL COMMENT '사용자 아이디. user.id',
  `rate` double default NULL COMMENT '시간당 가격',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `user` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `user_profile` */

insert  into `user_profile`(`id`,`title`,`note`,`education`,`last_work`,`skill`,`user_id`,`rate`) values (1,'PHP Master','My Skill is perfect!!!\r\nNever doubt on it, please.','University','MKP',NULL,1,42);
insert  into `user_profile`(`id`,`title`,`note`,`education`,`last_work`,`skill`,`user_id`,`rate`) values (2,'Qt Ghost','My Qt skill is just unbelievable','','',NULL,2,42);
insert  into `user_profile`(`id`,`title`,`note`,`education`,`last_work`,`skill`,`user_id`,`rate`) values (3,'','','','',NULL,3,0);

/*Table structure for table `user_skill_lang` */

DROP TABLE IF EXISTS `user_skill_lang`;

CREATE TABLE `user_skill_lang` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `user_id` int(11) default NULL,
  `lang_label` varchar(120) default NULL,
  `lang_level` int(11) default NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `user-lang` (`user_id`,`lang_label`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `user_skill_lang` */

insert  into `user_skill_lang`(`id`,`user_id`,`lang_label`,`lang_level`) values (5,1,'Chinese',3);
insert  into `user_skill_lang`(`id`,`user_id`,`lang_label`,`lang_level`) values (6,1,'English',5);
insert  into `user_skill_lang`(`id`,`user_id`,`lang_label`,`lang_level`) values (7,2,'English',5);

/*Table structure for table `user_track` */

DROP TABLE IF EXISTS `user_track`;

CREATE TABLE `user_track` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `action_type` tinyint(2) default '0' COMMENT '동작류형',
  `action_id` int(11) default NULL COMMENT '동작정보 아이디',
  `user_id` int(11) default NULL COMMENT '사용자 아이디',
  `created_on` datetime default NULL COMMENT '날자',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC COMMENT='사용자의 동작을 기록하는 테블이다.';

/*Data for the table `user_track` */

/*Table structure for table `user_wallet` */

DROP TABLE IF EXISTS `user_wallet`;

CREATE TABLE `user_wallet` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `user_id` int(11) default NULL COMMENT '사용자 아이디. user.id',
  `balance` double default NULL COMMENT '현재 금액',
  `unsettled` double default NULL COMMENT '완결되지 않은 금액',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `user_wallet` */

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `user_id` varchar(40) default NULL COMMENT '사용자의 가입아이디',
  `user_pwd` varchar(120) default NULL COMMENT '가입암호',
  `state` tinyint(1) default '0' COMMENT '상태. 1:승인, 0:승인안됨, 2:재정금지, 3:금지',
  `last_visited` datetime default NULL COMMENT '마지막으로 가입한 날자, 시간',
  `user_type` tinyint(1) default '0' COMMENT '사용자 류형. 0:개발자, 1:고객, 2:관리자',
  `email` varchar(120) default NULL COMMENT '메일주소',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `users` */

insert  into `users`(`id`,`user_id`,`user_pwd`,`state`,`last_visited`,`user_type`,`email`) values (1,'ray','070dd72385b8b2b205db53237da57200',1,'2015-07-21 14:22:27',0,'ray@mail.com');
insert  into `users`(`id`,`user_id`,`user_pwd`,`state`,`last_visited`,`user_type`,`email`) values (2,'snow','2b93fbdf27d43547bec8794054c28e00',1,'2015-06-29 15:18:56',0,'snow@mail.com');
insert  into `users`(`id`,`user_id`,`user_pwd`,`state`,`last_visited`,`user_type`,`email`) values (3,'andrew','d914e3ecf6cc481114a3f534a5faf90b',1,'2015-07-21 14:25:52',1,'andrew@mail.com');

/*Table structure for table `worklog` */

DROP TABLE IF EXISTS `worklog`;

CREATE TABLE `worklog` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `task_id` int(11) default NULL COMMENT '과제 아이디. task.id',
  `developer` int(11) default NULL COMMENT '개발자. user.id',
  `year` int(11) default '0' COMMENT '년',
  `month` tinyint(2) default '0' COMMENT '월',
  `m_hour` tinyint(2) default '0' COMMENT '시간',
  `m_min` tinyint(2) default '0' COMMENT '분',
  `k_total` int(11) default '0' COMMENT '건반 눌린수',
  `m_total` int(11) default '0' COMMENT '마우스 눌린수',
  `is_manual` tinyint(1) default '0' COMMENT '수동입력인가? 0:아니다, 1:예',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `LogInfo` (`task_id`,`developer`,`year`,`month`,`m_hour`,`m_min`),
  KEY `developer` (`task_id`,`developer`),
  KEY `developer_log` (`developer`,`year`,`month`,`m_hour`,`m_min`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC COMMENT='작업기록을 보관한다.';

/*Data for the table `worklog` */

/*Table structure for table `vw_agency` */

DROP TABLE IF EXISTS `vw_agency`;

/*!50001 DROP VIEW IF EXISTS `vw_agency` */;
/*!50001 DROP TABLE IF EXISTS `vw_agency` */;

/*!50001 CREATE TABLE `vw_agency` (
  `id` int(10) unsigned NOT NULL default '0',
  `agency_name` varchar(120) default NULL COMMENT 'Agency 이름',
  `owner` int(11) default NULL COMMENT '소유자의 아이디. users.id',
  `state` tinyint(1) default NULL COMMENT 'Agency상태. 1:Active, 0:Disabled',
  `note` text,
  `mems` bigint(21) NOT NULL default '0',
  `mem_ids` longblob
) ENGINE=InnoDB DEFAULT CHARSET=utf8 */;

/*View structure for view vw_agency */

/*!50001 DROP TABLE IF EXISTS `vw_agency` */;
/*!50001 DROP VIEW IF EXISTS `vw_agency` */;

/*!50001 CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_agency` AS (select `a`.`id` AS `id`,`a`.`agency_name` AS `agency_name`,`a`.`owner` AS `owner`,`a`.`state` AS `state`,`a`.`note` AS `note`,count(`u`.`id`) AS `mems`,concat(_utf8',',group_concat(`u`.`user_id` separator ','),_utf8',') AS `mem_ids` from (`agency` `a` left join `agency_user` `u` on(((`a`.`id` = `u`.`agency_id`) and (`u`.`is_removed` = 0) and (`u`.`state` = 1)))) group by `a`.`id`) */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
