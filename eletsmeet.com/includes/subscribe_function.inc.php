<?php

/* -----------------------------------------------------------------------------
  Function Name : getPlanDetailsById
  Purpose       : To get Plan Details from plan_details table.
  Parameters    : plan id, gm_date, DataHelper
  Returns       : array( with Plan details)
  Calls         :
  Called By     : index.php(Subscribe)
  ------------------------------------------------------------------------------ */

function getPlanDetailsById($plan_id, $gm_date, $dataHelper)
{
    if (!is_object($dataHelper))
    {
        throw new Exception("subscribe_function.inc.php : getPlanDetailsById : DataHelper Object did not instantiate", 104);
    }

    if (strlen(trim($plan_id)) <= 0)
    {
        throw new Exception("subscribe_function.inc.php : getPlanDetailsById : Missing Parameter plan_id.", 143);
    }

    if (strlen(trim($gm_date)) <= 0)
    {
        throw new Exception("subscribe_function.inc.php : getPlanDetailsById : Missing Parameter gm_date.", 143);
    }
    try
    {
        $strSqlQuery = "SELECT plan_id, plan_name, plan_desc, plan_for, plan_type, number_of_sessions, number_of_mins_per_sessions, plan_period, DATE_FORMAT(DATE_ADD('" . trim($gm_date) . "', INTERVAL plan_period DAY),  '%a, %M %D %Y') AS end_grace_time, number_of_invitee, meeting_recording, disk_space, is_free, plan_cost_inr, plan_cost_oth, concurrent_sessions, talk_time_mins, plan_status, plan_creation_dtm, plan_keyword, autorenew_flag, display_order, is_multiple FROM plan_details WHERE plan_id = '" . trim($plan_id) . "' AND plan_status = '1'";
        $arrResult = $dataHelper->fetchRecords("QR", $strSqlQuery);
        return $arrResult;
    }
    catch (Exception $e)
    {
        throw new Exception("subscribe_function.inc.php : Error in getting Plan details." . $e->getMessage(), 734);
    }
}

/* -----------------------------------------------------------------------------
  Function Name : getBillingInfoByUserId
  Purpose       : To get Billing Details from billing_info table.
  Parameters    : user id, DataHelper
  Returns       : array( with Billing details)
  Calls         :
  Called By     : index.php(Subscribe)
  ------------------------------------------------------------------------------ */

function getBillingInfoByUserId($user_id, $dataHelper)
{
    if (!is_object($dataHelper))
    {
        throw new Exception("subscribe_function.inc.php : getBillingInfoByUserId : DataHelper Object did not instantiate", 104);
    }

    if (strlen(trim($user_id)) <= 0)
    {
        throw new Exception("subscribe_function.inc.php : getBillingInfoByUserId : Missing Parameter user_id.", 143);
    }

    try
    {
        $strSqlQuery = "SELECT * FROM billing_info WHERE user_id = '" . trim($user_id) . "' ORDER BY billing_dtm DESC LIMIT 0,1";
        $arrResult = $dataHelper->fetchRecords("QR", $strSqlQuery);
        return $arrResult;
    }
    catch (Exception $e)
    {
        throw new Exception("subscribe_function.inc.php : Error in getting Plan details." . $e->getMessage(), 734);
    }
}

/* -----------------------------------------------------------------------------
  Function Name : insOrderMaster
  Purpose       : To add order master into order_master table.
  Parameters    : user_id, email_address, order_id, payment_gateway, payment_from, local_date, gm_date, ip_address, DataHelper
  Returns       : Order id
  Calls         :
  Called By     : index.php(Subscribe)
  ------------------------------------------------------------------------------ */

