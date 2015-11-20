<?php
$CLIENT_SITE_ROOT = $SITE_ROOT.'client/';

$CLIENT_WEBAPP_PATH   = "/home/eletsmeet/public_html/stage.eletsmeet.com/client/";

/*********************Configuration files ******************/
define("CLIENT_CLASSES_PATH",$CLIENT_WEBAPP_PATH."classes/");
define("CLIENT_CSS_PATH",$SITE_ROOT."assets/css/");
define("CLIENT_DBS_PATH",$WEBAPP_PATH."dbs/");
define("CLIENT_IMG_PATH",$SITE_ROOT."assets/images/");
define("CLIENT_INCLUDES_PATH",$CLIENT_WEBAPP_PATH."includes/");
define("CLIENT_JS_PATH",$SITE_ROOT."assets/js/");
define("CLIENT_LOGO_PATH",$SITE_ROOT."assets/images/client_logo/");
define("CLIENT_ROOT_PATH",$CLIENT_WEBAPP_PATH);

define("CLIENT_HEAD_INCLUDES_PATH",INCLUDES_PATH."head.php");
define("CLIENT_CSS_INCLUDES_PATH",INCLUDES_PATH."css_include.php");
define("CLIENT_TOP_NAVIGATION_INCLUDES_PATH",CLIENT_INCLUDES_PATH."top_navigation.php");
define("CLIENT_SIDEBAR_INCLUDES_PATH",CLIENT_INCLUDES_PATH."sidebar_navigation.php");
define("CLIENT_BREADCRUMBS_INCLUDES_PATH",CLIENT_INCLUDES_PATH."breadcrumbs_navigation.php");
define("CLIENT_JS_INCLUDES_PATH",INCLUDES_PATH);
define("CLIENT_FOOTER_INCLUDES_PATH",INCLUDES_PATH."footer.php");
/*********************Configuration files ******************/

/*********************Configuration Parameter Start ******************/
$CLIENT_CONST_SITETITLE = "Welcome to LetsMeet Client";
define("CLIENT_SESSION_NAME","ckClientLetsMeetSession");
define("CLIENT_PROFILE_URL",$CLIENT_SITE_ROOT."profile/");
define("CLIENT_LOGOUT_URL",$CLIENT_SITE_ROOT."logout/");
/*********************Configuration Parameter Start ******************/

/********************Error Logging Parameter Start************/
define('CLIENT_DEBUG_LOG', 1);
define("CLIENT_ERROR_LOG_EMAIL","mitesh.shah@quadridge.com");
define("CLIENT_ERROR_LOG_FILE_NAME","stage.eletsmeet_com_client_error_log.log");
define("CLIENT_LOG_ERROR", true);
define("CLIENT_LOGS_PATH",$WEBAPP_PATH."logs/");
define("CLIENT_MAIL_ERROR_LOG", true);
define("CLIENT_SHOW_TRACE",true);
/********************Error Logging Parameter Start************/

/********************CONFIG********************/
$minDate = '01/01/2015';
define('MIN_DATE', $minDate);
/********************CONFIG********************/