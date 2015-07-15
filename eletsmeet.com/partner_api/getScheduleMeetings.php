<?php

date_default_timezone_set('GMT');

require_once('../includes/global.inc.php');
require_once(CLASSES_PATH . 'error.inc.php');
require_once(DBS_PATH . 'DataHelper.php');
require_once(DBS_PATH . 'objDataHelper.php');
require_once(INCLUDES_PATH . "db_common_function.inc.php");
require_once('includes/partner_api_db_function.inc.php');

//header('Content-type: text/xml; charset=utf-8');

$strClientID = $_REQUEST['clientID'];
$strUserEmail = $_REQUEST['userEmail'];
$strUserPW = $_REQUEST['userPW'];
$strMeetingType = $_REQUEST['meetingType'];
$strTimestamp = $_REQUEST['TS'];
$strPassCode = $_REQUEST['passCode'];
$strProtocolID = $_REQUEST['protocolID'];

print_r($_REQUEST); 

// Validate Parameters Received : Start
$stat = 0;
$msg = "";

$Current_GMT_Datetime = GM_DATE;

if (!isset($strClientID))
{
    $stat = 1;
    $msg = "MISSING PARAMETER CLIENT ID";
}
else if (!isset($strUserEmail))
{
    $stat = 1;
    $msg = "MISSING PARAMETER USER EMAIL";
}
else if (!filter_var($strUserEmail, FILTER_VALIDATE_EMAIL))
{
    $stat = 1;
    $msg = "INVALID PARAMETER USER EMAIL";
}
else if (!isset($strUserPW))
{
    $stat = 1;
    $msg = "MISSING PARAMETER USER PW";
}
else if (!isset($strMeetingType))
{
    $stat = 1;
    $msg = "MISSING PARAMETER MEETING TYPE";
}
else if (!isset($strTimestamp))
{
    $stat = 1;
    $msg = "MISSING PARAMETER TIMESTAMP";
}
else if (!is_numeric($strTimestamp))
{
    $stat = 1;
    $msg = "INVALID PARAMETER TIMESTAMP";
}
else if (!isset($strPassCode))
{
    $stat = 1;
    $msg = "MISSING PARAMETER PASSCODE";
}
else if (!isset($strProtocolID))
{
    $stat = 1;
    $msg = "MISSING PARAMETER PROTOCOL ID";
}
else if (!is_numeric($strProtocolID))
{
    $stat = 1;
    $msg = "INVALID PARAMETER PROTOCOL ID";
}
// Validate Parameters Received : End

if ($stat === 0)
{
    try
    {
        $arrPartnerUserDetails = isAuthenticatePartnerUser($strClientID, $strUserEmail, $strUserPW, $objDataHelper);
    }
    catch (Exception $e)
    {
        throw new Exception("getScheduleMeetings.php : isAuthenticatePartnerUser Failed : " . $e->getMessage(), 6002);
    }

    if ((is_array($arrPartnerUserDetails)) && (sizeof($arrPartnerUserDetails)) > 0)
    {
        $user_ID = $arrPartnerUserDetails[0]['user_id'];
        $user_email_id = $arrPartnerUserDetails[0]['email_address'];
        $user_timezones = $arrPartnerUserDetails[0]['timezones'];

        $newPasscode = md5($user_email_id . $strTimestamp . CLIENT_SECRET_KEY);

        if ($newPasscode == $strPassCode)
        {
            //Get the future meeting list
            if ($strMeetingType == 'F')
            {
                try
                {
                    $arrMeetingList = getFutureMeetingList($user_ID, $objDataHelper);
                }
                catch (Exception $e)
                {
                    throw new Exception("getScheduleMeetings.php : getFutureMeetingList Failed : " . $e->getMessage(), 6003);
                }
            }
            
            //Get the archive meeting list
            else if ($strMeetingType == 'A')
            {
                try
                {
                    $arrMeetingList = getArchiveMeetingList($user_ID, $objDataHelper);
                }
                catch (Exception $e)
                {
                    throw new Exception("getScheduleMeetings.php : getFutureMeetingList Failed : " . $e->getMessage(), 6003);
                }
            }
            
            if ((is_array($arrMeetingList)) && (sizeof($arrMeetingList)) > 0)
            {

                for ($intCntr = 0; $intCntr < sizeof($arrMeetingList); $intCntr++)
                {
                    $mSchId = $arrMeetingList[$intCntr]["schedule_id"];
                    $mTitle = $arrMeetingList[$intCntr]["meeting_title"];
                    $mVoiceBridgeNo = $arrMeetingList[$intCntr]["voice_bridge"];
                    $mHost = $strUserEmail;
                    $mTime = dateFormat($arrMeetingList[$intCntr]["meeting_timestamp_gmt"], $arrMeetingList[$intCntr]["meeting_timestamp_local"], $user_timezones);

                    //Get the meeting invitee list
                    try
                    {
                        $arrInviteesList = getMeetingInviteeList($mSchId, $objDataHelper);
                    }
                    catch (Exception $a)
                    {
                        throw new Exception("Error in getMeetingInviteeList." . $a->getMessage(), 4104);
                    }

                    $totalParticipants = sizeof($arrInviteesList);
                    $msg .= '<schedulelist>
                            <schedule>
                                <scheduleID>' . $mSchId . '</scheduleID>
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
                        $msg .= '<participantName>' . $participantName . '</participantName>
                                        <participantEmail>' . $participantEmail . '</participantEmail>
                                        <participantRole>' . $strInvRole . '</participantRole>
                                        <invitationStatus>' . $strInvStatus . '</invitationStatus>
                                        <attendance>' . $strMeetingStatus . '</attendance>';
                    }
                    $msg .= '</participantsList>
                                 </schedule>
                              </schedulelist>';
                }
            }
            else
            {
                $stat = -1;
                $msg = "No scheduled meeting details found.";
            }
        }
        else
        {
            $stat = -1;
            $msg = "Passcode mismathced.";
        }
    }
    else
    {
        $stat = -1;
        $msg = "Invalid Partner user.";
    }
}
else
{
    $stat = -1;
    $msg = "Error, invalid get schedule meeting details request.";
}


if ($stat !== 0)
{
    $returncode = "FAILED";
}
else if ($stat === 0)
{
    $returncode = "SUCCESS";
}

$xmlResponse = '<?xml version="1.0" encoding="ISO-8859-1"?>
<response>
    <returncode>' . $returncode . '</returncode>
    <message>' . $msg . '</message>
</response>';
echo $xmlResponse;
?>