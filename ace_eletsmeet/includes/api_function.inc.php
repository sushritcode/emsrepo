<?php

/* -----------------------------------------------------------------------------
  Function Name : Call_CreateMeeting_API
  Purpose       : Creates a meeting, according to the parameters passed.
  Parameters    : CREATE_MEETING_API_URL, Meeting_Title, Schedule_ID, AttendeePWD, ModeratorPWD, Welcome_Message,
  Voice_Bridge, Web_Voice, Max_Participants, Record_Flag, Meeting_Duration, Meta_Tags, Salt
  Returns       : OutPut (with returncode, createTime, message)
  Calls         : create API
  Called By     : jmx.php
  Author        : Mitesh Shah
  Created  on   : 16-June-2012
  Modified By   :
  Modified on   :
  ------------------------------------------------------------------------------ */

function Call_CreateMeeting_API($CREATE_MEETING_API_URL, $Meeting_Title, $Schedule_ID, $AttendeePWD, $ModeratorPWD, $Welcome_Message, $Voice_Bridge, $Web_Voice, $Max_Participants, $Record_Flag, $Meeting_Duration, $Meta_Tags, $Salt, $Logout_URL) {
    try
    {
        if (strlen(trim($CREATE_MEETING_API_URL)) <= 0)
        {
            throw new Exception("Missing parameter.");
        }

        if (strlen(trim($Meeting_Title)) <= 0)
        {
            throw new Exception("Missing parameter.");
        }

        if (strlen(trim($Schedule_ID)) <= 0)
        {
            throw new Exception("Missing parameter.");
        }

        if (strlen(trim($AttendeePWD)) <= 0)
        {
            throw new Exception("Missing parameter.");
        }

        if (strlen(trim($ModeratorPWD)) <= 0)
        {
            throw new Exception("Missing parameter.");
        }

        if (strlen(trim($Salt)) <= 0)
        {
            throw new Exception("Missing parameter.");
        }

        //CALL create Meeting API
        $CMDATA = 'name=' . urlencode($Meeting_Title) .
                '&meetingID=' . urlencode($Schedule_ID) .
                '&attendeePW=' . urlencode($AttendeePWD) .
                '&moderatorPW=' . urlencode($ModeratorPWD) .
                '&voiceBridge=' . urlencode($Voice_Bridge) .
                '&webVoice=' . urlencode($Web_Voice) .
                '&maxParticipants=' . urlencode($Max_Participants) .
                '&record=' . urlencode($Record_Flag) .
                '&duration=' . urlencode($Meeting_Duration) .
                '&meta=' . urlencode($Meta_Tags) .
                '&logoutURL=' . urldecode($Logout_URL);

        $CMCHK = sha1("create" . $CMDATA . $Salt);
        $CMURL = $CREATE_MEETING_API_URL . $CMDATA . '&checksum=' . $CMCHK;
        //echo $CMURL."<br/>";

        $objUtilities = new Utilities;
        $result = $objUtilities->CallScript($CMURL);
        $data_array = (array) simplexml_load_string($result);
        //print_r($data_array);

        $CODE = $data_array['returncode'];
        $CRTIME = $data_array['createTime'];
        $MSG = $data_array['message'];
        $APWD = $data_array['attendeePW'];
        $MPWD = $data_array['moderatorPW'];

        $strOutPut = $CODE . SEPARATOR . $CRTIME . SEPARATOR . $MSG . SEPARATOR . $APWD . SEPARATOR . $MPWD;

        if (DEBUG_LOG == 1)
        {
            error_log(date("Y-m-d H:i:s") . " , " . $CMDATA . " \n\n " . $CMCHK . " \n\n " . $CMURL . " \n\n " . $strOutPut . "\n\n", 3, LOGS_PATH . "CREATE_MEETING_API_" . date('Y-m-d') . ".log");
        }

        return $strOutPut;
    }
    catch (Exception $e)
    {
        throw new Exception("api_function.inc.php : Call_CreateMeeting_API : Error occurred : " . $e->getMessage(), 2001);
    }
}

/* -----------------------------------------------------------------------------
  Function Name : Call_IsMeetingRunning_API
  Purpose       : To check on whether or not a meeting is running by looking it up with meetingID.
  Parameters    : IS_MEETING_RUNNING_API_URL, Schedule_ID, Salt
  Returns       : OutPut (with returncode, running)
  Calls         : isMeetingRunning API
  Called By     : jmx.php
  Author        : Mitesh Shah
  Created  on   : 16-June-2012
  Modified By   :
  Modified on   :
  ------------------------------------------------------------------------------ */

