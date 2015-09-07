<?php

/* -----------------------------------------------------------------------------
   Function Name : getFrequentInvitees
   Purpose       : retrieve all the frequent invited contacts of the userid address book
   Parameters    : userid, Datahelper , noOfInvitees
   Returns       : array
   Calls         : datahelper.fetchRecords
   Called By     :
   Author        : sushrit 
   Created  on   : Sept 7 ,2015
   Modified By   :
   Modified on   :
   ------------------------------------------------------------------------------ */
function getFrequentInvitees( $userid , $objDataHelper , $noOfInvitees )
{	
	if (!is_object($objDataHelper)) 
	{
		throw new Exception("dashboard.inc.php : getFrequentInvitees : DataHelper Object did not instantiate", 104);
	}
	if (strlen(trim($userid)) <= 0) 
	{
		throw new Exception("dashboard.inc.php: getFrequentInvitees  : Missing Parameter user id.", 141);
	}
	if(!is_int($noOfInvitees))
	{
		throw new Exception("dashboard.inc.php: getFrequentInvitees  : noOfInvitees should be a integee.", 141);
	}
	if($noOfInvitees > 0)
		$limit = "Limit 0,".$noOfInvitees;
	try 
	{
		$selQuery = "SELECT  distinct (inv.invitee_email_address) , count(inv.invitee_email_address) 'noOcurance' FROM  schedule_details sch, invitation_details inv, personal_contact_details pcd WHERE  sch.user_id = '".$userid."' AND sch.schedule_id = inv.schedule_id AND inv.invitee_email_address = pcd.contact_email_address AND 
			pcd.user_id = '".$userid."'Group By inv.invitee_email_address Order By noOcurance Desc;";

		$arrFrequentInvitees = $objDataHelper->fetchRecords("QR", $selQuery);
		return $arrFrequentInvitees;
	} 
	catch (Exception $e) 
	{
		throw new Exception("dashboard.inc.php : getFrequentInvitees : Could not fetch records : " . $e->getMessage(), 144);
	}
}



