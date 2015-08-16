<?php
require_once('../includes/global.inc.php');
require_once(CLASSES_PATH.'error.inc.php');
require_once(DBS_PATH.'DataHelper.php');
require_once(DBS_PATH.'objDataHelper.php');
require_once(INCLUDES_PATH.'cm_authfunc.inc.php');
$CONST_MODULE = 'Contacts';
$CONST_PAGEID = 'My Contacts';
require_once(INCLUDES_PATH.'cm_authorize.inc.php');
require_once(INCLUDES_PATH.'common_function.inc.php');
require_once(INCLUDES_PATH.'contact_function.inc.php');


//data population start	
$form_table_map = profile_form_table_map_contacts();
$contacts = getAllcontactsByUserID($strCK_user_id , $objDataHelper);

$arrGroups = getAllgroups($strCK_user_id , $objDataHelper);
for($i=0;$i<count($arrGroups);$i++)
{
	$groupOptions.="<option value='".$arrGroups[$i]['contact_group_name']."'>".$arrGroups[$i]['contact_group_name']."</option>";
}

$arrDistinctCountry = getDistinctCountry($objDataHelper);
$optionCountry ="";	
	for($cnt=0;$cnt< count($arrDistinctCountry);$cnt++)
	{
		$selected = ($userdetails[$form_table_map['frmaddress']['country']] == $arrDistinctCountry[$cnt]['country_name'])? "selected":"";

		
		$optionCountry.="<option value='".$arrDistinctCountry[$cnt]['country_idd_code']."' ".$selected.">".$arrDistinctCountry[$cnt]['country_name']." - ".$arrDistinctCountry[$cnt]['country_code']."</option>";

	}

