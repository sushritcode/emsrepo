<?php

/* -----------------------------------------------------------------------------
  Function Name : isUserEmailAddessExists
  Purpose       : To check whether user email address exists in database or not.
  Parameters    : email_address, Datahelper
  Returns       : user_id, email_address
  Calls         : datahelper.fetchRecords
  Called By     :
  Author        : Mitesh Shah
  Created  on   : 19-July-2015
  Modified By   :
  Modified on   :
  ------------------------------------------------------------------------------ */

function isUserEmailAddressExists($email_address, $dataHelper) {
    if (!is_object($dataHelper)) {
        throw new Exception("common_function.inc.php : isUserEmailAddressExists : DataHelper Object did not instantiate", 104);
    }

    if (strlen(trim($email_address)) <= 0) {
        throw new Exception("common_function.inc.php: isUserEmailAddressExists : Missing Parameter email_address.", 141);
    }

    try {
        $strSqlStatement = "SELECT user_id, email_address FROM user_details WHERE email_address='" . trim($email_address) . "';";
        $arrAuthResult = $dataHelper->fetchRecords("QR", $strSqlStatement);
        return $arrAuthResult;
    } catch (Exception $e) {
        throw new Exception("cm_authfunc.inc.php : isUserEmailAddressExists : Could not fetch records : " . $e->getMessage(), 144);
    }
}
/* -----------------------------------------------------------------------------
  Function Name : getAllIndustryType
  Purpose       : To fetch the list of all the industry types.
  Parameters    : Datahelper
  Returns       : List of Companies
  Calls         : datahelper.fetchRecords
  Called By     :
  Author        : Sushrit 
  Created  on   : 23-July-2015
  Modified By   :
  Modified on   :
  Descriptions  : Table columns are `industry_id`, `industry_name`, `status`
  ------------------------------------------------------------------------------ */
function getAllIndustryType($dataHelper)
{
	if (!is_object($dataHelper)) 
	{
		throw new Exception("common_function.inc.php : isUserEmailAddressExists : DataHelper Object did not instantiate", 104);
	}
	try 
	{
		$strSqlStatement = "SELECT * FROM industry_details WHERE status like '1';";
		$arrIndustryTypes = $dataHelper->fetchRecords("QR", $strSqlStatement);
		return $arrIndustryTypes;
	} 
	catch (Exception $e) 
	{
		throw new Exception("common_function.inc.php : getAllCompanyType : Could not fetch records : " . $e->getMessage(), 144);
	}

}


