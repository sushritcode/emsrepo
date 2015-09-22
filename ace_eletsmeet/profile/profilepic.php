<?php
require_once('../includes/global.inc.php');
require_once(CLASSES_PATH.'error.inc.php');
require_once(DBS_PATH.'DataHelper.php');
require_once(DBS_PATH.'objDataHelper.php');
require_once(INCLUDES_PATH.'cm_authfunc.inc.php');
$CONST_MODULE = 'Image';
$CONST_PAGEID = 'Image Upload';
require_once(INCLUDES_PATH.'cm_authorize.inc.php');
require_once(INCLUDES_PATH.'common_function.inc.php');
require_once(INCLUDES_PATH.'profile_function.inc.php');
//data population start	
//data population start	
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <!-- HEAD CONTENT AREA -->
        <?php include (INCLUDES_PATH.'head.php'); ?>
        <!-- HEAD CONTENT AREA -->

         <!-- CSS n JS CONTENT AREA -->
         <?php include (INCLUDES_PATH.'css_include.php'); ?>
	 <style>
		#ajaxcontainer {
			#margin: auto;
			width: 600px;
			border-top-width: 0px;
			border-right-width: 1px;
			border-bottom-width: 1px;
			border-left-width: 1px;
			border-top-style: solid;
			border-right-style: solid;
			border-bottom-style: solid;
			border-left-style: solid;
			border-top-color: #000033;
			border-right-color: #000033;
			border-bottom-color: #000033;
			border-left-color: #000033;
			background-color: #FFFFFF;
		}
		#ajaxcontainer #header #header_left {
			float: left;
			background-image: url(<?php echo IMG_PATH;?>uploader/header_left.gif);
			background-repeat: no-repeat;
			height: 42px;
			width: 45px;
		}
		#ajaxcontainer #header #header_right {
			background-image: url(<?php echo IMG_PATH;?>uploader/header_right.gif);
			background-repeat: no-repeat;
			height: 42px;
			width: 6px;
			float: right;
		}

		#ajaxcontainer #content {
			padding: 5px;
			font-family: Geneva, Arial, Helvetica, sans-serif;
			font-size: 12px;
			font-weight: normal;
			color: #666666;
		}
		#ajaxcontainer #footer {
			font-family: Geneva, Arial, Helvetica, sans-serif;
			font-size: 12px;
			color: #999999;
			text-align: right;
			border-top-width: 1px;
			border-right-width: 1px;
			border-bottom-width: 1px;
			border-left-width: 1px;
			border-top-style: solid;
			border-top-color: #999999;
			border-right-color: #000033;
			border-bottom-color: #000033;
			border-left-color: #000033;
			padding-top: 5px;
			padding-right: 10px;
			padding-bottom: 5px;
			padding-left: 5px;
		}
		#ajaxcontainer #footer a {
			color: #999999;
			text-decoration: none;
			font-family: Geneva, Arial, Helvetica, sans-serif;
			font-size: 10px;
		}

		#ajaxcontainer #header #header_main {
			float: left;
			padding: 5px;
			font-family: Geneva, Arial, Helvetica, sans-serif;
			font-size: 12px;
			font-weight: bold;
			color: #FFFFFF;
			margin-top: 5px;
			margin-right: 0px;
			margin-bottom: 0px;
			margin-left: 0px;
		}
		.sbtn    {
			background-image: url(<?php echo IMG_PATH;?>uploader/button.gif);
			border: 1px solid #000033;
			height: 22px;
			width: 82px;
			font-family: Geneva, Arial, Helvetica, sans-serif;
			font-size: 12px;
			color: #FFFFFF;
			font-weight: bold;
			background-position: center;
			padding: 0px;
			margin-top: 20px;
			margin-right: 20px;
			margin-bottom: 0px;
			margin-left: 20px;
		}
		button {
			font-family: Geneva, Arial, Helvetica, sans-serif;
			font-size: 12px;
			font-weight: bold;
			color: #FFFFFF;
			height: 22px;
			width: 82px;
			background-image: url(<?php echo IMG_PATH;?>uploader/button.gif);
		}
		#ajaxcontainer #content #form1 legend {
			padding: 5px;
			margin: auto;
		}
		form {
			margin: 10px 5px 0px 5px;
		}




		#ajaxcontainer #header {
			padding: 0px;
			margin-top: 0px;
			margin-right: 0px;
			margin-bottom: 0px;
			margin-left: 0px;
			background-image: url(<?php echo IMG_PATH;?>uploader/header_bg.gif);
			background-repeat: repeat-x;
			height: 42px;
		}
		label {
			padding: 0px;
			text-align: center;
		}

		.msg {
			text-align:left;
		 	color:#666;
			background-repeat: no-repeat;
		 	margin-left:30px;
		   margin-right:30px;
			padding:5px;
		   padding-left:30px;
		}

		.emsg {
			text-align:left;
			margin-left:30px;
		   margin-right:30px;
			color:#666;
			background-repeat: no-repeat;
			padding:5px;
		   padding-left:30px;
		}

		#loader{
		   visibility:hidden;
		}

		#f1_upload_form{
		   height:100px;
		}

		#f1_error{
		   font-family: Geneva, Arial, Helvetica, sans-serif;
			font-size: 12px;
		   font-weight:bold;
		   color:#FF0000;
		}

		#f1_ok{
		   font-family: Geneva, Arial, Helvetica, sans-serif;
			font-size: 12px;
		   font-weight:bold;
		   color:#00FF00;

		}

		#f1_upload_form {
			font-family: Geneva, Arial, Helvetica, sans-serif;
			font-size: 12px;
			font-weight: normal;
			color: #666666;
		}

		#f1_upload_process{
		   z-index:100;
		   visibility:hidden;
		   position:absolute;
		   text-align:center;
		   width:400px;
		}
	 </style>
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
			var BASEURL = "<?php echo $SITE_ROOT;?>";
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
			<div id='ajax_loader' style="width: 100%; height: 100%; position: fixed; left: 0px; top: 0px; background: transparent none repeat scroll 0% 0%; z-index: 20000;display:none;">
			    <img src="<?php echo IMG_PATH ?>loading.gif" style="position: relative; top: 30%; left: 50%;"></img>
			</div>
                        <div class="page-header">
			  	<div class="row" id="alert" style="display:none;">
					<div class="col-sm-12">
						<div id="succ" class="col-sm-12 alert alert-block alert-success" style="display:none;">
							<div class="ace-icon fa fa-bullhorn fa fa-check" style="font-weight: bold;">
								<span id="successmsg"> </span>
							</div>
						</div>
						<div id="err" class="alert alert-danger" style="display:none;">
							<div class="ace-icon fa fa-bullhorn fa fa-check" style="font-weight: bold;">
								<span id="errormsg"> </span>
							</div>
						</div>
					</div>
				</div>
				<div class="space-20"></div>
                            <h1>
                                Dashboard<small><i class="ace-icon fa fa-angle-double-right"></i>&nbsp;<?php echo $CONST_PAGEID?></small>
                            </h1>
                        </div>
                        <!-- PAGE HEADER -->

                        <div class="row">
                            <div class="col-xs-12">
                                <!-- PAGE CONTENT START -->
					<div id="ajaxcontainer">

			    <div id="header"><div id="header_left"></div>

				    <div id="header_main">Upload Profile Pic</div><div id="header_right"></div></div>

				    <div id="content">

					<form action="upload.php" method="post" enctype="multipart/form-data" target="upload_target" onsubmit="startUpload();" >

					     <p id="f1_upload_process">Loading...<br/><img src="<?php echo IMG_PATH?>uploader/loader.gif" /><br/></p>

					     <p id="f1_upload_form" align="center"><br/>

						 <label>File:  

						      <input name="myfile" type="file" size="30" />

						 </label>

						 <label>

						     <input type="submit" name="submitBtn" class="sbtn" value="Upload" />

						 </label>

					     </p>

					     

					     <iframe id="upload_target" name="upload_target" src="#" style="width:0;height:0;border:0px solid #fff;"></iframe>

					 </form>

				     </div>

				     <div id="footer">&nbsp;</div>

				 </div>
				<!-- Step 1 ends here -->
									
                               

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
	<script language="javascript" type="text/javascript">
<!--
function startUpload()
{
	document.getElementById('f1_upload_process').style.visibility = 'visible';
	document.getElementById('f1_upload_form').style.visibility = 'hidden';
	return true;
}
function stopUpload(success)
{

	var result = '';
	if (success == 1)
	{
		result = '<span class="msg">The Pic was uploaded successfully!<\/span><br/><br/>';
	}
	else 
	{
		result = '<span class="emsg">There was an error during Pic upload!<\/span><br/><br/>';
	}
	document.getElementById('f1_upload_process').style.visibility = 'hidden';
	document.getElementById('f1_upload_form').innerHTML = result + '<label>File: <input name="myfile" type="file" size="30" /><\/label><label><input type="submit" name="submitBtn" class="sbtn" value="Upload" /><\/label>';
	document.getElementById('f1_upload_form').style.visibility = 'visible';      
	location.reload();
	return true;   
}

function __createElement(tag, cls, id, name)
{
        var ele;
        ele = document.createElement(tag);
        if(cls != "")
                ele.className = cls;
        if(id != "")
                ele.id = id;
        if(name != "")
                ele.name = name;
        return ele;
};
//-->
</script>
     <script src="<?php echo JS_PATH; ?>profile.js"></script>

</html>
