<?php
date_default_timezone_set('GMT');

require_once('../includes/global.inc.php');
require_once(CLASSES_PATH . 'error.inc.php');
require_once(DBS_PATH . 'DataHelper.php');
require_once(DBS_PATH . 'objDataHelper.php');
require_once(INCLUDES_PATH . "db_common_function.inc.php");
require_once('includes/partner_api_db_function.inc.php');
//require_once('includes/partner_mail_function.inc.php');

$strClientID = $_REQUEST['clientID'];
$strUserEmail = $_REQUEST['userEmail'];
$strUserPW = $_REQUEST['userPW'];
$strScheduleID = $_REQUEST['scheduleID'];
$strTimestamp = $_REQUEST['TS'];
$strPassCode = $_REQUEST['passCode'];
$strProtocolID = $_REQUEST['protocolID'];

/* * * Validate Parameters Received : Start * * */

$stat = 0;
$msg = "";

$Current_GMT_Datetime = GM_DATE;

if (!isset($strClientID))
{
    $stat = 1;
    $msg = "MISSING PARAMETER CLIENT ID";
}
else if (!isset($strUserEmail))
{
    $stat = 1;
    $msg = "MISSING PARAMETER USER EMAIL";
}
else if (!filter_var($strUserEmail, FILTER_VALIDATE_EMAIL))
{
    $stat = 1;
    $msg = "INVALID PARAMETER USER EMAIL";
}
else if (!isset($strUserPW))
{
    $stat = 1;
    $msg = "MISSING PARAMETER USER PW";
}
else if (!isset($strScheduleID))
{
    $stat = 1;
    $msg = "MISSING PARAMETER SCHEDULE ID";
}
else if (!isset($strTimestamp))
{
    $stat = 1;
    $msg = "MISSING PARAMETER TIMESTAMP";
}
else if (!is_numeric($strTimestamp))
{
    $stat = 1;
    $msg = "INVALID PARAMETER TIMESTAMP";
}
else if (!isset($strPassCode))
{
    $stat = 1;
    $msg = "MISSING PARAMETER PASSCODE";
}
else if (!isset($strProtocolID))
{
    $stat = 1;
    $msg = "MISSING PARAMETER PROTOCOL ID";
}
else if (!is_numeric($strProtocolID))
{
    $stat = 1;
    $msg = "INVALID PARAMETER PROTOCOL ID";
}
/* * * Validate Parameters Received : End * * */

if ($stat === 0)
{
    $newPasscode = md5($strUserEmail . $strTimestamp . CLIENT_SECRET_KEY);

    if ($newPasscode == $strPassCode)
    {
        try
        {
            $arrSchDtls = isScheduleIdValid($strScheduleID, $objDataHelper);
        }
        catch (Exception $a)
        {
            throw new Exception("Error in isScheduleIdValid." . $a->getMessage(), 311);
        }
        
        if ((is_array($arrSchDtls)) && (sizeof($arrSchDtls)) > 0)
        {
            $Schedule_Id = trim($arrSchDtls[0]['schedule_id']);
          
            //Get the meeting invitee list
            try
            {
                $arrInviteesList = getMeetingInviteeList($Schedule_Id, $objDataHelper);
            }
            catch (Exception $a)
            {
                throw new Exception("Error in getMeetingInviteeList." . $a->getMessage(), 4104);
            }
            print_r($arrInviteesList);
            
            echo $validUserEmail = in_array($strUserEmail, $arrInviteesList);
            
            foreach ($arrInviteesList as $key => $value)
            {
                $validUserEmail = in_array($strUserEmail, $value);
            }            
                    
            if ($validUserEmail == 1)
            {
                echo "here";
                $PSCD = md5($Schedule_Id . ":" . $strUserEmail . ":" . SECRET_KEY);
                $jmData = "SCID=" . $Schedule_Id . "&EMID=" . $strUserEmail . "&PSCD=" . $PSCD . "&PRID=" . PRID;
                $jmUrl = JMX_API_ROOT . "?" . $jmData;
                $msg = $jmUrl;
            }
            else
            {
                $stat = -1;
                $msg = "Error, User Email is not authenticated.";
            }
        }
        else
        {
            $stat = -1;
            $msg = "Error, invalid meeting information.";
        }
    }
    else
    {
        $stat = -1;
        $msg = "Passcode mismathced.";
    }
}
else
{
    $stat = -1;
    $msg = "Error, invalid get meeting url request.";
}

if ($stat !== 0)
{
    $returncode = "FAILED";
}
else if ($stat === 0)
{
    $returncode = "SUCCESS";
}


$xmlResponse = '<? xml version="1.0" encoding="ISO-8859-1" ?>
<response>
<returncode>' . $returncode . '</returncode>
<message>' . $msg . '</message>
</response>';

echo $xmlResponse;
?>

