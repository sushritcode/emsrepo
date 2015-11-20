<?php

function getClientListByPartner($partner_id, $dataHelper) {
    
    if (!is_object($dataHelper))
    {
        throw new Exception("report_function.inc.php : getClientListByPartner : DataHelper Object did not instantiate", 104);
    }

    try
    {
        if (strlen(trim($partner_id)) > 0)
        {
            $strSqlQuery = " SELECT pd.partner_id, pd.partner_name, cd.client_id, cd.client_name, cd.status "
                    . "FROM partner_details AS pd, client_details AS cd "
                    . "WHERE pd.partner_id = cd.partner_id "
                    . "AND pd.partner_id = '" . trim($partner_id) . "' AND cd.status='1' ORDER BY pd.partner_name, cd.client_name; ";
        }
        else
        {
            $strSqlQuery = " SELECT pd.partner_id, pd.partner_name, cd.client_id, cd.client_name, cd.status "
                    . "FROM partner_details AS pd, client_details AS cd "
                    . "WHERE pd.partner_id = cd.partner_id AND cd.status='1' "
                    . "ORDER BY pd.partner_name, cd.client_name; ";
        }
        $arrResult = $dataHelper->fetchRecords("QR", $strSqlQuery);
        return $arrResult;
    }
    catch (Exception $e)
    {
        throw new Exception("report_function.inc.php : Error in getClientListByPartner." . $e->getMessage(), 734);
    }
}

function getSumOfClientLicenseByType($client_id, $opt_type, $dataHelper) {
    if (!is_object($dataHelper))
    {
        throw new Exception("client_db_function.inc.php : getTotalLicenseByClientId : DataHelper Object did not instantiate", 104);
    }

    try
    {
        $strSqlQuery = "SELECT IFNULL(SUM(no_of_license),0) AS LicenseSum FROM client_license_details WHERE operation_type = '".trim($opt_type)."' AND client_id ='" . trim($client_id) . "';";
        $arrResult = $dataHelper->fetchRecords("QR", $strSqlQuery);
        return $arrResult[0]['LicenseSum'];
    }
    catch (Exception $e)
    {
        throw new Exception("client_db_function.inc.php : Error in getting License details." . $e->getMessage(), 734);
    }
}

function getLicenseDetailsByClient($client_id, $dataHelper) {

    if (!is_object($dataHelper))
    {
        throw new Exception("report_function.inc.php : getLicenseCountByClient : DataHelper Object did not instantiate", 104);
    }

    try
    {
        $strSqlQuery = "SELECT *  FROM client_license_details WHERE client_id = '" . trim($client_id) . "' ";
        $arrResult = $dataHelper->fetchRecords("QR", $strSqlQuery);
        return $arrResult;
    }
    catch (Exception $e)
    {
        throw new Exception("report_function.inc.php : Error in getLicenseCountByClient." . $e->getMessage(), 734);
    }
}

/* -----------------------------------------------------------------------------
  Function Name : getPlanInformation
  Purpose           : To get license details by client_name and partner from partner_details, client_details, user_details, subscription_master table.
  Parameters : Datahelper, client_id
  Returns       :
  Calls             : datahelper.fetchRecords
  Called By     : rpt_plan_expiry.php
  ------------------------------------------------------------------------------ */

function getClientPlanInformation($client_id, $dataHelper) {
    if (!is_object($dataHelper))
    {
        throw new Exception("report_function.inc.php : getClientPlanInformation : DataHelper Object did not instantiate", 104);
    }

    try
    {
        $strSqlQuery = "SELECT pd.partner_name, cd.client_name, cd.client_id, cd.status, csm.plan_name, csm.subscription_start_date_gmt, "
                . "csm.subscription_end_date_gmt, DATEDIFF(csm.subscription_end_date_gmt, DATE_FORMAT(NOW(), '%Y-%m-%d')) AS diff_days  "
                . "FROM partner_details AS pd, client_details AS cd, client_subscription_master AS csm "
                . "WHERE pd.partner_id = cd.partner_id AND cd.client_id = csm.client_id "
                . "AND cd.client_id = '" . trim($client_id) . "' "
                . "ORDER BY pd.partner_name, cd.client_name, csm.subscription_end_date_gmt DESC;";
        $arrResult = $dataHelper->fetchRecords("QR", $strSqlQuery);
        return $arrResult;
    }
    catch (Exception $e)
    {
        throw new Exception("report_function.inc.php : Error in getClientPlanInformation." . $e->getMessage(), 734);
    }
}

