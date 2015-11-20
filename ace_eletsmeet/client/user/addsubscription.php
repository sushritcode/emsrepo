<?php
require_once('../../includes/global.inc.php');
require_once('../includes/config.inc.php');
require_once(CLIENT_CLASSES_PATH . 'client_error.inc.php');
require_once(DBS_PATH . 'DataHelper.php');
require_once(DBS_PATH . 'objDataHelper.php');
require_once(CLIENT_INCLUDES_PATH . 'client_authfunc.inc.php');
$CLIENT_CONST_MODULE = 'cl_user';
$CLIENT_CONST_PAGEID = 'Add Subscription';
require_once(CLIENT_INCLUDES_PATH . 'client_authorize.inc.php');
require_once(CLIENT_INCLUDES_PATH . 'client_db_function.inc.php');
require_once(CLIENT_INCLUDES_PATH . 'client_reports_function.inc.php');

$UserId = $_REQUEST["userId"];
$UserName = $_REQUEST["uName"];
//$GmtDatetime = GM_DATE;

try
{
    $arrUserDetails = getUserDetailsByUserId($UserId, $objDataHelper);
}
catch (Exception $a)
{
    throw new Exception("response.php : getUserDetailsByUserId : Error in getting User Details." . $a->getMessage(), 541);
}

$db_userid = $arrUserDetails[0]['user_id'];
$db_usename = $arrUserDetails[0]['user_name'];
$db_user_timezone = $arrUserDetails[0]['timezones'];
    
try
{
    $arrPlanDetails = getUnUsedPlanByClientId($strSetClient_ID, $objDataHelper);
}
catch (Exception $a)
{
    throw new Exception("index.php : getPlanDetails : Error in populating Plan Details." . $a->getMessage(), 541);
}

try
{
    $arrUserSubList = getSubscriptionDetailsByUserId($db_userid, $objDataHelper);
}
catch (Exception $a)
{
    throw new Exception("response.php : getSubscriptionDetailsByUserId : Error in getting User Details." . $a->getMessage(), 541);
}
//print_r($arrUserSubList);

