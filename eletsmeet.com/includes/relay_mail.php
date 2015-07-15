<?php
echo "1";
require_once('ses.php');
$ses = new SimpleEmailService('AKIAJCIHVAPBUWH4UP7A', '9gVenJ9KYL42nHHhwqCksW43gaDLK7gc8VuRhwMZ');
$m = new SimpleEmailServiceMessage();

$m->addTo("mitesh.shah@quadridge.com");
$m->setFrom("letsmeet@eletsmeet.com");
$m->setSubject('Hello, world!');
$m->setMessageFromString('This is the message body.');

print_r($ses->sendEmail($m));

exit;
echo "2";
$testMail  =  new Relay_Mail();
$result  = $testMail->sendRelayMail("mitesh.shah@quadridge.com","","","letsmeet@eletsmeet.com","Hello Sub","Hello Mess","letsmeet@eletsmeet.com");
print_r($result);
class Relay_Mail
{
    function sendRelayMail($To, $CC, $BCC, $ReplyTo, $Subject, $Message, $From)
    {
	  echo "4";
        $ses = new SimpleEmailService('AKIAJCIHVAPBUWH4UP7A', '9gVenJ9KYL42nHHhwqCksW43gaDLK7gc8VuRhwMZ');
        $m = new SimpleEmailServiceMessage();
                      
//        $arrReplyTo = explode(" ", $ReplyTo);
  //      print_r($arrReplyTo);
        echo $to = $To[0];
        echo $m->addTo($to);

        if (!empty($CC[0]))
        {
            $cc = $CC[0];
            $m->addCC($cc);
        }

        if (!empty($BCC[0]))
        {
            $bcc = $BCC[0];
            $m->addBCC($bcc);
        }

        $replyto = $arrReplyTo[0];
        $m->addReplyTo($replyto);

        $m->setReturnPath('letsmeet@eletsmeet.com');
        $m->setFrom($From);
        $m->setSubject($Subject);
        $m->setMessageFromString($Message);
        //$m->setMessageFromString($text, $html);
        $response = $ses->sendEmail($m);
        return $response;
    }

}

?>