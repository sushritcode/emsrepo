<?php

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

function getPasswordRequestDtls($email_address, $dataHelper) {
	if (!is_object($dataHelper)) {
		throw new Exception("profile_function.inc.php : getPasswordRequestDetails : DataHelper Object did not instantiate", 104);
	}

	if (strlen(trim($email_address)) <= 0) {
		throw new Exception("profile_function.inc.php: getPasswordRequestDetails : Missing Parameter email_address.", 141);
	}

	try {
		$strSqlStatement = "SELECT email_address, request_datetime FROM password_request_details WHERE email_address='" . trim($email_address) . "' AND request_id = (SELECT MAX(request_id) FROM password_request_details) GROUP BY email_address";
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

function deletePasswordRequestDtls($email_address, $dataHelper) {
	if (!is_object($dataHelper)) {
		throw new Exception("profile_function.inc.php : deletePasswordRequest : DataHelper Object did not instantiate", 104);
	}

	if (strlen(trim($email_address)) <= 0) {
		throw new Exception("profile_function.inc.php: deletePasswordRequest : Missing Parameter email_address.", 141);
	}

	try {
		$strSqlStatement = "DELETE FROM password_request_details WHERE email_address='" . trim($email_address) . "'";
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

function addPasswordRequestDtls($user_id, $email_address, $time_stamp, $dataHelper) {
	if (!is_object($dataHelper)) {
		throw new Exception("profile_function.inc.php : addPasswordRequestDtls : DataHelper Object did not instantiate", 104);
	}

	if (strlen(trim($user_id)) <= 0) {
		throw new Exception("profile_function.inc.php: addPasswordRequestDtls : Missing Parameter user_id.", 141);
	}

	if (strlen(trim($email_address)) <= 0) {
		throw new Exception("profile_function.inc.php: addPasswordRequestDtls : Missing Parameter email_address.", 141);
	}

	if (strlen(trim($time_stamp)) <= 0) {
		throw new Exception("profile_function.inc.php: addPasswordRequestDtls : Missing Parameter time_stamp.", 141);
	}

	try {
		$strSqlStatement = "INSERT INTO password_request_details(requested_by, email_address, request_datetime) VALUES('" . $user_id . "', '" . $email_address . "', '" . $time_stamp . "')";
		$arrPutRecord = $dataHelper->putRecords("QR", $strSqlStatement);
		return $arrPutRecord;
	} catch (Exception $e) {
		throw new Exception("cm_authfunc.inc.php : addPasswordRequestDtls : Could not fetch records : " . $e->getMessage(), 144);
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
function profile_form_table_map()
{

	//formname

	$arrForms = array("frmbasic"=>array(),"frmcontact"=>array(),"frmaddress"=>array(),"frmSocial"=>array(),"frmCompany"=>array(),"frmBilling"=>array(),"frmpassword"=>array());

	//formelementname 
	$arrForms["frmbasic"] = array("displayname"=>"nick_name","firstname"=>"first_name","lastname"=>"last_name");
	$arrForms["frmcontact"] =  array("phone1"=>"phone_number","mobile"=>"mobile_number","SecondryEmail"=>"secondry_email");
	$arrForms["frmaddress"] = array("landmark"=>"landmark","city"=>"city","address"=>"address","country"=>"country_name" );
	$arrForms["frmSocial"] = array("facebook"=>"facebook","twitter"=>"twitter","googleplus"=>"googleplus","linkedin"=>"linkedin");
	$arrForms["frmCompany"] = array( "companyname"=>"company_name","natureofbusiness"=>"nature_business","companyURL"=>"company_uri","briefDescription"=>"brief_desc_company","indutrytype"=>"industry_type");
	$arrForms["frmBilling"] = array();
	return $arrForms;
}

function updateUserProfile($paramString , $objDataHelper ,$strCK_user_id)
{
	
	try
	{
		
		$tableName = "user_details";
		$criteria  = " Where user_id ='".$strCK_user_id."'";
		$sqlQuery = "UPDATE ".$tableName." SET ".$paramString." ".$criteria;
		$result  = $objDataHelper->putRecords("QR",$sqlQuery);
		return true;
		
	}
	catch(Exception $e)
	{
		throw new Exception("profile.inc.php : updateProfile : Could not update records : " . $e->getMessage(), 144);
		
	}
}




