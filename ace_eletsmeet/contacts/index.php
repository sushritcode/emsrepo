<?php
require_once('../includes/global.inc.php');
require_once(CLASSES_PATH.'error.inc.php');
require_once(DBS_PATH.'DataHelper.php');
require_once(DBS_PATH.'objDataHelper.php');
require_once(INCLUDES_PATH.'cm_authfunc.inc.php');
$CONST_MODULE = 'contacts';
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
                        <?php include (INCLUDES_PATH . 'breadcrumbs_navigation.php'); ?>
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
                                Contacts <small><i class="ace-icon fa fa-angle-double-right"></i>&nbsp; my contacts</small>
                            </h1>
                        </div>
                        <!-- PAGE HEADER -->

                        <div class="row">
                            <div class="col-xs-12">
                                <!-- PAGE CONTENT START -->
                                <div id='ajax_loader' style="width: 100%; height: 100%; position: fixed; left: 0px; top: 0px; background: transparent none repeat scroll 0% 0%; z-index: 20000;display:none;">
			    <img src="<?php echo IMG_PATH ?>loading.gif" style="position: relative; top: 30%; left: 50%;"></img>
			</div>
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
                                
                                    <div class="clearfix">
                                        <div class="pull-right tableTools-container"></div>
                                        <div class="">
						<button class="ColVis_Button ColVis_MasterButton btn btn-white btn-info btn-bold" onclick=" document.getElementById('frmcontact').reset(); document.getElementById('type').value='add';document.getElementById('association').value= 'usr0000006';" alt="Add New Contact" title="Add New Contact" data-toggle="modal" role="button" href="#modal-table">
							<i class="ace-icon fa fa-user bigger-130"></i>
							<sup>
								<b style="font-size:12px;">+</b>
							</sup>
						</button>
					</div>
					
                                    </div>
                                    <div class="table-header">
					My Contacts
                                    </div>
					<div>
	   <div id="dynamic-table_wrapper" class="dataTables_wrapper form-inline no-footer">
	      <table id="dynamic-table" class="table table-striped table-bordered table-hover dataTable no-footer DTTT_selectable" role="grid" aria-describedby="dynamic-table_info">
		 <thead>
		    <tr>
		       <th class="center">
                            <label class="pos-rel">
                                    <i class="ace-icon fa fa-user bigger-110 hidden-480"></i>
                                    <span class="lbl"></span>
                            </label>
                        </th>
		       <th>Name</th>
		       <th>Email Address</th>
		       <th>Phone No.</th>
		       <th>Group</th>
		       <th>Update </th>
		       <th>Status</th>
		       <th></th>
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
                                    <i class="ace-icon fa fa-user smaller hidden-480"></i>
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
			  <span class="label label-sm label-success">Active</span>
                                                        <?php } else if($contacts[$i]['personal_contact_status'] == '2'){?>
			  <span class="label label-sm label-warning">Inactive</span>
                                                        <?php } else if($contacts[$i]['personal_contact_status'] == '3'){?>
			  <span class="label label-sm label-danger">Deleted</span>
			  <?php } else {?>
			  <span class="label label-sm label-warning">Error</span>
			  <?php }?>
		       </td>
		       <td>
			  <div class="hidden-sm hidden-xs btn-group">
			     <a onclick=" document.getElementById('frmcontact').reset();document.getElementById('type').value='update';fetchcontactdetails('<?php echo $strCK_user_id?>','<?php echo $contacts[$i]['personal_contact_id'];?>','getcontact');" alt="Edit <?php echo $contacts[$i]['contact_nick_name']?>'s details" title="Update  <?php echo $contacts[$i]['contact_nick_name']?>'s details" data-toggle="modal" class="green" role="button" href="#modal-table">

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
			      <?php } ?>
			  </div>
		       </td>
		    </tr>
		    <?php } ?>
		   </tbody>
	      </table>
	      
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
												<input type="hidden" name="contactid"  id="contactid">
												<div class="form-group">
													<label for="form-field-1" class="col-sm-3 control-label no-padding-right"> 
														Nick Name :
													</label>
													<div class="col-sm-9" style="padding:6px 20px;">
														<b>
															<input type="text" class="col-sm-9" placeholder="Nick Name" id="contactnickname" name="contactnickname" required for="basic" value="" validate="yes" msg="Please the nick name" >
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
															<input type="text" class="col-sm-9" placeholder="First Name" id="contactfirstname" name="contactfirstname" required for="basic" value="" validate="yes" msg="Please enter the first name">
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
															<input type="text" class="col-sm-9" placeholder="Last Name" id="contactlastname" name="contactlastname" required for="basic" value="" validate="yes" msg="Please enter the last name" >
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
															<input type="text" class="col-sm-9" placeholder="Email Address" id="contactemailaddress" name="contactemailaddress" required for="basic" value="" validate="yes" msg="Please enter the email address">
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
															<input type="text" class="col-sm-9" placeholder="Phone No." id="contactphoneno" name="contactphoneno" required for="basic" value="" validate="yes" msg="Please enter the contact phoone number">
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
														 <select class="col-sm-9" for="address" name="contact_phone_idd" id="contact_phone_idd" class="form-control" validate="yes" msg="Please enter the country name">
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
            <script type="text/javascript" src="<?php echo JS_PATH; ?>data_tables/jquery.dataTables.js"></script>
            <script type="text/javascript" src="<?php echo JS_PATH; ?>data_tables/jquery.dataTables.bootstrap.js"></script>
            <script type="text/javascript" src="<?php echo JS_PATH; ?>data_tables/dataTables.tableTools.js"></script>
            <script type="text/javascript" src="<?php echo JS_PATH; ?>data_tables/dataTables.colVis.js"></script>
            <script type="text/javascript" src="<?php echo JS_PATH; ?>contact.js"></script>
        <!-- JAVA SCRIPT -->
       
    </body>
    
    <script type="text/javascript">
                        
            jQuery(function ($) {
                
                //initiate dataTables plugin
                var oTable1 =
                        $('#dynamic-table')
                        //.wrap("<div class='dataTables_borderWrap' />")   //if you are applying horizontal scrolling (sScrollX)
                        .dataTable({
                            bAutoWidth: false,
                            "aoColumns": [
                                {"bSortable": false},
                                null, null, null, null, null, null,
                                {"bSortable": false}
                            ],
                            "aaSorting": [],
                            //,
                            //"sScrollY": "200px",
                            //"bPaginate": false,

                            //"sScrollX": "100%",
                            //"sScrollXInner": "120%",
                            //"bScrollCollapse": true,
                            //Note: if you are applying horizontal scrolling (sScrollX) on a ".table-bordered"
                            //you may want to wrap the table inside a "div.dataTables_borderWrap" element

                            //"iDisplayLength": 50
                        });
                //oTable1.fnAdjustColumnSizing();


                //TableTools settings
                TableTools.classes.container = "btn-group btn-overlap";
                TableTools.classes.print = {
                    "body": "DTTT_Print",
                    "info": "tableTools-alert gritter-item-wrapper gritter-info gritter-center white",
                    "message": "tableTools-print-navbar"
                }

                //initiate TableTools extension
                var tableTools_obj = new $.fn.dataTable.TableTools(oTable1, {
                    
                    "sSwfPath": "<?php echo JS_PATH; ?>/data_tables/swf/copy_csv_xls_pdf.swf", //in Ace demo ../assets will be replaced by correct assets path

                    "sRowSelector": "td:not(:last-child)",
                    "sRowSelect": "multi",
//                    "fnRowSelected": function (row) {
//                        //check checkbox when row is selected
//                        try {
//                            $(row).find('input[type=checkbox]').get(0).checked = true
//                        }
//                        catch (e) {
//                        }
//                    },
//                    "fnRowDeselected": function (row) {
//                        //uncheck checkbox
//                        try {
//                            $(row).find('input[type=checkbox]').get(0).checked = false
//                        }
//                        catch (e) {
//                        }
//                    },
                    "sSelectedClass": "success",
                    "aButtons": [
                        {
                            "sExtends": "copy",
                            "sToolTip": "Copy to clipboard",
                            "sButtonClass": "btn btn-white btn-primary btn-bold",
                            "sButtonText": "<i class='fa fa-copy bigger-110 pink'></i>",
                            "fnComplete": function () {
                                this.fnInfo('<h3 class="no-margin-top smaller">Table copied</h3>\
                                                                <p>Copied ' + (oTable1.fnSettings().fnRecordsTotal()) + ' row(s) to the clipboard.</p>',
                                        1500
                                        );
                            }
                        },
                        {
                            "sExtends": "csv",
                            "sToolTip": "Export to CSV",
                            "sButtonClass": "btn btn-white btn-primary  btn-bold",
                            "sButtonText": "<i class='fa fa-file-excel-o bigger-110 green'></i>"
                        },
                        {
                            "sExtends": "pdf",
                            "sToolTip": "Export to PDF",
                            "sButtonClass": "btn btn-white btn-primary  btn-bold",
                            "sButtonText": "<i class='fa fa-file-pdf-o bigger-110 red'></i>"
                        },
                        {
                            "sExtends": "print",
                            "sToolTip": "Print view",
                            "sButtonClass": "btn btn-white btn-primary  btn-bold",
                            "sButtonText": "<i class='fa fa-print bigger-110 grey'></i>",
                            "sMessage": "<div class='navbar navbar-default'><div class='navbar-header pull-left'><a class='navbar-brand' href='#'><small>Optional Navbar &amp; Text</small></a></div></div>",
                            "sInfo": "<h3 class='no-margin-top'>Print view</h3>\
                                                                  <p>Please use your browser's print function to\
                                                                  print this table.\
                                                                  <br />Press <b>escape</b> when finished.</p>",
                        }
                    ]
                });
                //we put a container before our table and append TableTools element to it
                $(tableTools_obj.fnContainer()).appendTo($('.tableTools-container'));

                //also add tooltips to table tools buttons
                //addding tooltips directly to "A" buttons results in buttons disappearing (weired! don't know why!)
                //so we add tooltips to the "DIV" child after it becomes inserted
                //flash objects inside table tools buttons are inserted with some delay (100ms) (for some reason)
                setTimeout(function () {
                    $(tableTools_obj.fnContainer()).find('a.DTTT_button').each(function () {
                        var div = $(this).find('> div');
                        if (div.length > 0)
                            div.tooltip({container: 'body'});
                        else
                            $(this).tooltip({container: 'body'});
                    });
                }, 200);



                //ColVis extension
                var colvis = new $.fn.dataTable.ColVis(oTable1, {
                    "buttonText": "<i class='fa fa-search'></i>",
                    "aiExclude": [0, 7],
                    "bShowAll": true,
                    //"bRestore": true,
                    "sAlign": "right",
                    "fnLabel": function (i, title, th) {
                        return $(th).text();//remove icons, etc
                    }

                });

                //style it
                $(colvis.button()).addClass('btn-group').find('button').addClass('btn btn-white btn-info btn-bold')

                //and append it to our table tools btn-group, also add tooltip
                $(colvis.button())
                        .prependTo('.tableTools-container .btn-group')
                        .attr('title', 'Show/hide columns').tooltip({container: 'body'});

                //and make the list, buttons and checkboxed Ace-like
                $(colvis.dom.collection)
                        .addClass('dropdown-menu dropdown-light dropdown-caret dropdown-caret-right')
                        .find('li').wrapInner('<a href="javascript:void(0)" />') //'A' tag is required for better styling
                        .find('input[type=checkbox]').addClass('ace').next().addClass('lbl padding-8');



                /////////////////////////////////
                //table checkboxes
               // $('th input[type=checkbox], td input[type=checkbox]').prop('checked', false);

                //select/deselect all rows according to table header checkbox
//                $('#dynamic-table > thead > tr > th input[type=checkbox]').eq(0).on('click', function () {
//                    var th_checked = this.checked;//checkbox inside "TH" table header
//
//                    $(this).closest('table').find('tbody > tr').each(function () {
//                        var row = this;
//                        if (th_checked)
//                            tableTools_obj.fnSelect(row);
//                        else
//                            tableTools_obj.fnDeselect(row);
//                    });
//                });

                //select/deselect a row when the checkbox is checked/unchecked
//                $('#dynamic-table').on('click', 'td input[type=checkbox]', function () {
//                    var row = $(this).closest('tr').get(0);
//                    if (!this.checked)
//                        tableTools_obj.fnSelect(row);
//                    else
//                        tableTools_obj.fnDeselect($(this).closest('tr').get(0));
//                });

                $(document).on('click', '#dynamic-table .dropdown-toggle', function (e) {
                    e.stopImmediatePropagation();
                    e.stopPropagation();
                    e.preventDefault();
                });

                /********************************/
                //add tooltip for small view action buttons in dropdown menu
                $('[data-rel="tooltip"]').tooltip({placement: tooltip_placement});

                //tooltip placement on right or left
                function tooltip_placement(context, source) {
                    var $source = $(source);
                    var $parent = $source.closest('table');
                    var off1 = $parent.offset();
                    var w1 = $parent.width();
                    var off2 = $source.offset();
                    //var w2 = $source.width();

                    if (parseInt(off2.left) < parseInt(off1.left) + parseInt(w1 / 2))
                        return 'right';
                    return 'left';
                }

            });
        </script>

</html>
