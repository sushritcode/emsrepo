<?php
require_once('../../includes/global.inc.php');
require_once('../includes/config.inc.php');
require_once(ADM_CLASSES_PATH . 'admin_error.inc.php');
require_once(DBS_PATH . 'DataHelper.php');
require_once(DBS_PATH . 'objDataHelper.php');
require_once(ADM_INCLUDES_PATH . 'adm_authfunc.inc.php');
require_once(ADM_INCLUDES_PATH . 'adm_authorize.inc.php');

 if (sizeof($arrAdminCookieDtls) < 0)    
{
    $redirectURL = $ADMIN_SITE_ROOT;
    header("Location: $redirectURL");
}
else
{
    $redirectURL = $ADMIN_SITE_ROOT . "reports/rpt_license_count.php";
    header("Location: $redirectURL");
}
?>
