/*
SQLyog Ultimate v8.55 
MySQL - 5.5.29-log : Database - yiixpress_core
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`yiixpress_core` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `yiixpress_core`;

/*Table structure for table `site1_admin_user` */

DROP TABLE IF EXISTS `site1_admin_user`;

CREATE TABLE `site1_admin_user` (
  `id` int(11) NOT NULL,
  `user_group_id` int(11) DEFAULT NULL,
  `username` varchar(64) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(128) DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `validation_code` varchar(64) DEFAULT NULL,
  `validation_type` smallint(6) DEFAULT NULL,
  `validation_expired` datetime DEFAULT NULL,
  `status` smallint(6) NOT NULL,
  `creation_datetime` datetime DEFAULT NULL,
  `last_update` datetime DEFAULT NULL,
  `deleted` smallint(6) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `site1_admin_user` */

insert  into `site1_admin_user`(`id`,`user_group_id`,`username`,`email`,`password`,`last_login`,`validation_code`,`validation_type`,`validation_expired`,`status`,`creation_datetime`,`last_update`,`deleted`) values (1,1,'hung','admin@yiixpress.com','827ccb0eea8a706c4c34a16891f84e7b','2013-06-26 09:00:45',NULL,NULL,NULL,1,NULL,NULL,0);

/*Table structure for table `site1_admin_user_group` */

DROP TABLE IF EXISTS `site1_admin_user_group`;

CREATE TABLE `site1_admin_user_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `status` varchar(5) NOT NULL,
  `creation_datetime` datetime DEFAULT NULL,
  `last_update` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

/*Data for the table `site1_admin_user_group` */

insert  into `site1_admin_user_group`(`id`,`name`,`status`,`creation_datetime`,`last_update`) values (1,'admin','t',NULL,NULL);

/*Table structure for table `site1_authassignment` */

DROP TABLE IF EXISTS `site1_authassignment`;

CREATE TABLE `site1_authassignment` (
  `itemname` varchar(64) NOT NULL,
  `userid` int(11) NOT NULL,
  `bizrule` longtext,
  `data` longtext,
  PRIMARY KEY (`itemname`,`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `site1_authassignment` */

insert  into `site1_authassignment`(`itemname`,`userid`,`bizrule`,`data`) values ('administrators',1,NULL,NULL),('administrators',3,NULL,NULL),('administrators',4,NULL,NULL),('administrators',5,NULL,NULL),('administrators',6,NULL,NULL),('administrators',7,NULL,NULL),('administrators',8,NULL,NULL),('administrators',9,NULL,NULL),('administrators',10,NULL,NULL),('administrators',11,NULL,'N;'),('administrators',13,NULL,'N;'),('administrators',14,NULL,'N;'),('administrators',15,NULL,NULL),('administrators',16,NULL,'N;'),('administrators',18,NULL,'N;'),('administrators',22,NULL,'N;'),('crm_admin',19,NULL,NULL),('crm_admin',23,NULL,NULL),('crm_admin',68,NULL,NULL),('crm_admin',81,NULL,NULL),('crm_admin',82,NULL,NULL),('crm_admin',84,NULL,NULL),('crm_admin',85,NULL,NULL),('crm_agent',21,NULL,NULL),('crm_agent',26,NULL,NULL),('crm_agent',27,NULL,NULL),('crm_agent',28,NULL,NULL),('crm_agent',29,NULL,NULL),('crm_agent',30,NULL,NULL),('crm_agent',31,NULL,NULL),('crm_agent',32,NULL,NULL),('crm_agent',33,NULL,NULL),('crm_agent',34,NULL,NULL),('crm_agent',35,NULL,NULL),('crm_agent',36,NULL,NULL),('crm_agent',37,NULL,NULL),('crm_agent',38,NULL,NULL),('crm_agent',39,NULL,NULL),('crm_agent',40,NULL,NULL),('crm_agent',41,NULL,NULL),('crm_agent',42,NULL,NULL),('crm_agent',43,NULL,NULL),('crm_agent',44,NULL,NULL),('crm_agent',45,NULL,NULL),('crm_agent',46,NULL,NULL),('crm_agent',47,NULL,NULL),('crm_agent',48,NULL,NULL),('crm_agent',49,NULL,NULL),('crm_agent',50,NULL,NULL),('crm_agent',51,NULL,NULL),('crm_agent',52,NULL,NULL),('crm_agent',53,NULL,NULL),('crm_agent',54,NULL,NULL),('crm_agent',55,NULL,NULL),('crm_agent',56,NULL,NULL),('crm_agent',57,NULL,NULL),('crm_agent',58,NULL,NULL),('crm_agent',59,NULL,NULL),('crm_agent',60,NULL,NULL),('crm_agent',61,NULL,NULL),('crm_agent',62,NULL,NULL),('crm_agent',63,NULL,NULL),('crm_agent',64,NULL,NULL),('crm_agent',65,NULL,NULL),('crm_agent',66,NULL,NULL),('crm_agent',67,NULL,NULL),('crm_agent',69,NULL,NULL),('crm_agent',70,NULL,NULL),('crm_agent',71,NULL,NULL),('crm_agent',72,NULL,NULL),('crm_agent',73,NULL,NULL),('crm_agent',74,NULL,NULL),('crm_agent',75,NULL,NULL),('crm_agent',76,NULL,NULL),('crm_agent',77,NULL,NULL),('crm_agent',78,NULL,NULL),('crm_agent',79,NULL,NULL),('crm_agent',80,NULL,NULL),('crm_agent',86,NULL,NULL),('crm_agent',87,NULL,NULL),('crm_agent',88,NULL,NULL),('crm_agent',89,NULL,NULL),('crm_agent',90,NULL,NULL),('crm_agent',91,NULL,NULL),('crm_agent',92,NULL,NULL),('crm_agent',93,NULL,NULL),('crm_agent',94,NULL,NULL),('crm_agent',95,NULL,NULL),('crm_agent',96,NULL,NULL),('crm_manager',20,NULL,NULL),('crm_manager',83,NULL,NULL),('crm_manager',97,NULL,NULL),('crm_report',98,NULL,NULL),('crm_report',99,NULL,NULL),('crm_report',100,NULL,NULL);

/*Table structure for table `site1_authitem` */

DROP TABLE IF EXISTS `site1_authitem`;

CREATE TABLE `site1_authitem` (
  `name` varchar(64) NOT NULL,
  `type` int(11) NOT NULL,
  `description` longtext,
  `bizrule` longtext,
  `data` longtext,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `site1_authitem` */

insert  into `site1_authitem`(`name`,`type`,`description`,`bizrule`,`data`) values ('administrators',2,NULL,NULL,NULL),('crm_admin',2,NULL,NULL,NULL),('crm_agent',2,NULL,NULL,NULL),('crm_manager',2,NULL,NULL,NULL),('crm_report',2,NULL,NULL,NULL);

/*Table structure for table `site1_authitemchild` */

DROP TABLE IF EXISTS `site1_authitemchild`;

CREATE TABLE `site1_authitemchild` (
  `parent` varchar(64) NOT NULL,
  `child` varchar(64) NOT NULL,
  PRIMARY KEY (`parent`,`child`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `site1_authitemchild` */

/*Table structure for table `site1_blog_post` */

DROP TABLE IF EXISTS `site1_blog_post`;

CREATE TABLE `site1_blog_post` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` text,
  `alias` text,
  `content` text,
  `revision` int(11) DEFAULT '1',
  `revision_log` varchar(512) NOT NULL,
  `status` int(11) DEFAULT '0',
  `views` int(11) DEFAULT NULL,
  `date_added` datetime DEFAULT NULL,
  `date_updated` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

/*Data for the table `site1_blog_post` */

insert  into `site1_blog_post`(`id`,`title`,`alias`,`content`,`revision`,`revision_log`,`status`,`views`,`date_added`,`date_updated`) values (1,'test','asdf','<p><strong>Heading</strong> 1 fsdf abc</p>\n\n<p>This is more test of style kjfladsf Â  Â </p>',1,'af',0,NULL,'2013-06-27 17:59:49','2013-06-28 14:10:25');

/*Table structure for table `site1_module` */

DROP TABLE IF EXISTS `site1_module`;

CREATE TABLE `site1_module` (
  `id` int(11) NOT NULL,
  `name` varchar(64) NOT NULL,
  `friendly_name` varchar(255) NOT NULL,
  `description` text,
  `enabled` tinyint(1) NOT NULL,
  `version` varchar(32) DEFAULT NULL,
  `has_back_end` char(1) NOT NULL,
  `is_system` varchar(5) NOT NULL,
  `icon` varchar(255) DEFAULT NULL,
  `path` varchar(255) DEFAULT NULL,
  `ordering` smallint(6) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `site1_module` */

insert  into `site1_module`(`id`,`name`,`friendly_name`,`description`,`enabled`,`version`,`has_back_end`,`is_system`,`icon`,`path`,`ordering`) values (0,'Blog','Blog','',1,'1.0','y','','pen',NULL,NULL),(1,'Xpress','Yii Xpress','Yii Xpress is a boilerplate to build web application upon Yii with best practices',1,'1.0','n','t','',NULL,NULL),(2,'Diagnostic','Diagnostic tool','Dianostic tool help to setup the system and detect common issues during deployment.',1,'1.0','n','f',NULL,NULL,NULL),(3,'XUser','Users','User management using standard YiiXpres\'s XUser module',1,'1.0','y','t','user',NULL,NULL);

/*Table structure for table `site1_setting` */

DROP TABLE IF EXISTS `site1_setting`;

CREATE TABLE `site1_setting` (
  `id` int(11) NOT NULL,
  `name` varchar(64) NOT NULL,
  `label` varchar(64) DEFAULT NULL,
  `value` longtext,
  `description` longtext,
  `setting_group` varchar(128) DEFAULT NULL,
  `ordering` int(11) DEFAULT NULL,
  `visible` smallint(6) DEFAULT NULL,
  `module` varchar(64) DEFAULT NULL,
  `customizable` varchar(5) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `site1_setting` */

insert  into `site1_setting`(`id`,`name`,`label`,`value`,`description`,`setting_group`,`ordering`,`visible`,`module`,`customizable`) values (1,'ADMIN_EMAIL','Administrator\'s email','admin@email.com','Administrator email','1. General settings',5,1,'','f'),(2,'TIMEZONE','Timezone','UTC','','',9,0,'','f'),(3,'BO_PAGE_SIZE','Entries per page in Admin panel','50','Number of entries per page in Back Office','2. Appearance',1,1,'','f'),(4,'BO_THEME','Back Office theme','admin','Back Office theme','',7,0,'','f'),(5,'DEFAULT_BO_LAYOUT','Default BO layout','main','Default Back Office layout','',8,0,'','f'),(6,'DEFAULT_LAYOUT','Default layout','//layouts/main','Default layout','',6,0,'','f'),(7,'DEFAULT_META_DESCRIPTION','Default meta description','',NULL,'1. General settings',2,1,'','f'),(8,'DEFAULT_META_KEYWORDS','Default meta keywords','',NULL,'1. General settings',3,1,'','f'),(10,'MAIL_METHOD','Mail sending method','smtp','Method to send mails','3. Email',1,1,'','f'),(11,'MAIL_SENDER_NAME','Email sender name','YiiXpress','Email sender name','3. Email',7,1,'','f'),(12,'MAIL_SERDER_ADDRESS','Email sender address','support@site1.com','Email sender address','3. Email',8,1,'','f'),(13,'MAIL_SIGNATURE','Email signature','YiiXpress Team','Email signature','3. Email',9,1,'','f'),(14,'PAGE_SIZE','Entries per page','20','Number of entry per page','2. Appearance',2,1,'','f'),(15,'SITE_COPYRIGHT','Copyright','YiiXpress Copyright (c) 2013.<br/>All rights reseved.','Copyright text on footer','1. General settings',4,1,'','f'),(16,'SITE_NAME','Site name','YiiXpress Demo','Site name, displayed on browser\'s title and used for SEO','1. General settings',1,1,'','f'),(17,'SITE_SECRET_KEY','Site secret key','eae60a4ee7e4000b08269c7ea7202d2b','Site secret key','',1,0,'','f'),(18,'SMTP_HOST','SMTP host','localhost','SMTP host name','3. Email',2,1,'','f'),(19,'SMTP_PASSWORD','SMTP password','','SMTP password','3. Email',6,1,'','f'),(20,'SMTP_PORT','SMTP port','25','SMTP port','3. Email',3,1,'','f'),(21,'SMTP_SECURE','SMTP sercure connection','','SMTP secure connection','3. Email',5,1,'','f'),(22,'SMTP_USERNAME','SMTP username','','SMTP username','3. Email',4,1,'','f'),(23,'THEME','Theme',NULL,'Frontend theme','',3,1,'','f'),(24,'UPLOAD_FOLDER','User upload folder','uploads','User uploaded folder (you must grant write permission on this folder)','1. General settings',9,1,'','f'),(25,'URL_EXT','URL extension','.html','Url extension','',4,1,'','f'),(26,'TIME_FORMAT','Time format','h:mm a','','',10,0,'','f'),(76,'LOGIN_SESSION_TTL','Log In Session','1200','','1. General settings',NULL,1,'','f'),(82,'BULK_MAILS','Number Of Bulk Emails','30','','',NULL,0,'Messaging','f'),(97,'SESSION_TIME','Session time','array(\'\' =>300)','','',NULL,1,'','f'),(112,'XML_SITEMAP_CACHE_TIME','XML sitemap cache time','1200','XML sitemap cache time (by minute)','',NULL,1,'Site','t'),(119,'MAIL_METHOD_ALT','Alternate mail method','smtp','Send mail by injecting into sendmail instead of smtp','3. Email',NULL,1,'','t'),(122,'SENDMAIL_PATH','Sendmail path','/usr/sbin/sendmail','Sendmail path for mails','3.Email',NULL,1,'','t');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
