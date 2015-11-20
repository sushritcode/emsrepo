<?php
require_once('../../includes/global.inc.php');
require_once('config.inc.php');
//destroy cookie..
//setcookie(ADM_COOKIE_NAME,"",time()-36000,"/");
//header("Location: ".$ADMIN_SITE_ROOT);
session_start();
unset($_SESSION[ADM_SESSION_NAME]);
header("Location: ".$ADMIN_SITE_ROOT);
?>