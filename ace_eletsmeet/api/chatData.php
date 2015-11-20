<?php
require_once('../includes/global.inc.php');
//require_once(CLASSES_PATH . 'error.inc.php');
//require_once(DBS_PATH . 'DataHelper.php');
//require_once(DBS_PATH . 'objDataHelper.php');

$strCDATA = trim($_REQUEST["CDATA"]);   //Chat Data

$debug_log = 1;
$LogPath = LOGS_PATH . "/chatdata_api_logs/";
   
if ($debug_log == 1)
{
    error_log(date("Y-m-d H:i:s") . "," . $_SERVER['REMOTE_ADDR'] . ", " . $strCDATA . "\r\n", 3, $LogPath . "chat_data_" . date('Y-m-d') . ".log");
}
