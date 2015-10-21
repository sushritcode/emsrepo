<?php
function getLicenseCountByID($client_id, $dataHelper) {
    if (!is_object($dataHelper))
    {
        throw new Exception("dashboard.inc.php : getLicenseCountByID : DataHelper Object did not instantiate", 104);
    }
    try
    {
        $strSqlStatement = "SELECT SUM(no_of_license) AS TotalLicense FROM client_license_details WHERE operation_type = '0' AND client_id ='" . trim($client_id) . "';";
        $arrResult = $dataHelper->fetchRecords("QR", $strSqlStatement);
        return $arrResult;
    }
    catch (Exception $e)
    {
        throw new Exception("dashboard.inc.php : getLicenseCountByID : Could not fetch records : " . $e->getMessage(), 144);
    }
}

function getContactCountByID($client_id, $dataHelper) {
    if (!is_object($dataHelper))
    {
        throw new Exception("dashboard.inc.php : getContactCountByID : DataHelper Object did not instantiate", 104);
    }
    try
    {
        $strSqlStatement = "SELECT COUNT(*) as TotalContacts FROM client_contact_details WHERE client_id ='" . trim($client_id) . "' AND client_contact_status = '1';";
        $arrResult = $dataHelper->fetchRecords("QR", $strSqlStatement);
        return $arrResult;
    }
    catch (Exception $e)
    {
        throw new Exception("dashboard.inc.php : getContactCountByID : Could not fetch records : " . $e->getMessage(), 144);
    }
}

function getMeetingCountByID($client_id, $dataHelper) {
    if (!is_object($dataHelper))
    {
        throw new Exception("dashboard.inc.php : getMeetingCountByID : DataHelper Object did not instantiate", 104);
    }
    try
    {
        $strSqlStatement = "SELECT COUNT(*) AS TotalMeeting FROM schedule_details sd,  user_login_details uld "
."WHERE schedule_id IN (SELECT schedule_id FROM invitation_details)  "
."AND sd.user_id = uld.user_id AND uld. client_id= '" . trim($client_id) . "' ;";
        $arrResult = $dataHelper->fetchRecords("QR", $strSqlStatement);
        return $arrResult;
    }
    catch (Exception $e)
    {
        throw new Exception("dashboard.inc.php : getMeetingCountByID : Could not fetch records : " . $e->getMessage(), 144);
    }
}


function getMeetingDurationByID($client_id, $dataHelper) {
    if (!is_object($dataHelper))
    {
        throw new Exception("dashboard.inc.php : getMeetingDurationByID : DataHelper Object did not instantiate", 104);
    }
    try
    {
        $strSqlStatement = "SELECT SUM(IFNULL(TIMESTAMPDIFF(MINUTE,sd.meeting_start_time, sd.meeting_end_time),0)) AS 'TotalMinutes' "
. "FROM  user_login_details AS uld, schedule_details AS sd  "
. "WHERE schedule_id IN (SELECT schedule_id FROM invitation_details) "
."AND sd.user_id = uld.user_id AND uld. client_id= '" . trim($client_id) . "' ;";
        $arrResult = $dataHelper->fetchRecords("QR", $strSqlStatement);
        return $arrResult;
    }
    catch (Exception $e)
    {
        throw new Exception("dashboard.inc.php : getMeetingDurationByID : Could not fetch records : " . $e->getMessage(), 144);
    }
}

function getMeetingOverviewByID($client_id, $dataHelper) {
    if (!is_object($dataHelper))
    {
        throw new Exception("dashboard.inc.php : getMeetingOverviewByID : DataHelper Object did not instantiate", 104);
    }
    try
    {
        $strSqlStatement = "SELECT CASE schedule_status WHEN '2' THEN 'Completed' WHEN '3' THEN 'Canceled' WHEN '4' THEN 'Overdue' END AS 'label', COUNT(schedule_status) AS 'data', CASE schedule_status WHEN '2' THEN 'orange2' WHEN '3' THEN 'grey' WHEN '4' THEN 'green' END AS 'color'  "
."FROM schedule_details AS sd, user_login_details AS uld "
."WHERE  schedule_id IN (SELECT schedule_id FROM invitation_details) "
."AND sd.schedule_status IN ('2','3','4') "
."AND uld.user_id = sd.user_id  "
."AND uld. client_id= '" . trim($client_id) . "' "
."GROUP BY schedule_status ORDER BY schedule_status; ";
        $arrResult = $dataHelper->fetchRecords("QR", $strSqlStatement);
        return $arrResult;
    }
    catch (Exception $e)
    {
        throw new Exception("dashboard.inc.php : getMeetingOverviewByID : Could not fetch records : " . $e->getMessage(), 144);
    }
}

