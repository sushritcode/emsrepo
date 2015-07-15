<?php

/* -----------------------------------------------------------------------------
  Function Name : getGroupList
  Purpose       : To Get Contact Group List from contacts_details Table
  Parameters    : contact_owner, Datahelper
  Returns       : array (with DISTINCT contact_group_name)
  Calls         : datahelper.fetchRecords
  Called By     :
  Author        : Mitesh Shah
  Created  on   : 13-June-2012
  Modified By   :
  Modified on   :
  ------------------------------------------------------------------------------- */

function getGroupList($strCK_user_id, $strCk_client_id, $dataHelper)
{
    if (!is_object($dataHelper))
    {
        throw new Exception("db_common_function.inc.php : getContactList : DataHelper Object did not instantiate", 104);
    }
    try
    {
        $dataHelper->setParam("'" . $strCK_user_id . "'", "I");
        $dataHelper->setParam("'" . $strCk_client_id . "'", "I");
        $arrGroupList = $dataHelper->fetchRecords("SP", "GetCombinedGroupName");
        $dataHelper->clearParams();
        return $arrGroupList;
    }
    catch (Exception $e)
    {
        throw new Exception("db_common_function.inc.php : $arrGroupList : Could not fetch Group List : " . $e->getMessage(), 1111);
    }
}

/* -----------------------------------------------------------------------------
  Function Name : getContactList
  Purpose       : To Get Contact List from contacts_details Table
  Parameters    : contact_owner, Datahelper
  Returns       : array (with contact_id, contact_nick_name, contact_first_name, contact_last_name, contact_email_address, contact_mobile_number, contact_group_name, contact_type, contact_owner, status)
  Calls         : datahelper.fetchRecords
  Called By     :
  Author        : Mitesh Shah
  Created  on   : 13-June-2012
  Modified By   :
  Modified on   :
  ------------------------------------------------------------------------------ */

function getContactList($strCK_user_id, $strCk_client_id, $dataHelper)
{
    if (!is_object($dataHelper))
    {
        throw new Exception("db_common_function.inc.php : getContactList : DataHelper Object did not instantiate", 104);
    }
    try
    {
        $dataHelper->setParam("'" . $strCK_user_id . "'", "I");
        $dataHelper->setParam("'" . $strCk_client_id . "'", "I");
        $arrContactList = $dataHelper->fetchRecords("SP", "GetCombinedContactList");
        $dataHelper->clearParams();
        return $arrContactList;
    }
    catch (Exception $e)
    {
        throw new Exception("db_common_function.inc.php : $arrContactList : Could not fetch Contact List : " . $e->getMessage(), 1111);
    }
}

/* -----------------------------------------------------------------------------
  Function Name : getMeetingInviteeList
  Purpose       : To Get Meeting Invitee List from invitation_details Table
  Parameters    : contact_owner, Datahelper
  Returns       : array (with invitation_id, schedule_id, invitee_email_address, invitation_creator, invitation_creation_dtm, invitation_status, invitation_status_dtm, meeting_status, meeting_status_join_dtm, meeting_status_left_dtm)
  Calls         : datahelper.fetchRecords
  Called By     :
  Author        : Mitesh Shah
  Created  on   : 13-June-2012
  Modified By   :
  Modified on   :
  ------------------------------------------------------------------------------ */

function getMeetingInviteeList($schedule_id, $dataHelper)
{
    if (!is_object($dataHelper))
    {
        throw new Exception("db_common_function.inc.php : getContactList : DataHelper Object did not instantiate", 104);
    }
    try
    {
        $strSqlStatement = "SELECT invitation_id, schedule_id, invitee_email_address, invitee_nick_name, invitee_idd_code, invitee_mobile_number, invitation_creator, invitation_creation_dtm, invitation_status, invitation_status_dtm, meeting_status, meeting_status_join_dtm, meeting_status_left_dtm FROM invitation_details WHERE schedule_id = '" . trim($schedule_id) . "'";
        $arrMeetingInviteList = $dataHelper->fetchRecords("QR", $strSqlStatement);
        return $arrMeetingInviteList;
    }
    catch (Exception $e)
    {
        throw new Exception("db_common_function.inc.php : getMeetingInviteeList : Could not fetch Invitee List : " . $e->getMessage(), 1111);
    }
}

