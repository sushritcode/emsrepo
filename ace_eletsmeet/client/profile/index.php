<?php
require_once('../../includes/global.inc.php');
require_once('../includes/config.inc.php');
require_once(CLIENT_CLASSES_PATH . 'client_error.inc.php');
require_once(CLIENT_DBS_PATH . 'DataHelper.php');
require_once(CLIENT_DBS_PATH . 'objDataHelper.php');
require_once(CLIENT_INCLUDES_PATH . 'client_authfunc.inc.php');
$CLIENT_CONST_MODULE = 'cl_profile';
$CLIENT_CONST_PAGEID = 'Client Profile';
require_once(CLIENT_INCLUDES_PATH . 'client_authorize.inc.php');
require_once(CLIENT_INCLUDES_PATH . 'client_db_function.inc.php');
require_once(CLIENT_INCLUDES_PATH . 'profile_function.inc.php');

//data population start	
$form_table_map = client_profile_form_table_map();

try
{
    $arrClientdetails = getClientDetailsByClientId($strSetClient_ID, $objDataHelper);
}
catch (Exception $a)
{
    throw new Exception("adm_authorize.inc.php : Error in getAdminUserDetailsByID" . $a->getMessage(), 161);
}


$clientdetails = $arrClientdetails[0];

$arrIndustryType = getAllIndustryType($objDataHelper);
$optionIndustryType = "";
for ($cnt = 0; $cnt < count($arrIndustryType); $cnt++)
{
    $selected = ($clientdetails[$form_table_map['frmCompany']['indutrytype']] == $arrIndustryType[$cnt]['industry_id']) ? "selected" : "";
    $optionIndustryType.="<option value='" . $arrIndustryType[$cnt]['industry_id'] . "' " . $selected . ">" . $arrIndustryType[$cnt]['industry_name'] . "</option>";
}

$arrTimezonesType = getTimezoneList($objDataHelper);
$optionTimezonesType = "";
for ($cnt = 0; $cnt < count($arrTimezonesType); $cnt++)
{
    $optionTimezonesType.="<option value='" . $arrTimezonesType[$cnt]['ct_id'] . "'>" . $arrTimezonesType[$cnt]['timezones'] . " - " . $arrTimezonesType[$cnt]['country_name'] . "</option>";
}

$arrDistinctCountry = getCountryDetails($objDataHelper);
$optionCountry = "";
for ($cnt = 0; $cnt < count($arrDistinctCountry); $cnt++)
{
    $selected = ($clientdetails[$form_table_map['frmaddress']['country']] == $arrDistinctCountry[$cnt]['country_name']) ? "selected" : "";
    $optionCountry.="<option value='" . $arrDistinctCountry[$cnt]['country_name'] . "' " . $selected . ">" . $arrDistinctCountry[$cnt]['country_name'] . " - " . $arrDistinctCountry[$cnt]['country_code'] . "</option>";
}
//data population end
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <!-- HEAD CONTENT AREA -->
        <?php include (CLIENT_HEAD_INCLUDES_PATH); ?>
        <!-- HEAD CONTENT AREA -->

        <!-- CSS n JS CONTENT AREA -->
<?php include (CLIENT_CSS_INCLUDES_PATH); ?>    
        <!-- CSS n JS CONTENT AREA -->
    </head>

    <body class="no-skin">

        <!-- TOP NAVIGATION BAR START -->
        <div id="navbar" class="navbar navbar-default">