function getClientSubscriptionInfo($partner_id, $client_id, $dataHelper) {
    if (!is_object($dataHelper))
    {
        throw new Exception("report_function.inc.php : getClientSubscriptionInfo : DataHelper Object did not instantiate", 104);
    }

    try
    {
        $strSqlQuery = "SELECT pd.partner_name, cd.client_name, cd.client_id, cd.status, csm.plan_name, csm.subscription_start_date_gmt, "
. "csm.subscription_end_date_gmt, DATEDIFF(csm.subscription_end_date_gmt, DATE_FORMAT(NOW(), '%Y-%m-%d')) AS diff_days, csm.subscription_status  "
. "FROM partner_details AS pd, client_details AS cd, client_subscription_master AS csm "
. "WHERE pd.partner_id = cd.partner_id AND cd.client_id = csm.client_id "
. "AND cd.client_id = '" . trim($client_id) . "' "
. "AND pd.partner_id = '" . trim($partner_id) . "' "
. "ORDER BY csm.subscription_status, csm.subscription_end_date_gmt DESC  LIMIT 0,3;";
        $arrResult = $dataHelper->fetchRecords("QR", $strSqlQuery);
        return $arrResult;
    }
    catch (Exception $e)
    {
        throw new Exception("report_function.inc.php : Error in getClientSubscriptionInfo." . $e->getMessage(), 734);
    }
}

