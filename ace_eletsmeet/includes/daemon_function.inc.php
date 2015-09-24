<?php

/* -----------------------------------------------------------------------------
  Function Name : getScheduleReminderList
  Purpose       : To get the schedule list for email and SMS reminder
  Parameters    : from_date, to_date, type, Datahelper
  Returns       : array (with schedule_id, user_id, schedule_status, schedule_creation_time, meeting_timestamp_gmt, meeting_timestamp_local, meeting_title,
                  meeting_agenga, meeting_timezone, meeting_gmt, welcome_message, voice_bridge, web_voice, max_participants, meeting_duration)
  Calls         : datahelper.fetchRecords
  Called By     : schedule_reminder.php
  Author        : Mitesh Shah
  Created  on   : 23-July-2012
  Modified By   :
  Modified on   :
  ------------------------------------------------------------------------------ */

function getScheduleReminderList($from_date, $to_date, $type, $dataHelper)
{
    try
    {
        if (strlen(trim($from_date)) <= 0)
        {
            throw new Exception("daemon_function.inc.php: getScheduleReminderList : Missing Parameter schedule_id.", 4001);
        }

        if (strlen(trim($to_date)) <= 0)
        {
            throw new Exception("daemon_function.inc.php: getScheduleReminderList : Missing Parameter schedule_status.", 4002);
        }

        if (strlen(trim($type)) <= 0)
        {
            throw new Exception("daemon_function.inc.php: getScheduleReminderList : Missing Parameter schedule_status.", 4003);
        }

        if (!is_object($dataHelper))
        {
            throw new Exception("daemon_function.inc.php : getScheduleReminderList : DataHelper Object did not instantiate", 104);
        }

        $dataHelper->setParam("'".$from_date."'", "I");
        $dataHelper->setParam("'".$to_date."'", "I");
        $dataHelper->setParam("'".$type."'", "I");
        $arrRemScheduleList = $dataHelper->fetchRecords("SP", "GetScheduleReminderList");
        $dataHelper->clearParams();
        return $arrRemScheduleList;
    }
    catch (Exception $e)
    {
        throw new Exception("daemon_function.inc.php : getScheduleReminderList : Could not fetch reminder list for schedule : ".$e->getMessage(), 4005);
    }
}

/* -----------------------------------------------------------------------------
  Function Name : updScheduleReminderList
  Purpose       : To update email_reminder_status or sms_reminder_status for schedule
  Parameters    : from_date, to_date, type, status, Datahelper
  Returns       : array (with status)
  Calls         : datahelper.putRecords
  Called By     : schedule_reminder.php
  Author        : Mitesh Shah
  Created  on   : 23-July-2012
  Modified By   :
  Modified on   :
  ------------------------------------------------------------------------------ */

function updScheduleReminderList($from_date, $to_date, $type, $dataHelper)
{
    try
    {
        if (strlen(trim($from_date)) <= 0)
        {
            throw new Exception("daemon_function.inc.php: updScheduleReminderList : Missing Parameter schedule_id.", 4011);
        }

        if (strlen(trim($to_date)) <= 0)
        {
            throw new Exception("daemon_function.inc.php: updScheduleReminderList : Missing Parameter schedule_status.", 4012);
        }

        if (strlen(trim($type)) <= 0)
        {
            throw new Exception("daemon_function.inc.php: updScheduleReminderList : Missing Parameter schedule_status.", 4013);
        }

        if (!is_object($dataHelper))
        {
            throw new Exception("daemon_function.inc.php : updScheduleReminderList : DataHelper Object did not instantiate", 104);
        }

        $dataHelper->setParam("'".$from_date."'", "I");
        $dataHelper->setParam("'".$to_date."'", "I");
        $dataHelper->setParam("'".$type."'", "I");
        $dataHelper->setParam("result", "O");
        $arrUpdScheduleList = $dataHelper->putRecords("SP", "UpdateScheduleReminderList");
        $dataHelper->clearParams();
        return $arrUpdScheduleList;
    }
    catch (Exception $e)
    {
        throw new Exception("daemon_function.inc.php : updScheduleReminderList : Could not update reminder list for schedule : ".$e->getMessage(), 4015);
    }
}

/* -----------------------------------------------------------------------------
  Function Name : getOverdueScheduleList
  Purpose       : To get list of Overdue schedules which is <= overdue_date.
  Parameters    : overdue_date, Datahelper
  Returns       : array(schedule_id, meeting_instance, schedule_status)
  Calls         : datahelper.fetchRecords
  Called By     : schedule_overdue.php
  Author        : Mitesh Shah
  Created  on   : 21-August-2012
  Modified By   :
  Modified on   :
  ------------------------------------------------------------------------------ */

