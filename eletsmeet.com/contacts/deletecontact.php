<?php
require_once('../includes/global.inc.php');
require_once(CLASSES_PATH.'error.inc.php');
require_once(DBS_PATH.'DataHelper.php');
require_once(DBS_PATH.'objDataHelper.php');
require_once(INCLUDES_PATH.'cm_authfunc.inc.php');
$CONST_MODULE = 'contact';
$CONST_PAGEID = 'Delete Contact';
require_once(INCLUDES_PATH.'cm_authorize.inc.php'); 
require_once(INCLUDES_PATH.'contact_function.inc.php');

try
{
    //session_start();  
    $strContactId = trim($_REQUEST["txtContactId"]);
    $strContactName = trim($_REQUEST["txtContactName"]);
    $strFormName = trim($_REQUEST["txtFormName"]);

    if(strlen(trim($strContactId)) <=0 )
    {
	throw new Exception("deletecontact.php : Missing Parameter strContactId",641);
    }

    try
    {
        $deleteContactDetails = delContactDetails($strContactId, $strCK_user_id, PERSONAL_CONTACT_TYPE,$objDataHelper);
        if($deleteContactDetails[0]['@STATUS'] == 1)
        {
            //$_SESSION['txtErrorMsg'] = 'Contact <b><font color=#006699>"'.$strContactName.'" </font></b>deleted successfully.';
            $msg = 'Contact <b><font color=#006699>"'.$strContactName.'" </font></b>deleted successfully.';
        }
        else
        {
            //$_SESSION['txtErrorMsg'] = 'Error in Deleting.';
            $msg = 'Error in Deleting.';
        }
    }
    catch(Exception $a)
    {
	throw new Exception("deletecontact.php : DeleteContact : Error in Deleting contact.".$a->getMessage(),642);
    }

    $strAction = $SITE_ROOT.'contacts?msg='.urlencode($msg);
    
    echo "<html>
    <head>
    <title></title>
    </head>
    <body>
    <form name='frmDelContact' method='post' action='".$strAction."'>
      <input type='hidden' name='txtContactId' value='".$strContactId."'>
      <input type='hidden' name='txtFormName' value='".$strFormName."'>
    </form>
    <script language='javascript'>
	    document.frmDelContact.submit();
    </script>
    </body>
    </html>";
}
catch(Exception $e)
{
  $ErrorHandler->RaiseError($_SERVER["PHP_SELF"], $e->getCode(),$e->getMessage(), true);
}
?>


