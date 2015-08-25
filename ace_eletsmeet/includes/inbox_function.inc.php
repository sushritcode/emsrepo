<?php
/* -----------------------------------------------------------------------------
   Function Name : getAllMessageOnUserId
   Purpose       : To get Message of a user on the basis of the user id.
   Parameters    : 
   Returns       : 
   Calls         : datahelper.fetchRecords
   Called By     :
   Author        : Sushrit
   Created  on   : 25-Aug-2015
   Modified By   :
   Modified on   :
   ------------------------------------------------------------------------------ */

function getAllMessageOnUserId($userId , $messageId='' , $objDataHelper )
{
	if (!is_object($objDataHelper)) 
	{
		throw new Exception("inbox_function.inc.php : getAllMessageOnUserId : DataHelper Object did not instantiate", 104);
	}
	if (strlen(trim($userid)) == 0) 
	{
		throw new exception("inbox_function.inc.php : getallmessageonuserid : userid not present", 104);
	}
	$selectMsgId = "1";
	if(strlen(trim($messageId)) != 0 )
		$selectMsgId =" msg_id ='".trim($messageId)."'";
	$selectMsgId.=($selectMsgId != "")? " AND ": "";
	$selectMsgId.=" msg_to = '".trim($userId)."'";
	
	$sqlQuery  = "SELECT * FROM user_inbox WHERE ".$messageId;
	$arrMessages  = $objDataHelper->fetchRecords("QR",$strSqlStatement);
	if(is_array($arrMessages))
		return $arrMessages;
	return false;
}

/* -----------------------------------------------------------------------------
   Function Name : deleteMsg
Purpose       : delete the message for a user using a user ID
Parameters    : email_address, Datahelper
Returns       : 
Calls         : datahelper.fetchRecords
Called By     :
Author        : Sushrit
Created  on   : 25-Aug-2015
Modified By   :
Modified on   :
------------------------------------------------------------------------------ */
function deleteMsg($userId , $messageId , $objDataHelper)
{


	if (!is_object($objDataHelper)) 
	{
		throw new Exception("inbox_function.inc.php : deleteMsg : DataHelper Object did not instantiate", 104);
	}
	if (strlen(trim($userid)) == 0) 
	{
		throw new exception("inbox_function.inc.php : deleteMsg : userid not present", 104);
	}	
	if (strlen(trim($messageId)) == 0) 
	{
		throw new exception("inbox_function.inc.php : deleteMsg : userid not present", 104);
	}

	$strUpdateQry = "Update user_inbox set msg_deleted = '1' where msg_id = '".trim($messageId)."'and msg_to = '".trim($userId)."'";
	$update = $objDataHelper->putRecords("QR",$strUpdateQry);
	return true;


}


/* -----------------------------------------------------------------------------
   Function Name : markAsRead
Purpose       : mark the message for a user using a user ID as read
Parameters    : email_address, Datahelper
Returns       : 
Calls         : datahelper.fetchRecords
Called By     :
Author        : Sushrit
Created  on   : 25-Aug-2015
Modified By   :
Modified on   :
------------------------------------------------------------------------------ */
function deleteMsg($userId , $messageId , $objDataHelper)
{


	if (!is_object($objDataHelper)) 
	{
		throw new Exception("inbox_function.inc.php : deleteMsg : DataHelper Object did not instantiate", 104);
	}
	if (strlen(trim($userid)) == 0) 
	{
		throw new exception("inbox_function.inc.php : deleteMsg : userid not present", 104);
	}	
	if (strlen(trim($messageId)) == 0) 
	{
		throw new exception("inbox_function.inc.php : deleteMsg : userid not present", 104);
	}

	$strUpdateQry = "Update user_inbox set msg_seen = '1' where msg_id = '".trim($messageId)."'and msg_to = '".trim($userId)."'";
	$update = $objDataHelper->putRecords("QR",$strUpdateQry);
	return true;


}
?>
