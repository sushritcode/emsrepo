<?php
require_once('ses.php');
require_once('class.phpmailer.php');
include("class.smtp.php"); 

function sendMail($arrUserName, $arrCC, $arrBCC, $REPLYTO, $Subject, $Message, $strFrom)
{
    if (RELAY_EMAIL_FLAG == 1)
    { 
    	$strFrom = CONST_NOREPLY_EID;
    	$ses = new SimpleEmailService('AKIAJCIHVAPBUWH4UP7A', '9gVenJ9KYL42nHHhwqCksW43gaDLK7gc8VuRhwMZ');
	$m   = new SimpleEmailServiceMessage();
    	
	for($cnt=0;$cnt<count($arrUserName);$cnt++)
		$m->addTo($arrUserName[$cnt]);
	for($cnt=0;$cnt<count($arrCC);$cnt++)
		if(trim($arrCC[$cnt]) !="")
			$m->addCC($arrCC[$cnt]);
	for($cnt=0;$cnt<count($arrBCC);$cnt++)
		if(trim($arrBCC[$cnt]) !="")	
			$m->addBCC($arrBCC[$cnt]);

	$m->setFrom($strFrom);
	$m->setSubject($Subject);
	$m->setMessageFromString($Message);
	$result  = $ses->sendEmail($m);

        if (($result['MessageId'] != '') && ($result['RequestId'] != ''))
        {
        	return true;
        }
	else
        {
        	return false;
        }
    }
    if (RELAY_EMAIL_FLAG == 2)
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
	            //$From = trim($strFrom);
	            $Sub = $Subject;
	            $Msg = $Message;
	                    
	            $mail = new PHPMailer(); // create a new object
	            $mail->IsSMTP(); 
	            $mail->SMTPDebug = 0; // debugging: 1 = errors and messages, 2 = messages only
	            $mail->SMTPAuth = true; 
	            $mail->SMTPSecure = 'ssl'; 
	            $mail->Host = "n1plcpnl0006.prod.ams1.secureserver.net"; // SMTP server
	            $mail->Port = 465; 
	            $mail->Username = "letsmeet@eletsmeet.com";             // SMTP account username
	            $mail->Password = "m@iL3TsM3et";  
	            $mail->SetFrom("letsmeet@eletsmeet.com", "LetsMeet");
	            $mail->AddReplyTo("letsmeet@eletsmeet.com", "LetsMeet");
	            
	            $mail->IsHTML(false);
	            $mail->Subject = $Sub;
	            $mail->Body = $Msg;
	            $mail->AddAddress($To);
	            if(!$mail->Send())
	            {
	                    //echo "Mailer Error: " . $mail->ErrorInfo;
	                    return false;
	            }
            }
            return true;
    }
    else
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
}

function scheduleMail($strCk_email_address, $strCk_nick_name, $arrInviteesEmail, $scheduleID, $gmTime, $localTime, $timezone, $meeting_title, $scheduleType)
{
    createMeetingMail($scheduleID, $gmTime, $localTime, $timezone, $meeting_title, $strCk_email_address, $strCk_nick_name);
    createInviteesMeetingMail($scheduleID, $gmTime, $localTime, $timezone, $meeting_title, $strCk_email_address, $strCk_nick_name, $arrInviteesEmail);
    if ($scheduleType == "N")
    {
        //sendInstantMsg($strCk_email_address, $arrInviteesEmail, $scheduleID, $gmTime, $localTime, $timezone, $meeting_title);
    }
}

