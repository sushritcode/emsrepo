<?php
require_once('../includes/global.inc.php');
require_once(CLASSES_PATH . 'error.inc.php');
require_once(DBS_PATH . 'DataHelper.php');
require_once(DBS_PATH . 'objDataHelper.php');
require_once(INCLUDES_PATH . 'cm_authfunc.inc.php');
$CONST_MODULE = 'meeting';
$CONST_PAGEID = 'Meeting Page';
require_once(INCLUDES_PATH . 'cm_authorize.inc.php');
require_once(INCLUDES_PATH . 'schedule_function.inc.php');

$ScheduleId = $_REQUEST["SchId"];
$InvitationStatus = $_REQUEST["iStat"];
$InvEmailAddress = $strCk_user_email_address;
$GmtDatetime = GM_DATE;

try
{
   $inviteeStatus = updInvitationStatus($ScheduleId, $InvitationStatus, $InvEmailAddress, $GmtDatetime, $objDataHelper);
}
catch(Exception $e)
{
   throw new Exception("index.php : updInvitationStatus Failed : ".$e->getMessage() , 1126);
}

return $inviteeStatus;

?>