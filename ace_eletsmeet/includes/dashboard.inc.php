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


//SELECT user_id, (
CASE WHEN nick_name IS NULL OR nick_name = '' THEN 0  ELSE 1 END +
CASE WHEN first_name IS NULL OR first_name = '' THEN 0 ELSE 1 END +
CASE WHEN last_name IS NULL OR last_name = '' THEN 0  ELSE 1 END +
CASE WHEN secondry_email IS NULL OR secondry_email = '' THEN 0  ELSE 1 END +
CASE WHEN landmark IS NULL OR landmark = '' THEN 0  ELSE 1 END +
CASE WHEN city IS NULL OR city = '' THEN 0  ELSE 1 END +
CASE WHEN address IS NULL OR address = '' THEN 0  ELSE 1 END +
CASE WHEN country_name IS NULL OR country_name = '' THEN 0  ELSE 1 END +
CASE WHEN timezones IS NULL OR timezones = '' THEN 0  ELSE 1 END +
CASE WHEN gmt IS NULL OR gmt = '' THEN 0  ELSE 1 END +
CASE WHEN phone_number IS NULL OR phone_number = '' THEN 0  ELSE 1 END +
CASE WHEN idd_code IS NULL OR idd_code = '' THEN 0  ELSE 1 END +
CASE WHEN mobile_number IS NULL OR mobile_number = '' THEN 0  ELSE 1 END +
CASE WHEN industry_type IS NULL OR industry_type = '' THEN 0  ELSE 1 END +
CASE WHEN company_name IS NULL OR company_name = '' THEN 0  ELSE 1 END +
CASE WHEN nature_business IS NULL OR nature_business = '' THEN 0  ELSE 1 END +
CASE WHEN company_uri IS NULL OR company_uri = '' THEN 0  ELSE 1 END +
CASE WHEN brief_desc_company IS NULL OR brief_desc_company = '' THEN 0  ELSE 1 END +
CASE WHEN facebook IS NULL OR facebook = '' THEN 0  ELSE 1 END +
CASE WHEN twitter IS NULL OR twitter = '' THEN 0  ELSE 1 END +
CASE WHEN googleplus IS NULL OR googleplus = '' THEN 0  ELSE 1 END +
CASE WHEN linkedin IS NULL OR linkedin = '' THEN 0  ELSE 1 END 
) * 100 / 22
FROM user_details
WHERE 1 


