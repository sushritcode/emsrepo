<?php

/* -----------------------------------------------------------------------------
  Function Name : isAuthenticAdminUser
  Purpose       : To Authenticate Admin User
  Parameters    : email_address, password, Datahelper
  Returns       : array (with admin_id, email_address, password)
  Calls         : datahelper.fetchRecords
  Called By     :
  Author        : 
  Created  on   : 29-August-2012
  Modified By   :
  Modified on   :
  ------------------------------------------------------------------------------ */

function isAuthenticAdminUser($email_address, $password, $dataHelper)
{
    if (!is_object($dataHelper))
    {
        throw new Exception("adm_authfunc.inc.php : isAuthenticAdminUser : DataHelper Object did not instantiate", 104);
    }

    if (strlen(trim($email_address)) <= 0)
    {
        throw new Exception("adm_authfunc.inc.php: isAuthenticAdminUser : Missing Parameter email_address.", 141);
    }

    if (strlen(trim($password)) <= 0)
    {
        throw new Exception("adm_authfunc.inc.php: isAuthenticAdminUser : Missing Parameter password.", 142);
    }

    try
    {
        $strSqlStatement = "SELECT admin_id, email_address, password, lastlogin_dtm, admin_creation_dtm, client_id, partner_id, status, flag FROM admin_login WHERE email_address='" . trim($email_address) . "' AND password='" . trim($password) . "' AND  status = '1'";
        $arrAuthResult = $dataHelper->fetchRecords("QR", $strSqlStatement);
        return $arrAuthResult;
    }
    catch (Exception $e)
    {
        throw new Exception("adm_authfunc.inc.php : isAuthenticAdminUser : Could not fetch records : " . $e->getMessage(), 144);
    }
}

/* -----------------------------------------------------------------------------
  Function Name : setAdminUserCookie
  Purpose       : To set the logged in admin user cookie.
  Parameters    : admin_id, email_address, password
  Returns       :
  Calls         : php.setcookie
  Called By     :
  Author        : 
  Created  on   : 29-August-2012
  Modified By   :
  Modified on   :
  ------------------------------------------------------------------------------ */

function setAdminUserCookie($admin_id, $email_address, $password)
{
    global $objErr;
    if (strlen(trim($admin_id)) <= 0)
    {
        throw new Exception("adm_authfunc.inc.php: setAdminUserCookie : Missing Parameter admin_id.", 151);
    }

    if (strlen(trim($email_address)) <= 0)
    {
        throw new Exception("adm_authfunc.inc.php: setAdminUserCookie : Missing Parameter email_address.", 152);
    }

    if (strlen(trim($password)) <= 0)
    {
        throw new Exception("adm_authfunc.inc.php: setAdminUserCookie : Missing Parameter password.", 153);
    }
    
    try
    {
        $strCookieValue = $admin_id . chr(5) . $email_address . chr(5) . $password;
        setcookie(ADM_COOKIE_NAME, $strCookieValue, 0, "/");
    }
    catch (Exception $e)
    {
        throw new Exception("adm_authfunc.inc.php : setAdminUserCookie : Could not Set User Session." . $e->getMessage(), 156);
    }
}

/* -----------------------------------------------------------------------------
  Function Name : getAdminUserCookie
  Purpose       : To get the logged in user details from cookie.
  Parameters    :
  Returns       : admin_id, email_address, password
  Calls         :
  Called By     :
  Author        : 
  Created  on   : 29-August-2012
  Modified By   :
  Modified on   :
  ------------------------------------------------------------------------------ */

function getAdminUserCookie()
{
    global $objErr; //Access Error Object
    $strCookieContents = $_COOKIE[ADM_COOKIE_NAME];
    if ($strCookieContents != "")
    {
        $arrCookie = explode(chr(5), $strCookieContents);
    }
    return $arrCookie;
}

/* -----------------------------------------------------------------------------
  Function Name : updAdminLastLoginDtls
  Purpose       : To update admin last logged in datetime
  Parameters    :
  Returns       : email_address
  Calls         :
  Called By     :
  Author        : 
  Created  on   : 29-August-2012
  Modified By   :
  Modified on   :
  ------------------------------------------------------------------------------ */