function timezoneConverter($sType, $timestamp, $timezone)
{
    if ($sType == "N")
    {
        $date = date_create($timestamp, timezone_open("GMT"));
        $t1 = date_format($date, "Y-m-d H:i:s");
        date_timezone_set($date, timezone_open($timezone));
        $t2 = date_format($date, "Y-m-d H:i:s");
    }
    else
    {
        $date = date_create($timestamp, timezone_open($timezone));
        $t2 = date_format($date, "Y-m-d H:i:s");
        $gD = date_timezone_set($date, timezone_open("GMT"));
        $t1 = date_format($gD, "Y-m-d H:i:s");
    }
    return $t1 . SEPARATOR . $t2;
}

function dateFormat($gmTime, $localTime, $timezone)
{
    $date = date_create($localTime, timezone_open("GMT"));
    $date_format = date_format(date_timezone_set($date, timezone_open($timezone)), 'P');
    $meeting_date = date("D, F jS Y, h:i A", strtotime($localTime)) . " " . $timezone . ", GMT " . $date_format . " (" . date("D, F jS Y, h:i A", strtotime($gmTime)) . " GMT)";
    return $meeting_date;
}

/* -----------------------------------------------------------------------------
  Function Name : userDetailsByUserId
  Purpose       :
  Parameters    :
  Returns       :
  Calls         :  datahelper.fetchRecords
  Called By     : createSchedule.php
  ------------------------------------------------------------------------------ */

function userDetailsByUserId($strCK_user_id, $dataHelper)
{
    if (!is_object($dataHelper))
    {
        throw new Exception("db_common_function.inc.php : userDetailsByUserId : DataHelper Object did not instantiate", 104);
    }
    try
    {
        $dataHelper->setParam("'" . $strCK_user_id . "'", "I");
        $arrUserDetails = $dataHelper->fetchRecords("SP", "GetUserDetailsByUserId");
        $dataHelper->clearParams();
        return $arrUserDetails;
    }
    catch (Exception $e)
    {
        throw new Exception("db_common_function.inc.php : $arrUserDetails : Could not fetch User Details : " . $e->getMessage(), 1111);
    }
}

function getRoundtableInstance($Load, $Schedule_ID, $StartTime, $EndTime)
{
    try
    {
        if (strlen(trim($Load)) <= 0)
        {
            throw new Exception("Missing parameter.");
        }

        if (strlen(trim($Schedule_ID)) <= 0)
        {
            throw new Exception("Missing parameter.");
        }

        if (strlen(trim($StartTime)) <= 0)
        {
            throw new Exception("Missing parameter.");
        }

        if (strlen(trim($EndTime)) <= 0)
        {
            throw new Exception("Missing parameter.");
        }

        $strOutPut = VIDEO_SERVER;
        return $strOutPut;

        //CALL LB Server
        //$DATA = 'load='.$Load.'&meetingid='.$Schedule_ID.'&starttime='.$StartTime.'&endtime='.$EndTime;
        //$objUtilities = new Utilities;
        //$URL = LOAD_BALANCER_SERVER.":".LOAD_BALANCER_SERVER_PORT."/load?".$DATA;
        //$result = $objUtilities->CallScript($URL);
        //$strOutPut = $result;
        //return $strOutPut;
    }
    catch (Exception $e)
    {
        throw new Exception("db_common_function.inc.php : getRoundtableInstance : Error occurred : " . $e->getMessage(), 2004);
    }
}

