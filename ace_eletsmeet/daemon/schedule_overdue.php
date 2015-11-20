<?php
//$DAEMON_WEBAPP_PATH = "/var/www/html/emsrepo/branches/mitesh/ace_eletsmeet/";
$DAEMON_WEBAPP_PATH = "/home/eletsmeet/public_html/stage.eletsmeet.com/";
require_once($DAEMON_WEBAPP_PATH.'includes/global.inc.php');
require_once($DAEMON_WEBAPP_PATH.'classes/error.inc.php');
require_once($DAEMON_WEBAPP_PATH.'dbs/DataHelper.php');
require_once($DAEMON_WEBAPP_PATH.'dbs/objDataHelper.php');
require_once($DAEMON_WEBAPP_PATH.'includes/daemon_function.inc.php');
//require_once($DAEMON_WEBAPP_PATH.'includes/common_function.inc.php');
require_once($DAEMON_WEBAPP_PATH.'includes/utilities.php');

header('Content-type: text/plain; charset=utf-8');

try
{
    define("SCHEDULE_REMINDER_UNIT", "minutes");

    define("OVERDUE_DATE_INTERVAL", DAEMON_MEETING_OVERDUE_GRACE_INTERVAL);

    $Current_GMT_Datetime = GM_DATE;

    $Overdue_Date_Interval = '-'.OVERDUE_DATE_INTERVAL.' '.SCHEDULE_REMINDER_UNIT;
    $Overdue_Datetime = date('Y-m-d H:i:s', strtotime($Overdue_Date_Interval, strtotime($Current_GMT_Datetime)));

    $Current_GMT_Datetime ."\r\n". $Overdue_Date_Interval ."\r\n". $Overdue_Datetime;
 
    try
    {
        $arrOverdueSchList = getOverdueScheduleList($Overdue_Datetime, $objDataHelper);
    }
    catch (Exception $a)
    {
        throw new Exception("Error in getOverdueScheduleList.".$a->getMessage(), 4102);
    }
    
//    echo "<pre/>";
//    print_r($arrOverdueSchList);
//    echo "<hr<pre/>";

    if (sizeof($arrOverdueSchList) > 0)
    {
        for ($intCntr = 0; $intCntr < sizeof($arrOverdueSchList); $intCntr++)
        {
            $Schedule_Id = $arrOverdueSchList[$intCntr]['schedule_id'];
            $Meeting_Instance = $arrOverdueSchList[$intCntr]['meeting_instance'];
            $Schedule_Status = $arrOverdueSchList[$intCntr]['schedule_status'];

//            try
//            {
//                $unLoadScheduleInstance = unLoadRoundtableInstance($Schedule_Id);
//            }
//            catch (Exception $e)
//            {
//                throw new Exception("createSchedule.php : getRoundtableInstance Failed : ".$e->getMessage(), 1138);
//            }
            
            //Schedule Status =  0 then Update to 4 (Overdue)
            $Old_Schedule_Status = $Schedule_Status;
            $New_Schedule_Status = "4";

            try
            {
                $arrUpdScheduleStatus = updScheduleStatus($Schedule_Id, $Old_Schedule_Status, $New_Schedule_Status, $objDataHelper);
            }
            catch (Exception $a)
            {
                throw new Exception("Error in updScheduleStatus.".$a->getMessage(), 4102);
            }
            $strUpdateStatus = trim($arrUpdScheduleStatus[0]['@result']);

            echo $OutPut = $strUpdateStatus . SEPARATOR . $Schedule_Id . "\n";
        }
    }
}
catch (Exception $e)
{
    $ErrorHandler->RaiseError($_SERVER["PHP_SELF"], $e->getCode(), $e->getMessage(), false);
}
?>
