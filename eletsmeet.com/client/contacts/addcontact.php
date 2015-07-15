<?php
require_once('../../includes/global.inc.php');
require_once('../includes/config.inc.php');
require_once(CLIENT_CLASSES_PATH . 'client_error.inc.php');
require_once(DBS_PATH . 'DataHelper.php');
require_once(DBS_PATH . 'objDataHelper.php');
require_once(CLIENT_INCLUDES_PATH . 'client_authfunc.inc.php');
$CLIENT_CONST_MODULE = 'clcontact';
$CLIENT_CONST_PAGEID = 'Add Contact';
require_once(CLIENT_INCLUDES_PATH . 'client_authorize.inc.php');
require_once(CLIENT_INCLUDES_PATH . 'client_db_function.inc.php');

try
{
    $errors = array();

    try
    {
        $arrContactGroupList = getContactGroupList($strSetClient_ID, $objDataHelper);
    }
    catch (Exception $a)
    {
        throw new Exception("addcontact.php : ContactGroupList : Error in populating Group List.".$a->getMessage(), 541);
    }

    try
    {
        $arrCountryDetails = getCountryDetails($objDataHelper);
    }
    catch (Exception $a)
    {
        throw new Exception("addcontact.php : GetCountryDetails : Error in populating Country List.".$a->getMessage(), 542);
    }

    if (isset($_POST['btnSubmit']))
    {
        $strNickName = trim($_POST['txtNickName']);
        $strFirstName = trim($_POST['txtFirstName']);
        $strLastName = trim($_POST['txtLastName']);
        $strEmail = trim($_POST['txtEmail']);
        $strCountryName = trim($_POST['CountryName']);
        $strIddCode = trim($_POST['txtIddCode']);
        $strMobile = trim($_POST['txtMobileNumber']);
        $strGroupNameCmb = trim($_POST['cmbGroupNameList']);
        $strGroupNameTxt = trim($_POST['txtGroupName']);

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
        if (strlen($strEmail) <= 0)
        {
            $errors[] = 'Please enter your Email Address.';
        }
        else if (eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $strEmail) != 1)
        {
            $errors[] = 'Please enter valid Email Address.';
        }
        if ($strGroupNameCmb == '---' && strlen($strGroupNameTxt) <= 0)
        {
            $errors[] = 'Please select your Group Name or Enter new Group Name.';
        }
        else if (strlen($strGroupNameTxt) != 0 && !preg_match('/^[a-z A-Z 0-9 . _]+$/', $strGroupNameTxt))
        {
            $errors[] = 'Group Name must be alphanumeric.';
        }
        if ($strCountryName != '---')
        {
            $strCountryName = explode(SEPARATOR, $strCountryName);
            $strCountryName = $strCountryName[1];
        }
        if (strlen($strMobile) != 0 && !ctype_digit($strMobile))
        {
            $errors[] = 'Only Digits are allowed in Mobile number.';
        }

        if (strlen($strEmail) != 0)
        {
            try
            {
                $isEmailExists = isContactEmailExists($strEmail, $strSetClient_ID, $objDataHelper);
                if ($isEmailExists != 0)
                {
                    $errors[] = 'Email Address <b><font color=#006699>"'.$strEmail.'"</font></b> already exists.';
                }
            }
            catch (Exception $a)
            {
                throw new Exception("addcontact.php : isContactEmailExists : Error in checking email address.".$a->getMessage(), 541);
            }
        }

        if (strlen($strGroupNameTxt) != 0)
        {
            try
            {
                $isGroupExists = isContactGroupExists($strGroupNameTxt, $strSetClient_ID, $objDataHelper);
                if ($isGroupExists != 0)
                {
                    $errors[] = 'Group name <b><font color=#006699>"'.$strGroupNameTxt.'"</font></b> already exists.';
                }
            }
            catch (Exception $a)
            {
                throw new Exception("addcontact.php : isContactGroupExists : Error in checking group name.".$a->getMessage(), 541);
            }
        }

        if (sizeof($errors) == 0)
        {
            try
            {
                if (strlen(trim($strGroupNameTxt)) > 0)
                {
                    $strGroupName = $strGroupNameTxt;
                }
                else
                {
                    $strGroupName = $strGroupNameCmb;
                }

                if (strlen(trim($strIddCode)) > 0)
                    $strIddCode = substr($strIddCode, 1);

                $addContactDetails = insContactDetails($strNickName, $strFirstName, $strLastName, $strEmail, $strIddCode, $strMobile, $strGroupName, CLIENT_CONTACT_TYPE, $strSetClient_ID, $objDataHelper);
                if ($addContactDetails[0]['@STATUS'] == 1)
                {
                    $msg = 'Contact <b><font color=#006699>"'.$strNickName.'" </font></b>added successfully.';
                    header("Location:".$CLIENT_SITE_ROOT.'contacts/?msg='.urlencode($msg));
                }
                else
                {
                    $errors[] = 'Error in Adding.';
                }
            }
            catch (Exception $a)
            {
                throw new Exception("addcontact.php : ContactGroupList : Error in populating ContactGroupList".$a->getMessage(), 613);
            }
        }
    }
    
    if (isset($_POST['btnCancel']))
    {
        header("Location:".$CLIENT_SITE_ROOT.'contacts');
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
        <?php include (CLIENT_INCLUDES_PATH.'head.php'); ?>    
    </head>
    <!-- Head content Area -->

    <body onload="SelectOption();">

        <!-- Navigation Bar, After Login Menu &  Product Logo -->
        <?php include (CLIENT_INCLUDES_PATH.'navigation.php'); ?>    
        <!-- Navigation Bar, After Login Menu &  Product Logo -->

        <!-- Main content Area -->
        <div class="container">
            <!-- Main hero unit for a primary marketing message or call to action -->

            <!-- Middle content Area -->
            <div class="row">

                <div class="span12">
                    <div class="fL"><h3>Add Contact</h3></div>
                </div>
               
                <div class="span12"><hr>

                    <?php if (count($errors)): ?>
                        <div class="alert alert-error"> 
                            <?php foreach ($errors as $error): ?>
                                <span><?php echo $error; ?></span><br />    
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                    <form name="Contact" method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
                        <div class="frm-fields tBold">Nick Name<span class="required">&nbsp;*</span><span class="colon">:&nbsp;&nbsp;</span></div>
                        <div><input type="text" name="txtNickName" value="<?php echo $strNickName; ?>" maxlength="50" class="span3" id="nickname"></div>
                        <div class="frm-fields tBold">First Name<span class="required">&nbsp;*</span><span class="colon">:&nbsp;&nbsp;</span></div>
                        <div><input type="text" name="txtFirstName" value="<?php echo $strFirstName; ?>" maxlength="50" class="span3" id=""></div>
                        <div class="frm-fields tBold">Last Name<span class="required">&nbsp;*</span><span class="colon">:&nbsp;&nbsp;</span></div>
                        <div><input type="text" name="txtLastName" value="<?php echo $strLastName; ?>" maxlength="50" class="span3" id=""></div>
                        <div class="frm-fields tBold">Email Address<span class="required">&nbsp;*</span><span class="colon">:&nbsp;&nbsp;</span></div>
                        <div><input type="text" name="txtEmail" value="<?php echo $strEmail; ?>" maxlength="100" class="span3" id=""></div>
                        <div class="frm-fields tBold">Group Name<span class="required">&nbsp;*</span><span class="colon">:&nbsp;&nbsp;</span></div>
                        <?php
                        echo"<div><select name='cmbGroupNameList' class='span3' onchange='SelectOption();'> ";
                        if (!empty($arrContactGroupList))
                        {
                            echo"<option value='---'>Select Group Name</option>";
                            for ($intCount = 0; $intCount < sizeof($arrContactGroupList); $intCount++)
                            {
                                if ($arrContactGroupList[$intCount]['contact_group_name'] == $strGroupNameCmb)
                                {
                                    echo"<option value='".$arrContactGroupList[$intCount]['contact_group_name']."' selected>".$arrContactGroupList[$intCount]['contact_group_name']."</option>";
                                }
                                else
                                {
                                    echo"<option value='".$arrContactGroupList[$intCount]['contact_group_name']."'>".$arrContactGroupList[$intCount]['contact_group_name']."</option>";
                                }
                            }
                        }
                        else
                        {
                            echo"<option value ='---'>Group Name List not available</option>";
                        }echo"
                                          </select></div>";
                        ?>
                        <div class="frm-fields" style="visibility: hidden;">Group</div>
                        <div class="mB5">Or</div>
                        <div><input type="text" name="txtGroupName" value="<?php echo $strGroupNameTxt; ?>" maxlength="100" class="span3" id=""></div>
                        <div class="frm-fields tBold">Country Name<span class="colon">:&nbsp;&nbsp;</span></div>
                        <?php
                        echo"<div><select name='CountryName' class='span3' onchange='SetIddCode(this.value)'> ";
                        if (!empty($arrCountryDetails))
                        {
                            echo"<option value='---'>Select Country</option>";
                            for ($intCount = 0; $intCount < sizeof($arrCountryDetails); $intCount++)
                            {
                                if ($arrCountryDetails[$intCount]['country_name'] == $strCountryName)
                                {
                                    echo"<option value='".$arrCountryDetails[$intCount]['country_idd_code'].SEPARATOR.$arrCountryDetails[$intCount]['country_name']."' selected>".$arrCountryDetails[$intCount]['country_name']."</option>";
                                }
                                else
                                {
                                    echo"<option value='".$arrCountryDetails[$intCount]['country_idd_code'].SEPARATOR.$arrCountryDetails[$intCount]['country_name']."'>".$arrCountryDetails[$intCount]['country_name']."</option>";
                                }
                            }
                        }
                        else
                        {
                            echo"<option value ='---'>Country Name List not available</option>";
                        }echo"
                                          </select></div>";
                        ?>
                        <div class="frm-fields tBold">Mobile<span class="colon">:&nbsp;&nbsp;</span></div>
                        <div><input type="text" name="txtIddCode" value="<?php echo $strIddCode; ?>" readonly="readonly" placeholder="Idd Code" class="span1" id="">
                            <input type="text" name="txtMobileNumber"  value="<?php echo $strMobile; ?>" maxlength="20" class="span2" style="width:187px" id=""></div></br>
                        <button name="btnSubmit" class="btn btn-primary" type="submit">Submit</button>
                        <button name="btnCancel" class="btn btn-primary mL10" type="submit">Cancel</button>
                    </form>
                    <hr>
                </div>
            </div>
            <!-- Middle content Area -->
        </div>
        <!-- Main content Area -->

        <!-- Footer content Area -->
        <?php include (CLIENT_INCLUDES_PATH.'footer.php'); ?>
        <!-- Footer content Area -->


        <!-- java script  -->
        <?php include (CLIENT_INCLUDES_PATH.'jsinclude.php'); ?>
        <!-- java script  -->

        <!-- java script  1-->
        <script src="<?php echo CLIENT_JS_PATH; ?>common.js"></script>
        <script src="<?php echo CLIENT_JS_PATH; ?>contacts.js"></script>
        <!-- java script  1-->

    </body>
</html>
