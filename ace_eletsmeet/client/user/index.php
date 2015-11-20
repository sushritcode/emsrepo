<?php
require_once('../../includes/global.inc.php');
require_once('../includes/config.inc.php');
require_once(CLIENT_CLASSES_PATH . 'client_error.inc.php');
require_once(CLIENT_DBS_PATH . 'DataHelper.php');
require_once(CLIENT_DBS_PATH . 'objDataHelper.php');
require_once(CLIENT_INCLUDES_PATH . 'client_authfunc.inc.php');
$CLIENT_CONST_MODULE = 'cl_user';
$CLIENT_CONST_PAGEID = 'User Home';
require_once(CLIENT_INCLUDES_PATH . 'client_authorize.inc.php');
//require_once(CLIENT_INCLUDES_PATH . 'client_db_function.inc.php');
require_once(CLIENT_INCLUDES_PATH . 'client_reports_function.inc.php');

try
{    
    try
    {
        $arrUserList = getUserListByPartnernClient($strSetPartner_ID, $strSetClient_ID, $objDataHelper);
    }
    catch (Exception $a)
    {
        throw new Exception("index.php : getUserDetailsByClient : Error in populating List." . $a->getMessage(), 541);
    }
    $strTotalUserCount = count($arrUserList);
    
    //License Purchased
    $strOptLicenseType = '0'; 
    try
    {
        $arrTotalLicense = getSumOfClientLicenseByType($strSetClient_ID, $strOptLicenseType,$objDataHelper);
    }
    catch (Exception $a)
    {
        throw new Exception("index.php : getPlanDetails : Error in populating Plan Details." . $a->getMessage(), 541);
    }
   
    $strTotalLicense = $arrTotalLicense[0]['LicenseSum'];
    
    //License Consumed
    try
    {
        $arrTotalConsumedLicense = getTotalConsumedLicenseByClientId($strSetClient_ID, $objDataHelper);
    }
    catch (Exception $a)
    {
        throw new Exception("adduser.php : getTotalConsumedLicenseByClientId Failed." . $a->getMessage(), 541);
    }
    $strConsumedLicense = $arrTotalConsumedLicense[0]['ConsumedLicense'];
    
    $strTotalVacantLicense = $strTotalLicense - $strConsumedLicense;

    
}
catch (Exception $e)
{
    $ErrorHandler->RaiseError($_SERVER["PHP_SELF"], $e->getCode(), $e->getMessage(), true);
}

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
                        <div class="page-header">
                            <h1>
                                User<small><i class="ace-icon fa fa-angle-double-right"></i>&nbsp; user&#39;s &amp; license&#39;s</small>
                            </h1>
                        </div>
                        <!-- PAGE HEADER -->

                        <div class="row">
                            <div class="col-xs-12">
                                <!-- PAGE CONTENT START -->
                                
                                    <div class="alert alert-danger errorDisplay" id="mError"></div>
                              
                                    <h5 class="header smaller lighter blue">Total No. Of License : <span class="green"><strong><?php echo $strTotalLicense; ?></strong></span>&nbsp;&nbsp;Total No. Of Allocated License : <span class="red"><strong><?php echo $strConsumedLicense; ?></strong></span>&nbsp;&nbsp;Total No. Of Vacant License : <span class="orange2"><strong><?php echo $strTotalVacantLicense; ?></strong></span></h5>
                                
                                    <div class="clearfix">
                                        <div class="pull-right tableTools-container"></div>
                                         <div class="">
                                             <?php if ($strTotalLicense > $strConsumedLicense) {?>
                                               <button href="#add_user" data-toggle="modal" class="btn btn-white btn-info btn-bold" onclick="addUser('<?php echo $strSetPartner_ID; ?>', '<?php echo $strSetClient_ID; ?>')" alt="Add New User" title="Add New User"><i class="ace-icon fa fa-user bigger-120">&nbsp;<sup>+</sup></i></button>
                                             <?php }else{ ?>
                                               <div class="alert alert-warning col-xs-8">
                                                   <button class="close" data-dismiss="alert" type="button"><i class="ace-icon fa fa-times"></i></button>
                                                   Sorry, You have consumed all your License's, For more license please contact sales@letsmeet.com
                                               </div>
                                             <?php }?>
                                         </div>
                                    </div>
                                    
                                    
                                    <div class="table-header">
                                        User List 
                                    </div>
                                    
                                    <div>
                                        <div id="dynamic-table_wrapper" class="dataTables_wrapper form-inline no-footer">
                                            <!--  Actual Table Start  -->
                                             <table id="dynamic-table" class="table table-striped table-bordered table-hover dataTable no-footer DTTT_selectable" role="grid" aria-describedby="dynamic-table_info">
