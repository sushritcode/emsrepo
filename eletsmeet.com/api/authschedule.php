<?php

require_once('../includes/global.inc.php');
require_once(CLASSES_PATH.'error.inc.php');
require_once(DBS_PATH.'DataHelper.php');
require_once(DBS_PATH.'objDataHelper.php');
require_once(INCLUDES_PATH.'api_db_function.inc.php');

header('Content-type: text/plain; charset=utf-8');

try
{
    $strUSID = trim($_GET["USID"]);   //user_id
    $strCLID = trim($_GET["CLID"]);   //client_id
    $strPRID = trim($_GET["PRID"]);

    $STATUS = '';

    if (strlen(trim($strUSID)) <= 0)
    {
        $STATUS = 1;
        $MESSAGE = "Missing Parameter USID";
    }
    elseif (strlen(trim($strCLID)) <= 0)
    {
        $STATUS = 1;
        $MESSAGE = "Missing Parameter CLID";
    }
    elseif (strlen(trim($strPRID)) <= 0)
    {
        $STATUS = 1;
        $MESSAGE = "Missing Parameter PRID";
    }
    elseif (!is_numeric($strPRID))
    {
        $STATUS = 1;
        $MESSAGE = "Invalid characters in PRID";
    }
    elseif ($strPRID != PRID)
    {
        $STATUS = 1;
        $MESSAGE = "Invalid PRID";
    }

    if (trim($STATUS) == "")
    {
        // Authenticate User's Subscription for Schedule Meeting.
        try
        {
            $arrAuthSchDtls = isAuthenticateScheduleUser($strUSID, $strCLID, $objDataHelper);
        }
        catch (Exception $a)
        {
            throw new Exception("Error in isAuthenticateScheduleUser.".$a->getMessage(), 311);
        }
//print_r($arrAuthSchDtls);
//exit;

        $strUSER_ID = trim($arrAuthSchDtls[0]['user_id']);
        $strUSER_STATUS = trim($arrAuthSchDtls[0]['user_status']);
        $strCLIENT_ID = trim($arrAuthSchDtls[0]['client_id']);
        $strCLIENT_STATUS = trim($arrAuthSchDtls[0]['client_status']);


        if (($strUSER_STATUS == 1) && ($strCLIENT_STATUS == 1))
        {
            $STATUS = 0;
            $MESSAGE = "You can Schedule E-Meeting";
        }
        else
        {
            $STATUS = 1;
            $MESSAGE = "You can't Schedule E-Meeting";
        }
    }
}
catch (Exception $e)
{
    if ($STATUS == "")
    {
        $STATUS = 8;
    }

    if ($MESSAGE == "")
    {
        $MESSAGE = "Fatal Error";
    }

    $ErrorHandler->RaiseError($_SERVER["PHP_SELF"], $e->getCode(), $e->getMessage(), false);
    /*
      if ($debug_log == 1)
      {
      error_log(date("Y-m-d H:i:s") . ", AUTHENTICATEV2," . $e->getCode() . "," . $e->getMessage() . "\r\n", 3, LOGS_PATH . "authenticateV2_error_" . date('Y-m-d') . ".log");
      } */
}

$RESPONSE = $STATUS.SEPARATOR.$MESSAGE;

echo $RESPONSE;
/*
  if ($debug_log == 1)
  {
  error_log(date("Y-m-d H:i:s") . ", AUTHENTICATEV2, " . $strMSISDN . ", " . $strBLID . ", " . $strVERNO . ", " . $strPLOUT . ", " . $strPRID . ", RESPONSE, " . $RESPONSE . "\r\n\n", 3, LOGS_PATH . "authenticateV2_" . date('Y-m-d') . ".log");
  } */
?>