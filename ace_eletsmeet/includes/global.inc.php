<?php
$arrHOST  = explode("." , $_SERVER['HTTP_HOST']);
$urlScheme = "https";
if(count($arrHOST) > 2 )
{
    $urlScheme = "http";
}
//$SITE_ROOT = $urlScheme."://".$_SERVER['HTTP_HOST']."/";
$SITE_ROOT = $urlScheme."://".$_SERVER['HTTP_HOST']."/letsemeet/emsrepo/branches/sushrit/ace_eletsmeet/";

$WEBAPP_PATH = "/var/www/html/letsemeet/emsrepo/branches/sushrit/ace_eletsmeet/";
//$WEBAPP_PATH = "/home/eletsmeet/public_html/";

/*********************Includes Configuration files ******************/
define("ASSETS_PATH",$SITE_ROOT."assets/images/");
define("AVATARS_PATH",$SITE_ROOT."assets/avatars/");
define("CLASSES_PATH",$WEBAPP_PATH."classes/");
define("CSS_PATH",$SITE_ROOT."assets/css/");
define("DBS_PATH",$WEBAPP_PATH."dbs/");
define("IMG_PATH",$SITE_ROOT."assets/images/");
define("INCLUDES_PATH",$WEBAPP_PATH."includes/");
define("JS_PATH",$SITE_ROOT."assets/js/");
define("ROOT_PATH",$WEBAPP_PATH);
/*********************Includes Configuration files ******************/

define("LOGOUT_URL",$SITE_ROOT."dashboard/logout.php");
define("PROFILE_URL",$SITE_ROOT."profile/");
/*********************Configuration Parameter Start ******************/
$CONST_SITETITLE = "Welcome to LetsMeet";

define("CLIENT_ID","cl00001");
define("PARTNER_ID","pr00001");
define("PRID",1);
define("SEPARATOR",chr(124));

$gmDate = gmdate("Y-m-d H:i:s");
define("GM_DATE",$gmDate);

$regValidTime = strtotime(gmdate("Y-m-d H:i:s", strtotime($gmDate)) . " +3 day");
define("REG_VALID_DATE",$regValidTime );

define("INT_API_ROOT",$SITE_ROOT);

define("CUSTOM_LOGO_NAME",str_replace("." , "_", $_SERVER['HTTP_HOST']).".png"); 
define("CUSTOM_LOGO_TITLE",$_SERVER['HTTP_HOST']);

define("REG_SECRET_KEY","L3tSm3e7qUadr1DG3");
define("SECRET_KEY","qUadr1DG3Le75m3e7");
define("USER_SESSION_NAME","ckUsrLetsMeetUserSession");
/*********************Configuration Parameter End ******************/

/*********************DB Connection Start******************/
//define("DB_CONNECTIONSTRING","root:root123:localhost:db_eletesmeet_com:3306");
define("DB_CONNECTIONSTRING","root:mclaren:172.16.1.53:dev_db_eletesmeet_com:3306");
/*********************DB Connection End******************/

/********************Error Logging Parameter Start************/
define('DEBUG_LOG', 0); //1 = On & 0= Off
define("ERROR_LOG_EMAIL","mitesh.shah@quadridge.com");
define("ERROR_LOG_FILE_NAME","eletsmeet_com_error_log.log");
define("LOG_ERROR", true);
define("LOGS_PATH",$WEBAPP_PATH."logs/"); 
define("MAIL_ERROR_LOG", true);
define("SHOW_TRACE",true);
/********************Error Logging Parameter End************/

/********************Email Informations Start**********************/
define("CONST_NOREPLY_EID", "letsmeet@eletsmeet.com");

define("CONST_PRODUCT_NAME", "LetsMeet");
define("RELAY_EMAIL_FLAG", 0); //1 = On & 0= Off
define("RELAY_MAIL_API",$SITE_ROOT."api/sendmail.php?");
//define("RELAY_MAIL_API",$SITE_ROOT."includes/relay_mail.php?"); // Used for Live Server
/********************Email Informations End**********************/
