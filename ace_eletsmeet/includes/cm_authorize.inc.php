<?php

try
{
    $arrUserSessionDetails = getUserSession();

    if (sizeof($arrUserSessionDetails) > 0 && $CONST_MODULE == "login")
    {
        header("Location:" . $SITE_ROOT . 'dashboard/');
        exit;
    }

    if (sizeof($arrUserSessionDetails) <= 0 && $CONST_MODULE == "dashboard")
    {
        header("Location:" . $SITE_ROOT);
        exit;
    }

    if (sizeof($arrUserSessionDetails) <= 0 && $CONST_MODULE == "schedule")
    {
        header("Location: " . $SITE_ROOT);
        exit;
    }

//    if (sizeof($arrUserSessionDetails) <= 0 && $CONST_MODULE == "meeting")
//    {
//        header("Location: ".$SITE_ROOT);
//        exit;
//    }
//    
//    if (sizeof($arrUserSessionDetails) <= 0 && $CONST_MODULE == "profile")
//    {
//        header("Location: ".$SITE_ROOT);
//        exit;
//    }
//    
//    if (sizeof($arrUserSessionDetails) <= 0 && $CONST_MODULE == "contact")
//    {
//	header("Location: ".$SITE_ROOT);
//	exit;
//    }


    if (is_array($arrUserSessionDetails) && sizeof($arrUserSessionDetails) > 0)
    {
        $strCK_user_id = $arrUserSessionDetails[0]; //user_id
        $strCk_user_email_address = $arrUserSessionDetails[1]; //email_address
        $strCk_user_client_id = $arrUserSessionDetails[2]; //client_id
        $strCk_user_nick_name = $arrUserSessionDetails[3]; //nick_name

        if (!isset($_SESSION[USER_SESSION_NAME]))
        {
            header("Location: " . $SITE_ROOT);
            exit;
        }
        else
        {
            try
            {
                $arrUserDetls = getUserLoginDetailsByID($strCk_user_email_address, $objDataHelper);
            }
            catch (Exception $a)
            {
                throw new Exception("adm_authorize.inc.php : Error in getAdminUserDetailsByID" . $a->getMessage(), 161);
            }

            if (!empty($arrUserDetls))
            {
                $strSetClient_Name = trim($arrUserDetls[0]['client_name']);
                $strSetClient_Logo_Flag = trim($arrUserDetls[0]['client_logo_flag']);
                $strSetClient_Logo = trim($arrUserDetls[0]['client_logo_url']);
            }
            else
            {
                header("Location: " . $SITE_ROOT);
                exit;
            }
        }
    }
}
catch (Exception $e)
{
    $ErrorHandler->RaiseError($_SERVER["PHP_SELF"], $e->getCode(), $e->getMessage(), true);
}