function createMeetingMail($scheduleID, $gmTime, $localTime, $timezone, $meeting_title, $strCk_email_address, $strCk_nick_name)
{
    $arrMailUsers[0] = $strCk_email_address;
    $arrCCUsers[0] = "";
    $arrBCCUsers[0] = "";
    $strUserEmail = $strCk_email_address;
    $meeting_dtm = dateFormat($gmTime, $localTime, $timezone);
    $PSCD = md5($scheduleID . ":" . $strCk_email_address . ":" . SECRET_KEY);

    $jmData = "SCID=" . $scheduleID . "&EMID=" . $strCk_email_address . "&PSCD=" . $PSCD . "&PRID=" . PRID;
    $jmUrl = JMX_API_ROOT . "?" . $jmData;
    $Subject = $meeting_title;
    $Message = "
Welcome,\n
Meeting Title : " . $meeting_title . "\n
Meeting Date : " . $meeting_dtm . "\n
Called By : ".$strCk_nick_name." (".$strCk_email_address.")\n
To join meeting, click here : " . $jmUrl . "\n
Thank you \n\n\n".md5(microtime());

    if (!sendMail($arrMailUsers, $arrCCUsers, $arrBCCUsers, $strUserEmail, $Subject, $Message, $strUserEmail))
    {
        throw new Exception("mail_common_function.inc.php : Unable to send Create Meeting Mail", 501);
    }
}

function createInviteesMeetingMail($scheduleID, $gmTime, $localTime, $timezone, $meeting_title, $strCk_email_address, $strCk_nick_name, $arrInviteesEmail)
{
    $arrInviteesEmail = explode(",", $arrInviteesEmail);
    $meeting_dtm = dateFormat($gmTime, $localTime, $timezone);
    for ($i = 0; $i < sizeof($arrInviteesEmail); $i++)
    {
        $arrMailUsers[0] = $arrInviteesEmail[$i];
        $arrCCUsers[0] = "";
        $arrBCCUsers[0] = "";
        $strUserEmail = $strCk_email_address;

        $PSCD = md5($scheduleID . ":" . $arrInviteesEmail[$i] . ":" . SECRET_KEY);

        $jmData = "SCID=" . $scheduleID . "&EMID=" . $arrInviteesEmail[$i] . "&PSCD=" . $PSCD . "&PRID=" . PRID;
        $jmUrl = JMX_API_ROOT . "?" . $jmData;
        //$pmUrl = JMX_API_ROOT . "pm.php?" . $jmData;
        $Subject = $meeting_title;
        $Message = "Welcome,\n
Meeting Title : " . $meeting_title . "\n
Meeting Date : " . $meeting_dtm . "\n
Called By : ".$strCk_nick_name." (".$strCk_email_address.")\n
To join this meeting or to accept/decline the invitation, click here : " . $jmUrl . "\n
Thank you \n\n\n".md5(microtime());
        /* $Message = "Welcome,\n
          Meeting Title : " . $meeting_title . "\n
          Meeting Date : " . $meeting_dtm . "\n
          To join meeting, click here : " . $jmUrl . "\n
          To accept or decline the invitation, click here : " . $pmUrl . "\n
          Thank you"; */
       
        if (!sendMail($arrMailUsers, $arrCCUsers, $arrBCCUsers, $strUserEmail, $Subject, $Message, $strUserEmail))
        {
            throw new Exception("mail_common_function.inc.php : Unable to send Join Meeting Mail", 501);
        }
    }
}

/* -----------------------------------------------------------------------------
  Function Name : cancelMeetingMail
  Purpose       : To send the cancel meeting mail to all the invite of meeting
  Parameters    : meeting_title, meeting_timestamp, creator_email, meeting_hosted_by and array of invitee emails n nickname
  Returns       :
  Calls         : sendMail
  Called By     : cancelschedule.php
  Author        : Mitesh Shah
  Created  on   : 25-May-2015
  Modified By   :
  Modified on   :
  ------------------------------------------------------------------------------ */

