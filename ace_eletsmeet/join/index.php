<?php
require_once('../includes/global.inc.php');
require_once(CLASSES_PATH.'error.inc.php');
require_once(DBS_PATH.'DataHelper.php');
require_once(DBS_PATH.'objDataHelper.php');
require_once(INCLUDES_PATH.'api_db_function.inc.php');
require_once(INCLUDES_PATH.'api_function.inc.php');
require_once(INCLUDES_PATH.'Utilities.php');
require_once(INCLUDES_PATH.'db_common_function.inc.php');

$CONST_MODULE = 'jm';
$CONST_PAGEID = 'Join Meeting';

$strSCID = trim($_REQUEST["SCID"]);   //schedule_id
$strEMID = trim($_REQUEST["EMID"]);   //email_address
$strPSCD = trim($_REQUEST["PSCD"]);   //passcode
$strPRID = trim($_REQUEST["PRID"]);   //protocol id

$Joinee_IP_Address = $_SERVER['REMOTE_ADDR'];
$arrHead=apache_request_headers();
$arrHeaders = array_change_key_case($arrHead,CASE_LOWER);
$clientBrowser = trim($arrHeaders['user-agent']);
   
//Update the invitee IP Address and Headers
$IPUpdate = updInviteeIPHeaders($strSCID, $strEMID, $Joinee_IP_Address, $clientBrowser, $objDataHelper);
                            
$strResponse = verifyScheduleInvite($strSCID, $strEMID, $strPSCD, $strPRID, $objDataHelper);

showForm($strResponse, $objDataHelper);
exit;