function insOrderMaster($user_id, $email_address, $order_id, $payment_gateway, $payment_from, $local_date, $gm_date, $ip_address, $dataHelper)
{
    if (!is_object($dataHelper))
    {
        throw new Exception("subscribe_function.inc.php : insOrderMaster : DataHelper Object did not instantiate", 104);
    }

    if (strlen(trim($user_id)) <= 0)
    {
        throw new Exception("subscribe_function.inc.php : insOrderMaster : Missing Parameter user_id.", 143);
    }

    if (strlen(trim($email_address)) <= 0)
    {
        throw new Exception("subscribe_function.inc.php : insOrderMaster : Missing Parameter email_address.", 141);
    }

    if (strlen(trim($order_id)) <= 0)
    {
        throw new Exception("subscribe_function.inc.php : insOrderMaster : Missing Parameter order_id.", 143);
    }

    if (strlen(trim($payment_gateway)) <= 0)
    {
        throw new Exception("subscribe_function.inc.php : insOrderMaster : Missing Parameter payment_gateway.", 143);
    }

    if (strlen(trim($payment_from)) <= 0)
    {
        throw new Exception("subscribe_function.inc.php : insOrderMaster : Missing Parameter payment_from.", 143);
    }

    if (strlen(trim($local_date)) <= 0)
    {
        throw new Exception("subscribe_function.inc.php : insOrderMaster : Missing Parameter local_date.", 143);
    }
    
    if (strlen(trim($gm_date)) <= 0)
    {
        throw new Exception("subscribe_function.inc.php : insOrderMaster : Missing Parameter gm_date.", 143);
    }

    if (strlen(trim($ip_address)) <= 0)
    {
        throw new Exception("subscribe_function.inc.php : insOrderMaster : Missing Parameter ip_address.", 143);
    }

    try
    {
        $dataHelper->setParam("'" . $user_id . "'", "I");
        $dataHelper->setParam("'" . $email_address . "'", "I");
        $dataHelper->setParam("'" . $order_id . "'", "I");
        $dataHelper->setParam("'" . $payment_gateway . "'", "I");
        $dataHelper->setParam("'" . $payment_from . "'", "I");
        $dataHelper->setParam("'" . $local_date . "'", "I");
        $dataHelper->setParam("'" . $gm_date . "'", "I");
        $dataHelper->setParam("'" . $ip_address . "'", "I");
        $dataHelper->setParam("STATUS", "O");
        $dataHelper->setParam("OUTPUT", "O");
        $arrInsertOrderMaster = $dataHelper->putRecords("SP", 'InsertOrderMaster');
        $dataHelper->clearParams();
        return $arrInsertOrderMaster;
    }
    catch (Exception $e)
    {
        throw new Exception(" subscribe_function.inc.php : insOrderMaster : Failed : " . $e->getMessage(), 145);
    }
}

/* -----------------------------------------------------------------------------
  Function Name : insOrderDetails
  Purpose       : To get Order Details from order_details table.
  Parameters    : order_id, plan_id, currency_type, amount, service_tax_percent, service_tax_amount, total_amount, DataHelper
  Returns       : Order id
  Calls         :
  Called By     : index.php(Subscribe)
  ------------------------------------------------------------------------------ */

