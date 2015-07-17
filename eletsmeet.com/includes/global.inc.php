<?php
$arrHOST  = explode("." , $_SERVER['HTTP_HOST']);

$urlScheme = "https";
if(count($arrHOST) > 2 )
{
	$urlScheme = "http";
}
//$SITE_ROOT = $urlScheme."://".$_SERVER['HTTP_HOST']."/";
$SITE_ROOT = $urlScheme."://".$_SERVER['HTTP_HOST']."/eletsmeet.com/";

//$SITE_ROOT = "https://".$_SERVER['HTTP_HOST']."/";
//$SITE_ROOT = 'http://' . $_SERVER['HTTP_HOST'] . '/eletsmeet.com/';

$WEBAPP_PATH = "/var/www/html/eletsmeet.com/";
//$WEBAPP_PATH = "/home/eletsmeet/public_html/";
/*********************Configuration files ******************/
$CONST_SITETITLE = "Welcome to LetsMeet";

define("CLASSES_PATH",$WEBAPP_PATH."classes/");
define("CSS_PATH",$SITE_ROOT."css/");
define("DBS_PATH",$WEBAPP_PATH."dbs/");
define("IMG_PATH",$SITE_ROOT."images/");
define("INCLUDES_PATH",$WEBAPP_PATH."includes/");
define("JS_PATH",$SITE_ROOT."js/");
define("LOGOUT_URL",$SITE_ROOT."authenticate/logout.php");
define("ROOT_PATH",$WEBAPP_PATH);
/*********************Configuration files ******************/

/*********************DB Connection Start******************/
/*********************DB Connection End******************/

/********************ERROR LOGGING************/
define('DEBUG_LOG', 1);
define("ERROR_LOG_EMAIL","mitesh.shah@quadridge.com");
define("ERROR_LOG_FILE_NAME","eletsmeet_com_error_log.log");
define("LOG_ERROR", true);
define("LOGS_PATH",$WEBAPP_PATH."logs/"); 
define("MAIL_ERROR_LOG", true);
define("SHOW_TRACE",true);
/********************ERROR LOGGING************/

/********************Email Informations**********************/
//$CONST_FEEDBACK_EID = "feedback.letsmeet@quadridge.com";
//$CONST_SUPPORT_EID  = "support.letsmeet@quadridge.com";
//$CONST_CONTACT_EID  = "contact.letsmeet@quadridge.com";
//$CONST_NOREPLY_EID    = "no-reply-lm@quadridge.com";
$CONST_NOREPLY_EID    = "letsmeet@eletsmeet.com";
//$CONST_ENQUIRY_EID  = "enquires.letsmeet@quadridge.com";

define("CONST_PRODUCT_NAME", "LetsMeet");
//define("CONST_NOREPLY_EID", "no-reply-lm@quadridge.com");
define("CONST_NOREPLY_EID", "letsmeet@eletsmeet.com");
define("RELAY_EMAIL_FLAG", 1); //1 = On & 0= Off
//define("RELAY_MAIL_API","http://lm.quadridge.com/api/sendmail.php?");
//define("RELAY_MAIL_API",$SITE_ROOT."api/sendmail.php?");
define("RELAY_MAIL_API",$SITE_ROOT."includes/relay_mail.php?");
/********************Email Informations**********************/

/********************CONFIG********************/
$gmDate = gmdate("Y-m-d H:i:s");
define("GM_DATE",$gmDate);

$regValidTime = strtotime(gmdate("Y-m-d H:i:s", strtotime($gmDate)) . " +3 day");
define("REG_VALID_DATE",$regValidTime );

define("PRID",1);
define("CLIENT_ID","cl00001");
define("PARTNER_ID","pr00001");
define("SEPARATOR",chr(124));

define("USER_SESSION_NAME","ckUsrLetsMeetUserSession");

define("SECRET_KEY","qUadr1DG3Le75m3e7");
define("REG_SECRET_KEY","L3tSm3e7qUadr1DG3");
define("INT_API_ROOT",$SITE_ROOT);
define("JMX_API_ROOT",$SITE_ROOT."join/");

//conference.eletsmeet.com
define("VIDEO_SERVER", "conference.eletsmeet.com");
define("VIDEO_SERVER_SALT","cda9d43824a4828383833ae77dde40ef");  
     
define("VIDEO_SERVER_API","/bigbluebutton/api/");
define("VIDEO_SERVER_CREATE_API","create?");
define("VIDEO_SERVER_JOIN_API","join?");
define("VIDEO_SERVER_IS_MEETING_RUNNING_API","isMeetingRunning?");
define("VIDEO_SERVER_GET_RECORDING_API","getRecordings?");
define("VIDEO_SERVER_END_MEETING_API","end?");
define("VIDEO_SERVER_GET_MEETING_INFO_API","getMeetingInfo?");
define("MEETING_LOGOUT_URL",$SITE_ROOT);

define("MODERATOR_PWD","mppwd");
define("ATTENDEE_PWD","appwd");
define("MEETING_RECORD_FLAG","true");
define("MEETING_START_GRACE_INTERVAL",15);
define("MEETING_END_GRACE_INTERVAL",60);
define("DAEMON_MEETING_OVERDUE_GRACE_INTERVAL",60);
define("DAEMON_MEETING_END_GRACE_INTERVAL",5);
define("DEFAULT_INVITEE_LIMIT",30);
define("MEETING_LIST_GRACE_INTERVAL",60);
define("JOIN_MEETING_WELCOME_MSG","Welcome to LetsMeet");
define("VOICE_BRIDGE_LENGTH",5);
define("VOICE_BRIDGE_PREFIX",7);
define("PERSONAL_CONTACT_TYPE","P");
define("CLIENT_CONTACT_TYPE","C");
define("MEETING_END_TIME",240);

define("CUSTOM_LOGO_FLAG",1); // 0= FALSE 1= TRUE
define("CUSTOM_LOGO_NAME","quadridge-logo-white.png"); 
define("CUSTOM_LOGO_TITLE","Quadridge Technologies"); 

define("GRACE_PERIOD",10);
//define("CLIENT_SECRET_KEY","L1vEmAric0LIVoN");
/********************CONFIG********************/
