/*
SQLyog Community v8.6 GA
MySQL - 5.5.43-0ubuntu0.14.04.1 : Database - db_eletesmeet_com
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`db_eletesmeet_com` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `db_eletesmeet_com`;

/*Table structure for table `admin_login` */

DROP TABLE IF EXISTS `admin_login`;

CREATE TABLE `admin_login` (
  `admin_id` int(10) NOT NULL AUTO_INCREMENT,
  `email_address` varchar(100) NOT NULL,
  `password` varchar(50) NOT NULL,
  `lastlogin_dtm` datetime DEFAULT NULL,
  `admin_creation_dtm` datetime NOT NULL,
  `client_id` varchar(25) NOT NULL COMMENT 'Client Id of Admin User',
  `partner_id` varchar(25) NOT NULL COMMENT 'Partner Id of Client',
  `status` enum('0','1','2','3') NOT NULL DEFAULT '0' COMMENT 'Status of user 0=Pending, 1=Active, 2=Deative, 3=Deleted',
  `flag` enum('S','A','CA') NOT NULL DEFAULT 'A' COMMENT 'Flag for S=Superadmin, A=Admin, CA=Client Admin',
  PRIMARY KEY (`admin_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

/*Table structure for table `billing_info` */

DROP TABLE IF EXISTS `billing_info`;

CREATE TABLE `billing_info` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'Unique row id of the records',
  `user_id` varchar(25) NOT NULL COMMENT 'Unique identification number of user',
  `order_id` varchar(25) NOT NULL COMMENT 'Unique Id of Order',
  `email_address` varchar(100) NOT NULL COMMENT 'Email Address of user',
  `first_name` varchar(50) DEFAULT NULL COMMENT 'First name of user',
  `last_name` varchar(50) DEFAULT NULL COMMENT 'Last name of user',
  `address` text COMMENT 'Address of user',
  `city` varchar(70) DEFAULT NULL COMMENT 'City of user',
  `state` varchar(70) DEFAULT NULL COMMENT 'State of user',
  `country_name` varchar(150) DEFAULT NULL COMMENT 'Country name of user from country_details table',
  `zipcode` varchar(25) DEFAULT NULL COMMENT 'Zip code of user',
  `idd_code` varchar(10) DEFAULT NULL COMMENT 'IDD code of user',
  `mobile_number` varchar(20) DEFAULT NULL COMMENT 'Mobil number of user',
  `billing_dtm` datetime DEFAULT NULL COMMENT 'Date of registration when user is register',
  `status` enum('0','1') NOT NULL DEFAULT '0' COMMENT 'Status of user 0=Pending, 1=Active, 2=Deative, 3=Deleted',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `client_contact_details` */

DROP TABLE IF EXISTS `client_contact_details`;

CREATE TABLE `client_contact_details` (
  `client_contact_id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'Unique identification number of client contcat Auto Increment',
  `contact_nick_name` varchar(50) NOT NULL COMMENT 'Nick name of contact',
  `contact_first_name` varchar(50) DEFAULT NULL COMMENT 'First name of contact',
  `contact_last_name` varchar(50) DEFAULT NULL COMMENT 'Last name of contact',
  `contact_email_address` varchar(100) NOT NULL COMMENT 'Email Address of contact',
  `contact_idd_code` varchar(10) DEFAULT NULL COMMENT 'IDD code for contact mobile n phone number',
  `contact_mobile_number` varchar(20) DEFAULT NULL COMMENT 'Mobil number of contact',
  `contact_group_name` varchar(100) NOT NULL COMMENT 'Groupname or Department Name of contact',
  `client_id` varchar(25) DEFAULT NULL COMMENT 'client_id',
  `client_contact_status` enum('1','2') DEFAULT '1' COMMENT '1=Active, 2=Deative',
  PRIMARY KEY (`client_contact_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;

/*Table structure for table `client_details` */

DROP TABLE IF EXISTS `client_details`;

CREATE TABLE `client_details` (
  `client_id` varchar(25) NOT NULL COMMENT 'Unique identification number of client',
  `partner_id` varchar(25) NOT NULL COMMENT 'Partner Id of Client',
  `client_name` varchar(200) NOT NULL COMMENT 'Client name',
  `client_logo_flag` enum('0','1') DEFAULT '0' COMMENT 'Client Logo Display Allowed or Not 0=NotAllowed, 1=Allowed',
  `client_logo_url` varchar(200) DEFAULT NULL COMMENT 'Client logo URL',
  `client_email_address` varchar(100) NOT NULL COMMENT 'Client Email Address',
  `client_password` varchar(50) NOT NULL COMMENT 'Client Email Address',
  `client_lastlogin_dtm` datetime DEFAULT NULL COMMENT 'Last Login Date and Time',
  `client_login_id` varchar(100) DEFAULT NULL COMMENT 'Adding Random number at the time of login',
  `client_login_ip_address` varchar(50) DEFAULT NULL COMMENT 'IP address from client is login',
  `client_creation_dtm` datetime NOT NULL COMMENT 'Date of creation when client is created',
  `client_secret_key` varchar(50) DEFAULT NULL COMMENT 'Client Secret Key for API Authentication',
  `auth_mode` enum('0','1','2') NOT NULL DEFAULT '0' COMMENT 'Status of Authentication mode  0=local, 1=API, 2=LDAP',
  `auth_api_url` varchar(200) DEFAULT NULL COMMENT 'URL of aith api in case of auth mode is 1=API',
  `import_contact_url` varchar(200) DEFAULT NULL COMMENT 'URL for importing contact',
  `rt_server_name` varchar(200) DEFAULT NULL COMMENT 'Server Name of LetsMeet Instance',
  `rt_server_salt` varchar(200) DEFAULT NULL COMMENT 'Server Salt of LetsMeet Instance',
  `rt_server_api_url` varchar(200) DEFAULT NULL COMMENT 'Server Api URLof LetsMeet Instance',
  `logout_url` varchar(200) DEFAULT NULL COMMENT 'Logout URL after logout where user is redirected',
  `status` enum('0','1','2','3') NOT NULL DEFAULT '0' COMMENT 'Status of client 0=Pending, 1=Active, 2=Deative, 3=Deleted',
  PRIMARY KEY (`client_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `client_license_details` */

DROP TABLE IF EXISTS `client_license_details`;

CREATE TABLE `client_license_details` (
  `license_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Unique row id of the records',
  `client_id` varchar(25) NOT NULL COMMENT 'Unique identification number of client',
  `no_of_license` int(10) DEFAULT '0' COMMENT 'No of license by Client',
  `operation_type` enum('0','1','2') DEFAULT '2' COMMENT '0 = License Added, 1= License Assigned, 2=License Disabled',
  `license_date` datetime NOT NULL COMMENT 'License date when will be license added assigend or disabled',
  PRIMARY KEY (`license_id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;

/*Table structure for table `client_subscription_master` */

DROP TABLE IF EXISTS `client_subscription_master`;

CREATE TABLE `client_subscription_master` (
  `client_subscription_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Unique identification number of subscription record',
  `client_id` varchar(25) NOT NULL COMMENT 'Unique identification number of client',
  `subscription_date` datetime NOT NULL COMMENT 'subscription date when will be subscription purchase or activate',
  `subscription_start_date_gmt` date NOT NULL COMMENT 'GMT subscription start date',
  `subscription_end_date_gmt` date NOT NULL COMMENT 'GMT subscription end date',
  `subscription_start_date_local` date DEFAULT NULL COMMENT 'Local subscription start date',
  `subscription_end_date_local` date DEFAULT NULL COMMENT 'Local subscription start date',
  `subscription_status` enum('0','1','2','3') NOT NULL COMMENT 'Represents 0=Request, 1=Trial, 2=Subscribe, 3=Expired',
  `order_id` varchar(50) NOT NULL COMMENT 'Order Id',
  `plan_id` int(10) NOT NULL COMMENT 'Unique identification number of plan',
  `plan_name` varchar(30) NOT NULL COMMENT 'Plan name',
  `plan_desc` text NOT NULL COMMENT 'Short description of plan',
  `plan_for` enum('ENT','OTH','POR','RET') DEFAULT NULL COMMENT 'Plan for ENT=Enterprise, OTH=Other, POR=Portal, RET=Retail',
  `plan_type` enum('S','T','U') DEFAULT NULL COMMENT 'Plan type S=Session based, T=Talktime based, U=Unlimited',
  `number_of_sessions` int(10) NOT NULL DEFAULT '0' COMMENT 'Number of session allowed, 0=Unlimited sessions',
  `number_of_mins_per_sessions` int(10) NOT NULL DEFAULT '0' COMMENT 'Number of mins per session 0=Forever',
  `plan_period` int(10) DEFAULT '0' COMMENT 'Plan period in days 9999=Forever',
  `number_of_invitee` int(10) DEFAULT '0' COMMENT 'Number of invitees allowed per meeting 0=No limit',
  `meeting_recording` enum('true','false') DEFAULT 'true' COMMENT 'Meeting recording true or false',
  `disk_space` bigint(20) DEFAULT '0' COMMENT 'Disk space for meeting',
  `is_free` enum('0','1') DEFAULT '0' COMMENT 'Plan cost 0=Paid, 1=Free',
  `plan_cost_inr` decimal(10,2) DEFAULT '0.00' COMMENT 'Plan cost in INR',
  `plan_cost_oth` decimal(10,2) DEFAULT '0.00' COMMENT 'Plan cost in Dollar',
  `concurrent_sessions` int(10) DEFAULT '1' COMMENT 'Concurrent Sessions allowed per meeting',
  `talk_time_mins` int(10) DEFAULT '0' COMMENT 'Talktime in mins number of mins allowed as per plan',
  `plan_keyword` varchar(20) DEFAULT NULL COMMENT 'Keyword for plan',
  `autorenew_flag` enum('0','1') NOT NULL DEFAULT '0' COMMENT 'Autorenew flag 0=No, 1=Yes',
  `consumed_number_of_sessions` int(10) DEFAULT '0' COMMENT 'Number of session consumed by User',
  `consumed_talk_time_mins` int(10) DEFAULT '0' COMMENT 'Consumed talk time in mins by User',
  PRIMARY KEY (`client_subscription_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

/*Table structure for table `country_details` */

DROP TABLE IF EXISTS `country_details`;

CREATE TABLE `country_details` (
  `country_id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'country id autoincrement field',
  `country_name` varchar(150) NOT NULL COMMENT 'Country name',
  `country_code` varchar(15) NOT NULL COMMENT 'Country code in 2 letters',
  `country_idd_code` varchar(10) NOT NULL COMMENT 'IDD code of country',
  `country_status` enum('0','1') NOT NULL DEFAULT '1' COMMENT '1 for Active, 0 for Deactive',
  PRIMARY KEY (`country_id`),
  KEY `Index_country_details_countrty_code` (`country_code`)
) ENGINE=InnoDB AUTO_INCREMENT=234 DEFAULT CHARSET=latin1;

/*Table structure for table `country_timezones` */

DROP TABLE IF EXISTS `country_timezones`;

CREATE TABLE `country_timezones` (
  `ct_id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'country id autoincrement field',
  `country_code` varchar(15) NOT NULL COMMENT 'Country code in 2 letters',
  `timezones` varchar(100) NOT NULL COMMENT 'Timezones of country',
  `gmt` varchar(20) DEFAULT NULL COMMENT 'Country GMT details',
  `ct_status` enum('0','1') NOT NULL DEFAULT '1' COMMENT '1 for Active, 0 for Deactive',
  PRIMARY KEY (`ct_id`),
  KEY `Index_country_timezones_countrty_code` (`country_code`),
  CONSTRAINT `FK_country_code` FOREIGN KEY (`country_code`) REFERENCES `country_details` (`country_code`)
) ENGINE=InnoDB AUTO_INCREMENT=398 DEFAULT CHARSET=latin1;

/*Table structure for table `instance_details` */

DROP TABLE IF EXISTS `instance_details`;

CREATE TABLE `instance_details` (
  `instance_id` int(10) NOT NULL AUTO_INCREMENT,
  `instance_name` varchar(100) NOT NULL,
  `instance_url` varchar(100) NOT NULL,
  `instance_salt` varchar(100) NOT NULL,
  `instance_logout_url` varchar(100) NOT NULL,
  `instance_api_url` varchar(100) NOT NULL,
  `instance_creation_dtm` datetime NOT NULL,
  `instance_stop_dtm` datetime DEFAULT NULL,
  `admin_id` int(10) NOT NULL,
  `status` enum('0','1','2','3') NOT NULL DEFAULT '0' COMMENT 'Status of user 0=Pending, 1=Active, 2=Deative, 3=Deleted',
  PRIMARY KEY (`instance_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

/*Table structure for table `invitation_details` */

DROP TABLE IF EXISTS `invitation_details`;

CREATE TABLE `invitation_details` (
  `invitation_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Unique identification number of invitation',
  `schedule_id` varchar(25) NOT NULL COMMENT 'identification number of schedule',
  `invitee_email_address` varchar(100) NOT NULL COMMENT 'Email Address of invitee',
  `invitee_nick_name` varchar(50) NOT NULL COMMENT 'Nick name of invitee',
  `invitee_idd_code` varchar(10) DEFAULT NULL COMMENT 'Country IDD code of invitee',
  `invitee_mobile_number` varchar(20) DEFAULT NULL COMMENT 'Mobile number of invitee',
  `invitation_creator` enum('C','I','M') NOT NULL DEFAULT 'I' COMMENT 'Invitaion Creator flag person is I=Invitee or C=Creator come Moderator or M=Moderator come Invitee',
  `invitation_creation_dtm` datetime NOT NULL COMMENT 'Datetime when invitaion send',
  `invitation_status` enum('0','1','2','3') NOT NULL DEFAULT '0' COMMENT 'Status of invitation 0=invited, 1=Accepted, 2=Declined, 3=Maybe',
  `invitation_status_dtm` datetime DEFAULT NULL COMMENT 'Invitation status datetime',
  `meeting_status` enum('0','1') NOT NULL DEFAULT '0' COMMENT 'Status of meeting 0=invited, 1=Joined',
  `meeting_status_join_dtm` datetime DEFAULT NULL COMMENT 'Datetime of meeting joined',
  `meeting_status_left_dtm` datetime DEFAULT NULL COMMENT 'Datetime of meeting left',
  `meeting_joined_ip_address` varchar(50) DEFAULT NULL COMMENT 'IP Address from where user is joing meeting',
  `meeting_joined_headers` tinytext COMMENT 'Headers details of Joinee',
  PRIMARY KEY (`invitation_id`)
) ENGINE=InnoDB AUTO_INCREMENT=44 DEFAULT CHARSET=latin1;

/*Table structure for table `ip2country` */

DROP TABLE IF EXISTS `ip2country`;

CREATE TABLE `ip2country` (
  `begin_ip` varchar(15) NOT NULL DEFAULT '',
  `end_ip` varchar(15) NOT NULL DEFAULT '',
  `begin_number` double NOT NULL DEFAULT '0',
  `end_number` double NOT NULL DEFAULT '0',
  `countryCode` char(2) NOT NULL DEFAULT '',
  `countryName` varchar(64) NOT NULL DEFAULT '',
  PRIMARY KEY (`begin_number`,`end_number`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Table structure for table `order_details` */

DROP TABLE IF EXISTS `order_details`;

CREATE TABLE `order_details` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'Unique row id of the records',
  `order_id` varchar(25) NOT NULL COMMENT 'Unique Id of Order',
  `plan_id` int(10) NOT NULL COMMENT 'Unique identification number of plan',
  `plan_name` varchar(30) NOT NULL COMMENT 'Plan name',
  `currency_type` varchar(10) DEFAULT NULL COMMENT 'currency type dollar or INR',
  `price` decimal(10,2) DEFAULT '0.00' COMMENT 'Price of unit',
  `quantity` int(10) DEFAULT '1' COMMENT 'Quantity',
  `amount` decimal(10,2) DEFAULT '0.00' COMMENT 'Price of order',
  `service_tax_percent` decimal(10,2) DEFAULT '0.00' COMMENT 'Service tax percent',
  `service_tax_amount` decimal(10,2) DEFAULT '0.00' COMMENT 'Service tax amount',
  `total_amount` decimal(10,2) DEFAULT '0.00' COMMENT 'Grand Total amount',
  `conversion_rate` decimal(10,2) DEFAULT '0.00' COMMENT 'Rupee conversion rate',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

/*Table structure for table `order_master` */

DROP TABLE IF EXISTS `order_master`;

CREATE TABLE `order_master` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'Unique row id of the records',
  `subscriber_id` varchar(25) NOT NULL COMMENT 'User Id or Client _Id who has bought',
  `email_address` varchar(100) NOT NULL COMMENT 'Email address of User',
  `order_id` varchar(25) NOT NULL COMMENT 'Unique Id of Order',
  `payment_id` varchar(25) DEFAULT NULL COMMENT 'Unique refrence number generated by EBS for the payment.',
  `transaction_id` varchar(25) DEFAULT NULL COMMENT 'Unique refrence number generated by EBS for the current status of the payment',
  `payment_gateway_name` varchar(50) NOT NULL COMMENT 'Payment gatway name eg: EBS, PayPal',
  `payment_from` varchar(10) NOT NULL COMMENT 'Payment from eg: wap, web or cheque',
  `order_status` enum('completed','pending','cancelled','failed','onhold') NOT NULL DEFAULT 'pending' COMMENT 'Order status',
  `order_date` datetime NOT NULL COMMENT 'Datetime of order when it is placed',
  `order_date_gmt` datetime NOT NULL COMMENT 'GMT Datetime of order when it is placed ',
  `ip_address` varchar(50) NOT NULL COMMENT 'IP address from where order is placed',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

/*Table structure for table `partner_details` */

DROP TABLE IF EXISTS `partner_details`;

CREATE TABLE `partner_details` (
  `partner_id` varchar(25) NOT NULL COMMENT 'Unique identification number of partner',
  `email_address` varchar(100) NOT NULL COMMENT 'Email Address of partner',
  `password` varchar(50) DEFAULT NULL COMMENT 'Password of partner',
  `partner_name` varchar(50) DEFAULT NULL COMMENT 'Partner name',
  `partner_creation_dtm` datetime DEFAULT NULL COMMENT 'Date of creation when partner is created',
  `status` enum('0','1','2','3') NOT NULL DEFAULT '0' COMMENT 'Status of user 0=Pending, 1=Active, 2=Deative, 3=Deleted',
  PRIMARY KEY (`partner_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `password_request_details` */

DROP TABLE IF EXISTS `password_request_details`;

CREATE TABLE `password_request_details` (
  `request_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Unique identification number of password request',
  `requested_by` varchar(25) NOT NULL COMMENT 'Unique identification number of user',
  `email_address` varchar(100) NOT NULL COMMENT 'Email Address of user',
  `request_datetime` datetime NOT NULL COMMENT 'Actual datetime of request of change password',
  PRIMARY KEY (`request_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `personal_contact_details` */

DROP TABLE IF EXISTS `personal_contact_details`;

CREATE TABLE `personal_contact_details` (
  `personal_contact_id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'Unique identification number of personal contcat Auto Increment',
  `contact_nick_name` varchar(50) NOT NULL COMMENT 'Nick name of contact',
  `contact_first_name` varchar(50) DEFAULT NULL COMMENT 'First name of contact',
  `contact_last_name` varchar(50) DEFAULT NULL COMMENT 'Last name of contact',
  `contact_email_address` varchar(100) NOT NULL COMMENT 'Email Address of contact',
  `contact_idd_code` varchar(10) DEFAULT NULL COMMENT 'IDD code for contact mobile n phone number ',
  `contact_mobile_number` varchar(20) DEFAULT NULL COMMENT 'Mobil number of contact',
  `contact_group_name` varchar(100) NOT NULL COMMENT 'Groupname name of contact',
  `user_id` varchar(25) DEFAULT NULL COMMENT 'user_id',
  `personal_contact_status` enum('1','2') DEFAULT '1' COMMENT '1=Active, 2=Deative',
  PRIMARY KEY (`personal_contact_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `plan_details` */

DROP TABLE IF EXISTS `plan_details`;

CREATE TABLE `plan_details` (
  `plan_id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'Unique identification number of plan',
  `plan_name` varchar(30) NOT NULL COMMENT 'Plan name',
  `plan_desc` text NOT NULL COMMENT 'Short description of plan',
  `plan_for` enum('ENT','OTH','POR','RET') DEFAULT 'OTH' COMMENT 'Plan for ENT=Enterprise, OTH=Other, POR=Portal, RET=Retail',
  `plan_type` enum('S','T','U') DEFAULT NULL COMMENT 'Plan type S=Session based, T=Talktime based, U=Unlimited',
  `number_of_sessions` int(10) NOT NULL DEFAULT '0' COMMENT 'Number of session allowed',
  `number_of_mins_per_sessions` int(10) NOT NULL DEFAULT '0' COMMENT 'Number of mins per session',
  `plan_period` int(10) DEFAULT '0' COMMENT 'Plan period in days 9999=Forever',
  `number_of_invitee` int(10) DEFAULT '0' COMMENT 'Number of invitees allowed per meeting 0=No limit',
  `meeting_recording` enum('true','false') DEFAULT 'true' COMMENT 'Meeting recording true or false',
  `disk_space` bigint(20) DEFAULT '0' COMMENT 'Disk space for meeting',
  `is_free` enum('0','1') DEFAULT '0' COMMENT 'Plan cost 0=Paid, 1=Free',
  `plan_cost_inr` decimal(10,2) DEFAULT '0.00' COMMENT 'Plan cost in INR',
  `plan_cost_oth` decimal(10,2) DEFAULT '0.00' COMMENT 'Plan cost in Dollar',
  `concurrent_sessions` int(10) DEFAULT '1' COMMENT 'Concurrent Sessions allowed per meeting',
  `talk_time_mins` int(10) DEFAULT '0' COMMENT 'Talktime in mins number of mins allowed as per plan',
  `plan_status` enum('1','2','3') NOT NULL DEFAULT '1' COMMENT '1=Active, 2=Deative, 3=Deleted',
  `plan_creation_dtm` datetime NOT NULL COMMENT 'Plan creation datetime',
  `plan_keyword` varchar(20) DEFAULT NULL COMMENT 'Keyword for plan',
  `autorenew_flag` enum('0','1') NOT NULL DEFAULT '0' COMMENT 'Autorenew flag 0=No, 1=Yes',
  `display_order` int(10) DEFAULT NULL COMMENT 'Display Order for Plan',
  `is_multiple` enum('yes','no') NOT NULL DEFAULT 'no' COMMENT 'Plan can be subscribed multiple times or not ',
  PRIMARY KEY (`plan_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;

/*Table structure for table `schedule_details` */

DROP TABLE IF EXISTS `schedule_details`;

CREATE TABLE `schedule_details` (
  `schedule_id` varchar(25) NOT NULL COMMENT 'Unique identification number of schedule',
  `user_id` varchar(25) NOT NULL COMMENT 'user id of person who define the schedule',
  `schedule_status` enum('0','1','2','3','4','5') NOT NULL DEFAULT '0' COMMENT 'Status of user 0=Define, 1=Created, 2=Done, 3=Canceled, 4=Overdue, 5= Error in Creation',
  `schedule_creation_time` datetime NOT NULL COMMENT 'Actual datetime of record creation for schedule',
  `schedule_status_update_time` datetime DEFAULT NULL COMMENT 'Date and Time when schedule status is updated.',
  `meeting_timestamp_gmt` datetime NOT NULL COMMENT 'GMT Datetime of meeting',
  `meeting_timestamp_local` datetime NOT NULL COMMENT 'Local Datetime of meeting',
  `meeting_title` varchar(200) DEFAULT NULL COMMENT 'Title of meeting',
  `meeting_agenda` varchar(150) DEFAULT NULL COMMENT 'Agenda of meeting',
  `meeting_timezone` varchar(100) DEFAULT NULL COMMENT 'Timezone of meeting eg. Asia/Kolkata',
  `meeting_gmt` varchar(50) DEFAULT NULL COMMENT 'GMT hrs of meeting as per timezone',
  `bbb_create_time` varchar(50) DEFAULT NULL COMMENT 'BBB createTime from XML of Create API ',
  `meeting_start_time` datetime DEFAULT NULL COMMENT 'Actual start time of meeting',
  `meeting_end_time` datetime DEFAULT NULL COMMENT 'Actual end time of meeting when last invitee leaves',
  `attendee_password` varchar(50) DEFAULT NULL COMMENT 'Password of attendee',
  `moderator_password` varchar(50) DEFAULT NULL COMMENT 'Password of moderator',
  `welcome_message` varchar(100) DEFAULT NULL COMMENT 'Welcome message',
  `voice_bridge` varchar(32) DEFAULT NULL COMMENT 'Voice bridge',
  `web_voice` varchar(32) DEFAULT NULL COMMENT 'Web voice',
  `max_participants` int(20) NOT NULL COMMENT 'Count of invitee in meeting',
  `record_flag` varchar(10) DEFAULT NULL COMMENT 'Record flag',
  `meeting_duration` int(20) DEFAULT NULL COMMENT 'Actual minutes of meeting is prcoeed',
  `meta_tags` varchar(250) DEFAULT NULL COMMENT 'Meta tags',
  `email_reminder_flag` enum('Y','N') DEFAULT 'Y' COMMENT 'Email reminder flag Y=Yes and N=No',
  `email_reminder_status` enum('0','1','2') DEFAULT '0' COMMENT 'Emain reminder status of meeting 0=Pending, 1=24hrsSent, 2=1hrsSent',
  `sms_reminder_flag` enum('Y','N') DEFAULT 'N' COMMENT 'SMS reminder flag Y=Yes and N=No',
  `sms_reminder_status` enum('0','1') DEFAULT '0' COMMENT 'SMS reminder status of meeting 0=not send, 1=1hrsSent',
  `bbb_message` varchar(255) DEFAULT NULL COMMENT 'Message from bbb in XML response',
  `meeting_instance` varchar(255) NOT NULL COMMENT 'Instance of roundtable where meeting will host',
  `subscription_id` int(10) DEFAULT '0' COMMENT 'Subscription Id',
  PRIMARY KEY (`schedule_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `subscription_master` */

DROP TABLE IF EXISTS `subscription_master`;

CREATE TABLE `subscription_master` (
  `subscription_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Unique identification number of subscription record',
  `user_id` varchar(25) NOT NULL COMMENT 'Unique identification number of user',
  `subscription_date` datetime NOT NULL COMMENT 'subscription date when will be subscription purchase or activate',
  `subscription_start_date_gmt` date NOT NULL COMMENT 'GMT subscription start date',
  `subscription_end_date_gmt` date NOT NULL COMMENT 'GMT subscription end date',
  `subscription_start_date_local` date DEFAULT NULL COMMENT 'Local subscription start date',
  `subscription_end_date_local` date DEFAULT NULL COMMENT 'Local subscription start date',
  `subscription_status` enum('0','1','2','3') NOT NULL COMMENT 'Represents 0=Request, 1=Trial, 2=Subscribe, 3=Expired',
  `order_id` varchar(50) NOT NULL COMMENT 'Order Id',
  `plan_id` int(10) NOT NULL COMMENT 'Unique identification number of plan',
  `plan_name` varchar(30) NOT NULL COMMENT 'Plan name',
  `plan_desc` text NOT NULL COMMENT 'Short description of plan',
  `plan_for` enum('ENT','OTH','POR','RET') DEFAULT NULL COMMENT 'Plan for ENT=Enterprise, OTH=Other, POR=Portal, RET=Retail',
  `plan_type` enum('S','T','U') DEFAULT NULL COMMENT 'Plan type S=Session based, T=Talktime based, U=Unlimited',
  `number_of_sessions` int(10) NOT NULL DEFAULT '0' COMMENT 'Number of session allowed, 0=Unlimited sessions',
  `number_of_mins_per_sessions` int(10) NOT NULL DEFAULT '0' COMMENT 'Number of mins per session 0=Forever',
  `plan_period` int(10) DEFAULT '0' COMMENT 'Plan period in days 9999=Forever',
  `number_of_invitee` int(10) DEFAULT '0' COMMENT 'Number of invitees allowed per meeting 0=No limit',
  `meeting_recording` enum('true','false') DEFAULT 'true' COMMENT 'Meeting recording true or false',
  `disk_space` bigint(20) DEFAULT '0' COMMENT 'Disk space for meeting',
  `is_free` enum('0','1') DEFAULT '0' COMMENT 'Plan cost 0=Paid, 1=Free',
  `plan_cost_inr` decimal(10,2) DEFAULT '0.00' COMMENT 'Plan cost in INR',
  `plan_cost_oth` decimal(10,2) DEFAULT '0.00' COMMENT 'Plan cost in Dollar',
  `concurrent_sessions` int(10) DEFAULT '1' COMMENT 'Concurrent Sessions allowed per meeting',
  `talk_time_mins` int(10) DEFAULT '0' COMMENT 'Talktime in mins number of mins allowed as per plan',
  `autorenew_flag` enum('0','1') NOT NULL DEFAULT '0' COMMENT 'Autorenew flag 0=No, 1=Yes',
  `consumed_number_of_sessions` int(10) DEFAULT '0' COMMENT 'Number of session consumed by User',
  `consumed_talk_time_mins` int(10) DEFAULT '0' COMMENT 'Consumed talk time in mins by User',
  PRIMARY KEY (`subscription_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

/*Table structure for table `transaction_log` */

DROP TABLE IF EXISTS `transaction_log`;

CREATE TABLE `transaction_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `response_code` int(10) DEFAULT NULL,
  `response_message` varchar(50) DEFAULT NULL,
  `date_created` datetime DEFAULT NULL,
  `payment_id` varchar(50) DEFAULT NULL,
  `merchant_ref_no` varchar(50) DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `mode` varchar(50) DEFAULT NULL,
  `billing_email` varchar(50) DEFAULT NULL,
  `description` text,
  `is_flagged` varchar(50) DEFAULT NULL,
  `transaction_id` varchar(50) DEFAULT NULL,
  `response_log` text COMMENT 'response log',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `user_details` */

DROP TABLE IF EXISTS `user_details`;

CREATE TABLE `user_details` (
  `user_id` varchar(25) NOT NULL COMMENT 'Unique identification number of user',
  `client_id` varchar(25) NOT NULL COMMENT 'Client Id of user',
  `partner_id` varchar(25) NOT NULL COMMENT 'Partner Id of Client',
  `email_address` varchar(100) NOT NULL COMMENT 'Email Address of user',
  `password` varchar(50) DEFAULT NULL COMMENT 'Password of user',
  `nick_name` varchar(50) DEFAULT NULL COMMENT 'Nick name of user',
  `first_name` varchar(50) DEFAULT NULL COMMENT 'First name of user',
  `last_name` varchar(50) DEFAULT NULL COMMENT 'Last name of user',
  `country_name` varchar(150) DEFAULT NULL COMMENT 'Country name of user from country_details table',
  `timezones` varchar(100) NOT NULL COMMENT 'Timezones of user',
  `gmt` varchar(20) NOT NULL COMMENT 'GMT hrs on the basis of timezones',
  `phone_number` varchar(20) DEFAULT NULL COMMENT 'Phone number of user',
  `idd_code` varchar(10) DEFAULT NULL COMMENT 'IDD code of user',
  `mobile_number` varchar(20) DEFAULT NULL COMMENT 'Mobil number of user',
  `registration_dtm` datetime DEFAULT NULL COMMENT 'Date of registration when user is register',
  `user_lastlogin_dtm` datetime DEFAULT NULL COMMENT 'Last user login date and time',
  `user_login_ip_address` varchar(50) DEFAULT NULL COMMENT 'IP address from which user is login',
  `is_admin` enum('0','1') DEFAULT '0' COMMENT 'User has Admin rights yes or no,  0=No, 1=Yes',
  `status` enum('0','1','2','3','4') NOT NULL DEFAULT '0' COMMENT 'Status of user 0=Pending, 1=Active, 2=Deative, 3=Deleted, 4=License limit over',
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/* Procedure structure for procedure `AddPwdRequestDtm` */

/*!50003 DROP PROCEDURE IF EXISTS  `AddPwdRequestDtm` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `AddPwdRequestDtm`(
	 p_user_id           INT,
         p_email_address     VARCHAR(100),
         p_request_datetime  DATETIME,
    OUT  p_result            INT,
    OUT  p_request_id        INT(10),
    OUT  p_email             VARCHAR(100),
    OUT  p_timestamp         DATETIME
)
BEGIN
    INSERT INTO password_request_details (user_id, email_address, request_datetime) 
    VALUES (p_user_id,p_email_address,p_request_datetime);
    
    SET @v_count = ROW_COUNT();
     
     IF @v_count > 0 THEN
     SELECT 1 INTO p_result; 
     SELECT request_id, email_address, request_datetime INTO p_request_id, p_email, p_timestamp FROM password_request_details WHERE email_address = p_email_address AND user_id = p_user_id AND request_datetime = p_request_datetime;
     
     ELSE
     
     SELECT 0 INTO p_result;   
     END IF;
                       
END */$$
DELIMITER ;

/* Procedure structure for procedure `CancelSchedule` */

/*!50003 DROP PROCEDURE IF EXISTS  `CancelSchedule` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `CancelSchedule`(
            p_schedule_id       VARCHAR(25),
            p_schedule_status   VARCHAR(3),
            p_update_datetime   DATETIME,
    OUT     p_status            INT(1)
)
BEGIN
DECLARE CONTINUE HANDLER FOR SQLEXCEPTION
SELECT 0 INTO  p_status;
    UPDATE schedule_details SET schedule_status = p_schedule_status, schedule_status_update_time = p_update_datetime WHERE schedule_id = p_schedule_id AND schedule_status = '0';
    SET  @v_upd_row_count  := ROW_COUNT();
    IF ( @v_upd_row_count  > 0) THEN
        SET p_status = 1;
    ELSEIF (@v_upd_row_count = 0) THEN
        SET p_status = 2;
    ELSE
        SET p_status = 0;
    END IF ;
END */$$
DELIMITER ;

/* Procedure structure for procedure `DeleteContactDetails` */

/*!50003 DROP PROCEDURE IF EXISTS  `DeleteContactDetails` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `DeleteContactDetails`(
        p_contact_id    INT(10),
        p_owner         VARCHAR(25),
        p_type     	VARCHAR(3),
    OUT p_status        INT(1),
    OUT p_message       VARCHAR(200)  
)
BEGIN
DECLARE CONTINUE HANDLER FOR SQLEXCEPTION
SELECT 0 INTO  p_status;
SELECT 'Error in Deleting' INTO p_message; 
BEGIN
    IF (p_type  = 'P') THEN
        DELETE FROM personal_contact_details WHERE personal_contact_details.personal_contact_id = p_contact_id AND user_id = p_owner;
        SET  @v_del_row_count  := ROW_COUNT();
        IF ( @v_del_row_count  > 0) THEN
   
            SET p_status = 1;
            SET p_message = 'Deleted Successfully';
           
        ELSEIF (@v_del_row_count = 0) THEN
            SET p_status = 2;
            SET p_message = 'Nothing to Delete';
        ELSE
            SET p_status = 0;
            SET p_message = 'Error in Deleting';
        END IF ;
    ELSEIF (p_type = 'C') THEN
        DELETE FROM client_contact_details WHERE client_contact_details.client_contact_id = p_contact_id AND client_id = p_owner;
        SET  @v_del_row_count  := ROW_COUNT();
        IF ( @v_del_row_count  > 0) THEN
   
            SET p_status = 1;
            SET p_message = 'Deleted Successfully';
           
        ELSEIF (@v_del_row_count = 0) THEN
            SET p_status = 2;
            SET p_message = 'Nothing to Delete';
        ELSE
            SET p_status = 0;
            SET p_message = 'Error in Deleting';
        END IF ;
    ELSE
        SET p_status = 0;
        SET p_message = 'Error in Deleting';
    END IF;
    
END ;
END */$$
DELIMITER ;

/* Procedure structure for procedure `GetCombinedContactList` */

/*!50003 DROP PROCEDURE IF EXISTS  `GetCombinedContactList` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `GetCombinedContactList`(
        p_user_id       VARCHAR(25),
        p_client_id     VARCHAR(25)
)
BEGIN
        
        SELECT client_contact_details.client_contact_id, 
	client_contact_details.contact_nick_name AS nick_name, 
	client_contact_details.contact_first_name AS f_name,
	client_contact_details.contact_last_name AS l_name,
	client_contact_details.contact_email_address,
	client_contact_details.contact_idd_code, 
	client_contact_details.contact_mobile_number, 
	client_contact_details.contact_group_name, 
	client_contact_details.client_id 
        FROM client_contact_details, user_details
        WHERE client_contact_details.client_id = user_details.client_id
        AND client_contact_details.client_id = p_client_id
        AND user_details.user_id = p_user_id
        AND client_contact_details.client_contact_status = '1'
        
        UNION
        SELECT personal_contact_details.personal_contact_id, 
	personal_contact_details.contact_nick_name AS nick_name, 
	personal_contact_details.contact_first_name AS f_name,
	personal_contact_details.contact_last_name AS l_name,
	personal_contact_details.contact_email_address,
	personal_contact_details.contact_idd_code, 
	personal_contact_details.contact_mobile_number, 
	personal_contact_details.contact_group_name, 
	personal_contact_details.user_id 
        FROM personal_contact_details, user_details
        WHERE personal_contact_details.user_id = user_details.user_id
        AND personal_contact_details.user_id = p_user_id
        AND user_details.client_id = p_client_id
        AND personal_contact_details.personal_contact_status = '1'
        ORDER BY nick_name, f_Name, l_Name;
        
END */$$
DELIMITER ;

/* Procedure structure for procedure `GetCombinedGroupName` */

/*!50003 DROP PROCEDURE IF EXISTS  `GetCombinedGroupName` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `GetCombinedGroupName`(
        p_user_id       VARCHAR(25),
        p_client_id     VARCHAR(25)
)
BEGIN
              
        SELECT DISTINCT client_contact_details.contact_group_name AS group_name
        FROM client_contact_details, user_details
        WHERE client_contact_details.client_id = user_details.client_id
        AND client_contact_details.client_id = p_client_id
        AND user_details.user_id = p_user_id 
        AND client_contact_details.client_contact_status = '1'
        UNION
        SELECT  DISTINCT personal_contact_details.contact_group_name AS group_name
        FROM personal_contact_details, user_details
        WHERE personal_contact_details.user_id = user_details.user_id
        AND personal_contact_details.user_id = p_user_id
        AND user_details.client_id = p_client_id
        AND personal_contact_details.personal_contact_status = '1'
        ORDER BY group_name;
        
END */$$
DELIMITER ;

/* Procedure structure for procedure `GetContactList` */

/*!50003 DROP PROCEDURE IF EXISTS  `GetContactList` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `GetContactList`(
    p_owner         	VARCHAR(25),
    p_type          	VARCHAR(3)
)
BEGIN
        IF (p_type = 'P') THEN
        
            SELECT personal_contact_id, contact_nick_name, contact_first_name, contact_last_name,
            contact_email_address, contact_idd_code,contact_mobile_number, contact_group_name, user_id
            FROM personal_contact_details
            WHERE user_id = p_owner
            AND personal_contact_status = '1'
            ORDER BY contact_nick_name, contact_first_name, contact_last_name, contact_group_name;
        
        END IF;   
        IF (p_type = 'C') THEN
            SELECT client_contact_id, contact_nick_name, contact_first_name, contact_last_name,
            contact_email_address, contact_idd_code, contact_mobile_number, contact_group_name, client_id 
            FROM client_contact_details
            WHERE client_id = p_owner
            AND client_contact_status = '1'
            ORDER BY contact_nick_name, contact_first_name, contact_last_name, contact_group_name;
        END IF;   
        
END */$$
DELIMITER ;

/* Procedure structure for procedure `GetRequestPwdDetails` */

/*!50003 DROP PROCEDURE IF EXISTS  `GetRequestPwdDetails` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `GetRequestPwdDetails`(
   p_email_address     VARCHAR(100),
   OUT  p_timestamp      DATETIME
)
BEGIN
    SELECT request_datetime INTO p_timestamp FROM  password_request_details WHERE email_address = p_email_address; 
END */$$
DELIMITER ;

/* Procedure structure for procedure `GetScheduleReminderList` */

/*!50003 DROP PROCEDURE IF EXISTS  `GetScheduleReminderList` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `GetScheduleReminderList`(
    p_from_date DATETIME,
    p_to_date   DATETIME,
    p_type      VARCHAR(3)
)
BEGIN
 IF (p_type = 'E1') THEN
SELECT schedule_id, ud.user_id, schedule_status, schedule_creation_time, meeting_timestamp_gmt, meeting_timestamp_local, meeting_title, meeting_agenda, meeting_timezone, meeting_gmt, welcome_message, voice_bridge, web_voice, max_participants, meeting_duration, email_address, nick_name
FROM schedule_details sd, user_details ud 
    WHERE sd.user_id = ud.user_id
    AND email_reminder_flag = 'Y' 
    AND email_reminder_status = '0' 
    AND schedule_status = '0' 
    AND meeting_timestamp_gmt BETWEEN p_from_date AND p_to_date
    ORDER BY meeting_timestamp_gmt;
END IF;   
 IF (p_type = 'E2') THEN
SELECT schedule_id, ud.user_id, schedule_status, schedule_creation_time, meeting_timestamp_gmt, meeting_timestamp_local, meeting_title, meeting_agenda, meeting_timezone, meeting_gmt, welcome_message, voice_bridge, web_voice, max_participants, meeting_duration, email_address, nick_name
FROM schedule_details sd, user_details ud
    WHERE sd.user_id = ud.user_id
    AND email_reminder_flag = 'Y' 
    AND email_reminder_status IN ('0', '1')
    AND schedule_status = '0' 
    AND meeting_timestamp_gmt BETWEEN p_from_date AND p_to_date
    ORDER BY meeting_timestamp_gmt;
END IF;   
 IF (p_type = 'S1') THEN
SELECT schedule_id, ud.user_id, schedule_status, schedule_creation_time, meeting_timestamp_gmt, meeting_timestamp_local, meeting_title, meeting_agenda, meeting_timezone, meeting_gmt, welcome_message, voice_bridge, web_voice, max_participants, meeting_duration, email_address, nick_name
FROM schedule_details sd, user_details ud
    WHERE sd.user_id = ud.user_id
    AND sms_reminder_flag = 'Y' 
    AND sms_reminder_status = '0'
    AND schedule_status = '0' 
    AND meeting_timestamp_gmt BETWEEN p_from_date AND p_to_date
    ORDER BY meeting_timestamp_gmt;
END IF;
END */$$
DELIMITER ;

/* Procedure structure for procedure `GetUserDetailsByUserId` */

/*!50003 DROP PROCEDURE IF EXISTS  `GetUserDetailsByUserId` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `GetUserDetailsByUserId`(
        p_user_id       VARCHAR(25)
)
BEGIN
              
    SELECT user_id, client_id, partner_id, email_address, password, nick_name, first_name, last_name, country_name, timezones, gmt, phone_number, idd_code, mobile_number, registration_dtm, status FROM user_details WHERE user_id = p_user_id;
        
END */$$
DELIMITER ;

/* Procedure structure for procedure `InsertClientLicenseDetails` */

/*!50003 DROP PROCEDURE IF EXISTS  `InsertClientLicenseDetails` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `InsertClientLicenseDetails`(
p_client_id           	VARCHAR(25),
p_no_of_license          INT(10),
p_operation_type       VARCHAR(5),
p_license_date		DATETIME,
    OUT  p_status           INT(1)
)
BEGIN
DECLARE CONTINUE HANDLER FOR SQLEXCEPTION
SELECT 0 INTO  p_status;
BEGIN
INSERT INTO client_license_details(client_id, no_of_license, operation_type, license_date)VALUES(p_client_id, p_no_of_license, p_operation_type, p_license_date);
        SET  @v_ins_row_count  := ROW_COUNT();
        IF  ( @v_ins_row_count  > 0) THEN
             SET p_status = 1;
        ELSE
            SET p_status = 0;
        END IF ;
END ;
END */$$
DELIMITER ;

/* Procedure structure for procedure `InsertClientSubscriptionMaster` */

/*!50003 DROP PROCEDURE IF EXISTS  `InsertClientSubscriptionMaster` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `InsertClientSubscriptionMaster`(
            p_client_id VARCHAR(25),
   	    p_subscription_date DATETIME,
            p_subscription_start_date_gmt DATE,
	    p_subscription_end_date_gmt DATE,
 	    p_subscription_start_date_local DATE,
	    p_subscription_end_date_local DATE,
 	    p_subscription_status ENUM('0', '1', '2', '3'),
	    p_order_id VARCHAR(25), 
            p_plan_id INT(10), 
	    p_plan_name VARCHAR(30),
            p_plan_desc TEXT,
            p_plan_for ENUM('ENT', 'OTH', 'POR', 'RET'),
            p_plan_type ENUM('S', 'T', 'U'),
            p_number_of_sessions INT(10),
	    p_number_of_mins_per_sessions INT(10),
            p_plan_period INT(10),
            p_number_of_invitee INT(10),
   	    p_meeting_recording ENUM('true', 'false'),
            p_disk_space BIGINT(20),
            p_is_free ENUM('0', '1'),
	    p_plan_cost_inr DECIMAL(10,2),
 	    p_plan_cost_oth DECIMAL(10,2),
 	    p_concurrent_sessions INT(10),
            p_talk_time_mins INT(10),
	    p_autorenew_flag ENUM('0', '1'),
	    p_consumed_number_of_sessions INT(10),
            p_consumed_talk_time_mins INT(10),
    OUT  p_status           INT(1),
    OUT  p_output           VARCHAR(25) 
)
BEGIN
DECLARE CONTINUE HANDLER FOR SQLEXCEPTION
SELECT 0 INTO  p_status;
SELECT 0 INTO  p_output; 
BEGIN
    IF p_talk_time_mins = '' THEN 
        BEGIN
              SET p_talk_time_mins = NULL;
        END;       
              
        END IF;
    INSERT INTO client_subscription_master
    (client_id, subscription_date, subscription_start_date_gmt, subscription_end_date_gmt, subscription_start_date_local, subscription_end_date_local, subscription_status, order_id, plan_id, plan_name, plan_desc, plan_for, plan_type, number_of_sessions, number_of_mins_per_sessions, plan_period, number_of_invitee, meeting_recording, disk_space, is_free, plan_cost_inr, plan_cost_oth, concurrent_sessions, talk_time_mins, autorenew_flag, consumed_number_of_sessions, consumed_talk_time_mins)
    VALUES (p_client_id, p_subscription_date, p_subscription_start_date_gmt, p_subscription_end_date_gmt, p_subscription_start_date_local, p_subscription_end_date_local, p_subscription_status, p_order_id, p_plan_id, p_plan_name, p_plan_desc, p_plan_for, p_plan_type, p_number_of_sessions, p_number_of_mins_per_sessions, p_plan_period, p_number_of_invitee, p_meeting_recording, p_disk_space, p_is_free, p_plan_cost_inr,  p_plan_cost_oth, p_concurrent_sessions, p_talk_time_mins, p_autorenew_flag, p_consumed_number_of_sessions, p_consumed_talk_time_mins);
    SET  @v_ins_row_count  := ROW_COUNT();
    IF  ( @v_ins_row_count  > 0) THEN
         SET p_status = 1;
         SET p_output =  LAST_INSERT_ID();
    ELSE
        SET p_status = 0;
        SET p_output = 0;
    END IF ;
    
END ;
END */$$
DELIMITER ;

/* Procedure structure for procedure `InsertContactDetails` */

/*!50003 DROP PROCEDURE IF EXISTS  `InsertContactDetails` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `InsertContactDetails`(
            p_nick_name     	VARCHAR(50),
            p_first_name    	VARCHAR(50),
            p_last_name     	VARCHAR(50),
            p_email_address 	VARCHAR(100),
            p_contact_idd_code  Varchar(10),
            p_mobile_number 	VARCHAR(20),
            p_group_name    	VARCHAR(100),
            p_type          	VARCHAR(3),
            p_owner         	VARCHAR(25),
    OUT     p_status            INT(1),
    OUT     p_message           VARCHAR(200)  
)
BEGIN
DECLARE CONTINUE HANDLER FOR SQLEXCEPTION
SELECT 0 INTO  p_status;
SELECT 'Error in Adding' INTO p_message; 
BEGIN
    IF (p_type = 'P') THEN
        INSERT INTO personal_contact_details
        (contact_nick_name, contact_first_name, contact_last_name, contact_email_address, contact_idd_code, contact_mobile_number, contact_group_name, user_id, personal_contact_status)
        VALUES
        (p_nick_name, p_first_name, p_last_name, p_email_address, p_contact_idd_code, p_mobile_number, p_group_name, p_owner, '1');
        SET  @v_ins_row_count  := ROW_COUNT();
        IF  ( @v_ins_row_count  > 0) THEN
             SET p_status = 1;
             SET p_message = 'Added Successfully';
        ELSE
            SET p_status = 0;
            SET p_message = 'Error in Adding';
        END IF ;
    ELSEIF (p_type = 'C') THEN
        INSERT INTO client_contact_details
        (contact_nick_name, contact_first_name, contact_last_name, contact_email_address, contact_idd_code, contact_mobile_number, contact_group_name, client_id, client_contact_status)
        VALUES
        (p_nick_name, p_first_name, p_last_name, p_email_address, p_contact_idd_code, p_mobile_number, p_group_name, p_owner, '1');
        SET  @v_ins_row_count  := ROW_COUNT();
        IF  ( @v_ins_row_count  > 0) THEN
             SET p_status = 1;
             SET p_message = 'Added Successfully';
        ELSE
            SET p_status = 0;
            SET p_message = 'Error in Adding';
        END IF ;
       
    ELSE
        SET p_status = 0;
        SET p_message = 'Error in Adding';
    END IF;   
    
END ;
END */$$
DELIMITER ;

/* Procedure structure for procedure `InsertInvitationDetails` */

/*!50003 DROP PROCEDURE IF EXISTS  `InsertInvitationDetails` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `InsertInvitationDetails`(
    p_schedule_id               VARCHAR(25), 
    p_invitee_email_address     VARCHAR(100),  
    p_invitee_nick_name         VARCHAR(50), 
    p_invitee_idd_code          VARCHAR(10), 
    p_invitee_mobile_number     VARCHAR(20), 
    p_invitation_creator        ENUM('C','I','M'), 
    p_invitation_creation_dtm   DATETIME, 
    OUT  p_status               INT(1)
    
)
BEGIN
DECLARE CONTINUE HANDLER FOR SQLEXCEPTION
SELECT 0 INTO  p_status;
BEGIN
        IF p_invitee_idd_code = '' THEN 
        BEGIN
              SET p_invitee_idd_code = NULL;
        END;       
        END IF;
        IF p_invitee_mobile_number = '' THEN 
        BEGIN
              SET p_invitee_mobile_number = NULL;
        END;       
        END IF;
INSERT INTO invitation_details (schedule_id, invitee_email_address, invitee_nick_name, invitee_idd_code, invitee_mobile_number, invitation_creator, invitation_creation_dtm)
VALUES (p_schedule_id, p_invitee_email_address, p_invitee_nick_name, p_invitee_idd_code, p_invitee_mobile_number, p_invitation_creator, p_invitation_creation_dtm);
        
        SET  @v_ins_row_count  := ROW_COUNT();
        IF  ( @v_ins_row_count  > 0) THEN
             SET p_status = 1;
        ELSE
            SET p_status = 0;
        END IF ;
    
END ;
END */$$
DELIMITER ;

/* Procedure structure for procedure `InsertOrderDetails` */

/*!50003 DROP PROCEDURE IF EXISTS  `InsertOrderDetails` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `InsertOrderDetails`(
            p_order_id VARCHAR(25),
            p_plan_id INT(10),
            p_plan_name VARCHAR(30),
            p_currency_type VARCHAR(10),
            p_price DECIMAL(10,2),
            p_quantity INT(10),
            p_amount DECIMAL(10,2),
            p_service_tax_percent DECIMAL(10,2),
            p_service_tax_amount DECIMAL(10,2),
            p_total_amount DECIMAL(10,2),
            p_conversion_rate DECIMAL(10,2),
    OUT  p_status           INT(1),
    OUT  p_output           VARCHAR(25)
)
BEGIN
DECLARE CONTINUE HANDLER FOR SQLEXCEPTION
SELECT 0 INTO  p_status;
SELECT '0' INTO  p_output;
BEGIN
    INSERT INTO order_details
    (order_id, plan_id, plan_name, currency_type, price, quantity, amount, service_tax_percent, service_tax_amount, total_amount, conversion_rate)
    VALUES ( p_order_id, p_plan_id, p_plan_name, p_currency_type, p_price, p_quantity, p_amount, p_service_tax_percent, p_service_tax_amount, p_total_amount, p_conversion_rate);
    SET  @v_ins_row_count  := ROW_COUNT();
    IF  ( @v_ins_row_count  > 0) THEN
         SET p_status = 1;
         SET p_output = p_order_id;
    ELSE
        SET p_status = 0;
        SET p_output = '0';
    END IF ;
END ;
END */$$
DELIMITER ;

/* Procedure structure for procedure `InsertOrderMaster` */

/*!50003 DROP PROCEDURE IF EXISTS  `InsertOrderMaster` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `InsertOrderMaster`(
p_sub_id           VARCHAR(25),
p_email_address 	VARCHAR(100),
p_order_id          VARCHAR(25),
p_pg_name           VARCHAR(50),
p_payment_from      VARCHAR(10),
p_order_date	DATETIME,
p_order_date_gmt	DATETIME,
p_ip_address     	VARCHAR(50),
    OUT  p_status           INT(1),
    OUT  p_output           VARCHAR(25)
)
BEGIN
DECLARE CONTINUE HANDLER FOR SQLEXCEPTION
SELECT 0 INTO  p_status;
SELECT '0' INTO  p_output;
BEGIN
INSERT INTO order_master
(subscriber_id, email_address, order_id, payment_gateway_name, payment_from, order_status, order_date, order_date_gmt, ip_address)
VALUES
(p_sub_id, p_email_address, p_order_id, p_pg_name, p_payment_from, 'pending', p_order_date, p_order_date_gmt, p_ip_address);
        SET  @v_ins_row_count  := ROW_COUNT();
        IF  ( @v_ins_row_count  > 0) THEN
             SET p_status = 1;
             SET p_output = p_order_id;
        ELSE
            SET p_status = 0;
            SET p_output = '0';
        END IF ;
END ;
END */$$
DELIMITER ;

/* Procedure structure for procedure `InsertScheduleDetails` */

/*!50003 DROP PROCEDURE IF EXISTS  `InsertScheduleDetails` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `InsertScheduleDetails`(
    p_schedule_id               VARCHAR(25), 
    p_user_id                   VARCHAR(25), 
    p_schedule_creation_time    DATETIME, 
    p_meeting_timestamp_gmt     DATETIME, 
    p_meeting_timestamp_local   DATETIME, 
    p_meeting_title             VARCHAR(200), 
    p_meeting_agenda            VARCHAR(150), 
    p_meeting_timezone          VARCHAR(100), 
    p_meeting_gmt               VARCHAR(50), 
    p_attendee_password         VARCHAR(50), 
    p_moderator_password        VARCHAR(50), 
    p_welcome_message           VARCHAR(100), 
    p_voice_bridge              VARCHAR(32), 
    p_web_voice                 VARCHAR(32), 
    p_max_participants          INT(20), 
    p_record_flag               VARCHAR(10), 
    p_meeting_duration          INT(20), 
    p_meta_tags                 VARCHAR(250), 
    p_email_reminder_flag       ENUM('Y', 'N'), 
    p_email_reminder_status     ENUM('0', '1', '2'), 
    p_sms_reminder_flag         ENUM('Y', 'N'), 
    p_sms_reminder_status       ENUM('0', '1'), 
    p_meeting_instance          VARCHAR(255), 
    p_subscription_id           INT(10),            
    OUT  p_status               INT(1),
    OUT  p_output               VARCHAR(25) 
)
BEGIN
DECLARE CONTINUE HANDLER FOR SQLEXCEPTION
SELECT 0 INTO  p_status;
SELECT '0' INTO  p_output; 
BEGIN
        IF p_meeting_agenda = '' THEN 
        BEGIN
              SET p_meeting_agenda = NULL;
        END;       
        END IF;
        IF p_welcome_message = '' THEN 
        BEGIN
              SET p_welcome_message = NULL;
        END;       
        END IF;
        IF p_meta_tags = '' THEN 
        BEGIN
              SET p_meta_tags = NULL;
        END;       
        END IF;
INSERT INTO schedule_details (schedule_id, user_id, schedule_creation_time, meeting_timestamp_gmt, meeting_timestamp_local, meeting_title, meeting_agenda, meeting_timezone, meeting_gmt, attendee_password, moderator_password, welcome_message, voice_bridge, web_voice, max_participants, record_flag, meeting_duration, meta_tags, email_reminder_flag, email_reminder_status, sms_reminder_flag, sms_reminder_status, meeting_instance, subscription_id)
VALUES (p_schedule_id, p_user_id, p_schedule_creation_time, p_meeting_timestamp_gmt, p_meeting_timestamp_local, p_meeting_title, p_meeting_agenda, p_meeting_timezone, p_meeting_gmt, p_attendee_password, p_moderator_password, p_welcome_message, p_voice_bridge, p_web_voice, p_max_participants, p_record_flag, p_meeting_duration, p_meta_tags, p_email_reminder_flag, p_email_reminder_status, p_sms_reminder_flag, p_sms_reminder_status, p_meeting_instance, p_subscription_id);
        
        SET  @v_ins_row_count  := ROW_COUNT();
        IF  ( @v_ins_row_count  > 0) THEN
             SET p_status = 1;
             SET p_output = p_schedule_id;
        ELSE
            SET p_status = 0;
            SET p_output = '0';
        END IF ;
END ;
END */$$
DELIMITER ;

/* Procedure structure for procedure `InsertSubscriptionMaster` */

/*!50003 DROP PROCEDURE IF EXISTS  `InsertSubscriptionMaster` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `InsertSubscriptionMaster`(
            p_user_id VARCHAR(25),
   	    p_subscription_date DATETIME,
            p_subscription_start_date_gmt DATE,
	    p_subscription_end_date_gmt DATE,
 	    p_subscription_start_date_local DATE,
	    p_subscription_end_date_local DATE,
 	    p_subscription_status ENUM('0', '1', '2', '3'),
	    p_order_id VARCHAR(25), 
            p_plan_id INT(10), 
	    p_plan_name VARCHAR(30),
            p_plan_desc TEXT,
            p_plan_for ENUM('ENT', 'OTH', 'POR', 'RET'),
            p_plan_type ENUM('S', 'T', 'U'),
            p_number_of_sessions INT(10),
	    p_number_of_mins_per_sessions INT(10),
            p_plan_period INT(10),
            p_number_of_invitee INT(10),
   	    p_meeting_recording ENUM('true', 'false'),
            p_disk_space BIGINT(20),
            p_is_free ENUM('0', '1'),
	    p_plan_cost_inr DECIMAL(10,2),
 	    p_plan_cost_oth DECIMAL(10,2),
 	    p_concurrent_sessions INT(10),
            p_talk_time_mins INT(10),
	    p_autorenew_flag ENUM('0', '1'),
	    p_consumed_number_of_sessions INT(10),
            p_consumed_talk_time_mins INT(10),
    OUT  p_status           INT(1),
    OUT  p_output           VARCHAR(25) 
)
BEGIN
DECLARE CONTINUE HANDLER FOR SQLEXCEPTION
SELECT 0 INTO  p_status;
SELECT '0' INTO  p_output; 
BEGIN
    IF p_talk_time_mins = '' THEN 
        BEGIN
              SET p_talk_time_mins = NULL;
        END;       
              
        END IF;
    INSERT INTO subscription_master 
    (user_id, subscription_date, subscription_start_date_gmt, subscription_end_date_gmt, subscription_start_date_local, subscription_end_date_local, subscription_status, order_id, plan_id, plan_name, plan_desc, plan_for, plan_type, number_of_sessions, number_of_mins_per_sessions, plan_period, number_of_invitee, meeting_recording, disk_space, is_free, plan_cost_inr, plan_cost_oth, concurrent_sessions, talk_time_mins, autorenew_flag, consumed_number_of_sessions, consumed_talk_time_mins)
    VALUES (p_user_id, p_subscription_date, p_subscription_start_date_gmt, p_subscription_end_date_gmt, p_subscription_start_date_local, p_subscription_end_date_local, p_subscription_status, p_order_id, p_plan_id, p_plan_name, p_plan_desc, p_plan_for, p_plan_type, p_number_of_sessions, p_number_of_mins_per_sessions, p_plan_period, p_number_of_invitee, p_meeting_recording, p_disk_space, p_is_free, p_plan_cost_inr,  p_plan_cost_oth, p_concurrent_sessions, p_talk_time_mins, p_autorenew_flag, p_consumed_number_of_sessions, p_consumed_talk_time_mins);
    SET  @v_ins_row_count  := ROW_COUNT();
    IF  ( @v_ins_row_count  > 0) THEN
         SET p_status = 1;
         SET p_output = LAST_INSERT_ID();
    ELSE
        SET p_status = 0;
        SET p_output = '0';
    END IF ;
    
END ;
END */$$
DELIMITER ;

/* Procedure structure for procedure `InsertUserDetails` */

/*!50003 DROP PROCEDURE IF EXISTS  `InsertUserDetails` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `InsertUserDetails`(
            p_user_id           VARCHAR(25),  
            p_client_id         VARCHAR(25),
            p_partner_id        VARCHAR(25),
            p_email_address 	VARCHAR(100),
            p_password          VARCHAR(50),
            p_nick_name     	VARCHAR(50),
            p_first_name    	VARCHAR(50),
            p_last_name     	VARCHAR(50),
            p_country_name      VARCHAR(150),
            p_timezones         VARCHAR(100),
            p_gmt               VARCHAR(20),
            p_phone_number 	VARCHAR(20),
            p_idd_code          VARCHAR(10),
            p_mobile_number 	VARCHAR(20),
            p_registration_dtm	DATETIME,
            p_is_admin		VARCHAR(3),
            p_status            VARCHAR(3),
    OUT  p_result           INT(1),
    OUT  p_userid           VARCHAR(50)  
)
BEGIN
DECLARE CONTINUE HANDLER FOR SQLEXCEPTION
SELECT 0 INTO  p_result;
SELECT '0' INTO  p_userid; 
BEGIN
        IF p_phone_number = '' THEN 
        BEGIN
              SET p_phone_number = NULL;
        END;       
              
        END IF;
        IF p_mobile_number = '' THEN 
        BEGIN
              SET p_mobile_number = NULL;
        END;       
              
        END IF;
        
        INSERT INTO user_details
        (user_id, client_id, partner_id, email_address, PASSWORD, nick_name, first_name, last_name, country_name, timezones, gmt, phone_number, idd_code, mobile_number, registration_dtm, is_admin, STATUS)
        VALUES
        (p_user_id, p_client_id, p_partner_id, p_email_address, p_password,  p_nick_name, p_first_name, p_last_name, p_country_name, p_timezones, p_gmt, p_phone_number, p_idd_code, p_mobile_number, p_registration_dtm, p_is_admin, p_status);
        
        SET  @v_ins_row_count  := ROW_COUNT();
        IF  ( @v_ins_row_count  > 0) THEN
             SET p_result = 1;
             SET p_userid = p_user_id;
        ELSE
            SET p_result = 0;
            SET p_userid = '0';
        END IF ;
    
END ;
END */$$
DELIMITER ;

/* Procedure structure for procedure `IsContactEmailExists` */

/*!50003 DROP PROCEDURE IF EXISTS  `IsContactEmailExists` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `IsContactEmailExists`(
            p_email_address   VARCHAR(100),
            p_user_id         VARCHAR(25),
            p_clientid        VARCHAR(25),
      OUT   p_status          INT(1),
      OUT   p_flag            VARCHAR(3)
)
BEGIN
    DECLARE  v_personal_contact_count    INT;
    DECLARE  v_client_contact_count      INT;
    SELECT 0 INTO  p_status;
    SELECT 'EE' INTO p_flag;
    SET v_personal_contact_count := (SELECT COUNT(contact_email_address) FROM personal_contact_details WHERE contact_email_address = p_email_address AND user_id = p_user_id);
    IF (v_personal_contact_count > 0 ) THEN
        SET p_status = 2;
        SET p_flag = 'PC';
    ELSE
        SET v_client_contact_count := (SELECT COUNT(contact_email_address) FROM client_contact_details WHERE contact_email_address = p_email_address AND client_id = p_clientid);
            IF (v_client_contact_count > 0 ) THEN
                SET p_status = 3;
                SET p_flag = 'CC';
            ELSE
                SET p_status = 1;
                SET p_flag = 'NC';
            END IF;
    END IF;
END */$$
DELIMITER ;

/* Procedure structure for procedure `IsContactGroupExists` */

/*!50003 DROP PROCEDURE IF EXISTS  `IsContactGroupExists` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `IsContactGroupExists`(
          p_group_name      VARCHAR(100),
          p_user_id          VARCHAR(25),
          p_clientid        VARCHAR(25),
     OUT  p_status          INT(1),
     OUT  p_flag            VARCHAR(3)
)
BEGIN
    DECLARE  v_personal_group_count    INT;
    DECLARE  v_client_group_count      INT;
    SELECT 0 INTO  p_status;
    SELECT 'GE' INTO p_flag;
    SET v_personal_group_count := (SELECT COUNT(contact_group_name) FROM personal_contact_details WHERE contact_group_name = p_group_name AND user_id = p_user_id);
    
    IF (v_personal_group_count > 0 ) THEN
        SET p_status = 2;
        SET p_flag = 'PC';
    ELSE
        SET v_client_group_count := (SELECT COUNT(contact_group_name) FROM client_contact_details WHERE contact_group_name = p_group_name AND client_id = p_clientid);
            IF (v_client_group_count > 0 ) THEN
                 SET p_status = 3;
                 SET p_flag = 'CC';
            ELSE
                SET p_status = 1;
                SET p_flag = 'NG';
            END IF;
    END IF;
END */$$
DELIMITER ;

/* Procedure structure for procedure `IsPartnerEmailExists` */

/*!50003 DROP PROCEDURE IF EXISTS  `IsPartnerEmailExists` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `IsPartnerEmailExists`(
            p_email_address   VARCHAR(100),
      OUT   p_status          INT(1)
)
BEGIN
    DECLARE  v_email_address_count    INT;
    
    SET v_email_address_count := (SELECT COUNT(email_address) FROM partner_details WHERE email_address = p_email_address);
    
    IF (v_email_address_count > 0 ) THEN
    
        SET p_status = 1;
        
     ELSE
                
	 SET p_status = 0;
               
    END IF;
    
END */$$
DELIMITER ;

/* Procedure structure for procedure `IsUserEmailExists` */

/*!50003 DROP PROCEDURE IF EXISTS  `IsUserEmailExists` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `IsUserEmailExists`(
            p_email_address   VARCHAR(100),
      OUT   p_status          INT(1)
)
BEGIN
    DECLARE  v_email_address_count    INT;
    
    SET v_email_address_count := (SELECT COUNT(email_address) FROM user_details WHERE email_address = p_email_address);
    
    IF (v_email_address_count > 0 ) THEN
    
        SET p_status = 1;
        
     ELSE
                
	 SET p_status = 0;
               
    END IF;
    
END */$$
DELIMITER ;

/* Procedure structure for procedure `UpdateAdminPassword` */

/*!50003 DROP PROCEDURE IF EXISTS  `UpdateAdminPassword` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `UpdateAdminPassword`(
         p_email_address    VARCHAR(100),
         p_old_password     VARCHAR(32),
         p_new_password     VARCHAR(32),
    OUT  p_status           INT,
    OUT  p_admin_id          VARCHAR(25),
    OUT  p_email            VARCHAR(100)
)
BEGIN
DECLARE v_email_address VARCHAR(100);
DECLARE v_password VARCHAR(32);
SELECT 0 INTO p_status;
	SELECT email_address,PASSWORD INTO v_email_address, v_password FROM admin_login WHERE email_address = p_email_address AND PASSWORD = p_old_password;
	
	IF(v_email_address IS NULL) THEN
	
	SELECT 2 INTO p_status;
	
	ELSEIF (v_password = p_new_password) THEN
	
        SELECT 3 INTO p_status;
        
        ELSE
        
		UPDATE  admin_login SET PASSWORD = p_new_password WHERE email_address = p_email_address;
		
		SET @v_count = ROW_COUNT();
		
		IF  @v_count > 0
		
		THEN
			SELECT 1 INTO p_status;
			
			SELECT admin_id, email_address INTO  p_admin_id, p_email FROM admin_login WHERE email_address = p_email_address AND PASSWORD = p_new_password; 
		
                END IF;
	END IF;
END */$$
DELIMITER ;

/* Procedure structure for procedure `UpdateClientConsumedSessions` */

/*!50003 DROP PROCEDURE IF EXISTS  `UpdateClientConsumedSessions` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `UpdateClientConsumedSessions`(
            p_client_subscription_id   INT(10),
            p_client_id           VARCHAR(25),
            p_type              VARCHAR(3),  
    OUT     p_status            INT(1)
)
BEGIN
DECLARE  v_number_of_sessions_count  INT;
DECLARE  v_consumed_sessions_count   INT;
DECLARE  v_plan_type   VARCHAR(3);
DECLARE CONTINUE HANDLER FOR SQLEXCEPTION
SELECT 0 INTO  p_status;
SELECT number_of_sessions, consumed_number_of_sessions, plan_type INTO v_number_of_sessions_count, v_consumed_sessions_count, v_plan_type FROM client_subscription_master WHERE client_subscription_id = p_client_subscription_id AND client_id = p_client_id;
    IF ((p_type = 'A') AND (v_plan_type = 'S') AND ((v_consumed_sessions_count < v_number_of_sessions_count) OR (v_consumed_sessions_count = 0 )) ) THEN
        
            UPDATE client_subscription_master SET consumed_number_of_sessions = (v_consumed_sessions_count + 1) WHERE client_subscription_id = p_client_subscription_id AND client_id = p_client_id;
            SET  @v_upd_row_count  := ROW_COUNT();
            
            IF ( @v_upd_row_count  > 0) THEN
            
                SET p_status = 1;
                
            ELSEIF (@v_upd_row_count = 0) THEN
            
                SET p_status = 2;
                
            ELSE
            
                SET p_status = 0;
                
            END IF ;
    
ELSEIF ((p_type = 'A') AND (v_plan_type <> 'S')) THEN
        
            UPDATE client_subscription_master SET consumed_number_of_sessions = (v_consumed_sessions_count + 1) WHERE client_subscription_id = p_client_subscription_id AND client_id = p_client_id;
            SET  @v_upd_row_count  := ROW_COUNT();
            
            IF ( @v_upd_row_count  > 0) THEN
            
                SET p_status = 1;
                
            ELSEIF (@v_upd_row_count = 0) THEN
            
                SET p_status = 2;
                
            ELSE
            
                SET p_status = 0;
                
            END IF ;
    
        
    ELSEIF ((p_type = 'S') AND (v_consumed_sessions_count > 0))THEN    
        
            UPDATE client_subscription_master SET consumed_number_of_sessions = (v_consumed_sessions_count - 1) WHERE client_subscription_id = p_client_subscription_id AND client_id = p_client_id;
            SET  @v_upd_row_count  := ROW_COUNT();
            
            IF ( @v_upd_row_count  > 0) THEN
            
                SET p_status = 1;
                
            ELSEIF (@v_upd_row_count = 0) THEN
            
                SET p_status = 2;
                
            ELSE
            
                SET p_status = 0;
                
            END IF ;
    ELSE
           
          SET p_status = 0;  
          
    END IF;
        
END */$$
DELIMITER ;

/* Procedure structure for procedure `UpdateClientPassword` */

/*!50003 DROP PROCEDURE IF EXISTS  `UpdateClientPassword` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `UpdateClientPassword`(
         p_email_address    VARCHAR(100),
         p_old_password     VARCHAR(32),
         p_new_password     VARCHAR(32),
    OUT  p_status           INT,
    OUT  p_client_id        VARCHAR(25),
    OUT  p_email            VARCHAR(100)
)
BEGIN
DECLARE v_email_address VARCHAR(100);
DECLARE v_password VARCHAR(32);
SELECT 0 INTO p_status;
	SELECT client_email_address, client_password INTO v_email_address, v_password FROM client_details WHERE client_email_address = p_email_address AND client_password = p_old_password;
	
	IF(v_email_address IS NULL) THEN
	
	SELECT 2 INTO p_status;
	
	ELSEIF (v_password = p_new_password) THEN
	
        SELECT 3 INTO p_status;
        
        ELSE
        
		UPDATE client_details SET client_password = p_new_password WHERE client_email_address = p_email_address;
		
		SET @v_count = ROW_COUNT();
		
		IF  @v_count > 0
		
		THEN
			SELECT 1 INTO p_status;
			
			SELECT client_id, client_email_address INTO  p_client_id, p_email FROM client_details WHERE client_email_address = p_email_address AND client_password = p_new_password; 
		
                END IF;
	END IF;
END */$$
DELIMITER ;

/* Procedure structure for procedure `UpdateConsumedSessions` */

/*!50003 DROP PROCEDURE IF EXISTS  `UpdateConsumedSessions` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `UpdateConsumedSessions`(
            p_subscription_id   INT(10),
            p_user_id           VARCHAR(25),
            p_type              VARCHAR(3),  
    OUT     p_status            INT(1)
)
BEGIN
DECLARE  v_number_of_sessions_count  INT;
DECLARE  v_consumed_sessions_count   INT;
DECLARE  v_plan_type   VARCHAR(3);
DECLARE CONTINUE HANDLER FOR SQLEXCEPTION
SELECT 0 INTO  p_status;
SELECT number_of_sessions, consumed_number_of_sessions, plan_type INTO v_number_of_sessions_count, v_consumed_sessions_count, v_plan_type FROM subscription_master WHERE subscription_id = p_subscription_id AND user_id = p_user_id;
    IF ((p_type = 'A') AND (v_plan_type = 'S') AND ((v_consumed_sessions_count < v_number_of_sessions_count) OR (v_consumed_sessions_count = 0 )) ) THEN
        
            UPDATE subscription_master SET consumed_number_of_sessions = (v_consumed_sessions_count + 1) WHERE subscription_id = p_subscription_id AND user_id = p_user_id;
            SET  @v_upd_row_count  := ROW_COUNT();
            
            IF ( @v_upd_row_count  > 0) THEN
            
                SET p_status = 1;
                
            ELSEIF (@v_upd_row_count = 0) THEN
            
                SET p_status = 2;
                
            ELSE
            
                SET p_status = 0;
                
            END IF ;
    
ELSEIF ((p_type = 'A') AND (v_plan_type <> 'S')) THEN
        
            UPDATE subscription_master SET consumed_number_of_sessions = (v_consumed_sessions_count + 1) WHERE subscription_id = p_subscription_id AND user_id = p_user_id;
            SET  @v_upd_row_count  := ROW_COUNT();
            
            IF ( @v_upd_row_count  > 0) THEN
            
                SET p_status = 1;
                
            ELSEIF (@v_upd_row_count = 0) THEN
            
                SET p_status = 2;
                
            ELSE
            
                SET p_status = 0;
                
            END IF ;
    
        
    ELSEIF ((p_type = 'S') AND (v_consumed_sessions_count > 0))THEN    
        
            UPDATE subscription_master SET consumed_number_of_sessions = (v_consumed_sessions_count - 1) WHERE subscription_id = p_subscription_id AND user_id = p_user_id;
            SET  @v_upd_row_count  := ROW_COUNT();
            
            IF ( @v_upd_row_count  > 0) THEN
            
                SET p_status = 1;
                
            ELSEIF (@v_upd_row_count = 0) THEN
            
                SET p_status = 2;
                
            ELSE
            
                SET p_status = 0;
                
            END IF ;
    ELSE
           
          SET p_status = 0;  
          
    END IF;
        
END */$$
DELIMITER ;

/* Procedure structure for procedure `UpdateContactDetails` */

/*!50003 DROP PROCEDURE IF EXISTS  `UpdateContactDetails` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `UpdateContactDetails`(
            p_contact_id        INT(10),
            p_nick_name     	VARCHAR(50),
            p_first_name    	VARCHAR(50),
            p_last_name     	VARCHAR(50),
            p_email_address 	VARCHAR(100),
            p_contact_idd_code  VARCHAR(10),
            p_mobile_number 	VARCHAR(20),
            p_group_name    	VARCHAR(100),
            p_type          	VARCHAR(3),
            p_owner         	VARCHAR(25),
    OUT     p_status            INT(1),
    OUT     p_message           VARCHAR(200)  
)
BEGIN
DECLARE CONTINUE HANDLER FOR SQLEXCEPTION
SELECT 0 INTO  p_status;
SELECT 'Error in Updating' INTO p_message; 
BEGIN
    IF (p_type  = 'P') THEN
        UPDATE personal_contact_details SET
        contact_nick_name = p_nick_name ,
        contact_first_name = p_first_name,
        contact_last_name = p_last_name,
        contact_email_address = p_email_address,
        contact_idd_code = p_contact_idd_code,
        contact_mobile_number = p_mobile_number,
        contact_group_name = p_group_name 
        WHERE personal_contact_id = p_contact_id
        AND user_id = p_owner;
        
        SET  @v_upd_row_count  := ROW_COUNT();
        IF ( @v_upd_row_count  > 0) THEN
   
            SET p_status = 1;
            SET p_message = 'Updated Successfully';
           
        ELSEIF (@v_upd_row_count = 0) THEN
            SET p_status = 2;
            SET p_message = 'Nothing to Update';
        ELSE
            SET p_status = 0;
            SET p_message = 'Error in Updating';
        END IF ;
    ELSEIF (p_type = 'C') THEN
        UPDATE client_contact_details SET
	contact_nick_name = p_nick_name,
	contact_first_name = p_first_name,
	contact_last_name = p_last_name,
	contact_email_address = p_email_address,
	contact_idd_code = p_contact_idd_code,
	contact_mobile_number = p_mobile_number,
	contact_group_name = p_group_name 
        WHERE client_contact_id = p_contact_id
	AND client_id = p_owner;
        SET  @v_upd_row_count  := ROW_COUNT();
        IF ( @v_upd_row_count  > 0) THEN
   
            SET p_status = 1;
            SET p_message = 'Updated Successfully';
           
        ELSEIF (@v_upd_row_count = 0) THEN
            SET p_status = 2;
            SET p_message = 'Nothing to Update';
        ELSE
            SET p_status = 0;
            SET p_message = 'Error in Updating';
        END IF ;
    
    ELSE
        SET p_status = 0;
        SET p_message = 'Error in Updating';
    END IF;      
    
END ;
END */$$
DELIMITER ;

/* Procedure structure for procedure `UpdateEndSchedule` */

/*!50003 DROP PROCEDURE IF EXISTS  `UpdateEndSchedule` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `UpdateEndSchedule`(
p_schedule_id          VARCHAR(25),
p_old_schedule_status  VARCHAR(3),
p_new_schedule_status  VARCHAR(3),
p_meeting_end_time     DATETIME,
OUT     p_status       INT(1)
)
BEGIN
DECLARE CONTINUE HANDLER FOR SQLEXCEPTION
SELECT 0 INTO  p_status;
    UPDATE schedule_details SET schedule_status = p_new_schedule_status, meeting_end_time = p_meeting_end_time WHERE schedule_id = p_schedule_id AND schedule_status = p_old_schedule_status;
    SET  @v_upd_row_count  := ROW_COUNT();
    IF (@v_upd_row_count  > 0) THEN
        SET p_status = 1;
    ELSEIF (@v_upd_row_count = 0) THEN
        SET p_status = 2;
    ELSE
        SET p_status = 0;
    END IF ;
END */$$
DELIMITER ;

/* Procedure structure for procedure `UpdateScheduleReminderList` */

/*!50003 DROP PROCEDURE IF EXISTS  `UpdateScheduleReminderList` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `UpdateScheduleReminderList`(
            p_from_date DATETIME,
            p_to_date   DATETIME,
            p_type      VARCHAR(3),
    OUT     p_status    INT(1)
)
BEGIN
DECLARE CONTINUE HANDLER FOR SQLEXCEPTION
SELECT 0 INTO  p_status;
 IF (p_type = 'E1') THEN
    UPDATE schedule_details SET email_reminder_status = '1' WHERE email_reminder_flag = 'Y' AND email_reminder_status = '0' AND schedule_status = '0' AND meeting_timestamp_gmt BETWEEN p_from_date AND p_to_date;
    SET  @v_upd_row_count  := ROW_COUNT();
    IF ( @v_upd_row_count  > 0) THEN
        SET p_status = 1;
    ELSEIF (@v_upd_row_count = 0) THEN
        SET p_status = 2;
    ELSE
        SET p_status = 0;
    END IF ;
END IF;   
 IF (p_type = 'E2') THEN
    UPDATE schedule_details SET email_reminder_status = '2' WHERE email_reminder_flag = 'Y' AND email_reminder_status IN ('0', '1') AND schedule_status = '0' AND meeting_timestamp_gmt BETWEEN p_from_date AND p_to_date;
    SET  @v_upd_row_count  := ROW_COUNT();
    IF ( @v_upd_row_count  > 0) THEN
        SET p_status = 1;
    ELSEIF (@v_upd_row_count = 0) THEN
        SET p_status = 2;
    ELSE
        SET p_status = 0;
    END IF ;
END IF;   
 IF (p_type = 'S1') THEN
    UPDATE schedule_details SET sms_reminder_status = '1' WHERE sms_reminder_flag = 'Y' AND sms_reminder_status = '0' AND schedule_status = '0' AND meeting_timestamp_gmt BETWEEN p_from_date AND p_to_date;
    SET  @v_upd_row_count  := ROW_COUNT();
    IF ( @v_upd_row_count  > 0) THEN
        SET p_status = 1;
    ELSEIF (@v_upd_row_count = 0) THEN
        SET p_status = 2;
    ELSE
        SET p_status = 0;
    END IF ;
END IF;
END */$$
DELIMITER ;

/* Procedure structure for procedure `UpdateScheduleStatus` */

/*!50003 DROP PROCEDURE IF EXISTS  `UpdateScheduleStatus` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `UpdateScheduleStatus`(
	p_schedule_id		VARCHAR(25),
	p_old_schedule_status 	VARCHAR(3),
	p_new_schedule_status 	VARCHAR(3),
OUT     p_status  		INT(1)
)
BEGIN
DECLARE CONTINUE HANDLER FOR SQLEXCEPTION
SELECT 0 INTO  p_status;
    UPDATE schedule_details SET schedule_status = p_new_schedule_status WHERE schedule_id = p_schedule_id and schedule_status =p_old_schedule_status ;
    
    SET  @v_upd_row_count  := ROW_COUNT();
    
    IF ( @v_upd_row_count  > 0) THEN
    
        SET p_status = 1;
        
    ELSEIF (@v_upd_row_count = 0) THEN
    
        SET p_status = 2;
        
    ELSE
    
        SET p_status = 0;
        
    END IF ;
    
END */$$
DELIMITER ;

/* Procedure structure for procedure `UpdateUserDetails` */

/*!50003 DROP PROCEDURE IF EXISTS  `UpdateUserDetails` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `UpdateUserDetails`(
            p_user_id           VARCHAR(25), 
            p_nick_name     	VARCHAR(50),
            p_first_name    	VARCHAR(50),
            p_last_name     	VARCHAR(50),
            p_country_name      VARCHAR(150),
            p_timezones         Varchar(100),
            p_gmt               VARCHAR(20),
            p_idd_code          VARCHAR(10),
            p_mobile_number 	VARCHAR(20),
            
    OUT     p_status            INT(1),
    OUT     p_message           VARCHAR(200) 
)
BEGIN
DECLARE CONTINUE HANDLER FOR SQLEXCEPTION
SELECT 0 INTO  p_status;
SELECT 'Error in Updating' INTO p_message; 
BEGIN
        UPDATE user_details SET
        nick_name = p_nick_name,
        first_name = p_first_name,
        last_name = p_last_name,
        country_name = p_country_name,
        timezones = p_timezones,
        gmt = p_gmt,
        idd_code = p_idd_code,
        mobile_number = p_mobile_number
        WHERE user_id = p_user_id;
       
        SET  @v_upd_row_count  := ROW_COUNT();
        IF ( @v_upd_row_count  > 0) THEN
   
            SET p_status = 1;
            SET p_message = 'Updated Successfully';
           
        ELSEIF (@v_upd_row_count = 0) THEN
            SET p_status = 2;
            SET p_message = 'Nothing to Update';
        ELSE
            SET p_status = 0;
            SET p_message = 'Error in Updating';
        END IF ;
    
END ;
END */$$
DELIMITER ;

/* Procedure structure for procedure `UpdateUserPassword` */

/*!50003 DROP PROCEDURE IF EXISTS  `UpdateUserPassword` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `UpdateUserPassword`(
         p_email_address    VARCHAR(100),
         p_old_password     VARCHAR(32),
         p_new_password     VARCHAR(32),
    OUT  p_status           INT,
    OUT  p_user_id          VARCHAR(25),
    OUT  p_client_id        VARCHAR(25),
    OUT  p_email            VARCHAR(100)
)
BEGIN
DECLARE v_email_address VARCHAR(100);
DECLARE v_password VARCHAR(32);
SELECT 0 INTO p_status;
	SELECT email_address,password INTO v_email_address, v_password FROM user_details WHERE email_address = p_email_address AND password = p_old_password;
	
	IF(v_email_address IS NULL) THEN
	
	SELECT 2 INTO p_status;
	
	ELSEIF (v_password = p_new_password) THEN
	
        SELECT 3 INTO p_status;
        
        ELSE
        
		UPDATE  user_details SET password = p_new_password WHERE email_address = p_email_address;
		
		SET @v_count = ROW_COUNT();
		
		IF  @v_count > 0
		
		THEN
			SELECT 1 INTO p_status;
			
			SELECT user_id, client_id, email_address INTO  p_user_id, p_client_id, p_email FROM user_details WHERE email_address = p_email_address AND password = p_new_password; 
		
                END IF;
	END IF;
END */$$
DELIMITER ;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
