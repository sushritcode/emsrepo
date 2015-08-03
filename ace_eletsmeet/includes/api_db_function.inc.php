<?php

/* -----------------------------------------------------------------------------
  Function Name : isAuthenticateScheduleUser
  Purpose       : To authenticate subscription of user for schedule the meeting
  Parameters    : user_id, client_id, Datahelper
  Returns       : array (with status, message)
  Calls         : datahelper.fetchRecords
  Called By     :
  Author        : Mitesh Shah
  Created  on   : 16-June-2012
  Modified By   :
  Modified on   :
  ------------------------------------------------------------------------------ */
//
//function isAuthenticateScheduleUser($user_id, $client_id, $dataHelper) {
//    try
//    {
//        if (strlen(trim($user_id)) <= 0)
//        {
//            throw new Exception("api_function.inc.php: isScheduleValid : Missing Parameter user_id.", 2011);
//        }
//
//        if (strlen(trim($client_id)) <= 0)
//        {
//            throw new Exception("api_function.inc.php: isScheduleValid : Missing Parameter client_id.", 2012);
//        }
//
//        if (!is_object($dataHelper))
//        {
//            throw new Exception("api_function.inc.php : isAuthenticateScheduleUser : DataHelper Object did not instantiate", 104);
//        }
//
//        $strSqlStatement = "SELECT user_details.user_id, user_details.status as user_status, client_details.client_id, client_details.status as client_status " .
//                "FROM user_details, client_details " .
//                "WHERE user_details.status= '1' " .
//                "AND client_details.client_id = user_details.client_id " .
//                "AND client_details.status= '1' " .
//                "AND user_details.user_id = '" . trim($user_id) . "' " .
//                "AND client_details.client_id = '" . trim($client_id) . "'";
//        $arrAuthSchResult = $dataHelper->fetchRecords("QR", $strSqlStatement);
//        $dataHelper->clearParams();
//        return $arrAuthSchResult;
//    }
//    catch (Exception $e)
//    {
//        throw new Exception("api_function.inc.php : isAuthenticateScheduleUser : Could not fetch records : " . $e->getMessage(), 2013);
//    }
//}

/* -----------------------------------------------------------------------------
  Function Name : isScheduleValid
  Purpose       : To Validate Schedule Id of the meeting from schedule_details table
  Parameters    : schedule_id, passcode(md5(schedule_id:secret_key)), MEETING_START_GRACE_INTERVAL, MEETING_END_GRACE_INTERVAL, Datahelper
  Returns       : array (with schedule_id, schedule_status, meeting_timestamp_gmt)
  Calls         : datahelper.fetchRecords
  Called By     :
  Author        : Mitesh Shah
  Created  on   : 16-June-2012
  Modified By   :
  Modified on   :
  ------------------------------------------------------------------------------ */

function isScheduleValid($schedule_id, $dataHelper) {
    try
    {
        if (strlen(trim($schedule_id)) <= 0)
        {
            throw new Exception("api_function.inc.php: isScheduleValid : Missing Parameter schedule_id.", 2021);
        }

        if (!is_object($dataHelper))
        {
            throw new Exception("api_function.inc.php : isScheduleValid : DataHelper Object did not instantiate", 104);
        }

        $strSqlStatement = "SELECT schedule_id, schedule_status, meeting_timestamp_gmt, meeting_timestamp_local, meeting_title, " .
                "meeting_timezone, meeting_gmt, user_details.user_id, email_address, nick_name, subscription_master.subscription_id, subscription_master.number_of_invitee, subscription_master.order_id " .
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
        throw new Exception("api_function.inc.php : isScheduleValid : Could not fetch records : " . $e->getMessage(), 2022);
    }
}

/* -----------------------------------------------------------------------------
  Function Name : isScheduleInviteeValid
  Purpose       : To Validate Schedule Id and Invitee of the meeting from schedule_details and invitation_details table
  Parameters    : schedule_id, passcode(md5(schedule_id:invitee_email_address:secret_key)), invitee_email_address, MEETING_START_GRACE_INTERVAL, MEETING_END_GRACE_INTERVAL, Datahelper
  Returns       : array (with schedule_id, schedule_status, meeting_timestamp_gmt, meeting_title, start_grace_time, end_grace_time, invitee_email_address, invitation_creator, user_id, email_address, client_id)
  Calls         : datahelper.fetchRecords
  Called By     : jm.php and jmx.php
  Author        : Mitesh Shah
  Created  on   : 16-June-2012
  Modified By   :
  Modified on   :
  ------------------------------------------------------------------------------ */

