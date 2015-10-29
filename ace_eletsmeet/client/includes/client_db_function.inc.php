<?php

function dateFormat($gmTime, $localTime, $timezone) {
    $date = date_create($localTime, timezone_open("GMT"));
    $date_format = date_format(date_timezone_set($date, timezone_open($timezone)), 'P');
    $meeting_date = date("D, F jS Y, h:i A", strtotime($localTime)) . "  (" . $timezone . ", GMT " . $date_format . ")  (" . date("D, F jS Y, h:i A", strtotime($gmTime)) . " GMT)";
    return $meeting_date;
}

/* -----------------------------------------------------------------------------
  Function Name : GetCountryDetails
  Purpose       : To Get Country Details from country_details Table
  Parameters    : Datahelper
  Returns       : array (with country details)
  Calls         : datahelper.fetchRecords
  Called By     :
  Author        : Mitesh Shah
  Created  on   : 25-May-2015
  Modified By   :
  Modified on   :
  ------------------------------------------------------------------------------ */

function getCountryDetails($dataHelper) {
    if (!is_object($dataHelper))
    {
        throw new Exception("client_db_function.inc.php : getCountryDetails : DataHelper Object did not instantiate", 104);
    }

    try
    {
        $strSqlQuery = "SELECT DISTINCT country_id, country_name, country_code, country_idd_code FROM country_details WHERE country_status = '1' ORDER BY country_name;";
        //$strSqlStatement = "SELECT DISTINCT cd.country_id, cd.country_name, cd.country_code, cd.country_idd_code FROM country_details cd, country_timezones ct WHERE cd.country_code = ct.country_code AND cd.country_status = '1' ORDER BY cd.country_name";
        $arrResult = $dataHelper->fetchRecords("QR", $strSqlQuery);
        return $arrResult;
    }
    catch (Exception $e)
    {
        throw new Exception("client_db_function.inc.php : Error in getting Country Details." . $e->getMessage(), 734);
    }
}

/* -----------------------------------------------------------------------------
  Function Name : GetCountryNamebyIdd
  Purpose       : To Get Country Name from country_details Table
  Parameters    : idd_code, Datahelper
  Returns       : array (with DISTINCT country name)
  Calls         : datahelper.fetchRecords
  Called By     :
  Author        : Mitesh Shah
  Created  on   : 25-May-2015
  Modified By   :
  Modified on   :
  ------------------------------------------------------------------------------ */

function getCountryNamebyIdd($idd_code, $dataHelper) {
    if (!is_object($dataHelper))
    {
        throw new Exception("client_db_function.inc.php : GetCountryNamebyIdd : DataHelper Object did not instantiate", 104);
    }

    if (strlen(trim($idd_code)) <= 0)
    {
        throw new Exception("client_db_function.inc.php : GetCountryNamebyIdd : Missing Parameter idd_code.", 143);
    }

    try
    {
        $strSqlQuery = "SELECT DISTINCT country_name FROM country_details WHERE country_status = '1' AND country_idd_code = '" . trim($idd_code) . "';";
        $arrResult = $dataHelper->fetchRecords("QR", $strSqlQuery);
        return $arrResult;
    }
    catch (Exception $e)
    {
        throw new Exception("client_db_function.inc.php : Error in getting CountryNamebyIdd." . $e->getMessage(), 734);
    }
}

function getAllIndustryType($dataHelper) {
    if (!is_object($dataHelper))
    {
        throw new Exception("common_function.inc.php : isUserEmailAddressExists : DataHelper Object did not instantiate", 104);
    }
    try
    {
        $strSqlStatement = "SELECT * FROM industry_details WHERE status like '1' ORDER BY industry_name;";
        $arrIndustryTypes = $dataHelper->fetchRecords("QR", $strSqlStatement);
        return $arrIndustryTypes;
    }
    catch (Exception $e)
    {
        throw new Exception("common_function.inc.php : getAllCompanyType : Could not fetch records : " . $e->getMessage(), 144);
    }
}

function getTimezoneList($dataHelper) {
    if (!is_object($dataHelper))
    {
        throw new Exception("common_function.inc.php : getTimezoneList : DataHelper Object did not instantiate", 104);
    }
    try
    {
        $strSqlStatement = "SELECT cd.country_name, cd.country_code, ct.timezones, ct.gmt FROM country_details cd, country_timezones ct WHERE cd.country_code = ct.country_code AND country_status='1' ORDER BY cd.country_name";
        $arrList = $dataHelper->fetchRecords("QR", $strSqlStatement);
        return $arrList;
    }
    catch (Exception $e)
    {
        throw new Exception("common_function.inc.php : Fetch Time zone Failed : " . $e->getMessage(), 1107);
    }
}

function getClientDetailsByClientId($client_id, $dataHelper) {
    if (!is_object($dataHelper))
    {
        throw new Exception("client_db_function.inc.php : getClientDetailsByClientId : DataHelper Object did not instantiate", 104);
    }
    try
    {
        $strSqlStatement = " SELECT cld.client_id, partner_id, client_username, client_password, client_name, client_email_address, client_logo_flag, client_logo_url, client_last_login_dtm, client_login_ip_address, client_login_random_id, client_login_enabled, client_creation_dtm, client_secret_key, auth_mode, auth_api_url, import_contact_url, rt_server_name, rt_server_salt, rt_server_api_url, logout_url, nick_name, first_name, last_name, secondry_email, landmark, city, address, country_name, timezones, gmt, phone_number, idd_code, mobile_number, industry_type, company_name, nature_business, company_uri, brief_desc_company, facebook, twitter, googleplus, linkedin FROM client_login_details AS cld, client_details cd WHERE cld.client_id = cd.client_id AND client_login_enabled = '1' AND cd.client_id = '" . trim($client_id) . "'";
        //$strSqlStatement = "SELECT cld.client_id, partner_id, client_username, client_name, client_email_address, client_password, client_creation_dtm, client_logo_flag, client_logo_url, client_last_login_dtm, client_login_ip_address, client_login_id, client_login_enabled, nick_name, first_name, last_name, secondry_email, landmark, city, address, country_name, timezones, gmt, phone_number, idd_code, mobile_number, industry_type, company_name, nature_business, company_uri, brief_desc_company, facebook, twitter, googleplus, linkedin FROM client_login_details cld, client_details cd WHERE cld.client_id = cd.client_id AND cld.client_login_enabled = '1' AND cd.client_id = '" . trim($client_id)."'";
        $arrAuthResult = $dataHelper->fetchRecords("QR", $strSqlStatement);
        return $arrAuthResult;
    }
    catch (Exception $e)
    {
        throw new Exception("client_db_function.inc.php : getClientDetailsByClientId : Could not fetch records : " . $e->getMessage(), 144);
    }
}

/* -----------------------------------------------------------------------------
  Function Name : getUserId
  Purpose       : To get user id from user_details table.
  Parameters    : Datahelper
  Returns       : MAX(user_id)
  Calls         : datahelper.fetchRecords
  Called By     : adduser.php(User) for inserting new user in user_details table
  Author        : Mitesh Shah
  Created  on   : 25-May-2015
  Modified By   :
  Modified on   :
  ------------------------------------------------------------------------------ */

function getUserId($dataHelper) {
    if (!is_object($dataHelper))
    {
        throw new Exception("client_db_function.inc.php : getUserId : DataHelper Object did not instantiate", 104);
    }
    try
    {
        $strSqlStatement = "SELECT MAX(user_id) FROM user_login_details";
        $arrMaxId = $dataHelper->fetchRecords("QR", $strSqlStatement);
        $s1 = $arrMaxId[0]['MAX(user_id)'];
        $s2 = explode("usr", $s1);
        $s3 = $s2[1] + 1;
        $s4 = strlen($s3);
        switch ($s4)
        {
            case 1: $userId = "usr000000" . $s3;
                break;
            case 2: $userId = "usr00000" . $s3;
                break;
            case 3: $userId = "usr0000" . $s3;
                break;
            case 4: $userId = "usr000" . $s3;
                break;
            case 5: $userId = "usr00" . $s3;
                break;
            case 6: $userId = "usr0" . $s3;
                break;
            case 7: $userId = "usr" . $s3;
                break;
            default: break;
        }
    }
    catch (Exception $e)
    {
        throw new Exception("client_db_function.inc.php : Get User Details Failed : " . $e->getMessage(), 1111);
    }
    return $userId;
}

