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

?>
