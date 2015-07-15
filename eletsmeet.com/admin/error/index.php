<?php
require_once('../../includes/global.inc.php');
require_once('../includes/config.inc.php');
require_once(ADM_CLASSES_PATH.'admin_error.inc.php');
require_once(DBS_PATH.'DataHelper.php');
require_once(DBS_PATH.'objDataHelper.php');
$ADM_CONST_MODULE = 'error';
$ADM_CONST_PAGEID = 'Error Page';
require_once(ADM_INCLUDES_PATH.'adm_authfunc.inc.php');
require_once(ADM_INCLUDES_PATH.'adm_authorize.inc.php');

$strErrorMsg = stripslashes(trim($_REQUEST["txtErrorMsg"]));
$strErrorNo = $_REQUEST['txtErrorNo'];

$Message = "Sorry, some unexpected error occured. Please try again later.";
//$Message = $strErrorMsg . '<br/>'. $strErrorNo;
?>

<!DOCTYPE html>
<html lang="en">
    <!-- Head content Area -->
    <head>
        <?php include (ADM_INCLUDES_PATH.'head.php'); ?>
    </head>
    <!-- Head content Area -->

    <body>

        <!-- Navigation Bar, After Login Menu &  Product Logo -->
        <?php include (ADM_INCLUDES_PATH.'navigation.php'); ?>
        <!-- Navigation Bar, After Login Menu &  Product Logo -->

        <!-- Main content Area -->
        <div class="container">
            <!-- Main hero unit for a primary marketing message or call to action -->

            <!-- Middle content Area -->
            <div class="row">
               <div class="span12">
                    <div class="fL"><h3>Error..!</h3></div>
                </div>

                <div class="span12"><hr>

                    <div class="cB"></div>
                    <?php
                    if (strlen(trim($Message)) > 0)
                    {
                        echo "<div class='alert alert-error'>$Message</div>";
                    }
                    ?>

                </div>
                <!-- Right content Area -->

                <!-- Right content Area -->
            </div>
            <!-- Middle content Area -->
        </div>
        <!-- Main content Area -->

        <!-- Footer content Area -->
        <?php include (ADM_INCLUDES_PATH.'footer.php'); ?>
        <!-- Footer content Area -->

        <!-- java script  -->
        <?php include (ADM_INCLUDES_PATH.'jsinclude.php'); ?>
        <!-- java script  -->

    </body>
</html>
