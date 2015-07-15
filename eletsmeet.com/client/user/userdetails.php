<?php
require_once('../../includes/global.inc.php');
require_once('../includes/config.inc.php');
require_once(CLIENT_CLASSES_PATH . 'client_error.inc.php');
require_once(DBS_PATH . 'DataHelper.php');
require_once(DBS_PATH . 'objDataHelper.php');
require_once(CLIENT_INCLUDES_PATH . 'client_authfunc.inc.php');
$CLIENT_CONST_MODULE = 'cluser';
$CLIENT_CONST_PAGEID = 'Subscription Details';
require_once(CLIENT_INCLUDES_PATH . 'client_authorize.inc.php');
require_once(CLIENT_INCLUDES_PATH . 'client_db_function.inc.php');

$strUserId = trim($_REQUEST['txtUserId']);

try
{
    $arrUserDetails = getUserDetailsByUserId($strUserId, $objDataHelper);
}
catch (Exception $a)
{
    throw new Exception("response.php : getUserDetailsByUserId : Error in getting User Details." . $a->getMessage(), 541);
}

$db_userid = $arrUserDetails[0]['user_id'];
$db_useremailid = $arrUserDetails[0]['email_address'];

try
{
    $arrUserSubList = getSubscriptionDetailsByUserId($db_userid, $objDataHelper);
}
catch (Exception $a)
{
    throw new Exception("response.php : getSubscriptionDetailsByUserId : Error in getting User Details." . $a->getMessage(), 541);
}

