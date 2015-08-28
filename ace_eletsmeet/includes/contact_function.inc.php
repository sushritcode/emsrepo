<?php

/* -----------------------------------------------------------------------------
   Function Name : getAllcontactsByUserID
   Purpose       : retrieve all the contacts on the basis of userid.
   Parameters    : userid, Datahelper
   Returns       : contacts
   Calls         : datahelper.fetchRecords
   Called By     :
   Author        : sushrit 
   Created  on   : aug-2-2015
   Modified By   :
   Modified on   :
   ------------------------------------------------------------------------------ */

function getAllcontactsByUserID($user_id , $objDataHelper )
{

	if (!is_object($objDataHelper)) 
	{
		throw new Exception("common_function.inc.php : getPasswordRequestDetails : DataHelper Object did not instantiate", 104);
	}

	if (strlen(trim($user_id)) <= 0) 
	{
		throw new Exception("common_function.inc.php: getPasswordRequestDetails : Missing Parameter user id.", 141);
	}

	try 
	{

		$strSqlStatement = "SELECT pdc.contact_group_name , pdc.personal_contact_id, pdc.contact_nick_name, pdc.contact_first_name, pdc.contact_last_name, pdc.contact_email_address, pdc.contact_idd_code, pdc.contact_mobile_number, pdc.contact_group_name, pdc.user_id, date(pdc.updatedon) 'updatdt', pdc.personal_contact_status FROM personal_contact_details pdc WHERE pdc.user_id = '".$user_id."'";
		$arrContactsResult = $objDataHelper->fetchRecords("QR", $strSqlStatement);
		return $arrContactsResult;
	} 
	catch (Exception $e) 
	{
		throw new Exception("contact_function.inc.php : getAllcontactsByUserID : Could not fetch records : " . $e->getMessage(), 144);
	}



}
/* -----------------------------------------------------------------------------
   Function Name : getAllcontactsByEmailId
   Purpose       : retrieve all the contacts on the basis of userid.
   Parameters    : userid, Datahelper
   Returns       : contacts
   Calls         : datahelper.fetchRecords
   Called By     :
   Author        : sushrit 
   Created  on   : aug-2-2015
   Modified By   :
   Modified on   :
   ------------------------------------------------------------------------------ */

function getAllcontactsByEmailId($user_id , $emailaddress ,  $objDataHelper )
{

	if (!is_object($objDataHelper)) 
	{
		throw new Exception("common_function.inc.php : getAllcontactsByEmailId : DataHelper Object did not instantiate", 104);
	}

	if (strlen(trim($user_id)) <= 0) 
	{
		throw new Exception("common_function.inc.php: getAllcontactsByEmailId : Missing Parameter user id.", 141);
	}
	if (strlen(trim($emailaddress)) <= 0) 
	{
		throw new Exception("common_function.inc.php: getAllcontactsByEmailId : Missing Parameter email id.", 141);
	}

	try 
	{

		$strSqlStatement = "SELECT pdc.personal_contact_id, pdc.contact_nick_name, pdc.contact_first_name, pdc.contact_last_name, pdc.contact_email_address, pdc.contact_idd_code, pdc.contact_mobile_number, pdc.contact_group_name, pdc.user_id, date(pdc.updatedon) 'updatdt', pdc.personal_contact_status FROM personal_contact_details pdc WHERE pdc.user_id = '".$user_id."' AND pdc.contact_email_address = '".$emailaddress."'";
		$arrContactsResult = $objDataHelper->fetchRecords("QR", $strSqlStatement);
		return $arrContactsResult;
	} 
	catch (Exception $e) 
	{
		throw new Exception("contact_function.inc.php : getAllcontactsByUserID : Could not fetch records : " . $e->getMessage(), 144);
	}



}
/* -----------------------------------------------------------------------------
   Function Name : disablecontact
   Purpose       : retrieve all the contacts on the basis of userid.
   Parameters    : userid, Datahelper
   Returns       : contacts
   Calls         : datahelper.fetchRecords
   Called By     :
   Author        : sushrit 
   Created  on   : aug-2-2015
   Modified By   :
   Modified on   :
   ------------------------------------------------------------------------------ */