<?php include (CLIENT_TOP_NAVIGATION_INCLUDES_PATH); ?>    
        </div>
        <!-- TOP NAVIGATION BAR END -->

        <!-- MAIN CONTAINER START -->
        <div class="main-container" id="main-container">
            <script type="text/javascript">
                var BASEURL = "<?php echo $CLIENT_SITE_ROOT; ?>";
                try {
                    ace.settings.check('main-container', 'fixed')
                } catch (e) {
                }
            </script>

            <!-- SIDE NAVIGATION BAR START -->
            <div id="sidebar" class="sidebar responsive">
                <?php include (CLIENT_SIDEBAR_INCLUDES_PATH); ?>    
            </div>
            <!-- SIDE NAVIGATION BAR END -->

            <!-- MAIN CONTENT START -->
            <div class="main-content">
                <div class="main-content-inner">

                    <!-- BREADCRUMBS N SEARCH BAR START -->
                    <div class="breadcrumbs" id="breadcrumbs">
                        <?php include (CLIENT_BREADCRUMBS_INCLUDES_PATH); ?>
                    </div>
                    <!-- BREADCRUMBS N SEARCH BAR END -->                       

                    <!--  PAGE CONTENT START -->
                    <div class="page-content">

                        <!-- SETTING CONTAINER START -->
                        <!--IF NEEDED then WE ADD -->
                        <!-- SETTING CONTAINER END -->

                        <!-- PAGE HEADER -->
                        <div id='ajax_loader' style="width: 100%; height: 100%; position: absolute; left: 0px; top: 0px; background: transparent none repeat scroll 0% 0%; z-index: 20000;display:none;">
                            <img src="<?php echo IMG_PATH ?>loading.gif" style="position: relative; top: 50%; left: 50%;"></img>
                        </div>

                        <div class="page-header">
                            <h1>
                                Profile<small><i class="ace-icon fa fa-angle-double-right"></i>&nbsp; edit&#39;s &amp; info&#39;s</small>
                            </h1>
                        </div>
                        <!-- PAGE HEADER -->

                        <div class="row">
                            <div class="col-xs-12">
                                <!-- PAGE CONTENT START -->
                                <div class="row" id="alert" style="display:none;">
                                    <div class="col-sm-12">
                                        <div id="succ" class="col-sm-12 alert alert-block alert-success" style="display:none;">
                                            <span id="successmsg"></span>
                                        </div>
                                        <div id="err" class="alert alert-danger" style="display:none;">
                                            <span id="errormsg"></span>
                                        </div>
                                    </div>
                                </div>

                                <!--                                <div class="space-20"></div>-->

                                <div class="row">
                                    <div class="col-sm-12">
                                        <!-- #section:elements.tab -->
                                        <div class="tabbable">
                                            <ul id="myTab" class="nav nav-tabs">
                                                <li class="active">
                                                    <a href="#basic" data-toggle="tab" aria-expanded="false">
                                                        <i class="green ace-icon fa fa-pencil-square-o bigger-120"></i>
                                                        Basic Details
                                                    </a>
                                                </li>
                                                <li class="">
                                                    <a href="#contact" data-toggle="tab" aria-expanded="false">
                                                        <i class="green ace-icon glyphicon glyphicon-user"></i>
                                                        Contact Details
                                                    </a>
                                                </li>
                                                <li class="">
                                                    <a href="#addressdtls" data-toggle="tab" aria-expanded="false">
                                                        <i class="green ace-icon fa fa-home bigger-120"></i>
                                                        Address details
                                                    </a>
                                                </li>
                                                <li class="">
                                                    <a href="#Social" data-toggle="tab" aria-expanded="false">
                                                        <i class="green ace-icon fa fa-users bigger-120"></i>
                                                        Social Media
                                                    </a>
                                                </li>
                                                <!--li class="">
                                                        <a href="#Billing" data-toggle="tab" aria-expanded="false">
                                                                <i class="green ace-icon fa fa-credit-card  bigger-120"></i>
                                                                Billing & Localisation
                                                        </a>
                                                </li-->	
                                                <li class="">
                                                    <a href="#Company" data-toggle="tab" aria-expanded="false">
                                                        <i class="green ace-icon fa fa-industry bigger-120"></i>
                                                        Company Details
                                                    </a>
                                                </li>
                                                <li class="">
                                                    <a href="#password" data-toggle="tab" aria-expanded="false">
                                                        <i class="red ace-icon fa fa-lock bigger-120"></i>
                                                        Change Password
                                                    </a>
                                                </li>

                                                <!--li class="">
                                                        <a href="#image" data-toggle="tab" aria-expanded="false">
                                                                <i class="red ace-icon fa fa-lock bigger-120"></i>
                                                                Display Picture
                                                        </a>
                                                </li-->									</ul>

                                            <div class="tab-content">
                                                <div class="tab-pane fade in active" id="basic">
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <div class="space-20"></div>

                                                            <form class="form-horizontal" role="form" name="frmbasic" id="frmbasic">
                                                                <div class="form-group">
                                                                    <label for="form-field-1" class="col-sm-2 control-label no-padding-right"> Email Id / Login Id </label>
                                                                    <div class="col-sm-9" style="padding:6px 20px;">
                                                                        <b><?php print $clientdetails['client_email_address']; ?></b>
                                                                    </div>
                                                                </div>

                                                                <div class="space-4"></div>


                                                                <div class="form-group">
                                                                    <label for="form-field-1" class="col-sm-2 control-label no-padding-right"> Display Name </label>
                                                                    <div class="col-sm-9">

                                                                        <input type="text" class="col-sm-5" placeholder="Display Name" id="displayname" name="displayname" required for="basic" value="<?php echo $clientdetails[$form_table_map['frmbasic']['displayname']]; ?>" validate="yes" msg="Display Name cannot be empty , only alphabets allowed">
                                                                    </div>
                                                                </div>

                                                                <div class="space-4"></div>

                                                                <div class="form-group">
                                                                    <label for="form-field-1" class="col-sm-2 control-label no-padding-right"> First Name </label>
                                                                    <div class="col-sm-9">
                                                                        <input type="text" class="col-sm-5" placeholder="First Name" id="firstname" name="firstname" required for="basic" value="<?php echo $clientdetails[$form_table_map['frmbasic']['firstname']]; ?>" validate="yes" msg="First Name cannot be empty">
                                                                    </div>
                                                                </div>

                                                                <div class="space-4"></div>

                                                                <div class="form-group">
                                                                    <label for="form-field-1" class="col-sm-2 control-label no-padding-right"> Last Name </label>
                                                                    <div class="col-sm-9">
                                                                        <input type="text" class="col-sm-5" placeholder="Last Name" id="lastname" name="lastname" required for="basic" value="<?php echo $clientdetails[$form_table_map['frmbasic']['lastname']]; ?>" validate="yes" msg="Last Name cannot be empty">
                                                                    </div>
                                                                </div>

                                                                <div class="space-20"></div>
                                                                <input type="submit" class="btn btn-info" value="Save Basic Details" onClick="javascript:return sendData('frmbasic', 'reset');">
                                                            </form>
                                                        </div>
                                                    </div>												
                                                </div>
                                                
                                                <div class="tab-pane fade" id="contact">
                                                    <div class="space-20"></div>
                                                    <form class="form-horizontal" role="form" name="frmcontact" id = "frmcontact">

                                                        <div class="form-group">
                                                            <label for="form-field-1" class="col-sm-2 control-label no-padding-right"> Phone Number</label>
                                                            <div class="col-sm-9">
                                                                <input type="text" class="col-sm-5" placeholder="Phone Number" id="phone1" name="phone1" required for="contact" value="<?php echo $clientdetails[$form_table_map['frmcontact']['phone1']]; ?>">
                                                            </div>
                                                        </div>
                                                        <div class="space-4"></div>


                                                        <div class="form-group">
                                                            <label for="form-field-1" class="col-sm-2 control-label no-padding-right"> Mobile Number </label>
                                                            <div class="col-sm-9">
                                                                <input type="text" class="col-sm-5" placeholder="Mobile Number" id="mobile" name="mobile" required for="contact"  value="<?php echo $clientdetails[$form_table_map['frmcontact']['mobile']]; ?>" validate="yes" msg="Please enter Mobile number"> 
                                                            </div>
                                                        </div> 
                                                        <div class="space-4"></div>

                                                        <div class="form-group">
                                                            <label for="form-field-1" class="col-sm-2 control-label no-padding-right"> Secondry Email </label>
                                                            <div class="col-sm-9">
                                                                <input type="text" class="col-sm-5" placeholder="Secondry Email" id="SecondryEmail" name="SecondryEmail" required for="contact"  value="<?php echo $clientdetails[$form_table_map['frmcontact']['SecondryEmail']]; ?>" >
                                                            </div>
                                                        </div> 
                                                        <div class="space-20"></div>
                                                        <input type="submit" class="btn btn-info" value="Save Contact Details" onClick="javascript:return sendData('frmcontact', 'reset');">
                                                    </form>
                                                </div>
                                                <div class="tab-pane fade" id="addressdtls">
                                                    <form class="form-horizontal" role="form" name="frmaddress" id = "frmaddress">
                                                        <div class="space-20"></div>


                                                        <div class="form-group">
                                                            <label for="form-field-1" class="col-sm-2 control-label no-padding-right"> Address </label>
                                                            <div class="col-sm-9">
                                                                <textarea placeholder="Address" name="address" id="address" class="col-sm-5" for="address"  validate="yes" msg="Please enter your address for correspondance"><?php echo trim($clientdetails[$form_table_map['frmaddress']['address']]); ?></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="space-4"></div> 	

                                                        <div class="form-group">
                                                            <label for="form-field-1" class="col-sm-2 control-label no-padding-right"> Land Mark </label>
                                                            <div class="col-sm-9">
                                                                <input type="text" class="col-sm-5" placeholder="Land Mark" id="landmark" name="landmark" required for="address" value="<?php echo $clientdetails[$form_table_map['frmaddress']['landmark']]; ?>" validate="yes" msg="Please enter the landmark" >
                                                            </div>
                                                        </div> 
                                                        <div class="space-4"></div>


                                                        <div class="form-group">
                                                            <label for="form-field-1" class="col-sm-2 control-label no-padding-right"> City </label>
                                                            <div class="col-sm-9">
                                                                <input type="text" class="col-sm-5" placeholder="City" id="city" name="city" required for="address" value="<?php echo $clientdetails[$form_table_map['frmaddress']['city']]; ?>" validate="yes" msg="Please enter nearst landmark" >
                                                            </div>
                                                        </div> 
                                                        <div class="space-4"></div>

                                                        <div class="form-group">
                                                            <label for="form-field-1" class="col-sm-2 control-label no-padding-right"> Select Country </label>
                                                            <div class="col-sm-9">
                                                                <select class="col-sm-5" id="form-field-select-1" for="address" name="country" id="country" validate="yes" msg="Please select the country">
                                                                    <option value="">Select Country</option>
                                                                        <?php echo $optionCountry; ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="space-20"></div>
                                                        <input type="submit" class="btn btn-info" value="Save Address Details" onClick="javascript:return sendData('frmaddress', 'reset');">
                                                    </form>


                                                </div>
                                                <div class="tab-pane fade" id="Social">
                                                    <form class="form-horizontal" role="form" name="frmSocial" id="frmSocial">
                                                        <div class="space-20"></div>

                                                        <div class="form-group">
                                                            <label for="form-field-1" class="col-sm-2 control-label no-padding-right"> Facebook ID </label>
                                                            <div class="col-sm-9">
                                                                <textarea placeholder="Facebook ID" name="facebook" id="facebook" class="col-sm-5" for="Social"> <?php echo $clientdetails[$form_table_map['frmSocial']['facebook']]; ?> </textarea>
                                                            </div>
                                                        </div>

                                                        <div class="space-4"></div>
                                                        <div class="form-group">
                                                            <label for="form-field-1" class="col-sm-2 control-label no-padding-right"> Twitter </label>
                                                            <div class="col-sm-9">
                                                                <textarea placeholder="Twitter" name="twitter" id="twitter" class="col-sm-5" for="Social"><?php echo $clientdetails[$form_table_map['frmSocial']['twitter']]; ?> </textarea>
                                                            </div>
                                                        </div>

                                                        <div class="space-4"></div>
                                                        <div class="form-group">
                                                            <label for="form-field-1" class="col-sm-2 control-label no-padding-right"> Google Plus </label>
                                                            <div class="col-sm-9">
                                                                <textarea placeholder="Google Plus" name="googleplus" id="googleplus" class="col-sm-5" for="Social"><?php echo $clientdetails[$form_table_map['frmSocial']['googleplus']]; ?> </textarea>
                                                            </div>
                                                        </div>

                                                        <div class="space-4"></div>
                                                        <div class="form-group">
                                                            <label for="form-field-1" class="col-sm-2 control-label no-padding-right"> LinkedIn </label>
                                                            <div class="col-sm-9">
                                                                <textarea placeholder="LinkedIn" name="linkedin" id="linkedin" class="col-sm-5" for="Social"><?php echo $clientdetails[$form_table_map['frmSocial']['linkedin']]; ?> </textarea>
                                                            </div>
                                                        </div>

                                                        <div class="space-20"></div>
                                                        <input type="submit" class="btn btn-info" value="Save Social info" onClick="javascript:return sendData('frmSocial', 'reset');">
                                                    </form>
                                                </div>
                                                <div class="tab-pane fade" id="Company">
                                                    <form class="form-horizontal" role="form" name="frmCompany" id="frmCompany">
                                                        <div class="space-20"></div>

                                                        <div class="form-group">
                                                            <label for="form-field-1" class="col-sm-2 control-label no-padding-right"> Industry Type </label>
                                                            <div class="col-sm-9">
                                                                <select class="col-sm-5" id="form-field-select-1" for="Company" name="indutrytype" id="indutrytype" validate="yes" msg="Please select the industry type">
                                                                    <option value="">Industry Type</option>
                                                                    <?php echo $optionIndustryType; ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="space-4"></div>

                                                        <div class="form-group">
                                                            <label for="form-field-1" class="col-sm-2 control-label no-padding-right"> Company Name </label>
                                                            <div class="col-sm-9">
                                                                <input type="text" class="col-sm-5" placeholder="Company Name" id="companyname" name="companyname" required  for="Company" value="<?php echo $clientdetails[$form_table_map['frmCompany']['companyname']]; ?>" validate="yes" msg="Please enter the name of the company">
                                                            </div>
                                                        </div>

                                                        <div class="space-4"></div>

                                                        <div class="form-group">
                                                            <label for="form-field-1" class="col-sm-2 control-label no-padding-right"> Nature Of Biusiness </label>
                                                            <div class="col-sm-9">
                                                                <input type="text" class="col-sm-5" placeholder="Nature Of Business" id="natureofbusiness" name="natureofbusiness" required  for="Company"  value="<?php echo $clientdetails[$form_table_map['frmCompany']['natureofbusiness']]; ?>" validate="yes" msg="Please mention the nature of business">
                                                            </div>
                                                        </div>

                                                        <div class="space-4"></div>

                                                        <div class="form-group">
                                                            <label for="form-field-1" class="col-sm-2 control-label no-padding-right"> Company URI </label>
                                                            <div class="col-sm-9">
                                                                <input type="text" class="col-sm-5" placeholder="Company URI" id="companyURL" name="companyURL" required  for="Company"  value="<?php echo $clientdetails[$form_table_map['frmCompany']['companyURL']]; ?>">
                                                            </div>
                                                        </div>

                                                        <div class="space-4"></div>

                                                        <div class="form-group">
                                                            <label for="form-field-1" class="col-sm-2 control-label no-padding-right"> Brief Discription Of Company </label>
                                                            <div class="col-sm-9">
                                                                <textarea placeholder="Brief Discription Of Company" id="briefDescription" name="briefDescription" class="col-sm-5"  for="Company" validate="yes" msg="please enter a brief description"> <?php echo $clientdetails[$form_table_map['frmCompany']['briefDescription']]; ?> </textarea>
                                                            </div>
                                                        </div>
                                                        <div class="space-20"></div>
                                                        <input type="submit" class="btn btn-info" value="Save Company Details" onClick="javascript:return sendData('frmCompany', 'reset');">
                                                    </form>
                                                </div>

                                                <div class="tab-pane fade" id="Billing">

                                                    <form class="form-horizontal" role="form" name="frmBilling" id="frmBilling">
                                                        <div class="space-20"></div>

                                                        <div class="form-group">
                                                            <label for="form-field-1" class="col-sm-2 control-label no-padding-right"> Billing Name </label>
                                                            <div class="col-sm-9">
                                                                <input type="text" class="col-sm-5" placeholder="Billing Name" id="billersname" name="billersname" required>
                                                            </div>
                                                        </div>
                                                        <div class="space-4"></div>

                                                        <div class="form-group">
                                                            <label for="form-field-1" class="col-sm-2 control-label no-padding-right"> Select Currency </label>
                                                            <div class="col-sm-9">
                                                                <select class="col-sm-5" id="currency" name="currency">
                                                                    <option value="">Select Currency</option>
                                                                    <option value="INR">INR - Indian Rupees</option>
                                                                    <option value="USD">USD - United State Dollars</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="space-4"></div>

                                                        <div class="form-group">
                                                            <label for="form-field-1" class="col-sm-2 control-label no-padding-right"> Select TimeZone </label>
                                                            <div class="col-sm-9">
                                                                <select class="col-sm-5" id="timezone" name="timezone">
                                                                    <option value="">Select TimeZone</option>
                                                                    <?php echo $optionTimezonesType; ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="space-20"></div>
                                                        <input type="submit" class="btn btn-info" value="Submit Button">
                                                    </form>


                                                </div>
                                                <div class="tab-pane fade" id="image">	
                                                    <form class="form-horizontal" role="form" name="frmpicture" id="frmpicture" enctype="multipart/form-data">
                                                        <input name="MAX_FILE_SIZE" value="102400" type="hidden">
                                                        <div class="space-20"></div>	
                                                        <div class="form-group">
                                                            <label for="form-field-1" class="col-sm-2 control-label no-padding-right"> Upload Picture </label>
                                                            <div class="col-sm-9">
                                                                <!-- image start-->
                                                                <div class="row">
                                                                    <div class="col-sm-4">
                                                                        <div class="widget-box">
                                                                            <div class="widget-body">
                                                                                <div class="widget-main">
                                                                                    <div class="form-group">
                                                                                        <div class="col-xs-12">
                                                                                            <!-- #section:custom/file-input -->
                                                                                            <label class="ace-file-input">
                                                                                                <input type="file" id="id-input-file-2">
                                                                                                <span data-title="Choose" class="ace-file-container">
                                                                                                    <span data-title="No File ..." class="ace-file-name">
                                                                                                        <i class=" ace-icon fa fa-upload"></i>
                                                                                                    </span>
                                                                                                </span>
                                                                                                <a href="#" class="remove">
                                                                                                    <i class=" ace-icon fa fa-times"></i>
                                                                                                </a>
                                                                                            </label>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="form-group">
                                                                                        <div class="col-xs-12">
                                                                                            <label class="ace-file-input ace-file-multiple">
                                                                                                <input type="file" id="id-input-file-3" multiple="">
                                                                                                <span data-title="Drop files here or click to choose" class="ace-file-container">
                                                                                                    <span data-title="No File ..." class="ace-file-name">
                                                                                                        <i class=" ace-icon ace-icon fa fa-cloud-upload"></i>
                                                                                                    </span>
                                                                                                </span>
                                                                                                <a href="#" class="remove">
                                                                                                    <i class=" ace-icon fa fa-times"></i>
                                                                                                </a>
                                                                                            </label>
                                                                                            <!-- /section:custom/file-input -->
                                                                                        </div>
                                                                                    </div>
                                                                                    <!-- #section:custom/file-input.filter -->
                                                                                    <label>
                                                                                        <input type="checkbox" class="ace" id="id-file-format" name="file-format">
                                                                                        <span class="lbl"> Allow only images</span>
                                                                                    </label>

                                                                                    <!-- /section:custom/file-input.filter -->
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <!-- image end-->
                                                            </div>
                                                        </div>
                                                        <div class="space-20"></div>
                                                        <input type="submit" class="btn btn-info" value="Save Picture" onClick="javascript:return sendData('frmpicture', 'reset');">




                                                    </form>


                                                </div>



                                                <div class="tab-pane fade" id="password">
                                                    <form class="form-horizontal" role="form" name="frmpassword" id="frmpassword">
                                                        <div class="space-20"></div>

                                                        <div class="form-group">
                                                            <label for="form-field-1" class="col-sm-2 control-label no-padding-right"> Current Password </label>
                                                            <div class="col-sm-9">
                                                                <input type="password" class="col-sm-5" placeholder="Current Password" id="currentpwd" name="currentpwd" for="password" validate="yes" msg="Please enter the Current Password">
                                                            </div>
                                                        </div>
                                                        <div class="space-4"></div>
                                                        <div class="form-group">
                                                            <label for="form-field-1" class="col-sm-2 control-label no-padding-right"> New Password </label>
                                                            <div class="col-sm-9">
                                                                <input type="password" class="col-sm-5" placeholder="New Password" id="newpwd" name="newpwd" for="password">
                                                            </div>
                                                        </div>
                                                        <div class="space-4"></div>
                                                        <div class="form-group">
                                                            <label for="form-field-1" class="col-sm-2 control-label no-padding-right"> Confirm New Password </label>
                                                            <div class="col-sm-9">
                                                                <input type="password" class="col-sm-5" placeholder="Confirm New Password" id="cnfnewpwd" name="cnfnewpwd" for="password">
                                                            </div>
                                                        </div>
                                                        <div class="space-20"></div>
                                                        <input type="submit" class="btn btn-info" value="Save new Password" onClick="javascript:return validatePassword();">
                                                    </form>


                                                </div>

                                            </div>
                                            </form>
                                        </div>

                                        <!-- /section:elements.tab -->
                                    </div><!-- /.col -->

                                    <div class="vspace-6-sm"></div>


                                </div>



                                <!-- PAGE CONTENT END -->
                            </div>
                        </div> 

                    </div>
                    <!-- PAGE CONTENT END -->

                </div>
            </div>
            <!--  MAIN CONTENT END -->

            <!-- FOOTER START -->
            <div class="footer">
                <?php include (CLIENT_FOOTER_INCLUDES_PATH); ?>  
            </div>
            <!-- FOOTER END -->

            <a href="#" id="btn-scroll-up" class="btn-scroll-up btn btn-sm btn-inverse">
                <i class="ace-icon fa fa-angle-double-up icon-only bigger-110"></i>
            </a>

        </div>
        <!-- MAIN CONTAINER END -->

        <!-- JAVA SCRIPT -->
        <?php include (CLIENT_JS_INCLUDES_PATH . 'static_js_includes.php'); ?>  
        <?php include (CLIENT_JS_INCLUDES_PATH . 'other_js_includes.php'); ?>  
        <!-- JAVA SCRIPT -->

    </body>
    <script type="text/javascript" src="<?php echo CLIENT_JS_PATH; ?>profile.js"></script>
</html>