/* -----------------------------------------------------------------------------
  Function Name : GetCountryDetails
  Purpose       : To Get Country Details from country_details Table
  Parameters    : Datahelper
  Returns       : array (with country details)
  Calls         : datahelper.fetchRecords
  Called By     :
  Author        : Priti Mahajan
  Created  on   : 16-August-2012
  Modified By   :
  Modified on   :
  ------------------------------------------------------------------------------ */

function getCountryDetails($dataHelper)
{
    if (!is_object($dataHelper))
    {
        throw new Exception("db_common_function.inc.php : getCountryDetails : DataHelper Object did not instantiate", 104);
    }

    try
    {
        $strSqlQuery = "SELECT DISTINCT country_id, country_name, country_code, country_idd_code FROM country_details WHERE country_status = '1' ORDER BY country_name;";
        $arrResult = $dataHelper->fetchRecords("QR", $strSqlQuery);
        return $arrResult;
    }
    catch (Exception $e)
    {
        throw new Exception("db_common_function.inc.php : Error in getting Country Details." . $e->getMessage(), 734);
    }
}

/* -----------------------------------------------------------------------------
  Function Name : GetCountryNamebyIdd
  Purpose       : To Get Country Name from country_details Table
  Parameters    : idd_code, Datahelper
  Returns       : array (with DISTINCT country name)
  Calls         : datahelper.fetchRecords
  Called By     :
  Author        : Priti Mahajan
  Created  on   : 16-August-2012
  Modified By   :
  Modified on   :
  ------------------------------------------------------------------------------ */

function getCountryNamebyIdd($idd_code, $dataHelper)
{
    if (!is_object($dataHelper))
    {
        throw new Exception("db_common_function.inc.php : GetCountryNamebyIdd : DataHelper Object did not instantiate", 104);
    }

    if (strlen(trim($idd_code)) <= 0)
    {
        throw new Exception("db_common_function.inc.php : GetCountryNamebyIdd : Missing Parameter idd_code.", 143);
    }

    try
    {
        $strSqlQuery = "SELECT DISTINCT country_name FROM country_details WHERE country_status = '1' AND country_idd_code = '" . trim($idd_code) . "';";
        $arrResult = $dataHelper->fetchRecords("QR", $strSqlQuery);
        return $arrResult;
    }
    catch (Exception $e)
    {
        throw new Exception("db_common_function.inc.php : Error in getting CountryNamebyIdd." . $e->getMessage(), 734);
    }
}

/* -----------------------------------------------------------------------------
  Function Name : updConsumedSessions
  Purpose       : Update consumed_number_of_sessions for the subscription_id and user_id in subscription_master table
  Parameters    : subscription_id, user_id, type(Add or Subtract), Datahelper
  Returns       : status(0,1,2)
  Calls         : datahelper.fetchRecords
  Called By     : cancelschedule.php, createSchedule.php
  Author        : Mitesh Shah
  Created  on   : 16-June-2012
  Modified By   :
  Modified on   :
  ------------------------------------------------------------------------------ */

