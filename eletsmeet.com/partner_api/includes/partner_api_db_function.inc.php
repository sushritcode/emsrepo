<?php

/* -----------------------------------------------------------------------------
  Function Name : isAuthenticatePartnerUser
  Purpose       : To authenticate partner user for schedule the meeting
  Parameters    : client_id, email_address, user_pw(md5 of client_id, email_address and password), Datahelper
  Returns       : array (with user_id, email_address, password, client_id, partner_id, user_status)
  Calls         : datahelper.fetchRecords
  Called By     :
  Author        : Mitesh Shah
  Created  on   : 08-July-2015
  Modified By   :
  Modified on   :
  ------------------------------------------------------------------------------ */

function isAuthenticatePartnerUser($clientID, $userEmail, $userPW, $dataHelper) {
    try
    {
        if (strlen(trim($clientID)) <= 0)
        {
            throw new Exception("partner_api_db_function.inc.php: isAuthenticatePartnerUser : Missing Parameter clientID.", 5011);
        }

        if (strlen(trim($userEmail)) <= 0)
        {
            throw new Exception("partner_api_db_function.inc.php: isAuthenticatePartnerUser : Missing Parameter userEmail.", 5012);
        }

        if (strlen(trim($userPW)) <= 0)
        {
            throw new Exception("partner_api_db_function.inc.php: isAuthenticatePartnerUser : Missing Parameter userPW.", 5013);
        }

        if (!is_object($dataHelper))
        {
            throw new Exception("partner_api_db_function.inc.php : isAuthenticatePartnerUser : DataHelper Object did not instantiate", 104);
        }

        $strSqlStatement = "SELECT user_details.user_id, user_details.email_address, user_details.nick_name, user_details.client_id, user_details.partner_id, user_details.idd_code, user_details.mobile_number, user_details.timezones, user_details.gmt, user_details.status AS user_status, client_details.client_secret_key, client_details.rt_server_name, client_details.rt_server_salt " .
                "FROM user_details, client_details, partner_details " .
                "WHERE user_details.status= '1' " .
                "AND client_details.status= '1' " .
                "AND partner_details.status= '1' " .
                "AND user_details.client_id = client_details.client_id " .
                "AND client_details.partner_id = partner_details.partner_id " .
                "AND user_details.email_address = '" . trim($userEmail) . "' " .
                "AND client_details.client_id = '" . trim($clientID) . "' " .
                "AND MD5(CONCAT(user_details.client_id,user_details.email_address,user_details.password)) = '" . trim($userPW) . "'";
        $arrResult = $dataHelper->fetchRecords("QR", $strSqlStatement);
        $dataHelper->clearParams();
        return $arrResult;
    }
    catch (Exception $e)
    {
        throw new Exception("partner_api_db_function.inc.php : isAuthenticatePartnerUser : Could not fetch records : " . $e->getMessage(), 5014);
    }
}

/* -----------------------------------------------------------------------------
  Function Name : isValidUserSubscription
  Purpose       : To validate partner user subscription
  Parameters    : subscription_id, user_id, Datahelper
  Returns       : array (with subscription_id, ud.user_id, subscription_date, subscription_start_date_gmt, subscription_end_date_gmt, subscription_start_date_local, subscription_end_date_local, subscription_status, order_id, plan_id, plan_name, plan_desc, plan_for, plan_type, number_of_sessions, number_of_mins_per_sessions, plan_period, number_of_invitee, meeting_recording, disk_space, is_free, plan_cost_inr, plan_cost_oth, concurrent_sessions, talk_time_mins, autorenew_flag, consumed_number_of_sessions, consumed_talk_time_mins)
  Calls         : datahelper.fetchRecords
  Called By     :
  Author        : Mitesh Shah
  Created  on   : 08-July-2015
  Modified By   :
  Modified on   :
  ------------------------------------------------------------------------------ */

