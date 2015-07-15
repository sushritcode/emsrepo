<?php

function sendMail($arrUserName, $arrCC, $arrBCC, $REPLYTO, $Subject, $Message, $strFrom)
{
    global $objErr;
    if (sizeof($arrUserName) == 0 or strlen(trim($Subject)) == 0 or strlen(trim($Message)) == 0)
    {
        $objErr->setErrorCode(201);
        return;
    }
    for ($intCnt = 0; $intCnt < sizeof($arrCC); $intCnt++)
    {
        if ($intCnt > 0)
        {
            $CC = $CC . ", " . $arrCC[$intCnt];
        }
        else
        {
            $CC = $arrCC[$intCnt];
        }
    }
    for ($intCnt = 0; $intCnt < sizeof($arrBCC); $intCnt++)
    {
        if ($intCnt > 0)
        {
            $BCC = $BCC . ", " . $arrBCC[$intCnt];
        }
        else
        {
            $BCC = $arrBCC[$intCnt];
        }
    }
    for ($intCnt = 0; $intCnt < sizeof($arrUserName); $intCnt++)
    {
        $To = $arrUserName[$intCnt];
        $mailheader = "From: " . trim($strFrom) . "\n";
        $mailheader .= "Content-Type: text/plain; charset=iso-8859-1\n";
        $mailheader .= "Reply-To: \n";
        if ($intCnt == 0)
        {
            $mailheader .= "Cc: " . $CC . "\n";
            $mailheader .= "Bcc: " . $BCC . "\n";
        }
        $retFlag = mail($To, $Subject, $Message, $mailheader, "-f" . $strFrom);
        if ($objErr->Errno > 0)
        {
            $objErr->setErrorCode(203);
            return false;
        }
    }
    return true;
}

/* -----------------------------------------------------------------------------
  Function Name : planSubscriptionMail
  Purpose       : To send plan subscription mail to user.
  Parameters    : email_address, nick_name, plan_name, CONST_SUPPORT_EID
  Returns       :
  Calls         : sendMail
  Called By     :
  Author        : 
  Created  on   : 02-April-2013
  Modified By   :
  Modified on   :
  ------------------------------------------------------------------------------ */

function planSubscriptionMail($email_address, $nick_name, $plan_name, $CONST_SUPPORT_EID)
{ 
    $arrMailUsers[0] = $email_address;
    $arrCCUsers[0] = "";
    $arrBCCUsers[0] = "";
    
    $Subject = 'Subscription is assigned to you';
    $Message = "
Hi " .$nick_name . ",\n
A new subscription plan '" . $plan_name . "' is assigned to you.\n
Thank you.";
    if (!sendMail($arrMailUsers, $arrCCUsers, $arrBCCUsers, $CONST_SUPPORT_EID, $Subject, $Message, $CONST_SUPPORT_EID))
    {
        throw new Exception("mail_common_function.inc.php : Unable to send subscription Mail", 501);
    }
}

/* -----------------------------------------------------------------------------
  Function Name : adminEmailChangeMail
  Purpose       : To send email change mail to admin.
  Parameters    : oldEmailAddress, newEmailAddress, siteRoot, adminSiteRoot, CONST_SUPPORT_EID
  Returns       :
  Calls         : sendMail
  Called By     : profile/changemail.php
  Author        : 
  Created  on   : 03-April-2013
  Modified By   :
  Modified on   :
  ------------------------------------------------------------------------------ */

function adminEmailChangeMail($oldEmailAddress, $newEmailAddress, $siteRoot, $adminSiteRoot, $CONST_NOREPLY_EID)
{ 
    $arrMailUsers[0] = $oldEmailAddress;
    $arrCCUsers[0] = "";
    $arrBCCUsers[0] = "";
    
    $Subject = 'Admin ID has been changed';
    $Message = "
Hi \n
Below is your changed ID details:\n
Old ID - ".$oldEmailAddress."\n
New ID - ".$newEmailAddress."\n
Change Time - ".GM_DATE."\n
Site Url - ".$siteRoot."\n
Admin Site Url - ".$adminSiteRoot."\n
Ip address - ".$_SERVER['REMOTE_ADDR']."\n\n
Thank you.";
    if (!sendMail($arrMailUsers, $arrCCUsers, $arrBCCUsers, $CONST_NOREPLY_EID, $Subject, $Message, $CONST_NOREPLY_EID))
    {
        throw new Exception("mail_common_function.inc.php : Unable to send change email address mail", 501);
    }
}

/* -----------------------------------------------------------------------------
  Function Name : resetPasswordMail
  Purpose       : To send reset password link mail.
  Parameters    : email_address $email_data, $adminSiteRoot, CONST_SUPPORT_EID
  Returns       :
  Calls         : sendMail
  Called By     :
  Author        : 
  Created  on   : 11-March-2013
  Modified By   :
  Modified on   :
  ------------------------------------------------------------------------------ */

function resetPasswordMail($email_address, $email_data, $ADMIN_SITE_ROOT, $CONST_NOREPLY_EID)
{ 
    $arrMailUsers[0] = $email_address;
    $arrCCUsers[0] = "";
    $arrBCCUsers[0] = "";
   
    //$token = md5($email_address . ":" . $time_stamp . ":" . SECRET_KEY);
    //$rpmData = "em=" . $email_address . "&ms=" . $time_stamp . "&cd=" . $token;
    
    $resetPwdLink = $ADMIN_SITE_ROOT . "reset/resetpassword.php?".urlencode($email_data);
    $Subject = 'You requested a new Q.CONFERENCE password';
    $Message = "
Hello,\n
You recently asked to reset your Q.CONFERENCE password. To complete your request, please follow this link:\n".$resetPwdLink."\n
Thank you.\n
Please note: This link is valid upto 24 hours from your requested time";
    if (!sendMail($arrMailUsers, $arrCCUsers, $arrBCCUsers, $CONST_NOREPLY_EID, $Subject, $Message, $CONST_NOREPLY_EID))
    {
        throw new Exception("mail_common_function.inc.php : Unable to send Reset Password Mail", 501);
    }
}
?>
