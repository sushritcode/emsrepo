<?php
require_once('../includes/global.inc.php');
require_once(CLASSES_PATH.'error.inc.php');
require_once(DBS_PATH.'DataHelper.php');
require_once(DBS_PATH.'objDataHelper.php');
//require_once(INCLUDES_PATH.'api_db_function.inc.php');
require_once(INCLUDES_PATH.'schedule_function.inc.php');
require_once(INCLUDES_PATH.'api_function.inc.php');
require_once(INCLUDES_PATH . 'common_function.inc.php');
require_once(INCLUDES_PATH.'utilities.php');

//header('Content-type: text/plain; charset=utf-8');

try
{
    $strSCID = trim($_REQUEST["SCID"]);   //schedule_id
    $strEMID = trim(urldecode($_REQUEST["EMID"]));   //email_address
    $strPSCD = trim($_REQUEST["PSCD"]);   //passcode
    $strPRID = trim($_REQUEST["PRID"]);   //protocol id
    
    $STATUS = '';

    if (strlen(trim($strSCID)) <= 0)
    {
        $STATUS = -1;
        $MESSAGE = "Missing Parameter SCID";
    }
    elseif (strlen(trim($strPSCD)) <= 0)
    {
        $STATUS = -1;
        $MESSAGE = "Missing Parameter PSCD";
    }
    elseif (strlen(trim($strPRID)) <= 0)
    {
        $STATUS = -1;
        $MESSAGE = "Missing Parameter PRID";
    }
    elseif (!is_numeric($strPRID))
    {
        $STATUS = -1;
        $MESSAGE = "Invalid characters in PRID";
    }
    elseif ($strPRID != PRID)
    {
        $STATUS = -1;
        $MESSAGE = "Invalid PRID";
    }

    $URL = "";

    $RETURN_URL = $SITE_ROOT."join";
    $RETURN_URL_DATA = '?SCID='.urlencode($strSCID).
            '&EMID='.urlencode($strEMID).
            '&PSCD='.urlencode($strPSCD).
            '&PRID='.urlencode($strPRID);

    $Current_GMT_Datetime = GM_DATE;

    if (trim($STATUS) == "")
    {
        $SG_Interval = MEETING_START_GRACE_INTERVAL;
        $EG_Interval = MEETING_END_GRACE_INTERVAL;

        try
        {
            $arrSchDtls = isScheduleInviteeValid($strSCID, $strPSCD, $strEMID, $SG_Interval, $EG_Interval, $objDataHelper);
        }
        catch (Exception $a)
        {
            throw new Exception("Error in isScheduleInviteeValid.".$a->getMessage(), 311);
        }
        
        if ((is_array($arrSchDtls)) && (sizeof($arrSchDtls)) > 0)
        {
            $Schedule_Id = trim($arrSchDtls[0]['schedule_id']);
            $Schedule_Status = trim($arrSchDtls[0]['schedule_status']);
            $Meeting_Time = dateFormat(trim($arrSchDtls[0]['meeting_timestamp_gmt']), trim($arrSchDtls[0]['meeting_timestamp_local']), trim($arrSchDtls[0]['meeting_timezone']));
            $SG_Time = trim($arrSchDtls[0]['start_grace_time']);
            $EG_Time = trim($arrSchDtls[0]['end_grace_time']);
            $Meeting_Title = trim($arrSchDtls[0]['meeting_title']);
            $Meeting_Agenda = trim($arrSchDtls[0]['meeting_agenda']);
            $Attendee_Pwd = trim($arrSchDtls[0]['attendee_password']);
            $Moderator_Pwd = trim($arrSchDtls[0]['moderator_password']);
            $Welcome_Message = trim($arrSchDtls[0]['welcome_message']);
            $Voice_Bridge = trim($arrSchDtls[0]['voice_bridge']);
            $Web_Voice = trim($arrSchDtls[0]['web_voice']);
            $Max_Participants = trim($arrSchDtls[0]['max_participants']);
            $Record_Flag = trim($arrSchDtls[0]['record_flag']);
            $Meeting_Duration = trim($arrSchDtls[0]['meeting_duration']);
            $Meta_Tags = trim($arrSchDtls[0]['meta_tags']);
            $Meeting_Instance = trim($arrSchDtls[0]['meeting_instance']);
            $Invitee_Email = trim($arrSchDtls[0]['invitee_email_address']);
            $Invitee_Nick_Name = trim($arrSchDtls[0]['invitee_nick_name']);
            $Invitee_IDD_Code = trim($arrSchDtls[0]['invitee_idd_code']);
            $Invitee_Mobile_No = trim($arrSchDtls[0]['invitee_mobile_number']);
            $Invitation_Creator = trim($arrSchDtls[0]['invitation_creator']);
            $Meeting_Status = trim($arrSchDtls[0]['meeting_status']);
            $User_Id = trim($arrSchDtls[0]['user_id']);
            $Client_Id = trim($arrSchDtls[0]['client_id']);
            $User_Email = trim($arrSchDtls[0]['email_address']);
            $User_NickName = trim($arrSchDtls[0]['nick_name']);
            $Subscription_Id = trim($arrSchDtls[0]['subscription_id']);
            $Plan_Id = trim($arrSchDtls[0]['plan_id']);
            $Plan_Type = trim($arrSchDtls[0]['plan_type']);
            $Number_Of_Sessions = trim($arrSchDtls[0]['number_of_sessions']);
            $Number_Of_Mins_Per_Sessions = trim($arrSchDtls[0]['number_of_mins_per_sessions']);
            $Concurrent_Sessions = trim($arrSchDtls[0]['concurrent_sessions']);
            $Talk_Time_Mins = trim($arrSchDtls[0]['talk_time_mins']);
            $Consumed_No_Of_Sessions = trim($arrSchDtls[0]['consumed_number_of_sessions']);
            $Consumed_Talk_Time_Mins = trim($arrSchDtls[0]['consumed_talk_time_mins']);

            $AttendeePW = $Attendee_Pwd;
            $ModeratorPW = $Moderator_Pwd;

            if (($Invitation_Creator == "C") || ($Invitation_Creator == "M"))
            {
                $JoinePassword = $ModeratorPW;
            }
            else
            {
                $JoinePassword = $AttendeePW;
            }

            try
            {
                $meetingInstanceDtls = getLMInstanceByClientId($Client_Id, $objDataHelper);
            }
            catch (Exception $e)
            {
                throw new Exception("Error in getLMInstanceByClientId.".$a->getMessage(), 312);
            }

            $LMInstanceURL = $meetingInstanceDtls[0]["rt_server_name"];
            $LMInstanceSalt = $meetingInstanceDtls[0]["rt_server_salt"];
            $LMInstanceLogoutUrl = $meetingInstanceDtls[0]["logout_url"];     
            $LMInstanceAPIUrl = $meetingInstanceDtls[0]["rt_server_api_url"];
            
            $Salt = $LMInstanceSalt;
            $CREATE_MEETING_API_URL = $Meeting_Instance.$LMInstanceAPIUrl.VIDEO_SERVER_CREATE_API;
            $JOIN_MEETING_API_URL = $Meeting_Instance.$LMInstanceAPIUrl.VIDEO_SERVER_JOIN_API;
            $IS_MEETING_RUNNING_API_URL = $Meeting_Instance.$LMInstanceAPIUrl.VIDEO_SERVER_IS_MEETING_RUNNING_API;
            $LogOutURL= $LMInstanceLogoutUrl;
            //Added by Mitesh Shah 29-12-2014

            if (( $strSCID == $Schedule_Id) && ( $strEMID == $Invitee_Email))
            {
                if ($SG_Time > $Current_GMT_Datetime)
                {
                    //echo "start grace time > current time";
                    $STATUS = 1;
                    $MESSAGE = "start grace time > current time";
                    $URL = $RETURN_URL;
                }
                else if (($SG_Time <= $Current_GMT_Datetime) && ($EG_Time >= $Current_GMT_Datetime))
                {
                    //echo "start grace time <= current time and end grace time >= current time";                   
                    if ($Schedule_Status == 0)
                    {
                        $CMAPI_OUTPUT = Call_CreateMeeting_API($CREATE_MEETING_API_URL, $Meeting_Title, $strSCID, $AttendeePW, $ModeratorPW, $Welcome_Message, $Voice_Bridge, $Web_Voice, $Max_Participants, $Record_Flag, $Meeting_Duration, $Meta_Tags, $Salt, $LogOutURL);
                        $arrCMAPI_Result = explode(SEPARATOR, $CMAPI_OUTPUT);

                        $CMAPI_ReturnCode = trim($arrCMAPI_Result[0]);
                        $CMAPI_CreateTime = trim($arrCMAPI_Result[1]);
                        $CMAPI_Message = trim($arrCMAPI_Result[2]);
                       
                        if ($CMAPI_ReturnCode == "SUCCESS")
                        {
                            //Update the schedule_status = 1 (Created), bbb_create_time, meeting_start_time=(Current Time  in GMT) and redirect user to join url
                            $New_Schedule_Status = '1';
                            $blnUpdate = updNewSchedule($strSCID, $New_Schedule_Status, $CMAPI_CreateTime, $CMAPI_Message, $Current_GMT_Datetime, $objDataHelper);

                            //Update the invitee meeting_status = 1 (joined), meeting_status_join_dtm =(now()) and redirect user to joind url
                            $blnUpdate = updInviteeStatus($strSCID, $Invitee_Email, $Current_GMT_Datetime, $objDataHelper);
 
                            $JMURL = Create_JoinMeeting_URL($JOIN_MEETING_API_URL, $strSCID, $Invitee_Nick_Name, $JoinePassword, $Invitee_Email, $Web_Voice, $Salt);

                            try
                            {
                                $arrInviteesList = getMeetingInviteeList($strSCID, $objDataHelper);
                            }
                            catch (Exception $a)
                            {
                                throw new Exception("Error in getMeetingInviteeList.".$a->getMessage(), 4103);
                            }

                            $STATUS = 1;
                            $MESSAGE = "";
                            $URL = $JMURL;
                        }
                        else
                        {
                            $STATUS = -8;
                            $MESSAGE = "Error, Sorry, you can't join this meeting.";
                        }
                    }
                    elseif ($Schedule_Status == 1)
                    {
                        $IMRAPI_OUTPUT = Call_IsMeetingRunning_API($IS_MEETING_RUNNING_API_URL, $strSCID, $Salt);
                        $arrIMRAPI_Result = explode(SEPARATOR, $IMRAPI_OUTPUT);

                        $IMRAPI_ReturnCode = trim($arrIMRAPI_Result[0]);
                        $IMRAPI_Running = trim($arrIMRAPI_Result[1]);

                        if (($IMRAPI_ReturnCode == "SUCCESS") && ($IMRAPI_Running == "true"))
                        {
                            //Update the invitee meeting_status = 1 (joined), meeting_status_join_dtm =(now()) and redirect user to joind url
                            $blnUpdate = updInviteeStatus($strSCID, $Invitee_Email, $Current_GMT_Datetime, $objDataHelper);
                            
                            $JMURL = Create_JoinMeeting_URL($JOIN_MEETING_API_URL, $strSCID, $Invitee_Nick_Name, $JoinePassword, $Invitee_Email, $Web_Voice, $Salt);
                            $STATUS = 1;
                            $MESSAGE = "";
                            $URL = $JMURL;
                        }
                        else
                        {                           
                            $CMAPI_OUTPUT = Call_CreateMeeting_API($CREATE_MEETING_API_URL, $Meeting_Title, $strSCID, $AttendeePW, $ModeratorPW, $Welcome_Message, $Voice_Bridge, $Web_Voice, $Max_Participants, $Record_Flag, $Meeting_Duration, $Meta_Tags, $Salt, $LogOutURL);
                            
                            $arrCMAPI_Result = explode(SEPARATOR, $CMAPI_OUTPUT);

                            $CMAPI_ReturnCode = trim($arrCMAPI_Result[0]);
                            $CMAPI_CreateTime = trim($arrCMAPI_Result[1]);
                            $CMAPI_Message = trim($arrCMAPI_Result[2]);

                            if ($CMAPI_ReturnCode == "SUCCESS")
                            {
                                //Update the meeting_status = 1 (joined), meeting_status_join_dtm =(now()) and redirect user to joind url
                                $blnUpdate = updInviteeStatus($strSCID, $Invitee_Email, $Current_GMT_Datetime, $objDataHelper);
                                                                
                                $JMURL = Create_JoinMeeting_URL($JOIN_MEETING_API_URL, $strSCID, $Invitee_Nick_Name, $JoinePassword, $Invitee_Email, $Web_Voice, $Salt);

                                $STATUS = 1;
                                $MESSAGE = "";
                                $URL = $JMURL;
                            }
                            else
                            {
                                $STATUS = -8;
                                $MESSAGE = "Error, Sorry, you can't join this meeting.";
                            }
                        }
                    }
                    elseif (trim($Schedule_Status) == "2")
                    {
                        $STATUS = 2;
                        $MESSAGE = "Meeting is already over.";
                    }
                    elseif (trim($Schedule_Status) == "3")
                    {
                        $STATUS = 3;
                        $MESSAGE = "Sorry, meeting has been cancelled.";
                    }
                    elseif (trim($Schedule_Status) == "4")
                    {
                        $STATUS = 4;
                        $MESSAGE = "Sorry, meeting is overdue.";
                    }
                    else
                    {
                        $STATUS = 7;
                        $MESSAGE = "Sorry, error while joining meeting, Please try later.";
                    }
                }
                else if ($EG_TIME < $Current_GMT_Datetime)
                {
                    $IMRAPI_OUTPUT = Call_IsMeetingRunning_API($IS_MEETING_RUNNING_API_URL, $strSCID, $Salt);
                    $arrIMRAPI_Result = explode(SEPARATOR, $IMRAPI_OUTPUT);

                    $IMRAPI_ReturnCode = trim($arrIMRAPI_Result[0]);
                    $IMRAPI_Running = trim($arrIMRAPI_Result[1]);

                    if (($IMRAPI_ReturnCode == "SUCCESS") && ($IMRAPI_Running == "true"))
                    {
                        $JMURL = Create_JoinMeeting_URL($JOIN_MEETING_API_URL, $strSCID, $Invitee_Nick_Name, $JoinePassword, $Invitee_Email, $Salt);
                        $STATUS = 1;
                        $MESSAGE = "";
                        $URL = $JMURL;
                    }
                    else
                    {
                        $STATUS = 2;
                        $MESSAGE = "end grace time < current time";
                        $URL = $RETURN_URL;

                        $strReferer = $RETURN_URL.$RETURN_URL_DATA;
                        header("Location:".$strReferer);
                    }
                }
                else
                {
                    $STATUS = 8;
                    $MESSAGE = "Error, while joining meeting";
                    $URL = $RETURN_URL;

                    $strReferer = $RETURN_URL.$RETURN_URL_DATA;
                    header("Location:".$strReferer);
                }
            }
            else
            {
                $STATUS = -1;
                $MESSAGE = "Error, invalid join meeting information.";
            }
        }
        else
        {
            $STATUS = -8;
            $MESSAGE = "Error, while joining meeting.";
        }
    }
    else
    {
        $STATUS = -1;
        $MESSAGE = "Error, invalid join meeting information.";
    }
}
catch (Exception $e)
{
    if ($STATUS == "")
    {
        $STATUS = -8;
    }

    if ($MESSAGE == "")
    {
        $MESSAGE = "Fatal Error";
    }
    print_r($e);
    $ErrorHandler->RaiseError($_SERVER["PHP_SELF"], $e->getCode(), $e->getMessage(), false);
}
$JMX_RESPONSE = $STATUS.SEPARATOR.$MESSAGE.SEPARATOR.$URL;
echo $JMX_RESPONSE;
?>