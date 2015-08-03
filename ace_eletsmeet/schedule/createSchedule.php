<?php
require_once('../includes/global.inc.php');
require_once(CLASSES_PATH . 'error.inc.php');
require_once(DBS_PATH . 'DataHelper.php');
require_once(DBS_PATH . 'objDataHelper.php');
require_once(INCLUDES_PATH . 'cm_authfunc.inc.php');
$CONST_MODULE = 'schedule';
$CONST_PAGEID = 'Schedule Page';
require_once(INCLUDES_PATH . 'cm_authorize.inc.php');
require_once(INCLUDES_PATH . 'common_function.inc.php');
require_once(INCLUDES_PATH . 'schedule_function.inc.php');
//require_once(INCLUDES_PATH."mail_common_function.inc.php");

try
{
    $meeting_title = $_REQUEST['title'];
    $meeting_timestamp = $_REQUEST['schedule_dtm'];
    $inviteesCnt = $_REQUEST['inviteesCnt'];
    $arrInviteesEmail = $_REQUEST['inviteesList'];
    $scheduleType = $_REQUEST['scheduleType'];
    $tZone = $_REQUEST['tzone'];
    $moderator = $_REQUEST['mod'];
    $uPlan = $_REQUEST['uplan'];
    $schStat = $_REQUEST['stat'];  

    $userTimeZone = explode("$:$", $tZone);
    $timezone = $userTimeZone[1];
    $gmt = $userTimeZone[2];

    /* Meeting Date Time : Start */
    if ($scheduleType == "N")
    {
        $meeting_timestamp = GM_DATE;
    }
    else if ($scheduleType == "L")
    {
        $meeting_timestamp = date("Y-m-d H:i:s", strtotime($meeting_timestamp));
    }
    /* Meeting Date Time : End */

    /* Timezone : Start */
    $dateTime = timezoneConverter($scheduleType, $meeting_timestamp, $timezone);
    $dtm = explode(SEPARATOR, $dateTime);
    $gmTime = $dtm[0];
    $localTime = $dtm[1];
    /* Timezone : End */

    /* Meeting Invitees Count : Start */
    $inviteesCnt = sizeof(explode(",", $arrInviteesEmail));
    /* Meeting Invitees Count : End */

    /* Plan Details : Start */
    $userPlan = explode("$:$", $uPlan);
    $subId = $userPlan[0];
    $schDt = date("Y-m-d", strtotime($meeting_timestamp));
    try
    {
        $planDetails = validatePlan($subId, $strCK_user_id, $objDataHelper);
    }
    catch (Exception $e)
    {
        throw new Exception("createSchedule.php : validatePlan Failed : ".$e->getMessage(), 1131);
    }

    $subscriptionId = $planDetails[0]["subscription_id"];
    $maxSubscriptionDt = $planDetails[0]["subscription_end_date_gmt"];
    $planType = $planDetails[0]["plan_type"];
    $maxSessions = $planDetails[0]["number_of_sessions"];
    $maxSessionsMinutes = $planDetails[0]["number_of_mins_per_sessions"];
    $maxInviteesCount = $planDetails[0]["number_of_invitee"];
    $meetingRecoding = $planDetails[0]["meeting_recording"];
    $maxConcurrentSessions = $planDetails[0]["concurrent_sessions"];
    $maxTalktime = $planDetails[0]["talk_time_mins"];
    $consumedSessions = $planDetails[0]["consumed_number_of_sessions"];
    $consumedTalktime = $planDetails[0]["consumed_talk_time_mins"];
    $userorderId = $planDetails[0]["order_id"];
 
    /* Plan Details : End */

    /* Concurrent Session : Start */
    try
    {
        $currentSession = currentSession($strCK_user_id, $subscriptionId, $gmTime, $objDataHelper);
    }
    catch (Exception $e)
    {
        throw new Exception("createSchedule.php : currentSession Failed : ".$e->getMessage(), 1131);
    }

    if (is_array($currentSession) && sizeof($currentSession) > 0)
    {
        $sessionCount = sizeof($currentSession);
        foreach ($currentSession as $cSKey => $cSVal)
        {
            $currentMStatus .= $cSVal["schedule_status"].",";
        }
        $currentMStatus = substr_replace($currentMStatus, "", -1);
    }
    else
    {
        $sessionCount = 0;
    }
    $activeMCount = array_count_values(explode(",", $currentMStatus));
    $activeMCount = $activeMCount[0];
    /* Concurrent Session : End */

    /* Validation For Data Received : Start */
    if (($scheduleType == "N") && ($activeMCount > 0) && ((int) $activeMCount >= (int) $maxConcurrentSessions && (int) $maxConcurrentSessions != 0))
    {
        $stat = 0;
        $msg = "You already have a meeting in progress. Please select a different date-time.";
    }
    else if (strtotime($schDt) > strtotime($maxSubscriptionDt))
    {
        $stat = 0;
        $msg = "Schedule date exceeds Plan Expiry Date";
    }
    else if (($schStat == 'N') && ((int) $sessionCount >= (int) $maxConcurrentSessions && (int) $maxConcurrentSessions != 0))
    {
        if ((int) $sessionCount > 1)
        {
            $m1 = "Your meeting schedule date-time clashes with multiple meetings.";
        }
        else if ((int) $sessionCount == 1)
        {
            $mTitle = $currentSession[0]["meeting_title"];
            $mTime = date("D, F jS Y, h:i A", strtotime($currentSession[0]["meeting_timestamp_local"]));
            $mZone = $currentSession[0]["meeting_timezone"];
            $m1 = "Your meeting schedule clashes with meeting '".$mTitle."' scheduled on ".$mTime." , ".$mZone.".";
        }

        if ($scheduleType == "N")
        {
            $stat = 2;
            $msg = $m1." Please change your Meeting Schedule Date Time.";
        }
        else if ($scheduleType == "L")
        {
            $stat = 3;
            $msg = $m1." Do you still want to proceed ?";
        }
    }
    else if ($planType == "S")
    {
        if ((int) $consumedSessions >= (int) $maxSessions)
        {
            if ((int) $sessionCount > 1)
            {
                $mS = " Please choose another plan OR subscribe to a plan to schedule a meeting.";
            }
            else
            {
                $mS = " Please subscribe to a plan to schedule meeting.";
            }
            $stat = 3;
            $msg = "You have already consumed your prescribed Meeting Sessions.".$mS;
        }
    }
    else if ($planType == "T")
    {
        if ((int) $consumedTalktime >= (int) $maxTalktime)
        {
            if ((int) $sessionCount > 1)
            {
                $mS = " Please choose another plan OR subscribe to a plan to schedule a meeting.";
            }
            else
            {
                $mS = " Please subscribe to a plan to schedule a meeting.";
            }
            $stat = 3;
            $msg = "You have already consumed your prescribed Meeting Talk Time.".$mS;
        }
    }
    else if ((int) $inviteesCnt <= 0)
    {
        $stat = 0;
        $msg = "Please select Invitees";
    }
    else if (strlen($meeting_title) <= 0)
    {
        $stat = 0;
        $msg = "Please enter a Title";
    }
    else if ((int) $inviteesCnt > (int) $maxInviteesCount && (int) $maxInviteesCount != 0)
    {
        $stat = 0;
        $msg = "Your max limit for Invitees List is ".$maxInviteesCount."";
    }
    /* Validation For Data Received : End */
    
    /* Validating & Inserting the Data : Start */
    if (strlen($msg) <= 0)
    {
        try
        {
            //$schStatus = validateSchedule($strCK_user_id, $strCk_user_client_id, '1');
            $arrSchStatus = isAuthenticateScheduleUser($strCK_user_id, $strCk_user_client_id, $objDataHelper);
        }
        catch (Exception $e)
        {
            throw new Exception("createSchedule.php : validateSchedule Failed : ".$e->getMessage(), 1131);
        }

         if (is_array($arrSchStatus) && sizeof($arrSchStatus) > 0)
        {
            try
            {
                $voiceBridgeToken = voiceBridgeToken($objDataHelper);
            }
            catch (Exception $e)
            {
                throw new Exception("createSchedule.php : voiceBridgeToken Failed : ".$e->getMessage(), 1136);
            }

            try
            {
                $schID = getScheduleId($objDataHelper);
            }
            catch (Exception $e)
            {
                throw new Exception("createSchedule.php : getScheduleId Failed : ".$e->getMessage(), 1138);
            }

            if (strlen($schID) > 0)
            {
                try
                {
                    $meetingInstanceDtls = getLMInstanceByClientId($strCk_user_client_id, $objDataHelper);
                    //print_r($meetingInstanceDtls);
                }
                catch (Exception $e)
                {
                    throw new Exception("Error in getLMInstanceByClientId.".$a->getMessage(), 312);
                }
                $LMInstanceURL = $meetingInstanceDtls[0]["rt_server_name"];
                
                if (strlen($LMInstanceURL) > 0)
                {
                    $meetingInstance = $LMInstanceURL;
                    $meetingAttendeePWD = ATTENDEE_PWD;
                    $meetingModeratorPWD = MODERATOR_PWD;

                    try
                    {
                        $scheduleID = scheduleDetails($schID, $strCK_user_id, $gmTime, $localTime, $meeting_title, $timezone, $gmt, $meetingAttendeePWD, $meetingModeratorPWD, $voiceBridgeToken, $inviteesCnt, $meetingRecoding, $maxSessionsMinutes, $meetingInstance, $subscriptionId, $objDataHelper);
                    }
                    catch (Exception $e)
                    {
                        throw new Exception("createSchedule.php : scheduleDetails Failed : ".$e->getMessage(), 1132);
                    }

                    $type = "A";
                    try
                    {
                        $updSession = updConsumedSessions($subscriptionId, $strCK_user_id, $type, $objDataHelper);
                    }
                    catch (Exception $e)
                    {
                        throw new Exception("createSchedule.php : updConsumedSessions Failed : ".$e->getMessage(), 1137);
                    }
                    
                     if ($updSession[0]["@result"] == 1 && $planType == "S")
                    {   
                        try
                        {
                            $arrClSubDtls = getClSubInfoFromUserOrderId($userorderId, $objDataHelper);
                        }
                        catch (Exception $e)
                        {
                            throw new Exception("createSchedule.php : getClSubInfoFromUserOrderId Failed : ".$e->getMessage(), 1137);
                        }
                        
                        $strClSubId = $arrClSubDtls[0]['client_subscription_id'];
                        $strClientId = $arrClSubDtls[0]['client_id'];

                        $type = "A";
                        try
                        {
                            $updSession = updClientConsumedSessions($strClSubId, $strClientId, $type, $objDataHelper);
                        }
                        catch (Exception $e)
                        {
                            throw new Exception("createSchedule.php : getClSubInfoFromUserOrderId Failed : ".$e->getMessage(), 1137);
                        }
                     }
        
                    if ($updSession[0]["@result"] == 0 && $planType == "S")
                    {                        
                        $stat = 0;
                        $msg = "You have already consumed your prescribed Meeting Sessions.";
                    }
                    else
                    {
                        try
                        {
                            $arrUserDetailsById = getUserDetailsByID($strCK_user_id, $objDataHelper);
                        }
                        catch (Exception $e)
                        {
                            throw new Exception("index.php : getUserDetailsByID Failed : " . $e->getMessage(), 1129);
                        }

                        $strUserDetails = $arrUserDetailsById[0]["nick_name"].":".$arrUserDetailsById[0]["idd_code"].":".$arrUserDetailsById[0]["mobile_number"];

                        try
                        {
                           $invitees = inviteesDetails($scheduleID, $strCk_user_email_address, $strUserDetails, $arrInviteesEmail, $moderator, $objDataHelper);
                        }
                        catch (Exception $e)
                        {
                            throw new Exception("createSchedule.php : inviteesDetails Failed : ".$e->getMessage(), 1133);
                        }

                        $inviteesEmail = explode(",", $arrInviteesEmail);
                        for ($i = 0; $i < sizeof($inviteesEmail); $i++)
                        {
                            $inviteesDetails[] = explode(":", $inviteesEmail[$i]);
                        }
                        for ($i = 0; $i < sizeof($inviteesDetails); $i++)
                        {
                            $inviteesEmailArr .= $inviteesDetails[$i][0].",";
                        }
                        $inviteesEmailArr = substr($inviteesEmailArr, 0, -1);

                        if ($invitees)
                        {
//                            try
//                            {
//                                $scheduleMail = scheduleMail($strCk_user_email_address, $strCk_user_nick_name, $inviteesEmailArr, $scheduleID, $gmTime, $localTime, $timezone, $meeting_title, $scheduleType);
//                            }
//                            catch (Exception $e)
//                            {
//                                throw new Exception("createSchedule.php : scheduleMail Failed : ".$e->getMessage(), 1134);
//                            }
                        }

                        $meeting_dtm = dateFormat($gmTime, $localTime, $timezone);
                        
                        try
                        {
                            $arrInviteesList = getMeetingInviteeList($scheduleID, $objDataHelper);
                        }
                        catch (Exception $a)
                        {
                            throw new Exception("Error in getMeetingInviteeList.".$a->getMessage(), 4103);
                        }
                         
                        if (is_array($arrInviteesList) && sizeof($arrInviteesList) > 0)
                        {
                            
                            $invlist_msg ="<hr><div class=\"space\"></div>
                                  <div>
                                      <table class=\"table table-striped table-bordered\">
                                          <thead>
                                                  <tr><th class=\"center\">*</th><th>Name</th><th class=\"\">Email Address</th><th class=\"hidden-480\">Role</th></tr>
                                          </thead>
                                          <tbody>";
                                              for ($intCntr = 0; $intCntr < sizeof($arrInviteesList); $intCntr++)
                                              {
                                                    switch ($arrInviteesList[$intCntr]['invitation_creator'])
                                                    {
                                                        case "C" :
                                                            $strInvRoll = "<i class=\"ace-icon fa fa-eye\"></i> Host (Moderator)";
                                                            break;
                                                        case "M" :
                                                            $strInvRoll = "<i class=\"ace-icon fa fa-eye\"></i> Moderator";
                                                            break;
                                                        default:
                                                            $strInvRoll = "<i class=\"ace-icon fa fa-user\"></i> Invitee";
                                                    }
                                                   $invlist_msg .="<tr>
                                                          <td class=\"center\"> <i class=\"ace-icon fa fa-user\"></i> </td>
                                                          <td> ".$arrInviteesList[$intCntr]['invitee_nick_name']." </td>
                                                          <td class=\"\"> <i class=\"ace-icon fa fa-envelope-square\"></i> ".$arrInviteesList[$intCntr]['invitee_email_address']." </td>
                                                          <td class=\"hidden-480\"> ".$strInvRoll." </td>
                                                  </tr>";
                                              }
                                           $invlist_msg .="</tbody>
                                      </table>
                                  </div>";
                        }

                        if ($scheduleType == "N")
                        {
                            $stat = 1;
                            $msg = "<div class=\"well\">
                                        <h1 class=\"green lighter smaller\">
                                                <span class=\"green bigger-125\">
                                                        <i class=\"ace-icon fa fa-users \"></i>
                                                        Meeting Scheduled Successfully !
                                                </span>
                                        </h1>
                                        <hr>
                                        <h3 class=\"lighter smaller\">
                                                ".$meeting_title."
                                        </h3>
                                        <div class=\"space\"></div>
                                        <div>
                                                <h4 class=\"lighter smaller\"><i class=\"ace-icon fa fa-calendar  blue\"></i> ".$meeting_dtm." </h4>
                                        </div>
                                        <hr>
                                        <div class=\"space\"></div>
                                        <div class=\"center\"><form method='post'>
                                                <a class=\"btn btn-success\" href=\"start.php?startId=".$scheduleID."\" target=\"_blank\" onclick=\"f_click(this);\">
                                                        <i class=\"ace-icon fa fa-check\"></i>
                                                        Start Meeting
                                                </a>
                                                &nbsp; &nbsp; &nbsp;
                                                <a class=\"btn btn-grey\" href=\"".$SITE_ROOT."schedule/\">
                                                        <i class=\"ace-icon fa fa-arrow-left\"></i>
                                                        Go Back
                                                </a><input type='hidden' name='sKey' id='sKey' value='".$scheduleID."'></form>
                                        </div>
                                        ".$invlist_msg."
                                        
                                </div>";
                        }
                        else
                        {
                            $stat = 1;
                            $msg = "<div class=\"well\">
                                        <h1 class=\"green lighter smaller\">
                                                <span class=\"green bigger-125\">
                                                        <i class=\"ace-icon fa fa-users \"></i>
                                                        Meeting Scheduled Successfully !
                                                </span>
                                        </h1>
                                        <hr>
                                        <h3 class=\"lighter smaller\">
                                                ".$meeting_title."
                                        </h3>
                                        <div class=\"space\"></div>
                                        <div>
                                                <h4 class=\"lighter smaller\"><i class=\"ace-icon fa fa-calendar  blue\"></i> ".$meeting_dtm." </h4>
                                        </div>
                                        <hr>
                                        <div class=\"space\"></div>
                                        <div class=\"center\">
                                                <a class=\"btn btn-grey\" href=\"".$SITE_ROOT."schedule/\">
                                                        <i class=\"ace-icon fa fa-arrow-left\"></i>
                                                        Go Back
                                                </a>
                                        </div>
                                        ".$invlist_msg."
                                </div>";
                        }
                    }
                }
                else
                {
                    $stat = 0;
                    $msg = "Please try again,Server not found.";   /*                     * * Meeting Instance Failed * * */
                }
            }
            else
            {
                $stat = 0;
                $msg = "Please try again.";    /*                 * * Schedule ID Generation Failed * * */
            }
        }
        else
        {
            $stat = 0;
            $msg = "You can't Schedule Meeting";     /*             * * User Validation Failed * * */
        }
    }
    /* Validating & Inserting the Data : End */
}
catch (Exception $e)
{
    throw new Exception("createSchedule.php : Failed : ".$e->getMessage(), 1135);
}
$finalStat = $stat.SEPARATOR.$msg;
echo $finalStat;