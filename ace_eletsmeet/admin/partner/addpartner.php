<?php
require_once('../../includes/global.inc.php');
require_once('../includes/config.inc.php');
require_once(ADM_CLASSES_PATH . 'admin_error.inc.php');
require_once(DBS_PATH . 'DataHelper.php');
require_once(DBS_PATH . 'objDataHelper.php');
$ADM_CONST_MODULE = 'partner';
$ADM_CONST_PAGEID = 'Add Partner';
require_once(ADM_INCLUDES_PATH . 'adm_authfunc.inc.php');
require_once(ADM_INCLUDES_PATH . 'adm_authorize.inc.php');
require_once(ADM_INCLUDES_PATH . 'partner_function.inc.php');

try
{
    if (isset($_POST['btnSubmit']))
    {
        $strPartnerName = trim($_POST['txtPartnerName']);
        $strEmail = trim($_POST['txtEmail']);
        $strPassword = trim($_POST['txtPassword']);
        //$strCnfPassword = trim($_POST['txtCnfPassword']);

        if (strlen($strPartnerName) <= 0)
        {
            $errors[] = 'Please enter your Partner Name.';
        }
        else if (!preg_match('/^[a-z A-Z 0-9 . _]+$/', $strPartnerName))
        {
            $errors[] = 'Partner Name must be alphanumeric.';
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
//        if ($strPassword != $strCnfPassword)
//        {
//            $errors[] = 'Password and Confirm Password do not match.';
//        }
        if (strlen($strPartnerName) != 0)
        {
            try
            {
                $isPartnerNameExists = IsPartnerNameExists($strPartnerName, $objDataHelper);
                if ($isPartnerNameExists[0]['COUNT(partner_name)'] == 1)
                {
                    $errors[] = 'Partner Name <b><font color=#006699>"' . $strPartnerName . '"</font></b> already exists.';
                }
            }
            catch (Exception $a)
            {
                throw new Exception("addclient.php : IsClientNameExists : Error in checking client name." . $a->getMessage(), 541);
            }
        }
        if (strlen($strEmail) != 0)
        {
            try
            {
                $isEmailExists = isPartnerEmailExists($strEmail, $objDataHelper);
                if ($isEmailExists[0]['@STATUS'] == 1)
                {
                    $errors[] = 'Email Address <b><font color=#006699>"' . $strEmail . '"</font></b> already exists.';
                }
            }
            catch (Exception $a)
            {
                throw new Exception("addpartner.php : isPartnerEmailExists : Error in checking email address." . $a->getMessage(), 541);
            }
        }

        if (sizeof($errors) == 0)
        {
            try
            {
                $partnerId = getPartnerId($objDataHelper);
            }
            catch (Exception $a)
            {
                throw new Exception("addpartner.php : getPartnerId : Error in getting Partner Id." . $a->getMessage(), 541);
            }
            try
            {
                $addPartnerDetails = InsertPartnerDetails($partnerId, $strPartnerName, $strEmail, md5($strPassword), GM_DATE, $objDataHelper);
                if ($addPartnerDetails == '1')
                {
                    $success = 'Partner <b><font color=#006699>"' . $strPartnerName . '" </font></b>added successfully.';
                }
                else
                {
                    $errors[] = 'Error in Adding.';
                }
            }
            catch (Exception $a)
            {
                throw new Exception("addpartner.php : insUserDetails : Error in adding partner details" . $a->getMessage(), 613);
            }
        }
    }
    if (isset($_POST['btnBack']))
    {
        header("Location:" . $ADMIN_SITE_ROOT . 'partner');
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
                    <div class="fL"><h3>Add Partner</h3></div>
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
                        <form name="addpartner" method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
                            <div class="frm-fields tBold">Partner Name<span class="required">&nbsp;*</span><span class="colon">:&nbsp;:&nbsp;</span></div>
                            <div><input type="text" name="txtPartnerName" value="<?php echo $strPartnerName; ?>" maxlength="50" class="span3" id=""></div>
                            <div class="frm-fields tBold">Email Address<span class="required">&nbsp;*</span><span class="colon">:&nbsp;&nbsp;</span></div>
                            <div><input type="text" name="txtEmail" value="<?php echo $strEmail; ?>" maxlength="100" class="span3" id=""></div>
                            <div class="frm-fields tBold">Password<span class="required">&nbsp;*</span><span class="colon">:&nbsp;&nbsp;</span></div>
                            <div><input type="password" name="txtPassword" maxlength="50" class="span3" id=""></div>
<!--                            <div class="frm-fields tBold">Confirm Password<span class="required">&nbsp;*</span><span class="colon">:&nbsp;&nbsp;</span></div>
                            <div><input type="password" name="txtCnfPassword" maxlength="50" class="span3" id=""></div>-->

                            <button name="btnSubmit" class="btn btn-primary" type="submit">Submit</button>
                            <button name="btnReset" class="btn btn-primary mL10" type="submit">Reset</button>
                        </form>
                        <?php
                    }
                    else
                    {
                        ?>
                        <form name="addpartner" method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
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

    </body>
</html>

