<?php

function getPersonalContactCountByID($user_id, $dataHelper) {
    if (!is_object($dataHelper))
    {
        throw new Exception("dashboard.inc.php : getPersonalContactCountByID : DataHelper Object did not instantiate", 104);
    }
    try
    {
        $strSqlStatement = "SELECT COUNT(*) as TotalContacts FROM personal_contact_details WHERE user_id ='" . trim($user_id) . "';";
        $arrResult = $dataHelper->fetchRecords("QR", $strSqlStatement);
        return $arrResult;
    }
    catch (Exception $e)
    {
        throw new Exception("dashboard.inc.php : getPersonalContactCountByID : Could not fetch records : " . $e->getMessage(), 144);
    }
}

function getTotalHostMeetingCountByID($user_id, $dataHelper) {
    if (!is_object($dataHelper))
    {
        throw new Exception("dashboard.inc.php : getPersonalContactCountByID : DataHelper Object did not instantiate", 104);
    }
    try
    {
        $strSqlStatement = "SELECT COUNT(*) AS TotalMeetingCreated FROM schedule_details WHERE user_id ='" . trim($user_id) . "' AND schedule_id IN (SELECT schedule_id FROM invitation_details);";
        $arrResult = $dataHelper->fetchRecords("QR", $strSqlStatement);
        return $arrResult;
    }
    catch (Exception $e)
    {
        throw new Exception("dashboard.inc.php : getPersonalContactCountByID : Could not fetch records : " . $e->getMessage(), 144);
    }
}

function getTotalMeetingDurationByID($user_id, $dataHelper) {
    if (!is_object($dataHelper))
    {
        throw new Exception("dashboard.inc.php : getTotalMeetingDurationByID : DataHelper Object did not instantiate", 104);
    }
    try
    {
        $strSqlStatement = "SELECT SUM(IFNULL(TIMESTAMPDIFF(MINUTE,sd.meeting_start_time, sd.meeting_end_time),0)) AS 'TotalDuration' FROM  user_login_details AS uld, schedule_details AS sd  WHERE uld.user_id = sd.user_id AND uld.user_id ='" . trim($user_id) . "' ;";
        $arrResult = $dataHelper->fetchRecords("QR", $strSqlStatement);
        return $arrResult;
    }
    catch (Exception $e)
    {
        throw new Exception("dashboard.inc.php : getTotalMeetingDurationByID : Could not fetch records : " . $e->getMessage(), 144);
    }
}

function getTotalInviteMeetingCountByID($email_address, $dataHelper) {
    if (!is_object($dataHelper))
    {
        throw new Exception("dashboard.inc.php : getTotalMeetingDurationByID : DataHelper Object did not instantiate", 104);
    }
    try
    {
        $strSqlStatement = "SELECT COUNT(*) AS TotalMeetingInvite FROM invitation_details WHERE invitee_email_address ='" . trim($email_address) . "' AND invitation_creator='I' AND schedule_id IN (SELECT schedule_id FROM schedule_details);";
        $arrResult = $dataHelper->fetchRecords("QR", $strSqlStatement);
        return $arrResult;
    }
    catch (Exception $e)
    {
        throw new Exception("dashboard.inc.php : getTotalMeetingDurationByID : Could not fetch records : " . $e->getMessage(), 144);
    }
}

function getTotalDistinctInviteeCountByID($user_id, $dataHelper) {
    if (!is_object($dataHelper))
    {
        throw new Exception("dashboard.inc.php : getTotalMeetingDurationByID : DataHelper Object did not instantiate", 104);
    }
    try
    {
        $strSqlStatement = "SELECT COUNT(DISTINCT invitee_email_address) AS DistinctInvitee FROM schedule_details AS sd, invitation_details AS id WHERE sd.user_id ='" . trim($user_id) . "' AND sd.schedule_id = id.schedule_id AND invitation_creator != 'C';";
        $arrResult = $dataHelper->fetchRecords("QR", $strSqlStatement);
        return $arrResult;
    }
    catch (Exception $e)
    {
        throw new Exception("dashboard.inc.php : getTotalMeetingDurationByID : Could not fetch records : " . $e->getMessage(), 144);
    }
}