//function getTotalInviteMeetingCountByID($email_address, $dataHelper) {
//    if (!is_object($dataHelper))
//    {
//        throw new Exception("dashboard.inc.php : getTotalMeetingDurationByID : DataHelper Object did not instantiate", 104);
//    }
//    try
//    {
//        $strSqlStatement = "SELECT COUNT(*) AS TotalMeetingInvite FROM invitation_details WHERE invitee_email_address ='" . trim($email_address) . "' AND invitation_creator='I' AND schedule_id IN (SELECT schedule_id FROM schedule_details);";
//        $arrResult = $dataHelper->fetchRecords("QR", $strSqlStatement);
//        return $arrResult;
//    }
//    catch (Exception $e)
//    {
//        throw new Exception("dashboard.inc.php : getTotalMeetingDurationByID : Could not fetch records : " . $e->getMessage(), 144);
//    }
//}
//
//function getTotalDistinctInviteeCountByID($client_id, $dataHelper) {
//    if (!is_object($dataHelper))
//    {
//        throw new Exception("dashboard.inc.php : getTotalMeetingDurationByID : DataHelper Object did not instantiate", 104);
//    }
//    try
//    {
//        $strSqlStatement = "SELECT COUNT(DISTINCT invitee_email_address) AS DistinctInvitee FROM schedule_details AS sd, invitation_details AS id WHERE sd.user_id ='" . trim($client_id) . "' AND sd.schedule_id = id.schedule_id AND invitation_creator != 'C';";
//        $arrResult = $dataHelper->fetchRecords("QR", $strSqlStatement);
//        return $arrResult;
//    }
//    catch (Exception $e)
//    {
//        throw new Exception("dashboard.inc.php : getTotalMeetingDurationByID : Could not fetch records : " . $e->getMessage(), 144);
//    }
//}
//
//function getProfileCompletePercentByID($client_id, $dataHelper) {
//    if (!is_object($dataHelper))
//    {
//        throw new Exception("dashboard.inc.php : getProfileCompletePercentByID : DataHelper Object did not instantiate", 104);
//    }
//    try
//    {
//        $strSqlStatement = "SELECT user_id, FLOOR((
//CASE WHEN nick_name IS NULL OR nick_name = '' THEN 0  ELSE 1 END +
//CASE WHEN first_name IS NULL OR first_name = '' THEN 0 ELSE 1 END +
//CASE WHEN last_name IS NULL OR last_name = '' THEN 0  ELSE 1 END +
//CASE WHEN secondry_email IS NULL OR secondry_email = '' THEN 0  ELSE 1 END +
//CASE WHEN landmark IS NULL OR landmark = '' THEN 0  ELSE 1 END +
//CASE WHEN city IS NULL OR city = '' THEN 0  ELSE 1 END +
//CASE WHEN address IS NULL OR address = '' THEN 0  ELSE 1 END +
//CASE WHEN country_name IS NULL OR country_name = '' THEN 0  ELSE 1 END +
//CASE WHEN timezones IS NULL OR timezones = '' THEN 0  ELSE 1 END +
//CASE WHEN gmt IS NULL OR gmt = '' THEN 0  ELSE 1 END +
//CASE WHEN phone_number IS NULL OR phone_number = '' THEN 0  ELSE 1 END +
//CASE WHEN idd_code IS NULL OR idd_code = '' THEN 0  ELSE 1 END +
//CASE WHEN mobile_number IS NULL OR mobile_number = '' THEN 0  ELSE 1 END +
//CASE WHEN industry_type IS NULL OR industry_type = '' THEN 0  ELSE 1 END +
//CASE WHEN company_name IS NULL OR company_name = '' THEN 0  ELSE 1 END +
//CASE WHEN nature_business IS NULL OR nature_business = '' THEN 0  ELSE 1 END +
//CASE WHEN company_uri IS NULL OR company_uri = '' THEN 0  ELSE 1 END +
//CASE WHEN brief_desc_company IS NULL OR brief_desc_company = '' THEN 0  ELSE 1 END +
//CASE WHEN facebook IS NULL OR facebook = '' THEN 0  ELSE 1 END +
//CASE WHEN twitter IS NULL OR twitter = '' THEN 0  ELSE 1 END +
//CASE WHEN googleplus IS NULL OR googleplus = '' THEN 0  ELSE 1 END +
//CASE WHEN linkedin IS NULL OR linkedin = '' THEN 0  ELSE 1 END 
//) * 100 / 22) AS 'ProfilePercentage' FROM user_details WHERE user_id = '".trim($client_id)."';";
//        $arrResult = $dataHelper->fetchRecords("QR", $strSqlStatement);
//        return $arrResult;
//    }
//    catch (Exception $e)
//    {
//        throw new Exception("dashboard.inc.php : getProfileCompletePercentByID : Could not fetch records : " . $e->getMessage(), 144);
//    }
//}
//
//function getFrequentInvitees($client_id, $noOfInvitees, $dataHelper) {
//    
//    if (!is_object($dataHelper))
//    {
//        throw new Exception("dashboard.inc.php : getTotalMeetingDurationByID : DataHelper Object did not instantiate", 104);
//    }
//    
//    if (strlen(trim($client_id)) <= 0)
//    {
//        throw new Exception("dashboard.inc.php: getFrequentInvitees  : Missing Parameter user id.", 141);
//    }
//    
//    if (!is_int($noOfInvitees))
//    {
//        throw new Exception("dashboard.inc.php: getFrequentInvitees  : noOfInvitees should be a integee.", 141);
//    }
//    
//    if ($noOfInvitees > 0)
//    {
//        $limit = "Limit 0," . $noOfInvitees;
//    }
//    
//    try
//    {
//        //$strSqlStatement = "SELECT  DISTINCT (id.invitee_email_address) AS 'noOfOcurance' FROM  schedule_details sd, invitation_details id, personal_contact_details pcd WHERE  sd.user_id = '" . trim($client_id) . "' AND sd.schedule_id = id.schedule_id AND id.invitee_email_address = pcd.contact_email_address AND pcd.user_id = sd.user_id Group By id.invitee_email_address Order By noOfOcurance Desc;";
//        $strSqlStatement = "SELECT DISTINCT contact_nick_name, contact_email_address FROM schedule_details AS sd, invitation_details AS id,  personal_contact_details AS pcd WHERE sd.user_id = '" . trim($client_id) . "' AND sd.schedule_id = id.schedule_id AND id.invitee_email_address = pcd.contact_email_address;";        
//        $arrFrequentInvitees = $dataHelper->fetchRecords("QR", $strSqlStatement);
//        return $arrFrequentInvitees;
//    }
//    catch (Exception $e)
//    {
//        throw new Exception("dashboard.inc.php : getFrequentInvitees : Could not fetch records : " . $e->getMessage(), 144);
//    }
//}
//


//function getMinuteBaseMeetingGraphByID($client_id, $dataHelper) {
//    if (!is_object($dataHelper))
//    {
//        throw new Exception("dashboard.inc.php : getMinuteBaseMeetingGraphByID : DataHelper Object did not instantiate", 104);
//    }
//    try
//    {
//        $strSqlStatement = "SELECT COUNT(sd.schedule_id) AS 'SchedueCount', SUM(IFNULL(TIMESTAMPDIFF( MINUTE , meeting_start_time, meeting_end_time),0)) AS 'TotalMinute',  DATE_FORMAT( meeting_start_time,'%d-%m-%Y' ) AS 'DateOfMeeting' FROM schedule_details AS sd WHERE user_id = '".trim($client_id)."' AND schedule_status = '2' GROUP BY DateOfMeeting  ORDER BY DateOfMeeting;";
//        $arrResult = $dataHelper->fetchRecords("QR", $strSqlStatement);
//        return $arrResult;
//    }
//    catch (Exception $e)
//    {
//        throw new Exception("dashboard.inc.php : getMinuteBaseMeetingGraphByID : Could not fetch records : " . $e->getMessage(), 144);
//    }
//}
//
