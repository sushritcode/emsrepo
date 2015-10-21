<?php

try
{
    $arrClientCookieDtls = getClientSession();
    
    if ( is_array($arrClientCookieDtls) && sizeof($arrClientCookieDtls) > 0 && $CLIENT_CONST_MODULE == "cl_home") 
    {
        header("Location: " . $CLIENT_SITE_ROOT . 'dashboard/');
        exit;
    }
    
    if (sizeof($arrClientCookieDtls) <= 0 && $CLIENT_CONST_MODULE == "cl_dashboard")
    {
        header("Location: " . $CLIENT_SITE_ROOT);
        exit;
    }
    
    if (sizeof($arrClientCookieDtls) <= 0 && $CLIENT_CONST_MODULE == "cl_user")
    {
        header("Location: " . $CLIENT_SITE_ROOT);
        exit;
    }
    
     if (sizeof($arrClientCookieDtls) <= 0 && $CLIENT_CONST_MODULE == "cl_profile")
    {
        header("Location: " . $CLIENT_SITE_ROOT);
        exit;
    }
       
     if (sizeof($arrClientCookieDtls) <= 0 && $CLIENT_CONST_MODULE == "cl_reports")
    {
        header("Location: " . $CLIENT_SITE_ROOT);
        exit;
    }
    
    
    
    if (is_array($arrClientCookieDtls) && sizeof($arrClientCookieDtls) > 0)
    {
        $strCK_ID = $arrClientCookieDtls[0];
        $strCk_email_address = $arrClientCookieDtls[1];

        if (!isset($_SESSION[CLIENT_SESSION_NAME]))
        {
            header("Location: " . $CLIENT_SITE_ROOT);
            exit;
        }
        else
        {
            try
            {
                $arrClientDetls = getClientDetailsByID($strCk_email_address, $objDataHelper);
            }
            catch (Exception $a)
            {
                throw new Exception("adm_authorize.inc.php : Error in getAdminUserDetailsByID" . $a->getMessage(), 161);
            }

            if (!empty($arrClientDetls))
            {
                $strSetClient_ID = trim($arrClientDetls[0]['client_id']);
                $strSetPartner_ID = trim($arrClientDetls[0]['partner_id']);
                $strSetClient_Logo = trim($arrClientDetls[0]['client_logo_url']);
                $strSetClient_Name = trim($arrClientDetls[0]['client_name']);
                $strSetClient_Logo_Flag = trim($arrClientDetls[0]['client_logo_flag']);
            }
            else
            {
                header("Location: " . $CLIENT_SITE_ROOT);
                exit;
            }
        }
    }
}
catch (Exception $e)
{
    $ErrorHandler->RaiseError($_SERVER["PHP_SELF"], $e->getCode(), $e->getMessage(), true);
}