//data population ends
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
                                <?php echo $CONST_MODULE?><small><i class="ace-icon fa fa-angle-double-right"></i><?php echo $CONST_PAGEID;?></small>
                            </h1>
                        </div>
                        <!-- PAGE HEADER -->

                        <div class="row">
                            <div class="col-xs-12">
                                <!-- PAGE CONTENT START -->
					<div>
	   <div id="dynamic-table_wrapper" class="dataTables_wrapper form-inline no-footer">
	      <div class="row">
		 <div class="col-xs-6">
	            
	            <a href="#modal-table" role="button" class="blue" data-toggle="modal" title="Add New Contact" alt="Add New Contact"  onClick="document.getElementById('type').value='add';document.getElementById('association').value= '<?php echo $strCK_user_id;?>';">
			<i class="ace-icon fa fa-user bigger-130"></i>
				<sup>
					<b style="font-size:12px;">+</b>
				</sup>
		   </a>
		    <!--div class="dataTables_length" id="dynamic-table_length">
		       <label>
			  Display 
			  <select name="dynamic-table_length" aria-controls="dynamic-table" class="form-control input-sm">
			     <option value="10">10</option>
			     <option value="25">25</option>
			     <option value="50">50</option>
			     <option value="100">100</option>
			  </select>
			  records
		       </label>
		    </div-->
		 </div>
		 <div class="col-xs-6">
		    <div id="dynamic-table_filter" class="dataTables_filter"><label>Search:<input type="search" class="form-control input-sm" placeholder="" aria-controls="dynamic-table"></label></div>
		 </div>
	      </div>
	      <table class="table table-striped table-bordered table-hover dataTable no-footer DTTT_selectable" id="dynamic-table" role="grid" aria-describedby="dynamic-table_info">
		 <thead>
		    <tr role="row">
		       <th class="center sorting_disabled" rowspan="1" colspan="1">
			  <label class="pos-rel">
			  <!--input type="checkbox" class="ace"-->
			  <span class="lbl"></span>
			  </label>
		       </th>
		       <th tabindex="0" aria-controls="dynamic-table" rowspan="1" colspan="1" >Name</th>
		       <th tabindex="0" aria-controls="dynamic-table" rowspan="1" colspan="1" >Email Address</th>
		       <th tabindex="0" aria-controls="dynamic-table" rowspan="1" colspan="1" >Phone No.</th>
		       <th tabindex="0" aria-controls="dynamic-table" rowspan="1" colspan="1" >Group</th>
		       <th tabindex="0" aria-controls="dynamic-table" rowspan="1" colspan="1" >Update </th>
		       <th tabindex="0" aria-controls="dynamic-table" rowspan="1" colspan="1" >Status</th>
		       <th class="sorting_disabled" rowspan="1" colspan="1" aria-label=""></th>
		    </tr>
		 </thead>
		 <tbody>
		 <?php 
			$trclass="odd";
			$counter=0;
			for($i=0;$i<count($contacts);$i++)
			{
			$trclass = ($trclass == "odd")?"even":"odd";
			$counter++;
			

		    ?>
		    <tr role="row" class="<?php echo $trclass;?>">
		       <td class="center">
			  <label class="pos-rel">
			  <!--input type="checkbox" class="ace"-->
				<?php echo $counter;?>
			  <span class="lbl"></span>
			  </label>
		       </td>
		       <td><?php echo $contacts[$i]['contact_first_name']." ".$contacts[$i]['contact_last_name'];?></td>
		       <td><?php echo $contacts[$i]['contact_email_address'];?></td>
		       <td><?php echo $contacts[$i]['contact_mobile_number'];?></td>
		       <td class="hidden-480"><?php echo  $contacts[$i]['contact_group_name'];?></td>
		       <td><?php echo  $contacts[$i]['updatdt'];?></td>
		       <td class="hidden-480">
			  <?php if($contacts[$i]['personal_contact_status'] == '1'){?>
			  <span class="label label-sm label-success">Registered</span>
			  <?php } else {?>
			  <span class="label label-sm label-warning">Disabled</span>
			  <?php }?>
		       </td>
		       <td>
			  <div class="hidden-sm hidden-xs action-buttons">
			     <!--a href="#" class="blue">
			     <i class="ace-icon fa fa-search-plus bigger-130"></i>
			     </a-->
			     <a href="#" class="green">
			     <i class="ace-icon fa fa-pencil bigger-130"></i>
			     </a>
			      <?php if($contacts[$i]['personal_contact_status'] == '1'){?>
			     <a href="<?php echo $SITE_ROOT."contacts/api/action.php?action=disable&contactid=".$contacts[$i]['personal_contact_id']?>" class="red">
			     <i class="ace-icon fa fa-trash-o bigger-130"></i>
			     </a>
			      <?php }elseif($contacts[$i]['personal_contact_status'] == '2'){?>
				<a href="<?php echo $SITE_ROOT."contacts/api/action.php?action=enable&contactid=".$contacts[$i]['personal_contact_id']?>" class="green">
                             <i class="ace-icon fa fa-undo bigger-130"></i>
                             </a>

			      <?}?>
			  </div>
			  <div class="hidden-md hidden-lg">
			     <div class="inline pos-rel">
				<button data-position="auto" data-toggle="dropdown" class="btn btn-minier btn-yellow dropdown-toggle">
				<i class="ace-icon fa fa-caret-down icon-only bigger-120"></i>
				</button>
				<ul class="dropdown-menu dropdown-only-icon dropdown-yellow dropdown-menu-right dropdown-caret dropdown-close">
				   <li>
				      <a title="" data-rel="tooltip" class="tooltip-info" href="#" data-original-title="View">
				      <span class="blue">
				      <i class="ace-icon fa fa-search-plus bigger-120"></i>
				      </span>
				      </a>
				   </li>
				   <li>
				      <a title="" data-rel="tooltip" class="tooltip-success" href="#" data-original-title="Edit">
				      <span class="green">
				      <i class="ace-icon fa fa-pencil-square-o bigger-120"></i>
				      </span>
				      </a>
				   </li>
				   <li>
				      <a title="" data-rel="tooltip" class="tooltip-error" href="#" data-original-title="Delete">
				      <span class="red">
				      <i class="ace-icon fa fa-trash-o bigger-120"></i>
				      </span>
				      </a>
				   </li>
				</ul>
			     </div>
			  </div>
		       </td>
		    </tr>
		    <?php }?>
		   </tbody>
	      </table>
	      <div class="row">
		 <div class="col-xs-6">
		    <!--div class="dataTables_info" id="dynamic-table_info" role="status" aria-live="polite">Showing 1 to 10 of 23 entries</div-->
		 </div>
		 <!--div class="col-xs-6">
		    <div class="dataTables_paginate paging_simple_numbers" id="dynamic-table_paginate">
		       <ul class="pagination">
			  <li class="paginate_button previous disabled" aria-controls="dynamic-table" tabindex="0" id="dynamic-table_previous"><a href="#">Previous</a></li>
			  <li class="paginate_button active" aria-controls="dynamic-table" tabindex="0"><a href="#">1</a></li>
			  <li class="paginate_button " aria-controls="dynamic-table" tabindex="0"><a href="#">2</a></li>
			  <li class="paginate_button " aria-controls="dynamic-table" tabindex="0"><a href="#">3</a></li>
			  <li class="paginate_button next" aria-controls="dynamic-table" tabindex="0" id="dynamic-table_next"><a href="#">Next</a></li>
		       </ul>
		    </div>
		 </div-->
	      </div>
	   </div>
	</div>


	<div tabindex="-1" class="modal fade" id="modal-table" style="display: none;" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header no-padding">
					<div class="table-header">
						<button aria-hidden="true" data-dismiss="modal" class="close" type="button">
							<span class="white">×</span>
						</button>
						Add / Update a  Contact
					</div>
				</div>

				<div class="modal-body no-padding">
					<!-- Modal data start -->
						<div class="tab-content">
							<div class="tab-pane fade in active" id="basic">
								<div class="row">
									<div class="">
										<div class="space-10"></div>
											<input type="hidden" name="type" id="type">
											<form class="form-horizontal" role="form" name="frmcontact" id="frmcontact">
												<input type="hidden" name="association"  id="association">
												<div class="form-group">
													<label for="form-field-1" class="col-sm-3 control-label no-padding-right"> 
														Nick Name :
													</label>
													<div class="col-sm-9" style="padding:6px 20px;">
														<b>
															<input type="text" class="col-sm-9" placeholder="Nick Name" id="contactnickname" name="contactnickname" required for="basic" value="" >
														</b>
													</div>
												 </div>
												<div class="space-4"></div>
												<div class="form-group">
													<label for="form-field-1" class="col-sm-3 control-label no-padding-right"> 
														First Name :
													</label>
													<div class="col-sm-9" style="padding:6px 20px;">
														<b>
															<input type="text" class="col-sm-9" placeholder="First Name" id="contactfirstname" name="contactfirstname" required for="basic" value="" >
														</b>
													</div>
												 </div>
												<div class="space-4"></div>
												<div class="form-group">
													<label for="form-field-1" class="col-sm-3 control-label no-padding-right"> 
														Last Name :
													</label>
													<div class="col-sm-9" style="padding:6px 20px;">
														<b>
															<input type="text" class="col-sm-9" placeholder="Last Name" id="contactlastname" name="contactlastname" required for="basic" value="" >
														</b>
													</div>
												 </div>
												<div class="space-4"></div>


												<div class="form-group">
													<label for="form-field-1" class="col-sm-3 control-label no-padding-right"> 
														Email Address :
													</label>
													<div class="col-sm-9" style="padding:6px 20px;">
														<b>
															<input type="text" class="col-sm-9" placeholder="Email Address" id="contactemailaddress" name="contactemailaddress" required for="basic" value="" >
														</b>
													</div>
												 </div>
												<div class="space-4"></div>
												<div class="form-group">
													<label for="form-field-1" class="col-sm-3 control-label no-padding-right"> 
														Phone No. :
													</label>
													<div class="col-sm-9" style="padding:6px 20px;">
														<b>
															<input type="text" class="col-sm-9" placeholder="Phone No." id="contactphoneno" name="contactphoneno" required for="basic" value="" >
														</b>
													</div>
												 </div>
												<div class="space-4"></div>
												<div class="form-group">
													<label for="form-field-1" class="col-sm-3 control-label no-padding-right"> 
														Group :
													</label>
													<div class="col-sm-9" style="padding:6px 20px;">
														<b>
																<select class="col-sm-5" id="contactgroup" name="contactgroup">
																<?php echo $groupOptions;?>
																</select>
														</b>
														<div style="float:left;padding:10px;">
