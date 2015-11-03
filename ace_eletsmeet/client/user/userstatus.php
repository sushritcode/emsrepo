<?php
require_once('../../includes/global.inc.php');
require_once('../includes/config.inc.php');
require_once(CLIENT_CLASSES_PATH . 'client_error.inc.php');
require_once(DBS_PATH . 'DataHelper.php');
require_once(DBS_PATH . 'objDataHelper.php');
require_once(CLIENT_INCLUDES_PATH . 'client_authfunc.inc.php');
$CLIENT_CONST_MODULE = 'cl_user';
$CLIENT_CONST_PAGEID = 'User Status';
require_once(CLIENT_INCLUDES_PATH . 'client_authorize.inc.php');
require_once(CLIENT_INCLUDES_PATH . 'client_reports_function.inc.php');
require_once(CLIENT_INCLUDES_PATH . 'client_db_function.inc.php');

$strUserId = $_REQUEST["userId"];
$strUserStatus = $_REQUEST["iStat"]; 
$strUserName = $_REQUEST["uName"]; 
$GmtDatetime = GM_DATE;

switch($strUserStatus)
{
  case 0: $MsgUserStatus = "Pending";
     break;
  case 1: $MsgUserStatus = "Activate";
     break;
  case 2: $MsgUserStatus = "Deativate";
     break;
  case 3: $MsgUserStatus = "Delete";
     break;
  default: break;
}

    if((isset($_POST['txUserId'])) &&  (isset($_POST['txStatus']))  )
    {   
        $strUserId = trim($_POST['txUserId']);        
        $strNewUserStatus = trim($_POST['txStatus']);
         
        switch($strNewUserStatus)
        {
           case 0: $MsgNewUserStatus = "Pending";
              break;
           case 1: $MsgNewUserStatus = "Actived";
              break;
           case 2: $MsgNewUserStatus = "Deatived";
              break;
           case 3: $MsgNewUserStatus = "Deleted";
              break;
           default: break;
        }
              
        try
        {
           $arrUserDetls = getUserDetailsByUserId($strUserId, $objDataHelper);
        }
        catch(Exception $e)
        {
           throw new Exception("index.php : updInvitationStatus Failed : ".$e->getMessage() , 1126);
        }
        
        if((is_array($arrUserDetls)) && (sizeof($arrUserDetls)) > 0)
        {      
                $strDBUserId = trim($arrUserDetls[0]['user_id']);
                $strDBOldUserStatus = trim($arrUserDetls[0]['login_enabled']);
                $strDBUserName = trim($arrUserDetls[0]['user_name']);

                 $gmt_datetime = GM_DATE;

                if ($strNewUserStatus == 3)
                {
                         try
                        {
                            $expPlan = isUserPlanActive($strDBUserId, $gmt_datetime, $objDataHelper);
                        }
                        catch (Exception $e)
                        {
                            throw new Exception("index.php : isPlanExpired Failed : " . $e->getMessage(), 1125);
                        }
                         $expPlanDtm = strtotime(trim($expPlan[0]["expGMT"]));
                         
                         if ($expPlanDtm != "")
                        {
                                  $stat = "0";
                                  $msg = "You can not delete User <strong>&QUOT;"."$strDBUserName"."&QUOT;</strong> <br/>Please revoke the assigned plan first !";
                        }
                        else
                        {
                            try
                            {
                               $arrUserStatus = updateUserStatus($strDBUserId, $strDBUserName, $strNewUserStatus, $strDBOldUserStatus, $objDataHelper);
                            }
                            catch(Exception $e)
                            {
                               throw new Exception("index.php : updInvitationStatus Failed : ".$e->getMessage() , 1126);
                            }

                            $strUpdStatus = trim($arrUserStatus[0]['@STATUS']);

                            if ($strUpdStatus == 1)
                            {
                                //if ($strNewUserStatus == 3)
                                //{
                                    $strLicense = 1;
                                    $OperationType = 4;
                                    $gmt_datetime = GM_DATE;
                                    try
                                    {
                                        $insClientLicense = insClientLicenseDetails($strSetClient_ID, $strLicense, $OperationType, $gmt_datetime, $objDataHelper);
                                    }
                                    catch (Exception $a)
                                    {
                                        throw new Exception("addsubscription.php : insOrderMaster : Error in adding order master." . $a->getMessage(), 613);
                                    }
                                    $inslicensestatus = $insClientLicense[0]['@STATUS'];                    
                                //}
                                $stat = "1";
                                $msg = "User <strong>&QUOT;"."$strDBUserName"."&QUOT;</strong> "."$MsgNewUserStatus"." successfully.";
                            }
                             else if ($strUpdStatus == 2)
                            {
                                $stat = "0";
                                $msg = "Nothing is updated.";
                            }
                            else
                            {
                                $stat = "0";
                                $msg = 'Error in while updating.';
                            }
                        }
                }
                else
                {
                        try
                       {
                          $arrUserStatus = updateUserStatus($strDBUserId, $strDBUserName, $strNewUserStatus, $strDBOldUserStatus, $objDataHelper);
                       }
                       catch(Exception $e)
                       {
                          throw new Exception("index.php : updInvitationStatus Failed : ".$e->getMessage() , 1126);
                       }

                       $strUpdStatus = trim($arrUserStatus[0]['@STATUS']);

                       if ($strUpdStatus == 1)
                       {
//                           if ($strNewUserStatus == 3)
//                           {
//                               $strLicense = 1;
//                               $OperationType = 4;
//                               $gmt_datetime = GM_DATE;
//                               try
//                               {
//                                   $insClientLicense = insClientLicenseDetails($strSetClient_ID, $strLicense, $OperationType, $gmt_datetime, $objDataHelper);
//                               }
//                               catch (Exception $a)
//                               {
//                                   throw new Exception("addsubscription.php : insOrderMaster : Error in adding order master." . $a->getMessage(), 613);
//                               }
//                               $inslicensestatus = $insClientLicense[0]['@STATUS'];                    
//                           }
                           $stat = "1";
                           $msg = "User <strong>&QUOT;"."$strDBUserName"."&QUOT;</strong> "."$MsgNewUserStatus"." successfully.";
                       }
                        else if ($strUpdStatus == 2)
                       {
                           $stat = "0";
                           $msg = "Nothing is updated.";
                       }
                       else
                       {
                           $stat = "0";
                           $msg = 'Error in while updating.';
                       }
                }
        }
        else
       {
         $stat = "-1";
         $msg = "Error occured, Please try later.";
       }
       $finalStat = $stat.SEPARATOR.$msg;
        echo $finalStat;
        exit;
    } 