function isValidUserSubscription($userID, $subscriptionID, $dataHelper) {
    try
    {
        if (strlen(trim($userID)) <= 0)
        {
            throw new Exception("partner_api_db_function.inc.php: isValidUserSubscription : Missing Parameter userID.", 5021);
        }

        if (strlen(trim($subscriptionID)) <= 0)
        {
            throw new Exception("partner_api_db_function.inc.php: isValidUserSubscription : Missing Parameter subscriptionID.", 5022);
        }

        if (!is_object($dataHelper))
        {
            throw new Exception("partner_api_db_function.inc.php : isValidUserSubscription : DataHelper Object did not instantiate", 104);
        }

        $strSqlStatement = "SELECT subscription_id, ud.user_id, subscription_date, subscription_start_date_gmt, subscription_end_date_gmt, subscription_start_date_local, subscription_end_date_local, subscription_status, order_id, plan_id, plan_name, plan_desc, plan_for, plan_type, number_of_sessions, number_of_mins_per_sessions, plan_period, number_of_invitee, meeting_recording, disk_space, is_free, plan_cost_inr, plan_cost_oth, concurrent_sessions, talk_time_mins, autorenew_flag, consumed_number_of_sessions, consumed_talk_time_mins " .
                "FROM subscription_master sm, user_details ud " .
                "WHERE sm.user_id = ud.user_id " .
                "AND ud.user_id = '" . trim($userID) . "' " .
                "AND subscription_id = '" . trim($subscriptionID) . "'";
        $arrResult = $dataHelper->fetchRecords("QR", $strSqlStatement);
        $dataHelper->clearParams();
        return $arrResult;
    }
    catch (Exception $e)
    {
        throw new Exception("partner_api_db_function.inc.php : isValidUserSubscription : Could not fetch records : " . $e->getMessage(), 5023);
    }
}

/* -----------------------------------------------------------------------------
  Function Name : getConcurrentSessions
  Purpose       : To get concurrent sessions of user
  Parameters    : user_id, subscription_id, GMT_Time, Datahelper
  Returns       : array (with subscription_id, ud.user_id, subscription_date, subscription_start_date_gmt, subscription_end_date_gmt, subscription_start_date_local, subscription_end_date_local, subscription_status, order_id, plan_id, plan_name, plan_desc, plan_for, plan_type, number_of_sessions, number_of_mins_per_sessions, plan_period, number_of_invitee, meeting_recording, disk_space, is_free, plan_cost_inr, plan_cost_oth, concurrent_sessions, talk_time_mins, autorenew_flag, consumed_number_of_sessions, consumed_talk_time_mins)
  Calls         : datahelper.fetchRecords
  Called By     :
  Author        : Mitesh Shah
  Created  on   : 08-July-2015
  Modified By   :
  Modified on   :
  ------------------------------------------------------------------------------ */

function getConcurrentSessions($userID, $subscriptionID, $gmtTime, $dataHelper) {
    try
    {
        if (strlen(trim($userID)) <= 0)
        {
            throw new Exception("partner_api_db_function.inc.php: getConcurrentSessions : Missing Parameter userID.", 5031);
        }

        if (strlen(trim($subscriptionID)) <= 0)
        {
            throw new Exception("partner_api_db_function.inc.php: getConcurrentSessions : Missing Parameter subscriptionID.", 5032);
        }

        if (strlen(trim($gmtTime)) <= 0)
        {
            throw new Exception("partner_api_db_function.inc.php: getConcurrentSessions : Missing Parameter gmtTime.", 5033);
        }

        if (!is_object($dataHelper))
        {
            throw new Exception("partner_api_db_function.inc.php : getConcurrentSessions : DataHelper Object did not instantiate", 104);
        }

        $meetingGMT = strtotime($gmtTime);


        $beforeTime = date("Y-m-d H:i:s", strtotime("-" . MEETING_START_GRACE_INTERVAL . " minutes", $meetingGMT));
        $afterTime = date("Y-m-d H:i:s", strtotime("+" . MEETING_END_GRACE_INTERVAL . " minutes", $meetingGMT));
        $strSqlStatement = "SELECT schedule_id, schedule_status, meeting_title, meeting_timestamp_gmt, meeting_timestamp_local, meeting_timezone " .
                "FROM schedule_details " .
                "WHERE user_id = '" . trim($userID) . "' " .
                "AND schedule_status IN ('0','1') " .
                "AND subscription_id = '" . trim($subscriptionID) . "' " .
                "AND meeting_timestamp_gmt >= '" . trim($beforeTime) . "' " .
                "AND meeting_timestamp_gmt <= '" . trim($afterTime) . "'";
        $arrResult = $dataHelper->fetchRecords("QR", $strSqlStatement);
        $dataHelper->clearParams();
        return $arrResult;
    }
    catch (Exception $e)
    {
        throw new Exception("partner_api_db_function.inc.php : getConcurrentSessions : Could not fetch records : " . $e->getMessage(), 5034);
    }
}

