<?php
require_once('../includes/global.inc.php');
require_once(CLASSES_PATH.'error.inc.php');
require_once(DBS_PATH.'DataHelper.php');
require_once(DBS_PATH.'objDataHelper.php');
require_once(INCLUDES_PATH.'cm_authfunc.inc.php');
$CONST_MODULE = 'profile';
$CONST_PAGEID = 'My Profile';
require_once(INCLUDES_PATH.'cm_authorize.inc.php');
require_once(INCLUDES_PATH.'common_function.inc.php');
require_once(INCLUDES_PATH.'profile_function.inc.php');



//data population start	

$form_table_map = profile_form_table_map();
$userdetails = getUserDetailsByID( $strCK_user_id , $objDataHelper);
$userdetails = $userdetails[0];


$arrIndustryType = getAllIndustryType($objDataHelper);	
$optionIndustryType = "";
	for($cnt=0;$cnt< count($arrIndustryType);$cnt++)
	{
		$selected = ($userdetails[$form_table_map['frmCompany']['indutrytype']] == $arrIndustryType[$cnt]['industry_id'])? "selected":"";
		$optionIndustryType.="<option value='".$arrIndustryType[$cnt]['industry_id']."' ".$selected.">".$arrIndustryType[$cnt]['industry_name']."</option>";

	}

$arrTimezonesType = getTimezoneList($objDataHelper);
$optionTimezonesType = "";	
	for($cnt=0;$cnt< count($arrTimezonesType);$cnt++)
	{
		$optionTimezonesType.="<option value='".$arrTimezonesType[$cnt]['ct_id']."'>".$arrTimezonesType[$cnt]['timezones']." - ".$arrTimezonesType[$cnt]['country_name']."</option>";

	}

$arrDistinctCountry = getDistinctCountry($objDataHelper);
$optionCountry ="";	
	for($cnt=0;$cnt< count($arrDistinctCountry);$cnt++)
	{
		$selected = ($userdetails[$form_table_map['frmaddress']['country']] == $arrDistinctCountry[$cnt]['country_id'])? "selected":"";

		
		$optionCountry.="<option value='".$arrDistinctCountry[$cnt]['country_id']."' ".$selected.">".$arrDistinctCountry[$cnt]['country_name']." - ".$arrDistinctCountry[$cnt]['country_code']."</option>";

	}

//data population end

?>

