/*
SQLyog Ultimate v8.55 
MySQL - 5.5.5-10.1.9-MariaDB : Database - customer_relation_management
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`customer_relation_management` /*!40100 DEFAULT CHARACTER SET utf8 */;

USE `customer_relation_management`;

/*Table structure for table `account_statuses` */

DROP TABLE IF EXISTS `account_statuses`;

CREATE TABLE `account_statuses` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `is_deleted` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

/*Data for the table `account_statuses` */

insert  into `account_statuses`(`id`,`name`,`is_deleted`) values (1,'Contacted',0),(2,'Proposed',0);

/*Table structure for table `accounts` */

DROP TABLE IF EXISTS `accounts`;

CREATE TABLE `accounts` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `industry` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `email_address` varchar(255) NOT NULL,
  `phone` varchar(50) NOT NULL,
  `handler_id` bigint(20) NOT NULL,
  `account_status_id` bigint(20) NOT NULL,
  `date_modified` datetime DEFAULT NULL,
  `date_created` datetime DEFAULT NULL,
  `is_deleted` tinyint(1) DEFAULT '0',
  `notes` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

/*Data for the table `accounts` */

insert  into `accounts`(`id`,`name`,`industry`,`address`,`email_address`,`phone`,`handler_id`,`account_status_id`,`date_modified`,`date_created`,`is_deleted`,`notes`) values (1,'Jenny Bercasio','IT','qc commonwealth','jen@gmail.com','09876543213',3,2,'2016-02-18 12:36:14','2016-02-18 12:21:13',0,NULL),(2,'John','Motor','las pinas','euroshop@gmail.com','1234567',4,1,'2016-02-18 16:40:36','2016-02-18 16:40:36',0,'just a test');

/*Table structure for table `accounts_contact_persons` */

DROP TABLE IF EXISTS `accounts_contact_persons`;

CREATE TABLE `accounts_contact_persons` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `account_id` bigint(20) DEFAULT NULL,
  `contact_person_id` bigint(20) DEFAULT NULL,
  `notes` varchar(255) DEFAULT NULL,
  `user_id` bigint(20) DEFAULT NULL,
  `is_deleted` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `accounts_contact_persons` */

/*Table structure for table `accounts_products` */

DROP TABLE IF EXISTS `accounts_products`;

CREATE TABLE `accounts_products` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `opp_id` bigint(20) DEFAULT NULL,
  `product_name` varchar(255) DEFAULT NULL,
  `assigned_to` bigint(20) DEFAULT NULL,
  `date_modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `date_start` date DEFAULT NULL,
  `date_end` date DEFAULT NULL,
  `price` float DEFAULT NULL,
  `product_status_id` bigint(20) DEFAULT NULL,
  `is_deleted` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*Data for the table `accounts_products` */

insert  into `accounts_products`(`id`,`opp_id`,`product_name`,`assigned_to`,`date_modified`,`date_start`,`date_end`,`price`,`product_status_id`,`is_deleted`) values (1,NULL,'laptop',1,'2016-03-10 12:11:11',NULL,NULL,10,1,0);

/*Table structure for table `act_types` */

DROP TABLE IF EXISTS `act_types`;

CREATE TABLE `act_types` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `type` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

/*Data for the table `act_types` */

insert  into `act_types`(`id`,`name`,`type`) values (1,'To Do',1),(2,'Call',0),(3,'Meeting',0),(4,'Mobile Call',0),(5,'Birthday',2),(6,'Organization',3),(7,'Opportunity',4);

/*Table structure for table `activities` */

DROP TABLE IF EXISTS `activities`;

CREATE TABLE `activities` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `opp_id` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `notes` varchar(255) NOT NULL,
  `page` varchar(50) NOT NULL,
  `item` int(11) NOT NULL,
  `action_date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=92 DEFAULT CHARSET=utf8;

/*Data for the table `activities` */

insert  into `activities`(`id`,`opp_id`,`user_id`,`notes`,`page`,`item`,`action_date`) values (3,2,1,'Updated quote id 2','',0,'2016-04-08 00:00:00'),(4,2,2,'Updated quote id 2','',0,'2016-04-08 00:00:00'),(5,2,2,'Updated quote id 2 file.','',0,'2016-04-08 00:00:00'),(6,2,1,'Updated quote id 2 details.','',2,'2016-04-08 00:00:00'),(7,2,1,'Updated quote id 2 details.','quotes',2,'2016-04-08 00:00:00'),(8,2,5,'Updated quote id 1 details.','quotes',1,'2016-04-11 00:00:00'),(86,5,5,'Added a contact ()','',0,'2016-04-15 10:18:08'),(87,5,5,'Added a document (Project Tracker)','',0,'2016-04-15 10:51:53'),(88,6,1,'Added a quote (test quote)','',0,'2016-04-18 10:05:11'),(89,6,1,'Added a product (Accounting)','',0,'2016-04-18 13:42:24'),(90,6,1,'Modified quote (test quote123)','',0,'2016-04-18 14:05:40'),(91,6,1,'Modified quote (test quote1234)','',0,'2016-04-18 14:07:44');

/*Table structure for table `calendar` */

DROP TABLE IF EXISTS `calendar`;

CREATE TABLE `calendar` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `startdate` varchar(48) NOT NULL,
  `enddate` varchar(48) NOT NULL,
  `allDay` varchar(5) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;

/*Data for the table `calendar` */

insert  into `calendar`(`id`,`title`,`startdate`,`enddate`,`allDay`) values (1,'Birthday','2016-03-08','2016-03-08','false'),(2,'a','2016-03-22T00:00:00','2016-03-22T00:00:00','false'),(3,'1','2016-03-02','2016-03-02','true'),(4,'y','2016-03-15T00:30:00','2016-03-15T00:30:00','false'),(6,'Retreat','2016-03-19','2016-03-20','false'),(7,'THIS IS A TEST','2016-03-21T09:00:00','2016-03-21T012:30:00','false'),(8,'april','2016-04-02','2016-04-02','false'),(9,'Sir Jordan\'s Birthday','2016-03-23','2016-03-23','true'),(10,'sample','2016-03-21 14:37:29','2016-03-21 15:37:29','false');

/*Table structure for table `calendar_event` */

DROP TABLE IF EXISTS `calendar_event`;

CREATE TABLE `calendar_event` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `event` varchar(300) DEFAULT NULL,
  `description` text NOT NULL,
  `day` int(8) DEFAULT NULL,
  `month` int(8) DEFAULT NULL,
  `year` int(8) DEFAULT NULL,
  `time_from` varchar(10) NOT NULL,
  `time_until` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `calendar_event` */

/*Table structure for table `calendar_users` */

DROP TABLE IF EXISTS `calendar_users`;

