<?php
require_once('../../includes/global.inc.php');
require_once('../includes/config.inc.php');
require_once(CLIENT_CLASSES_PATH . 'client_error.inc.php');
require_once(CLIENT_DBS_PATH . 'DataHelper.php');
require_once(CLIENT_DBS_PATH . 'objDataHelper.php');
require_once(CLIENT_INCLUDES_PATH . 'client_authfunc.inc.php');
$CLIENT_CONST_MODULE = 'cl_contacts';
$CLIENT_CONST_PAGEID = 'Contacts Import';
require_once(CLIENT_INCLUDES_PATH . 'client_authorize.inc.php');
require_once(CLIENT_INCLUDES_PATH . 'client_dashboard_function.inc.php');
require_once(CLIENT_INCLUDES_PATH.'client_contact_function.inc.php');

//data population start	
$form_table_map = profile_form_table_map_contacts();

$arrGroups = getAllgroups($strCK_user_id , $objDataHelper);
for($i=0;$i<count($arrGroups);$i++)
{
	$groupOptions.="<option value='".$arrGroups[$i]['contact_group_name']."'>".$arrGroups[$i]['contact_group_name']."</option>";
}
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
                            <h1>
                                Dashboard<small><i class="ace-icon fa fa-angle-double-right"></i>&nbsp;<?php echo $CONST_PAGEID?></small>
                            </h1>
                        </div>
                        <!-- PAGE HEADER -->

                        <div class="row">
                            <div class="col-xs-12">
                                <!-- PAGE CONTENT START -->
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
				
					<div id="ajaxcontainer">

			    <div id="header"><div id="header_left"></div>

				    <div id="header_main">Contacts File Import</div><div id="header_right"></div></div>

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
				<form class="form-horizontal" role="form"  name="frmFileUploadData"  id="frmFileUploadData" style="display:none;">
					<input type="hidden" id="type" name="type" value="uploadfiledata">
					<input type="hidden" id="filename" name="filename">
					<input type ="hidden" id="filestring" name="filestring">
					<table aria-describedby="dynamic-table_info" role="grid" id="dynamic-table" class="table table-striped table-bordered table-hover dataTable no-footer DTTT_selectable" style="width:600px;">
						<thead>
							<tr role="row">
								<th colspan="3" rowspan="1" aria-controls="dynamic-table" tabindex="0">
									Map the columns with the CSV columns.
								</th>
							</tr>
						</thead>
						<tbody>
							<tr class="even" role="row">
							       	<td class="center" width="100px">
								  	<label class="pos-rel">1</label>
							       	</td>
							       	<td class="center">
									<label class="pos-rel">First Name</label>
							       	</td>
								<td class="center">
									<label class="pos-rel">
										<select onChange="javascript:reshuffleSelect(this);"  name="selfirstname" id="selfirstname" ><option value="">Map Column</option></select>
									</label>
							       	</td>
							</tr>
							<tr class="odd" role="row">
								<td class="center">
								  	<label class="pos-rel">2</label>
							       	</td>
							       	<td class="center">
								  	<label class="pos-rel">Last Name</label>
							       	</td>
								<td class="center">
									<label class="pos-rel">
										<select onChange="javascript:reshuffleSelect(this);"  name="sellastname" id="sellastname" ><option value="">Map Column</option></select>
									</label>
							       	</td>
							</tr>
							<tr class="even" role="row">
								<td class="center">
								  	<label class="pos-rel">3</label>
							       	</td>
							       	<td class="center">
								  	<label class="pos-rel">Nick Name</label>
							       	</td>
								<td class="center">
									<label class="pos-rel">
										<select onChange="javascript:reshuffleSelect(this);"  name="selnickname" id="selnickname" ><option value="">Map Column</option></select>
									</label>
							       	</td>
							</tr>
							<tr class="odd" role="row">
								<td class="center">
								  	<label class="pos-rel">4</label>
							       	</td>
							       	<td class="center">
								  	<label class="pos-rel">Email Address</label>
							       	</td>
								<td class="center">
									<label class="pos-rel">
										<select onChange="javascript:reshuffleSelect(this);"  name="selemailaddress" id="selemailaddress" ><option value="">Map Column</option></select>
									</label>
							       	</td>
							</tr>
							<tr class="even" role="row">
								<td class="center">
								  	<label class="pos-rel">5</label>
							       	</td>
							       	<td class="center">
								  	<label class="pos-rel">Phone Number</label>
							       	</td>
								<td class="center">
									<label class="pos-rel">
										<select onChange="javascript:reshuffleSelect(this);"  name="selphonenumber" id="selphonenumber" ><option value="">Map Column</option></select>
									</label>
							       	</td>
							</tr>
							<tr class="odd" role="row">
								<td class="center">
								  	<label class="pos-rel">6</label>
							       	</td>
								<td class="center">
								  	<label class="pos-rel">Group</label>
							       	</td>
								<td class="center">
									<label class="pos-rel">
										<select onChange="javascript:reshuffleSelect(this);"  name="selgroupname" id="selgroupname" ><?php echo $groupOptions;?></select>
									</label>
							       	</td>
							</tr>
							<tr class="even" role="row">
								<td class="center">
								  	<label class="pos-rel">6</label>
							       	</td>
								<td class="center">
								  	<label class="pos-rel">Country</label>
							       	</td>
								<td class="center">
									<label class="pos-rel">
										<select onChange="javascript:reshuffleSelect(this);"  name="selcountry" id="selcountry" ><option value="">Map Column</option></select>
									</label>
							       	</td>
							</tr>
							<tr class="even" role="row">
								<td colspan="3" align="right">
								  	<input type="button" name="resetSelect" class="btn btn-info " value="Reset Maps" onclick="javascript:makeselect();">
							       	
								  	<input type="submit" name="submitfilecontact" class="btn btn-info " value="Save Contacts" onclick="javascript:return sendData('frmFileUploadData','uploadfiledata');">
							       	</td>
							</tr>
						</tbody>
					</table>
					</form>
					
                               

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
var columnCount = 0;
function stopUpload(success , filepath , contents)
{
	
	var result = '';
	if (success == 1)
	{
		result = '<span class="msg">The file was uploaded successfully!<\/span><br/><br/>';
		var json = eval("(" + contents + ")");
  		for(key in json)
		{
			var arrRowData  = json[key].split(",");
      			if(arrRowData.length > columnCount)
       				columnCount = arrRowData.length;
		}
		if(columnCount < 6)
		{
			result = '<span class="emsg">There was an error during file upload!<\/span><br/><br/>';
		}
		else
		{
			document.getElementById("filename").value = filepath;
			//document.getElementById("filestring").value = contents;
			makeselect();
			document.getElementById('frmFileUploadData').style.display="";
		}

		
	}
	else 
	{
		result = '<span class="emsg">There was an error during file upload!<\/span><br/><br/>';
	}
	document.getElementById('f1_upload_process').style.visibility = 'hidden';
	document.getElementById('f1_upload_form').innerHTML = result + '<label>File: <input name="myfile" type="file" size="30" /><\/label><label><input type="submit" name="submitBtn" class="sbtn" value="Upload" /><\/label>';
	document.getElementById('f1_upload_form').style.visibility = 'visible';      
	return true;   
}