function disablecontact($contactid, $userid , $objDataHelper)
{	
	if (!is_object($objDataHelper)) 
	{
		throw new Exception("common_function.inc.php : getPasswordRequestDetails : DataHelper Object did not instantiate", 104);
	}
	if (strlen(trim($userid)) <= 0) 
	{
		throw new Exception("common_function.inc.php: getPasswordRequestDetails : Missing Parameter user id.", 141);
	}
	if (strlen(trim($contactid)) <= 0) 
	{
		throw new Exception("common_function.inc.php: getPasswordRequestDetails : Missing Parameter user id.", 141);
	}

	$sqlQuery = "Update personal_contact_details set personal_contact_status ='2' , updatedon= now() where personal_contact_id ='".$contactid."' and user_id = '".$userid."'";
	$arrUpdateContact = $objDataHelper->putRecords("QR", $sqlQuery);
	if($objDataHelper->affectedRows == 0)
		return 0;
	else
		return 1;

}
/* -----------------------------------------------------------------------------
   Function Name : enablecontact
   Purpose       : retrieve all the contacts on the basis of userid.
   Parameters    : userid, Datahelper
   Returns       : contacts
   Calls         : datahelper.fetchRecords
   Called By     :
   Author        : sushrit 
   Created  on   : aug-2-2015
   Modified By   :
   Modified on   :
   ------------------------------------------------------------------------------ */
function enablecontact($contactid, $userid , $objDataHelper)
{	
	if (!is_object($objDataHelper)) 
	{
		throw new Exception("common_function.inc.php : getPasswordRequestDetails : DataHelper Object did not instantiate", 104);
	}

	if (strlen(trim($userid)) <= 0) 
	{
		throw new Exception("common_function.inc.php: getPasswordRequestDetails : Missing Parameter user id.", 141);
	}
	if (strlen(trim($contactid)) <= 0) 
	{
		throw new Exception("common_function.inc.php: getPasswordRequestDetails : Missing Parameter user id.", 141);
	}

	$sqlQuery = "Update personal_contact_details set personal_contact_status ='1' , updatedon= now() where personal_contact_id ='".$contactid."' and user_id = '".$userid."'";
	$arrUpdateContact = $objDataHelper->putRecords("QR", $sqlQuery);
		return 0;

}
/* -----------------------------------------------------------------------------
   Function Name : getAllgroups
   Purpose       : retrieve all the group names on the basis of userid.
   Parameters    : userid, Datahelper
   Returns       : groups
   Calls         : datahelper.fetchRecords
   Called By     :
   Author        : sushrit 
   Created  on   : aug-5-2015
   Modified By   :
   Modified on   :
   ------------------------------------------------------------------------------ */
