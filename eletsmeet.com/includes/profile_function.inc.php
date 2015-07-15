<?php

/* -----------------------------------------------------------------------------
  Function Name : updateUserPassword
  Purpose       : To update password of user while profile update.
  Parameters    : email_address, old_password, new_password, Datahelper
  Returns       : array (with status, user_id, client_id, email)
  Calls         : datahelper.putRecords
  Called By     :
  Author        : Priti Mahajan
  Created  on   : 20-July-2012
  Modified By   :
  Modified on   :
  ------------------------------------------------------------------------------ */

function updateUserPassword($email_address, $old_password, $new_password, $dataHelper)
{
    if (!is_object($dataHelper))
    {
        throw new Exception("profile_authfunc.inc.php : updateUserPassword : DataHelper Object did not instantiate", 104);
    }

    if (strlen(trim($email_address)) <= 0)
    {
        throw new Exception("profile_authfunc.inc.php: updateUserPassword : Missing Parameter email_address.", 141);
    }

    if (strlen(trim($old_password)) <= 0)
    {
        throw new Exception("profile_authfunc.inc.php: updateUserPassword : Missing Parameter old_password.", 142);
    }

    if (strlen(trim($new_password)) <= 0)
    {
        throw new Exception("profile_authfunc.inc.php: updateUserPassword : Missing Parameter new_password.", 143);
    }

    try
    {
        if (!is_object($dataHelper))
        {
            throw new Exception("profile_authfunc.inc.php : updateUserPassword : DataHelper Object did not instantiate", 104);
        }
        $dataHelper->setParam("'".$email_address."'", "I");
        $dataHelper->setParam("'".$old_password."'", "I");
        $dataHelper->setParam("'".$new_password."'", "I");
        $dataHelper->setParam("STATUS", "O");
        $dataHelper->setParam("USER_ID", "O");
        $dataHelper->setParam("CLIENT_ID", "O");
        $dataHelper->setParam("EMAIL", "O");
        $arrUpdatePwd = $dataHelper->putRecords("SP", 'UpdateUserPassword');
        $dataHelper->clearParams();
        return $arrUpdatePwd;
    }
    catch (Exception $e)
    {
        throw new Exception(" profile_authfunc.inc.php : updateUserPassword : Failed : ".$e->getMessage(), 145);
    }
}

/* -----------------------------------------------------------------------------
  Function Name : isEmailIdExists
  Purpose       : To check whether email address exists while login.
  Parameters    : email_address, Datahelper
  Returns       : user_id, email_address
  Calls         : datahelper.fetchRecords
  Called By     :
  Author        : Priti Mahajan
  Created  on   : 20-July-2012
  Modified By   :
  Modified on   :
  ------------------------------------------------------------------------------ */

function isEmailIdExists($email_address, $dataHelper)
{
    if (!is_object($dataHelper))
    {
        throw new Exception("profile_authfunc.inc.php : isEmailIdExists : DataHelper Object did not instantiate", 104);
    }

    if (strlen(trim($email_address)) <= 0)
    {
        throw new Exception("profile_authfunc.inc.php: isEmailIdExists : Missing Parameter email_address.", 141);
    }

    try
    {
        $strSqlStatement = "SELECT user_id, email_address FROM user_details WHERE email_address='".trim($email_address)."' AND status = '1'";
        $arrAuthResult = $dataHelper->fetchRecords("QR", $strSqlStatement);
        return $arrAuthResult;
    }
    catch (Exception $e)
    {
        throw new Exception("cm_authfunc.inc.php : isEmailIdExists : Could not fetch records : ".$e->getMessage(), 144);
    }
}

/* -----------------------------------------------------------------------------
  Function Name : addPwdRequestDtm
  Purpose       : To add password details into password_request_details table while reset password.
  Parameters    : user_id, email_address, time_stamp, Datahelper
  Returns       :
  Calls         : datahelper.putRecords
  Called By     :
  Author        : Priti Mahajan
  Created  on   : 20-July-2012
  Modified By   :
  Modified on   :
  ------------------------------------------------------------------------------ */

