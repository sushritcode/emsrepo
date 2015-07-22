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
                        <div class="page-header">
                            <h1>
                                My Profile<small><i class="ace-icon fa fa-angle-double-right"></i>&nbsp;personal details</small>
                            </h1>
                        </div>
                        <!-- PAGE HEADER -->

                        <div class="row">
                            <div class="col-xs-12">
                                <!-- PAGE CONTENT START -->
<div id="myModal" class="modal fade" style="padding:210px;">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
		Processing Request.....
       </div>
      <div class="modal-body">
        <p>Please wait !!!! </p>
      </div>
      <div class="modal-footer">
        <!--button type="button" class="btn btn-default" data-dismiss="modal">Close</button-->
        <!--button type="button" class="btn btn-primary">Save changes</button-->
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="container">
	<div class="row clearfix">
		<div class="col-md-10 column">
			<div class="carousel slide corosolHtmBox" id="carousel-144227"  data-interval="false">
				<!--ol class="carousel-indicators">
					<li class="active" data-slide-to="0" data-target="#carousel-144227" style="border:1px solid">
					</li>
					<li data-slide-to="1" data-target="#carousel-144227">
					</li>
					<li data-slide-to="2" data-target="#carousel-144227">
					</li>
				</ol-->
				<div class="carousel-inner">
					<div class="item corosolHtmItem active">
						<div class="row">
							 <div class="col-md-2 stepsRegistration active">1 of 6</div>
							 <div class="col-md-2 stepsRegistration">2 of 6</div>
							 <div class="col-md-2 stepsRegistration">3 of 6</div>
							 <div class="col-md-2 stepsRegistration">4 of 6</div>
							 <div class="col-md-2 stepsRegistration">5 of 6</div>
						</div>
						<form class="form-horizontal" name="basic-details">
<fieldset>
<!-- Form Name -->
<legend>Basic Details</legend>
<!-- Select Basic -->
<div class="elementContainer">
<div class="form-group">
  <label class="col-md-4 control-label" for="selectbasic"></label>
  <div class="col-md-5">
    <select id="industryType" name="industryType" class="form-control" required>
      <option value="">Industry Type</option>
    </select>
  </div>
</div>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="companyname"></label>  
  <div class="col-md-5">
  <input id="companyname" name="companyname" placeholder="Company Name" class="form-control " required="" type="text" required >
    
  </div>
</div>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="natureofbusiness"></label>  
  <div class="col-md-5">
  <input id="natureofbusiness" name="natureofbusiness" placeholder="Nature of Bussiness" class="form-control " required="" type="text" value = "">
    
  </div>
</div>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="displayName"></label>  
  <div class="col-md-5">
  <input id="displayName" name="displayName" placeholder="Display Name" class="form-control " type="text" required value="">    
  </div>
</div>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="companyURL"></label>  
  <div class="col-md-5">
  <input id="companyURL" name="companyURL" placeholder="Company URI" class="form-control " required="" type="text" value="">
    
  </div>
</div>

<!-- Textarea -->
<div class="form-group">
  <label class="col-md-4 control-label" for="briefDescription"></label>
  <div class="col-md-4">                     
    <textarea class="form-control" id="briefDescription" name="briefDescription" placeholder ="Brief Description Of Company" required></textarea>
  </div>
</div>
</div>

<!-- Button (Double) -->
<div class="form-group">
  <label class="col-md-4 control-label" for="skip"></label>
  <div class="col-md-12">
    <!--button id="skip" name="skip" class="btn btn-success">Skip &gt;&gt;</button-->
    <button type="submit" id="SaveandNext" name="SaveandNext" class="btn btn-success fr" data-slide-to="" data-target="#carousel-144227" next-slide="1" onClick="javascript:slideCorosal(this,document.forms['basic-details']);">Save and Next</button>
  </div>
</div>
<div class="cl"></div>


</fieldset>
</form>

					</div>
					<div class="item corosolHtmItem">
						<div class="row">
							 <div class="col-md-2 stepsRegistration">1 of 6</div>
							 <div class="col-md-2 stepsRegistration active">2 of 6</div>
							 <div class="col-md-2 stepsRegistration">3 of 6</div>
							 <div class="col-md-2 stepsRegistration">4 of 6</div>
							 <div class="col-md-2 stepsRegistration">5 of 6</div>
						</div>

						<form class="form-horizontal" name="contact-details">
<fieldset>

<!-- Form Name -->
<legend>Contact Details</legend>

<div class="elementContainer">
<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="phone1"></label>  
  <div class="col-md-5">
  <input id="phone1" name="phone1" placeholder="Phone #1" class="form-control " required="" type="text" value="" >
    
  </div>
