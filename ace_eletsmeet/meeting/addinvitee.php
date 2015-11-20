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

 //if (isset($_POST['btnAddInvitee']))
  if(  (isset($_POST['txtScheduleId'])) &&  (isset($_POST['txtName'])) && (isset($_POST['txtEmail'])) && (isset($_POST['txtScheduleDtls'])) )
{             
     $strScheduleId      = trim($_POST['txtScheduleId']);
     $strScheduleDtls  = trim($_POST['txtScheduleDtls']);
     $strInviteeName    = trim($_POST['txtName']);
     $strInviteEmail       = trim($_POST['txtEmail']);
     
     try
    {
       $arrSchDtls = getScheduleDetailsById($strScheduleId, $objDataHelper);
    }
    catch(Exception $e)
    {
       throw new Exception("index.php : getScheduleDetailsById Failed : ".$e->getMessage() , 1126);
    }
    
    //print_r($arrSchDtls);
    
     if((is_array($arrSchDtls)) && (sizeof($arrSchDtls)) > 0)
     {
         $strUser_id = $arrSchDtls[0]['user_id'];
         try
         {
            $arrInviteeList = getMeetingInviteeList($strScheduleId , $objDataHelper);
         }
         catch(Exception $e)
         {
            throw new Exception("addInvitee.php : getMeetingInviteeList Failed : ".$e->getMessage() , 1141);
         }

         if((is_array($arrInviteeList)) && (sizeof($arrInviteeList)) > 0)
         {
            for($i = 0; $i < sizeof($arrInviteeList); $i++)
            {
               $sArr[$i] = $arrInviteeList[$i]["invitee_email_address"];
            }

            if(in_array($strInviteEmail , $sArr))
            {
                $stat = "0";
                $msg = "Invitee ".$strInviteEmail." already exists.";
            }

            if(sizeof($inviteeList) <= $inviteeCount)
            {
               if(strlen($msg) <= 0)
               {
                  $gmTime = $arrSchDtls[0]["meeting_timestamp_gmt"];
                  $localTime = $arrSchDtls[0]["meeting_timestamp_local"];
                  $timezone = $arrSchDtls[0]["meeting_timezone"];
                  $meeting_title = $arrSchDtls[0]["meeting_title"];

                  $arrInviteeEmailNick = $strInviteEmail.":".$strInviteeName."::";
                  $strUserDetails = "";
                  try
                  {
                     $invitees = insInviteesDetails($strScheduleId , "" , $strUserDetails , $arrInviteeEmailNick , $moderator , $objDataHelper);
                  }
                  catch(Exception $e)
                  {
                     throw new Exception("addInvitee.php : inviteesDetails Failed : ".$e->getMessage() , 1143);
                  }

                   try
                   {
                       $insRequest = insUrlRequest($strScheduleId, "", $arrInviteeEmailNick, $objDataHelper);
                   }
                   catch (Exception $e)
                   {
                        throw new Exception("createSchedule.php : inviteesDetails Failed : ".$e->getMessage(), 1133);
                   }
                  
                  try
                  {
                     $counter = setScheduleCounter($strScheduleId , $objDataHelper);
                  }
                  catch(Exception $e)
                  {
                     throw new Exception("addInvitee.php : setScheduleCounter Failed : ".$e->getMessage() , 1144);
                  }

                  try
                  {
                      $jMail = createInviteesMeetingMail($strScheduleId , $gmTime , $localTime , $timezone , $meeting_title , $strCk_user_email_address , $strCk_user_nick_name, $strInviteEmail);
                  }
                  catch(Exception $e)
                  {
                     throw new Exception("addInvitee.php : createInviteesMeetingMail Failed : ".$e->getMessage() , 1145);
                  }
                  $stat = "1";
                  $msg = "Invitee "."$strInviteEmail"." Added Successfully.";
               }
            }
            else
            {
               $stat = "0";
               $msg = "Your max limit for Invitees is ".$inviteeCount."";
            }
         }
         else
         {
            $stat = "-1";
            $msg = "Invalid Data";
         }
      }
      else
      {
         $stat = "-1";
         $msg = "Invalid SCHID";
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
    <div id="add-invitee">
        <div id="error-msg" class="alert alert-danger errorDisplay"></div>        
        <form name="frmAddInvitee" method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
            <div>
                <label for="form-field-8">Name </label>
                <input id="txtName" class="form-control" type="text" placeholder="Name" name="txtName" maxlength="50"/>
            </div>
            <hr>
            <div>
                <label for="form-field-8">Email Address </label>
                <input id="txtEmail" class="form-control" type="text" placeholder="Email Address" name="txtEmail" maxlength="100"/>
            </div>
            <div class="form-actions center">
                <button class="btn btn-sm btn-yellow" type="button" id="btnAddInvitee" name="btnAddInvitee">
                        Add Invitee
                </button>
            </div>
            <input type="hidden" id="txtScheduleId" name="txtScheduleId" value="<?php echo $Schedule_Id; ?>">
            <input type="hidden" id="txtScheduleDtls" name="txtScheduleDtls" value="<?php echo $strPassCode; ?>">
            
        </form>
    </div>
</div>
<script type='text/javascript'>
$(document).ready(function () {
    $('#error-msg').html('');

    $("#btnAddInvitee").click(function() {
                var SchId  = $("#txtScheduleId").val();
                var iNick   = $("#txtName").val();
                var iEmail = $("#txtEmail").val();
                var SchDtl = $("#txtScheduleDtls").val();

                if($.trim(iNick).length == 0) 
                {
                    $("#error-msg").html("Please enter Nick Name");
                    $("#error-msg").css({"display":"block"});
                    var textbox = document.getElementById("txtName");
                    textbox.focus();
                    textbox.scrollIntoView(true);
                    return false;
                } else if($.trim(iEmail).length == 0) 
                {
                    $("#error-msg").html("Please enter Email Address");
                    $("#error-msg").css({"display":"block"});
                    var textbox = document.getElementById("txtEmail");
                    textbox.focus();
                    textbox.scrollIntoView(true);
                    return false;
                } else if (!(/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(iEmail))) 
                {
                    $("#error-msg").html("Please enter a valid Email Address");
                    $("#error-msg").css({"display":"block"});
                    var textbox = document.getElementById("txtEmail");
                    textbox.focus();
                    textbox.scrollIntoView(true);
                    return false;
                }
                else
                {
                    $.post("addinvitee.php", {txtScheduleId: SchId, txtScheduleDtls: SchDtl, txtName: iNick, txtEmail: iEmail}, function (data)
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
                            $("#add-invitee").addClass("errorDisplay");
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