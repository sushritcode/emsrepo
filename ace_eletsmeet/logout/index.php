<?php
require_once('../includes/global.inc.php');
session_start();
unset($_SESSION[USER_SESSION_NAME]);
//header("Location:".$SITE_ROOT.'login/');
header("Location:".$SITE_ROOT);