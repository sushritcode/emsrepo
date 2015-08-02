<?php

require_once('../../includes/global.inc.php');
require_once(CLASSES_PATH.'error.inc.php');
require_once(DBS_PATH.'DataHelper.php');
require_once(DBS_PATH.'objDataHelper.php');
require_once(INCLUDES_PATH.'cm_authfunc.inc.php');
require_once(INCLUDES_PATH.'cm_authorize.inc.php');
require_once(INCLUDES_PATH.'common_function.inc.php');
require_once(INCLUDES_PATH.'contact_function.inc.php');


if(isset($_REQUEST["action"]))
{
	switch($_REQUEST["action"])
	{
		case "disable":	
			$returnVal = disablecontact($_REQUEST['contactid'], $strCK_user_id , $objDataHelper);
print "herer";
			?>
				<script type="text/javascript">location.href = <?php echo $SITE_ROOT."contacts/";?>;</script>
			<?
			exit;
		break;
		case "enable":
			$returnVal = enablecontact($_REQUEST['contactid'], $strCK_user_id , $objDataHelper);
print "herer";
			?>
				<script type="text/javascript">location.href = <?php echo $SITE_ROOT."contacts/";?>;</script>
			<?
			exit;
		break;
	}
}
