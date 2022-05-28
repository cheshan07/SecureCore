/*
SQLyog Ultimate v9.63 
MySQL - 5.5.5-10.4.17-MariaDB : Database - securecore_db
*********************************************************************
*/


/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`securecore_db` /*!40100 DEFAULT CHARACTER SET utf8 */;

USE `securecore_db`;

/*Table structure for table `encounters` */

DROP TABLE IF EXISTS `encounters`;

CREATE TABLE `encounters` (
  `encounter_id` int(11) NOT NULL AUTO_INCREMENT,
  `patient_id` int(11) DEFAULT NULL,
  `doctor_id` int(11) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `actioned_user_id` int(11) DEFAULT NULL,
  `last_action_status` varchar(255) DEFAULT NULL,
  `action_date` date DEFAULT NULL,
  PRIMARY KEY (`encounter_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

/*Data for the table `encounters` */

insert  into `encounters`(`encounter_id`,`patient_id`,`doctor_id`,`description`,`actioned_user_id`,`last_action_status`,`action_date`) values (1,4,6,'Blood test',1,'added','2022-03-20');

/*Table structure for table `medical_records` */

DROP TABLE IF EXISTS `medical_records`;

CREATE TABLE `medical_records` (
  `record_id` int(11) NOT NULL AUTO_INCREMENT,
  `patient_id` int(11) DEFAULT NULL,
  `report_name` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `report_link` varchar(255) DEFAULT NULL,
  `actioned_user_id` int(11) DEFAULT NULL,
  `last_action_status` varchar(255) DEFAULT NULL,
  `action_date` date DEFAULT NULL,
  PRIMARY KEY (`record_id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;

/*Data for the table `medical_records` */

insert  into `medical_records`(`record_id`,`patient_id`,`report_name`,`description`,`report_link`,`actioned_user_id`,`last_action_status`,`action_date`) values (3,4,'hgjghj','gjgjgj','report1.png',4,'added','2022-04-18'),(16,4,'Fiver','Fever details',NULL,6,'added','2022-04-30');

/*Table structure for table `members` */

DROP TABLE IF EXISTS `members`;

CREATE TABLE `members` (
  `member_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `user_nic` varchar(255) DEFAULT NULL,
  `role_id` int(11) DEFAULT NULL,
  `first_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `gender` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `phone_no` varchar(20) DEFAULT NULL,
  `nic` varchar(20) DEFAULT NULL,
  `otp` varchar(255) DEFAULT NULL,
  `otp_expire_time` datetime DEFAULT NULL,
  `access_flag` tinyint(4) NOT NULL DEFAULT 0,
  `member_status` tinyint(1) DEFAULT 1,
  `actioned_user_id` int(11) DEFAULT NULL,
  `last_action_status` varchar(255) DEFAULT NULL,
  `action_date` datetime DEFAULT NULL,
  PRIMARY KEY (`member_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

/*Data for the table `members` */

insert  into `members`(`member_id`,`user_id`,`user_nic`,`role_id`,`first_name`,`last_name`,`gender`,`address`,`phone_no`,`nic`,`otp`,`otp_expire_time`,`access_flag`,`member_status`,`actioned_user_id`,`last_action_status`,`action_date`) values (1,4,'999111333V',2,'Sam','Perera','Male','Colombo','779089655','123456789V','456079','2022-04-30 13:36:24',0,1,1,'updated','2022-04-30 13:34:24');

/*Table structure for table `permissions` */

DROP TABLE IF EXISTS `permissions`;

CREATE TABLE `permissions` (
  `permission_id` int(11) NOT NULL AUTO_INCREMENT,
  `permission` varchar(255) DEFAULT NULL,
  `permission_description` varchar(255) DEFAULT NULL,
  `showing_status` tinyint(1) DEFAULT NULL,
  `actioned_user_id` int(11) DEFAULT NULL,
  `action_date` datetime DEFAULT NULL,
  `last_action_status` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`permission_id`)
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8;

/*Data for the table `permissions` */

insert  into `permissions`(`permission_id`,`permission`,`permission_description`,`showing_status`,`actioned_user_id`,`action_date`,`last_action_status`) values (1,'add_dashboard','Add Dashboard',0,1,'2022-03-03 16:24:55','added'),(2,'edit_dashboard','Edit Dashboard',0,1,'2022-03-03 16:25:35','added'),(3,'delete_dashboard','Delete Dashboard',0,1,'2022-03-03 16:25:35','added'),(4,'view_dashboard','View Dashboard',1,1,'2022-03-03 16:25:35','added'),(5,'add_user','Add User',1,1,'2022-03-03 16:24:55','added'),(6,'edit_user','Edit User',1,1,'2022-03-03 16:24:55','added'),(7,'delete_user','Delete User',1,1,'2022-03-03 16:24:55','added'),(8,'view_user','View User',1,1,'2022-03-03 16:24:55','added'),(9,'add_doctor','Add Doctor',1,1,'2022-03-03 16:24:55','added'),(10,'edit_doctor','Edit Doctor',1,1,'2022-03-03 16:24:55','added'),(11,'delete_doctor','Delete Doctor',1,1,'2022-03-03 16:24:55','added'),(12,'view_doctor','View Doctor',1,1,'2022-03-03 16:24:55','added'),(13,'add_member','Add Member',1,1,'2022-03-03 16:24:55','added'),(14,'edit_member','Edit Member',1,1,'2022-03-03 16:24:55','added'),(15,'delete_member','Delete Member',1,1,'2022-03-03 16:24:55','added'),(16,'view_member','View Member',1,1,'2022-03-03 16:24:55','added'),(17,'add_patient','Add Patient',1,1,'2022-03-03 16:24:55','added'),(18,'edit_patient','Edit Patient',1,1,'2022-03-03 16:24:55','added'),(19,'delete_patient','Delete Patient',1,1,'2022-03-03 16:24:55','added'),(20,'view_patient','View Patient',1,1,'2022-03-03 16:24:55','added'),(21,'add_report','Add Report',0,1,'2022-03-03 16:24:55','added'),(22,'edit_report','Edit Report',0,1,'2022-03-03 16:24:55','added'),(23,'delete_report','Delete Report',1,1,'2022-03-03 16:24:55','added'),(24,'view_report','View Report',1,1,'2022-03-03 16:24:55','added'),(25,'add_roles','Add Roles',0,1,'2022-03-03 16:24:55','added'),(26,'edit_roles','Edit Roles',0,1,'2022-03-03 16:24:55','added'),(27,'delete_roles','Delete Roles',0,1,'2022-03-03 16:24:55','added'),(28,'view_roles','View Roles',1,1,'2022-03-03 16:24:55','added'),(29,'add_encounter','Add Encounter',1,1,'2022-03-03 16:24:55','added'),(30,'edit_encounter','Edit Encounter',1,1,'2022-03-03 16:24:55','added'),(31,'delete_encounter','Delete Encounter',1,1,'2022-03-03 16:24:55','added'),(32,'view_encounter','View Encounter',1,1,'2022-03-03 16:24:55','added'),(33,'add_medical_record','Add Medical Record',1,1,'2022-03-03 16:24:55','added'),(34,'edit_medical_record','Edit Medical Record',1,1,'2022-03-03 16:24:55','added'),(35,'delete_medical_record','Delete Medical Record',1,1,'2022-03-03 16:24:55','added'),(36,'view_medical_record','View Medical Record',1,1,'2022-03-03 16:24:55','added'),(37,'add_emergency','Add Emergency',1,1,'2022-03-03 16:24:55','added'),(38,'edit_emergency','Edit Emergency',1,1,'2022-03-03 16:24:55','added'),(39,'delete_emergency','Delete Emergency',1,1,'2022-03-03 16:24:55','added'),(40,'view_emergency','View Emergency',1,1,'2022-03-03 16:24:55','added');

/*Table structure for table `roles` */

DROP TABLE IF EXISTS `roles`;

CREATE TABLE `roles` (
  `role_id` int(11) NOT NULL AUTO_INCREMENT,
  `role` varchar(50) NOT NULL,
  `description` varchar(255) NOT NULL,
  `actioned_user_id` int(11) NOT NULL,
  `action_date` datetime NOT NULL,
  `last_action_status` varchar(255) NOT NULL,
  PRIMARY KEY (`role_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

/*Data for the table `roles` */

insert  into `roles`(`role_id`,`role`,`description`,`actioned_user_id`,`action_date`,`last_action_status`) values (1,'Admin','Admin User',1,'2022-03-03 10:31:55','added'),(2,'Patient','Patient User',1,'2022-03-03 10:36:11','added'),(3,'Doctor','Doctor User',1,'2022-03-03 10:36:11','added'),(4,'User','Normal User',1,'2022-03-18 21:47:03','added');

/*Table structure for table `user_permissions` */

DROP TABLE IF EXISTS `user_permissions`;

CREATE TABLE `user_permissions` (
  `role_id` int(11) DEFAULT NULL,
  `permission_id` int(11) DEFAULT NULL,
  KEY `permission_id` (`permission_id`),
  KEY `role_id` (`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `user_permissions` */

insert  into `user_permissions`(`role_id`,`permission_id`) values (1,1),(1,2),(1,3),(1,4),(1,5),(1,6),(1,7),(1,8),(1,9),(1,10),(1,11),(1,12),(1,15),(1,16),(1,17),(1,18),(1,19),(1,20),(1,21),(1,22),(1,23),(1,24),(1,25),(1,26),(1,27),(1,28),(1,31),(1,32),(2,4),(2,13),(2,14),(2,15),(2,16),(2,32),(3,4),(3,17),(3,18),(3,19),(3,20),(3,24),(3,29),(3,30),(3,31),(3,32),(4,4),(4,5),(4,6),(4,8),(4,9),(4,10),(4,12),(4,16),(4,17),(4,18),(4,20),(4,32),(1,33),(1,34),(1,35),(1,36),(2,33),(2,34),(2,35),(2,36),(3,33),(3,34),(3,35),(3,36),(1,37),(1,38),(1,39),(1,40),(3,37),(3,38),(3,39),(3,40);

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(25) DEFAULT NULL,
  `first_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `gender` varchar(25) DEFAULT NULL,
  `user_nic` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `phone_no` varchar(25) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `status` tinyint(1) DEFAULT 0,
  `role_id` int(11) DEFAULT 3,
  `otp` varchar(255) DEFAULT NULL,
  `otp_expire_time` datetime DEFAULT NULL,
  `report_otp` varchar(255) DEFAULT NULL,
  `report_otp_expire_time` datetime DEFAULT NULL,
  `verify_code` varchar(255) DEFAULT NULL,
  `password_reset_code` varchar(255) DEFAULT NULL,
  `profile_img` varchar(255) DEFAULT NULL,
  `actioned_user_id` int(11) DEFAULT NULL,
  `action_date` datetime DEFAULT NULL,
  `last_action_status` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  KEY `role_id` (`role_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

/*Data for the table `users` */

insert  into `users`(`user_id`,`title`,`first_name`,`last_name`,`gender`,`user_nic`,`address`,`phone_no`,`email`,`password`,`status`,`role_id`,`otp`,`otp_expire_time`,`report_otp`,`report_otp_expire_time`,`verify_code`,`password_reset_code`,`profile_img`,`actioned_user_id`,`action_date`,`last_action_status`) values (1,'Mr','Ca','Anu','Male','123456789V','Kandy','779089655','admin@gmail.com','$2y$12$bBqTlfD8cWD2dzimCNnMfOoMKMF0acLvfYyKwhmBQiuuclPWBuGci',1,1,'704192','2022-05-03 09:06:30','301258','2022-04-30 12:07:59',NULL,NULL,'team-1.jpg',1,'2022-05-03 10:24:46','edited'),(4,'Mr','Sunimal','Perera','Male','999111333V','Colombo','721113458','patient@gmail.com','$2y$12$WBKAmw1yYU/YYNVMJLkiNuA4m9UxZotreDI5/zY9NZ8iOoQ4rsTGi',1,2,'869720','2022-04-30 09:31:01','487563','2022-04-30 10:00:57',NULL,NULL,NULL,1,'2022-04-30 09:58:57','updated'),(5,'Miss','Nayomi','Udugama','Male',NULL,'Mathale','721113458','patient2@gmail.com','$2y$12$zR8Wx5au6.hlkOZctgigru0mtLkwr53Cu/PwjVigIr5hc0fySg6tO',1,2,'926785','2022-03-20 15:32:44','386120','2022-04-30 09:53:48',NULL,NULL,NULL,1,'2022-04-30 09:51:48','updated'),(6,'Dr','Kapila','Bandu','Male','777888777V','Colombo','779089655','doctor@gmail.com','$2y$12$Osmag4w2oaRZ92YChg9jzueSXT0GiEwCTL5ON.kb.ZTpPxCC0ExWq',1,3,'682403','2022-04-30 09:52:07',NULL,NULL,NULL,NULL,NULL,1,'2022-04-30 09:50:07','updated');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