</div>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="phone2"></label>  
  <div class="col-md-5">
  <input id="phone2" name="phone2" placeholder="Phone #2" class="form-control " required="" type="text" value="" >
    
  </div>
</div>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="mobile"></label>  
  <div class="col-md-5">
  <input id="mobile" name="mobile" placeholder="Mobile Number" class="form-control " required="" type="text" value="" >
    
  </div>
</div>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="SecondryEmail"></label>  
  <div class="col-md-5">
  <input id="SecondryEmail" name="SecondryEmail" placeholder="Secondry Email Id" class="form-control " required="" type="text" value="" >
    
  </div>
</div>
</div>
<!-- Button (Double) -->
<div class="form-group">
  <label class="col-md-4 control-label" for="skip"></label>
  <div class="col-md-12">
    <button id="previous" name="previous1" class="btn btn-success fl" data-slide-to="0" data-target="#carousel-144227" >Previous</button>
    <button id="SaveandNext1" name="SaveandNext1" class="btn btn-success fr" data-slide-to="" data-target="#carousel-144227" next-slide="2" onClick="javascript:slideCorosal(this, document.forms['contact-details']);" >Save and Next</button>
  </div>
</div>
<div class="cl"></div>

</fieldset>
</form>

					</div>
					<div class="item corosolHtmItem">
						<div class="row">
							 <div class="col-md-2 stepsRegistration">1 of 6</div>
							 <div class="col-md-2 stepsRegistration">2 of 6</div>
							 <div class="col-md-2 stepsRegistration active">3 of 6</div>
							 <div class="col-md-2 stepsRegistration">4 of 6</div>
							 <div class="col-md-2 stepsRegistration">5 of 6</div>
						</div>

						<form class="form-horizontal" name="address-details">
<fieldset>

<!-- Form Name -->
<legend>Address Details</legend>

<div class="elementContainer">
<!-- Textarea -->
<div class="form-group">
  <label class="col-md-4 control-label" for="address1"></label>
  <div class="col-md-4">                     
    <textarea class="form-control" id="address1" name="address1" placeholder="Primary Address"></textarea>
  </div>
</div>

<!-- Textarea -->
<div class="form-group">
  <label class="col-md-4 control-label" for="address2"></label>
  <div class="col-md-4">                     
    <textarea class="form-control" id="address2" name="address2" placeholder="Address 1"></textarea>
  </div>
</div>

<!-- Textarea -->
<div class="form-group">
  <label class="col-md-4 control-label" for="address3"></label>
  <div class="col-md-4">                     
    <textarea class="form-control" id="address3" name="address3" placeholder="Address 2"></textarea>
  </div>
</div>
</div>
<!-- Button (Double) -->
<div class="form-group">
  <label class="col-md-4 control-label" for="skip"></label>
  <div class="col-md-12">
    <button id="skip" name="skip" class="btn btn-success fl"   data-slide-to="1" data-target="#carousel-144227"   data-slide-to="3" data-target="#carousel-144227"  >Previous</button>
    <button id="SaveandNext2" name="SaveandNext2" class="btn btn-success fr"  data-slide-to="" data-target="#carousel-144227" next-slide="3" onClick="javascript:slideCorosal(this,document.forms['address-details']);" >Save and Next</button>
  </div>
</div>
<div class="cl"></div>

</fieldset>
</form>

					</div>
					<div class="item corosolHtmItem">
						<div class="row">
							 <div class="col-md-2 stepsRegistration">1 of 6</div>
							 <div class="col-md-2 stepsRegistration">2 of 6</div>
							 <div class="col-md-2 stepsRegistration">3 of 6</div>
							 <div class="col-md-2 stepsRegistration active">4 of 6</div>
							 <div class="col-md-2 stepsRegistration">5 of 6</div>
						</div>

						<form class="form-horizontal" name="social-media">
<fieldset>

<!-- Form Name -->
<legend>Social Media</legend>

<div class="elementContainer">
<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="facebookid"></label>  
  <div class="col-md-5">
  <input id="facebookid" name="facebookid" placeholder="Facebook" class="form-control " type="text" value="" >
    
  </div>
</div>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="twitter"></label>  
  <div class="col-md-5">
  <input id="twitter" name="twitter" placeholder="Twitter" class="form-control " type="text" value="" >
    
  </div>
</div>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="googleplus"></label>  
  <div class="col-md-5">
  <input id="googleplus" name="googleplus" placeholder="Google +" class="form-control " type="text" value="" >
    
  </div>
</div>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="linkedin"></label>  
  <div class="col-md-5">
  <input id="linkedin" name="linkedin" placeholder="Linked In" class="form-control " type="text" value="">
    
  </div>
</div>
</div>

