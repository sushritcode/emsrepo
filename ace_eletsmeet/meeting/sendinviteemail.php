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
require_once(INCLUDES_PATH."mail_common_function.inc.php");

$strScheduleId = trim($_REQUEST['SchId']);
$strPassCode = trim($_REQUEST['SchDtl']);

try
{
   $arrSchValidDtls = isScheduleValid($strScheduleId, $strCk_user_email_address , $strPassCode, $objDataHelper);
}
catch(Exception $e)
{
   throw new Exception("index.php : getScheduleDetailsById Failed : ".$e->getMessage() , 1126);
}

$Schedule_Id = trim($arrSchValidDtls[0]['schedule_id']);
$Meeting_Title = trim($arrSchValidDtls[0]['meeting_title']);

try
{
    $arrInviteesList = getMeetingInviteeList($Schedule_Id, $objDataHelper);
}
catch (Exception $a)
{
    throw new Exception("Error in getMeetingInviteeList.".$a->getMessage(), 4103);
}
//print_r($arrInviteesList);

if(  (isset($_POST['SchId'])) &&  (isset($_POST['txtInviteeEmail'])) && (isset($_POST['SchDtl'])) )
 {             
         $strScheduleId      = trim($_POST['SchId']);
         $strScheduleDtls  = trim($_POST['SchDtl']);
         $strInviteeName    = trim($_POST['txtInviteeEmail']); 
         
         $inviteesEmail = explode(",", $strInviteeName);
         for ($i = 0; $i < sizeof($inviteesEmail); $i++)
        {
            $inviteesDetails[] = explode(":", $inviteesEmail[$i]);
        }
        
        for ($i = 0; $i < sizeof($inviteesDetails); $i++)
        {
            $inviteesEmailArr .= $inviteesDetails[$i][0].",";
        }
        
        $inviteesEmailArr = substr($inviteesEmailArr, 0, -1);
         
         if((is_array($inviteesDetails)) && (sizeof($inviteesDetails)) > 0)
         {
           
                  $gmTime = $arrSchValidDtls[0]["meeting_timestamp_gmt"];
                  $localTime = $arrSchValidDtls[0]["meeting_timestamp_local"];
                  $timezone = $arrSchValidDtls[0]["meeting_timezone"];
                  $meeting_title = $arrSchValidDtls[0]["meeting_title"];
                  try
                  {
                      $jMail = createInviteesMeetingMail($strScheduleId , $gmTime , $localTime , $timezone , $meeting_title , $strCk_user_email_address , $strCk_user_nick_name, $inviteesEmailArr);
                  }
                  catch(Exception $e)
                  {
                     throw new Exception("addInvitee.php : createInviteesMeetingMail Failed : ".$e->getMessage() , 1145);
                  }
                  $stat = "1";
                  $msg = "Invitee email send successfully.";
         }
         else
         {
            $stat = "0";
            $msg = "Error while resending email, Please try later.";
         }
      $finalStat = $stat.SEPARATOR.$msg;
      echo $finalStat;
      exit;
}
?>
<div class="well">
    <h4 class="smaller"><?php echo $Meeting_Title; ?></h4>
    <hr>
    <div id="success-msg" class="alert alert-success errorDisplay"></div>
    <div id="send-invitee">
        <div id="error-msg" class="alert alert-danger errorDisplay"></div>        
        <form name="frmSendInvitee" method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
             <div>
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th class="center"><i class="ace-icon fa fa-user"></i></th>
                                <th>Name</th>
                                <th>Email Address</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php for ($intCntr = 0; $intCntr < sizeof($arrInviteesList); $intCntr++){ 
                                $strInviteChkValue = $arrInviteesList[$intCntr]['invitee_email_address'].":".$arrInviteesList[$intCntr]['invitee_nick_name'].":".$arrInviteesList[$intCntr]['invitee_idd_code'].":".$arrInviteesList['$intCntr']['invitee_mobile_number'];
                                //$strInviteChkId = $arrInviteesList[$intCntr]['invitation_id'].":".$arrInviteesList[$intCntr]['invitee_email_address'] ;
                                //$strInviteChkName = "dest[]" ;
                                ?>
                            <tr>
                                <td class="center">
                                    <label class="pos-rel">
                                            <input type="checkbox" class="ace" name='uData[]'  id='uData[]' value='<?php echo $strInviteChkValue; ?>'>
                                            <span class="lbl"></span>
                                    </label>
                                </td>
                                <td><?php echo $arrInviteesList[$intCntr]['invitee_nick_name']; ?></td>
                                <td class=""> <i class="ace-icon fa fa-envelope-square"></i> <?php echo $arrInviteesList[$intCntr]['invitee_email_address']; ?></td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                    <div class="form-actions center">
                      <button class="btn btn-sm btn-yellow" type="button" id="btnAddInvitee" name="btnAddInvitee">
                              Resend Invite
                      </button>
                    </div>
                </div>
            <input type="hidden" id="SchId" name="SchId" value="<?php echo $Schedule_Id; ?>">
            <input type="hidden" id="SchDtl" name="SchDtl" value="<?php echo $strPassCode; ?>">
            <input type="hidden" id="txtInviteeEmail" name="txtInviteeEmail" value="">
        </form>
    </div>
</div>
    
    

<script type='text/javascript'>
$(document).ready(function () {

    $('#error-msg').html('');

    $("#btnAddInvitee").click(function() {
            var SchId  = $("#SchId").val();
            var SchDtl = $("#SchDtl").val();    
            var checkVal="", ele = document.getElementsByTagName("input");
            for(var i=0;i < ele.length ;i++)
            {
                if(ele[i].type=="checkbox")
                {
                  if(ele[i].checked)
                  {
                      if(checkVal != "") checkVal+=",";
                      checkVal+=ele[i].value;
                  }
                }
            }
            
            if (checkVal == "")
            {
                $("#error-msg").html("Please select at least one Invitee");
                $("#error-msg").css({"display":"block"});
                //alert("Please select at least one.");
                return false;
            }
            else
            {
                //return true;
                $.post("sendinviteemail.php", {SchId: SchId, SchDtl: SchDtl, txtInviteeEmail: checkVal}, function (data)
                {
                    var response=data;
                    $("#response").html(data);                       
                    var sep  = "<?php echo SEPARATOR; ?>";
                    var html = response.split(sep);
                    if (html[0] == 1) 
                    {
                        $("#success-msg").html(html[1]);
                        $("#error-msg").removeClass("alert-error");
                        $("#success-msg").addClass("alert-success");
                        $("#success-msg").css({"display":"block"});
                        $("#send-invitee").addClass("errorDisplay");
                    } 
                    else 
                    {
                        $("#error-msg").html(html[1]);
                        $("#error-msg").css({"display":"block"});
                        return false;
                    }
                });
                    return false;
            }
                $("#error-msg").html("");
                $("#error-msg").css({"display":"none"});
            });
});
</script>