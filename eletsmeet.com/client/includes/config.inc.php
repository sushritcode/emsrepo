<?php
$CLIENT_SITE_ROOT = $SITE_ROOT.'client/';

$CLIENT_WEBAPP_PATH   = "/var/www/html/eletsmeet.com/client/";
//$CLIENT_WEBAPP_PATH   = "/home/eletsmeet/public_html/client/";
/*********************Configuration files ******************/
$CLIENT_CONST_SITETITLE = "Welcome to LetsMeet Client";

define("CLIENT_CSS_PATH",$CLIENT_SITE_ROOT."css/");
define("CLIENT_IMG_PATH",$CLIENT_SITE_ROOT."images/");
define("CLIENT_LOGO_PATH",$CLIENT_SITE_ROOT."images/client_logo/");
define("CLIENT_INCLUDES_PATH",$CLIENT_WEBAPP_PATH."includes/");
define("CLIENT_JS_PATH",$CLIENT_SITE_ROOT."js/");
define("CLIENT_CLASSES_PATH",$CLIENT_WEBAPP_PATH."classes/");
define("CLIENT_ROOT_PATH",$CLIENT_WEBAPP_PATH);
define("CLIENT_LOGOUT_URL",$CLIENT_SITE_ROOT."includes/logout.php");

define("CLIENT_SESSION_NAME","ckClientLetsMeetSession");
/*********************Configuration files ******************/

/********************ERROR LOGGING************/
define('CLIENT_DEBUG_LOG', 1);

define("CLIENT_ERROR_LOG_EMAIL","mitesh.shah@quadridge.com");
define("CLIENT_ERROR_LOG_FILE_NAME","eletsmeet_com_client_error_log.log");
define("CLIENT_LOG_ERROR", true);
define("CLIENT_LOGS_PATH",$WEBAPP_PATH."logs/");
define("CLIENT_MAIL_ERROR_LOG", true);
define("CLIENT_SHOW_TRACE",true);
/********************ERROR LOGGING************/

/********************CONFIG********************/
$minDate = '01/01/2015';
define('MIN_DATE', $minDate);
/********************CONFIG********************/