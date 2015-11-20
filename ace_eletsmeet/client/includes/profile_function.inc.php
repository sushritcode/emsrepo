<?php
/* -----------------------------------------------------------------------------
   Function Name : getPasswordRequestDetailsById
   Purpose       : To get password request details from password_request_details table.
   Parameters    : email_address, Datahelper
   Returns       : array (with email_address, request_datetime)
   Calls         : datahelper.fetchRecords
   Called By     :
   Author        : Mitesh Shah
   Created  on   : 19-July-2015
   Modified By   :
   Modified on   :
   ------------------------------------------------------------------------------ */

function getPasswordRequestDtlsById($email_address, $id, $dataHelper) {
	if (!is_object($dataHelper)) {
		throw new Exception("profile_function.inc.php : getPasswordRequestDetails : DataHelper Object did not instantiate", 104);
	}

	if (strlen(trim($email_address)) <= 0) {
		throw new Exception("profile_function.inc.php: getPasswordRequestDetails : Missing Parameter email_address.", 141);
	}

	try {
		$strSqlStatement = "SELECT * FROM password_request_details WHERE email_address='" . trim($email_address) . "' AND request_id = '".trim($id)."'";
		$arrPwdResult = $dataHelper->fetchRecords("QR", $strSqlStatement);
		return $arrPwdResult;
	} catch (Exception $e) {
		throw new Exception("cm_authfunc.inc.php : getPasswordRequestDetails : Could not fetch records : " . $e->getMessage(), 144);
	}
}



/* -----------------------------------------------------------------------------
   Function Name : getPasswordRequestDetails
Purpose       : To get password request details from password_request_details table.
Parameters    : email_address, Datahelper
Returns       : array (with email_address, request_datetime)
Calls         : datahelper.fetchRecords
Called By     :
Author        : Mitesh Shah
Created  on   : 19-July-2015
Modified By   :
Modified on   :
------------------------------------------------------------------------------ */

function getPasswordRequestDtls($email_address, $strRequestedBy, $dataHelper) {
	if (!is_object($dataHelper)) {
		throw new Exception("profile_function.inc.php : getPasswordRequestDetails : DataHelper Object did not instantiate", 104);
	}

	if (strlen(trim($email_address)) <= 0) {
		throw new Exception("profile_function.inc.php: getPasswordRequestDetails : Missing Parameter email_address.", 141);
	}

	try {
		$strSqlStatement = "SELECT * FROM password_request_details WHERE email_address='" . trim($email_address) . "' AND requested_by ='".trim($strRequestedBy)."'  AND request_id = (SELECT MAX(request_id) FROM password_request_details) GROUP BY email_address";
		$arrPwdResult = $dataHelper->fetchRecords("QR", $strSqlStatement);
		return $arrPwdResult;
	} catch (Exception $e) {
		throw new Exception("cm_authfunc.inc.php : getPasswordRequestDetails : Could not fetch records : " . $e->getMessage(), 144);
	}
}

/* -----------------------------------------------------------------------------
   Function Name : deletePasswordRequestDetails
Purpose       : To delete password request details from password_request_details table.
Parameters    : email_address, Datahelper
Returns       :
Calls         : datahelper.putRecords
Called By     :
Author        : Mitesh Shah
Created  on   : 19-July-2015
Modified By   :
Modified on   :
------------------------------------------------------------------------------ */

function deletePasswordRequestDtls($email_address, $strRequestedBy, $dataHelper) {
	if (!is_object($dataHelper)) {
		throw new Exception("profile_function.inc.php : deletePasswordRequest : DataHelper Object did not instantiate", 104);
	}

	if (strlen(trim($email_address)) <= 0) {
		throw new Exception("profile_function.inc.php: deletePasswordRequest : Missing Parameter email_address.", 141);
	}

	try {
		$strSqlStatement = "DELETE FROM password_request_details WHERE email_address='" . trim($email_address) . "' AND requested_by = '".trim($strRequestedBy)."'";
		$arrPwdResult = $dataHelper->putRecords("QR", $strSqlStatement);
		return $arrPwdResult;
	} catch (Exception $e) {
		throw new Exception("cm_authfunc.inc.php : deletePasswordRequest : Could not fetch records : " . $e->getMessage(), 144);
	}
}

