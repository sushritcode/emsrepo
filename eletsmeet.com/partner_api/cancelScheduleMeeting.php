<?php
date_default_timezone_set('GMT');

require_once('../includes/global.inc.php');
require_once(CLASSES_PATH . 'error.inc.php');
require_once(DBS_PATH . 'DataHelper.php');
require_once(DBS_PATH . 'objDataHelper.php');
require_once(INCLUDES_PATH . "db_common_function.inc.php");
require_once('includes/partner_api_db_function.inc.php');
require_once('includes/partner_mail_function.inc.php');


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
else if (!isset($strScheduleID))
{
    $stat = 1;
    $msg = "MISSING PARAMETER SCHEDULE ID";
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
/* * * Validate Parameters Received : End * * */

if ($stat === 0)
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
        $Schedule_Status = trim($arrSchDtls[0]['schedule_status']);
        $Meeting_Title = trim($arrSchDtls[0]['meeting_title']);
        $Meeting_Time = dateFormat(trim($arrSchDtls[0]['meeting_timestamp_gmt']), trim($arrSchDtls[0]['meeting_timestamp_local']), trim($arrSchDtls[0]['meeting_timezone']));
        $meeting_GMT_Time = trim($arrSchDtls[0]['meeting_timestamp_gmt']);
        $Creator_Email = trim($arrSchDtls[0]['email_address']);
        $Meeting_Hosted_By = trim($arrSchDtls[0]['nick_name']);
        $User_Id = trim($arrSchDtls[0]['user_id']);
        $Subscription_Id = trim($arrSchDtls[0]['subscription_id']);

        $newPasscode = md5($strUserEmail . $strTimestamp . CLIENT_SECRET_KEY);
        
        if ($newPasscode == $strPassCode)
        {
            if ($meeting_GMT_Time >= $Current_GMT_Datetime)
            {
                if (trim($Schedule_Status) == "0")
                {
                    //Meeting Status = 0 then Update to 3 (Cancelled)
                    $New_Schedule_Status = '3';

                    try
                    {
                        $arrCancelSchedule = cancelScheduleMeeting($Schedule_Id, $New_Schedule_Status, $Current_GMT_Datetime, $objDataHelper);
                    }
                    catch (Exception $a)
                    {
                        throw new Exception("Error in cancelScheduleMeeting." . $a->getMessage(), 4102);
                    }
                    $strCancelStatus = trim($arrCancelSchedule[0]['@result']);

                    if ($strCancelStatus == 1)
                    {
                        //Cancel Status is 1 (Success) then reduce the number of consumed_session.
                        $Type = "S";

                        try
                        {
                            $arrUpdConSession = updConsumedSessions($Subscription_Id, $User_Id, $Type, $objDataHelper);
                        }
                        catch (Exception $a)
                        {
                            throw new Exception("Error in updConsumedSessions." . $a->getMessage(), 4103);
                        }
                        $strUpdConStatus = trim($arrUpdConSession[0]['@result']);

                        //Get the meeting invitee list
                        try
                        {
                            $arrInviteesList = getMeetingInviteeList($Schedule_Id, $objDataHelper);
                        }
                        catch (Exception $a)
                        {
                            throw new Exception("Error in getMeetingInviteeList." . $a->getMessage(), 4104);
                        }

                        //Sending the Cancelation mail to all meeting invitee
                        foreach ($arrInviteesList as $key => $value)
                        {
                            $InviteesEmailnNick .= $value['invitee_email_address'] = $value['invitee_email_address'] . '#' . $value['invitee_nick_name'] . ",";
                        }
                        $InviteesEmailnNick = substr($InviteesEmailnNick, 0, -1);

                        try
                        {
                            cancelScheduleMeetingMail($Meeting_Title, $Meeting_Time, $Creator_Email, $Meeting_Hosted_By, $InviteesEmailnNick);
                        }
                        catch (Exception $e)
                        {
                            throw new Exception("cancelScheduleMeeting.php : cancelScheduleMeetingMail Failed : " . $e->getMessage(), 6010);
                        }

                        $stat = 1;
                        $msg = "Meeting has been cancelled successfully.";
                    }
                    else
                    {
                        $stat = -1;
                        $msg = "Error, while canceling meeting.";
                    }
                }
                else
                {
                    $stat = -1;
                    $msg = "Sorry, you can't cancel the meeting.";
                }
            }
            else
            {
                $stat = -1;
                $msg = "Sorry, your meeting schedule time has been passed.";
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
        $msg = "Error, invalid meeting information.";
    }
}
else
{
    $stat = -1;
    $msg = "Error, invalid cancel meeting request.";
}

if ($stat !== 0)
{
    $returncode = "FAILED";
}
else if ($stat === 0)
{
    $returncode = "SUCCESS";
}


$xmlResponse = '<? xml version="1.0" encoding="ISO-8859-1" ?>
<response>
<returncode>' . $returncode . '</returncode>
<message>' . $msg . '</message>
</response>';

echo $xmlResponse;
?>
