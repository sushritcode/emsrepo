<?php 
require_once('../includes/global.inc.php');
require_once(CLASSES_PATH . 'error.inc.php');
require_once(DBS_PATH . 'DataHelper.php');
require_once(DBS_PATH . 'objDataHelper.php');
require_once(INCLUDES_PATH . 'cm_authfunc.inc.php');
$CONST_MODULE = 'meeting';
$CONST_PAGEID = 'Meeting Page';
require_once(INCLUDES_PATH . 'cm_authorize.inc.php');
require_once(INCLUDES_PATH . 'common_function.inc.php');
require_once(INCLUDES_PATH . 'schedule_function.inc.php');

$strScheduleId = trim($_REQUEST['SchId']);
$strPassCode = trim($_REQUEST['SchDtl']);

try
{
   $arrSchDtls = isScheduleValid($strScheduleId, $strCk_user_email_address , $strPassCode, $objDataHelper);
}
catch(Exception $e)
{
   throw new Exception("index.php : getScheduleDetailsById Failed : ".$e->getMessage() , 1126);
}
//echo "<pre>";
//print_r($arrSchDtls);
//echo "<pre>";

$Schedule_Id = trim($arrSchDtls[0]['schedule_id']);
$Schedule_Status = trim($arrSchDtls[0]['schedule_status']);
$Meeting_Title = trim($arrSchDtls[0]['meeting_title']);
$Meeting_Agenda = trim($arrSchDtls[0]['meeting_agenda']);
$Meeting_Time = dateFormat(trim($arrSchDtls[0]['meeting_timestamp_gmt']), trim($arrSchDtls[0]['meeting_timestamp_local']), trim($arrSchDtls[0]['meeting_timezone']));
$Voice_Bridge = trim($arrSchDtls[0]['voice_bridge']);
$Max_Participants = trim($arrSchDtls[0]['max_participants']);
$User_Email = trim($arrSchDtls[0]['email_address']);
$User_NickName = trim($arrSchDtls[0]['nick_name']);

 try
{
    $arrInviteesList = getMeetingInviteeList($Schedule_Id, $objDataHelper);
}
catch (Exception $a)
{
    throw new Exception("Error in getMeetingInviteeList.".$a->getMessage(), 4103);
}
        
?>
<!--<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header no-padding">
            <div class="table-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    <span class="white">&times;</span>
                </button>
                &nbsp;
            </div>
        </div>
        <div class="modal-body">-->
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
                <?php  if (strlen($Meeting_Agenda) > 0) {?>
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
                            <?php for ($intCntr = 0; $intCntr < sizeof($arrInviteesList); $intCntr++){ 
                                
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
                                            $strInvStatus =  "<span class=\"label label-sm label-grey\">Invited</span>";
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
<!--        </div>
    </div>  
</div>-->