/* -----------------------------------------------------------------------------
   Function Name : addPasswordRequestDtls
Purpose       : To add password request details into password_request_details table for reset password.
Parameters    : user_id, email_address, time_stamp, Datahelper
Returns       :
Calls         : datahelper.putRecords
Called By     :
Author        : Mitesh Shah
Created  on   : 19-July-2015
Modified By   :
Modified on   :
------------------------------------------------------------------------------ */

function addPasswordRequestDtls($client_id, $email_address, $time_stamp, $dataHelper) {
	if (!is_object($dataHelper)) {
		throw new Exception("profile_function.inc.php : addPasswordRequestDtls : DataHelper Object did not instantiate", 104);
	}

	if (strlen(trim($client_id)) <= 0) {
		throw new Exception("profile_function.inc.php: addPasswordRequestDtls : Missing Parameter user_id.", 141);
	}

	if (strlen(trim($email_address)) <= 0) {
		throw new Exception("profile_function.inc.php: addPasswordRequestDtls : Missing Parameter email_address.", 141);
	}

	if (strlen(trim($time_stamp)) <= 0) {
		throw new Exception("profile_function.inc.php: addPasswordRequestDtls : Missing Parameter time_stamp.", 141);
	}

	try {
		$strSqlStatement = "INSERT INTO password_request_details(requested_by, email_address, request_datetime, type) VALUES('" . $client_id . "', '" . $email_address . "', '" . $time_stamp . "','c')";
		$arrPutRecord = $dataHelper->putRecords("QR", $strSqlStatement);
		return $arrPutRecord;
	} catch (Exception $e) {
		throw new Exception("cm_authfunc.inc.php : addPasswordRequestDtls : Could not fetch records : " . $e->getMessage(), 144);
	}
}

/* -----------------------------------------------------------------------------
   Function Name : forgotPwd
Purpose       : To update the users password from loagin box
Parameters    : 
Returns       :
Calls         : 
Called By     :
Author        : Sushrit
Created  on   : 09-Sept-2015
Modified By   :
Modified on   :
------------------------------------------------------------------------------ */

function forgotPwd($objDataHelper)
{

	$forgot_email = trim($_REQUEST['forgot_email']);
	try 
	{
		$arrIsValidEmailResult = isClientEmailIdExists($forgot_email, $objDataHelper);
	} 
	catch (Exception $a) 
	{
		return "01";exit;
	}
	if (is_array($arrIsValidEmailResult) && sizeof($arrIsValidEmailResult) > 0) 
	{
		$clientId = $arrIsValidEmailResult[0]['client_id'];
		$email_address = $arrIsValidEmailResult[0]['client_email_address'];
		$currentTime = GM_DATE;
		$strTimeStamp = strtotime($currentTime);
		$Token = md5($email_address . ":" . $strTimeStamp . ":" . REG_SECRET_KEY);
		$ResetPwdData = "em=" . $email_address . "&ms=" . $strTimeStamp . "&cd=" . $Token.'c';
		try 
		{
			$arrPasswordRequestDtls = getPasswordRequestDtls($email_address, $clientId, $objDataHelper);
			if (is_array($arrPasswordRequestDtls) && sizeof($arrPasswordRequestDtls) > 0) 
			{
				try 
				{
					deletePasswordRequestDtls($email_address, $clientId, $objDataHelper);
				} 
				catch (Exception $e) 
				{
					return "02";exit;
					throw new Exception("index.php : deleteRequestPwd : Error in deleting" . $a->getMessage(), 61333333);
				}
			}
			try 
			{
				$insertPwd = addPasswordRequestDtls($clientId, $email_address, $currentTime, $objDataHelper);
			} 
			catch (Exception $e) 
			{
				return "03";exit;
				throw new Exception("index.php : addPwdRequestDtm : Error in adding pwdDetails" . $a->getMessage(), 61333333);
			}
		} catch (Exception $e) 
		{
			return "04";exit;
			throw new Exception("index.php : getRequestPwdDetails : Error in getting details" . $a->getMessage(), 61333333);
		}

		try
		{
			$ResetPwdData.="&in=".$objDataHelper->last_insert_id;
			resetPasswordMail($email_address, $ResetPwdData, CONST_NOREPLY_EID,'c');
		}
		catch (Exception $e)
		{
			return "05";exit;
			throw new Exception("index.php : resetPasswordMail : Error in password reset".$a->getMessage(), 61333333);
		}
	} 
	else 
	{
		return "06";exit;
	}
	return "1";
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

function resetClientPassword($email_address, $new_password ,$type , $requested_by, $dataHelper)
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
		$strSqlStatement = "UPDATE client_login_details SET client_password = '".trim($new_password)."' WHERE client_username='".trim($email_address)."' AND client_login_enabled = '1'";

		$arrAuthResult = $dataHelper->putRecords("QR", $strSqlStatement);
		return $arrAuthResult;
	}
	catch (Exception $e)
	{
		throw new Exception("cm_authfunc.inc.php : isEmailIdExists : Could not fetch records : ".$e->getMessage(), 144);
	}
}


