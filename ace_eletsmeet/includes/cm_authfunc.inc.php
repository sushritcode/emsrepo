<?php

/* -----------------------------------------------------------------------------
  Function Name : isAuthenticUser
  Purpose       : To Authenticate User
  Parameters    : email_address, password, Datahelper
  Returns       : array (with user_id, client_id, email_address, password, nick_name, first_name, last_name, country_code, phone_number, mobile_number, status)
  Calls         : datahelper.fetchRecords
  Called By     :
  Author        : Mitesh Shah
  Created  on   : 19-July-2015
  Modified By   :
  Modified on   :
  ------------------------------------------------------------------------------ */

function isAuthenticUser($email_address, $password, $dataHelper) {
    if (!is_object($dataHelper))
    {
        throw new Exception("cm_authfunc.inc.php : isAuthenticUser : DataHelper Object did not instantiate", 104);
    }

    if (strlen(trim($email_address)) <= 0)
    {
        throw new Exception("cm_authfunc.inc.php: isAuthenticUser : Missing Parameter email_address.", 141);
    }

    if (strlen(trim($password)) <= 0)
    {
        throw new Exception("cm_authfunc.inc.php: isAuthenticUser : Missing Parameter password.", 142);
    }

    try
    {
        $strSqlStatement = "SELECT lu.user_id, lu.client_id, lu.partner_id, lu.email_address, ud.nick_name FROM user_login_details lu , user_details ud WHERE lu.email_address='" . trim($email_address) . "' AND lu.password='" . trim($password) . "' AND  lu.login_enabled = '1' and lu.user_id = ud.user_id";
        $arrAuthResult = $dataHelper->fetchRecords("QR", $strSqlStatement);
        return $arrAuthResult;
    }
    catch (Exception $e)
    {
        throw new Exception("cm_authfunc.inc.php : isAuthenticUser : Could not fetch records : " . $e->getMessage(), 144);
    }
}

/** Session Management for authentication of the user * */
/* -----------------------------------------------------------------------------
  Function Name : setLMUserSession
  Purpose       : To set the logged in user session.
  Parameters    : user_id, email_address, password, client_id, nick_name
  Returns       :
  Calls         : php.setcookie
  Called By     :
  Author        : Mitesh Shah
  Created  on   : 19-July-2015
  Modified By   :
  Modified on   :
  ------------------------------------------------------------------------------ */

function setLMUserSession($user_id, $email_address, $client_id, $nick_name) {

    global $objErr;
    if (strlen(trim($user_id)) <= 0)
    {
        throw new Exception("cm_authfunc.inc.php: setLMUserSession : Missing Parameter user_id.", 151);
    }

    if (strlen(trim($email_address)) <= 0)
    {
        throw new Exception("cm_authfunc.inc.php: setLMUserSession : Missing Parameter email_address.", 152);
    }

    if (strlen(trim($client_id)) <= 0)
    {
        throw new Exception("cm_authfunc.inc.php: setLMUserSession : Missing Parameter client_id.", 154);
    }

    if (strlen(trim($nick_name)) <= 0)
    {
        throw new Exception("cm_authfunc.inc.php: setLMUserSession : Missing Parameter nick_name.", 155);
    }

    try
    {
        $strCookieValue = $user_id . chr(5) . $email_address . chr(5) . $client_id . chr(5) . $nick_name;
        session_start();
        $_SESSION[USER_SESSION_NAME] = $strCookieValue;
    }
    catch (Exception $e)
    {
        throw new Exception("cm_authfunc.inc.php : setLMUserSession : Could not Set User Session." . $e->getMessage(), 156);
    }
}

/* -----------------------------------------------------------------------------
  Function Name : getUserSession
  Purpose       : To get the logged in user details from cookie.
  Parameters    :
  Returns       : user_id, email_address, password, client_id
  Calls         :
  Called By     :
  Author        : Mitesh Shah
  Created  on   : 19-July-2015
  Modified By   :
  Modified on   :
  ------------------------------------------------------------------------------ */

function getUserSession() {
    global $objErr; //Access Error Object
    session_start();
    $strSessionContents = $_SESSION[USER_SESSION_NAME];

    if ($strSessionContents != "")
    {
        $arrSession = explode(chr(5), $strSessionContents);
    }
    return $arrSession;
}

/* -----------------------------------------------------------------------------
  Function Name : unsetUserSession
  Purpose       : To unset the logged in user .
  Parameters    :
  Returns       : user_id, email_address, password, client_id
  Calls         :
  Called By     :
  Author        : Mitesh Shah
  Created  on   : 19-July-2015
  Modified By   :
  Modified on   :
  ------------------------------------------------------------------------------ */

function unsetUserSession() {
    try
    {
        session_start();
        unset($_SESSION[USER_SESSION_NAME]);
        return true;
    }
    catch (Exception $e)
    {
        return false;
    }
}

/* -----------------------------------------------------------------------------
  Function Name : updUserLastLoginDtls
  Purpose       : To update User last logged in datetime
  Parameters    :
  Returns       : email_address
  Calls         : datahelper.putRecords
  Called By     :
  Author        : Mitesh Shah
  Created  on   : 19-July-2015
  Modified By   :
  Modified on   :
  -------------------------------------------------------------------------------- */

function updUserLastLoginDtls($email_address, $user_id, $datetime, $ipaddress, $dataHelper) {
    if (!is_object($dataHelper))
    {
        throw new Exception("cm_authfunc.inc.php : updUserLastLoginDtls : DataHelper Object did not instantiate", 104);
    }

    try
    {
        $strSqlStatement = "UPDATE user_login_details SET user_lastlogin_dtm = '" . trim($datetime) . "', user_login_ip_address = '" . trim($ipaddress) . "' WHERE user_name = '" . trim($email_address) . "' AND user_id='" . trim($user_id) . "';";
        $arrAuthResult = $dataHelper->putRecords("QR", $strSqlStatement);
        return $arrAuthResult;
    }
    catch (Exception $e)
    {
        throw new Exception("cm_authfunc.inc.php : updUserLastLoginDtls : Could not update status : " . $e->getMessage(), 144);
    }
}

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

function getUserDetailsByID($email_address, $dataHelper) {
    if (!is_object($dataHelper))
    {
        throw new Exception("cm_authfunc.inc.php : getUserDetailsByID : DataHelper Object did not instantiate", 104);
    }

    if (strlen(trim($email_address)) <= 0)
    {
        throw new Exception("cm_authfunc.inc.php: getUserDetailsByID : Missing Parameter email_address.", 141);
    }

    try
    {
        $strSqlStatement = "SELECT lu.user_id, lu.user_name , nick_name , first_name , last_name , country_name , timezones , gmt , phone_number , idd_code , mobile_number "
                . "FROM user_details AS ud, user_login_details AS lu, client_details AS cd "
                . "WHERE lu.user_name ='" . trim($email_address) . "' "
                . "ANd lu.user_id = ud.user_id AND cd.client_id = lu.client_id AND lu.login_enabled = '1'; ";
        $arrAuthResult = $dataHelper->fetchRecords("QR", $strSqlStatement);
        return $arrAuthResult;
    }
    catch (Exception $e)
    {
        throw new Exception("cm_authfunc.inc.php : getUserDetailsByID : Could not fetch records : " . $e->getMessage(), 144);
    }
}