/* -----------------------------------------------------------------------------
  Function Name : getScheduleID
  Purpose       : To generate new schedule id for the meeting
  Parameters    : Datahelper
  Returns       : array (schedule_id)
  Calls         : datahelper.fetchRecords
  Called By     :
  Author        : Mitesh Shah
  Created  on   : 08-July-2015
  Modified By   :
  Modified on   :
  ------------------------------------------------------------------------------ */

function getScheduleID($dataHelper) {
    try
    {
        if (!is_object($dataHelper))
        {
            throw new Exception("partner_api_db_function.inc.php : getScheduleID : DataHelper Object did not instantiate", 104);
        }

//        $strSVariable = "sch";
//        $strSqlStatement = "SELECT MAX(schedule_id) FROM schedule_details";
//        $arrMaxId = $dataHelper->fetchRecords("QR", $strSqlStatement);
//        $dataHelper->clearParams();
//        $s1 = $arrMaxId[0]['MAX(schedule_id)'];
//        $s2 = explode($strSVariable, $s1);
//        $s3 = $s2[1] + 1;
//        $s4 = strlen($s3);
//        switch ($s4)
//        {
//            case 1: $schId = $strSVariable . "000000" . $s3;
//                break;
//            case 2: $schId = $strSVariable . "00000" . $s3;
//                break;
//            case 3: $schId = $strSVariable . "0000" . $s3;
//                break;
//            case 4: $schId = $strSVariable . "000" . $s3;
//                break;
//            case 5: $schId = $strSVariable . "00" . $s3;
//                break;
//            case 6: $schId = $strSVariable . "0" . $s3;
//                break;
//            case 7: $schId = $strSVariable . $s3;
//                break;
//            default: break;
//        }
//        return $schId;
          $schId = uniqid('',FALSE);
          return $schId;
    }
    catch (Exception $e)
    {
        throw new Exception("partner_api_db_function.inc.php : getScheduleID : Could not fetch records : " . $e->getMessage(), 5041);
    }
}

/* -----------------------------------------------------------------------------
  Function Name : getVoiceBridgeToken
  Purpose       : To generate new Voice Bridge Token for the meeting
  Parameters    : Datahelper
  Returns       : array (voice_bridge)
  Calls         : datahelper.fetchRecords
  Called By     :
  Author        : Mitesh Shah
  Created  on   : 08-July-2015
  Modified By   :
  Modified on   :
  ------------------------------------------------------------------------------ */

function getVoiceBridgeToken($dataHelper) {
    try
    {
        $length = VOICE_BRIDGE_LENGTH;
        $random = "";
        srand((double) microtime() * 1000000);
        $char_list .= "0123456789";
        for ($i = 0; $i < $length - 1; $i++)
        {
            $random .= substr($char_list, (rand() % (strlen($char_list))), 1);
        }
        $voiceBridgeToken = VOICE_BRIDGE_PREFIX . $random;

        if (!is_object($dataHelper))
        {
            throw new Exception("partner_api_db_function.inc.php : getVoiceBridgeToken : DataHelper Object did not instantiate", 104);
        }

        try
        {
            $voiceBridgeStatus = isValidVoiceBridgeToken($voiceBridgeToken, $dataHelper);
        }
        catch (Exception $a)
        {
            throw new Exception("partner_api_db_function.inc.php : isValidVoiceBridgeToken : Could not fetch records : " . $a->getMessage(), 5051);
        }

        if ($voiceBridgeStatus == "1")
        {
            return $voiceBridgeToken;
        }
        else
        {
            getVoiceBridgeToken();
        }
    }
    catch (Exception $e)
    {
        throw new Exception("partner_api_db_function.inc.php : getVoiceBridgeToken : Could not fetch records : " . $e->getMessage(), 5052);
    }
}