function getProfileCompletePercentByID($user_id, $dataHelper) {
    if (!is_object($dataHelper))
    {
        throw new Exception("dashboard.inc.php : getProfileCompletePercentByID : DataHelper Object did not instantiate", 104);
    }
    try
    {
        $strSqlStatement = "SELECT user_id, FLOOR((
CASE WHEN nick_name IS NULL OR nick_name = '' THEN 0  ELSE 1 END +
CASE WHEN first_name IS NULL OR first_name = '' THEN 0 ELSE 1 END +
CASE WHEN last_name IS NULL OR last_name = '' THEN 0  ELSE 1 END +
CASE WHEN secondry_email IS NULL OR secondry_email = '' THEN 0  ELSE 1 END +
CASE WHEN landmark IS NULL OR landmark = '' THEN 0  ELSE 1 END +
CASE WHEN city IS NULL OR city = '' THEN 0  ELSE 1 END +
CASE WHEN address IS NULL OR address = '' THEN 0  ELSE 1 END +
CASE WHEN country_name IS NULL OR country_name = '' THEN 0  ELSE 1 END +
CASE WHEN timezones IS NULL OR timezones = '' THEN 0  ELSE 1 END +
CASE WHEN gmt IS NULL OR gmt = '' THEN 0  ELSE 1 END +
CASE WHEN phone_number IS NULL OR phone_number = '' THEN 0  ELSE 1 END +
CASE WHEN idd_code IS NULL OR idd_code = '' THEN 0  ELSE 1 END +
CASE WHEN mobile_number IS NULL OR mobile_number = '' THEN 0  ELSE 1 END +
CASE WHEN industry_type IS NULL OR industry_type = '' THEN 0  ELSE 1 END +
CASE WHEN company_name IS NULL OR company_name = '' THEN 0  ELSE 1 END +
CASE WHEN nature_business IS NULL OR nature_business = '' THEN 0  ELSE 1 END +
CASE WHEN company_uri IS NULL OR company_uri = '' THEN 0  ELSE 1 END +
CASE WHEN brief_desc_company IS NULL OR brief_desc_company = '' THEN 0  ELSE 1 END +
CASE WHEN facebook IS NULL OR facebook = '' THEN 0  ELSE 1 END +
CASE WHEN twitter IS NULL OR twitter = '' THEN 0  ELSE 1 END +
CASE WHEN googleplus IS NULL OR googleplus = '' THEN 0  ELSE 1 END +
CASE WHEN linkedin IS NULL OR linkedin = '' THEN 0  ELSE 1 END 
) * 100 / 22) AS 'ProfilePercentage' FROM user_details WHERE user_id = '" . trim($user_id) . "';";
        $arrResult = $dataHelper->fetchRecords("QR", $strSqlStatement);
        return $arrResult;
    }
    catch (Exception $e)
    {
        throw new Exception("dashboard.inc.php : getProfileCompletePercentByID : Could not fetch records : " . $e->getMessage(), 144);
    }
}

function getFrequentInvitees($user_id, $noOfInvitees, $dataHelper) {

    if (!is_object($dataHelper))
    {
        throw new Exception("dashboard.inc.php : getTotalMeetingDurationByID : DataHelper Object did not instantiate", 104);
    }

    if (strlen(trim($user_id)) <= 0)
    {
        throw new Exception("dashboard.inc.php: getFrequentInvitees  : Missing Parameter user id.", 141);
    }

    if (!is_int($noOfInvitees))
    {
        throw new Exception("dashboard.inc.php: getFrequentInvitees  : noOfInvitees should be a integee.", 141);
    }

    if ($noOfInvitees > 0)
    {
        $limit = "Limit 0," . $noOfInvitees;
    }

    try
    {
        //$strSqlStatement = "SELECT  DISTINCT (id.invitee_email_address) AS 'noOfOcurance' FROM  schedule_details sd, invitation_details id, personal_contact_details pcd WHERE  sd.user_id = '" . trim($user_id) . "' AND sd.schedule_id = id.schedule_id AND id.invitee_email_address = pcd.contact_email_address AND pcd.user_id = sd.user_id Group By id.invitee_email_address Order By noOfOcurance Desc;";
        $strSqlStatement = "SELECT DISTINCT contact_nick_name, contact_email_address FROM schedule_details AS sd, invitation_details AS id,  personal_contact_details AS pcd WHERE sd.user_id = '" . trim($user_id) . "' AND sd.schedule_id = id.schedule_id AND id.invitee_email_address = pcd.contact_email_address;";
        $arrFrequentInvitees = $dataHelper->fetchRecords("QR", $strSqlStatement);
        return $arrFrequentInvitees;
    }
    catch (Exception $e)
    {
        throw new Exception("dashboard.inc.php : getFrequentInvitees : Could not fetch records : " . $e->getMessage(), 144);
    }
}

