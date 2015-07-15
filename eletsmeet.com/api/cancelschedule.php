<?php

require_once('../includes/global.inc.php');
require_once(CLASSES_PATH.'error.inc.php');
require_once(DBS_PATH.'DataHelper.php');
require_once(DBS_PATH.'objDataHelper.php');
require_once(INCLUDES_PATH.'api_db_function.inc.php');
require_once(INCLUDES_PATH.'db_common_function.inc.php');
require_once(INCLUDES_PATH.'mail_common_function.inc.php');

header('Content-type: text/plain; charset=utf-8');

try
{
    $strSCID = trim($_REQUEST["SCID"]);   //schedule_id
    $strPRID = trim($_REQUEST["PRID"]);   //protocol id

    $STATUS = '';

    if (strlen(trim($strSCID)) <= 0)
    {
        $STATUS = -1;
        $MESSAGE = "Missing Parameter SCID";
    }
    elseif (strlen(trim($strPRID)) <= 0)
    {
        $STATUS = -1;
        $MESSAGE = "Missing Parameter PRID";
    }
    elseif (!is_numeric($strPRID))
    {
        $STATUS = -1;
        $MESSAGE = "Invalid characters in PRID";
    }
    elseif ($strPRID != PRID)
    {
        $STATUS = -1;
        $MESSAGE = "Invalid PRID";
    }

    $Current_GMT_Datetime = GM_DATE;

    if (trim($STATUS) == "")
    {
        try
        {
            $arrSchDtls = isScheduleValid($strSCID, $objDataHelper);
        }
        catch (Exception $a)
        {
            throw new Exception("Error in isAuthenticateScheduleUser.".$a->getMessage(), 311);
        }

        if ((is_array($arrSchDtls)) && (sizeof($arrSchDtls)) > 0)
        {
            $Schedule_Id = trim($arrSchDtls[0]['schedule_id']);
            $Schedule_Status = trim($arrSchDtls[0]['schedule_status']);
            $Meeting_Title = trim($arrSchDtls[0]['meeting_title']);
            $Meeting_Time = dateFormat(trim($arrSchDtls[0]['meeting_timestamp_gmt']), trim($arrSchDtls[0]['meeting_timestamp_local']), trim($arrSchDtls[0]['meeting_timezone']));
            $Creator_Email = trim($arrSchDtls[0]['email_address']);
            $Meeting_Hosted_By = trim($arrSchDtls[0]['nick_name']);
            $User_Id = trim($arrSchDtls[0]['user_id']);
            $Subscription_Id = trim($arrSchDtls[0]['subscription_id']);
            $userorderId = $arrSchDtls[0]["order_id"];
            
            if (trim($Schedule_Status) == "0")
            {
                //Meeting Status = 0 then Update to 3 (Cancelled)
                $New_Schedule_Status = '3';

                try
                {
                    $arrCancelSchedule = cancelSchedule($Schedule_Id, $New_Schedule_Status, $Current_GMT_Datetime, $objDataHelper);
                }
                catch (Exception $a)
                {
                    throw new Exception("Error in cancelSchedule.".$a->getMessage(), 4102);
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
                        throw new Exception("Error in updConsumedSessions.".$a->getMessage(), 4103);
                    }
                    $strUpdConStatus = trim($arrUpdConSession[0]['@result']);
                    if ($strUpdConStatus == 1)
                    {
                            try
                           {
                               $arrClSubDtls = getClSubInfoFromUserOrderId($userorderId, $objDataHelper);
                           }
                           catch (Exception $e)
                           {
                               throw new Exception("createSchedule.php : updConsumedSessions Failed : ".$e->getMessage(), 1137);
                           }
                           
                           $strClSubId = $arrClSubDtls[0]['client_subscription_id'];
                           $strClientId = $arrClSubDtls[0]['client_id'];
                                   
                          $Type = "S";
                           try
                           {
                               $updSession = updClientConsumedSessions($strClSubId, $strClientId, $Type, $objDataHelper);
                           }
                           catch (Exception $e)
                           {
                               throw new Exception("createSchedule.php : updConsumedSessions Failed : ".$e->getMessage(), 1137);
                           }
                    }

                    //Get the meeting invitee list
                    try
                    {
                        $arrInviteesList = getMeetingInviteeList($Schedule_Id, $objDataHelper);
                    }
                    catch (Exception $a)
                    {
                        throw new Exception("Error in getMeetingInviteeList.".$a->getMessage(), 4104);
                    }

                    //Sending the Cancelation mail to all meeting invitee
                    foreach ($arrInviteesList as $key => $value)
                    {
                        $InviteesEmailnNick .= $value['invitee_email_address'] = $value['invitee_email_address'].'#'.$value['invitee_nick_name'].",";
                    }
                    $InviteesEmailnNick = substr($InviteesEmailnNick, 0, -1);

                    cancelMeetingMail($Meeting_Title, $Meeting_Time, $Creator_Email, $Meeting_Hosted_By, $InviteesEmailnNick);

                    $STATUS = 1;
                    $MESSAGE = "Meeting has been cancelled successfully.";
                }
                else
                {
                    $STATUS = -8;
                    $MESSAGE = "Error, while canceling meeting.";
                }
            }
            else
            {
                $STATUS = 7;
                $MESSAGE = "Sorry, you can't cancel the meeting.";
            }
        }
        else
        {
            $STATUS = -8;
            $MESSAGE = "Error, invalid meeting information.";
        }
    }
    else
    {
        $STATUS = -1;
        $MESSAGE = "Error, invalid cancel meeting request.";
    }
}
catch (Exception $e)
{
    if ($STATUS == "")
    {
        $STATUS = -8;
    }

    if ($MESSAGE == "")
    {
        $MESSAGE = "Fatal Error";
    }
    $ErrorHandler->RaiseError($_SERVER["PHP_SELF"], $e->getCode(), $e->getMessage(), false);
}
$CANCEL_RESPONSE = $STATUS.SEPARATOR.$MESSAGE;
echo $CANCEL_RESPONSE;
?>