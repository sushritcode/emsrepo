<?php
require_once('../includes/global.inc.php');
require_once(CLASSES_PATH.'error.inc.php');
require_once(DBS_PATH.'DataHelper.php');
require_once(DBS_PATH.'objDataHelper.php');
require_once(INCLUDES_PATH.'cm_authfunc.inc.php');
$CONST_MODULE = 'profile';
$CONST_PAGEID = 'My Profile';
require_once(INCLUDES_PATH.'cm_authorize.inc.php');
require_once(INCLUDES_PATH.'profile_function.inc.php');
require_once(INCLUDES_PATH.'db_common_function.inc.php');

try
{
    session_start();
    $strSuccessMsg = $_SESSION['txtSuccessMsg'];
    $arrUserDetails = getUserDetailsByUserId($strCK_user_id, $objDataHelper);
}
catch (Exception $a)
{
    throw new Exception("index.php : getUserDetailsByUserId : Error in populating User Details.".$a->getMessage(), 541);
}

if (isset($_POST['btnSubmit']))
{
    header("Location:".$SITE_ROOT.'profile/editprofile.php');
}
?>
<!DOCTYPE html>
<html lang="en">
    <!-- Head content Area -->
    <head>
<?php include (INCLUDES_PATH.'head.php'); ?>    
    </head>
    <!-- Head content Area -->

    <body>

        <!-- Navigation Bar, After Login Menu &  Product Logo -->
        <?php include (INCLUDES_PATH.'navigation.php'); ?>    
        <!-- Navigation Bar, After Login Menu &  Product Logo -->

        <!-- Main content Area -->
        <div class="container">
            <!-- Main hero unit for a primary marketing message or call to action -->

            <!-- Middle content Area -->
            <div class="row">
                <div class="span12">
                    <h2>My Profile</h2>
                </div>

                <div class="span12">
                
                    <?php if ($strSuccessMsg): ?>
                        <div class="alert alert-success"> 
                            <span><?php echo $strSuccessMsg; session_unset(); ?></span><br /> 
                        </div></br>
                    <?php endif; ?>
                    <hr>
                    <form name="Profile" method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
                        <table class="" width="60%">
                            <tr class=""><td class="tBold">Nick Name<span class="colon">:&nbsp;</span></td><td><?php if ($arrUserDetails[0]['nick_name'])
                        echo $arrUserDetails[0]['nick_name'];else
                        echo '--'; ?></td></tr><tr><td class="pB20"></td></tr>
                            <tr><td class="tBold">First Name<span class="colon">:&nbsp;</span></td><td><?php if ($arrUserDetails[0]['first_name'])
                        echo $arrUserDetails[0]['first_name'];else
                        echo '--'; ?></td></tr><tr><td class="pB20"></td></tr>
                            <tr><td class="tBold">Last Name<span class="colon">:&nbsp;</span></td><td><?php if ($arrUserDetails[0]['last_name'])
                        echo $arrUserDetails[0]['last_name'];else
                        echo '--'; ?></td></tr><tr><td class="pB20"></td></tr>
                            <tr><td class="tBold">Email Address<span class="colon">:&nbsp;</span></td><td><?php if ($arrUserDetails[0]['email_address'])
                        echo $arrUserDetails[0]['email_address'];else
                        echo '--'; ?></td></tr><tr><td class="pB20"></td></tr>
                            <tr><td class="tBold">Password<span class="colon">:&nbsp;</span></td><td><a href="<?php echo $SITE_ROOT.'profile/changepassword.php' ?>">Change Password</a></td></tr>
                            <tr><td class="pB20"></td></tr>
                            <tr><td class="tBold">Country Name<span class="colon">:&nbsp;</span></td><td><?php if ($arrUserDetails[0]['country_name'])
                        echo $arrUserDetails[0]['country_name'];else
                        echo '--'; ?></td></tr><tr><td class="pB20"></td></tr>
                            <tr><td class="tBold">Timezone<span class="colon">:&nbsp;</span></td><td><?php if ($arrUserDetails[0]['timezones'])
                        echo $arrUserDetails[0]['timezones']." ".$arrUserDetails[0]['gmt'];else
                        echo '--'; ?></td></tr><tr><td class="pB20"></td></tr>
                            <tr><td class="tBold">Mobile<span class="colon">:&nbsp;</span></td><td><?php if ($arrUserDetails[0]['mobile_number'])
                        echo "+".$arrUserDetails[0]['idd_code']."-".$arrUserDetails[0]['mobile_number'];else
                        echo '--'; ?></td></tr><tr><td class="pB20"></td></tr>
                            <tr><td><button name="btnSubmit" class="btn btn-primary" type="submit">Edit</button></td></tr>
                        </table>
                    </form>
                    <hr>
                </div>

                <!-- RHS : Start -->

                <!-- RHS : End -->

            </div>
            <!-- Middle content Area -->
        </div>
        <!-- Main content Area -->

        <!-- Footer content Area -->
<?php include (INCLUDES_PATH.'footer.php'); ?>
        <!-- Footer content Area -->

        <!-- java script  -->
        <?php include (INCLUDES_PATH.'jsinclude.php'); ?>
        <!-- java script  -->

    </body>
</html>
