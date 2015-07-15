<?php

/* -----------------------------------------------------------------------------
  Function Name : GetCountryDetails
  Purpose       : To Get Country Details from country_details Table
  Parameters    : Datahelper
  Returns       : array (with country details)
  Calls         : datahelper.fetchRecords
  Called By     :
  Author        : 
  Created  on   : 16-August-2012
  Modified By   :
  Modified on   :
  ------------------------------------------------------------------------------ */

function getCountryDetails($dataHelper)
{
    if (!is_object($dataHelper))
    {
        throw new Exception("adm_db_common_function.inc.php : getCountryDetails : DataHelper Object did not instantiate", 104);
    }

    try
    {
        $strSqlQuery = "SELECT DISTINCT country_id, country_name, country_code, country_idd_code FROM country_details WHERE country_status = '1' ORDER BY country_name;";
        $arrResult = $dataHelper->fetchRecords("QR", $strSqlQuery);
        return $arrResult;
    }
    catch (Exception $e)
    {
        throw new Exception("adm_db_common_function.inc.php : Error in getting Country Details." . $e->getMessage(), 734);
    }
}

/* -----------------------------------------------------------------------------
  Function Name : getCountryNamebyIdd
  Purpose       : To Get Country Name from country_details Table
  Parameters    : idd_code, Datahelper
  Returns       : array (with DISTINCT country name)
  Calls         : datahelper.fetchRecords
  Called By     :
  Author        : 
  Created  on   : 16-August-2012
  Modified By   :
  Modified on   :
  ------------------------------------------------------------------------------ */

function getCountryNamebyIdd($idd_code, $dataHelper)
{
    if (!is_object($dataHelper))
    {
        throw new Exception("adm_db_common_function.inc.php : GetCountryNamebyIdd : DataHelper Object did not instantiate", 104);
    }

    if (strlen(trim($idd_code)) <= 0)
    {
        throw new Exception("adm_db_common_function.inc.php : GetCountryNamebyIdd : Missing Parameter idd_code.", 143);
    }

    try
    {
        $strSqlQuery = "SELECT DISTINCT country_name FROM country_details WHERE country_status = '1' AND country_idd_code = '" . trim($idd_code) . "';";
        $arrResult = $dataHelper->fetchRecords("QR", $strSqlQuery);
        return $arrResult;
    }
    catch (Exception $e)
    {
        throw new Exception("adm_db_common_function.inc.php : Error in getting CountryNamebyIdd." . $e->getMessage(), 734);
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
        $strSqlStatement = "SELECT cd.country_name, cd.country_code, ct.timezones, ct.gmt FROM country_details AS cd, country_timezones AS ct WHERE cd.country_code = ct.country_code AND country_status = '1' ";
        $arrList = $dataHelper->fetchRecords("QR", $strSqlStatement);
        return $arrList;
    }
    catch (Exception $e)
    {
        throw new Exception("sch_function.inc.php : Fetch Time zone Failed : " . $e->getMessage(), 1107);
    }
}

/* -----------------------------------------------------------------------------
  Function Name : getClientList
  Purpose       : To Get Client List from client_details Table.
  Parameters    : Datahelper
  Returns       : array (with Client Details)
  Calls         : datahelper.fetchRecords
  Called By     :
  Author        : 
  Created  on   : 30-August-2012
  Modified By   :
  Modified on   :
  ------------------------------------------------------------------------------ */

function getClientList($dataHelper)
{
    if (!is_object($dataHelper))
    {
        throw new Exception("adm_db_common_function.inc.php : getClientList : DataHelper Object did not instantiate", 104);
    }
    try
    {
        //$strSqlQuery = "SELECT pd.partner_name, cd.client_id, cd.partner_id, cd.client_name, cd.client_creation_dtm, cd.status FROM client_details AS cd, partner_details AS pd WHERE cd.partner_id = pd.partner_id AND cd.status != '3' ORDER BY pd.partner_name, cd.client_name";
        $strSqlQuery = "SELECT pd.partner_name, cd.client_id, cd.partner_id, cd.client_name, cd.client_creation_dtm, cd.status FROM client_details AS cd, partner_details AS pd WHERE cd.partner_id = pd.partner_id ORDER BY pd.partner_name, cd.client_name";
        $arrResult = $dataHelper->fetchRecords("QR", $strSqlQuery);
        return $arrResult;
    }
    catch (Exception $e)
    {
        throw new Exception("adm_db_common_function.inc.php : Error in getClientList." . $e->getMessage(), 734);
    }
}

/* -----------------------------------------------------------------------------
  Function Name : getPartnerList
  Purpose       : To Get Partner List from partner_details Table.
  Parameters    : Datahelper
  Returns       : array (with Partner Details)
  Calls         : datahelper.fetchRecords
  Called By     :
  Author        : 
  Created  on   : 05-September-2012
  Modified By   :
  Modified on   :
  ------------------------------------------------------------------------------ */

function getPartnerList($dataHelper)
{
    if (!is_object($dataHelper))
    {
        throw new Exception("adm_db_common_function.inc.php : getPartnerList : DataHelper Object did not instantiate", 104);
    }
    try
    {
        $strSqlQuery = "SELECT partner_id, email_address, partner_name, partner_creation_dtm, status FROM partner_details WHERE status = '1' ORDER BY partner_name, partner_creation_dtm";
        $arrResult = $dataHelper->fetchRecords("QR", $strSqlQuery);
        return $arrResult;
    }
    catch (Exception $e)
    {
        throw new Exception("adm_db_common_function.inc.php : Error in getPartnerList." . $e->getMessage(), 734);
    }
}