function insOrderDetails($order_id, $plan_id, $plan_name, $currency_type, $price, $quantity, $amount, $service_tax_percent, $service_tax_amount, $total_amount, $conversion_rate, $dataHelper)
{
    if (!is_object($dataHelper))
    {
        throw new Exception("subscribe_function.inc.php : insOrderDetails : DataHelper Object did not instantiate", 104);
    }

    if (strlen(trim($order_id)) <= 0)
    {
        throw new Exception("subscribe_function.inc.php : insOrderMaster : Missing Parameter order_id.", 143);
    }

    if (strlen(trim($plan_id)) <= 0)
    {
        throw new Exception("subscribe_function.inc.php : insOrderDetails : Missing Parameter plan_id.", 143);
    }

    if (strlen(trim($plan_name)) <= 0)
    {
        throw new Exception("subscribe_function.inc.php : insOrderDetails : Missing Parameter plan_name.", 143);
    }

    if (strlen(trim($currency_type)) <= 0)
    {
        throw new Exception("subscribe_function.inc.php : insOrderDetails : Missing Parameter currency_type.", 141);
    }

    if (strlen(trim($price)) <= 0)
    {
        throw new Exception("subscribe_function.inc.php : insOrderDetails : Missing Parameter price.", 141);
    }
    
    if (strlen(trim($quantity)) <= 0)
    {
        throw new Exception("subscribe_function.inc.php : insOrderDetails : Missing Parameter quantity.", 141);
    }
    
    if (strlen(trim($amount)) <= 0)
    {
        throw new Exception("subscribe_function.inc.php : insOrderDetails : Missing Parameter amount.", 143);
    }

    if (strlen(trim($service_tax_percent)) <= 0)
    {
        throw new Exception("subscribe_function.inc.php : insOrderDetails : Missing Parameter service_tax_percent.", 143);
    }

    if (strlen(trim($service_tax_amount)) <= 0)
    {
        throw new Exception("subscribe_function.inc.php : insOrderDetails : Missing Parameter service_tax_amount.", 143);
    }

    if (strlen(trim($total_amount)) <= 0)
    {
        throw new Exception("subscribe_function.inc.php : insOrderDetails : Missing Parameter total_amount.", 143);
    }

    try
    {
        $dataHelper->setParam("'" . $order_id . "'", "I");
        $dataHelper->setParam("'" . $plan_id . "'", "I");
        $dataHelper->setParam("'" . $plan_name . "'", "I");
        $dataHelper->setParam("'" . $currency_type . "'", "I");
        $dataHelper->setParam("'" . $price . "'", "I");
        $dataHelper->setParam("'" . $quantity . "'", "I");
        $dataHelper->setParam("'" . $amount . "'", "I");
        $dataHelper->setParam("'" . $service_tax_percent . "'", "I");
        $dataHelper->setParam("'" . $service_tax_amount . "'", "I");
        $dataHelper->setParam("'" . $total_amount . "'", "I");
        $dataHelper->setParam("'" . $conversion_rate . "'", "I");
        $dataHelper->setParam("STATUS", "O");
        $dataHelper->setParam("OUTPUT", "O");
        $arrInsertOrderDetails = $dataHelper->putRecords("SP", 'InsertOrderDetails');
        $dataHelper->clearParams();
        return $arrInsertOrderDetails;
    }
    catch (Exception $e)
    {
        throw new Exception(" subscribe_function.inc.php : insOrderDetails : Failed : " . $e->getMessage(), 145);
    }
}

/* -----------------------------------------------------------------------------
  Function Name : insTransactionLog
  Purpose       : To insert Transaction log details into transaction_log table.
  Parameters    : response_code, response_msg, date_created, payment_id, merchant_ref_no, amount, mode, billing_email, description, is_flagged, transaction_id, DataHelper
  Returns       :
  Calls         :
  Called By     : response.php(Subscribe)
  ------------------------------------------------------------------------------ */

function insTransactionLog($response_code, $response_msg, $date_created, $payment_id, $merchant_ref_no, $amount, $mode, $billing_email, $description, $is_flagged, $transaction_id, $response_details, $dataHelper)
{
    if (!is_object($dataHelper))
    {
        throw new Exception("subscribe_function.inc.php : insTransactionLog : DataHelper Object did not instantiate", 104);
    }

    try
    {
        $strSqlQuery = "INSERT INTO transaction_log(response_code, response_message, date_created, payment_id, merchant_ref_no, amount, mode, billing_email, description, is_flagged, transaction_id,response_log) 
	                VALUES ('" . $response_code . "', '" . $response_msg . "', '" . $date_created . "', '" . $payment_id . "', '" . $merchant_ref_no . "', '" . $amount . "', '" . $mode . "', '" . $billing_email . "', '" . $description . "', '" . $is_flagged . "', '" . $transaction_id . "', '" . $response_details . "')";
        $arrResult = $dataHelper->putRecords("QR", $strSqlQuery);
        return $arrResult;
    }
    catch (Exception $e)
    {
        throw new Exception("subscribe_function.inc.php : Error in adding Billing Info." . $e->getMessage(), 734);
    }
}

