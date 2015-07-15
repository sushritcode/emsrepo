<?php
require_once('../includes/global.inc.php');
require_once(CLASSES_PATH.'error.inc.php');
require_once(DBS_PATH.'DataHelper.php');
require_once(DBS_PATH.'objDataHelper.php');
require_once(INCLUDES_PATH.'api_db_function.inc.php');
require_once(INCLUDES_PATH.'api_function.inc.php');
require_once(INCLUDES_PATH.'Utilities.php');
require_once(INCLUDES_PATH.'db_common_function.inc.php');

$strSCID = trim($_REQUEST["SCID"]);   //schedule_id
$strEMID = trim($_REQUEST["EMID"]);   //email_address
$strPSCD = trim($_REQUEST["PSCD"]);   //passcode
$strPRID = trim($_REQUEST["PRID"]);   //protocol id

$strResponse = verifyScheduleInvite($strSCID, $strEMID, $strPSCD, $strPRID, $objDataHelper);

showForm($strResponse, $objDataHelper);
exit;

function showForm($strResponse, $objDataHelper)
{
    $arrResult = explode(SEPARATOR, $strResponse);
    //print_r($arrResult);

    $STATUS = trim($arrResult[0]);  //status of verification
    $MESG = trim($arrResult[1]);     //message from verification if any
    $SCH_ID = trim($arrResult[2]); //schedule_id
    $SCH_STATUS = trim($arrResult[3]); //schedule_status
    $MET_TIME = trim($arrResult[4]); //meeting_timestamp_gmt
    $SGR_TIME = trim($arrResult[5]); //meeting_start_grace_time
    $EGR_TIME = trim($arrResult[6]); //meeting_end_grace_time
    $MET_TITLE = trim($arrResult[7]); //meeting_title
    $MET_AGENDA = trim($arrResult[8]); //meeting_agenda
    $ATE_PWD = trim($arrResult[9]); //attendee_password
    $MOE_PWD = trim($arrResult[10]); //moderator_password
    $WEL_MESG = trim($arrResult[11]); //welcome_message
    $VOE_BRIDGE = trim($arrResult[12]); //voice_bridge
    $MAX_PARTI = trim($arrResult[13]); //max_participants
    $MET_DUR = trim($arrResult[14]); //meeting_duration
    $INV_EMAIL = trim($arrResult[15]); //invitee_email_address
    $INV_NICK = trim($arrResult[16]); //invitee_nick_name
    $INV_IDD = trim($arrResult[17]); //invitee_idd_code
    $INV_MOBILE = trim($arrResult[18]); //invitee_mobile_number
    $INV_CREATOR = trim($arrResult[19]); //invitation_creator
    $MET_STATUS = trim($arrResult[20]);  //meeting_status
    $USR_ID = trim($arrResult[21]); //user_id
    $CLN_ID = trim($arrResult[22]); //client_id
    $USR_EMAIL = trim($arrResult[23]); //user email_address
    $USR_NICK = trim($arrResult[24]); //user nick_name
    $PSCD = trim($arrResult[25]); //passcode
    $PRID = trim($arrResult[26]); //protocol id

    $Current_GMT_Datetime = GM_DATE;

    if ((trim($SCH_STATUS) == "0") || (trim($SCH_STATUS) == "1"))
    {
        if (isset($_POST['response_submit']))
        {
            if (($_POST['response_submit']) == "Accept Request")
            {
                $ADRS = "1";
                $strResponse = UpdateInvitation($SCH_ID, $ADRS, $INV_EMAIL, $Current_GMT_Datetime, $objDataHelper);
                $MESG = "You have Accepted this meeting request.";
            }
            if (($_POST['response_submit']) == "Decline Request")
            {
                $ADRS = "2";
                $strResponse = UpdateInvitation($SCH_ID, $ADRS, $INV_EMAIL, $Current_GMT_Datetime, $objDataHelper);
                $MESG = "You have Declined this meeting request.";
            }
            $errors[] = $MESG;
        }
    }
    else if (trim($SCH_STATUS) == "2")
    {
        $MESG = "Meeting is already over.";
    }
    else if (trim($SCH_STATUS) == "3")
    {
        $MESG = "Sorry, meeting has been cancelled.";
    }
    else if (trim($SCH_STATUS) == "4")
    {
        $MESG = "Sorry, meeting is overdue.";
    }
    else
    {
        $MESG = "Sorry, error you can't Accept or Decline this meeting, Please try later!";
    }


    try
    {
        $arrInviteesList = getMeetingInviteeList($SCH_ID, $objDataHelper);
    }
    catch (Exception $a)
    {
        throw new Exception("Error in getMeetingInviteeList.".$a->getMessage(), 4103);
    }
    ?>

    <!DOCTYPE html>
    <html lang="en">
        <!-- Head content Area -->
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
            <meta charset="utf-8">
            <title>Welcome to Q.CONFERENCE</title>
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <meta name="description" content="">
            <meta name="author" content="LittlesparkVT">

            <link href="<?php echo CSS_PATH; ?>bootstrap.css" rel="stylesheet">
            <link href="<?php echo CSS_PATH; ?>responsive.css" rel="stylesheet">
            <link href="<?php echo CSS_PATH; ?>animate.css" rel="stylesheet">
            <link href="<?php echo CSS_PATH; ?>custom.css" rel="stylesheet">
            <link href="<?php echo CSS_PATH; ?>style.css" rel="stylesheet" />
        </head>
        <!-- Head content Area -->

        <body>

            <!-- Navigation Bar, After Login Menu &  Product Logo -->
            <div class="navbar  animated fadeIn">
                <div class="navbar-inner">
                    <div class="container">
                        <div class="brand"><img src="<?php echo IMG_PATH; ?>quadridge_logo.png" alt="Q.CONFERENCE" title="Q.CONFERENCE"></div>
                    </div>
                </div>
            </div>
            <!-- Navigation Bar, After Login Menu &  Product Logo -->

            <!-- Main content Area -->
            <div class="container">
                <!-- Main hero unit for a primary marketing message or call to action -->



                <!-- Middle content Area -->
                <div class="row landingSlogan">
                    <div class="span12">
                        <br>
                        <h2>Simple and reliable multiparty audio-video conference with presentation, annotation and chat.</h2>
                        <br>
                        <br>
                    </div>
                </div>
                <!-- Middle content Area -->

                <!-- Bottom content Area -->
                <div class="row">
                    <div class="span12 well pricing">
                        <h1><?php echo JOIN_MEETING_WELCOME_MSG ?></h1>
                        <ul class="pricing unstyled">
                            <li><strong>Meeting Title :</strong> <?php echo $MET_TITLE; ?></li>
                            <li><strong>Meeting Hosted By :</strong> <?php echo $USR_NICK; ?></li>
                            <li><strong>Meeting Time :</strong> <?php echo $MET_TIME; ?></li>
                            <li><strong>Meeting Voice Bridge Code :</strong> <?php echo $VOE_BRIDGE; ?></li>
                            <li><strong>Dial in Phone Numbers :</strong> (USA) +12062990867</li>
                        </ul>
                        <?php
                        if ((trim($SCH_STATUS) == "0") || (trim($SCH_STATUS) == "1"))
                        {
                            //if (($SGR_TIME <= $Current_GMT_Datetime) && ($EGR_TIME >= $Current_GMT_Datetime))
                            if (($EGR_TIME >= $Current_GMT_Datetime))
                            {
                                ?>
                                <?php if (count($errors)): ?>
                                    <?php foreach ($errors as $error): ?>

                                        <?php
                                        if (trim($ADRS) == "1")
                                        {
                                            echo "<div class='alert alert-success'>$error</div>";
                                        }
                                        else
                                        {
                                            echo "<div class='alert alert-error'>$error</div>";
                                        }
                                        ?>

                                    <?php endforeach;
                                else : ?>
                                    <div class='alert alert-block'>Your response as Participant is requested.</div>
            <?php endif; ?>
                                <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>" name="frmResponse">                       
                                    <button name="response_submit" class="btn btn-success" type="submit" value="Accept Request" id="rResponse">Accept Request</button>
                                    <button name="response_submit" class="btn btn-danger" type="submit" value="Decline Request" id="rResponse">Decline Request</button>
                                    <input type='hidden' name ='SCID' value='<?php echo $SCH_ID; ?>'>
                                    <input type='hidden' name ='EMID' value='<?php echo $INV_EMAIL; ?>'>
                                    <input type='hidden' name ='PSCD' value='<?php echo $PSCD; ?>'>
                                    <input type='hidden' name ='PRID' value='<?php echo $PRID; ?>'>
                                </form>
                                <?php
                            }
                            else
                            {
                                ?>
                                <div class='alert alert-error'>Sorry, it is too late to Accept / Decline this meeting.</div>
                                <?php
                            }
                        }
                        else
                        {
                            ?>
                            <div class='alert alert-error'><?php echo $MESG; ?></div>
                            <?php
                        }
                        ?>

                        <?php
                        if (sizeof($arrInviteesList) <= 0)
                        {
                            echo "<div class='alert alert-error'>Error occurred..!</div>";
                        }
                        else
                        {
                            ?>
                            <div class="span11 pricing">    
                                <h4>Number of Participants : <?php echo sizeof($arrInviteesList); ?></h4>

                                <table class="tblz01" width="100%">
                                    <thead>
                                        <tr class="thead">
                                            <td width="">Participant Name</td>
                                            <td width="20%">Role</td>
                                            <td width="20%">Invitation Status</td>
                                            <td width="20%">Attendance</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        for ($intCntr = 0; $intCntr < sizeof($arrInviteesList); $intCntr++)
                                        {
                                            //0=invited, 1=Accepted, 2=Declined 
                                            switch ($arrInviteesList[$intCntr]['invitation_status'])
                                            {
                                                case "1" :
                                                    $strInvStatus = "Accepted";
                                                    break;
                                                case "2" :
                                                    $strInvStatus = "<span style='color:#D81830'>Declined</span>";
                                                    break;
                                                default:
                                                    $strInvStatus = "Invited";
                                            }
                                            //C= Creator & Moderator, M=Moderator, I=Invitee
                                            switch ($arrInviteesList[$intCntr]['invitation_creator'])
                                            {
                                                case "C" :
                                                    $strInvRoll = "<i class='icon-eye-open'></i>&nbsp;Host (Moderator)";
                                                    break;
                                                case "M" :
                                                    $strInvRoll = "<i class='icon-eye-open'></i>&nbsp;Moderator";
                                                    break;
                                                default:
                                                    $strInvRoll = "<i class='icon-user'></i>&nbsp;Invitee";
                                            }
                                            //0=invited, 1=Joined
                                            switch ($arrInviteesList[$intCntr]['meeting_status'])
                                            {
                                                case "1" :
                                                    $strMeetingStatus = "Joined";
                                                    break;
                                                default:
                                                    $strMeetingStatus = "Invited";
                                            }
                                            ?>
                                            <tr>
                                                <td><?php echo $arrInviteesList[$intCntr]['invitee_nick_name']; ?></td>
                                                <td><?php echo $strInvRoll; ?></td>
                                                <td><?php if (trim($arrInviteesList[$intCntr]['invitation_creator']) == "C")
                                                echo "-";
                                            else
                                                echo $strInvStatus; ?></td>
                                                <td><?php echo $strMeetingStatus; ?></td>
                                            </tr>
                            <?php } ?>
                                    </tbody>
                                </table>
                            </div>
    <?php } ?>
                    </div>
                    <!-- Bottom content Area -->
                </div>
                <!-- Main content Area -->

                <!-- Footer content Area -->
    <?php //include (INCLUDES_PATH.'footer.php');  ?>
                <!-- Footer content Area -->

        </body>
    </html>
    <?
}