CREATE TABLE `calendar_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) DEFAULT NULL,
  `password` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*Data for the table `calendar_users` */

insert  into `calendar_users`(`id`,`username`,`password`) values (1,'root','68b1f6ea805204c193c63b9bee8bce63');

/*Table structure for table `contact_persons` */

DROP TABLE IF EXISTS `contact_persons`;

CREATE TABLE `contact_persons` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `department` varchar(255) DEFAULT NULL,
  `contact_no` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `is_deleted` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `contact_persons` */

/*Table structure for table `contacts` */

DROP TABLE IF EXISTS `contacts`;

CREATE TABLE `contacts` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `fname` varchar(255) DEFAULT NULL,
  `lname` varchar(255) DEFAULT NULL,
  `office_phone` varchar(50) DEFAULT NULL,
  `home_phone` varchar(50) DEFAULT NULL,
  `mobile_phone` varchar(50) DEFAULT NULL,
  `org_id` bigint(20) DEFAULT NULL,
  `department_id` bigint(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `assigned_to` bigint(20) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `profile_pic` varchar(255) DEFAULT NULL,
  `is_deleted` tinyint(1) DEFAULT '0',
  `date_created` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

/*Data for the table `contacts` */

insert  into `contacts`(`id`,`fname`,`lname`,`office_phone`,`home_phone`,`mobile_phone`,`org_id`,`department_id`,`email`,`dob`,`assigned_to`,`description`,`profile_pic`,`is_deleted`,`date_created`) values (1,'Carla','Barja','12345','67890','101112',1,1,'carla@ymail.com','1990-04-30',1,'test contact','Barja, Carla.jpg',0,NULL),(2,'Hanna','Celzo','56',NULL,NULL,2,2,NULL,'1990-04-28',2,'test 101','Bercasio, Jen.jpg',0,NULL),(3,'Jenny','Asis','12345',NULL,NULL,1,3,'hanna@gmail.com','1990-04-20',2,'test only dude',NULL,0,NULL),(4,'Jen','Bercasio','3256','9425631','09465437746',2,2,'buenojen@ymail.com','1993-04-26',1,'SDG','Bercasio, Jen.jpg',0,'2016-04-07 09:50:38'),(5,'Mia','Bercilla','3256','234','092475675235',6,4,'mia@info.com','1990-04-18',2,'safhbjnk re','Bercilla, Mia.jpg',0,'2016-04-07 10:02:08');

/*Table structure for table `departments` */

DROP TABLE IF EXISTS `departments`;

CREATE TABLE `departments` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `is_deleted` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

/*Data for the table `departments` */

insert  into `departments`(`id`,`name`,`is_deleted`) values (1,'IT',0),(2,'Marketing',0),(3,'Sales',0),(4,'Management',0),(5,'Finance',0);

/*Table structure for table `documents` */

DROP TABLE IF EXISTS `documents`;

CREATE TABLE `documents` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `document` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `date_uploaded` datetime DEFAULT CURRENT_TIMESTAMP,
  `date_modified` datetime DEFAULT CURRENT_TIMESTAMP,
  `user_name` bigint(20) DEFAULT NULL,
  `opp_id` bigint(20) DEFAULT NULL,
  `is_deleted` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8;

/*Data for the table `documents` */

insert  into `documents`(`id`,`title`,`subject`,`document`,`description`,`date_uploaded`,`date_modified`,`user_name`,`opp_id`,`is_deleted`) values (1,'UMA test','um','_Document_2016-04-07-10-00-22.xlsx','move on','2016-04-07 16:00:22','2016-04-14 08:11:58',2,1,0),(2,'TEST',NULL,'2_Document_2016-04-01-04-46-23.jpg','test2','2016-04-01 00:00:00','2016-04-07 16:02:49',1,2,0),(3,'Mr Puffy',NULL,'2_Document_2016-04-07-04-12-31.pdf','wqtw','2016-04-07 00:00:00','2016-04-07 12:03:38',1,2,0),(4,'ewt',NULL,'Default.jpg','wet','2016-04-07 13:35:56','2016-04-07 13:35:56',1,2,0),(5,'ooow',NULL,'Default.jpg','sdgsg','2016-04-07 13:42:02','2016-04-07 13:42:02',1,2,0),(6,'LSA',NULL,'Default.jpg','lsa','2016-04-07 13:43:55','2016-04-07 13:43:55',2,3,0),(7,'tues',NULL,'Default.jpg','et111','2016-04-07 13:45:43','2016-04-12 11:33:50',1,3,1),(8,'kaysee',NULL,'3_Document_2016-04-07-08-21-07.jpg','sdfghj','2016-04-07 14:21:07','2016-04-07 14:21:07',1,3,0),(9,'TEST 2',NULL,'JAMES REID AND NADINE LUSTRE - BAHALA NA OFFICIAL LYRICS VIDEO.mp3','rtdowertyu','2016-04-07 14:48:40','2016-04-11 16:29:34',1,1,0),(10,'Nami here',NULL,'nami.jpg','wqttt','2016-04-07 15:15:31','2016-04-07 15:15:31',1,2,0),(11,'GANTT CHART',NULL,'Gantt chart CRM.xlsx','CHART','2016-04-07 15:23:54','2016-04-07 15:23:54',2,3,0),(12,'gant',NULL,'2_Document_2016-04-07-09-38-36.xlsx','gant','2016-04-07 15:38:36','2016-04-07 15:38:36',1,2,0),(13,'my title',NULL,'3_Document_2016-04-07-10-06-58.sql','asdggfdfghjkl;dfghjnkl','2016-04-07 16:06:58','2016-04-07 16:06:58',1,3,0),(14,'HRIS',NULL,'HRIS (v1) modules.xlsx','dfghbjnk','2016-04-07 16:29:46','2016-04-07 16:29:46',1,4,0),(15,'me',NULL,'IMG_3682.JPG','myself and I','2016-04-08 09:58:44','2016-04-08 09:58:44',1,1,0),(16,'db',NULL,'hrisv2db (4).sql','db hris','2016-04-08 10:00:25','2016-04-11 17:22:25',1,1,0),(17,'gc',NULL,'Gantt chart CRM.xlsx','gc crm','2016-04-08 10:00:53','2016-04-08 10:00:53',1,1,0),(18,'HRIS',NULL,'HRIS (v1) modules.xlsx','db hris','2016-04-08 17:00:36','2016-04-08 17:00:36',2,2,0),(19,'jen',NULL,'crm.sql','sfyry','2016-04-09 10:55:33','2016-04-09 10:55:33',3,1,0),(20,'monday',NULL,'biggest-loser.docx','monday test','2016-04-11 13:20:03','2016-04-11 13:20:03',1,1,0),(21,'iso',NULL,'iso8583.pdf','iso file','2016-04-13 22:44:33','2016-04-13 22:44:33',1,3,0),(22,'event',NULL,'2_Document_2016-04-14-02-14-33.sql','qet','2016-04-14 08:14:33','2016-04-14 08:14:33',1,2,0),(23,'Project Tracker',NULL,'Project Tracker PRODCOM.xlsx','test 1','2016-04-15 10:51:53','2016-04-15 10:51:53',1,5,0);

/*Table structure for table `event_status` */

DROP TABLE IF EXISTS `event_status`;

CREATE TABLE `event_status` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `type` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

/*Data for the table `event_status` */

insert  into `event_status`(`id`,`name`,`type`) values (1,'Not Started',1),(2,'In Progress',1),(3,'Completed',1),(4,'Pending Input',1),(5,'Deferred',1),(6,'Planned',3),(7,'Held',2),(8,'Not Held',2);

/*Table structure for table `events` */

DROP TABLE IF EXISTS `events`;

CREATE TABLE `events` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `subject` varchar(255) DEFAULT NULL,
  `assigned_to` bigint(20) DEFAULT NULL,
  `start_date` datetime DEFAULT NULL,
  `end_date` datetime DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `event_stat` bigint(20) DEFAULT NULL,
  `activity_type` bigint(20) DEFAULT NULL,
  `location_id` bigint(20) DEFAULT NULL,
  `priority` int(11) DEFAULT NULL COMMENT 'High(1), Medium(2), Low(3)',
  `description` varchar(255) DEFAULT NULL,
  `is_deleted` tinyint(1) DEFAULT '0',
  `is_related` bigint(20) DEFAULT NULL,
  `date_created` datetime DEFAULT CURRENT_TIMESTAMP,
  `date_modified` datetime DEFAULT NULL,
  `allDay` varchar(5) DEFAULT NULL,
  `org_id` bigint(20) DEFAULT NULL,
  `opp_id` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8 COMMENT='High(1), Medium(2), Low(3)';

/*Data for the table `events` */

insert  into `events`(`id`,`subject`,`assigned_to`,`start_date`,`end_date`,`due_date`,`event_stat`,`activity_type`,`location_id`,`priority`,`description`,`is_deleted`,`is_related`,`date_created`,`date_modified`,`allDay`,`org_id`,`opp_id`) values (1,'Meeting with Euro Shop',1,'2016-02-28 18:00:00','2016-02-28 12:00:00','2016-03-20',1,1,0,0,'test event',0,1,'2016-03-21 13:55:44','2016-04-14 16:12:55','false',NULL,2),(2,'Call BHF now',2,'2016-03-28 20:00:00','2016-03-29 07:00:00','2016-03-29',6,4,0,1,'test',0,1,'2016-03-21 13:55:44','2016-04-14 13:35:42','false',NULL,1),(3,'Summer',1,'2016-03-21 10:00:00','0000-00-00 00:00:00','2016-03-29',1,1,2,2,'asha',0,2,'2016-03-21 14:37:29',NULL,'false',NULL,1),(4,'mathinique',1,'2016-03-18 17:00:00','2016-03-18 21:09:00','2016-04-15',6,1,0,0,'wee',0,2,'2016-03-21 16:38:54','2016-04-14 16:13:15','false',NULL,2),(5,'Accounting',1,'2016-04-30 00:00:00','2016-04-30 00:00:00',NULL,1,7,NULL,1,NULL,0,2,'2016-04-01 09:27:26',NULL,'true',NULL,1),(6,'Payroll',1,'2016-04-30 00:00:00','2016-04-30 00:00:00',NULL,1,7,NULL,1,NULL,0,2,'2016-04-01 09:33:57',NULL,'true',NULL,2),(7,'Payroll',1,'2016-04-29 00:00:00','2016-04-29 00:00:00',NULL,1,7,NULL,1,NULL,0,2,'2016-04-01 09:35:11',NULL,'true',NULL,2),(8,'Payroll',2,'2016-04-16 00:00:00','2016-04-16 00:00:00',NULL,1,7,NULL,1,NULL,0,2,'2016-04-01 10:05:40',NULL,'true',NULL,2),(9,'Accounting',1,'2016-05-18 00:00:00','2016-05-18 00:00:00',NULL,1,7,NULL,2,NULL,0,2,'2016-04-01 10:27:30',NULL,'true',NULL,2),(10,'test',1,'2016-04-01 00:00:00','2016-04-01 00:00:00',NULL,1,7,NULL,2,NULL,0,3,'2016-04-01 10:28:00',NULL,'true',NULL,1),(11,'Training',1,'2016-04-04 14:00:00','2016-04-04 17:00:00',NULL,2,3,0,1,'EMV Training',0,1,'2016-04-04 13:04:37',NULL,NULL,NULL,2),(12,'test',1,'2016-04-29 00:00:00','2016-04-29 00:00:00',NULL,1,7,NULL,3,NULL,0,2,'2016-04-05 10:16:43',NULL,'true',NULL,2),(13,'kitkat',1,'2016-04-27 00:00:00','2016-04-27 00:00:00',NULL,1,7,NULL,3,NULL,0,1,'2016-04-05 10:38:21',NULL,'true',NULL,1),(14,'Jen Bercasio\'s Birthday',1,'2016-04-06 00:00:00','2016-04-06 00:00:00',NULL,2,5,NULL,3,'SDG',0,1,'2016-04-07 10:00:05',NULL,'true',NULL,1),(15,'Mia Bercilla\'s Birthday',2,'2016-04-07 00:00:00','2016-04-07 00:00:00',NULL,2,5,NULL,2,'safhbjnk re',0,1,'2016-04-07 10:02:08',NULL,'true',NULL,2),(16,'AR',1,'2016-04-08 00:00:00','2016-04-08 00:00:00',NULL,2,7,NULL,2,NULL,0,1,'2016-04-07 10:02:46',NULL,'true',NULL,1),(17,'Training Camp',1,'2016-04-24 08:00:00','2016-04-28 17:00:00',NULL,6,3,0,1,'ertryguhi',0,1,'2016-04-13 10:38:09',NULL,'false',NULL,1),(18,'Move on',1,'2016-04-30 08:00:00','2016-05-04 17:00:00',NULL,8,3,NULL,3,NULL,0,3,'2016-04-13 11:38:21',NULL,'false',NULL,3),(19,'This is it.',1,'2016-04-30 08:00:00','2016-05-04 17:00:00',NULL,8,3,NULL,2,NULL,0,3,'2016-04-13 11:44:41',NULL,'false',NULL,3),(20,'Wednesday meeting',1,'2016-04-13 09:00:00','2016-04-13 17:00:00',NULL,6,3,NULL,2,NULL,0,3,'2016-04-13 12:53:26',NULL,'false',NULL,3),(21,'To do test',1,'2016-04-13 12:00:00',NULL,'2016-04-21',2,1,NULL,2,NULL,0,3,'2016-04-13 14:07:55',NULL,'false',NULL,3),(22,'Delivery',1,'2016-04-13 18:00:00',NULL,'2016-04-20',3,1,NULL,2,NULL,0,3,'2016-04-13 14:22:02','2016-04-13 16:26:53','false',NULL,3),(23,'call jenny',1,'2016-04-20 21:30:00','2016-04-20 21:30:00',NULL,6,4,NULL,2,NULL,0,3,'2016-04-13 14:41:34',NULL,'false',NULL,3),(24,'Call me maybe',1,'2016-04-14 22:00:00',NULL,'2016-04-14',1,1,NULL,2,NULL,0,3,'2016-04-13 14:43:52',NULL,'false',NULL,3),(25,'Puntang SM',1,'2016-04-14 14:00:00',NULL,'2016-04-14',1,1,NULL,2,NULL,0,3,'2016-04-13 16:02:03',NULL,'false',NULL,3),(26,'ADD ME DITO',1,'2016-04-20 09:00:00','2016-04-20 09:00:00',NULL,6,3,NULL,2,NULL,0,3,'2016-04-13 16:38:40','2016-04-13 16:45:07','false',NULL,3),(27,'Meet anna at Mega',1,'2016-04-22 09:00:00','2016-04-22 09:00:00',NULL,6,3,NULL,2,NULL,0,NULL,'2016-04-13 17:42:41','2016-04-13 17:42:54','false',NULL,3),(28,'Eat Bulaga',1,'2016-04-11 10:00:00',NULL,'2016-04-11',3,1,NULL,2,NULL,0,3,'2016-04-13 17:43:31','2016-04-13 17:43:49','false',NULL,3),(29,'AR',1,'2016-04-27 00:00:00','2016-04-27 00:00:00',NULL,NULL,7,NULL,2,NULL,0,NULL,'2016-04-13 23:04:34',NULL,'true',NULL,3),(30,'Meet anna at MAkati',3,'2016-04-29 10:00:00','2016-04-29 12:00:00',NULL,6,3,NULL,2,NULL,0,NULL,'2016-04-14 10:08:15',NULL,'false',NULL,2),(31,'Follow Up BDO',3,'2016-04-14 12:00:00',NULL,'2016-04-14',1,1,NULL,2,NULL,0,4,'2016-04-14 10:14:01',NULL,'false',NULL,2),(32,'AR',3,'2016-04-29 00:00:00','2016-04-29 00:00:00',NULL,NULL,7,NULL,2,NULL,0,NULL,'2016-04-14 10:21:48',NULL,'true',NULL,2),(35,'gala ulit',3,'2016-04-22 18:00:00',NULL,'2016-04-23',2,1,NULL,NULL,NULL,0,4,'2016-04-14 14:30:20','2016-04-14 14:32:31','false',NULL,2),(36,'Kain',3,'2016-04-14 18:00:00','2016-04-14 18:00:00','2016-04-14',4,1,0,2,'gala + lamon',0,NULL,'2016-04-14 14:41:23',NULL,'false',NULL,0),(37,'Jogging',2,'2016-04-14 19:00:00',NULL,'2016-04-14',1,1,0,3,'jog pa more',0,NULL,'2016-04-14 15:44:19',NULL,'false',NULL,0),(38,'Accounting',1,'2016-04-26 00:00:00','2016-04-26 00:00:00',NULL,NULL,7,NULL,NULL,NULL,0,NULL,'2016-04-18 13:42:24',NULL,'true',NULL,6);

/*Table structure for table `file_categories` */

DROP TABLE IF EXISTS `file_categories`;

CREATE TABLE `file_categories` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `is_deleted` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

/*Data for the table `file_categories` */

insert  into `file_categories`(`id`,`name`,`is_deleted`) values (1,'quotation',0),(2,'purchase order',0),(3,'invoices',0),(4,'others',0);

/*Table structure for table `files` */

DROP TABLE IF EXISTS `files`;

CREATE TABLE `files` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `description` varchar(20) DEFAULT NULL,
  `document` varchar(20) DEFAULT NULL,
  `user_id` bigint(20) DEFAULT NULL,
  `date_uploaded` datetime DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  `opp_id` bigint(20) DEFAULT NULL,
  `cat_id` bigint(20) DEFAULT NULL,
  `is_deleted` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;

/*Data for the table `files` */

insert  into `files`(`id`,`title`,`subject`,`description`,`document`,`user_id`,`date_uploaded`,`date_modified`,`opp_id`,`cat_id`,`is_deleted`) values (1,'PO2','wrer','Purchase order test','po.docx',1,'2016-04-18 23:06:28','2016-04-18 14:23:06',6,2,0),(2,'rtf','trfgh','fgvhbj','test.docx',1,'2016-04-16 23:06:28','2016-04-16 23:06:28',6,3,0),(3,'je','tfdg','gfdg','gfhd',1,'2016-04-16 23:06:28','2016-04-18 11:18:15',6,3,0),(4,'po222',NULL,'potpot and friends','Project Tracker PROD',1,'2016-04-18 10:21:00','2016-04-18 14:24:20',6,2,0),(5,'pt',NULL,'pt trak','iso8583.pdf',1,'2016-04-18 11:38:54','2016-04-18 11:38:54',6,3,0),(6,'111111111111',NULL,'pt trak','iso8583.pdf',1,'2016-04-18 11:39:49','2016-04-18 11:49:11',6,3,0),(7,'22 update',NULL,'pt trak','iso8583.pdf',1,'2016-04-18 11:43:19','2016-04-18 12:12:08',6,3,0),(8,'33333333333',NULL,'12345678','euro shop 3-31-16.tx',1,'2016-04-18 11:50:20','2016-04-18 11:50:20',6,4,0),(9,'444444444',NULL,'12345678','euro shop 3-31-16.tx',1,'2016-04-18 11:50:51','2016-04-18 11:50:51',6,4,0),(10,'5555',NULL,'55555','iso8583.pdf',1,'2016-04-18 11:55:19','2016-04-18 11:55:19',6,4,0),(11,'666',NULL,'66','iso8583.pdf',1,'2016-04-18 11:56:33','2016-04-18 11:56:33',6,4,0),(12,'others',NULL,'others file','iso8583.pdf',1,'2016-04-18 14:33:19','2016-04-18 14:33:19',6,3,0),(13,'others',NULL,'others','iso8583.pdf',1,'2016-04-18 14:34:31','2016-04-18 14:34:31',6,3,0),(14,'others',NULL,'others','iso8583.pdf',1,'2016-04-18 14:35:45','2016-04-18 14:35:45',6,3,0),(15,'others',NULL,'wqertgh','iso8583.pdf',1,'2016-04-18 14:37:48','2016-04-18 14:37:48',6,3,0),(16,'UMAasa',NULL,'stgasg','iso8583.pdf',1,'2016-04-18 14:41:45','2016-04-18 14:45:20',6,4,0);

/*Table structure for table `locations` */

DROP TABLE IF EXISTS `locations`;

CREATE TABLE `locations` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `is_deleted` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

/*Data for the table `locations` */

insert  into `locations`(`id`,`name`,`is_deleted`) values (1,'Ortigas',0),(2,'Alabang',0),(3,'Makati',0),(4,'Rizal',0),(5,'sdg',0),(6,'wdfgh',0);

/*Table structure for table `opp_contacts` */

DROP TABLE IF EXISTS `opp_contacts`;

CREATE TABLE `opp_contacts` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `c_id` bigint(20) DEFAULT NULL,
  `opp_id` bigint(20) DEFAULT NULL,
  `is_deleted` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

/*Data for the table `opp_contacts` */

insert  into `opp_contacts`(`id`,`c_id`,`opp_id`,`is_deleted`) values (1,3,4,1),(2,4,4,0),(3,2,5,0);

/*Table structure for table `opp_docs` */

DROP TABLE IF EXISTS `opp_docs`;

CREATE TABLE `opp_docs` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `doc_id` bigint(20) DEFAULT NULL,
  `opp_id` bigint(20) DEFAULT NULL,
  `is_deleted` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

/*Data for the table `opp_docs` */

insert  into `opp_docs`(`id`,`doc_id`,`opp_id`,`is_deleted`) values (1,1,2,0),(2,2,2,1);

/*Table structure for table `opp_products` */

DROP TABLE IF EXISTS `opp_products`;

CREATE TABLE `opp_products` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `prod_id` bigint(20) DEFAULT NULL,
  `opp_id` bigint(20) DEFAULT NULL,
  `prod_name` varchar(255) DEFAULT NULL,
  `prod_based_price` float DEFAULT NULL,
  `prod_price` float DEFAULT NULL,
  `commission_rate` float DEFAULT NULL,
  `assigned_to` bigint(20) DEFAULT NULL,
  `expected_close_date` date DEFAULT NULL,
  `is_deleted` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8;

/*Data for the table `opp_products` */

insert  into `opp_products`(`id`,`prod_id`,`opp_id`,`prod_name`,`prod_based_price`,`prod_price`,`commission_rate`,`assigned_to`,`expected_close_date`,`is_deleted`) values (1,6,2,'AR',20000,50000,500,2,'2016-04-01',0),(2,2,2,'kitkat',100,500,10,2,'2016-04-20',0),(3,4,2,'Payroll',50000,55000,10000,2,'2016-04-16',0),(4,5,2,'Accounting',50000,60000,1000,1,'2016-05-18',0),(5,3,1,'test',10000,1000,1000,1,'2016-04-01',0),(6,3,2,'test',10000,10000,0,1,'2016-04-29',1),(7,2,3,'kitkat',100,150,0,1,'2016-04-27',0),(8,6,1,'AR',20000,60000,150,1,'2016-04-08',0),(9,4,3,'Payroll',50000,60000,1000,1,'2016-04-20',0),(10,4,3,'Payroll',50000,60000,1000,1,'2016-04-20',0),(11,4,3,'Payroll',50000,60000,1000,1,'2016-04-20',0),(12,4,3,'Payroll',50000,60000,1000,1,'2016-04-20',0),(13,4,3,'Payroll',50000,60000,1000,1,'2016-04-20',0),(14,4,3,'Payroll',50000,60000,1000,1,'2016-04-20',0),(15,6,3,'AR',20000,50000,500,1,'2016-04-27',0),(16,6,4,'AR',20000,50000,1000,3,'2016-04-29',0),(17,5,6,'Accounting',50000,60000,1000,1,'2016-04-26',0);

/*Table structure for table `opp_quotes` */

DROP TABLE IF EXISTS `opp_quotes`;

CREATE TABLE `opp_quotes` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `opp_id` bigint(20) DEFAULT NULL,
  `quote_id` bigint(20) DEFAULT NULL,
  `is_deleted` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

/*Data for the table `opp_quotes` */

insert  into `opp_quotes`(`id`,`opp_id`,`quote_id`,`is_deleted`) values (1,2,1,0),(2,2,2,0),(3,2,3,0);

/*Table structure for table `opp_statuses` */

DROP TABLE IF EXISTS `opp_statuses`;

CREATE TABLE `opp_statuses` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `is_deleted` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

/*Data for the table `opp_statuses` */

insert  into `opp_statuses`(`id`,`name`,`is_deleted`) values (1,'Prospecting',0),(2,'Qualification',1),(3,'Need Analysis',1),(4,'Value Proposition',1),(5,'Identify Decision Makers',1),(6,'Perception Analysis',1),(7,'Proposal or Price Quote',0),(8,'Negotiation or Review',0),(9,'Closed Won',0),(10,'Closed Lost',0);

/*Table structure for table `opp_types` */

DROP TABLE IF EXISTS `opp_types`;

CREATE TABLE `opp_types` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `is_deleted` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

/*Data for the table `opp_types` */

insert  into `opp_types`(`id`,`name`,`is_deleted`) values (1,'Existing Business',0),(2,'New Business',0);

/*Table structure for table `opportunities` */

DROP TABLE IF EXISTS `opportunities`;

CREATE TABLE `opportunities` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `opp_name` varchar(255) DEFAULT NULL,
  `org_id` bigint(20) DEFAULT NULL,
  `contact_id` bigint(20) DEFAULT NULL,
  `opp_type` bigint(20) DEFAULT NULL,
  `assigned_to` bigint(20) DEFAULT NULL,
  `user_type` bigint(20) DEFAULT NULL,
  `sales_stage` bigint(20) DEFAULT NULL,
  `forecast_amount` float DEFAULT NULL,
  `amount` float DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `product_set` bigint(20) DEFAULT NULL,
  `expected_close_date` date DEFAULT NULL,
  `date_created` datetime DEFAULT CURRENT_TIMESTAMP,
  `date_modified` datetime DEFAULT CURRENT_TIMESTAMP,
  `is_deleted` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

/*Data for the table `opportunities` */

insert  into `opportunities`(`id`,`opp_name`,`org_id`,`contact_id`,`opp_type`,`assigned_to`,`user_type`,`sales_stage`,`forecast_amount`,`amount`,`description`,`product_set`,`expected_close_date`,`date_created`,`date_modified`,`is_deleted`) values (1,'Payroll',1,2,1,1,1,10,75000,100000,'',NULL,'2016-03-31','2016-03-17 11:16:40','2016-04-14 17:41:02',0),(2,'BHF Payroll',7,2,1,2,2,4,100000,50000,'',NULL,'2016-03-26','2016-03-17 11:20:17','2016-04-05 14:46:55',0),(3,'POD BDO',6,0,1,1,1,2,100000,NULL,'',NULL,'2016-05-30','2016-04-05 10:29:14','2016-04-05 10:29:14',0),(4,'Valiant',6,3,1,3,2,4,100000,NULL,'dsg',NULL,'2016-05-31','2016-04-07 16:22:12','2016-04-07 16:22:12',0),(5,'Angono',1,5,1,5,1,7,100000,NULL,'',NULL,'2016-04-20','2016-04-15 10:17:38','2016-04-15 10:17:38',0),(6,'HRIS for Goldilocks',6,2,2,1,NULL,7,0,NULL,'',NULL,'2016-04-29','2016-04-16 23:06:28','2016-04-16 23:06:28',0),(7,'test',6,3,1,1,NULL,7,0,NULL,'',NULL,'2016-04-30','2016-04-16 23:07:39','2016-04-16 23:07:39',0),(8,'LCC',6,2,2,1,NULL,7,100000,NULL,'',NULL,'2016-04-28','2016-04-18 17:46:49','2016-04-18 17:46:49',0);

/*Table structure for table `org_industry` */

DROP TABLE IF EXISTS `org_industry`;

CREATE TABLE `org_industry` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8;

/*Data for the table `org_industry` */

insert  into `org_industry`(`id`,`name`) values (1,'Apparel'),(2,'Banking'),(3,'Biotechnology'),(4,'Chemicals'),(5,'Communications'),(6,'Construction'),(7,'Consulting'),(8,'Education'),(9,'Electronics'),(10,'Energy'),(11,'Engineering'),(12,'Entertainment'),(13,'Environmental'),(14,'Finance'),(15,'Food & Beverage'),(16,'Government'),(17,'Healthcare'),(18,'Hospitality'),(19,'Insurance'),(20,'Machinery'),(21,'Manufacturing'),(22,'Media'),(23,'Not for Profit'),(24,'Recreation'),(25,'Retail'),(26,'Shipping'),(27,'Technology'),(28,'Telecommunications'),(29,'Transportation'),(30,'Utilities'),(31,'Others');

/*Table structure for table `org_ratings` */

DROP TABLE IF EXISTS `org_ratings`;

CREATE TABLE `org_ratings` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `is_deleted` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

/*Data for the table `org_ratings` */

insert  into `org_ratings`(`id`,`name`,`is_deleted`) values (1,'Acquired',0),(2,'Active',0),(3,'Market Failed',0),(4,'Project Cancelled',0),(5,'Shutdown',0);

/*Table structure for table `org_types` */

DROP TABLE IF EXISTS `org_types`;

CREATE TABLE `org_types` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `is_deleted` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

/*Data for the table `org_types` */

insert  into `org_types`(`id`,`name`,`is_deleted`) values (1,'Analyst',0),(2,'Competitor',0),(3,'Customer',0),(4,'Integrator',0),(5,'Investor',0),(6,'Partner',0);

/*Table structure for table `organizations` */

DROP TABLE IF EXISTS `organizations`;

CREATE TABLE `organizations` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `org_name` varchar(255) DEFAULT NULL,
  `reg_name` varchar(255) DEFAULT NULL,
  `trade_name` varchar(255) DEFAULT NULL,
  `tin_num` varchar(255) DEFAULT NULL,
  `tel_num` varchar(255) DEFAULT NULL,
  `phone_num` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `industry` bigint(20) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `rating` bigint(20) DEFAULT NULL,
  `contact_id` bigint(20) DEFAULT NULL,
  `org_type` bigint(20) DEFAULT NULL,
  `annual_revenue` float DEFAULT NULL,
  `assigned_to` int(11) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `date_created` datetime DEFAULT CURRENT_TIMESTAMP,
  `date_modified` datetime DEFAULT CURRENT_TIMESTAMP,
  `is_deleted` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

/*Data for the table `organizations` */

insert  into `organizations`(`id`,`org_name`,`reg_name`,`trade_name`,`tin_num`,`tel_num`,`phone_num`,`email`,`industry`,`address`,`rating`,`contact_id`,`org_type`,`annual_revenue`,`assigned_to`,`description`,`date_created`,`date_modified`,`is_deleted`) values (1,'Angono Rizal Project 1',NULL,NULL,NULL,NULL,'3448009','angono@ymail.com',5,'pasig city phl',1,NULL,1,1000000,1,'','2016-03-04 12:36:14','2016-04-12 13:14:21',0),(2,'Euro Shop',NULL,NULL,NULL,NULL,'1234567','eurosop@ymail.com',10,'Las Pinas',3,NULL,2,5000000,2,'test2','2016-03-04 12:36:14','2016-03-04 12:36:14',0),(3,'Landbank',NULL,NULL,NULL,NULL,'9876543','test@gmail.com',14,'sample',2,NULL,3,10000,3,'test3','2016-03-04 12:36:14','2016-03-04 12:36:14',0),(4,'Rustans',NULL,NULL,NULL,NULL,'1234','',0,'',0,NULL,0,0,4,'','2016-03-04 12:36:14','2016-03-04 12:36:14',0),(5,'Unionbank',NULL,NULL,NULL,NULL,'0987654321','jen@gmail.com',2,'qc commonwealth',2,NULL,2,10,2,'test','2016-03-04 12:36:14','2016-03-04 12:36:14',0),(6,'BDO',NULL,NULL,NULL,NULL,'','',0,'',0,NULL,0,0,2,'','2016-03-04 12:36:14','2016-03-04 12:36:14',0),(7,'BHF',NULL,NULL,NULL,NULL,'0987654321','bh@ymail.com',3,'manila',2,NULL,6,0,3,'','2016-03-04 12:36:14','2016-03-04 12:36:14',0),(8,'Bench',NULL,NULL,NULL,NULL,'9876543','bench@info.com.ph',3,'Makati',2,NULL,3,0,2,'','2016-03-15 08:55:38','2016-03-15 08:59:45',0),(9,'DAR',NULL,NULL,NULL,NULL,'','',3,'',2,NULL,3,0,2,'test dar','2016-03-17 09:39:45','2016-03-17 09:39:45',0),(10,'wer1111111111111','ertd','fert','4356','567','567','fg@gmail.com',2,'try',2,2,2,0,1,'waesrdtfyguhi','2016-04-18 17:27:06','2016-04-18 17:37:14',0);

/*Table structure for table `posts` */

DROP TABLE IF EXISTS `posts`;

CREATE TABLE `posts` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `message` varchar(255) DEFAULT NULL,
  `user_id` bigint(20) DEFAULT NULL,
  `post_type` int(11) DEFAULT NULL COMMENT '1-white,2-yellow,3-red',
  `is_deleted` tinyint(1) DEFAULT '0',
  `date_created` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*Data for the table `posts` */

insert  into `posts`(`id`,`title`,`message`,`user_id`,`post_type`,`is_deleted`,`date_created`) values (1,'Sample Post','This sample post',1,1,0,'2016-02-17');

/*Table structure for table `priorities` */

DROP TABLE IF EXISTS `priorities`;

CREATE TABLE `priorities` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

/*Data for the table `priorities` */

insert  into `priorities`(`id`,`name`) values (1,'High'),(2,'Medium'),(3,'Low');

/*Table structure for table `product_statuses` */

DROP TABLE IF EXISTS `product_statuses`;

CREATE TABLE `product_statuses` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `is_deleted` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

/*Data for the table `product_statuses` */

insert  into `product_statuses`(`id`,`name`,`is_deleted`) values (1,'Interested',0),(2,'Rejected',0);

/*Table structure for table `products` */

DROP TABLE IF EXISTS `products`;

CREATE TABLE `products` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `opp_id` bigint(20) DEFAULT NULL,
  `prod_code` varchar(255) DEFAULT NULL,
  `product_name` varchar(255) DEFAULT NULL,
  `unit_price` float DEFAULT NULL,
  `commission_rate` float DEFAULT NULL,
  `qty_unit` bigint(20) DEFAULT NULL,
  `total_price` float DEFAULT NULL,
  `assigned_to` bigint(20) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_deleted` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