?>

<div class="modal-body">
    <button aria-hidden="true" data-dismiss="modal" class="bootbox-close-button close" type="button" style="margin-top: -10px;" onclick="PageRefresh();" alt="Close" title="Close">x</button>

    <div class="space-10"></div>
    <div id="success-msg" class="alert alert-success errorDisplay"></div> 
    <div id="error-msg" class="alert alert-danger errorDisplay"></div>
    <div class="space-4"></div>
        
    <div class="bootbox-body" id="update-user">    
        <p> Are you sure, you want to <strong>&QUOT;<?php echo $MsgUserStatus; ?>&QUOT;</strong> the user <strong>&QUOT;<?php echo $strUserName; ?>&QUOT;</strong> ?</p>
    </div>
</div>
<div id="update-user-btn">
    <form name="frmUpdUserStatus" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>" class="form-horizontal" role="form">
      <div class="modal-footer">
          <button class="btn btn-sm btn-primary" type="button" data-bb-handler="confirm" id="btnUpdStaus" name="btnUpdStaus">Confirm</button>
      </div>
      <input type="hidden" id="txtUserId" name="txtUserId" value="<?php echo $strUserId; ?>">
      <input type="hidden" id="txtStatus" name="txtStatus" value="<?php echo $strUserStatus; ?>">
   </form>          
</div>

<script type="text/javascript">
 $(document).ready(function () {
//    $('#error-msg').html('');

    $("#btnUpdStaus").click(function() {
            var clUserId   = $("#txtUserId").val();
            var clStatus   = $("#txtStatus").val();
            $.post("userstatus.php", {txUserId: clUserId, txStatus: clStatus}, function (data)
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
                    $("#update-user").addClass("errorDisplay");
                    $("#update-user-btn").addClass("errorDisplay");
                } 
                else 
                {
                    $("#error-msg").html(html[1]);
                    $("#error-msg").css({"display":"block"});
                    $("#update-user").addClass("errorDisplay");
                    $("#update-user-btn").addClass("errorDisplay");
                    return false;
                }
            });
            return false;
    });
 });
</script>
