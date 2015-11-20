<?php
require_once('../../includes/global.inc.php');
require_once('../includes/config.inc.php');
require_once(ADM_CLASSES_PATH.'admin_error.inc.php');
require_once(DBS_PATH.'DataHelper.php');
require_once(DBS_PATH.'objDataHelper.php');
$ADM_CONST_MODULE = 'client';
$ADM_CONST_PAGEID = 'Add Client';
require_once(ADM_INCLUDES_PATH.'adm_authfunc.inc.php');
require_once(ADM_INCLUDES_PATH.'adm_authorize.inc.php');
require_once(ADM_INCLUDES_PATH.'client_function.inc.php');
require_once(ADM_INCLUDES_PATH.'adm_db_common_function.inc.php');

try
{
    try
    {
        $arrPartnerList = getPartnerList($objDataHelper);
    }
    catch (Exception $a)
    {
        throw new Exception("index.php : getPartnerList : Error in populating Partner List.".$a->getMessage(), 541);
    }
    
    try
    {
        $arrInstanceList = getInstanceDetails($objDataHelper);
    }
    catch (Exception $a)
    {
        throw new Exception("index.php : getInstanceDetails : Error in populating Instance List.".$a->getMessage(), 542);
    }
    
    if (isset($_POST['btnBack']))
    {
        header("Location:".$ADMIN_SITE_ROOT.'client');
    }
    
    if (isset($_POST['btnSubmit']))
    {
        $strPartnerInfo = trim($_POST['PartnerName']);
        $strClientName  = trim($_POST['txtClientName']);
        $strInstanceInfo = trim($_POST['InstanceName']);
        $strClientEmail = trim($_POST['txtClientEmail']);
        $strClientPwd = trim($_POST['txtClientPwd']);  
             
        
        if ($strPartnerInfo == '---')
        {
            $errors[] = 'Please select Partner Name.';
        }
                        
        if (strlen($strClientName) <= 0)
        {
            $errors[] = 'Please enter Client Name.';
        }
        else if (!preg_match('/^[a-z A-Z 0-9 . _ &]+$/', $strClientName))	
        {
            $errors[] = 'Client Name must be alphanumeric.';
        }
      	//else if (!preg_match('/^[a-z A-Z 0-9 . _]+$/', $strClientName))
      
        if (strlen($strClientEmail) <= 0)
        {
            $errors[] = 'Please enter your Email Address.';
        }
        else if (eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $strClientEmail) != 1)
        {
            $errors[] = 'Please enter valid Email Address.';
        }
         
         
        if (strlen($strClientPwd) <= 0)
        {
            $errors[] = 'Please enter your Password.';
        }
        else if (strlen($strClientPwd) < 3 || strlen($strClientPwd) > 15)
        {
            $errors[] = 'Password must consist of minimum 3 and maximum 15 characters.';
        }
        
        
        if ($strPartnerInfo == '---')
        {
            $errors[] = 'Please select Instance Name.';
        }
                     
        if ($strPartnerInfo != '---')
        {
            $strPartnerDtls = split('_', $strPartnerInfo);
            $strPartnerId = $strPartnerDtls[0];
            $strPartnerName = $strPartnerDtls[1];
        }
        
        if ($strInstanceInfo != '---')
        {
            $strInstanceDtls = split('_', $strInstanceInfo);
            $strInstanceId = $strInstanceDtls[0];
            $strInstanceName = $strInstanceDtls[1];
        }
        
        if (strlen($strClientName) != 0)
        {
            try
            {
                $isClientNameExists = IsClientNameExists($strClientName, $objDataHelper);
                if ($isClientNameExists[0]['COUNT(client_name)'] == 1)
                {
                    $errors[] = 'Client Name <b><font color=#006699>"'.$strClientName.'"</font></b> already exists.';
                }
            }
            catch (Exception $a)
            {
                throw new Exception("addclient.php : IsClientNameExists : Error in checking client name.".$a->getMessage(), 541);
            }
        }
        
        if (sizeof($errors) == 0)
        {
            try
            {
                $clientId = getClientId($objDataHelper);
            }
            catch (Exception $a)
            {
                throw new Exception("addclient.php : getClientId : Error in getting Client Id.".$a->getMessage(), 541);
            }
            
            try
            {
                $arrInstanceDetails = getInstanceDetailsById($strInstanceId, $objDataHelper);
            }
            catch (Exception $a)
            {
                throw new Exception("addclient.php : getClientId : Error in getting Instnace Detsils By Id.".$a->getMessage(), 541);
            }

            $strServerURL   = trim($arrInstanceDetails[0]['instance_url']);
            $strServerSalt  = trim($arrInstanceDetails[0]['instance_salt']);
            $strLogoutURL   = trim($arrInstanceDetails[0]['instance_logout_url']);
            $strServerAPI   = trim($arrInstanceDetails[0]['instance_api_url']);
            
            try
            {        
                $addClientDetails = InsertClientDetails($clientId, $strPartnerId, $strClientName, $strClientEmail , md5($strClientPwd) , GM_DATE, $strLogoutURL, $strServerURL, $strServerSalt, $strServerAPI, $objDataHelper);
                if ($addClientDetails == '1')
                {
                    $success = 'Client <b><font color=#006699>"'.$strClientName.'" </font></b>added successfully.';
                }
                else
                {
                    $errors[] = 'Error in Adding.';
                }
            }
            catch (Exception $a)
            {
                throw new Exception("addclient.php : InsertClientDetails : Error in adding client details".$a->getMessage(), 613);
            }
        }
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
        <?php include (ADM_INCLUDES_PATH.'head.php'); ?>    
    </head>
    <!-- Head content Area -->

    <body>

        <!-- Navigation Bar, After Login Menu &  Product Logo -->
        <?php include (ADM_INCLUDES_PATH.'navigation.php'); ?>    
        <!-- Navigation Bar, After Login Menu &  Product Logo -->

        <!-- Main content Area -->
        <div class="container">
            <!-- Main hero unit for a primary marketing message or call to action -->

            <!-- Middle content Area -->
            <div class="row">

                <div class="span12">
                    <div class="fL"><h3>Add Client</h3></div>
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
                        <form name="addclient" method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
                            <div class="frm-fields tBold">Partner Name<span class="required">&nbsp;*</span><span class="colon">:&nbsp;&nbsp;</span></div>
                            <?php
                            echo"<div><select name='PartnerName' class='span3' id='PartnerName' > ";
                            if (!empty($arrPartnerList))
                            {
                                echo"<option value='---'>Select Partner Name</option>";
                                for ($intCount = 0; $intCount < sizeof($arrPartnerList); $intCount++)
                                {
                                    if ($arrPartnerList[$intCount]['partner_id'] == $strPartnerId)
                                    {
                                        echo"<option value='".$arrPartnerList[$intCount]['partner_id']._.$arrPartnerList[$intCount]['partner_name']."' selected>".$arrPartnerList[$intCount]['partner_name']."</option>";
                                    }
                                    else
                                    {
                                        echo"<option value='".$arrPartnerList[$intCount]['partner_id']._.$arrPartnerList[$intCount]['partner_name']."'>".$arrPartnerList[$intCount]['partner_name']."</option>";
                                    }
                                }
                            }
                            else
                            {
                                echo"<option value ='---'>Partner Name List not available</option>";
                            }echo"
                                          </select></div>";
                            ?>
                             <div class="mB10"></div>
                             
                            <div class="frm-fields tBold">Client Name<span class="required">&nbsp;*</span><span class="colon">:&nbsp;&nbsp;</span></div>
                            <div><input type="text" name="txtClientName" value="<?php echo $strClientName ?>" maxlength="100" class="span3" id=""></div>
                            <div class="mB10"></div>
                             
                           <div class="frm-fields tBold"> Email Address<span class="required">&nbsp;*</span><span class="colon">:&nbsp;&nbsp;</span></div>
                            <div><input type="text" name="txtClientEmail" value="<?php echo $strClientEmail ?>" maxlength="100" class="span3" id=""></div>
                           <div class="mB10"></div>
                             
                           <div class="frm-fields tBold">Password<span class="required">&nbsp;*</span><span class="colon">:&nbsp;&nbsp;</span></div>
                           <div><input type="password" name="txtClientPwd" value="" maxlength="50" class="span3" id=""></div>
                           <div class="mB10"></div>
                             
                           
                             
                            <div class="frm-fields tBold">Instance Name<span class="required">&nbsp;*</span><span class="colon">:&nbsp;&nbsp;</span></div>
                            <?php
                            echo"<div><select name='InstanceName' class='span3' id='InstanceName' > ";
                            if (!empty($arrInstanceList))
                            {
                                echo"<option value='---'>Select Instance Name</option>";
                                for ($intCount = 0; $intCount < sizeof($arrInstanceList); $intCount++)
                                {
                                    if ($arrInstanceList[$intCount]['instance_id'] == $strInstanceId)
                                    {
                                        echo"<option value='".$arrInstanceList[$intCount]['instance_id']._.$arrInstanceList[$intCount]['instance_name']."' selected>".$arrInstanceList[$intCount]['instance_name']."</option>";
                                    }
                                    else
                                    {
                                        echo"<option value='".$arrInstanceList[$intCount]['instance_id']._.$arrInstanceList[$intCount]['instance_name']."'>".$arrInstanceList[$intCount]['instance_name']."</option>";
                                    }
                                }
                            }
                            else
                            {
                                echo"<option value ='---'>Instance Name List not available</option>";
                            }echo"
                                          </select></div>";
                            ?>
                            <div class="mB10"></div>
                            <button name="btnSubmit" class="btn btn-primary" type="submit">Submit</button>
                            <button name="btnReset" class="btn btn-primary mL10" type="submit">Reset</button>
                        </form>
                        <?php
                    }
                    else
                    {
                        ?>
                        <form name="addclient" method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
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
        <?php include (ADM_INCLUDES_PATH.'footer.php'); ?>
        <!-- Footer content Area -->

        <!-- java script  -->
        <?php include (ADM_INCLUDES_PATH.'jsinclude.php'); ?>
        <!-- java script  -->

    </body>
</html>
