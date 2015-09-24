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
        $strSqlStatement = "SELECT lu.user_id, lu.client_id, lu.partner_id, lu.email_address, ud.nick_name FROM user_login_details lu, user_details ud WHERE lu.email_address='" . trim($email_address) . "' AND lu.password='" . trim($password) . "' AND  lu.login_enabled = '1' and lu.user_id = ud.user_id;";
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
  Returns       : 
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
  Returns       : 
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
  Function Name : getUserLoginDetailsByUserName
  Purpose       : To get user login details from User Email Address
  Parameters    : user_name, Datahelper
  Returns       : 
  Calls         : datahelper.fetchRecords
  Called By     :
  Author        : Mitesh Shah
  Created  on   : 19-July-2015
  Modified By   :
  Modified on   :
  -------------------------------------------------------------------------------- */

function getUserLoginDetailsByUserName($user_name, $dataHelper) {
    if (!is_object($dataHelper))
    {
        throw new Exception("cm_authfunc.inc.php : getUserLoginDetailsByID : DataHelper Object did not instantiate", 104);
    }

    if (strlen(trim($user_name)) <= 0)
    {
        throw new Exception("cm_authfunc.inc.php: getUserLoginDetailsByID : Missing Parameter email_address.", 141);
    }

    try
    {
        $strSqlStatement = "SELECT ud.user_id, user_name, cd.client_id, cd.partner_id, email_address, role, login_enabled, createdOn, createdBy, user_lastlogin_dtm, user_login_ip_address, cd.client_name, cd.client_logo_flag, cd.client_logo_url "
                . "FROM user_details AS ud, user_login_details AS lu, client_details AS cd "
                . "WHERE lu.user_name ='" . trim($user_name) . "' "
                . "ANd lu.user_id = ud.user_id AND cd.client_id = lu.client_id AND lu.login_enabled = '1'; ";
        $arrAuthResult = $dataHelper->fetchRecords("QR", $strSqlStatement);
        return $arrAuthResult;
    }
    catch (Exception $e)
    {
        throw new Exception("cm_authfunc.inc.php : getUserLoginDetailsByID : Could not fetch records : " . $e->getMessage(), 144);
    }
}


/* -----------------------------------------------------------------------------
   Function Name : getUserImage
Purpose       : To fetch the user profile pic tables as per the user input
Parameters    : 
Returns       :
Calls         : 
Called By     :
Author        : Sushrit
Created  on   : 23-August-2015
Modified By   :
Modified on   :
------------------------------------------------------------------------------ */
function getUserImage($user_id , $objDataHelper)
{	try
	{	

		if (strlen(trim($user_id)) <= 0) 
		{
			throw new Exception("common_function.inc.php: getUserImage : Missing Parameter user_id.", 141);
		}
		if(!isset($objDataHelper))
		{
			throw new Exception("common_function.inc.php: getUserImage : Datahlper not set.", 141);
		}
		$strQueryUserImage = "Select * from user_images where user_id ='".$user_id."'";
		$arrUserImage = $objDataHelper->fetchRecords("QR",$strQueryUserImage);
		return $arrUserImage;
	}
	catch(Exception $e)
	{
		throw new Exception("common_.inc.php : getUserImage : Could not find records : " . $e->getMessage(), 144);

	}

}

/* -----------------------------------------------------------------------------
 Function Name : createThumbnail
Purpose       : To fetch the user profile pic tables as per the user input
Parameters    : 
Returns       :
Calls         : 
Called By     :
Author        : Sushrit
Created  on   : 23-August-2015
Modified By   :
Modified on   :
------------------------------------------------------------------------------ */
function createThumbnail($filepath, $thumbpath, $thumbnail_width, $thumbnail_height) 
{
	list($original_width, $original_height, $original_type) = getimagesize($filepath);
	if ($original_width > $original_height) 
	{
		$new_width = $thumbnail_width;
		$new_height = intval($original_height * $new_width / $original_width);
	} else 
	{
		$new_height = $thumbnail_height;
		$new_width = intval($original_width * $new_height / $original_height);
	}
	$dest_x = intval(($thumbnail_width - $new_width) / 2);
	$dest_y = intval(($thumbnail_height - $new_height) / 2);

	if ($original_type === 1) {
		$imgt = "ImageGIF";
		$imgcreatefrom = "ImageCreateFromGIF";
	} else if ($original_type === 2) {
		$imgt = "ImageJPEG";
		$imgcreatefrom = "ImageCreateFromJPEG";
	} else if ($original_type === 3) {
		$imgt = "ImagePNG";
		$imgcreatefrom = "ImageCreateFromPNG";
	} else {
		return false;
	}
	try
	{
	print "1".$old_image = $imgcreatefrom($filepath);
	print "2".$new_image = imagecreatetruecolor($thumbnail_width, $thumbnail_height);
	imagecopyresampled($new_image, $old_image, $dest_x, $dest_y, 0, 0, $new_width, $new_height, $original_width, $original_height);
	$imgt($new_image, $thumbpath);
	}
	catch(Exception $e)
	{
		print_r($e);
		exit;
	}
	print "here is the path ".$thumbpath;

	return file_exists($thumbpath);
}