/*Data for the table `products` */

insert  into `products`(`id`,`opp_id`,`prod_code`,`product_name`,`unit_price`,`commission_rate`,`qty_unit`,`total_price`,`assigned_to`,`description`,`is_deleted`) values (1,1,'prod1','PMS1',50000,1000,1,50000,1,'',0),(2,2,'prod2','kitkat',100,1,1,1,1,'',0),(3,1,'testcode','test',10000,1000,1,10000,1,'test',0),(4,4,'prod4','Payroll',50000,5000,1,50000,1,'For BHF',0),(5,2,'prod5','Accounting',50000,1000,1,50000,1,'',0),(6,1,'prod6','AR',20000,1000,3,60000,1,'',0);

/*Table structure for table `quotes` */

DROP TABLE IF EXISTS `quotes`;

CREATE TABLE `quotes` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `user_name` bigint(20) DEFAULT NULL,
  `date_uploaded` datetime DEFAULT CURRENT_TIMESTAMP,
  `date_modified` datetime DEFAULT CURRENT_TIMESTAMP,
  `document` varchar(255) DEFAULT NULL,
  `opportunity_name` bigint(20) DEFAULT NULL,
  `is_deleted` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8;

/*Data for the table `quotes` */

insert  into `quotes`(`id`,`title`,`subject`,`description`,`user_name`,`date_uploaded`,`date_modified`,`document`,`opportunity_name`,`is_deleted`) values (1,'TEST',NULL,'SDH',1,'2016-04-01 00:00:00','2016-04-08 09:39:39','2_Quotation_2016-04-01-16-42-02.xlsx',3,0),(2,'HRIS',NULL,'',1,'2016-04-02 00:00:00','2016-04-08 09:39:39','2_Quotation_2016-04-02-02-59-15.xlsx',1,0),(3,'Report',NULL,'Abby',2,'2016-04-02 00:00:00','2016-04-08 09:39:39','2_Quotation_2016-04-02-03-04-16.xlsx',2,0),(4,'db',NULL,'crm db6',1,'2016-04-11 17:34:22','2016-04-08 10:29:30','crm.sql',1,0),(5,'hris',NULL,'hris db',1,'2016-04-08 10:30:45','2016-04-08 10:30:45','HRIS (v1) modules.xlsx',3,0),(6,'kaysee',NULL,'qrwr',1,'2016-04-08 10:35:27','2016-04-08 10:35:27','IMG_3682.JPG',3,0),(7,'gc',NULL,'wryuio',1,'2016-04-11 14:15:34','2016-04-11 14:15:34','1_Quotation_2016-04-11-08-15-34.xlsx',1,0),(8,'tuesday',NULL,'tuesday test',1,'2016-04-12 11:13:21','2016-04-12 11:13:21','save_documents.php',3,0),(9,'Jhay',NULL,'Jhay Frias',1,'2016-04-13 21:11:56','2016-04-13 21:11:56','documents.php',1,0),(10,'wertyu',NULL,'rh',1,'2016-04-13 21:14:42','2016-04-13 21:14:42','frm_documents.php',1,0),(11,'ctvg',NULL,'ur',1,'2016-04-13 21:17:08','2016-04-13 21:17:08','frm_documents.php',1,0),(12,'frias',NULL,'frias',1,'2016-04-13 21:17:43','2016-04-13 21:17:43','frm_documents.php',1,0),(13,'Lord Jay',NULL,'kulot',1,'2016-04-13 21:22:05','2016-04-13 21:22:05','save_documents.php',1,0),(14,'don don',NULL,'kulot ulit',1,'2016-04-13 21:27:13','2016-04-13 21:27:13','Spotify.exe',1,0),(15,'jjjjjjjjjjjjjjjjj',NULL,'jjjjjjjjjjjjj',1,'2016-04-13 21:40:19','2016-04-13 21:40:19','cef_200_percent.pak',1,0),(16,'qqqqqqqqqqqq',NULL,'qqqqqqqqqqq',1,'2016-04-13 21:44:30','2016-04-13 21:44:30','crm.sql',3,0),(17,'pt',NULL,'pt',1,'2016-04-13 22:09:18','2016-04-13 22:09:18','Project Tracker PRODCOM.xlsx',3,0),(18,'pt',NULL,'pt',1,'2016-04-13 22:09:42','2016-04-13 22:09:42','Project Tracker PRODCOM.xlsx',3,0),(19,'ptertyui',NULL,'papay',1,'2016-04-13 22:11:02','2016-04-13 22:11:02','Project Tracker PRODCOM.xlsx',3,0),(20,'test quote1234',NULL,'wqtqet',1,'2016-04-18 10:05:11','2016-04-18 14:07:43','Project Tracker PRODCOM.xlsx',6,0);

