<?php

date_default_timezone_set('GMT');

require_once('../includes/global.inc.php');
require_once(CLASSES_PATH . 'error.inc.php');
require_once(DBS_PATH . 'DataHelper.php');
require_once(DBS_PATH . 'objDataHelper.php');
require_once(INCLUDES_PATH . "db_common_function.inc.php");
require_once('includes/partner_api_db_function.inc.php');
//require_once('includes/partner_mail_function.inc.php');

header('Content-type: text/xml; charset=utf-8');

$meetingData = $_REQUEST['data'];
$prID = $_REQUEST['protocolID'];

// Validate Parameters Received : Start
$stat = 0;
$msg = "";

$Current_GMT_Datetime = GM_DATE;

//echo "</br>";
//echo $valid_time = strtotime("+".GRACE_PERIOD."minutes", strtotime($Current_GMT_Datetime));

if ($meetingData == '')
{
    $stat = 1;
    $msg = "Missing Parameter DATA";
}

if ($prID == '')
{
    $stat = 1;
    $msg = "Missing Parameter PROTOCOL ID";
}
else if (!is_numeric($prID))
{
    $stat = 1;
    $msg = "Invalid Parameter PROTOCOL ID";
}
// Validate Parameters Received : End 

if ($stat == 0)
{
    // XML DATA PARSING : Start
    $xml = simplexml_load_string($meetingData);

    //var_dump($xml);

    function xml2array($xml) {
        $arr = array();
        foreach ($xml->children() as $r)
        {
            $t = array();
            if (count($r->children()) == 0)
            {
                $arr[$r->getName()] = strval($r);
            }
            else
            {
                $arr[$r->getName()][] = xml2array($r);
            }
        }
        return $arr;
    }

    $dataArray = xml2array($xml);
    $userArray = array(clientID => $dataArray['clientID'], userEmail => $dataArray['userEmail'], userPW => $dataArray['userPW'], subscriptionID => $dataArray['subscriptionID'], meetingTitle => $dataArray['meetingTitle'], scheduleType => $dataArray['scheduleType'], scheduleDateTime => $dataArray['scheduleDateTime'], timezone => $dataArray['timezone'], timestamp => $dataArray['timestamp'], passCode => $dataArray['passCode']);

    $strClientID = $userArray['clientID'];
    $strUserEmail = $userArray['userEmail'];
    $strUserPW = $userArray['userPW'];
    $strSubscriptionID = $userArray['subscriptionID'];
    $strMeetingTitle = $userArray['meetingTitle'];
    $strScheduleType = $userArray['scheduleType'];
    $strScheduleDateTime = $userArray['scheduleDateTime'];
    $strTimezone = $userArray['timezone'];
    $strTimestamp = $userArray['timestamp'];
    $strPassCode = $userArray['passCode'];

    $inviteeArray = array();
    foreach ($dataArray['inviteeList'][0]['invitee'] as $key => $value)
    {
        $inviteeArray[] = array(inviteeEmail => $value['inviteeEmail'], inviteeNickName => $value['inviteeNickName'], inviteeIDDCode => $value['inviteeIDDCode'], inviteeMobile => $value['inviteeMobile'], moderatorFlag => $value['moderatorFlag']);
    }
    // XML DATA PARSING : End
    
    // Validate Data Values : Start
    if ($strClientID == '')
    {
        $stat = 1;
        $msg = "Missing Parameter CLIENT ID";
    }
    else if ($strUserEmail == '')
    {
        $stat = 1;
        $msg = "Missing Parameter EMAIL ID";
    }
    else if (!filter_var($strUserEmail, FILTER_VALIDATE_EMAIL))
    {
        $stat = 1;
        $msg = "Invalid Parameter USER EMAIL";
    }
    else if ($strUserPW == '')
    {
        $stat = 1;
        $msg = "Missing Parameter USERPW";
    }
    else if ($strSubscriptionID == '')
    {
        $stat = 1;
        $msg = "Missing Parameter SUBSCRIPTION ID";
    }
    else if ($strMeetingTitle == '')
    {
        $stat = 1;
        $msg = "Missing Parameter MEETING TITLE";
    }
    else if ($strScheduleType == '')
    {
        $stat = 1;
        $msg = "Missing Parameter SCHEDULE TYPE";
    }
    else if ($strScheduleType == 'L' && $strScheduleDateTime == '')
    {
        $stat = 1;
        $msg = "Missing Parameter SCHEDULE DATETIME";
    }
    else if ($strTimezone == '')
    {
        $stat = 1;
        $msg = "Missing Parameter TIMEZONE";
    }
    else if ($strTimestamp == '')
    {
        $stat = 1;
        $msg = "Missing Parameter TIMESTAMP";
    }
    else if (!is_numeric($strTimestamp))
    {
        $stat = 1;
        $msg = "Invalid Parameter TIMESTAMP";
    }
    else if ($strPassCode == '')
    {
        $stat = 1;
        $msg = "Missing Parameter PASSCODE";
    }

    for ($intCount = 0; $intCount < sizeof($inviteeArray); $intCount++)
    {
        if ($inviteeArray[$intCount]['inviteeEmail'] == '')
        {
            $stat = 1;
            $msg = "Invitee Email Address missing";
        }
        else if (!filter_var($inviteeArray[$intCount]['inviteeEmail'], FILTER_VALIDATE_EMAIL))
        {
            $stat = 1;
            $msg = "Invitee Email Invalid";
        }
        else if ($inviteeArray[$intCount]['inviteeNickName'] == '')
        {
            $stat = 1;
            $msg = "Invitee Nick Name missing";
        }
        else if ($inviteeArray[$intCount]['inviteeIDDCode'] == '')
        {
            $stat = 1;
            $msg = "Invitee IDD Code missing";
        }
        else if (!is_numeric($inviteeArray[$intCount]['inviteeIDDCode']))
        {
            $stat = 1;
            $msg = "Invitee IDD Code is Invalid";
        }
        else if ($inviteeArray[$intCount]['inviteeMobile'] == '')
        {
            $stat = 1;
            $msg = "Invitee Mobile missing";
        }
        else if (!is_numeric($inviteeArray[$intCount]['inviteeMobile']))
        {
            $stat = 1;
            $msg = "Invitee Mobile is Invalid";
        }
        else if ($inviteeArray[$intCount]['moderatorFlag'] == '')
        {
            $stat = 1;
            $msg = "Invitee Moderator Flag missing";
        }
    }

    $strModerator = '';

    function IsModerator($inviteeArray) {
        foreach ($inviteeArray as $key => $value)
        {
            if ($value['moderatorFlag'] === 'Y')
                return $value['inviteeEmail'];
        }
    }

    $strModeratorEmail = IsModerator($inviteeArray);
    if (!isset($strModeratorEmail))
    {
        $stat = 1;
        $msg = "Moderator missing";
    }
    // Validate Data Values : End
    
     if ($stat == 0)
     {
            // Meeting Date Time : Start
            if ($strScheduleType == "N")
            {
                $meeting_timestamp = $Current_GMT_Datetime;
            }
            else if ($strScheduleType == "L")
            {
                $meeting_timestamp = date("Y-m-d H:i:s", strtotime($strScheduleDateTime));
            }
            // Meeting Date Time : End

            try
            {
                $arrTimeZoneDetails = getTimezoneDetails($strTimezone, $objDataHelper);
            }
            catch (Exception $e)
            {
                throw new Exception("scheduleMeeting.php : getTimezoneDetails Failed : " . $e->getMessage(), 6001);
            }

            if ((is_array($arrTimeZoneDetails)) && (sizeof($arrTimeZoneDetails)) > 0)
            {
                $meeting_GMT = substr($arrTimeZoneDetails[0]['gmt'], 1);
            }

            // Timezone : Start 
            $dateTime = timezoneConverter($strScheduleType, $meeting_timestamp, $strTimezone);
            $dtm = explode(SEPARATOR, $dateTime);
            $meeting_GMT_Time = $dtm[0];
            $meeting_Local_Time = $dtm[1];
            // Timezone : End

            if ($meeting_GMT_Time >= $Current_GMT_Datetime)
            {
                $valid_time = strtotime("+" . GRACE_PERIOD . "minutes", strtotime($Current_GMT_Datetime));

                if ($strTimestamp <= $valid_time)
                {
                    if ($stat == 0)
                    {
                        try
                        {
                            $arrPartnerUserDetails = isAuthenticatePartnerUser($strClientID, $strUserEmail, $strUserPW, $objDataHelper);
                        }
                        catch (Exception $e)
                        {
                            throw new Exception("scheduleMeeting.php : isAuthenticatePartnerUser Failed : " . $e->getMessage(), 6002);
                        }

                        if ((is_array($arrPartnerUserDetails)) && (sizeof($arrPartnerUserDetails)) > 0)
                        {
                            $user_ID = $arrPartnerUserDetails[0]['user_id'];
                            $user_timezones = $arrPartnerUserDetails[0]['timezones'];
                            $user_gmt = $arrPartnerUserDetails[0]['gmt'];
                            $user_email_id = $arrPartnerUserDetails[0]['email_address'];

                            $client_secret_key =  $arrPartnerUserDetails[0]['client_secret_key'];

                            $newPasscode = md5($user_email_id . $strTimestamp . $client_secret_key);

                            if ($newPasscode == $strPassCode)
                            {
                                try
                                {
                                    $arrUserSubscriptionDetails = isValidUserSubscription($user_ID, $strSubscriptionID, $objDataHelper);
                                }
                                catch (Exception $e)
                                {
                                    throw new Exception("scheduleMeeting.php : isValidUserSubscription Failed : " . $e->getMessage(), 6003);
                                }
                                if ((is_array($arrUserSubscriptionDetails)) && (sizeof($arrUserSubscriptionDetails)) > 0)
                                {
                                    $subscription_ID = $arrUserSubscriptionDetails[0]['subscription_id'];
                                    $plan_Type = $arrUserSubscriptionDetails[0]['plan_type'];
                                    $invitee_Count = $arrUserSubscriptionDetails[0]['number_of_invitee'];
                                    $meeting_Recording = $arrUserSubscriptionDetails[0]["meeting_recording"];
                                    $max_Sessions_Minutes = $planDetails[0]["number_of_mins_per_sessions"];
                                    $number_Of_Session = $arrUserSubscriptionDetails[0]['number_of_sessions'];
                                    $consumed_Session = $arrUserSubscriptionDetails[0]['consumed_number_of_sessions'];
                                    $number_Of_Minutes = $arrUserSubscriptionDetails[0]["talk_time_mins"];
                                    $consumed_Talk_Time_Mins = $arrUserSubscriptionDetails[0]['consumed_talk_time_mins'];
                                    $concurrent_Session = $arrUserSubscriptionDetails[0]['concurrent_sessions'];
                                    $sub_End_Date_GMT = $arrUserSubscriptionDetails[0]['subscription_end_date_gmt'];

                                    if (date("Y-m-d", strtotime($Current_GMT_Datetime)) > $sub_End_Date_GMT)
                                    {
                                        $stat = -1;
                                        $msg = "Your subscription is Expired on " . $sub_End_Date_GMT . ", Please subscribe for the plan";
                                    }
                                    else if (date("Y-m-d", strtotime($meeting_GMT_Time)) > $sub_End_Date_GMT)
                                    {
                                        $stat = -1;
                                        $msg = "Schedule date exceeds Plan Expiry Date";
                                    }
                                    else if ((sizeof($inviteeArray) > $invitee_Count) && ($invitee_Count != 0))
                                    {
                                        $stat = -1;
                                        $msg = "Your max limit for Invitees is " . $invitee_Count . "";
                                    }
                                    else if ($plan_Type == 'S')
                                    {
                                        if ($consumed_Session > $number_Of_Session)
                                        {
                                            $stat = -1;
                                            $msg = "Consumed number of session exceeds allowed number of session";
                                        }
                                    }
                                    else if ($plan_Type == 'T')
                                    {
                                        if ($consumed_Talk_Time_Mins > $number_Of_Minutes)
                                        {
                                            $stat = -1;
                                            $msg = "Consumed Talk Time exceeds allowed talk time";
                                        }
                                    }

                                    if ($stat == 0)
                                    {
                                        if ($concurrent_Session != '0')
                                        {
                                            // Concurrent Session : Start
                                            try
                                            {
                                                $arrConcurrentSessionsDetails = getConcurrentSessions($user_ID, $subscription_ID, $meeting_GMT_Time, $objDataHelper);
                                            }
                                            catch (Exception $e)
                                            {
                                                throw new Exception("scheduleMeeting.php : getConcurrentSessions Failed : " . $e->getMessage(), 6004);
                                            }

                                            if (is_array($arrConcurrentSessionsDetails) && (sizeof($arrConcurrentSessionsDetails) == 1))
                                            {
                                                $mTitle = $arrConcurrentSessionsDetails[0]["meeting_title"];
                                                $mTime = date("D, F jS Y, h:i A", strtotime($arrConcurrentSessionsDetails[0]["meeting_timestamp_local"]));
                                                $mZone = $arrConcurrentSessionsDetails[0]["meeting_timezone"];
                                                $session_info = "Your meeting schedule clashes with meeting '" . $mTitle . "' scheduled on " . $mTime . " , " . $mZone . "";
                                            }

                                            if (is_array($arrConcurrentSessionsDetails) && (sizeof($arrConcurrentSessionsDetails) > 1))
                                            {
                                                $session_info = "Your meeting schedule date-time clashes with multiple meetings";
                                            }

                                            if ($strScheduleType == 'N' && is_array($arrConcurrentSessionsDetails) && (sizeof($arrConcurrentSessionsDetails)) >= $concurrent_Session)
                                            {
                                                $stat = -1;
                                                $msg = $session_info . "Please change your Meeting Schedule Date Time";
                                            }

                                            if ($strScheduleType == 'L' && is_array($arrConcurrentSessionsDetails) && (sizeof($arrConcurrentSessionsDetails)) >= $concurrent_Session)
                                            {
                                                $stat = 0;
                                                $msg = $session_info;
                                            }

                                            if (($strScheduleType == 'N' || $strScheduleType == 'L') && empty($arrConcurrentSessionsDetails))
                                            {
                                                $stat = 0;
                                            }
                                            // Concurrent Session : End
                                        }

                                        if (($stat == 0) || ($concurrent_Session == '0'))
                                        {
                                            try
                                            {
                                                $schedule_ID = getScheduleID($objDataHelper);
                                            }
                                            catch (Exception $e)
                                            {
                                                throw new Exception("scheduleMeeting.php : getScheduleID Failed : " . $e->getMessage(), 6005);
                                            }

                                            if (strlen($schedule_ID) > 0)
                                            {
                                                try
                                                {
                                                    $voice_Bridge_Token = getVoiceBridgeToken($objDataHelper);
                                                }
                                                catch (Exception $e)
                                                {
                                                    throw new Exception("scheduleMeeting.php : getVoiceBridgeToken Failed : " . $e->getMessage(), 6006);
                                                }

                                                // Insert meeting schedule details : Start 
                                                $meeting_Instance = $arrPartnerUserDetails[0]['rt_server_name'];
                                                $meeting_Attendee_PWD = ATTENDEE_PWD;
                                                $meeting_Moderator_PWD = MODERATOR_PWD;
                                                $meeting_Agenda = '';
                                                $welcome_Message = '';
                                                $web_voice = $voice_Bridge_Token;
                                                $meta_Tags = '';
                                                $email_Reminder_Flag = 'Y';
                                                $email_Reminder_Status = '0';
                                                $sms_Reminder_Flag = 'N';
                                                $sms_Reminder_Status = '0';

                                                try
                                                {
                                                    $arrInsertSchedule = insScheduleDetails($schedule_ID, $user_ID, $Current_GMT_Datetime, $meeting_GMT_Time, $meeting_Local_Time, $strMeetingTitle, $meeting_Agenda, $strTimezone, $meeting_GMT, $meeting_Attendee_PWD, $meeting_Moderator_PWD, $welcome_Message, $voice_Bridge_Token, $web_voice, $invitee_Count, $meeting_Recording, $max_Sessions_Minutes, $meta_Tags, $email_Reminder_Flag, $email_Reminder_Status, $sms_Reminder_Flag, $sms_Reminder_Status, $meeting_Instance, $subscription_ID, $objDataHelper);
                                                }
                                                catch (Exception $e)
                                                {
                                                    throw new Exception("scheduleMeeting.php : insScheduleDetails Failed : " . $e->getMessage(), 6007);
                                                }

                                                $strInsScheduleStatus = trim($arrInsertSchedule[0]['@status']);
                                                $strInsScheduleOutPut = trim($arrInsertSchedule[0]['@output']);
                                                //Insert meeting schedule details : End

                                                if ($strInsScheduleStatus == '1')
                                                {
                                                    // Update Consumed session : Start
                                                    $type = "A";
                                                    try
                                                    {
                                                        $updSession = updConsumedSessions($subscription_ID, $user_ID, $type, $objDataHelper);
                                                    }
                                                    catch (Exception $e)
                                                    {
                                                        throw new Exception("scheduleMeeting.php : updConsumedSessions Failed : " . $e->getMessage(), 6008);
                                                    }
                                                    // Update Consumed session : End

                                                    for ($intCount = 0; $intCount < sizeof($inviteeArray); $intCount++)
                                                    {
                                                        // Insert invitation details : Start
                                                        if ($inviteeArray[$intCount]['moderatorFlag'] == 'Y')
                                                        {
                                                            $invitation_Creator = 'M';
                                                        }
                                                        else
                                                        {
                                                            $invitation_Creator = 'I';
                                                        }

                                                        try
                                                        {
                                                            $arrInsertInvitation = insInvitationDetails($schedule_ID, $inviteeArray[$intCount]['inviteeEmail'], $inviteeArray[$intCount]['inviteeNickName'], $inviteeArray[$intCount]['inviteeIDDCode'], $inviteeArray[$intCount]['inviteeMobile'], $invitation_Creator, $Current_GMT_Datetime, $objDataHelper);
                                                        }
                                                        catch (Exception $e)
                                                        {
                                                            throw new Exception("scheduleMeeting.php : insInvitationDetails Failed : " . $e->getMessage(), 6009);
                                                        }
                                                        $strInsInvitationStatus = trim($arrInsertInvitation[0]['@status']);
                                                        // Insert invitation details : End

                                                        if ($strInsInvitationStatus == '1')
                                                        {
                                                            // Send Invitee Mail : Start
        //                                                        try
        //                                                        {
        //                                                            $jMail = sendInviteesMeetingMail($schedule_ID, $meeting_GMT_Time, $meeting_Local_Time, $strTimezone, $strMeetingTitle, $user_email_id, $inviteeArray[$intCount]['inviteeEmail']);
        //                                                        }
        //                                                        catch (Exception $e)
        //                                                        {
        //                                                            throw new Exception("scheduleMeeting.php : sendInviteesMeetingMail Failed : ".$e->getMessage(), 6010);
        //                                                        }
                                                            // Send Invitee Mail : End
                                                        }
                                                        else
                                                        {
                                                            $stat = -1;
                                                            $msg = "Some technical error occured";
                                                        }
                                                    }
                                                }
                                                else
                                                {
                                                    $stat = -1;
                                                    $msg = "Some technical error occured";
                                                }
                                            }
                                            else
                                            {
                                                $stat = -1;
                                                $msg = "Please try again";   //Schedule ID Generation Failed 
                                            }
                                        }
                                    }
                                }
                                else
                                {
                                    $stat = -1;
                                    $msg = "No subscription details found";
                                }
                            }
                            else
                            {
                                $stat = -1;
                                $msg = "Passcode mismathced";
                            }
                        }
                        else
                        {
                            $stat = -1;
                            $msg = "Invalid Partner user";
                        }
                    }
                }
                else
                {
                    $ctime = strtotime($Current_GMT_Datetime);
                    $valid_time = strtotime("+" . GRACE_PERIOD . "minutes", strtotime($Current_GMT_Datetime));
                    $stat = -1;
                    $msg = "Invalid Request TimeStamp=" . $strTimestamp . "-- ValidTimeStamp" . $valid_time . "-- CurrentTimeStamp" . $ctime;
                }
            }
            else
            {
                $stat = -1;
                $msg = "Schedule time cannot be less than current Datetime";
            }
        }
        else
        {
            $stat = -1;
            $msg = "Error, invalid schedule data request";
        }
}
else
{
    $stat = -1;
    $msg = "Error, invalid schedule meeting request";
}

if ($stat != 0)
{
    $returncode = "FAILED";
    $returnMSG = "<message>$msg</message>";
}
else if ($stat == 0)
{
    $returncode = "SUCCESS";
    if ($msg == "")
    {
        $returnMSG ="<scheduleID>$schedule_ID</scheduleID>\n<message>Meeting Scheduled Successfully</message>";
    }
}

$xmlResponse = '<?xml version="1.0" encoding="ISO-8859-1" ?>
<response>
<returncode>' . $returncode . '</returncode>';
    $xmlResponse .= $returnMSG;
    $xmlResponse .= '</response>';

echo $xmlResponse;

$debug_log = 1;
$LogPath = LOGS_PATH . "/partner_api_logs/";

if ($debug_log == 1)
{
    error_log(date("Y-m-d H:i:s") . ", scheduleMeeting , " . $_SERVER['REMOTE_ADDR'] . ", " . $meetingData . ", " . $prID . ", \nRESPONSE, " . $xmlResponse . "\r\n\n", 3, $LogPath . "prt_api_scheduleMeeting_" . date('Y-m-d') . ".log");
}
?>