/* -----------------------------------------------------------------------------
  Function Name : isValidVoiceBridgeToken
  Purpose       : To validate Voice Bridge Token for the meeting
  Parameters    : Datahelper
  Returns       : array (voice_bridge)
  Calls         : datahelper.fetchRecords
  Called By     :
  Author        : Mitesh Shah
  Created  on   : 08-July-2015
  Modified By   :
  Modified on   :
  ------------------------------------------------------------------------------ */

function isValidVoiceBridgeToken($voiceBridgeToken, $dataHelper) {
    try
    {
        if (strlen(trim($voiceBridgeToken)) <= 0)
        {
            throw new Exception("partner_api_db_function.inc.php: isValidVoiceBridgeToken : Missing Parameter voiceBridgeToken.", 5061);
        }

        if (!is_object($dataHelper))
        {
            throw new Exception("partner_api_db_function.inc.php : isValidVoiceBridgeToken : DataHelper Object did not instantiate", 104);
        }

        $strSqlStatement = "SELECT voice_bridge FROM schedule_details WHERE voice_bridge = '" . trim($voiceBridgeToken) . "'";
        $arrList = $dataHelper->fetchRecords("QR", $strSqlStatement);
        $dataHelper->clearParams();
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
        throw new Exception("partner_api_db_function.inc.php : isValidVoiceBridgeToken : Could not fetch records : " . $e->getMessage(), 5062);
    }
}

/* -----------------------------------------------------------------------------
  Function Name : getTimezoneDetails
  Purpose       : To get details from country_timezones table by passing timzone.
  Parameters    : timezone, Datahelper
  Returns       : ct_id, country_code, timezones, gmt, ct_status
  Calls         : datahelper.fetchRecords
  Called By     :
  Author        : Mitesh Shah
  Created  on   : 08-July-2015
  Modified By   :
  Modified on   :
  ------------------------------------------------------------------------------ */

function getTimezoneDetails($timZone, $dataHelper) {
    try
    {
        if (strlen(trim($timZone)) <= 0)
        {
            throw new Exception("partner_api_db_function.inc.php: getTimezoneDetails : Missing Parameter time_zone.", 5071);
        }

        if (!is_object($dataHelper))
        {
            throw new Exception("partner_api_db_function.inc.php : getTimezoneDetails : DataHelper Object did not instantiate", 104);
        }

        $sqlQuery = "SELECT ct_id, country_code, timezones, gmt, ct_status FROM country_timezones WHERE timezones = '" . trim($timZone) . "'";
        $arrResult = $dataHelper->fetchRecords("QR", $sqlQuery);
        $dataHelper->clearParams();
        return $arrResult;
    }
    catch (Exception $e)
    {
        throw new Exception("partner_api_db_function.inc.php : getTimezoneDetails : Could not fetch records : " . $e->getMessage(), 5072);
    }
}

/* -----------------------------------------------------------------------------
  Function Name : insScheduleDetails
  Purpose       : To insert schedule details in the schedule_details table.
  Parameters    : schedule_id, user_id, schedule_creation_time, meeting_timestamp_gmt, meeting_timestamp_local, meeting_title, meeting_agenda, meeting_timezone, meeting_gmt, attendee_password, moderator_password, welcome_message, voice_bridge, web_voice, max_participants, record_flag, meeting_duration, meta_tags, meeting_instance, subscription_id, Datahelper
  Returns       : status, output
  Calls         : datahelper.putRecords
  Called By     :
  Author        : Mitesh Shah
  Created  on   : 08-July-2015
  Modified By   :
  Modified on   :
  ------------------------------------------------------------------------------ */