function Call_IsMeetingRunning_API($IS_MEETING_RUNNING_API_URL, $Schedule_ID, $Salt) {
    try
    {
        if (strlen(trim($IS_MEETING_RUNNING_API_URL)) <= 0)
        {
            throw new Exception("Missing parameter.");
        }

        if (strlen(trim($Schedule_ID)) <= 0)
        {
            throw new Exception("Missing parameter.");
        }

        if (strlen(trim($Salt)) <= 0)
        {
            throw new Exception("Missing parameter.");
        }

        //CALL Is Meeting Runing API
        $RMDATA = 'meetingID=' . urlencode($Schedule_ID);
        $RMCHK = sha1("isMeetingRunning" . $RMDATA . $Salt);
        $RMURL = $IS_MEETING_RUNNING_API_URL . $RMDATA . '&checksum=' . $RMCHK;

        $objUtilities = new Utilities;
        $result = $objUtilities->CallScript($RMURL);
        $data_array = (array) simplexml_load_string($result);

        $CODE = $data_array['returncode'];
        $RUNNING = $data_array['running'];

        $strOutPut = $CODE . SEPARATOR . $RUNNING;
        
        if (DEBUG_LOG == 1)
        {
            error_log(date("Y-m-d H:i:s") . " , " . $RMDATA . " \n\n " . $RMCHK . " \n\n " . $RMURL . " \n\n " . $strOutPut . "\n\n", 3, LOGS_PATH . "IS_MEETING_RUNNING_API_" . date('Y-m-d') . ".log");
        }
        
        return $strOutPut;
    }
    catch (Exception $e)
    {
        throw new Exception("api_function.inc.php : Call_IsMeetingRunning_API : Error occurred : " . $e->getMessage(), 2002);
    }
}

/* -----------------------------------------------------------------------------
  Function Name : Create_JoinMeeting_URL
  Purpose       : Joins automatically a user to the meeting specified in the meetingID.
  Parameters    : JOIN_MEETING_API_URL, Schedule_ID, JoineName, JoinePWD, JoineEmail, Salt
  Returns       : OutPut (Join meeting URL)
  Calls         : join API
  Called By     : jmx.php
  Author        : Mitesh Shah
  Created  on   : 16-June-2012
  Modified By   :
  Modified on   :
  ------------------------------------------------------------------------------ */

function Create_JoinMeeting_URL($JOIN_MEETING_API_URL, $Schedule_ID, $JoineName, $JoinePWD, $JoineEmail, $Web_Voice, $Salt) {
    try
    {
        if (strlen(trim($JOIN_MEETING_API_URL)) <= 0)
        {
            throw new Exception("Missing parameter1.");
        }

        if (strlen(trim($Schedule_ID)) <= 0)
        {
            throw new Exception("Missing parameter2.");
        }

        if (strlen(trim($JoineName)) <= 0)
        {
            throw new Exception("Missing parameter3.");
        }

        if (strlen(trim($JoinePWD)) <= 0)
        {
            throw new Exception("Missing parameter4.");
        }

        if (strlen(trim($Salt)) <= 0)
        {
            throw new Exception("Missing parameter5.");
        }

        //CALL Join Meeting API
        $JMDATA = 'meetingID=' . urlencode($Schedule_ID) .
                '&fullName=' . urlencode($JoineName) .
                '&password=' . urlencode($JoinePWD) .
                '&webVoiceConf=' . urlencode($Web_Voice);
        $JMCHK = sha1("join" . $JMDATA . $Salt);
        $JMURL = $JOIN_MEETING_API_URL . $JMDATA . '&checksum=' . $JMCHK;
        $strOutPut = $JMURL;

        if (DEBUG_LOG == 1)
        {
            error_log(date("Y-m-d H:i:s") . " , " . $JMDATA . " \n\n " . $JMCHK . " \n\n " . $JMURL . " \n\n " . $strOutPut . "\n\n", 3, LOGS_PATH . "JOIN_MEETING_API_" . date('Y-m-d') . ".log");
        }

        return $strOutPut;
    }
    catch (Exception $e)
    {
        throw new Exception("api_function.inc.php : Create_JoinMeeting_URL : Error occurred : " . $e->getMessage(), 2003);
    }
}

