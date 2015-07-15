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
$strRecordingUrl = trim($_REQUEST['txtUrl']);
?>
<div>
    <img class="fR" id="close" border='0' title='Close' alt='Close' src="<?php echo IMG_PATH; ?>close_black.png" onclick="closeDetails();">
    <Iframe name="RecordBox" src="<?php echo $strRecordingUrl; ?>" seamless="seamless" scrolling="auto" height="666px" width="1226px" align="middle" scale="3"></Iframe>
</div>        