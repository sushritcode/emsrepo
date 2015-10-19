<?php
require_once('../includes/global.inc.php');
require_once('includes/config.inc.php');
require_once(CLIENT_CLASSES_PATH . 'client_error.inc.php');
require_once(CLIENT_DBS_PATH . 'DataHelper.php');
require_once(CLIENT_DBS_PATH . 'objDataHelper.php');
require_once(CLIENT_INCLUDES_PATH . 'client_authfunc.inc.php');
$CLIENT_CONST_MODULE = 'cl_home';
$CLIENT_CONST_PAGEID = 'Home';
require_once(CLIENT_INCLUDES_PATH . 'client_authorize.inc.php');
require_once(CLIENT_INCLUDES_PATH . 'client_db_function.inc.php');
//require_once(CLIENT_INCLUDES_PATH . 'mail_common_function.inc.php');

$Login_IP_Address = $_SERVER['REMOTE_ADDR'];

if (isset($_POST['lgn_submit'])) {
    $signin_email = trim($_POST['signin_email']);
    $signin_password = trim($_POST['signin_password']);

    if (strlen(trim($signin_email)) <= 0) {
        $errors[] = 'Please enter your Email Address';
        //$errors['email'] = 'Please enter your Email Address';
        $errEmailClass = 'has-error';
    }

    if (strlen(trim($signin_password)) <= 0) {
        $errors[] = 'Please enter a Password';
        $errPwdClass = 'has-error';
    }

    if (sizeof($errors) == 0) {
        $errEmailClass = '';
        $errPwdClass = '';

        try {
            $arrAuthUserResult = isAuthenticClient($signin_email, md5($signin_password), $objDataHelper);
        } catch (Exception $a) {
            throw new Exception("login.php : isAuthenticUser_API : Error in Authenticing User" . $a->getMessage(), 613);
        }

        if (is_array($arrAuthUserResult) && sizeof($arrAuthUserResult) > 0) {
            $db_client_id = $arrAuthUserResult[0]['client_id'];
            $db_partner_id = $arrAuthUserResult[0]['partner_id'];
            $db_client_email_address = $arrAuthUserResult[0]['client_email_address'];
            $strRandomID = md5(microtime());
            setClientSession($strRandomID, $db_client_email_address);
            $arrUpdLastLoginDtls = updClientLastLoginDtls($db_client_id, $db_client_email_address, $strRandomID, GM_DATE, $Login_IP_Address, $objDataHelper);
            $strReferer = "dashboard/";
            header("Location:" . $CLIENT_SITE_ROOT . $strReferer);
        } else {
            $strAction = $SITE_ROOT;
            $strErrorMsg = "Invalid information. Please try again.";
            $errEmailClass = 'has-error';
            $errPwdClass = 'has-error';
        }
        $errors[] = $strErrorMsg;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <script type="text/javascript">
            var BASEURL = "<?php echo $CLIENT_SITE_ROOT; ?>";
        </script>
        <!-- HEAD CONTENT AREA -->
        <?php include (CLIENT_HEAD_INCLUDES_PATH); ?>
        <!-- HEAD CONTENT AREA -->

        <!-- CSS n JS CONTENT AREA -->
        <?php include (CLIENT_CSS_INCLUDES_PATH); ?>    
        <!-- CSS n JS CONTENT AREA -->
    </head>

    <body class="login-layout">
        <div id='ajax_loader' style="width: 100%; height: 100%; position: absolute; left: 0px; top: 0px; background: transparent none repeat scroll 0% 0%; z-index: 20000;display:none;">
            <img src="<?php echo CLIENT_IMG_PATH ?>loading.gif" style="position: relative; top: 50%; left: 50%;"></img>
        </div>

        <div class="main-container">
            <div class="main-content">
                <div class="row">
                    <div class="col-sm-10 col-sm-offset-1">
                        <div class="login-container">
                            <div class="center">
                                <img src='<?php echo CLIENT_IMG_PATH; ?>quadridge-logo-530-270.png' title="Quadridge Technologies">
                            </div>

                            <div class="space-6"></div>

                            <div class="position-relative">
                                <div id="login-box" class="login-box visible widget-box no-border">
                                    <div class="widget-body">
                                        <div class="widget-main">
                                            <h4 class="header blue lighter bigger">
                                                <i class="ace-icon fa fa-coffee green"></i>
                                                Please Enter Your Information
                                            </h4>

                                            <div class="space-6"></div>

                                            <form method="POST" action="<?php echo $CLIENT_SITE_ROOT; //$_SERVER['PHP_SELF'];  ?>" name="frmLogin">

                                                <?php if (count($errors)): ?>
                                                    <div class="alert alert-danger">
                                                        <?php foreach ($errors as $error): ?>
                                                            <?php echo $error; ?>
                                                        <?php endforeach; ?>
                                                    </div>
                                                <?php endif; ?>

                                                <fieldset>
                                                    <div class="form-group <?php echo $errEmailClass; ?>">
                                                        <label class="block clearfix">
                                                            <span class="block input-icon input-icon-right">
                                                                <input type="email" class="form-control" placeholder="Email Address" value="<?php echo $signin_email; ?>" name="signin_email" />
                                                                <i class="ace-icon fa fa-user"></i>
                                                            </span>
                                                        </label>
                                                        <!--                                                        <label class="control-label no-padding-right" for="inputError">Input with error</label>-->
                                                    </div>

                                                    <div class="form-group <?php echo $errPwdClass; ?>">
                                                        <label class="block clearfix">
                                                            <span class="block input-icon input-icon-right">
                                                                <input type="password" class="form-control" placeholder="Password" value="<?php echo $signin_password; ?>" name="signin_password"/>
                                                                <i class="ace-icon fa fa-lock"></i>
                                                            </span>
                                                        </label>
                                                    </div>

                                                    <div class="space"></div>

                                                    <div class="clearfix">
                                                        <button class="width-35 pull-right btn btn-sm btn-primary" name="lgn_submit">
                                                            <i class="ace-icon fa fa-key"></i>&nbsp;<span class="bigger-110">Login</span>
                                                        </button>
                                                    </div>
                                                    <div class="space-4"></div>
                                                </fieldset>
                                            </form>

                                        </div><!-- /.widget-main -->

                                        <div class="toolbar clearfix">
                                            <div>
                                                <a href="#" data-target="#forgot-box" class="forgot-password-link"><i class="ace-icon fa fa-arrow-left">&nbsp;</i>I forgot my password</a>
                                            </div>

                                            <!--  <div>
                                                <a href="#" data-target="#signup-box" class="user-signup-link">I want to register<i class="ace-icon fa fa-arrow-right"></i></a>
                                            </div> -->
                                        </div>
                                    </div><!-- /.widget-body -->
                                </div><!-- /.login-box -->

                                <div id="forgot-box" class="forgot-box widget-box no-border">
                                    <div class="widget-body">
                                        <div class="widget-main">
                                            <h4 class="header red lighter bigger">
                                                <i class="ace-icon fa fa-key"></i>&nbsp;Retrieve Password
                                            </h4>
                                            <div class="row" id="alert" style="display:none;">
                                                <div class="col-sm-12">
                                                    <div id="succ" class="col-sm-12 alert alert-block alert-success" style="display:none;">
                                                        <div class="ace-icon fa fa-bullhorn fa fa-check" style="font-weight: bold;">
                                                            <span id="successmsg"> </span>
                                                        </div>
                                                    </div>
                                                    <div id="err" class="alert alert-danger" style="display:none;">
                                                        <div class="ace-icon fa fa-bullhorn fa fa-check" style="font-weight: bold;">
                                                            <span id="errormsg"> </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="space-6"></div>
                                            <p>Enter your email and to receive instructions</p>

                                            <form name="frmFgtPwd">
                                                <fieldset>
                                                    <label class="block clearfix">
                                                        <span class="block input-icon input-icon-right">
                                                            <input type="email" class="form-control" placeholder="Email" name="forgot_email"/>
                                                            <i class="ace-icon fa fa-envelope"></i>
                                                        </span>
                                                    </label>

                                                    <div class="clearfix">
                                                        <input type="button" class="width-40 pull-right btn btn-sm btn-danger" name="SendPassowrd" value="Send Password" onClick="javascript:return forgotPwd('frmFgtPwd', 'forgotpwd');">
                                                        <!--button class="width-35 pull-right btn btn-sm btn-danger" name="fgt_submit" data-target="#forgot-box">
                                                            <i class="ace-icon fa fa-lightbulb-o"></i>&nbsp;<span class="bigger-110">Send Me!</span>
                                                        </button-->
                                                    </div>
                                                </fieldset>
                                            </form>
                                        </div><!-- /.widget-main -->

                                        <div class="toolbar center">
                                            <a href="#" data-target="#login-box" class="back-to-login-link">
                                                Back to login
                                                <i class="ace-icon fa fa-arrow-right"></i>
                                            </a>
                                        </div>
                                    </div><!-- /.widget-body -->
                                </div><!-- /.forgot-box -->

                                <div id="signup-box" class="signup-box widget-box no-border">
                                    <div class="widget-body">
                                        <div class="widget-main">
                                            <h4 class="header green lighter bigger">
                                                <i class="ace-icon fa fa-users blue"></i>
                                                New User Registration
                                            </h4>

                                            <div class="space-6"></div>
                                            <p> Enter your details to begin: </p>

                                            <form>
                                                <fieldset>
                                                    <label class="block clearfix">
                                                        <span class="block input-icon input-icon-right">
                                                            <input type="email" class="form-control" placeholder="Email" />
                                                            <i class="ace-icon fa fa-envelope"></i>
                                                        </span>
                                                    </label>

                                                    <label class="block clearfix">
                                                        <span class="block input-icon input-icon-right">
                                                            <input type="text" class="form-control" placeholder="Username" />
                                                            <i class="ace-icon fa fa-user"></i>
                                                        </span>
                                                    </label>

                                                    <label class="block clearfix">
                                                        <span class="block input-icon input-icon-right">
                                                            <input type="password" class="form-control" placeholder="Password" />
                                                            <i class="ace-icon fa fa-lock"></i>
                                                        </span>
                                                    </label>

                                                    <label class="block clearfix">
                                                        <span class="block input-icon input-icon-right">
                                                            <input type="password" class="form-control" placeholder="Repeat password" />
                                                            <i class="ace-icon fa fa-retweet"></i>
                                                        </span>
                                                    </label>

                                                    <label class="block">
                                                        <input type="checkbox" class="ace" />
                                                        <span class="lbl">
                                                            I accept the
                                                            <a href="#">User Agreement</a>
                                                        </span>
                                                    </label>

                                                    <div class="space-24"></div>

                                                    <div class="clearfix">
                                                        <button type="reset" class="width-30 pull-left btn btn-sm">
                                                            <i class="ace-icon fa fa-refresh"></i>
                                                            <span class="bigger-110">Reset</span>
                                                        </button>

                                                        <button type="button" class="width-65 pull-right btn btn-sm btn-success">
                                                            <span class="bigger-110">Register</span>

                                                            <i class="ace-icon fa fa-arrow-right icon-on-right"></i>
                                                        </button>
                                                    </div>
                                                </fieldset>
                                            </form>
                                        </div>

                                        <div class="toolbar center">
                                            <a href="#" data-target="#login-box" class="back-to-login-link">
                                                <i class="ace-icon fa fa-arrow-left"></i>
                                                Back to login
                                            </a>
                                        </div>
                                    </div><!-- /.widget-body -->
                                </div><!-- /.signup-box -->
                            </div><!-- /.position-relative -->


                        </div>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.main-content -->
        </div><!-- /.main-container -->

        <!-- JAVA SCRIPT -->
        <?php include (CLIENT_JS_INCLUDES_PATH . 'static_js_includes.php'); ?>  
        <!-- JAVA SCRIPT -->

        <!-- inline scripts related to this page -->
        <script type="text/javascript">
            jQuery(function ($) {
                $(document).on('click', '.toolbar a[data-target]', function (e) {
                    e.preventDefault();
                    var target = $(this).data('target');
                    $('.widget-box.visible').removeClass('visible');//hide others
                    $(target).addClass('visible');//show target
                });
            });
        </script>

    </body>
</html>
