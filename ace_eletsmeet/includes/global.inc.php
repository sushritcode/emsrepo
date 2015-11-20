<?php
$arrHOST  = explode("." , $_SERVER['HTTP_HOST']);
$urlScheme = "https";
if(count($arrHOST) > 2 )
{
    $urlScheme = "http";
}
$SITE_ROOT = $urlScheme."://".$_SERVER['HTTP_HOST']."/";

$WEBAPP_PATH = "/home/eletsmeet/public_html/stage.eletsmeet.com/";

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

define("LOGOUT_URL",$SITE_ROOT."logout/");
define("PROFILE_URL",$SITE_ROOT."profile/");
/*********************Configuration Parameter Start ******************/
$CONST_SITETITLE = "Welcome to LetsMeet";

//define("CLIENT_ID","cl00001");
//define("PARTNER_ID","pr00001");
define("PRID",1);
define("SEPARATOR",chr(124));

$gmDate = gmdate("Y-m-d H:i:s");
define("GM_DATE",$gmDate);

$regValidTime = strtotime(gmdate("Y-m-d H:i:s", strtotime($gmDate)) . " +3 day");
define("REG_VALID_DATE",$regValidTime );

define("INT_API_ROOT",$SITE_ROOT);
define("JMX_API_ROOT",$SITE_ROOT."join/");

define("CUSTOM_LOGO_NAME",str_replace("." , "_", $_SERVER['HTTP_HOST']).".png"); 
define("CUSTOM_LOGO_TITLE",$_SERVER['HTTP_HOST']);

define("REG_SECRET_KEY","L3tSm3e7qUadr1DG3");
define("SECRET_KEY","qUadr1DG3Le75m3e7");
define("USER_SESSION_NAME","ckUsrLetsMeetUserSession");

/*********************Configuration Parameter End ******************/

/*********************DB Connection Start******************/
define("DB_CONNECTIONSTRING","stageletsmeet:Qu@dEl3ts!meE7:localhost:stage_db_eletesmeet_com:3306");
/*********************DB Connection End******************/

/********************Error Logging Parameter Start************/
define('DEBUG_LOG', 1); //1 = On & 0= Off
define("ERROR_LOG_EMAIL","mitesh.shah@quadridge.com");
define("ERROR_LOG_FILE_NAME","stage.eletsmeet_com_error_log.log");
define("LOG_ERROR", true);
define("LOGS_PATH",$WEBAPP_PATH."logs/"); 
define("MAIL_ERROR_LOG", true);
define("SHOW_TRACE",true);
/********************Error Logging Parameter End************/

/********************Email Informations Start**********************/
define("CONST_NOREPLY_EID", "letsmeet@eletsmeet.com");

define("CONST_PRODUCT_NAME", "LetsMeet");
define("RELAY_EMAIL_FLAG", 2); //1 = On & 0= Off
define("RELAY_MAIL_API",$SITE_ROOT."includes/sendmail.php?");
//define("RELAY_MAIL_API",$SITE_ROOT."includes/relay_mail.php?"); // Used for Live Server
/********************Email Informations End**********************/


define("VOICE_BRIDGE_LENGTH",5);
define("VOICE_BRIDGE_PREFIX",7);
define("PERSONAL_CONTACT_TYPE","P");
define("CLIENT_CONTACT_TYPE","C");

define("MODERATOR_PWD","mppwd");
define("ATTENDEE_PWD","appwd");
define("MEETING_RECORD_FLAG","true");
define("MEETING_START_GRACE_INTERVAL",60);
define("MEETING_END_GRACE_INTERVAL",60);
define("DAEMON_MEETING_OVERDUE_GRACE_INTERVAL",60);
define("DAEMON_MEETING_END_GRACE_INTERVAL",5);
define("DEFAULT_INVITEE_LIMIT",30);
define("MEETING_LIST_GRACE_INTERVAL",60);
define("JOIN_MEETING_WELCOME_MSG","Welcome to LetsMeet");

define("VIDEO_SERVER_API","/bigbluebutton/api/");
define("VIDEO_SERVER_CREATE_API","create?");
define("VIDEO_SERVER_JOIN_API","join?");
define("VIDEO_SERVER_IS_MEETING_RUNNING_API","isMeetingRunning?");
define("VIDEO_SERVER_GET_RECORDING_API","getRecordings?");
define("VIDEO_SERVER_END_MEETING_API","end?");
define("VIDEO_SERVER_GET_MEETING_INFO_API","getMeetingInfo?");
define("MEETING_LOGOUT_URL",$SITE_ROOT);

//define("MEETING_END_TIME",240);
//define("GRACE_PERIOD",180);