function updConsumedSessions($subscription_id, $user_id, $type, $dataHelper)
{
    try
    {
        if (strlen(trim($subscription_id)) <= 0)
        {
            throw new Exception("api_function.inc.php: updConsumedSessions : Missing Parameter subscription_id.", 2081);
        }

        if (strlen(trim($user_id)) <= 0)
        {
            throw new Exception("api_function.inc.php: updConsumedSessions : Missing Parameter user_id.", 2082);
        }

        if (strlen(trim($type)) <= 0)
        {
            throw new Exception("api_function.inc.php: updConsumedSessions : Missing Parameter type.", 2083);
        }

        if (!is_object($dataHelper))
        {
            throw new Exception("api_function.inc.php : updConsumedSessions : DataHelper Object did not instantiate", 104);
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
        throw new Exception("api_function.inc.php : updConsumedSessions : Could not update Consumed Sessions : " . $e->getMessage(), 2084);
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
  Created  on   : 6-June-2015
  Modified By   :
  Modified on   :
  ------------------------------------------------------------------------------ */

function getClSubInfoFromUserOrderId($user_order_id, $dataHelper)
{
    try
    {
        if (strlen(trim($user_order_id)) <= 0)
        {
            throw new Exception("api_function.inc.php: getClSubInfoFromUserOrderId : Missing Parameter subscription_id.", 2081);
        }

        if (!is_object($dataHelper))
        {
            throw new Exception("api_function.inc.php : getClSubInfoFromUserOrderId : DataHelper Object did not instantiate", 104);
        }

        $strSqlStatement = "SELECT csm.client_subscription_id,  csm.client_id, csm.order_id FROM client_subscription_master csm,  subscription_master sm "
                . " WHERE csm.order_id = sm.order_id AND sm.order_id = '".trim($user_order_id)."'";
        $arrResult = $dataHelper->fetchRecords("QR", $strSqlStatement);
        return $arrResult;
    }
    catch (Exception $e)
    {
        throw new Exception("api_function.inc.php : getClSubInfoFromUserOrderId : Could not get details : " . $e->getMessage(), 2084);
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
  Created  on   : 6-June-2015
  Modified By   :
  Modified on   :
  ------------------------------------------------------------------------------ */

function updClientConsumedSessions($subscription_id, $client_id, $type, $dataHelper)
{
    try
    {
        if (strlen(trim($subscription_id)) <= 0)
        {
            throw new Exception("api_function.inc.php: updClientConsumedSessions : Missing Parameter subscription_id.", 2081);
        }

        if (strlen(trim($client_id)) <= 0)
        {
            throw new Exception("api_function.inc.php: updClientConsumedSessions : Missing Parameter user_id.", 2082);
        }

        if (strlen(trim($type)) <= 0)
        {
            throw new Exception("api_function.inc.php: updClientConsumedSessions : Missing Parameter type.", 2083);
        }

        if (!is_object($dataHelper))
        {
            throw new Exception("api_function.inc.php : updClientConsumedSessions : DataHelper Object did not instantiate", 104);
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
        throw new Exception("api_function.inc.php : updClientConsumedSessions : Could not update Consumed Sessions : " . $e->getMessage(), 2084);
    }
}

/* -----------------------------------------------------------------------------
  Function Name : currentSession
  Purpose       :
  Parameters    :
  Returns       :
  Calls         : datahelper.fetchRecords
  Called By     : schedule/createSchedule.php
  ------------------------------------------------------------------------------ */

function currentSession($strCK_user_id, $subscriptionId, $gmTime, $dataHelper)
{
    if (!is_object($dataHelper))
    {
        throw new Exception("sch_function.inc.php : scheduledPlans : DataHelper Object did not instantiate", 104);
    }
    try
    {
        $meetingGMT = strtotime($gmTime);
        $beforeTime = date("Y-m-d H:i:s", strtotime("-" . MEETING_START_GRACE_INTERVAL . " minutes", $meetingGMT));
        $afterTime = date("Y-m-d H:i:s", strtotime("+" . MEETING_END_GRACE_INTERVAL . " minutes", $meetingGMT));
        $strSqlStatement = "SELECT schedule_id, schedule_status, meeting_title, meeting_timestamp_gmt, meeting_timestamp_local, meeting_timezone FROM schedule_details WHERE user_id = '" . $strCK_user_id . "' AND schedule_status IN ('0','1') AND subscription_id = '" . $subscriptionId . "' AND meeting_timestamp_gmt >= '" . $beforeTime . "' AND meeting_timestamp_gmt <= '" . $afterTime . "'";
        $arrResult = $dataHelper->fetchRecords("QR", $strSqlStatement);
        return $arrResult;
    }
    catch (Exception $e)
    {
        throw new Exception("sch_function.inc.php : Fetch Schedule Plan Details Failed : " . $e->getMessage(), 1105);
    }
}

/* -----------------------------------------------------------------------------
  Function Name : getPlanDetails
  Purpose       : To Get Plan Details from plan_details Table
  Parameters    : CountryCode, Datahelper
  Returns       : array (with Plan details)
  Calls         : datahelper.fetchRecords
  Called By     :
  Author        : Priti Mahajan
  Created  on   : 11-September-2012
  Modified By   :
  Modified on   :
  ------------------------------------------------------------------------------ */

function getPlanDetails($strCountryCode, $dataHelper)
{
    if (!is_object($dataHelper))
    {
        throw new Exception("db_common_function.inc.php : getPlanDetails : DataHelper Object did not instantiate", 104);
    }

    try
    {
        if (trim($strCountryCode) == 'IN')
        {
            $strSqlQuery = "SELECT plan_id, plan_name, plan_desc, plan_for, plan_type, number_of_sessions, number_of_mins_per_sessions, plan_period, number_of_invitee, meeting_recording, disk_space, is_free, plan_cost_inr, concurrent_sessions, talk_time_mins, plan_status, plan_creation_dtm, plan_keyword, autorenew_flag, display_order, is_multiple FROM plan_details WHERE plan_status = '1' AND (plan_cost_inr > '0' OR is_free = '1') ORDER BY display_order";
            //SELECT * FROM plan_details WHERE plan_status = '1' ORDER BY display_order";
        }
        else
        {
            $strSqlQuery = "SELECT plan_id, plan_name, plan_desc, plan_for, plan_type, number_of_sessions, number_of_mins_per_sessions, plan_period, number_of_invitee, meeting_recording, disk_space, is_free, plan_cost_inr, concurrent_sessions, talk_time_mins, plan_status, plan_creation_dtm, plan_keyword, autorenew_flag, display_order, is_multiple FROM plan_details WHERE plan_status = '1' AND (plan_cost_oth > '0' OR is_free = '1') ORDER BY display_order";
        }

        $arrResult = $dataHelper->fetchRecords("QR", $strSqlQuery);
        return $arrResult;
    }
    catch (Exception $e)
    {
        throw new Exception("db_common_function.inc.php : Error in getting Plan details." . $e->getMessage(), 734);
    }
}

/* -----------------------------------------------------------------------------
  Function Name : getUserSubscriptionHistory
  Purpose       : To Get User Subscription Details from subscription_master, order_master Table
  Parameters    : user_id, Datahelper
  Returns       : array (with subscription and plan details)
  Calls         : datahelper.fetchRecords
  Called By     :
  Author        : Mitesh Shah
  Created  on   : 25-September-2012
  Modified By   :
  Modified on   :
  ------------------------------------------------------------------------------ */

function getUserSubscriptionHistory($user_id, $dataHelper)
{
    if (strlen(trim($user_id)) <= 0)
    {
        throw new Exception("db_common_function.inc.php: getUserSubscriptionHistory : Missing Parameter user_id.", 2082);
    }

    if (!is_object($dataHelper))
    {
        throw new Exception("db_common_function.inc.php : getUserSubscriptionHistory : DataHelper Object did not instantiate", 104);
    }

    try
    {
        $strSqlQuery = "SELECT subscription_id, sm.user_id, subscription_date, subscription_start_date_gmt, subscription_end_date_gmt, subscription_start_date_local, subscription_end_date_local, subscription_status, sm.order_id, payment_id, transaction_id, order_status,order_date, plan_id, plan_name, plan_desc, plan_for, plan_type, number_of_sessions, number_of_mins_per_sessions, plan_period, number_of_invitee, meeting_recording, disk_space, is_free, plan_cost_inr, plan_cost_oth, concurrent_sessions, talk_time_mins, autorenew_flag, consumed_number_of_sessions, consumed_talk_time_mins
FROM subscription_master sm, order_master od WHERE sm.order_id = od.order_id AND sm.user_id = '" . trim($user_id) . "' ORDER BY order_date DESC";
        $arrResult = $dataHelper->fetchRecords("QR", $strSqlQuery);
        $dataHelper->clearParams();
        return $arrResult;
    }
    catch (Exception $e)
    {
        throw new Exception("db_common_function.inc.php : Error in getting User Subscription Details." . $e->getMessage(), 734);
    }
}

/* -----------------------------------------------------------------------------
  Function Name : getUserSubscriptionDetails
  Purpose       : To Get User Subscription Details from subscription_master, order_master Table
  Parameters    : user_id, $gmt_date, Datahelper
  Returns       : array (with subscription and plan details)
  Calls         : datahelper.fetchRecords
  Called By     :
  Author        : Mitesh Shah
  Created  on   : 25-September-2012
  Modified By   :
  Modified on   :
  ------------------------------------------------------------------------------ */

function getUserSubscriptionDetails($user_id, $gmt_date, $dataHelper)
{
    if (strlen(trim($user_id)) <= 0)
    {
        throw new Exception("db_common_function.inc.php: getUserSubscriptionDetails : Missing Parameter user_id.", 2082);
    }

    if (strlen(trim($gmt_date)) <= 0)
    {
        throw new Exception("db_common_function.inc.php: getUserSubscriptionDetails : Missing Parameter user_id.", 2082);
    }

    if (!is_object($dataHelper))
    {
        throw new Exception("db_common_function.inc.php : getUserSubscriptionDetails : DataHelper Object did not instantiate", 104);
    }

    try
    {
        $strSqlQuery = "SELECT subscription_id, sm.user_id, subscription_date, subscription_start_date_gmt, subscription_end_date_gmt, subscription_start_date_local, subscription_end_date_local, subscription_status, sm.order_id, payment_id, transaction_id, order_status,order_date, plan_id, plan_name, plan_desc, plan_for, plan_type, number_of_sessions, number_of_mins_per_sessions, plan_period, number_of_invitee, meeting_recording, disk_space, is_free, plan_cost_inr, plan_cost_oth, concurrent_sessions, talk_time_mins, autorenew_flag, consumed_number_of_sessions, consumed_talk_time_mins
FROM subscription_master sm, order_master od WHERE sm.order_id = od.order_id AND sm.user_id = '" . trim($user_id) . "' AND order_status='completed' AND subscription_end_date_gmt > '" . trim($gmt_date) . "' AND ( (plan_type =  'S' AND consumed_number_of_sessions < number_of_sessions)
OR (plan_type =  'T' AND consumed_talk_time_mins < talk_time_mins) OR (plan_type =  'U')) ORDER BY subscription_end_date_gmt DESC";
        $arrResult = $dataHelper->fetchRecords("QR", $strSqlQuery);
        $dataHelper->clearParams();
        return $arrResult;
    }
    catch (Exception $e)
    {
        throw new Exception("db_common_function.inc.php : Error in getting User Subscription Details." . $e->getMessage(), 734);
    }
}

/* -----------------------------------------------------------------------------
  Function Name : getSchId
  Purpose       : To Get User Subscription Id FROM schedule_details, invitation_details Table
  Parameters    : emailId, vBridge, Datahelper
  Returns       : array (with schedule_id, voice_bridge, invitee_email_address)
  Calls         : datahelper.fetchRecords
  Called By     : getinviteelist.php
  ------------------------------------------------------------------------------ */

function getSchId($emailId, $vBridge, $dataHelper)
{
    if (!is_object($dataHelper))
    {
        throw new Exception("db_common_function.inc.php : getSchId : DataHelper Object did not instantiate", 104);
    }
    try
    {
        $sqlQuery = "SELECT sd.schedule_id,voice_bridge,invitee_email_address FROM schedule_details sd, invitation_details id WHERE sd.schedule_id = id.schedule_id AND voice_bridge = '" . trim($vBridge) . "' AND id.invitee_email_address = '" . trim($emailId) . "'";
        $arrResult = $dataHelper->fetchRecords("QR", $sqlQuery);
        $dataHelper->clearParams();
        return $arrResult;
    }
    catch (Exception $e)
    {
        throw new Exception("db_common_function.inc.php : Error fetching Schedule Id." . $e->getMessage(), 734);
    }
}

/* -----------------------------------------------------------------------------
  Function Name : getSuperAdminDetails
  Purpose       : To get super admin details
  Parameters    : Datahelper
  Returns       : array (with admin_id, email_address)
  Calls         : datahelper.fetchRecords
  Called By     : signup/accpunt_activation.php
  Author        : Priti Mahajan
  Created  on   : 03-April-2013
  Modified By   :
  Modified on   :
  ------------------------------------------------------------------------------ */

function getSuperAdminDetails($dataHelper)
{
    if (!is_object($dataHelper))
    {
        throw new Exception("db_common_function.inc.php : getSuperAdminDetails : DataHelper Object did not instantiate", 104);
    }

    try
    {
        $strSqlStatement = "SELECT admin_id, email_address, password FROM admin_login WHERE status = '1' AND flag = 'S'";
        $arrAuthResult = $dataHelper->fetchRecords("QR", $strSqlStatement);
        return $arrAuthResult;
    }
    catch (Exception $e)
    {
        throw new Exception("db_common_function.inc.php : getSuperAdminDetails : Could not fetch records : " . $e->getMessage(), 144);
    }
}


function getLMInstanceByClientId($client_id, $dataHelper)
{
    if (!is_object($dataHelper))
    {
        throw new Exception("db_common_function.inc.php : getLMInstanceByClientId : DataHelper Object did not instantiate", 104);
    }
    
    try
    {
        $strSqlStatement = "SELECT client_id, partner_id, logout_url, rt_server_name, rt_server_salt, rt_server_api_url, status FROM client_details  WHERE status = '1' AND client_id = '" . trim($client_id) . "'";
        $arrInstanceList = $dataHelper->fetchRecords("QR", $strSqlStatement);
        return $arrInstanceList;
    }
    catch (Exception $e)
    {
        throw new Exception("db_common_function.inc.php : getLMInstanceByClientId : Could not fetch records : " . $e->getMessage(), 1111);
    }
}

/* -----------------------------------------------------------------------------
  Function Name : updInviteeIPHeaders
  Purpose       : Update meeting_joined_ip_address, meeting_joined_headers for the schedule_id in invitation_details table
  Parameters    : schedule_id, invitee_email_address,  Datahelper
  Returns       :
  Calls         : datahelper.putRecords
  Called By     : start.php
  Author        : Mitesh Shah
  Created  on   : 17-June-2015
  Modified By   :
  Modified on   :
  ------------------------------------------------------------------------------ */

function updInviteeIPHeaders($schedule_id, $inv_email_address, $ip_address, $inv_headers, $dataHelper) {
    try
    {
        if (strlen(trim($schedule_id)) <= 0)
        {
            throw new Exception("api_function.inc.php: updInviteeIPHeaders : Missing Parameter schedule_id.", 2051);
        }

        if (strlen(trim($inv_email_address)) <= 0)
        {
            throw new Exception("api_function.inc.php: updInviteeIPHeaders : Missing Parameter inv_email_address.", 2052);
        }

        if (!is_object($dataHelper))
        {
            throw new Exception("api_function.inc.php : updInviteeIPHeaders : DataHelper Object did not instantiate", 104);
        }

        $strSqlStatement ="UPDATE invitation_details SET meeting_joined_ip_address = '" . trim($ip_address) . "', "
                . "meeting_joined_headers = '" . trim($inv_headers) . "' WHERE invitee_email_address = '" . trim($inv_email_address) . "' "
                . "AND schedule_id IN (SELECT schedule_id  FROM schedule_details WHERE schedule_status NOT IN('2','5') AND schedule_id = '" . trim($schedule_id) . "' );";
        $Result = $dataHelper->putRecords('QR', $strSqlStatement);
        $dataHelper->clearParams();
        return $Result;
    }
    catch (Exception $e)
    {
        throw new Exception("api_function.inc.php : updInviteeStatus : Could not update invitation details : " . $e->getMessage(), 2053);
    }
}