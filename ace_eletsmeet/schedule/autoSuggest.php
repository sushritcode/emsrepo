<?php
require_once('../includes/global.inc.php');
require_once(CLASSES_PATH . 'error.inc.php');
require_once(DBS_PATH . 'DataHelper.php');
require_once(DBS_PATH . 'objDataHelper.php');
require_once(INCLUDES_PATH . 'cm_authfunc.inc.php');
$CONST_MODULE = 'schedule';
$CONST_PAGEID = 'Schedule Page';
require_once(INCLUDES_PATH . 'cm_authorize.inc.php');
require_once(INCLUDES_PATH . 'common_function.inc.php');
require_once(INCLUDES_PATH . 'schedule_function.inc.php');

$word = $_REQUEST['aLetter'];
$clID = $_REQUEST['clID'];
$email = $_REQUEST['email'];

try
{
    try
    {
        $aList = autoSuggest($strCk_user_client_id, $strCK_user_id,  $word, $objDataHelper);
    }
    catch (Exception $e)
    {
        throw new Exception("autoSuggest.php : autoSuggest Failed : ".$e->getMessage(), 1151);
    }
    
    foreach ($aList as $aKey => $aKeyArray)
    {
        if ($aKeyArray['contact_email_address'] !== $email)
        {
            //$contacts .= "<div style='float:left;width:90%'>".$aKeyArray['nick_name']."</div><div style='float:left;'><input type='checkbox' name='uData' value='".$aKeyArray['contact_email_address'].":".$aKeyArray['nick_name'].":".$aKeyArray['contact_idd_code'].":".$aKeyArray['contact_mobile_number']."' id='".$aKeyArray['contact_group_name'].":".$aKeyArray['contact_email_address']."' onclick=javascript:cCounter('".urlencode($aKeyArray['contact_group_name'])."','".$aKeyArray['contact_email_address']."','con')></div>";
            $contacts .="<div class='input-group' style='padding-bottom: 1px;'><span class='input-group-addon'><i class='ace-icon fa fa-user'></i></span><label class='form-control'><small>".$aKeyArray['nick_name']."</small></label><span class='input-group-addon'><input type='checkbox' class='ace' name='uData' value='".$aKeyArray['contact_email_address'].":".$aKeyArray['nick_name'].":".$aKeyArray['contact_idd_code'].":".$aKeyArray['contact_mobile_number']."' id='".$aKeyArray['contact_group_name'].":".$aKeyArray['contact_email_address']."' onclick=javascript:cCounter('".urlencode($aKeyArray['contact_group_name'])."','".$aKeyArray['contact_email_address']."','con') /><span class='lbl'></span></span></div>";
        }
    }
}
catch (Exception $e)
{
    throw new Exception("autoSuggest.php : Failed : ".$e->getMessage(), 1152);
}
echo $contacts;