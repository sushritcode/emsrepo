<?php
require_once('../includes/global.inc.php');
require_once(CLASSES_PATH . 'error.inc.php');
require_once(DBS_PATH . 'DataHelper.php');
require_once(DBS_PATH . 'objDataHelper.php');
require_once(INCLUDES_PATH . 'schedule_function.inc.php');
require_once(INCLUDES_PATH . 'api_function.inc.php');
require_once(INCLUDES_PATH . 'common_function.inc.php');
require_once(INCLUDES_PATH . 'utilities.php');
$CONST_MODULE = 'join';
$CONST_PAGEID = 'Join Meeting';

$strMID = trim($_REQUEST["MID"]);   //MID
               
//$strSCID = trim($_REQUEST["SCID"]);   //schedule_id
//$strEMID = trim($_REQUEST["EMID"]);   //email_address
//$strPSCD = trim($_REQUEST["PSCD"]);   //passcode
//$strPRID = trim($_REQUEST["PRID"]);   //protocol id 


//$strResponse = verifyScheduleInvite($strMID, $strSCID, $strEMID, $strPSCD, $strPRID, $objDataHelper);
$strResponse = verifyScheduleInvite($strMID, $objDataHelper);

showForm($strResponse, $objDataHelper);
exit;

function showForm($strResponse, $objDataHelper)
{
    $arrResult = explode(SEPARATOR, $strResponse);

//    echo "<pre>";
//    print_r($arrResult);
//    echo "<pre>";
    
    $STATUS = trim($arrResult[0]);  //status of verification
    $MESG = trim($arrResult[1]);     //message from verification if any
    $SCH_ID = trim($arrResult[2]); //schedule_id
    $SCH_STATUS = trim($arrResult[3]); //schedule_status
    $SGR_TIME = trim($arrResult[4]); //meeting_start_grace_time
    $EGR_TIME = trim($arrResult[5]); //meeting_end_grace_time
    $INV_EMAIL = trim($arrResult[6]); //invitee_email_address
    $INV_CREATOR = trim($arrResult[7]); //invitation_creator
    $MET_STATUS = trim($arrResult[8]);  //meeting_status
    $USR_ID = trim($arrResult[9]); //user_id
    $CLN_ID = trim($arrResult[10]); //client_id
    $USR_EMAIL = trim($arrResult[11]); //user email_address
    $USR_NICK = trim($arrResult[12]); //user nick_name
    $PSCD = trim($arrResult[13]); //passcode
    $SUB_ID = trim($arrResult[14]); //subscription_id
    $PLN_ID = trim($arrResult[15]);  //plan_id
    $PLN_TYPE = trim($arrResult[16]);  //plan_type
    $PRID = trim($arrResult[17]); //protocol id
    $MID = trim($arrResult[18]); //MID

    $Current_GMT_Datetime = GM_DATE;
    
    try
    {
        $arrSchDtls = getScheduleDetailsById($SCH_ID, $objDataHelper);
    }
    catch (Exception $e)
    {
        throw new Exception("index.php : getScheduleDetailsById Failed : " . $e->getMessage(), 1126);
    }
    
//    echo "<pre>";
//    print_r($arrSchDtls);
//    echo "<pre>";
    
    $Schedule_Id = trim($arrSchDtls[0]['schedule_id']);
    $Schedule_Status = trim($arrSchDtls[0]['schedule_status']);
    $Meeting_Title = trim($arrSchDtls[0]['meeting_title']);
    $Meeting_Agenda = trim($arrSchDtls[0]['meeting_agenda']);
    $Meeting_Time = dateFormat(trim($arrSchDtls[0]['meeting_timestamp_gmt']), trim($arrSchDtls[0]['meeting_timestamp_local']), trim($arrSchDtls[0]['meeting_timezone']));
    $Voice_Bridge = trim($arrSchDtls[0]['voice_bridge']);
    $Max_Participants = trim($arrSchDtls[0]['max_participants']);
    $User_Email = trim($arrSchDtls[0]['email_address']);
    $User_NickName = trim($arrSchDtls[0]['nick_name']);

    if ((trim($STATUS) == "1") || (trim($STATUS) == "2"))
    {
        if ((trim($SCH_STATUS) == "0") || (trim($SCH_STATUS) == "1"))
        {
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
                        //print_r($arrJMXAPI_Result); exit;

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
    <head>
        <!-- HEAD CONTENT AREA -->
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
        <meta charset="utf-8" />
        <title><?php echo CONST_PRODUCT_NAME; ?> - <?php echo $Meeting_Title; ?></title>
        <meta name="description" content="overview &amp; stats" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
        <!-- HEAD CONTENT AREA -->

        <!-- CSS n JS CONTENT AREA -->
        <?php include (INCLUDES_PATH . 'css_include.php'); ?>    
        <!-- CSS n JS CONTENT AREA -->
    </head>

    <body class="no-skin">

        <!-- TOP NAVIGATION BAR START -->
        <div id="navbar" class="navbar navbar-default">
            <script type="text/javascript">
                try 
                {
                    ace.settings.check('navbar', 'fixed')
                } 
                catch (e) 
                {
                }
            </script>
            <div class="navbar-container" id="navbar-container">
                

                <div class="navbar-header pull-left">
                    <a href="#" class="navbar-brand">
                        <small><i class="fa fa-leaf"></i>&nbsp;LetsMeet</small>
                    </a>
                </div>


                <div class="navbar-buttons navbar-header pull-right" role="navigation">
                    <ul class="nav ace-nav">           
                    </ul>
                </div>
            </div>
        </div>
        <!-- TOP NAVIGATION BAR END -->

        <!-- MAIN CONTAINER START -->
        <div class="main-container" id="main-container">
            <script type="text/javascript">
                try {
                    ace.settings.check('main-container', 'fixed')
                } catch (e) {
                }
            </script>

            <!-- MAIN CONTENT START -->
            <div class="main-content">
                <div class="main-content-inner">

                    <!--  PAGE CONTENT START -->
                    <div class="page-content">

                        <!-- PAGE HEADER -->
                        <div class="page-header">
                            <h1>
                                Join Meeting
                            </h1>
                        </div>
                        <!-- PAGE HEADER -->

                        <div class="row">
                            <div class="col-xs-12">
                                <!-- PAGE CONTENT START -->
                                <?php  if ( (trim($STATUS) == "-1") || (trim($STATUS) == "-8") ){?>
                                
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="center">
                                            <img src="<?php echo IMG_PATH; ?>oops_image.png">
                                        </div>
                                        <div class="center">
                                            <h4>LetsMeet failed due to any of these reasons:</h4>
                                            <h5>Invalid meeting request</h5>
                                            <h5>Our system encountered an obstacle</h5>
                                            <h5>Still unable to join meeting? write to us at support@letsmeet.com</h5>
                                        </div>
                                    </div>    
                                </div>
                                
                                <?php } else {?>
                                
                                <div class="row">
                                   
                                    <div class="col-sm-10">
                                        <div class="well">
                                            <h3><?php echo $User_NickName; ?> invited you to "<?php echo $Meeting_Title; ?>" </h3>
                                            <hr>
                                            <div>
                                                <h5 class="lighter smaller"><i class="ace-icon fa fa-calendar  green"></i> <?php echo $Meeting_Time; ?> </h5>
                                            </div>        
                                            <div class="space"></div>
                                            <div>
                                                <h5 class="lighter smaller"><i class="ace-icon fa fa-user  blue"></i> Hosted By : <?php echo $User_NickName; ?> </h5>
                                            </div>        
                                            <div class="space"></div>
                                            <?php if (strlen($Meeting_Agenda) > 0)
                                            { ?>
                                                <div>
                                                    <h5 class="lighter smaller">Agenda Of Meeting : <?php echo $Meeting_Agenda; ?> </h5>
                                                </div>        
                                                <div class="space"></div>
                                            <?php } ?>
                                            <div>
                                                <h5 class="lighter smaller">Number of Participants : <label class="blue"><?php echo sizeof($arrInviteesList); ?></label></h5>
                                            </div>
                                            
                                            <div class="space"></div>
                                            
                                             <div> 
                                                <?php
                                                if (((trim($SCH_STATUS) == "0") || (trim($SCH_STATUS) == "1")) && (trim($STATUS) == "1"))
                                                {
                                                    if ($SGR_TIME > $Current_GMT_Datetime)
                                                    {
                                                        ?>
                                                        <div class='alert alert-block alert-info'>Sorry, it is too early to start this meeting, you can start meeting <?php echo MEETING_START_GRACE_INTERVAL; ?> minutes prior to scheduled meeting time.</div>
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
                                                                    <div class='alert alert-block alert-info'>This meeting has been already started. If you exited and want to join again click button below.</div>
                                                                    <?php
                                                                }
                                                                else
                                                                {
                                                                    ?>
                                                                    <div class='alert alert-block alert-info'>This meeting has been already started.</div>
                                                                    <?php
                                                                }
                                                            }
                                                            ?>
                                                            <button name="join_submit" class="btn btn-success" type="submit">JOIN MEETING</button>
                                                            <input type='hidden' name ='MID' value='<?php echo $MID; ?>'>
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
                                                        <div class='alert alert-block alert-danger'>Sorry, It is too late to join this meeting now.</div>
                                                        <?php
                                                    }
                                                    else
                                                    {
                                                        ?>
                                                        <div class='alert alert-block alert-danger'>Sorry, Some technical error, Please try later.</div>
                                                        <?php
                                                    }
                                                }
                                                else if (((trim($SCH_STATUS) == "0") || (trim($SCH_STATUS) == "1")) && (trim($STATUS) == "2"))
                                                {
                                                    ?>
                                                    <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>" name="frmJoin">
                                                        <div class='alert alert-block alert-info'>This meeting has been already running. If you exited and want to join again click button below.</div>
                                                        <button name="join_submit" class="btn btn-success btn-sm" type="submit"><i class="ace-icon fa fa-users bigger-120"></i>&nbsp;Join Meeting</button>
                                                        <input type='hidden' name ='MID' value='<?php echo $MID; ?>'>
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
                                                    <div class='alert alert-block alert-danger'><?php echo $MESG; ?></div>
                                                <?php
                                                }
                                                ?>
                                              </div>
                                                
                                                <?php if ($INV_CREATOR != "C") { ?>
                                                   <div class="space"></div>
                                                   <div> 
                                                   <?php if ((trim($SCH_STATUS) == "0") || (trim($SCH_STATUS) == "1")){ ?>
                                                            <?php if (count($errors)):
                                                                foreach ($errors as $error):
                                                                    if (trim($ADRS) == "1")
                                                                    {
                                                                        echo"<div class='alert alert-block alert-success'>$error</div>";
                                                                    }
                                                                    elseif (trim($ADRS) == "3")
                                                                    {
                                                                        echo"<div class='alert alert-block alert-warning'>$error</div>";
                                                                    }
                                                                    else
                                                                    {
                                                                         echo"<div class='alert alert-block alert-danger'>$error</div>";
                                                                    }
                                                                 endforeach;
                                                            else : ?>
                                                            <h4 class="smaller">Your response as Participant is requested.</h4>
                                                            <?php endif; ?>
                                                            <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>" name="frmResponse">                       
                                                                <button name="response_submit" class="btn btn-success btn-sm" type="submit" value="Accept Request" id="rResponse"><i class="ace-icon fa fa-thumbs-o-up bigger-120"></i>&nbsp;Accept</button>
                                                                <button name="response_submit" class="btn btn-danger btn-sm" type="submit" value="Decline Request" id="rResponse"><i class="ace-icon fa fa-thumbs-o-down bigger-120"></i>&nbsp;Decline</button>
                                                                <button name="response_submit" class="btn btn-grey btn-sm" type="submit" value="Maybe Request" id="rResponse"><i class="ace-icon fa fa-question bigger-120"></i>&nbsp;Maybe</button>
                                                                <input type='hidden' name ='MID' value='<?php echo $MID; ?>'>
                                                                <input type='hidden' name ='SCID' value='<?php echo $SCH_ID; ?>'>
                                                                <input type='hidden' name ='EMID' value='<?php echo $INV_EMAIL; ?>'>
                                                                <input type='hidden' name ='PSCD' value='<?php echo $PSCD; ?>'>
                                                                <input type='hidden' name ='PRID' value='<?php echo $PRID; ?>'>
                                                            </form>
                                                        <?php } ?>
                                                        
                                                    </div> 
                                                <?php } ?>
                                            
<!--                                            <div class="alert alert-block alert-info">mites</div>-->
                                            
                                            <div class="space"></div>
                                            
                                            <div>
                                                <table class="table table-striped table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th class="center">*</th>
                                                            <th>Name</th>
                                                            <th>Email Address</th>
                                                            <th class="hidden-480">Role</th>
                                                            <th class="hidden-480">Invitation Status</th>
                                                            <th class="hidden-480">Attendance</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        for ($intCntr = 0; $intCntr < sizeof($arrInviteesList); $intCntr++)
                                                        {
                                                            switch ($arrInviteesList[$intCntr]['invitation_creator'])
                                                            {
                                                                case "C" :
                                                                    $strInvRoll = "<i class=\"ace-icon fa fa-eye green\"></i> Host (Moderator)";
                                                                    break;
                                                                case "M" :
                                                                    $strInvRoll = "<i class=\"ace-icon fa fa-eye blue\"></i> Moderator";
                                                                    break;
                                                                default:
                                                                    $strInvRoll = "<i class=\"ace-icon fa fa-user\"></i> Invitee";
                                                            }
                                                            switch ($arrInviteesList[$intCntr]['invitation_status'])
                                                            {
                                                                case "1" :
                                                                    $strInvStatus = "<span class=\"label label-sm label-success\">Accepted</span>";
                                                                    break;
                                                                case "2" :
                                                                    $strInvStatus = "<span class=\"label label-sm label-danger\">Declined</span>";
                                                                    break;
                                                                case "3" :
                                                                    $strInvStatus = "<span class=\"label label-sm label-warning\">Maybe</span>";
                                                                    break;
                                                                default:
                                                                    $strInvStatus = "<span class=\"label label-sm label-grey\">Invited</span>";
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
                                                                <td class="center"> <i class="ace-icon fa fa-user"></i> </td>
                                                                <td><?php echo $arrInviteesList[$intCntr]['invitee_nick_name']; ?></td>
                                                                <td class=""> <i class="ace-icon fa fa-envelope-square"></i> <?php echo $arrInviteesList[$intCntr]['invitee_email_address']; ?> </td>
                                                                <td class="hidden-480"><?php echo $strInvRoll; ?></td>
                                                                <td class="hidden-480"><?php echo $strInvStatus; ?></td>
                                                                <td class="hidden-480"><?php echo $strInvAttendance; ?></td>
                                                            </tr>
                                                            <?php } ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-sm-2">
                                        <div class="well">
                                            Hello <h6 class="smaller"><?php echo $STATUS; ?></h6>
                                        </div>
                                    </div>
                                </div>

                                <?php } ?>
                                
                                <!-- PAGE CONTENT END -->
                            </div>
                        </div> 

                    </div>
                    <!-- PAGE CONTENT END -->

                </div>
                <span style="color: #fff;"><?php echo $STATUS;?></span>
            </div>
            <!--  MAIN CONTENT END -->

            <!-- FOOTER START -->
            <div class="footer">
                <?php include (INCLUDES_PATH . 'footer.php'); ?>
            </div>
            <!-- FOOTER END -->

            <a href="#" id="btn-scroll-up" class="btn-scroll-up btn btn-sm btn-inverse">
                <i class="ace-icon fa fa-angle-double-up icon-only bigger-110"></i>
            </a>

        </div>
        <!-- MAIN CONTAINER END -->

        <!-- JAVA SCRIPT -->
        <?php //include (INCLUDES_PATH . 'static_js_includes.php'); ?>  
        <?php //include (INCLUDES_PATH . 'other_js_includes.php'); ?>  
        <!-- JAVA SCRIPT -->

    </body>
</html>
<?php
}

//function verifyScheduleInvite($strMID, $strSCID, $strEMID, $strPSCD, $strPRID, $objDataHelper)
function verifyScheduleInvite($strMID, $objDataHelper)
{
    try
    {
        $arrURLResponse = getURLRequestByMID($strMID, $objDataHelper);
    
        $strParamObject = $arrURLResponse [0]['param_object'];
        $arrParamObject = json_decode($strParamObject, true);

        $strSCID = trim($arrParamObject['SCID']);    //schedule_id
        $strEMID = trim($arrParamObject['EMID']);   //email_address
        $strPSCD = trim($arrParamObject['PSCD']); //passcode
        $strPRID = trim($arrParamObject['PRID']);    //protocol id
        
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
            $Joinee_IP_Address = $_SERVER['REMOTE_ADDR'];
            $arrHead = apache_request_headers();
            $arrHeaders = array_change_key_case($arrHead, CASE_LOWER);
            $clientBrowser = trim($arrHeaders['user-agent']);

            //Update the invitee IP Address and Headers
            $IPUpdate = updInviteeIPHeaders($strSCID, $strEMID, $Joinee_IP_Address, $clientBrowser, $objDataHelper);

            $SG_Interval = MEETING_START_GRACE_INTERVAL;
            $EG_Interval = MEETING_END_GRACE_INTERVAL;
            
            try
            {
                $arrSchInviteeDtls = isScheduleInviteeValid($strSCID, $strPSCD, $strEMID, $SG_Interval, $EG_Interval, $objDataHelper);
            }
            catch (Exception $a)
            {
                throw new Exception("Error in isScheduleInviteeValid.".$a->getMessage(), 311);
            }

            if (is_array($arrSchInviteeDtls) && sizeof($arrSchInviteeDtls) > 0)
            {
                $Schedule_Id = trim($arrSchInviteeDtls[0]['schedule_id']);
                $Schedule_Status = trim($arrSchInviteeDtls[0]['schedule_status']);
                $SG_Time = trim($arrSchInviteeDtls[0]['start_grace_time']);
                $EG_Time = trim($arrSchInviteeDtls[0]['end_grace_time']);
                $Invitee_Email = trim($arrSchInviteeDtls[0]['invitee_email_address']);
                $Invitation_Creator = trim($arrSchInviteeDtls[0]['invitation_creator']);
                $Meeting_Status = trim($arrSchInviteeDtls[0]['meeting_status']);
                $User_Id = trim($arrSchInviteeDtls[0]['user_id']);
                $Client_Id = trim($arrSchInviteeDtls[0]['client_id']);
                $User_Email = trim($arrSchInviteeDtls[0]['email_address']);
                $User_NickName = trim($arrSchInviteeDtls[0]['nick_name']);
                $Subscription_Id = trim($arrSchInviteeDtls[0]['subscription_id']);
                $Plan_Id = trim($arrSchInviteeDtls[0]['plan_id']);
                $Plan_Type = trim($arrSchInviteeDtls[0]['plan_type']);
                
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

         $RESPONSE = $STATUS.SEPARATOR.$MESSAGE.SEPARATOR.$Schedule_Id.SEPARATOR.$Schedule_Status.SEPARATOR.$SG_Time.SEPARATOR.$EG_Time.SEPARATOR.$Invitee_Email.SEPARATOR.$Invitation_Creator.SEPARATOR.$Meeting_Status.SEPARATOR.$User_Id.SEPARATOR.$Client_Id.SEPARATOR.$User_Email.SEPARATOR.$User_NickName.SEPARATOR.$strPSCD.SEPARATOR.$Subscription_Id.SEPARATOR.$Plan_Id.SEPARATOR.$Plan_Type.SEPARATOR.$strPRID.SEPARATOR.$strMID;
         
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
