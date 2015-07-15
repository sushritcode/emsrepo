<?php
require_once('../../includes/global.inc.php');
require_once('../includes/config.inc.php');
require_once(CLIENT_CLASSES_PATH . 'client_error.inc.php');
require_once(DBS_PATH . 'DataHelper.php');
require_once(DBS_PATH . 'objDataHelper.php');
require_once(CLIENT_INCLUDES_PATH . 'client_authfunc.inc.php');
$CLIENT_CONST_MODULE = 'clcontact';
$CLIENT_CONST_PAGEID = 'Edit Contact';
require_once(CLIENT_INCLUDES_PATH . 'client_authorize.inc.php');
require_once(CLIENT_INCLUDES_PATH . 'client_db_function.inc.php');

try
{   
    if (isset($_POST['contId']))
    {
       $contactId = $_POST['contId'];
    }
       
    $strErrorMsg = trim($_REQUEST["txtErrorMsg"]);
    
    try
    {
        $arrContactGroupList = getContactGroupList($strSetClient_ID, $objDataHelper);
    }
    catch (Exception $a)
    {
        throw new Exception("editcontact.php : getContactGroupList : Error in populating Group List.".$a->getMessage(), 541);
    }

    try
    {
        $arrCountryIddCode = getCountryDetails($objDataHelper);
    }
    catch (Exception $a)
    {
        throw new Exception("editcontact.php : getCountryDetails : Error in populating CountryIddCodes.".$a->getMessage(), 541);
    }

    try
    {
        $arrContactDetails = getContactDetails($contactId, $objDataHelper);
        $txtContactId  = trim($arrContactDetails[0]['client_contact_id']);
        $txtNickName = trim($arrContactDetails[0]['contact_nick_name']);
        $txtFirstName = trim($arrContactDetails[0]['contact_first_name']);
        $txtLastName = trim($arrContactDetails[0]['contact_last_name']);
        $txtEmail = trim($arrContactDetails[0]['contact_email_address']);
        $txtGroupNameCmb = trim($arrContactDetails[0]['contact_group_name']);
        $txtMobile = trim($arrContactDetails[0]['contact_mobile_number']);

        if ($arrContactDetails[0]['contact_idd_code'] != NULL)
        {
            try
            {
                $txtIddCode = trim($arrContactDetails[0]['contact_idd_code']);
                $arrCountryName = getCountryNamebyIdd($txtIddCode, $objDataHelper);
                $txtCountryName = $arrCountryName [0]['country_name'];
            }
            catch (Exception $a)
            {
                throw new Exception("editcontact.php : getCountryNamebyIdd : Error in populating CountryName.".$a->getMessage(), 541);
            }
        }
    }
    catch (Exception $a)
    {
        throw new Exception("contactlist.php : getContactDetails : Error in getting contact details.".$a->getMessage(), 624);
    }

    if (isset($_POST['btnUpdate']))
    {
        $strContactId = trim($_POST['contId']);
        $strNickName = trim($_POST['txtNickName']);
        $strFirstName = trim($_POST['txtFirstName']);
        $strLastName = trim($_POST['txtLastName']);
        $strEmail = trim($_POST['txtEmail']);
        $strCountryName = trim($_POST['CountryName']);
        $strIddCode = trim($_POST['txtIddCode']);
        $strMobile = trim($_POST['txtMobileNumber']);
        $strGroupNameCmb = trim($_POST["cmbGroupNameList"]);
        $strGroupNameTxt = trim($_POST['txtGroupName']);
              
        if  ( (strlen($strGroupNameTxt) == 0) && ($txtNickName == $strNickName && $txtFirstName == $strFirstName && $txtLastName == $strLastName && $txtEmail == $strEmail && $txtGroupNameCmb == $strGroupNameCmb && $txtIddCode == $strIddCode && $txtMobile == $strMobile) )
        {
             $msg = 'You have made no changes to save.';
            header("Location:".$CLIENT_SITE_ROOT.'contacts/?msg='.urlencode($msg));
        }
        else
        {
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
                $strCountryName = split('_', $strCountryName);
                $strCountryName = $strCountryName[1];
            }
            if (strlen($strMobile) != 0 && !ctype_digit($strMobile))
            {
                $errors[] = 'Only Digits are allowed in Mobile number.';
            }

            if ($txtEmail != $strEmail)
            {
                try
                {
                    $isEmailExists = isContactEmailExists($strEmail, $strSetClient_ID, $objDataHelper);
                }
                catch (Exception $a)
                {
                    throw new Exception("editcontact.php : isContactEmailExists : Error in checking email address.".$a->getMessage(), 541);
                }
                if ($isEmailExists[0]['@STATUS'] != 1)
                {
                    $errors[] = 'Email Address <b><font color=#006699>"'.$strEmail.'"</font></b> already exists.';
                }
            }

            if (strlen($strGroupNameTxt) != 0)
            {
                try
                {
                    $isGroupExists = isContactGroupExists($strGroupNameTxt, $strSetClient_ID, $objDataHelper);
                }
                catch (Exception $a)
                {
                    throw new Exception("editcontact.php : isContactGroupExists : Error in checking group name.".$a->getMessage(), 541);
                }
                if ($isGroupExists[0]['@STATUS'] != 1)
                {
                    $errors[] = 'Group name <b><font color=#006699>"'.$strGroupNameTxt.'"</font></b> already exists.';
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
                    {
                        $strIddCode = substr($strIddCode, 1);
                    }
                    
                    $updContactDetails = updContactDetails($strContactId, $strNickName, $strFirstName, $strLastName, $strEmail, $strIddCode, $strMobile, $strGroupName, CLIENT_CONTACT_TYPE, $strSetClient_ID, $objDataHelper);
                    //$updContactDetails = updContactDetails($txtContactID, $strNickName, $strFirstName, $strLastName, $strEmail, $strIddCode, $strMobile, $strGroupName, CLIENT_CONTACT_TYPE, $strSetClient_ID, $objDataHelper);
                    
                    if ($updContactDetails[0]['@STATUS'] == 1)
                    {
                        $msg = 'Contact <b><font color=#006699>"'.$strNickName.'" </font></b>edited successfully.';
                        header("Location:".$CLIENT_SITE_ROOT.'contacts/?msg='.urlencode($msg));
                    }
                    if ($updContactDetails[0]['@STATUS'] == 2)
                    {
                        $msg = 'You have made no changes to save.';
                        header("Location:".$CLIENT_SITE_ROOT.'contacts/?msg='.urlencode($msg));
                    }
                    else
                    {
                        $errors[] = 'Error in Updating.';
                    }
                }
                catch (Exception $a)
                {
                    throw new Exception("editcontact.php : updContactDetails : Error in Updating".$a->getMessage(), 613);
                }
            }
        }
    }
    
    if (isset($_POST['btnCancel']))
    {
         header("Location:".$CLIENT_SITE_ROOT.'contacts/');
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
        <?php include (CLIENT_INCLUDES_PATH.'head.php'); ?>    
    </head>
    <!-- Head content Area -->

    <body>

        <!-- Navigation Bar, After Login Menu &  Product Logo -->
        <?php include (CLIENT_INCLUDES_PATH.'navigation.php'); ?>    
        <!-- Navigation Bar, After Login Menu &  Product Logo -->

        <!-- Main content Area -->
        <div class="container">
            <!-- Main hero unit for a primary marketing message or call to action -->

            <!-- Middle content Area -->
            <div class="row">
                
                <div class="span12">
                    <div class="fL"><h3>Edit Contact</h3></div>
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
                        <div><input type="text" name="txtNickName" value="<?php echo $txtNickName; ?>" maxlength="50" class="span3" id=""></div>
                        <div class="frm-fields tBold">First Name<span class="required">&nbsp;*</span><span class="colon">:&nbsp;&nbsp;</span></div>
                        <div><input type="text" name="txtFirstName" value="<?php echo $txtFirstName; ?>" maxlength="50" class="span3" id=""></div>
                        <div class="frm-fields tBold">Last Name<span class="required">&nbsp;*</span><span class="colon">:&nbsp;&nbsp;</span></div>
                        <div><input type="text" name="txtLastName" value="<?php echo $txtLastName; ?>" maxlength="50" class="span3" id=""></div>
                        <div class="frm-fields tBold">Email Address<span class="required">&nbsp;*</span><span class="colon">:&nbsp;&nbsp;</span></div>
                        <div><input type="text" name="txtEmail" value="<?php echo $txtEmail; ?>" maxlength="100" class="span3" id=""></div>
                        <div class="frm-fields tBold">Group Name<span class="required">&nbsp;*</span><span class="colon">:&nbsp;&nbsp;</span></div>
                        <?php
                        echo"<div><select name='cmbGroupNameList' class='span3' onchange='SelectOption();'> ";
                        if (!empty($arrContactGroupList))
                        {
                            echo"<option value='---'>Select Group Name</option>";
                            for ($intCount = 0; $intCount < sizeof($arrContactGroupList); $intCount++)
                            {
                                if ($arrContactGroupList[$intCount]['contact_group_name'] == $txtGroupNameCmb)
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
                        <div><input type="text" name="txtGroupName" value="<?php echo $txtGroupNameTxt; ?>" maxlength="100" class="span3" id=""></div>
                        <div class="frm-fields tBold">Country Name<span class="colon">:&nbsp;&nbsp;</span></div>
                        <?php
                        echo"<div><select name='CountryName' class='span3' onchange='SetIddCode(this.value)'> ";
                        if (!empty($arrCountryIddCode))
                        {
                            echo"<option value='---'>Select Country</option>";
                            for ($intCount = 0; $intCount < sizeof($arrCountryIddCode); $intCount++)
                            {
                                if ($arrCountryIddCode[$intCount]['country_name'] == $txtCountryName)
                                {
                                    echo"<option value='".$arrCountryIddCode[$intCount]['country_idd_code'].SEPARATOR.$arrCountryIddCode[$intCount]['country_name']."' selected>".$arrCountryIddCode[$intCount]['country_name']."</option>";
                                }
                                else
                                {
                                    echo"<option value='".$arrCountryIddCode[$intCount]['country_idd_code'].SEPARATOR.$arrCountryIddCode[$intCount]['country_name']."'>".$arrCountryIddCode[$intCount]['country_name']."</option>";
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
                        <div><input type="text" name="txtIddCode" value="<?php echo "+".$txtIddCode; ?>" readonly="readonly" placeholder="Idd Code" class="span1" id="">
                            <input type="text" name="txtMobileNumber"  value="<?php echo $txtMobile; ?>" maxlength="20" class="span2" style="width:187px" id=""></div></br>
                            <button name="btnUpdate" class="btn btn-primary" type="submit">Update</button>
                            <button name="btnCancel" class="btn btn-primary mL10" type="submit">Cancel</button>
                            <input type='hidden' name='contId' value="<?php echo $txtContactId; ?>">
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