function showForm($strResponse, $objDataHelper)
{
    $arrResult = explode(SEPARATOR, $strResponse);

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
    $WEB_BRIDGE = trim($arrResult[13]); //web_voice
    $MAX_PARTICIPANTS = trim($arrResult[14]); //max_participants
    $REC_FLAG = trim($arrResult[15]); //record_flag
    $MET_DURATION = trim($arrResult[16]); //meeting_duration
    $MET_TAGS = trim($arrResult[17]); //meta_tags
    $MET_INSTANCE = trim($arrResult[18]); //meta_instance
    $INV_EMAIL = trim($arrResult[19]); //invitee_email_address
    $INV_NICK = trim($arrResult[20]); //invitee_nick_name
    $INV_IDD = trim($arrResult[21]); //invitee_idd_code
    $INV_MOBILE = trim($arrResult[22]); //invitee_mobile_number
    $INV_CREATOR = trim($arrResult[23]); //invitation_creator
    $MET_STATUS = trim($arrResult[24]);  //meeting_status
    $USR_ID = trim($arrResult[25]); //user_id
    $CLN_ID = trim($arrResult[26]); //client_id
    $USR_EMAIL = trim($arrResult[27]); //user email_address
    $USR_NICK = trim($arrResult[28]); //user nick_name
    $PSCD = trim($arrResult[29]); //passcode
    $SUB_ID = trim($arrResult[30]); //subscription_id
    $PLN_ID = trim($arrResult[31]);  //plan_id
    $PLN_TYPE = trim($arrResult[32]);  //plan_type
    $NUM_SESSION = trim($arrResult[33]); //number_of_sessions
    $NUM_OF_MINS_PER_SESSION = trim($arrResult[34]); //number_of_mins_per_sessions
    $CCR_SESSION = trim($arrResult[35]); //concurrent_sessions
    $TLK_TIME_MINS = trim($arrResult[36]); //talk_time_mins
    $CSM_NUM_OF_SESSION = trim($arrResult[37]); //consumed_number_of_sessions
    $CSM_TLK_TIME_MINS = trim($arrResult[38]); //consumed_talk_time_mins
    $PRID = trim($arrResult[39]); //protocol id

    $Current_GMT_Datetime = GM_DATE;

    //echo "<pre/>";
    //print_r($arrResult);
    //echo "<pre/>";
    //echo "<pre/>";
    //print_r($arrInviteesList);
    //echo "/pre/>";


    if ((trim($STATUS) == "1") || (trim($STATUS) == "2"))
    {
        if ((trim($SCH_STATUS) == "0") || (trim($SCH_STATUS) == "1"))
        {
            //echo "here1";

            if (isset($_POST['response_submit']))
            {
                if (($_POST['response_submit']) == "Accept Request")
                {
                    $ADRS = "1";
                    $strResponse = UpdateInvitation($SCH_ID, $ADRS, $INV_EMAIL, $Current_GMT_Datetime, $objDataHelper);
                    $MESG = "You have accepted this meeting request.";
                }
                if (($_POST['response_submit']) == "Decline Request")
                {
                    $ADRS = "2";
                    $strResponse = UpdateInvitation($SCH_ID, $ADRS, $INV_EMAIL, $Current_GMT_Datetime, $objDataHelper);
                    $MESG = "You have declined this meeting request.";
                }
                if (($_POST['response_submit']) == "Maybe Request")
                {
                    $ADRS = "3";
                    $strResponse = UpdateInvitation($SCH_ID, $ADRS, $INV_EMAIL, $Current_GMT_Datetime, $objDataHelper);
                    $MESG = "You are not sure for this meeting request.";
                }
                $errors[] = $MESG;
            }

            try
            {
                $arrConSessionDtls = getUserConcurrentSessions($USR_ID, $SUB_ID, $Current_GMT_Datetime, $objDataHelper);
            }
            catch (Exception $a)
            {
                throw new Exception("Error in getUserConcurrentSessions.".$a->getMessage(), 312);
            }

            if ((is_array($arrConSessionDtls)) && (sizeof($arrConSessionDtls) > 0) && (count($arrConSessionDtls) >= $CCR_SESSION) && ($CCR_SESSION != 0) )
            {
                $Con_Meeting_Count = trim(count($arrConSessionDtls));

                if ($Con_Meeting_Count > 1)
                {
                    for ($intCntr = 0; $intCntr < sizeof($arrConSessionDtls); $intCntr++)
                    {
                        $Con_Schedule_Id .= $arrConSessionDtls[$intCntr]['schedule_id'].",";
                    }
                    $Con_Schedule_Id = explode(",", substr_replace($Con_Schedule_Id, "", -1));
                }
                else
                {
                    $Con_Schedule_Id = array(trim($arrConSessionDtls[0]['schedule_id']));
                    $Con_Meeting_Title = trim($arrConSessionDtls[0]['meeting_title']);
                }

                if (!(in_array($SCH_ID, $Con_Schedule_Id)))
                {
                    $STATUS = 7;
                    if ($INV_CREATOR == "C")
                    {
                        if ($Con_Meeting_Count > 1)
                        {
                            $MESG = "You already have ".$Con_Meeting_Count." meetings in progress.";
                        }
                        else
                        {
                            $MESG = "You already have \" ".$Con_Meeting_Title." \"  meeting in progress.";
                        }
                    }
                    else
                    {
                        $MESG = "Sorry you can't join this meeting, Please contact host (".$USR_NICK.").";
                    }
                }
                else
                {
                    if (isset($_POST['join_submit']))
                    {
                        if ((strlen(trim($SCH_ID)) > 0) && (strlen(trim($INV_EMAIL)) > 0) && (strlen(trim($PSCD)) > 0) && (strlen(trim($PRID)) > 0))
                        {
                            $JMXAPI_OUTPUT = Call_JMX_Meeting_API($SCH_ID, $INV_EMAIL, $PSCD, $PRID);

                            $arrJMXAPI_Result = explode(SEPARATOR, $JMXAPI_OUTPUT);

                            $JMXAPI_STATUS = trim($arrJMXAPI_Result[0]);
                            $JMXAPI_MESSAGE = trim($arrJMXAPI_Result[1]);
                            $JMXAPI_URL = trim($arrJMXAPI_Result[2]);

                            if (trim($JMXAPI_STATUS) == "1")
                            {
                                $strReferer = $JMXAPI_URL; 
                                header("Location:".$strReferer);
                            }
                            else
                            {
                                $MESG = $JMXAPI_MESSAGE;
                            }
                        }
                        else
                        {
                            $MESG = "Error while joining meeting, Please try later.";
                        }
                    }
                }
            }
            else
            {
                if (isset($_POST['join_submit']))
                {
                    if ((strlen(trim($SCH_ID)) > 0) && (strlen(trim($INV_EMAIL)) > 0) && (strlen(trim($PSCD)) > 0) && (strlen(trim($PRID)) > 0))
                    {
                        $JMXAPI_OUTPUT = Call_JMX_Meeting_API($SCH_ID, $INV_EMAIL, $PSCD, $PRID);

                        $arrJMXAPI_Result = explode(SEPARATOR, $JMXAPI_OUTPUT);
                        //print_r($arrJMXAPI_Result);

                        $JMXAPI_STATUS = trim($arrJMXAPI_Result[0]);
                        $JMXAPI_MESSAGE = trim($arrJMXAPI_Result[1]);
                        $JMXAPI_URL = trim($arrJMXAPI_Result[2]);

                        if (trim($JMXAPI_STATUS) == "1")
                        {
                            $strReferer = $JMXAPI_URL;
                            header("Location:".$strReferer);
                        }
                        else
                        {
                            $MESG = $JMXAPI_MESSAGE;
                        }
                    }
                    else
                    {
                        $MESG = "Error while joining meeting, Please try later.";
                    }
                }
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
            $MESG = "Sorry, error while joining meeting, Please try later.";
        }
    }
    else if (trim($STATUS) == "-8")
    {
        $MESG = "Sorry, Invalid meeting information, Please contact support.";
    }
    else
    {
        $MESG = $MESG;
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
            <title><?php echo $CONST_SITETITLE; ?></title>
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <link href="<?php echo IMG_PATH; ?>favicon.ico" rel="shortcut icon" type="image/ico">

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
                        <div class="brand">
                            <img src="<?php echo IMG_PATH . CUSTOM_LOGO_NAME; ?>" width="188" height="105" vspace="3px" alt="<?php echo CUSTOM_LOGO_TITLE; ?>" title="<?php echo CUSTOM_LOGO_TITLE; ?>">
                        </div>
                    </div>
                </div>
            </div>
            <!-- Navigation Bar, After Login Menu &  Product Logo -->

            <!-- Main content Area -->
            <div class="container">

                <!-- Bottom content Area -->
                <div class="row">
                    
                    <div class="span12">
                        <h2>LetsMeet</h2>
                    </div>
                    
                    <div class="span12"><hr>
                         
                         <div class="cB"></div>
                         
                         <div>

                             <?php   if ((trim($STATUS) == "1") || (trim($STATUS) == "2") || (trim($STATUS) == "7")) {  ?>
<!--                            <h3><?php //echo JOIN_MEETING_WELCOME_MSG ?></h3><br/>-->
                            <h3><?php echo $USR_NICK; ?> invited you to "<?php echo $MET_TITLE; ?>" </h3><br/>
                            <ul class="unstyled animated fadeIn ">
                                <li><strong>Meeting Title :</strong> <?php echo $MET_TITLE; ?></li><br/>
                                <li><strong>Meeting Hosted By :</strong> <?php echo $USR_NICK; ?></li><br/>
                                <li><strong>Meeting Time :</strong> <?php echo $MET_TIME; ?></li><br/>
                                <li><strong>Meeting Voice Bridge PIN :</strong> <?php echo $VOE_BRIDGE; ?></li><br/>
                            </ul>
                            <table>
                                <tr><strong>Dial in Phone Numbers</strong></tr>
                                <tr>
                                    <td width=""> <span style="top: 5px;"><img src="<?php echo IMG_PATH ; ?>usa_icon.png" width="32" height="32" vspace="0px" alt="USA" title="USA"></span></td>
                                    <td width="">+12542356554, +12012150443</td>
                                    <td width="">&nbsp;</td>
                                    <td width=""><img src="<?php echo IMG_PATH ; ?>canada_icon.png" width="32" height="32" vspace="0px" alt="Canada" title="Canada"></td>
                                    <td width="">+12892030090</td>
                                    <td width="">&nbsp;</td>
                                   <td width=""><img src="<?php echo IMG_PATH ; ?>uk_icon.png" width="32" height="32" vspace="0px" alt="UK" title="UK"></td>
                                    <td width="">+441223794023</td>
                                </tr>
                                <tr><td>&nbsp;</td></tr>
                            </table>
                            <?php
                            if (((trim($SCH_STATUS) == "0") || (trim($SCH_STATUS) == "1")) && (trim($STATUS) == "1"))
                            {
                                if ($SGR_TIME > $Current_GMT_Datetime)
                                {
                                    ?>
                                    <div class='alert alert-info'>Sorry, it is too early to start this meeting, you can start meeting <?php echo MEETING_START_GRACE_INTERVAL; ?> minutes prior to scheduled meeting time.</div>
                                    <?php
                                }
                                else if (($SGR_TIME <= $Current_GMT_Datetime) && ($EGR_TIME >= $Current_GMT_Datetime))
                                {
                                    ?>
                                    <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>" name="frmJoin">
                                        <?php
                                        if (trim($SCH_STATUS) == "1")
                                        {
                                            if (trim($MTSTATUS) == "1")
                                            {
                                            ?>
                                                <div class='alert alert-info'>This meeting has been already created. If you exited and want to join again click button below.</div>
                                                <?php
                                            }
                                            else
                                            {
                                                ?>
                                                <div class='alert alert-info'>This meeting has been already created.</div>
                                                <?php
                                            }
                                        }
                                        ?>
                                        <button name="join_submit" class="btn btn-success" type="submit">JOIN MEETING</button>
                                        <input type='hidden' name ='SCID' value='<?php echo $SCH_ID; ?>'>
                                        <input type='hidden' name ='EMID' value='<?php echo $INV_EMAIL; ?>'>
                                        <input type='hidden' name ='PSCD' value='<?php echo $PSCD; ?>'>
                                        <input type='hidden' name ='PRID' value='<?php echo $PRID; ?>'>
                                    </form><br/>
                                    <?php
                                }
                                else if ($EGR_TIME < $Current_GMT_Datetime)
                                {
                                    ?>
                                    <div class='alert alert-error'>Sorry, it is too late to join this meeting now.</div>
                                    <?php
                                }
                                else
                                {
                                    ?>
                                    <div class='alert alert-error'>Sorry, some technical error in creating meeting, Please try later.</div>
                                    <?php
                                }
                            }
                            else if (((trim($SCH_STATUS) == "0") || (trim($SCH_STATUS) == "1")) && (trim($STATUS) == "2"))
                            {
                                ?>
                                <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>" name="frmJoin">
                                    <div class='alert alert-info'>This meeting has been already running. If you exited and want to join again click button below.</div>
                                    <button name="join_submit" class="btn btn-success" type="submit">JOIN MEETING</button>
                                    <input type='hidden' name ='SCID' value='<?php echo $SCH_ID; ?>'>
                                    <input type='hidden' name ='EMID' value='<?php echo $INV_EMAIL; ?>'>
                                    <input type='hidden' name ='PSCD' value='<?php echo $PSCD; ?>'>
                                    <input type='hidden' name ='PRID' value='<?php echo $PRID; ?>'>
                                </form><br/>
                            <?php
                            }
                            else
                            {
                            ?>
                                <div class='alert alert-error'><?php echo $MESG; ?></div>
                            <?php
                            }
                            ?>

                            <!--Accept Decline Code-->
                            <?php
                            if ($INV_CREATOR != "C")
                            {
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
                                                elseif (trim($ADRS) == "3")
                                                {
                                                    echo "<div class='alert alert-info'>$error</div>";
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
                                            <button name="response_submit" class="btn btn-success" type="submit" value="Accept Request" id="rResponse"><i class="icon-white icon-ok-sign"></i>&nbsp;Accept</button>
                                            <button name="response_submit" class="btn btn-danger" type="submit" value="Decline Request" id="rResponse"><i class="icon-white icon-remove-sign"></i>&nbsp;Decline</button>
                                            <button name="response_submit" class="btn btn-small" type="submit" value="Maybe Request" id="rResponse"><i class="icon-question-sign"></i>&nbsp;Maybe</button>
                                            <input type='hidden' name ='SCID' value='<?php echo $SCH_ID; ?>'>
                                            <input type='hidden' name ='EMID' value='<?php echo $INV_EMAIL; ?>'>
                                            <input type='hidden' name ='PSCD' value='<?php echo $PSCD; ?>'>
                                            <input type='hidden' name ='PRID' value='<?php echo $PRID; ?>'>
                                        </form><br/>
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
                                    <h4>Number of Participants : <?php echo sizeof($arrInviteesList); ?></h4><br/>
                                    
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
                                                //C= Creator & Moderator, M=Moderator, I=Invitee
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
                                                //0=invited, 1=Joined
                                                switch ($arrInviteesList[$intCntr]['meeting_status'])
                                                {
                                                    case "1" :
                                                        $strMeetingStatus = "Yes";
                                                        break;
                                                    default:
                                                        $strMeetingStatus = "-";
                                                }
                                                ?>
                                                <tr>
                                                    <td><?php echo $arrInviteesList[$intCntr]['invitee_nick_name']; ?></td>
                                                    <td><?php echo $strInvRoll; ?></td>
                                                    <td><?php 
                                                        if (trim($arrInviteesList[$intCntr]['invitation_creator']) == "C")
                                                            echo "-";
                                                        else
                                                            echo $strInvStatus;
                                                        ?></td>
                                                    <td><?php echo $strMeetingStatus; ?></td>
                                                </tr>
                                             <?php } ?>
                                        </tbody>
                                    </table>

                                        <?php } ?>                        
                        
                                <?php
                                }
                                else if (trim($STATUS) == "-8")
                                {
                                ?>
                                    <div class='alert alert-error'><?php echo $MESG; ?></div>
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

                              
                          </div>
                         
                     </div>
                         
                </div>
                <!-- Bottom content Area -->
                
            </div>
            <!-- Main content Area -->

            <!-- Footer content Area -->
            <?php include (INCLUDES_PATH.'footer.php'); ?>
            <!-- Footer content Area -->

        </body>
    </html>

    <?php
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

            //echo "<pre/>";
            //print_r($arrSchDtls);
            //echo "<pre/>";


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
                
                //Commented by Mitesh Shah 29-12-2014
                //$Salt = VIDEO_SERVER_SALT;
                //$IS_MEETING_RUNNING_API_URL = $Meeting_Instance.VIDEO_SERVER_API.VIDEO_SERVER_IS_MEETING_RUNNING_API;    
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
                $IS_MEETING_RUNNING_API_URL = $Meeting_Instance.$LMInstanceAPIUrl.VIDEO_SERVER_IS_MEETING_RUNNING_API;    
                //Added by Mitesh Shah 29-12-2014
                
                $IMRAPI_OUTPUT = Call_IsMeetingRunning_API($IS_MEETING_RUNNING_API_URL, $Schedule_Id, $Salt);
                $arrIMRAPI_Result = explode(SEPARATOR, $IMRAPI_OUTPUT);

                $IMRAPI_ReturnCode = trim($arrIMRAPI_Result[0]);
                $IMRAPI_Running = trim($arrIMRAPI_Result[1]);

                if (($IMRAPI_ReturnCode == "SUCCESS") && ($IMRAPI_Running == "true"))
                {
                    $STATUS = 2;
                    $MESSAGE = "";
                }
                else
                {
                    $STATUS = 1;
                    $MESSAGE = "";
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
            $MESSAGE = "Error, invalid meeting information.";
        }

        $RESPONSE = $STATUS.SEPARATOR.$MESSAGE.SEPARATOR.$Schedule_Id.SEPARATOR.$Schedule_Status.SEPARATOR.
                $Meeting_Time.SEPARATOR.$SG_Time.SEPARATOR.$EG_Time.SEPARATOR.$Meeting_Title.SEPARATOR.
                $Meeting_Agenda.SEPARATOR.$Attendee_Pwd.SEPARATOR.$Moderator_Pwd.SEPARATOR.$Welcome_Message.SEPARATOR.
                $Voice_Bridge.SEPARATOR.$Web_Voice.SEPARATOR.$Max_Participants.SEPARATOR.$Record_Flag.SEPARATOR.
                $Meeting_Duration.SEPARATOR.$Meta_Tags.SEPARATOR.$Meeting_Instance.SEPARATOR.$Invitee_Email.SEPARATOR.
                $Invitee_Nick_Name.SEPARATOR.$Invitee_IDD_Code.SEPARATOR.$Invitee_Mobile_No.SEPARATOR.
                $Invitation_Creator.SEPARATOR.$Meeting_Status.SEPARATOR.$User_Id.SEPARATOR.$Client_Id.SEPARATOR.
                $User_Email.SEPARATOR.$User_NickName.SEPARATOR.$strPSCD.SEPARATOR.$Subscription_Id.SEPARATOR.
                $Plan_Id.SEPARATOR.$Plan_Type.SEPARATOR.$Number_Of_Sessions.SEPARATOR.$Number_Of_Mins_Per_Sessions.SEPARATOR.
                $Concurrent_Sessions.SEPARATOR.$Talk_Time_Mins.SEPARATOR.$Consumed_No_Of_Sessions.SEPARATOR.
                $Consumed_Talk_Time_Mins.SEPARATOR.$strPRID;
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
