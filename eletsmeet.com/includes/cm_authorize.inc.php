<?php
try
{
    $arrUserCookieDtls = getUserSession();
    
    if (sizeof($arrUserCookieDtls) > 0 && $CONST_MODULE == "home")
    {
        header("Location: ".$SITE_ROOT.'schedule');
        exit;
    }
    
    if (sizeof($arrUserCookieDtls) <= 0 && $CONST_MODULE == "schedule")
    {
        header("Location: ".$SITE_ROOT);
        exit;
    }
    
    if (sizeof($arrUserCookieDtls) <= 0 && $CONST_MODULE == "meeting")
    {
        header("Location: ".$SITE_ROOT);
        exit;
    }
    
    if (sizeof($arrUserCookieDtls) <= 0 && $CONST_MODULE == "profile")
    {
        header("Location: ".$SITE_ROOT);
        exit;
    }
    
    if (sizeof($arrUserCookieDtls) <= 0 && $CONST_MODULE == "contact")
    {
	header("Location: ".$SITE_ROOT);
	exit;
    }
    
    if (is_array($arrUserCookieDtls) && sizeof($arrUserCookieDtls) > 0)        
    {
        $strCK_user_id              = $arrUserCookieDtls[0]; //user_id
        $strCk_email_address  = $arrUserCookieDtls[1]; //email_address
        $strCk_client_id             = $arrUserCookieDtls[2]; //client_id
        $strCk_nick_name        = $arrUserCookieDtls[3]; //nick_name
        
         if (!isset($_SESSION[USER_SESSION_NAME]))                
        {
            header("Location: ".$SITE_ROOT);
            exit;
        }
        else
        {
            try
            {
                $arrUserDetls = getUserDetailsByID($strCk_email_address, $objDataHelper);
            }
            catch (Exception $a)
            {
                throw new Exception("adm_authorize.inc.php : Error in getAdminUserDetailsByID" . $a->getMessage(), 161);
            }

            if (!empty($arrUserDetls))
            {
                $strSetClient_Logo_Flag = trim($arrUserDetls[0]['client_logo_flag']);
                $strSetClient_Logo = trim($arrUserDetls[0]['client_logo_url']);
                $strSetClient_Name = trim($arrUserDetls[0]['client_name']);
            }
            else
            {
                 header("Location: ".$SITE_ROOT);
                exit;
            }
        }
    }
}
catch (Exception $e)
{
    $ErrorHandler->RaiseError($_SERVER["PHP_SELF"], $e->getCode(), $e->getMessage(), true);
}
?>