/* -----------------------------------------------------------------------------
   Function Name : profile_form_table_map
Purpose       : To maintain the mapping of all the html elements and table fields
Parameters    :
Returns       :
Calls         :
Called By     :
Author        : Sushrit
Created  on   : 29-July-2015
Modified By   :
Modified on   :
------------------------------------------------------------------------------ */

function client_profile_form_table_map() {

	//formname

	$arrForms = array("frmbasic" => array(), "frmcontact" => array(), "frmaddress" => array(), "frmSocial" => array(), "frmCompany" => array(), "frmBilling" => array(), "frmpassword" => array());

	//formelementname 
	$arrForms["frmbasic"] = array("displayname" => "nick_name", "firstname" => "first_name", "lastname" => "last_name");
	$arrForms["frmcontact"] = array("phone1" => "phone_number", "mobile" => "mobile_number", "SecondryEmail" => "secondry_email");
	$arrForms["frmaddress"] = array("landmark" => "landmark", "city" => "city", "address" => "address", "country" => "country_name");
	$arrForms["frmSocial"] = array("facebook" => "facebook", "twitter" => "twitter", "googleplus" => "googleplus", "linkedin" => "linkedin");
	$arrForms["frmCompany"] = array("companyname" => "company_name", "natureofbusiness" => "nature_business", "companyURL" => "company_uri", "briefDescription" => "brief_desc_company", "indutrytype" => "industry_type");
	$arrForms["frmBilling"] = array();
	$arrForms["frmpassword"] = array("newpwd" => "client_password");
	return $arrForms;
}
/* -----------------------------------------------------------------------------
   Function Name : updateUserProfile
Purpose       : To update the user profile tables as per the user input
Parameters    :
Returns       :
Calls         :
Called By     :
Author        : Sushrit
Created  on   : 29-July-2015
Modified By   :
Modified on   :
------------------------------------------------------------------------------ */

function updateClientProfile($paramString, $objDataHelper, $client_id, $type) {

	try
	{
		switch ($type)
		{
			case "reset":
				$tableName = "client_details";
				//$strSession = $_SESSION[CLIENT_SESSION_NAME];
				//$arrSession = explode(chr(5), $strSession);
				$criteria = " Where client_id ='" . $client_id . "'";
				$sqlQuery = "UPDATE " . $tableName . " SET " . $paramString . " " . $criteria;
				$result = $objDataHelper->putRecords("QR", $sqlQuery);
				return true;
				break;
			case "resetpwd":
				$tableName = "client_login_details";
				$criteria = " Where client_id ='" . $client_id . "' and client_password='" . md5(trim($_REQUEST["currentpwd"])) . "'";
				$sqlQuery = "UPDATE " . $tableName . " SET " . $paramString . " " . $criteria;
				$result = $objDataHelper->putRecords("QR", $sqlQuery);
				if ($objDataHelper->affectedRows == 0)
					return "101";
				else
					return 1;
				break;
		}
	}
	catch (Exception $e)
	{
		throw new Exception("profile.inc.php : updateProfile : Could not update records : " . $e->getMessage(), 144);
	}
}

