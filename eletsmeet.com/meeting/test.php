<?php
require_once('../includes/global.inc.php');
require_once(CLASSES_PATH."error.inc.php");
require_once(INCLUDES_PATH."Utilities.php");
require_once(DBS_PATH."DataHelper.php");
require_once(DBS_PATH."objDataHelper.php");
require_once(INCLUDES_PATH."db_common_function.inc.php");
require_once(INCLUDES_PATH."cm_authfunc.inc.php");
$CONST_MODULE = 'meeting';
$CONST_PAGEID = 'Meeting';
require_once(INCLUDES_PATH."cm_authorize.inc.php");
require_once(INCLUDES_PATH."sch_function.inc.php");
require_once(INCLUDES_PATH."api_db_function.inc.php");
require_once(INCLUDES_PATH."rc4.php");
require_once(INCLUDES_PATH."api_function.inc.php");
?>
<div>
    <img class="fR" id="close" border='0' title='Close' alt='Close' src="<?php echo IMG_PATH; ?>close_black.png" onclick="closeDetails();">
<!--    <Iframe name="DBox" src="http://conference.eletsmeet.com/playback/presentation/0.9.0/playback.html?meetingId=d1e5e2bb9a93b38592851fd1a3682ada0c8398b0-1434540247675" seamless="seamless" scrolling="auto" height="650px" width="1360px" align="middle" scale="3"></Iframe>-->

<Iframe name="DBox" src="http://172.16.1.128/eletsmeet.com/recording/playback/presentation/0.9.0/playback.html?meetingId=d1e5e2bb9a93b38592851fd1a3682ada0c8398b0-1434540247675" seamless="seamless" scrolling="auto" height="666px" width="1226px" align="middle" scale="3"></Iframe>
</div>        