if ((isset($_POST['userId'])) && (isset($_POST['txPassword'])) && (isset($_POST['txPlanId'])) && (isset($_POST['txPlanOrdId'])) && (isset($_POST['txPlanSucId'])))
{ 
    $strUserId = trim($_REQUEST['userId']);
    $strPassword = trim($_REQUEST['txPassword']);
    $strPlanId = trim($_REQUEST['txPlanId']);
    $strPlanOrderId = trim($_REQUEST['txPlanOrdId']);
    $strPlanSubscriptionId = trim($_REQUEST['txPlanSucId']);
    
    if (strlen($strPassword) != 0)
    { 
        try 
        {
            $arrAuthUserResult = isAuthenticClient($strCK_Username, md5($strPassword), $objDataHelper);
        } 
        catch (Exception $a) 
        {
            throw new Exception("login.php : isAuthenticUser_API : Error in Authenticing User" . $a->getMessage(), 613);
        }
    
        if (is_array($arrAuthUserResult) && sizeof($arrAuthUserResult) <= 0) 
        {
            $stat = "0";
            $msg = "Incorrect Password, Please re-enter.";
        }
        else
        {
//            $db_usertimezone = $arrUserDetails[0]['timezones'];
//            $gmt_datetime = GM_DATE;
//            $Type = "N";
//            $dateTime = timezoneConverter($Type, $gmt_datetime, $db_usertimezone);
//            $dtm = explode(SEPARATOR, $dateTime);
//            $local_datetime = $dtm[1];
            
            try
            {
                $arrSubPlanDetails = getSubDtlsByClientIdnPlanId($strSetClient_ID, $strPlanId,  $strPlanSubscriptionId, $strPlanOrderId, $objDataHelper);
            }
            catch (Exception $e)
            {
                throw new Exception("addsubscription.php : getPlanDetailsById Failed : " . $e->getMessage(), 1125);
            }              
                            
            //$db_subscriptiondate = $arrSubPlanDetails[0]['subscription_date'];
            $db_subscriptionstartdategmt = $arrSubPlanDetails[0]['subscription_start_date_gmt'];
            $db_subscriptionenddategmt = $arrSubPlanDetails[0]['subscription_end_date_gmt'];
            $db_subscriptionstartdatelocal = $arrSubPlanDetails[0]['subscription_start_date_local'];
            $db_subscriptionend_date_local = $arrSubPlanDetails[0]['subscription_end_date_local'];
            $db_subscriptionstatus = $arrSubPlanDetails[0]['subscription_status'];
            $db_orderid = $arrSubPlanDetails[0]['order_id'];
            $db_planid = $arrSubPlanDetails[0]['plan_id'];
            $db_planname = $arrSubPlanDetails[0]['plan_name'];
            $db_plandesc = $arrSubPlanDetails[0]['plan_desc'];
            $db_planfor = $arrSubPlanDetails[0]['plan_for'];
            $db_plantype = $arrSubPlanDetails[0]['plan_type'];
            $db_numberofsessions = $arrSubPlanDetails[0]['number_of_sessions'];
            $db_numberofminspersessions = $arrSubPlanDetails[0]['number_of_mins_per_sessions'];
            $db_planperiod = $arrSubPlanDetails[0]['plan_period'];
            $db_numberofinvitee = $arrSubPlanDetails[0]['number_of_invitee'];
            $db_meetingrecording = $arrSubPlanDetails[0]['meeting_recording'];
            $db_diskspace = $arrSubPlanDetails[0]['disk_space'];
            $db_isfree = $arrSubPlanDetails[0]['is_free'];
            $db_plancostinr = $arrSubPlanDetails[0]['plan_cost_inr'];
            $db_plancostoth = $arrSubPlanDetails[0]['plan_cost_oth'];
            $db_concurrentsessions = $arrSubPlanDetails[0]['concurrent_sessions'];
            $db_talktimemins = $arrSubPlanDetails[0]['talk_time_mins'];
            //$db_plankeyword = $arrSubPlanDetails[0]['plan_keyword'];
            $db_autorenewflag = $arrSubPlanDetails[0]['autorenew_flag'];    
            $db_consumednumberofsessions = $arrSubPlanDetails[0]['consumed_number_of_sessions'];    
            $db_consumedtalktimemins = $arrSubPlanDetails[0]['consumed_talk_time_mins'];    
               
            if ($db_subscriptionstatus == 4)
            {
                $db_subscriptionstatus = 2;
            }
            else
            {
                $db_subscriptionstatus = $db_subscriptionstatus;
            }
            $gmt_datetime = GM_DATE;
            $gmt_start_date = date("Y-m-d", strtotime(GM_DATE));
            $Type = "N";
            $dateTime = timezoneConverter($Type, $gmt_datetime, $db_user_timezone);
            $dtm = explode(SEPARATOR, $dateTime);
            $local_start_date = date("Y-m-d", strtotime($dtm[1]));
            
            try
            {
                $arrSubscDetails = insUserSubscriptionDetails($db_userid, $gmt_datetime, $gmt_start_date, $db_subscriptionenddategmt, $local_start_date, $db_subscriptionend_date_local, $db_subscriptionstatus, $db_orderid, $db_planid, $db_planname, $db_plandesc, $db_planfor, $db_plantype, $db_numberofsessions, $db_numberofminspersessions, $db_planperiod, $db_numberofinvitee, $db_meetingrecording, $db_diskspace, $db_isfree, $db_plancostinr, $db_plancostoth, $db_concurrentsessions, $db_talktimemins, $db_autorenewflag, $db_consumednumberofsessions, $db_consumedtalktimemins,  $objDataHelper);
            }
            catch (Exception $a)
            {
                throw new Exception("response.php : insSubscriptionMaster : Error in adding subscription details." . $a->getMessage(), 541);
            }
            $subscStatus = $arrSubscDetails[0]['@STATUS'];
            $subsc_id = $arrSubscDetails[0]['@OUTPUT'];
            if ($subscStatus == 1)
            {
                $ClientSubStatus= "2";
                //update CSM
                 try
                {
                   $arrClientSubStatus = updateClientSubscription($ClientSubStatus, $gmt_datetime, $strSetClient_ID, $db_orderid, $db_planid, $objDataHelper);
                }
                catch(Exception $e)
                {
                   throw new Exception("index.php : updInvitationStatus Failed : ".$e->getMessage() , 1126);
                }
                  $stat = "1";
                  $msg = "Plan <b>"."$db_planname"."</b> successfully assigned to <b>"."$db_usename"."</b>.";
            }
            else
            {
                $stat = "0";
                $msg = 'Error in Adding.';
            }
        }
    }
    else
    {
        $stat = "-1";
        $msg = "Invalid Data";
    }
    $finalStat = $stat . SEPARATOR . $msg;
    echo $finalStat;
    exit;
}
?>
<div class="well">
     <div id="success-msg" class="alert alert-success errorDisplay"></div>
    <div id="add-sub">
        <h4 class="smaller">Subscription Details of <span class="blue">"<?php echo $db_usename; ?>"</span></h4>
        
        <hr>
    
        <?php if ((is_array($arrUserSubList) && sizeof($arrUserSubList) > 0))
        { ?>
            <div>
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th class="center">*</th>
                            <th><small>Plan Name</small></th>
                            <th><small>Start Date</small></th>
                            <th><small>End Date</small></th>
                            <th><small>Status</small></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        for ($intCntr = 0; $intCntr < sizeof($arrUserSubList); $intCntr++)
                        {
                            switch ($arrUserSubList[$intCntr]['subscription_status'])
                            {
                                case "1" :
                                    $strSubStatus = "<span class=\"label label-sm label-warning\">Trial</span>";
                                    break;
                                case "2" :
                                    $strSubStatus = "<span class=\"label label-sm label-success\">Subscribe</span>";
                                    break;
                                case "3" :
                                    $strSubStatus = "<span class=\"label label-sm label-danger\">Expired</span>";
                                    break;
                                default:
                                    $strSubStatus = "<span class=\"label label-sm label-grey\">Requested</span>";
                            }
                            ?>
                            <tr>
                                <td class="center"><i class="ace-icon fa fa-user"></i> </td>
                                <td><small><?php echo trim($arrUserSubList[$intCntr]['plan_name']); ?></small></td>
                                <td><small><?php echo trim($arrUserSubList[$intCntr]['subscription_start_date_local']); ?></small></td>
                                <td><small><?php echo trim($arrUserSubList[$intCntr]['subscription_end_date_local']); ?></small></td>
                                <td><small><?php echo $strSubStatus; ?></small></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        <?php } else { ?>
            <div class="alert alert-warning">Sorry, You are not subscribe to a plan.</div>
        <?php } ?>

        <div class="hr hr32 hr-dotted"></div>

        <h4 class="smaller">  Assign Subscription to <span class="blue">"<?php echo $db_usename; ?>"</span></h4>

        <hr>

        <div id="error-msg" class="alert alert-danger errorDisplay"></div> 
            <?php if ((is_array($arrPlanDetails) && sizeof($arrPlanDetails) > 0)) { ?>
                <form name="frmAddSubscription" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" class="form-horizontal" role="form">
                    <div class="form-group required">
                        <label for="form-field-1" class="col-sm-4 control-label no-padding-right"> Password </label>
                        <div class="col-sm-6">
                            <input id="txtPassword" name="txtPassword" type="password" class="col-sm-12" placeholder="Password"  maxlength="15"/>
                        </div>
                    </div>
                    <div class="form-group required">
                        <label for="form-field-1" class="col-sm-4 control-label no-padding-right"> Plan Name </label>
                        <div class="col-sm-6">
                            <select name='txtPlanInfo' class="col-sm-12" id="txtPlanInfo">
                                <?php
                                if (!empty($arrPlanDetails))
                                {
                                    echo"<option value='---'>Select Plan</option>";
                                    for ($intCount = 0; $intCount < sizeof($arrPlanDetails); $intCount++)
                                    {
                                        $strNo = $intCount + 1;
                                        if ($arrPlanDetails[$intCount]['plan_cost_inr'] != '0.00')
                                        {
                                            echo"<option value='" . $arrPlanDetails[$intCount]['plan_id'] . SEPARATOR . $arrPlanDetails[$intCount]['plan_name'] . SEPARATOR . $arrPlanDetails[$intCount]['order_id'] . SEPARATOR . $arrPlanDetails[$intCount]['client_subscription_id'] . SEPARATOR . $arrPlanDetails[$intCount]['is_multiple'] . "'>$strNo " . " &nbsp;-&nbsp; " . $arrPlanDetails[$intCount]['plan_name'] . "&nbsp;-&nbsp;(Rs." . $arrPlanDetails[$intCount]['plan_cost_inr'] . ")</option>";
                                        }
                                        else if ($arrPlanDetails[$intCount]['plan_cost_oth'] != '0.00')
                                        {
                                            echo"<option value='" . $arrPlanDetails[$intCount]['plan_id'] . SEPARATOR . $arrPlanDetails[$intCount]['plan_name'] . SEPARATOR . $arrPlanDetails[$intCount]['order_id'] . SEPARATOR . $arrPlanDetails[$intCount]['client_subscription_id'] . SEPARATOR . $arrPlanDetails[$intCount]['is_multiple'] . "'>$strNo " . "&nbsp;-&nbsp;" . $arrPlanDetails[$intCount]['plan_name'] . "&nbsp;-&nbsp;($&nbsp;" . $arrPlanDetails[$intCount]['plan_cost_oth'] . ")</option>";
                                        }
                                        else
                                        {
                                            echo"<option value='" . $arrPlanDetails[$intCount]['plan_id'] . SEPARATOR . $arrPlanDetails[$intCount]['plan_name'] . SEPARATOR . $arrPlanDetails[$intCount]['order_id'] . SEPARATOR . $arrPlanDetails[$intCount]['client_subscription_id'] . SEPARATOR . $arrPlanDetails[$intCount]['is_multiple'] . "'>$strNo " . "&nbsp;-&nbsp;" . $arrPlanDetails[$intCount]['plan_name'] . "</option>";
                                        }
                                    }
                                }
                                else
                                {
                                    echo"<option value ='---'>Plan Name List not available</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <input type="hidden" id="userId" name="userId" value="<?php echo $UserId; ?>">
                    <div class="form-actions center">
                        <button class="btn btn-sm btn-yellow" type="button" id="btnAddSub" name="btnAddSub"> Submit </button>
                    </div>
                </form>
            <?php } else { ?>
                <div class="alert alert-danger">Sorry, You have consumed all your plan's, For more plan please contact sales@letsmeet.com</div>
            <?php } ?>                   
    </div>
</div>

<script type='text/javascript'>
    var CLIENT_SITE_ROOT = "<?php echo $CLIENT_SITE_ROOT; ?>";

    function PageRefresh( )
    {
        location.reload(true);
    }

    $(document).ready(function () {
        $('#error-msg').html('');

        $("#btnAddSub").click(function () {
            var clUserId   = $("#userId").val();
            var clPassword = $("#txtPassword").val();
            var clPlanInfo = $("#txtPlanInfo").val();
            var substr = clPlanInfo.split('<?php echo SEPARATOR ?>');
            var clPlanId = substr[0];
            var clPlanName = substr[1];
            var clPlanOrderId = substr[2];
            var clPlanSubId = substr[3];
            
            if ($.trim(clPassword).length == 0)
            {
                $("#error-msg").html("Please enter Password.");
                $("#error-msg").css({"display": "block"});
                var textbox = document.getElementById("txtPassword");
                textbox.focus();
                textbox.scrollIntoView(true);
                return false;
            }
            else if ($.trim(clPlanInfo) == "---")
            {
                $("#error-msg").html("Please select Plan Name.");
                $("#error-msg").css({"display": "block"});
                var textbox = document.getElementById("txtPlanInfo");
                textbox.focus();
                textbox.scrollIntoView(true);
                return false;
            }
            else
            {
                $.post("addsubscription.php", {userId: clUserId, txPassword: clPassword, txPlanId: clPlanId, txPlanOrdId: clPlanOrderId, txPlanSucId: clPlanSubId}, function (data)
                {
                    var response = data;
                    $("#response").html(data);
                    var sep = "<?php echo SEPARATOR; ?>";
                    var html = response.split(sep);
                    if (html[0] == 1)
                    {
                        $("#success-msg").html(html[1]);
                        $("#error-msg").removeClass("alert-error");
                        $("#success-msg").addClass("alert-success");
                        $("#success-msg").css({"display": "block"});
                        $("#add-sub").addClass("errorDisplay");
                    }
                    else
                    {
                        $("#error-msg").html(html[1]);
                        $("#error-msg").css({"display": "block"});
                        document.getElementById('txtPassword').value = '';
                        return false;
                    }
                });
                return false;
            }
            $("#error-msg").html("");
            $("#error-msg").css({"display": "none"});
            document.getElementById('txtPassword').value = '';
        });
    });

</script>