<?php
require_once('../includes/global.inc.php');
require_once(CLASSES_PATH . 'error.inc.php');
require_once(DBS_PATH . 'DataHelper.php');
require_once(DBS_PATH . 'objDataHelper.php');
require_once(INCLUDES_PATH . 'cm_authfunc.inc.php');
$CONST_MODULE = 'meeting';
$CONST_PAGEID = 'Meeting Page';
require_once(INCLUDES_PATH . 'cm_authorize.inc.php');
//require_once(INCLUDES_PATH . 'common_function.inc.php');
require_once(INCLUDES_PATH . 'schedule_function.inc.php');

//echo GM_DATE;

try
{
   $arrSchMeetingList = getScheduledMeetingList($strCk_user_email_address , $objDataHelper);
}
catch(Exception $e)
{
   throw new Exception("index.php : getMyMeetingList Failed : ".$e->getMessage() , 1126);
}
//print_r($arrSchMeetingList);
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
                                Scheduled<small><i class="ace-icon fa fa-angle-double-right"></i>&nbsp;meetings</small>
                            </h1>
                        </div>
                        <!-- PAGE HEADER -->

                        <div class="row">
                            <div class="col-xs-12">
                                <!-- PAGE CONTENT START -->
                                
<!--                             <div class="table-header">
                                        Scheduled Meetings
                                    </div>-->
                                <div class="alert alert-danger errorDisplay" id="mError"></div>
                        
                                <?php 
                                if((is_array($arrSchMeetingList)) && (count($arrSchMeetingList)) > 0){ ?>

                                    <div class="clearfix">
                                        <div class="pull-right tableTools-container"></div>
                                    </div>
                                    <div class="table-header">
                                        Scheduled Meetings
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
                                                                        <i class="ace-icon fa fa-calendar bigger-110 hidden-480"></i>
                                                                        <span class="lbl"></span>
                                                                </label>
                                                            </th>
                                                            <th> Meeting Title </th>
                                                            <th><i class="ace-icon fa fa-clock-o bigger-110 hidden-480"></i> Meeting Datetime </th>
                                                            <th class="hidden-480"> No. of Invitee </th>
                                                            <th class="hidden-480"><i class="ace-icon fa fa-user bigger-110 hidden-480"></i> Moderator </th>                                                           
                                                            <th></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php  for($intCntr = 0; $intCntr < sizeof($arrSchMeetingList); $intCntr++) {
                                                        $schScheduleId = $arrSchMeetingList[$intCntr]["schedule_id"];
                                                        $sTitle = $arrSchMeetingList[$intCntr]["meeting_title"];
                                                        $schTitle = implode(" " , array_splice(explode(" " , $sTitle) , 0 , 5));
                                                        if (count(explode(" ",$schTitle)) > 5)
                                                        {
                                                            $schTitle = $schTitle."...";
                                                        }
                                                        else if (strlen($smTitle) > 20) 
                                                        {
                                                            $schTitle = substr($schTitle,0,20)."...";
                                                        }
                                                        $schDateTime = $arrSchMeetingList[$intCntr]["meeting_timestamp_local"];
                                                        $schGmtTime = $arrSchMeetingList[$intCntr]["meeting_timestamp_gmt"];
                                                        $schCreator = $arrSchMeetingList[$intCntr]["invitation_creator"];
                                                        $schStatus = $arrSchMeetingList[$intCntr]["schedule_status"]; 
                                                        
                                                        if($schCreator == "C") 
                                                        {
                                                            $schModerator = $strCk_user_nick_name;
                                                        }
                                                        else
                                                        {
                                                           try
                                                           {
                                                              $arrModDetails = getModeratorDetails($schScheduleId , $objDataHelper);
                                                           }
                                                           catch(Exception $e)
                                                           {
                                                              throw new Exception("index.php : moderatorDetails Failed : ".$e->getMessage() , 1126);
                                                           }
                                                           $schModerator = $arrModDetails[0]["invitee_nick_name"];
                                                        }
                                                        $schInviteeCount = $arrSchMeetingList[$intCntr]["max_participants"];
                                                        
                                                        $gmtStartTime = date("Y-m-d H:i:s" , strtotime($schGmtTime."-".MEETING_START_GRACE_INTERVAL." min"));
                                                        $gmtEndTime = date("Y-m-d H:i:s" , strtotime($schGmtTime."+".MEETING_END_GRACE_INTERVAL." min"));
                                                        $schPassCode = md5($schScheduleId . ":" . $strCk_user_email_address . ":" . SECRET_KEY);
                                                    ?>
                                                    <tr>
                                                        <td class="center">
                                                            <label class="pos-rel">
                                                                    <i class="ace-icon fa fa-calendar smaller hidden-480"></i>
                                                                <span class="lbl"></span>
                                                            </label>
                                                        </td>
                                                        <td><?php echo $schTitle; ?></td>
                                                        <td><?php echo $schDateTime; ?></td>
                                                        <td class="hidden-480"> <?php echo $schInviteeCount; ?> </td>
                                                        <td class="hidden-480"> <?php echo $schModerator; ?> </td>
                                                        <td>
                                                            <div class="hidden-sm hidden-xs btn-group">
                                                                    <!--   <a href="#" id="id-btn-dialog2" class="btn btn-info btn-sm">Confirm Dialog</a>-->
                                                                    <a href="#sch-detls" data-toggle="modal" class="btn btn-xs btn-inverse" onclick="meetingDetails('<?php echo $schScheduleId; ?>', '<?php echo $schPassCode; ?>')" alt="Details" title="Details">&nbsp;<i class="ace-icon fa fa-info bigger-120"></i>&nbsp;</a>
                                                                    <?php if((GM_DATE > $gmtStartTime) && (GM_DATE <= $gmtEndTime)) { ?>
                                                                    <button class="btn btn-xs btn-info" onclick="joinMeeting('<?php echo $schScheduleId; ?>')" alt="Join" title="Join">
                                                                            <i class="ace-icon fa fa-users bigger-120"></i>
                                                                    </button>
                                                                    <?php } ?>
                                                                    
                                                                    <?php if (($schCreator == "C") &&   (GM_DATE > $gmtStartTime) && (GM_DATE <= $gmtEndTime) && ($schStatus == "0") ){ ?>
                                                                    <button class="btn btn-xs btn-danger" alt="Cancel" title="Cancel">
                                                                            <i class="ace-icon fa fa-remove bigger-120"></i>
                                                                    </button>
                                                                    <?php } ?>

                                                                    <?php if (($schCreator == "C")  &&  (GM_DATE > $gmtStartTime) && (GM_DATE <= $gmtEndTime) && (($schStatus == "0") ||($schStatus == "1")) ){ ?>
                                                                    <button class="btn btn-xs btn-warning" alt="Add Invitee" title="Add Invitee">
                                                                            <i class="ace-icon fa fa-user bigger-120"><sup>+</sup></i>
                                                                    </button>
                                                                    <button class="btn btn-xs btn-purple" alt="Resend Invitee Email" title="Resend Invitee Email">
                                                                            <i class="ace-icon fa fa-envelope-o bigger-120"></i>
                                                                    </button>
                                                                    <?php } ?>
                                                                    
                                                                    <?php if ($schCreator != "C") { ?>
                                                                    <button class="btn btn-xs btn-success" onclick="inviteeStatus('<?php echo $schScheduleId; ?>',1)" alt="Accept" title="Accept">
                                                                            <i class="ace-icon fa fa-thumbs-o-up bigger-120"></i>
                                                                    </button>
                                                                    <button class="btn btn-xs btn-pink" onclick="inviteeStatus('<?php echo $schScheduleId; ?>',3)" alt="MayBe" title="MayBe">
                                                                        &nbsp;<i class="ace-icon fa fa-question bigger-120"></i>&nbsp;
                                                                    </button>
                                                                    <button class="btn btn-xs btn-danger" onclick="inviteeStatus('<?php echo $schScheduleId; ?>',2)" alt="Decline" title="Decline">
                                                                            <i class="ace-icon fa fa-thumbs-o-down bigger-120"></i>
                                                                    </button>
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
                                    <div id="dialog-confirm" class="hide">
                                            <div class="alert alert-info bigger-110">
                                                    These items will be permanently deleted and cannot be recovered.
                                            </div>

                                            <div class="space-6"></div>

                                            <p class="bigger-110 bolder center grey">
                                                    <i class="ace-icon fa fa-hand-o-right blue bigger-120"></i>
                                                    Are you sure?
                                            </p>
                                    </div>
                                
                                <?php }else{?>

                                    <div class="alert alert-block alert-danger">
                                        <strong >Sorry</strong>, No meeting scheduled.
                                    </div>
                                                
                               <?php } ?>

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
        <!-- JAVA SCRIPT -->
       
    </body>
    
    <script type="text/javascript">
            document.onclick=function()
            {
                 document.getElementById('mError').style.display="none";
            };
            
            var SITE_ROOT = "<?php echo $SITE_ROOT; ?>";

            function joinMeeting(schId) {
                window.open(SITE_ROOT+"schedule/start.php?startId="+schId);
            }
            
            
             function inviteeStatus (schId,iStat)
            {
                $.ajax({
                    type: "GET",
                    //url: "inviteestatus.php",
                    url: SITE_ROOT+"meeting/inviteestatus.php",
                    cache: false,
                    data: "SchId="+schId+"&iStat="+iStat+"&Num="+Math.random(),
                    loading: $(".loading").html(""),
                    success: function(html) {
                        if (iStat == 1) 
                        {
                            $("#mError").html("You have Accepted the meeting request.");
                            $("#mError").removeClass("alert-danger alert-warning").addClass("alert-success");
                        }
                        else if (iStat == 2) 
                        {
                            $("#mError").html("You have Declined the meeting request.");
                            $("#mError").removeClass("alert-success alert-warning").addClass("alert-danger");
                        }
                        else 
                        {
                            $("#mError").html("You are not sure for this meeting request.");
                            $("#mError").removeClass("alert-success alert-danger").addClass("alert-warning");
                        }
                        $("#mError").css({"display":"block"});
                    }
                });
            }

        
            function meetingDetails(schId,schdtl) {
                $.ajax({
                    type: "GET",
                    //url: "meetingdetails.php",
                    url: SITE_ROOT+"meeting/meetingdetails.php",
                    cache: false,
                    data: "SchId="+schId+"&SchDtl="+schdtl+"&Num="+Math.random(),
                    loading: $(".loading").html(""),
                    success:    function(html) {
                        $("#SubDetails").html(html);
                    }
                }); }
            
            jQuery(function ($) {
                
                
                //override dialog's title function to allow for HTML titles
                $.widget("ui.dialog", $.extend({}, $.ui.dialog.prototype, {
                        _title: function(title) {
                                var $title = this.options.title || '&nbsp;'
                                if( ("title_html" in this.options) && this.options.title_html == true )
                                        title.html($title);
                                else title.text($title);
                        }
                }));
                $( "#id-btn-dialog2" ).on('click', function(e) {
                e.preventDefault();

                $( "#dialog-confirm" ).removeClass('hide').dialog({
                                resizable: false,
                                width: '320',
                                modal: true,
                                title: "<div class='widget-header'><h4 class='smaller'><i class='ace-icon fa fa-exclamation-triangle red'></i> Empty the recycle bin?</h4></div>",
                                title_html: true,
                                buttons: [
                                        {
                                                html: "<i class='ace-icon fa fa-trash-o bigger-110'></i>&nbsp; Delete all items",
                                                "class" : "btn btn-danger btn-minier",
                                                click: function() {
                                                        $( this ).dialog( "close" );
                                                }
                                        }
                                        ,
                                        {
                                                html: "<i class='ace-icon fa fa-times bigger-110'></i>&nbsp; Cancel",
                                                "class" : "btn btn-minier",
                                                click: function() {
                                                        $( this ).dialog( "close" );
                                                }
                                        }
                                ]
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
                                null, null, null, null,
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
                    "aiExclude": [0, 5],
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
