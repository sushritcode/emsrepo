<?php

require_once('../../includes/global.inc.php');
require_once(CLASSES_PATH.'error.inc.php');
require_once(DBS_PATH.'DataHelper.php');
require_once(DBS_PATH.'objDataHelper.php');
require_once(INCLUDES_PATH.'cm_authfunc.inc.php');
require_once(INCLUDES_PATH.'cm_authorize.inc.php');
require_once(INCLUDES_PATH.'common_function.inc.php');
require_once(INCLUDES_PATH.'profile_function.inc.php');
require_once(INCLUDES_PATH.'mail_common_function.inc.php');


if(isset($_REQUEST["action"]))
{
	switch($_REQUEST["action"])
	{
		case "reset":
			$formMaps  = profile_form_table_map();
			$updateparams = getUpdateQueryString($_REQUEST , $formMaps);
			$result  = updateUserProfile($updateparams , $objDataHelper , $strCK_user_id , $_REQUEST["action"]);
			echo $result;
			break;
		case "resetpwd":
			$formMaps = profile_form_table_map();
			$updateparams = getUpdateQueryString($_REQUEST , $formMaps);
			$result  = updateUserProfile($updateparams , $objDataHelper , $strCK_user_id , $_REQUEST["action"]);
			echo $result;
			break;
		case "forgotpwd":
			//print_r($_REQUEST);
			$result  = forgotPwd($objDataHelper);
			echo $result;
			break;

			
	}	
}
?>

