<?php

/* -----------------------------------------------------------------------------
  Function Name : isPlanExists
  Purpose       : To check is user has subscribed or not from subscription_master table.
  Parameters    : user_id, datetime Datahelper
  Returns       :
  Calls         : datahelper.fetchRecords
  Called By     :
  Author        : Mitesh Shah
  Created  on   : 19-July-2015
  Modified By   :
  Modified on   :
  ------------------------------------------------------------------------------ */

function isPlanExists($user_id, $datetime, $dataHelper) {
    if (!is_object($dataHelper))
    {
        throw new Exception("schedule_function.inc.php : isPlanExists : DataHelper Object did not instantiate", 104);
    }
    try
    {
        //$gDtm = date("Y-m-d", strtotime(GM_DATE));
        $gDtm = $datetime;
        $sqlStatement = "SELECT MAX( subscription_end_date_gmt ) AS eGMT FROM subscription_master WHERE user_id =  '" . $user_id . "' AND subscription_end_date_gmt >=  '" . $gDtm . "' AND ( (plan_type =  'S' AND consumed_number_of_sessions < number_of_sessions) OR (plan_type =  'T' AND consumed_talk_time_mins < talk_time_mins) OR (plan_type =  'U'));";
        $arrResult = $dataHelper->fetchRecords("QR", $sqlStatement);
        return $arrResult;
    }
    catch (Exception $e)
    {
        throw new Exception("schedule_function.inc.php : Fetch Max Date Schedule Plan Failed : " . $e->getMessage(), 1105);
    }
}

/* -----------------------------------------------------------------------------
  Function Name : isPlanExpired
  Purpose       : To check is user has subscribed or not from subscription_master table.
  Parameters    : user_id, datetime Datahelper
  Returns       :
  Calls         : datahelper.fetchRecords
  Called By     :
  Author        : Mitesh Shah
  Created  on   : 19-July-2015
  Modified By   :
  Modified on   :
  ------------------------------------------------------------------------------ */

function isPlanExpired($user_id, $datetime, $dataHelper) {
    if (!is_object($dataHelper))
    {
        throw new Exception("schedule_function.inc.php : isPlanExpired : DataHelper Object did not instantiate", 104);
    }
    try
    {
        //$gDtm = date("Y-m-d", strtotime(GM_DATE));
        $gDtm = $datetime;
        $sqlStatement = "SELECT MAX( subscription_end_date_gmt ) AS expGMT FROM subscription_master WHERE user_id =  '" . $user_id . "' AND ((plan_type =  'S' AND consumed_number_of_sessions = number_of_sessions) OR (plan_type =  'T' AND consumed_talk_time_mins = talk_time_mins) OR (plan_type =  'U' AND subscription_end_date_gmt <=  '" . $gDtm . "') OR subscription_end_date_gmt <=  '" . $gDtm . "')";
        $arrResult = $dataHelper->fetchRecords("QR", $sqlStatement);
        return $arrResult;
    }
    catch (Exception $e)
    {
        throw new Exception("schedule_function.inc.php : isPlanExpired Failed : " . $e->getMessage(), 1105);
    }
}

/* -----------------------------------------------------------------------------
  Function Name : scheduledPlans
  Purpose       : To check is user has subscribed or not from subscription_master table.
  Parameters    : user_id, datetime, Datahelper
  Returns       :
  Calls         : datahelper.fetchRecords
  Called By     :
  Author        : Mitesh Shah
  Created  on   : 19-July-2015
  Modified By   :
  Modified on   :
  ------------------------------------------------------------------------------ */

function scheduledPlans($user_id, $datetime, $dataHelper) {
    if (!is_object($dataHelper))
    {
        throw new Exception("schedule_function.inc.php : scheduledPlans : DataHelper Object did not instantiate", 104);
    }
    try
    {
        //$gDtm = date("Y-m-d", strtotime(GM_DATE));
        $gDtm = $datetime;
        $strSqlStatement = "SELECT subscription_id, user_id, subscription_end_date_gmt, plan_id, plan_name, plan_type, number_of_sessions, plan_period, number_of_invitee, concurrent_sessions, talk_time_mins, consumed_number_of_sessions, consumed_talk_time_mins FROM subscription_master WHERE user_id = '" . $user_id . "' AND subscription_end_date_gmt >= '" . $gDtm . "' AND ( (plan_type = 'S' AND consumed_number_of_sessions < number_of_sessions) OR (plan_type = 'T' AND consumed_talk_time_mins < talk_time_mins) OR (plan_type = 'U') ) ORDER BY subscription_end_date_gmt DESC;";
        $arrResult = $dataHelper->fetchRecords("QR", $strSqlStatement);
        return $arrResult;
    }
    catch (Exception $e)
    {
        throw new Exception("schedule_function.inc.php : Fetch Schedule Plan Details Failed : " . $e->getMessage(), 1105);
    }
}

/* -----------------------------------------------------------------------------
  Function Name : getCombineGroupList
  Purpose       : To Get Contact Group List from client_contact_details and personal_contact_details  Table
  Parameters    : user_id, client_id, Datahelper
  Returns       :
  Calls         : datahelper.fetchRecords
  Called By     :
  Author        : Mitesh Shah
  Created  on   : 19-July-2015
  Modified By   :
  Modified on   :
  ------------------------------------------------------------------------------- */

function getCombineGroupList($user_id, $client_id, $dataHelper) {
    if (!is_object($dataHelper))
    {
        throw new Exception("schedule_function.inc.php : getCombineGroupList : DataHelper Object did not instantiate", 104);
    }
    try
    {
        $dataHelper->setParam("'" . $user_id . "'", "I");
        $dataHelper->setParam("'" . $client_id . "'", "I");
        $arrGroupList = $dataHelper->fetchRecords("SP", "GetCombinedGroupName");
        $dataHelper->clearParams();
        return $arrGroupList;
    }
    catch (Exception $e)
    {
        throw new Exception("schedule_function.inc.php : getCombineGroupList : Could not fetch Group List : " . $e->getMessage(), 1111);
    }
}