function updAdminLastLoginDtls($email_address, $dataHelper)
{
    if (!is_object($dataHelper))
    {
        throw new Exception("user_function.inc.php : updAdminLastLoginDtls : DataHelper Object did not instantiate", 104);
    }

    try
    {
        $strSqlStatement = "UPDATE admin_login SET lastlogin_dtm = now() WHERE email_address = '".trim($email_address)."'";
        $arrAuthResult = $dataHelper->putRecords("QR", $strSqlStatement);
        return $arrAuthResult;
    }
    catch (Exception $e)
    {
        throw new Exception("user_function.inc.php : updAdminLastLoginDtls : Could not update status : ".$e->getMessage(), 144);
    }
}

/* -----------------------------------------------------------------------------
  Function Name : setAdminUserSession
  Purpose       : To set the logged in admin user cookie.
  Parameters    : admin_id, email_address, password
  Returns       :
  Calls         : php.setcookie
  Called By     :
  Author        : 
  Created  on   : 29-August-2012
  Modified By   :
  Modified on   :
  ------------------------------------------------------------------------------ */

function setAdminUserSession($admin_id, $email_address)
{
    global $objErr;
    if (strlen(trim($admin_id)) <= 0)
    {
        throw new Exception("adm_authfunc.inc.php: setAdminUserSession : Missing Parameter admin_id.", 151);
    }

    if (strlen(trim($email_address)) <= 0)
    {
        throw new Exception("adm_authfunc.inc.php: setAdminUserSession : Missing Parameter email_address.", 152);
    }
    
    try
    {
        $strCookieValue = $admin_id . chr(5) . $email_address;
        session_start();
        $_SESSION[ADM_SESSION_NAME] =$strCookieValue;
    }
    catch (Exception $e)
    {
        throw new Exception("adm_authfunc.inc.php : setAdminUserSession : Could not Set User Session." . $e->getMessage(), 156);
    }
}

/* -----------------------------------------------------------------------------
  Function Name : getAdminUserSession
  Purpose       : To get the logged in user details from cookie.
  Parameters    :
  Returns       : admin_id, email_address, password
  Calls         :
  Called By     :
  Author        : 
  Created  on   : 29-August-2012
  Modified By   :
  Modified on   :
  ------------------------------------------------------------------------------ */

function getAdminUserSession()
{
    global $objErr; //Access Error Object
    session_start();
    $strSessionContents = $_SESSION[ADM_SESSION_NAME];
    
    if ($strSessionContents != "")
    {
        $arrSession = explode(chr(5), $strSessionContents);
    }
    return $arrSession;
}

/* -----------------------------------------------------------------------------
  Function Name : unsetAdminUserSession
  Purpose       : To unset the logged in user .
  Parameters    :
  Returns       : user_id, email_address, password, client_id
  Calls         :
  Called By     :
  Author        : Mitesh Shah
  Created  on   : 13-June-2012
  Modified By   :
  Modified on   :
  ------------------------------------------------------------------------------ */
function unsetAdminUserSession()
{
    try
    {
        session_start();
        unset($_SESSION[ADM_SESSION_NAME]);
        return true;
    }
    catch(Exception $e)
    {
        return false;
    }
}

function getAdminUserDetailsByID($email_address, $dataHelper)
{
    if (!is_object($dataHelper))
    {
        throw new Exception("adm_authfunc.inc.php : getAdminUserDetailsByID : DataHelper Object did not instantiate", 104);
    }

    if (strlen(trim($email_address)) <= 0)
    {
        throw new Exception("adm_authfunc.inc.php: getAdminUserDetailsByID : Missing Parameter email_address.", 141);
    }

    try
    {
        $strSqlStatement = "SELECT admin_id, email_address, password, lastlogin_dtm, admin_creation_dtm, client_id, partner_id, status, flag FROM admin_login WHERE email_address='" . trim($email_address) . "'";
        $arrAuthResult = $dataHelper->fetchRecords("QR", $strSqlStatement);
        return $arrAuthResult;
    }
    catch (Exception $e)
    {
        throw new Exception("adm_authfunc.inc.php : getAdminUserDetailsByID : Could not fetch records : " . $e->getMessage(), 144);
    }
}