<b>OR</b>
														</div>
  														<div style="float:left;">
															<input type="text" value="" for="basic" name="newcontactgroupname" id="newcontactgroupname" placeholder="New Group Name">
   														</div>
  														<div style="clear:both;"></div>
													</div>
												 </div>
												 <div class="space-4"></div>
												<div class="form-group">
													<label for="form-field-1" class="col-sm-3 control-label no-padding-right"> Select Country </label>
													<div class="col-sm-9" style="padding:6px 20px;">
														 <select class="col-sm-9" for="address" name="contact_phone_idd" id="contact_phone_idd" class="form-control">
														    <option value="">Select Country</option>
														    <?php echo $optionCountry;?>
														</select>
													</div>
												</div>
												<div class="space-10"></div>
												<div class="form-group">
													<label for="form-field-1" class="col-sm-3 control-label no-padding-right"> 
													</label>
													<div class="col-sm-9" style="padding:6px 20px;">
														<b>
															<input type="submit" onclick="javascript:return sendData('frmcontact',document.getElementById('type').value);" value="Save Contact" class="btn btn-info " name="submitcontact">
														</b>
													</div>

											</form>
										</div>
									</div>
								</div>
							</div>
						</div>
					<!-- Modal data end -->
				</div>

				<div class="modal-footer no-margin-top">
					<!--button data-dismiss="modal" class="btn btn-sm btn-danger pull-left">
						<i class="ace-icon fa fa-times"></i>
						Close
					</button-->

					
				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
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
     <script src="<?php echo JS_PATH; ?>contact.js"></script>
</html>
