<?php

require_once('../includes/global.inc.php');
require_once(CLASSES_PATH."error.inc.php");
require_once(DBS_PATH."DataHelper.php");
require_once(DBS_PATH."objDataHelper.php");
require_once(INCLUDES_PATH."cm_authfunc.inc.php");
$CONST_MODULE = 'schedule';
$CONST_PAGEID = 'Schedule';
require_once(INCLUDES_PATH."cm_authorize.inc.php");
require_once(INCLUDES_PATH."sch_function.inc.php");
require_once(INCLUDES_PATH."db_common_function.inc.php");

$scheduleID = $_REQUEST['startId'];

try
{
    try
    {
        $schStatus = validateSchedule($strCK_user_id, $strCk_client_id, PRID);
    }
    catch (Exception $e)
    {
        throw new Exception("start.php : validateSchedule Failed : ".$e->getMessage(), 1121);
    }

    $schedule1 = explode(SEPARATOR, $schStatus);
       
    $schedule = $schedule1[0];

    if ($schedule == 0)
    {
        $Joinee_IP_Address = $_SERVER['REMOTE_ADDR'];
        $arrHead=apache_request_headers();
        $arrHeaders = array_change_key_case($arrHead,CASE_LOWER);
        $clientBrowser = trim($arrHeaders['user-agent']);

        //Update the invitee IP Address and Headers
        $IPUpdate = updInviteeIPHeaders($scheduleID, $strCk_email_address, $Joinee_IP_Address, $clientBrowser, $objDataHelper);

        try
        {
            $cDetails = createMeeting($scheduleID, $strCk_email_address);
        }
        catch (Exception $e)
        {
            throw new Exception("start.php : createMeeting Failed : ".$e->getMessage(), 1122);
        }

        $sDetails = explode(SEPARATOR, $cDetails);
        if ($sDetails[0] == 1)
        {
            $url = $sDetails[2];
            header("Location:".$url);
        }
    }
}
catch (Exception $e)
{
    throw new Exception("start.php : Failed : ".$e->getMessage(), 1123);
}
?>