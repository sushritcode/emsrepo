<?php
require_once('../../includes/global.inc.php');
require_once('../includes/config.inc.php');
require_once(CLIENT_CLASSES_PATH . 'client_error.inc.php');
require_once(CLIENT_DBS_PATH . 'DataHelper.php');
require_once(CLIENT_DBS_PATH . 'objDataHelper.php');
require_once(CLIENT_INCLUDES_PATH . 'client_authfunc.inc.php');
$CLIENT_CONST_MODULE = 'cl_reports';
$CLIENT_CONST_PAGEID = 'Meeting Report_1';
require_once(CLIENT_INCLUDES_PATH . 'client_authorize.inc.php');
//require_once(CLIENT_INCLUDES_PATH . 'client_db_function.inc.php');
require_once(CLIENT_INCLUDES_PATH . 'client_reports_function.inc.php');

$strScheduleId = trim($_REQUEST['SchId']);
$strEmailId = trim($_REQUEST['Email']);
$strPassCode = trim($_REQUEST['SchDtl']);

try
{
    $arrSchDtls = isScheduleValid($strScheduleId, $strEmailId, $strPassCode, $objDataHelper);
}
catch (Exception $e)
{
    throw new Exception("index.php : getScheduleDetailsById Failed : " . $e->getMessage(), 1126);
}

//echo "<pre>";
//print_r($arrSchDtls);
//echo "<pre>"; 

$Schedule_Id = trim($arrSchDtls[0]['schedule_id']);
$Schedule_Status = trim($arrSchDtls[0]['schedule_status']);
$Meeting_Title = trim($arrSchDtls[0]['meeting_title']);
$Meeting_Agenda = trim($arrSchDtls[0]['meeting_agenda']);
if (strlen($Meeting_Agenda) <= 0) 
{
    $Meeting_Agenda = "---";
}
$Meeting_Time = dateFormat(trim($arrSchDtls[0]['meeting_timestamp_gmt']), trim($arrSchDtls[0]['meeting_timestamp_local']), trim($arrSchDtls[0]['meeting_timezone']));
$Voice_Bridge = trim($arrSchDtls[0]['voice_bridge']);
$Max_Participants = trim($arrSchDtls[0]['max_participants']);
$User_Email = trim($arrSchDtls[0]['email_address']);
$User_NickName = trim($arrSchDtls[0]['nick_name']);
//$Meeting_Start_Time= trim($arrSchDtls[0]['meeting_start_time']);
$Meeting_Start_Time = date("D, F jS Y, h:i A", strtotime(trim($arrSchDtls[0]['meeting_start_time'])));
$Meeting_End_Time = trim($arrSchDtls[0]['meeting_end_time']);

try
{
    $arrInviteesList = getMeetingInviteeList($Schedule_Id, $objDataHelper);
}
catch (Exception $a)
{
    throw new Exception("Error in getMeetingInviteeList." . $a->getMessage(), 4103);
}
//echo "<pre>";
//print_r($arrInviteesList);
//echo "<pre>";
?>
<div class="well">
    <h4 class="smaller"><?php echo $Meeting_Title; ?></h4>
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
        <h5 class="lighter smaller">Meeting Start Time : <label class="blue"><?php echo $Meeting_Start_Time;?></label></h5>
    </div>
    <div class="space"></div>
    
    <div>
        <h5 class="lighter smaller">Meeting End Time : <label class="blue"><?php echo sizeof($arrInviteesList); ?></label></h5>
    </div>
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
