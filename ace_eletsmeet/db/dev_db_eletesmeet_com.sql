-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jul 27, 2015 at 04:14 PM
-- Server version: 5.5.41-0ubuntu0.14.04.1
-- PHP Version: 5.5.9-1ubuntu4.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `dev_db_eletesmeet_com`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `AddPwdRequestDtm`(
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
                       
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `CancelSchedule`(
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
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `DeleteContactDetails`(
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
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GetCombinedContactList`(
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
        
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GetCombinedGroupName`(
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
        
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GetContactList`(
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
        
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GetRequestPwdDetails`(
   p_email_address     VARCHAR(100),
   OUT  p_timestamp      DATETIME
)
BEGIN
    SELECT request_datetime INTO p_timestamp FROM  password_request_details WHERE email_address = p_email_address; 
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GetScheduleReminderList`(
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
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GetUserDetailsByUserId`(
        p_user_id       VARCHAR(25)
)
BEGIN
              
    SELECT user_id, client_id, partner_id, email_address, password, nick_name, first_name, last_name, country_name, timezones, gmt, phone_number, idd_code, mobile_number, registration_dtm, status FROM user_details WHERE user_id = p_user_id;
        
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `InsertClientLicenseDetails`(
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
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `InsertClientSubscriptionMaster`(
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
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `InsertContactDetails`(
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
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `InsertInvitationDetails`(
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
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `InsertOrderDetails`(
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
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `InsertOrderMaster`(
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
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `InsertScheduleDetails`(
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
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `InsertSubscriptionMaster`(
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
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `InsertUserDetails`(
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
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `IsContactEmailExists`(
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
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `IsContactGroupExists`(
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
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `IsPartnerEmailExists`(
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
    
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `IsUserEmailExists`(
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
    
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `UpdateAdminPassword`(
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
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `UpdateClientConsumedSessions`(
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
        
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `UpdateClientPassword`(
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
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `UpdateConsumedSessions`(
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
        
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `UpdateContactDetails`(
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
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `UpdateEndSchedule`(
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
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `UpdateScheduleReminderList`(
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
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `UpdateScheduleStatus`(
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
    
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `UpdateUserDetails`(
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
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `UpdateUserPassword`(
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
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `admin_login`
--

CREATE TABLE IF NOT EXISTS `admin_login` (
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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `admin_login`
--

INSERT INTO `admin_login` (`admin_id`, `email_address`, `password`, `lastlogin_dtm`, `admin_creation_dtm`, `client_id`, `partner_id`, `status`, `flag`) VALUES
(1, 'mitesh.shah@quadridge.com', '928fd3b8720f50ddf7cb64a8fa8b83c6', '2015-07-21 02:33:42', '2014-10-29 14:33:31', 'cl00001', 'pr00001', '1', 'S'),
(2, 'althea.lopez@quadridge.com', '928fd3b8720f50ddf7cb64a8fa8b83c6', '2015-04-27 11:52:11', '2015-02-04 12:03:50', 'cl00001', 'pr00001', '1', 'CA');

-- --------------------------------------------------------

--
-- Table structure for table `billing_info`
--

CREATE TABLE IF NOT EXISTS `billing_info` (
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
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `client_contact_details`
--

CREATE TABLE IF NOT EXISTS `client_contact_details` (
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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=15 ;

--
-- Dumping data for table `client_contact_details`
--

INSERT INTO `client_contact_details` (`client_contact_id`, `contact_nick_name`, `contact_first_name`, `contact_last_name`, `contact_email_address`, `contact_idd_code`, `contact_mobile_number`, `contact_group_name`, `client_id`, `client_contact_status`) VALUES
(1, 'Mitesh Shah', 'Mitesh', 'Shah', 'mitesh.shah@quadridge.com', '91', '', 'Quadridge', 'cl00001', '1'),
(2, 'Sushrit Shrivatava', 'Sushrit', 'Shrivastava', 'sushrit.shrivastava@quadridge.com', '91', '', 'Quadridge', 'cl00001', '1'),
(3, 'Kiran Kulkarni', 'Kiran', 'Kulkarni', 'kiran@quadridge.com', '91', '', 'Quadridge', 'cl00001', '1'),
(4, 'Althea Lopez', 'Althea', 'Lopez', 'althea.lopez@quadridge.com', '91', '', 'Quadridge', 'cl00001', '1'),
(5, 'Nastassia Florindo', 'Nastassia', 'Florindo', 'nastassia.florindo@quadridge.com', '91', '', 'Quadridge', 'cl00001', '1'),
(6, 'Anirudha Khopade', 'Anirudha', 'Khopade', 'anirudha.khopade@quadridge.com', '91', '', 'Quadridge', 'cl00001', '1'),
(7, 'Pankaj Kumar', 'Pankaj', 'Kumar', 'pankaj@quadridge.com', '91', '', 'Quadridge', 'cl00001', '1'),
(8, 'Gopal Sirnaik', 'Gopal', 'Sirnaik', 'gopal.sirnaik@quadridge.com', '91', '', 'Quadridge', 'cl00001', '1'),
(9, 'Santosh Khaire', 'Santosh', 'Khaire', 'santosh.khaire@quadridge.com', '91', '', 'Quadridge', 'cl00001', '1'),
(10, 'Jayesh', 'Jayesh', 'Nishane', 'jnishane@in.ibm.com', '91', '9099002479', 'Adani', 'cl00008', '1'),
(11, 'Adani User1', 'Yogesh', 'Pandya', 'yogesh.pandya@in.ibm.com', '91', '9099005969', 'Adani', 'cl00008', '1'),
(12, 'Adani User3', 'Palak', 'Purohit', 'palpuroh@in.ibm.com', '91', '9099005644', 'Adani', 'cl00008', '1'),
(13, 'Adani User4', 'Kail', 'Shah', 'kailshah@in.ibm.com', '91', '9824047517', 'Adani', 'cl00008', '1'),
(14, 'Adani User5', 'Shalin', 'Shah', 'shalin.shah@in.ibm.com', '91', '9099055010', 'Adani', 'cl00008', '1');

-- --------------------------------------------------------

--
-- Table structure for table `client_details`
--

CREATE TABLE IF NOT EXISTS `client_details` (
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

--
-- Dumping data for table `client_details`
--

INSERT INTO `client_details` (`client_id`, `partner_id`, `client_name`, `client_logo_flag`, `client_logo_url`, `client_email_address`, `client_password`, `client_lastlogin_dtm`, `client_login_id`, `client_login_ip_address`, `client_creation_dtm`, `client_secret_key`, `auth_mode`, `auth_api_url`, `import_contact_url`, `rt_server_name`, `rt_server_salt`, `rt_server_api_url`, `logout_url`, `status`) VALUES
('cl00001', 'pr00001', 'Quadridge Technologies Pvt Ltd', '0', 'quadridge_tech_logo.png', 'mitesh.shah@quadridge.com', 'a76fa1bfbb8d88b76e2c76d951d9e9fd', '2015-07-15 05:57:43', '276e9cccf1897e9de8ac1f0df52f5da7', '49.248.5.250', '2015-06-10 16:48:31', 'L3tSm3e7', '0', NULL, NULL, 'http://conference.eletsmeet.com', 'cda9d43824a4828383833ae77dde40ef', '/bigbluebutton/api/', 'https://eletsmeet.com', '1'),
('cl00002', 'pr00001', 'Epic Television Networks Private Limited', '0', 'epic_logo.png', 'adsilva@epicchannel.com', '2e111e1357ee5a6ecc9095c05b916e84', '2015-07-08 08:57:16', 'ec8964bb5078969f0ff82010d803cccd', '49.248.5.250', '2015-05-26 12:24:53', NULL, '0', NULL, NULL, 'http://conference.eletsmeet.com', 'cda9d43824a4828383833ae77dde40ef', '/bigbluebutton/api/', 'https://eletsmeet.com', '1'),
('cl00003', 'pr00001', 'Muscat Consultants', '0', NULL, 'cjshah35@gmail.com', '2e111e1357ee5a6ecc9095c05b916e84', NULL, NULL, NULL, '2015-06-11 11:34:55', NULL, '0', NULL, NULL, 'http://conference.eletsmeet.com', 'cda9d43824a4828383833ae77dde40ef', '/bigbluebutton/api/', 'https://eletsmeet.com', '1'),
('cl00004', 'pr00001', 'The Times Group', '0', 'times_group_logo.jpg', 'manoj.dehankar@timesgroup.com', '2e111e1357ee5a6ecc9095c05b916e84', '2015-07-06 06:26:22', '53e35f0180ae32180c8bda92d54c8865', '49.248.5.250', '2015-06-11 11:45:24', NULL, '0', NULL, NULL, 'http://conference.eletsmeet.com', 'cda9d43824a4828383833ae77dde40ef', '/bigbluebutton/api/', 'https://eletsmeet.com', '1'),
('cl00005', 'pr00001', 'NewsRise Financial Research & Information Services LLP', '0', 'newsrise_logo.jpg', 'sumeet.nihalani@newsrise.org', '2e111e1357ee5a6ecc9095c05b916e84', '2015-07-15 05:00:05', '77152a2b3528ca87f39ee1cca29f431e', '49.248.5.250', '2015-06-11 12:04:03', NULL, '0', NULL, NULL, 'http://conference.eletsmeet.com', 'cda9d43824a4828383833ae77dde40ef', '/bigbluebutton/api/', 'https://eletsmeet.com', '1'),
('cl00006', 'pr00001', 'Singtel', '0', 'singtel_logo.png', 'krishnan@singtel.com', '2e111e1357ee5a6ecc9095c05b916e84', '2015-06-12 05:45:05', '1f0a2aefcb981be806442858d68072f4', '49.248.5.250', '2015-06-11 12:15:49', NULL, '0', NULL, NULL, 'http://conference.eletsmeet.com', 'cda9d43824a4828383833ae77dde40ef', '/bigbluebutton/api/', 'https://eletsmeet.com', '1'),
('cl00007', 'pr00001', 'labradogstudios.com', '0', NULL, 'zahir@labradogstudios.com', '2e111e1357ee5a6ecc9095c05b916e84', '2015-06-27 07:11:46', 'ecb156d189dd792af70bb9019235e701', '182.59.214.101', '2015-06-27 07:06:19', NULL, '0', NULL, NULL, 'http://conference.eletsmeet.com', 'cda9d43824a4828383833ae77dde40ef', '/bigbluebutton/api/', 'https://eletsmeet.com', '1'),
('cl00008', 'pr00001', 'Adani Enterprises Ltd', '1', 'adani_eletsmeet_com.png', 'jnishane@in.ibm.com', 'e0271b0517f3cb39f486c3a9c9c5e504', '2015-07-15 05:39:48', 'efeab3c40dcf75c748cd95b037dbf921', '117.239.35.226', '2015-07-09 06:29:40', NULL, '0', NULL, NULL, 'http://conference.eletsmeet.com', 'cda9d43824a4828383833ae77dde40ef', '/bigbluebutton/api/', 'http://adani.eletsmeet.com', '1'),
('cl00009', 'pr00001', 'AxSys Technology Ltd', '0', NULL, 'pradeep@axsys.co.uk', '66e704b9469873338291fc04b4a5ec15', '2015-07-14 13:59:32', '4a7e1bbd4ca4ed5186b0b471671bc2b2', '49.248.5.250', '2015-07-14 13:53:59', 'L3tSm3e7Ax5ys', '0', NULL, NULL, 'http://conference.eletsmeet.com', 'cda9d43824a4828383833ae77dde40ef', '/bigbluebutton/api/', 'https://eletsmeet.com', '1'),
('cl00010', 'pr00001', 'Prime Securities Ltd', '0', NULL, 'jakes@primesec.com', '2e111e1357ee5a6ecc9095c05b916e84', '2015-07-21 09:29:04', '5b7a2ade989fc689022dfad1459225db', '49.248.5.250', '2015-07-21 09:27:53', NULL, '0', NULL, NULL, 'http://conference.eletsmeet.com', 'cda9d43824a4828383833ae77dde40ef', '/bigbluebutton/api/', 'https://eletsmeet.com', '1');

-- --------------------------------------------------------

--
-- Table structure for table `client_license_details`
--

CREATE TABLE IF NOT EXISTS `client_license_details` (
  `license_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Unique row id of the records',
  `client_id` varchar(25) NOT NULL COMMENT 'Unique identification number of client',
  `no_of_license` int(10) DEFAULT '0' COMMENT 'No of license by Client',
  `operation_type` enum('0','1','2') DEFAULT '2' COMMENT '0 = License Added, 1= License Assigned, 2=License Disabled',
  `license_date` datetime NOT NULL COMMENT 'License date when will be license added assigend or disabled',
  PRIMARY KEY (`license_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=34 ;

--
-- Dumping data for table `client_license_details`
--

INSERT INTO `client_license_details` (`license_id`, `client_id`, `no_of_license`, `operation_type`, `license_date`) VALUES
(1, 'cl00001', 8, '0', '2015-06-10 16:48:31'),
(2, 'cl00001', 1, '1', '2015-06-12 05:52:49'),
(3, 'cl00001', 1, '1', '2015-06-12 05:55:16'),
(4, 'cl00001', 1, '1', '2015-06-12 05:56:31'),
(5, 'cl00001', 1, '1', '2015-06-12 05:57:50'),
(6, 'cl00001', 1, '1', '2015-06-12 05:58:38'),
(7, 'cl00001', 1, '1', '2015-06-12 05:59:25'),
(8, 'cl00001', 1, '1', '2015-06-12 06:00:57'),
(9, 'cl00001', 1, '1', '2015-06-12 06:01:53'),
(10, 'cl00002', 1, '0', '2015-05-26 12:25:53'),
(11, 'cl00002', 1, '1', '2015-05-26 12:27:29'),
(12, 'cl00007', 2, '0', '2015-06-27 07:06:37'),
(13, 'cl00007', 1, '1', '2015-06-27 07:14:37'),
(14, 'cl00007', 1, '1', '2015-06-27 07:16:59'),
(15, 'cl00004', 3, '0', '2015-07-06 06:21:58'),
(16, 'cl00004', 1, '1', '2015-07-06 07:16:29'),
(17, 'cl00004', 1, '1', '2015-07-06 07:17:47'),
(18, 'cl00004', 1, '1', '2015-07-06 07:18:37'),
(19, 'cl00001', 1, '0', '2015-07-08 11:20:36'),
(20, 'cl00001', 1, '1', '2015-07-08 11:29:29'),
(21, 'cl00008', 5, '0', '2015-07-09 06:30:05'),
(23, 'cl00008', 1, '1', '2015-07-13 04:53:03'),
(24, 'cl00008', 1, '1', '2015-07-13 05:23:04'),
(25, 'cl00008', 1, '1', '2015-07-13 05:24:30'),
(26, 'cl00008', 1, '1', '2015-07-13 05:26:01'),
(27, 'cl00008', 1, '1', '2015-07-13 05:27:13'),
(28, 'cl00009', 1, '0', '2015-07-14 13:57:33'),
(29, 'cl00009', 1, '1', '2015-07-14 14:04:33'),
(30, 'cl00005', 1, '0', '2015-07-15 05:12:27'),
(31, 'cl00005', 1, '1', '2015-07-15 05:39:12'),
(32, 'cl00010', 1, '0', '2015-07-21 09:28:06'),
(33, 'cl00010', 1, '1', '2015-07-21 09:30:49');

-- --------------------------------------------------------

--
-- Table structure for table `client_subscription_master`
--

CREATE TABLE IF NOT EXISTS `client_subscription_master` (
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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=24 ;

--
-- Dumping data for table `client_subscription_master`
--

INSERT INTO `client_subscription_master` (`client_subscription_id`, `client_id`, `subscription_date`, `subscription_start_date_gmt`, `subscription_end_date_gmt`, `subscription_start_date_local`, `subscription_end_date_local`, `subscription_status`, `order_id`, `plan_id`, `plan_name`, `plan_desc`, `plan_for`, `plan_type`, `number_of_sessions`, `number_of_mins_per_sessions`, `plan_period`, `number_of_invitee`, `meeting_recording`, `disk_space`, `is_free`, `plan_cost_inr`, `plan_cost_oth`, `concurrent_sessions`, `talk_time_mins`, `plan_keyword`, `autorenew_flag`, `consumed_number_of_sessions`, `consumed_talk_time_mins`) VALUES
(1, 'cl00001', '2015-06-12 06:20:33', '2015-06-12', '2015-12-09', '2015-06-12', '2015-12-09', '2', 'ord143409003311', 1, 'Quadridge LetsMeet', 'LetsMeet Default Plan for Quadridge Internal People', 'OTH', 'U', 0, 0, 180, 20, 'true', 0, '1', 0.00, 0.00, 0, NULL, NULL, '0', 0, 0),
(2, 'cl00001', '2015-06-12 06:27:53', '2015-06-12', '2015-12-09', '2015-06-12', '2015-12-09', '2', 'ord143409047311', 1, 'Quadridge LetsMeet', 'LetsMeet Default Plan for Quadridge Internal People', 'OTH', 'U', 0, 0, 180, 20, 'true', 0, '1', 0.00, 0.00, 0, NULL, NULL, '0', 0, 0),
(3, 'cl00001', '2015-06-12 06:28:08', '2015-06-12', '2015-12-09', '2015-06-12', '2015-12-09', '2', 'ord143409048811', 1, 'Quadridge LetsMeet', 'LetsMeet Default Plan for Quadridge Internal People', 'OTH', 'U', 0, 0, 180, 20, 'true', 0, '1', 0.00, 0.00, 0, NULL, NULL, '0', 0, 0),
(4, 'cl00001', '2015-06-12 06:28:29', '2015-06-12', '2015-12-09', '2015-06-12', '2015-12-09', '2', 'ord143409050911', 1, 'Quadridge LetsMeet', 'LetsMeet Default Plan for Quadridge Internal People', 'OTH', 'U', 0, 0, 180, 20, 'true', 0, '1', 0.00, 0.00, 0, NULL, NULL, '0', 0, 0),
(5, 'cl00001', '2015-06-12 06:28:44', '2015-06-12', '2015-12-09', '2015-06-12', '2015-12-09', '2', 'ord143409052411', 1, 'Quadridge LetsMeet', 'LetsMeet Default Plan for Quadridge Internal People', 'OTH', 'U', 0, 0, 180, 20, 'true', 0, '1', 0.00, 0.00, 0, NULL, NULL, '0', 0, 0),
(6, 'cl00001', '2015-06-12 06:28:56', '2015-06-12', '2015-12-09', '2015-06-12', '2015-12-09', '2', 'ord143409053611', 1, 'Quadridge LetsMeet', 'LetsMeet Default Plan for Quadridge Internal People', 'OTH', 'U', 0, 0, 180, 20, 'true', 0, '1', 0.00, 0.00, 0, NULL, NULL, '0', 0, 0),
(7, 'cl00001', '2015-06-12 06:29:24', '2015-06-12', '2015-12-09', '2015-06-12', '2015-12-09', '2', 'ord143409056411', 1, 'Quadridge LetsMeet', 'LetsMeet Default Plan for Quadridge Internal People', 'OTH', 'U', 0, 0, 180, 20, 'true', 0, '1', 0.00, 0.00, 0, NULL, NULL, '0', 0, 0),
(8, 'cl00002', '2015-05-26 12:29:08', '2015-05-26', '2015-11-30', '2015-05-26', '2015-11-30', '2', 'ord143516159521', 8, 'LetsMeet PRO', 'LetsMeet PRO', 'ENT', 'U', 0, 0, 180, 15, 'true', 0, '0', 4000.00, 0.00, 1, NULL, NULL, '0', 0, 0),
(9, 'cl00007', '2015-06-27 07:10:48', '2015-06-27', '2015-07-27', '2015-06-27', '2015-07-27', '2', 'ord143538904871', 7, 'LetsMeet LITE', 'LetsMeet LITE', 'ENT', 'U', 0, 0, 30, 5, 'true', 0, '0', 3000.00, 0.00, 1, NULL, NULL, '0', 0, 0),
(10, 'cl00007', '2015-06-27 07:11:01', '2015-06-27', '2015-07-27', '2015-06-27', '2015-07-27', '2', 'ord143538906171', 7, 'LetsMeet LITE', 'LetsMeet LITE', 'ENT', 'U', 0, 0, 30, 5, 'true', 0, '0', 3000.00, 0.00, 1, NULL, NULL, '0', 0, 0),
(11, 'cl00004', '2015-07-06 06:23:32', '2015-07-06', '2015-09-04', '2015-07-06', '2015-09-04', '2', 'ord143616381241', 8, 'LetsMeet PRO', 'LetsMeet PRO', 'ENT', 'U', 0, 0, 60, 15, 'true', 0, '0', 4000.00, 0.00, 1, NULL, NULL, '0', 0, 0),
(12, 'cl00004', '2015-07-06 06:23:47', '2015-07-06', '2015-09-04', '2015-07-06', '2015-09-04', '2', 'ord143616382741', 8, 'LetsMeet PRO', 'LetsMeet PRO', 'ENT', 'U', 0, 0, 60, 15, 'true', 0, '0', 4000.00, 0.00, 1, NULL, NULL, '0', 0, 0),
(13, 'cl00004', '2015-07-06 06:24:09', '2015-07-06', '2015-09-04', '2015-07-06', '2015-09-04', '2', 'ord143616384941', 8, 'LetsMeet PRO', 'LetsMeet PRO', 'ENT', 'U', 0, 0, 60, 15, 'true', 0, '0', 4000.00, 0.00, 1, NULL, NULL, '0', 0, 0),
(14, 'cl00001', '2015-07-08 11:25:24', '2015-07-08', '2015-07-23', '2015-07-08', '2015-07-23', '2', 'ord143635472411', 5, 'LetsMeet Trial 15', 'LetsMeet Trial Plan for 15days for Demo', 'ENT', 'S', 15, 0, 15, 5, 'false', 0, '1', 0.00, 0.00, 1, NULL, NULL, '0', 0, 0),
(15, 'cl00001', '2015-07-09 08:44:41', '2015-07-09', '2016-01-05', '2015-07-09', '2016-01-05', '2', 'ord143643148111', 1, 'Quadridge LetsMeet', 'LetsMeet Default Plan for Quadridge Internal People', 'OTH', 'U', 0, 0, 180, 20, 'true', 0, '1', 0.00, 0.00, 0, NULL, NULL, '0', 0, 0),
(16, 'cl00008', '2015-07-09 09:56:50', '2015-07-09', '2015-07-24', '2015-07-09', '2015-07-24', '2', 'ord143643581181', 6, 'LetsMeet Silver Trial 15', 'LetsMeet Silver Trial Plan for 15days for Demo', 'ENT', 'S', 15, 0, 15, 10, 'true', 0, '1', 0.00, 0.00, 1, NULL, NULL, '0', 1, 0),
(17, 'cl00008', '2015-07-09 09:57:04', '2015-07-09', '2015-07-24', '2015-07-09', '2015-07-24', '2', 'ord143643582481', 6, 'LetsMeet Silver Trial 15', 'LetsMeet Silver Trial Plan for 15days for Demo', 'ENT', 'S', 15, 0, 15, 10, 'true', 0, '1', 0.00, 0.00, 1, NULL, NULL, '0', 1, 0),
(18, 'cl00008', '2015-07-09 09:57:16', '2015-07-09', '2015-07-24', '2015-07-09', '2015-07-24', '2', 'ord143643583681', 6, 'LetsMeet Silver Trial 15', 'LetsMeet Silver Trial Plan for 15days for Demo', 'ENT', 'S', 15, 0, 15, 10, 'true', 0, '1', 0.00, 0.00, 1, NULL, NULL, '0', 0, 0),
(19, 'cl00008', '2015-07-09 09:57:43', '2015-07-09', '2015-07-24', '2015-07-09', '2015-07-24', '2', 'ord143643586381', 6, 'LetsMeet Silver Trial 15', 'LetsMeet Silver Trial Plan for 15days for Demo', 'ENT', 'S', 15, 0, 15, 10, 'true', 0, '1', 0.00, 0.00, 1, NULL, NULL, '0', 0, 0),
(20, 'cl00008', '2015-07-09 09:57:55', '2015-07-09', '2015-07-24', '2015-07-09', '2015-07-24', '2', 'ord143643587581', 6, 'LetsMeet Silver Trial 15', 'LetsMeet Silver Trial Plan for 15days for Demo', 'ENT', 'S', 15, 0, 15, 10, 'true', 0, '1', 0.00, 0.00, 1, NULL, NULL, '0', 0, 0),
(21, 'cl00009', '2015-07-14 13:58:59', '2015-07-14', '2015-08-13', '2015-07-14', '2015-08-13', '2', 'ord143688233991', 7, 'LetsMeet LITE', 'LetsMeet LITE', 'ENT', 'U', 0, 0, 30, 5, 'true', 0, '0', 3000.00, 0.00, 1, NULL, NULL, '0', 0, 0),
(22, 'cl00005', '2015-07-15 05:13:59', '2015-07-15', '2016-07-14', '2015-07-15', '2016-07-14', '2', 'ord143693723951', 8, 'LetsMeet PRO', 'LetsMeet PRO', 'ENT', 'U', 0, 0, 360, 15, 'true', 0, '0', 4000.00, 0.00, 1, NULL, NULL, '0', 0, 0),
(23, 'cl00010', '2015-07-21 09:28:21', '2015-07-21', '2015-08-20', '2015-07-21', '2015-08-20', '2', 'ord1437470901102', 8, 'LetsMeet PRO', 'LetsMeet PRO', 'ENT', 'U', 0, 0, 30, 15, 'true', 0, '0', 4000.00, 0.00, 1, NULL, NULL, '0', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `country_details`
--

CREATE TABLE IF NOT EXISTS `country_details` (
  `country_id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'country id autoincrement field',
  `country_name` varchar(150) NOT NULL COMMENT 'Country name',
  `country_code` varchar(15) NOT NULL COMMENT 'Country code in 2 letters',
  `country_idd_code` varchar(10) NOT NULL COMMENT 'IDD code of country',
  `country_status` enum('0','1') NOT NULL DEFAULT '1' COMMENT '1 for Active, 0 for Deactive',
  PRIMARY KEY (`country_id`),
  KEY `Index_country_details_countrty_code` (`country_code`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=234 ;

--
-- Dumping data for table `country_details`
--

INSERT INTO `country_details` (`country_id`, `country_name`, `country_code`, `country_idd_code`, `country_status`) VALUES
(1, 'Andorra', 'AD', '376', '1'),
(2, 'Afghanistan', 'AF', '93', '1'),
(3, 'Albania', 'AL', '355', '1'),
(4, 'Algeria', 'DZ', '213', '1'),
(5, 'American Samoa', 'AS', '1684', '1'),
(6, 'Angola', 'AO', '244', '1'),
(7, 'Anguilla', 'AI', '1264', '1'),
(8, 'Antarctica', 'AQ', '672', '1'),
(9, 'Antigua and Barbuda', 'AG', '1268', '1'),
(10, 'Argentina', 'AR', '54', '1'),
(11, 'Armenia', 'AM', '374', '1'),
(12, 'Aruba', 'AW', '297', '1'),
(13, 'Australia', 'AU', '61', '1'),
(14, 'Austria', 'AT', '43', '1'),
(15, 'Azerbaijan', 'AZ', '994', '1'),
(16, 'Bahamas', 'BS', '1242', '1'),
(17, 'Bahrain', 'BH', '973', '1'),
(18, 'Bangladesh', 'BD', '880', '1'),
(19, 'Barbados', 'BB', '1246', '1'),
(20, 'Belarus', 'BY', '375', '1'),
(21, 'Belgium', 'BE', '32', '1'),
(22, 'Belize', 'BZ', '501', '1'),
(23, 'Benin', 'BJ', '229', '1'),
(24, 'Bermuda', 'BM', '1441', '1'),
(25, 'Bhutan', 'BT', '975', '1'),
(26, 'Bolivia', 'BO', '591', '1'),
(27, 'Bosnia and Herzegovina', 'BA', '387', '1'),
(28, 'Botswana', 'BW', '267', '1'),
(29, 'Brazil', 'BR', '55', '1'),
(30, 'British Indian Ocean Territory', 'IO', '246', '1'),
(31, 'British Virgin Islands', 'VG', '1284', '1'),
(32, 'Brunei', 'BN', '673', '1'),
(33, 'Bulgaria', 'BG', '359', '1'),
(34, 'Burkina Faso', 'BF', '226', '1'),
(35, 'Burma (Myanmar)', 'MM', '95', '1'),
(36, 'Burundi', 'BI', '257', '1'),
(37, 'Cambodia', 'KH', '855', '1'),
(38, 'Cameroon', 'CM', '237', '1'),
(39, 'Canada', 'CA', '1', '1'),
(40, 'Cape Verde', 'CV', '238', '1'),
(41, 'Cayman Islands', 'KY', '1345', '1'),
(42, 'Central African Republic', 'CF', '236', '1'),
(43, 'Chad', 'TD', '235', '1'),
(44, 'Chile', 'CL', '56', '1'),
(45, 'China', 'CN', '86', '1'),
(46, 'Christmas Island', 'CX', '61', '1'),
(47, 'Cocos (Keeling) Islands', 'CC', '61', '1'),
(48, 'Colombia', 'CO', '57', '1'),
(49, 'Comoros', 'KM', '269', '1'),
(50, 'Cook Islands', 'CK', '682', '1'),
(51, 'Costa Rica', 'CR', '506', '1'),
(52, 'Croatia', 'HR', '385', '1'),
(53, 'Cuba', 'CU', '53', '1'),
(54, 'Cyprus', 'CY', '357', '1'),
(55, 'Czech Republic', 'CZ', '420', '1'),
(56, 'Democratic Republic of the Congo', 'CD', '243', '1'),
(57, 'Denmark', 'DK', '45', '1'),
(58, 'Djibouti', 'DJ', '253', '1'),
(59, 'Dominica', 'DM', '1767', '1'),
(60, 'Dominican Republic', 'DO', '1809', '1'),
(61, 'Ecuador', 'EC', '593', '1'),
(62, 'Egypt', 'EG', '20', '1'),
(63, 'El Salvador', 'SV', '503', '1'),
(64, 'Equatorial Guinea', 'GQ', '240', '1'),
(65, 'Eritrea', 'ER', '291', '1'),
(66, 'Estonia', 'EE', '372', '1'),
(67, 'Ethiopia', 'ET', '251', '1'),
(68, 'Falkland Islands', 'FK', '500', '1'),
(69, 'Faroe Islands', 'FO', '298', '1'),
(70, 'Fiji', 'FJ', '679', '1'),
(71, 'Finland', 'FI', '358', '1'),
(72, 'France', 'FR', '33', '1'),
(73, 'French Polynesia', 'PF', '689', '1'),
(74, 'Gabon', 'GA', '241', '1'),
(75, 'Gambia', 'GM', '220', '1'),
(76, 'Georgia', 'GE', '995', '1'),
(77, 'Germany', 'DE', '49', '1'),
(78, 'Ghana', 'GH', '233', '1'),
(79, 'Gibraltar', 'GI', '350', '1'),
(80, 'Greece', 'GR', '30', '1'),
(81, 'Greenland', 'GL', '299', '1'),
(82, 'Grenada', 'GD', '1473', '1'),
(83, 'Guam', 'GU', '1671', '1'),
(84, 'Guatemala', 'GT', '502', '1'),
(85, 'Guinea', 'GN', '224', '1'),
(86, 'Guinea-Bissau', 'GW', '245', '1'),
(87, 'Guyana', 'GY', '592', '1'),
(88, 'Haiti', 'HT', '509', '1'),
(89, 'Holy See (Vatican City)', 'VA', '39', '1'),
(90, 'Honduras', 'HN', '504', '1'),
(91, 'Hong Kong', 'HK', '852', '1'),
(92, 'Hungary', 'HU', '36', '1'),
(93, 'Iceland', 'IS', '354', '1'),
(94, 'India', 'IN', '91', '1'),
(95, 'Indonesia', 'ID', '62', '1'),
(96, 'Iran', 'IR', '98', '1'),
(97, 'Iraq', 'IQ', '964', '1'),
(98, 'Ireland', 'IE', '353', '1'),
(99, 'Isle of Man', 'IM', '44', '1'),
(100, 'Israel', 'IL', '972', '1'),
(101, 'Italy', 'IT', '39', '1'),
(102, 'Ivory Coast', 'CI', '225', '1'),
(103, 'Jamaica', 'JM', '1876', '1'),
(104, 'Japan', 'JP', '81', '1'),
(105, 'Jersey', 'JE', '44', '1'),
(106, 'Jordan', 'JO', '962', '1'),
(107, 'Kazakhstan', 'KZ', '7', '1'),
(108, 'Kenya', 'KE', '254', '1'),
(109, 'Kiribati', 'KI', '686', '1'),
(110, 'Kuwait', 'KW', '965', '1'),
(111, 'Kyrgyzstan', 'KG', '996', '1'),
(112, 'Laos', 'LA', '856', '1'),
(113, 'Latvia', 'LV', '371', '1'),
(114, 'Lebanon', 'LB', '961', '1'),
(115, 'Lesotho', 'LS', '266', '1'),
(116, 'Liberia', 'LR', '231', '1'),
(117, 'Libya', 'LY', '218', '1'),
(118, 'Liechtenstein', 'LI', '423', '1'),
(119, 'Lithuania', 'LT', '370', '1'),
(120, 'Luxembourg', 'LU', '352', '1'),
(121, 'Macau', 'MO', '853', '1'),
(122, 'Macedonia', 'MK', '389', '1'),
(123, 'Madagascar', 'MG', '261', '1'),
(124, 'Malawi', 'MW', '265', '1'),
(125, 'Malaysia', 'MY', '60', '1'),
(126, 'Maldives', 'MV', '960', '1'),
(127, 'Mali', 'ML', '223', '1'),
(128, 'Malta', 'MT', '356', '1'),
(129, 'Marshall Islands', 'MH', '692', '1'),
(130, 'Mauritania', 'MR', '222', '1'),
(131, 'Mauritius', 'MU', '230', '1'),
(132, 'Mayotte', 'YT', '262', '1'),
(133, 'Mexico', 'MX', '52', '1'),
(134, 'Micronesia', 'FM', '691', '1'),
(135, 'Moldova', 'MD', '373', '1'),
(136, 'Monaco', 'MC', '377', '1'),
(137, 'Mongolia', 'MN', '976', '1'),
(138, 'Montenegro', 'ME', '382', '1'),
(139, 'Montserrat', 'MS', '1664', '1'),
(140, 'Morocco', 'MA', '212', '1'),
(141, 'Mozambique', 'MZ', '258', '1'),
(142, 'Namibia', 'NA', '264', '1'),
(143, 'Nauru', 'NR', '674', '1'),
(144, 'Nepal', 'NP', '977', '1'),
(145, 'Netherlands', 'NL', '31', '1'),
(146, 'Netherlands Antilles', 'AN', '599', '1'),
(147, 'New Caledonia', 'NC', '687', '1'),
(148, 'New Zealand', 'NZ', '64', '1'),
(149, 'Nicaragua', 'NI', '505', '1'),
(150, 'Niger', 'NE', '227', '1'),
(151, 'Nigeria', 'NG', '234', '1'),
(152, 'Niue', 'NU', '683', '1'),
(153, 'North Korea', 'KP', '850', '1'),
(154, 'Northern Mariana Islands', 'MP', '1670', '1'),
(155, 'Norway', 'NO', '47', '1'),
(156, 'Oman', 'OM', '968', '1'),
(157, 'Pakistan', 'PK', '92', '1'),
(158, 'Palau', 'PW', '680', '1'),
(159, 'Panama', 'PA', '507', '1'),
(160, 'Papua New Guinea', 'PG', '675', '1'),
(161, 'Paraguay', 'PY', '595', '1'),
(162, 'Peru', 'PE', '51', '1'),
(163, 'Philippines', 'PH', '63', '1'),
(164, 'Pitcairn Islands', 'PN', '870', '1'),
(165, 'Poland', 'PL', '48', '1'),
(166, 'Portugal', 'PT', '351', '1'),
(167, 'Puerto Rico', 'PR', '1', '1'),
(168, 'Qatar', 'QA', '974', '1'),
(169, 'Republic of the Congo', 'CG', '242', '1'),
(170, 'Romania', 'RO', '40', '1'),
(171, 'Russia', 'RU', '7', '1'),
(172, 'Rwanda', 'RW', '250', '1'),
(173, 'Saint Barthelemy', 'BL', '590', '1'),
(174, 'Saint Helena', 'SH', '290', '1'),
(175, 'Saint Kitts and Nevis', 'KN', '1869', '1'),
(176, 'Saint Lucia', 'LC', '1758', '1'),
(177, 'Saint Martin', 'MF', '1599', '1'),
(178, 'Saint Pierre and Miquelon', 'PM', '508', '1'),
(179, 'Saint Vincent and the Grenadines', 'VC', '1784', '1'),
(180, 'Samoa', 'WS', '685', '1'),
(181, 'San Marino', 'SM', '378', '1'),
(182, 'Sao Tome and Principe', 'ST', '239', '1'),
(183, 'Saudi Arabia', 'SA', '966', '1'),
(184, 'Senegal', 'SN', '221', '1'),
(185, 'Serbia', 'RS', '381', '1'),
(186, 'Seychelles', 'SC', '248', '1'),
(187, 'Sierra Leone', 'SL', '232', '1'),
(188, 'Singapore', 'SG', '65', '1'),
(189, 'Slovakia', 'SK', '421', '1'),
(190, 'Slovenia', 'SI', '386', '1'),
(191, 'Solomon Islands', 'SB', '677', '1'),
(192, 'Somalia', 'SO', '252', '1'),
(193, 'South Africa', 'ZA', '27', '1'),
(194, 'South Korea', 'KR', '82', '1'),
(195, 'Spain', 'ES', '34', '1'),
(196, 'Sri Lanka', 'LK', '94', '1'),
(197, 'Sudan', 'SD', '249', '1'),
(198, 'Suriname', 'SR', '597', '1'),
(199, 'Svalbard', 'SJ', '47', '1'),
(200, 'Swaziland', 'SZ', '268', '1'),
(201, 'Sweden', 'SE', '46', '1'),
(202, 'Switzerland', 'CH', '41', '1'),
(203, 'Syria', 'SY', '963', '1'),
(204, 'Taiwan', 'TW', '886', '1'),
(205, 'Tajikistan', 'TJ', '992', '1'),
(206, 'Tanzania', 'TZ', '255', '1'),
(207, 'Thailand', 'TH', '66', '1'),
(208, 'Timor-Leste', 'TL', '670', '1'),
(209, 'Togo', 'TG', '228', '1'),
(210, 'Tokelau', 'TK', '690', '1'),
(211, 'Tonga', 'TO', '676', '1'),
(212, 'Trinidad and Tobago', 'TT', '1868', '1'),
(213, 'Tunisia', 'TN', '216', '1'),
(214, 'Turkey', 'TR', '90', '1'),
(215, 'Turkmenistan', 'TM', '993', '1'),
(216, 'Turks and Caicos Islands', 'TC', '1649', '1'),
(217, 'Tuvalu', 'TV', '688', '1'),
(218, 'Uganda', 'UG', '256', '1'),
(219, 'Ukraine', 'UA', '380', '1'),
(220, 'United Arab Emirates', 'AE', '971', '1'),
(221, 'United Kingdom', 'UK', '44', '1'),
(222, 'United States', 'US', '1', '1'),
(223, 'Uruguay', 'UY', '598', '1'),
(224, 'US Virgin Islands', 'VI', '1340', '1'),
(225, 'Uzbekistan', 'UZ', '998', '1'),
(226, 'Vanuatu', 'VU', '678', '1'),
(227, 'Venezuela', 'VE', '58', '1'),
(228, 'Vietnam', 'VN', '84', '1'),
(229, 'Wallis and Futuna', 'WF', '681', '1'),
(230, 'Western Sahara', 'EH', '212', '1'),
(231, 'Yemen', 'YE', '967', '1'),
(232, 'Zambia', 'ZM', '260', '1'),
(233, 'Zimbabwe', 'ZW', '263', '1');

-- --------------------------------------------------------

--
-- Table structure for table `country_timezones`
--

CREATE TABLE IF NOT EXISTS `country_timezones` (
  `ct_id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'country id autoincrement field',
  `country_code` varchar(15) NOT NULL COMMENT 'Country code in 2 letters',
  `timezones` varchar(100) NOT NULL COMMENT 'Timezones of country',
  `gmt` varchar(20) DEFAULT NULL COMMENT 'Country GMT details',
  `ct_status` enum('0','1') NOT NULL DEFAULT '1' COMMENT '1 for Active, 0 for Deactive',
  PRIMARY KEY (`ct_id`),
  KEY `Index_country_timezones_countrty_code` (`country_code`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=398 ;

--
-- Dumping data for table `country_timezones`
--

INSERT INTO `country_timezones` (`ct_id`, `country_code`, `timezones`, `gmt`, `ct_status`) VALUES
(1, 'AD', 'Europe/Andorra', '+02:00', '1'),
(2, 'AF', 'Asia/Kabul', '+04:30', '1'),
(3, 'AL', 'Europe/Tirane', '+02:00', '1'),
(4, 'DZ', 'Africa/Algiers', '+01:00', '1'),
(5, 'AS', 'Pacific/Pago_Pago', '-11:00', '1'),
(6, 'AO', 'Africa/Luanda', '+01:00', '1'),
(7, 'AI', 'America/Anguilla', '-04:00', '1'),
(8, 'AQ', 'Antarctica/Casey', '+08:00', '1'),
(9, 'AQ', 'Antarctica/Davis', '+07:00', '1'),
(10, 'AQ', 'Antarctica/DumontDUrville', '+10:00', '1'),
(11, 'AQ', 'Antarctica/Macquarie', '+11:00', '1'),
(12, 'AQ', 'Antarctica/Mawson', '+05:00', '1'),
(13, 'AQ', 'Antarctica/McMurdo', '+12:00', '1'),
(14, 'AQ', 'Antarctica/Palmer', '-04:00', '1'),
(15, 'AQ', 'Antarctica/Rothera', '-03:00', '1'),
(16, 'AQ', 'Antarctica/South_Pole', '+12:00', '1'),
(17, 'AQ', 'Antarctica/Syowa', '+03:00', '1'),
(18, 'AQ', 'Antarctica/Vostok', '+06:00', '1'),
(19, 'AG', 'America/Antigua', '-04:00', '1'),
(20, 'AR', 'America/Argentina/Buenos_Aires', '-03:00', '1'),
(21, 'AR', 'America/Argentina/Catamarca', '-03:00', '1'),
(22, 'AR', 'America/Argentina/Cordoba', '-03:00', '1'),
(23, 'AR', 'America/Argentina/Jujuy', '-03:00', '1'),
(24, 'AR', 'America/Argentina/La_Rioja', '-03:00', '1'),
(25, 'AR', 'America/Argentina/Mendoza', '-03:00', '1'),
(26, 'AR', 'America/Argentina/Rio_Gallegos', '-03:00', '1'),
(27, 'AR', 'America/Argentina/Salta', '-03:00', '1'),
(28, 'AR', 'America/Argentina/San_Juan', '-03:00', '1'),
(29, 'AR', 'America/Argentina/San_Luis', '-03:00', '1'),
(30, 'AR', 'America/Argentina/Tucuman', '-03:00', '1'),
(31, 'AR', 'America/Argentina/Ushuaia', '-03:00', '1'),
(32, 'AM', 'Asia/Yerevan', '+04:00', '1'),
(33, 'AW', 'America/Aruba', '-04:00', '1'),
(34, 'AU', 'Australia/Adelaide', '+09:30', '1'),
(35, 'AU', 'Australia/Brisbane', '+10:00', '1'),
(36, 'AU', 'Australia/Broken_Hill', '+09:30', '1'),
(37, 'AU', 'Australia/Currie', '+10:00', '1'),
(38, 'AU', 'Australia/Darwin', '+09:30', '1'),
(39, 'AU', 'Australia/Eucla', '+08:45', '1'),
(40, 'AU', 'Australia/Hobart', '+10:00', '1'),
(41, 'AU', 'Australia/Lindeman', '+10:00', '1'),
(42, 'AU', 'Australia/Lord_Howe', '+10:30', '1'),
(43, 'AU', 'Australia/Melbourne', '+10:00', '1'),
(44, 'AU', 'Australia/Perth', '+08:00', '1'),
(45, 'AU', 'Australia/Sydney', '+10:00', '1'),
(46, 'AT', 'Europe/Vienna', '+02:00', '1'),
(47, 'AZ', 'Asia/Baku', '+05:00', '1'),
(48, 'BS', 'America/Nassau', '-04:00', '1'),
(49, 'BH', 'Asia/Bahrain', '+03:00', '1'),
(50, 'BD', 'Asia/Dhaka', '+06:00', '1'),
(51, 'BB', 'America/Barbados', '-04:00', '1'),
(52, 'BY', 'Europe/Minsk', '+03:00', '1'),
(53, 'BE', 'Europe/Brussels', '+02:00', '1'),
(54, 'BZ', 'America/Belize', '-06:00', '1'),
(55, 'BJ', 'Africa/Porto-Novo', '+01:00', '1'),
(56, 'BM', 'Atlantic/Bermuda', '-03:00', '1'),
(57, 'BT', 'Asia/Thimphu', '+06:00', '1'),
(58, 'BO', 'America/La_Paz', '-04:00', '1'),
(59, 'BA', 'Europe/Sarajevo', '+02:00', '1'),
(60, 'BW', 'Africa/Gaborone', '+02:00', '1'),
(61, 'BR', 'America/Araguaina', '-03:00', '1'),
(62, 'BR', 'America/Bahia', '-03:00', '1'),
(63, 'BR', 'America/Belem', '-03:00', '1'),
(64, 'BR', 'America/Boa_Vista', '-04:00', '1'),
(65, 'BR', 'America/Campo_Grande', '-04:00', '1'),
(66, 'BR', 'America/Cuiaba', '-04:00', '1'),
(67, 'BR', 'America/Eirunepe', '-04:00', '1'),
(68, 'BR', 'America/Fortaleza', '-03:00', '1'),
(69, 'BR', 'America/Maceio', '-03:00', '1'),
(70, 'BR', 'America/Manaus', '-04:00', '1'),
(71, 'BR', 'America/Noronha', '-02:00', '1'),
(72, 'BR', 'America/Porto_Velho', '-04:00', '1'),
(73, 'BR', 'America/Recife', '-03:00', '1'),
(74, 'BR', 'America/Rio_Branco', '-04:00', '1'),
(75, 'BR', 'America/Santarem', '-03:00', '1'),
(76, 'BR', 'America/Sao_Paulo', '-03:00', '1'),
(77, 'IO', 'Indian/Chagos', '+06:00', '1'),
(78, 'VG', 'America/Tortola', '-04:00', '1'),
(79, 'BN', 'Asia/Brunei', '+08:00', '1'),
(80, 'BG', 'Europe/Sofia', '+03:00', '1'),
(81, 'BF', 'Africa/Ouagadougou', '+00:00', '1'),
(82, 'MM', 'Asia/Rangoon', '+06:30', '1'),
(83, 'BI', 'Africa/Bujumbura', '+02:00', '1'),
(84, 'KH', 'Asia/Phnom_Penh', '+07:00', '1'),
(85, 'CM', 'Africa/Douala', '+01:00', '1'),
(86, 'CA', 'America/Atikokan', '-05:00', '1'),
(87, 'CA', 'America/Blanc-Sablon', '-04:00', '1'),
(88, 'CA', 'America/Cambridge_Bay', '-06:00', '1'),
(89, 'CA', 'America/Creston', '-07:00', '1'),
(90, 'CA', 'America/Dawson', '-07:00', '1'),
(91, 'CA', 'America/Dawson_Creek', '-07:00', '1'),
(92, 'CA', 'America/Edmonton', '-06:00', '1'),
(93, 'CA', 'America/Glace_Bay', '-03:00', '1'),
(94, 'CA', 'America/Goose_Bay', '-03:00', '1'),
(95, 'CA', 'America/Halifax', '-03:00', '1'),
(96, 'CA', 'America/Inuvik', '-06:00', '1'),
(97, 'CA', 'America/Iqaluit', '-04:00', '1'),
(98, 'CA', 'America/Moncton', '-03:00', '1'),
(99, 'CA', 'America/Montreal', '-04:00', '1'),
(100, 'CA', 'America/Nipigon', '-04:00', '1'),
(101, 'CA', 'America/Pangnirtung', '-04:00', '1'),
(102, 'CA', 'America/Rainy_River', '-05:00', '1'),
(103, 'CA', 'America/Rankin_Inlet', '-05:00', '1'),
(104, 'CA', 'America/Regina', '-06:00', '1'),
(105, 'CA', 'America/Resolute', '-05:00', '1'),
(106, 'CA', 'America/St_Johns', '-02:30', '1'),
(107, 'CA', 'America/Swift_Current', '-06:00', '1'),
(108, 'CA', 'America/Thunder_Bay', '-04:00', '1'),
(109, 'CA', 'America/Toronto', '-04:00', '1'),
(110, 'CA', 'America/Vancouver', '-07:00', '1'),
(111, 'CA', 'America/Whitehorse', '-07:00', '1'),
(112, 'CA', 'America/Winnipeg', '-05:00', '1'),
(113, 'CA', 'America/Yellowknife', '-06:00', '1'),
(114, 'CV', 'Atlantic/Cape_Verde', '-01:00', '1'),
(115, 'KY', 'America/Cayman', '-05:00', '1'),
(116, 'CF', 'Africa/Bangui', '+01:00', '1'),
(117, 'TD', 'Africa/Ndjamena', '+01:00', '1'),
(118, 'CL', 'America/Santiago', '-04:00', '1'),
(119, 'CL', 'Pacific/Easter', '-06:00', '1'),
(120, 'CN', 'Asia/Chongqing', '+08:00', '1'),
(121, 'CN', 'Asia/Harbin', '+08:00', '1'),
(122, 'CN', 'Asia/Kashgar', '+08:00', '1'),
(123, 'CN', 'Asia/Shanghai', '+08:00', '1'),
(124, 'CN', 'Asia/Urumqi', '+08:00', '1'),
(125, 'CX', 'Indian/Christmas', '+07:00', '1'),
(126, 'CC', 'Indian/Cocos', '+06:30', '1'),
(127, 'CO', 'America/Bogota', '-05:00', '1'),
(128, 'KM', 'Indian/Comoro', '+03:00', '1'),
(129, 'CK', 'Pacific/Rarotonga', '-10:00', '1'),
(130, 'CR', 'America/Costa_Rica', '-06:00', '1'),
(131, 'HR', 'Europe/Zagreb', '+02:00', '1'),
(132, 'CU', 'America/Havana', '-04:00', '1'),
(133, 'CY', 'Asia/Nicosia', '+03:00', '1'),
(134, 'CZ', 'Europe/Prague', '+02:00', '1'),
(135, 'CD', 'Africa/Kinshasa', '+01:00', '1'),
(136, 'CD', 'Africa/Lubumbashi', '+02:00', '1'),
(137, 'DK', 'Europe/Copenhagen', '+02:00', '1'),
(138, 'DJ', 'Africa/Djibouti', '+03:00', '1'),
(139, 'DM', 'America/Dominica', '-04:00', '1'),
(140, 'DO', 'America/Santo_Domingo', '-04:00', '1'),
(141, 'EC', 'America/Guayaquil', '-05:00', '1'),
(142, 'EC', 'Pacific/Galapagos', '-06:00', '1'),
(143, 'EG', 'Africa/Cairo', '+02:00', '1'),
(144, 'SV', 'America/El_Salvador', '-06:00', '1'),
(145, 'GQ', 'Africa/Malabo', '+01:00', '1'),
(146, 'ER', 'Africa/Asmara', '+03:00', '1'),
(147, 'EE', 'Europe/Tallinn', '+03:00', '1'),
(148, 'ET', 'Africa/Addis_Ababa', '+03:00', '1'),
(149, 'FK', 'Atlantic/Stanley', '-03:00', '1'),
(150, 'FO', 'Atlantic/Faroe', '+01:00', '1'),
(151, 'FJ', 'Pacific/Fiji', '+12:00', '1'),
(152, 'FI', 'Europe/Helsinki', '+03:00', '1'),
(153, 'FR', 'Europe/Paris', '+02:00', '1'),
(154, 'PF', 'Pacific/Gambier', '-09:00', '1'),
(155, 'PF', 'Pacific/Marquesas', '-09:30', '1'),
(156, 'PF', 'Pacific/Tahiti', '-10:00', '1'),
(157, 'GA', 'Africa/Libreville', '+01:00', '1'),
(158, 'GM', 'Africa/Banjul', '+00:00', '1'),
(159, 'GE', 'Asia/Tbilisi', '+04:00', '1'),
(160, 'DE', 'Europe/Berlin', '+02:00', '1'),
(161, 'GH', 'Africa/Accra', '+00:00', '1'),
(162, 'GI', 'Europe/Gibraltar', '+02:00', '1'),
(163, 'GR', 'Europe/Athens', '+03:00', '1'),
(164, 'GL', 'America/Danmarkshavn', '+00:00', '1'),
(165, 'GL', 'America/Godthab', '-02:00', '1'),
(166, 'GL', 'America/Scoresbysund', '+00:00', '1'),
(167, 'GL', 'America/Thule', '-03:00', '1'),
(168, 'GD', 'America/Grenada', '-04:00', '1'),
(169, 'GU', 'Pacific/Guam', '+10:00', '1'),
(170, 'GT', 'America/Guatemala', '-06:00', '1'),
(171, 'GN', 'Africa/Conakry', '+00:00', '1'),
(172, 'GW', 'Africa/Bissau', '+00:00', '1'),
(173, 'GY', 'America/Guyana', '-04:00', '1'),
(174, 'HT', 'America/Port-au-Prince', '-05:00', '1'),
(175, 'VA', 'Europe/Vatican', '+02:00', '1'),
(176, 'HN', 'America/Tegucigalpa', '-06:00', '1'),
(177, 'HK', 'Asia/Hong_Kong', '+08:00', '1'),
(178, 'HU', 'Europe/Budapest', '+02:00', '1'),
(179, 'IS', 'Atlantic/Reykjavik', '+00:00', '1'),
(180, 'IN', 'Asia/Kolkata', '+05:30', '1'),
(181, 'ID', 'Asia/Jakarta', '+07:00', '1'),
(182, 'ID', 'Asia/Jayapura', '+09:00', '1'),
(183, 'ID', 'Asia/Makassar', '+08:00', '1'),
(184, 'ID', 'Asia/Pontianak', '+07:00', '1'),
(185, 'IR', 'Asia/Tehran', '+04:30', '1'),
(186, 'IQ', 'Asia/Baghdad', '+03:00', '1'),
(187, 'IE', 'Europe/Dublin', '+01:00', '1'),
(188, 'IM', 'Europe/Isle_of_Man', '+01:00', '1'),
(189, 'IL', 'Asia/Jerusalem', '+03:00', '1'),
(190, 'IT', 'Europe/Rome', '+02:00', '1'),
(191, 'CI', 'Africa/Abidjan', '+00:00', '1'),
(192, 'JM', 'America/Jamaica', '-05:00', '1'),
(193, 'JP', 'Asia/Tokyo', '+09:00', '1'),
(194, 'JE', 'Europe/Jersey', '+01:00', '1'),
(195, 'JO', 'Asia/Amman', '+03:00', '1'),
(196, 'KZ', 'Asia/Almaty', '+06:00', '1'),
(197, 'KZ', 'Asia/Aqtau', '+05:00', '1'),
(198, 'KZ', 'Asia/Aqtobe', '+05:00', '1'),
(199, 'KZ', 'Asia/Oral', '+05:00', '1'),
(200, 'KZ', 'Asia/Qyzylorda', '+06:00', '1'),
(201, 'KE', 'Africa/Nairobi', '+03:00', '1'),
(202, 'KI', 'Pacific/Enderbury', '+13:00', '1'),
(203, 'KI', 'Pacific/Kiritimati', '+14:00', '1'),
(204, 'KI', 'Pacific/Tarawa', '+12:00', '1'),
(205, 'KW', 'Asia/Kuwait', '+03:00', '1'),
(206, 'KG', 'Asia/Bishkek', '+06:00', '1'),
(207, 'LA', 'Asia/Vientiane', '+07:00', '1'),
(208, 'LV', 'Europe/Riga', '+03:00', '1'),
(209, 'LB', 'Asia/Beirut', '+03:00', '1'),
(210, 'LS', 'Africa/Maseru', '+02:00', '1'),
(211, 'LR', 'Africa/Monrovia', '+00:00', '1'),
(212, 'LY', 'Africa/Tripoli', '+02:00', '1'),
(213, 'LI', 'Europe/Vaduz', '+02:00', '1'),
(214, 'LT', 'Europe/Vilnius', '+03:00', '1'),
(215, 'LU', 'Europe/Luxembourg', '+02:00', '1'),
(216, 'MO', 'Asia/Macau', '+08:00', '1'),
(217, 'MK', 'Europe/Skopje', '+02:00', '1'),
(218, 'MG', 'Indian/Antananarivo', '+03:00', '1'),
(219, 'MW', 'Africa/Blantyre', '+02:00', '1'),
(220, 'MY', 'Asia/Kuala_Lumpur', '+08:00', '1'),
(221, 'MY', 'Asia/Kuching', '+08:00', '1'),
(222, 'MV', 'Indian/Maldives', '+05:00', '1'),
(223, 'ML', 'Africa/Bamako', '+00:00', '1'),
(224, 'MT', 'Europe/Malta', '+02:00', '1'),
(225, 'MH', 'Pacific/Kwajalein', '+12:00', '1'),
(226, 'MH', 'Pacific/Majuro', '+12:00', '1'),
(227, 'MR', 'Africa/Nouakchott', '+00:00', '1'),
(228, 'MU', 'Indian/Mauritius', '+04:00', '1'),
(229, 'YT', 'Indian/Mayotte', '+03:00', '1'),
(230, 'MX', 'America/Bahia_Banderas', '-05:00', '1'),
(231, 'MX', 'America/Cancun', '-05:00', '1'),
(232, 'MX', 'America/Chihuahua', '-06:00', '1'),
(233, 'MX', 'America/Hermosillo', '-07:00', '1'),
(234, 'MX', 'America/Matamoros', '-05:00', '1'),
(235, 'MX', 'America/Mazatlan', '-06:00', '1'),
(236, 'MX', 'America/Merida', '-05:00', '1'),
(237, 'MX', 'America/Mexico_City', '-05:00', '1'),
(238, 'MX', 'America/Monterrey', '-05:00', '1'),
(239, 'MX', 'America/Ojinaga', '-06:00', '1'),
(240, 'MX', 'America/Santa_Isabel', '-07:00', '1'),
(241, 'MX', 'America/Tijuana', '-07:00', '1'),
(242, 'FM', 'Pacific/Chuuk', '+10:00', '1'),
(243, 'FM', 'Pacific/Kosrae', '+11:00', '1'),
(244, 'FM', 'Pacific/Pohnpei', '+11:00', '1'),
(245, 'MD', 'Europe/Chisinau', '+03:00', '1'),
(246, 'MC', 'Europe/Monaco', '+02:00', '1'),
(247, 'MN', 'Asia/Choibalsan', '+08:00', '1'),
(248, 'MN', 'Asia/Hovd', '+07:00', '1'),
(249, 'MN', 'Asia/Ulaanbaatar', '+08:00', '1'),
(250, 'ME', 'Europe/Podgorica', '+02:00', '1'),
(251, 'MS', 'America/Montserrat', '-04:00', '1'),
(252, 'MA', 'Africa/Casablanca', '+00:00', '1'),
(253, 'MZ', 'Africa/Maputo', '+02:00', '1'),
(254, 'NA', 'Africa/Windhoek', '+01:00', '1'),
(255, 'NR', 'Pacific/Nauru', '+12:00', '1'),
(256, 'NP', 'Asia/Kathmandu', '+05:45', '1'),
(257, 'NL', 'Europe/Amsterdam', '+02:00', '1'),
(258, 'NC', 'Pacific/Noumea', '+11:00', '1'),
(259, 'NZ', 'Pacific/Auckland', '+12:00', '1'),
(260, 'NZ', 'Pacific/Chatham', '+12:45', '1'),
(261, 'NI', 'America/Managua', '-06:00', '1'),
(262, 'NE', 'Africa/Niamey', '+01:00', '1'),
(263, 'NG', 'Africa/Lagos', '+01:00', '1'),
(264, 'NU', 'Pacific/Niue', '-11:00', '1'),
(265, 'KP', 'Asia/Pyongyang', '+09:00', '1'),
(266, 'MP', 'Pacific/Saipan', '+10:00', '1'),
(267, 'NO', 'Europe/Oslo', '+02:00', '1'),
(268, 'OM', 'Asia/Muscat', '+04:00', '1'),
(269, 'PK', 'Asia/Karachi', '+05:00', '1'),
(270, 'PW', 'Pacific/Palau', '+09:00', '1'),
(271, 'PA', 'America/Panama', '-05:00', '1'),
(272, 'PG', 'Pacific/Port_Moresby', '+10:00', '1'),
(273, 'PY', 'America/Asuncion', '-04:00', '1'),
(274, 'PE', 'America/Lima', '-05:00', '1'),
(275, 'PH', 'Asia/Manila', '+08:00', '1'),
(276, 'PN', 'Pacific/Pitcairn', '-08:00', '1'),
(277, 'PL', 'Europe/Warsaw', '+02:00', '1'),
(278, 'PT', 'Atlantic/Azores', '+00:00', '1'),
(279, 'PT', 'Atlantic/Madeira', '+01:00', '1'),
(280, 'PT', 'Europe/Lisbon', '+01:00', '1'),
(281, 'PR', 'America/Puerto_Rico', '-04:00', '1'),
(282, 'QA', 'Asia/Qatar', '+03:00', '1'),
(283, 'CG', 'Africa/Brazzaville', '+01:00', '1'),
(284, 'RO', 'Europe/Bucharest', '+03:00', '1'),
(285, 'RU', 'Asia/Anadyr', '+12:00', '1'),
(286, 'RU', 'Asia/Irkutsk', '+09:00', '1'),
(287, 'RU', 'Asia/Kamchatka', '+12:00', '1'),
(288, 'RU', 'Asia/Krasnoyarsk', '+08:00', '1'),
(289, 'RU', 'Asia/Magadan', '+12:00', '1'),
(290, 'RU', 'Asia/Novokuznetsk', '+07:00', '1'),
(291, 'RU', 'Asia/Novosibirsk', '+07:00', '1'),
(292, 'RU', 'Asia/Omsk', '+07:00', '1'),
(293, 'RU', 'Asia/Sakhalin', '+11:00', '1'),
(294, 'RU', 'Asia/Vladivostok', '+11:00', '1'),
(295, 'RU', 'Asia/Yakutsk', '+10:00', '1'),
(296, 'RU', 'Asia/Yekaterinburg', '+06:00', '1'),
(297, 'RU', 'Europe/Kaliningrad', '+03:00', '1'),
(298, 'RU', 'Europe/Moscow', '+04:00', '1'),
(299, 'RU', 'Europe/Samara', '+04:00', '1'),
(300, 'RU', 'Europe/Volgograd', '+04:00', '1'),
(301, 'RW', 'Africa/Kigali', '+02:00', '1'),
(302, 'BL', 'America/St_Barthelemy', '-04:00', '1'),
(303, 'SH', 'Atlantic/St_Helena', '+00:00', '1'),
(304, 'KN', 'America/St_Kitts', '-04:00', '1'),
(305, 'LC', 'America/St_Lucia', '-04:00', '1'),
(306, 'MF', 'America/Marigot', '-04:00', '1'),
(307, 'PM', 'America/Miquelon', '-02:00', '1'),
(308, 'VC', 'America/St_Vincent', '-04:00', '1'),
(309, 'WS', 'Pacific/Apia', '+13:00', '1'),
(310, 'SM', 'Europe/San_Marino', '+02:00', '1'),
(311, 'ST', 'Africa/Sao_Tome', '+00:00', '1'),
(312, 'SA', 'Asia/Riyadh', '+03:00', '1'),
(313, 'SN', 'Africa/Dakar', '+00:00', '1'),
(314, 'RS', 'Europe/Belgrade', '+02:00', '1'),
(315, 'SC', 'Indian/Mahe', '+04:00', '1'),
(316, 'SL', 'Africa/Freetown', '+00:00', '1'),
(317, 'SG', 'Asia/Singapore', '+08:00', '1'),
(318, 'SK', 'Europe/Bratislava', '+02:00', '1'),
(319, 'SI', 'Europe/Ljubljana', '+02:00', '1'),
(320, 'SB', 'Pacific/Guadalcanal', '+11:00', '1'),
(321, 'SO', 'Africa/Mogadishu', '+03:00', '1'),
(322, 'ZA', 'Africa/Johannesburg', '+02:00', '1'),
(323, 'KR', 'Asia/Seoul', '+09:00', '1'),
(324, 'ES', 'Africa/Ceuta', '+02:00', '1'),
(325, 'ES', 'Atlantic/Canary', '+01:00', '1'),
(326, 'ES', 'Europe/Madrid', '+02:00', '1'),
(327, 'LK', 'Asia/Colombo', '+05:30', '1'),
(328, 'SD', 'Africa/Khartoum', '+03:00', '1'),
(329, 'SR', 'America/Paramaribo', '-03:00', '1'),
(330, 'SJ', 'Arctic/Longyearbyen', '+02:00', '1'),
(331, 'SZ', 'Africa/Mbabane', '+02:00', '1'),
(332, 'SE', 'Europe/Stockholm', '+02:00', '1'),
(333, 'CH', 'Europe/Zurich', '+02:00', '1'),
(334, 'SY', 'Asia/Damascus', '+03:00', '1'),
(335, 'TW', 'Asia/Taipei', '+08:00', '1'),
(336, 'TJ', 'Asia/Dushanbe', '+05:00', '1'),
(337, 'TZ', 'Africa/Dar_es_Salaam', '+03:00', '1'),
(338, 'TH', 'Asia/Bangkok', '+07:00', '1'),
(339, 'TL', 'Asia/Dili', '+09:00', '1'),
(340, 'TG', 'Africa/Lome', '+00:00', '1'),
(341, 'TK', 'Pacific/Fakaofo', '+14:00', '1'),
(342, 'TO', 'Pacific/Tongatapu', '+13:00', '1'),
(343, 'TT', 'America/Port_of_Spain', '-04:00', '1'),
(344, 'TN', 'Africa/Tunis', '+01:00', '1'),
(345, 'TR', 'Europe/Istanbul', '+03:00', '1'),
(346, 'TM', 'Asia/Ashgabat', '+05:00', '1'),
(347, 'TC', 'America/Grand_Turk', '-04:00', '1'),
(348, 'TV', 'Pacific/Funafuti', '+12:00', '1'),
(349, 'UG', 'Africa/Kampala', '+03:00', '1'),
(350, 'UA', 'Europe/Kiev', '+03:00', '1'),
(351, 'UA', 'Europe/Simferopol', '+03:00', '1'),
(352, 'UA', 'Europe/Uzhgorod', '+03:00', '1'),
(353, 'UA', 'Europe/Zaporozhye', '+03:00', '1'),
(354, 'AE', 'Asia/Dubai', '+04:00', '1'),
(355, 'US', 'America/Adak', '-09:00', '1'),
(356, 'US', 'America/Anchorage', '-08:00', '1'),
(357, 'US', 'America/Boise', '-06:00', '1'),
(358, 'US', 'America/Chicago', '-05:00', '1'),
(359, 'US', 'America/Denver', '-06:00', '1'),
(360, 'US', 'America/Detroit', '-04:00', '1'),
(361, 'US', 'America/Indiana/Indianapolis', '-04:00', '1'),
(362, 'US', 'America/Indiana/Knox', '-05:00', '1'),
(363, 'US', 'America/Indiana/Marengo', '-04:00', '1'),
(364, 'US', 'America/Indiana/Petersburg', '-04:00', '1'),
(365, 'US', 'America/Indiana/Tell_City', '-05:00', '1'),
(366, 'US', 'America/Indiana/Vevay', '-04:00', '1'),
(367, 'US', 'America/Indiana/Vincennes', '-04:00', '1'),
(368, 'US', 'America/Indiana/Winamac', '-04:00', '1'),
(369, 'US', 'America/Juneau', '-08:00', '1'),
(370, 'US', 'America/Kentucky/Louisville', '-04:00', '1'),
(371, 'US', 'America/Kentucky/Monticello', '-04:00', '1'),
(372, 'US', 'America/Los_Angeles', '-07:00', '1'),
(373, 'US', 'America/Menominee', '-05:00', '1'),
(374, 'US', 'America/Metlakatla', '-08:00', '1'),
(375, 'US', 'America/New_York', '-04:00', '1'),
(376, 'US', 'America/Nome', '-08:00', '1'),
(377, 'US', 'America/North_Dakota/Beulah', '-05:00', '1'),
(378, 'US', 'America/North_Dakota/Center', '-05:00', '1'),
(379, 'US', 'America/North_Dakota/New_Salem', '-05:00', '1'),
(380, 'US', 'America/Phoenix', '-07:00', '1'),
(381, 'US', 'America/Shiprock', '-06:00', '1'),
(382, 'US', 'America/Sitka', '-08:00', '1'),
(383, 'US', 'America/Yakutat', '-08:00', '1'),
(384, 'US', 'Pacific/Honolulu', '-10:00', '1'),
(385, 'UY', 'America/Montevideo', '-03:00', '1'),
(386, 'VI', 'America/St_Thomas', '-04:00', '1'),
(387, 'UZ', 'Asia/Samarkand', '+05:00', '1'),
(388, 'UZ', 'Asia/Tashkent', '+05:00', '1'),
(389, 'VU', 'Pacific/Efate', '+11:00', '1'),
(390, 'VE', 'America/Caracas', '-04:30', '1'),
(391, 'VN', 'Asia/Ho_Chi_Minh', '+07:00', '1'),
(392, 'WF', 'Pacific/Wallis', '+12:00', '1'),
(393, 'EH', 'Africa/El_Aaiun', '+00:00', '1'),
(394, 'YE', 'Asia/Aden', '+03:00', '1'),
(395, 'ZM', 'Africa/Lusaka', '+02:00', '1'),
(396, 'ZW', 'Africa/Harare', '+02:00', '1'),
(397, 'UK', 'Europe/London', '+00:00', '1');

-- --------------------------------------------------------

--
-- Table structure for table `industry_details`
--

CREATE TABLE IF NOT EXISTS `industry_details` (
  `industry_id` int(11) NOT NULL AUTO_INCREMENT,
  `industry_name` varchar(200) NOT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '1',
  PRIMARY KEY (`industry_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=58 ;

--
-- Dumping data for table `industry_details`
--

INSERT INTO `industry_details` (`industry_id`, `industry_name`, `status`) VALUES
(1, 'Agriculture', '1'),
(2, 'Grocery ', '1'),
(3, 'Accounting', '1'),
(4, 'Health Care ', '1'),
(5, 'Advertising', '1'),
(6, 'Internet Publishing ', '1'),
(7, 'Aerospace', '1'),
(8, 'Investment Banking ', '1'),
(9, 'Aircraft', '1'),
(10, 'Legal ', '1'),
(11, 'Airline', '1'),
(12, 'Manufacturing ', '1'),
(13, 'Apparel & Accessories', '1'),
(14, 'Motion Picture & Video ', '1'),
(15, 'Automotive', '1'),
(16, 'Music ', '1'),
(17, 'Banking', '1'),
(18, 'Newspaper Publishers ', '1'),
(19, 'Broadcasting', '1'),
(20, 'Online Auctions ', '1'),
(21, 'Brokerage', '1'),
(22, 'Pension Funds ', '1'),
(23, 'Biotechnology', '1'),
(24, 'Pharmaceuticals ', '1'),
(25, 'Call Centers', '1'),
(26, 'Private Equity ', '1'),
(27, 'Cargo Handling', '1'),
(28, 'Publishing ', '1'),
(29, 'Chemical', '1'),
(30, 'Real Estate ', '1'),
(31, 'Computer', '1'),
(32, 'Retail & Wholesale ', '1'),
(33, 'Consulting', '1'),
(34, 'Securities & Commodity Exchanges ', '1'),
(35, 'Consumer Products', '1'),
(36, 'Service ', '1'),
(37, 'Cosmetics', '1'),
(38, 'Soap & Detergent ', '1'),
(39, 'Defense', '1'),
(40, 'Software ', '1'),
(41, 'Department Stores', '1'),
(42, 'Sports ', '1'),
(43, 'Education', '1'),
(44, 'Technology ', '1'),
(45, 'Electronics', '1'),
(46, 'Telecommunications ', '1'),
(47, 'Energy', '1'),
(48, 'Television ', '1'),
(49, 'Entertainment & Leisure', '1'),
(50, 'Transportation ', '1'),
(51, 'Executive Search', '1'),
(52, 'Trucking ', '1'),
(53, 'Financial Services', '1'),
(54, 'Venture Capital ', '1'),
(55, 'Food', '1'),
(56, 'Beverages', '1'),
(57, 'Tobacco', '1');

-- --------------------------------------------------------

--
-- Table structure for table `instance_details`
--

CREATE TABLE IF NOT EXISTS `instance_details` (
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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `instance_details`
--

INSERT INTO `instance_details` (`instance_id`, `instance_name`, `instance_url`, `instance_salt`, `instance_logout_url`, `instance_api_url`, `instance_creation_dtm`, `instance_stop_dtm`, `admin_id`, `status`) VALUES
(1, 'LetsMeet Instance 46.137.222.211', 'http://46.137.222.211', 'b4982b9f578e96ec9cd645da8b9939d5', 'http://lm.quadridge.com/', '/bigbluebutton/api/', '2015-02-03 13:57:00', NULL, 1, '2'),
(2, 'Voice Bridge Instance 122.248.249.218 ', 'http://122.248.249.218', 'eb8941ccead5186d6b9fe587c7fcb4b3', 'http://lm.quadridge.com/', '/bigbluebutton/api/', '2015-02-26 13:00:51', NULL, 1, '2'),
(3, 'LetsMeet lmmeeting.quadridge.com Instance', 'http://lmmeeting.quadridge.com', '31d97087ac7081dbba7aad557c9d61e0', 'http://lm.quadridge.com/', '/bigbluebutton/api/', '2015-03-17 14:51:00', '2015-06-27 12:35:23', 1, '2'),
(4, 'LetsMeet - conference.eletsmeet.com', 'http://conference.eletsmeet.com', 'cda9d43824a4828383833ae77dde40ef', 'https://eletsmeet.com', '/bigbluebutton/api/', '2015-06-10 22:17:06', NULL, 1, '1');

-- --------------------------------------------------------

--
-- Table structure for table `invitation_details`
--

CREATE TABLE IF NOT EXISTS `invitation_details` (
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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=119 ;

--
-- Dumping data for table `invitation_details`
--

INSERT INTO `invitation_details` (`invitation_id`, `schedule_id`, `invitee_email_address`, `invitee_nick_name`, `invitee_idd_code`, `invitee_mobile_number`, `invitation_creator`, `invitation_creation_dtm`, `invitation_status`, `invitation_status_dtm`, `meeting_status`, `meeting_status_join_dtm`, `meeting_status_left_dtm`, `meeting_joined_ip_address`, `meeting_joined_headers`) VALUES
(1, '557abcc164904', 'nastassia.florindo@quadridge.com', 'Nastassia', '91', '9833133645', 'C', '2015-06-12 11:04:33', '0', NULL, '1', '2015-06-12 11:04:50', NULL, NULL, NULL),
(2, '557abcc164904', 'mitesh.shah@quadridge.com', 'Mitesh Shah', '91', '', 'I', '2015-06-12 11:04:33', '0', NULL, '1', '2015-06-12 11:05:14', NULL, NULL, NULL),
(3, '557acee4da13e', 'sushrit.shrivastava@quadridge.com', 'Sushrit', '91', '9000000004', 'C', '2015-06-12 12:21:56', '0', NULL, '1', '2015-06-12 12:22:01', NULL, NULL, NULL),
(4, '557acee4da13e', 'nastassia.florindo@quadridge.com', 'Nastassia Florindo', '91', '', 'I', '2015-06-12 12:21:56', '0', NULL, '0', NULL, NULL, NULL, NULL),
(5, '557ad3190bbaa', 'sushrit.shrivastava@quadridge.com', 'Sushrit', '91', '9000000004', 'C', '2015-06-12 12:39:52', '0', NULL, '1', '2015-06-12 12:39:55', NULL, NULL, NULL),
(6, '557ad3190bbaa', 'santosh.khaire@quadridge.com', 'Santosh Khaire', '91', '', 'I', '2015-06-12 12:39:52', '0', NULL, '1', '2015-06-12 12:42:24', NULL, NULL, NULL),
(7, '557bfe78d014d', 'sushrit.shrivastava@quadridge.com', 'Sushrit', '91', '9000000004', 'C', '2015-06-13 09:57:12', '0', NULL, '0', NULL, NULL, NULL, NULL),
(8, '557bfe78d014d', 'gopal.sirnaik@quadridge.com', 'Gopal Sirnaik', '91', '', 'M', '2015-06-13 09:57:12', '1', '2015-06-13 09:58:42', '1', '2015-06-13 10:06:45', NULL, NULL, NULL),
(9, '557bfe78d014d', 'santosh.khaire@quadridge.com', 'Santosh Khaire', '91', '', 'I', '2015-06-13 09:57:12', '3', '2015-06-13 10:05:52', '1', '2015-06-13 10:07:50', NULL, NULL, NULL),
(10, '557bfe78d014d', 'mitesh.shah@quadridge.com', 'Mitesh Shah', '91', '', 'I', '2015-06-13 09:57:12', '2', '2015-06-13 10:05:27', '1', '2015-06-13 10:08:23', NULL, NULL, NULL),
(11, '557c024bbcd88', 'sushrit.shrivastava@quadridge.com', 'Sushrit', '91', '9000000004', 'C', '2015-06-13 10:13:31', '0', NULL, '0', NULL, NULL, NULL, NULL),
(12, '557c024bbcd88', 'nastassia.florindo@quadridge.com', 'Nastassia Florindo', '91', '', 'I', '2015-06-13 10:13:31', '0', NULL, '0', NULL, NULL, NULL, NULL),
(13, '557c024bbcd88', 'mitesh.shah@quadridge.com', 'Mitesh Shah', '91', '', 'M', '2015-06-13 10:13:31', '0', NULL, '0', NULL, NULL, NULL, NULL),
(14, '557fb42793086', 'sushrit.shrivastava@quadridge.com', 'Sushrit', '91', '9000000004', 'C', '2015-06-16 05:29:10', '0', NULL, '1', '2015-06-16 05:36:31', NULL, NULL, NULL),
(15, '557fb42793086', 'mitesh.shah@quadridge.com', 'Mitesh Shah', '91', '', 'I', '2015-06-16 05:29:10', '0', NULL, '0', NULL, NULL, NULL, NULL),
(16, '557fb65babeef', 'sushrit.shrivastava@quadridge.com', 'Sushrit', '91', '9000000004', 'C', '2015-06-16 05:38:35', '0', NULL, '0', NULL, NULL, NULL, NULL),
(17, '557fb65babeef', 'nastassia.florindo@quadridge.com', 'Nastassia Florindo', '91', '', 'I', '2015-06-16 05:38:35', '0', NULL, '1', '2015-06-16 06:26:47', NULL, NULL, NULL),
(18, '558125b70d0d2', 'sushrit.shrivastava@quadridge.com', 'Sushrit', '91', '9000000004', 'C', '2015-06-17 07:45:58', '0', NULL, '1', '2015-06-17 07:46:34', NULL, '49.248.5.250', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:38.0) Gecko/20100101 Firefox/38.0'),
(19, '558125b70d0d2', 'nastassia.florindo@quadridge.com', 'Nastassia Florindo', '91', '', 'I', '2015-06-17 07:45:58', '1', '2015-06-17 07:46:49', '1', '2015-06-17 07:46:52', NULL, '49.248.5.250', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/43.0.2357.124 Safari/537.36'),
(20, '558125b70d0d2', 'mitesh.shah@quadridge.com', 'Mitesh Shah', '91', '', 'I', '2015-06-17 07:45:58', '0', NULL, '1', '2015-06-17 08:04:13', NULL, '114.143.39.176', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:38.0) Gecko/20100101 Firefox/38.0'),
(21, '55890c3ec7ab3', 'sushrit.shrivastava@quadridge.com', 'Sushrit', '91', '9000000004', 'C', '2015-06-23 07:35:26', '0', NULL, '1', '2015-06-23 07:36:24', NULL, '49.248.5.250', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/43.0.2357.125 Safari/537.36'),
(22, '55890c3ec7ab3', 'mitesh.shah@quadridge.com', 'Mitesh Shah', '91', '', 'I', '2015-06-23 07:35:26', '0', NULL, '1', '2015-06-23 07:36:02', NULL, '49.248.5.250', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:38.0) Gecko/20100101 Firefox/38.0'),
(23, '558a84c574be4', 'sushrit.shrivastava@quadridge.com', 'Sushrit', '91', '9000000004', 'C', '2015-06-24 10:21:56', '0', NULL, '1', '2015-06-24 10:22:07', NULL, '49.248.5.250', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/43.0.2357.125 Safari/537.36'),
(24, '558a84c574be4', 'nastassia.florindo@quadridge.com', 'Nastassia Florindo', '91', '', 'I', '2015-06-24 10:21:56', '1', '2015-06-24 10:30:03', '1', '2015-06-24 10:30:05', NULL, '49.248.5.250', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/43.0.2357.130 Safari/537.36'),
(25, '558a84c574be4', 'althea.lopez@quadridge.com', 'Althea Lopez', '91', '', 'I', '2015-06-24 10:21:56', '0', NULL, '1', '2015-06-24 10:25:47', NULL, '49.248.5.250', 'Mozilla/5.0 (Windows NT 6.3; WOW64; rv:38.0) Gecko/20100101 Firefox/38.0'),
(26, '558a84c574be4', 'mitesh.shah@quadridge.com', 'Mitesh Shah', '91', '', 'I', '2015-06-24 10:21:56', '0', NULL, '1', '2015-06-24 10:30:12', NULL, '49.248.5.250', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:38.0) Gecko/20100101 Firefox/38.0'),
(27, '558a84c574be4', 'gopal.sirnaik@quadridge.com', 'Gopal', '', '', 'I', '2015-06-24 10:29:16', '0', NULL, '0', NULL, NULL, '49.248.5.250', 'Mozilla/5.0 (Windows NT 6.1; rv:38.0) Gecko/20100101 Firefox/38.0'),
(28, '55655e21d8457', 'adsilva@epicchannel.com', 'Ashwin', '91', '9821234851', 'C', '2015-05-27 06:03:13', '0', NULL, '1', '2015-05-27 06:03:19', NULL, NULL, NULL),
(29, '55655e21d8457', 'ddgama@epicchannel.com', 'desmond', '', '', 'I', '2015-05-27 06:03:13', '1', '2015-05-27 06:05:03', '1', '2015-05-27 06:05:10', NULL, NULL, NULL),
(30, '556568aad4020', 'adsilva@epicchannel.com', 'Ashwin', '91', '9821234851', 'C', '2015-05-27 06:48:10', '0', NULL, '1', '2015-05-27 06:48:22', NULL, NULL, NULL),
(31, '556568aad4020', 'GaneshShirsath@winjit.com', 'ganesh', '', '', 'I', '2015-05-27 06:48:10', '0', NULL, '1', '2015-05-27 06:53:55', NULL, NULL, NULL),
(32, '556568aad4020', 'dchawla@epicchannel.com', 'dhruv', '', '', 'I', '2015-05-27 06:48:10', '1', '2015-05-27 06:50:47', '1', '2015-05-27 06:50:52', NULL, NULL, NULL),
(33, '556856f952d03', 'adsilva@epicchannel.com', 'Ashwin', '91', '9821234851', 'C', '2015-05-29 12:09:29', '0', NULL, '1', '2015-05-29 12:09:37', NULL, NULL, NULL),
(34, '556856f952d03', 'vwarerkar@epicchannel.com', 'vaibhav', '', '', 'I', '2015-05-29 12:09:29', '0', NULL, '1', '2015-05-29 12:09:49', NULL, NULL, NULL),
(35, '556d9574f1a93', 'adsilva@epicchannel.com', 'Ashwin', '91', '9821234851', 'C', '2015-06-02 11:37:24', '0', NULL, '1', '2015-06-02 11:37:32', NULL, NULL, NULL),
(36, '556d9574f1a93', 'GaneshShirsath@winjit.com', 'ganesh', '', '', 'I', '2015-06-02 11:37:24', '0', NULL, '1', '2015-06-02 11:39:16', NULL, NULL, NULL),
(37, '556d9574f1a93', 'dchawla@epicchannel.com', 'dhruv', '', '', 'I', '2015-06-02 11:37:24', '1', '2015-06-02 11:42:29', '1', '2015-06-02 11:42:37', NULL, NULL, NULL),
(38, '558ce26beaffe', 'sushrit.shrivastava@quadridge.com', 'Sushrit', '91', '9000000004', 'C', '2015-06-26 05:26:03', '0', NULL, '1', '2015-06-26 05:26:44', NULL, '49.248.5.250', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:38.0) Gecko/20100101 Firefox/38.0'),
(39, '558ce26beaffe', 'nastassia.florindo@quadridge.com', 'Nastassia Florindo', '91', '', 'I', '2015-06-26 05:26:03', '1', '2015-06-26 05:27:06', '1', '2015-06-26 05:27:08', NULL, '49.248.5.250', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/43.0.2357.130 Safari/537.36'),
(40, '558ce26beaffe', 'suyashsa@gmail.com', 'Suyash', '', '', 'M', '2015-06-26 05:26:03', '0', NULL, '0', NULL, NULL, NULL, NULL),
(41, '558e5619bd013', 'gopal.sirnaik@quadridge.com', 'Gopal', '91', '9000000002', 'C', '2015-06-27 07:51:53', '0', NULL, '1', '2015-06-27 07:51:58', NULL, '49.248.5.250', 'Mozilla/5.0 (Windows NT 6.1; rv:38.0) Gecko/20100101 Firefox/38.0'),
(42, '558e5619bd013', 'mitesh.shah@quadridge.com', 'Mitesh Shah', '91', '', 'I', '2015-06-27 07:51:53', '0', NULL, '0', NULL, NULL, NULL, NULL),
(43, '558fb03205416', 'pankaj@quadridge.com', 'Pankaj', '91', '9000000006', 'C', '2015-06-28 08:28:33', '0', NULL, '1', '2015-06-28 08:28:43', NULL, '123.201.187.120', 'Mozilla/5.0 (Windows NT 6.1; rv:38.0) Gecko/20100101 Firefox/38.0'),
(44, '558fb03205416', 'ajayh@gmail.com', 'ajay', '', '', 'I', '2015-06-28 08:28:33', '0', NULL, '0', NULL, NULL, NULL, NULL),
(45, '558fb252394b8', 'pankaj@quadridge.com', 'Pankaj', '91', '9000000006', 'C', '2015-06-28 08:37:38', '0', NULL, '1', '2015-06-28 08:37:48', NULL, '123.201.187.120', 'Mozilla/5.0 (Windows NT 6.1; rv:38.0) Gecko/20100101 Firefox/38.0'),
(46, '558fb252394b8', 'ajayh.kaul@gmail.com', 'ajay', '', '', 'I', '2015-06-28 08:37:38', '1', '2015-06-28 08:38:34', '1', '2015-06-28 08:38:42', NULL, '45.33.133.176', 'Mozilla/5.0 (X11; U; Linux x86_64; en-gb) AppleWebKit/537.36 (KHTML, like Gecko)  Chrome/30.0.1599.114 Safari/537.36 Puffin/4.1.4.1387AP'),
(47, '558fb5803d476', 'pankaj@quadridge.com', 'Pankaj', '91', '9000000006', 'C', '2015-06-28 08:51:12', '0', NULL, '1', '2015-06-28 08:51:19', NULL, '123.201.187.120', 'Mozilla/5.0 (Windows NT 6.1; rv:38.0) Gecko/20100101 Firefox/38.0'),
(48, '558fb5803d476', 'pankaj.geodesic@gmail.com', 'pankaj', '', '', 'I', '2015-06-28 08:51:12', '0', NULL, '1', '2015-06-28 08:53:10', NULL, '123.201.187.120', 'Mozilla/5.0 (iPhone; CPU iPhone OS 8_1_2 like Mac OS X) AppleWebKit/600.1.4 (KHTML, like Gecko) Version/8.0 Mobile/12B440 Safari/600.1.4'),
(49, '559a3ae344b64', 'adsilva@epicchannel.com', 'Ashwin', '91', '9821234851', 'C', '2015-07-06 08:22:58', '0', NULL, '1', '2015-07-06 08:23:07', NULL, '14.140.91.202', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_5) AppleWebKit/600.6.3 (KHTML, like Gecko) Version/7.1.6 Safari/537.85.15'),
(50, '559a3ae344b64', 'althea.lopez@quadridge.com', 'althea', '', '', 'I', '2015-07-06 08:22:58', '0', NULL, '0', NULL, NULL, NULL, NULL),
(51, '559a43ffd49ad', 'zahir@labradogstudios.com', 'Zahir', '91', '9000000000', 'C', '2015-07-06 09:01:51', '0', NULL, '1', '2015-07-06 09:01:56', NULL, '115.97.39.184', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_6_8) AppleWebKit/534.59.10 (KHTML, like Gecko) Version/5.1.9 Safari/534.59.10'),
(52, '559a43ffd49ad', 'kaevan.umrigar@doosra.in', 'kaevan', '', '', 'I', '2015-07-06 09:01:51', '0', NULL, '1', '2015-07-06 09:02:40', NULL, '49.248.26.226', 'Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; WOW64; Trident/5.0)'),
(53, '559ccef3bfc22', 'nastassia.florindo@quadridge.com', 'Nastassia', '91', '9833133645', 'C', '2015-07-08 07:19:15', '0', NULL, '1', '2015-07-08 07:19:29', NULL, '49.248.5.250', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:28.0) Gecko/20100101 Firefox/28.0'),
(54, '559ccef3bfc22', 'mitesh.shah@quadridge.com', 'Mitesh Shah', '91', '', 'I', '2015-07-08 07:19:15', '0', NULL, '0', NULL, NULL, NULL, NULL),
(55, '559e0b39aab5d', 'sushrit.shrivastava@quadridge.com', 'Sushrit', '91', '9000000004', 'C', '2015-07-09 05:48:41', '0', NULL, '1', '2015-07-09 05:50:30', NULL, '49.248.5.250', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:38.0) Gecko/20100101 Firefox/38.0'),
(56, '559e0b39aab5d', 'mitesh.shah@quadridge.com', 'Mitesh Shah', '91', '', 'I', '2015-07-09 05:48:41', '0', NULL, '1', '2015-07-09 05:49:20', NULL, '49.248.5.250', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:38.0) Gecko/20100101 Firefox/38.0'),
(57, '559e11bdd77d2', 'sushrit.shrivastava@quadridge.com', 'Sushrit', '91', '9000000004', 'C', '2015-07-09 06:16:29', '0', NULL, '1', '2015-07-09 06:16:48', NULL, '49.248.5.250', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:38.0) Gecko/20100101 Firefox/38.0'),
(58, '559e11bdd77d2', 'mistesh.shah@quadridge.com', 'mitesh', '', '', 'I', '2015-07-09 06:16:29', '0', NULL, '0', NULL, NULL, NULL, NULL),
(59, '559e11bdd77d2', 'nastassia.florindo@quadridge.com', 'Nastassia Florindo', '91', '', 'I', '2015-07-09 06:16:29', '0', NULL, '0', NULL, NULL, NULL, NULL),
(60, '559e364be4211', 'mitesh.shah@quadridge.com', 'Mitesh', '91', '9000000003', 'C', '2015-07-09 08:52:27', '0', NULL, '1', '2015-07-09 09:08:52', NULL, '49.248.5.250', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:38.0) Gecko/20100101 Firefox/38.0'),
(61, '559e364be4211', 'harshadvb@gmail.com', 'Harshad Badbade', '', '', 'I', '2015-07-09 08:52:27', '0', NULL, '1', '2015-07-09 09:09:02', NULL, '1.39.46.89', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/43.0.2357.132 Safari/537.36'),
(62, '559e68123e7f0', 'althea.lopez@quadridge.com', 'Althea', '91', '9000000001', 'C', '2015-07-09 12:24:49', '0', NULL, '1', '2015-07-09 12:24:56', NULL, '49.248.5.250', 'Mozilla/5.0 (Windows NT 6.3; WOW64; rv:39.0) Gecko/20100101 Firefox/39.0'),
(63, '559e68123e7f0', 'gopal.sirnaik@quadridge.com', 'Gopal Sirnaik', '91', '', 'I', '2015-07-09 12:24:49', '0', NULL, '0', NULL, NULL, NULL, NULL),
(64, '559e68123e7f0', 'kiran@quadridge.com', 'Kiran Kulkarni', '91', '', 'I', '2015-07-09 12:24:49', '0', NULL, '0', NULL, NULL, NULL, NULL),
(65, '559f5c96eb2c5', 'nastassia.florindo@quadridge.com', 'Nastassia', '91', '9833133645', 'C', '2015-07-10 05:48:06', '0', NULL, '1', '2015-07-10 05:55:10', NULL, '49.248.5.250', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:28.0) Gecko/20100101 Firefox/28.0'),
(66, '559f5c96eb2c5', 'sushrit.shrivastava@quadridge.com', 'Sushrit Shrivatava', '91', '', 'I', '2015-07-10 05:48:06', '0', NULL, '0', NULL, NULL, NULL, NULL),
(67, '559f5c96eb2c5', 'mitesh.shah@quadridge.com', 'Mitesh Shah', '', '', 'I', '2015-07-10 05:48:06', '0', NULL, '0', NULL, NULL, NULL, NULL),
(68, '559f93c240436', 'zahir@labradogstudios.com', 'Zahir', '91', '9000000000', 'C', '2015-07-10 09:43:29', '0', NULL, '1', '2015-07-10 09:43:35', NULL, '114.143.38.199', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_6_8) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/44.0.2403.61 Safari/537.36'),
(69, '559f93c240436', 'kaevan.umrigar@doosra.in', 'Kaiwan', '', '', 'I', '2015-07-10 09:43:29', '0', NULL, '0', NULL, NULL, NULL, NULL),
(70, '55a15da618479', 'mitesh.shah@quadridge.com', 'Mitesh', '91', '9000000003', 'C', '2015-07-11 18:17:09', '0', NULL, '0', NULL, NULL, NULL, NULL),
(71, '55a15da618479', 'mitesh.a.shah@gmail.com', 'mite', '', '', 'I', '2015-07-11 18:17:09', '0', NULL, '0', NULL, NULL, NULL, NULL),
(72, '55a1605e3ea45', 'mitesh.shah@quadridge.com', 'Mitesh', '91', '9000000003', 'C', '2015-07-11 18:28:46', '0', NULL, '0', NULL, NULL, NULL, NULL),
(73, '55a1605e3ea45', 'mitesh.a.shah@gmail.com', 'mitesh', '', '', 'I', '2015-07-11 18:28:46', '0', NULL, '0', NULL, NULL, NULL, NULL),
(74, '55a1640cb6fc6', 'mitesh.shah@quadridge.com', 'Mitesh', '91', '9000000003', 'C', '2015-07-11 18:44:28', '0', NULL, '0', NULL, NULL, NULL, NULL),
(75, '55a1640cb6fc6', 'contact@quadridge.com', 'contact', '', '', 'I', '2015-07-11 18:44:28', '0', NULL, '0', NULL, NULL, NULL, NULL),
(76, '55a35cb9a6103', 'yogesh.pandya@in.ibm.com', 'User1', '91', '9099002479', 'C', '2015-07-13 06:37:45', '0', NULL, '1', '2015-07-13 06:37:57', NULL, '117.239.35.226', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:31.0) Gecko/20100101 Firefox/31.0'),
(77, '55a35cb9a6103', 'mitesh.shah@quadridge.com', 'Mitesh', '', '', 'I', '2015-07-13 06:37:45', '0', NULL, '1', '2015-07-13 06:38:15', NULL, '49.248.5.250', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/43.0.2357.132 Safari/537.36'),
(78, '55a35cb9a6103', 'jnishane@in.ibm.com', 'Jayesh', '91', '9099002479', 'I', '2015-07-13 06:37:45', '0', NULL, '0', NULL, NULL, NULL, NULL),
(79, '55a4af7e35ce0', 'nastassia.florindo@quadridge.com', 'Nastassia', '91', '9833133645', 'C', '2015-07-14 06:43:09', '0', NULL, '1', '2015-07-14 06:43:15', NULL, '49.248.5.250', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:39.0) Gecko/20100101 Firefox/39.0'),
(80, '55a4af7e35ce0', 'vishnuwf@gmail.com', 'Vishnu', '', '', 'I', '2015-07-14 06:43:09', '1', '2015-07-14 06:44:38', '1', '2015-07-14 06:44:43', NULL, '49.248.5.250', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:38.0) Gecko/20100101 Firefox/38.0'),
(81, '55a51d47473a8', 'mitesh.shah@quadridge.com', 'Mitesh', '91', '9000000003', 'C', '2015-07-14 14:31:34', '0', NULL, '1', '2015-07-14 14:31:38', NULL, '49.248.5.250', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:39.0) Gecko/20100101 Firefox/39.0'),
(82, '55a51d47473a8', 'sushrit.shrivastava@quadridge.com', 'Sushrit Shrivatava', '91', '', 'I', '2015-07-14 14:31:34', '0', NULL, '0', NULL, NULL, NULL, NULL),
(83, '55a5fb0c6902a', 'jnishane@in.ibm.com', 'User2', '91', '9099002479', 'C', '2015-07-15 06:17:48', '0', NULL, '0', NULL, NULL, NULL, NULL),
(84, '55a5fb0c6902a', 'yogesh.pandya@in.ibm.com', 'Adani User1', '91', '9099005969', 'I', '2015-07-15 06:17:48', '0', NULL, '0', NULL, NULL, NULL, NULL),
(85, '55a5fb0c6902a', 'palpuroh@in.ibm.com', 'Adani User3', '91', '9099005644', 'I', '2015-07-15 06:17:48', '1', '2015-07-15 06:21:12', '0', NULL, NULL, '117.239.35.226', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:31.0) Gecko/20100101 Firefox/31.0'),
(86, '55a5fb0c6902a', 'kailshah@in.ibm.com', 'Adani User4', '91', '9824047517', 'I', '2015-07-15 06:17:48', '0', NULL, '1', '2015-07-15 06:58:34', NULL, '117.239.35.226', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:31.0) Gecko/20100101 Firefox/31.0'),
(87, '55a5fb0c6902a', 'shalin.shah@in.ibm.com', 'Adani User5', '91', '9099055010', 'I', '2015-07-15 06:17:48', '0', NULL, '0', NULL, NULL, NULL, NULL),
(88, '55a5fb0c6902a', 'mitesh.shah@quadridge.com', 'Mitesh Shah', '', '', 'I', '2015-07-15 06:17:48', '0', NULL, '1', '2015-07-15 06:46:16', NULL, '49.248.5.250', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:39.0) Gecko/20100101 Firefox/39.0'),
(89, '55a5fb0c6902a', 'jnishane@in.ibm.com', 'Jayesh Nishane', '', '', 'I', '2015-07-15 06:17:48', '0', NULL, '0', NULL, NULL, NULL, NULL),
(90, '55a60ced432a4', 'mitesh.shah@quadridge.com', 'Mitesh', '91', '9000000003', 'C', '2015-07-15 07:34:05', '0', NULL, '1', '2015-07-15 07:34:18', NULL, '49.248.5.250', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:39.0) Gecko/20100101 Firefox/39.0'),
(91, '55a60ced432a4', 'jnishane@in.ibm.com', 'Jayesh Nishane', '91', '9099002479', 'I', '2015-07-15 07:34:05', '0', NULL, '1', '2015-07-15 07:37:10', NULL, '14.96.238.148', 'Mozilla/5.0 (Windows NT 6.1; WOW64; Trident/7.0; rv:11.0) like Gecko'),
(92, '55a6481d1e889', 'adsilva@epicchannel.com', 'Ashwin', '91', '9821234851', 'C', '2015-07-15 11:46:36', '0', NULL, '1', '2015-07-15 11:47:20', NULL, '14.140.91.202', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_5) AppleWebKit/600.6.3 (KHTML, like Gecko) Version/7.1.6 Safari/537.85.15'),
(93, '55a6481d1e889', 'ashah@epicchannel.com', 'aditya', '', '', 'M', '2015-07-15 11:46:36', '0', NULL, '0', NULL, NULL, NULL, NULL),
(94, '55a6481d1e889', 'ddgama@epicchannel.com', 'desmond', '', '', 'I', '2015-07-15 11:46:36', '0', NULL, '1', '2015-07-15 11:48:25', NULL, '14.140.91.202', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_3) AppleWebKit/600.6.3 (KHTML, like Gecko) Version/8.0.6 Safari/600.6.3'),
(95, '55a7796d2e5e3', 'anirudha.khopade@quadridge.com', 'Anirudha', '91', '9619732555', 'C', '2015-07-16 09:29:16', '0', NULL, '1', '2015-07-16 09:29:20', NULL, '114.143.35.134', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/43.0.2357.132 Safari/537.36'),
(96, '55a7796d2e5e3', 'abc@abc.com', 'abc', '', '', 'I', '2015-07-16 09:29:16', '0', NULL, '0', NULL, NULL, NULL, NULL),
(97, '55a8cf01318e6', 'mitesh.shah@quadridge.com', 'mitesh', '91', '9920540000', 'I', '2015-07-17 09:46:41', '0', NULL, '0', NULL, NULL, NULL, NULL),
(98, '55a8cf01318e6', 'sushrit.shrivastava@quadridge.com', 'sushrit', '91', '9167997663', 'M', '2015-07-17 09:46:41', '0', NULL, '0', NULL, NULL, NULL, NULL),
(99, '55a8d19d20584', 'mitesh.shah@quadridge.com', 'Mitesh', '91', '9000000003', 'C', '2015-07-17 09:57:48', '0', NULL, '1', '2015-07-17 09:57:52', NULL, '49.248.5.250', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:39.0) Gecko/20100101 Firefox/39.0'),
(100, '55a8d19d20584', 'jnishane@in.ibm.com', 'Jayesh Nishane', '91', '9099002479', 'I', '2015-07-17 09:57:48', '0', NULL, '1', '2015-07-17 10:00:28', NULL, '117.239.35.226', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:31.0) Gecko/20100101 Firefox/31.0'),
(101, '55a8d5c26fb88', 'mitesh.shah@quadridge.com', 'mitesh', '91', '9920540000', 'I', '2015-07-17 10:15:30', '0', NULL, '0', NULL, NULL, NULL, NULL),
(102, '55a8d5c26fb88', 'sushrit.shrivastava@quadridge.com', 'sushrit', '91', '9167997663', 'M', '2015-07-17 10:15:30', '0', NULL, '0', NULL, NULL, NULL, NULL),
(103, '55a8d68d4424f', 'mitesh.shah@quadridge.com', 'Mitesh', '91', '9000000003', 'C', '2015-07-17 10:18:53', '0', NULL, '1', '2015-07-17 10:18:56', NULL, '49.248.5.250', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:39.0) Gecko/20100101 Firefox/39.0'),
(104, '55a8d68d4424f', 'jnishane@in.ibm.com', 'Jayesh Nishane', '91', '9099002479', 'I', '2015-07-17 10:18:53', '0', NULL, '1', '2015-07-17 10:25:35', NULL, '115.117.218.167', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:31.0) Gecko/20100101 Firefox/31.0'),
(105, '55a8e898d6a0e', 'balaji.prasad@axsys-healthtech.com', 'Balaji', '91', '9849016208', 'M', '2015-07-17 11:35:52', '0', NULL, '0', NULL, NULL, NULL, NULL),
(106, '55a9e10ff1f43', 'mitesh.shah@quadridge.com', 'Mitesh', '91', '9000000003', 'C', '2015-07-18 05:15:59', '0', NULL, '1', '2015-07-18 05:16:26', NULL, '49.248.5.250', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:39.0) Gecko/20100101 Firefox/39.0'),
(107, '55a9e10ff1f43', 'jnishane@in.ibm.com', 'Jayesh Nishane', '91', '9099002479', 'I', '2015-07-18 05:15:59', '0', NULL, '1', '2015-07-18 05:25:52', NULL, '1.39.15.79', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:31.0) Gecko/20100101 Firefox/31.0'),
(108, '55a9e10ff1f43', 'althea.lopez@quadridge.com', 'Althea Lopez', '', '', 'I', '2015-07-18 05:18:34', '0', NULL, '1', '2015-07-18 05:20:31', NULL, '49.248.5.250', 'Mozilla/5.0 (Windows NT 6.3; WOW64; rv:39.0) Gecko/20100101 Firefox/39.0'),
(109, '55acc11be713e', 'sumeet.nihalani@newsrise.org', 'Sumeet Nihalani', '91', '9049294103', 'C', '2015-07-20 09:36:27', '0', NULL, '1', '2015-07-20 09:37:38', NULL, '14.141.27.97', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_4) AppleWebKit/600.7.12 (KHTML, like Gecko) Version/8.0.7 Safari/600.7.12'),
(110, '55acc11be713e', 'sameer.bhatnagar@newsrise.org', 'Sameer Bhatnagar', '', '', 'I', '2015-07-20 09:36:27', '1', '2015-07-20 09:45:10', '1', '2015-07-20 09:45:32', NULL, '115.112.159.6', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/43.0.2357.132 Safari/537.36'),
(111, '55accd45574dd', 'sumeet.nihalani@newsrise.org', 'Sumeet Nihalani', '91', '9049294103', 'C', '2015-07-20 10:28:21', '0', NULL, '1', '2015-07-20 10:28:33', NULL, '14.141.27.97', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_4) AppleWebKit/600.7.12 (KHTML, like Gecko) Version/8.0.7 Safari/600.7.12'),
(112, '55accd45574dd', 'dinesh.kumar@newsrise.org', 'Dinesh', '', '', 'I', '2015-07-20 10:28:21', '0', NULL, '0', NULL, NULL, NULL, NULL),
(113, '55acd09916109', 'sumeet.nihalani@newsrise.org', 'Sumeet Nihalani', '91', '9049294103', 'C', '2015-07-20 10:42:32', '0', NULL, '1', '2015-07-20 10:42:38', NULL, '115.112.159.6', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:39.0) Gecko/20100101 Firefox/39.0'),
(114, '55acd09916109', 'dinesh.kumar@newsrise.org', 'Dinesh', '', '', 'I', '2015-07-20 10:42:32', '1', '2015-07-20 10:46:19', '1', '2015-07-20 10:46:29', NULL, '14.141.27.97', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:39.0) Gecko/20100101 Firefox/39.0'),
(115, '55acd2d101c5c', 'sumeet.nihalani@newsrise.org', 'Sumeet Nihalani', '91', '9049294103', 'C', '2015-07-20 10:52:00', '0', NULL, '0', NULL, NULL, NULL, NULL),
(116, '55acd2d101c5c', 'dk3047@gmail.com', 'dinesh', '', '', 'I', '2015-07-20 10:52:00', '0', NULL, '0', NULL, NULL, '14.141.27.97', 'Mozilla/5.0 (Windows NT 6.1; WOW64; Trident/7.0; rv:11.0) like Gecko'),
(117, '55acd392e5e76', 'sumeet.nihalani@newsrise.org', 'Sumeet Nihalani', '91', '9049294103', 'C', '2015-07-20 10:55:14', '0', NULL, '1', '2015-07-20 10:56:06', NULL, '115.112.159.6', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/43.0.2357.134 Safari/537.36'),
(118, '55acd392e5e76', 'dk3047@gmail.com', 'dinesh', '', '', 'I', '2015-07-20 10:55:14', '1', '2015-07-20 10:58:41', '1', '2015-07-20 10:58:47', NULL, '14.141.27.97', 'Mozilla/5.0 (Windows NT 6.1; WOW64; Trident/7.0; rv:11.0) like Gecko');

-- --------------------------------------------------------

--
-- Table structure for table `order_details`
--

CREATE TABLE IF NOT EXISTS `order_details` (
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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=24 ;

--
-- Dumping data for table `order_details`
--

INSERT INTO `order_details` (`id`, `order_id`, `plan_id`, `plan_name`, `currency_type`, `price`, `quantity`, `amount`, `service_tax_percent`, `service_tax_amount`, `total_amount`, `conversion_rate`) VALUES
(1, 'ord143409003311', 1, 'Quadridge LetsMeet', '$', 0.00, 6, 0.00, 0.00, 0.00, 0.00, 0.00),
(2, 'ord143409047311', 1, 'Quadridge LetsMeet', '$', 0.00, 6, 0.00, 0.00, 0.00, 0.00, 0.00),
(3, 'ord143409048811', 1, 'Quadridge LetsMeet', '$', 0.00, 6, 0.00, 0.00, 0.00, 0.00, 0.00),
(4, 'ord143409050911', 1, 'Quadridge LetsMeet', '$', 0.00, 6, 0.00, 0.00, 0.00, 0.00, 0.00),
(5, 'ord143409052411', 1, 'Quadridge LetsMeet', '$', 0.00, 6, 0.00, 0.00, 0.00, 0.00, 0.00),
(6, 'ord143409053611', 1, 'Quadridge LetsMeet', '$', 0.00, 6, 0.00, 0.00, 0.00, 0.00, 0.00),
(7, 'ord143409056411', 1, 'Quadridge LetsMeet', '$', 0.00, 6, 0.00, 0.00, 0.00, 0.00, 0.00),
(8, 'ord143516159521', 8, 'LetsMeet PRO', 'INR', 4000.00, 6, 21033.60, 12.36, 2966.40, 24000.00, 0.00),
(9, 'ord143538904871', 7, 'LetsMeet LITE', 'INR', 3000.00, 1, 2580.00, 14.00, 420.00, 3000.00, 0.00),
(10, 'ord143538906171', 7, 'LetsMeet LITE', 'INR', 3000.00, 1, 2580.00, 14.00, 420.00, 3000.00, 0.00),
(11, 'ord143616381241', 8, 'LetsMeet PRO', 'INR', 4000.00, 2, 6880.00, 14.00, 1120.00, 8000.00, 0.00),
(12, 'ord143616382741', 8, 'LetsMeet PRO', 'INR', 4000.00, 2, 6880.00, 14.00, 1120.00, 8000.00, 0.00),
(13, 'ord143616384941', 8, 'LetsMeet PRO', 'INR', 4000.00, 2, 6880.00, 14.00, 1120.00, 8000.00, 0.00),
(14, 'ord143635472411', 5, 'LetsMeet Trial 15', '$', 0.00, 1, 0.00, 0.00, 0.00, 0.00, 0.00),
(15, 'ord143643148111', 1, 'Quadridge LetsMeet', '$', 0.00, 6, 0.00, 0.00, 0.00, 0.00, 0.00),
(16, 'ord143643581181', 6, 'LetsMeet Silver Trial 15', '$', 0.00, 1, 0.00, 0.00, 0.00, 0.00, 0.00),
(17, 'ord143643582481', 6, 'LetsMeet Silver Trial 15', '$', 0.00, 1, 0.00, 0.00, 0.00, 0.00, 0.00),
(18, 'ord143643583681', 6, 'LetsMeet Silver Trial 15', '$', 0.00, 1, 0.00, 0.00, 0.00, 0.00, 0.00),
(19, 'ord143643586381', 6, 'LetsMeet Silver Trial 15', '$', 0.00, 1, 0.00, 0.00, 0.00, 0.00, 0.00),
(20, 'ord143643587581', 6, 'LetsMeet Silver Trial 15', '$', 0.00, 1, 0.00, 0.00, 0.00, 0.00, 0.00),
(21, 'ord143688233991', 7, 'LetsMeet LITE', 'INR', 3000.00, 1, 2580.00, 14.00, 420.00, 3000.00, 0.00),
(22, 'ord143693723951', 8, 'LetsMeet PRO', 'INR', 4000.00, 12, 41280.00, 14.00, 6720.00, 48000.00, 0.00),
(23, 'ord1437470901102', 8, 'LetsMeet PRO', 'INR', 4000.00, 1, 3440.00, 14.00, 560.00, 4000.00, 0.00);

-- --------------------------------------------------------

--
-- Table structure for table `order_master`
--

CREATE TABLE IF NOT EXISTS `order_master` (
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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=24 ;

--
-- Dumping data for table `order_master`
--

INSERT INTO `order_master` (`id`, `subscriber_id`, `email_address`, `order_id`, `payment_id`, `transaction_id`, `payment_gateway_name`, `payment_from`, `order_status`, `order_date`, `order_date_gmt`, `ip_address`) VALUES
(1, 'cl00001', 'mitesh.shah@quadridge.com', 'ord143409003311', 'AP14340900330', 'AT14340900330', 'ADMIN', 'Web', 'completed', '2015-06-12 11:50:33', '2015-06-12 06:20:33', '49.248.5.250'),
(2, 'cl00001', 'mitesh.shah@quadridge.com', 'ord143409047311', 'AP14340904730', 'AT14340904730', 'ADMIN', 'Web', 'completed', '2015-06-12 11:57:53', '2015-06-12 06:27:53', '49.248.5.250'),
(3, 'cl00001', 'mitesh.shah@quadridge.com', 'ord143409048811', 'AP14340904880', 'AT14340904880', 'ADMIN', 'Web', 'completed', '2015-06-12 11:58:08', '2015-06-12 06:28:08', '49.248.5.250'),
(4, 'cl00001', 'mitesh.shah@quadridge.com', 'ord143409050911', 'AP14340905090', 'AT14340905090', 'ADMIN', 'Web', 'completed', '2015-06-12 11:58:29', '2015-06-12 06:28:29', '49.248.5.250'),
(5, 'cl00001', 'mitesh.shah@quadridge.com', 'ord143409052411', 'AP14340905240', 'AT14340905240', 'ADMIN', 'Web', 'completed', '2015-06-12 11:58:44', '2015-06-12 06:28:44', '49.248.5.250'),
(6, 'cl00001', 'mitesh.shah@quadridge.com', 'ord143409053611', 'AP14340905360', 'AT14340905360', 'ADMIN', 'Web', 'completed', '2015-06-12 11:58:56', '2015-06-12 06:28:56', '49.248.5.250'),
(7, 'cl00001', 'mitesh.shah@quadridge.com', 'ord143409056411', 'AP14340905640', 'AT14340905640', 'ADMIN', 'Web', 'completed', '2015-06-12 11:59:24', '2015-06-12 06:29:24', '49.248.5.250'),
(8, 'cl00002', 'adsilva@epicchannel.com', 'ord143516159521', 'AP14351615950', 'AT14351615950', 'ADMIN', 'Web', 'completed', '2015-05-26 17:59:08', '2015-05-26 12:29:08', '49.248.5.250'),
(9, 'cl00007', 'zahir@labradogstudios.com', 'ord143538904871', 'AP14353890480', 'AT14353890480', 'ADMIN', 'Web', 'completed', '2015-06-27 12:40:48', '2015-06-27 07:10:48', '182.59.214.101'),
(10, 'cl00007', 'zahir@labradogstudios.com', 'ord143538906171', 'AP14353890610', 'AT14353890610', 'ADMIN', 'Web', 'completed', '2015-06-27 12:41:01', '2015-06-27 07:11:01', '182.59.214.101'),
(11, 'cl00004', 'manoj.dehankar@timesgroup.com', 'ord143616381241', 'AP14361638120', 'AT14361638120', 'ADMIN', 'Web', 'completed', '2015-07-06 11:53:32', '2015-07-06 06:23:32', '49.248.5.250'),
(12, 'cl00004', 'manoj.dehankar@timesgroup.com', 'ord143616382741', 'AP14361638270', 'AT14361638270', 'ADMIN', 'Web', 'completed', '2015-07-06 11:53:47', '2015-07-06 06:23:47', '49.248.5.250'),
(13, 'cl00004', 'manoj.dehankar@timesgroup.com', 'ord143616384941', 'AP14361638490', 'AT14361638490', 'ADMIN', 'Web', 'completed', '2015-07-06 11:54:09', '2015-07-06 06:24:09', '49.248.5.250'),
(14, 'cl00001', 'mitesh.shah@quadridge.com', 'ord143635472411', 'AP14363547240', 'AT14363547240', 'ADMIN', 'Web', 'completed', '2015-07-08 16:55:24', '2015-07-08 11:25:24', '49.248.5.250'),
(15, 'cl00001', 'mitesh.shah@quadridge.com', 'ord143643148111', 'AP14364314820', 'AT14364314820', 'ADMIN', 'Web', 'completed', '2015-07-09 14:14:41', '2015-07-09 08:44:41', '49.248.5.250'),
(16, 'cl00008', 'mitesh.a.shah@outlook.com', 'ord143643581181', 'AP14364358110', 'AT14364358110', 'ADMIN', 'Web', 'completed', '2015-07-09 15:26:50', '2015-07-09 09:56:50', '49.248.5.250'),
(17, 'cl00008', 'mitesh.a.shah@outlook.com', 'ord143643582481', 'AP14364358240', 'AT14364358240', 'ADMIN', 'Web', 'completed', '2015-07-09 15:27:04', '2015-07-09 09:57:04', '49.248.5.250'),
(18, 'cl00008', 'mitesh.a.shah@outlook.com', 'ord143643583681', 'AP14364358360', 'AT14364358360', 'ADMIN', 'Web', 'completed', '2015-07-09 15:27:16', '2015-07-09 09:57:16', '49.248.5.250'),
(19, 'cl00008', 'mitesh.a.shah@outlook.com', 'ord143643586381', 'AP14364358630', 'AT14364358630', 'ADMIN', 'Web', 'completed', '2015-07-09 15:27:43', '2015-07-09 09:57:43', '49.248.5.250'),
(20, 'cl00008', 'mitesh.a.shah@outlook.com', 'ord143643587581', 'AP14364358750', 'AT14364358750', 'ADMIN', 'Web', 'completed', '2015-07-09 15:27:55', '2015-07-09 09:57:55', '49.248.5.250'),
(21, 'cl00009', 'pradeep@axsys.co.uk', 'ord143688233991', 'AP14368823390', 'AT14368823390', 'ADMIN', 'Web', 'completed', '2015-07-14 19:28:59', '2015-07-14 13:58:59', '49.248.5.250'),
(22, 'cl00005', 'sumeet.nihalani@newsrise.org', 'ord143693723951', 'AP14369372390', 'AT14369372390', 'ADMIN', 'Web', 'completed', '2015-07-15 10:43:59', '2015-07-15 05:13:59', '49.248.5.250'),
(23, 'cl00010', 'jakes@primesec.com', 'ord1437470901102', 'AP14374709010', 'AT14374709010', 'ADMIN', 'Web', 'completed', '2015-07-21 14:58:21', '2015-07-21 09:28:21', '49.248.5.250');

-- --------------------------------------------------------

--
-- Table structure for table `partner_details`
--

CREATE TABLE IF NOT EXISTS `partner_details` (
  `partner_id` varchar(25) NOT NULL COMMENT 'Unique identification number of partner',
  `email_address` varchar(100) NOT NULL COMMENT 'Email Address of partner',
  `password` varchar(50) DEFAULT NULL COMMENT 'Password of partner',
  `partner_name` varchar(50) DEFAULT NULL COMMENT 'Partner name',
  `partner_creation_dtm` datetime DEFAULT NULL COMMENT 'Date of creation when partner is created',
  `status` enum('0','1','2','3') NOT NULL DEFAULT '0' COMMENT 'Status of user 0=Pending, 1=Active, 2=Deative, 3=Deleted',
  PRIMARY KEY (`partner_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `partner_details`
--

INSERT INTO `partner_details` (`partner_id`, `email_address`, `password`, `partner_name`, `partner_creation_dtm`, `status`) VALUES
('pr00001', 'mitesh.shah@quadridge.com', 'cdbe75eb932913e135ed90941f1b3789', 'Quadridge Technologies Private Limited', '2015-06-09 07:26:15', '1');

-- --------------------------------------------------------

--
-- Table structure for table `password_request_details`
--

CREATE TABLE IF NOT EXISTS `password_request_details` (
  `request_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Unique identification number of password request',
  `requested_by` varchar(25) NOT NULL COMMENT 'Unique identification number of user',
  `email_address` varchar(100) NOT NULL COMMENT 'Email Address of user',
  `request_datetime` datetime NOT NULL COMMENT 'Actual datetime of request of change password',
  PRIMARY KEY (`request_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `password_request_details`
--

INSERT INTO `password_request_details` (`request_id`, `requested_by`, `email_address`, `request_datetime`) VALUES
(7, 'usr0000005', 'mitesh.shah@quadridge.com', '2015-07-11 18:10:14');

-- --------------------------------------------------------

--
-- Table structure for table `personal_contact_details`
--

CREATE TABLE IF NOT EXISTS `personal_contact_details` (
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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=17 ;

--
-- Dumping data for table `personal_contact_details`
--

INSERT INTO `personal_contact_details` (`personal_contact_id`, `contact_nick_name`, `contact_first_name`, `contact_last_name`, `contact_email_address`, `contact_idd_code`, `contact_mobile_number`, `contact_group_name`, `user_id`, `personal_contact_status`) VALUES
(1, 'Zahir', 'Zahir', 'Mirza', 'mirza.zaheer@gmail.com', '91', '9820605773', 'Labradog', 'usr0000011', '1'),
(2, 'manojd', 'manoj', 'dehankar', 'mdehankar@gmail.com', '91', '9820556077', 'Test group', 'usr0000012', '1'),
(3, 'Ramesh Nair', 'Ramesh', 'Nair', 'Ramesh.Nair@timesgroup.com', '91', '9833193738', 'Test group', 'usr0000012', '1'),
(4, 'Piyush Puri', 'Piyush', 'Puri', 'Piyush.Puri@timesgroup.com', '91', '7738394669', 'Test group', 'usr0000012', '1'),
(5, 'Vishal Vora', 'Vishal', 'Vora', 'vishal.vora@timesgroup.com', '91', '9820214882', 'Test group', 'usr0000012', '1'),
(6, 'Sriram Kilambi', 'Sriram', 'Kilambi', 'sriram.kilambi@timesgroup.com', '91', '9833311377', 'Test group', 'usr0000012', '1'),
(7, 'Jitendra Kothari', 'Jitendra', 'Kothari', 'Jitendra.Kothari@timesgroup.com', '91', '9820422271', 'Test group', 'usr0000012', '1'),
(8, 'Anant Pandit', 'Anant', 'Pandit', 'Anant.Pandit@timesgroup.com', '91', '9833793493', 'Test group', 'usr0000012', '1'),
(9, 'Atul Kurkure', 'Atul', 'Kurkure', 'Atul.Kurkure@timesgroup.com', '91', '9820158099', 'Test group', 'usr0000012', '1'),
(10, 'Jermy John', 'Jermy', 'John', 'jermy.john@gmail.com', '91', '9967195718', 'Test group', 'usr0000012', '1'),
(11, 'jermy _1', 'jermy', 'john', 'jermy.john@timesgroup.com', '91', '9967195718', 'Test group', 'usr0000012', '1'),
(12, 'ravindra manrekar', 'Ravindra', 'Manrekar', 'Ravindra.Manerkar@timesgroup.com', '91', '9740733551', 'Test group', 'usr0000012', '1'),
(13, 'Dominic', 'Dominic', 'Vijay', 'Dominic.Vijay@timesgroup.com', '91', '8042200418', 'Test group', 'usr0000012', '1'),
(14, 'ganesh pawar', 'ganesh', 'pawar', 'ganesh.pawar@timesgroup.com', '91', '9820974241', 'Test group', 'usr0000012', '1'),
(15, 'vivek', 'Vivek', 'kalsekar', 'vivek.kalsekar@timesgroup.com', '91', '9967078278', 'Test group', 'usr0000012', '1'),
(16, 'Jayesh Nishane', 'Jayesh', 'Nishane', 'jnishane@in.ibm.com', '91', '9099002479', 'Adani IBM', 'usr0000005', '1');

-- --------------------------------------------------------

--
-- Table structure for table `plan_details`
--

CREATE TABLE IF NOT EXISTS `plan_details` (
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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

--
-- Dumping data for table `plan_details`
--

INSERT INTO `plan_details` (`plan_id`, `plan_name`, `plan_desc`, `plan_for`, `plan_type`, `number_of_sessions`, `number_of_mins_per_sessions`, `plan_period`, `number_of_invitee`, `meeting_recording`, `disk_space`, `is_free`, `plan_cost_inr`, `plan_cost_oth`, `concurrent_sessions`, `talk_time_mins`, `plan_status`, `plan_creation_dtm`, `plan_keyword`, `autorenew_flag`, `display_order`, `is_multiple`) VALUES
(1, 'Quadridge LetsMeet', 'LetsMeet Default Plan for Quadridge Internal People', 'OTH', 'U', 0, 0, 30, 20, 'true', 0, '1', 0.00, 0.00, 0, NULL, '1', '2015-06-12 11:44:48', 'LMQUAD', '0', 60, 'no'),
(2, 'LetsMeet Trial 1', 'LetsMeet Trial Plan for 1days for Demo', 'ENT', 'S', 1, 0, 1, 3, 'false', 0, '1', 0.00, 0.00, 1, NULL, '1', '2015-01-02 19:16:08', 'LMTRY1', '0', 10, 'no'),
(3, 'LetsMeet Trial 3', 'LetsMeet Trial Plan for 3days for Demo', 'ENT', 'S', 5, 0, 3, 5, 'false', 0, '1', 0.00, 0.00, 1, NULL, '1', '2015-01-02 19:16:08', 'LMTRY3', '0', 20, 'no'),
(4, 'LetsMeet Trial 7', 'LetsMeet Trial Plan for 7days for Demo', 'ENT', 'S', 10, 0, 7, 5, 'false', 0, '1', 0.00, 0.00, 1, NULL, '1', '2015-01-02 19:16:08', 'LMTRY7', '0', 30, 'no'),
(5, 'LetsMeet Trial 15', 'LetsMeet Trial Plan for 15days for Demo', 'ENT', 'S', 15, 0, 15, 5, 'false', 0, '1', 0.00, 0.00, 1, NULL, '1', '2015-01-02 19:16:08', 'LMTRY15', '0', 40, 'no'),
(6, 'LetsMeet Silver Trial 15', 'LetsMeet Silver Trial Plan for 15days for Demo', 'ENT', 'S', 15, 0, 15, 10, 'true', 0, '1', 0.00, 0.00, 1, NULL, '1', '2015-01-02 19:16:08', 'LMTRYS15', '0', 50, 'no'),
(7, 'LetsMeet LITE', 'LetsMeet LITE', 'ENT', 'U', 0, 0, 30, 5, 'true', 0, '0', 3000.00, 0.00, 1, 0, '1', '2015-06-10 21:24:21', 'LMLITE', '0', 1, 'no'),
(8, 'LetsMeet PRO', 'LetsMeet PRO', 'ENT', 'U', 0, 0, 30, 15, 'true', 0, '0', 4000.00, 0.00, 1, 0, '1', '2015-06-10 21:24:21', 'LMPRO', '0', 2, 'no'),
(9, 'LetsMeet ULTRA', 'LetsMeet ULTRA', 'ENT', 'U', 0, 0, 30, 25, 'true', 0, '0', 5000.00, 0.00, 1, 0, '1', '2015-06-10 21:24:21', 'LMULTRA', '0', 3, 'no');

-- --------------------------------------------------------

--
-- Table structure for table `schedule_details`
--

CREATE TABLE IF NOT EXISTS `schedule_details` (
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

--
-- Dumping data for table `schedule_details`
--

INSERT INTO `schedule_details` (`schedule_id`, `user_id`, `schedule_status`, `schedule_creation_time`, `schedule_status_update_time`, `meeting_timestamp_gmt`, `meeting_timestamp_local`, `meeting_title`, `meeting_agenda`, `meeting_timezone`, `meeting_gmt`, `bbb_create_time`, `meeting_start_time`, `meeting_end_time`, `attendee_password`, `moderator_password`, `welcome_message`, `voice_bridge`, `web_voice`, `max_participants`, `record_flag`, `meeting_duration`, `meta_tags`, `email_reminder_flag`, `email_reminder_status`, `sms_reminder_flag`, `sms_reminder_status`, `bbb_message`, `meeting_instance`, `subscription_id`) VALUES
('55655e21d8457', 'usr0000009', '2', '2015-05-27 06:03:13', NULL, '2015-05-27 06:03:13', '2015-05-27 11:33:13', 'test', 'NULL', 'Asia/Kolkata', '05:30', '1432706463859', '2015-05-27 06:03:19', '2015-05-27 06:19:01', 'appwd', 'mppwd', 'NULL', '77609', '77609', 1, 'true', 0, 'NULL', 'Y', '0', 'N', '0', '', 'http://lmmeeting.quadridge.com', 8),
('556568aad4020', 'usr0000009', '2', '2015-05-27 06:48:10', NULL, '2015-05-27 06:48:10', '2015-05-27 12:18:10', 'winjit', 'NULL', 'Asia/Kolkata', '05:30', '1432709166572', '2015-05-27 06:48:22', '2015-05-27 07:04:01', 'appwd', 'mppwd', 'NULL', '75558', '75558', 2, 'true', 0, 'NULL', 'Y', '0', 'N', '0', '', 'http://lmmeeting.quadridge.com', 8),
('556856f952d03', 'usr0000009', '2', '2015-05-29 12:09:29', NULL, '2015-05-29 12:09:29', '2015-05-29 17:39:29', 'hhh', 'NULL', 'Asia/Kolkata', '05:30', '1432901237864', '2015-05-29 12:09:37', '2015-05-29 12:25:01', 'appwd', 'mppwd', 'NULL', '76934', '76934', 1, 'true', 0, 'NULL', 'Y', '0', 'N', '0', '', 'http://lmmeeting.quadridge.com', 8),
('556d9574f1a93', 'usr0000009', '2', '2015-06-02 11:37:24', NULL, '2015-06-02 11:37:24', '2015-06-02 17:07:24', 'winjit 02/6/15', 'NULL', 'Asia/Kolkata', '05:30', '1433244905103', '2015-06-02 11:37:32', '2015-06-02 12:20:01', 'appwd', 'mppwd', 'NULL', '70965', '70965', 2, 'true', 0, 'NULL', 'Y', '0', 'N', '0', '', 'http://lmmeeting.quadridge.com', 8),
('557abcc164904', 'usr0000007', '2', '2015-06-12 11:04:33', NULL, '2015-06-12 11:04:33', '2015-06-12 16:34:33', 'test 2', 'NULL', 'Asia/Kolkata', '05:30', '1434107091471', '2015-06-12 11:04:50', '2015-06-12 11:08:01', 'appwd', 'mppwd', 'NULL', '71502', '71502', 1, 'true', 0, 'NULL', 'Y', '0', 'N', '0', '', 'http://conference.eletsmeet.com', 5),
('557acee4da13e', 'usr0000006', '2', '2015-06-12 12:21:56', NULL, '2015-06-12 12:21:56', '2015-06-12 17:51:56', 'Demo On eLetsMeet', 'NULL', 'Asia/Kolkata', '05:30', '1434111721753', '2015-06-12 12:22:01', '2015-06-12 12:25:01', 'appwd', 'mppwd', 'NULL', '72212', '72212', 1, 'true', 0, 'NULL', 'Y', '0', 'N', '0', '', 'http://conference.eletsmeet.com', 7),
('557ad3190bbaa', 'usr0000006', '2', '2015-06-12 12:39:52', NULL, '2015-06-12 12:39:52', '2015-06-12 18:09:52', 'EletsMeet Timer Setting', 'NULL', 'Asia/Kolkata', '05:30', '1434112796106', '2015-06-12 12:39:55', '2015-06-12 13:13:01', 'appwd', 'mppwd', 'NULL', '79361', '79361', 1, 'true', 0, 'NULL', 'Y', '0', 'N', '0', '', 'http://conference.eletsmeet.com', 7),
('557bfe78d014d', 'usr0000006', '2', '2015-06-13 09:57:12', NULL, '2015-06-13 09:57:12', '2015-06-13 15:27:12', 'Demo on eLetsMeet.com', 'NULL', 'Asia/Kolkata', '05:30', '1434190004709', '2015-06-13 10:06:45', '2015-06-13 10:36:01', 'appwd', 'mppwd', 'NULL', '76718', '76718', 3, 'true', 0, 'NULL', 'Y', '0', 'N', '0', '', 'http://conference.eletsmeet.com', 7),
('557c024bbcd88', 'usr0000006', '3', '2015-06-13 10:13:31', '2015-06-13 10:14:33', '2015-06-13 10:30:00', '2015-06-13 16:00:00', 'Demo Meeting for Cancellation', 'NULL', 'Asia/Kolkata', '05:30', NULL, NULL, NULL, 'appwd', 'mppwd', 'NULL', '71112', '71112', 2, 'true', 0, 'NULL', 'Y', '0', 'N', '0', NULL, 'http://conference.eletsmeet.com', 7),
('557fb42793086', 'usr0000006', '2', '2015-06-16 05:29:10', NULL, '2015-06-16 05:29:10', '2015-06-16 10:59:10', 'Demo on eLetsMeet', 'NULL', 'Asia/Kolkata', '05:30', '1434432989025', '2015-06-16 05:36:31', '2015-06-16 05:34:02', 'appwd', 'mppwd', 'NULL', '79104', '79104', 1, 'true', 0, 'NULL', 'Y', '0', 'N', '0', '', 'http://conference.eletsmeet.com', 7),
('557fb65babeef', 'usr0000006', '2', '2015-06-16 05:38:35', NULL, '2015-06-16 05:38:35', '2015-06-16 11:08:35', 'Demo another meeting', 'NULL', 'Asia/Kolkata', '05:30', '1434436004533', '2015-06-16 06:26:47', '2015-06-16 06:26:01', 'appwd', 'mppwd', 'NULL', '78544', '78544', 1, 'true', 0, 'NULL', 'Y', '0', 'N', '0', '', 'http://conference.eletsmeet.com', 7),
('558125b70d0d2', 'usr0000006', '2', '2015-06-17 07:45:58', NULL, '2015-06-17 07:45:58', '2015-06-17 13:15:58', 'Demo on eLetsMeet for IP', 'NULL', 'Asia/Kolkata', '05:30', '1434527190733', '2015-06-17 07:46:34', '2015-06-17 08:08:01', 'appwd', 'mppwd', 'NULL', '79454', '79454', 2, 'true', 0, 'NULL', 'Y', '0', 'N', '0', '', 'http://conference.eletsmeet.com', 7),
('55890c3ec7ab3', 'usr0000006', '2', '2015-06-23 07:35:26', NULL, '2015-06-23 07:35:26', '2015-06-23 13:05:26', 'Demo Meeting for Recoridng Testing', 'NULL', 'Asia/Kolkata', '05:30', '1435044962720', '2015-06-23 07:36:02', '2015-06-23 07:42:01', 'appwd', 'mppwd', 'NULL', '76553', '76553', 1, 'true', 0, 'NULL', 'Y', '0', 'N', '0', '', 'http://conference.eletsmeet.com', 7),
('558a84c574be4', 'usr0000006', '2', '2015-06-24 10:21:56', NULL, '2015-06-24 10:21:56', '2015-06-24 15:51:56', 'Voice Bridge Testing Meeting 1', 'NULL', 'Asia/Kolkata', '05:30', '1435141326772', '2015-06-24 10:22:07', '2015-06-24 10:56:01', 'appwd', 'mppwd', 'NULL', '79933', '79933', 4, 'true', 0, 'NULL', 'Y', '0', 'N', '0', '', 'http://conference.eletsmeet.com', 7),
('558ce26beaffe', 'usr0000006', '2', '2015-06-26 05:26:03', NULL, '2015-06-26 05:26:03', '2015-06-26 10:56:03', 'Demo Meeting on eLetsMeet', 'NULL', 'Asia/Kolkata', '05:30', '1435296402375', '2015-06-26 05:26:44', '2015-06-26 05:52:01', 'appwd', 'mppwd', 'NULL', '72830', '72830', 2, 'true', 0, 'NULL', 'Y', '0', 'N', '0', '', 'http://conference.eletsmeet.com', 7),
('558e5619bd013', 'usr0000004', '2', '2015-06-27 07:51:53', NULL, '2015-06-27 07:51:53', '2015-06-27 13:21:53', 'TEST MEETING', 'NULL', 'Asia/Kolkata', '05:30', '1435391515263', '2015-06-27 07:51:58', '2015-06-27 07:52:01', 'appwd', 'mppwd', 'NULL', '71579', '71579', 1, 'true', 0, 'NULL', 'Y', '0', 'N', '0', '', 'http://conference.eletsmeet.com', 3),
('558fb03205416', 'usr0000002', '2', '2015-06-28 08:28:33', NULL, '2015-06-28 08:28:33', '2015-06-28 13:58:33', 'call with ajy', 'NULL', 'Asia/Kolkata', '05:30', '1435480118558', '2015-06-28 08:28:43', '2015-06-28 08:32:01', 'appwd', 'mppwd', 'NULL', '74956', '74956', 1, 'true', 0, 'NULL', 'Y', '0', 'N', '0', '', 'http://conference.eletsmeet.com', 6),
('558fb252394b8', 'usr0000002', '2', '2015-06-28 08:37:38', NULL, '2015-06-28 08:37:38', '2015-06-28 14:07:38', 'call with ajay', 'NULL', 'Asia/Kolkata', '05:30', '1435480663639', '2015-06-28 08:37:48', '2015-06-28 08:48:01', 'appwd', 'mppwd', 'NULL', '70497', '70497', 1, 'true', 0, 'NULL', 'Y', '0', 'N', '0', '', 'http://conference.eletsmeet.com', 6),
('558fb5803d476', 'usr0000002', '2', '2015-06-28 08:51:12', NULL, '2015-06-28 08:51:12', '2015-06-28 14:21:12', 'self', 'NULL', 'Asia/Kolkata', '05:30', '1435481475398', '2015-06-28 08:51:19', '2015-06-28 08:55:01', 'appwd', 'mppwd', 'NULL', '73999', '73999', 1, 'true', 0, 'NULL', 'Y', '0', 'N', '0', '', 'http://conference.eletsmeet.com', 6),
('559a3ae344b64', 'usr0000009', '2', '2015-07-06 08:22:58', NULL, '2015-07-06 08:22:58', '2015-07-06 13:52:58', 'test', 'NULL', 'Asia/Kolkata', '05:30', '1436170975467', '2015-07-06 08:23:07', '2015-07-06 09:54:01', 'appwd', 'mppwd', 'NULL', '72654', '72654', 1, 'true', 0, 'NULL', 'Y', '0', 'N', '0', '', 'http://conference.eletsmeet.com', 8),
('559a43ffd49ad', 'usr0000010', '2', '2015-07-06 09:01:51', NULL, '2015-07-06 09:01:51', '2015-07-06 14:31:51', 'kk', 'NULL', 'Asia/Kolkata', '05:30', '1436173304260', '2015-07-06 09:01:56', '2015-07-06 09:28:01', 'appwd', 'mppwd', 'NULL', '76144', '76144', 1, 'true', 0, 'NULL', 'Y', '0', 'N', '0', '', 'http://conference.eletsmeet.com', 9),
('559ccef3bfc22', 'usr0000007', '2', '2015-07-08 07:19:15', NULL, '2015-07-08 07:19:15', '2015-07-08 12:49:15', 'test 1', 'NULL', 'Asia/Kolkata', '05:30', '1436339955405', '2015-07-08 07:19:29', '2015-07-08 07:45:01', 'appwd', 'mppwd', 'NULL', '75582', '75582', 1, 'true', 0, 'NULL', 'Y', '0', 'N', '0', '', 'http://conference.eletsmeet.com', 5),
('559e0b39aab5d', 'usr0000006', '2', '2015-07-09 05:48:41', NULL, '2015-07-09 05:48:41', '2015-07-09 11:18:41', 'demo Meeting', 'NULL', 'Asia/Kolkata', '05:30', '1436420945248', '2015-07-09 05:49:20', '2015-07-09 06:20:01', 'appwd', 'mppwd', 'NULL', '73925', '73925', 1, 'true', 0, 'NULL', 'Y', '0', 'N', '0', '', 'http://conference.eletsmeet.com', 7),
('559e11bdd77d2', 'usr0000006', '2', '2015-07-09 06:16:29', NULL, '2015-07-09 06:16:29', '2015-07-09 11:46:29', 'testing kfor adani POC', 'NULL', 'Asia/Kolkata', '05:30', '1436422592963', '2015-07-09 06:16:48', '2015-07-09 06:17:01', 'appwd', 'mppwd', 'NULL', '76588', '76588', 2, 'true', 0, 'NULL', 'Y', '0', 'N', '0', '', 'http://conference.eletsmeet.com', 7),
('559e364be4211', 'usr0000005', '2', '2015-07-09 08:52:27', NULL, '2015-07-09 09:10:00', '2015-07-09 14:40:00', 'LetsMeet Demo Meeting', 'NULL', 'Asia/Kolkata', '05:30', '1436432917090', '2015-07-09 09:08:52', '2015-07-09 09:27:01', 'appwd', 'mppwd', 'NULL', '74070', '74070', 1, 'true', 0, 'NULL', 'Y', '0', 'N', '0', '', 'http://conference.eletsmeet.com', 15),
('559e68123e7f0', 'usr0000003', '2', '2015-07-09 12:24:49', NULL, '2015-07-09 12:24:49', '2015-07-09 17:54:49', 'LetsMeet', 'NULL', 'Asia/Kolkata', '05:30', '1436444680762', '2015-07-09 12:24:56', '2015-07-09 13:32:01', 'appwd', 'mppwd', 'NULL', '76300', '76300', 2, 'true', 0, 'NULL', 'Y', '0', 'N', '0', '', 'http://conference.eletsmeet.com', 1),
('559f5c96eb2c5', 'usr0000007', '2', '2015-07-10 05:48:06', NULL, '2015-07-10 06:00:00', '2015-07-10 11:30:00', 'trail Demo', 'NULL', 'Asia/Kolkata', '05:30', '1436507693806', '2015-07-10 05:55:10', '2015-07-10 06:39:01', 'appwd', 'mppwd', 'NULL', '70990', '70990', 2, 'true', 0, 'NULL', 'Y', '0', 'N', '0', '', 'http://conference.eletsmeet.com', 5),
('559f93c240436', 'usr0000010', '2', '2015-07-10 09:43:29', NULL, '2015-07-10 09:43:29', '2015-07-10 15:13:29', 'Kaii', 'NULL', 'Asia/Kolkata', '05:30', '1436521398959', '2015-07-10 09:43:35', '2015-07-10 10:02:01', 'appwd', 'mppwd', 'NULL', '72174', '72174', 1, 'true', 0, 'NULL', 'Y', '0', 'N', '0', '', 'http://conference.eletsmeet.com', 9),
('55a15da618479', 'usr0000005', '4', '2015-07-11 18:17:09', NULL, '2015-07-11 18:17:09', '2015-07-11 23:47:09', 'test mail on gmail', 'NULL', 'Asia/Kolkata', '05:30', NULL, NULL, NULL, 'appwd', 'mppwd', 'NULL', '78768', '78768', 1, 'true', 0, 'NULL', 'Y', '0', 'N', '0', NULL, 'http://conference.eletsmeet.com', 15),
('55a1605e3ea45', 'usr0000005', '4', '2015-07-11 18:28:46', NULL, '2015-07-11 18:28:46', '2015-07-11 23:58:46', 'test again', 'NULL', 'Asia/Kolkata', '05:30', NULL, NULL, NULL, 'appwd', 'mppwd', 'NULL', '73670', '73670', 1, 'true', 0, 'NULL', 'Y', '0', 'N', '0', NULL, 'http://conference.eletsmeet.com', 15),
('55a1640cb6fc6', 'usr0000005', '4', '2015-07-11 18:44:28', NULL, '2015-07-11 18:44:28', '2015-07-12 00:14:28', 'demo', 'NULL', 'Asia/Kolkata', '05:30', NULL, NULL, NULL, 'appwd', 'mppwd', 'NULL', '77753', '77753', 1, 'true', 0, 'NULL', 'Y', '0', 'N', '0', NULL, 'http://conference.eletsmeet.com', 15),
('55a35cb9a6103', 'usr0000017', '2', '2015-07-13 06:37:45', NULL, '2015-07-13 06:37:45', '2015-07-13 12:07:45', 'test1', 'NULL', 'Asia/Kolkata', '05:30', '1436769456938', '2015-07-13 06:37:57', '2015-07-13 07:05:01', 'appwd', 'mppwd', 'NULL', '77806', '77806', 2, 'true', 0, 'NULL', 'Y', '0', 'N', '0', '', 'http://conference.eletsmeet.com', 17),
('55a4af7e35ce0', 'usr0000007', '2', '2015-07-14 06:43:09', NULL, '2015-07-14 06:43:09', '2015-07-14 12:13:09', 'Test', 'NULL', 'Asia/Kolkata', '05:30', '1436856173845', '2015-07-14 06:43:15', '2015-07-14 07:18:02', 'appwd', 'mppwd', 'NULL', '75108', '75108', 1, 'true', 0, 'NULL', 'Y', '0', 'N', '0', '', 'http://conference.eletsmeet.com', 5),
('55a51d47473a8', 'usr0000005', '2', '2015-07-14 14:31:34', NULL, '2015-07-14 14:31:34', '2015-07-14 20:01:34', 'Demo Meeting', 'NULL', 'Asia/Kolkata', '05:30', '1436884276689', '2015-07-14 14:31:38', '2015-07-14 14:32:01', 'appwd', 'mppwd', 'NULL', '78843', '78843', 1, 'true', 0, 'NULL', 'Y', '0', 'N', '0', '', 'http://conference.eletsmeet.com', 15),
('55a5fb0c6902a', 'usr0000016', '2', '2015-07-15 06:17:48', NULL, '2015-07-15 07:00:00', '2015-07-15 12:30:00', 'test', 'NULL', 'Asia/Kolkata', '05:30', '1436942754751', '2015-07-15 06:46:16', '2015-07-15 07:00:02', 'appwd', 'mppwd', 'NULL', '71611', '71611', 6, 'true', 0, 'NULL', 'Y', '0', 'N', '0', '', 'http://conference.eletsmeet.com', 16),
('55a60ced432a4', 'usr0000005', '2', '2015-07-15 07:34:05', NULL, '2015-07-15 07:34:05', '2015-07-15 13:04:05', 'Demo LetsMeet Meeting', 'NULL', 'Asia/Kolkata', '05:30', '1436945636446', '2015-07-15 07:34:18', '2015-07-15 09:41:02', 'appwd', 'mppwd', 'NULL', '79407', '79407', 1, 'true', 0, 'NULL', 'Y', '0', 'N', '0', '', 'http://conference.eletsmeet.com', 15),
('55a6481d1e889', 'usr0000009', '2', '2015-07-15 11:46:36', NULL, '2015-07-15 11:46:36', '2015-07-15 17:16:36', 'tamil vod', 'NULL', 'Asia/Kolkata', '05:30', '1436960817468', '2015-07-15 11:47:20', '2015-07-15 11:51:01', 'appwd', 'mppwd', 'NULL', '75447', '75447', 2, 'true', 0, 'NULL', 'Y', '0', 'N', '0', '', 'http://conference.eletsmeet.com', 8),
('55a7796d2e5e3', 'usr0000008', '2', '2015-07-16 09:29:16', NULL, '2015-07-16 09:29:16', '2015-07-16 14:59:16', 'test', 'NULL', 'Asia/Kolkata', '05:30', '1437038937315', '2015-07-16 09:29:20', '2015-07-16 09:30:01', 'appwd', 'mppwd', 'NULL', '77723', '77723', 1, 'true', 0, 'NULL', 'Y', '0', 'N', '0', '', 'http://conference.eletsmeet.com', 2),
('55a8cf01318e6', 'usr0000021', '4', '2015-07-17 09:46:41', NULL, '2015-07-17 09:46:41', '2015-07-17 15:16:41', 'Test Meeting - Plan S', NULL, 'Asia/Kolkata', '05:30', NULL, NULL, NULL, 'appwd', 'mppwd', NULL, '72738', '72738', 5, 'true', 0, NULL, 'Y', '0', 'N', '0', NULL, 'http://conference.eletsmeet.com', 23),
('55a8d19d20584', 'usr0000005', '2', '2015-07-17 09:57:48', NULL, '2015-07-17 09:57:48', '2015-07-17 15:27:48', 'LetsMeet Demo Meeting', 'NULL', 'Asia/Kolkata', '05:30', '1437127047779', '2015-07-17 09:57:52', '2015-07-17 09:59:01', 'appwd', 'mppwd', 'NULL', '71677', '71677', 1, 'true', 0, 'NULL', 'Y', '0', 'N', '0', '', 'http://conference.eletsmeet.com', 15),
('55a8d5c26fb88', 'usr0000021', '4', '2015-07-17 10:15:30', NULL, '2015-07-17 10:15:30', '2015-07-17 15:45:30', 'Test Meeting - Plan S', NULL, 'Asia/Kolkata', '05:30', NULL, NULL, NULL, 'appwd', 'mppwd', NULL, '72898', '72898', 5, 'true', 0, NULL, 'Y', '0', 'N', '0', NULL, 'http://conference.eletsmeet.com', 23),
('55a8d68d4424f', 'usr0000005', '2', '2015-07-17 10:18:53', NULL, '2015-07-17 10:18:53', '2015-07-17 15:48:53', 'LetsMeet POC', 'NULL', 'Asia/Kolkata', '05:30', '1437128312083', '2015-07-17 10:18:56', '2015-07-17 10:49:01', 'appwd', 'mppwd', 'NULL', '79335', '79335', 1, 'true', 0, 'NULL', 'Y', '0', 'N', '0', '', 'http://conference.eletsmeet.com', 15),
('55a8e898d6a0e', 'usr0000021', '4', '2015-07-17 11:35:52', NULL, '2015-07-17 11:35:52', '2015-07-17 17:05:52', 'TestMeeting Setup - Plan S', NULL, 'Asia/Kolkata', '05:30', NULL, NULL, NULL, 'appwd', 'mppwd', NULL, '76589', '76589', 5, 'true', 0, NULL, 'Y', '0', 'N', '0', NULL, 'http://conference.eletsmeet.com', 23),
('55a9e10ff1f43', 'usr0000005', '2', '2015-07-18 05:15:59', NULL, '2015-07-18 05:15:59', '2015-07-18 10:45:59', 'LetsMeet POC', 'NULL', 'Asia/Kolkata', '05:30', '1437196561299', '2015-07-18 05:16:26', '2015-07-18 05:47:01', 'appwd', 'mppwd', 'NULL', '75781', '75781', 2, 'true', 0, 'NULL', 'Y', '0', 'N', '0', '', 'http://conference.eletsmeet.com', 15),
('55acc11be713e', 'usr0000022', '2', '2015-07-20 09:36:27', NULL, '2015-07-20 09:36:27', '2015-07-20 15:06:27', 'LetsMeet test', 'NULL', 'Asia/Kolkata', '05:30', '1437385030198', '2015-07-20 09:37:38', '2015-07-20 09:55:01', 'appwd', 'mppwd', 'NULL', '74665', '74665', 1, 'true', 0, 'NULL', 'Y', '0', 'N', '0', '', 'http://conference.eletsmeet.com', 24),
('55accd45574dd', 'usr0000022', '2', '2015-07-20 10:28:21', NULL, '2015-07-20 10:28:21', '2015-07-20 15:58:21', 'lets check', 'NULL', 'Asia/Kolkata', '05:30', '1437388085032', '2015-07-20 10:28:33', '2015-07-20 10:29:01', 'appwd', 'mppwd', 'NULL', '70226', '70226', 1, 'true', 0, 'NULL', 'Y', '0', 'N', '0', '', 'http://conference.eletsmeet.com', 24),
('55acd09916109', 'usr0000022', '2', '2015-07-20 10:42:32', NULL, '2015-07-20 10:42:32', '2015-07-20 16:12:32', 'check and meet', 'NULL', 'Asia/Kolkata', '05:30', '1437388930533', '2015-07-20 10:42:38', '2015-07-20 10:43:01', 'appwd', 'mppwd', 'NULL', '74933', '74933', 1, 'true', 0, 'NULL', 'Y', '0', 'N', '0', '', 'http://conference.eletsmeet.com', 24),
('55acd2d101c5c', 'usr0000022', '3', '2015-07-20 10:52:00', '2015-07-20 10:53:37', '2015-07-20 10:52:00', '2015-07-20 16:22:00', 'meeting', 'NULL', 'Asia/Kolkata', '05:30', NULL, NULL, NULL, 'appwd', 'mppwd', 'NULL', '78334', '78334', 1, 'true', 0, 'NULL', 'Y', '0', 'N', '0', NULL, 'http://conference.eletsmeet.com', 24),
('55acd392e5e76', 'usr0000022', '2', '2015-07-20 10:55:14', NULL, '2015-07-20 10:55:14', '2015-07-20 16:25:14', 'meet', 'NULL', 'Asia/Kolkata', '05:30', '1437389737921', '2015-07-20 10:56:06', '2015-07-20 11:01:01', 'appwd', 'mppwd', 'NULL', '71826', '71826', 1, 'true', 0, 'NULL', 'Y', '0', 'N', '0', '', 'http://conference.eletsmeet.com', 24);

-- --------------------------------------------------------

--
-- Table structure for table `subscription_master`
--

CREATE TABLE IF NOT EXISTS `subscription_master` (
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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=26 ;

--
-- Dumping data for table `subscription_master`
--

INSERT INTO `subscription_master` (`subscription_id`, `user_id`, `subscription_date`, `subscription_start_date_gmt`, `subscription_end_date_gmt`, `subscription_start_date_local`, `subscription_end_date_local`, `subscription_status`, `order_id`, `plan_id`, `plan_name`, `plan_desc`, `plan_for`, `plan_type`, `number_of_sessions`, `number_of_mins_per_sessions`, `plan_period`, `number_of_invitee`, `meeting_recording`, `disk_space`, `is_free`, `plan_cost_inr`, `plan_cost_oth`, `concurrent_sessions`, `talk_time_mins`, `autorenew_flag`, `consumed_number_of_sessions`, `consumed_talk_time_mins`) VALUES
(1, 'usr0000003', '2015-06-12 10:25:00', '2015-06-12', '2015-12-09', '2015-06-12', '2015-12-09', '2', 'ord143409003311', 1, 'Quadridge LetsMeet', 'LetsMeet Default Plan for Quadridge Internal People', 'OTH', 'U', 0, 0, 180, 20, 'true', 0, '1', 0.00, 0.00, 0, NULL, '0', 1, 0),
(2, 'usr0000008', '2015-06-12 10:25:13', '2015-06-12', '2015-12-09', '2015-06-12', '2015-12-09', '2', 'ord143409047311', 1, 'Quadridge LetsMeet', 'LetsMeet Default Plan for Quadridge Internal People', 'OTH', 'U', 0, 0, 180, 20, 'true', 0, '1', 0.00, 0.00, 0, NULL, '0', 1, 0),
(3, 'usr0000004', '2015-06-12 10:25:27', '2015-06-12', '2015-12-09', '2015-06-12', '2015-12-09', '2', 'ord143409048811', 1, 'Quadridge LetsMeet', 'LetsMeet Default Plan for Quadridge Internal People', 'OTH', 'U', 0, 0, 180, 20, 'true', 0, '1', 0.00, 0.00, 0, NULL, '0', 1, 0),
(4, 'usr0000001', '2015-06-12 10:27:14', '2015-06-12', '2015-12-09', '2015-06-12', '2015-12-09', '2', 'ord143409050911', 1, 'Quadridge LetsMeet', 'LetsMeet Default Plan for Quadridge Internal People', 'OTH', 'U', 0, 0, 180, 20, 'true', 0, '1', 0.00, 0.00, 0, NULL, '0', 0, 0),
(5, 'usr0000005', '2015-06-12 10:27:27', '2015-06-12', '2015-12-09', '2015-06-12', '2015-12-09', '2', 'ord143409052411', 1, 'Quadridge LetsMeet', 'LetsMeet Default Plan for Quadridge Internal People', 'OTH', 'U', 0, 0, 180, 20, 'true', 0, '1', 0.00, 0.00, 0, NULL, '0', 5, 0),
(6, 'usr0000002', '2015-06-12 10:28:10', '2015-06-12', '2015-12-09', '2015-06-12', '2015-12-09', '2', 'ord143409053611', 1, 'Quadridge LetsMeet', 'LetsMeet Default Plan for Quadridge Internal People', 'OTH', 'U', 0, 0, 180, 20, 'true', 0, '1', 0.00, 0.00, 0, NULL, '0', 3, 0),
(7, 'usr0000006', '2015-06-12 11:31:28', '2015-06-12', '2015-12-09', '2015-06-12', '2015-12-09', '2', 'ord143409056411', 1, 'Quadridge LetsMeet', 'LetsMeet Default Plan for Quadridge Internal People', 'OTH', 'U', 0, 0, 180, 20, 'true', 0, '1', 0.00, 0.00, 0, NULL, '0', 11, 0),
(8, 'usr0000009', '2015-06-24 16:12:05', '2015-05-26', '2015-11-30', '2015-05-26', '2015-11-30', '2', 'ord143516159521', 8, 'LetsMeet PRO', 'LetsMeet PRO', 'ENT', 'U', 0, 0, 180, 15, 'true', 0, '0', 4000.00, 0.00, 1, NULL, '0', 2, 0),
(9, 'usr0000010', '2015-06-27 07:17:38', '2015-06-27', '2015-07-27', '2015-06-27', '2015-07-27', '2', 'ord143538904871', 7, 'LetsMeet LITE', 'LetsMeet LITE', 'ENT', 'U', 0, 0, 30, 5, 'true', 0, '0', 3000.00, 0.00, 1, NULL, '0', 2, 0),
(10, 'usr0000011', '2015-06-27 07:17:47', '2015-06-27', '2015-07-27', '2015-06-27', '2015-07-27', '2', 'ord143538906171', 7, 'LetsMeet LITE', 'LetsMeet LITE', 'ENT', 'U', 0, 0, 30, 5, 'true', 0, '0', 3000.00, 0.00, 1, NULL, '0', 0, 0),
(11, 'usr0000012', '2015-07-06 07:19:10', '2015-07-06', '2015-09-04', '2015-07-06', '2015-09-04', '2', 'ord143616381241', 8, 'LetsMeet PRO', 'LetsMeet PRO', 'ENT', 'U', 0, 0, 60, 15, 'true', 0, '0', 4000.00, 0.00, 1, NULL, '0', 0, 0),
(12, 'usr0000013', '2015-07-06 07:19:19', '2015-07-06', '2015-09-04', '2015-07-06', '2015-09-04', '2', 'ord143616382741', 8, 'LetsMeet PRO', 'LetsMeet PRO', 'ENT', 'U', 0, 0, 60, 15, 'true', 0, '0', 4000.00, 0.00, 1, NULL, '0', 0, 0),
(13, 'usr0000014', '2015-07-06 07:19:27', '2015-07-06', '2015-09-04', '2015-07-06', '2015-09-04', '2', 'ord143616384941', 8, 'LetsMeet PRO', 'LetsMeet PRO', 'ENT', 'U', 0, 0, 60, 15, 'true', 0, '0', 4000.00, 0.00, 1, NULL, '0', 0, 0),
(14, 'usr0000015', '2015-07-08 11:30:03', '2015-07-08', '2015-07-23', '2015-07-08', '2015-07-23', '2', 'ord143635472411', 5, 'LetsMeet Trial 15', 'LetsMeet Trial Plan for 15days for Demo', 'ENT', 'S', 15, 0, 15, 5, 'false', 0, '1', 0.00, 0.00, 1, NULL, '0', 0, 0),
(15, 'usr0000005', '2015-07-09 08:45:48', '2015-07-09', '2016-01-05', '2015-07-09', '2016-01-05', '2', 'ord143643148111', 1, 'Quadridge LetsMeet', 'LetsMeet Default Plan for Quadridge Internal People', 'OTH', 'U', 0, 0, 180, 20, 'true', 0, '1', 0.00, 0.00, 0, NULL, '0', 9, 0),
(16, 'usr0000016', '2015-07-13 04:54:14', '2015-07-09', '2015-07-24', '2015-07-09', '2015-07-24', '2', 'ord143643581181', 6, 'LetsMeet Silver Trial 15', 'LetsMeet Silver Trial Plan for 15days for Demo', 'ENT', 'S', 15, 0, 15, 10, 'true', 0, '1', 0.00, 0.00, 1, NULL, '0', 1, 0),
(17, 'usr0000017', '2015-07-13 06:28:58', '2015-07-09', '2015-07-24', '2015-07-09', '2015-07-24', '2', 'ord143643582481', 6, 'LetsMeet Silver Trial 15', 'LetsMeet Silver Trial Plan for 15days for Demo', 'ENT', 'S', 15, 0, 15, 10, 'true', 0, '1', 0.00, 0.00, 1, NULL, '0', 1, 0),
(18, 'usr0000017', '2015-07-13 06:29:02', '2015-07-09', '2015-07-24', '2015-07-09', '2015-07-24', '2', 'ord143643582481', 6, 'LetsMeet Silver Trial 15', 'LetsMeet Silver Trial Plan for 15days for Demo', 'ENT', 'S', 15, 0, 15, 10, 'true', 0, '1', 0.00, 0.00, 1, NULL, '0', 0, 0),
(19, 'usr0000018', '2015-07-13 06:29:44', '2015-07-09', '2015-07-24', '2015-07-09', '2015-07-24', '2', 'ord143643583681', 6, 'LetsMeet Silver Trial 15', 'LetsMeet Silver Trial Plan for 15days for Demo', 'ENT', 'S', 15, 0, 15, 10, 'true', 0, '1', 0.00, 0.00, 1, NULL, '0', 0, 0),
(20, 'usr0000019', '2015-07-13 06:30:04', '2015-07-09', '2015-07-24', '2015-07-09', '2015-07-24', '2', 'ord143643586381', 6, 'LetsMeet Silver Trial 15', 'LetsMeet Silver Trial Plan for 15days for Demo', 'ENT', 'S', 15, 0, 15, 10, 'true', 0, '1', 0.00, 0.00, 1, NULL, '0', 0, 0),
(21, 'usr0000019', '2015-07-13 06:30:09', '2015-07-09', '2015-07-24', '2015-07-09', '2015-07-24', '2', 'ord143643586381', 6, 'LetsMeet Silver Trial 15', 'LetsMeet Silver Trial Plan for 15days for Demo', 'ENT', 'S', 15, 0, 15, 10, 'true', 0, '1', 0.00, 0.00, 1, NULL, '0', 0, 0),
(22, 'usr0000020', '2015-07-13 06:30:55', '2015-07-09', '2015-07-24', '2015-07-09', '2015-07-24', '2', 'ord143643587581', 6, 'LetsMeet Silver Trial 15', 'LetsMeet Silver Trial Plan for 15days for Demo', 'ENT', 'S', 15, 0, 15, 10, 'true', 0, '1', 0.00, 0.00, 1, NULL, '0', 0, 0),
(23, 'usr0000021', '2015-07-14 14:04:47', '2015-07-14', '2015-08-13', '2015-07-14', '2015-08-13', '2', 'ord143688233991', 7, 'LetsMeet LITE', 'LetsMeet LITE', 'ENT', 'U', 0, 0, 30, 5, 'true', 0, '0', 3000.00, 0.00, 1, NULL, '0', 3, 0),
(24, 'usr0000022', '2015-07-15 05:39:38', '2015-07-15', '2016-07-14', '2015-07-15', '2016-07-14', '2', 'ord143693723951', 8, 'LetsMeet PRO', 'LetsMeet PRO', 'ENT', 'U', 0, 0, 360, 15, 'true', 0, '0', 4000.00, 0.00, 1, NULL, '0', 4, 0),
(25, 'usr0000023', '2015-07-21 09:31:14', '2015-07-21', '2015-08-20', '2015-07-21', '2015-08-20', '2', 'ord1437470901102', 8, 'LetsMeet PRO', 'LetsMeet PRO', 'ENT', 'U', 0, 0, 30, 15, 'true', 0, '0', 4000.00, 0.00, 1, NULL, '0', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `transaction_log`
--

CREATE TABLE IF NOT EXISTS `transaction_log` (
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
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `user_details`
--

CREATE TABLE IF NOT EXISTS `user_details` (
  `user_id` varchar(25) NOT NULL COMMENT 'Unique identification number of user',
  `nick_name` varchar(50) DEFAULT NULL COMMENT 'Nick name of user',
  `first_name` varchar(50) DEFAULT NULL COMMENT 'First name of user',
  `last_name` varchar(50) DEFAULT NULL COMMENT 'Last name of user',
  `country_name` varchar(150) DEFAULT NULL COMMENT 'Country name of user from country_details table',
  `timezones` varchar(100) NOT NULL COMMENT 'Timezones of user',
  `gmt` varchar(20) NOT NULL COMMENT 'GMT hrs on the basis of timezones',
  `phone_number` varchar(20) DEFAULT NULL COMMENT 'Phone number of user',
  `idd_code` varchar(10) DEFAULT NULL COMMENT 'IDD code of user',
  `mobile_number` varchar(20) DEFAULT NULL COMMENT 'Mobil number of user',
  `status` enum('0','1','2','3','4') NOT NULL DEFAULT '0' COMMENT 'Status of user 0=Pending, 1=Active, 2=Deative, 3=Deleted, 4=License limit over',
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_details`
--

INSERT INTO `user_details` (`user_id`, `nick_name`, `first_name`, `last_name`, `country_name`, `timezones`, `gmt`, `phone_number`, `idd_code`, `mobile_number`, `status`) VALUES
('usr0000001', 'kiran', 'Kiran', 'Kulkarni', 'India', 'Asia/Kolkata', '+05:30', NULL, '91', '9821144275', '1'),
('usr0000002', 'Pankaj', 'Pankaj', 'Kumar', 'India', 'Asia/Kolkata', '+05:30', NULL, '91', '9000000006', '1'),
('usr0000003', 'Althea', 'Althea', 'Lopez', 'India', 'Asia/Kolkata', '+05:30', NULL, '91', '9000000001', '1'),
('usr0000004', 'Gopal', 'Gopal', 'Sirnaik', 'India', 'Asia/Kolkata', '+05:30', NULL, '91', '9000000002', '1'),
('usr0000005', 'Mitesh', 'Mitesh', 'Shah', 'India', 'Asia/Kolkata', '+05:30', NULL, '91', '9000000003', '1'),
('usr0000006', 'Sushrit', 'Sushrit', 'Shrivastava', 'India', 'Asia/Kolkata', '+05:30', NULL, '91', '9000000002', '1'),
('usr0000007', 'Nastassia', 'Nastassia', 'Florindo', 'India', 'Asia/Kolkata', '+05:30', NULL, '91', '9833133645', '1'),
('usr0000008', 'Anirudha', 'Anirudha', 'Khopade', 'India', 'Asia/Kolkata', '+05:30', NULL, '91', '9619732555', '1'),
('usr0000009', 'Ashwin', 'Ashwin', 'Dsilva', 'India', 'Asia/Kolkata', '+05:30', NULL, '91', '9821234851', '1'),
('usr0000010', 'Zahir', 'Zahir', 'Zahir', 'India', 'Asia/Kolkata', '+05:30', NULL, '91', '9000000000', '1'),
('usr0000011', 'Reetika', 'Reetika', 'Chatterjee', 'India', 'Asia/Kolkata', '+05:30', NULL, '91', '9820009854', '1'),
('usr0000012', 'Manoj Dehankar', 'Manoj', 'Dehankar', 'India', 'Asia/Kolkata', '+05:30', NULL, '91', '9123456789', '1'),
('usr0000013', 'Sriram Kilambi', 'Sriram', 'Kilambi', 'India', 'Asia/Kolkata', '+05:30', NULL, '91', '9123456789', '1'),
('usr0000014', 'Vishal Vora', 'Vishal', 'Vora', 'India', 'Asia/Kolkata', '+05:30', NULL, '91', '9123456789', '1'),
('usr0000015', 'Harshad', 'Harshad', 'Badbade', 'India', 'Asia/Kolkata', '+05:30', NULL, '91', '9820300135', '1'),
('usr0000016', 'User2', 'User2', 'Adani', 'India', 'Asia/Kolkata', '+05:30', NULL, '91', '9099002479', '1'),
('usr0000017', 'User1', 'User1', 'Adani', 'India', 'Asia/Kolkata', '+05:30', NULL, '91', '9099002479', '1'),
('usr0000018', 'User3', 'User3', 'Adani', 'India', 'Asia/Kolkata', '+05:30', NULL, '91', '9099002479', '1'),
('usr0000019', 'User4', 'User4', 'Adani', 'India', 'Asia/Kolkata', '+05:30', NULL, '91', '9099002479', '1'),
('usr0000020', 'User5', 'User5', 'Adani', 'India', 'Asia/Kolkata', '+05:30', NULL, '91', '9099002479', '1'),
('usr0000021', 'AxSys', 'AxSys', 'LetsMeet', 'United Kingdom', 'Europe/London', '+00:00', NULL, '44', '8700848600', '1'),
('usr0000022', 'Sumeet Nihalani', 'Sumeet', 'Nihalani', 'India', 'Asia/Kolkata', '+05:30', NULL, '91', '9049294103', '1'),
('usr0000023', 'Jay Kumar', 'Jay', 'Kumar', 'India', 'Asia/Kolkata', '+05:30', NULL, '91', '9867327027', '1');

-- --------------------------------------------------------

--
-- Table structure for table `user_login_details`
--

CREATE TABLE IF NOT EXISTS `user_login_details` (
  `user_id` varchar(25) NOT NULL,
  `user_name` varchar(100) NOT NULL,
  `client_id` varchar(25) NOT NULL,
  `partner_id` varchar(25) NOT NULL,
  `password` varchar(50) NOT NULL,
  `email_address` varchar(150) NOT NULL,
  `role` enum('0','1') NOT NULL COMMENT '0-SuperAdmin,1-CompanyAdmin',
  `login_enabled` tinyint(1) NOT NULL,
  `createdOn` datetime NOT NULL,
  `createdBy` varchar(100) DEFAULT NULL,
  `user_lastlogin_dtm` datetime DEFAULT NULL,
  `user_login_ip_address` varchar(25) DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `user_name` (`user_name`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_login_details`
--

INSERT INTO `user_login_details` (`user_id`, `user_name`, `client_id`, `partner_id`, `password`, `email_address`, `role`, `login_enabled`, `createdOn`, `createdBy`, `user_lastlogin_dtm`, `user_login_ip_address`) VALUES
('usr0000005', 'mitesh.shah@quadridge.com', 'cl00001', 'pr00001', 'cdbe75eb932913e135ed90941f1b3789', 'mitesh.shah@quadridge.com', '1', 1, '2015-07-21 18:19:17', '', '2015-07-27 09:00:41', '172.16.1.128'),
('usr0000006', 'sushrit@quadridge.com', 'cl00001', 'pr00001', 'cdbe75eb932913e135ed90941f1b3789', 'sushrit@quadridge.com', '1', 1, '2015-07-21 18:19:17', '', '2015-07-27 09:50:07', '172.16.1.130');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `country_timezones`
--
ALTER TABLE `country_timezones`
  ADD CONSTRAINT `FK_country_code` FOREIGN KEY (`country_code`) REFERENCES `country_details` (`country_code`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
