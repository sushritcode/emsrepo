<?php

require_once('../../includes/global.inc.php');
require_once('../includes/config.inc.php');
require_once(CLIENT_CLASSES_PATH . 'client_error.inc.php');
require_once(DBS_PATH . 'DataHelper.php');
require_once(DBS_PATH . 'objDataHelper.php');
require_once(CLIENT_INCLUDES_PATH . 'client_authfunc.inc.php');
$CLIENT_CONST_MODULE = 'cluser';
$CLIENT_CONST_PAGEID   = 'Add Subscription';
require_once(CLIENT_INCLUDES_PATH . 'client_authorize.inc.php');
require_once(CLIENT_INCLUDES_PATH . 'client_db_function.inc.php');

$strUserId = $_POST['txtUserId'];
$strPassword = MD5(trim($_POST['txtPassword']));
$strPlanId = $_POST['txtPlanId'];
$strPlanOrderId = $_POST['txtPlanOrdId'];
$strPlanSubscriptionId = $_POST['txtPlanSucId'];
//$strQuantity = $_POST['txtMonth'];

//if (isset($strUserId) && isset($strPassword) && isset($strPlanId) && isset($strQuantity))
if (isset($strUserId) && isset($strPassword) && isset($strPlanId))
{
    try
    {
        $arrAuthUserResult = isAuthenticClient($strCk_email_address, $strPassword, $objDataHelper);
    }
    catch (Exception $a)
    {
        throw new Exception("addsubscription.php : isAuthenticUser_API : Error in validating password" . $a->getMessage(), 613);
    }

    if (is_array($arrAuthUserResult) && sizeof($arrAuthUserResult) > 0)
    {
        try
        {
            $arrUserDetails = getUserDetailsByUserId($strUserId, $objDataHelper);
        }
        catch (Exception $a)
        {
            throw new Exception("response.php : getUserDetailsByUserId : Error in getting User Details." . $a->getMessage(), 541);
        }
        
        //print_r($arrUserDetails);
        
        $db_userId = $arrUserDetails[0]['user_id'];
        $db_usertimezone = $arrUserDetails[0]['timezones'];
        $gmt_datetime = GM_DATE;
        $Type = "N";
        $dateTime = timezoneConverter($Type, $gmt_datetime, $db_usertimezone);
        $dtm = explode(SEPARATOR, $dateTime);
        $local_datetime = $dtm[1];
        
        //$payment_gateway_name = 'CLADMIN';
        //$payment_from = 'WEB';
        //$ip_address = $_SERVER['REMOTE_ADDR'];
                 
         try
        {
            $arrSubPlanDetails = getSubDtlsByClientIdnPlanId($strSetClient_ID, $strPlanId,  $strPlanSubscriptionId, $strPlanOrderId, $objDataHelper);
        }
        catch (Exception $e)
        {
            throw new Exception("addsubscription.php : getPlanDetailsById Failed : " . $e->getMessage(), 1125);
        }
        
         //print_r($arrSubPlanDetails); exit;
                     
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
                
            try
            {
                $arrSubscDetails = insUserSubscriptionDetails($db_userId, $gmt_datetime, $db_subscriptionstartdategmt, $db_subscriptionenddategmt, $db_subscriptionstartdatelocal, $db_subscriptionend_date_local, $db_subscriptionstatus, $db_orderid, $db_planid, $db_planname, $db_plandesc, $db_planfor, $db_plantype, $db_numberofsessions, $db_numberofminspersessions, $db_planperiod, $db_numberofinvitee, $db_meetingrecording, $db_diskspace, $db_isfree, $db_plancostinr, $db_plancostoth, $db_concurrentsessions, $db_talktimemins, $db_autorenewflag, $db_consumednumberofsessions, $db_consumedtalktimemins,  $objDataHelper);
            }
            catch (Exception $a)
            {
                throw new Exception("response.php : insSubscriptionMaster : Error in adding subscription details." . $a->getMessage(), 541);
            }
            $subscStatus = $arrSubscDetails[0]['@STATUS'];
            $subsc_id = $arrSubscDetails[0]['@OUTPUT'];
            if ($subscStatus == 1)
            {
                echo "<div id='msg'>yes</div>";
            }
            else
            {
                echo "<div id='msg'>No</div>";
            }    
    }
    else
    {
        echo "<div id='msg'>Invalid</div>";
    }
}
else
{
    header("Location: " . $CLIENT_SITE_ROOT);
    exit;
}
?>
