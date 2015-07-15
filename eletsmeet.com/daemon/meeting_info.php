<?php
$DAEMON_WEBAPP_PATH = "/var/www/html/eletsmeet.com/";
//$DAEMON_WEBAPP_PATH = "/home/eletsmeet/public_html/";
require_once($DAEMON_WEBAPP_PATH.'includes/global.inc.php');
require_once($DAEMON_WEBAPP_PATH.'classes/error.inc.php');
require_once($DAEMON_WEBAPP_PATH.'dbs/DataHelper.php');
require_once($DAEMON_WEBAPP_PATH.'dbs/objDataHelper.php');
require_once($DAEMON_WEBAPP_PATH.'includes/daemon_function.inc.php');
require_once($DAEMON_WEBAPP_PATH.'includes/api_function.inc.php');
require_once($DAEMON_WEBAPP_PATH.'includes/db_common_function.inc.php');
require_once($DAEMON_WEBAPP_PATH.'includes/Utilities.php');

//header('Content-type: text/plain; charset=utf-8');

try
{
    $Client_Id = "cl00001";
    $Schedule_Id = "55827bdcec809";
    
    $Moderator_Password ="mppwd";
    $Meeting_Instance = "http://conference.eletsmeet.com";
            
    try
    {
        $meetingInstanceDtls = getLMInstanceByClientId($Client_Id, $objDataHelper);
    }
    catch (Exception $e)
    {
        throw new Exception("Error in getLMInstanceByClientId.".$a->getMessage(), 312);
    }
    $LMInstanceSalt= $meetingInstanceDtls[0]["rt_server_salt"];
    $LMInstanceAPIUrl = $meetingInstanceDtls[0]["rt_server_api_url"];
    $Salt = $LMInstanceSalt;
            
            
    $GET_MEETING_INFO_API_URL = $Meeting_Instance.$LMInstanceAPIUrl.VIDEO_SERVER_GET_MEETING_INFO_API;      
    $GMIAPI_OUTPUT = Call_GetMeetingInfo_API($GET_MEETING_INFO_API_URL, $Schedule_Id, $Moderator_Password, $Salt);

    $arrGMIAPI_Result = explode(SEPARATOR, $GMIAPI_OUTPUT);
    echo "<hr/>";
    echo "<pre/>";
    print_r($arrGMIAPI_Result);
    echo "<pre/>";
}
catch (Exception $e)
{
    $ErrorHandler->RaiseError($_SERVER["PHP_SELF"], $e->getCode(), $e->getMessage(), false);
}
?>