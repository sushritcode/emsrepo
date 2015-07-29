<?php

/* -----------------------------------------------------------------------------
  Function Name : getUserDetailsByID
  Purpose       : To get user details from User Email Address
  Parameters    :
  Returns       : email_address
  Calls         : datahelper.fetchRecords
  Called By     :
  Author        : Mitesh Shah
  Created  on   : 19-July-2015
  Modified By   :
  Modified on   :
  -------------------------------------------------------------------------------- */

function getUserDetailsByID($user_id, $dataHelper) {
    if (!is_object($dataHelper))
    {
        throw new Exception("cm_authfunc.inc.php : getUserDetailsByID : DataHelper Object did not instantiate", 104);
    }
    try
    {

         $strSqlStatement = "SELECT lu.user_id, lu.user_name , lu.client_id, lu.partner_id, lu.email_address, ud.nick_name, ud.first_name, ud.last_name, ud.country_name, ud.timezones, ud.gmt, ud.phone_number, ud.idd_code, ud.mobile_number, ud.secondry_email , ud.landmark , ud.city , ud.address , ud.country_name , ud.timezones , ud.gmt , ud.idd_code , ud.mobile_number , ud.industry_type , ud.company_name , ud.nature_business , ud.company_uri , ud.brief_desc_company , ud.facebook , ud.twitter , ud.googleplus , ud.linkedin FROM user_details AS ud, user_login_details AS lu, client_details AS cd WHERE lu.user_id ='" . trim($user_id) . "' AND lu.user_id = ud.user_id AND cd.client_id = lu.client_id AND lu.login_enabled = '1'; ";

        //$strSqlStatement = "SELECT lu.user_id, lu.user_name , nick_name , first_name , last_name , country_name , timezones , gmt , phone_number , idd_code , mobile_number "
        //     . "FROM user_details AS ud, user_login_details AS lu, client_details AS cd "
        //  . "WHERE lu.user_name ='" . trim($email_address) . "' "
        //. "ANd lu.user_id = ud.user_id AND cd.client_id = lu.client_id AND lu.login_enabled = '1'; ";
        $arrAuthResult = $dataHelper->fetchRecords("QR", $strSqlStatement);
        return $arrAuthResult;
    }
    catch (Exception $e)
    {
        throw new Exception("cm_authfunc.inc.php : getUserDetailsByID : Could not fetch records : " . $e->getMessage(), 144);
    }
}

/* -----------------------------------------------------------------------------
  Function Name : isUserEmailAddessExists
  Purpose       : To check whether user email address exists in database or not.
  Parameters    : email_address, Datahelper
  Returns       : user_id, email_address
  Calls         : datahelper.fetchRecords
  Called By     :
  Author        : Mitesh Shah
  Created  on   : 19-July-2015
  Modified By   :
  Modified on   :
  ------------------------------------------------------------------------------ */

function isUserEmailAddressExists($email_address, $dataHelper) {
    if (!is_object($dataHelper))
    {
        throw new Exception("common_function.inc.php : isUserEmailAddressExists : DataHelper Object did not instantiate", 104);
    }

    if (strlen(trim($email_address)) <= 0)
    {
        throw new Exception("common_function.inc.php: isUserEmailAddressExists : Missing Parameter email_address.", 141);
    }

    try
    {
        $strSqlStatement = "SELECT user_id, email_address FROM user_login_details WHERE email_address='" . trim($email_address) . "';";
        $arrAuthResult = $dataHelper->fetchRecords("QR", $strSqlStatement);
        return $arrAuthResult;
    }
    catch (Exception $e)
    {
        throw new Exception("cm_authfunc.inc.php : isUserEmailAddressExists : Could not fetch records : " . $e->getMessage(), 144);
    }
}

