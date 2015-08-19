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
   $arrSchValidDtls = isScheduleValid($strScheduleId, $strCk_user_email_address , $strPassCode, $objDataHelper);
}
catch(Exception $e)
{
   throw new Exception("index.php : getScheduleDetailsById Failed : ".$e->getMessage() , 1126);
}

$Schedule_Id = trim($arrSchValidDtls[0]['schedule_id']);
$Meeting_Title = trim($arrSchValidDtls[0]['meeting_title']);

if (isset($_POST['txtCanReason'])) 
{
    $strScheduleId = trim($_POST['txtScheduleId']);
    //$strSchStatus  = trim($_POST['txtSchStatus']);
    $strCanReason  = trim($_POST['txtCanReason']);

    try
    {
       $arrSchDtls = getScheduleDetailsById($strScheduleId, $objDataHelper);
    }
    catch(Exception $e)
    {
       throw new Exception("index.php : getScheduleDetailsById Failed : ".$e->getMessage() , 1126);
    }

    //$Schedule_Id = trim($arrSchDtls[0]['schedule_id']);
    $Schedule_Status = trim($arrSchDtls[0]['schedule_status']);
    $User_Id = trim($arrSchDtls[0]['user_id']);
    $Subscription_Id = trim($arrSchDtls[0]['subscription_id']);
    $UserOrder_Id = $arrSchDtls[0]["order_id"];


    if (trim($Schedule_Status) == "0")
    {
        //Meeting Status = 0 then Update to 3 (Cancelled)
        $New_Schedule_Status = '3';
        $Current_GMT_Datetime = GM_DATE;

        try
        {
            $arrCancelSchedule = cancelSchedule($strScheduleId, $New_Schedule_Status, $Current_GMT_Datetime, $strCanReason, $objDataHelper);
        }
        catch (Exception $a)
        {
            throw new Exception("Error in cancelSchedule.".$a->getMessage(), 4102);
        }
        $strCancelStatus = trim($arrCancelSchedule[0]['@result']);

        if ($strCancelStatus == 1)
        {
            //Cancel Status is 1 (Success) then reduce the number of consumed_session.
            $Type = "S";

            try
            {
                $arrUpdConSession = updConsumedSessions($Subscription_Id, $User_Id, $Type, $objDataHelper);
            }
            catch (Exception $a)
            {
                throw new Exception("Error in updConsumedSessions.".$a->getMessage(), 4103);
            }
            $strUpdConStatus = trim($arrUpdConSession[0]['@result']);
            if ($strUpdConStatus == 1)
            {
                try
                {
                    $arrClSubDtls = getClSubInfoFromUserOrderId($UserOrder_Id, $objDataHelper);
                }
                catch (Exception $e)
                {
                    throw new Exception("createSchedule.php : updConsumedSessions Failed : ".$e->getMessage(), 1137);
                }

                $strClSubId = $arrClSubDtls[0]['client_subscription_id'];
                $strClientId = $arrClSubDtls[0]['client_id'];

                $Type = "S";
                try
                {
                    $updSession = updClientConsumedSessions($strClSubId, $strClientId, $Type, $objDataHelper);
                }
                catch (Exception $e)
                {
                    throw new Exception("createSchedule.php : updConsumedSessions Failed : ".$e->getMessage(), 1137);
                }
            }

            //Get the meeting invitee list
            try
            {
                $arrInviteesList = getMeetingInviteeList($Schedule_Id, $objDataHelper);
            }
            catch (Exception $a)
            {
                throw new Exception("Error in getMeetingInviteeList.".$a->getMessage(), 4104);
            }

            //Sending the Cancelation mail to all meeting invitee
            foreach ($arrInviteesList as $key => $value)
            {
                $InviteesEmailnNick .= $value['invitee_email_address'] = $value['invitee_email_address'].'#'.$value['invitee_nick_name'].",";
            }
            $InviteesEmailnNick = substr($InviteesEmailnNick, 0, -1);

            //cancelMeetingMail($Meeting_Title, $Meeting_Time, $Creator_Email, $Meeting_Hosted_By, $InviteesEmailnNick);

            echo"<div id='msg'>yes</div>";
        }
        else
        {
            echo"<div id='msg'>error</div>";
        }
    }
    else
    {
        echo"<div id='msg'>no</div>";
    }
} 
?>

<div class="well">
    <h3 class="smaller"><?php echo $Meeting_Title; ?></h3>
    <hr>
    <div id="success-msg" class="alert alert-success errorDisplay"></div>
    <div id="can-detls">
        <div id="error-msg" class="alert alert-danger errorDisplay"></div>
        <?php if (count($errors)): ?>
             <div class="alert alert-danger">
                <?php foreach ($errors as $error): ?>
                          <?php echo $error; ?>
                <?php endforeach; ?>
             </div>
        <?php endif; ?>
        <form name="frmCanMeeting" method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
            <div>
                <textarea placeholder="Type meeting cancel reason" id="txtCanReason" class="form-control" name="txtCanReason" maxlength="100"></textarea>
            </div>
            <div class="space"></div>
            <div class="center">
                <button class="btn btn-sm btn-success" type="button"  id="btnCanMeeting" name="btnCanMeeting">
                        <i class="ace-icon fa fa-check bigger-110"></i>
                        Submit
                </button>
            </div>
            <input type="hidden" id="txtScheduleId" name="txtScheduleId" value="<?php echo $Schedule_Id; ?>">
        </form>
    </div>
</div>
<script type='text/javascript'>
$(document).ready(function () {
    $('#error-msg').html('');

    $("#btnCanMeeting").click(function() {
        var canReason = $("#txtCanReason").val();
        var SchId = $("#txtScheduleId").val();
        //var SchDtl = $("#txtSchDetails").val();
        if($.trim(canReason).length == 0) 
        {
            $('#success-msg').css({"display": "none"});
            $('#error-msg').css({"display":"block"});
            $('#error-msg').html("Please enter meeting cancel reason");
            var textbox = document.getElementById("txtCanReason");
            textbox.focus();
            textbox.scrollIntoView(true);
            return false;
        }
        else
        {
            //$.post("canceldetails.php", {txtScheduleId: SchId, txtSchDetails: SchDtl, txtCanReason: canReason}, function (data)
            $.post("cancelmeeting.php", {txtScheduleId: SchId, txtCanReason: canReason}, function (data)
            {  
                var $response=$(data);
                var oneval = $response.filter('#msg').text();
                if(oneval == 'yes') 
                {
                    $('#error-msg').css({"display":"none"});
                    $('#success-msg').css({"display":"block"});
                    $('#success-msg').html('Meeting has been cancelled successfully.');
                    $("#can-detls").addClass("errorDisplay");
                }
                else if (oneval == 'no') 
                {
                    $('#error-msg').css({"display":"block"});
                    $('#error-msg').html('Sorry, you can\'t cancel the meeting.');
                }
                else 
                {
                    $('#error-msg').css({"display":"block"});
                    $('#error-msg').html('Error while canceling meeting,Please try later.');
                }
            });
            return false;
        }
    });
});
</script>