/* -----------------------------------------------------------------------------
  Function Name : getOrderDetailsById
  Purpose       : To get Order details from order_master and order_details table.
  Parameters    : order_id, DataHelper
  Returns       : array( with Order details)
  Calls         :
  Called By     : response.php(Subscribe)
  ------------------------------------------------------------------------------ */

function getOrderDetailsById($order_id, $dataHelper)
{
    if (!is_object($dataHelper))
    {
        throw new Exception("subscribe_function.inc.php : getOrderDetailsById : DataHelper Object did not instantiate", 104);
    }

    if (strlen(trim($order_id)) <= 0)
    {
        throw new Exception("subscribe_function.inc.php : getOrderDetailsById : Missing Parameter order_id.", 143);
    }

    try
    {
        $strSqlQuery = "SELECT
	om.order_id,  	
	om.user_id, 
	om.email_address, 
	om.payment_id, 
	om.transaction_id, 
	om.payment_gateway_name, 
	om.payment_from, 
	om.order_status, 
	om.order_date, 
	om.ip_address,
	od.plan_id,
        od.plan_name,
	od.currency_type,
        od.price,
        od.quantity,
	od.amount, 
	od.service_tax_percent, 
	od.service_tax_amount, 
	od.total_amount, 
	od.conversion_rate
	FROM 
	order_master om,  order_details od	
	WHERE om.order_id = od.order_id
	AND om.order_id = '" . trim($order_id) . "'";
        $arrResult = $dataHelper->fetchRecords("QR", $strSqlQuery);
        return $arrResult;
    }
    catch (Exception $e)
    {
        throw new Exception("subscribe_function.inc.php : Error in getting Order details." . $e->getMessage(), 734);
    }
}

/* -----------------------------------------------------------------------------
  Function Name : updateOrderMaster
  Purpose       : To upadte order status in order master table.
  Parameters    : order_id, payment_id, transaction_id, order_status, DataHelper
  Returns       :
  Calls         :
  Called By     : response.php(Subscribe)
  ------------------------------------------------------------------------------ */

function updateOrderMaster($order_id, $payment_id, $transaction_id, $order_status, $dataHelper)
{
    if (!is_object($dataHelper))
    {
        throw new Exception("subscribe_function.inc.php : updateOrderMaster : DataHelper Object did not instantiate", 104);
    }

    if (strlen(trim($order_id)) <= 0)
    {
        throw new Exception("subscribe_function.inc.php : updateOrderMaster : Missing Parameter order_id.", 143);
    }

    if (strlen(trim($payment_id)) <= 0)
    {
        throw new Exception("subscribe_function.inc.php : updateOrderMaster : Missing Parameter payment_id.", 143);
    }

    if (strlen(trim($transaction_id)) <= 0)
    {
        throw new Exception("subscribe_function.inc.php : updateOrderMaster : Missing Parameter transaction_id.", 143);
    }

    if (strlen(trim($order_status)) <= 0)
    {
        throw new Exception("subscribe_function.inc.php : updateOrderMaster : Missing Parameter order_status.", 143);
    }

    try
    {
        $strSqlQuery = "UPDATE order_master SET payment_id = '" . trim($payment_id) . "', transaction_id =  '" . trim($transaction_id) . "', order_status = '" . trim($order_status) . "' WHERE order_id = '" . trim($order_id) . "'";
        $arrResult = $dataHelper->putRecords("QR", $strSqlQuery);
        return $arrResult;
    }
    catch (Exception $e)
    {
        throw new Exception("subscribe_function.inc.php : Error in updating order master." . $e->getMessage(), 734);
    }
}

