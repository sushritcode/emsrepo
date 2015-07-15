<?php

require_once('../includes/global.inc.php');
require_once(CLASSES_PATH.'error.inc.php');
require_once(DBS_PATH.'DataHelper.php');
require_once(DBS_PATH.'objDataHelper.php');
require_once(INCLUDES_PATH.'api_db_function.inc.php');
require_once(INCLUDES_PATH.'db_common_function.inc.php');

header('Content-type: text/plain; charset=utf-8');

try
{
    $strPRID = trim($_REQUEST["PRID"]);

    $STATUS = '';
    if (strlen(trim($strPRID)) <= 0)
    {
        $STATUS = -1;
        $MESSAGE = "Missing Parameter PRID";
    }
    else if (!is_numeric($strPRID))
    {
        $STATUS = -1;
        $MESSAGE = "Invalid characters in PRID";
    }

    if (trim($STATUS) == "")
    {
        if (trim($strPRID) == 1)
        {
            $strSCID_1 = trim($_REQUEST["SCID"]);
            if (strlen(trim($strSCID_1)) <= 0)
            {
                $STATUS = -1;
                $MESSAGE = "Missing Parameter SCID";
            }
        }
        else if (trim($strPRID) == 2)
        {
            $strEmail = trim($_REQUEST["EMAIL"]);
            $strVBridge = trim($_REQUEST["VBRIDGE"]);

            if (strlen(trim($strEmail)) <= 0)
            {
                $STATUS = -1;
                $MESSAGE = "Missing Parameter EMAIL";
            }
            else if (!(filter_var($strEmail, FILTER_VALIDATE_EMAIL)))
            {
                $STATUS = -1;
                $MESSAGE = "Invalid Parameter EMAIL";
            }
            else if (strlen(trim($strVBridge)) <= 0)
            {
                $STATUS = -1;
                $MESSAGE = "Missing Parameter VBRIDGE";
            }
        }
    }

    if (trim($STATUS) == "")
    {
        if (trim($strPRID) == 2)
        {
            try
            {
                $arrSchId = getSchId($strEmail, $strVBridge, $objDataHelper);
            }
            catch (Exception $e)
            {
                throw new Exception("Error in getSchId.".$a->getMessage(), 311);
            }
            
            if ((is_array($arrSchId)) && (sizeof($arrSchId)) > 0)
            {
                $strSCID_2 = $arrSchId[0]["schedule_id"];
            }
            else
            {
               $STATUS = 0;
               $MESSAGE = "No invitee list";
               $LIST = "";
               echo $INVITEE_LIST_RESPONSE = $STATUS.SEPARATOR.$MESSAGE.SEPARATOR.$LIST;
               exit;
            }
        }

        if (strlen(trim($strSCID_1)) > 0)
        {
            $strSCID = $strSCID_1;
        }
        else
        {
            $strSCID = $strSCID_2;
        }

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
            $strSchedule_Id = trim($arrSchDtls[0]['schedule_id']);
            $strSchedule_Status = trim($arrSchDtls[0]['schedule_status']);
            $Meeting_Title = trim($arrSchDtls[0]['meeting_title']);
            $strMeeting_Time = date("l, F jS Y, h:i:s A", strtotime(trim($arrSchDtls[0]['meeting_timestamp'])));
            $strCreator_Email = trim($arrSchDtls[0]['email_address']);

            try
            {
                $arrInviteesEmail = getMeetingInviteeList($strSchedule_Id, $objDataHelper);
            }
            catch (Exception $a)
            {
                throw new Exception("Error in getMeetingInviteeList.".$a->getMessage(), 311);
            }

            if ((is_array($arrInviteesEmail)) && (sizeof($arrInviteesEmail)) > 0)
            {
                if (trim($strPRID) == 1)
                {
                    foreach ($arrInviteesEmail as $key => $value)
                    {
                        $strInviteesEmails .= $value['invitee_email_address'].",";
                    }
                    $strInviteesEmails = substr($strInviteesEmails, 0, -1);
                }
                else if (trim($strPRID) == 2)
                {
                    $strInviteesEmails = "<?xml version=\"1.0\" encoding=\"utf-8\" ?><inviteelist><scheduleid value=\"".$strSchedule_Id."\"></scheduleid>";
                    foreach ($arrInviteesEmail as $key => $value)
                    {
                        if ($value['invitation_status'] == 0)
                        {
                            $stat = "Invited";
                        }
                        else if ($value['invitation_status'] == 1)
                        {
                            $stat = "Accepted";
                        }
                        else if ($value['invitation_status'] == 2)
                        {
                            $stat = "Declined";
                        }
                        $strInviteesEmails .= "<invitee><email>".$value['invitee_email_address']."</email><nickname>".$value['invitee_nick_name']."</nickname><status>".$stat."</status></invitee>";
                    }
                    $strInviteesEmails .= "</inviteelist>";
                }
                $STATUS = 1;
                $MESSAGE = "";
                $LIST = $strInviteesEmails;
            }
        }
        else
        {
            $STATUS = 0;
            $MESSAGE = "No invitee list";
            $LIST = "";
        }
    }
    $INVITEE_LIST_RESPONSE = $STATUS.SEPARATOR.$MESSAGE.SEPARATOR.$LIST;
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

echo $INVITEE_LIST_RESPONSE;
?>