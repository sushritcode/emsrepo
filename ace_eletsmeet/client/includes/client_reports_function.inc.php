<?php

/* -------------------------------------------------------------------------------
  Function Name : getUserListByPartnernClient
  Purpose       : To get user details by client_id, partner_id from user_details table.
  Parameters    : client_id, partner_id, Datahelper
  Returns       : array (with user details)
  Calls         : datahelper.fetchRecords
  Called By     : index.php(User)
  Author        : Mitesh Shah
  Created  on   : 25-May-2015
  Modified By   :
  Modified on   :
  -------------------------------------------------------------------------------- */

function getUserListByPartnernClient($partner_id, $client_id, $dataHelper) {
    if (!is_object($dataHelper))
    {
        throw new Exception("client_db_function.inc.php : getUserListByPartnernClient : DataHelper Object did not instantiate", 104);
    }

    try
    {
$strSqlQuery = "SELECT uld.user_id, uld.user_name, uld.client_id, cld.client_name, pd.partner_id, pd.partner_name, uld.email_address, uld.login_enabled, ud.nick_name, ud.first_name, ud.last_name, ud.country_name, ud.timezones, ud.gmt, ud.phone_number, ud.idd_code, ud.mobile_number, uld.created_on, uld.role "
. "FROM user_details AS ud, user_login_details AS uld, client_login_details AS cld, client_details AS cd , partner_details AS pd "
. "WHERE uld.user_id = ud.user_id "
. "AND uld.partner_id = pd.partner_id "
. "AND uld.client_id = cld.client_id "
. "AND cld.client_id = cd.client_id "
. "AND cld.client_login_enabled = '1' "
. "AND uld.partner_id = '" . trim($partner_id) . "' "
. "AND uld.client_id = '" . trim($client_id) . "' "
. "ORDER BY ud.first_name, ud.last_name, ud.nick_name, uld.email_address;";
        $arrResult = $dataHelper->fetchRecords("QR", $strSqlQuery);
        return $arrResult;
    }
    catch (Exception $e)
    {
        throw new Exception("client_db_function.inc.php : Error in getUserListByPartnernClient." . $e->getMessage(), 734);
    }
}

/* -----------------------------------------------------------------------------
  Function Name : getSumOfClientLicenseByType
  Purpose       : To Get License Details from client_license_details Table
  Parameters    : Datahelper
  Returns       : array (with Plan details)
  Calls         : datahelper.fetchRecords
  Called By     :
  Author        : Mitesh Shah
  Created  on   : 25-May-2015
  Modified By   :
  Modified on   :
  ------------------------------------------------------------------------------ */

function getSumOfClientLicenseByType($client_id, $opt_type, $dataHelper) {
    if (!is_object($dataHelper))
    {
        throw new Exception("client_db_function.inc.php : getSumOfClientLicenseByType : DataHelper Object did not instantiate", 104);
    }

    try
    {
$strSqlQuery = "SELECT IFNULL(SUM(no_of_license),0) AS LicenseSum FROM client_license_details AS ld, client_login_details AS cld, client_details AS cd WHERE ld.client_id = cld.client_id AND cld.client_id = cd.client_id AND client_login_enabled = '1'  AND operation_type = '".trim($opt_type)."' AND ld.client_id ='" . trim($client_id) . "';";
        $arrResult = $dataHelper->fetchRecords("QR", $strSqlQuery);
        return $arrResult[0]['LicenseSum'];
    }
    catch (Exception $e)
    {
        throw new Exception("client_db_function.inc.php : Error in getting License details." . $e->getMessage(), 734);
    }
}

/* -----------------------------------------------------------------------------
  Function Name : getTotalConsumedLicenseByClientId
  Purpose       : To Get Total Number of User by Client from user_details Table
  Parameters    : Datahelper
  Returns       : array (with Plan details)
  Calls         : datahelper.fetchRecords
  Called By     :
  Author        : Mitesh Shah
  Created  on   : 25-May-2015
  Modified By   :
  Modified on   :
  ------------------------------------------------------------------------------ */

function getTotalConsumedLicenseByClientId($client_id, $dataHelper) {
    if (!is_object($dataHelper))
    {
        throw new Exception("client_db_function.inc.php : getTotalConsumedLicenseByClientId : DataHelper Object did not instantiate", 104);
    }

    try
    {
$strSqlQuery = "SELECT COUNT(*) AS ConsumedLicense FROM user_login_details AS uld, user_details AS ud, client_login_details AS cld, client_details AS cd WHERE uld.user_id = ud.user_id AND login_enabled !='3'  AND cld.client_id = cd.client_id AND client_login_enabled = '1' AND uld.client_id = '" . trim($client_id) . "';";
        $arrResult = $dataHelper->fetchRecords("QR", $strSqlQuery);
        return $arrResult;
    }
    catch (Exception $e)
    {
        throw new Exception("client_db_function.inc.php : Error in getting Plan details." . $e->getMessage(), 734);
    }
}