/*Table structure for table `to_dos` */

DROP TABLE IF EXISTS `to_dos`;

CREATE TABLE `to_dos` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `subject` varchar(255) DEFAULT NULL,
  `assigned_to` bigint(20) DEFAULT NULL,
  `start_date` datetime DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `contact_id` bigint(20) DEFAULT NULL,
  `status_id` bigint(20) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_deleted` tinyint(2) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*Data for the table `to_dos` */

insert  into `to_dos`(`id`,`subject`,`assigned_to`,`start_date`,`due_date`,`contact_id`,`status_id`,`location`,`description`,`is_deleted`) values (1,'Pickup ',1,'2016-03-17 11:20:17','2016-03-30',1,1,'Pasig','test to do',0);

/*Table structure for table `user_types` */

DROP TABLE IF EXISTS `user_types`;

CREATE TABLE `user_types` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `is_deleted` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

/*Data for the table `user_types` */

insert  into `user_types`(`id`,`name`,`is_deleted`) values (1,'Administrator',0),(2,'Agents',0),(3,'CSR',0),(4,'Marketing',0),(5,'Manager',0);

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_type_id` bigint(20) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `middle_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `contact_no` varchar(255) NOT NULL,
  `last_login` date NOT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='Table for users';

/*Data for the table `users` */

insert  into `users`(`id`,`user_type_id`,`first_name`,`middle_name`,`last_name`,`username`,`password`,`email`,`contact_no`,`last_login`,`is_deleted`) values (1,1,'Ad','-','Min','admin','admin','a@a.com','0940124','0000-00-00',0),(2,2,'Abbigail','a','Luzon','abby','abby','abby@gmail.com','0987654321','0000-00-00',0),(3,2,'Elka','L','Mejia','elka','elka','elka@gmail.com','1234567890','0000-00-00',0),(4,3,'Prettie','-','Pantaleon','csr','csr','pj@gmail.co','','0000-00-00',0),(5,1,'test','-','lang','jen','3f4N0Lr0a1e/pVFSAOWE/2x7+xaQF6MeZF2agPO3Yuo=','test@gmail.com','214','0000-00-00',0);

/*Table structure for table `qry_posts` */

DROP TABLE IF EXISTS `qry_posts`;

/*!50001 DROP VIEW IF EXISTS `qry_posts` */;
/*!50001 DROP TABLE IF EXISTS `qry_posts` */;

/*!50001 CREATE TABLE  `qry_posts`(
 `id` bigint(20) ,
 `title` varchar(255) ,
 `message` varchar(255) ,
 `date_created` date ,
 `user` text ,
 `user_id` bigint(20) ,
 `post_type` int(11) ,
 `is_deleted` tinyint(1) 
)*/;

/*Table structure for table `qry_users` */

DROP TABLE IF EXISTS `qry_users`;

/*!50001 DROP VIEW IF EXISTS `qry_users` */;
/*!50001 DROP TABLE IF EXISTS `qry_users` */;

/*!50001 CREATE TABLE  `qry_users`(
 `first_name` varchar(255) ,
 `middle_name` varchar(255) ,
 `last_name` varchar(255) ,
 `username` varchar(255) ,
 `email` varchar(255) ,
 `contact_no` varchar(255) ,
 `id` bigint(20) 
)*/;

/*Table structure for table `vw_calendar` */

DROP TABLE IF EXISTS `vw_calendar`;

/*!50001 DROP VIEW IF EXISTS `vw_calendar` */;
/*!50001 DROP TABLE IF EXISTS `vw_calendar` */;

/*!50001 CREATE TABLE  `vw_calendar`(
 `id` bigint(20) ,
 `subjects` varchar(255) ,
 `assigned_to` bigint(20) ,
 `uname` varchar(511) ,
 `start_date` datetime ,
 `end_date` datetime ,
 `due_date` date ,
 `stat_id` bigint(20) ,
 `event_stat` varchar(255) ,
 `atype_id` bigint(20) ,
 `activity_type` varchar(255) ,
 `location` varchar(255) ,
 `priority_id` int(11) ,
 `priority` varchar(255) ,
 `description` varchar(255) ,
 `is_deleted` tinyint(1) ,
 `is_related_id` bigint(20) ,
 `is_related` varchar(255) ,
 `date_created` datetime ,
 `allDay` varchar(5) ,
 `org_id` bigint(20) ,
 `org_name` varchar(255) ,
 `opp_id` bigint(20) ,
 `opp_name` varchar(255) 
)*/;

/*Table structure for table `vw_contact_persons` */

DROP TABLE IF EXISTS `vw_contact_persons`;

/*!50001 DROP VIEW IF EXISTS `vw_contact_persons` */;
/*!50001 DROP TABLE IF EXISTS `vw_contact_persons` */;

/*!50001 CREATE TABLE  `vw_contact_persons`(
 `id` bigint(20) ,
 `fname` varchar(255) ,
 `lname` varchar(255) ,
 `office_phone` varchar(50) ,
 `email` varchar(100) ,
 `org_id` bigint(20) ,
 `org_name` varchar(255) ,
 `home_phone` varchar(50) ,
 `mobile_phone` varchar(50) ,
 `department` varchar(255) ,
 `dob` date ,
 `users` text ,
 `description` varchar(255) ,
 `profile_pic` varchar(255) ,
 `is_deleted` tinyint(1) 
)*/;

/*Table structure for table `vw_opp` */

DROP TABLE IF EXISTS `vw_opp`;

/*!50001 DROP VIEW IF EXISTS `vw_opp` */;
/*!50001 DROP TABLE IF EXISTS `vw_opp` */;

/*!50001 CREATE TABLE  `vw_opp`(
 `id` bigint(20) ,
 `opp_name` varchar(255) ,
 `org_id` bigint(20) ,
 `org_name` varchar(255) ,
 `cname` varchar(511) ,
 `opp_type` varchar(255) ,
 `users` text ,
 `utype` bigint(20) ,
 `sales_stage` varchar(255) ,
 `forecast_amount` float ,
 `amount` float ,
 `tprice` double ,
 `opp_description` varchar(255) ,
 `product_set` bigint(20) ,
 `description` varchar(255) ,
 `expected_close_date` date ,
 `date_created` datetime ,
 `date_modified` datetime ,
 `is_deleted` tinyint(1) 
)*/;

/*Table structure for table `vw_org` */

DROP TABLE IF EXISTS `vw_org`;

/*!50001 DROP VIEW IF EXISTS `vw_org` */;
/*!50001 DROP TABLE IF EXISTS `vw_org` */;

/*!50001 CREATE TABLE  `vw_org`(
 `id` bigint(20) ,
 `org_name` varchar(255) ,
 `phone_num` varchar(255) ,
 `email` varchar(255) ,
 `industry` varchar(255) ,
 `address` varchar(255) ,
 `org_type` varchar(255) ,
 `rating` varchar(255) ,
 `annual_revenue` float ,
 `uid` bigint(20) ,
 `users` text ,
 `description` varchar(255) ,
 `date_created` datetime ,
 `date_modified` datetime ,
 `is_deleted` tinyint(1) 
)*/;

/*Table structure for table `vw_prod` */

DROP TABLE IF EXISTS `vw_prod`;

/*!50001 DROP VIEW IF EXISTS `vw_prod` */;
/*!50001 DROP TABLE IF EXISTS `vw_prod` */;

/*!50001 CREATE TABLE  `vw_prod`(
 `id` bigint(20) ,
 `opp_id` bigint(20) ,
 `opp_name` varchar(255) ,
 `prod_code` varchar(255) ,
 `product_name` varchar(255) ,
 `unit_price` float ,
 `commission_rate` float ,
 `qty_unit` bigint(20) ,
 `total_price` float ,
 `uid` bigint(20) ,
 `users` text ,
 `description` varchar(255) ,
 `is_deleted` tinyint(1) 
)*/;

/*View structure for view qry_posts */

/*!50001 DROP TABLE IF EXISTS `qry_posts` */;
/*!50001 DROP VIEW IF EXISTS `qry_posts` */;

/*!50001 CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `qry_posts` AS select `p`.`id` AS `id`,`p`.`title` AS `title`,`p`.`message` AS `message`,`p`.`date_created` AS `date_created`,concat(`u`.`last_name`,', ',`u`.`first_name`,' ',`u`.`middle_name`) AS `user`,`u`.`id` AS `user_id`,`p`.`post_type` AS `post_type`,`p`.`is_deleted` AS `is_deleted` from (`posts` `p` join `users` `u` on((`p`.`user_id` = `u`.`id`))) */;

/*View structure for view qry_users */

/*!50001 DROP TABLE IF EXISTS `qry_users` */;
/*!50001 DROP VIEW IF EXISTS `qry_users` */;

/*!50001 CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `qry_users` AS (select `users`.`first_name` AS `first_name`,`users`.`middle_name` AS `middle_name`,`users`.`last_name` AS `last_name`,`users`.`username` AS `username`,`users`.`email` AS `email`,`users`.`contact_no` AS `contact_no`,`users`.`id` AS `id` from `users` where (`users`.`is_deleted` = 0)) */;

/*View structure for view vw_calendar */

/*!50001 DROP TABLE IF EXISTS `vw_calendar` */;
/*!50001 DROP VIEW IF EXISTS `vw_calendar` */;

/*!50001 CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_calendar` AS (select `ev`.`id` AS `id`,`ev`.`subject` AS `subjects`,`ev`.`assigned_to` AS `assigned_to`,concat(`users`.`first_name`,' ',`users`.`last_name`) AS `uname`,`ev`.`start_date` AS `start_date`,`ev`.`end_date` AS `end_date`,`ev`.`due_date` AS `due_date`,`es`.`id` AS `stat_id`,`es`.`name` AS `event_stat`,`at`.`id` AS `atype_id`,`at`.`name` AS `activity_type`,`l`.`name` AS `location`,`ev`.`priority` AS `priority_id`,`pr`.`name` AS `priority`,`ev`.`description` AS `description`,`ev`.`is_deleted` AS `is_deleted`,`ev`.`is_related` AS `is_related_id`,`opp`.`opp_name` AS `is_related`,`ev`.`date_created` AS `date_created`,`ev`.`allDay` AS `allDay`,`ev`.`org_id` AS `org_id`,`org`.`org_name` AS `org_name`,`ev`.`opp_id` AS `opp_id`,`opp`.`opp_name` AS `opp_name` from ((((((((`events` `ev` left join `priorities` `pr` on((`ev`.`priority` = `pr`.`id`))) left join `users` on((`ev`.`assigned_to` = `users`.`id`))) left join `user_types` `utype` on((`users`.`user_type_id` = `utype`.`id`))) left join `act_types` `at` on((`ev`.`activity_type` = `at`.`id`))) left join `event_status` `es` on((`ev`.`event_stat` = `es`.`id`))) left join `locations` `l` on((`ev`.`location_id` = `l`.`id`))) left join `opportunities` `opp` on((`ev`.`opp_id` = `opp`.`id`))) left join `organizations` `org` on((`ev`.`org_id` = `org`.`id`)))) */;

