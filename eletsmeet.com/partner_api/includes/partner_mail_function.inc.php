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

function sendInviteesMeetingMail($scheduleID, $gmTime, $localTime, $timezone, $meeting_title, $user_email_id, $inviteeEmail)
{
    $meeting_dtm = dateFormat($gmTime, $localTime, $timezone);
    $arrMailUsers[0] = $inviteeEmail;
    $arrCCUsers[0] = "";
    $arrBCCUsers[0] = "";
   // $strUserEmail = $user_email_id;
    $strUserEmail = CLIENT_FROM_EMAIL;

    $PSCD = md5($scheduleID . ":" . $inviteeEmail . ":" . SECRET_KEY);

    $jmData = "SCID=" . $scheduleID . "&EMID=" . $inviteeEmail . "&PSCD=" . $PSCD . "&PRID=" . PRID;
    $jmUrl = JMX_API_ROOT . "?" . $jmData;
    $Subject = $meeting_title;
    $Message = "
Welcome,\n
Meeting Title : " . $meeting_title . "\n
Meeting Date : " . $meeting_dtm . "\n
To join meeting, click here : " . $jmUrl . "\n
To accept or decline the invitation, click here : " . $pmUrl . "\n
Thank you";
    if (!sendMail($arrMailUsers, $arrCCUsers, $arrBCCUsers, $strUserEmail, $Subject, $Message, $strUserEmail))
    {
        throw new Exception("partner_mail_function.inc.php : Unable to send Invitees Meeting Mail", 501);
    }
}

/* -----------------------------------------------------------------------------
  Function Name : cancelScheduleMeetingMail
  Purpose       : To send the cancel meeting mail to all the invite of meeting
  Parameters    : meeting_title, meeting_timestamp, creator_email, meeting_hosted_by and array of invitee emails n nickname
  Returns       :
  Calls         : sendMail
  Called By     : cancelschedule.php
  Author        : Mitesh Shah
  Created  on   : 13-June-2012
  Modified By   :
  Modified on   :
  ------------------------------------------------------------------------------ */

function cancelScheduleMeetingMail($meeting_title, $meeting_timestamp, $creator_email, $meeting_hosted_by, $arrInviteesEmailnNick)
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
Sorry, meeting has been cancelled.\n
Thanks and Regards,\n
" . $meeting_hosted_by . "";

        if (!sendMail($arrMailUsers, $arrCCUsers, $arrBCCUsers, $strUserEmail, $Subject, $Message, $strUserEmail))
        {
            throw new Exception("mail_common_function.inc.php : Unable to send Cancel Meeting Mail", 501);
        }
    }
}
