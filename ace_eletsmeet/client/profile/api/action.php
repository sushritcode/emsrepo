<?php
require_once('../../includes/global.inc.php');
require_once('../includes/config.inc.php');
require_once(CLIENT_CLASSES_PATH . 'client_error.inc.php');
require_once(CLIENT_DBS_PATH . 'DataHelper.php');
require_once(CLIENT_DBS_PATH . 'objDataHelper.php');
require_once(CLIENT_INCLUDES_PATH . 'client_authfunc.inc.php');
$CLIENT_CONST_MODULE = 'cl_profile';
$CLIENT_CONST_PAGEID = 'Client Profile';
require_once(CLIENT_INCLUDES_PATH . 'client_authorize.inc.php');
require_once(CLIENT_INCLUDES_PATH . 'client_db_function.inc.php');
require_once(CLIENT_INCLUDES_PATH . 'profile_function.inc.php');
require_once(CLIENT_INCLUDES_PATH . 'mail_common_function.inc.php');

if (isset($_REQUEST["action"])) {
    switch ($_REQUEST["action"]) {
        case "reset":
            $formMaps = profile_form_table_map();
            $updateparams = getUpdateQueryString($_REQUEST, $formMaps);
            $result = updateUserProfile($updateparams, $objDataHelper, $strCK_user_id, $_REQUEST["action"]);
            echo $result;
            break;
        case "resetpwd":
            $formMaps = profile_form_table_map();
            $updateparams = getUpdateQueryString($_REQUEST, $formMaps);
            $result = updateUserProfile($updateparams, $objDataHelper, $strCK_user_id, $_REQUEST["action"]);
            echo $result;
            break;
        case "forgotpwd":
            //print_r($_REQUEST);
            $result = forgotPwd($objDataHelper);
            echo $result;
            break;
    }
}