/* -----------------------------------------------------------------------------
  Function Name : isUserEmailExists
  Purpose       : To check whether user email address exists.
  Parameters    : email_address, Datahelper
  Returns       : array (with STATUS)
  Calls         : datahelper.fetchRecords
  Called By     : adduser.php(User)
  Author        : Mitesh Shah
  Created  on   : 25-May-2015
  Modified By   :
  Modified on   :
  ------------------------------------------------------------------------------ */

function isUserEmailExists($email_address, $dataHelper) {
    if (!is_object($dataHelper))
    {
        throw new Exception("client_db_function.inc.php : isUserEmailExists : DataHelper Object did not instantiate", 104);
    }

    if (strlen(trim($email_address)) <= 0)
    {
        throw new Exception("client_db_function.inc.php : isUserEmailExists : Missing Parameter email_address.", 141);
    }

    try
    {
        $dataHelper->setParam("'" . $email_address . "'", "I");
        $dataHelper->setParam("STATUS", "O");
        $arrIsEmailExists = $dataHelper->fetchRecords("SP", 'IsUserEmailExists');
        $dataHelper->clearParams();
        return $arrIsEmailExists;
    }
    catch (Exception $e)
    {
        throw new Exception("client_db_function.inc.php : isUserEmailExists : Failed : " . $e->getMessage(), 145);
    }
}

/* -----------------------------------------------------------------------------
  Function Name : insUserDetails (Not Used)
  Purpose       : To insert user details into user_details table.
  Parameters    : user_id, client_id, email_address, pwd, nick_name, first_name, last_name, country_name, timezone,gmt, phone, idd_code, mobile, reg_time, is_admin, status, Datahelper
  Returns       : array (with STATUS, MESSAGE)
  Calls         : datahelper.putRecords
  Called By     : adduser.php(User)
  Author        : Mitesh Shah
  Created  on   : 25-May-2015
  Modified By   :
  Modified on   :
  ------------------------------------------------------------------------------ */

//function insUserDetails_Prco_NotUsed($user_id, $client_id, $partner_id, $email_address, $pwd, $nick_name, $first_name, $last_name, $country_name, $timezone, $gmt, $phone, $idd_code, $mobile, $reg_time, $is_admin, $status, $dataHelper) {
//    if (!is_object($dataHelper))
//    {
//        throw new Exception("client_db_function.inc.php : insUserDetails : DataHelper Object did not instantiate", 104);
//    }
//
//    if (strlen(trim($user_id)) <= 0)
//    {
//        throw new Exception("client_db_function.inc.php : insUserDetails : Missing Parameter user_id.", 142);
//    }
//
//    if (strlen(trim($client_id)) <= 0)
//    {
//        throw new Exception("client_db_function.inc.php : insUserDetails : Missing Parameter client_id.", 142);
//    }
//
//    if (strlen(trim($partner_id)) <= 0)
//    {
//        throw new Exception("client_db_function.inc.php : insUserDetails : Missing Parameter partner_id.", 142);
//    }
//
//    if (strlen(trim($email_address)) <= 0)
//    {
//        throw new Exception("client_db_function.inc.php : insUserDetails : Missing Parameter email_address.", 141);
//    }
//
//    if (strlen(trim($pwd)) <= 0)
//    {
//        throw new Exception("client_db_function.inc.php : insUserDetails : Missing Parameter password.", 141);
//    }
//
//    if (strlen(trim($nick_name)) <= 0)
//    {
//        throw new Exception("client_db_function.inc.php : insUserDetails : Missing Parameter nick_name.", 142);
//    }
//
//    if (strlen(trim($first_name)) <= 0)
//    {
//        throw new Exception("client_db_function.inc.php : insUserDetails : Missing Parameter first_name.", 142);
//    }
//
//    if (strlen(trim($last_name)) <= 0)
//    {
//        throw new Exception("client_db_function.inc.php : insUserDetails : Missing Parameter last_name.", 143);
//    }
//
//    if (strlen(trim($country_name)) <= 0)
//    {
//        throw new Exception("client_db_function.inc.php : insUserDetails : Missing Parameter country_name.", 143);
//    }
//
//    if (strlen(trim($timezone)) <= 0)
//    {
//        throw new Exception("client_db_function.inc.php : insUserDetails : Missing Parameter timezone.", 143);
//    }
//
//    if (strlen(trim($gmt)) <= 0)
//    {
//        throw new Exception("client_db_function.inc.php : insUserDetails : Missing Parameter gmt.", 143);
//    }
//
//    if (strlen(trim($idd_code)) <= 0)
//    {
//        throw new Exception("client_db_function.inc.php : insUserDetails : Missing Parameter idd_code.", 143);
//    }
//
//    if (strlen(trim($mobile)) <= 0)
//    {
//        throw new Exception("client_db_function.inc.php : insUserDetails : Missing Parameter mobile.", 143);
//    }
//
//    if (strlen(trim($reg_time)) <= 0)
//    {
//        throw new Exception("client_db_function.inc.php : insUserDetails : Missing Parameter reg_time.", 143);
//    }
//
//    if (strlen(trim($is_admin)) <= 0)
//    {
//        throw new Exception("client_db_function.inc.php : insUserDetails : Missing Parameter is_admin.", 143);
//    }
//
//    try
//    {
//        $dataHelper->setParam("'" . $user_id . "'", "I");
//        $dataHelper->setParam("'" . $client_id . "'", "I");
//        $dataHelper->setParam("'" . $partner_id . "'", "I");
//        $dataHelper->setParam("'" . $email_address . "'", "I");
//        $dataHelper->setParam("'" . $pwd . "'", "I");
//        $dataHelper->setParam("'" . $nick_name . "'", "I");
//        $dataHelper->setParam("'" . $first_name . "'", "I");
//        $dataHelper->setParam("'" . $last_name . "'", "I");
//        $dataHelper->setParam("'" . $country_name . "'", "I");
//        $dataHelper->setParam("'" . $timezone . "'", "I");
//        $dataHelper->setParam("'" . $gmt . "'", "I");
//        $dataHelper->setParam("'" . $phone . "'", "I");
//        $dataHelper->setParam("'" . $idd_code . "'", "I");
//        $dataHelper->setParam("'" . $mobile . "'", "I");
//        $dataHelper->setParam("'" . $reg_time . "'", "I");
//        $dataHelper->setParam("'" . $is_admin . "'", "I");
//        $dataHelper->setParam("'" . $status . "'", "I");
//
//        $dataHelper->setParam("STATUS", "O");
//        $dataHelper->setParam("MESSAGE", "O");
//        $arrAddDetails = $dataHelper->putRecords("SP", 'InsertUserDetails');
//        $dataHelper->clearParams();
//        return $arrAddDetails;
//    }
//    catch (Exception $e)
//    {
//        throw new Exception(" client_db_function.inc.php : insUserDetails : Failed : " . $e->getMessage(), 145);
//    }
//}

/* -------------------------------------------------------------------------------
  Function Name : updateUserStatus
  Purpose       : To update user status.
  Parameters    : user_id, status, Datahelper
  Returns       : array (with user details)
  Calls         : datahelper.putRecords
  Called By     : index.php(User)
  Author        : Mitesh Shah
  Created  on   : 25-May-2015
  Modified By   :
  Modified on   :
  -------------------------------------------------------------------------------- */