function isScheduleInviteeValid($schedule_id, $passcode, $email_address, $SGInterval, $EGInterval, $dataHelper) {
    try
    {
        if (strlen(trim($schedule_id)) <= 0)
        {
            throw new Exception("api_function.inc.php: isScheduleInviteeValid : Missing Parameter schedule_id.", 2031);
        }

        if (strlen(trim($passcode)) <= 0)
        {
            throw new Exception("api_function.inc.php: isScheduleInviteeValid : Missing Parameter passcode.", 2032);
        }

        if (strlen(trim($email_address)) <= 0)
        {
            throw new Exception("api_function.inc.php: isScheduleInviteeValid : Missing Parameter email_address.", 2033);
        }

        if (strlen(trim($SGInterval)) <= 0)
        {
            throw new Exception("api_function.inc.php: isScheduleInviteeValid : Missing Parameter SGInterval.", 2034);
        }

        if (strlen(trim($EGInterval)) <= 0)
        {
            throw new Exception("api_function.inc.php: isScheduleInviteeValid : Missing Parameter EGInterval.", 2035);
        }

        if (!is_object($dataHelper))
        {
            throw new Exception("api_function.inc.php : isScheduleInviteeValid : DataHelper Object did not instantiate", 104);
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
        throw new Exception("api_function.inc.php : isScheduleValid : Could not fetch records : " . $e->getMessage(), 2036);
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
  Created  on   : 16-June-2012
  Modified By   :
  Modified on   :
  ------------------------------------------------------------------------------ */

function updNewSchedule($schedule_id, $schedule_status, $bbb_create_time, $bbb_message, $gmt_datetime, $dataHelper) {
    try
    {
        if (strlen(trim($schedule_id)) <= 0)
        {
            throw new Exception("api_function.inc.php: updNewSchedule : Missing Parameter schedule_id.", 2041);
        }

        if (strlen(trim($schedule_status)) <= 0)
        {
            throw new Exception("api_function.inc.php: updNewSchedule : Missing Parameter schedule_status.", 2042);
        }

        if (!is_object($dataHelper))
        {
            throw new Exception("api_function.inc.php : updNewSchedule : DataHelper Object did not instantiate", 104);
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
        throw new Exception("api_function.inc.php : updNewSchedule : Could not update schedule details : " . $e->getMessage(), 2043);
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
  Created  on   : 16-June-2012
  Modified By   :
  Modified on   :
  ------------------------------------------------------------------------------ */

function updInviteeStatus($schedule_id, $inv_email_address, $gmt_datetime, $dataHelper) {
    try
    {
        if (strlen(trim($schedule_id)) <= 0)
        {
            throw new Exception("api_function.inc.php: updInviteeStatus : Missing Parameter schedule_id.", 2051);
        }

        if (strlen(trim($inv_email_address)) <= 0)
        {
            throw new Exception("api_function.inc.php: updInviteeStatus : Missing Parameter inv_email_address.", 2052);
        }

        if (!is_object($dataHelper))
        {
            throw new Exception("api_function.inc.php : updInviteeStatus : DataHelper Object did not instantiate", 104);
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
        throw new Exception("api_function.inc.php : updInviteeStatus : Could not update invitation details : " . $e->getMessage(), 2053);
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
  Created  on   : 16-June-2012
  Modified By   :
  Modified on   :
  ------------------------------------------------------------------------------ */

function updInvitationStatus($schedule_id, $invitation_status, $inv_email_address, $gmt_datetime, $dataHelper) {
    try
    {
        if (strlen(trim($schedule_id)) <= 0)
        {
            throw new Exception("api_function.inc.php: updInvitationStatus : Missing Parameter schedule_id.", 2061);
        }

        if (strlen(trim($invitation_status)) <= 0)
        {
            throw new Exception("api_function.inc.php: updInvitationStatus : Missing Parameter invitation_status.", 2062);
        }

        if (strlen(trim($inv_email_address)) <= 0)
        {
            throw new Exception("api_function.inc.php: updInvitationStatus : Missing Parameter inv_email_address.", 2063);
        }

        if (!is_object($dataHelper))
        {
            throw new Exception("api_function.inc.php : updInvitationStatus : DataHelper Object did not instantiate", 104);
        }

        $strSqlStatement = "UPDATE invitation_details SET invitation_status = '" . trim($invitation_status) . "', " .
                "invitation_status_dtm = '" . trim($gmt_datetime) . "' " .
                "WHERE schedule_id = '" . trim($schedule_id) . "' " .
                "AND invitee_email_address = '" . trim($inv_email_address) . "' " .
                "AND meeting_status = '0'";
        $UpdResult = $dataHelper->putRecords('QR', $strSqlStatement);
        $dataHelper->clearParams();
        return $UpdResult;
    }
    catch (Exception $e)
    {
        throw new Exception("api_function.inc.php : updInvitationStatus : Could not update invitation status : " . $e->getMessage(), 2064);
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
  Created  on   : 16-June-2012
  Modified By   :
  Modified on   :
  ------------------------------------------------------------------------------ */

function cancelSchedule($schedule_id, $schedule_status, $gmt_datetime, $dataHelper) {
    try
    {
        if (strlen(trim($schedule_id)) <= 0)
        {
            throw new Exception("api_function.inc.php: cancelSchedule : Missing Parameter schedule_id.", 2071);
        }

        if (strlen(trim($schedule_status)) <= 0)
        {
            throw new Exception("api_function.inc.php: cancelSchedule : Missing Parameter schedule_status.", 2072);
        }

        if (strlen(trim($gmt_datetime)) <= 0)
        {
            throw new Exception("api_function.inc.php: cancelSchedule : Missing Parameter schedule_status.", 2073);
        }

        if (!is_object($dataHelper))
        {
            throw new Exception("api_function.inc.php : cancelSchedule : DataHelper Object did not instantiate", 104);
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
        throw new Exception("api_function.inc.php : cancelSchedule : Could not update schedule status : " . $e->getMessage(), 2074);
    }
}

function getUserConcurrentSessions($user_id, $subscription_id, $gmt_datetime, $dataHelper) {
    if (!is_object($dataHelper))
    {
        throw new Exception("sch_function.inc.php : scheduledPlans : DataHelper Object did not instantiate", 104);
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
        throw new Exception("api_function.inc.php : getUserConcurrentSessions : Could not fetch records : " . $e->getMessage(), 2081);
    }
}