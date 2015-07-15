<?php
require_once('../includes/global.inc.php');
require_once('includes/config.inc.php');
require_once(ADM_CLASSES_PATH . 'admin_error.inc.php');
require_once(DBS_PATH . 'DataHelper.php');
require_once(DBS_PATH . 'objDataHelper.php');
require_once(ADM_INCLUDES_PATH . 'adm_authfunc.inc.php');
$ADM_CONST_MODULE = 'home';
$ADM_CONST_PAGEID = 'Home';
require_once(ADM_INCLUDES_PATH . 'adm_authorize.inc.php');
require_once(ADM_INCLUDES_PATH . 'adm_db_common_function.inc.php');
require_once(ADM_INCLUDES_PATH . 'mail_common_function.inc.php');

try
{
    $errors = array();
    if (isset($_POST['lgn_submit']))
    {

        $username = trim($_POST['txtUsername']);
        $password = MD5(trim($_POST['txtPassword']));

        if (strlen(trim($username)) <= 0)
        {
            $errors[] = 'Please enter your Email Address.';
        }

        if (strlen(trim($_POST['txtPassword'])) <= 0)
        {
            $errors[] = 'Please enter your Password.';
        }

        if (sizeof($errors) == 0)
        {
            try
            {
                $arrAuthUserResult = isAuthenticAdminUser($username, $password, $objDataHelper);
            }
            catch (Exception $a)
            {
                throw new Exception("index.php : isAuthenticUser_API : Error in Authenticing User" . $a->getMessage(), 613);
            }

            if (is_array($arrAuthUserResult) && sizeof($arrAuthUserResult) > 0)
            {
                $admin_id = $arrAuthUserResult[0]['admin_id'];
                $email_address = $arrAuthUserResult[0]['email_address'];
                //$password = $arrAuthUserResult[0]['password'];
                $flag = strtolower(trim($arrAuthUserResult[0]['flag']));
            
                //setAdminUserCookie($admin_id, $email_address, $password);
                setAdminUserSession($admin_id, $email_address);
                $arrUpdLastLoginDtls = updAdminLastLoginDtls($email_address, $objDataHelper);
                
                 if ($flag != "ca") 
                {    
                    $strReferer = "partner/";
                    header("Location:" . $ADMIN_SITE_ROOT . $strReferer);
                }
                else
                {
                    $strReferer = "reports/";
                    header("Location:" . $ADMIN_SITE_ROOT . $strReferer);
                }
            }
            else
            {
                $strAction = $SITE_ROOT;
                $strErrorMsg = "The Email Address or Password you entered is incorrect.";
            }
            $errors[] = $strErrorMsg;
        }
    }

    if (isset($_POST['txtEmail']))
    {
        $strEmail = trim($_POST['txtEmail']);
        try
        {
            $arrIsValidEmailResult = isEmailIdExists($strEmail, $objDataHelper);
        }
        catch (Exception $a)
        {
            throw new Exception("index.php : isEmailIdExists : Error while Validating EmailId" . $a->getMessage(), 61333333);
        }

        if (is_array($arrIsValidEmailResult) && sizeof($arrIsValidEmailResult) > 0)
        {
            $adminId = $arrIsValidEmailResult[0]['admin_id'];

            $currentTime = GM_DATE;
            $strTimeStamp = strtotime($currentTime);
            $Token = md5($strEmail . ":" . $strTimeStamp . ":" . REG_SECRET_KEY);
            $ResetPwdData = "em=" . $strEmail . "&ms=" . $strTimeStamp . "&cd=" . $Token;

            try
            {
                $arrRequestPwdDetails = getRequestPwdDetails($strEmail, $objDataHelper);
                if ($arrRequestPwdDetails)
                {
                    try
                    {
                        deleteRequestPwd($strEmail, $objDataHelper);
                    }
                    catch (Exception $e)
                    {
                        throw new Exception("index.php : deleteRequestPwd : Error in deleting" . $a->getMessage(), 61333333);
                    }
                }
                try
                {
                    $insertPwd = addPwdRequestDtm($adminId, $strEmail, $currentTime, $objDataHelper);
                }
                catch (Exception $e)
                {
                    throw new Exception("index.php : addPwdRequestDtm : Error in adding pwdDetails" . $a->getMessage(), 61333333);
                }
                echo "<div id='msg'>yes</div>";
            }
            catch (Exception $e)
            {
                throw new Exception("index.php : getRequestPwdDetails : Error in getting details" . $a->getMessage(), 61333333);
            }

            try
            {
                resetPasswordMail($strEmail, $ResetPwdData, $ADMIN_SITE_ROOT, $CONST_NOREPLY_EID);
            }
            catch (Exception $e)
            {
                throw new Exception("index.php : resetPasswordMail : Error in password reset" . $a->getMessage(), 61333333);
            }
        }
        else
        {
            echo "<div id='msg'>no</div>";
        }
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

            <!-- Top content Area -->
            <div class="row">
                <div class="span12 hidden-phone" style="position:relative">

                    <!-- Banner Images -->
                    <div id="myCarousel" class="carousel slide">
                        <div class="carousel-inner" style="height: 350px; margin: 75px 0 0 -15px;">
                            <div class="item active">
                                <img src="<?php echo IMG_PATH; ?>letsmeet-logo.png" alt="LetsMeet">
                            </div>
                            <div style="margin: 0 0 0 18px;">
                                <h1 style="margin-bottom: 0px;">GEOGRAPHY IS NOW HISTORY</h1>
                            </div>
                        </div>
                    </div>
                    <!-- Banner Images -->

                    <!-- Login Box -->
                    <div class="hubble_login" id="login-box">
                        <div class="s16 b mB10">Login</div>
                        <?php if (count($errors)): ?>
                            <div class="alert alert-error"> 
                                <?php foreach ($errors as $error): ?>
                                    <span><?php echo $error; ?></span><br />    
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                        <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>" name="frmLogin">
                            <div class="mB10"><input type="text" class="input-xclarge" value="<?php echo $username; ?>"  placeholder="Email Address" name="txtUsername" id="acpro_inp4"></div>
                            <div class="mB10"><input type="password" class="input-xclarge" placeholder="Password" name="txtPassword" id="acpro_inp4"></div>
                            <div class="mB10 brdbdGy pB15"><button href="#" class="btn btn-primary fL" name="lgn_submit">Login</button>&nbsp;&nbsp;&nbsp;<!--<span class="view fR">Forgot Password</span>--><div class="cB"></div></div>                            
                        </form>

                    </div>
                    <!-- Login Box -->

                    <!-- Forgot Password -->
                    <div id="layer"></div>
                    <div class="hubble_login" id="popup" style="display:none">
                        <div class="s16 b mB10">Forgot Password <img class="fR" id="close" border='0' title='Close' alt='Close' src='<?php echo IMG_PATH; ?>close_black.png'></div></br>
                        <div id="error-msg" class="alert alert-error" style="display: none;"></div><div id="success-msg" class="alert alert-success" style="display: none;"></div>
                        <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>" name="frmFgtPwd">
                            <div id="fgtpwd">
                                <div class="mB10"><input type="text" class="input-xclarge" name="txtEmail" id="txtEmail" placeholder="Email Address" value="" name="txtUsername" id="acpro_inp4"></div>
                                <div class="mB10 brdbdGy pB15"><button class="btn btn-primary fL" id="btnSubmit" name="btnSubmit">Submit</button><div class="cB"></div></div>             
                            </div>
                        </form>

                    </div>
                    <!-- Forgot Password -->

                </div>
            </div>
            <!-- Top content Area -->

            <!-- Middle content Area -->
            
            <!-- Middle content Area -->

            <!-- Bottom content Area -->

            <!-- Bottom content Area -->
        </div>
        <!-- Main content Area -->

        <!-- Footer content Area -->
        <?php include (ADM_INCLUDES_PATH . 'footer.php'); ?>    
        <!-- Footer content Area -->

        <!-- java script  -->
        <?php include (ADM_INCLUDES_PATH . 'jsinclude.php'); ?>
        <!-- java script  -->

        <!-- java script  1-->
        <script src="<?php echo ADM_JS_PATH; ?>show-popup.js"></script>

        <script type='text/javascript'>
            $(document).ready(function () {
                $('#error-msg').html('');
                
                $('.view').click(function() {
                    showPopup('#popup', '#layer');
                });
                
                $('#close').click(function() {
//                    hidePopup('<?php echo $ADM_CONST_MODULE?>', '#popup', '#layer');
                      hidePopup('#popup', '#layer');
                });
                
                $("#btnSubmit").click(function() {
                    var email = $("#txtEmail").val();
                    if($.trim(email).length == 0) {
                        $('#error-msg').css({"display":"block"});
                        $('#error-msg').html("Please enter your Email Address.");
                        return false;
                    }
                    else if (!/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(email)){
                        $('#error-msg').css({"display":"block"});
                        $('#error-msg').html('Please enter valid Email Address.');
                        return false;
                    }
                    else
                    {
                        $.post("index.php",{txtEmail:email} ,function(data)
                        {  
                            var $response=$(data);
                            var oneval = $response.filter('#msg').text();console.log(data);
                            if(oneval == 'yes') {
                                $('#success-msg').css({"display":"block"});
                                $('#error-msg').css({"display":"none"});
                                $('#success-msg').html('Please check your Email for password reset instructions.');
                                $('#fgtpwd').hide();
                            } 
                            else 
                            {
                                $('#error-msg').css({"display":"block"});
                                $('#error-msg').html('Invalid Email Address. Please re-enter.');
                            }
                        });
                        return false;
                    }
                });
            });
           
        </script>
        <!-- java script  1-->

    </body>
</html>