function updateUserStatus($user_id, $user_name, $new_status, $old_status, $dataHelper) {
    if (!is_object($dataHelper))
    {
        throw new Exception("client_db_function.inc.php : updateUserStatus : DataHelper Object did not instantiate", 104);
    }
//
//    if (strlen(trim($user_id)) <= 0)
//    {
//        throw new Exception("client_db_function.inc.php: updateUserStatus : Missing Parameter user_id.", 141);
//    }
//
//    if (strlen(trim($status)) <= 0)
//    {
//        throw new Exception("client_db_function.inc.php: updateUserStatus : Missing Parameter status.", 141);
//    }
//
//    try
//    {
//        $strSqlStatement = "UPDATE user_login_details SET login_enabled = '" . trim($status) . "' WHERE user_id = '" . trim($user_id) . "';";
//        $UpdResult = $dataHelper->putRecords('QR', $strSqlStatement);
//        if ($objDataHelper->affectedRows == 0)
//        {
//            return 0;
//        }
//        else
//        {
//            return 1;
//        }
//    }
//    catch (Exception $e)
//    {
//        throw new Exception("client_db_function.inc.php : updUserStatus : Could not update status : " . $e->getMessage(), 144);
//    }

    try
    {
        $dataHelper->setParam("'" . $user_id . "'", "I");
        $dataHelper->setParam("'" . $user_name . "'", "I");
        $dataHelper->setParam("'" . $new_status . "'", "I");
        $dataHelper->setParam("'" . $old_status . "'", "I");
        $dataHelper->setParam("STATUS", "O");
        $arrUpdResult = $dataHelper->putRecords("SP", 'UpdateUserStatus');
        $dataHelper->clearParams();
        return $arrUpdResult;
    }
    catch (Exception $e)
    {
        throw new Exception("client_db_function.inc.php : isUserEmailExists : Failed : " . $e->getMessage(), 145);
    }
}

/* -----------------------------------------------------------------------------
  Function Name : getPlanDetailsByClientId
  Purpose       : To Get Plan Details from client_subscription_master Table
  Parameters    : Datahelper
  Returns       : array (with Plan details)
  Calls         : datahelper.fetchRecords
  Called By     :
  Author        : Mitesh Shah
  Created  on   : 25-May-2015
  Modified By   :
  Modified on   :
  ------------------------------------------------------------------------------ */

function getPlanDetailsByClientId($client_id, $dataHelper) {
    if (!is_object($dataHelper))
    {
        throw new Exception("client_db_function.inc.php : getPlanDetailsByClientId : DataHelper Object did not instantiate", 104);
    }

    try
    {
        $strSqlQuery = "SELECT plan_name, subscription_start_date_gmt,  subscription_end_date_gmt, DATEDIFF(subscription_end_date_gmt, DATE_FORMAT(NOW(), '%Y-%m-%d')) AS diff_days, subscription_date FROM client_subscription_master WHERE client_id='" . trim($client_id) . "' ORDER BY subscription_date;";
        $arrResult = $dataHelper->fetchRecords("QR", $strSqlQuery);
        return $arrResult;
    }
    catch (Exception $e)
    {
        throw new Exception("client_db_function.inc.php : Error in getting Plan details." . $e->getMessage(), 734);
    }
}

/* -------------------------------------------------------------------------------
  Function Name : getSubDtlsByClientIdnPlanId
  Purpose       : To get user details by client_id, plan_id from client_subscription_master table.
  Parameters    : client_id, plan_id, Datahelper
  Returns       : array (with client subscription details)
  Calls         : datahelper.fetchRecords
  Called By     : addsubscription.php
  Author        : Mitesh Shah
  Created  on   : 25-May-2015
  Modified By   :
  Modified on   :
  -------------------------------------------------------------------------------- */

function getSubDtlsByClientIdnPlanId($client_id, $plan_id, $sub_id, $order_id, $dataHelper) {
    if (!is_object($dataHelper))
    {
        throw new Exception("client_db_function.inc.php : getSubDtlsByClientIdnPlanId : DataHelper Object did not instantiate", 104);
    }

    try
    {
        $strSqlQuery = "SELECT client_subscription_id, csm.client_id, subscription_date, subscription_start_date_gmt, "
. "subscription_end_date_gmt, subscription_start_date_local, subscription_end_date_local, "
. "subscription_status, order_id, plan_id, plan_name, plan_desc, plan_for, plan_type, number_of_sessions, "
. "number_of_mins_per_sessions, plan_period, number_of_invitee, meeting_recording, disk_space, "
. "is_free, plan_cost_inr, plan_cost_oth, concurrent_sessions, talk_time_mins, plan_keyword, "
. "autorenew_flag, consumed_number_of_sessions, consumed_talk_time_mins "
. "FROM client_subscription_master AS csm, client_login_details AS cld, client_details AS cd "
. "WHERE csm.client_id =  '" . trim($client_id) . "'  "
. "AND csm.client_id = cld.client_id AND cld.client_id = cd.client_id AND cld.client_login_enabled = '1' "
. "AND plan_id= '" . trim($plan_id) . "' AND client_subscription_id= '" . trim($sub_id) . "' AND order_id= '" . trim($order_id) . "'";
        $arrResult = $dataHelper->fetchRecords("QR", $strSqlQuery);
        return $arrResult;
    }
    catch (Exception $e)
    {
        throw new Exception("client_db_function.inc.php : Error in getSubDtlsByClientIdnPlanId." . $e->getMessage(), 734);
    }
}

/* -----------------------------------------------------------------------------
  Function Name : insUserSubscriptionDetails
  Purpose       : To insert User Subscription details into subscription_master table.
  Parameters    : user_id, curr_datetime, curr_date, end_date, curr_date, end_date, subscription_status, order_id, plan_id, plan_name, plan_desc, plan_for, plan_type, number_of_sessions, number_of_mins_per_sessions, plan_period, number_of_invitee, meeting_recording, disk_space, is_free, plan_cost_inr, plan_cost_oth, concurrent_sessions, talk_time_mins, autorenew_flag, consumed_number_of_sessions, consumed_talk_time_mins, DataHelper
  Returns       : Status and Subscription id
  Calls         :
  Called By     : addsubscription.php
  Author        : Mitesh Shah
  Created  on   : 25-May-2015
  Modified By   :
  Modified on   :
  ------------------------------------------------------------------------------ */