/* -----------------------------------------------------------------------------
  Function Name : insSubscriptionMaster
  Purpose       : To insert Subscription details into subscription_master table.
  Parameters    : user_id, curr_datetime, curr_date, end_date, curr_date, end_date, subscription_status, order_id, plan_id, plan_name, plan_desc, plan_for, plan_type, number_of_sessions, number_of_mins_per_sessions, plan_period, number_of_invitee, meeting_recording, disk_space, is_free, plan_cost_inr, plan_cost_oth, concurrent_sessions, talk_time_mins, autorenew_flag, consumed_number_of_sessions, consumed_talk_time_mins, DataHelper
  Returns       : Subscription id
  Calls         :
  Called By     : response.php(Subscribe)
  ------------------------------------------------------------------------------ */

function insSubscriptionMaster($user_id, $gmt_datetime, $gmt_start_date, $gmt_end_date, $local_start_date, $local_end_date, $subscription_status, $order_id, $plan_id, $plan_name, $plan_desc, $plan_for, $plan_type, $number_of_sessions, $number_of_mins_per_sessions, $plan_period, $number_of_invitee, $meeting_recording, $disk_space, $is_free, $plan_cost_inr, $plan_cost_oth, $concurrent_sessions, $talk_time_mins, $autorenew_flag, $consumed_number_of_sessions, $consumed_talk_time_mins, $dataHelper)
{
    if (!is_object($dataHelper))
    {
        throw new Exception("subscribe_function.inc.php : insSubscriptionMaster : DataHelper Object did not instantiate", 104);
    }

    if (strlen(trim($user_id)) <= 0)
    {
        throw new Exception("subscribe_function.inc.php : insSubscriptionMaster : Missing Parameter user_id.", 143);
    }

    if (strlen(trim($gmt_datetime)) <= 0)
    {
        throw new Exception("subscribe_function.inc.php : insSubscriptionMaster : Missing Parameter gmt_datetime.", 143);
    }

    if (strlen(trim($gmt_start_date)) <= 0)
    {
        throw new Exception("subscribe_function.inc.php : insSubscriptionMaster : Missing Parameter gmt_start_date.", 141);
    }

    if (strlen(trim($gmt_end_date)) <= 0)
    {
        throw new Exception("subscribe_function.inc.php : insSubscriptionMaster : Missing Parameter gmt_end_date.", 143);
    }

    if (strlen(trim($local_start_date)) <= 0)
    {
        throw new Exception("subscribe_function.inc.php : insSubscriptionMaster : Missing Parameter local_start_date.", 143);
    }

    if (strlen(trim($local_end_date)) <= 0)
    {
        throw new Exception("subscribe_function.inc.php : insSubscriptionMaster : Missing Parameter local_end_date.", 143);
    }

    if (strlen(trim($subscription_status)) <= 0)
    {
        throw new Exception("subscribe_function.inc.php : insSubscriptionMaster : Missing Parameter subscription_status.", 143);
    }

    if (strlen(trim($order_id)) <= 0)
    {
        throw new Exception("subscribe_function.inc.php : insSubscriptionMaster : Missing Parameter order_id.", 143);
    }

    if (strlen(trim($plan_id)) <= 0)
    {
        throw new Exception("subscribe_function.inc.php : insSubscriptionMaster : Missing Parameter plan_id.", 143);
    }

    if (strlen(trim($plan_name)) <= 0)
    {
        throw new Exception("subscribe_function.inc.php : insSubscriptionMaster : Missing Parameter plan_name.", 143);
    }

    if (strlen(trim($plan_desc)) <= 0)
    {
        throw new Exception("subscribe_function.inc.php : insSubscriptionMaster : Missing Parameter plan_desc.", 143);
    }

    if (strlen(trim($plan_for)) <= 0)
    {
        throw new Exception("subscribe_function.inc.php : insSubscriptionMaster : Missing Parameter plan_for.", 143);
    }

    if (strlen(trim($plan_type)) <= 0)
    {
        throw new Exception("subscribe_function.inc.php : insSubscriptionMaster : Missing Parameter plan_type.", 143);
    }

    if (strlen(trim($number_of_sessions)) <= 0)
    {
        throw new Exception("subscribe_function.inc.php : insSubscriptionMaster : Missing Parameter number_of_sessions.", 143);
    }

    if (strlen(trim($number_of_mins_per_sessions)) <= 0)
    {
        throw new Exception("subscribe_function.inc.php : insSubscriptionMaster : Missing Parameter number_of_mins_per_sessions.", 143);
    }

    if (strlen(trim($plan_period)) <= 0)
    {
        throw new Exception("subscribe_function.inc.php : insSubscriptionMaster : Missing Parameter plan_period.", 143);
    }

    if (strlen(trim($number_of_invitee)) <= 0)
    {
        throw new Exception("subscribe_function.inc.php : insSubscriptionMaster : Missing Parameter number_of_invitee.", 143);
    }

    if (strlen(trim($meeting_recording)) <= 0)
    {
        throw new Exception("subscribe_function.inc.php : insSubscriptionMaster : Missing Parameter meeting_recording.", 143);
    }

    if (strlen(trim($disk_space)) <= 0)
    {
        throw new Exception("subscribe_function.inc.php : insSubscriptionMaster : Missing Parameter disk_space.", 143);
    }

    if (strlen(trim($is_free)) <= 0)
    {
        throw new Exception("subscribe_function.inc.php : insSubscriptionMaster : Missing Parameter is_free.", 143);
    }

    if (strlen(trim($plan_cost_inr)) <= 0)
    {
        throw new Exception("subscribe_function.inc.php : insSubscriptionMaster : Missing Parameter plan_cost_inr.", 143);
    }

    if (strlen(trim($plan_cost_oth)) <= 0)
    {
        throw new Exception("subscribe_function.inc.php : insSubscriptionMaster : Missing Parameter plan_cost_oth.", 143);
    }

    if (strlen(trim($concurrent_sessions)) <= 0)
    {
        throw new Exception("subscribe_function.inc.php : insSubscriptionMaster : Missing Parameter concurrent_sessions.", 143);
    }

    if (strlen(trim($autorenew_flag)) <= 0)
    {
        throw new Exception("subscribe_function.inc.php : insSubscriptionMaster : Missing Parameter autorenew_flag.", 143);
    }

    if (strlen(trim($consumed_number_of_sessions)) <= 0)
    {
        throw new Exception("subscribe_function.inc.php : insSubscriptionMaster : Missing Parameter consumed_number_of_sessions.", 143);
    }

    if (strlen(trim($consumed_talk_time_mins)) <= 0)
    {
        throw new Exception("subscribe_function.inc.php : insSubscriptionMaster : Missing Parameter consumed_number_of_sessions.", 143);
    }

    try
    {
        $dataHelper->setParam("'" . $user_id . "'", "I");
        $dataHelper->setParam("'" . $gmt_datetime . "'", "I");
        $dataHelper->setParam("'" . $gmt_start_date . "'", "I");
        $dataHelper->setParam("'" . $gmt_end_date . "'", "I");
        $dataHelper->setParam("'" . $local_start_date . "'", "I");
        $dataHelper->setParam("'" . $local_end_date . "'", "I");
        $dataHelper->setParam("'" . $subscription_status . "'", "I");
        $dataHelper->setParam("'" . $order_id . "'", "I");
        $dataHelper->setParam("'" . $plan_id . "'", "I");
        $dataHelper->setParam("'" . $plan_name . "'", "I");
        $dataHelper->setParam("'" . $plan_desc . "'", "I");
        $dataHelper->setParam("'" . $plan_for . "'", "I");
        $dataHelper->setParam("'" . $plan_type . "'", "I");
        $dataHelper->setParam("'" . $number_of_sessions . "'", "I");
        $dataHelper->setParam("'" . $number_of_mins_per_sessions . "'", "I");
        $dataHelper->setParam("'" . $plan_period . "'", "I");
        $dataHelper->setParam("'" . $number_of_invitee . "'", "I");
        $dataHelper->setParam("'" . $meeting_recording . "'", "I");
        $dataHelper->setParam("'" . $disk_space . "'", "I");
        $dataHelper->setParam("'" . $is_free . "'", "I");
        $dataHelper->setParam("'" . $plan_cost_inr . "'", "I");
        $dataHelper->setParam("'" . $plan_cost_oth . "'", "I");
        $dataHelper->setParam("'" . $concurrent_sessions . "'", "I");
        $dataHelper->setParam("'" . $talk_time_mins . "'", "I");
        $dataHelper->setParam("'" . $autorenew_flag . "'", "I");
        $dataHelper->setParam("'" . $consumed_number_of_sessions . "'", "I");
        $dataHelper->setParam("'" . $consumed_talk_time_mins . "'", "I");


        $dataHelper->setParam("STATUS", "O");
        $dataHelper->setParam("OUTPUT", "O");
        $arrInsertSubscriptionDetails = $dataHelper->putRecords("SP", 'InsertSubscriptionMaster');
        $dataHelper->clearParams();
        return $arrInsertSubscriptionDetails;
    }
    catch (Exception $e)
    {
        throw new Exception(" subscribe_function.inc.php : insSubscriptionMaster : Failed : " . $e->getMessage(), 145);
    }
}