/* -----------------------------------------------------------------------------
  Function Name : getTimezoneList
  Purpose       : To Get Timezone List from country_timezones, country_details
  Parameters    :  Datahelper
  Returns       :
  Calls         : datahelper.fetchRecords
  Called By     :
  Author        : Mitesh Shah
  Created  on   : 19-July-2015
  Modified By   :
  Modified on   :
  ------------------------------------------------------------------------------ */

function getTimezoneList($dataHelper) {
    if (!is_object($dataHelper))
    {
        throw new Exception("common_function.inc.php : getTimezoneList : DataHelper Object did not instantiate", 104);

    }
    try
    {
        $strSqlStatement = "SELECT  ct.ct_id , cd.country_name,  cd.country_code, ct.timezones, ct.gmt FROM country_details cd, country_timezones ct WHERE cd.country_code = ct.country_code AND country_status='1'  ORDER BY cd.country_name";
        $arrList = $dataHelper->fetchRecords("QR", $strSqlStatement);
        return $arrList;
    }
    catch (Exception $e)
    {
        throw new Exception("common_function.inc.php : Fetch Time zone Failed : " . $e->getMessage(), 1107);
    }
}

/* -----------------------------------------------------------------------------
  Function Name : getAllIndustryType
  Purpose       : To fetch the list of all the industry types.
  Parameters    : Datahelper
  Returns       : List of Companies
  Calls         : datahelper.fetchRecords
  Called By     :
  Author        : Sushrit
  Created  on   : 23-July-2015
  Modified By   :
  Modified on   :
  Descriptions  : Table columns are `industry_id`, `industry_name`, `status`
  ------------------------------------------------------------------------------ */

function getAllIndustryType($dataHelper) {
    if (!is_object($dataHelper))
    {
        throw new Exception("common_function.inc.php : isUserEmailAddressExists : DataHelper Object did not instantiate", 104);
    }
    try
    {
        $strSqlStatement = "SELECT * FROM industry_details WHERE status like '1';";
        $arrIndustryTypes = $dataHelper->fetchRecords("QR", $strSqlStatement);
        return $arrIndustryTypes;
    }
    catch (Exception $e)
    {
        throw new Exception("common_function.inc.php : getAllCompanyType : Could not fetch records : " . $e->getMessage(), 144);
    }
}


/* -----------------------------------------------------------------------------
  Function Name : getDistinctCountry
  Purpose       : To Get Distinct country for mall the supported time zones
  Parameters    :  Datahelper
  Returns       :
  Calls         : datahelper.fetchRecords
  Called By     :
  Author        : Sushrit 
  Created  on   : July 28 , 2015
  Modified By   :
  Modified on   :
  ------------------------------------------------------------------------------ */

function getDistinctCountry($dataHelper) {
    if (!is_object($dataHelper))
    {
        throw new Exception("common_function.inc.php : getDistinctCountry : DataHelper Object did not instantiate", 104);

    }
    try
    {
        $strSqlStatement = "SELECT DISTINCT cd.country_id, cd.country_name, cd.country_code, cd.country_idd_code FROM country_details cd, country_timezones ct
WHERE cd.country_code = ct.country_code AND cd.country_status = '1' ORDER BY cd.country_name";
        $arrList = $dataHelper->fetchRecords("QR", $strSqlStatement);
        return $arrList;
	
    }
    catch (Exception $e)
    {
        throw new Exception("common_function.inc.php : Fetch Distinct Country Failed : " . $e->getMessage(), 1107);
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
function getUpdateQueryString($formValues , $formTableMap)
{
	if(isset($formValues['formname']))
	{
		if($formValues['formname'] != "") 
		{
			$updateString = "";

			foreach($formValues as $key => $value)
			{
				if($key != "formname")
				{
					if(isset($formTableMap[$formValues['formname']][$key]) && $value !="")
					{
						$updateString .= ($updateString!="")?" , ":"";
						$updateString .= $formTableMap[$formValues['formname']][$key]." = \"".trim($value)."\"";
					}
				}
			}
			return $updateString;
		}
	}
}