try
{
    $arrPlanDetails = getUnUsedPlanByClientId($strSetClient_ID, $objDataHelper);
}
catch (Exception $a)
{
    throw new Exception("index.php : getPlanDetails : Error in populating Plan Details." . $a->getMessage(), 541);
}
//print_r($arrPlanDetails);
?>
<div>
    <img class="fR" id="close" border='0' title='Close' alt='Close' src='<?php echo CLIENT_IMG_PATH; ?>close_black.png'>
    <h3>Subscription Details</h3><br/>
    <div class="s13 mB10"><strong>Email Address&nbsp;:&nbsp;</strong><?php echo $db_useremailid; ?></div>
    <?php if ((is_array($arrUserSubList) && sizeof($arrUserSubList) > 0)) { ?>
        <table class="tblz01" width="100%" id="user-results">
            <thead>
                <tr class="thead">
                    <td width="3%">&nbsp;</td>
                    <td>Plan Name</td>
                    <td width="25%">Start Date</td>
                    <td width="25%">End Date</td>
                </tr>
            </thead>
            <tbody>
                <?php for ($intCntr = 0; $intCntr < sizeof($arrUserSubList); $intCntr++)
                { ?>
                    <tr>
                        <td><b>&DoubleRightArrow;</b></td>
                        <td><?php echo $arrUserSubList[$intCntr]['plan_name']; ?></td>
                        <td><?php echo $arrUserSubList[$intCntr]['subscription_start_date_local']; ?></td>
                        <td><?php echo $arrUserSubList[$intCntr]['subscription_end_date_local']; ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    <?php } else {?>
    <div class="alert alert-heading">
        <span>Sorry, You have not subscribed to any plan.</span>
    </div>
    <?php } ?>
</div>

<div class="pB15"></div>

<div> 
    <h3>Assign Subscription</h3><br/>
    <?php if ((is_array($arrPlanDetails) && sizeof($arrPlanDetails) > 0)) { ?>
    <div id="error-msg" class="alert alert-error" style="display: none;"></div>
    <div id="success-msg" class="alert alert-success" style="display: none;"></div>
    <div id="sub_form">
    <form name="addsubscptn" method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
        <div class="frm-fields tBold">Password<span class="required">&nbsp;*</span><span class="colon">:&nbsp;&nbsp;</span></div>
        <div><input type="password" name="txtPassword" maxlength="50" class="span3" id="txtPassword"></div>
        <div class="frm-fields tBold">Plan Name<span class="required">&nbsp;*</span><span class="colon">:&nbsp;&nbsp;</span></div>
        <div><select name='txtPlanName' class='span3' id='txtPlanName' onchange='IsMultiple(this.value)'>
                <?php
                if (!empty($arrPlanDetails))
                {
                    echo"<option value='---'>Select Plan</option>";
                    for ($intCount = 0; $intCount < sizeof($arrPlanDetails); $intCount++)
                    {
                        $strNo =$intCount+1;
                        if ($arrPlanDetails[$intCount]['plan_cost_inr'] != '0.00')
                        {
                            echo"<option value='" . $arrPlanDetails[$intCount]['plan_id'] . SEPARATOR . $arrPlanDetails[$intCount]['plan_name'] . SEPARATOR . $arrPlanDetails[$intCount]['order_id'] . SEPARATOR . $arrPlanDetails[$intCount]['client_subscription_id'] . SEPARATOR . $arrPlanDetails[$intCount]['is_multiple'] . "'>$strNo "." &nbsp;-&nbsp; " . $arrPlanDetails[$intCount]['plan_name'] . "&nbsp;-&nbsp;(Rs." . $arrPlanDetails[$intCount]['plan_cost_inr'] . ")</option>";
                        }
                        else if ($arrPlanDetails[$intCount]['plan_cost_oth'] != '0.00')
                        {
                            echo"<option value='" . $arrPlanDetails[$intCount]['plan_id'] . SEPARATOR . $arrPlanDetails[$intCount]['plan_name'] . SEPARATOR . $arrPlanDetails[$intCount]['order_id'] . SEPARATOR . $arrPlanDetails[$intCount]['client_subscription_id'] . SEPARATOR . $arrPlanDetails[$intCount]['is_multiple'] . "'>$strNo "."&nbsp;-&nbsp;" . $arrPlanDetails[$intCount]['plan_name'] . "&nbsp;-&nbsp;($&nbsp;" . $arrPlanDetails[$intCount]['plan_cost_oth'] . ")</option>";
                        }
                        else
                        {
                            echo"<option value='" . $arrPlanDetails[$intCount]['plan_id'] . SEPARATOR . $arrPlanDetails[$intCount]['plan_name'] . SEPARATOR . $arrPlanDetails[$intCount]['order_id'] . SEPARATOR . $arrPlanDetails[$intCount]['client_subscription_id'] . SEPARATOR . $arrPlanDetails[$intCount]['is_multiple'] . "'>$strNo "."&nbsp;-&nbsp;" . $arrPlanDetails[$intCount]['plan_name'] . "</option>";
                        }
                    }
                }
                else
                {
                    echo"<option value ='---'>Plan Name List not available</option>";
                }
                ?>
            </select></div>
            <input type="hidden" id="txtUserId" name="userid" value="<?php echo $db_userid; ?>">
            <input type="hidden" id="txtUserEmail" name="emailid" value="<?php echo $db_useremailid; ?>">
            <button class="btn btn-primary" id="btnSubscptn" name="btnSubscptn">Submit</button>
    </form>
    </div>
    <?php } else { ?>
    <div id="error-msg" class="alert alert-error">
        <span>Sorry, You have consumed all your Plans.<br/>For more plans please contact sales@letsmeet.com.</span>
    </div>
    <?php } ?>
</div>

<script type='text/javascript'>
    $(document).ready(function () {
        
        $('#close').click(function () {
            hidePopup('#popupS', '#layer');
        });
    
    
    $('#btnSubscptn').click(function () {
                    var uid = $("#txtUserId").val();
                    var uemail = $("#txtUserEmail").val();
                    var pwd = $("#txtPassword").val();
                    var plan = $("#txtPlanName").val();
                    var substr = plan.split('<?php echo SEPARATOR ?>');
                    var plan_id = substr[0];
                    var plan_name = substr[1];
                    var plan_order_id = substr[2];
                    var plan_sub_id = substr[3];
                    if ($.trim(pwd).length == 0) {
                        $('#success-msg').css({"display": "none"});
                        $('#error-msg').css({"display": "block"});
                        $('#error-msg').html("Please enter your Password");
                        return false;
                    }
                    else if (plan == '---') {
                        $('#success-msg').css({"display": "none"});
                        $('#error-msg').css({"display": "block"});
                        $('#error-msg').html("Please select Plan Name");
                        return false;
                    }
                    else {
                        $.post("addsubscription.php", {txtUserId: uid, txtPassword: pwd, txtPlanId: plan_id, txtPlanOrdId: plan_order_id, txtPlanSucId: plan_sub_id}, function (data)
                        {
                            var $response = $(data);
                            var oneval = $response.filter('#msg').text();
                            console.log(oneval);
                            if (oneval == 'yes') {
                                $('#txtPassword').val('');
                                $("#txtPlanName").val('---');
                                $('#success-msg').css({"display": "block"});
                                $('#error-msg').css({"display": "none"});
                                $('#success-msg').html('Plan <b><font color=#006699>"' + plan_name + '"</font></b> successfully assigned to <b><font color=#006699>"' + uemail + '"</font></b>.');
                                //location.reload();
                                $('#sub_form').css({"display": "none"});
                            }
                            else if (oneval == 'Invalid') {
                                $('#error-msg').css({"display": "block"});
                                $('#error-msg').html('Incorrect Password, Please re-enter');
                            }
                            else {
                                $('#error-msg').css({"display": "block"});
                                $('#error-msg').html('Some error occured, Please try again later');
                            }
                        });
                        return false;
                    }
                });
      });
</script>   