/*View structure for view vw_contact_persons */

/*!50001 DROP TABLE IF EXISTS `vw_contact_persons` */;
/*!50001 DROP VIEW IF EXISTS `vw_contact_persons` */;

/*!50001 CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_contact_persons` AS (select `cont`.`id` AS `id`,`cont`.`fname` AS `fname`,`cont`.`lname` AS `lname`,`cont`.`office_phone` AS `office_phone`,`cont`.`email` AS `email`,`org`.`id` AS `org_id`,`org`.`org_name` AS `org_name`,`cont`.`home_phone` AS `home_phone`,`cont`.`mobile_phone` AS `mobile_phone`,`dept`.`name` AS `department`,`cont`.`dob` AS `dob`,concat(`users`.`last_name`,' ',`users`.`first_name`,' ',`users`.`middle_name`) AS `users`,`cont`.`description` AS `description`,`cont`.`profile_pic` AS `profile_pic`,`cont`.`is_deleted` AS `is_deleted` from ((((`contacts` `cont` left join `organizations` `org` on((`cont`.`org_id` = `org`.`id`))) left join `departments` `dept` on((`cont`.`department_id` = `dept`.`id`))) left join `users` on((`cont`.`assigned_to` = `users`.`id`))) left join `user_types` `utype` on((`users`.`user_type_id` = `utype`.`id`)))) */;

