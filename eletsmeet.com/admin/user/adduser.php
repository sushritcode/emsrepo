<?php
require_once('../../includes/global.inc.php');
require_once('../includes/config.inc.php');
require_once(ADM_CLASSES_PATH . 'admin_error.inc.php');
require_once(DBS_PATH . 'DataHelper.php');
require_once(DBS_PATH . 'objDataHelper.php');
$ADM_CONST_MODULE = 'user';
$ADM_CONST_PAGEID = 'Add User';
require_once(ADM_INCLUDES_PATH . 'adm_authfunc.inc.php');
require_once(ADM_INCLUDES_PATH . 'adm_authorize.inc.php');
require_once(ADM_INCLUDES_PATH . 'user_function.inc.php');
require_once(ADM_INCLUDES_PATH . 'getTimezones.php');
require_once(ADM_INCLUDES_PATH . 'adm_db_common_function.inc.php');

try
{
    session_start();
    try
    {
        $arrCountryIddCode = getCountryDetails($objDataHelper);
    }
    catch (Exception $e)
    {
        throw new Exception("user.php : GetCountryDetails Failed : " . $e->getMessage(), 1128);
    }

    try
    {
        $arrTimeZone = getTimezoneList($objDataHelper);
    }
    catch (Exception $e)
    {
        throw new Exception("user.php : getTimezoneList Failed : " . $e->getMessage(), 1128);
    }

    try
    {
        $arrClientList = getClientList($objDataHelper);
    }
    catch (Exception $e)
    {
        throw new Exception("user.php : GetClientDetails Failed : " . $e->getMessage(), 1128);
    }


    if (isset($_POST['btnSubmit']))
    {
        $strClientInfo = trim($_POST['ClientName']);
        $strNickName = trim($_POST['txtNickName']);
        $strFirstName = trim($_POST['txtFirstName']);
        $strLastName = trim($_POST['txtLastName']);
        $strEmail = trim($_POST['txtEmail']);
        $strCountryName = trim($_POST['txtCountryName']);
        $strIddCode = trim($_POST['txtIddCode']);
        $strMobile = trim($_POST['txtMobileNumber']);
        $strPassword = trim($_POST['txtPassword']);
        $strCnfPassword = trim($_POST['txtCnfPassword']);
        $strTimeZone = trim($_POST['txtTimeZone']);
        $strIsAdmin = trim($_POST['isAdmin']);
        $_SESSION['timezone'] = $strTimeZone;

        if ($strClientInfo == '---')
        {
            $errors[] = 'Please select your Client Name.';
        }
        if (strlen($strEmail) <= 0)
        {
            $errors[] = 'Please enter your Email Address.';
        }
        else if (eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $strEmail) != 1)
        {
            $errors[] = 'Please enter valid Email Address.';
        }
        if (strlen($strPassword) <= 0)
        {
            $errors[] = 'Please enter your Password.';
        }
        else if (strlen($strPassword) < 3 || strlen($strPassword) > 15)
        {
            $errors[] = 'Password must consist of minimum 3 and maximum 15 characters.';
        }
        if ($strPassword != $strCnfPassword)
        {
            $errors[] = 'Password and Confirm Password do not match.';
        }
        if (strlen($strNickName) <= 0)
        {
            $errors[] = 'Please enter your Nick Name.';
        }
        else if (!preg_match('/^[a-z A-Z 0-9 . _]+$/', $strNickName))
        {
            $errors[] = 'Nick Name must be alphanumeric.';
        }
        if (strlen($strFirstName) <= 0)
        {
            $errors[] = 'Please enter your First Name.';
        }
        else if (!preg_match('/^[a-z A-Z 0-9 . _]+$/', $strFirstName))
        {
            $errors[] = 'First Name must be alphanumeric.';
        }
        if (strlen($strLastName) <= 0)
        {
            $errors[] = 'Please enter your Last Name.';
        }
        else if (!preg_match('/^[a-z A-Z 0-9 . _]+$/', $strLastName))
        {
            $errors[] = 'Last Name must be alphanumeric.';
        }
        if ($strCountryName == '---')
        {
            $errors[] = 'Please select your Country Name.';
        }
        if ($strTimeZone == '---')
        {
            $errors[] = 'Please select your Timezone.';
        }
        if (strlen($strMobile) <= 0)
        {
            $errors[] = 'Please enter your Mobile Number.';
        }
        else if (strlen($strMobile) != 0 && !ctype_digit($strMobile))
        {
            $errors[] = 'Only Digits are allowed in Mobile Number.';
        }
        
//        if (strlen($strIsAdmin) <= 0)
//        {
//            $errors[] = 'Please select Is Admin option.';
//        }
        
        if ($strClientInfo != '---')
        {
            $strClientDtls = explode(SEPARATOR, $strClientInfo);
            $strClientId = $strClientDtls[0];
            $strClientName = $strClientDtls[1];
            $strPartnerId = $strClientDtls[2];
        }
        if ($strCountryName != '---')
        {
            $strCountryName = explode(SEPARATOR, $strCountryName);
            $strCountryName = $strCountryName[1];
        }
        if ($strIsAdmin == '1')
        {
            $yes_status = 'checked';
        }
        else if ($strIsAdmin == '0')
        {
            $no_status = 'checked';
        }
        if (strlen($strEmail) != 0)
        {
            try
            {
                $isEmailExists = isUserEmailExists($strEmail, $objDataHelper);
                if ($isEmailExists[0]['@STATUS'] == 1)
                {
                    $errors[] = 'Email Address <b><font color=#006699>"' . $strEmail . '"</font></b> already exists.';
                }
            }
            catch (Exception $a)
            {
                throw new Exception("adduser.php : isUserEmailExists : Error in checking email address." . $a->getMessage(), 541);
            }
        }

        if (sizeof($errors) == 0)
        {
            if (strlen(trim($strIddCode)) > 0)
                $strIddCode = substr($strIddCode, 1);
            if (strlen(trim($strTimeZone)) > 0)
            {
                $strTimeZone = split(" ", $strTimeZone);
                $TimeZone = $strTimeZone[0];
                $gmt = $strTimeZone[1];
            }
            try
            {
                $userId = getUserId($objDataHelper);
            }
            catch (Exception $a)
            {
                throw new Exception("user.php : getUserId : Error in getting User Id." . $a->getMessage(), 541);
            }
            try
            {
                $status = '1';
                $strPhone = '';
                $addUserDetails = insUserDetails($userId, $strClientId, $strPartnerId, $strEmail, md5($strPassword), $strNickName, $strFirstName, $strLastName, $strCountryName, $TimeZone, $gmt, $strPhone, $strIddCode, $strMobile, GM_DATE, $strIsAdmin, $status, $objDataHelper);
                $newUserId = $addUserDetails[0]['@MESSAGE'];
                if ($addUserDetails[0]['@STATUS'] == 1)
                {
                    $success = 'User <b><font color=#006699>"' . $strNickName . '" </font></b>added successfully.';
                }
                else
                {
                    $errors[] = 'Error in Adding.';
                }
            }
            catch (Exception $a)
            {
                throw new Exception("user.php : insUserDetails : Error in adding user details" . $a->getMessage(), 613);
            }
        }
    }

    if (isset($_POST['btnBack']))
    {
        header("Location:" . $ADMIN_SITE_ROOT . 'user');
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
                    <div class="fL"><h3>Add User</h3></div>
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
                        <form name="Contact" method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
                            <div class="frm-fields tBold">Client Name<span class="required">&nbsp;*</span><span class="colon">:&nbsp;&nbsp;</span></div>
                            <?php
                            echo"<div><select name='ClientName' class='span3' id='ClientName'> ";
                            if (!empty($arrClientList))
                            {
                                //echo"<option value='---'>Select Client</option>";
                                for ($intCount = 0; $intCount < sizeof($arrClientList); $intCount++)
                                {
                                    if ($arrClientList[$intCount]['client_id'] == $strClientId)
                                    {
                                        echo"<option value='" . $arrClientList[$intCount]['client_id'] . SEPARATOR . $arrClientList[$intCount]['client_name'] . SEPARATOR. $arrClientList[$intCount]['partner_id']. "' selected>" . $arrClientList[$intCount]['client_name'] . "</option>";
                                    }
                                    else
                                    {
                                        echo"<option value='" . $arrClientList[$intCount]['client_id'] . SEPARATOR . $arrClientList[$intCount]['client_name'] . SEPARATOR. $arrClientList[$intCount]['partner_id']. "'>" . $arrClientList[$intCount]['client_name'] . "</option>";
                                    }
                                }
                            }
                            else
                            {
                                echo"<option value ='---'>Client Name List not available</option>";
                            }echo"
                                          </select></div>";
                            ?>
                            <div class="frm-fields tBold">Email Address<span class="required">&nbsp;*</span><span class="colon">:&nbsp;&nbsp;</span></div>
                            <div><input type="text" name="txtEmail" value="<?php echo $strEmail; ?>" maxlength="100" class="span3" id=""></div>
                            <div class="frm-fields tBold">Password<span class="required">&nbsp;*</span><span class="colon">:&nbsp;&nbsp;</span></div>
                            <div><input type="password" name="txtPassword" maxlength="15" class="span3" id=""></div>
                            <div class="frm-fields tBold">Confirm Password<span class="required">&nbsp;*</span><span class="colon">:&nbsp;&nbsp;</span></div>
                            <div><input type="password" name="txtCnfPassword" maxlength="15" class="span3" id=""></div>
                            <div class="frm-fields tBold">Nick Name<span class="required">&nbsp;*</span><span class="colon">:&nbsp;&nbsp;</span></div>
                            <div><input type="text" name="txtNickName" value="<?php echo $strNickName; ?>" maxlength="50" class="span3" id="nickname"></div>
                            <div class="frm-fields tBold">First Name<span class="required">&nbsp;*</span><span class="colon">:&nbsp;&nbsp;</span></div>
                            <div><input type="text" name="txtFirstName" value="<?php echo $strFirstName; ?>" maxlength="50" class="span3" id=""></div>
                            <div class="frm-fields tBold">Last Name<span class="required">&nbsp;*</span><span class="colon">:&nbsp;&nbsp;</span></div>
                            <div><input type="text" name="txtLastName" value="<?php echo $strLastName; ?>" maxlength="50" class="span3" id=""></div>
                            <div class="frm-fields tBold">Country Name<span class="required">&nbsp;*</span><span class="colon">:&nbsp;&nbsp;</span></div>
                            <?php
                            echo"<div><select name='txtCountryName' class='span3' id='CountryName' onchange='SetIddCode(this.value); addOption(this.value)'> ";
                            if (!empty($arrCountryIddCode))
                            {
                                echo"<option value='---'>Select Country</option>";
                                for ($intCount = 0; $intCount < sizeof($arrCountryIddCode); $intCount++)
                                {
                                    if ($arrCountryIddCode[$intCount]['country_name'] == $strCountryName)
                                    {
                                        echo"<option value='" . $arrCountryIddCode[$intCount]['country_idd_code'] . SEPARATOR . $arrCountryIddCode[$intCount]['country_name'] . SEPARATOR . $arrCountryIddCode[$intCount]['country_code'] . "' selected>" . $arrCountryIddCode[$intCount]['country_name'] . "</option>";
                                    }
                                    else
                                    {
                                        echo"<option value='" . $arrCountryIddCode[$intCount]['country_idd_code'] . SEPARATOR . $arrCountryIddCode[$intCount]['country_name'] . SEPARATOR . $arrCountryIddCode[$intCount]['country_code'] . "'>" . $arrCountryIddCode[$intCount]['country_name'] . "</option>";
                                    }
                                }
                            }
                            else
                            {
                                echo"<option value ='---'>Country Name List not available</option>";
                            }echo"
                                          </select></div>";
                            ?>
                            <div class="frm-fields tBold">Timezone<span class="required">&nbsp;*</span><span class="colon">:&nbsp;&nbsp;</span></div>
                            <div><select name="txtTimeZone" class="span3" id="TimeZone">
                                    <option value='---'>Select Timezone</option>
                                    <?php
                                    if (isset($_SESSION['timezone']))
                                    {
                                        echo"<option value='" . $_SESSION['timezone'] . "' selected>" . $_SESSION['timezone'] . "</option>";
                                        unset($_SESSION['timezone']);
                                    }
                                    ?>
                                </select></div>
                            <div class="frm-fields tBold">Mobile<span class="required">&nbsp;*</span><span class="colon">:&nbsp;&nbsp;</span></div>
                            <div><input type="text" name="txtIddCode" value="<?php echo $strIddCode; ?>" readonly="readonly" placeholder="Idd Code" class="span1" id="">
                                <input type="text" name="txtMobileNumber"  value="<?php echo $strMobile; ?>" maxlength="10" class="span2" id=""></div>
                                <input type="hidden" name="isAdmin" value="0"></br>
                            <button name="btnSubmit" class="btn btn-primary" type="submit">Submit</button>
                            <button name="btnReset" class="btn btn-primary mL10" type="submit">Reset</button>
                        </form>
                    <?php
                    }
                    else
                    {
                        ?>
                        <form name="Contact" method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
                            <input type="submit" name="btnBack" class="btn btn-primary" value="Back" />
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
        <?php include (ADM_INCLUDES_PATH.'jsinclude.php'); ?>
        <!-- java script  -->

        <!-- java script  1-->
        <script src="<?php echo ADM_JS_PATH; ?>common.js"></script>
        <script>
            $(document).ready(function ()
            {
                var countryname = $('#CountryName').val();
                var timezone = $('#TimeZone').val();
                if(countryname != '---' && timezone == '---')
                {
                    addOption(countryname);
                }
            });
        </script>

        <!-- java script  1-->

    </body>
</html>
