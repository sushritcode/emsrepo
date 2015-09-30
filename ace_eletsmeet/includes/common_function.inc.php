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
        $strSqlStatement = "SELECT lu.user_id, lu.user_name , lu.client_id, lu.partner_id, lu.email_address, ud.nick_name, ud.first_name, ud.last_name, ud.country_name, ud.timezones, ud.gmt, ud.phone_number, ud.idd_code, ud.mobile_number FROM user_details AS ud, user_login_details AS lu, client_details AS cd WHERE lu.user_id ='" . trim($user_id) . "' AND lu.user_id = ud.user_id AND cd.client_id = lu.client_id AND lu.login_enabled = '1'; ";

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
        $strSqlStatement = "SELECT  cd.country_name,  cd.country_code, ct.timezones, ct.gmt FROM country_details cd, country_timezones ct WHERE cd.country_code = ct.country_code AND country_status='1'  ORDER BY cd.country_name";
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

function isAuthenticateScheduleUser($user_id, $client_id, $dataHelper) {
    try
    {
        if (!is_object($dataHelper))
        {
            throw new Exception("api_function.inc.php : isAuthenticateScheduleUser : DataHelper Object did not instantiate", 104);
        }

                $strSqlStatement = "SELECT user_login_details.user_id, user_login_details.login_enabled as user_status, client_details.client_id, client_details.status as client_status " .
                "FROM user_login_details, client_details " .
                "WHERE user_login_details.login_enabled= '1' " .
                "AND client_details.client_id = user_login_details.client_id " .
                "AND client_details.status= '1' " .
                "AND user_login_details.user_id = '" . trim($user_id) . "' " .
                "AND client_details.client_id = '" . trim($client_id) . "'";
        $arrAuthSchResult = $dataHelper->fetchRecords("QR", $strSqlStatement);
        $dataHelper->clearParams();
        return $arrAuthSchResult;
    }
    catch (Exception $e)
    {
        throw new Exception("api_function.inc.php : isAuthenticateScheduleUser : Could not fetch records : " . $e->getMessage(), 2013);
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
    $meeting_date = date("D, F jS Y, h:i A", strtotime($localTime)) . "  (" . $timezone . ", GMT ".$date_format.")  (" . date("D, F jS Y, h:i A", strtotime($gmTime)) . " GMT)";
    return $meeting_date;
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
                . "AND schedule_id IN (SELECT schedule_id  FROM schedule_details WHERE schedule_status NOT IN('2','5') AND schedule_id = '" . trim($schedule_id) . "');";
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

					
						$columnName = $formTableMap[$formValues['formname']][$key];
						$columnValue = ($columnName == "password")?md5(trim($value)):trim($value);
						$updateString .= ($updateString!="")?" , ":"";
						$updateString .= $columnName." = \"".$columnValue."\"";
					}
				}
			}
			return $updateString;
		}
	}
}

/* -----------------------------------------------------------------------------
  Function Name : getInsertQueryString
  Purpose       : to generate the insert query string
  Parameters    :  array Form values
  Returns       :
  Calls         : 
  Called By     :
  Author        : Sushrit 
  Created  on   : Aug 5 , 2015
  Modified By   :
  Modified on   :
  ------------------------------------------------------------------------------ */

function getInsertQueryString($formValues , $formTableMap)
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
						if(trim($value) == "")
							return -1;
						$columnName.=($columnName != "")?" , ":"";
						$columnName.=$formTableMap[$formValues['formname']][$key];
						$columnValue.=($columnValue != "")?" , ":"";
						$columnValue.="'".$value."'";
					}
				}
			}
			$insertString = " ( ".$columnName." )  VALUES ( ".$columnValue." ) ;";
			return $insertString;
		}
	}
}
/* -----------------------------------------------------------------------------
  Function Name : getDistinctCountryByCountryName
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

function getDistinctCountryByCountryName($countryName , $dataHelper) {
    if (!is_object($dataHelper))
    {
        throw new Exception("common_function.inc.php : getDistinctCountry : DataHelper Object did not instantiate", 104);

    }
    try
    {
        $strSqlStatement = "SELECT DISTINCT cd.country_id, cd.country_name, cd.country_code, cd.country_idd_code FROM country_details cd, country_timezones ct
WHERE cd.country_code = ct.country_code AND cd.country_status = '1' AND TRIM(LOWER(cd.country_name)) like  '".trim(strtolower($countryName))."'";
        $arrList = $dataHelper->fetchRecords("QR", $strSqlStatement);
        return $arrList;
	
    }
    catch (Exception $e)
    {
        throw new Exception("common_function.inc.php : Fetch Distinct Country Failed : " . $e->getMessage(), 1107);
    }
}

