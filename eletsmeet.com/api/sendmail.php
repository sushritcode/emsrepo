<?php

print "<br>".$mailToEmailAddress = urldecode($_GET['to']);
print "<br>".$mailSubject = urldecode($_GET['sub']);
print "<br>".$mailBody = urldecode($_GET['body']);
print "<br>".$mailFrom = urldecode($_GET['frm']);

$result  = mail($mailToEmailAddress,$mailSubject,$mailBody,"From: $mailFrom\n");
echo "mail done".$result;
echo "<br>".$mailToEmailAddress."=====".$mailSubject."====".$mailBody."======".$mailFrom;
?>
