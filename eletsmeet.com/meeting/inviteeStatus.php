<?php
require_once('../includes/global.inc.php');
require_once(CLASSES_PATH."error.inc.php");
require_once(INCLUDES_PATH."Utilities.php");
require_once(DBS_PATH."DataHelper.php");
require_once(DBS_PATH."objDataHelper.php");
require_once(INCLUDES_PATH."db_common_function.inc.php");
require_once(INCLUDES_PATH."cm_authfunc.inc.php");
$CONST_MODULE = 'meeting';
$CONST_PAGEID = 'Meeting';
require_once(INCLUDES_PATH."cm_authorize.inc.php");
require_once(INCLUDES_PATH."api_db_function.inc.php");

$schedule_id = $_REQUEST["sId"];
$invitation_status = $_REQUEST["iStat"];
$inv_email_address = $strCk_email_address;
$gmt_datetime = GM_DATE;
try
{
   $inviteeStatus = updInvitationStatus($schedule_id, $invitation_status, $inv_email_address, $gmt_datetime, $objDataHelper);
}
catch(Exception $e)
{
   throw new Exception("index.php : updInvitationStatus Failed : ".$e->getMessage() , 1126);
}

return $inviteeStatus;

?>