function insScheduleDetails($scheduleID, $userID, $scheduleCreateTime, $gmtMeetingTime, $localMeetingTime, $meetingTtitle, $meetingAgenda, $timeZone, $gmtTime, $meetingAttendeePWD, $meetingModeratorPWD, $welcomeMessage, $voiceBridgeToken, $webVoice, $inviteeCount, $meetingRecording, $maxSessionsMinutes, $metaTags, $emailReminderFlag, $emailReminderStatus, $smsReminderFlag, $smsReminderStatus, $meetingInstance, $subscriptionID, $dataHelper) {
    try
    {
        if (!is_object($dataHelper))
        {
            throw new Exception("partner_api_db_function.inc.php : insScheduleDetails : DataHelper Object did not instantiate", 104);
        }

        $dataHelper->setParam("'" . trim($scheduleID) . "'", "I");
        $dataHelper->setParam("'" . trim($userID) . "'", "I");
        $dataHelper->setParam("'" . trim($scheduleCreateTime) . "'", "I");
        $dataHelper->setParam("'" . trim($gmtMeetingTime) . "'", "I");
        $dataHelper->setParam("'" . trim($localMeetingTime) . "'", "I");
        $dataHelper->setParam("'" . trim($meetingTtitle) . "'", "I");
        $dataHelper->setParam("'" . trim($meetingAgenda) . "'", "I");
        $dataHelper->setParam("'" . trim($timeZone) . "'", "I");
        $dataHelper->setParam("'" . trim($gmtTime) . "'", "I");
        $dataHelper->setParam("'" . trim($meetingAttendeePWD) . "'", "I");
        $dataHelper->setParam("'" . trim($meetingModeratorPWD) . "'", "I");
        $dataHelper->setParam("'" . trim($welcomeMessage) . "'", "I");
        $dataHelper->setParam("'" . trim($voiceBridgeToken) . "'", "I");
        $dataHelper->setParam("'" . trim($webVoice) . "'", "I");
        $dataHelper->setParam("'" . trim($inviteeCount) . "'", "I");
        $dataHelper->setParam("'" . trim($meetingRecording) . "'", "I");
        $dataHelper->setParam("'" . trim($maxSessionsMinutes) . "'", "I");
        $dataHelper->setParam("'" . trim($metaTags) . "'", "I");
        $dataHelper->setParam("'" . trim($emailReminderFlag) . "'", "I");
        $dataHelper->setParam("'" . trim($emailReminderStatus) . "'", "I");
        $dataHelper->setParam("'" . trim($smsReminderFlag) . "'", "I");
        $dataHelper->setParam("'" . trim($smsReminderStatus) . "'", "I");
        $dataHelper->setParam("'" . trim($meetingInstance) . "'", "I");
        $dataHelper->setParam("'" . trim($subscriptionID) . "'", "I");
        $dataHelper->setParam("status", "O");
        $dataHelper->setParam("output", "O");
        $arrInsSchedule = $dataHelper->putRecords("SP", "InsertScheduleDetails");
        $dataHelper->clearParams();
        return $arrInsSchedule;
    }
    catch (Exception $e)
    {
        throw new Exception("partner_api_db_function.inc.php : insScheduleDetails : Could not insert records : " . $e->getMessage(), 5081);
    }
}

/* -----------------------------------------------------------------------------
  Function Name : insInvitationDetails
  Purpose       : To insert invitation details in the invitation_details table.
  Parameters    : invitation_id, schedule_id, invitee_email_address, invitee_nick_name, invitee_idd_code, invitee_mobile_number, invitation_creator, invitation_creation_dtm, invitation_status, meeting_status, Datahelper
  Returns       : status
  Calls         : datahelper.putRecords
  Called By     :
  Author        : Mitesh Shah
  Created  on   : 08-July-2015
  Modified By   :
  Modified on   :
  ------------------------------------------------------------------------------ */

function insInvitationDetails($scheduleID, $inviteeEmail, $inviteeNickName, $inviteeIDDCode, $inviteeMobileNumber, $invitationCreator, $invitationCreationDateTime, $dataHelper) {
    try
    {
        if (!is_object($dataHelper))
        {
            throw new Exception("partner_api_db_function.inc.php : insInvitationDetails : DataHelper Object did not instantiate", 104);
        }
        $dataHelper->setParam("'" . trim($scheduleID) . "'", "I");
        $dataHelper->setParam("'" . trim($inviteeEmail) . "'", "I");
        $dataHelper->setParam("'" . trim($inviteeNickName) . "'", "I");
        $dataHelper->setParam("'" . trim($inviteeIDDCode) . "'", "I");
        $dataHelper->setParam("'" . trim($inviteeMobileNumber) . "'", "I");
        $dataHelper->setParam("'" . trim($invitationCreator) . "'", "I");
        $dataHelper->setParam("'" . trim($invitationCreationDateTime) . "'", "I");
        $dataHelper->setParam("status", "O");
        $arrInsSchedule = $dataHelper->putRecords("SP", "InsertInvitationDetails");
        $dataHelper->clearParams();
        return $arrInsSchedule;
    }
    catch (Exception $e)
    {
        throw new Exception("partner_api_db_function.inc.php : insInvitationDetails : Could not insert records : " . $e->getMessage(), 5091);
    }
}

