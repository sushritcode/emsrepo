<?php
require_once('../../includes/global.inc.php');
require_once('../includes/config.inc.php');
require_once(ADM_CLASSES_PATH . 'admin_error.inc.php');
require_once(DBS_PATH . 'DataHelper.php');
require_once(DBS_PATH . 'objDataHelper.php');
require_once(ADM_INCLUDES_PATH . 'adm_authfunc.inc.php');
$CONST_MODULE = 'profile';
$CONST_PAGEID = 'My Profile';
require_once(ADM_INCLUDES_PATH . 'adm_authorize.inc.php');

try
{
    session_start();
    $strSuccessMsg = $_SESSION['txtSuccessMsg'];
}
catch (Exception $a)
{
    throw new Exception("index.php : getUserDetailsByUserId : Error in populating User Details." . $a->getMessage(), 541);
}

if (isset($_POST['btnSubmit']))
{
    header("Location:" . $SITE_ROOT . 'profile/editprofile.php');
}
?>
<!DOCTYPE html>
<html lang="en">
    <!-- Head content Area -->
    <head>
        <?php include (ADM_INCLUDES_PATH . 'head.php'); ?>    
    </head>
    <!-- Head content Area -->

    <body>

        <!-- Navigation Bar, After Login Menu &  Product Logo -->
        <?php include (ADM_INCLUDES_PATH . 'navigation.php'); ?>    
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
                
                    <?php if ($strSuccessMsg): ?>
                        <div class="alert alert-success"> 
                            <span><?php echo $strSuccessMsg;
                        session_unset(); ?></span><br /> 
                        </div></br>
                    <?php endif; ?>
                    
                    <form name="Profile" method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
                        <table class="" width="60%">
                            <tr>
                                <td class="tBold">Email Address<span class="colon">:&nbsp;&nbsp;</span></td>
                                <td><?php echo $strCk_email_address; ?></td>
                            </tr>
                            
                            <tr><td class="pB20"></td></tr>
                            
                            <tr>
                                <td class="tBold">Password<span class="colon">:&nbsp;&nbsp;</span></td>
                                <td><a href="<?php echo $ADMIN_SITE_ROOT . 'profile/changepassword.php' ?>">Change Password</a></td>
                            </tr>
                            
                            <tr><td class="pB20"></td></tr>
                            
                            
                            <tr><td class="pB20"></td></tr>
                            
                          </table>
                    </form>
                    <hr>
                </div>

            </div>
            <!-- Middle content Area -->
        </div>
        <!-- Main content Area -->

        <!-- Footer content Area -->
        <?php include (ADM_INCLUDES_PATH . 'footer.php'); ?>
        <!-- Footer content Area -->

        <!-- java script  -->
        <?php include (ADM_INCLUDES_PATH . 'jsinclude.php'); ?>
        <!-- java script  -->

    </body>
</html>