function getOverdueScheduleList($overdue_date, $dataHelper)
{
    try
    {
        if (strlen(trim($overdue_date)) <= 0)
        {
            throw new Exception("daemon_function.inc.php: getOverdueScheduleList : Missing Parameter overdue_date.", 4021);
        }

        if (!is_object($dataHelper))
        {
            throw new Exception("daemon_function.inc.php : getOverdueScheduleList : DataHelper Object did not instantiate", 104);
        }

        $strSqlStatement = "SELECT schedule_id, meeting_instance, schedule_status FROM schedule_details WHERE meeting_timestamp_gmt <= '".trim($overdue_date)."' AND schedule_status ='0'";
        $arrOverdueSchList = $dataHelper->fetchRecords("QR", $strSqlStatement);
        $dataHelper->clearParams();
        return $arrOverdueSchList;
    }
    catch (Exception $e)
    {
        throw new Exception("daemon_function.inc.php : getOverdueScheduleList : Could not fetch overdue list of schedule : ".$e->getMessage(), 4022);
    }
}

/* -----------------------------------------------------------------------------
  Function Name : unLoadRoundtableInstance
  Purpose       : To Unload the instance of loadbalancer instance for a praticular schedule
  Parameters    : schedule_id
  Returns       : 
  Calls         : unload API of LB Server
  Called By     : schedule_overdue.php
  Author        : Mitesh Shah
  Created  on   : 21-August-2012
  Modified By   :
  Modified on   :
  ------------------------------------------------------------------------------ */

function unLoadRoundtableInstance($schedule_id)
{
    //CALL LB Server
    $DATA = 'meetingid='.$schedule_id;
    $objUtilities = new Utilities;
    $URL = LOAD_BALANCER_SERVER.":".LOAD_BALANCER_SERVER_PORT."/unload?".$DATA;
    $result = $objUtilities->CallScript($URL);
    $strOutPut = $result;
    return $strOutPut;
}

/* -----------------------------------------------------------------------------
  Function Name : getCreatedScheduleList
  Purpose       : To get list of schedules created and which is <= given date.
  Parameters    : till_date, Datahelper
  Returns       : array(schedule_id, meeting_instance, schedule_status)
  Calls         : datahelper.fetchRecords
  Called By     : schedule_end.php
  Author        : Mitesh Shah
  Created  on   : 21-August-2012
  Modified By   :
  Modified on   :
  ------------------------------------------------------------------------------ */

function getCreatedScheduleList($till_date, $dataHelper)
{
    try
    {
        if (strlen(trim($till_date)) <= 0)
        {
            throw new Exception("daemon_function.inc.php: getCreatedScheduleList : Missing Parameter overdue_date.", 4021);
        }

        if (!is_object($dataHelper))
        {
            throw new Exception("daemon_function.inc.php : getCreatedScheduleList : DataHelper Object did not instantiate", 104);
        }

        //$strSqlStatement = "SELECT schedule_id, meeting_instance, schedule_status FROM schedule_details WHERE schedule_status = '1' AND meeting_timestamp_gmt <= '".trim($till_date)."';";
        $strSqlStatement = "SELECT schedule_id, meeting_instance, schedule_status, moderator_password, sd.user_id, client_id, partner_id FROM schedule_details sd,  user_login_details uld WHERE schedule_status = '1' AND meeting_timestamp_gmt <= '".trim($till_date)."' AND sd.user_id = uld.user_id";
        $arrCreatedSchList = $dataHelper->fetchRecords("QR", $strSqlStatement);
        $dataHelper->clearParams();
        return $arrCreatedSchList;
    }
    catch (Exception $e)
    {
        throw new Exception("daemon_function.inc.php : getCreatedScheduleList : Could not fetch list of created schedule : ".$e->getMessage(), 4022);
    }
}

/* -----------------------------------------------------------------------------
  Function Name : updScheduleStatus
  Purpose       : Update schedule_status for the schedule_id in schedule_details table
  Parameters    : schedule_id, old_schedule_status, new_schedule_status, Datahelper
  Returns       : status (0, 1, 2)
  Calls         : datahelper.putRecords
  Called By     : schedule_end.php, schedule_overdue.php
  Author        : Mitesh Shah
  Created  on   : 21-August-2012
  Modified By   :
  Modified on   :
  ------------------------------------------------------------------------------ */

