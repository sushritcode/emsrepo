<?php
require_once('../includes/global.inc.php');
require_once(CLASSES_PATH . 'error.inc.php');
require_once(DBS_PATH . 'DataHelper.php');
require_once(DBS_PATH . 'objDataHelper.php');
require_once(INCLUDES_PATH . 'cm_authfunc.inc.php');
$CONST_MODULE = 'profile';
$CONST_PAGEID = 'Edit Profile';
require_once(INCLUDES_PATH . 'cm_authorize.inc.php');
require_once(INCLUDES_PATH . 'profile_function.inc.php');
require_once(INCLUDES_PATH . 'db_common_function.inc.php');
require_once(INCLUDES_PATH . 'getTimezones.php');

try
{
    session_start();
    try
    {
        $arrUserDetails = getUserDetailsByUserId($strCK_user_id, $objDataHelper);
        $txtEmail = $arrUserDetails[0]['email_address'];
        $txtNickName = $arrUserDetails[0]['nick_name'];
        $txtFirstName = $arrUserDetails[0]['first_name'];
        $txtLastName = $arrUserDetails[0]['last_name'];
        $txtCountryName = $arrUserDetails[0]['country_name'];
        $txtTimezone = $arrUserDetails[0]['timezones'];
        $txtIddCode = $arrUserDetails[0]['idd_code'];
        $txtGmt = $arrUserDetails[0]['gmt'];
        $txtMobile = $arrUserDetails[0]['mobile_number'];
    }
    catch (Exception $e)
    {
        throw new Exception("editprofile.php : getUserDetailsByUserId Failed : " . $e->getMessage(), 1128);
    }
    try
    {
        $arrCountryIddCode = GetCountryDetails($objDataHelper);
    }
    catch (Exception $e)
    {
        throw new Exception("editprofile.php : GetCountryDetails Failed : " . $e->getMessage(), 1128);
    }
    if (isset($_POST['btnUpdate']))
    {
        $strNickName = trim($_POST['txtNickName']);
        $strFirstName = trim($_POST['txtFirstName']);
        $strLastName = trim($_POST['txtLastName']);
        $strCountryName = trim($_POST['CountryName']);
        $strIddCode = trim($_POST['txtIddCode']);
        $strMobile = trim($_POST['txtMobileNumber']);
        $strTimeZone = trim($_POST['txtTimeZone']);
        $_SESSION['timezone'] = $strTimeZone;

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
        if (strlen($strMobile) != 0 && !ctype_digit($strMobile))
        {
            $errors[] = 'Only Digits are allowed in Mobile number.';
        }

        if (sizeof($errors) == 0)
        {
            if ($strCountryName != '---')
            {
                $strCountryName = explode(SEPARATOR, $strCountryName);
                $strCountryName = $strCountryName[1];
            }
            
            if (strlen(trim($strIddCode)) > 0)
                $strIddCode = substr($strIddCode, 1);
            
            if (strlen(trim($strTimeZone)) > 0)
            {
                $strTimeZone = explode(" ", $strTimeZone);
                $TimeZone = $strTimeZone[0];
                $gmt = $strTimeZone[1];
            }

            if ($txtNickName == $strNickName && $txtFirstName == $strFirstName && $txtLastName == $strLastName && $txtCountryName == $strCountryName && $txtTimezone == $TimeZone && $txtIddCode == $strIddCode && $txtMobile == $strMobile)
            {
                $_SESSION['txtSuccessMsg'] = 'Your profile edited successfully.';
                header("Location:" . $SITE_ROOT . 'profile');
            }
            else
            {
                try
                {
                    $updUserDetails = updUserDetails($strCK_user_id, $strNickName, $strFirstName, $strLastName, $strCountryName, $TimeZone, $gmt, $strIddCode, $strMobile, $objDataHelper);
                    if ($updUserDetails[0]['@STATUS'] == 1)
                    {
                        $_SESSION['txtSuccessMsg'] = 'Your profile edited successfully.';
                        header("Location:" . $SITE_ROOT . 'profile');
                    }
                    else
                    {
                        $errors[] = 'Error in Adding.';
                    }
                }
                catch (Exception $a)
                {
                    throw new Exception("editprofile.php : updUserDetails : Error in updating user details" . $a->getMessage(), 613);
                }
            }
        }
    }
    if (isset($_POST['btnCancel']))
    {
        header("Location:" . $SITE_ROOT . 'profile');
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
                    <h2>Edit Profile</h2>
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
                    <form name="Contact" method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
                        <div class="frm-fields tBold">Email Address<span class="colon">:&nbsp;&nbsp;</span></div><div><p class="txt-email tBold"><?php echo $txtEmail; ?></p></div>
                        <div class="frm-fields tBold">Nick Name<span class="required">&nbsp;*</span><span class="colon">:&nbsp;&nbsp;</span></div>
                        <div><input type="text" name="txtNickName" value="<?php echo $txtNickName; ?>" maxlength="50" class="span3" id="nickname"></div>
                        <div class="frm-fields tBold">First Name<span class="required">&nbsp;*</span><span class="colon">:&nbsp;&nbsp;</span></div>
                        <div><input type="text" name="txtFirstName" value="<?php echo $txtFirstName; ?>" maxlength="50" class="span3" id=""></div>
                        <div class="frm-fields tBold">Last Name<span class="required">&nbsp;*</span><span class="colon">:&nbsp;&nbsp;</span></div>
                        <div><input type="text" name="txtLastName" value="<?php echo $txtLastName; ?>" maxlength="50" class="span3" id=""></div>
                        <div class="frm-fields tBold">Country Name<span class="required">&nbsp;*</span><span class="colon">:&nbsp;&nbsp;</span></div>
                        <?php
                        echo"<div><select name='CountryName' class='span3' id='CountryName' onchange='SetIddCode(this.value); addOption(this.value)'> ";
                        if (!empty($arrCountryIddCode))
                        {
                            echo"<option value='---'>Select Country</option>";
                            for ($intCount = 0; $intCount < sizeof($arrCountryIddCode); $intCount++)
                            {
                                if ($arrCountryIddCode[$intCount]['country_name'] == $txtCountryName)
                                {
                                    echo"<option value='".$arrCountryIddCode[$intCount]['country_idd_code'].SEPARATOR.$arrCountryIddCode[$intCount]['country_name'].SEPARATOR.$arrCountryIddCode[$intCount]['country_code']."' selected>".$arrCountryIddCode[$intCount]['country_name']."</option>";
                                }
                                else
                                {
                                    echo"<option value='".$arrCountryIddCode[$intCount]['country_idd_code'].SEPARATOR.$arrCountryIddCode[$intCount]['country_name'].SEPARATOR.$arrCountryIddCode[$intCount]['country_code']."'>".$arrCountryIddCode[$intCount]['country_name']."</option>";
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
                                if (isset($arrUserDetails[0]['timezones']))
                                {
                                    echo"<option value='".$txtTimezone." ".$txtGmt."' selected>".$txtTimezone." ".$txtGmt."</option>";
                                    //session_unset();
                                }
                                ?>
                            </select></div>
                        <div class="frm-fields tBold">Mobile<span class="colon">:&nbsp;&nbsp;</span></div>
                        <div><input type="text" name="txtIddCode" value="<?php echo "+".$txtIddCode; ?>" readonly="readonly" placeholder="Idd Code" class="span1" id="">
                            <input type="text" name="txtMobileNumber"  value="<?php echo $txtMobile; ?>" maxlength="10" class="span2" style="width:187px" id=""></div><br>
                        <button name="btnUpdate" class="btn btn-primary" type="submit">Update</button>
                        <button name="btnCancel" class="btn btn-primary mL10" type="submit">Cancel</button>
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

        <!-- java script  1-->
        <script src="<?php echo JS_PATH;?>common.js"></script>
        <!-- java script  1-->

    </body>
</html>