/* -----------------------------------------------------------------------------
  Function Name : isScheduleIdValid
  Purpose       : To Validate Schedule Id of the meeting from schedule_details table
  Parameters    : schedule_id, passcode(md5(schedule_id:secret_key)), MEETING_START_GRACE_INTERVAL, MEETING_END_GRACE_INTERVAL, Datahelper
  Returns       : array (with schedule_id, schedule_status, meeting_timestamp_gmt)
  Calls         : datahelper.fetchRecords
  Called By     :
  Author        : Mitesh Shah
  Created  on   : 08-July-2015
  Modified By   :
  Modified on   :
  ------------------------------------------------------------------------------ */

function isScheduleIdValid($schedule_id, $dataHelper) {
    try
    {
        if (strlen(trim($schedule_id)) <= 0)
        {
            throw new Exception("partner_api_db_function.inc.php: isScheduleIdValid : Missing Parameter schedule_id.", 2021);
        }

        if (!is_object($dataHelper))
        {
            throw new Exception("partner_api_db_function.inc.php : isScheduleIdValid : DataHelper Object did not instantiate", 104);
        }

        $strSqlStatement = "SELECT schedule_id, schedule_status, meeting_timestamp_gmt, meeting_timestamp_local, meeting_title, " .
                "meeting_timezone, meeting_gmt, user_details.user_id, email_address, nick_name, subscription_master.subscription_id, subscription_master.number_of_invitee " .
                "FROM schedule_details, user_details, subscription_master " .
                "WHERE schedule_details.user_id = user_details.user_id " .
                "AND schedule_details.subscription_id = subscription_master.subscription_id " .
                "AND schedule_id='" . trim($schedule_id) . "'";
        $arrResult = $dataHelper->fetchRecords("QR", $strSqlStatement);
        $dataHelper->clearParams();
        return $arrResult;
    }
    catch (Exception $e)
    {
        throw new Exception("partner_api_db_function.inc.php : isScheduleIdValid : Could not fetch records : " . $e->getMessage(), 2022);
    }
}

/* -----------------------------------------------------------------------------
  Function Name : cancelScheduleMeeting
  Purpose       : Update schedule_status = 3(Cancelled) for the schedule_id in schedule_details table
  Parameters    : schedule_id, schedule_status, gmt_datetime(GMT), Datahelper
  Returns       : status(0,1,2)
  Calls         : datahelper.fetchRecords
  Called By     : cancelScheduleMeeting.php
  Author        : Mitesh Shah
  Created  on   : 08-July-2015
  Modified By   :
  Modified on   :
  ------------------------------------------------------------------------------ */

function cancelScheduleMeeting($schedule_id, $schedule_status, $gmt_datetime, $dataHelper) {
    try
    {
        if (strlen(trim($schedule_id)) <= 0)
        {
            throw new Exception("partner_api_db_function.inc.php: cancelScheduleMeeting : Missing Parameter schedule_id.", 2071);
        }

        if (strlen(trim($schedule_status)) <= 0)
        {
            throw new Exception("api_function.inc.php: cancelScheduleMeeting : Missing Parameter schedule_status.", 2072);
        }

        if (strlen(trim($gmt_datetime)) <= 0)
        {
            throw new Exception("partner_api_db_function.inc.php: cancelScheduleMeeting : Missing Parameter schedule_status.", 2073);
        }

        if (!is_object($dataHelper))
        {
            throw new Exception("partner_api_db_function.inc.php : cancelScheduleMeeting : DataHelper Object did not instantiate", 104);
        }

        $dataHelper->setParam("'" . trim($schedule_id) . "'", "I");
        $dataHelper->setParam("'" . trim($schedule_status) . "'", "I");
        $dataHelper->setParam("'" . trim($gmt_datetime) . "'", "I");
        $dataHelper->setParam("result", "O");
        $arrCancelSchedule = $dataHelper->putRecords("SP", "CancelSchedule");
        $dataHelper->clearParams();
        return $arrCancelSchedule;
    }
    catch (Exception $e)
    {
        throw new Exception("partner_api_db_function.inc.php : cancelScheduleMeeting : Could not update schedule status : " . $e->getMessage(), 2074);
    }
}