/* -----------------------------------------------------------------------------
  Function Name : getMeetingDurationByClient
  Purpose       : To get duration of meetingsfrom partner_details, client_details, user_details, schedule_details table.
  Parameters    : Datahelper, client_id
  Returns       :
  Calls             : datahelper.fetchRecords
  Called By     : index.php while searching.
  ------------------------------------------------------------------------------ */

function getMeetingDurationByClient($client_id, $dataHelper) {
    if (!is_object($dataHelper))
    {
        throw new Exception("report_function.inc.php : getMeetingDurationByClient : DataHelper Object did not instantiate", 104);
    }

    try
    {
        if (strlen(trim($client_id)) > 0)
        {
            $strSqlQuery = "SELECT pd.partner_id,pd.partner_name,cd.client_id,cd.client_name,COUNT(ud.client_id) AS 'TotalMeetings', "
                    . "SUM(IFNULL(TIMESTAMPDIFF(MINUTE,sd.meeting_start_time, sd.meeting_end_time),0)) AS 'TotalDuration'"
                    . "FROM  partner_details AS pd, client_details AS cd, user_details AS ud, schedule_details AS sd  "
                    . "WHERE pd.partner_id = cd.partner_id  AND cd.client_id = ud.client_id "
                    . "AND ud.user_id = sd.user_id AND cd.client_id = '" . trim($client_id) . "'"
                    . " GROUP BY pd.partner_id, ud.client_id ORDER BY pd.partner_id , cd.client_name ";
        }
        else
        {
            $strSqlQuery = "SELECT pd.partner_id,pd.partner_name,cd.client_id,cd.client_name,COUNT(ud.client_id) AS 'TotalMeetings', "
                    . "SUM(IFNULL(TIMESTAMPDIFF(MINUTE,sd.meeting_start_time, sd.meeting_end_time),0)) AS 'TotalDuration'"
                    . "FROM  partner_details AS pd, client_details AS cd, user_details AS ud, schedule_details AS sd  "
                    . "WHERE pd.partner_id = cd.partner_id  AND cd.client_id = ud.client_id AND ud.user_id = sd.user_id"
                    . " GROUP BY pd.partner_id, ud.client_id ORDER BY pd.partner_id , cd.client_name ";
        }
        $arrResult = $dataHelper->fetchRecords("QR", $strSqlQuery);
        return $arrResult;
    }
    catch (Exception $e)
    {
        throw new Exception("report_function.inc.php : Error in getMeetingDurationByClient." . $e->getMessage(), 734);
    }
}

/* -----------------------------------------------------------------------------
  Function Name : getMeetingCountByUser
  Purpose       : To get  number of meetings by client's user from partner_details, client_details, user_details, schedule_details table.
  Parameters    : Datahelper
  Returns       :
  Calls             : datahelper.fetchRecords
  Called By     : index.php while searching.
  ------------------------------------------------------------------------------ */

function getMeetingCountByUser($dataHelper) {
    if (!is_object($dataHelper))
    {
        throw new Exception("report_function.inc.php : getMeetingCountByUser    : DataHelper Object did not instantiate", 104);
    }

    try
    {
        $strSqlQuery = "SELECT pd.partner_id,cd.client_id,cd.client_name,ud.first_name,ud.last_name,ud.user_id,COUNT(ud.user_id) "
                . "AS 'TotalMeetings',SUM(IFNULL(TIMESTAMPDIFF(MINUTE,sd.meeting_start_time, sd.meeting_end_time),0)) AS 'TotalDuration' "
                . "FROM partner_details AS pd, client_details AS cd, user_details AS ud, schedule_details AS sd "
                . "WHERE pd.partner_id = cd.partner_id AND cd.client_id = ud.client_id AND ud.user_id = sd.user_id "
                . "GROUP BY pd.partner_id, ud.client_id,ud.user_id ORDER BY TotalDuration desc";
        $arrResult = $dataHelper->fetchRecords("QR", $strSqlQuery);
        return $arrResult;
    }
    catch (Exception $e)
    {
        throw new Exception("report_function.inc.php : Error in getMeetingCountByUser." . $e->getMessage(), 734);
    }
}