/* -----------------------------------------------------------------------------
  Function Name : getMeetingDurationByClient
  Purpose       : To get duration of meetingsfrom partner_details, client_details, user_details, schedule_details table.
  Parameters    : Datahelper
  Returns       : array (with country details)
  Calls         : datahelper.fetchRecords
  Called By     :
  Author        : Mitesh Shah
  Created  on   : 25-May-2015
  Modified By   :
  Modified on   :
  ------------------------------------------------------------------------------ */

function getMeetingCountNDurationByClient($client_id, $dataHelper) {
    if (!is_object($dataHelper))
    {
        throw new Exception("client_db_function.inc.php : getMeetingDurationByClient : DataHelper Object did not instantiate", 104);
    }

    try
    {
$strSqlQuery = "SELECT pd.partner_id, pd.partner_name, cld.client_id, cld.client_name, COUNT(uld.client_id) AS 'TotalMeetings', "
. "SUM(IFNULL(TIMESTAMPDIFF(MINUTE, sd.meeting_start_time, sd.meeting_end_time),0)) AS 'TotalDuration' "
. "FROM  partner_details AS pd, client_login_details AS cld, client_details AS cd, user_login_details AS uld, schedule_details AS sd  "
. "WHERE pd.partner_id = cld.partner_id AND cld.client_id = cd.client_id AND cld.client_id = uld.client_id "
. "AND client_login_enabled = '1' AND uld.user_id = sd.user_id AND cld.client_id = '" . trim($client_id) . "' "
. "GROUP BY pd.partner_id, uld.client_id ORDER BY pd.partner_id , cld.client_name "; 
        $arrResult = $dataHelper->fetchRecords("QR", $strSqlQuery);
        return $arrResult;
    }
    catch (Exception $e)
    {
        throw new Exception("client_db_function.inc.php : Error in getMeetingDurationByClient." . $e->getMessage(), 734);
    }
}

/* -----------------------------------------------------------------------------
  Function Name : getMeetingCountByClientUser
  Purpose       : To get number of meetings by client's user from partner_details, client_details, user_details, schedule_details table.
  Parameters    : Datahelper
  Returns       : array (with country details)
  Calls         : datahelper.fetchRecords
  Called By     :
  Author        : Mitesh Shah
  Created  on   : 25-May-2015
  Modified By   :
  Modified on   :
  ------------------------------------------------------------------------------ */

function getMeetingCountByClientUser($client_id, $dataHelper) {
    if (!is_object($dataHelper))
    {
        throw new Exception("client_db_function.inc.php : getMeetingCountByClientUser    : DataHelper Object did not instantiate", 104);
    }

    try
    {
$strSqlQuery = "SELECT pd.partner_id, cld.client_id, cld.client_name, uld.user_id, uld.user_name, uld.email_address, COUNT(uld.user_id) AS 'TotalMeetings', "
. "SUM(IFNULL(TIMESTAMPDIFF(MINUTE,sd.meeting_start_time, sd.meeting_end_time),0)) AS 'TotalDuration' "
. "FROM partner_details AS pd, client_login_details AS cld, client_details AS cd, user_login_details AS uld, schedule_details AS sd "
. "WHERE sd.schedule_id IN (SELECT schedule_id FROM invitation_details) "
. "AND pd.partner_id = cld.partner_id AND cld.client_id = cd.client_id AND cld.client_id = uld.client_id AND uld.user_id = sd.user_id "
. "AND cld.client_id = '" . trim($client_id) . "' GROUP BY pd.partner_id, uld.client_id, uld.user_id ORDER BY uld.user_name;"; 
        $arrResult = $dataHelper->fetchRecords("QR", $strSqlQuery);
        return $arrResult;
    }
    catch (Exception $e)
    {
        throw new Exception("client_db_function.inc.php : Error in getMeetingCountByClientUser." . $e->getMessage(), 734);
    }
}

function getMeetingListByUserId($user_id, $dataHelper) {
    if (!is_object($dataHelper))
    {
        throw new Exception("client_db_function.inc.php : getMeetingListByUserId    : DataHelper Object did not instantiate", 104);
    }

    try
    {
        $strSqlQuery = "SELECT * FROM schedule_details WHERE user_id ='".trim($user_id)."' ORDER BY meeting_timestamp_local DESC;"; 
        $arrResult = $dataHelper->fetchRecords("QR", $strSqlQuery);
        return $arrResult;
    }
    catch (Exception $e)
    {
        throw new Exception("client_db_function.inc.php : Error in getMeetingListByUserId." . $e->getMessage(), 734);
    }
}

/* -----------------------------------------------------------------------------
  Function Name : getUserDetailsByUserID
  Purpose       : To get user details for profile update.
  Parameters    : user_id, Datahelper
  Returns       : array (with user details)
  Calls         : datahelper.fetchRecords
  Called By     :
  Author        : Mitesh Shah
  Created  on   : 25-May-2015
  Modified By   :
  Modified on   :
  ------------------------------------------------------------------------------ */