function getAllgroups( $userid , $objDataHelper)
{
	if (!is_object($objDataHelper)) 
	{
		throw new Exception("common_function.inc.php : getPasswordRequestDetails : DataHelper Object did not instantiate", 104);
	}

	if (strlen(trim($userid)) <= 0) 
	{
		throw new Exception("common_function.inc.php: getPasswordRequestDetails : Missing Parameter user id.", 141);
	}
	//$sqlQuery = "SELECT * FROM contact_group WHERE association = '".$userid."'";
	$sqlQuery = "SELECT distinct contact_group_name FROM personal_contact_details WHERE user_id = '".$userid."'";
	$arrGroupsResult = $objDataHelper->fetchRecords("QR", $sqlQuery);
	return $arrGroupsResult;

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
function profile_form_table_map_contacts()
{
	/*/`group_id`, `group_name`, `association`, `group_status`

	//`contact_nick_name`, `contact_first_name`, `contact_last_name`, `contact_email_address`, `contact_idd_code`, `contact_mobile_number`, `contact_group_name`, `user_id`, `updatedon`, `personal_contact_status`*/

	//formname
	$arrForms = array("frmcontact"=>array() , "frmFileUploadData"=>array());
	//formelementname 
	$arrForms["frmcontact"] = array("contactfirstname"=>"contact_first_name","contactlastname"=>"contact_last_name","contactnickname"=>"contact_nick_name","contactemailaddress"=>"contact_email_address","contactphoneno"=>"contact_mobile_number","contactgroup"=>"contact_group_name","contact_phone_idd"=>"contact_idd_code","association"=>"user_id","updatedon"=>"updatedon");
	$arrForms["frmFileUploadData"] = array("contact_first_name"=>"selfirstname" , "contact_last_name"=>"sellastname","contact_nick_name"=>"selnickname","contact_email_address"=>"selemailaddress","contact_mobile_number"=>"selphonenumber","contact_group_name"=>"selgroupname","contact_idd_code"=>"selcountry");
	return $arrForms;
}
/* -----------------------------------------------------------------------------
Function Name : change_user_profile
Purpose       : To maintain the mapping of all the html elements and table fields
Parameters    : 
Returns       :
Calls         : 
Called By     :
Author        : Sushrit
Created  on   : 10-August-20a15
Modified By   :
Modified on   :
------------------------------------------------------------------------------ */

function change_user_profile($paramString , $objDataHelper ,$strCK_user_id ,$type)
{

	try
	{
		switch($type)
		{
			case "add":
				$tableName = "personal_contact_details";
				$criteria  = ";";
				$sqlQuery = "Insert into ".$tableName." ".$paramString." ".$criteria;
				$result  = $objDataHelper->putRecords("QR",$sqlQuery);
				return "1";
				break;
		}
		return "0";
	}
	catch(Exception $e)
	{
		throw new Exception("common_function_inc.php : change_user_profile Missing Parameter.", 141);
	}
}
/* -----------------------------------------------------------------------------
   Function Name : getContactByContactid
   Purpose       : retrieve all the contacts on the basis of contactid.
   Parameters    : userid, Datahelper , contactid
   Returns       : contacts
   Calls         : datahelper.fetchRecords
   Called By     :
   Author        : sushrit 
   Created  on   : aug-16-2015
   Modified By   :
   Modified on   :
   ------------------------------------------------------------------------------ */

function getContactByContactid($user_id , $personal_contact_id ,  $objDataHelper )
{

	if (!is_object($objDataHelper)) 
	{
		throw new Exception("common_function.inc.php : getContactByContactid : DataHelper Object did not instantiate", 104);
	}

	if (strlen(trim($user_id)) <= 0) 
	{
		throw new Exception("common_function.inc.php: getContactByContactid: Missing Parameter user id.", 141);
	}
	if (strlen(trim($personal_contact_id)) <= 0) 
	{
		throw new Exception("common_function.inc.php: getAllcontactsByEmailId : Missing Paramete personnel contact id.", 141);
	}

	try 
	{

		$strSqlStatement = "SELECT pdc.personal_contact_id, pdc.contact_nick_name, pdc.contact_first_name, pdc.contact_last_name, pdc.contact_email_address, pdc.contact_idd_code, pdc.contact_mobile_number, pdc.contact_group_name, pdc.user_id, date(pdc.updatedon) 'updatdt', pdc.personal_contact_status FROM personal_contact_details pdc WHERE pdc.user_id = '".$user_id."' AND pdc.personal_contact_id = '".trim($personal_contact_id)."'";
		$arrContactsResult = $objDataHelper->fetchRecords("QR", $strSqlStatement);
		return $arrContactsResult;
	} 
	catch (Exception $e) 
	{
		throw new Exception("contact_function.inc.php : getAllcontactsByUserID : Could not fetch records : " . $e->getMessage(), 144);
	}



}
/* -----------------------------------------------------------------------------
   Function Name : updateContactProfile
Purpose       : To update the contact profile tables as per the user input
Parameters    : 
Returns       :
Calls         : 
Called By     :
Author        : Sushrit
Created  on   : 16-August-2015
Modified By   :
Modified on   :
------------------------------------------------------------------------------ */

function updateContactProfile($paramString ,$contactid , $objDataHelper ,$strCK_user_id ,$type)
{
	
	try
	{
		switch($type)
		{
			case "update":
				$tableName = "personal_contact_details";
				$criteria  = " Where user_id ='".$strCK_user_id."' and personal_contact_id = '".$contactid."'";
				$sqlQuery = "UPDATE ".$tableName." SET ".$paramString." ".$criteria;
				$result  = $objDataHelper->putRecords("QR",$sqlQuery);
				return true;
				break;
		}
		
	}
	catch(Exception $e)
	{
		throw new Exception("contacts.inc.php : updateProfile : Could not update records : " . $e->getMessage(), 144);
		
	}
}



?>
