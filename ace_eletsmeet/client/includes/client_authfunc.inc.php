<?php

/* -----------------------------------------------------------------------------
  Function Name : isAuthenticClient
  Purpose       : To Authenticate Admin User
  Parameters    : client_username, password, Datahelper
  Returns       : array (with client_id, partner_id, client_username, client_password, client_name, client_email_address, client_logo_url,client_last_login_dtm, client_login_enabled)
  Calls         : datahelper.fetchRecords
  Called By     :
  Author            : Mitesh Shah
  Created  on   : 25-May-2015
  Modified By   :
  Modified on   :
-------------------------------------------------------------------------------- */

function isAuthenticClient($client_username, $password, $dataHelper) {
    if (!is_object($dataHelper))
    {
        throw new Exception("client_authfunc.inc.php : isAuthenticClient : DataHelper Object did not instantiate", 104);
    }

    if (strlen(trim($client_username)) <= 0)
    {
        throw new Exception("client_authfunc.inc.php: isAuthenticClient : Missing Parameter.", 141);
    }

    if (strlen(trim($password)) <= 0)
    {
        throw new Exception("client_authfunc.inc.php: isAuthenticClient : Missing Parameter .", 142);
    }

    try
    {
        $strSqlStatement = "SELECT cld.client_id, partner_id, client_username, client_password, client_name, client_email_address, client_logo_url,client_last_login_dtm, client_login_enabled FROM client_login_details AS cld, client_details AS cd WHERE cld.client_id = cd.client_id AND client_username='" . trim($client_username) . "' AND client_password='" . trim($password) . "' AND  client_login_enabled = '1';";
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

//function setClientSession($id, $client_username, $email_address) {
function setClientSession($arrSessionVal) {
    global $objErr;
    /*if (strlen(trim($id)) <= 0)
    {
        throw new Exception("client_authfunc.inc.php: setClientSession : Missing Parameter.", 151);
    }

     if (strlen(trim($client_username)) <= 0)
    {
        throw new Exception("client_authfunc.inc.php: setClientSession : Missing Parameter.", 152);
    }
    
    if (strlen(trim($email_address)) <= 0)
    {
        throw new Exception("client_authfunc.inc.php: setClientSession : Missing Parameter.", 152);
    }*/

    try
    {
        //$strCookieValue = $id . chr(5) . $client_username . chr(5) . $email_address;
	$strCookieValue = "";
	for($i=0;$i<count($arrSessionVal);$i++)
		$strCookieValue.=($strCookieValue =="")?$arrSessionVal[$i]:chr(5).$arrSessionVal[$i];
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
function updClientLastLoginDtls($client_id, $random_id, $datetime, $ipaddress, $dataHelper) {
    if (!is_object($dataHelper))
    {
        throw new Exception("client_authfunc.inc.php : updClientLastLoginDtls : DataHelper Object did not instantiate", 104);
    }

    try
    {
        $strSqlStatement = "UPDATE client_login_details SET client_last_login_dtm = '".trim($datetime)."', client_login_random_id = '".trim($random_id)."', client_login_ip_address = '".trim($ipaddress)."' WHERE client_id ='".trim($client_id)."';";
        $arrAuthResult = $dataHelper->putRecords("QR", $strSqlStatement);
        return $arrAuthResult;
    }
    catch (Exception $e)
    {
        throw new Exception("client_authfunc.inc.php : updClientLastLoginDtls : Could not update status : " . $e->getMessage(), 144);
    }
}

/* -----------------------------------------------------------------------------
  Function Name : getClientDetailsByClientUsername
  Purpose       : 
  Parameters    :
  Returns       : 
  Calls         : datahelper.fetchRecords
  Called By     :
  Author        : Mitesh Shah
  Created  on   : 25-May-2015
  Modified By   :
  Modified on   :
-------------------------------------------------------------------------------- */

function getClientDetailsByClientUsername($client_username, $dataHelper) {
    if (!is_object($dataHelper))
    {
        throw new Exception("client_authfunc.inc.php : getClientDetailsByClientUsername : DataHelper Object did not instantiate", 104);
    }

    if (strlen(trim($client_username)) <= 0)
    {
        throw new Exception("client_authfunc.inc.php: getClientDetailsByClientUsername : Missing Parameter .", 141);
    }

    try
    {
        $strSqlStatement = "SELECT cld.client_id, partner_id, client_username, client_name, client_email_address, client_logo_flag, client_logo_url, client_last_login_dtm, client_login_ip_address, client_login_random_id, client_login_enabled, client_creation_dtm, client_secret_key, auth_mode, auth_api_url, import_contact_url, rt_server_name, rt_server_salt, rt_server_api_url, logout_url FROM client_login_details AS cld, client_details AS cd WHERE cld.client_id = cd.client_id AND cld.client_username = '".trim($client_username)."' AND cld.client_login_enabled = '1' ;";
        //$strSqlStatement = "SELECT client_id, partner_id, client_name, client_logo_flag, client_logo_url, client_email_address, client_last_login_dtm, client_creation_dtm, auth_mode, auth_api_url, import_contact_url, rt_server_name, rt_server_salt, rt_server_api_url, logout_url, status FROM client_details WHERE client_email_address = '".trim($email_address)."';";
        $arrAuthResult = $dataHelper->fetchRecords("QR", $strSqlStatement);
        return $arrAuthResult;
    }
    catch (Exception $e)
    {
        throw new Exception("client_authfunc.inc.php : getClientDetailsByClientUsername : Could not fetch records : " . $e->getMessage(), 144);
    }
}
