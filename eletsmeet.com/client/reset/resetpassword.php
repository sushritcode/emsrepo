<?php
require_once('../../includes/global.inc.php');
require_once('../includes/config.inc.php');
require_once(CLIENT_CLASSES_PATH . 'client_error.inc.php');
require_once(DBS_PATH . 'DataHelper.php');
require_once(DBS_PATH . 'objDataHelper.php');
require_once(CLIENT_INCLUDES_PATH . 'client_authfunc.inc.php');
$CLIENT_CONST_MODULE = 'clreset';
$CLIENT_CONST_PAGEID = 'Reset Password';
//require_once(CLIENT_INCLUDES_PATH . 'client_authorize.inc.php');
require_once(CLIENT_INCLUDES_PATH . 'client_db_function.inc.php');

try
{
    $url = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    if (($pos = strpos($url, '?')) !== false)
    {
        $url = urldecode(substr($url, $pos + 1));
    }
    $elems_ar = explode('&', $url);
    for ($i = 0; $i < count($elems_ar); $i++)
    {
        list($key, $val) = explode('=', $elems_ar[$i]);
        $ret_ar[urldecode($key)] = urldecode($val);
    }

    $strEmail = $ret_ar["em"];
    $strTimeStamp = $ret_ar["ms"];
    $strToken = $ret_ar["cd"];
    $currentTime = time();
    $validTime = strtotime('+1 day', $strTimeStamp);

    if ($currentTime <= $validTime)
    {        
        try
        {
            $arrClientDetls = getClientDetailsByID($strEmail, $objDataHelper);
        }
        catch (Exception $a)
        {
            throw new Exception("adm_authorize.inc.php : Error in getAdminUserDetailsByID" . $a->getMessage(), 161);
        }

        $db_clientid = $arrClientDetls[0]['client_id'];

        $arrRequestPwdDetails = getRequestPwdDetails($db_clientid, $strEmail, $objDataHelper);
       
        if ($arrRequestPwdDetails)
        {
            $emailId = $arrRequestPwdDetails[0]['email_address'];
            $strRequestDateTime = $arrRequestPwdDetails[0]['request_datetime'];
            $strTimeStamp = strtotime($strRequestDateTime);
            $newToken = md5($strEmail . ":" . $strTimeStamp . ":" . REG_SECRET_KEY);
            //echo $emailId." ".$timeStamp." ".$newToken." ".$strToken; exit;

            if ($strToken == $newToken)
            {
                if (isset($_POST['pwd_submit']))
                {
                    $errors = array();
                    $msg = array();
                    $new_password = trim($_POST['txtNewPassword']);
                    $cnf_password = trim($_POST['txtCnfPassword']);

                    if ($new_password === '')
                    {
                        $errors[] = 'Please enter your New password.';
                    }
                    else if (strlen($new_password) < 3 || strlen($new_password) > 15)
                    {
                        $errors[] = 'New Password must consist of minimum 3 and maximum 15 characters.';
                    }
                    else if ($new_password != $cnf_password)
                    {
                        $errors[] = 'New Password and Confirm New Password do not match.';
                    }
                    if (sizeof($errors) == 0)
                    {
                        try
                        {
                            $arrUpdPwdResult = resetClientPassword($strEmail, MD5($new_password), $objDataHelper);
                            if ($arrUpdPwdResult)
                            {
                                $success = 'Password changed successfully. Click here to <a href="' . $CLIENT_SITE_ROOT . '">SIGN IN.</a>';
                                $deleteReqPwd = deleteRequestPwd($db_clientid, $strEmail, $objDataHelper);
                            }
                        }
                        catch (Exception $a)
                        {
                            throw new Exception("resetpassword.php : resetUserPassword : Error while Changing Password" . $a->getMessage(), 613);
                        }
                    }
                }
            }
            else
            {
                $msg[] = 'Sorry, Your reset password link is invalid';
            }
        }
        else
        {
            $msg[] = 'Sorry, Your reset password link is invalid.';
        }
    }
    else
    {
        $msg[] = 'Sorry, Your password reset link has expired.';
    }

    if (isset($_POST['btnCancel']))
    {
        header("Location:" . $CLIENT_SITE_ROOT);
        exit;
    }
}
catch (Exception $e)
{
    $ErrorHandler->RaiseError($_SERVER["PHP_SELF"], $e->getCode(), $e->getMessage(), false);
}
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
                    <div class="fL"><h2>Reset Password</h2></div>
                </div>
                
                <div class="span12"><hr>
                    <div>
                        <?php if (count($errors)): ?>
                            <div class="alert alert-error"> 
                                <?php foreach ($errors as $error): ?>
                                    <span><?php echo $error; ?></span><br />    
                                <?php endforeach; ?>
                            </div></br>
                        <?php endif; ?>
                        <?php if (count($msg)): ?>
                            <?php foreach ($msg as $msg): ?>
                                <div class="alert alert-error"> 
                                    <span><?php echo $msg; ?></span><br /> 
                                <?php endforeach; ?>
                            </div></br>
                        <?php endif; ?>
                        <?php if ($success): ?>
                            <div class="alert alert-success"> 
                                <span><?php echo $success; ?></span><br /> 
                            </div></br>
                        <?php endif; ?>
                        <?php if (empty($msg) && empty($success))
                        { ?>
                            <form name="resetpwd" method="POST">
                                <div class="frm-fields tBold">New Password<span class="required">&nbsp;*</span><span class="colon">&nbsp;:&nbsp;</span></div>
                                <div><input type="password" name="txtNewPassword" placeholder="New Password" maxlength="50" class="span3" id=""></div>
                                <div class="mB10"></div>
                                
                                <div class="frm-fields tBold">Confirm New Password<span class="required">&nbsp;*</span><span class="colon">&nbsp;:&nbsp;</span></div>
                                <div><input type="password" name="txtCnfPassword" placeholder="Confirm New Password" maxlength="50" class="span3" id=""></div>
                                <div class="mB10"></div>
                            
                                <button name="pwd_submit" class="btn btn-primary" type="submit">Submit</button>
                                <button name="btnCancel" class="btn btn-primary mL10" type="submit">Cancel</button>
                            </form>
                        <?php } ?>
                    <hr>
                    </div>
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