/* -----------------------------------------------------------------------------
  Function Name : insBillingInfo
  Purpose       : To insert Billing information into billing_info table.
  Parameters    : user_id, order_id, email_address, first_name, last_name, address, city, state, country_name, zip_code, idd_code, mobile, gm_date, status, DataHelper
  Returns       :
  Calls         :
  Called By     : response.php(Subscribe)
  ------------------------------------------------------------------------------ */

function insBillingInfo($user_id, $order_id, $email_address, $first_name, $last_name, $address, $city, $state, $country_name, $zip_code, $idd_code, $mobile, $gm_date, $status, $dataHelper)
{
    if (!is_object($dataHelper))
    {
        throw new Exception("subscribe_function.inc.php : insBillingInfo : DataHelper Object did not instantiate", 104);
    }

    try
    {
        $strSqlQuery = "INSERT INTO billing_info(user_id, order_id, email_address, first_name, last_name, address, city, state, country_name, zipcode, idd_code, mobile_number, billing_dtm, status) 
	                VALUES ('" . $user_id . "', '" . $order_id . "', '" . $email_address . "', '" . $first_name . "', '" . $last_name . "', '" . $address . "', '" . $city . "', '" . $state . "', '" . $country_name . "', '" . $zip_code . "', '" . $idd_code . "', '" . $mobile . "', '" . $gm_date . "', '" . $status . "')";
        $arrResult = $dataHelper->putRecords("QR", $strSqlQuery);
        return $arrResult;
    }
    catch (Exception $e)
    {
        throw new Exception("subscribe_function.inc.php : Error in adding Billing Info." . $e->getMessage(), 734);
    }
}

