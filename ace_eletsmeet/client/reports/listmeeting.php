<?php
require_once('../../includes/global.inc.php');
require_once('../includes/config.inc.php');
require_once(CLIENT_CLASSES_PATH . 'client_error.inc.php');
require_once(CLIENT_DBS_PATH . 'DataHelper.php');
require_once(CLIENT_DBS_PATH . 'objDataHelper.php');
require_once(CLIENT_INCLUDES_PATH . 'client_authfunc.inc.php');
$CLIENT_CONST_MODULE = 'cl_reports';
$CLIENT_CONST_PAGEID = 'Meeting List';
require_once(CLIENT_INCLUDES_PATH . 'client_authorize.inc.php');
require_once(CLIENT_INCLUDES_PATH . 'client_reports_function.inc.php');

try
{
    try
    {
        $arrDateRange = getMinMaxScheduleDateByClient_Id($strSetClient_ID, $objDataHelper);
    }
    catch (Exception $a)
    {
        throw new Exception("index.php : getNumberOfLicenseList : Error in populating List." . $a->getMessage(), 541);
    }
    $strMin_Date = $arrDateRange[0]['MinimumDate'];
    $strMax_Date = $arrDateRange[0]['MaximumDate'];
    $strCurr_Date = $arrDateRange[0]['CurrentDate'];   

    if($_REQUEST['q'] == "user")
    {
        $from_date = trim($_REQUEST['d1']);        
        $to_date = trim($_REQUEST['d2']);
    }
    else
    {
//        $to_date = date("Y-m-j");
//        $newdate = strtotime ( '-11 month' , strtotime ( date("Y-m-j") ) ) ;
//        $from_date = date ( 'Y-m-j' , $newdate );

        $from_date = $strMin_Date;
        $to_date = $strCurr_Date;
    }

    try
    {
        $arrAllMeetingList = getAllMeetingListByClient_Id($strSetClient_ID, $from_date, $to_date, $objDataHelper);
    }
    catch (Exception $a)
    {
        throw new Exception("index.php : getNumberOfLicenseList : Error in populating List." . $a->getMessage(), 541);
    }   
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
        <link rel="stylesheet" href="../../assets/css/daterangepicker.css" />
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
                                Meeting<small><i class="ace-icon fa fa-angle-double-right"></i>&nbsp; list &amp; information</small>
                            </h1>
                        </div>
                        <!-- PAGE HEADER -->

                        <div class="row">
                            <div class="col-xs-12">
                                <!-- PAGE CONTENT START -->
                                
                                    <div class="clearfix">                                        
                                        <div class="pull-right tableTools-container"></div> 
                                            <div class="row">
                                                    <div class="col-xs-3 col-sm-3">
                                                            <div class="input-group">
                                                                    <span class="input-group-addon">
                                                                            <i class="fa fa-calendar"></i>
                                                                    </span>
                                                                <form id="frmMeetingReport" name="frmMeetingReport" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>" class="form-horizontal" role="form">
                                                                    <input  type="hidden"  name="q"  value="user" />
                                                                    <input  type="hidden"  name="d1"  id="d1" value="<?php echo $strMin_Date;?>" />
                                                                    <input  type="hidden"  name="d2"   id="d2" value="<?php echo $strCurr_Date;?>" />
                                                                    <input class="form-control" type="text" name="date-range-picker" id="report-date-range" value="<?php echo $from_date; ?> - <?php echo $to_date; ?>"/>
                                                                 </form>
                                                            </div>
                                                    </div>
                                            </div>                                        
                                    </div>
                                    
                                    <div class="table-header">Meeting List</div>
                                    
                                    <div>
                                        <div id="dynamic-table_wrapper" class="dataTables_wrapper form-inline no-footer">
                                            <!--  Actual Table Start  -->
                                             <table id="dynamic-table" class="table table-striped table-bordered table-hover dataTable no-footer DTTT_selectable" role="grid" aria-describedby="dynamic-table_info">
                                                <thead>
                                                     <tr> 
                                                        <th class="center">
                                                            <label class="pos-rel">
                                                                    <i class="ace-icon fa fa-calendar hidden-480"></i>
                                                                    <span class="lbl"></span>
                                                            </label>
                                                        </th>
                                                        <th> Username </th>
                                                        <th> Creation Time </th>
                                                        <th> Meeting Date </th>
                                                        <th> Title </th>
                                                        <th> Meeting Start Time </th>
                                                        <th> Meeting End Time </th>
                                                        <th> No. Of Participants </th>
                                                        <th> Status</th>
                                                        <th></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php  for($intCntr = 0; $intCntr < sizeof($arrAllMeetingList); $intCntr++) {
                                                        $SchID = $arrAllMeetingList[$intCntr]["schedule_id"];
                                                        $SchUserID = $arrAllMeetingList[$intCntr]["user_id"];
                                                        $SchUserName = $arrAllMeetingList[$intCntr]["user_name"];  
                                                        $SchStatus = $arrAllMeetingList[$intCntr]["schedule_status"];
                                                        $SchCreationTime = $arrAllMeetingList[$intCntr]["schedule_creation_time"];
                                                        $MeetingTimeGMT = $arrAllMeetingList[$intCntr]["meeting_timestamp_gmt"];
                                                        $MeetingTimeLocal = $arrAllMeetingList[$intCntr]["meeting_timestamp_local"];
                                                        $MTitle = $arrAllMeetingList[$intCntr]["meeting_title"];
                                                        $MeetingTimezone = $arrAllMeetingList[$intCntr]["meeting_timezone"];
                                                        $MeetingGMT = $arrAllMeetingList[$intCntr]["meeting_gmt"];
                                                        $MeetingStartTime = $arrAllMeetingList[$intCntr]["meeting_start_time"];
                                                        if (strlen($MeetingStartTime) <= 0) 
                                                        {
                                                            $MeetingStartTime = "---";
                                                        }
                                                        $MeetingEndTime = $arrAllMeetingList[$intCntr]["meeting_end_time"];
                                                        if (strlen($MeetingEndTime) <= 0) 
                                                        {
                                                            $MeetingEndTime = "---";
                                                        }
                                                        $MaxParticipants = $arrAllMeetingList[$intCntr]["max_participants"];
                                                        
                                                        $schPassCode = md5($SchID . ":" . $strDBUserEmail . ":" . SECRET_KEY);
                                                        
                                                        
                                                        switch($SchStatus)
                                                        {
                                                           case 0: $MeetingStatus = "<span class=\"label label-sm label-warning\">Scheduled</span>";
                                                              break;
                                                           case 1: $MeetingStatus = "<span class=\"label label-sm label\">In Progress</span>";
                                                              break;
                                                           case 2: $MeetingStatus = "<span class=\"label label-sm label-success\">Completed</span>";
                                                              break;
                                                           case 3: $MeetingStatus = "<span class=\"label label-sm label-inverse\">Cancelled</span>";
                                                              break;
                                                           case 4: $MeetingStatus = "<span class=\"label label-sm label-warning\">Overdue</span>";
                                                              break;
                                                           case 5: $MeetingStatus = "<span class=\"label label-sm label-danger\">Error</span>";
                                                              break;
                                                           default: break;
                                                        }
                                                        $MeetingTitle = implode(" " , array_splice(explode(" " , $MTitle) , 0 , 5));
                                                        if (count(explode(" ",$MeetingTitle)) > 5)
                                                        {
                                                            $MeetingTitle = $MeetingTitle."...";
                                                        }
                                                        else if (strlen($MeetingTitle) > 20) 
                                                        {
                                                            $MeetingTitle = substr($MeetingTitle,0,20)."...";
                                                        }
                                                    ?>
                                                    <tr>
                                                        <td class="center">
                                                            <label class="pos-rel">
                                                                    <i class="ace-icon fa fa-calendar hidden-480"></i>
                                                                <span class="lbl"></span>
                                                            </label>
                                                        </td>
                                                        <td><?php echo $SchUserName; ?></td>
                                                        <td><?php echo $SchCreationTime; ?></td>
                                                        <td><?php echo $MeetingTimeLocal; ?></td>
                                                        <td><?php echo $MeetingTitle; ?></td>
<!--                                                        <td><?php //echo $MeetingTimezone." ". $MeetingGMT; ?></td>-->
                                                        <td><?php echo $MeetingStartTime; ?></td>
                                                        <td><?php echo $MeetingEndTime; ?></td>
                                                        <td><?php echo $MaxParticipants; ?></td>
                                                        <td><?php echo $MeetingStatus; ?></td>
                                                        <td>
                                                            <div class="hidden-sm hidden-xs btn-group">
                                                                <button href="#sch-detls" data-toggle="modal" class="btn btn-sm" onclick="meetingDetails('<?php echo $SchID; ?>','<?php echo $strDBUserEmail; ?>', '<?php echo $schPassCode; ?>')" alt="Details" title="Details"><i class="ace-icon fa fa-info bigger-110"></i></button>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <?php } ?>
                                                </tbody>
                                            </table>
                                            <!--  Actual Table End  -->
                                            <hr>
                                            <div><a class="btn btn-sm btn-grey pull-right" href="<?php echo $CLIENT_SITE_ROOT.'reports/' ?>"><i class="ace-icon fa fa-arrow-left"></i>Go Back</a></div>
                                        </div>
                                    </div>
                                                                   
                                    <!--  pop up-->
                                    <div id="sch-detls" class="modal fade" tabindex="-1">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header no-padding">
                                                    <div class="table-header">
                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                                                            <span class="white">&times;</span>
                                                        </button>
                                                        &nbsp;
                                                    </div>
                                                </div>
                                                <div class="modal-body">
                                                    <div id="SubDetails"></div>
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
        <script type="text/javascript" src="<?php echo CLIENT_JS_PATH; ?>moment.js"></script>
        <script type="text/javascript" src="<?php echo CLIENT_JS_PATH; ?>daterangepicker.js"></script>
        <!-- JAVA SCRIPT -->

    </body>
    <script type="text/javascript">
            var CLIENT_SITE_ROOT = "<?php echo $CLIENT_SITE_ROOT; ?>";
          
//            document.onclick=function()
//            {
//                 document.getElementById('mError').style.display="none";
//            };
                 
            jQuery(function ($) {
                
                
                $(function() {

                     $('#report-date-range').daterangepicker({
                        "autoApply": true,
                        "autoUpdateInput": true,
                        "format": "YYYY-MM-DD",
                        "startDate": "<?php echo $from_date; ?>",
                        "endDate": "<?php echo $to_date; ?>",
                        "applyClass" : "btn-sm btn-success",
                        "cancelClass" : "btn-sm btn-default",
                        locale: {
                            cancelLabel: 'Reset'
                        }
                    });

                    $('#report-date-range').on('apply.daterangepicker', function(ev, picker) {
                        $(this).val(picker.startDate.format('DD-MM-YYYY') + ' - ' + picker.endDate.format('DD-MM-YYYY'));
                        var clStartDate   = picker.startDate.format('YYYY-MM-DD');
                        var clEndDate   = picker.endDate.format('YYYY-MM-DD');
                        document.frmMeetingReport.d1.value = clStartDate;
                        document.frmMeetingReport.d2.value = clEndDate;
                        $("#frmMeetingReport").submit();

                       // $.post("listmeeting.php", {txStartDate: clStartDate, txEndDate: clEndDate}, function (data)
                        //{                        
                           // var response=data;
                            //$("#response").html(data);    
                        //});
                       // return false;
                    });

                    $('#report-date-range').on('cancel.daterangepicker', function(ev, picker) {
                        document.frmMeetingReport.d1.value = "<?php  echo $strMin_Date; ?>";
                        document.frmMeetingReport.d2.value = "<?php  echo $strCurr_Date; ?>";
                        $("#frmMeetingReport").submit();
                    });

              });
                
                //initiate dataTables plugin
                var oTable1 =
                $('#dynamic-table')
                //.wrap("<div class='dataTables_borderWrap' />")   //if you are applying horizontal scrolling (sScrollX)
                .dataTable({
                    bAutoWidth: false,
                    "aoColumns": [
                        {"bSortable": false},
                        null,null,null,null,null,null,null,null, 
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
                    "aiExclude": [0,9],
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
            
           function meetingDetails(schId,email,schdtl) {
            $.ajax({
                type: "GET",
                url: CLIENT_SITE_ROOT+"reports/meetingdetails.php",
                cache: false,
                data: "SchId="+schId+"&Email="+email+"&SchDtl="+schdtl+"&Num="+Math.random(),
                loading: $(".loading").html(""),
                success: function(html) {
                    $("#SubDetails").html(html);
                }
            }); }
            
        </script>
</html>
