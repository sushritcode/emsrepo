<?php

/* -----------------------------------------------------------------------------
  Function Name : getUserList
  Purpose       : To get user details from user_details table.
  Parameters    : Datahelper
  Returns       : array (with user details)
  Calls         : datahelper.fetchRecords
  Called By     : index.php(User)
  ------------------------------------------------------------------------------ */

function getUserList($dataHelper)
{
    if (!is_object($dataHelper))
    {
        throw new Exception("user_function.inc.php : getUserList : DataHelper Object did not instantiate", 104);
    }

    try
    {
        $strSqlQuery = "SELECT 	
        pd.partner_name,
        cd.client_name,
        ud.user_id,
        ud.client_id, 
	ud.email_address, 
	ud.nick_name, 
	ud.first_name, 
	ud.last_name, 
	ud.country_name, 
	ud.timezones, 
	ud.gmt, 
	ud.phone_number, 
	ud.idd_code, 
	ud.mobile_number, 
	ud.registration_dtm, 
	ud.is_admin, 
	ud.status FROM user_details AS ud, client_details AS cd , partner_details AS pd WHERE pd.partner_id = ud.partner_id AND cd.client_id = ud.client_id AND cd.status = '1' ORDER BY pd.partner_name, cd.client_name, ud.nick_name, ud.first_name, ud.last_name, ud.email_address";
        $arrResult = $dataHelper->fetchRecords("QR", $strSqlQuery);
        return $arrResult;
    }
    catch (Exception $e)
    {
        throw new Exception("user_function.inc.php : Error in getUserList." . $e->getMessage(), 734);
    }
}

/* -----------------------------------------------------------------------------
  Function Name : getUserId
  Purpose       : To get user id from user_details table.
  Parameters    : Datahelper
  Returns       : MAX(user_id)
  Calls         : datahelper.fetchRecords
  Called By     : adduser.php(User) for inserting new user in user_details table
  ------------------------------------------------------------------------------ */