/*View structure for view vw_opp */

/*!50001 DROP TABLE IF EXISTS `vw_opp` */;
/*!50001 DROP VIEW IF EXISTS `vw_opp` */;

/*!50001 CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_opp` AS (select `opp`.`id` AS `id`,`opp`.`opp_name` AS `opp_name`,`org`.`id` AS `org_id`,`org`.`org_name` AS `org_name`,concat(`con`.`fname`,' ',`con`.`lname`) AS `cname`,`otype`.`name` AS `opp_type`,concat(`users`.`last_name`,' ',`users`.`first_name`,' ',`users`.`middle_name`) AS `users`,`opp`.`assigned_to` AS `utype`,`op_stat`.`name` AS `sales_stage`,`opp`.`forecast_amount` AS `forecast_amount`,`opp`.`amount` AS `amount`,(select sum(`op`.`prod_price`) from `opp_products` `op` where ((`op`.`is_deleted` = 0) and (`op`.`opp_id` = `opp`.`id`))) AS `tprice`,`opp`.`description` AS `opp_description`,`opp`.`product_set` AS `product_set`,`opp`.`description` AS `description`,`opp`.`expected_close_date` AS `expected_close_date`,`opp`.`date_created` AS `date_created`,`opp`.`date_modified` AS `date_modified`,`opp`.`is_deleted` AS `is_deleted` from (((((`opportunities` `opp` left join `organizations` `org` on((`opp`.`org_id` = `org`.`id`))) left join `opp_types` `otype` on((`opp`.`opp_type` = `otype`.`id`))) left join `contacts` `con` on((`opp`.`contact_id` = `con`.`id`))) left join `users` on((`opp`.`assigned_to` = `users`.`id`))) left join `opp_statuses` `op_stat` on((`opp`.`sales_stage` = `op_stat`.`id`))) where (`opp`.`is_deleted` = 0)) */;