function insUserSubscriptionDetails($user_id, $gmt_datetime, $gmt_start_date, $gmt_end_date, $local_start_date, $local_end_date, $subscription_status, $order_id, $plan_id, $plan_name, $plan_desc, $plan_for, $plan_type, $number_of_sessions, $number_of_mins_per_sessions, $plan_period, $number_of_invitee, $meeting_recording, $disk_space, $is_free, $plan_cost_inr, $plan_cost_oth, $concurrent_sessions, $talk_time_mins, $autorenew_flag, $consumed_number_of_sessions, $consumed_talk_time_mins, $dataHelper) {
    if (!is_object($dataHelper))
    {
        throw new Exception("client_db_function.inc.php : insUserSubscriptionDetails : DataHelper Object did not instantiate", 104);
    }
   
    if (strlen(trim($user_id)) <= 0)
    {
        throw new Exception("client_db_function.inc.php : insUserSubscriptionDetails : Missing Parameter user_id.", 143);
    }

    if (strlen(trim($gmt_datetime)) <= 0)
    {
        throw new Exception("client_db_function.inc.php : insUserSubscriptionDetails : Missing Parameter gmt_datetime.", 143);
    }

    if (strlen(trim($gmt_start_date)) <= 0)
    {
        throw new Exception("client_db_function.inc.php : insUserSubscriptionDetails : Missing Parameter gmt_start_date.", 141);
    }

    if (strlen(trim($gmt_end_date)) <= 0)
    {
        throw new Exception("client_db_function.inc.php : insUserSubscriptionDetails : Missing Parameter gmt_end_date.", 143);
    }

    if (strlen(trim($local_start_date)) <= 0)
    {
        throw new Exception("client_db_function.inc.php : insUserSubscriptionDetails : Missing Parameter local_start_date.", 143);
    }

    if (strlen(trim($local_end_date)) <= 0)
    {
        throw new Exception("client_db_function.inc.php : insUserSubscriptionDetails : Missing Parameter local_end_date.", 143);
    }

    if (strlen(trim($subscription_status)) <= 0)
    {
        throw new Exception("client_db_function.inc.php : insUserSubscriptionDetails : Missing Parameter subscription_status.", 143);
    }

    if (strlen(trim($order_id)) <= 0)
    {
        throw new Exception("client_db_function.inc.php : insUserSubscriptionDetails : Missing Parameter order_id.", 143);
    }

    if (strlen(trim($plan_id)) <= 0)
    {
        throw new Exception("client_db_function.inc.php : insUserSubscriptionDetails : Missing Parameter plan_id.", 143);
    }

    if (strlen(trim($plan_name)) <= 0)
    {
        throw new Exception("client_db_function.inc.php : insUserSubscriptionDetails : Missing Parameter plan_name.", 143);
    }

    if (strlen(trim($plan_desc)) <= 0)
    {
        throw new Exception("client_db_function.inc.php : insUserSubscriptionDetails : Missing Parameter plan_desc.", 143);
    }

    if (strlen(trim($plan_for)) <= 0)
    {
        throw new Exception("client_db_function.inc.php : insUserSubscriptionDetails : Missing Parameter plan_for.", 143);
    }

    if (strlen(trim($plan_type)) <= 0)
    {
        throw new Exception("client_db_function.inc.php : insUserSubscriptionDetails : Missing Parameter plan_type.", 143);
    }

    if (strlen(trim($number_of_sessions)) <= 0)
    {
        throw new Exception("client_db_function.inc.php : insUserSubscriptionDetails : Missing Parameter number_of_sessions.", 143);
    }

    if (strlen(trim($number_of_mins_per_sessions)) <= 0)
    {
        throw new Exception("client_db_function.inc.php : insUserSubscriptionDetails : Missing Parameter number_of_mins_per_sessions.", 143);
    }

    if (strlen(trim($plan_period)) <= 0)
    {
        throw new Exception("client_db_function.inc.php : insUserSubscriptionDetails : Missing Parameter plan_period.", 143);
    }

    if (strlen(trim($number_of_invitee)) <= 0)
    {
        throw new Exception("client_db_function.inc.php : insUserSubscriptionDetails : Missing Parameter number_of_invitee.", 143);
    }

    if (strlen(trim($meeting_recording)) <= 0)
    {
        throw new Exception("client_db_function.inc.php : insUserSubscriptionDetails : Missing Parameter meeting_recording.", 143);
    }

    if (strlen(trim($disk_space)) <= 0)
    {
        throw new Exception("client_db_function.inc.php : insUserSubscriptionDetails : Missing Parameter disk_space.", 143);
    }

    if (strlen(trim($is_free)) <= 0)
    {
        throw new Exception("client_db_function.inc.php : insUserSubscriptionDetails : Missing Parameter is_free.", 143);
    }

    if (strlen(trim($plan_cost_inr)) <= 0)
    {
        throw new Exception("client_db_function.inc.php : insUserSubscriptionDetails : Missing Parameter plan_cost_inr.", 143);
    }

    if (strlen(trim($plan_cost_oth)) <= 0)
    {
        throw new Exception("client_db_function.inc.php : insUserSubscriptionDetails : Missing Parameter plan_cost_oth.", 143);
    }

    if (strlen(trim($concurrent_sessions)) <= 0)
    {
        throw new Exception("client_db_function.inc.php : insUserSubscriptionDetails : Missing Parameter concurrent_sessions.", 143);
    }

    if (strlen(trim($autorenew_flag)) <= 0)
    {
        throw new Exception("client_db_function.inc.php : insUserSubscriptionDetails : Missing Parameter autorenew_flag.", 143);
    }

    if (strlen(trim($consumed_number_of_sessions)) <= 0)
    {
        throw new Exception("client_db_function.inc.php : insUserSubscriptionDetails : Missing Parameter consumed_number_of_sessions.", 143);
    }

    if (strlen(trim($consumed_talk_time_mins)) <= 0)
    {
        throw new Exception("client_db_function.inc.php : insUserSubscriptionDetails : Missing Parameter consumed_number_of_sessions.", 143);
    }

    try
    {
        $dataHelper->setParam("'" . $user_id . "'", "I");
        $dataHelper->setParam("'" . $gmt_datetime . "'", "I");
        $dataHelper->setParam("'" . $gmt_start_date . "'", "I");
        $dataHelper->setParam("'" . $gmt_end_date . "'", "I");
        $dataHelper->setParam("'" . $local_start_date . "'", "I");
        $dataHelper->setParam("'" . $local_end_date . "'", "I");
        $dataHelper->setParam("'" . $subscription_status . "'", "I");
        $dataHelper->setParam("'" . $order_id . "'", "I");
        $dataHelper->setParam("'" . $plan_id . "'", "I");
        $dataHelper->setParam("'" . $plan_name . "'", "I");
        $dataHelper->setParam("'" . $plan_desc . "'", "I");
        $dataHelper->setParam("'" . $plan_for . "'", "I");
        $dataHelper->setParam("'" . $plan_type . "'", "I");
        $dataHelper->setParam("'" . $number_of_sessions . "'", "I");
        $dataHelper->setParam("'" . $number_of_mins_per_sessions . "'", "I");
        $dataHelper->setParam("'" . $plan_period . "'", "I");
        $dataHelper->setParam("'" . $number_of_invitee . "'", "I");
        $dataHelper->setParam("'" . $meeting_recording . "'", "I");
        $dataHelper->setParam("'" . $disk_space . "'", "I");
        $dataHelper->setParam("'" . $is_free . "'", "I");
        $dataHelper->setParam("'" . $plan_cost_inr . "'", "I");
        $dataHelper->setParam("'" . $plan_cost_oth . "'", "I");
        $dataHelper->setParam("'" . $concurrent_sessions . "'", "I");
        $dataHelper->setParam("'" . $talk_time_mins . "'", "I");
        $dataHelper->setParam("'" . $autorenew_flag . "'", "I");
        $dataHelper->setParam("'" . $consumed_number_of_sessions . "'", "I");
        $dataHelper->setParam("'" . $consumed_talk_time_mins . "'", "I");

        $dataHelper->setParam("STATUS", "O");
        $dataHelper->setParam("OUTPUT", "O");
        $arrInsertSubscriptionDetails = $dataHelper->putRecords("SP", 'InsertSubscriptionMaster');
        $dataHelper->clearParams();
        return $arrInsertSubscriptionDetails;
    }
    catch (Exception $e)
    {
        throw new Exception(" client_db_function.inc.php : insUserSubscriptionDetails : Failed : " . $e->getMessage(), 145);
    }
}

/* -----------------------------------------------------------------------------
  Function Name : timezoneConverter
  Purpose       : To Get formatted date.
  Parameters    : type, timestamp, timezone
  Returns       : formatted time
  Calls         :
  Called By     : addsubscription.php(User)
  Author        : Mitesh Shah
  Created  on   : 25-May-2015
  Modified By   :
  Modified on   :
  ------------------------------------------------------------------------------ */

