<?php

require_once('../includes/global.inc.php');
require_once(CLASSES_PATH.'error.inc.php');
require_once(DBS_PATH.'DataHelper.php');
require_once(DBS_PATH.'objDataHelper.php');
require_once(INCLUDES_PATH.'db_common_function.inc.php');
require_once(INCLUDES_PATH.'cm_authfunc.inc.php');
require_once(INCLUDES_PATH.'cm_authorize.inc.php');
require_once(INCLUDES_PATH.'profile_function.inc.php');
require_once(INCLUDES_PATH.'sch_function.inc.php');
require_once(INCLUDES_PATH.'mail_common_function.inc.php');
require_once(INCLUDES_PATH.'api_db_function.inc.php');

try
{
    //$scheduleID = trim($_REQUEST['SCHID']);
    $moderatorEmail = trim($_REQUEST['moderatorEMAIL']);
    $voiceBridge = trim($_REQUEST["voiceBRIDGE"]);
    $inviteesEmail = trim($_REQUEST['inviteesEMAIL']);
    $inviteesNick = trim($_REQUEST['inviteesNICK']);
    $PRID = trim($_REQUEST['PRID']);

    if (strlen(trim($moderatorEmail)) <= 0)
    {
        $stat = -1;
        $msg = "Missing Parameter moderatorEMAIL";
    }
    else if (!(filter_var($moderatorEmail, FILTER_VALIDATE_EMAIL)))
    {
        $stat = -1;
        $msg = "Invalid Parameter moderatorEMAIL";
    }
    else if (strlen(trim($voiceBridge)) <= 0)
    {
        $stat = -1;
        $msg = "Missing Parameter voiceBRIDGE";
    }
    else if (strlen(trim($inviteesEmail)) <= 0)
    {
        $stat = -1;
        $msg = "Missing Parameter inviteesEMAIL";
    }
    else if (!(filter_var($inviteesEmail, FILTER_VALIDATE_EMAIL)))
    {
        $stat = -1;
        $msg = "Invalid Parameter inviteesEMAIL";
    }
    else if (strlen(trim($inviteesNick)) <= 0)
    {
        $stat = -1;
        $msg = "Missing Parameter inviteesNICK";
    }
    else if (strlen(trim($PRID)) <= 0)
    {
        $stat = -1;
        $msg = "Missing Parameter PRID";
    }

    if (trim($stat) == "")
    {
        try
        {
            $arrSchId = getSchId($moderatorEmail, $voiceBridge, $objDataHelper);
        }
        catch (Exception $e)
        {
            throw new Exception("Error in getSchId.".$a->getMessage(), 311);
        }


        if ((is_array($arrSchId)) && (sizeof($arrSchId)) > 0)
        {
            $scheduleID = $arrSchId[0]["schedule_id"];

            try
            {
                $arrSchDtls = isScheduleValid($scheduleID, $objDataHelper);
            }
            catch (Exception $a)
            {
                throw new Exception("Error in isAuthenticateScheduleUser.".$a->getMessage(), 311);
            }

            if ((is_array($arrSchDtls)) && (sizeof($arrSchDtls)) > 0)
            {
                $strUser_id = $arrSchDtls[0]['user_id'];
                $inviteeCount = $arrSchDtls[0]['number_of_invitee'];

                try
                {
                    $inviteeList = getMeetingInviteeList($scheduleID, $objDataHelper);
                }
                catch (Exception $e)
                {
                    throw new Exception("addInvitee.php : getMeetingInviteeList Failed : ".$e->getMessage(), 1141);
                }

                if ((is_array($inviteeList)) && (sizeof($inviteeList)) > 0)
                {
                    for ($i = 0; $i < sizeof($inviteeList); $i++)
                    {
                        $sArr[$i] = $inviteeList[$i]["invitee_email_address"];
                    }

                    if (in_array($inviteesEmail, $sArr))
                    {
                        $stat = "0";
                        $msg = "Invitee already exists.";
                    }

                    if (sizeof($inviteeList) <= $inviteeCount)
                    {
                        if (strlen($msg) <= 0)
                        {
                            try
                            {
                                $scheduleDetails = getScheduleMeeting($strUser_id, $scheduleID, $objDataHelper);
                            }
                            catch (Exception $e)
                            {
                                throw new Exception("addInvitee.php : getScheduleMeeting Failed : ".$e->getMessage(), 1142);
                            }

                            $gmTime = $scheduleDetails[0]["meeting_timestamp_gmt"];
                            $localTime = $scheduleDetails[0]["meeting_timestamp_local"];
                            $timezone = $scheduleDetails[0]["meeting_timezone"];
                            $meeting_title = $scheduleDetails[0]["meeting_title"];

                            $arrInviteesEmail = $inviteesEmail.":".$inviteesNick."::";
                            $strUserDetails = "";
                            try
                            {
                                $invitees = inviteesDetails($scheduleID, "", $strUserDetails, $arrInviteesEmail, $moderator, $objDataHelper);
                            }
                            catch (Exception $e)
                            {
                                throw new Exception("addInvitee.php : inviteesDetails Failed : ".$e->getMessage(), 1143);
                            }

                            try
                            {
                                $counter = setScheduleCounter($scheduleID, $objDataHelper);
                            }
                            catch (Exception $e)
                            {
                                throw new Exception("addInvitee.php : setScheduleCounter Failed : ".$e->getMessage(), 1144);
                            }

                            try
                            {
                                $jMail = createInviteesMeetingMail($scheduleID, $gmTime, $localTime, $timezone, $meeting_title, $strCk_email_address, $strCk_nick_name, $inviteesEmail);
                            }
                            catch (Exception $e)
                            {
                                throw new Exception("addInvitee.php : createInviteesMeetingMail Failed : ".$e->getMessage(), 1145);
                            }
                            $stat = "1";
                            $msg = "Invitee Added Successfully.";
                        }
                    }
                    else
                    {
                        $stat = "0";
                        $msg = "Your max limit for Invitees is ".$inviteeCount.".";
                    }
                }
                else
                {
                    $stat = "-1";
                    $msg = "Invalid Data";
                }
            }
            else
            {
                $stat = "-1";
                $msg = "Invalid SCHID";
            }
        }
        else
        {
             $stat = "-1";
             $msg = "Invalid SCHID";
        }
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
$finalStat = $stat.SEPARATOR.$msg;
echo $finalStat;
?>