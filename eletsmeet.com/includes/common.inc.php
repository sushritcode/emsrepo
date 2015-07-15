<?php
/* -----------------------------------------------------------------------------
  Function Name : sendSMS
  Purpose       :
  Parameters    :  $numbers - Destination no's (String), $msg - Text Message
  Returns       :  
  Calls         :  SMS API
  Called By     :  
 ------------------------------------------------------------------------------ */
function sendSMS($numbers,$msg)
{
    for($i=0;$i<sizeof($numbers);$i++) {
	  $numStr .= $numbers[$i].",";
	  $numEnc .= urlencode($numbers[$i]).",";
    }

    $numStr = substr($numStr, 0, strlen($numStr)-1);
    $numEnc = substr($numEnc, 0, strlen($numEnc)-1);

    $ts = strtotime(date("Y-m-d H:i:s"));
    $user = SMS_UNAME;
    $pwd = SMS_PASWD;
    $v = SMS_VER;
    $uno = urlencode($numbers);
    $ts.$user.$pwd.$numbers.$msg;
    $pass = md5($ts.$user.$pwd.$numStr.$msg);
    $msg = urlencode($msg);
    $url = SMS_URL."?ts=".$ts."&user=".$user."&pass=".$pass."&dest=".$numEnc."&mesg=".$msg."&v=".$v."";
    $curlurl = curl_init($url);
    curl_setopt($curlurl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curlurl, CURLOPT_HEADER, false);
    curl_setopt($curlurl, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($curlurl, CURLOPT_SSL_VERIFYPEER, false);
    $strReturnValue = curl_exec($curlurl);
    curl_close($curlurl);
    return $strReturnValue;
}
?>