<!--                                             <table id="dynamic-table" class="table table-striped table-bordered table-hover">-->
                                                <thead>
                                                     <tr> 
                                                        <th class="center">
                                                            <label class="pos-rel">
                                                                    <i class="ace-icon fa fa-user hidden-480"></i>
                                                                    <span class="lbl"></span>
                                                            </label>
                                                        </th>
                                                        <th> Username </th>
                                                        <th> Nick Name </th>
                                                        <th> Name </th>
                                                        <th> Email Address </th>
                                                        <th> Status </th>
                                                        <th class="hidden-480"> Created On </th>
<!--                                                            <th class="hidden-480"> Mobile </th>
                                                        <th class="hidden-480"> Country Name </th>                                                           -->
                                                        <th></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php  for($intCntr = 0; $intCntr < sizeof($arrUserList); $intCntr++) {
                                                        $strCL_User_Id = $arrUserList[$intCntr]["user_id"];
                                                        $strCL_UserName = $arrUserList[$intCntr]["user_name"];
                                                        $strCL_User_NickName = $arrUserList[$intCntr]["nick_name"];
                                                        $strCL_User_Email = $arrUserList[$intCntr]["email_address"];
                                                        $strCL_User_FirstName = $arrUserList[$intCntr]["first_name"];
                                                        $strCL_User_LastName = $arrUserList[$intCntr]["last_name"];
                                                       
                                                        if (strlen($strCL_User_FirstName) <= 0) 
                                                        {
                                                            $strCL_User_FnLName = "---";
                                                        }
                                                        else
                                                        {
                                                            $strCL_User_FnLName = $strCL_User_FirstName." ".$strCL_User_LastName;
                                                        }

                                                        $strCL_User_Status = $arrUserList[$intCntr]["login_enabled"]; 
                                                        switch($strCL_User_Status)
                                                        {
                                                           case 0: $UserStatus = "<span class=\"label label-sm label-inverse\">Pending</span>";
                                                              break;
                                                           case 1: $UserStatus = "<span class=\"label label-sm label-success\">Active</span>";
                                                              break;
                                                           case 2: $UserStatus = "<span class=\"label label-sm label-warning\">Deative</span>";
                                                              break;
                                                           case 3: $UserStatus = "<span class=\"label label-sm label-danger\">Deleted</span>";
                                                              break;
                                                           default: break;
                                                        }
                                                        $strCL_User_CreatedOn = $arrUserList[$intCntr]["created_on"];
                                                    ?>
                                                    <tr>
                                                        <td class="center">
                                                            <label class="pos-rel">
                                                                    <i class="ace-icon fa fa-user hidden-480"></i>
                                                                <span class="lbl"></span>
                                                            </label>
                                                        </td>
                                                        <td><?php echo $strCL_UserName; ?></td>
                                                        <td><?php echo $strCL_User_NickName; ?></td>
                                                        <td><?php echo $strCL_User_FnLName; ?></td>
                                                        <td><?php echo $strCL_User_Email; ?></td>
                                                        <td><?php echo $UserStatus; ?></td>
                                                        <td class="hidden-480"> <?php echo $strCL_User_CreatedOn; ?> </td>
                                                        <td>
                                                            <div class="hidden-sm hidden-xs btn-group">
                                                                <button href="#user_details" data-toggle="modal" class="btn btn-sm" onclick="getUserDetails('<?php echo $strCL_User_Id; ?>', '<?php echo $strCL_UserName; ?>')" alt="User Details" title="User Details"><i class="ace-icon fa fa-info"></i></button>

                                                                <?php if ( ($strCL_User_Status == '0') || ($strCL_User_Status == '2') )  { ?>
                                                                    <button href="#user_status" data-toggle="modal" class="btn btn-sm btn-success" onclick="updUserStatus('<?php echo $strCL_User_Id; ?>', '1', '<?php echo $strCL_UserName; ?>')" alt="Activate" title="Activate"><i class="ace-icon fa fa-check"></i></button>
                                                                <?php } ?>
                                                                <?php if ($strCL_User_Status == '1')  { ?>
                                                                    <button href="#user_status" data-toggle="modal" class="btn btn-sm btn-warning" onclick="updUserStatus('<?php echo $strCL_User_Id; ?>', '2', '<?php echo $strCL_UserName; ?>')" alt="Deactivate" title="Deactivate"><i class="ace-icon fa fa-ban"></i></button>
                                                                <?php } ?>
                                                                 <?php if ($strCL_User_Status != '3')  { ?>
                                                                <button href="#user_status" data-toggle="modal" class="btn btn-sm btn-danger" onclick="updUserStatus('<?php echo $strCL_User_Id; ?>', '3', '<?php echo $strCL_UserName; ?>')" alt="Delete" title="Delete"><i class="ace-icon fa fa-remove"></i></button>
                                                                <?php } ?>
                                                                <?php if ($strCL_User_Status != '3')  { ?>        
                                                                <button href="#user_subscription" data-toggle="modal" class="btn btn-sm btn-info" onclick="addUserSubscription('<?php echo $strCL_User_Id; ?>', '<?php echo urldecode($strCL_UserName); ?>')" alt="Subscription Info" title="Subscription Info"><i class="ace-icon fa fa-key"></i></button>
                                                                <?php } ?>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <?php } ?>
                                                </tbody>
                                            </table>
                                            <!--  Actual Table End  -->
                                        </div>
                                    </div>
                                    
                                    <!--  pop up-->
                                    <div id="add_user" class="modal fade" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header no-padding">
                                                    <div class="table-header">
                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true" onclick="PageRefresh();" alt="Close" title="Close">
                                                            <span class="white">&times;</span>
                                                        </button>
                                                        &nbsp;
                                                    </div>
                                                </div>
                                                <div class="modal-body">
                                                    <div id="addUserDtls"></div>
                                                </div>
                                            </div>  
                                        </div>
                                    </div>
                                    
                                    <!--  pop up-->
                                    <div id="user_details" class="modal fade" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header no-padding">
                                                    <div class="table-header">
                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true" alt="Close" title="Close">
                                                            <span class="white">&times;</span>
                                                        </button>
                                                        &nbsp;
                                                    </div>
                                                </div>
                                                <div class="modal-body">
                                                    <div id="getUserDtls"></div>
                                                </div>
                                            </div>  
                                        </div>
                                    </div>
                                    
                                    <!--  pop up-->
                                    <div id="user_status" class="modal fade" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div id="updUserStatus"> </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!--  pop up-->
                                    <div id="user_subscription" class="modal fade" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header no-padding">
                                                    <div class="table-header">
                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true" alt="Close" title="Close">
                                                            <span class="white">&times;</span>
                                                        </button>
                                                        &nbsp;
                                                    </div>
                                                </div>
                                                <div class="modal-body">
                                                    <div id="addUserSub"></div>
                                                </div>
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
        <script type="text/javascript" src="<?php echo CLIENT_JS_PATH; ?>data_tables/jquery.dataTables.js"></script>
        <script type="text/javascript" src="<?php echo CLIENT_JS_PATH; ?>data_tables/jquery.dataTables.bootstrap.js"></script>
        <script type="text/javascript" src="<?php echo CLIENT_JS_PATH; ?>data_tables/dataTables.tableTools.js"></script>
        <script type="text/javascript" src="<?php echo CLIENT_JS_PATH; ?>data_tables/dataTables.colVis.js"></script>
        <!-- JAVA SCRIPT -->

    </body>
    <script type="text/javascript">
            var CLIENT_SITE_ROOT = "<?php echo $CLIENT_SITE_ROOT; ?>";
          
            document.onclick=function()
            {
                 document.getElementById('mError').style.display="none";
            };
                 
            jQuery(function ($) {
                //initiate dataTables plugin
                var oTable1 =
                        $('#dynamic-table')
                        //.wrap("<div class='dataTables_borderWrap' />")   //if you are applying horizontal scrolling (sScrollX)
                        .dataTable({
                            bAutoWidth: false,
                            "aoColumns": [
                                {"bSortable": false},
                                null, null, null, null,null,null,
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
                    "aiExclude": [0,7],
                    "bShowAll": true,
                    "bRestore": true,
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
            
            function addUser(prid,clid) {
            $.ajax({
                type: "GET",
                url: CLIENT_SITE_ROOT+"user/adduser.php",
                cache: false,
                data: "prid="+prid+"&clid="+clid+"&Num="+Math.random(),
                loading: $(".loading").html(""),
                success: function(html) {
                    $("#addUserDtls").html(html);
                }
            }); }
            
            function getUserDetails(userId,uName) {
            $.ajax({
                type: "GET",
                url: CLIENT_SITE_ROOT+"user/userdetails.php",
                cache: false,                
                data: "userId="+userId+"&uName="+uName+"&Num="+Math.random(),
                loading: $(".loading").html(""),
                success: function(html) {
                    $("#getUserDtls").html(html);
                }
            }); }
            
            function updUserStatus(userId,iStat,uName) {
            $.ajax({
                type: "GET",
                url: CLIENT_SITE_ROOT+"user/userstatus.php",
                cache: false,
                //data: "userId="+userId+"&iStat="+iStat+"&Num="+Math.random(),
                data: "userId="+userId+"&iStat="+iStat+"&uName="+uName+"&Num="+Math.random(),
                loading: $(".loading").html(""),
                success: function(html) {
                    $("#updUserStatus").html(html);
                }
            }); }
            
            function addUserSubscription(userId,uName) {
            $.ajax({
                type: "GET",
                url: CLIENT_SITE_ROOT+"user/addsubscription.php",
                cache: false,
                data: "userId="+userId+"&uName="+uName+"&Num="+Math.random(),
                loading: $(".loading").html(""),
                success: function(html) {
                    $("#addUserSub").html(html);
                }
            }); }
             
            function PageRefresh( ) 
            {
              location.reload(true);
            }
        </script>
</html>
