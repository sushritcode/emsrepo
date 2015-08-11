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

		$strSqlStatement = "SELECT cg.group_name , pdc.personal_contact_id, pdc.contact_nick_name, pdc.contact_first_name, pdc.contact_last_name, pdc.contact_email_address, pdc.contact_idd_code, pdc.contact_mobile_number, pdc.contact_group_name, pdc.user_id, date(pdc.updatedon) 'updatdt', pdc.personal_contact_status FROM personal_contact_details pdc , contact_group cg WHERE pdc.contact_group_name = cg.group_id AND  pdc.user_id = '".$user_id."'";
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

		$strSqlStatement = "SELECT cg.group_name , pdc.personal_contact_id, pdc.contact_nick_name, pdc.contact_first_name, pdc.contact_last_name, pdc.contact_email_address, pdc.contact_idd_code, pdc.contact_mobile_number, pdc.contact_group_name, pdc.user_id, date(pdc.updatedon) 'updatdt', pdc.personal_contact_status FROM personal_contact_details pdc , contact_group cg WHERE pdc.contact_group_name = cg.group_id AND  pdc.user_id = '".$user_id."' AND pdc.contact_email_address = '".$emailaddress."'";
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
	$sqlQuery = "SELECT * FROM contact_group WHERE association = '".$userid."'";
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
	$arrForms = array("frmcontact"=>array());
	//formelementname 
	$arrForms["frmcontact"] = array("contactfirstname"=>"contact_first_name","contactlastname"=>"contact_last_name","contactnickname"=>"contact_nick_name","contactemailaddress"=>"contact_email_address","contactphoneno"=>"contact_mobile_number","contactgroup"=>"contact_group_name","contact_phone_idd"=>"contact_idd_code","association"=>"user_id","updatedon"=>"updatedon");
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

?>
