<?php
require_once('../includes/global.inc.php');
//destroy cookie..
//setcookie(USER_COOKIE_NAME,"",time()-36000,"/");
session_start();
unset($_SESSION[USER_SESSION_NAME]);
header("Location: ".$SITE_ROOT);
?>