<?php
$DAEMON_WEBAPP_PATH = "/var/www/html/emsrepo/branches/mitesh/ace_eletsmeet/";
//$DAEMON_WEBAPP_PATH = "/home/eletsmeet/public_html/";
require_once($DAEMON_WEBAPP_PATH.'includes/global.inc.php');
require_once($DAEMON_WEBAPP_PATH.'classes/error.inc.php');
require_once($DAEMON_WEBAPP_PATH.'dbs/DataHelper.php');
require_once($DAEMON_WEBAPP_PATH.'dbs/objDataHelper.php');
require_once($DAEMON_WEBAPP_PATH.'includes/daemon_function.inc.php');
require_once($DAEMON_WEBAPP_PATH.'includes/api_function.inc.php');
require_once($DAEMON_WEBAPP_PATH.'includes/common_function.inc.php');
require_once($DAEMON_WEBAPP_PATH.'includes/utilities.php');

header('Content-type: text/plain; charset=utf-8');

try
{
    define("SCHEDULE_REMINDER_UNIT", "minutes");
    define("END_DATE_INTERVAL", DAEMON_MEETING_END_GRACE_INTERVAL);

    $Current_GMT_Datetime = GM_DATE;

    $End_Date_Interval = '-'.END_DATE_INTERVAL.' '.SCHEDULE_REMINDER_UNIT;
    $End_Datetime = date('Y-m-d H:i:s', strtotime($End_Date_Interval, strtotime($Current_GMT_Datetime)));

    $Current_GMT_Datetime ."\r\n". $End_Date_Interval ."\r\n". $End_Datetime;

    try
    {
        $arrCreatedSchList = getCreatedScheduleList($End_Datetime, $objDataHelper);
    }
    catch (Exception $a)
    {
        throw new Exception("Error in getCreatedScheduleList.".$a->getMessage(), 4102);
    }
//    echo "<pre/>";
//    print_r($arrCreatedSchList);
//    echo "<pre/>";   
    
    if (sizeof($arrCreatedSchList) > 0)
    {
        
        for ($intCntr = 0; $intCntr < sizeof($arrCreatedSchList); $intCntr++)
        {
            $Schedule_Id = $arrCreatedSchList[$intCntr]['schedule_id'];
            $Meeting_Instance = $arrCreatedSchList[$intCntr]['meeting_instance'];
            $Schedule_Status = $arrCreatedSchList[$intCntr]['schedule_status'];
            $Client_Id = $arrCreatedSchList[$intCntr]['client_id'];
            $Moderator_Password = $arrCreatedSchList[$intCntr]['moderator_password'];
            
            //Added by Mitesh Shah 29-12-2014 
            try
            {

                $meetingInstanceDtls = getLMInstanceByClientId($Client_Id, $objDataHelper);
                //print_r($meetingInstanceDtls);
            }
            catch (Exception $e)
            {
                throw new Exception("Error in getLMInstanceByClientId.".$a->getMessage(), 312);
            }

            $LMInstanceSalt= $meetingInstanceDtls[0]["rt_server_salt"];
            $LMInstanceAPIUrl = $meetingInstanceDtls[0]["rt_server_api_url"];
            //Added by Mitesh Shah 29-12-2014 

            //$Salt = VIDEO_SERVER_SALT;
            $Salt = $LMInstanceSalt;
        
            
            //$IS_MEETING_RUNNING_API_URL = $Meeting_Instance.VIDEO_SERVER_API.VIDEO_SERVER_IS_MEETING_RUNNING_API;
            $IS_MEETING_RUNNING_API_URL = $Meeting_Instance.$LMInstanceAPIUrl.VIDEO_SERVER_IS_MEETING_RUNNING_API;
            
            $IMRAPI_OUTPUT = Call_IsMeetingRunning_API($IS_MEETING_RUNNING_API_URL, $Schedule_Id, $Salt);
            $arrIMRAPI_Result = explode(SEPARATOR, $IMRAPI_OUTPUT);
            
            //echo "<pre/>";
            //print_r($arrIMRAPI_Result);
            //echo "<pre/>";
            
            $IMRAPI_ReturnCode = trim($arrIMRAPI_Result[0]);
            $IMRAPI_Running = trim($arrIMRAPI_Result[1]);

            if (($IMRAPI_ReturnCode == "SUCCESS") && ($IMRAPI_Running == "true"))
            {
                $STATUS = 2;
                $MESSAGE = "Meeting is running";
            }
            else
            {
                $END_MEETING_RUNNING_API_URL = $Meeting_Instance.$LMInstanceAPIUrl.VIDEO_SERVER_END_MEETING_API;
                
                $EMRAPI_OUTPUT = Call_EndMeeting_API($END_MEETING_RUNNING_API_URL, $Schedule_Id, $Moderator_Password, $Salt);
                
                $arrEMRAPI_Result = explode(SEPARATOR, $EMRAPI_OUTPUT);
                //echo "<pre/>";
                //print_r($arrEMRAPI_Result);
                //echo "<pre/>";
                
                
                $STATUS = 1;
                $MESSAGE = "Meeting is not running";
                
                //Schedule Status =  1 then Update to 2 (End)
                $Old_Schedule_Status = $Schedule_Status;
                $New_Schedule_Status = "2";
                
                try
                {
                    //$arrUpdScheduleStatus = UpdateEndSchedule($Schedule_Id, $Old_Schedule_Status, $New_Schedule_Status, $Current_GMT_Datetime, $objDataHelper);
                    $arrUpdScheduleStatus = UpdateEndSchedule($Schedule_Id, $Old_Schedule_Status, $New_Schedule_Status, $End_Datetime, $objDataHelper);
                }
                catch (Exception $a)
                {
                    throw new Exception("Error in UpdateEndSchedule.".$a->getMessage(), 4102);
                }
                $strUpdateSchStatus = trim($arrUpdScheduleStatus[0]['@result']);
                  if ($strUpdateSchStatus == 1)
                      {
                             //update Client Subscription Data for Consumed session
                             
                      }
            }
            
            echo $OutPut = $STATUS . SEPARATOR . $Schedule_Id . SEPARATOR . $MESSAGE . SEPARATOR . $strUpdateSchStatus . "\n";
        }
    }
}
catch (Exception $e)
{
    $ErrorHandler->RaiseError($_SERVER["PHP_SELF"], $e->getCode(), $e->getMessage(), false);
}
?>