function addPwdRequestDtm($user_id, $email_address, $time_stamp, $dataHelper)
{
    if (!is_object($dataHelper))
    {
        throw new Exception("profile_authfunc.inc.php : addPwdRequestDtm : DataHelper Object did not instantiate", 104);
    }

    if (strlen(trim($user_id)) <= 0)
    {
        throw new Exception("profile_authfunc.inc.php: addPwdRequestDtm : Missing Parameter user_id.", 141);
    }

    if (strlen(trim($email_address)) <= 0)
    {
        throw new Exception("profile_authfunc.inc.php: addPwdRequestDtm : Missing Parameter email_address.", 141);
    }

    if (strlen(trim($time_stamp)) <= 0)
    {
        throw new Exception("profile_authfunc.inc.php: addPwdRequestDtm : Missing Parameter time_stamp.", 141);
    }

    try
    {
        $strSqlStatement = "INSERT INTO password_request_details(requested_by, email_address, request_datetime) VALUES('".$user_id."', '".$email_address."', '".$time_stamp."')";
        $arrPutRecord = $dataHelper->putRecords("QR", $strSqlStatement);
        return $arrPutRecord;
    }
    catch (Exception $e)
    {
        throw new Exception("cm_authfunc.inc.php : addPwdRequestDtm : Could not fetch records : ".$e->getMessage(), 144);
    }
}

/* -----------------------------------------------------------------------------
  Function Name : getRequestPwdDetails
  Purpose       : To get password details from password_request_details table.
  Parameters    : email_address, Datahelper
  Returns       : array (with email_address, request_datetime)
  Calls         : datahelper.fetchRecords
  Called By     :
  Author        : Priti Mahajan
  Created  on   : 20-July-2012
  Modified By   :
  Modified on   :
  ------------------------------------------------------------------------------ */

function getRequestPwdDetails($email_address, $dataHelper)
{
    if (!is_object($dataHelper))
    {
        throw new Exception("profile_authfunc.inc.php : getRequestPwdDetails : DataHelper Object did not instantiate", 104);
    }

    if (strlen(trim($email_address)) <= 0)
    {
        throw new Exception("profile_authfunc.inc.php: getRequestPwdDetails : Missing Parameter email_address.", 141);
    }

    try
    {
        $strSqlStatement = "SELECT email_address, request_datetime FROM password_request_details WHERE email_address='".trim($email_address)."' AND request_id = (SELECT MAX(request_id) FROM password_request_details) GROUP BY email_address";
        $arrPwdResult = $dataHelper->fetchRecords("QR", $strSqlStatement);
        return $arrPwdResult;
    }
    catch (Exception $e)
    {
        throw new Exception("cm_authfunc.inc.php : getRequestPwdDetails : Could not fetch records : ".$e->getMessage(), 144);
    }
}

/* -----------------------------------------------------------------------------
  Function Name : resetUserPassword
  Purpose       : To reset password when requested for a new one.
  Parameters    : email_address, new_password, Datahelper
  Returns       :
  Calls         : datahelper.putRecords
  Called By     :
  Author        : Priti Mahajan
  Created  on   : 20-July-2012
  Modified By   :
  Modified on   :
  ------------------------------------------------------------------------------ */

function resetUserPassword($email_address, $new_password, $dataHelper)
{
    if (!is_object($dataHelper))
    {
        throw new Exception("profile_authfunc.inc.php : resetUserPassword : DataHelper Object did not instantiate", 104);
    }

    if (strlen(trim($email_address)) <= 0)
    {
        throw new Exception("profile_authfunc.inc.php: resetUserPassword : Missing Parameter email_address.", 141);
    }

    if (strlen(trim($new_password)) <= 0)
    {
        throw new Exception("profile_authfunc.inc.php: resetUserPassword : Missing Parameter $new_password.", 141);
    }

    try
    {
        $strSqlStatement = "UPDATE user_details SET password = '".trim($new_password)."' WHERE email_address='".trim($email_address)."' AND status = '1'";
        $arrAuthResult = $dataHelper->putRecords("QR", $strSqlStatement);
        return $arrAuthResult;
    }
    catch (Exception $e)
    {
        throw new Exception("cm_authfunc.inc.php : isEmailIdExists : Could not fetch records : ".$e->getMessage(), 144);
    }
}

/* -----------------------------------------------------------------------------
  Function Name : deleteRequestPwd
  Purpose       : To delete password details from password_request_details table after password is changed .
  Parameters    : email_address, Datahelper
  Returns       :
  Calls         : datahelper.putRecords
  Called By     :
  Author        : Priti Mahajan
  Created  on   : 20-July-2012
  Modified By   :
  Modified on   :
  ------------------------------------------------------------------------------ */

function deleteRequestPwd($email_address, $dataHelper)
{
    if (!is_object($dataHelper))
    {
        throw new Exception("profile_authfunc.inc.php : deleteRequestPwd : DataHelper Object did not instantiate", 104);
    }

    if (strlen(trim($email_address)) <= 0)
    {
        throw new Exception("profile_authfunc.inc.php: deleteRequestPwd : Missing Parameter email_address.", 141);
    }

    try
    {
        $strSqlStatement = "DELETE FROM password_request_details WHERE email_address='".trim($email_address)."'";
        $arrPwdResult = $dataHelper->putRecords("QR", $strSqlStatement);
        return $arrPwdResult;
    }
    catch (Exception $e)
    {
        throw new Exception("cm_authfunc.inc.php : deleteRequestPwd : Could not fetch records : ".$e->getMessage(), 144);
    }
}

