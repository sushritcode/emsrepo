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

$contacts = getAllcontactsByUserID($strCK_user_id , $objDataHelper);

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
                        <div class="page-header">
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
		       <th tabindex="0" aria-controls="dynamic-table" rowspan="1" colspan="1" >Group</th>
		       <th tabindex="0" aria-controls="dynamic-table" rowspan="1" colspan="1" >  Update </th>
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
		       <td class="hidden-480"><?php echo  $contacts[$i]['group_name'];?></td>
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
</html>
