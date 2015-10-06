<?php
require_once('../includes/global.inc.php');
require_once(CLASSES_PATH . 'error.inc.php');
require_once(DBS_PATH . 'DataHelper.php');
require_once(DBS_PATH . 'objDataHelper.php');
require_once(INCLUDES_PATH . 'profile_function.inc.php');
$CONST_MODULE = 'auth';
$CONST_PAGEID = 'Reset Password';

try
{
    $url = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    if (($pos = strpos($url, '?')) !== false)
        $url = urldecode(substr($url, $pos + 1));
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
        $arrRequestPwdDetails = getRequestPwdDetails($strEmail, $objDataHelper);

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
                            $arrUpdPwdResult = resetUserPassword($strEmail, MD5($new_password), $objDataHelper);
                            if ($arrUpdPwdResult)
                            {
                                $success = 'Password changed successfully. Click here to <a href="' . $SITE_ROOT . '">SIGN IN.</a>';
                                $deleteReqPwd = deleteRequestPwd($strEmail, $objDataHelper);
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
                $msg[] = 'Sorry, Your reset password link is invalid.';
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
        header("Location:" . $SITE_ROOT);
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
    <head>
        <!-- HEAD CONTENT AREA -->
        <?php include (INCLUDES_PATH.'head.php'); ?>
        <!-- HEAD CONTENT AREA -->

         <!-- CSS n JS CONTENT AREA -->
         <?php include (INCLUDES_PATH.'css_include.php'); ?>    
         <!-- CSS n JS CONTENT AREA -->
    </head>

   <body class="no-skin">
       
       <!-- TOP NAVIGATION BAR START -->
        <div id="navbar" class="navbar navbar-default">
            <script type="text/javascript">
                try 
                {
                    ace.settings.check('navbar', 'fixed')
                } 
                catch (e) 
                {
                }
            </script>
            <div class="navbar-container" id="navbar-container">
                

                <div class="navbar-header pull-left">
                    <a href="#" class="navbar-brand">
                        <small><i class="fa fa-leaf"></i>&nbsp;LetsMeet</small>
                    </a>
                </div>


                <div class="navbar-buttons navbar-header pull-right" role="navigation">
                    <ul class="nav ace-nav">           
                    </ul>
                </div>
            </div>
        </div>
        <!-- TOP NAVIGATION BAR END -->
        
         <!-- MAIN CONTAINER START -->
        <div class="main-container" id="main-container">
            <script type="text/javascript">
                try {
                    ace.settings.check('main-container', 'fixed')
                } catch (e) {
                }
            </script>

            <!-- SIDE NAVIGATION BAR START -->
<!--            <div id="sidebar" class="sidebar responsive">
                 <?php //include (INCLUDES_PATH.'sidebar_navigation.php'); ?>    
            </div>-->
            <!-- SIDE NAVIGATION BAR END -->
            
            <!-- MAIN CONTENT START -->
            <div class="main-content">
                <div class="main-content-inner">
                    
                    <!-- BREADCRUMBS N SEARCH BAR START -->
                    <div class="breadcrumbs" id="breadcrumbs">
                        <?php //include (INCLUDES_PATH.'breadcrumbs_navigation.php'); ?>   
                    </div>
                    <!-- BREADCRUMBS N SEARCH BAR END -->                    
                   
                    <!--  PAGE CONTENT START -->
                    <div class="page-content">
                        
                         <!-- SETTING CONTAINER START -->
                                  <!--IF NEEDED then WE ADD -->
                         <!-- SETTING CONTAINER END -->
                        
                        <!-- PAGE HEADER -->
                        <div class="page-header">
                            <h1>
                                Reset Password
                            </h1>
                        </div>
                        <!-- PAGE HEADER -->

                        <div class="row">
                            <div class="col-xs-12">
                                <!-- PAGE CONTENT START -->
                                
                                <?php if (count($errors)): ?>
                                    <div class="alert alert-block alert-danger">
                                        <?php foreach ($errors as $error): ?>
                                            <?php echo $error; ?>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                                <?php if (count($msg)): ?>
                                    <?php foreach ($msg as $msg): ?>
                                        <div class="alert alert-block alert-danger">
                                            <?php echo $msg; ?>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                                <?php if ($success): ?>
                                    <div class="alert alert-block alert-success"> 
                                        <?php echo $success; ?>
                                    </div>
                                <?php endif; ?>
                                <?php if (empty($msg) && empty($success))
                                { ?>
                                     <form class="form-horizontal" role="form" name="resetpwd" method="POST">
                                         
                                            <div class="form-group required">
                                                <label for="form-field-1-1" class="col-sm-2 control-label no-padding-right"> New Password </label>
                                                <div class="col-sm-3">
                                                    <input placeholder="Type your New Password" id="" class="form-control" name="txtNewPassword" type="password" maxlength="50">
                                                </div>
                                            </div>
                                         
                                            <div class="form-group required">
                                                <label for="form-field-1-1" class="col-sm-2 control-label no-padding-right"> Confirm New Password </label>
                                                <div class="col-sm-3">
                                                    <input placeholder="Type your Confirm New Password" id="" class="form-control" name="txtCnfPassword" type="password" maxlength="50">
                                                </div>
                                            </div>
                                         
                                              <div class="clearfix form-actions">
                                                    <div class="col-md-offset-4">
                                                            <button type="submit" class="btn btn-info" name="pwd_submit">
                                                                    <i class="ace-icon fa fa-check bigger-110"></i>
                                                                    Submit
                                                            </button>
                                                            &nbsp; &nbsp; &nbsp;
                                                            <button type="reset" class="btn" name="btnCancel">
                                                                    <i class="ace-icon fa fa-undo bigger-110"></i>
                                                                    Cancel
                                                            </button>
                                                    </div>
                                                  </div>

<!--                                            <button name="pwd_submit" class="btn btn-primary" type="submit">Submit</button>
                                            <button name="btnCancel" class="btn btn-primary mL10" type="submit">Cancel</button>-->
                                     </form>
                                <?php } ?>
                                
                                

                                
                        
                                

                                <!-- PAGE CONTENT END -->
                            </div>
                        </div> 
                       
                    </div>
                   <!-- PAGE CONTENT END -->
                    
                </div>
            </div>
            <!--  MAIN CONTENT END -->

            <!-- FOOTER START -->
            <div class="footer">
                <?php include (INCLUDES_PATH.'footer.php'); ?>  
            </div>
            <!-- FOOTER END -->

            <a href="#" id="btn-scroll-up" class="btn-scroll-up btn btn-sm btn-inverse">
                <i class="ace-icon fa fa-angle-double-up icon-only bigger-110"></i>
            </a>
            
        </div>
        <!-- MAIN CONTAINER END -->
        
        <!-- JAVA SCRIPT -->
            <?php include (INCLUDES_PATH.'static_js_includes.php'); ?>  
            <?php include (INCLUDES_PATH.'other_js_includes.php'); ?>  
        <!-- JAVA SCRIPT -->
       
    </body>
</html>