<!DOCTYPE html>
<html lang="en">
    <head>
       <!-- HEAD CONTENT AREA -->
        <?php include (INCLUDES_PATH.'head.php'); ?>
        <!-- HEAD CONTENT AREA -->

         <!-- CSS n JS CONTENT AREA -->
         <?php include (INCLUDES_PATH.'css_include.php'); ?>    
         <!-- CSS n JS CONTENT AREA -->
    </head>

    <body class="no-skin">
      
        <!-- TOP NAVIGATION BAR START -->
        <div id="navbar" class="navbar navbar-default">
            <?php include (INCLUDES_PATH.'top_navigation.php'); ?>    
        </div>
        <!-- TOP NAVIGATION BAR END -->
        
         <!-- MAIN CONTAINER START -->
        <div class="main-container" id="main-container">
            <script type="text/javascript">
		var BASEURL = "<?php echo $SITE_ROOT;?>";
                try {
                    ace.settings.check('main-container', 'fixed')
                } catch (e) {
                }
            </script>

            <!-- SIDE NAVIGATION BAR START -->
            <div id="sidebar" class="sidebar responsive">
                 <?php include (INCLUDES_PATH.'sidebar_navigation.php'); ?>    
            </div>
            <!-- SIDE NAVIGATION BAR END -->
            
            <!-- MAIN CONTENT START -->
            <div class="main-content">
                <div class="main-content-inner">
                    
                    <!-- BREADCRUMBS N SEARCH BAR START -->
                    <div class="breadcrumbs" id="breadcrumbs">
                        <?php include (INCLUDES_PATH.'breadcrumbs_navigation.php'); ?>    
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
			</script>
                        <div class="page-header">
                            <h1>
                                My Profile<small><i class="ace-icon fa fa-angle-double-right"></i>&nbsp;personal details</small>
                            </h1>
                        </div>
                        <!-- PAGE HEADER -->

                        <div class="row">
                            <div class="col-xs-12">
                                <!-- PAGE CONTENT START -->
					<div class="row" id="alert" style="display:none;">
						<div class="col-sm-6">
							<h3 class="header smaller lighter green">
								<i class="ace-icon fa fa-bullhorn"></i>
								Alerts
							</h3>


							<div id="succ" class="alert alert-block alert-success" style="display:none;">
								<button data-dismiss="alert" class="close" type="button" onClick = "javascript:document.getElementById('alert').style.display = 'none';">
									<i class="ace-icon fa fa-times"></i>
								</button>

								<p>
									<strong>
										<i class="ace-icon fa fa-check"></i>
										Done !!!
									</strong>
									<div id="successmsg"></div>
								</p>
							</div>
							<div id="err" class="alert alert-danger" style="display:none;" onClick = "javascript:document.getElementById('alert').style.display = 'none';">
								<button data-dismiss="alert" class="close" type="button">
									<i class="ace-icon fa fa-times"></i>
								</button>

								<strong>
									<i class="ace-icon fa fa-times"></i>
									Oh !!!
								</strong>
								<div id="errormsg"></div>
								<br>
							</div>
						</div>
					</div>
					<div class="space-20"></div>
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
												<a href="#address" data-toggle="tab" aria-expanded="false">
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
													<i class="green ace-icon fa fa-credit-card  bigger-120"></i>
													Company Details
												</a>
											</li>
											<li class="">
												<a href="#password" data-toggle="tab" aria-expanded="false">
													<i class="red ace-icon fa fa-lock bigger-120"></i>
													Change Password
												</a>
											</li>																				</ul>

										<div class="tab-content">
											<div class="tab-pane fade in active" id="basic">
												<div class="row">
													<div class="col-sm-12">
														<div class="space-20"></div>
														
														<form class="form-horizontal" role="form" name="frmbasic" id="frmbasic">
														<div class="form-group">
															<label for="form-field-1" class="col-sm-2 control-label no-padding-right"> Email Id / Login Id </label>
															<div class="col-sm-9" style="padding:6px 20px;">
																<b><?php echo $strCk_user_email_address;?></b>
															</div>
														</div>

														<div class="space-4"></div>

														
														<div class="form-group">
															<label for="form-field-1" class="col-sm-2 control-label no-padding-right"> Display Name </label>
															<div class="col-sm-9">

																<input type="text" class="col-sm-5" placeholder="Display Name" id="displayname" name="displayname" required for="basic" value="<?php echo $userdetails[$form_table_map['frmbasic']['displayname']]; ?>">
															</div>
														</div>

														<div class="space-4"></div>
		
														<div class="form-group">
															<label for="form-field-1" class="col-sm-2 control-label no-padding-right"> First Name </label>
															<div class="col-sm-9">
																<input type="text" class="col-sm-5" placeholder="First Name" id="firstname" name="firstname" required for="basic" value="<?php echo $userdetails[$form_table_map['frmbasic']['firstname']]; ?>">
															</div>
														</div>

														<div class="space-4"></div>

														<div class="form-group">
															<label for="form-field-1" class="col-sm-2 control-label no-padding-right"> Last Name </label>
															<div class="col-sm-9">
																<input type="text" class="col-sm-5" placeholder="Last Name" id="lastname" name="lastname" required for="basic" value="<?php echo $userdetails[$form_table_map['frmbasic']['lastname']]; ?>">
															</div>
														</div>

														<div class="space-20"></div>
														<input type="submit" class="btn btn-info" value="Save Basic Details" onClick="javascript:return sendData('frmbasic','reset');">
														</form>






																												

													</div>
												</div>												
											</div>
											<div class="tab-pane fade" id="contact">
											<div class="space-20"></div>
											<form class="form-horizontal" role="form" name="frmcontact" id = "frmcontact">

											<div class="form-group">
												<label for="form-field-1" class="col-sm-2 control-label no-padding-right"> Phone #1 </label>
												<div class="col-sm-9">
													<input type="text" class="col-sm-5" placeholder="Phone #1" id="phone1" name="phone1" required for="contact" value="<?php echo $userdetails[$form_table_map['frmcontact']['phone1']]; ?>">
												</div>
											</div>
											<div class="space-4"></div>

											
											<div class="form-group">
												<label for="form-field-1" class="col-sm-2 control-label no-padding-right"> Mobile Number </label>
												<div class="col-sm-9">
													<input type="text" class="col-sm-5" placeholder="Mobile Number" id="mobile" name="mobile" required for="contact"  value="<?php echo $userdetails[$form_table_map['frmcontact']['mobile']]; ?>" > 
												</div>
											</div> 
											<div class="space-4"></div>

											<div class="form-group">
												<label for="form-field-1" class="col-sm-2 control-label no-padding-right"> Secondry Email </label>
												<div class="col-sm-9">
													<input type="text" class="col-sm-5" placeholder="Secondry Email" id="SecondryEmail" name="SecondryEmail" required for="contact"  value="<?php echo $userdetails[$form_table_map['frmcontact']['SecondryEmail']]; ?>" >
												</div>
											</div> 
											<div class="space-20"></div>
											<input type="submit" class="btn btn-info" value="Save Contact Details" onClick="javascript:return sendData('frmcontact','reset');">
											</form>
											</div>
											<div class="tab-pane fade" id="address">
											<form class="form-horizontal" role="form" name="frmaddress" id = "frmaddress">
												<div class="space-20"></div>

												
												<div class="form-group">
													<label for="form-field-1" class="col-sm-2 control-label no-padding-right"> Address </label>
													<div class="col-sm-9">
														<textarea placeholder="Address" name="address" id="address" class="col-sm-5" for="address"><?php echo $userdetails[$form_table_map['frmaddress']['address']]; ?> </textarea>
													</div>
												</div>
												<div class="space-4"></div> 	
													
												<div class="form-group">
													<label for="form-field-1" class="col-sm-2 control-label no-padding-right"> Land Mark </label>
													<div class="col-sm-9">
														<input type="text" class="col-sm-5" placeholder="Land Mark" id="landmark" name="landmark" required for="address" value="<?php echo $userdetails[$form_table_map['frmaddress']['landmark']]; ?>" >
													</div>
												</div> 
												<div class="space-4"></div>
												

												<div class="form-group">
													<label for="form-field-1" class="col-sm-2 control-label no-padding-right"> City </label>
													<div class="col-sm-9">
														<input type="text" class="col-sm-5" placeholder="City" id="city" name="city" required for="address" value="<?php echo $userdetails[$form_table_map['frmaddress']['city']]; ?>" >
													</div>
												</div> 
												<div class="space-4"></div>
												
												<div class="form-group">
													<label for="form-field-1" class="col-sm-2 control-label no-padding-right"> Select Country </label>
													<div class="col-sm-9">
														 <select class="col-sm-5" id="form-field-select-1" for="address" name="country" id="country">
														    <option value="">Select Country</option>
														    <?php echo $optionCountry;?>
														</select>
													</div>
												</div>
												<div class="space-20"></div>
												<input type="submit" class="btn btn-info" value="Save Address Details" onClick="javascript:return sendData('frmaddress','reset');">
												</form>


																															     </div>
											<div class="tab-pane fade" id="Social">
												<form class="form-horizontal" role="form" name="frmSocial" id="frmSocial">
												<div class="space-20"></div>

												<div class="form-group">
													<label for="form-field-1" class="col-sm-2 control-label no-padding-right"> Facebook ID </label>
													<div class="col-sm-9">
														<textarea placeholder="Facebook ID" name="facebook" id="facebook" class="col-sm-5" for="Social"> <?php echo $userdetails[$form_table_map['frmSocial']['facebook']];?> </textarea>
													</div>
												</div>
												
												<div class="space-4"></div>
												<div class="form-group">
													<label for="form-field-1" class="col-sm-2 control-label no-padding-right"> Twitter </label>
													<div class="col-sm-9">
														<textarea placeholder="Twitter" name="twitter" id="twitter" class="col-sm-5" for="Social"><?php echo $userdetails[$form_table_map['frmSocial']['twitter']];?> </textarea>
													</div>
												</div>
												
												<div class="space-4"></div>
												<div class="form-group">
													<label for="form-field-1" class="col-sm-2 control-label no-padding-right"> Google Plus </label>
													<div class="col-sm-9">
														<textarea placeholder="Google Plus" name="googleplus" id="googleplus" class="col-sm-5" for="Social"><?php echo $userdetails[$form_table_map['frmSocial']['googleplus']];?> </textarea>
													</div>
												</div>
												
												<div class="space-4"></div>
												<div class="form-group">
													<label for="form-field-1" class="col-sm-2 control-label no-padding-right"> LinkedIn </label>
													<div class="col-sm-9">
														<textarea placeholder="LinkedIn" name="linkedin" id="linkedin" class="col-sm-5" for="Social"><?php echo $userdetails[$form_table_map['frmSocial']['linkedin']];?> </textarea>
													</div>
												</div>
												
												<div class="space-20"></div>
												<input type="submit" class="btn btn-info" value="Save Social info" onClick="javascript:return sendData('frmSocial','reset');">
												</form>
											</div>
											<div class="tab-pane fade" id="Company">
												<form class="form-horizontal" role="form" name="frmCompany" id="frmCompany">
												<div class="space-20"></div>
				
												<div class="form-group">
															<label for="form-field-1" class="col-sm-2 control-label no-padding-right"> Industry Type </label>
															<div class="col-sm-9">
											                                         <select class="col-sm-5" id="form-field-select-1" for="Company" name="indutrytype" id="indutrytype">
				                                        					                    <option value="">Industry Type</option>
																    <?php echo $optionIndustryType;?>
				                                                        					</select>
															</div>
														</div>
														<div class="space-4"></div>

														<div class="form-group">
															<label for="form-field-1" class="col-sm-2 control-label no-padding-right"> Company Name </label>
															<div class="col-sm-9">
																<input type="text" class="col-sm-5" placeholder="Company Name" id="companyname" name="companyname" required  for="Company" value="<?php echo $userdetails[$form_table_map['frmCompany']['companyname']];?>">
															</div>
														</div>

														<div class="space-4"></div>

														<div class="form-group">
															<label for="form-field-1" class="col-sm-2 control-label no-padding-right"> Nature Of Biusiness </label>
															<div class="col-sm-9">
																<input type="text" class="col-sm-5" placeholder="Nature Of Business" id="natureofbusiness" name="natureofbusiness" required  for="Company"  value="<?php echo $userdetails[$form_table_map['frmCompany']['natureofbusiness']];?>">
															</div>
														</div>

														<div class="space-4"></div>
																												
														<div class="form-group">
															<label for="form-field-1" class="col-sm-2 control-label no-padding-right"> Company URI </label>
															<div class="col-sm-9">
																<input type="text" class="col-sm-5" placeholder="Company URI" id="companyURL" name="companyURL" required  for="Company"  value="<?php echo $userdetails[$form_table_map['frmCompany']['companyURL']];?>">
															</div>
														</div>

														<div class="space-4"></div>

														<div class="form-group">
															<label for="form-field-1" class="col-sm-2 control-label no-padding-right"> Brief Discription Of Company </label>
															<div class="col-sm-9">
																<textarea placeholder="Brief Discription Of Company" id="briefDescription" name="briefDescription" class="col-sm-5"  for="Company"> <?php echo $userdetails[$form_table_map['frmCompany']['briefDescription']];?> </textarea>
															</div>
														</div>
														<div class="space-20"></div>
														<input type="submit" class="btn btn-info" value="Save Company Details" onClick="javascript:return sendData('frmCompany','reset');">
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
															 <?php echo $optionTimezonesType;?>
				                                                        			</select>
													</div>
												</div>
												<div class="space-20"></div>
												<input type="submit" class="btn btn-info" value="Submit Button">
												</form>
												
				
											</div>

											
											<div class="tab-pane fade" id="password">
												<form class="form-horizontal" role="form" name="frmpassword" id="frmpassword">
												<div class="space-20"></div>

												<div class="form-group">
													<label for="form-field-1" class="col-sm-2 control-label no-padding-right"> Current Password </label>
													<div class="col-sm-9">
														<input type="text" class="col-sm-5" placeholder="Current Password" id="currentpwd" name="currentpwd" required for="password">
													</div>
												</div>
												<div class="space-4"></div>
												<div class="form-group">
													<label for="form-field-1" class="col-sm-2 control-label no-padding-right"> New Password </label>
													<div class="col-sm-9">
														<input type="text" class="col-sm-5" placeholder="New Password" id="newpwd" name="newpwd" required for="password">
													</div>
												</div>
												<div class="space-4"></div>
												<div class="form-group">
													<label for="form-field-1" class="col-sm-2 control-label no-padding-right"> Confirm New Password </label>
													<div class="col-sm-9">
														<input type="text" class="col-sm-5" placeholder="Confirm New Password" id="cnfnewpwd" name="cnfnewpwd" required for="password">
													</div>
												</div>
												<div class="space-20"></div>
												<input type="submit" class="btn btn-info" value="Save new Password" onClick="javascript:return sendData('frmbasic','resetpwd');">
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
                <?php include (INCLUDES_PATH.'footer.php'); ?>  
            </div>
            <!-- FOOTER END -->

            <a href="#" id="btn-scroll-up" class="btn-scroll-up btn btn-sm btn-inverse">
                <i class="ace-icon fa fa-angle-double-up icon-only bigger-110"></i>
            </a>
            
        </div>
        <!-- MAIN CONTAINER END -->
        
        <!-- JAVA SCRIPT -->
            <?php include (INCLUDES_PATH.'static_js_includes.php'); ?>  
            <?php include (INCLUDES_PATH.'other_js_includes.php'); ?>  
        <!-- JAVA SCRIPT -->
       
    </body>
    <script src="<?php echo JS_PATH; ?>profile.js"></script>
</html>
