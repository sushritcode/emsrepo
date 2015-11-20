<?php
require_once('../../includes/global.inc.php');
require_once('../includes/config.inc.php');
require_once(ADM_CLASSES_PATH . 'admin_error.inc.php');
require_once(DBS_PATH . 'DataHelper.php');
require_once(DBS_PATH . 'objDataHelper.php');
require_once(ADM_INCLUDES_PATH . 'adm_authfunc.inc.php');
$ADM_CONST_MODULE = 'profile';
$ADM_CONST_PAGEID = 'Change Email';
require_once(ADM_INCLUDES_PATH . 'adm_authorize.inc.php');
require_once(ADM_INCLUDES_PATH . 'mail_common_function.inc.php');

try
{
    session_start();
    if (isset($_POST['pwd_submit']))
    {
        $errors = array();
        $new_email = trim($_POST['txtNewEmail']);

        if (strlen($new_email) <= 0)
        {
            $errors[] = 'Please enter your Email Address.';
        }
        else if (eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $new_email) != 1)
        {
            $errors[] = 'Please enter valid Email Address.';
        }

        if (sizeof($errors) == 0)
        {
            if ($strCk_email_address == $new_email)
            {
                $_SESSION['txtSuccessMsg'] = 'Your ID changed successfully.';
                header("Location:" . $ADMIN_SITE_ROOT . 'profile');
            }
            else
            {
                try
                {
                    $arrUpdEmailResult = updateAdminEmail($strCk_email_address, $new_email, $objDataHelper);
                }
                catch (Exception $a)
                {
                    throw new Exception("changeemail.php : updateAdminEmail : Error while Changing Email" . $a->getMessage(), 613);
                }

                if ($arrUpdEmailResult == 1)
                {
                    try
                    {
                        adminEmailChangeMail($strCk_email_address, $new_email, $SITE_ROOT, $ADMIN_SITE_ROOT, $CONST_NOREPLY_EID);
                    }
                    catch (Exception $e)
                    {
                        throw new Exception("changeemail.php : adminEmailChangeMail : Error in sending change of emaiid to admin" . $a->getMessage(), 61333333);
                    }
                        
                    setcookie("ckRoundTableAdminUser", "", time() - 36000, "/");
                    setAdminUserCookie($strCK_admin_id, $new_email, $strCk_password);
                    $_SESSION['txtSuccessMsg'] = 'Your ID changed successfully.';
                    header("Location:" . $ADMIN_SITE_ROOT . 'profile');
                }
                else
                {
                    $_SESSION['txtSuccessMsg'] = 'Error in updating.';
                    header("Location:" . $ADMIN_SITE_ROOT . 'profile');
                }
            }
        }
    }

    if (isset($_POST['btnCancel']))
    {
        header("Location:" . $ADMIN_SITE_ROOT . 'profile');
    }
}
catch (Exception $e)
{
    $ErrorHandler->RaiseError($_SERVER["PHP_SELF"], $e->getCode(), $e->getMessage(), true);
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

                <div class="span6">
                    <h1>Change ID</h1>
                </div>

                <!-- User setting option include start. -->
                <?php include (ADM_INCLUDES_PATH . 'user_setting.php'); ?>
                <!-- User setting option include start. -->

                <div class="span8 pR75"><hr>

                    <?php if (count($errors)): ?>
                        <div class="alert alert-error"> 
                            <?php foreach ($errors as $error): ?>
                                <span><?php echo $error; ?></span><br />    
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                    <?php if ($success): ?>
                        <div class="alert alert-success"> 
                            <span><?php echo $success; ?></span><br /> 
                        </div></br>
                    <?php endif; ?>
                    <?php if (empty($success))
                    { ?>
                        <form name="frmChangePassword" method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
                            <div class="frm-fields tBold">Current Email Address<span class="colon">:&nbsp;&nbsp;</span></div><div><p class="txt-email tBold"><?php echo $strCk_email_address; ?></p></div>
                            <div class="frm-fields tBold">New Email Address<span class="required">&nbsp;*</span><span class="colon">:&nbsp;&nbsp;</span></div>
                            <div><input type="text" name="txtNewEmail" maxlength="50" class="span3" id=""></div>
                            <button name="pwd_submit" class="btn btn-primary" type="submit">Submit</button>
                            <button name="btnCancel" class="btn btn-primary mL10" type="submit">Cancel</button>
                        </form>
                        <?php
                    }
                    else
                    {
                        ?>
                        <form name="frmChangePassword" method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
                            <button name="btnCancel" class="btn btn-primary" type="submit">Back</button>
                        </form>
                    <?php } ?>
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
