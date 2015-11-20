<?php
require_once('../../includes/global.inc.php');
require_once('../includes/config.inc.php');
require_once(ADM_CLASSES_PATH . 'admin_error.inc.php');
require_once(DBS_PATH . 'DataHelper.php');
require_once(DBS_PATH . 'objDataHelper.php');
require_once(ADM_INCLUDES_PATH . 'adm_authfunc.inc.php');
$ADM_CONST_MODULE = 'profile';
$ADM_CONST_PAGEID = 'Change Password';
require_once(ADM_INCLUDES_PATH . 'adm_authorize.inc.php');
require_once(ADM_INCLUDES_PATH.'profile_function.inc.php');

try
{
    if (isset($_POST['pwd_submit']))
    {
        $errors = array();
        $old_password = trim($_POST['txtOldPassword']);
        $new_password = trim($_POST['txtNewPassword']);
        $cnf_password = trim($_POST['txtCnfPassword']);

        if ($old_password === '')
        {
            $errors[] = 'Please enter your Current Password.';
        }

        if ($new_password === '')
        {
            $errors[] = 'Please enter your New Password.';
        }
        else if (strlen($new_password) < 3 || strlen($new_password) > 15)
        {
            $errors[] = 'New Password must consist of minimum 3 and maximum 15 characters.';
        }
        else if (!preg_match('/^[a-zA-Z0-9$!@#%&]+$/', $new_password))
        {
            $errors[] = 'Password must be alphanumeric with some special characters [$!@#%&*].';
        }

        if ($new_password != $cnf_password)
        {
            $errors[] = 'New Password and Confirm New Password do not match.';
        }

        if (strlen($new_password) > 0 && strlen($old_password) > 0)
        {
            if ($new_password == $old_password)
            {
                $errors[] = 'New Password and Current Password is same, please enter different New Password.';
            }
        }

        if (sizeof($errors) == 0)
        {
            try
            {
                $arrUpdPwdResult = updateAdminPassword($strCk_email_address, MD5($old_password), MD5($new_password), $objDataHelper);
            }
            catch (Exception $a)
            {
                throw new Exception("changepassword.php : updateUserPassword : Error while Changing Password" . $a->getMessage(), 613);
            }

            $strDB_Status = trim($arrUpdPwdResult[0]['@STATUS']);
            $strDB_Admin_Id = trim($arrUpdPwdResult[0]['@ADMIN_ID']);
            $strDB_Email = trim($arrUpdPwdResult[0]['@EMAIL']);

            switch ($strDB_Status)
            {
                case "1" :
                    $success = "Password Changed Successfully.";
                    $redirectFlag = 1;
                    break;
                case "2" :
                    $errors[] = "Current Password invalid, Please re-enter.";
                    break;
                case "3" :
                    $errors[] = "New Password and Current Password is same, please enter different New Password.";
                    break;
                default:
                    $errors[] = "Error in changing password, Please try after some time.";
            }

            if ($redirectFlag == 1 && $strDB_Status == 1)
            {
                setcookie(ADM_COOKIE_NAME,"",time()-36000,"/");
                setAdminUserCookie($strDB_Admin_Id, $strDB_Email, MD5($new_password));
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

               <div class="span12">
                    <div class="fL"><h3>Change Password</h3></div>
                </div> 

                <div class="span12"><hr>

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
                            <div class="frm-fields tBold">Current Password<span class="required">&nbsp;*</span><span class="colon">:&nbsp;&nbsp;</span></div>
                            <div><input type="password" name="txtOldPassword" placeholder="Current Password" maxlength="50" class="span3" id="nickname"></div>
                            <div class="frm-fields tBold">New Password<span class="required">&nbsp;*</span><span class="colon">:&nbsp;&nbsp;</span></div>
                            <div><input type="password" name="txtNewPassword" placeholder="New Password" maxlength="50" class="span3" id=""></div>
                            <div class="frm-fields tBold">Confirm New Password<span class="required">&nbsp;*</span><span class="colon">:&nbsp;&nbsp;</span></div>
                            <div><input type="password" name="txtCnfPassword" placeholder="Confirm New Password" maxlength="50" class="span3" id=""></div></br>
                            <button name="pwd_submit" class="btn btn-primary" type="submit">Submit</button>
                            <button name="btnCancel" class="btn btn-primary mL10" type="submit">Cancel</button>
                        </form>
                    <?php }
                    else
                    { ?>
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
