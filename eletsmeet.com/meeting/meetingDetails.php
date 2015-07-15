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
require_once(INCLUDES_PATH."sch_function.inc.php");
require_once(INCLUDES_PATH."api_db_function.inc.php");
require_once(INCLUDES_PATH."rc4.php");
require_once(INCLUDES_PATH."api_function.inc.php");


try
{
    $strSCID = trim($_REQUEST["schId"]);   //schedule_id
    $strEMID = trim($_REQUEST["email"]);   //email_address
    $strPSCD = trim($_REQUEST["pC"]);      //pass code
    $strTYPE = trim($_REQUEST["mT"]);       //from page...
        
    $SG_Interval = MEETING_START_GRACE_INTERVAL;
    $EG_Interval = MEETING_END_GRACE_INTERVAL;
    $Current_GMT_Datetime = GM_DATE;

    try
    {
        $arrSchDtls = isScheduleInviteeValid($strSCID, $strPSCD, $strEMID, $SG_Interval, $EG_Interval, $objDataHelper);
    }
    catch (Exception $a)
    {
        throw new Exception("Error in isScheduleInviteeValid.".$a->getMessage(), 311);
    }

    if (is_array($arrSchDtls) && sizeof($arrSchDtls) > 0)
    {
        $Schedule_Id = trim($arrSchDtls[0]['schedule_id']);
        $Schedule_Status = trim($arrSchDtls[0]['schedule_status']);
        $Meeting_Time = dateFormat(trim($arrSchDtls[0]['meeting_timestamp_gmt']), trim($arrSchDtls[0]['meeting_timestamp_local']), trim($arrSchDtls[0]['meeting_timezone']));

        $Meeting_Title = trim($arrSchDtls[0]['meeting_title']);
        $Meeting_Agenda = trim($arrSchDtls[0]['meeting_agenda']);


        $Voice_Bridge = trim($arrSchDtls[0]['voice_bridge']);
        $Max_Participants = trim($arrSchDtls[0]['max_participants']);
        $Meeting_Duration = trim($arrSchDtls[0]['meeting_duration']);
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
        $Meeting_Instance = trim($arrSchDtls[0]['meeting_instance']);

        try
        {
            $arrInviteesList = getMeetingInviteeList($Schedule_Id, $objDataHelper);
        }
        catch (Exception $a)
        {
            throw new Exception("Error in getMeetingInviteeList.".$a->getMessage(), 4103);
        }

        if ($Schedule_Status == "2")
        {
            //Commented by Mitesh Shah 29-12-2014
            //$Salt = VIDEO_SERVER_SALT;
            //$IS_MEETING_RUNNING_API_URL = $Meeting_Instance.VIDEO_SERVER_API.VIDEO_SERVER_GET_RECORDING_API;
            //Commented by Mitesh Shah 29-12-2014
            
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
            
            $Salt = $LMInstanceSalt;
            $IS_MEETING_RUNNING_API_URL = $Meeting_Instance.$LMInstanceAPIUrl.VIDEO_SERVER_GET_RECORDING_API;
            //Added by Mitesh Shah 29-12-2014 
            
            try
            {
                $arrMeetingRecordings = Call_getRecordings_API($IS_MEETING_RUNNING_API_URL, $Schedule_Id, $Salt);
            }
            catch (Exception $a)
            {
                throw new Exception("Error in Call_getRecordings_API.".$a->getMessage(), 4104);
            }
            //print_r($arrMeetingRecordings);
        }
    }
    else
    {
        $STATUS = -8;
        $MESSAGE = "Error, while joining meeting.";
    }
}
catch (Exception $e)
{
    $ErrorHandler->RaiseError($_SERVER["PHP_SELF"], $e->getCode(), $e->getMessage(), true);
}
?>
<hr>
<!-- Main content Area -->
<div class="container">
    <!-- Main hero unit for a primary marketing message or call to action -->
    <!-- Bottom content Area -->
    <div class="row">
        <?php
        if (is_array($arrSchDtls) && sizeof($arrSchDtls) > 0)
        {
            ?>
            <div class="span12">
                <ul class="unstyled pricing animated fadeIn noborder">
                    <li><strong>Meeting Title :</strong> <?php echo $Meeting_Title; ?></li>
                    <li><strong>Meeting Hosted By :</strong> <?php echo $User_NickName; ?></li>
                    <li><strong>Meeting Time :</strong> <?php echo $Meeting_Time; ?></li>
                </ul>

                <?php
                if (sizeof($arrInviteesList) <= 0)
                {
                    echo "<div class='alert alert-error'>Error occurred..!</div>";
                }
                else
                {
                    ?>
                    <div class="pricing mT20 noborder pL10 pR10">
                        <h4>Number of Participants : <?php echo sizeof($arrInviteesList); ?></h4>

                        <table class="tblz01" width="100%">
                            <thead>
                                <tr class="thead">
                                    <td width="25%">Name</td>
                                    <td width="30%">Email Address</td>
                                    <td width="20%">Role</td>
                                    <td width="15%">Invitation Status</td>
                                    <td width="10%">Attendance</td>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                for ($intCntr = 0; $intCntr < sizeof($arrInviteesList); $intCntr++)
                                {
                                    //0=invited, 1=Accepted, 2=Declined, 3=Maybe
                                    switch ($arrInviteesList[$intCntr]['invitation_status'])
                                    {
                                        case "1" :
                                            $strInvStatus = "<span style='color:green'>Accepted</span>";
                                            break;
                                        case "2" :
                                            $strInvStatus = "<span style='color:red'>Declined</span>";
                                            break;
                                        case "3" :
                                            $strInvStatus = "<span style='color:#ffffff'>Maybe</span>";
                                            break;
                                        default:
                                            $strInvStatus = "Invited";
                                    }
                                    switch ($arrInviteesList[$intCntr]['invitation_creator'])
                                    {
                                        case "C" :
                                            $strInvRoll = "<i class='icon-white icon-eye-open'></i>&nbsp;Host (Moderator)";
                                            break;
                                        case "M" :
                                            $strInvRoll = "<i class='icon-white icon-eye-open'></i>&nbsp;Moderator";
                                            break;
                                        default:
                                            $strInvRoll = "<i class='icon-white icon-user'></i>&nbsp;Invitee";
                                    }
                                    switch ($arrInviteesList[$intCntr]['meeting_status'])
                                    {
                                        case "1" :
                                            $strInvAttendance = "Yes";
                                            break;
                                        default:
                                            $strInvAttendance = "-";
                                    }
                                    ?>
                                    <tr>
                                        <td><?php echo $arrInviteesList[$intCntr]['invitee_nick_name']; ?></td>
                                        <td><?php echo $arrInviteesList[$intCntr]['invitee_email_address']; ?></td>
                                        <td><?php echo $strInvRoll; ?></td>
                                        <td><?php
                                            if (trim($arrInviteesList[$intCntr]['invitation_creator']) == "C")
                                                echo "-";
                                            else
                                                echo $strInvStatus;
                                            ?></td>
                                        <td><?php echo $strInvAttendance; ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                <?php
                }
                if (sizeof($arrMeetingRecordings) <= 0)
                {
                    //echo "<div class='alert alert-error'>Error occurred..!</div>";
                }
                else
                {
                    ?>
                    <div class="span12 pricing mT40">
                        <h4>Meeting Recordings : <?php echo sizeof($arrMeetingRecordings); ?> file(s)</h4>

                        <table class="tblz01" width="100%">
                            <tbody>
                            <?php
                            for ($intCntr = 0; $intCntr < sizeof($arrMeetingRecordings); $intCntr++)
                            {
                                ?>
                                    <tr class="thead">
                                        <td class="span2"><?php echo $arrMeetingRecordings[$intCntr]['length']; ?> min(s)</td>
                                        <td>
                                            <a class="btn btn-small" target="_blank" href="<?php echo $arrMeetingRecordings[$intCntr]['url']; ?>"><i class="icon-play-circle"></i> Play</a>
<!--                                     <a class="btn btn-small cPointer" href="#" onclick="recordingDetails('<?php echo $arrMeetingRecordings[$intCntr]['url']; ?>');"><i class="icon-play-circle"></i> Play</a>-->
                                        </td>
                                        
                                        <td>
                                            <div id="layer"></div>
                                            <!-- Recording Box -->
                                            <div id ="popupS" class="record-details" style="display:none">
                                                <div id="RecordDetails"></div>
                                            </div>
                                            <!-- Recording Details Box -->
                                        </td>
            <!--                     <td>
                                           <a class="btn btn-small" target="_blank" href="<?php echo $arrMeetingRecordings[$intCntr]['url']; ?>"><i class="icon-play-circle"></i> Play</a>
                                        </td>
                                        <td><?php //echo $arrMeetingRecordings[$intCntr]['length']; ?></td>-->
                                    </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                    </div>
                <?php
                }

                if ($strTYPE == "m")
                {
                    ?>
                    <div class="fR"><a class="btn btn-primary mT10 mR10 mB10" href="<?php echo $SITE_ROOT."meeting/"; ?>">Back</a></div>
                <?php
            }
            else
            {
                ?>
                    <div class="fR"><a class="btn btn-primary mT10 mR10 mB10" href="<?php echo $SITE_ROOT."schedule/"; ?>">Back</a></div>
            <?php } ?>
            </div>
    <?php
}
else
{
    ?>
            <div class='alert alert-error'><?php echo $MESG; ?></div>
    <?php
}
?>
    </div>
    <!-- Bottom content Area -->
</div>
<!-- Main content Area -->