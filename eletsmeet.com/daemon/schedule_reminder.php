<?php
$DAEMON_WEBAPP_PATH = "/var/www/html/lm.quadridge.com/";
require_once($DAEMON_WEBAPP_PATH.'includes/global.inc.php');
require_once($DAEMON_WEBAPP_PATH.'classes/error.inc.php');
require_once($DAEMON_WEBAPP_PATH.'dbs/DataHelper.php');
require_once($DAEMON_WEBAPP_PATH.'dbs/objDataHelper.php');
require_once($DAEMON_WEBAPP_PATH.'includes/daemon_function.inc.php');
require_once($DAEMON_WEBAPP_PATH.'includes/db_common_function.inc.php');
require_once($DAEMON_WEBAPP_PATH.'includes/mail_common_function.inc.php');
//require_once($DAEMON_WEBAPP_PATH.'includes/Utilities.php');

//header('Content-type: text/plain; charset=utf-8');

try
{
    define("SCHEDULE_REMINDER_UNIT","minutes");
    
    define("EML_REM_FROM_INTERVAL_24HRS",180);
    define("EML_REM_TO_INTERVAL_24HRS",1440);
    define("EML_REM_TYPE_24HRS","E1");
    define("EML_REM_SUBJECT_24HRS","Reminder for meeting: 24hrs to go.");
    
    define("EML_REM_FROM_INTERVAL_1HRS",10);
    define("EML_REM_TO_INTERVAL_1HRS",60);
    define("EML_REM_TYPE_1HRS","E2");
    define("EML_REM_SUBJECT_1HRS","Reminder for meeting: 1hrs to go.");
    
    define("SMS_REM_FROM_INTERVAL_1HRS",10);
    define("SMS_REM_TO_INTERVAL_1HRS",60);
    define("SMS_REM_TYPE_1HRS","S1");

    echo '<pre/>';
    //echo $Current_GMT_Datetime = gmdate("Y-m-d H:i:s");
    echo $Current_GMT_Datetime = GM_DATE;
    echo '<pre/>';
    
    if (trim(EML_REM_TYPE_24HRS) == "E1")
    {
        $Email_From_Interval_24hrs = '+'.EML_REM_FROM_INTERVAL_24HRS.' '.SCHEDULE_REMINDER_UNIT ;
        $Email_From_Datetime_24hrs = date('Y-m-d H:i:s', strtotime ( $Email_From_Interval_24hrs  , strtotime ( $Current_GMT_Datetime ) ) );

        $Email_To_Interval_24hrs = '+'.EML_REM_TO_INTERVAL_24HRS.' '.SCHEDULE_REMINDER_UNIT ;
        $Email_To_Datetime_24hrs = date('Y-m-d H:i:s', strtotime ( $Email_To_Interval_24hrs  , strtotime ( $Current_GMT_Datetime ) ) );
    
        try
        {
            $arrEmailRemList24hrs = getScheduleReminderList($Email_From_Datetime_24hrs, $Email_To_Datetime_24hrs, EML_REM_TYPE_24HRS, $objDataHelper);
        }
        catch (Exception $a)
        {
            throw new Exception("Error in getScheduleReminderList." . $a->getMessage(), 4101);
        }
        $Schedule_Id   = trim($arrEmailRemList24hrs[0]['schedule_id']);
        $Meeting_Title = trim($arrEmailRemList24hrs[0]['meeting_title']);
        $Meeting_Time  = dateFormat(trim($arrEmailRemList24hrs[0]['meeting_timestamp_gmt']), trim($arrEmailRemList24hrs[0]['meeting_timestamp_local']), trim($arrEmailRemList24hrs[0]['meeting_timezone']));
        $Meeting_Hosted_By = trim($arrEmailRemList24hrs[0]['nick_name']);
        
        echo "<pre/>";
        print_r($arrEmailRemList24hrs);
        echo "<pre/>";
        
        try
        {
           $arrEmailRemUpd24hrs = updScheduleReminderList($Email_From_Datetime_24hrs, $Email_To_Datetime_24hrs, EML_REM_TYPE_24HRS, $objDataHelper);
        }
        catch(Exception $a)
        {
            throw new Exception("Error in updScheduleReminderList.".$a->getMessage(),4102);
        }
        $strUpdStatusEmlRem24hrs = trim($arrEmailRemUpd24hrs[0]['@result']);
        
        try
        {
            $arrInviteesList24hrs = getMeetingInviteeList($Schedule_Id, $objDataHelper);
        }
        catch (Exception $a)
        {
            throw new Exception("Error in getMeetingInviteeList." . $a->getMessage(), 4103);
        }
        
        echo "<pre/>";
        print_r($arrInviteesList24hrs);
        echo "<pre/>";
        
        foreach($arrInviteesList24hrs as $key => $value)
        {
           $InviteesEmailnNick24hrs .=  $value['invitee_email_address'] = $value['invitee_email_address'].'#'.$value['invitee_nick_name'].",";
        }
        $InviteesEmailnNick24hrs = substr($InviteesEmailnNick24hrs, 0, -1);
        
        echo "<pre/>";
        echo $InviteesEmailnNick24hrs;
        echo "<pre/>";
        
        meetingReminderMail ($Schedule_Id, EML_REM_SUBJECT_24HRS, $Meeting_Title, $Meeting_Time, $Meeting_Hosted_By, $CONST_SUPPORT_EID, $InviteesEmailnNick24hrs);                
    }
    
    if (trim(EML_REM_TYPE_1HRS) == "E2")
    {
        $Email_From_Interval_1hrs = '-'.EML_REM_FROM_INTERVAL_1HRS.' '.SCHEDULE_REMINDER_UNIT ;
        $Email_From_Datetime_1hrs = date('Y-m-d H:i:s', strtotime ( $Email_From_Interval_1hrs  , strtotime ( $Current_GMT_Datetime ) ) );

        $Email_To_Interval_1hrs = '+'.EML_REM_TO_INTERVAL_1HRS.' '.SCHEDULE_REMINDER_UNIT ;
        $Email_To_Datetime_1hrs = date('Y-m-d H:i:s', strtotime ( $Email_To_Interval_1hrs  , strtotime ( $Current_GMT_Datetime ) ) );
    
        try
        {
            $arrEmailRemList1hrs = getScheduleReminderList($Email_From_Datetime_1hrs, $Email_To_Datetime_1hrs, EML_REM_TYPE_1HRS, $objDataHelper);
        }
        catch (Exception $a)
        {
            throw new Exception("Error in getScheduleReminderList." . $a->getMessage(), 4101);
        }
        $Schedule_Id   = trim($arrEmailRemList1hrs[0]['schedule_id']);
        $Meeting_Title = trim($arrEmailRemList1hrs[0]['meeting_title']);
        $Meeting_Time  = dateFormat(trim($arrEmailRemList1hrs[0]['meeting_timestamp_gmt']), trim($arrEmailRemList1hrs[0]['meeting_timestamp_local']), trim($arrEmailRemList1hrs[0]['meeting_timezone']));
        $Meeting_Hosted_By = trim($arrEmailRemList1hrs[0]['nick_name']);
        
        echo "<pre/>";
        print_r($arrEmailRemList1hrs);
        echo "<pre/>";
        
        try
        {
            $arrEmailRemUpd1hrs = updScheduleReminderList($Email_From_Datetime_1hrs, $Email_To_Datetime_1hrs, EML_REM_TYPE_1HRS, $objDataHelper);
        }
        catch(Exception $a)
        {
            throw new Exception("Error in updScheduleReminderList.".$a->getMessage(),4102);
        }
        $strUpdStatusEmlRem1hrs = trim($arrEmailRemUpd1hrs[0]['@result']);
        
        try
        {
            $arrInviteesList1hrs = getMeetingInviteeList($Schedule_Id, $objDataHelper);
        }
        catch (Exception $a)
        {
            throw new Exception("Error in getMeetingInviteeList." . $a->getMessage(), 4103);
        }
        
        echo "<pre/>";
        print_r($arrInviteesList1hrs);
        echo "<pre/>";
        
        foreach($arrInviteesList1hrs as $key => $value)
        {
           $InviteesEmailnNick1hrs .=  $value['invitee_email_address'] = $value['invitee_email_address'].'#'.$value['invitee_nick_name'].",";
        }
        $InviteesEmailnNick1hrs = substr($InviteesEmailnNick1hrs, 0, -1);
        
        echo "<pre/>";
        echo $InviteesEmailnNick1hrs;
        echo "<pre/>";
        
        meetingReminderMail ($Schedule_Id, EML_REM_SUBJECT_1HRS, $Meeting_Title, $Meeting_Time, $Meeting_Hosted_By, $CONST_SUPPORT_EID, $InviteesEmailnNick1hrs);                
    }
    
    if (trim(SMS_REM_TYPE_1HRS) == "S1")
    {
        define("SMS_REM_FROM_INTERVAL_1HRS",10);
        define("SMS_REM_TO_INTERVAL_1HRS",60);
        define("SMS_REM_TYPE_1HRS","S1");
        
        $SMS_From_Interval_1hrs = '+'.SMS_REM_FROM_INTERVAL_1HRS.' '.SCHEDULE_REMINDER_UNIT ;
        $SMS_From_Datetime_1hrs = date('Y-m-d H:i:s', strtotime ( $SMS_From_Interval_1hrs  , strtotime ( $Current_GMT_Datetime ) ) );

        $SMS_To_Interval_1hrs = '+'.SMS_REM_TO_INTERVAL_1HRS.' '.SCHEDULE_REMINDER_UNIT ;
        $SMS_To_Datetime_1hrs = date('Y-m-d H:i:s', strtotime ( $SMS_To_Interval_1hrs  , strtotime ( $Current_GMT_Datetime ) ) );
    
        try
        {
            $arrSMSRemList1hrs = getScheduleReminderList($SMS_From_Datetime_1hrs, $SMS_To_Datetime_1hrs, SMS_REM_TYPE_1HRS, $objDataHelper);
        }
        catch (Exception $a)
        {
            throw new Exception("Error in getScheduleReminderList." . $a->getMessage(), 4101);
        }
        $Schedule_Id   = trim($arrSMSRemList1hrs[0]['schedule_id']);
        $Meeting_Title = trim($arrSMSRemList1hrs[0]['meeting_title']);
        $Meeting_Time  = dateFormat(trim($arrSMSRemList1hrs[0]['meeting_timestamp_gmt']), trim($arrSMSRemList1hrs[0]['meeting_timestamp_local']), trim($arrSMSRemList1hrs[0]['meeting_timezone']));
        $Meeting_Hosted_By = trim($arrSMSRemList1hrs[0]['nick_name']);
        
        echo "<pre/>";
        print_r($arrEmailRemList1hrs);
        echo "<pre/>";
        
        try
        {
            $arrSMSRemUpd1hrs = updScheduleReminderList($SMS_From_Datetime_1hrs, $SMS_To_Datetime_1hrs, SMS_REM_TYPE_1HRS, $objDataHelper);
        }
        catch(Exception $a)
        {
            throw new Exception("Error in updScheduleReminderList.".$a->getMessage(),4102);
        }
        $strUpdStatusSMSRem1hrs = trim($arrSMSRemUpd1hrs[0]['@result']);
        
        try
        {
            $arrInviteesList1hrs = getMeetingInviteeList($Schedule_Id, $objDataHelper);
        }
        catch (Exception $a)
        {
            throw new Exception("Error in getMeetingInviteeList." . $a->getMessage(), 4103);
        }
        
        echo "<pre/>";
        print_r($arrInviteesList1hrs);
        echo "<pre/>";
    }
}
catch (Exception $e)
{
    $ErrorHandler->RaiseError($_SERVER["PHP_SELF"], $e->getCode(), $e->getMessage(), false);
}
?>
