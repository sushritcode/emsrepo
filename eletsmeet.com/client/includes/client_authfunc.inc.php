<?php

/* -----------------------------------------------------------------------------
  Function Name : isAuthenticClient
  Purpose       : To Authenticate Admin User
  Parameters    : email_address, password, Datahelper
  Returns       : array (with admin_id, email_address, password)
  Calls         : datahelper.fetchRecords
  Called By     :
  Author            : Mitesh Shah
  Created  on   : 25-May-2015
  Modified By   :
  Modified on   :
-------------------------------------------------------------------------------- */

function isAuthenticClient($email_address, $password, $dataHelper) {
    if (!is_object($dataHelper))
    {
        throw new Exception("client_authfunc.inc.php : isAuthenticClient : DataHelper Object did not instantiate", 104);
    }

    if (strlen(trim($email_address)) <= 0)
    {
        throw new Exception("client_authfunc.inc.php: isAuthenticClient : Missing Parameter email_address.", 141);
    }

    if (strlen(trim($password)) <= 0)
    {
        throw new Exception("client_authfunc.inc.php: isAuthenticClient : Missing Parameter password.", 142);
    }

    try
    {
        $strSqlStatement = "SELECT client_id, partner_id, client_name, client_logo_url, "
                . "client_email_address, client_lastlogin_dtm, status "
                . "FROM client_details "
                . "WHERE client_email_address='" . trim($email_address) . "' AND client_password='" . trim($password) . "' AND  status = '1';";
        $arrAuthResult = $dataHelper->fetchRecords("QR", $strSqlStatement);
        return $arrAuthResult;
    }
    catch (Exception $e)
    {
        throw new Exception("client_authfunc.inc.php : isAuthenticClient : Could not fetch records : " . $e->getMessage(), 144);
    }
}

/* -----------------------------------------------------------------------------
  Function Name : setClientSession
  Purpose       : To set the logged in admin user cookie.
  Parameters    : admin_id, email_address, password
  Returns       :
  Calls         : php.setcookie
  Called By     :
  Author        : Mitesh Shah
  Created  on   : 25-May-2015
  Modified By   :
  Modified on   :
-------------------------------------------------------------------------------- */

function setClientSession($id, $email_address) {
    global $objErr;
    if (strlen(trim($id)) <= 0)
    {
        throw new Exception("client_authfunc.inc.php: setClientSession : Missing Parameter admin_id.", 151);
    }

    if (strlen(trim($email_address)) <= 0)
    {
        throw new Exception("client_authfunc.inc.php: setClientSession : Missing Parameter email_address.", 152);
    }

    try
    {
        $strCookieValue = $id . chr(5) . $email_address;
        session_start();
        $_SESSION[CLIENT_SESSION_NAME] = $strCookieValue;
    }
    catch (Exception $e)
    {
        throw new Exception("client_authfunc.inc.php : setClientSession : Could not Set User Session." . $e->getMessage(), 156);
    }
}

/* -----------------------------------------------------------------------------
  Function Name : getClientUserSession
  Purpose       : To get the logged in client admin details from cookie.
  Parameters    :
  Returns       : id, email_address
  Calls         :
  Called By     :
  Author        : Mitesh Shah
  Created  on   : 25-May-2015
  Modified By   :
  Modified on   :
-------------------------------------------------------------------------------- */

function getClientSession() {
    global $objErr; 
    session_start();
    $strSessionContents = $_SESSION[CLIENT_SESSION_NAME];

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
  Returns       : 
  Calls         :
  Called By     :
  Author        : Mitesh Shah
  Created  on   : 25-May-2015
  Modified By   :
  Modified on   :
------------------------------------------------------------------------------- */
function unsetClientSession() {
    try
    {
        session_start();
        unset($_SESSION[CLIENT_SESSION_NAME]);
        return true;
    }
    catch (Exception $e)
    {
        return false;
    }
}

/* -----------------------------------------------------------------------------
  Function Name : updClientLastLoginDtls
  Purpose       : To update client last logged in datetime
  Parameters    :
  Returns       : email_address
  Calls         : datahelper.putRecords
  Called By     :
  Author        : Mitesh Shah
  Created  on   : 25-May-2015
  Modified By   :
  Modified on   :
-------------------------------------------------------------------------------- */
function updClientLastLoginDtls($client_id, $email_address, $random_id, $datetime, $ipaddress, $dataHelper) {
    if (!is_object($dataHelper))
    {
        throw new Exception("client_authfunc.inc.php : updClientLastLoginDtls : DataHelper Object did not instantiate", 104);
    }

    try
    {
        $strSqlStatement = "UPDATE client_details SET client_lastlogin_dtm = '".trim($datetime)."', client_login_id = '".trim($random_id)."', client_login_ip_address = '".trim($ipaddress)."' WHERE client_email_address = '" . trim($email_address) . "' AND client_id ='".trim($client_id)."';";
        $arrAuthResult = $dataHelper->putRecords("QR", $strSqlStatement);
        return $arrAuthResult;
    }
    catch (Exception $e)
    {
        throw new Exception("client_authfunc.inc.php : updClientLastLoginDtls : Could not update status : " . $e->getMessage(), 144);
    }
}

/* -----------------------------------------------------------------------------
  Function Name : getClientDetailsByID
  Purpose       : To update admin last logged in datetime
  Parameters    :
  Returns       : email_address
  Calls         : datahelper.fetchRecords
  Called By     :
  Author        : Mitesh Shah
  Created  on   : 25-May-2015
  Modified By   :
  Modified on   :
-------------------------------------------------------------------------------- */

function getClientDetailsByID($email_address, $dataHelper) {
    if (!is_object($dataHelper))
    {
        throw new Exception("client_authfunc.inc.php : getClientDetailsByID : DataHelper Object did not instantiate", 104);
    }

    if (strlen(trim($email_address)) <= 0)
    {
        throw new Exception("client_authfunc.inc.php: getClientDetailsByID : Missing Parameter email_address.", 141);
    }

    try
    {
        $strSqlStatement = "SELECT client_id, partner_id, client_name, client_logo_flag, client_logo_url, client_email_address, client_lastlogin_dtm, client_creation_dtm, auth_mode, auth_api_url, import_contact_url, rt_server_name, rt_server_salt, rt_server_api_url, logout_url, status FROM client_details WHERE client_email_address = '".trim($email_address)."';";
        $arrAuthResult = $dataHelper->fetchRecords("QR", $strSqlStatement);
        return $arrAuthResult;
    }
    catch (Exception $e)
    {
        throw new Exception("client_authfunc.inc.php : getClientDetailsByID : Could not fetch records : " . $e->getMessage(), 144);
    }
}