/* -----------------------------------------------------------------------------
  Function Name : Call_JMX_Meeting_API
  Purpose       : To Validate Schedule Id and Invitee of the meeting from schedule_details and invitation_details table
  Parameters    : Schedule_ID, EMID (invitee_email_address), PSCD(passcode), PRID(protocol id)
  Returns       : OutPut (with schedule_id, schedule_status, meeting_timestamp)
  Calls         : jmx.php
  Called By     : jm.php
  Author        : Mitesh Shah
  Created  on   : 16-June-2012
  Modified By   :
  Modified on   :
  ------------------------------------------------------------------------------ */

function Call_JMX_Meeting_API($Schedule_ID, $EMID, $PSCD, $PRID) {
    try
    {
        if (strlen(trim($Schedule_ID)) <= 0)
        {
            throw new Exception("Missing parameter.");
        }

        if (strlen(trim($EMID)) <= 0)
        {
            throw new Exception("Missing parameter.");
        }

        if (strlen(trim($PSCD)) <= 0)
        {
            throw new Exception("Missing parameter.");
        }

        if (strlen(trim($PRID)) <= 0)
        {
            throw new Exception("Missing parameter.");
        }

        //CALL JMX API
        $DATA = 'SCID=' . urlencode($Schedule_ID) . '&EMID=' . urlencode($EMID) . '&PSCD=' . urlencode($PSCD) . '&PRID=' . $PRID;
        $objUtilities = new Utilities;
        $URL = JMX_API_ROOT . "jmx.php?" . $DATA;
        $result = $objUtilities->CallScript($URL);
        $strOutPut = $result;

        if (DEBUG_LOG == 1)
        {
            error_log(date("Y-m-d H:i:s") . " , " . $DATA . " \n\n " . $URL . " \n\n " . $strOutPut . "\n\n", 3, LOGS_PATH . "JMX_API_" . date('Y-m-d') . ".log");
        }

        return $strOutPut;
    }
    catch (Exception $e)
    {
        throw new Exception("api_function.inc.php : Call_JMX_Meeting_API : Error occurred : " . $e->getMessage(), 2004);
    }
}

/* -----------------------------------------------------------------------------
  Function Name : Call_getRecordings_API
  Purpose       : To get the recordings of meeting with meetingID.
  Parameters    : GET_RECORDING_API_URL, Schedule_ID, Salt
  Returns       : OutPut (with returncode, running)
  Calls         : isMeetingRunning API
  Called By     : jmx.php
  Author        : Mitesh Shah
  Created  on   : 16-June-2012
  Modified By   :
  Modified on   :
  ------------------------------------------------------------------------------ */

function Call_getRecordings_API($GET_RECORDING_API_URL, $Schedule_ID, $Salt) {
    try
    {
        if (strlen(trim($GET_RECORDING_API_URL)) <= 0)
        {
            throw new Exception("Missing parameter.");
        }

        if (strlen(trim($Schedule_ID)) <= 0)
        {
            throw new Exception("Missing parameter.");
        }

        if (strlen(trim($Salt)) <= 0)
        {
            throw new Exception("Missing parameter.");
        }

        //CALL Is Meeting Runing API
        $GRDATA = 'meetingID=' . urlencode($Schedule_ID);
        $GRCHK = sha1("getRecordings" . $GRDATA . $Salt);
        $GRURL = $GET_RECORDING_API_URL . $GRDATA . '&checksum=' . $GRCHK;

        $objUtilities = new Utilities;
        $result = $objUtilities->CallScript($GRURL);
        $data_array = simplexml_load_string($result);


        $i = 0;
        foreach ($data_array->recordings->recording as $recording)
        {
            foreach ($recording->playback->format as $format)
            {
                $arrURLDetails [$i] = array("url" => "$format->url", "length" => "$format->length");
            }
            $i++;
        }

        if (DEBUG_LOG == 1)
        {
            error_log(date("Y-m-d H:i:s") . " , " . $GRDATA . " \n\n " . $GRURL . " \n\n " . $arrURLDetails . "\n\n", 3, LOGS_PATH . "RECORD_API_" . date('Y-m-d') . ".log");
        }
        
        return $arrURLDetails;
    }
    catch (Exception $e)
    {
        throw new Exception("api_function.inc.php : Call_IsMeetingRunning_API : Error occurred : " . $e->getMessage(), 2002);
    }
}

