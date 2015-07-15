<?php
require_once('../../includes/global.inc.php');
require_once('../includes/config.inc.php');
require_once(CLIENT_CLASSES_PATH . 'client_error.inc.php');
require_once(DBS_PATH . 'DataHelper.php');
require_once(DBS_PATH . 'objDataHelper.php');
require_once(CLIENT_INCLUDES_PATH . 'client_authfunc.inc.php');
$CLIENT_CONST_MODULE = 'clprofile';
$CLIENT_CONST_PAGEID = 'My Profile';
require_once(CLIENT_INCLUDES_PATH . 'client_authorize.inc.php');
require_once(CLIENT_INCLUDES_PATH . 'client_db_function.inc.php');

try
{
    $arrClientDetls = getClientDetailsByID($strCk_email_address, $objDataHelper);
}
catch (Exception $a)
{
    throw new Exception("adm_authorize.inc.php : Error in getAdminUserDetailsByID" . $a->getMessage(), 161);
}

$db_clientname = $arrClientDetls[0]['client_name'];
$db_clientemail = $arrClientDetls[0]['client_email_address'];
$db_clientlastlogin = $arrClientDetls[0]['client_lastlogin_dtm'];
?>
<!DOCTYPE html>
<html lang="en">
    <!-- Head content Area -->
    <head>
        <?php include (CLIENT_INCLUDES_PATH . 'head.php'); ?>    
    </head>
    <!-- Head content Area -->

    <body>

        <!-- Navigation Bar, After Login Menu &  Product Logo -->
        <?php include (CLIENT_INCLUDES_PATH . 'navigation.php'); ?>    
        <!-- Navigation Bar, After Login Menu &  Product Logo -->

        <!-- Main content Area -->
        <div class="container">
            <!-- Main hero unit for a primary marketing message or call to action -->

            <!-- Middle content Area -->
            <div class="row">
                 
                <div class="span12">
                    <div class="fL"><h3>My Profile</h3></div>
                </div>

                <div class="span12"><hr>                            
                        <table class="" width="60%">
                            <tr>
                                <td class="tBold">Name<span class="colon">&nbsp;:&nbsp;</span></td>
                                <td><?php echo $db_clientname; ?></td>
                            </tr>
                            <tr><td class="pB20"></td></tr>
                            <tr>
                                <td class="tBold">Email Address<span class="colon">&nbsp;:&nbsp;</span></td>
                                <td><?php echo $db_clientemail; ?></td>
                            </tr>
                            <tr><td class="pB20"></td></tr>
                             <tr>
                                <td class="tBold">Password<span class="colon">&nbsp;:&nbsp;</span></td>
                                <td><a href="<?php echo $CLIENT_SITE_ROOT . 'profile/changepassword.php' ?>">Change Password</a></td>
                            </tr>
                            <tr><td class="pB20"></td></tr>
                            <tr>
                                <td class="tBold">Last Login<span class="colon">&nbsp;:&nbsp;</span></td>
                                <td><?php echo $db_clientlastlogin; ?></td>
                            </tr>
                          </table>
                     <hr>
                </div>

            </div>
            <!-- Middle content Area -->
        </div>
        <!-- Main content Area -->

        <!-- Footer content Area -->
        <?php include (CLIENT_INCLUDES_PATH . 'footer.php'); ?>
        <!-- Footer content Area -->

        <!-- java script  -->
        <?php include (CLIENT_INCLUDES_PATH . 'jsinclude.php'); ?>
        <!-- java script  -->

    </body>
</html>
