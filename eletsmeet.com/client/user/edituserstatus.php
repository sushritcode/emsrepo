<?php

require_once('../../includes/global.inc.php');
require_once('../includes/config.inc.php');
require_once(CLIENT_CLASSES_PATH . 'client_error.inc.php');
require_once(DBS_PATH . 'DataHelper.php');
require_once(DBS_PATH . 'objDataHelper.php');
require_once(CLIENT_INCLUDES_PATH . 'client_authfunc.inc.php');
$CLIENT_CONST_MODULE = 'cluser';
$CLIENT_CONST_PAGEID   = 'Edit User Status';
require_once(CLIENT_INCLUDES_PATH . 'client_authorize.inc.php');
require_once(CLIENT_INCLUDES_PATH . 'client_db_function.inc.php');

try
{
    //session_start();
    $strStatus = trim($_REQUEST["txtAction"]);
    $strUserId = trim($_REQUEST["txtUserId"]);
    $strFirstName = trim($_REQUEST["txtFirstName"]);
    $strLastName = trim($_REQUEST["txtLastName"]);
    $strEmailId = trim($_REQUEST["txtEmailId"]);

    if (strlen(trim($strUserId)) <= 0)
    {
        throw new Exception("edituserstatus.php : Missing Parameter strUserId", 641);
    }

    if ($strStatus == 'enable')
    {
        $userStatus = '1';  //Active
    }
    else
    {
        $userStatus = '2';  //Deactive
    }

    try
    {
        $editUserStatus = updateUserStatus($strUserId, $userStatus, $objDataHelper);
        if ($editUserStatus == 1)
        {
            if ($userStatus == 1)
            {
                $txtErrorMsg = 'User <b><font color=#006699>"' . $strFirstName . ' ' . $strLastName . '" </font></b>activated successfully.';
            }
            else
            {
                $txtErrorMsg = 'User <b><font color=#006699>"' . $strFirstName . ' ' . $strLastName . '" </font></b>deactivated successfully.';
            }
        }
        else
        {
            $txtErrorMsg = 'Error';
        }
    }
    catch (Exception $a)
    {
        throw new Exception("edituserstatus.php : DeleteContact : Error in Deleting contact." . $a->getMessage(), 642);
    }

    $strAction = $CLIENT_SITE_ROOT . 'user/';
    
    echo "<html>
      <head>
      <title></title>
      </head>
      <body>
      <form name='frmEditUser' method='post' action='" . $strAction . "'>
      <input type='hidden' name='txtFirstName' value='" . $_SESSION['fName'] . "'>
      <input type='hidden' name='txtLastName' value='" . $_SESSION['lName'] . "'>
      <input type='hidden' name='txtEmailId' value='" . $_SESSION['emailId'] . "'>
      <input type='hidden' name='txtErrorMsg' value='" . $txtErrorMsg . "'>
      </form>
      <script language='javascript'>
      document.frmEditUser.submit();
      </script>
      </body>
      </html>";
}
catch (Exception $e)
{
    $ErrorHandler->RaiseError($_SERVER["PHP_SELF"], $e->getCode(), $e->getMessage(), true);
}
?>