function cancelMeetingMail($meeting_title, $meeting_timestamp, $creator_email, $meeting_hosted_by, $arrInviteesEmailnNick)
{
    $arrInviteesEmailnNick = explode(",", $arrInviteesEmailnNick);
    for ($i = 0; $i < sizeof($arrInviteesEmailnNick); $i++)
    {
        $arrInviteesDtls = explode("#", $arrInviteesEmailnNick[$i]);
        $strInviteesEmail = $arrInviteesDtls[0];
        $strInviteesNick = $arrInviteesDtls[1];

        $arrMailUsers[0] = $strInviteesEmail;
        $arrCCUsers[0] = "";
        $arrBCCUsers[0] = "";
        $strUserEmail = $creator_email;

        $Subject = $meeting_title;
        $Message = "Hello " . $strInviteesNick . ",\n
Meeting Title : " . $meeting_title . "\n
Meeting Date : " . $meeting_timestamp . "\n
Called By : ".$meeting_hosted_by." (".$creator_email.")\n
Sorry, meeting has been cancelled.\n
Thanks and Regards,\n
" . $meeting_hosted_by ."\n\n\n".md5(microtime());

        if (!sendMail($arrMailUsers, $arrCCUsers, $arrBCCUsers, $strUserEmail, $Subject, $Message, $strUserEmail))
        {
            throw new Exception("mail_common_function.inc.php : Unable to send Cancel Meeting Mail", 501);
        }
    }
}

/* -----------------------------------------------------------------------------
  Function Name : resetPasswordMail
  Purpose       : To send reset password link mail.
  Parameters    : email_address $=time_stamp, CONST_SUPPORT_EID
  Returns       :
  Calls         : sendMail
  Called By     :
  Author        : Mitesh Shah
  Created  on   : 25-May-2015
  Modified By   :
  Modified on   :
  ------------------------------------------------------------------------------ */

function resetPasswordMail($email_address, $email_data, $CONST_NOREPLY_EID)
{
    $arrMailUsers[0] = $email_address;
    $arrCCUsers[0] = "";
    $arrBCCUsers[0] = "";

    //$token = md5($email_address . ":" . $time_stamp . ":" . SECRET_KEY);
    //$rpmData = "em=" . $email_address . "&ms=" . $time_stamp . "&cd=" . $token;

    $resetPwdLink = INT_API_ROOT . "reset/resetpassword.php?" . urlencode($email_data);
    $Subject = 'You requested a new '. CONST_PRODUCT_NAME .' Password';
    $Message = "
Hello,\n
You recently asked to reset your " . CONST_PRODUCT_NAME . " password.\nTo complete your request, please follow this link:\n" . $resetPwdLink . "\n
Thank you.\n
Please note: This link is valid upto 24 hours from your requested time\n\n\n\n".md5(microtime());;
    if (!sendMail($arrMailUsers, $arrCCUsers, $arrBCCUsers, $CONST_NOREPLY_EID, $Subject, $Message, $CONST_NOREPLY_EID))
    {
        throw new Exception("mail_common_function.inc.php : Unable to send Reset Password Mail", 501);
    }
}