/* -----------------------------------------------------------------------------
  Function Name : getCombineContactList
  Purpose       : To Get Contact List from client_contact_details and personal_contact_details  Table
  Parameters    : user_id, client_id, Datahelper
  Returns       :
  Calls         : datahelper.fetchRecords
  Called By     :
  Author        : Mitesh Shah
  Created  on   : 19-July-2015
  Modified By   :
  Modified on   :
  ------------------------------------------------------------------------------ */

function getCombineContactList($user_id, $client_id, $dataHelper) {
    if (!is_object($dataHelper))
    {
        throw new Exception("schedule_function.inc.php : getCombineContactList : DataHelper Object did not instantiate", 104);
    }
    try
    {
        $dataHelper->setParam("'" . $user_id . "'", "I");
        $dataHelper->setParam("'" . $client_id . "'", "I");
        $arrContactList = $dataHelper->fetchRecords("SP", "GetCombinedContactList");
        $dataHelper->clearParams();
        return $arrContactList;
    }
    catch (Exception $e)
    {
        throw new Exception("schedule_function.inc.php : getCombineContactList : Could not fetch Contact List : " . $e->getMessage(), 1111);
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

function autoSuggest($client_id, $user_id, $word, $dataHelper) {
    if (!is_object($dataHelper))
    {
        throw new Exception("schedule_function.inc.php : autoSuggest : DataHelper Object did not instantiate", 104);
    }
    try
    {
        $strSqlStatement = "SELECT client_contact_details.client_contact_id, client_contact_details.contact_nick_name AS nick_name, client_contact_details.contact_email_address, client_contact_details.contact_idd_code, client_contact_details.contact_mobile_number, client_contact_details.contact_group_name "
                . "FROM client_contact_details, user_login_details "
                . "WHERE client_contact_details.client_id = user_login_details.client_id AND client_contact_details.client_id = '" . $client_id . "' AND user_login_details.user_id = '" . $user_id . "' AND client_contact_details.client_contact_status = '1' AND client_contact_details.contact_nick_name LIKE '" . trim($word) . "%' UNION "
                . "SELECT personal_contact_details.personal_contact_id, personal_contact_details.contact_nick_name AS nick_name, personal_contact_details.contact_email_address, personal_contact_details.contact_idd_code, personal_contact_details.contact_mobile_number, personal_contact_details.contact_group_name "
                . "FROM personal_contact_details, user_login_details "
                . "WHERE personal_contact_details.user_id = user_login_details.user_id AND personal_contact_details.user_id = '" . $user_id . "' AND user_login_details.client_id = '" . $client_id . "' AND personal_contact_details.personal_contact_status = '1' AND personal_contact_details.contact_nick_name LIKE '" . trim($word) . "%' ORDER BY nick_name;";
        $arrList = $dataHelper->fetchRecords("QR", $strSqlStatement);
        return $arrList;
    }
    catch (Exception $e)
    {
        throw new Exception("schedule_function.inc.php : Fetch Auto Suggest Failed : " . $e->getMessage(), 1106);
    }
}

function validatePlan($subscription_id, $user_id, $dataHelper) {
    if (!is_object($dataHelper))
    {
        throw new Exception("schedule_function.inc.php : scheduledPlans : DataHelper Object did not instantiate", 104);
    }
    try
    {
        $strSqlStatement = "SELECT subscription_id, user_id, subscription_date, subscription_start_date_gmt, subscription_end_date_gmt, subscription_start_date_local, subscription_end_date_local, subscription_status, order_id, plan_id, plan_name, plan_desc, plan_for, plan_type, number_of_sessions, number_of_mins_per_sessions, plan_period, number_of_invitee, meeting_recording, disk_space, is_free, plan_cost_inr, plan_cost_oth, concurrent_sessions, talk_time_mins, autorenew_flag, consumed_number_of_sessions, consumed_talk_time_mins FROM subscription_master WHERE user_id = '" . trim($user_id) . "' AND subscription_id = '" . trim($subscription_id) . "'";
        $arrResult = $dataHelper->fetchRecords("QR", $strSqlStatement);
        return $arrResult;
    }
    catch (Exception $e)
    {
        throw new Exception("schedule_function.inc.php : Fetch Schedule Plan Details Failed : " . $e->getMessage(), 1105);
    }
}

function currentSession($user_id, $subscription_id, $gmTime, $dataHelper) {
    if (!is_object($dataHelper))
    {
        throw new Exception("schedule_function.inc.php : scheduledPlans : DataHelper Object did not instantiate", 104);
    }
    try
    {
        $meetingGMT = strtotime($gmTime);
        $beforeTime = date("Y-m-d H:i:s", strtotime("-" . MEETING_START_GRACE_INTERVAL . " minutes", $meetingGMT));
        $afterTime = date("Y-m-d H:i:s", strtotime("+" . MEETING_END_GRACE_INTERVAL . " minutes", $meetingGMT));
        $strSqlStatement = "SELECT schedule_id, schedule_status, meeting_title, meeting_timestamp_gmt, meeting_timestamp_local, meeting_timezone FROM schedule_details WHERE user_id = '" . $user_id . "' AND schedule_status IN ('0','1') AND subscription_id = '" . $subscription_id . "' AND meeting_timestamp_gmt >= '" . $beforeTime . "' AND meeting_timestamp_gmt <= '" . $afterTime . "'";
        $arrResult = $dataHelper->fetchRecords("QR", $strSqlStatement);
        return $arrResult;
    }
    catch (Exception $e)
    {
        throw new Exception("schedule_function.inc.php : Fetch Schedule Plan Details Failed : " . $e->getMessage(), 1105);
    }
}

function voiceBridgeToken($dataHelper) {
    $length = VOICE_BRIDGE_LENGTH;
    $random = "";
    srand((double) microtime() * 1000000);
    $char_list .= "0123456789";
    for ($i = 0; $i < $length - 1; $i++)
    {
        $random .= substr($char_list, (rand() % (strlen($char_list))), 1);
    }
    $voiceBridgeToken = VOICE_BRIDGE_PREFIX . $random;
    try
    {
        $voiceBridgeStatus = isVoiceBridgeValidate($voiceBridgeToken, $dataHelper);
    }
    catch (Exception $e)
    {
        throw new Exception("schedule_function.inc.php : Fetch Voice Failed : " . $e->getMessage(), 1109);
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

function isVoiceBridgeValidate($voiceBridgeToken, $dataHelper) {
    if (!is_object($dataHelper))
    {
        throw new Exception("schedule_function.inc.php : isVoiceBridgeValidate : DataHelper Object did not instantiate", 104);
    }
    try
    {
        $strSqlStatement = "SELECT voice_bridge FROM schedule_details WHERE voice_bridge = '" . $voiceBridgeToken . "'";
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
        throw new Exception("schedule_function.inc.php : Fetch Voice Bridge Failed : " . $e->getMessage(), 1110);
    }
}

function getScheduleId($dataHelper) {
    if (!is_object($dataHelper))
    {
        throw new Exception("schedule_function.inc.php : getScheduleId : DataHelper Object did not instantiate", 104);
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
        $schId = uniqid('', FALSE);
    }
    catch (Exception $e)
    {
        throw new Exception("schedule_function.inc.php : Insert Schedule Details Failed : " . $e->getMessage(), 1111);
    }
    return $schId;
}

function getLMInstanceByClientId($client_id, $dataHelper) {
    if (!is_object($dataHelper))
    {
        throw new Exception("schedule_function.inc.php : getLMInstanceByClientId : DataHelper Object did not instantiate", 104);
    }

    try
    {
        $strSqlStatement = "SELECT client_id, partner_id, logout_url, rt_server_name, rt_server_salt, rt_server_api_url, status FROM client_details  WHERE status = '1' AND client_id = '" . trim($client_id) . "'";
        $arrInstanceList = $dataHelper->fetchRecords("QR", $strSqlStatement);
        return $arrInstanceList;
    }
    catch (Exception $e)
    {
        throw new Exception("schedule_function.inc.php : getLMInstanceByClientId : Could not fetch records : " . $e->getMessage(), 1111);
    }
}

function scheduleDetails($schID, $user_id, $gmTime, $localTime, $meeting_title, $timezone, $gmt, $meetingAttendeePWD, $meetingModeratorPWD, $voiceBridgeToken, $inviteesCnt, $meetingRecoding, $maxSessionsMinutes, $meetingInstance, $subscription_id, $meeting_agenda, $dataHelper) {
    if (!is_object($dataHelper))
    {
        throw new Exception("schedule_function.inc.php : scheduleMeeting : DataHelper Object did not instantiate", 104);
    }
    try
    {
        $insSqlStatement = "INSERT INTO schedule_details (schedule_id , user_id , schedule_creation_time , meeting_timestamp_gmt , meeting_timestamp_local , meeting_title , meeting_agenda , meeting_timezone , meeting_gmt , attendee_password , moderator_password , welcome_message , voice_bridge , web_voice , max_participants , record_flag , meeting_duration, meta_tags, meeting_instance, subscription_id) VALUES ('" . trim($schID) . "' , '" . trim($user_id) . "' , '" . trim(GM_DATE) . "' , '" . trim($gmTime) . "' , '" . trim($localTime) . "' , '" . trim($meeting_title) . "' , '" . trim($meeting_agenda) . "' , '" . trim($timezone) . "', '" . trim($gmt) . "', '" . trim($meetingAttendeePWD) . "' , '" . trim($meetingModeratorPWD) . "' , 'NULL' , '" . trim($voiceBridgeToken) . "' , '" . trim($voiceBridgeToken) . "' , '" . trim($inviteesCnt) . "' , '" . trim($meetingRecoding) . "' , '" . trim($maxSessionsMinutes) . "', 'NULL', '" . trim($meetingInstance) . "', '" . trim($subscription_id) . "')";
        $arrSchedule = $dataHelper->putRecords("QR", $insSqlStatement);
        return $schID;
    }
    catch (Exception $e)
    {
        throw new Exception("schedule_function.inc.php : Insert Schedule Details Failed : " . $e->getMessage(), 1101);
    }
}

function updConsumedSessions($subscription_id, $user_id, $type, $dataHelper) {
    try
    {
        if (strlen(trim($subscription_id)) <= 0)
        {
            throw new Exception("schedule_function.inc.php: updConsumedSessions : Missing Parameter subscription_id.", 2081);
        }

        if (strlen(trim($user_id)) <= 0)
        {
            throw new Exception("schedule_function.inc.php: updConsumedSessions : Missing Parameter user_id.", 2082);
        }

        if (strlen(trim($type)) <= 0)
        {
            throw new Exception("schedule_function.inc.php: updConsumedSessions : Missing Parameter type.", 2083);
        }

        if (!is_object($dataHelper))
        {
            throw new Exception("schedule_function.inc.php : updConsumedSessions : DataHelper Object did not instantiate", 104);
        }

        $dataHelper->setParam("'" . trim($subscription_id) . "'", "I");
        $dataHelper->setParam("'" . trim($user_id) . "'", "I");
        $dataHelper->setParam("'" . trim($type) . "'", "I");
        $dataHelper->setParam("result", "O");
        $arrUpdConSession = $dataHelper->putRecords("SP", "UpdateConsumedSessions");
        $dataHelper->clearParams();
        return $arrUpdConSession;
    }
    catch (Exception $e)
    {
        throw new Exception("schedule_function.inc.php : updConsumedSessions : Could not update Consumed Sessions : " . $e->getMessage(), 2084);
    }
}

/* -----------------------------------------------------------------------------
  Function Name : getClSubInfoFromUserOrderId
  Purpose       : Get client_subscription_id,  client_id, order_id FROM client_subscription_master,  subscription_master table
  Parameters    : subscription_id, Datahelper
  Returns       : client_subscription_id,  client_id, order_id
  Calls         : datahelper.fetchRecords
  Called By     : cancelschedule.php, createSchedule.php
  Author        : Mitesh Shah
  Created  on   : 19-July-2015
  Modified By   :
  Modified on   :
  ------------------------------------------------------------------------------ */

function getClSubInfoFromUserOrderId($user_order_id, $dataHelper) {
    try
    {
        if (strlen(trim($user_order_id)) <= 0)
        {
            throw new Exception("schedule_function.inc.php: getClSubInfoFromUserOrderId : Missing Parameter subscription_id.", 2081);
        }

        if (!is_object($dataHelper))
        {
            throw new Exception("schedule_function.inc.php : getClSubInfoFromUserOrderId : DataHelper Object did not instantiate", 104);
        }

        $strSqlStatement = "SELECT csm.client_subscription_id,  csm.client_id, csm.order_id FROM client_subscription_master csm,  subscription_master sm "
                . " WHERE csm.order_id = sm.order_id AND sm.order_id = '" . trim($user_order_id) . "'";
        $arrResult = $dataHelper->fetchRecords("QR", $strSqlStatement);
        return $arrResult;
    }
    catch (Exception $e)
    {
        throw new Exception("schedule_function.inc.php : getClSubInfoFromUserOrderId : Could not get details : " . $e->getMessage(), 2084);
    }
}

/* -----------------------------------------------------------------------------
  Function Name : updClientConsumedSessions
  Purpose       : Update consumed_number_of_sessions for the subscription_id and client_id in client_subscription_master table
  Parameters    : client_subscription_id, client_id, type(Add or Subtract), Datahelper
  Returns       : status(0,1,2)
  Calls         : datahelper.fetchRecords
  Called By     : cancelschedule.php, createSchedule.php
  Author        : Mitesh Shah
  Created  on   : 19-July-2015
  Modified By   :
  Modified on   :
  ------------------------------------------------------------------------------ */

function updClientConsumedSessions($subscription_id, $client_id, $type, $dataHelper) {
    try
    {
        if (strlen(trim($subscription_id)) <= 0)
        {
            throw new Exception("schedule_function.inc.php: updClientConsumedSessions : Missing Parameter subscription_id.", 2081);
        }

        if (strlen(trim($client_id)) <= 0)
        {
            throw new Exception("schedule_function.inc.php: updClientConsumedSessions : Missing Parameter user_id.", 2082);
        }

        if (strlen(trim($type)) <= 0)
        {
            throw new Exception("schedule_function.inc.php: updClientConsumedSessions : Missing Parameter type.", 2083);
        }

        if (!is_object($dataHelper))
        {
            throw new Exception("schedule_function.inc.php : updClientConsumedSessions : DataHelper Object did not instantiate", 104);
        }

        $dataHelper->setParam("'" . trim($subscription_id) . "'", "I");
        $dataHelper->setParam("'" . trim($client_id) . "'", "I");
        $dataHelper->setParam("'" . trim($type) . "'", "I");
        $dataHelper->setParam("result", "O");
        $arrUpdConSession = $dataHelper->putRecords("SP", "UpdateClientConsumedSessions");
        $dataHelper->clearParams();
        return $arrUpdConSession;
    }
    catch (Exception $e)
    {
        throw new Exception("schedule_function.inc.php : updClientConsumedSessions : Could not update Consumed Sessions : " . $e->getMessage(), 2084);
    }
}

function inviteesDetails($schedule_id, $email_address, $strUserDetails, $arrInviteesEmail, $moderator, $dataHelper) {
    if (!is_object($dataHelper))
    {
        throw new Exception("schedule_function.inc.php : inviteesDetails : DataHelper Object did not instantiate", 104);
    }
    try
    {
        $userDetails = explode(":", $strUserDetails);
        if (strlen($email_address) > 0)
        {
            $insSchedulerStatement = "INSERT INTO invitation_details (schedule_id, invitee_email_address, invitation_creator, invitee_nick_name, invitee_idd_code, invitee_mobile_number, invitation_status, invitation_creation_dtm, meeting_status) VALUES ('" . $schedule_id . "' , '" . $email_address . "' , 'C' , '" . $userDetails[0] . "' , '" . $userDetails[1] . "' , '" . $userDetails[2] . "' , '0' , '" . GM_DATE . "' , '0')";
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
                $insSqlStatement = "INSERT INTO invitation_details (schedule_id, invitee_email_address, invitation_creator, invitee_nick_name, invitee_idd_code, invitee_mobile_number, invitation_status, invitation_creation_dtm, meeting_status) VALUES ('" . $schedule_id . "' , '" . $invitees[$i][0] . "' , 'M', '" . $invitees[$i][1] . "' , '" . $invitees[$i][2] . "' , '" . $invitees[$i][3] . "' , '0' , '" . GM_DATE . "' , '0')";
            }
            else
            {
                $insSqlStatement = "INSERT INTO invitation_details (schedule_id, invitee_email_address, invitee_nick_name, invitee_idd_code, invitee_mobile_number, invitation_status, invitation_creation_dtm, meeting_status) VALUES ('" . $schedule_id . "' , '" . $invitees[$i][0] . "' , '" . $invitees[$i][1] . "' , '" . $invitees[$i][2] . "' , '" . $invitees[$i][3] . "' , '0' , '" . GM_DATE . "' , '0')";
            }
            $inviteesStat = $dataHelper->putRecords("QR", $insSqlStatement);
        }
        return true;
    }
    catch (Exception $e)
    {
        throw new Exception("schedule_function.inc.php : Insert Invitees Details Failed : " . $e->getMessage(), 1102);
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

function createMeeting($schedule_id, $email_address) {
    $PSCD = md5($schedule_id . ":" . $email_address . ":" . SECRET_KEY);
    $jmData = "SCID=" . $schedule_id . "&EMID=" . urlencode($email_address) . "&PSCD=" . $PSCD . "&PRID=" . PRID;
    $jmUrl = INT_API_ROOT . "join/jmx.php?" . $jmData;
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
  Function Name : isScheduleValid
  Purpose       : To Validate Schedule Id of the meeting from schedule_details table
  Parameters    : schedule_id, passcode(md5(schedule_id:secret_key)), MEETING_START_GRACE_INTERVAL, MEETING_END_GRACE_INTERVAL, Datahelper
  Returns       : array (with schedule_id, schedule_status, meeting_timestamp_gmt)
  Calls         : datahelper.fetchRecords
  Called By     :
  Author        : Mitesh Shah
  Created  on   : 19-July-2015
  Modified By   :
  Modified on   :
  ------------------------------------------------------------------------------ */

//
//function isScheduleValid($schedule_id, $dataHelper) {
//    try
//    {
//        if (strlen(trim($schedule_id)) <= 0)
//        {
//            throw new Exception("schedule_function.inc.php: isScheduleValid : Missing Parameter schedule_id.", 2021);
//        }
//
//        if (!is_object($dataHelper))
//        {
//            throw new Exception("schedule_function.inc.php : isScheduleValid : DataHelper Object did not instantiate", 104);
//        }
//
//        $strSqlStatement = "SELECT schedule_id, schedule_status, meeting_timestamp_gmt, meeting_timestamp_local, meeting_title, " .
//                "meeting_timezone, meeting_gmt, user_details.user_id, user_login_details.email_address, user_details.nick_name, subscription_master.subscription_id, subscription_master.number_of_invitee, subscription_master.order_id " .
//                "FROM schedule_details, user_details, user_login_details, subscription_master " .
//                "WHERE schedule_details.user_id = user_details.user_id " .
//                "user_login_details.user_id = user_details.user_id " .
//                "AND schedule_details.subscription_id = subscription_master.subscription_id " .
//                "AND schedule_id='" . trim($schedule_id) . "'";
//        $arrResult = $dataHelper->fetchRecords("QR", $strSqlStatement);
//        $dataHelper->clearParams();
//        return $arrResult;
//    }
//    catch (Exception $e)
//    {
//        throw new Exception("schedule_function.inc.php : isScheduleValid : Could not fetch records : " . $e->getMessage(), 2022);
//    }
//}

/* -----------------------------------------------------------------------------
  Function Name : isScheduleInviteeValid
  Purpose       : To Validate Schedule Id and Invitee of the meeting from schedule_details and invitation_details table
  Parameters    : schedule_id, passcode(md5(schedule_id:invitee_email_address:secret_key)), invitee_email_address, MEETING_START_GRACE_INTERVAL, MEETING_END_GRACE_INTERVAL, Datahelper
  Returns       : array (with schedule_id, schedule_status, meeting_timestamp_gmt, meeting_title, start_grace_time, end_grace_time, invitee_email_address, invitation_creator, user_id, email_address, client_id)
  Calls         : datahelper.fetchRecords
  Called By     : jm.php and jmx.php
  Author        : Mitesh Shah
  Created  on   : 19-July-2015
  Modified By   :
  Modified on   :
  ------------------------------------------------------------------------------ */

function isScheduleInviteeValid($schedule_id, $passcode, $email_address, $SGInterval, $EGInterval, $dataHelper) {
    try
    {
        if (!is_object($dataHelper))
        {
            throw new Exception("schedule_function.inc.php : isScheduleInviteeValid : DataHelper Object did not instantiate", 104);
        }

        $strSqlStatement = "SELECT sd.schedule_id, schedule_status, meeting_timestamp_gmt, meeting_timestamp_local, " .
                "DATE_SUB(meeting_timestamp_gmt, INTERVAL " . trim($SGInterval) . " MINUTE) AS start_grace_time, " .
                "DATE_ADD(meeting_timestamp_gmt, INTERVAL " . trim($EGInterval) . " MINUTE) AS end_grace_time, " .
                "meeting_title, meeting_agenda, meeting_timezone, meeting_gmt, attendee_password, moderator_password, " .
                "welcome_message, voice_bridge, web_voice, max_participants, record_flag, meeting_duration, meta_tags, " .
                "meeting_instance, invitee_email_address, invitee_nick_name, invitee_idd_code, invitee_mobile_number, " .
                "invitation_creator, meeting_status, ud.user_id, uld.client_id, uld.email_address, ud.nick_name, sd.subscription_id, " .
                "sm.plan_id, sm.plan_type, sm.number_of_sessions, sm.number_of_mins_per_sessions, sm.concurrent_sessions, " .
                "sm.talk_time_mins, sm.consumed_number_of_sessions, sm.consumed_talk_time_mins " .
                "FROM schedule_details sd, invitation_details id, user_details ud, user_login_details uld, subscription_master sm " .
                "WHERE sd.user_id = ud.user_id " .
                "AND ud.user_id = uld.user_id " .
                "AND sd.schedule_id = id.schedule_id " .
                "AND sd.subscription_id = sm.subscription_id " .
                "AND sd.schedule_id='" . trim($schedule_id) . "' " .
                "AND MD5(CONCAT('" . trim($schedule_id) . "',':','" . trim($email_address) . "',':','" . SECRET_KEY . "')) = '" . trim($passcode) . "' " .
                "AND invitee_email_address = '" . trim($email_address) . "'";
        $arrSchResult = $dataHelper->fetchRecords("QR", $strSqlStatement);
        return $arrSchResult;
    }
    catch (Exception $e)
    {
        throw new Exception("schedule_function.inc.php : isScheduleValid : Could not fetch records : " . $e->getMessage(), 2036);
    }
}

/* -----------------------------------------------------------------------------
  Function Name : updNewSchedule
  Purpose       : Update schedule_status, bbb_create_time, bbb_message, gmt_datetime for the schedule_id in schedule_details table
  Parameters    : schedule_id, schedule_status, bbb_create_time, bbb_message, gmt_datetime, Datahelper
  Returns       :
  Calls         : datahelper.putRecords
  Called By     : jmx.php
  Author        : Mitesh Shah
  Created  on   : 19-July-2015
  Modified By   :
  Modified on   :
  ------------------------------------------------------------------------------ */

function updNewSchedule($schedule_id, $schedule_status, $bbb_create_time, $bbb_message, $gmt_datetime, $dataHelper) {
    try
    {
        if (strlen(trim($schedule_id)) <= 0)
        {
            throw new Exception("schedule_function.inc.php: updNewSchedule : Missing Parameter schedule_id.", 2041);
        }

        if (strlen(trim($schedule_status)) <= 0)
        {
            throw new Exception("schedule_function.inc.php: updNewSchedule : Missing Parameter schedule_status.", 2042);
        }

        if (!is_object($dataHelper))
        {
            throw new Exception("schedule_function.inc.php : updNewSchedule : DataHelper Object did not instantiate", 104);
        }

        $strSqlStatement = "UPDATE schedule_details SET schedule_status = '" . trim($schedule_status) . "', " .
                "bbb_create_time = '" . trim($bbb_create_time) . "', " .
                "meeting_start_time = '" . trim($gmt_datetime) . "', " .
                "bbb_message = '" . trim($bbb_message) . "' " .
                "WHERE schedule_id = '" . trim($schedule_id) . "' and schedule_status = '0'";
        $Result = $dataHelper->putRecords('QR', $strSqlStatement);
        $dataHelper->clearParams();
        return $Result;
    }
    catch (Exception $e)
    {
        throw new Exception("schedule_function.inc.php : updNewSchedule : Could not update schedule details : " . $e->getMessage(), 2043);
    }
}

/* -----------------------------------------------------------------------------
  Function Name : updInviteeStatus
  Purpose       : Update meeting_status = 1(Joined), meeting_status_join_dtm for the schedule_id in invitation_details table
  Parameters    : schedule_id, invitee_email_address,  gmt_datetime, Datahelper
  Returns       :
  Calls         : datahelper.putRecords
  Called By     : jmx.php
  Author        : Mitesh Shah
  Created  on   : 19-July-2015
  Modified By   :
  Modified on   :
  ------------------------------------------------------------------------------ */

function updInviteeStatus($schedule_id, $inv_email_address, $gmt_datetime, $dataHelper) {
    try
    {
        if (strlen(trim($schedule_id)) <= 0)
        {
            throw new Exception("schedule_function.inc.php: updInviteeStatus : Missing Parameter schedule_id.", 2051);
        }

        if (strlen(trim($inv_email_address)) <= 0)
        {
            throw new Exception("schedule_function.inc.php: updInviteeStatus : Missing Parameter inv_email_address.", 2052);
        }

        if (!is_object($dataHelper))
        {
            throw new Exception("schedule_function.inc.php : updInviteeStatus : DataHelper Object did not instantiate", 104);
        }

        $strSqlStatement = "UPDATE invitation_details SET meeting_status = '1', " .
                "meeting_status_join_dtm = '" . trim($gmt_datetime) . "' " .
                "WHERE schedule_id = '" . trim($schedule_id) . "' " .
                "AND invitee_email_address = '" . trim($inv_email_address) . "' " .
                "AND meeting_status = '0'";
        $Result = $dataHelper->putRecords('QR', $strSqlStatement);
        $dataHelper->clearParams();
        return $Result;
    }
    catch (Exception $e)
    {
        throw new Exception("schedule_function.inc.php : updInviteeStatus : Could not update invitation details : " . $e->getMessage(), 2053);
    }
}

/* -----------------------------------------------------------------------------
  Function Name : updInvitationStatus
  Purpose       : Update invitation_status, invitation_status_dtm for the schedule_id in invitation_details table
  Parameters    : schedule_id, invitee_email_address, gmt_datetime, Datahelper
  Returns       :
  Calls         : datahelper.putRecords
  Called By     : pm.php
  Author        : Mitesh Shah
  Created  on   : 19-July-2015
  Modified By   :
  Modified on   :
  ------------------------------------------------------------------------------ */

function updInvitationStatus($schedule_id, $invitation_status, $inv_email_address, $gmt_datetime, $dataHelper) {
    try
    {
        if (strlen(trim($schedule_id)) <= 0)
        {
            throw new Exception("schedule_function.inc.php: updInvitationStatus : Missing Parameter schedule_id.", 2061);
        }

        if (strlen(trim($invitation_status)) <= 0)
        {
            throw new Exception("schedule_function.inc.php: updInvitationStatus : Missing Parameter invitation_status.", 2062);
        }

        if (strlen(trim($inv_email_address)) <= 0)
        {
            throw new Exception("schedule_function.inc.php: updInvitationStatus : Missing Parameter inv_email_address.", 2063);
        }

        if (!is_object($dataHelper))
        {
            throw new Exception("schedule_function.inc.php : updInvitationStatus : DataHelper Object did not instantiate", 104);
        }

        $strSqlStatement = "UPDATE invitation_details SET invitation_status = '" . trim($invitation_status) . "', " .
                "invitation_status_dtm = '" . trim($gmt_datetime) . "' " .
                "WHERE schedule_id = '" . trim($schedule_id) . "' " .
                "AND invitee_email_address = '" . trim($inv_email_address) . "' " .
                "AND meeting_status = '0'";
        $UpdResult = $dataHelper->putRecords('QR', $strSqlStatement);
        $dataHelper->clearParams();
        if ($objDataHelper->affectedRows == 0)
        {
            return 0;
        }
        else
        {
            return 1;
        }
        //break;
        //return $UpdResult;
    }
    catch (Exception $e)
    {
        throw new Exception("schedule_function.inc.php : updInvitationStatus : Could not update invitation status : " . $e->getMessage(), 2064);
    }
}

/* -----------------------------------------------------------------------------
  Function Name : cancelSchedule
  Purpose       : Update schedule_status = 3(Cancelled) for the schedule_id in schedule_details table
  Parameters    : schedule_id, schedule_status, gmt_datetime(GMT), Datahelper
  Returns       : status(0,1,2)
  Calls         : datahelper.fetchRecords
  Called By     : cancelschedule.php
  Author        : Mitesh Shah
  Created  on   : 19-July-2015
  Modified By   :
  Modified on   :
  ------------------------------------------------------------------------------ */

function cancelSchedule($schedule_id, $schedule_status, $gmt_datetime, $cancel_reason, $dataHelper) {
    try
    {
        if (strlen(trim($schedule_id)) <= 0)
        {
            throw new Exception("schedule_function.inc.php: cancelSchedule : Missing Parameter schedule_id.", 2071);
        }

        if (strlen(trim($schedule_status)) <= 0)
        {
            throw new Exception("schedule_function.inc.php: cancelSchedule : Missing Parameter schedule_status.", 2072);
        }

        if (strlen(trim($gmt_datetime)) <= 0)
        {
            throw new Exception("schedule_function.inc.php: cancelSchedule : Missing Parameter schedule_status.", 2073);
        }

        if (!is_object($dataHelper))
        {
            throw new Exception("schedule_function.inc.php : cancelSchedule : DataHelper Object did not instantiate", 104);
        }

        $dataHelper->setParam("'" . trim($schedule_id) . "'", "I");
        $dataHelper->setParam("'" . trim($schedule_status) . "'", "I");
        $dataHelper->setParam("'" . trim($gmt_datetime) . "'", "I");
        $dataHelper->setParam("'" . trim($cancel_reason) . "'", "I");
        $dataHelper->setParam("result", "O");
        $arrCancelSchedule = $dataHelper->putRecords("SP", "CancelSchedule");
        $dataHelper->clearParams();
        return $arrCancelSchedule;
    }
    catch (Exception $e)
    {
        throw new Exception("schedule_function.inc.php : cancelSchedule : Could not update schedule status : " . $e->getMessage(), 2074);
    }
}

function getUserConcurrentSessions($user_id, $subscription_id, $gmt_datetime, $dataHelper) {
    if (!is_object($dataHelper))
    {
        throw new Exception("schedule_function.inc.php : scheduledPlans : DataHelper Object did not instantiate", 104);
    }
    try
    {
        $meetingGMT = strtotime($gmt_datetime);
        $beforeTime = date("Y-m-d H:i:s", strtotime("-" . MEETING_START_GRACE_INTERVAL . " minutes", $meetingGMT));
        $afterTime = date("Y-m-d H:i:s", strtotime("+" . MEETING_END_GRACE_INTERVAL . " minutes", $meetingGMT));
        //$strSqlStatement = "SELECT schedule_id, schedule_status, meeting_title, meeting_timestamp_gmt, meeting_timestamp_local FROM schedule_details WHERE user_id = '".trim($user_id)."' AND schedule_status ='1' AND subscription_id = '".trim($subscription_id)."' AND meeting_timestamp_gmt >= '".trim($beforeTime)."' AND meeting_timestamp_gmt <= '".trim($afterTime)."'";
        $strSqlStatement = "SELECT schedule_id, schedule_status, meeting_title, meeting_timestamp_gmt, meeting_timestamp_local " .
                "FROM schedule_details " .
                "WHERE user_id = '" . trim($user_id) . "' AND schedule_status ='1' " .
                "AND subscription_id = '" . trim($subscription_id) . "' ";
        $arrResult = $dataHelper->fetchRecords("QR", $strSqlStatement);
        return $arrResult;
    }
    catch (Exception $e)
    {
        throw new Exception("schedule_function.inc.php : getUserConcurrentSessions : Could not fetch records : " . $e->getMessage(), 2081);
    }
}

/* -----------------------------------------------------------------------------
  Function Name : getScheduledMeetingList
  Purpose       :
  Parameters    :
  Returns       :
  Calls         :  datahelper.fetchRecords
  Called By     : meeting/index.php
  ------------------------------------------------------------------------------ */

function getScheduledMeetingList($email_address, $dataHelper) {
    if (!is_object($dataHelper))
    {
        throw new Exception("schedule_function.inc.php : getMyMeetingList : DataHelper Object did not instantiate", 104);
    }
    try
    {
        //$strSqlStatement = "SELECT sd.schedule_id, sd.user_id, sd.schedule_status, sd.meeting_timestamp_gmt, sd.meeting_timestamp_local, sd.meeting_title, sd.meeting_timezone, sd.max_participants, id.invitation_creator FROM schedule_details AS sd, invitation_details AS id WHERE sd.schedule_id = id.schedule_id AND id.invitee_email_address = '".trim($email_address)."' AND UNIX_TIMESTAMP(meeting_timestamp_gmt) >= UNIX_TIMESTAMP(UTC_TIMESTAMP())  AND schedule_status IN ('0')  ORDER BY meeting_timestamp_gmt ASC";
        $strSqlStatement = "SELECT sd.schedule_id, sd.user_id, sd.schedule_status, sd.schedule_creation_time, sd.meeting_timestamp_gmt, sd.meeting_timestamp_local, sd.meeting_title, sd.meeting_agenda, sd.meeting_timezone, sd.meeting_gmt, sd.meeting_start_time, sd.meeting_end_time, sd.voice_bridge, sd.web_voice, sd.max_participants, sd.record_flag, sd.subscription_id, id.invitation_creator "
                . "FROM schedule_details AS sd, invitation_details AS id "
                . "WHERE sd.schedule_id = id.schedule_id AND id.invitee_email_address = '" . trim($email_address) . "' "
                . "AND schedule_status IN ('0','1') ORDER BY meeting_timestamp_gmt ASC ;";
        $arrResult = $dataHelper->fetchRecords("QR", $strSqlStatement);
        return $arrResult;
    }
    catch (Exception $e)
    {
        throw new Exception("schedule_function.inc.php : Fetch Schedule Meeting List Failed : " . $e->getMessage(), 1105);
    }
}

/* -----------------------------------------------------------------------------
  Function Name : getModeratorDetails
  Purpose       :
  Parameters    :
  Returns       :
  Calls         :  datahelper.fetchRecords
  Called By     : meeting/index.php
  ------------------------------------------------------------------------------ */

function getModeratorDetails($schedule_id, $dataHelper) {
    if (!is_object($dataHelper))
    {
        throw new Exception("schedule_function.inc.php : moderatorDetails : DataHelper Object did not instantiate", 104);
    }
    try
    {
        $strSqlStatement = "SELECT invitee_email_address, invitee_nick_name FROM invitation_details WHERE schedule_id = '" . trim($schedule_id) . "' AND invitation_creator = 'C'";
        $arrResult = $dataHelper->fetchRecords("QR", $strSqlStatement);
        return $arrResult;
    }
    catch (Exception $e)
    {
        throw new Exception("schedule_function.inc.php : Fetch Moderator Details Failed : " . $e->getMessage(), 1105);
    }
}

/* -----------------------------------------------------------------------------
  Function Name : getScheduledMeetingList
  Purpose       :
  Parameters    :
  Returns       :
  Calls         :  datahelper.fetchRecords
  Called By     : meeting/index.php
  ------------------------------------------------------------------------------ */

function getArchiveMeetingList($email_address, $dataHelper) {
    if (!is_object($dataHelper))
    {
        throw new Exception("schedule_function.inc.php : getMyMeetingList : DataHelper Object did not instantiate", 104);
    }
    try
    {
        $strSqlStatement = "SELECT sd.schedule_id, sd.user_id, sd.schedule_status, sd.schedule_creation_time, sd.meeting_timestamp_gmt, sd.meeting_timestamp_local, sd.meeting_title, sd.meeting_agenda, sd.meeting_timezone, sd.meeting_gmt, sd.meeting_start_time, sd.meeting_end_time, sd.voice_bridge, sd.web_voice, sd.max_participants, sd.record_flag, sd.subscription_id, id.invitation_creator "
                . "FROM schedule_details AS sd, invitation_details AS id "
                . "WHERE sd.schedule_id = id.schedule_id AND id.invitee_email_address = '" . trim($email_address) . "' "
                . "AND ( (sd.meeting_timestamp_gmt < '".GM_DATE."' AND sd.schedule_status NOT IN ('0','1') ) OR sd.schedule_status IN ('2','3','4','5') ) "
                . "ORDER BY sd.meeting_timestamp_gmt DESC ;";
        $arrResult = $dataHelper->fetchRecords("QR", $strSqlStatement);
        return $arrResult;
    }
    catch (Exception $e)
    {
        throw new Exception("schedule_function.inc.php : Fetch Schedule Meeting List Failed : " . $e->getMessage(), 1105);
    }
}

function isScheduleValid($schedule_id, $email_address, $pass_code, $dataHelper) {
    try
    {
        if (!is_object($dataHelper))
        {
            throw new Exception("schedule_function.inc.php : isScheduleInviteeValid : DataHelper Object did not instantiate", 104);
        }
        $strSqlStatement = "SELECT sd.schedule_id, sd.user_id, sd.schedule_status, sd.schedule_creation_time, sd.meeting_timestamp_gmt, sd.meeting_timestamp_local, sd.meeting_title, sd.meeting_agenda, sd.meeting_timezone, sd.meeting_gmt, sd.meeting_start_time, sd.meeting_end_time, sd.voice_bridge, sd.web_voice, sd.max_participants, sd.record_flag, sd.subscription_id, uld.email_address, ud.nick_name, sm.subscription_id, sm.number_of_invitee, sm.order_id "
                . "FROM schedule_details sd, user_login_details uld, user_details ud, subscription_master sm "
                . "WHERE sd.user_id = uld.user_id  AND uld.user_id = ud.user_id "
                . "AND sd.subscription_id = sm.subscription_id "
                . "AND sd.schedule_id='" . trim($schedule_id) . "' "
                . "AND MD5(CONCAT('" . trim($schedule_id) . "',':','" . trim($email_address) . "',':','" . SECRET_KEY . "')) = '" . trim($pass_code) . "';";
        $arrSchResult = $dataHelper->fetchRecords("QR", $strSqlStatement);
        return $arrSchResult;
    }
    catch (Exception $e)
    {
        throw new Exception("schedule_function.inc.php : isScheduleValid : Could not fetch records : " . $e->getMessage(), 2036);
    }
}

function getScheduleDetailsById($schedule_id, $dataHelper) {
    try
    {
        if (!is_object($dataHelper))
        {
            throw new Exception("schedule_function.inc.php : isScheduleInviteeValid : DataHelper Object did not instantiate", 104);
        }
        //$strSqlStatement = "SELECT sd.schedule_id, sd.user_id, sd.schedule_status, sd.schedule_creation_time, sd.meeting_timestamp_gmt, sd.meeting_timestamp_local, sd.meeting_title, sd.meeting_agenda, sd.meeting_timezone, sd.meeting_gmt, sd.meeting_start_time, sd.meeting_end_time, sd.voice_bridge, sd.web_voice, sd.max_participants, sd.record_flag, sd.subscription_id FROM schedule_details sd WHERE sd.schedule_id='".trim($schedule_id)."';";
        $strSqlStatement = "SELECT schedule_id, schedule_status, meeting_timestamp_gmt, meeting_timestamp_local, meeting_title, " .
                "meeting_timezone, meeting_gmt, cancel_reason, user_details.user_id, user_login_details.email_address, user_details.nick_name, subscription_master.subscription_id, subscription_master.number_of_invitee, subscription_master.order_id " .
                "FROM schedule_details, user_details, user_login_details, subscription_master " .
                "WHERE schedule_details.user_id = user_login_details.user_id " .
                "AND user_login_details.user_id = user_details.user_id " .
                "AND schedule_details.subscription_id = subscription_master.subscription_id " .
                "AND schedule_id='" . trim($schedule_id) . "'";
        $arrSchResult = $dataHelper->fetchRecords("QR", $strSqlStatement);
        return $arrSchResult;
    }
    catch (Exception $e)
    {
        throw new Exception("schedule_function.inc.php : getScheduleDetailsById : Could not fetch records : " . $e->getMessage(), 2036);
    }
}

function setScheduleCounter($schedule_id, $dataHelper) {
    if (!is_object($dataHelper))
    {
        throw new Exception("sch_function.inc.php : setScheduleCounter : DataHelper Object did not instantiate", 104);
    }
    try
    {
        $strSqlStatement = "SELECT max_participants FROM schedule_details WHERE schedule_id = '" . $schedule_id . "'";
        $arrResult = $dataHelper->fetchRecords("QR", $strSqlStatement);
        $maxParticipants = $arrResult[0]['max_participants'];
        $maxP = $maxParticipants + 1;
        $updStatement = "UPDATE schedule_details SET max_participants = '" . $maxP . "' WHERE schedule_id = '" . $schedule_id . "'";
        $updResponse = $dataHelper->putRecords("QR", $updStatement);
    }
    catch (Exception $e)
    {
        throw new Exception("sch_function.inc.php : Update Schedule Participants Counter Failed : " . $e->getMessage(), 1104);
    }
}