/* -----------------------------------------------------------------------------
  Function Name : updateOrderStatus
  Purpose       : To upadte order status in order master table.
  Parameters    : order_id, new_status, old_status, DataHelper
  Returns       :
  Calls         :
  Called By     : response.php(Subscribe)
  ------------------------------------------------------------------------------ */

function updateOrderStatus($order_id, $new_status, $old_status, $dataHelper)
{
    if (!is_object($dataHelper))
    {
        throw new Exception("subscribe_function.inc.php : updateOrderStatus : DataHelper Object did not instantiate", 104);
    }

    if (strlen(trim($order_id)) <= 0)
    {
        throw new Exception("subscribe_function.inc.php : updateOrderStatus : Missing Parameter order_id.", 143);
    }

    if (strlen(trim($new_status)) <= 0)
    {
        throw new Exception("subscribe_function.inc.php : updateOrderStatus : Missing Parameter new_status.", 143);
    }

    if (strlen(trim($old_status)) <= 0)
    {
        throw new Exception("subscribe_function.inc.php : updateOrderStatus : Missing Parameter old_status.", 143);
    }

    try
    {
        $strSqlQuery = "UPDATE order_master SET order_status = '" . trim($new_status) . "' WHERE order_id = '" . trim($order_id) . "' AND order_status = '" . trim($old_status) . "'";
        $arrResult = $dataHelper->putRecords("QR", $strSqlQuery);
        return $arrResult;
    }
    catch (Exception $e)
    {
        throw new Exception("subscribe_function.inc.php : Error in updating order status." . $e->getMessage(), 734);
    }
}