function timezoneConverter($sType, $timestamp, $timezone) {
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

function getUnUsedPlanByClientId($client_id, $dataHelper) {
    if (!is_object($dataHelper))
    {
        throw new Exception("client_db_function.inc.php : getPlanDetailsByClientId : DataHelper Object did not instantiate", 104);
    }

    try
    {
        $strSqlQuery = "SELECT * FROM client_subscription_master WHERE client_id='" . trim($client_id) . "' AND subscription_status NOT IN ('0', '3') AND order_id NOT IN (SELECT order_id FROM subscription_master WHERE subscription_status NOT IN ('4'));";
        $arrResult = $dataHelper->fetchRecords("QR", $strSqlQuery);
        return $arrResult;
    }
    catch (Exception $e)
    {
        throw new Exception("client_db_function.inc.php : Error in getting Plan details." . $e->getMessage(), 734);
    }
}

function getSubscriptionDetailsByUserId($user_id, $dataHelper) {
    if (!is_object($dataHelper))
    {
        throw new Exception("client_db_function.inc.php : getSubscriptionDetailsByUserId : DataHelper Object did not instantiate", 104);
    }

    try
    {
        $strSqlQuery = "SELECT user_id, plan_name, subscription_start_date_local, subscription_end_date_local, subscription_status FROM subscription_master WHERE user_id='" . trim($user_id) . "' ORDER BY subscription_end_date_local DESC;";
        $arrResult = $dataHelper->fetchRecords("QR", $strSqlQuery);
        return $arrResult;
    }
    catch (Exception $e)
    {
        throw new Exception("client_db_function.inc.php : Error in getting Plan details." . $e->getMessage(), 734);
    }
}

function updateClientPassword($email_address, $old_password, $new_password, $dataHelper) {
    if (!is_object($dataHelper))
    {
        throw new Exception("client_db_function.inc.php : updateClientPassword : DataHelper Object did not instantiate", 104);
    }

    if (strlen(trim($email_address)) <= 0)
    {
        throw new Exception("client_db_function.inc.php: updateClientPassword : Missing Parameter email_address.", 141);
    }

    if (strlen(trim($old_password)) <= 0)
    {
        throw new Exception("client_db_function.inc.php: updateClientPassword : Missing Parameter old_password.", 142);
    }

    if (strlen(trim($new_password)) <= 0)
    {
        throw new Exception("client_db_function.inc.php: updateClientPassword : Missing Parameter new_password.", 143);
    }

    try
    {
        if (!is_object($dataHelper))
        {
            throw new Exception("client_db_function.inc.php : updateClientPassword : DataHelper Object did not instantiate", 104);
        }
        $dataHelper->setParam("'" . $email_address . "'", "I");
        $dataHelper->setParam("'" . $old_password . "'", "I");
        $dataHelper->setParam("'" . $new_password . "'", "I");
        $dataHelper->setParam("STATUS", "O");
        $dataHelper->setParam("CLIENT_ID", "O");
        $dataHelper->setParam("EMAIL", "O");
        $arrUpdatePwd = $dataHelper->putRecords("SP", 'UpdateClientPassword');
        $dataHelper->clearParams();
        return $arrUpdatePwd;
    }
    catch (Exception $e)
    {
        throw new Exception(" client_db_function.inc.php : updateClientPassword : Failed : " . $e->getMessage(), 145);
    }
}

function isClientEmailIdExists($email_address, $dataHelper) {
    if (!is_object($dataHelper))
    {
        throw new Exception("client_db_function.inc.php : isClientEmailIdExists : DataHelper Object did not instantiate", 104);
    }

    if (strlen(trim($email_address)) <= 0)
    {
        throw new Exception("client_db_function.inc.php: isClientEmailIdExists : Missing Parameter email_address.", 141);
    }

    try
    {
        $strSqlStatement = "SELECT client_id, client_email_address FROM client_details WHERE client_email_address='" . trim($email_address) . "' AND status = '1'";
        $arrAuthResult = $dataHelper->fetchRecords("QR", $strSqlStatement);
        return $arrAuthResult;
    }
    catch (Exception $e)
    {
        throw new Exception("client_db_function.inc.php : isEmailIdExists : Could not fetch records : " . $e->getMessage(), 144);
    }
}

function getRequestPwdDetails($client_id, $email_address, $dataHelper) {
    if (!is_object($dataHelper))
    {
        throw new Exception("client_db_function.inc.php : getRequestPwdDetails : DataHelper Object did not instantiate", 104);
    }

    if (strlen(trim($email_address)) <= 0)
    {
        throw new Exception("client_db_function.inc.php: getRequestPwdDetails : Missing Parameter email_address.", 141);
    }

    try
    {
        $strSqlStatement = "SELECT email_address, request_datetime FROM password_request_details "
                . "WHERE email_address='" . trim($email_address) . "' "
                . "AND requested_by ='" . trim($client_id) . "' AND request_id = (SELECT MAX(request_id) FROM password_request_details) GROUP BY email_address";
        $arrPwdResult = $dataHelper->fetchRecords("QR", $strSqlStatement);
        return $arrPwdResult;
    }
    catch (Exception $e)
    {
        throw new Exception("client_db_function.inc.php : getRequestPwdDetails : Could not fetch records : " . $e->getMessage(), 144);
    }
}

function addPwdRequestDtm($client_id, $email_address, $time_stamp, $dataHelper) {
    if (!is_object($dataHelper))
    {
        throw new Exception("client_db_function.inc.php : addPwdRequestDtm : DataHelper Object did not instantiate", 104);
    }

    if (strlen(trim($client_id)) <= 0)
    {
        throw new Exception("client_db_function.inc.php: addPwdRequestDtm : Missing Parameter user_id.", 141);
    }

    if (strlen(trim($email_address)) <= 0)
    {
        throw new Exception("client_db_function.inc.php: addPwdRequestDtm : Missing Parameter email_address.", 141);
    }

    if (strlen(trim($time_stamp)) <= 0)
    {
        throw new Exception("client_db_function.inc.php: addPwdRequestDtm : Missing Parameter time_stamp.", 141);
    }

    try
    {
        $strSqlStatement = "INSERT INTO password_request_details(requested_by, email_address, request_datetime) VALUES('" . trim($client_id) . "', '" . trim($email_address) . "', '" . trim($time_stamp) . "')";
        $arrPutRecord = $dataHelper->putRecords("QR", $strSqlStatement);
        return $arrPutRecord;
    }
    catch (Exception $e)
    {
        throw new Exception("client_db_function.inc.php : addPwdRequestDtm : Could not fetch records : " . $e->getMessage(), 144);
    }
}

function deleteRequestPwd($client_id, $email_address, $dataHelper) {
    if (!is_object($dataHelper))
    {
        throw new Exception("client_db_function.inc.php : deleteRequestPwd : DataHelper Object did not instantiate", 104);
    }

    if (strlen(trim($email_address)) <= 0)
    {
        throw new Exception("client_db_function.inc.php: deleteRequestPwd : Missing Parameter email_address.", 141);
    }

    try
    {
        $strSqlStatement = "DELETE FROM password_request_details WHERE email_address='" . trim($email_address) . "' AND requested_by = '" . trim($client_id) . "'";
        $arrPwdResult = $dataHelper->putRecords("QR", $strSqlStatement);
        return $arrPwdResult;
    }
    catch (Exception $e)
    {
        throw new Exception("client_db_function.inc.php : deleteRequestPwd : Could not fetch records : " . $e->getMessage(), 144);
    }
}

function getLicenseDetailsByClient($client_id, $dataHelper) {

    if (!is_object($dataHelper))
    {
        throw new Exception("client_db_function.inc.php : getLicenseCountByClient : DataHelper Object did not instantiate", 104);
    }

    try
    {
        $strSqlQuery = "SELECT *  FROM client_license_details WHERE client_id = '" . trim($client_id) . "' ";
        $arrResult = $dataHelper->fetchRecords("QR", $strSqlQuery);
        return $arrResult;
    }
    catch (Exception $e)
    {
        throw new Exception("client_db_function.inc.php : Error in getLicenseCountByClient." . $e->getMessage(), 734);
    }
}

function resetClientPassword($email_address, $new_password, $dataHelper) {
    if (!is_object($dataHelper))
    {
        throw new Exception("client_db_function.inc.php : resetClientPassword : DataHelper Object did not instantiate", 104);
    }

    if (strlen(trim($email_address)) <= 0)
    {
        throw new Exception("client_db_function.inc.php: resetClientPassword : Missing Parameter email_address.", 141);
    }

    if (strlen(trim($new_password)) <= 0)
    {
        throw new Exception("client_db_function.inc.php: resetClientPassword : Missing Parameter $new_password.", 141);
    }

    try
    {
        $strSqlStatement = "UPDATE client_details SET client_password = '" . trim($new_password) . "' WHERE client_email_address='" . trim($email_address) . "' AND status = '1'";
        $arrAuthResult = $dataHelper->putRecords("QR", $strSqlStatement);
        return $arrAuthResult;
    }
    catch (Exception $e)
    {
        throw new Exception("client_db_function.inc.php : resetClientPassword : Could not fetch records : " . $e->getMessage(), 144);
    }
}

function insClientLicenseDetails($client_id, $no_of_license, $operation_type, $license_datetime, $dataHelper) {
    if (!is_object($dataHelper))
    {
        throw new Exception("client_db_function.inc.php : insClientLicenseDetails : DataHelper Object did not instantiate", 104);
    }

    if (strlen(trim($client_id)) <= 0)
    {
        throw new Exception("client_db_function.inc.php : insClientLicenseDetails : Missing Parameter client_id.", 143);
    }

    if (strlen(trim($no_of_license)) <= 0)
    {
        throw new Exception("client_db_function.inc.php : insClientLicenseDetails : Missing Parameter gmt_datetime.", 143);
    }

    if (strlen(trim($license_datetime)) <= 0)
    {
        throw new Exception("client_db_function.inc.php : insClientLicenseDetails : Missing Parameter gmt_start_date.", 141);
    }

    try
    {
        $dataHelper->setParam("'" . $client_id . "'", "I");
        $dataHelper->setParam("'" . $no_of_license . "'", "I");
        $dataHelper->setParam("'" . $operation_type . "'", "I");
        $dataHelper->setParam("'" . $license_datetime . "'", "I");
        $dataHelper->setParam("STATUS", "O");
        $arrInsertLicenseDetails = $dataHelper->putRecords("SP", 'InsertClientLicenseDetails');
        $dataHelper->clearParams();
        return $arrInsertLicenseDetails;
    }
    catch (Exception $e)
    {
        throw new Exception(" client_db_function.inc.php : insClientLicenseDetails : Failed : " . $e->getMessage(), 145);
    }
}

/* -----------------------------------------------------------------------------
  Function Name : getContactListbyType
  Purpose       : To get contact list by using contact type (P or C).
  Parameters    : owner_id, contact_type, Datahelper
  Returns       : array (with status, message)
  Calls         : datahelper.fetchRecords
  Called By     :
  Author        : Mitesh Shah
  Created  on   : 25-May-2015
  Modified By   :
  Modified on   :
  ------------------------------------------------------------------------------ */

function getContactListbyType($owner_id, $contact_type, $dataHelper) {
    if (!is_object($dataHelper))
    {
        throw new Exception("client_db_function.inc.php : getContactListbyType : DataHelper Object did not instantiate", 104);
    }

    if (strlen(trim($owner_id)) <= 0)
    {
        throw new Exception("client_db_function.inc.php : getContactListbyType : Missing Parameter owner_id.", 143);
    }

    if (strlen(trim($contact_type)) <= 0)
    {
        throw new Exception("client_db_function.inc.php : getContactListbyType : Missing Parameter contact_type.", 143);
    }

    try
    {
        $dataHelper->setParam("'" . $owner_id . "'", "I");
        $dataHelper->setParam("'" . $contact_type . "'", "I");
        $arrContactList = $dataHelper->fetchRecords("SP", 'GetContactList');
        $dataHelper->clearParams();
        return $arrContactList;
    }
    catch (Exception $e)
    {
        throw new Exception("client_db_function.inc.php : getContactList : Failed : " . $e->getMessage(), 145);
    }
}

/* -----------------------------------------------------------------------------
  Function Name : getContactGroupList
  Purpose       : To get group names while adding contact.
  Parameters    : owner_id, Datahelper
  Returns       : array (with group names)
  Calls         : datahelper.fetchRecords
  Called By     :
  Author        : Mitesh Shah
  Created  on   : 25-May-2015
  Modified By   :
  Modified on   :
  ------------------------------------------------------------------------------ */

function getContactGroupList($owner_id, $dataHelper) {
    if (!is_object($dataHelper))
    {
        throw new Exception("client_db_function.inc.php : ContactGroupList : DataHelper Object did not instantiate", 104);
    }

    if (strlen(trim($owner_id)) <= 0)
    {
        throw new Exception("client_db_function.inc.php : ContactGroupList : Missing Parameter owner_id.", 143);
    }

    try
    {
        $strSqlQuery = "SELECT DISTINCT contact_group_name FROM client_contact_details WHERE client_id = '" . trim($owner_id) . "';";
        $arrResult = $dataHelper->fetchRecords("QR", $strSqlQuery);
        return $arrResult;
    }
    catch (Exception $e)
    {
        throw new Exception("client_db_function.inc.php : Error in ContactGroupList." . $e->getMessage(), 734);
    }
}

/* -----------------------------------------------------------------------------
  Function Name : isContactEmailExists
  Purpose       : To check whether email address already exists while adding contact.
  Parameters    : email_address, owner_id, client_id, Datahelper
  Returns       : array (with status, message)
  Calls         : datahelper.fetchRecords
  Called By     :
  Author        : Mitesh Shah
  Created  on   : 25-May-2015
  Modified By   :
  Modified on   :
  ------------------------------------------------------------------------------ */

function isContactEmailExists($email_address, $client_id, $dataHelper) {
    if (!is_object($dataHelper))
    {
        throw new Exception("client_db_function.inc.php : isContactEmailExists : DataHelper Object did not instantiate", 104);
    }

    if (strlen(trim($email_address)) <= 0)
    {
        throw new Exception("client_db_function.inc.php : isContactEmailExists : Missing Parameter email_address.", 141);
    }

    if (strlen(trim($client_id)) <= 0)
    {
        throw new Exception("client_db_function.inc.php : isContactEmailExists : Missing Parameter client_id.", 143);
    }

    try
    {
        $strSqlQuery = "SELECT COUNT(contact_email_address) AS EmailCount FROM client_contact_details WHERE contact_email_address = '" . trim($email_address) . "' AND client_id = '" . trim($client_id) . "';";
        $arrResult = $dataHelper->fetchRecords("QR", $strSqlQuery);
        return $arrResult[0]['EmailCount'];
    }
    catch (Exception $e)
    {
        throw new Exception(" client_db_function.inc.php : isContactEmailExists : Failed : " . $e->getMessage(), 145);
    }
}

/* -----------------------------------------------------------------------------
  Function Name : isContactGroupExists
  Purpose       : To check whether group name already exists while adding a group in contact.
  Parameters    : group_name, owner_id, client_id, Datahelper
  Returns       : array (with status, message)
  Calls         : datahelper.fetchRecords
  Called By     :
  Author        : Mitesh Shah
  Created  on   : 25-May-2015
  Modified By   :
  Modified on   :
  ------------------------------------------------------------------------------ */

function isContactGroupExists($group_name, $client_id, $dataHelper) {
    if (!is_object($dataHelper))
    {
        throw new Exception("client_db_function.inc.php : isContactGroupExists : DataHelper Object did not instantiate", 104);
    }

    if (strlen(trim($group_name)) <= 0)
    {
        throw new Exception("client_db_function.inc.php : isContactGroupExists : Missing Parameter group_name.", 141);
    }

    if (strlen(trim($client_id)) <= 0)
    {
        throw new Exception("client_db_function.inc.php : isContactGroupExists : Missing Parameter client_id.", 143);
    }

    try
    {
        $strSqlQuery = "SELECT COUNT(contact_group_name) AS GroupCount FROM client_contact_details WHERE contact_group_name = '" . trim($group_name) . "' AND client_id = '" . trim($client_id) . "';";
        $arrResult = $dataHelper->fetchRecords("QR", $strSqlQuery);
        return $arrResult[0]['GroupCount'];
    }
    catch (Exception $e)
    {
        throw new Exception(" client_db_function.inc.php : isContactGroupExists : Failed : " . $e->getMessage(), 145);
    }
}

/* -----------------------------------------------------------------------------
  Function Name : insContactDetails
  Purpose       : To insert contact details.
  Parameters    : nick_name, first_name, last_name, email_address, idd_code, mobile, group_name, contact_type, owner_id, Datahelper
  Returns       : array (with status, message)
  Calls         : datahelper.putRecords
  Called By     :
  Author        : Mitesh Shah
  Created  on   : 25-May-2015
  Modified By   :
  Modified on   :
  ------------------------------------------------------------------------------ */

function insContactDetails($nick_name, $first_name, $last_name, $email_address, $idd_code, $mobile, $group_name, $contact_type, $owner_id, $dataHelper) {
    if (!is_object($dataHelper))
    {
        throw new Exception("client_db_function.inc.php : insContactDetails : DataHelper Object did not instantiate", 104);
    }

    if (strlen(trim($nick_name)) <= 0)
    {
        throw new Exception("client_db_function.inc.php : insContactDetails : Missing Parameter nick_name.", 142);
    }

    if (strlen(trim($email_address)) <= 0)
    {
        throw new Exception("client_db_function.inc.php : insContactDetails : Missing Parameter email_address.", 141);
    }

    if (strlen(trim($group_name)) <= 0)
    {
        throw new Exception("client_db_function.inc.php : insContactDetails : Missing Parameter group_name.", 143);
    }

    if (strlen(trim($contact_type)) <= 0)
    {
        throw new Exception("client_db_function.inc.php : insContactDetails : Missing Parameter contact_type.", 143);
    }

    if (strlen(trim($owner_id)) <= 0)
    {
        throw new Exception("client_db_function.inc.php : insContactDetails : Missing Parameter owner_id.", 143);
    }

    try
    {
        $dataHelper->setParam("'" . $nick_name . "'", "I");
        $dataHelper->setParam("'" . $first_name . "'", "I");
        $dataHelper->setParam("'" . $last_name . "'", "I");
        $dataHelper->setParam("'" . $email_address . "'", "I");
        $dataHelper->setParam("'" . $idd_code . "'", "I");
        $dataHelper->setParam("'" . $mobile . "'", "I");
        $dataHelper->setParam("'" . $group_name . "'", "I");
        $dataHelper->setParam("'" . $contact_type . "'", "I");
        $dataHelper->setParam("'" . $owner_id . "'", "I");
        $dataHelper->setParam("STATUS", "O");
        $dataHelper->setParam("MESSAGE", "O");
        $arrAddDetails = $dataHelper->putRecords("SP", 'InsertContactDetails');
        $dataHelper->clearParams();
        return $arrAddDetails;
    }
    catch (Exception $e)
    {
        throw new Exception(" client_db_function.inc.php : insContactDetails : Failed : " . $e->getMessage(), 145);
    }
}

/* -----------------------------------------------------------------------------
  Function Name : getContactDetails
  Purpose       : To get contact details for updating.
  Parameters    : contact_id, Datahelper
  Returns       : array (with personal_contact_details)
  Calls         : datahelper.fetchRecords
  Called By     :
  Author        : Mitesh Shah
  Created  on   : 25-May-2015
  Modified By   :
  Modified on   :
  ------------------------------------------------------------------------------ */

function getContactDetails($contact_id, $dataHelper) {
    if (!is_object($dataHelper))
    {
        throw new Exception("contact_function.inc.php : getContactDetails : DataHelper Object did not instantiate", 104);
    }

    if (strlen(trim($contact_id)) <= 0)
    {
        throw new Exception("contact_function.inc.php : getContactDetails : Missing Parameter contact_id.", 143);
    }

    try
    {
        $strSqlQuery = "SELECT * FROM client_contact_details WHERE client_contact_id = '" . trim($contact_id) . "';";
        $arrResult = $dataHelper->fetchRecords("QR", $strSqlQuery);
        return $arrResult;
    }
    catch (Exception $e)
    {
        throw new Exception("contact_function.inc.php : Error in getContactDetails." . $e->getMessage(), 734);
    }
}

/* -----------------------------------------------------------------------------
  Function Name : updContactDetails
  Purpose       : To update contact details.
  Parameters    : contact_id, nick_name, first_name, last_name, email_address, idd_code, mobile, group_name, contact_type, owner_id, Datahelper
  Returns       : array (with status, message)
  Calls         : datahelper.putRecords
  Called By     :
  Author        : Mitesh Shah
  Created  on   : 25-May-2015
  Modified By   :
  Modified on   :
  ------------------------------------------------------------------------------ */

function updContactDetails($contact_id, $nick_name, $first_name, $last_name, $email_address, $idd_code, $mobile, $group_name, $contact_type, $owner_id, $dataHelper) {
    if (!is_object($dataHelper))
    {
        throw new Exception("contact_function.inc.php : updContactDetails : DataHelper Object did not instantiate", 104);
    }

    if (strlen(trim($contact_id)) <= 0)
    {
        throw new Exception("contact_function.inc.php : updContactDetails : Missing Parameter contact_id.", 142);
    }

    if (strlen(trim($nick_name)) <= 0)
    {
        throw new Exception("contact_function.inc.php : updContactDetails : Missing Parameter nick_name.", 142);
    }

    if (strlen(trim($email_address)) <= 0)
    {
        throw new Exception("contact_function.inc.php : updContactDetails : Missing Parameter email_address.", 141);
    }

    if (strlen(trim($group_name)) <= 0)
    {
        throw new Exception("contact_function.inc.php : updContactDetails : Missing Parameter group_name.", 143);
    }

    if (strlen(trim($contact_type)) <= 0)
    {
        throw new Exception("contact_function.inc.php : updContactDetails : Missing Parameter contact_type.", 143);
    }

    if (strlen(trim($owner_id)) <= 0)
    {
        throw new Exception("contact_function.inc.php : updContactDetails : Missing Parameter owner_id.", 143);
    }

    try
    {
        $dataHelper->setParam("'" . $contact_id . "'", "I");
        $dataHelper->setParam("'" . $nick_name . "'", "I");
        $dataHelper->setParam("'" . $first_name . "'", "I");
        $dataHelper->setParam("'" . $last_name . "'", "I");
        $dataHelper->setParam("'" . $email_address . "'", "I");
        $dataHelper->setParam("'" . $idd_code . "'", "I");
        $dataHelper->setParam("'" . $mobile . "'", "I");
        $dataHelper->setParam("'" . $group_name . "'", "I");
        $dataHelper->setParam("'" . $contact_type . "'", "I");
        $dataHelper->setParam("'" . $owner_id . "'", "I");
        $dataHelper->setParam("STATUS", "O");
        $dataHelper->setParam("MESSAGE", "O");
        $arrUpdateDetails = $dataHelper->putRecords("SP", 'UpdateContactDetails');
        $dataHelper->clearParams();
        return $arrUpdateDetails;
    }
    catch (Exception $e)
    {
        throw new Exception(" contact_function.inc.php : updContactDetails : Failed : " . $e->getMessage(), 145);
    }
}

/* -----------------------------------------------------------------------------
  Function Name : delContactDetails
  Purpose       : To delete a contact.
  Parameters    : contact_id, owner_id, contact_type, Datahelper
  Returns       : array (with status, message)
  Calls         : datahelper.putRecords
  Called By     :
  Author        : Mitesh Shah
  Created  on   : 25-May-2015
  Modified By   :
  Modified on   :
  ------------------------------------------------------------------------------ */

function delContactDetails($contact_id, $owner_id, $contact_type, $dataHelper) {
    if (!is_object($dataHelper))
    {
        throw new Exception("contact_function.inc.php : delContactDetails : DataHelper Object did not instantiate", 104);
    }

    if (strlen(trim($contact_id)) <= 0)
    {
        throw new Exception("contact_function.inc.php : delContactDetails : Missing Parameter contact_id.", 143);
    }

    if (strlen(trim($owner_id)) <= 0)
    {
        throw new Exception("contact_function.inc.php : delContactDetails : Missing Parameter owner_id.", 143);
    }

    if (strlen(trim($contact_type)) <= 0)
    {
        throw new Exception("contact_function.inc.php : delContactDetails : Missing Parameter contact_type.", 143);
    }

    try
    {
        $dataHelper->setParam("'" . $contact_id . "'", "I");
        $dataHelper->setParam("'" . $owner_id . "'", "I");
        $dataHelper->setParam("'" . $contact_type . "'", "I");
        $dataHelper->setParam("STATUS", "O");
        $dataHelper->setParam("MESSAGE", "O");
        $arrDeleteCnt = $dataHelper->putRecords("SP", 'DeleteContactDetails');
        $dataHelper->clearParams();
        return $arrDeleteCnt;
    }
    catch (Exception $e)
    {
        throw new Exception("contact_function.inc.php : delContactDetails : Failed : " . $e->getMessage(), 145);
    }
}

/* -----------------------------------------------------------------------------
  Function Name : insUserLoginDetails
  Purpose       : To insert user details into user_login_details and user_details table.
  Parameters    : user_id, user_name, client_id, partner_id, password, email_address, role, login_enabled, created_on, nick_name, first_name, last_name, country_name, timezones, gmt, idd_code, mobile_number, status, Datahelper
  Returns       :
  Calls         : datahelper.putRecords
  Called By     : adduser.php(User)
  Author        : Mitesh Shah
  Created  on   : 14-October-2015
  Modified By   :
  Modified on   :
  ------------------------------------------------------------------------------ */

function insUserLoginDetails($user_id, $user_name, $client_id, $partner_id, $password, $email_address, $role, $login_enabled, $created_on, $created_by, $dataHelper) {
    if (!is_object($dataHelper))
    {
        throw new Exception("client_db_function.inc.php : insUserLoginDetails : DataHelper Object did not instantiate", 104);
    }
    try
    {
        $strSqlStatement = "INSERT INTO user_login_details(user_id, user_name, client_id, partner_id, password, email_address, role, login_enabled, created_on, created_by) VALUES('" . $user_id . "','" . $user_name . "', '" . $client_id . "', '" . $partner_id . "', '" . $password . "', '" . $email_address . "', '" . $role . "', '" . $login_enabled . "', '" . $created_on . "', '" . $created_by . "')";
        $arrInsUserLoginDetails = $dataHelper->putRecords("QR", $strSqlStatement);
        $dataHelper->clearParams();
        return $arrInsUserLoginDetails;
    }
    catch (Exception $e)
    {
        throw new Exception(" client_db_function.inc.php : insUserLoginDetails : Failed : " . $e->getMessage(), 145);
    }
}

/* -----------------------------------------------------------------------------
  Function Name : insUserDetails
  Purpose       : To insert user details into user_login_details and user_details table.
  Parameters    : user_id, nick_name, country_name, timezones, gmt, idd_code, mobile_number, status, Datahelper
  Returns       :
  Calls         : datahelper.putRecords
  Called By     : adduser.php(User)
  Author        : Mitesh Shah
  Created  on   : 14-October-2015
  Modified By   :
  Modified on   :
  ------------------------------------------------------------------------------ */

function insUserDetails($user_id, $nick, $countryname, $timezone, $gmt, $iddcode, $mobile, $dataHelper) {
    if (!is_object($dataHelper))
    {
        throw new Exception("client_db_function.inc.php : insUserDetails : DataHelper Object did not instantiate", 104);
    }
    try
    {
        $strSqlStatement = "INSERT INTO user_details(user_id, nick_name, country_name, timezones, gmt, idd_code, mobile_number) VALUES ('" . $user_id . "', '" . $nick . "', '" . $countryname . "', '" . $timezone . "', '" . $gmt . "', '" . $iddcode . "', '" . $mobile . "');";
        $arrInsUserDetails = $dataHelper->putRecords("QR", $strSqlStatement);
        $dataHelper->clearParams();
        return $arrInsUserDetails;
    }
    catch (Exception $e)
    {
        throw new Exception(" client_db_function.inc.php : insUserDetails : Failed : " . $e->getMessage(), 145);
    }
}

/* -----------------------------------------------------------------------------
  Function Name : getUpdateQueryString
  Purpose       : to generate the update query string
  Parameters    :  array Form values
  Returns       :
  Calls         :
  Called By     :
  Author        : Sushrit
  Created  on   : July 29 , 2015
  Modified By   :
  Modified on   :
  ------------------------------------------------------------------------------ */

function getUpdateQueryString($formValues, $formTableMap) {
    if (isset($formValues['formname']))
    {
        if ($formValues['formname'] != "")
        {
            $updateString = "";

            foreach ($formValues as $key => $value)
            {
                if ($key != "formname")
                {
                    if (isset($formTableMap[$formValues['formname']][$key]) && $value != "")
                    {
                        $columnName = $formTableMap[$formValues['formname']][$key];
                        $columnValue = ($columnName == "client_password") ? md5(trim($value)) : trim($value);
                        $updateString .= ($updateString != "") ? " , " : "";
                        $updateString .= $columnName . " = \"" . $columnValue . "\"";
                    }
                }
            }
            return $updateString;
        }
    }
}

/* -----------------------------------------------------------------------------
  Function Name : revokeUserSubscription
  Purpose       : To Revoke Plan from User Update the subscription_master with staus and subscription date
  Parameters    : subscription_id, order_id, user_id, subscription_status, Datahelper
  Returns       :
  Calls         : datahelper.putRecords
  Called By     : 
  Author        : Mitesh Shah
  Created  on   : 28-October-2015
  Modified By   :
  Modified on   :
  ------------------------------------------------------------------------------ */
function revokeUserSubscription($subscription_enddate, $subscription_status, $change_datetime, $subscriptionid, $user_id, $orderid, $planid, $dataHelper) {
    if (!is_object($dataHelper))
    {
        throw new Exception("client_db_function.inc.php : revokeUserSubscription : DataHelper Object did not instantiate", 104);
    }    
        try
        {
            $dataHelper->setParam("'" . $subscription_enddate . "'", "I");
            $dataHelper->setParam("'" . $subscription_status . "'", "I");
            $dataHelper->setParam("'" . $change_datetime . "'", "I");
            $dataHelper->setParam("'" . $subscriptionid . "'", "I");
            $dataHelper->setParam("'" . $user_id . "'", "I");
            $dataHelper->setParam("'" . $orderid . "'", "I");
            $dataHelper->setParam("'" . $planid . "'", "I");
            $dataHelper->setParam("STATUS", "O");
            $arrUpdResult = $dataHelper->putRecords("SP", 'RevokeUserSubscription');
            $dataHelper->clearParams();
            return $arrUpdResult;
        }
        catch (Exception $e)
        {
            throw new Exception("client_db_function.inc.php : revokeUserSubscription : Failed : " . $e->getMessage(), 145);
        }
}

/* -----------------------------------------------------------------------------
  Function Name : revokeClientSubscription
  Purpose       : To Revoke Plan from Client Update the client_subscription_master with staus
  Parameters    : subscription_id, order_id, client_id, subscription_status, Datahelper
  Returns       :
  Calls         : datahelper.putRecords
  Called By     : 
  Author        : Mitesh Shah
  Created  on   : 28-October-2015
  Modified By   :
  Modified on   :
  ------------------------------------------------------------------------------ */
function revokeClientSubscription($subscription_status, $change_datetime, $client_id, $orderid, $planid, $dataHelper) {
    if (!is_object($dataHelper))
    {
        throw new Exception("client_db_function.inc.php : revokeClientSubscription : DataHelper Object did not instantiate", 104);
    }    
        try
        {
            $dataHelper->setParam("'" . $subscription_status . "'", "I");
            $dataHelper->setParam("'" . $change_datetime . "'", "I");
            $dataHelper->setParam("'" . $client_id . "'", "I");
            $dataHelper->setParam("'" . $orderid . "'", "I");
            $dataHelper->setParam("'" . $planid . "'", "I");
            $dataHelper->setParam("STATUS", "O");
            $arrUpdResult = $dataHelper->putRecords("SP", 'RevokeClientSubscription');
            $dataHelper->clearParams();
            return $arrUpdResult;
        }
        catch (Exception $e)
        {
            throw new Exception("client_db_function.inc.php : revokeClientSubscription : Failed : " . $e->getMessage(), 145);
        }
}