function getUserDetailsByUserId($user_id, $dataHelper) {
    
    if (!is_object($dataHelper))
    {
        throw new Exception("client_db_function.inc.php : getUserDetailsByUserId : DataHelper Object did not instantiate", 104);
    }

    if (strlen(trim($user_id)) <= 0)
    {
        throw new Exception("client_db_function.inc.php : getUserDetailsByUserId : Missing Parameter user_id.", 141);
    }

    try
    {
        $dataHelper->setParam("'" . $user_id . "'", "I");
        $arrUserDetails = $dataHelper->fetchRecords("SP", 'GetUserDetailsByUserId');
        $dataHelper->clearParams();
        return $arrUserDetails;
    }
    catch (Exception $e)
    {
        throw new Exception(" client_db_function.inc.php : getUserDetailsByUserId : Failed : " . $e->getMessage(), 145);
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
                . "FROM schedule_details AS sd, user_login_details AS uld, user_details AS ud, subscription_master AS sm "
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

function getClientSubscriptionInfo($partner_id, $client_id, $dataHelper) {
    if (!is_object($dataHelper))
    {
        throw new Exception("report_function.inc.php : getClientSubscriptionInfo : DataHelper Object did not instantiate", 104);
    }

    try
    {
       $strSqlQuery = "SELECT pd.partner_name, cld.client_name, cld.client_id, csm.client_subscription_id, csm.order_id, csm.plan_id, csm.plan_name, csm.subscription_start_date_gmt, "
. "csm.subscription_end_date_gmt, DATEDIFF(csm.subscription_end_date_gmt, DATE_FORMAT(NOW(), '%Y-%m-%d')) AS diff_days, csm.subscription_status  "
. "FROM partner_details AS pd, client_subscription_master AS csm, client_login_details AS cld, client_details AS cd "
. "WHERE pd.partner_id = cld.partner_id AND cld.client_id = csm.client_id "
."AND cld.client_id = cd.client_id AND client_login_enabled = '1' "      
. "AND cld.client_id = '" . trim($client_id) . "' "
. "AND pd.partner_id = '" . trim($partner_id) . "' "
. "ORDER BY csm.subscription_status, csm.subscription_end_date_gmt DESC;";
        $arrResult = $dataHelper->fetchRecords("QR", $strSqlQuery);
        return $arrResult;
    }
    catch (Exception $e)
    {
        throw new Exception("report_function.inc.php : Error in getClientSubscriptionInfo." . $e->getMessage(), 734);
    }
}

function getSubscriptionAssignInfo($order_id, $dataHelper) {
    if (!is_object($dataHelper))
    {
        throw new Exception("report_function.inc.php : getClientSubscriptionInfo : DataHelper Object did not instantiate", 104);
    }

    try
    {
        $strSqlQuery = "SELECT sm.subscription_id, sm.user_id, sm.subscription_date, sm.subscription_start_date_gmt, sm.subscription_end_date_gmt, sm.subscription_start_date_local, sm.subscription_end_date_local,  sm.subscription_status,  sm.plan_name, uld.user_name "
."FROM subscription_master AS sm, user_login_details AS uld "
."WHERE order_id = '".trim($order_id)."' AND sm.user_id = uld.user_id;";
        $arrResult = $dataHelper->fetchRecords("QR", $strSqlQuery);
        return $arrResult;
    }
    catch (Exception $e)
    {
        throw new Exception("report_function.inc.php : Error in getClientSubscriptionInfo." . $e->getMessage(), 734);
    }
}

function getPlanDetailsByPlanId($plan_id, $dataHelper)
{
    if (!is_object($dataHelper))
    {
        throw new Exception("subscribe_function.inc.php : getPlanDetailsById : DataHelper Object did not instantiate", 104);
    }

    try
    {
        $strSqlQuery = "SELECT * FROM plan_details WHERE plan_id = '" . trim($plan_id) . "' ";
        $arrResult = $dataHelper->fetchRecords("QR", $strSqlQuery);
        return $arrResult;
    }
    catch (Exception $e)
    {
        throw new Exception("subscribe_function.inc.php : Error in getting Plan details." . $e->getMessage(), 734);
    }
}

function getClientUserSubscriptionDetails($client_id, $dataHelper) {
    if (!is_object($dataHelper))
    {
        throw new Exception("client_db_function.inc.php : getSubscriptionDetailsByUserId : DataHelper Object did not instantiate", 104);
    }

    try
    {
        $strSqlQuery = "SELECT sm.user_id, plan_name, subscription_start_date_local, subscription_end_date_local, subscription_status 
FROM subscription_master AS sm, client_login_details AS cld, client_details AS cd, user_login_details AS uld, user_details AS ud
WHERE sm.user_id = uld.user_id  
AND uld.user_id = ud.user_id 
AND uld.client_id = cld.client_id 
AND cld.client_id = cd.client_id 
AND client_login_enabled = '1' 
AND cld.client_id='" . trim($client_id) . " 
ORDER BY subscription_end_date_local DESC;";
        $arrResult = $dataHelper->fetchRecords("QR", $strSqlQuery);
        return $arrResult;
    }
    catch (Exception $e)
    {
        throw new Exception("client_db_function.inc.php : Error in getting Plan details." . $e->getMessage(), 734);
    }
}
