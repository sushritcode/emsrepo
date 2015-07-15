<?php

/* -----------------------------------------------------------------------------
  Function Name : validateSchedule
  Purpose       :
  Parameters    :
  Returns       : status & msg
  Calls         :  api/authschedule.php
  Called By     : createSchedule.php
  ------------------------------------------------------------------------------ */

function validateSchedule($strCK_user_id, $strCk_client_id, $PRID)
{
    $url = INT_API_ROOT."api/authschedule.php?USID=".$strCK_user_id."&CLID=".$strCk_client_id."&PRID=".$PRID;
    $curlurl = curl_init($url);
    curl_setopt($curlurl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curlurl, CURLOPT_HEADER, false);
    curl_setopt($curlurl, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($curlurl, CURLOPT_SSL_VERIFYPEER, false);
    $strReturnValue = curl_exec($curlurl);
    curl_close($curlurl);
    return $strReturnValue;
}

/* -----------------------------------------------------------------------------
  Function Name : getScheduleId
  Purpose       :
  Parameters    :
  Returns       : meeting schedule id
  Calls         :  datahelper.fetchRecords
  Called By     : createSchedule.php
  ------------------------------------------------------------------------------ */

function getScheduleId($dataHelper)
{
    if (!is_object($dataHelper))
    {
        throw new Exception("sch_function.inc.php : getScheduleId : DataHelper Object did not instantiate", 104);
    }
    try
    {
//        $strSqlStatement = "SELECT MAX(schedule_id) FROM schedule_details";
//        $arrMaxId = $dataHelper->fetchRecords("QR", $strSqlStatement);
//        $s1 = $arrMaxId[0]['MAX(schedule_id)'];
//        $s2 = explode("scl", $s1);
//        $s3 = $s2[1] + 1;
//        $s4 = strlen($s3);
//        switch ($s4)
//        {
//            case 1: $schId = "scl000000".$s3;
//                break;
//            case 2: $schId = "scl00000".$s3;
//                break;
//            case 3: $schId = "scl0000".$s3;
//                break;
//            case 4: $schId = "scl000".$s3;
//                break;
//            case 5: $schId = "scl00".$s3;
//                break;
//            case 6: $schId = "scl0".$s3;
//                break;
//            case 7: $schId = "scl".$s3;
//                break;
//            default: break;
//        }
          //$schId = uniqid("letsmeet",TRUE);
          $schId = uniqid('',FALSE);
    }
    catch (Exception $e)
    {
        throw new Exception("sch_function.inc.php : Insert Schedule Details Failed : ".$e->getMessage(), 1111);
    }
    return $schId;
}

/* -----------------------------------------------------------------------------
  Function Name : scheduleDetails
  Purpose       :
  Parameters    :
  Returns       : status & msg
  Calls         :  api/authschedule.php
  Called By     : createSchedule.php
  ------------------------------------------------------------------------------ */

function scheduleDetails($schID, $strCK_user_id, $gmTime, $localTime, $meeting_title, $timezone, $gmt, $meetingAttendeePWD, $meetingModeratorPWD, $voiceBridgeToken, $inviteesCnt, $meetingRecoding, $maxSessionsMinutes, $meetingInstance, $subscriptionId, $dataHelper)
{
    if (!is_object($dataHelper))
    {
        throw new Exception("sch_function.inc.php : scheduleMeeting : DataHelper Object did not instantiate", 104);
    }
    try
    {
        $insSqlStatement = "INSERT INTO schedule_details (schedule_id , user_id , schedule_creation_time , meeting_timestamp_gmt , meeting_timestamp_local , meeting_title , meeting_agenda , meeting_timezone , meeting_gmt , attendee_password , moderator_password , welcome_message , voice_bridge , web_voice , max_participants , record_flag , meeting_duration, meta_tags, meeting_instance, subscription_id) VALUES ('".trim($schID)."' , '".trim($strCK_user_id)."' , '".trim(GM_DATE)."' , '".trim($gmTime)."' , '".trim($localTime)."' , '".trim($meeting_title)."' , 'NULL' , '".trim($timezone)."', '".trim($gmt)."', '".trim($meetingAttendeePWD)."' , '".trim($meetingModeratorPWD)."' , 'NULL' , '".trim($voiceBridgeToken)."' , '".trim($voiceBridgeToken)."' , '".trim($inviteesCnt)."' , '".trim($meetingRecoding)."' , '".trim($maxSessionsMinutes)."', 'NULL', '".trim($meetingInstance)."', '".trim($subscriptionId)."')";
        $arrSchedule = $dataHelper->putRecords("QR", $insSqlStatement);
        return $schID;
    }
    catch (Exception $e)
    {
        throw new Exception("sch_function.inc.php : Insert Schedule Details Failed : ".$e->getMessage(), 1101);
    }
}

/* -----------------------------------------------------------------------------
  Function Name : createMeeting
  Purpose       :
  Parameters    :
  Returns       :
  Calls         :  meeting/cmx.php
  Called By     : start.php
  ------------------------------------------------------------------------------ */

function createMeeting($scheduleID, $strCk_email_address)
{
    $PSCD = md5($scheduleID.":".$strCk_email_address.":".SECRET_KEY);
    $jmData = "SCID=".$scheduleID."&EMID=".urlencode($strCk_email_address)."&PSCD=".$PSCD."&PRID=".PRID;
    $jmUrl = INT_API_ROOT."join/jmx.php?".$jmData;
    $curlurl = curl_init($jmUrl);
    curl_setopt($curlurl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curlurl, CURLOPT_HEADER, false);
    curl_setopt($curlurl, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($curlurl, CURLOPT_SSL_VERIFYPEER, false);
    $strReturnValue = curl_exec($curlurl);
    curl_close($curlurl);
    return $strReturnValue;
}

/* -----------------------------------------------------------------------------
  Function Name : inviteesDetails
  Purpose       :
  Parameters    :
  Returns       :
  Calls         :  datahelper.putRecords
  Called By     : addInvitee.php
  ------------------------------------------------------------------------------ */

function inviteesDetails($scheduleID, $strCk_email_address, $strUserDetails, $arrInviteesEmail, $moderator, $dataHelper)
{
    if (!is_object($dataHelper))
    {
        throw new Exception("sch_function.inc.php : inviteesDetails : DataHelper Object did not instantiate", 104);
    }
    try
    {
        $userDetails = explode(":", $strUserDetails);
        if (strlen($strCk_email_address) > 0)
        {
            $insSchedulerStatement = "INSERT INTO invitation_details
            (schedule_id, invitee_email_address, invitation_creator, invitee_nick_name, invitee_idd_code, invitee_mobile_number, invitation_status, invitation_creation_dtm, meeting_status)
            VALUES ('".$scheduleID."' , '".$strCk_email_address."' , 'C' , '".$userDetails[0]."' , '".$userDetails[1]."' , '".$userDetails[2]."' , '0' , '".GM_DATE."' , '0')";
            $scheduler = $dataHelper->putRecords("QR", $insSchedulerStatement);
        }
        $inviteesEmail = explode(",", $arrInviteesEmail);
        for ($i = 0; $i < sizeof($inviteesEmail); $i++)
        {
            $invitees[] = explode(":", $inviteesEmail[$i]);
        }

        for ($i = 0; $i < sizeof($invitees); $i++)
        {
            if ((strlen($moderator) > 0) && ($moderator == $invitees[$i][0]))
            {
                $insSqlStatement = "INSERT INTO invitation_details
               (schedule_id, invitee_email_address, invitation_creator, invitee_nick_name, invitee_idd_code, invitee_mobile_number, invitation_status, invitation_creation_dtm, meeting_status)
               VALUES ('".$scheduleID."' , '".$invitees[$i][0]."' , 'M', '".$invitees[$i][1]."' , '".$invitees[$i][2]."' , '".$invitees[$i][3]."' , '0' , '".GM_DATE."' , '0')";
            }
            else
            {
                $insSqlStatement = "INSERT INTO invitation_details
               (schedule_id, invitee_email_address, invitee_nick_name, invitee_idd_code, invitee_mobile_number, invitation_status, invitation_creation_dtm, meeting_status)
               VALUES ('".$scheduleID."' , '".$invitees[$i][0]."' , '".$invitees[$i][1]."' , '".$invitees[$i][2]."' , '".$invitees[$i][3]."' , '0' , '".GM_DATE."' , '0')";
            }
            $inviteesStat = $dataHelper->putRecords("QR", $insSqlStatement);
        }
        return true;
    }
    catch (Exception $e)
    {
        throw new Exception("sch_function.inc.php : Insert Invitees Details Failed : ".$e->getMessage(), 1102);
    }
}

/* -----------------------------------------------------------------------------
  Function Name : getScheduleMeeting
  Purpose       :
  Parameters    :
  Returns       :
  Calls         :  datahelper.putRecords
  Called By     : addInvitee.php
  ------------------------------------------------------------------------------ */

function getScheduleMeeting($strCK_user_id, $scheduleId, $dataHelper)
{
    if (!is_object($dataHelper))
    {
        throw new Exception("sch_function.inc.php : getScheduleMeeting : DataHelper Object did not instantiate", 104);
    }
    try
    {
        $strSqlStatement = "SELECT
         schedule_id,
         schedule_status,
         meeting_timestamp_gmt,
         meeting_timestamp_local,
         meeting_title,
         meeting_timezone
         FROM schedule_details
         WHERE user_id = '".$strCK_user_id."'
            AND schedule_id = '".$scheduleId."'";
        $arrResult = $dataHelper->fetchRecords("QR", $strSqlStatement);
        return $arrResult;
    }
    catch (Exception $e)
    {
        throw new Exception("sch_function.inc.php : Fetch Schedule Meeting Details Failed : ".$e->getMessage(), 1103);
    }
}

/* -----------------------------------------------------------------------------
  Function Name : setScheduleCounter
  Purpose       :
  Parameters    :
  Returns       :
  Calls         :  datahelper.putRecords
  Called By     : addInvitee.php
  ------------------------------------------------------------------------------ */

function setScheduleCounter($scheduleId, $dataHelper)
{
    if (!is_object($dataHelper))
    {
        throw new Exception("sch_function.inc.php : setScheduleCounter : DataHelper Object did not instantiate", 104);
    }
    try
    {
        $strSqlStatement = "SELECT max_participants FROM schedule_details WHERE schedule_id = '".$scheduleId."'";
        $arrResult = $dataHelper->fetchRecords("QR", $strSqlStatement);
        $maxParticipants = $arrResult[0]['max_participants'];
        $maxP = $maxParticipants + 1;
        $updStatement = "UPDATE schedule_details SET max_participants = '".$maxP."' WHERE schedule_id = '".$scheduleId."'";
        $updResponse = $dataHelper->putRecords("QR", $updStatement);
    }
    catch (Exception $e)
    {
        throw new Exception("sch_function.inc.php : Update Schedule Participants Counter Failed : ".$e->getMessage(), 1104);
    }
}

/* -----------------------------------------------------------------------------
  Function Name : getMeetingListRHS
  Purpose       :
  Parameters    :
  Returns       :
  Calls         :  datahelper.fetchRecords
  Called By     : schedule/index.php
  ------------------------------------------------------------------------------ */

function getMeetingListRHS($strCK_user_id, $startGrace, $dataHelper)
{
    if (!is_object($dataHelper))
    {
        throw new Exception("sch_function.inc.php : getMeetingListRHS : DataHelper Object did not instantiate", 104);
    }
    try
    {
        $now = strtotime(GM_DATE);
        $bTime = date("Y-m-d H:i:s", strtotime("-$startGrace minutes", $now));

        $strSqlStatement = "SELECT
         schedule_id,
         schedule_status,
         meeting_timestamp_gmt,
         meeting_timestamp_local,
         meeting_title,
         meeting_timezone,
         max_participants
         FROM schedule_details
         WHERE user_id = '".$strCK_user_id."'
            AND schedule_status IN ('0','1')
            AND meeting_timestamp_gmt >= '".$bTime."'
               ORDER BY meeting_timestamp_gmt";
        $arrResult = $dataHelper->fetchRecords("QR", $strSqlStatement);
        return $arrResult;
    }
    catch (Exception $e)
    {
        throw new Exception("sch_function.inc.php : Fetch Schedule Meeting List Failed : ".$e->getMessage(), 1105);
    }
}

/* -----------------------------------------------------------------------------
  Function Name : autoSuggest
  Purpose       :
  Parameters    :
  Returns       :
  Calls         :  datahelper.fetchRecords
  Called By     : autoSuggest.php
  ------------------------------------------------------------------------------ */

function autoSuggest($clID, $strCK_user_id, $word, $dataHelper)
{
    if (!is_object($dataHelper))
    {
        throw new Exception("sch_function.inc.php : autoSuggest : DataHelper Object did not instantiate", 104);
    }
    try
    {
        $strSqlStatement = "SELECT
            client_contact_details.client_contact_id,
            client_contact_details.contact_nick_name AS nick_name,
            client_contact_details.contact_email_address,
            client_contact_details.contact_idd_code,
            client_contact_details.contact_mobile_number,
            client_contact_details.contact_group_name
         FROM client_contact_details, user_details
         WHERE
            client_contact_details.client_id = user_details.client_id
            AND client_contact_details.client_id = '".$clID."'
            AND user_details.user_id = '".$strCK_user_id."'
            AND client_contact_details.client_contact_status = '1'
            AND client_contact_details.contact_nick_name LIKE '".trim($word)."%'
         UNION
         SELECT
            personal_contact_details.personal_contact_id,
            personal_contact_details.contact_nick_name AS nick_name,
            personal_contact_details.contact_email_address,
            personal_contact_details.contact_idd_code,
            personal_contact_details.contact_mobile_number,
            personal_contact_details.contact_group_name
         FROM personal_contact_details, user_details
         WHERE
            personal_contact_details.user_id = user_details.user_id
            AND personal_contact_details.user_id = '".$strCK_user_id."'
            AND user_details.client_id = '".$clID."'
            AND personal_contact_details.personal_contact_status = '1'
            AND personal_contact_details.contact_nick_name LIKE '".trim($word)."%'
         ORDER BY nick_name";

        $arrList = $dataHelper->fetchRecords("QR", $strSqlStatement);
        return $arrList;
    }
    catch (Exception $e)
    {
        throw new Exception("sch_function.inc.php : Fetch Auto Suggest Failed : ".$e->getMessage(), 1106);
    }
}

/* -----------------------------------------------------------------------------
  Function Name : getTimezoneList
  Purpose       :
  Parameters    :
  Returns       :
  Calls         :  datahelper.fetchRecords
  Called By     : index.php
  ------------------------------------------------------------------------------ */

function getTimezoneList($dataHelper)
{
    if (!is_object($dataHelper))
    {
        throw new Exception("sch_function.inc.php : getTimezoneList : DataHelper Object did not instantiate", 104);
    }
    try
    {
        $strSqlStatement = "SELECT
         cd.country_name,
         cd.country_code,
         ct.timezones,
         ct.gmt
         FROM country_details AS cd, country_timezones AS ct
         WHERE cd.country_code = ct.country_code
            AND country_status = '1'";
        $arrList = $dataHelper->fetchRecords("QR", $strSqlStatement);
        return $arrList;
    }
    catch (Exception $e)
    {
        throw new Exception("sch_function.inc.php : Fetch Time zone Failed : ".$e->getMessage(), 1107);
    }
}

/* -----------------------------------------------------------------------------
  Function Name : getUserDetails
  Purpose       :
  Parameters    :
  Returns       :
  Calls         :  datahelper.fetchRecords
  Called By     : index.php
  ------------------------------------------------------------------------------ */

function getUserTimezone($strCK_user_id, $strCk_email_address, $strCk_client_id, $dataHelper)
{
    if (!is_object($dataHelper))
    {
        throw new Exception("sch_function.inc.php : getUserTimezone : DataHelper Object did not instantiate", 104);
    }
    try
    {
        $strSqlStatement = "SELECT
         country_name,
         timezones,
         gmt
         FROM user_details
         WHERE user_id = '".$strCK_user_id."'
            AND client_id = '".$strCk_client_id."'
            AND email_address = '".$strCk_email_address."'
            AND status = '1' ";
        $arrList = $dataHelper->fetchRecords("QR", $strSqlStatement);
        return $arrList;
    }
    catch (Exception $e)
    {
        throw new Exception("sch_function.inc.php : Fetch User Time zone Failed : ".$e->getMessage(), 1108);
    }
}

/* -----------------------------------------------------------------------------
  Function Name : voiceBridgeToken
  Purpose       :
  Parameters    :
  Returns       :
  Calls         :
  Called By     : createSchedule.php
  ------------------------------------------------------------------------------ */

function voiceBridgeToken($dataHelper)
{
    $length = VOICE_BRIDGE_LENGTH;
    $random = "";
    srand((double) microtime() * 1000000);
    $char_list .= "0123456789";
    for ($i = 0; $i < $length - 1; $i++)
    {
        $random .= substr($char_list, (rand() % (strlen($char_list))), 1);
    }
    $voiceBridgeToken = VOICE_BRIDGE_PREFIX.$random;
    try
    {
        $voiceBridgeStatus = isVoiceBridgeValidate($voiceBridgeToken, $dataHelper);
    }
    catch (Exception $e)
    {
        throw new Exception("sch_function.inc.php : Fetch Voice Failed : ".$e->getMessage(), 1109);
    }
    if ($voiceBridgeStatus == "1")
    {
        return $voiceBridgeToken;
    }
    else
    {
        voiceBridgeToken();
    }
}

/* -----------------------------------------------------------------------------
  Function Name : isVoiceBridgeValidate
  Purpose       :
  Parameters    :
  Returns       :
  Calls         :  datahelper.fetchRecords
  Called By     : createSchedule.php
  ------------------------------------------------------------------------------ */

function isVoiceBridgeValidate($voiceBridgeToken, $dataHelper)
{
    if (!is_object($dataHelper))
    {
        throw new Exception("sch_function.inc.php : isVoiceBridgeValidate : DataHelper Object did not instantiate", 104);
    }
    try
    {
        $strSqlStatement = "SELECT voice_bridge FROM schedule_details WHERE voice_bridge = '".$voiceBridgeToken."'";
        $arrList = $dataHelper->fetchRecords("QR", $strSqlStatement);
        if (!empty($arrList))
        {
            $stat = "0";
        }
        else
        {
            $stat = "1";
        }
        return $stat;
    }
    catch (Exception $e)
    {
        throw new Exception("sch_function.inc.php : Fetch Voice Bridge Failed : ".$e->getMessage(), 1110);
    }
}

/* -----------------------------------------------------------------------------
  Function Name : getMyMeetingList
  Purpose       :
  Parameters    :
  Returns       :
  Calls         :  datahelper.fetchRecords
  Called By     : meeting/index.php
  ------------------------------------------------------------------------------ */

function getMyMeetingList($strCk_email_address, $dataHelper)
{
    if (!is_object($dataHelper))
    {
        throw new Exception("sch_function.inc.php : getMyMeetingList : DataHelper Object did not instantiate", 104);
    }
    try
    {
        $strSqlStatement = "SELECT
         sd.schedule_id,
         sd.user_id,
         sd.schedule_status,
         sd.meeting_timestamp_gmt,
         sd.meeting_timestamp_local,
         sd.meeting_title,
         sd.meeting_timezone,
         sd.max_participants,
         id.invitation_creator
         FROM schedule_details AS sd, invitation_details AS id
         WHERE sd.schedule_id = id.schedule_id
            AND id.invitee_email_address = '".$strCk_email_address."'
            AND schedule_status IN ('0','1')
         ORDER BY meeting_timestamp_gmt ASC";
        $arrResult = $dataHelper->fetchRecords("QR", $strSqlStatement);
        return $arrResult;
    }
    catch (Exception $e)
    {
        throw new Exception("sch_function.inc.php : Fetch Schedule Meeting List Failed : ".$e->getMessage(), 1105);
    }
}

/* -----------------------------------------------------------------------------
  Function Name : getMyMeetingList
  Purpose       :
  Parameters    :
  Returns       :
  Calls         : datahelper.fetchRecords
  Called By     : meeting/index.php
  ------------------------------------------------------------------------------ */

function getMyArchiveMeetings($strCk_email_address, $dataHelper)
{
    if (!is_object($dataHelper))
    {
        throw new Exception("sch_function.inc.php : getMyArchiveMeetings : DataHelper Object did not instantiate", 104);
    }
    try
    {
        $datetime = date("Y-m-d H:i:s", STRTOTIME(GM_DATE."-".MEETING_LIST_GRACE_INTERVAL." min"));
        $strSqlStatement = "SELECT
         sd.schedule_id,
         sd.user_id,
         sd.schedule_status,
         sd.meeting_timestamp_gmt,
         sd.meeting_timestamp_local,
         sd.meeting_title,
         sd.meeting_timezone,
         sd.max_participants,
         id.invitation_creator
         FROM schedule_details AS sd, invitation_details AS id
         WHERE sd.schedule_id = id.schedule_id
            AND id.invitee_email_address = '".$strCk_email_address."'
            AND ( (meeting_timestamp_gmt < '".GM_DATE."'
            AND schedule_status NOT IN ('0','1') )
            OR schedule_status IN ('2','3','4','5') )
         ORDER BY meeting_timestamp_gmt DESC";
        $arrResult = $dataHelper->fetchRecords("QR", $strSqlStatement);
        return $arrResult;
    }
    catch (Exception $e)
    {
        throw new Exception("sch_function.inc.php : Fetch Archive Meeting List Failed : ".$e->getMessage(), 1105);
    }
}

/* -----------------------------------------------------------------------------
  Function Name : getMyMeetingList
  Purpose       :
  Parameters    :
  Returns       :
  Calls         :  datahelper.fetchRecords
  Called By     : meeting/index.php
  ------------------------------------------------------------------------------ */

function moderatorDetails($scheduleID, $dataHelper)
{
    if (!is_object($dataHelper))
    {
        throw new Exception("sch_function.inc.php : moderatorDetails : DataHelper Object did not instantiate", 104);
    }
    try
    {
        $strSqlStatement = "SELECT
         invitee_email_address,
         invitee_nick_name
         FROM invitation_details
         WHERE schedule_id = '".$scheduleID."'
            AND invitation_creator = 'C'";
        $arrResult = $dataHelper->fetchRecords("QR", $strSqlStatement);
        return $arrResult;
    }
    catch (Exception $e)
    {
        throw new Exception("sch_function.inc.php : Fetch Moderator Details Failed : ".$e->getMessage(), 1105);
    }
}

/* -----------------------------------------------------------------------------
  Function Name : isPlanExists
  Purpose       :
  Parameters    :
  Returns       :
  Calls         : datahelper.fetchRecords
  Called By     : schedule/index.php
  ------------------------------------------------------------------------------ */

function isPlanExists($strCK_user_id, $dataHelper)
{
    if (!is_object($dataHelper))
    {
        throw new Exception("sch_function.inc.php : isPlanExists : DataHelper Object did not instantiate", 104);
    }
    try
    {
        $gDtm = date("Y-m-d", strtotime(GM_DATE));
        $sqlStatement = "SELECT MAX( subscription_end_date_gmt ) AS eGMT
                          FROM subscription_master
                          WHERE user_id =  '".$strCK_user_id."'
                          AND subscription_end_date_gmt >=  '".$gDtm."'
                          AND ( (plan_type =  'S' AND consumed_number_of_sessions < number_of_sessions)
                             OR (plan_type =  'T' AND consumed_talk_time_mins < talk_time_mins)
                             OR (plan_type =  'U'))";
        $arrResult = $dataHelper->fetchRecords("QR", $sqlStatement);
        return $arrResult;
    }
    catch (Exception $e)
    {
        throw new Exception("sch_function.inc.php : Fetch Max Date Schedule Plan Failed : ".$e->getMessage(), 1105);
    }
}

/* -----------------------------------------------------------------------------
  Function Name : scheduledPlans
  Purpose       :
  Parameters    :
  Returns       :
  Calls         : datahelper.fetchRecords
  Called By     : schedule/index.php
  ------------------------------------------------------------------------------ */

function scheduledPlans($strCK_user_id, $dataHelper)
{
    if (!is_object($dataHelper))
    {
        throw new Exception("sch_function.inc.php : scheduledPlans : DataHelper Object did not instantiate", 104);
    }
    try
    {
        $gDtm = date("Y-m-d", strtotime(GM_DATE));
        $strSqlStatement = "SELECT subscription_id, user_id, subscription_end_date_gmt, plan_id, plan_name, plan_type, number_of_sessions, plan_period, number_of_invitee, concurrent_sessions, talk_time_mins, consumed_number_of_sessions, consumed_talk_time_mins FROM subscription_master WHERE user_id = '".$strCK_user_id."' AND subscription_end_date_gmt >= '".$gDtm."' AND ( (plan_type = 'S' AND consumed_number_of_sessions < number_of_sessions) OR (plan_type = 'T' AND consumed_talk_time_mins < talk_time_mins) OR (plan_type = 'U') ) ORDER BY subscription_end_date_gmt DESC";
        $arrResult = $dataHelper->fetchRecords("QR", $strSqlStatement);
        return $arrResult;
    }
    catch (Exception $e)
    {
        throw new Exception("sch_function.inc.php : Fetch Schedule Plan Details Failed : ".$e->getMessage(), 1105);
    }
}

/* -----------------------------------------------------------------------------
  Function Name : validatePlan
  Purpose       :
  Parameters    :
  Returns       :
  Calls         : datahelper.fetchRecords
  Called By     : schedule/createSchedule.php
  ------------------------------------------------------------------------------ */

function validatePlan($subId, $strCK_user_id, $dataHelper)
{
    if (!is_object($dataHelper))
    {
        throw new Exception("sch_function.inc.php : scheduledPlans : DataHelper Object did not instantiate", 104);
    }
    try
    {
        $strSqlStatement = "SELECT subscription_id, user_id, subscription_date, subscription_start_date_gmt, subscription_end_date_gmt, subscription_start_date_local, subscription_end_date_local, subscription_status, order_id, plan_id, plan_name, plan_desc, plan_for, plan_type, number_of_sessions, number_of_mins_per_sessions, plan_period, number_of_invitee, meeting_recording, disk_space, is_free, plan_cost_inr, plan_cost_oth, concurrent_sessions, talk_time_mins, autorenew_flag, consumed_number_of_sessions, consumed_talk_time_mins FROM subscription_master WHERE user_id = '".trim($strCK_user_id)."' AND subscription_id = '".trim($subId)."'";
        $arrResult = $dataHelper->fetchRecords("QR", $strSqlStatement);
        return $arrResult;
    }
    catch (Exception $e)
    {
        throw new Exception("sch_function.inc.php : Fetch Schedule Plan Details Failed : ".$e->getMessage(), 1105);
    }
}


function isPlanExpired($strCK_user_id, $dataHelper)
{
    if (!is_object($dataHelper))
    {
        throw new Exception("sch_function.inc.php : isPlanExpired : DataHelper Object did not instantiate", 104);
    }
    try
    {
        $gDtm = date("Y-m-d", strtotime(GM_DATE));
        $sqlStatement = "SELECT MAX( subscription_end_date_gmt ) AS expGMT FROM subscription_master WHERE user_id =  '".$strCK_user_id."'
                         AND ((plan_type =  'S' AND consumed_number_of_sessions = number_of_sessions)
                         OR (plan_type =  'T' AND consumed_talk_time_mins = talk_time_mins)
                         OR (plan_type =  'U' AND subscription_end_date_gmt <=  '".$gDtm."')
                         OR subscription_end_date_gmt <=  '".$gDtm."')";
        $arrResult = $dataHelper->fetchRecords("QR", $sqlStatement);
        return $arrResult;
    }
    catch (Exception $e)
    {
        throw new Exception("sch_function.inc.php : isPlanExpired Failed : ".$e->getMessage(), 1105);
    }
}