<?php

try
{
    $arrAdminCookieDtls = getAdminUserSession();
    //print_r($arrAdminCookieDtls);
    
    if (sizeof($arrAdminCookieDtls) > 0 && $ADM_CONST_MODULE == "home")
    {
        header("Location: ".$ADMIN_SITE_ROOT.'user');
        exit;
    }
    if (sizeof($arrAdminCookieDtls) <= 0 && $ADM_CONST_MODULE == "profile")
    {
        header("Location: " . $ADMIN_SITE_ROOT);
        exit;
    }
    if (sizeof($arrAdminCookieDtls) <= 0 && $ADM_CONST_MODULE == "user")
    {
        header("Location: " . $ADMIN_SITE_ROOT);
        exit;
    }
    if (sizeof($arrAdminCookieDtls) <= 0 && $ADM_CONST_MODULE == "partner" )
    {
        header("Location: " . $ADMIN_SITE_ROOT);
        exit;
    }
    if (sizeof($arrAdminCookieDtls) <= 0 && $ADM_CONST_MODULE == "client")
    {
        header("Location: " . $ADMIN_SITE_ROOT);
        exit;
    }
     if (sizeof($arrAdminCookieDtls) <= 0 && $ADM_CONST_MODULE == "reports")
    {
        header("Location: " . $ADMIN_SITE_ROOT);
        exit;
    }  

    if (sizeof($arrAdminCookieDtls) > 0)
    {
        $strCK_admin_id = $arrAdminCookieDtls[0]; //admin_id
        $strCk_email_address = $arrAdminCookieDtls[1]; //email_address
        //$strCk_password = $arrAdminCookieDtls[2]; //password

                
//        try
//        {
//            $arrAuthUserResult = isAuthenticAdminUser($strCk_email_address, $strCk_password, $objDataHelper);
//            //if (!is_array($arrAuthUserResult) && sizeof($arrAuthUserResult) <= 0)
//            if (!is_array($arrAuthUserResult) && !empty($arrAuthUserResult))
//            {
//                header("Location: " . $ADMIN_SITE_ROOT);
//                exit;
//            }
//        }
//        catch (Exception $a)
//        {
//            throw new Exception("adm_authorize.inc.php : Error in isAuthenticAdminUser" . $a->getMessage(), 161);
//        }
        
         if (!isset($_SESSION[ADM_SESSION_NAME]))                
        {
            header("Location: ".$ADMIN_SITE_ROOT);
            exit;
        }
        else
        {
            try
            {
                $arrAdminUserDetls = getAdminUserDetailsByID($strCk_email_address, $objDataHelper);
            }
            catch (Exception $a)
            {
                throw new Exception("adm_authorize.inc.php : Error in getAdminUserDetailsByID" . $a->getMessage(), 161);
            }

            $flag = strtolower(trim($arrAdminUserDetls[0]['flag']));
            $strAdminFlag = $flag;  
            if ($strAdminFlag == "ca")
            {
                $strAdminClientl_Id = strtolower(trim($arrAdminUserDetls[0]['client_id']));
                $strAdminPartner_Id = strtolower(trim($arrAdminUserDetls[0]['partner_id']));
            }
        }
    }
}
catch (Exception $e)
{
    $ErrorHandler->RaiseError($_SERVER["PHP_SELF"], $e->getCode(), $e->getMessage(), true);
}
?>
