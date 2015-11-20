<?php
$ADMIN_SITE_ROOT = $SITE_ROOT.'admin/';

//$ADMIN_WEBAPP_PATH   = "/home/eletsmeet/public_html/admin/";
$ADMIN_WEBAPP_PATH = "/var/www/html/emsrepo/branches/mitesh/ace_eletsmeet/admin/";

/*********************Configuration files ******************/
$ADM_CONST_SITETITLE = "Welcome to LetsMeet Admin";

define("ADM_CSS_PATH",$ADMIN_SITE_ROOT."css/");
define("ADM_IMG_PATH",$ADMIN_SITE_ROOT."images/");
define("ADM_INCLUDES_PATH",$ADMIN_WEBAPP_PATH."includes/");
define("ADM_JS_PATH",$ADMIN_SITE_ROOT."js/");
define("ADM_CLASSES_PATH",$ADMIN_WEBAPP_PATH."classes/");
define("ADM_ROOT_PATH",$ADMIN_WEBAPP_PATH);
define("ADM_LOGOUT_URL",$ADMIN_SITE_ROOT."includes/logout.php");

define("ADM_SESSION_NAME","ckAdmLetsMeetUserSession");
/*********************Configuration files ******************/

/********************ERROR LOGGING************/
define('ADM_DEBUG_LOG', 1);

define("ADM_ERROR_LOG_EMAIL","mitesh.shah@quadridge.com");
define("ADM_ERROR_LOG_FILE_NAME","stage.eletsmeet_com_admin_error_log.log");
define("ADM_LOG_ERROR", true);
//define("ADM_LOGS_PATH",$ADMIN_WEBAPP_PATH."admin_logs/");
define("ADM_LOGS_PATH",$WEBAPP_PATH."logs/");
define("ADM_MAIL_ERROR_LOG", true);
define("ADM_SHOW_TRACE",true);
/********************ERROR LOGGING************/

/********************CONFIG********************/
$minDate = '01/01/2015';
define('MIN_DATE', $minDate);
/********************CONFIG********************/