/*View structure for view vw_org */

/*!50001 DROP TABLE IF EXISTS `vw_org` */;
/*!50001 DROP VIEW IF EXISTS `vw_org` */;

/*!50001 CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_org` AS (select `org`.`id` AS `id`,`org`.`org_name` AS `org_name`,`org`.`phone_num` AS `phone_num`,`org`.`email` AS `email`,`industry`.`name` AS `industry`,`org`.`address` AS `address`,`otypes`.`name` AS `org_type`,`oratings`.`name` AS `rating`,`org`.`annual_revenue` AS `annual_revenue`,`users`.`id` AS `uid`,concat(`users`.`last_name`,' ',`users`.`first_name`,' ',`users`.`middle_name`) AS `users`,`org`.`description` AS `description`,`org`.`date_created` AS `date_created`,`org`.`date_modified` AS `date_modified`,`org`.`is_deleted` AS `is_deleted` from ((((((`organizations` `org` left join `opportunities` `opp` on((`org`.`id` = `opp`.`id`))) left join `org_types` `otypes` on((`org`.`org_type` = `otypes`.`id`))) left join `org_ratings` `oratings` on((`org`.`rating` = `oratings`.`id`))) left join `users` on((`org`.`assigned_to` = `users`.`id`))) left join `user_types` `utype` on((`users`.`user_type_id` = `utype`.`id`))) left join `org_industry` `industry` on((`org`.`industry` = `industry`.`id`)))) */;

/*View structure for view vw_prod */

/*!50001 DROP TABLE IF EXISTS `vw_prod` */;
/*!50001 DROP VIEW IF EXISTS `vw_prod` */;

/*!50001 CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_prod` AS (select `prod`.`id` AS `id`,`prod`.`opp_id` AS `opp_id`,`opp`.`opp_name` AS `opp_name`,`prod`.`prod_code` AS `prod_code`,`prod`.`product_name` AS `product_name`,`prod`.`unit_price` AS `unit_price`,`prod`.`commission_rate` AS `commission_rate`,`prod`.`qty_unit` AS `qty_unit`,`prod`.`total_price` AS `total_price`,`users`.`id` AS `uid`,concat(`users`.`last_name`,' ',`users`.`first_name`,' ',`users`.`middle_name`) AS `users`,`prod`.`description` AS `description`,`prod`.`is_deleted` AS `is_deleted` from (((`products` `prod` left join `users` on((`prod`.`assigned_to` = `users`.`id`))) left join `user_types` `utype` on((`users`.`user_type_id` = `utype`.`id`))) left join `opportunities` `opp` on((`opp`.`id` = `prod`.`id`)))) */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