/* -----------------------------------------------------------------------------
  Function Name : getUserDetailsByUserId
  Purpose       : To get user details for profile update.
  Parameters    : user_id, Datahelper
  Returns       : array (with user details)
  Calls         : datahelper.fetchRecords
  Called By     :
  Author        : Priti Mahajan
  Created  on   : 23-July-2012
  Modified By   :
  Modified on   :
  ------------------------------------------------------------------------------ */

function getUserDetailsByUserId($user_id, $dataHelper)
{
    if (!is_object($dataHelper))
    {
        throw new Exception("profile_function.inc.php : getUserDetailsByUserId : DataHelper Object did not instantiate", 104);
    }

    if (strlen(trim($user_id)) <= 0)
    {
        throw new Exception("profile_function.inc.php : getUserDetailsByUserId : Missing Parameter user_id.", 141);
    }

    try
    {
        $dataHelper->setParam("'".$user_id."'", "I");
        $arrUserDetails = $dataHelper->fetchRecords("SP", 'GetUserDetailsByUserId');
        $dataHelper->clearParams();
        return $arrUserDetails;
    }
    catch (Exception $e)
    {
        throw new Exception(" profile_function.inc.php : getUserDetailsByUserId : Failed : ".$e->getMessage(), 145);
    }
}

/* -----------------------------------------------------------------------------
  Function Name : updUserDetails
  Purpose       : To upadte user details.
  Parameters    : user_id, nick_name, first_name, last_name, country_name, timezone, gmt, idd_code, mobile, Datahelper
  Returns       : array (with status, message)
  Calls         : datahelper.fetchRecords
  Called By     :
  Author        : Priti Mahajan
  Created  on   : 20-July-2012
  Modified By   :
  Modified on   :
  ------------------------------------------------------------------------------ */

function updUserDetails($user_id, $nick_name, $first_name, $last_name, $country_name, $timezone, $gmt, $idd_code, $mobile, $dataHelper)
{
    if (!is_object($dataHelper))
    {
        throw new Exception("signup_function.inc.php : updUserDetails : DataHelper Object did not instantiate", 104);
    }

    if (strlen(trim($user_id)) <= 0)
    {
        throw new Exception("signup_function.inc.php : updUserDetails : Missing Parameter user_id.", 142);
    }

    if (strlen(trim($nick_name)) <= 0)
    {
        throw new Exception("signup_function.inc.php : updUserDetails : Missing Parameter nick_name.", 142);
    }

    if (strlen(trim($first_name)) <= 0)
    {
        throw new Exception("signup_function.inc.php : updUserDetails : Missing Parameter first_name.", 142);
    }

    if (strlen(trim($last_name)) <= 0)
    {
        throw new Exception("signup_function.inc.php : updUserDetails : Missing Parameter last_name.", 143);
    }

    if (strlen(trim($country_name)) <= 0)
    {
        throw new Exception("signup_function.inc.php : updUserDetails : Missing Parameter country_name.", 143);
    }

    if (strlen(trim($timezone)) <= 0)
    {
        throw new Exception("signup_function.inc.php : updUserDetails : Missing Parameter timezone.", 143);
    }

    if (strlen(trim($gmt)) <= 0)
    {
        throw new Exception("signup_function.inc.php : updUserDetails : Missing Parameter gmt.", 143);
    }

    if (strlen(trim($idd_code)) <= 0)
    {
        throw new Exception("signup_function.inc.php : updUserDetails : Missing Parameter idd_code.", 143);
    }

    try
    {
        $dataHelper->setParam("'".$user_id."'", "I");
        $dataHelper->setParam("'".$nick_name."'", "I");
        $dataHelper->setParam("'".$first_name."'", "I");
        $dataHelper->setParam("'".$last_name."'", "I");
        $dataHelper->setParam("'".$country_name."'", "I");
        $dataHelper->setParam("'".$timezone."'", "I");
        $dataHelper->setParam("'".$gmt."'", "I");
        $dataHelper->setParam("'".$idd_code."'", "I");
        $dataHelper->setParam("'".$mobile."'", "I");

        $dataHelper->setParam("STATUS", "O");
        $dataHelper->setParam("MESSAGE", "O");
        $arrUpdDetails = $dataHelper->putRecords("SP", 'UpdateUserDetails');
        $dataHelper->clearParams();
        return $arrUpdDetails;
    }
    catch (Exception $e)
    {
        throw new Exception(" signup_function.inc.php : updUserDetails : Failed : ".$e->getMessage(), 145);
    }
}

?>