/* -----------------------------------------------------------------------------
  Function Name : getFutureMeetingList
  Purpose       : To get future scheduled meeting details.
  Parameters    : user_id, Datahelper
  Returns       : Array
  Calls         : datahelper.fetchRecords
  Called By     : partner_api/getScheduleMeetings.php
  Author        : Mitesh Shah
  Created  on   : 08-July-2015
  Modified By   :
  Modified on   :
  ------------------------------------------------------------------------------ */

function getFutureMeetingList($user_id, $dataHelper) {
    if (!is_object($dataHelper))
    {
        throw new Exception("partner_api_db_function.inc.php : getFutureMeetingList : DataHelper Object did not instantiate", 104);
    }
    if (strlen(trim($user_id)) <= 0)
    {
        throw new Exception("partner_api_db_function.inc.php: getFutureMeetingList : Missing Parameter user_id.", 2071);
    }
    try
    {
        echo $strSqlStatement = "SELECT schedule_id, user_id, schedule_status, schedule_creation_time, schedule_status_update_time, meeting_timestamp_gmt, meeting_timestamp_local, meeting_title, meeting_agenda, meeting_timezone, meeting_gmt, bbb_create_time, meeting_start_time, meeting_end_time, attendee_password, moderator_password, welcome_message, voice_bridge, web_voice, max_participants, record_flag, meeting_duration, meta_tags, email_reminder_flag, email_reminder_status, sms_reminder_flag, sms_reminder_status, bbb_message, meeting_instance, subscription_id FROM schedule_details WHERE user_id = '" . $user_id . "' AND meeting_timestamp_gmt >= '" . GM_DATE . "' AND schedule_status IN ('0','1') ORDER BY meeting_timestamp_gmt ASC";
        $arrResult = $dataHelper->fetchRecords("QR", $strSqlStatement);
        return $arrResult;
    }
    catch (Exception $e)
    {
        throw new Exception("partner_api_db_function.inc.php : Fetch Schedule Meeting List Failed : " . $e->getMessage(), 1105);
    }
}

/* -----------------------------------------------------------------------------
  Function Name : getArchiveMeetingList
  Purpose       : To get archived meeting details.
  Parameters    : user_id, Datahelper
  Returns       : Array
  Calls         : datahelper.fetchRecords
  Called By     : partner_api/getScheduleMeetings.php
  Author        : Mitesh Shah
  Created  on   : 08-July-2015
  Modified By   :
  Modified on   :
  ------------------------------------------------------------------------------ */

function getArchiveMeetingList($user_id, $dataHelper) {
    if (!is_object($dataHelper))
    {
        throw new Exception("partner_api_db_function.inc.php : getArchiveMeetingList : DataHelper Object did not instantiate", 104);
    }
    if (strlen(trim($user_id)) <= 0)
    {
        throw new Exception("partner_api_db_function.inc.php: getArchiveMeetingList : Missing Parameter user_id.", 2071);
    }
    try
    {
        $strSqlStatement = "SELECT schedule_id, user_id, schedule_status, schedule_creation_time, schedule_status_update_time, meeting_timestamp_gmt, meeting_timestamp_local, meeting_title, meeting_agenda, meeting_timezone, meeting_gmt, bbb_create_time, meeting_start_time, meeting_end_time, attendee_password, moderator_password, welcome_message, voice_bridge, web_voice, max_participants, record_flag, meeting_duration, meta_tags, email_reminder_flag, email_reminder_status, sms_reminder_flag, sms_reminder_status, bbb_message, meeting_instance, subscription_id FROM schedule_details WHERE user_id = '" . $user_id . "' AND meeting_timestamp_gmt < '" . GM_DATE . "' ORDER BY meeting_timestamp_gmt DESC";
        $arrResult = $dataHelper->fetchRecords("QR", $strSqlStatement);
        return $arrResult;
    }
    catch (Exception $e)
    {
        throw new Exception("partner_api_db_function.inc.php : Fetch Archive Meeting List Failed : " . $e->getMessage(), 1105);
    }
}
