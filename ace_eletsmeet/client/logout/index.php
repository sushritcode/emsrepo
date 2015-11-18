<?php
require_once('../../includes/global.inc.php');
require_once('../config.inc.php');
session_start();
unset($_SESSION[CLIENT_SESSION_NAME]);
header("Location: ".$CLIENT_SITE_ROOT);
?>