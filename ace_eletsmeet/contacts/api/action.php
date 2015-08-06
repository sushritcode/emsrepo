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
			?>
				<script type="text/javascript">window.location.href = "<?php echo $SITE_ROOT."contacts/";?>";</script>
			<?
			exit;
		break;
		case "enable":
			$returnVal = enablecontact($_REQUEST['contactid'], $strCK_user_id , $objDataHelper);
			?>
				<script type="text/javascript">window.location.href = "<?php echo $SITE_ROOT."contacts/";?>";</script>
			<?
			exit;
		break;
		case "add":
			$formMaps  = profile_form_table_map_contacts();
			$_REQUEST["association"] = $strCK_user_id;
			print $insertParams = getInsertQueryString($_REQUEST , $formMaps);
			exit;
			$result  = updateUserProfile($insertParams , $objDataHelper , $strCK_user_id , $_REQUEST["action"]);
			echo $result;
		break;
		case "update":
			$formMaps  = profile_form_table_map();
			$updateparams = getUpdateQueryString($_REQUEST , $formMaps);
			$result  = updateUserProfile($updateparams , $objDataHelper , $strCK_user_id , $_REQUEST["action"]);
			echo $result;

		break;
	}
}
?>