function updScheduleStatus($schedule_id, $old_schedule_status, $new_schedule_status, $dataHelper)
{
    try
    {
        if (strlen(trim($schedule_id)) <= 0)
        {
            throw new Exception("daemon_function.inc.php: updScheduleStatus : Missing Parameter schedule_id.", 2071);
        }

        if (strlen(trim($old_schedule_status)) <= 0)
        {
            throw new Exception("daemon_function.inc.php: updScheduleStatus : Missing Parameter old_schedule_status.", 2072);
        }
        
        if (strlen(trim($new_schedule_status)) <= 0)
        {
            throw new Exception("daemon_function.inc.php: updScheduleStatus : Missing Parameter new_schedule_status.", 2072);
        }

        if (!is_object($dataHelper))
        {
            throw new Exception("daemon_function.inc.php : updScheduleStatus : DataHelper Object did not instantiate", 104);
        }

        $dataHelper->setParam("'".$schedule_id."'", "I");
        $dataHelper->setParam("'".$old_schedule_status."'", "I");
        $dataHelper->setParam("'".$new_schedule_status."'", "I");
        $dataHelper->setParam("result", "O");
        $arrUpdResult = $dataHelper->putRecords("SP", "UpdateScheduleStatus");
        $dataHelper->clearParams();
        return $arrUpdResult;
    }
    catch (Exception $e)
    {
        throw new Exception("daemon_function.inc.php : updScheduleStatus : Could not update schedule status : ".$e->getMessage(), 4022);
    }
}

/* -----------------------------------------------------------------------------
  Function Name : UpdateEndSchedule
  Purpose       : Update schedule_status and meeting_end_time for end schedule in schedule_details table
  Parameters    : schedule_id, old_schedule_status, new_schedule_status, meeting_end_time, Datahelper
  Returns       : status (0, 1, 2)
  Calls         : datahelper.putRecords
  Called By     : schedule_end.php
  Author        : Mitesh Shah
  Created  on   : 21-August-2012
  Modified By   :
  Modified on   :
  ------------------------------------------------------------------------------ */

function UpdateEndSchedule($schedule_id, $old_schedule_status, $new_schedule_status, $meeting_end_time, $dataHelper)
{
    try
    {
        if (strlen(trim($schedule_id)) <= 0)
        {
            throw new Exception("daemon_function.inc.php: UpdateEndSchedule : Missing Parameter schedule_id.", 2071);
        }

        if (strlen(trim($old_schedule_status)) <= 0)
        {
            throw new Exception("daemon_function.inc.php: UpdateEndSchedule : Missing Parameter old_schedule_status.", 2072);
        }
        
        if (strlen(trim($new_schedule_status)) <= 0)
        {
            throw new Exception("daemon_function.inc.php: UpdateEndSchedule : Missing Parameter new_schedule_status.", 2072);
        }
        
        if (strlen(trim($meeting_end_time)) <= 0)
        {
            throw new Exception("daemon_function.inc.php: UpdateEndSchedule : Missing Parameter meeting_end_time.", 2072);
        }

        if (!is_object($dataHelper))
        {
            throw new Exception("daemon_function.inc.php : UpdateEndSchedule : DataHelper Object did not instantiate", 104);
        }

        $dataHelper->setParam("'".$schedule_id."'", "I");
        $dataHelper->setParam("'".$old_schedule_status."'", "I");
        $dataHelper->setParam("'".$new_schedule_status."'", "I");
        $dataHelper->setParam("'".$meeting_end_time."'", "I");
        $dataHelper->setParam("result", "O");
        $arrUpdResult = $dataHelper->putRecords("SP", "UpdateEndSchedule");
        $dataHelper->clearParams();
        return $arrUpdResult;
    }
    catch (Exception $e)
    {
        throw new Exception("daemon_function.inc.php : UpdateEndSchedule : Could not update schedule status : ".$e->getMessage(), 4022);
    }
}

function getLMInstanceByClientId($client_id, $dataHelper) {
    if (!is_object($dataHelper))
    {
        throw new Exception("schedule_function.inc.php : getLMInstanceByClientId : DataHelper Object did not instantiate", 104);
    }

    try
    {
        $strSqlStatement = "SELECT client_id, partner_id, logout_url, rt_server_name, rt_server_salt, rt_server_api_url, status FROM client_details  WHERE status = '1' AND client_id = '" . trim($client_id) . "'";
        $arrInstanceList = $dataHelper->fetchRecords("QR", $strSqlStatement);
        return $arrInstanceList;
    }
    catch (Exception $e)
    {
        throw new Exception("schedule_function.inc.php : getLMInstanceByClientId : Could not fetch records : " . $e->getMessage(), 1111);
    }
}