/* -----------------------------------------------------------------------------
  Function Name : getUserDetailsByUserId
  Purpose       : To get user details for profile update.
  Parameters    : user_id, Datahelper
  Returns       : array (with user details)
  Calls         : datahelper.fetchRecords
  Called By     :
  Author        : 
  Created  on   : 01-October-2012
  Modified By   :
  Modified on   :
  ------------------------------------------------------------------------------ */

function getUserDetailsByUserId($user_id, $dataHelper)
{
    if (!is_object($dataHelper))
    {
        throw new Exception("adm_db_common_function.inc.php : getUserDetailsByUserId : DataHelper Object did not instantiate", 104);
    }

    if (strlen(trim($user_id)) <= 0)
    {
        throw new Exception("adm_db_common_function.inc.php : getUserDetailsByUserId : Missing Parameter user_id.", 141);
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
        throw new Exception(" adm_db_common_function.inc.php : getUserDetailsByUserId : Failed : " . $e->getMessage(), 145);
    }
}

/* -----------------------------------------------------------------------------
  Function Name : getPlanDetails
  Purpose       : To Get Plan Details from plan_details Table
  Parameters    : Datahelper
  Returns       : array (with Plan details)
  Calls         : datahelper.fetchRecords
  Called By     :
  Author        : 
  Created  on   : 01-October-2012
  Modified By   :
  Modified on   :
  ------------------------------------------------------------------------------ */

function getPlanDetails($dataHelper)
{
    if (!is_object($dataHelper))
    {
        throw new Exception("adm_db_common_function.inc.php : getPlanDetails : DataHelper Object did not instantiate", 104);
    }

    try
    {
        $strSqlQuery = "SELECT * FROM plan_details WHERE plan_status = '1' ORDER BY display_order";
        $arrResult = $dataHelper->fetchRecords("QR", $strSqlQuery);
        return $arrResult;
    }
    catch (Exception $e)
    {
        throw new Exception("adm_db_common_function.inc.php : Error in getting Plan details." . $e->getMessage(), 734);
    }
}

/* -----------------------------------------------------------------------------
  Function Name : timezoneConverter
  Purpose       : To Get formatted date.
  Parameters    : type, timestamp, timezone
  Returns       :formatted time
  Calls         :
  Called By     : addsubscription.php(User)
  Author        :
  Created  on   : 01-October-2012
  Modified By   :
  Modified on   :
  ------------------------------------------------------------------------------ */

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

/* -----------------------------------------------------------------------------
  Function Name : getInstanceDetails
  Purpose       : To Get Instance Details from instance_details Table
  Parameters    : Datahelper
  Returns       : array (with Instance details)
  Calls         : datahelper.fetchRecords
  Called By     :
  Author        : 
  Created  on   : 02-February-2015
  Modified By   :
  Modified on   :
  ------------------------------------------------------------------------------ */

function getInstanceDetails($dataHelper)
{
    if (!is_object($dataHelper))
    {
        throw new Exception("adm_db_common_function.inc.php : getInstanceDetails : DataHelper Object did not instantiate", 104);
    }

    try
    {
        $strSqlQuery = "SELECT instance_id, instance_name, instance_url, instance_salt, instance_logout_url, instance_api_url, instance_creation_dtm, instance_stop_dtm, admin_id, status FROM instance_details WHERE status = '1' ORDER BY instance_name";
        $arrResult = $dataHelper->fetchRecords("QR", $strSqlQuery);
        return $arrResult;
    }
    catch (Exception $e)
    {
        throw new Exception("adm_db_common_function.inc.php : Error in getInstanceDetails." . $e->getMessage(), 734);
    }
}

/* -----------------------------------------------------------------------------
  Function Name : getInstanceDetailsByID
  Purpose       : To Get Instance Details from instance_details Table from ID instance_id
  Parameters    : Datahelper
  Returns       : array (with Instance details)
  Calls         : datahelper.fetchRecords
  Called By     :
  Author        : 
  Created  on   : 02-February-2015
  Modified By   :
  Modified on   :
  ------------------------------------------------------------------------------ */

function getInstanceDetailsByID($instance_id,$dataHelper)
{
    if (!is_object($dataHelper))
    {
        throw new Exception("adm_db_common_function.inc.php : getInstanceDetails : DataHelper Object did not instantiate", 104);
    }

    try
    {
        $strSqlQuery = "SELECT instance_id, instance_name, instance_url, instance_salt, instance_logout_url, instance_api_url, instance_creation_dtm, instance_stop_dtm, admin_id, status FROM instance_details WHERE status = '1' AND instance_id = '".trim($instance_id)."' ORDER BY instance_name";
        $arrResult = $dataHelper->fetchRecords("QR", $strSqlQuery);
        return $arrResult;
    }
    catch (Exception $e)
    {
        throw new Exception("adm_db_common_function.inc.php : Error in getInstanceDetails." . $e->getMessage(), 734);
    }
}

/* -----------------------------------------------------------------------------
  Function Name : getClientDtlsById
  Purpose       : To get client details by Client Id
  Parameters    : client_id, Datahelper
  Returns       : array (with client details)
  Calls         : datahelper.fetchRecords
  Called By     : index.php(Client)
  ------------------------------------------------------------------------------ */

function getClientDtlsById( $client_id, $dataHelper)
{
    if (!is_object($dataHelper))
    {
        throw new Exception("client_function.inc.php : getClientList : DataHelper Object did not instantiate", 104);
    }

    try
    {
        $strSqlQuery = "SELECT  * FROM  client_details WHERE client_id ='".trim($client_id)."'";
        $arrResult = $dataHelper->fetchRecords("QR", $strSqlQuery);
        return $arrResult;
    }
    catch (Exception $e)
    {
        throw new Exception("client_function.inc.php : Error in getClientDtlsById." . $e->getMessage(), 734);
    }
}