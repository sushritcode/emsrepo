<?php
require_once('../../includes/global.inc.php');
require_once('../includes/config.inc.php');
require_once(CLIENT_CLASSES_PATH . 'client_error.inc.php');
require_once(DBS_PATH . 'DataHelper.php');
require_once(DBS_PATH . 'objDataHelper.php');
require_once(CLIENT_INCLUDES_PATH . 'client_authfunc.inc.php');
$CLIENT_CONST_MODULE = 'cluser';
$CLIENT_CONST_PAGEID = 'Add User';
require_once(CLIENT_INCLUDES_PATH . 'client_authorize.inc.php');
require_once(CLIENT_INCLUDES_PATH . 'getTimezones.php');
require_once(CLIENT_INCLUDES_PATH . 'client_db_function.inc.php');

try
{
    try
    {
        $arrCountryIddCode = getCountryDetails($objDataHelper);
    }
    catch (Exception $e)
    {
        throw new Exception("adduser.php : getCountryDetails Failed : " . $e->getMessage(), 541);
    }

    try
    {
        $arrTotalConsumedLicense = getTotalConsumedLicenseByClientId($strSetClient_ID, $objDataHelper);
    }
    catch (Exception $a)
    {
        throw new Exception("adduser.php : getTotalConsumedLicenseByClientId Failed." . $a->getMessage(), 541);
    }

    $strConsumedLicense = $arrTotalConsumedLicense[0]['ConsumedLicense'];

    if (isset($_POST['btnSubmit']))
    {
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
        //$_SESSION['timezone'] = $strTimeZone;
        $strTime_Zone = $strTimeZone;

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

        if ($strCountryName != '---')
        {
            $strCountryName = explode(SEPARATOR, $strCountryName);
            $strCountryName = $strCountryName[1];
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
                throw new Exception("addadduser.php : isUserEmailExists : Error in checking email address." . $a->getMessage(), 541);
            }
        }

        if (sizeof($errors) == 0)
        {
            if (strlen(trim($strIddCode)) > 0)
            {
                $strIddCode = substr($strIddCode, 1);
            }

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
                throw new Exception("adduser.php : getUserId : Error in getting User Id." . $a->getMessage(), 541);
            }

            try
            {
                try
                {
                    $totalLicenseAdded = getSumOfClientLicenseByType($strSetClient_ID, '0', $objDataHelper);
                    $totalLicenseConsumed = getSumOfClientLicenseByType($strSetClient_ID, '1', $objDataHelper);
                    $totalLicenseDisabled = getSumOfClientLicenseByType($strSetClient_ID, '2', $objDataHelper);
                    $status = (($totalLicenseAdded - ($totalLicenseConsumed + $totalLicenseDisabled)) > 0) ? 1 : 4;
                }
                catch (Exception $a)
                {
                    throw new Exception("index.php : getSumOfClientLicenseByType : Error in License." . $a->getMessage(), 541);
                }

                if ($status == 1)
                {
                    $strPhone = '';
                    $addUserDetails = insUserDetails($userId, $strSetClient_ID, $strSetPartner_ID, $strEmail, md5($strPassword), $strNickName, $strFirstName, $strLastName, $strCountryName, $TimeZone, $gmt, $strPhone, $strIddCode, $strMobile, GM_DATE, $strIsAdmin, $status, $objDataHelper);
                    $newUserId = $addUserDetails[0]['@MESSAGE'];
                    if ($addUserDetails[0]['@STATUS'] == 1)
                    {
                        $strLicense = 1;
                        $OperationType = 1;
                        $gmt_datetime = GM_DATE;

                        try
                        {
                            $addClientLicense = insClientLicenseDetails($strSetClient_ID, $strLicense, $OperationType, $gmt_datetime, $objDataHelper);
                        }
                        catch (Exception $a)
                        {
                            throw new Exception("addsubscription.php : insOrderMaster : Error in adding order master." . $a->getMessage(), 613);
                        }
                        $strLicenseStatus = $addClientLicense[0]['@STATUS'];
                        $success = 'User <b><font color=#006699>"' . $strNickName . '" </font></b>added successfully.';
                    }
                    else
                    {
                        $errors[] = 'Error in Adding.';
                    }
                }
                else
                {
                    $success= "Sorry, You have consumed all your License's, For more license please contact sales@letsmeet.com";
                }
            }
            catch (Exception $a)
            {
                throw new Exception("adduser.php : insUserDetails : Error in adding user details" . $a->getMessage(), 613);
            }
        }
    }

    if (isset($_POST['btnBack']))
    {
        header("Location:" . $CLIENT_SITE_ROOT . 'user');
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
                    <div class="fL"><h3>Add User </h3></div>
                </div>

                <div class="span12"><hr>

                    <?php if (count($errors)): ?>
                        <div class="alert alert-error"> 
                            <?php foreach ($errors as $error): ?>
                                <span><?php echo $error; ?></span><br/>    
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                    <?php if ($success): ?>
                        <?php if ($status == 4): ?>
                                <div class="alert alert-error"> 
                          <?php else:?>
                        <div class="alert alert-success"> 
                            <?php endif;?>
                            <span><?php echo $success; ?></span><br/> 
                        </div>
                    <?php endif; ?>
                    <?php if (empty($success))
                    {
                        ?>
                        <form name="Contact" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                            <div class="frm-fields tBold">Email Address<span class="required">&nbsp;*</span><span class="colon">&nbsp;:&nbsp;</span></div>
                            <div><input type="text" name="txtEmail" value="<?php  echo $strEmail; ?>" maxlength="100" class="span3" id=""></div>
                            <div class="frm-fields tBold">Password<span class="required">&nbsp;*</span><span class="colon">&nbsp;:&nbsp;</span></div>
                            <div><input type="password" name="txtPassword" maxlength="15" class="span3" id=""></div>
                            <div class="frm-fields tBold">Confirm Password<span class="required">&nbsp;*</span><span class="colon">&nbsp;:&nbsp;</span></div>
                            <div><input type="password" name="txtCnfPassword" maxlength="15" class="span3" id=""></div>
                            <div class="frm-fields tBold">Nick Name<span class="required">&nbsp;*</span><span class="colon">&nbsp;:&nbsp;</span></div>
                            <div><input type="text" name="txtNickName" value="<?php  echo $strNickName; ?>" maxlength="50" class="span3" id="nickname"></div>
                            <div class="frm-fields tBold">First Name<span class="required">&nbsp;*</span><span class="colon">&nbsp;:&nbsp;</span></div>
                            <div><input type="text" name="txtFirstName" value="<?php  echo $strFirstName; ?>" maxlength="50" class="span3" id=""></div>
                            <div class="frm-fields tBold">Last Name<span class="required">&nbsp;*</span><span class="colon">&nbsp;:&nbsp;</span></div>
                            <div><input type="text" name="txtLastName" value="<?php  echo $strLastName; ?>" maxlength="50" class="span3" id=""></div>
                            <div class="frm-fields tBold">Country Name<span class="required">&nbsp;*</span><span class="colon">&nbsp;:&nbsp;</span></div>
                            <div><select name='txtCountryName' class='span3' id='CountryName' onchange='SetIddCode(this.value);
                                    addOption(this.value)'>
                                    <?php
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
                                    }
                                    ?>
                                </select></div>
                            <div class="frm-fields tBold">Timezone<span class="required">&nbsp;*</span><span class="colon">&nbsp;:&nbsp;</span></div>
                            <div><select name='txtTimeZone' class='span3' id='TimeZone'>
                                    <option value='---'>Select Timezone</option>
                                    <?php
                                    if (isset($strTime_Zone))
                                    {
                                        echo"<option value='" . $strTime_Zone . "' selected>" . $strTime_Zone . "</option>";
                                    }
                                    ?>
                                </select></div>
                            <div class="frm-fields tBold">Mobile<span class="required">&nbsp;*</span><span class="colon">&nbsp;:&nbsp;</span></div>
                            <div><input type="text" name="txtIddCode" value="<?php  echo $strIddCode; ?>" readonly="readonly" placeholder="Idd Code" class="span1" id="">
                                <input type="text" name="txtMobileNumber"  value="<?php  echo $strMobile;?>" maxlength="10" class="span2" id=""></div>
                            <div><input type="hidden" name="isAdmin" value="0"></div>
                            <button name="btnSubmit" class="btn btn-primary" type="submit">Submit</button>
                            <button name="btnReset" class="btn btn-primary mL10" type="submit">Reset</button>
                            <a class="btn btn-primary mL10" href="<?php echo $CLIENT_SITE_ROOT; ?>user/">Back</a>
                        </form>
                        <?php } else { ?>
                        <form name="Contact" method="POST" action="<?php  echo $_SERVER["PHP_SELF"]; ?>">
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
        <?php include (CLIENT_INCLUDES_PATH . 'footer.php'); ?>
        <!-- Footer content Area -->


        <!-- java script  -->
        <?php include (CLIENT_INCLUDES_PATH . 'jsinclude.php'); ?>
        <!-- java script  -->

        <!-- java script  1-->
        <script src="<?php echo CLIENT_JS_PATH; ?>common.js"></script>
        <script>
                $(document).ready(function ()
                {
                    var countryname = $('#CountryName').val();
                    var timezone = $('#TimeZone').val();
                    if (countryname != '---' && timezone == '---')
                    {
                        addOption(countryname);
                    }
                });
        </script>
        <!-- java script  1-->
    </body>
</html>