function Call_EndMeeting_API($END_MEETING_API_URL, $Schedule_ID, $ModeratorPWD, $Salt) {
    try
    {
        if (strlen(trim($END_MEETING_API_URL)) <= 0)
        {
            throw new Exception("Missing parameter.");
        }

        if (strlen(trim($Schedule_ID)) <= 0)
        {
            throw new Exception("Missing parameter.");
        }

        if (strlen(trim($Salt)) <= 0)
        {
            throw new Exception("Missing parameter.");
        }

        //CALL Is Meeting Runing API
        $EMDATA = 'meetingID=' . urlencode($Schedule_ID) . '&password=' . urlencode($ModeratorPWD);
        $EMCHK = sha1("end" . $EMDATA . $Salt);
        $EMURL = $END_MEETING_API_URL . $EMDATA . '&checksum=' . $EMCHK;

        $objUtilities = new Utilities;
        $result = $objUtilities->CallScript($EMURL);
        $data_array = (array) simplexml_load_string($result);

        $CODE = $data_array['returncode'];
        $MSGKEY = $data_array['messageKey'];
        $MSG = $data_array['message'];

        $strOutPut = $CODE . SEPARATOR . $MSGKEY . SEPARATOR . $MSG;

        if (DEBUG_LOG == 1)
        {
            error_log(date("Y-m-d H:i:s") . " , " . $EMDATA . " \n\n " . $EMCHK . " \n\n " . $EMURL . " \n\n " . $strOutPut . "\n\n", 3, LOGS_PATH . "END_MEETING_API_" . date('Y-m-d') . ".log");
        }

        return $strOutPut;
    }
    catch (Exception $e)
    {
        throw new Exception("api_function.inc.php : Call_IsMeetingRunning_API : Error occurred : " . $e->getMessage(), 2002);
    }
}

function Call_GetMeetingInfo_API($GET_MEETINGINFO_API_URL, $Schedule_ID, $ModeratorPWD, $Salt) {
    try
    {
        if (strlen(trim($GET_MEETINGINFO_API_URL)) <= 0)
        {
            throw new Exception("Missing parameter.");
        }

        if (strlen(trim($Schedule_ID)) <= 0)
        {
            throw new Exception("Missing parameter.");
        }

        if (strlen(trim($Salt)) <= 0)
        {
            throw new Exception("Missing parameter.");
        }

        //CALL Is Meeting Runing API
        $GMIDATA = 'meetingID=' . urlencode($Schedule_ID) . '&password=' . urlencode($ModeratorPWD);
        $GMICHK = sha1("getMeetingInfo" . $GMIDATA . $Salt);
        $GMIURL = $GET_MEETINGINFO_API_URL . $GMIDATA . '&checksum=' . $GMICHK;

        $objUtilities = new Utilities;
        $result = $objUtilities->CallScript($GMIURL);
        $data_array = (array) simplexml_load_string($result);

        $CODE = $data_array['returncode'];
        $MEETINGID = $data_array['meetingID'];
        $CREATETIME = $data_array['createTime'];
        $STARTTIME = $data_array['startTime'];
        $ENDTIME = $data_array['endTime'];
        $MSGKEY = $data_array['messageKey'];
        $MSG = $data_array['message'];

        $strOutPut = $CODE . SEPARATOR . $MEETINGID . SEPARATOR . $CREATETIME. SEPARATOR . $STARTTIME . SEPARATOR . $ENDTIME. SEPARATOR . $MSGKEY . SEPARATOR . $MSG;

        if (DEBUG_LOG == 1)
        {
            error_log(date("Y-m-d H:i:s") . " , " . $GMIDATA . " \n\n " . $GMICHK . " \n\n " . $GMIURL . " \n\n " . $strOutPut . "\n\n", 3, LOGS_PATH . "GET_MEETINGINFO_API_" . date('Y-m-d') . ".log");
        }

        return $strOutPut;
    }
    catch (Exception $e)
    {
        throw new Exception("api_function.inc.php : Call_GetMeetingInfo_API : Error occurred : " . $e->getMessage(), 2002);
    }
}