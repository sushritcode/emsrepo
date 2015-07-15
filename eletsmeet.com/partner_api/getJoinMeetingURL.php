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

$strClientID = $_REQUEST['clientID'];
$strUserEmail = $_REQUEST['userEmail'];
$strUserPW = $_REQUEST['userPW'];
$strScheduleID = $_REQUEST['scheduleID'];
$strTimestamp = $_REQUEST['TS'];
$strPassCode = $_REQUEST['passCode'];
$strProtocolID = $_REQUEST['protocolID'];

/* * * Validate Parameters Received : Start * * */

$stat = 0;
$msg = "";

$Current_GMT_Datetime = GM_DATE;

//if (!isset($strClientID))
if ($strClientID == '')
{
    $stat = 1;
    $msg = "Missing Parameter CLIENT ID";
}

if ($strUserEmail == '')
{
    $stat = 1;
    $msg = "Missing Parameter USER EMAIL";
}
else if (!filter_var($strUserEmail, FILTER_VALIDATE_EMAIL))
{
    $stat = 1;
    $msg = "Invalid Parameter USER EMAIL";
}

if ($strUserPW == '')
{
    $stat = 1;
    $msg = "Missing Parameter USER PW";
}
else if (!isset($strScheduleID))
{
    $stat = 1;
    $msg = "Missing Parameter SCHEDULE ID";
}

if ($strTimestamp == '')
{
    $stat = 1;
    $msg = "Missing Parameter TIMESTAMP";
}
else if (!is_numeric($strTimestamp))
{
    $stat = 1;
    $msg = "Invalid Parameter TIMESTAMP";
}

if ($strPassCode == '')    
{
    $stat = 1;
    $msg = "Missing Parameter PASSCODE";
}

if ($strProtocolID == '')    
{
    $stat = 1;
    $msg = "Missing Parameter PROTOCOL ID";
}
else if (!is_numeric($strProtocolID))
{
    $stat = 1;
    $msg = "Invalid Parameter PROTOCOL ID";
}
/* * * Validate Parameters Received : End * * */

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
                $arrSchDtls = isScheduleIdValid($strScheduleID, $objDataHelper);
            }
            catch (Exception $a)
            {
                throw new Exception("Error in isScheduleIdValid." . $a->getMessage(), 311);
            }

            if ((is_array($arrSchDtls)) && (sizeof($arrSchDtls)) > 0)
            {
                $Schedule_Id = trim($arrSchDtls[0]['schedule_id']);
                $mTitle = trim($arrSchDtls[0]['meeting_title']);
                $mVoiceBridgeNo = trim($arrSchDtls[0]['voice_bridge']);
                $mHost = $strUserEmail;
                $mTime = dateFormat($arrSchDtls[0]["meeting_timestamp_gmt"], $arrSchDtls[0]["meeting_timestamp_local"], $user_timezones);

                //Get the meeting invitee list
                try
                {
                    $arrInviteesList = getMeetingInviteeList($Schedule_Id, $objDataHelper);
                }
                catch (Exception $a)
                {
                    throw new Exception("Error in getMeetingInviteeList." . $a->getMessage(), 4104);
                }
                //print_r($arrInviteesList);

                $totalParticipants = sizeof($arrInviteesList);
                $msg .= '<schedulelist>
                            <schedule>
                                <scheduleID>' . $Schedule_Id . '</scheduleID>
                                <meetingTitle>' . $mTitle . '</meetingTitle>
                                <meetingHost>' . $mHost . '</meetingHost>
                                <meetingTime>' . $mTime . '</meetingTime>
                                <VoiceBridgeNumber>' . $mVoiceBridgeNo . '</VoiceBridgeNumber>
                                <DialNumber>(USA) +' . DIAL_NUMBER . '</DialNumber>
                                <totalParticipants>' . $totalParticipants . '</totalParticipants>
                                    <participantsList>';
                                        foreach ($arrInviteesList as $key => $value)
                                        {
                                            $participantName = $value["invitee_nick_name"];
                                            $participantEmail = $value["invitee_email_address"];
                                            
                                            //0=invited, 1=Accepted, 2=Declined 
                                            switch ($value['invitation_status'])
                                            {
                                                case "1" :
                                                    $strInvStatus = "Accepted";
                                                    break;
                                                case "2" :
                                                    $strInvStatus = "Declined";
                                                    break;
                                                default:
                                                    $strInvStatus = "Invited";
                                            }
                                            
                                            //C= Creator & Moderator, M=Moderator, I=Invitee
                                            switch ($value['invitation_creator'])
                                            {
                                                case "C" :
                                                    $strInvRole = "Host (Moderator)";
                                                    break;
                                                case "M" :
                                                    $strInvRole = "Moderator";
                                                    break;
                                                default:
                                                    $strInvRole = "Invitee";
                                            }
                                            
                                            //0=invited, 1=Joined
                                            switch ($value['meeting_status'])
                                            {
                                                case "1" :
                                                    $strMeetingStatus = "Yes";
                                                    break;
                                                default:
                                                    $strMeetingStatus = "No";
                                            }
                                            $PSCD = md5($Schedule_Id . ":" . $participantEmail . ":" . SECRET_KEY);
                                            echo $jmData = "SCID=" . $Schedule_Id . "&EMID=" . $participantEmail . "&PSCD=" . $PSCD . "&PRID=" . PRID;
                                            echo $jmUrl = JMX_API_ROOT . "?" . $jmData;
                                            
                                            $msg .= '<participant>';
                                            $msg .= '<participantName>' . $participantName . '</participantName>
                                                                <participantEmail>' . $participantEmail . '</participantEmail>
                                                                <joinURL>' . $jmUrl . '</joinURL>
                                                                <participantRole>' . $strInvRole . '</participantRole>
                                                                <invitationStatus>' . $strInvStatus . '</invitationStatus>
                                                                <attendance>' . $strMeetingStatus . '</attendance>';
                                            $msg .= '</participant>';
                                        }
                                        $msg .= '</participantsList>
                                                         </schedule>
                                                      </schedulelist>';


//                                echo $validUserEmail = in_array($strUserEmail, $arrInviteesList);
//
//                                foreach ($arrInviteesList as $key => $value)
//                                {
//                                    $validUserEmail = in_array($strUserEmail, $value);
//                                }            
//
//                                if ($validUserEmail == 1)
//                                {
//                                    echo "here";
//                                    $PSCD = md5($Schedule_Id . ":" . $strUserEmail . ":" . SECRET_KEY);
//                                    $jmData = "SCID=" . $Schedule_Id . "&EMID=" . $strUserEmail . "&PSCD=" . $PSCD . "&PRID=" . PRID;
//                                    $jmUrl = JMX_API_ROOT . "?" . $jmData;
//                                    $msg = $jmUrl;
//                                }
//                                else
//                                {
//                                    $stat = -1;
//                                    $msg = "Error, User Email is not authenticated.";
//                                }
            }
            else
            {
                $stat = -1;
                $msg = "Error, invalid meeting information";
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
else
{
    $stat = -1;
    $msg = "Error, invalid get join meeting url request";
}


if ($stat != 0)
{
    $returncode = "FAILED";
    $returnMSG = "<message>$msg</message>";
}
else if ($stat == 0)
{
    $returncode = "SUCCESS";
    $returnMSG = $msg;
}

$xmlResponse = '<?xml version="1.0" encoding="ISO-8859-1" ?>
<response>
<returncode>' . $returncode . '</returncode>';
    $xmlResponse .= $returnMSG;
    $xmlResponse .= '</response>';
echo $xmlResponse;
?>