function getMeetingOverviewByID($email_address, $dataHelper) {
    if (!is_object($dataHelper))
    {
        throw new Exception("dashboard.inc.php : getMeetingOverviewByID : DataHelper Object did not instantiate", 104);
    }
    try
    {
        $strSqlStatement = "SELECT "
                . "CASE schedule_status "
                . "WHEN '2' THEN 'Completed' "
                . "WHEN '3' THEN 'Canceled' "
                . "WHEN '4' THEN 'Overdue' "
                . "END AS 'label', COUNT(schedule_status) AS 'data', "
                . "CASE schedule_status "
                . "WHEN '2' THEN '#82af6f' "
                . "WHEN '3' THEN '#d15b47' "
                . "WHEN '4' THEN '#f89406' "
                . "END AS 'color' "
                . "FROM schedule_details AS sd, invitation_details AS id  WHERE sd.schedule_id = id.schedule_id AND sd.schedule_status IN ('2','3','4') AND id.invitee_email_address = '" . trim($email_address) . "' GROUP BY schedule_status ORDER BY label;";
        $arrResult = $dataHelper->fetchRecords("QR", $strSqlStatement);
        return $arrResult;
    }
    catch (Exception $e)
    {
        throw new Exception("dashboard.inc.php : getMeetingOverviewByID : Could not fetch records : " . $e->getMessage(), 144);
    }
}

function getMinuteBaseMeetingGraphByID($user_id, $dataHelper) {
    if (!is_object($dataHelper))
    {
        throw new Exception("dashboard.inc.php : getMinuteBaseMeetingGraphByID : DataHelper Object did not instantiate", 104);
    }
    try
    {
        $strSqlStatement = "SELECT COUNT(sd.schedule_id) AS 'SchedueCount', SUM(IFNULL(TIMESTAMPDIFF( MINUTE , meeting_start_time, meeting_end_time),0)) AS 'TotalMinute',  DATE_FORMAT( meeting_start_time,'%d-%m-%Y' ) AS 'DateOfMeeting' FROM schedule_details AS sd WHERE user_id = '" . trim($user_id) . "' AND schedule_status = '2' GROUP BY DateOfMeeting  ORDER BY DateOfMeeting;";
        $arrResult = $dataHelper->fetchRecords("QR", $strSqlStatement);
        return $arrResult;
    }
    catch (Exception $e)
    {
        throw new Exception("dashboard.inc.php : getMinuteBaseMeetingGraphByID : Could not fetch records : " . $e->getMessage(), 144);
    }
}

function getTotalMeetingCurrentMonth($user_id, $dataHelper) {
    if (!is_object($dataHelper))
    {
        throw new Exception("dashboard.inc.php : getTotalMeetingCurrentMonth : DataHelper Object did not instantiate", 104);
    }
    try
    {

        $strSqlStatement = "SELECT  
	meeting_title AS 'title', 
	CONCAT( \"new Date(\", YEAR( meeting_timestamp_gmt ) , \",\", MONTH( meeting_timestamp_gmt ) -1, \",\", DAYOFMONTH( meeting_timestamp_gmt ) , \",\" , HOUR( meeting_timestamp_gmt ), \",\" , MINUTE( meeting_timestamp_gmt )  , \")\" ) AS 'start', 
	CASE schedule_status  WHEN 
	'0' THEN 'label-inverse' WHEN 
	'1' THEN 'label-info' WHEN 
	'2' THEN 'label-success' WHEN 
	'3' THEN 'label-danger' WHEN 
	'4' THEN 'label-warning' END as \"className\" , 
	sd.schedule_id as 'schedule_id' , 
	MD5(CONCAT(schedule_id,\":\",uld.user_name,\":\",'" . SECRET_KEY . "')) as 'secKey' 
	FROM 
	schedule_details sd, 
	user_login_details uld, 
	user_details ud 
	WHERE  
	uld.user_id = '" . trim($user_id) . "' AND 
	uld.user_id = ud.user_id  AND 
	uld.user_id = sd.user_id AND 
	uld.login_enabled = '1';";

        $arrResult = $dataHelper->fetchRecords("QR", $strSqlStatement);
        return $arrResult;
    }
    catch (Exception $e)
    {
        throw new Exception("dashboard.inc.php : getTotalMeetingCurrentMonth : Could not fetch records : " . $e->getMessage(), 144);
    }
}
