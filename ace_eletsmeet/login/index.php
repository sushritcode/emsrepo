<?php
require_once('../includes/global.inc.php');
require_once(CLASSES_PATH.'error.inc.php');
require_once(DBS_PATH.'DataHelper.php');
require_once(DBS_PATH.'objDataHelper.php');
require_once(INCLUDES_PATH.'cm_authfunc.inc.php');
$CONST_MODULE = 'login';
$CONST_PAGEID = 'Login Page';
require_once(INCLUDES_PATH.'cm_authorize.inc.php');
require_once(INCLUDES_PATH.'common_function.inc.php');
require_once(INCLUDES_PATH.'profile_function.inc.php');

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
            $arrAuthUserResult = isAuthenticUser($signin_email, md5($signin_password), $objDataHelper);
        } catch (Exception $a) {
            throw new Exception("login.php : isAuthenticUser_API : Error in Authenticing User" . $a->getMessage(), 613);
        }

        if (is_array($arrAuthUserResult) && sizeof($arrAuthUserResult) > 0) {
            $user_id = $arrAuthUserResult[0]['user_id'];
            $email_address = $arrAuthUserResult[0]['email_address'];
            $client_id = $arrAuthUserResult[0]['client_id'];
            $nick_name =  $arrAuthUserResult[0]['nick_name']; // to be fetched from user details
            setLMUserSession($user_id, $email_address, $client_id, $nick_name);
            $arrUpdLastLoginDtls = updUserLastLoginDtls($email_address, $user_id, GM_DATE, $Login_IP_Address, $objDataHelper);
            $strReferer = "dashboard/";
            header("Location:" . $SITE_ROOT . $strReferer);
        } else {
            $strAction = $SITE_ROOT;
            $strErrorMsg = "Invalid information. Please try again.";
            $errEmailClass = 'has-error';
            $errPwdClass = 'has-error';
        }
        $errors[] = $strErrorMsg;
    }
}