<!-- Button (Double) -->
<div class="form-group">
  <label class="col-md-4 control-label" for="skip"></label>
  <div class="col-md-12">
    <button id="skip" name="skip" class="btn btn-success fl"   data-slide-to="2" data-target="#carousel-144227"    data-slide-to="4" data-target="#carousel-144227"   >Previous</button>
    <button id="SaveandNext3" name="SaveandNext3" class="btn btn-success fr"   data-slide-to="" data-target="#carousel-144227" next-slide="4" onClick="javascript:slideCorosal(this,document.forms['social-media']);"  >Save and Next</button>
  </div>
</div>
<div class="cl"></div>

</fieldset>
</form>

					</div>
					<div class="item corosolHtmItem">
						<div class="row">
							 <div class="col-md-2 stepsRegistration">1 of 6</div>
							 <div class="col-md-2 stepsRegistration">2 of 6</div>
							 <div class="col-md-2 stepsRegistration">3 of 6</div>
							 <div class="col-md-2 stepsRegistration">4 of 6</div>
							 <div class="col-md-2 stepsRegistration active">5 of 6</div>
						</div>
	
						<form class="form-horizontal" name="billinglocal">
<fieldset>

<!-- Form Name -->
<legend>Billing And Localisation</legend>

<div class="elementContainer">
<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="billersName"></label>  
  <div class="col-md-5">
  <input id="billersName" name="billersName" placeholder="Billers name" class="form-control " type="text" value=""> 
    
  </div>
</div>

<!-- Select Multiple -->
<div class="form-group">
  <label class="col-md-4 control-label" for="currency"></label>
  <div class="col-md-5">
    <select id="currency" name="currency" class="form-control">
      <option value="">Select Currency</option>
	 
       
    </select>
  </div>
</div>

<!-- Select Multiple -->
<div class="form-group">
  <label class="col-md-4 control-label" for="timezones"></label>
  <div class="col-md-5">
    <select id="timezones" name="timezones" class="form-control">
      <option value="">Select Time Zones</option>
	
    </select>
  </div>
</div>
</div>
<!-- Button (Double) -->
<div class="form-group">
  <label class="col-md-4 control-label" for="skip"></label>
  <div class="col-md-12">
    <button id="skip" name="skip" class="btn btn-success fl"   data-slide-to="3" data-target="#carousel-144227"  >Previous</button>
    <button id="done" name="done" class="btn btn-success fr"   data-slide-to="" data-target="#carousel-144227" next-slide="1" onClick="javascript:slideCorosal(this,document.forms['billinglocal']);"  >Finished</button>
  </div>
</div>
<div class="cl"></div>

</fieldset>
</form>

					</div>
				</div> 
					 <a id="prev-carousel" class="left1 carousel-control1" href="#carousel-144227" data-slide="prev">&nbsp;
					 </a> 
					 <a id="next-carousel" class="right1 carousel-control1" href="#carousel-144227" data-slide="next">&nbsp;
					</a>
			</div>
		</div>
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
	<script type="text/javascript">	
function initAjax()
{
	if (window.XMLHttpRequest)
	{// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	}
	else
	{// code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	return xmlhttp;
};

function getAllElementsValURI(frmName)
{	

	var uri = "?formname="+frmName.name+"&";
	var len  = document.forms[frmName.name].getElementsByTagName("input").length;
	var eleArrray = document.forms[frmName.name].getElementsByTagName("input");
	for(var i=0;i<len;i++)
		uri+=eleArrray[i].name+"="+eleArrray[i].value+"&";
	var len  = document.forms[frmName.name].getElementsByTagName("textarea").length;
	var eleArrray = document.forms[frmName.name].getElementsByTagName("textarea");
	for(var i=0;i<len;i++)
		uri+=eleArrray[i].name+"="+eleArrray[i].value+"&";
	var len  = document.forms[frmName.name].getElementsByTagName("select").length;
	var eleArrray = document.forms[frmName.name].getElementsByTagName("select");
	for(var i=0;i<len;i++)
		uri+=eleArrray[i].name+"="+eleArrray[i].value+"&";

	return uri;
};
function slideCorosal(ele, frmName)
{

	$('#myModal').modal('show')
		var uri = getAllElementsValURI(frmName);

	//var frmAction =BASEURL+"users/save/";
	xmlhttp = initAjax();
	xmlhttp.corosalObj = ele
		xmlhttp.onreadystatechange = function() 
		{ 
			if(xmlhttp.readyState==4)
			{
				document.getElementById("next-carousel").click();

				$('#myModal').modal('hide');
			} 
		};
	//var url = frmAction+uri;
	var url = "http://sushrit.quadridge.net/letsemeet/emsrepo/branches/sushrit/ace_eletsmeet/profile/index.php";
	xmlhttp.open("GET",url,true);
	xmlhttp.send(null);
	return false;
};

</script>

        <!-- JAVA SCRIPT -->
       
    </body>
</html>
