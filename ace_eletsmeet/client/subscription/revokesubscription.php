<?php
require_once('../../includes/global.inc.php');
require_once('../includes/config.inc.php');
require_once(CLIENT_CLASSES_PATH . 'client_error.inc.php');
require_once(CLIENT_DBS_PATH . 'DataHelper.php');
require_once(CLIENT_DBS_PATH . 'objDataHelper.php');
require_once(CLIENT_INCLUDES_PATH . 'client_authfunc.inc.php');
$CLIENT_CONST_MODULE = 'cl_subscription';
$CLIENT_CONST_PAGEID = 'Subscription Details';
require_once(CLIENT_INCLUDES_PATH . 'client_authorize.inc.php');
require_once(CLIENT_INCLUDES_PATH . 'client_db_function.inc.php');
require_once(CLIENT_INCLUDES_PATH . 'client_reports_function.inc.php');

$SubOrderId = trim($_REQUEST['OrdId']);
$SubPlanId = trim($_REQUEST['PlId']);

try
{
    $arrPlanDetails = getPlanDetailsByPlanId($SubPlanId, $objDataHelper);
}
catch (Exception $e)
{
    throw new Exception("index.php : getScheduleDetailsById Failed : " . $e->getMessage(), 1126);
}

$Plan_Name = trim($arrPlanDetails[0]['plan_name']);



    if((isset($_POST['txOrderId'])) &&  (isset($_POST['txPlanId']))  )
    {   
        $strSubOrderId = trim($_POST['txOrderId']);        
        $strSubPlanId = trim($_POST['txPlanId']);
        
        try
        {
            $arrSubAssignDtls = getClientSubAssignInfoById($strSubOrderId, $objDataHelper);
        }
        catch (Exception $e)
        {
            throw new Exception("index.php : getScheduleDetailsById Failed : " . $e->getMessage(), 1126);
        }
                
        if((is_array($arrSubAssignDtls)) && (sizeof($arrSubAssignDtls)) > 0)
        {      
            $SubScriptionId = trim($arrSubAssignDtls[0]['subscription_id']);
            $SubUserId = trim($arrSubAssignDtls[0]['user_id']);
            $SubOrderId = trim($arrSubAssignDtls[0]['order_id']);
            $SubPlanId = trim($arrSubAssignDtls[0]['plan_id']);
            $SubPlanname = trim($arrSubAssignDtls[0]['plan_name']);
            $SubUsername = trim($arrSubAssignDtls[0]['user_name']);
            $SubStatus = trim($arrSubAssignDtls[0]['subscription_status']);
            
            $GMTDatetime = GM_DATE;
            $SubEndDate = date("Y-m-d", strtotime(GM_DATE));
            $NewSubStatus= "4";
                              
            try
            {
               $arrRevokeStatus = revokeUserSubscription($SubEndDate, $NewSubStatus, $GMTDatetime, $SubScriptionId, $SubUserId, $SubOrderId, $SubPlanId, $objDataHelper);
            }
            catch(Exception $e)
            {
               throw new Exception("index.php : updInvitationStatus Failed : ".$e->getMessage() , 1126);
            }
            
            $strUpdStatus = trim($arrRevokeStatus[0]['@STATUS']);

            if ($strUpdStatus == 1)
            {
                 $ClientSubStatus= "4";
                //update CSM
                 try
                {
                   $arrClientSubStatus = revokeClientSubscription($ClientSubStatus, $GMTDatetime, $strSetClient_ID, $SubOrderId, $SubPlanId, $objDataHelper);
                }
                catch(Exception $e)
                {
                   throw new Exception("index.php : updInvitationStatus Failed : ".$e->getMessage() , 1126);
                }

                $strClientSubUpdStatus = trim($arrClientSubStatus[0]['@STATUS']);
            
                $stat = "1";
                $msg = "Plan  <strong>&QUOT;"."$SubPlanname"."&QUOT;</strong> has been revoke successfully.";
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
        <p> Are you sure, you want to revoke the plan <strong>&QUOT;<?php echo $Plan_Name; ?>&QUOT;</strong> ?</p>
    </div>
</div>
<div id="update-user-btn">
    <form name="frmRevokePlan" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>" class="form-horizontal" role="form">
      <div class="modal-footer">
          <button class="btn btn-sm btn-primary" type="button" data-bb-handler="confirm" id="btnUpdStaus" name="btnUpdStaus">Confirm</button>
      </div>
      <input type="hidden" id="txtOrderId" name="txtOrderId" value="<?php echo $SubOrderId; ?>">
      <input type="hidden" id="txtPlanId" name="txtPlanId" value="<?php echo $SubPlanId; ?>">
   </form>          
</div>

<script type="text/javascript">
 $(document).ready(function () {
//    $('#error-msg').html('');

    $("#btnUpdStaus").click(function() {
            var clOrderId   = $("#txtOrderId").val();
            var clPlanId   = $("#txtPlanId").val();
            
            $.post("revokeplan.php", {txOrderId: clOrderId, txPlanId: clPlanId}, function (data)
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