//if (isset($_POST['forgot_email']))
if (isset($_POST['fgt_submit'])) {
    //$strEmail = trim($_POST['txtEmail']);
    $forgot_email = trim($_POST['forgot_email']);

    try {
        $arrIsValidEmailResult = isUserEmailAddressExists($forgot_email, $objDataHelper);
    } catch (Exception $a) {
        throw new Exception("index.php : isEmailIdExists : Error while Validating EmailId" . $a->getMessage(), 61333333);
    }

    if (is_array($arrIsValidEmailResult) && sizeof($arrIsValidEmailResult) > 0) {
        echo "<div id='msg'>yes</div>";
        $userId = $arrIsValidEmailResult[0]['user_id'];
        $email_address = $arrIsValidEmailResult[0]['email_address'];

        $currentTime = GM_DATE;
        $strTimeStamp = strtotime($currentTime);
        $Token = md5($email_address . ":" . $strTimeStamp . ":" . REG_SECRET_KEY);
        $ResetPwdData = "em=" . $email_address . "&ms=" . $strTimeStamp . "&cd=" . $Token;

        try {
            $arrPasswordRequestDtls = getPasswordRequestDtls($email_address, $objDataHelper);
            if (is_array($arrPasswordRequestDtls) && sizeof($arrPasswordRequestDtls) > 0) {
                try {
                    deletePasswordRequestDtls($email_address, $objDataHelper);
                } catch (Exception $e) {
                    throw new Exception("index.php : deleteRequestPwd : Error in deleting" . $a->getMessage(), 61333333);
                }
            }
            try {
                $insertPwd = addPasswordRequestDtls($userId, $email_address, $currentTime, $objDataHelper);
            } catch (Exception $e) {
                throw new Exception("index.php : addPwdRequestDtm : Error in adding pwdDetails" . $a->getMessage(), 61333333);
            }
        } catch (Exception $e) {
            throw new Exception("index.php : getRequestPwdDetails : Error in getting details" . $a->getMessage(), 61333333);
        }

//            try
//            {
//                resetPasswordMail($strEmail, $ResetPwdData, $CONST_NOREPLY_EID);
//            }
//            catch (Exception $e)
//            {
//                throw new Exception("index.php : resetPasswordMail : Error in password reset".$a->getMessage(), 61333333);
//            }
    } else {
        echo "<div id='msg'>no</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <!-- HEAD CONTENT AREA -->
<?php include (INCLUDES_PATH . 'head.php'); ?>
        <!-- HEAD CONTENT AREA -->

        <!-- CSS n JS CONTENT AREA -->
<?php include (INCLUDES_PATH . 'css_include.php'); ?>    
        <!-- CSS n JS CONTENT AREA -->
    </head>

    <body class="login-layout">
        <div class="main-container">
            <div class="main-content">
                <div class="row">
                    <div class="col-sm-10 col-sm-offset-1">
                        <div class="login-container">
                            <div class="center">
                                <img src='<?php echo IMG_PATH; ?>quadridge-logo-530-270.png' title="Quadridge Technologies">
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

                                            <form method="POST" action="<?php echo $SITE_ROOT . 'login/index.php'; //$_SERVER['PHP_SELF']; ?>" name="frmLogin">
                                            <?php if (count($errors)): ?>
                                                                                                <div class="alert alert-danger">
                                                <?php foreach ($errors as $error): ?>
                                                                                                        <span><?php echo $error; ?></span><br />    
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

                                            <!--                                        <div>
                                                                                            <a href="#" data-target="#signup-box" class="user-signup-link">I want to register<i class="ace-icon fa fa-arrow-right"></i></a>
                                                                                        </div>-->
                                        </div>
                                    </div><!-- /.widget-body -->
                                </div><!-- /.login-box -->

                                <div id="forgot-box" class="forgot-box widget-box no-border">
                                    <div class="widget-body">
                                        <div class="widget-main">
                                            <h4 class="header red lighter bigger">
                                                <i class="ace-icon fa fa-key"></i>&nbsp;Retrieve Password
                                            </h4>

                                            <div class="space-6"></div>
                                            <p>Enter your email and to receive instructions</p>

                                            <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>" name="frmFgtPwd">
                                                <fieldset>
                                                    <label class="block clearfix">
                                                        <span class="block input-icon input-icon-right">
                                                            <input type="email" class="form-control" placeholder="Email" name="forgot_email"/>
                                                            <i class="ace-icon fa fa-envelope"></i>
                                                        </span>
                                                    </label>

                                                    <div class="clearfix">
                                                        <button class="width-35 pull-right btn btn-sm btn-danger" name="fgt_submit">
                                                            <i class="ace-icon fa fa-lightbulb-o"></i>&nbsp;<span class="bigger-110">Send Me!</span>
                                                        </button>
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

                            <div class="navbar-fixed-top align-right">
                                <br />
                                &nbsp;
                                <a id="btn-login-dark" href="#">Dark</a>
                                &nbsp;
                                <span class="blue">/</span>
                                &nbsp;
                                <a id="btn-login-blur" href="#">Blur</a>
                                &nbsp;
                                <span class="blue">/</span>
                                &nbsp;
                                <a id="btn-login-light" href="#">Light</a>
                                &nbsp; &nbsp; &nbsp;
                            </div>
                        </div>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.main-content -->
        </div><!-- /.main-container -->

        <!-- JAVA SCRIPT -->
<?php include (INCLUDES_PATH . 'static_js_includes.php'); ?>  
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

            //you don't need this, just used for changing background
            jQuery(function ($) {
                $('#btn-login-dark').on('click', function (e) {
                    $('body').attr('class', 'login-layout');
                    $('#id-text2').attr('class', 'white');
                    $('#id-company-text').attr('class', 'blue');

                    e.preventDefault();
                });
                $('#btn-login-light').on('click', function (e) {
                    $('body').attr('class', 'login-layout light-login');
                    $('#id-text2').attr('class', 'grey');
                    $('#id-company-text').attr('class', 'blue');

                    e.preventDefault();
                });
                $('#btn-login-blur').on('click', function (e) {
                    $('body').attr('class', 'login-layout blur-login');
                    $('#id-text2').attr('class', 'white');
                    $('#id-company-text').attr('class', 'light-blue');

                    e.preventDefault();
                });
            });
        </script>

    </body>
</html>
