<?php

require_once('../includes/global.inc.php');
require_once(CLASSES_PATH."error.inc.php");
require_once(INCLUDES_PATH."Utilities.php");
require_once(DBS_PATH."DataHelper.php");
require_once(DBS_PATH."objDataHelper.php");
require_once(INCLUDES_PATH."db_common_function.inc.php");
require_once(INCLUDES_PATH."cm_authfunc.inc.php");
$CONST_MODULE = 'schedule';
$CONST_PAGEID = 'Schedule';
require_once(INCLUDES_PATH."cm_authorize.inc.php");
require_once(INCLUDES_PATH."sch_function.inc.php");
require_once(INCLUDES_PATH."mail_common_function.inc.php");

try
{
    $meeting_timestamp = $_REQUEST['schedule_dtm'];
    $inviteesCnt = $_REQUEST['inviteesCnt'];
    $arrInviteesEmail = $_REQUEST['inviteesList'];
    $meeting_title = $_REQUEST['title'];
    $scheduleType = $_REQUEST['scheduleType'];
    $tZone = $_REQUEST['tzone'];
    $moderator = $_REQUEST['mod'];
    $uPlan = $_REQUEST['uplan'];
    $schStat = $_REQUEST['stat'];

    $userTimeZone = explode("$:$", $tZone);
    $userPlan = explode("$:$", $uPlan);
    $timezone = $userTimeZone[1];
    $gmt = $userTimeZone[2];

    /* Meeting Date Time : Start */
    if ($scheduleType == "N")
    {
        $meeting_timestamp = GM_DATE;
    }
    else if ($scheduleType == "S")
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
        echo $sessionCount = 0;
    }
    $activeMCount = array_count_values(explode(",", $currentMStatus));
    $activeMCount = $activeMCount[1];
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
        else if ($scheduleType == "S")
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
            $schStatus = validateSchedule($strCK_user_id, $strCk_client_id, '1');
        }
        catch (Exception $e)
        {
            throw new Exception("createSchedule.php : validateSchedule Failed : ".$e->getMessage(), 1131);
        }

        $schedule1 = explode(SEPARATOR, $schStatus);
        $schedule = $schedule1[0];

        if ($schedule == 0)
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
                //Commented by Mitesh Shah 29-12-2014
                /*try
                {
                    
                    $StartTime = strtotime($gmTime);
                    $EndTime = $StartTime + MEETING_END_TIME;
                    $meetingInstance = getRoundtableInstance($inviteesCnt, $schID, $StartTime, $EndTime);
                }
                catch (Exception $e)
                {
                    throw new Exception("createSchedule.php : getRoundtableInstance Failed : ".$e->getMessage(), 1138);
                }*/
                //Commented by Mitesh Shah 29-12-2014
                
                //Added by Mitesh Shah 29-12-2014
                try
                {
                    
                    $meetingInstanceDtls = getLMInstanceByClientId($strCk_client_id, $objDataHelper);
                    //print_r($meetingInstanceDtls);
                }
                catch (Exception $e)
                {
                    throw new Exception("Error in getLMInstanceByClientId.".$a->getMessage(), 312);
                }
                $LMInstanceURL = $meetingInstanceDtls[0]["rt_server_name"];
                //Added by Mitesh Shah 29-12-2014

                //Commented by Mitesh Shah 29-12-2014
                //if (strlen($meetingInstance) > 0)
                if (strlen($LMInstanceURL) > 0) //Added by Mitesh Shah 29-12-2014
                {
                    //Commented by Mitesh Shah 29-12-2014
                    //$meetingInstance = "http://".$meetingInstance;
                    
                    //Added by Mitesh Shah 29-12-2014
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
                            $userDetails = userDetailsByUserId($strCK_user_id, $objDataHelper);
                        }
                        catch (Exception $e)
                        {
                            throw new Exception("createSchedule.php : userDetailsByUserId Failed : ".$e->getMessage(), 1137);
                        }

                        $strUserDetails = $userDetails[0]["nick_name"].":".$userDetails[0]["idd_code"].":".$userDetails[0]["mobile_number"];

                        try
                        {
                            $invitees = inviteesDetails($scheduleID, $strCk_email_address, $strUserDetails, $arrInviteesEmail, $moderator, $objDataHelper);
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
                            try
                            {
                                $scheduleMail = scheduleMail($strCk_email_address, $strCk_nick_name, $inviteesEmailArr, $scheduleID, $gmTime, $localTime, $timezone, $meeting_title, $scheduleType);
                            }
                            catch (Exception $e)
                            {
                                throw new Exception("createSchedule.php : scheduleMail Failed : ".$e->getMessage(), 1134);
                            }
                        }

                        $meeting_dtm = dateFormat($gmTime, $localTime, $timezone);

                        if ($scheduleType == "N")
                        {
                            $msg = "<div class='alert alert-success mT10'>Meeting Scheduled Successfully !</div><b>Meeting Title</b> : $meeting_title<br /><br /><b>Meeting Date</b> : ".$meeting_dtm."<br /><br /><form method='post'>Click here to <a href='start.php?startId=".$scheduleID."' target='_blank'><input type='button' class='btn-success cWh' value='Start Meeting' id='sSchedule' onclick='changeStyle();'></a><input type='hidden' name='sKey' id='sKey' value='".$scheduleID."'></form>Click here to <a href='".$SITE_ROOT."schedule/'><input type='button' class='btn-primary cWh' value='Go Back' id='mSchedule'></a>";
                            $stat = 1;
                        }
                        else
                        {
                            $stat = 1;
                            $msg = "<div class='alert alert-success mT10'>Meeting Scheduled Successfully !</div><b>Meeting Title</b> : $meeting_title<br /><br /><b>Meeting Date</b> : ".$meeting_dtm."<br /><br />Click here to <a href='".$SITE_ROOT."schedule/'><input type='button' class='btn-primary cWh' value='Go Back' id='mSchedule'></a>";
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
            $msg = $schedule1[1];     /*             * * User Validation Failed * * */
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