function verifyScheduleInvite($strSCID, $strEMID, $strPSCD, $strPRID, $objDataHelper)
{
    try
    {
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
        elseif (strlen(trim($strEMID)) <= 0)
        {
            $STATUS = -1;
            $MESSAGE = "Missing Parameter EMID";
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
            //print_r($arrSchDtls);

            if (is_array($arrSchDtls) && sizeof($arrSchDtls) > 0)
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
                $Max_Participants = trim($arrSchDtls[0]['max_participants']);
                $Meeting_Duration = trim($arrSchDtls[0]['meeting_duration']);
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



                $STATUS = 1;
                $MESSAGE = "";
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
            $MESSAGE = "Error, invalid meeting information.";
        }
        $RESPONSE = $STATUS.SEPARATOR.$MESSAGE.SEPARATOR.$Schedule_Id.SEPARATOR.$Schedule_Status.SEPARATOR.$Meeting_Time.SEPARATOR.$SG_Time.SEPARATOR.$EG_Time.SEPARATOR.$Meeting_Title.SEPARATOR.$Meeting_Agenda.SEPARATOR.$Attendee_Pwd.SEPARATOR.$Moderator_Pwd.SEPARATOR.$Welcome_Message.SEPARATOR.$Voice_Bridge.SEPARATOR.$Max_Participants.SEPARATOR.$Meeting_Duration.SEPARATOR.$Invitee_Email.SEPARATOR.$Invitee_Nick_Name.SEPARATOR.$Invitee_IDD_Code.SEPARATOR.$Invitee_Mobile_No.SEPARATOR.$Invitation_Creator.SEPARATOR.$Meeting_Status.SEPARATOR.$User_Id.SEPARATOR.$Client_Id.SEPARATOR.$User_Email.SEPARATOR.$User_NickName.SEPARATOR.$strPSCD.SEPARATOR.$strPRID;
        return $RESPONSE;
    }
    catch (Exception $e)
    {
        $ErrorHandler->RaiseError($_SERVER["PHP_SELF"], $e->getCode(), $e->getMessage(), true);
    }
}

function UpdateInvitation($SCH_ID, $ADRS, $INV_EMAIL, $Current_GMT_Datetime, $objDataHelper)
{
    try
    {
        $strResult = updInvitationStatus($SCH_ID, $ADRS, $INV_EMAIL, $Current_GMT_Datetime, $objDataHelper);
    }
    catch (Exception $a)
    {
        throw new Exception("pm.php : updInvitationStatus : Erro could not update invitation status".$a->getMessage(), 613);
    }
    return $strResult;
}
?>