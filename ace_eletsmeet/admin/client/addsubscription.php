<?php

require_once('../../includes/global.inc.php');
require_once('../includes/config.inc.php');
require_once(ADM_CLASSES_PATH . 'admin_error.inc.php');
require_once(DBS_PATH . 'DataHelper.php');
require_once(DBS_PATH . 'objDataHelper.php');
$ADM_CONST_MODULE = 'user';
$ADM_CONST_PAGEID = 'Add Subscription';
require_once(ADM_INCLUDES_PATH . 'adm_authfunc.inc.php');
require_once(ADM_INCLUDES_PATH . 'adm_authorize.inc.php');
require_once(ADM_INCLUDES_PATH . 'adm_db_common_function.inc.php');
require_once(ADM_INCLUDES_PATH . 'subscription_function.inc.php');

$strClientId = $_REQUEST['txtClientId'];
$strPassword = MD5(trim($_REQUEST['txtPassword']));
$strPlanId = $_REQUEST['txtPlanId'];
$strMonth = $_REQUEST['txtMonth'];
$strLicense= $_REQUEST['txtLicense'];

if (isset($strClientId) && isset($strPassword) && isset($strPlanId) && isset($strMonth))
{
    try
    {
        $arrAuthUserResult = isAuthenticAdminUser($strCk_email_address, $strPassword, $objDataHelper);
    }
    catch (Exception $a)
    {
        throw new Exception("addsubscription.php : isAuthenticUser_API : Error in validating password" . $a->getMessage(), 613);
    }

    if (is_array($arrAuthUserResult) && sizeof($arrAuthUserResult) > 0)
    {

        try
        {
            $arrClientDtls = getClientDtlsById( $strClientId , $objDataHelper);
        }
        catch (Exception $a)
        {
            throw new Exception("response.php : getUserDetailsByUserId : Error in getting User Details." . $a->getMessage(), 541);
        }               

        $DBClientId = $arrClientDtls[0]['client_id'];
        $email_address = $arrClientDtls[0]['client_email_address'];
        //$UserTimeZone = $arrClientDtls[0]['timezones'];
        //$UserGMT = $arrClientDtls[0]['gmt'];

        $UserTimeZone = 'Asia/Kolkata';
        
        $client_id = explode("cl", $DBClientId);
        $client_id = ltrim($client_id[1], "0");
        $order_id = "ord" . time() . $client_id . strlen($client_id);
        $payment_gateway_name = 'ADMIN';
        $payment_from = 'Web';
        $ip_address = $_SERVER['REMOTE_ADDR'];

        $gmt_datetime = GM_DATE;
        $Type = "N";
        $dateTime = timezoneConverter($Type, $gmt_datetime, $UserTimeZone);
        $dtm = explode(SEPARATOR, $dateTime);
        $local_datetime = $dtm[1];

        if (isset($strPlanId))
        {
            try
            {
                $arrPlanDetails = getPlanDetailsById($strPlanId, GM_DATE, $objDataHelper);
                if ($arrPlanDetails[0]['plan_cost_inr'] != '0.00')
                {
                    $plan_cost = $arrPlanDetails[0]['plan_cost_inr'];
                    //$service_tax_percent = '12.36 %';
                    $service_tax_percent = '14.00 %';
                    $currency = 'INR';
                }
                else
                {
                    $plan_cost = $arrPlanDetails[0]['plan_cost_oth'];
                    $service_tax_percent = '0 %';
                    $currency = '$';
                }
            }
            catch (Exception $e)
            {
                throw new Exception("addsubscription.php : getPlanDetailsById Failed : " . $e->getMessage(), 1125);
            }
        }

        //Insert in OrderMaster
        try
        {
            $addOrderMaster = insOrderMaster($DBClientId, $email_address, $order_id, $payment_gateway_name, $payment_from, $local_datetime, $gmt_datetime, $ip_address, $objDataHelper);
        }
        catch (Exception $a)
        {
            throw new Exception("addsubscription.php : insOrderMaster : Error in adding order master." . $a->getMessage(), 613);
        }
        $strORM_Status = $addOrderMaster[0]['@STATUS'];
        $strORM_Order_Id = $addOrderMaster[0]['@OUTPUT'];

        if ($strORM_Status == 1)
        {
            //Insert in OrderDetails
            try
            {
                $plan_id = $arrPlanDetails[0]['plan_id'];
                $plan_name = $arrPlanDetails[0]['plan_name'];

                $total_amount = ($plan_cost * $strMonth);
                $service_tax_amount = ($total_amount * $service_tax_percent) / 100;
                $amount = $total_amount - $service_tax_amount;
                $conversion_rate = '0.00';
                $addOrderDetails = insOrderDetails($strORM_Order_Id, $plan_id, $plan_name, $currency, $plan_cost, $strMonth, $amount, $service_tax_percent, $service_tax_amount, $total_amount, $conversion_rate, $objDataHelper);
            }
            catch (Exception $a)
            {
                throw new Exception("addsubscription.php : insOrderDetails : Error in adding order details" . $a->getMessage(), 613);
            }
            $strORD_Status = $addOrderDetails[0]['@STATUS'];
            $strORD_Order_ID = $addOrderDetails[0]['@OUTPUT'];
            
            if ($strORD_Status == 1)
            {
                //Insert in SubscriptionMaster               
                $txtPlanId = $arrPlanDetails[0]['plan_id'];
                $txtPlanName = $arrPlanDetails[0]['plan_name'];
                $txtPlanDesc = $arrPlanDetails[0]['plan_desc'];
                $txtPlanFor = $arrPlanDetails[0]['plan_for'];
                $txtPlanType = $arrPlanDetails[0]['plan_type'];
                $txtNumberOfSessions = $arrPlanDetails[0]['number_of_sessions'];
                $txtNumberOfMinsPerSessions = $arrPlanDetails[0]['number_of_mins_per_sessions'];
                //$txtPlanPeriod = $arrPlanDetails[0]['plan_period'];
                $txtPlanPeriod = $arrPlanDetails[0]['plan_period'] * $strMonth; 
                $txtNumberOfInvitee = $arrPlanDetails[0]['number_of_invitee'];
                $txtMeetingRecording = $arrPlanDetails[0]['meeting_recording'];
                $txtDiskSpace = $arrPlanDetails[0]['disk_space'];
                $txtIsFree = $arrPlanDetails[0]['is_free'];
                $txtPlanCostInr = $arrPlanDetails[0]['plan_cost_inr'];
                $txtPlanCostOth = $arrPlanDetails[0]['plan_cost_oth'];
                $txtConcurrentSessions = $arrPlanDetails[0]['concurrent_sessions'];
                $txtTalkTimeMins = $arrPlanDetails[0]['talk_time_mins'];
                $txtAutorenewFlag = $arrPlanDetails[0]['autorenew_flag'];

                //$user_nick_name = $arrUserDetails[0]['nick_name'];
                $time_zone = $arrUserDetails[0]['timezones'];
                $gmt_datetime = GM_DATE;
                $gmt_start_date = date("Y-m-d", strtotime(GM_DATE));
                $gmt_end_date = date('Y-m-d', strtotime($gmt_start_date . ' + ' . $txtPlanPeriod . ' days'));

                $Type = "N";
                $dateTime = timezoneConverter($Type, $gmt_datetime, $time_zone);
                $dtm = explode(SEPARATOR, $dateTime);

                $local_start_date = date("Y-m-d", strtotime($dtm[1]));
                $local_end_date = date('Y-m-d', strtotime($local_start_date . ' + ' . $txtPlanPeriod . ' days'));

                $subscription_status = '2';
                $consumed_number_of_sessions = '0';
                $consumed_talk_time_mins = '0';

                try
                {
                    $arrSubscDetails = insClientSubscriptionMaster($DBClientId, $gmt_datetime, $gmt_start_date, $gmt_end_date, $local_start_date, $local_end_date, $subscription_status, $strORD_Order_ID, $txtPlanId, $txtPlanName, $txtPlanDesc, $txtPlanFor, $txtPlanType, $txtNumberOfSessions, $txtNumberOfMinsPerSessions, $txtPlanPeriod, $txtNumberOfInvitee, $txtMeetingRecording, $txtDiskSpace, $txtIsFree, $txtPlanCostInr, $txtPlanCostOth, $txtConcurrentSessions, $txtTalkTimeMins, $txtAutorenewFlag, $consumed_number_of_sessions, $consumed_talk_time_mins, $objDataHelper);
                }
                catch (Exception $a)
                {
                    throw new Exception("response.php : insSubscriptionMaster : Error in adding subscription details." . $a->getMessage(), 541);
                }
                              
                
                $subscStatus = $arrSubscDetails[0]['@STATUS'];
                $subsc_id = $arrSubscDetails[0]['@OUTPUT'];
                
                if ($subscStatus == 1)
                {
                    try
                    {
                        $PaymentID = 'AP' . time() . $user_id . strlen($user_id);
                        $TransactionID = 'AT' . time() . $user_id . strlen($user_id);
                        $orderStatus = 'completed';
                        $arrUpdOrderMaster = updateOrderMaster($strORD_Order_ID, $PaymentID, $TransactionID, $orderStatus, $objDataHelper);
                    }
                    catch (Exception $a)
                    {
                        throw new Exception("response.php : updateOrderMaster : Error in updating order master." . $a->getMessage(), 541);
                    }
                    echo "<div id='msg'>yes</div>";
                }
                else
                {
                    echo "<div id='msg'>No</div>";
                }
            }
        }
    }
    else
    {
        echo "<div id='msg'>Invalid</div>";
    }
}
?>