function makeselect()
{

	var len  = document.forms["frmFileUploadData"].getElementsByTagName("select").length;
	var eleArrray = document.forms["frmFileUploadData"].getElementsByTagName("select");
	for(var j=0;j<len;j++)
	{
		if(eleArrray[j].name != "selgroupname")
			while(eleArrray[j].childNodes.length > 1)
			{
				eleArrray[j].removeChild(eleArrray[j].childNodes[eleArrray[j].childNodes.length -1]);
			}
	}

	for(var j=0;j<len;j++)
		{

			for(i=0;i < columnCount ;i++)
			{

				var opt = __createElement("OPTION");
				opt.setAttribute("value",i);
				opt.innerHTML = "Column "+(i+1);
				if(eleArrray[j].name != "selgroupname")
					eleArrray[j].appendChild(opt);						
			}
			//if(j>0)
				//eleArrray[j].disabled = true;
	
		}

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

function reshuffleSelect(ele)
{
    var selVal = ele.value;
    var selName = ele.name;
    var len  = document.forms["frmFileUploadData"].getElementsByTagName("select").length;
    var eleArrray = document.forms["frmFileUploadData"].getElementsByTagName("select");
    //console.debug(len);
		for(var j=0;j<len;j++)
		{
       			var eleChilds =  eleArrray[j].childNodes;
		       for(var i=0;i< eleChilds.length;i++)
		       {
			   if(eleArrray[j].name != selName)
			   {
			     var obj = eleArrray[j].childNodes[i];
			     if(obj.value == selVal)
			     {
			       	if(eleArrray[j].name != "selgroupname")
			       		eleArrray[j].removeChild(eleArrray[j].childNodes[i]);
			     }
			   }
		       }
      
    		}
};

//-->
</script>
     <script src="<?php echo JS_PATH; ?>contact.js"></script>

</html>