/* -----------------------------------------------------------------------------
  Function Name : getScheduleDetailsByOrderId
  Purpose       : To get Schedule Details from schedule_details table.
  Parameters    : order_id, DataHelper
  Returns       : array( with Schedule details)
  Calls         :
  Called By     : scheduleDetails.php(Subscribe)
  ------------------------------------------------------------------------------ */

function getScheduleDetailsByOrderId($order_id, $dataHelper)
{
    if (!is_object($dataHelper))
    {
        throw new Exception("subscribe_function.inc.php : getScheduleDetailsByOrderId : DataHelper Object did not instantiate", 104);
    }

    if (strlen(trim($order_id)) <= 0)
    {
        throw new Exception("subscribe_function.inc.php : getScheduleDetailsByOrderId : Missing Parameter order_id.", 143);
    }

    try
    {
        $strSqlQuery = "SELECT
        sm.order_id,
        sm.subscription_id,
        sd.schedule_id,
        sd.user_id,
        sd.schedule_status,
        sd.meeting_timestamp_gmt,
        sd.meeting_timestamp_local,
        sd.meeting_title,
        sd.max_participants
        FROM schedule_details AS sd, subscription_master AS sm , order_master AS om WHERE sm.order_id = om.order_id AND sm.subscription_id = sd.subscription_id AND sm.order_id = '" . trim($order_id) . "'";
        $arrResult = $dataHelper->fetchRecords("QR", $strSqlQuery);
        return $arrResult;
    }
    catch (Exception $e)
    {
        throw new Exception("subscribe_function.inc.php : Error in getting Schedule details." . $e->getMessage(), 734);
    }
}

/* -----------------------------------------------------------------------------
  Function Name : getFreePlanDetails
  Purpose       : To get Free Plan Details from plan_details table.
  Parameters    : gm_date, DataHelper
  Returns       : array( with Plan details)
  Calls         :
  Called By     : account_activation(Signup)
  ------------------------------------------------------------------------------ */

function getFreePlanDetails($gm_date, $dataHelper)
{
    if (!is_object($dataHelper))
    {
        throw new Exception("subscribe_function.inc.php : getFreePlanDetails : DataHelper Object did not instantiate", 104);
    }

    if (strlen(trim($gm_date)) <= 0)
    {
        throw new Exception("subscribe_function.inc.php : getFreePlanDetails : Missing Parameter gm_date.", 143);
    }
    try
    {
        $strSqlQuery = "SELECT plan_id, plan_name, plan_desc, plan_for, plan_type, number_of_sessions, number_of_mins_per_sessions, plan_period, DATE_FORMAT(DATE_ADD('" . trim($gm_date) . "', INTERVAL plan_period DAY),  '%a, %M %D %Y') AS end_grace_time, number_of_invitee, meeting_recording, disk_space, is_free, plan_cost_inr, plan_cost_oth, concurrent_sessions, talk_time_mins, plan_status, plan_creation_dtm, plan_keyword, autorenew_flag, display_order, is_multiple FROM plan_details WHERE is_free = '1' AND plan_status = '1'";
        $arrResult = $dataHelper->fetchRecords("QR", $strSqlQuery);
        return $arrResult;
    }
    catch (Exception $e)
    {
        throw new Exception("subscribe_function.inc.php : Error in getting Free Plan details." . $e->getMessage(), 734);
    }
}
?>