function getUserId($dataHelper)
{
    if (!is_object($dataHelper))
    {
        throw new Exception("user_function.inc.php : getUserId : DataHelper Object did not instantiate", 104);
    }
    try
    {
        $strSqlStatement = "SELECT MAX(user_id) FROM user_details";
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
        throw new Exception("user_function.inc.php : Get User Details Failed : " . $e->getMessage(), 1111);
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
  ------------------------------------------------------------------------------ */

function isUserEmailExists($email_address, $dataHelper)
{
    if (!is_object($dataHelper))
    {
        throw new Exception("user_function.inc.php : isUserEmailExists : DataHelper Object did not instantiate", 104);
    }

    if (strlen(trim($email_address)) <= 0)
    {
        throw new Exception("user_function.inc.php : isUserEmailExists : Missing Parameter email_address.", 141);
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
        throw new Exception("user_function.inc.php : isUserEmailExists : Failed : " . $e->getMessage(), 145);
    }
}

/* -----------------------------------------------------------------------------
  Function Name : insUserDetails
  Purpose       : To insert user details into user_details table.
  Parameters    : user_id, client_id, email_address, pwd, nick_name, first_name, last_name, country_name, timezone,gmt, phone, idd_code, mobile, reg_time, is_admin, status, Datahelper
  Returns       : array (with STATUS, MESSAGE)
  Calls         : datahelper.putRecords
  Called By     : adduser.php(User)
  ------------------------------------------------------------------------------ */

function insUserDetails($user_id, $client_id, $partner_id, $email_address, $pwd, $nick_name, $first_name, $last_name, $country_name, $timezone, $gmt, $phone, $idd_code, $mobile, $reg_time, $is_admin, $status, $dataHelper)
{
    if (!is_object($dataHelper))
    {
        throw new Exception("user_function.inc.php : insUserDetails : DataHelper Object did not instantiate", 104);
    }

    if (strlen(trim($user_id)) <= 0)
    {
        throw new Exception("user_function.inc.php : insUserDetails : Missing Parameter user_id.", 142);
    }

    if (strlen(trim($client_id)) <= 0)
    {
        throw new Exception("user_function.inc.php : insUserDetails : Missing Parameter client_id.", 142);
    }

    if (strlen(trim($partner_id)) <= 0)
    {
        throw new Exception("user_function.inc.php : insUserDetails : Missing Parameter partner_id.", 142);
    }

    if (strlen(trim($email_address)) <= 0)
    {
        throw new Exception("user_function.inc.php : insUserDetails : Missing Parameter email_address.", 141);
    }

    if (strlen(trim($pwd)) <= 0)
    {
        throw new Exception("user_function.inc.php : insUserDetails : Missing Parameter password.", 141);
    }

    if (strlen(trim($nick_name)) <= 0)
    {
        throw new Exception("user_function.inc.php : insUserDetails : Missing Parameter nick_name.", 142);
    }

    if (strlen(trim($first_name)) <= 0)
    {
        throw new Exception("user_function.inc.php : insUserDetails : Missing Parameter first_name.", 142);
    }

    if (strlen(trim($last_name)) <= 0)
    {
        throw new Exception("user_function.inc.php : insUserDetails : Missing Parameter last_name.", 143);
    }

    if (strlen(trim($country_name)) <= 0)
    {
        throw new Exception("user_function.inc.php : insUserDetails : Missing Parameter country_name.", 143);
    }

    if (strlen(trim($timezone)) <= 0)
    {
        throw new Exception("user_function.inc.php : insUserDetails : Missing Parameter timezone.", 143);
    }

    if (strlen(trim($gmt)) <= 0)
    {
        throw new Exception("user_function.inc.php : insUserDetails : Missing Parameter gmt.", 143);
    }

    if (strlen(trim($idd_code)) <= 0)
    {
        throw new Exception("user_function.inc.php : insUserDetails : Missing Parameter idd_code.", 143);
    }

    if (strlen(trim($mobile)) <= 0)
    {
        throw new Exception("user_function.inc.php : insUserDetails : Missing Parameter mobile.", 143);
    }

    if (strlen(trim($reg_time)) <= 0)
    {
        throw new Exception("user_function.inc.php : insUserDetails : Missing Parameter reg_time.", 143);
    }

    if (strlen(trim($is_admin)) <= 0)
    {
        throw new Exception("user_function.inc.php : insUserDetails : Missing Parameter is_admin.", 143);
    }
    
    try
    {
        $dataHelper->setParam("'" . $user_id . "'", "I");
        $dataHelper->setParam("'" . $client_id . "'", "I");
        $dataHelper->setParam("'" . $partner_id . "'", "I");
        $dataHelper->setParam("'" . $email_address . "'", "I");
        $dataHelper->setParam("'" . $pwd . "'", "I");
        $dataHelper->setParam("'" . $nick_name . "'", "I");
        $dataHelper->setParam("'" . $first_name . "'", "I");
        $dataHelper->setParam("'" . $last_name . "'", "I");
        $dataHelper->setParam("'" . $country_name . "'", "I");
        $dataHelper->setParam("'" . $timezone . "'", "I");
        $dataHelper->setParam("'" . $gmt . "'", "I");
        $dataHelper->setParam("'" . $phone . "'", "I");
        $dataHelper->setParam("'" . $idd_code . "'", "I");
        $dataHelper->setParam("'" . $mobile . "'", "I");
        $dataHelper->setParam("'" . $reg_time . "'", "I");
        $dataHelper->setParam("'" . $is_admin . "'", "I");
        $dataHelper->setParam("'" . $status . "'", "I");

        $dataHelper->setParam("STATUS", "O");
        $dataHelper->setParam("MESSAGE", "O");
        $arrAddDetails = $dataHelper->putRecords("SP", 'InsertUserDetails');
        $dataHelper->clearParams();
        return $arrAddDetails;
    }
    catch (Exception $e)
    {
        throw new Exception(" user_function.inc.php : insUserDetails : Failed : " . $e->getMessage(), 145);
    }
}

/* -----------------------------------------------------------------------------
  Function Name : getUserDetailsByClientName
  Purpose       : To get user details by client_name OR first_name from user_details table.
  Parameters    : client_name, first_name, Datahelper
  Returns       : array (with user details)
  Calls         : datahelper.fetchRecords
  Called By     : index.php(User) while searching.
  ------------------------------------------------------------------------------ */

function getUserDetailsByClientName($partner_name, $client_name, $first_name, $dataHelper)
{
    if (!is_object($dataHelper))
    {
        throw new Exception("user_function.inc.php : getUserDetailsByClientName : DataHelper Object did not instantiate", 104);
    }

    try
    {
        $strSqlQuery = "SELECT 
        pd.partner_name,
        cd.client_name,
        ud.user_id,
        ud.client_id, 
	ud.email_address, 
	ud.nick_name, 
	ud.first_name, 
	ud.last_name, 
	ud.country_name, 
	ud.timezones, 
	ud.gmt, 
	ud.phone_number, 
	ud.idd_code, 
	ud.mobile_number, 
	ud.registration_dtm, 
	ud.is_admin, 
	ud.status FROM user_details AS ud, client_details AS cd , partner_details AS pd WHERE pd.partner_id = ud.partner_id AND cd.client_id = ud.client_id AND pd.partner_name LIKE '%" . trim($partner_name) . "%' AND cd.client_name LIKE '%" . trim($client_name) . "%' AND ud.first_name LIKE '%" . trim($first_name) . "%' AND cd.status = '1' ORDER BY pd.partner_name, cd.client_name, ud.nick_name, ud.first_name, ud.last_name, ud.email_address";
        $arrResult = $dataHelper->fetchRecords("QR", $strSqlQuery);
        return $arrResult;
    }
    catch (Exception $e)
    {
        throw new Exception("user_function.inc.php : Error in getUserDetailsByClientName." . $e->getMessage(), 734);
    }
}

/* -----------------------------------------------------------------------------
  Function Name : updUserStatus
  Purpose       : To update user status.
  Parameters    : user_id, status, Datahelper
  Returns       :
  Calls         : datahelper.putRecords
  Called By     :
  ------------------------------------------------------------------------------ */

function updUserStatus($user_id, $status, $dataHelper)
{
    if (!is_object($dataHelper))
    {
        throw new Exception("user_function.inc.php : updUserStatus : DataHelper Object did not instantiate", 104);
    }

    if (strlen(trim($user_id)) <= 0)
    {
        throw new Exception("user_function.inc.php: updUserStatus : Missing Parameter user_id.", 141);
    }

    if (strlen(trim($status)) <= 0)
    {
        throw new Exception("user_function.inc.php: updUserStatus : Missing Parameter status.", 141);
    }

    try
    {
        $strSqlStatement = "UPDATE user_details SET status = '".trim($status)."' WHERE user_id = '".trim($user_id)."'";
        $arrAuthResult = $dataHelper->putRecords("QR", $strSqlStatement);
        return $arrAuthResult;
    }
    catch (Exception $e)
    {
        throw new Exception("user_function.inc.php : updUserStatus : Could not update status : ".$e->getMessage(), 144);
    }
}