function sendInstantMsg($strCk_email_address, $arrInviteesEmail, $scheduleID, $gmTime, $localTime, $timezone, $meeting_title)
{
    $d1 = "category=user&recipient=online&domain=quadridge.com";
    $d2 = "&subject=" . $meeting_title;
    $d = $d1 . $d2;
    $userAgent = $_SERVER['HTTP_USER_AGENT'];
    $arrInviteesEmail = explode(",", $arrInviteesEmail);
    for ($i = 0; $i < sizeof($arrInviteesEmail); $i++)
    {
        if (strpos($arrInviteesEmail[$i], "quadridge"))
        {
            $inviteesEmail = explode("@", $arrInviteesEmail[$i]);
            $PSCD = md5($scheduleID . ":" . $arrInviteesEmail[$i] . ":" . SECRET_KEY);
            $jmData = "SCID=" . $scheduleID . "&EMID=" . $arrInviteesEmail[$i] . "&PSCD=" . $PSCD . "&PRID=" . PRID;
            $jmUrl = JMX_API_ROOT . "?" . $jmData;
            $meeting_dtm = dateFormat($gmTime, $localTime, $timezone);
            $url = INSTANT_MSG . $d . "&value=" . $inviteesEmail[0] . "&msg=" . urlencode("Meeting Title : " . $meeting_title . "\nMeeting Date : " . $meeting_dtm . "\nJoin Meeting : " . $jmUrl . "\n\nFrom - " . $strCk_email_address);
            $curlurl = curl_init($url);
            curl_setopt($curlurl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_USERAGENT, $userAgent);
            curl_setopt($curlurl, CURLOPT_HEADER, false);
            curl_setopt($curlurl, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($curlurl, CURLOPT_SSL_VERIFYPEER, false);
            $strReturnValue = curl_exec($curlurl);
            curl_close($curlurl);
        }
    }
}

/* -----------------------------------------------------------------------------
  Function Name : meetingReminderMail
  Purpose       : To send the 24hrs and 1hrs reminder of meeting
  Parameters    : schedule_id, meeting_subject, meeting_title, meeting_timestamp, $meeting_hosted_by, support_email and array of invitee emails n nickname
  Returns       :
  Calls         : sendMail
  Called By     :
  Author        : Mitesh Shah
  Created  on   : 25-May-2015
  Modified By   :
  Modified on   :
  ------------------------------------------------------------------------------ */

function meetingReminderMail($schedule_id, $meeting_subject, $meeting_title, $meeting_timestamp, $meeting_hosted_by, $CONST_SUPPORT_EID, $arrInviteesEmailnNick)
{
    $arrInviteesEmailnNick = explode(",", $arrInviteesEmailnNick);
    for ($i = 0; $i < sizeof($arrInviteesEmailnNick); $i++)
    {
        $arrInviteesDtls = explode("#", $arrInviteesEmailnNick[$i]);
        $strInviteesEmail = $arrInviteesDtls[0];
        $strInviteesNick = $arrInviteesDtls[1];

        $PSCD = md5($schedule_id . ":" . $strInviteesEmail . ":" . SECRET_KEY);
        $jmData = "SCID=" . $schedule_id . "&EMID=" . $strInviteesEmail . "&PSCD=" . $PSCD . "&PRID=" . PRID;
        $jmUrl = JMX_API_ROOT . "?" . $jmData;
        $pmUrl = JMX_API_ROOT . "pm.php?" . $jmData;

        $arrMailUsers[0] = $strInviteesEmail;
        $arrCCUsers[0] = "";
        $arrBCCUsers[0] = "";
        $Subject = $meeting_subject;
        $Message = "Hello " . $strInviteesNick . ",\n
Hosted By : " . $meeting_hosted_by . "\n
This is reminder for the following meeting:\n
Meeting Title : " . $meeting_title . "\n
Meeting Date : " . $meeting_timestamp . "\n
To join this meeting or to accept/decline the invitation, click here : " . $jmUrl . "\n
Thanks and Regards,
EMeet Support Team.";
        if (!sendMail($arrMailUsers, $arrCCUsers, $arrBCCUsers, $CONST_SUPPORT_EID, $Subject, $Message, $CONST_SUPPORT_EID))
        {
            throw new Exception("mail_common_function.inc.php : Unable to send Reminder Meeting Mail", 501);
        }
    }
}

/* -----------------------------------------------------------------------------
  Function Name : sendSubscriptionSupportMail
  Purpose       : To send subscription error mail to support.
  Parameters    : user_id, order_id, order_status, CONST_SUPPORT_EID
  Returns       :
  Calls         : sendMail
  Called By     :
  Author        : Mitesh Shah
  Created  on   : 25-May-2015
  Modified By   :
  Modified on   :
  ------------------------------------------------------------------------------ */

function sendSubscriptionSupportMail($user_id, $order_id, $order_status, $CONST_SUPPORT_EID)
{
    $arrMailUsers[0] = $CONST_SUPPORT_EID;
    $arrCCUsers[0] = "mitesh.shah@quadridge.com";
    $arrBCCUsers[0] = "";
    if ($order_status == '7')
    {
        $Subject = 'Transcation successful but subscription failed';
        $Message = "
Hello,\n
Payment is successful for user(" . $user_id . "), but subscription is not completed.\n
Order id is " . $order_id . ".\n
Thank you.\n";
    }
    if ($order_status == '8')
    {
        $Subject = 'Found order status other than pending and completed';
        $Message = "
Hello,\n
Transaction is not successful for user(" . $user_id . "), due to improper order status.\n
Order id is " . $order_id . ".\n
Thank you.\n";
    }
    if ($order_status == '2')
    {
        $Subject = 'Transaction is waiting for manual verification';
        $Message = "
Hello,\n
Transaction is waiting for manual verification for user(" . $user_id . "), need to be authorized by EBS.\n
Order id is " . $order_id . ".\n
Thank you.\n";
    }
    if ($order_status == '6')
    {
        $Subject = 'Unauthorized access of database';
        $Message = "
Hello,\n
Order details has been accessed illegaly from databse for order(" . $order_id . ").\n
Thank you.\n";
    }
    if (!sendMail($arrMailUsers, $arrCCUsers, $arrBCCUsers, $CONST_SUPPORT_EID, $Subject, $Message, $CONST_SUPPORT_EID))
    {
        throw new Exception("mail_common_function.inc.php : Unable to send Subscription Mail", 501);
    }
}

/* -----------------------------------------------------------------------------
  Function Name : sendSubscriptionSuccesstMail
  Purpose       : To send subscription success mail to user.
  Parameters    : email_address, order_id, payment_id, plan_name, plan_amount, order_date, CONST_SUPPORT_EID
  Returns       :
  Calls         : sendMail
  Called By     :
  Author        : Mitesh Shah
  Created  on   : 25-May-2015
  Modified By   :
  Modified on   :
  ------------------------------------------------------------------------------ */

function sendSubscriptionSuccesstMail($email_address, $order_id, $payment_id, $plan_name, $plan_amount, $total_amount, $order_date, $end_date, $quantity, $CONST_SUPPORT_EID)
{
    $arrMailUsers[0] = $email_address;
    $arrCCUsers[0] = "";
    $arrBCCUsers[0] = "";
    $Subject = 'Your EMeet Subscription Information';
    $Message = "
Hello,\n
Your transaction is successful, below are the details of your subscription:\n
Order Id - " . $order_id . "\n
Order Date - " . $order_date . "\n
Payment Id - " . $payment_id . "\n
Plan Name - " . $plan_name . "\n
Plan Amount - " . $plan_amount . "\n
Month - " . $quantity . "\n
Total Amount - " . $total_amount . "\n
valid till - " . $end_date . "\n
Thank you.\n";
    if (!sendMail($arrMailUsers, $arrCCUsers, $arrBCCUsers, $CONST_SUPPORT_EID, $Subject, $Message, $CONST_SUPPORT_EID))
    {
        throw new Exception("mail_common_function.inc.php : Unable to send Reset Password Mail", 501);
    }
}

/* -----------------------------------------------------------------------------
  Function Name : signUpActivationMail
  Purpose       : To send account activation mail to user.
  Parameters    : email_address, user_id, nick_name, gm_date, CONST_SUPPORT_EID
  Returns       :
  Calls         : sendMail
  Called By     :
 Author        : Mitesh Shah
  Created  on   : 25-May-2015
  Modified By   :
  Modified on   :
  ------------------------------------------------------------------------------ */

function signUpActivationMail($email_address, $user_id, $nick_name, $gmtDate, $CONST_NOREPLY_EID)
{
    $arrMailUsers[0] = $email_address;
    $arrCCUsers[0] = "";
    $arrBCCUsers[0] = "";

    $token = md5($email_address . ":" . strtotime($gmtDate) . ":" . REG_SECRET_KEY);
    $rpmData = "userid=" . $user_id . "&ms=" . strtotime($gmtDate) . "&cd=" . $token;
    $accountActvnLink = INT_API_ROOT . "signup/account_activation.php?" . urlencode($rpmData);
    $Subject = ''. CONST_PRODUCT_NAME .'  Account activation link';
    $Message = "
Hi " . $nick_name . ",\n
Thank you for registering at '. CONST_PRODUCT_NAME .' . To activate your account, please follow this link:\n
" . $accountActvnLink . "\n
Thank you.\n
Please note: This link is valid upto 3 days from your registration time.";
    if (!sendMail($arrMailUsers, $arrCCUsers, $arrBCCUsers, $CONST_NOREPLY_EID, $Subject, $Message, $CONST_NOREPLY_EID))
    {
        throw new Exception("mail_common_function.inc.php : Unable to send Account Activation Mail", 501);
    }
}

/* -----------------------------------------------------------------------------
  Function Name : newUserNotificationMail
  Purpose       : To send new account creation mail to admin.
  Parameters    : email_address, user_id, nick_name, CONST_SUPPORT_EID
  Returns       :
  Calls         : sendMail
  Called By     :
  Author        : Mitesh Shah
  Created  on   : 25-May-2015
  Modified By   :
  Modified on   :
  ------------------------------------------------------------------------------ */

function newUserNotificationMail($adminEmailId, $userNickName, $userEmailId, $siteRoot, $CONST_NOREPLY_EID)
{
    $arrMailUsers[0] = $adminEmailId;
    $arrCCUsers[0] = "";
    $arrBCCUsers[0] = "";

    $Subject = 'Assign subscription to newly created user - ' . $userEmailId . '';
    $Message = "
Hi,\n
Assign subscription to below user:\n
Nick Name - " . $userNickName . "\n
Email Address - " . $userEmailId . "\n  
Admin Site Url - " . $siteRoot . "admin/\n
Thank you.\n";

    if (!sendMail($arrMailUsers, $arrCCUsers, $arrBCCUsers, $CONST_NOREPLY_EID, $Subject, $Message, $CONST_NOREPLY_EID))
    {
        throw new Exception("mail_common_function.inc.php : Unable to send new user notification Mail", 501);
    }
}


/* -----------------------------------------------------------------------------
  Function Name : resetClientPasswordMail
  Purpose       : To send reset password link mail for Client.
  Parameters    : email_address $email_data, $client Site Root, CONST_SUPPORT_EID
  Returns       :
  Calls         : sendMail
  Called By     :
  Author        : Mitesh Shah
  Created  on   : 25-May-2015
  Modified By   :
  Modified on   :
  ------------------------------------------------------------------------------ */

function resetClientPasswordMail($email_address, $email_data, $CLIENT_SITE_ROOT, $CONST_NOREPLY_EID)
{ 
    $arrMailUsers[0] = $email_address;
    $arrCCUsers[0] = "";
    $arrBCCUsers[0] = "";
       
    $resetPwdLink = $CLIENT_SITE_ROOT . "reset/resetpassword.php?".urlencode($email_data);
    
    $Subject = 'You requested a new '.CONST_PRODUCT_NAME.' password';
    $Message = "Hello,\n
You recently asked to reset your ".CONST_PRODUCT_NAME." password. To complete your request, please follow this link:\n".$resetPwdLink."\n
Thank you.\n
Please note: This link is valid upto 24 hours from your requested time";
    if (!sendMail($arrMailUsers, $arrCCUsers, $arrBCCUsers, $CONST_NOREPLY_EID, $Subject, $Message, $CONST_NOREPLY_EID))
    {
        throw new Exception("mail_common_function.inc.php : Unable to send Reset Password Mail", 501);
    }
}