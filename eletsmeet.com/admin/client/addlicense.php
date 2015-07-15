<?php

require_once('../../includes/global.inc.php');
require_once('../includes/config.inc.php');
require_once(ADM_CLASSES_PATH . 'admin_error.inc.php');
require_once(DBS_PATH . 'DataHelper.php');
require_once(DBS_PATH . 'objDataHelper.php');
$ADM_CONST_MODULE = 'user';
$ADM_CONST_PAGEID = 'Add License';
require_once(ADM_INCLUDES_PATH . 'adm_authfunc.inc.php');
require_once(ADM_INCLUDES_PATH . 'adm_authorize.inc.php');
require_once(ADM_INCLUDES_PATH . 'adm_db_common_function.inc.php');
require_once(ADM_INCLUDES_PATH . 'subscription_function.inc.php');

$strClientId = $_REQUEST['txtClientId'];
$strPassword = MD5(trim($_REQUEST['txtPassword']));
$strLicense= $_REQUEST['txtLicense'];

if (isset($strClientId) && isset($strPassword) && isset($strLicense))
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
        $gmt_datetime = GM_DATE;
        $OperationType = 0;
        
         try
        {
            $addClientLicense = insClientLicenseDetails($DBClientId, $strLicense, $OperationType, $gmt_datetime, $objDataHelper);
        }
        catch (Exception $a)
        {
            throw new Exception("addsubscription.php : insOrderMaster : Error in adding order master." . $a->getMessage(), 613);
        }
        $strLicenseStatus = $addClientLicense[0]['@STATUS'];
        
        if ($strLicenseStatus == 1)
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
?>
