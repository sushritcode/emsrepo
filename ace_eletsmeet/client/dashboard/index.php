<?php
require_once('../../includes/global.inc.php');
require_once('../includes/config.inc.php');
require_once(CLIENT_CLASSES_PATH . 'client_error.inc.php');
require_once(CLIENT_DBS_PATH . 'DataHelper.php');
require_once(CLIENT_DBS_PATH . 'objDataHelper.php');
require_once(CLIENT_INCLUDES_PATH . 'client_authfunc.inc.php');
$CLIENT_CONST_MODULE = 'cl_dashboard';
$CLIENT_CONST_PAGEID = 'Client Dashboard';
require_once(CLIENT_INCLUDES_PATH . 'client_authorize.inc.php');
require_once(CLIENT_INCLUDES_PATH . 'client_dashboard_function.inc.php');

try
{
    $arrLicenseCount = getLicenseCountByID($strSetClient_ID, $objDataHelper);
}
catch (Exception $e)
{
    throw new Exception("index.php : getLicenseCountByID Failed : " . $e->getMessage(), 1125);
}
$strTotalLicense = $arrLicenseCount[0]['TotalLicense'];

try
{
    $arrTotalConsumedLicense = getTotalConsumedLicenseByClientId($strSetClient_ID, $objDataHelper);
}
catch (Exception $a)
{
    throw new Exception("adduser.php : getTotalConsumedLicenseByClientId Failed." . $a->getMessage(), 541);
}
$strTotalConsumedLicense = $arrTotalConsumedLicense[0]['ConsumedLicense'];

try
{
    $arrContactCount = getContactCountByID($strSetClient_ID, $objDataHelper);
}
catch (Exception $e)
{
    throw new Exception("index.php : getContactCountByID Failed : " . $e->getMessage(), 1125);
}
$strTotalContacts = $arrContactCount[0]['TotalContacts'];

try
{
    $arrHostMeetingCount = getMeetingCountByID($strSetClient_ID, $objDataHelper);
}
catch (Exception $e)
{
    throw new Exception("index.php : getMeetingCountByID Failed : " . $e->getMessage(), 1125);
}
$strTotalMeeting = $arrHostMeetingCount[0]['TotalMeeting'];

try
{
    $arrMeetingDuration = getMeetingDurationByID($strSetClient_ID, $objDataHelper);
}
catch (Exception $e)
{
    throw new Exception("index.php : getMeetingDurationByID Failed : " . $e->getMessage(), 1125);
}
$strTotalDuration = $arrMeetingDuration[0]['TotalMinutes'];

try
{
    $arrMeetingOverview = getMeetingOverviewByID($strSetClient_ID, $objDataHelper);
}
catch (Exception $e)
{
    throw new Exception("index.php : getMeetingOverviewByID Failed : " . $e->getMessage(), 1125);
}
//print_r($arrMeetingOverview);

 try
{
    $arrSubscriptionInfo = getClientSubscriptionInfo($strSetPartner_ID, $strSetClient_ID, $objDataHelper);
}
catch (Exception $a)
{
    throw new Exception("index.php : getClientSubscriptionInfo : Error in populating List." . $a->getMessage(), 541);
}

try
{
    $arrProfileCompletePercent = getProfileCompletePercent($strSetClient_ID, $objDataHelper);
}
catch (Exception $e)
{
    throw new Exception("index.php : getProfileCompletePercent Failed : " . $e->getMessage(), 1125);
}
$strProfileCompletePercent = $arrProfileCompletePercent[0]['ProfilePercentage'];


$start_date = "05/2015" ;
$end_date = "11/2015" ;
        
try
{
    $arrMonthWiseMeetingGraph = getMonthWiseMeetingGraph($strSetClient_ID, $start_date, $end_date, $objDataHelper);
}
catch (Exception $e)
{
    throw new Exception("index.php : getMinuteBaseMeetingGraphByID Failed : " . $e->getMessage(), 1125);
}

//TotalMinute //DateOfMeeting

for ($i = 0; $i < sizeof($arrMonthWiseMeetingGraph); $i++)
{
    //$arrMonthArr .= $arrMonthWiseMeetingGraph[$i]['MeetingMonth'].",";
    $arrMonthArr .= "'".$arrMonthWiseMeetingGraph[$i]['MeetingMonth']."',";
}
$arrMonthArr = substr($arrMonthArr, 0, -1);

//print_r($arrMonthArr);
        
for ($i = 0; $i < sizeof($arrMonthWiseMeetingGraph); $i++)
{
    $arrTotalMeetingArr .= $arrMonthWiseMeetingGraph[$i]['TotalMeetings'].",";
}
$arrTotalMeetingArr = substr($arrTotalMeetingArr, 0, -1);

//print_r($arrTotalMeetingArr);


//$noOfInvitees = 9;
//
//try
//{
//    $arrFrequentInvitees = getFrequentInvitees($strCK_user_id, $noOfInvitees, $objDataHelper);
//}
//catch (Exception $e)
//{
//    throw new Exception("index.php : getFrequentInvitees Failed : " . $e->getMessage(), 1125);
//}
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

                    <div class="page-header">
                        <h1>
                            <?php echo $strSetClient_Name; ?>
                        </h1>
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
                                Dashboard<small><i class="ace-icon fa fa-angle-double-right"></i>&nbsp;overview &amp; stats</small>
                            </h1>
                        </div>
                        <!-- PAGE HEADER -->

                        <div class="row">
                            <div class="col-xs-12">
                                <!-- PAGE CONTENT START -->
                                <div class="row">

                                    <div class="col-sm-6 infobox-container">
                                        
                                        <div class="infobox infobox-green">
                                            <div class="infobox-icon">
                                                <i class="ace-icon fa fa-pencil-square-o "></i>
                                            </div>
                                            <div class="infobox-data">
                                                <span class="infobox-data-number"><?php echo $strTotalLicense; ?></span>
                                                <div class="infobox-content">Total No. of License</div>
                                            </div>
                                            <!--   <div class="stat stat-success">8%</div>-->
                                        </div>
                                        
                                        <div class="infobox infobox-red">
                                            <div class="infobox-icon">
                                                <i class="ace-icon fa fa-pencil-square-o "></i>
                                            </div>
                                            <div class="infobox-data">
                                                <span class="infobox-data-number"><?php echo $strTotalConsumedLicense; ?></span>
                                                <div class="infobox-content">Consumed License</div>
                                            </div>
                                        </div>
                                        
                                        
                                        
                                        <div class="infobox infobox-blue">
                                            <div class="infobox-icon">
                                                <i class="ace-icon fa fa-phone"></i>
                                            </div>
                                            <div class="infobox-data">
                                                <span class="infobox-data-number"><?php echo $strTotalContacts; ?></span>
                                                <div class="infobox-content">Total No. of Contacts</div>
                                            </div>
                                        </div>

                                        <div class="infobox infobox-green2">
                                            <div class="infobox-icon">
                                                <i class="ace-icon fa fa-users"></i>
                                            </div>
                                            <div class="infobox-data">
                                                <span class="infobox-data-number"><?php echo $strTotalMeeting; ?></span>
                                                <div class="infobox-content">Total Meeting Hosted</div>
                                            </div>
                                        </div>
                                        
                                        <div class="infobox infobox-pink">
                                            <div class="infobox-icon">
                                                <i class="ace-icon fa fa-comments-o"></i>
                                            </div>
                                            <div class="infobox-data">
                                                <span class="infobox-data-number"><?php echo $strTotalDuration; ?></span>
                                                <div class="infobox-content">Total Meeting Minutes</div>
                                            </div>
                                        </div>

                                        <div class="infobox infobox-orange">
                                            <div class="infobox-icon">
                                                <i class="ace-icon fa fa- fa-user"></i>
                                            </div>
                                            <div class="infobox-data">
                                                <span class="infobox-data-number"><?php echo $strProfileCompletePercent; ?> &percnt;</span>
                                                <div class="infobox-content small">Profile  Complete</div>
                                            </div>
                                        </div>
                                        
                                    </div>

                                    <div class="vspace-12-sm"></div>

                                    <div class="col-sm-6">
                                        <div class="widget-box">
                                            <div class="widget-header widget-header-flat widget-header-small">
                                                <h5 class="widget-title">
                                                    <i class="ace-icon fa fa-users"></i>
                                                    Meeting Statistics
                                                </h5>
                                            </div>
                                            <div class="widget-body">
                                                <div class="widget-main">
                                                    <div class="center">
                                                         <?php  for($intCntr = 0; $intCntr < sizeof($arrMeetingOverview); $intCntr++) {  
                                                             $strLabel = $arrMeetingOverview[$intCntr]['label'];
                                                             $strData  = $arrMeetingOverview[$intCntr]['data'];
                                                             $strColor = $arrMeetingOverview[$intCntr]['color'];
                                                         ?>
                                                        <div class="infobox infobox-<?php echo $strColor;?> infobox-small infobox-dark">
                                                                <div class="infobox-icon">
                                                                        <i class="ace-icon fa fa-users"></i>
                                                                </div>
                                                                <div class="infobox-data">
                                                                        <div class="infobox-content"><?php echo $strLabel; ?></div>
                                                                        <div class="infobox-content"><?php echo $strData ?></div>
                                                                </div>
                                                        </div>
<!--                                                        <div class="infobox infobox-<?php echo $strColor;?> infobox-small infobox-dark">
                                                                <div class="infobox-progress">
                                                                        <div data-size="39" data-percent="<?php echo $strData ?>" class="easy-pie-chart percentage" style="height: 39px; width: 39px; line-height: 38px;">
                                                                                <span class="percent"><?php echo $strData ?></span>%
                                                                        <canvas height="39" width="39"></canvas></div>
                                                                </div>

                                                                <div class="infobox-data">
                                                                        <div class="infobox-content"><?php echo $strLabel; ?></div>
                                                                </div>
                                                        </div>-->
                                                         <?php } ?>
                                                    </div>  
                                                    <div class="hr hr-dotted"></div>
                                                    <p><i><a href="<?php echo $CLIENT_SITE_ROOT."reports/"?>">Click to see more...</a></i></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                </div>

                                <div class="hr hr32 hr-dotted"></div>
                                
                                <div class="row">
                                    
                                     <div class="col-sm-6">
                                        <div class="widget-box">
                                            <div class="widget-header widget-header-flat widget-header-small">
                                                <h5 class="widget-title">
                                                    <i class="ace-icon fa fa-asterisk"></i>
                                                    Subscription Information
                                                </h5>
                                            </div>
                                            <div class="widget-body">
                                                <div class="widget-main" style="min-height: 265px; margin: 0 auto;">
                                                    <table class="table table-bordered table-striped">
                                                            <thead class="thin-border-bottom">
                                                                    <tr>
                                                                        <th><small><i class="ace-icon fa fa-caret-right blue"></i>Plan Name</small></th>
                                                                            <th><small><i class="ace-icon fa fa-caret-right blue"></i>Start Date</small></th>
                                                                             <th><small><i class="ace-icon fa fa-caret-right blue"></i>Expiry Date</small></th>
                                                                            <th class="hidden-480"><small><i class="ace-icon fa fa-caret-right blue"></i>Status</small></th>
                                                                    </tr>
                                                            </thead>

                                                            <tbody>
                                                                    <?php  for($intCntr = 0; $intCntr < sizeof($arrSubscriptionInfo); $intCntr++) {
                                                                        $PlanName = $arrSubscriptionInfo[$intCntr]["plan_name"];
                                                                        $SubStartDate = $arrSubscriptionInfo[$intCntr]["subscription_start_date_gmt"];
                                                                        $SubEndDate = $arrSubscriptionInfo[$intCntr]["subscription_end_date_gmt"];
                                                                        $DiffDays = $arrSubscriptionInfo[$intCntr]["diff_days"];
                                                                         if ($DiffDays <= 0) 
                                                                         {
                                                                            $strColor = "red";
                                                                         }
                                                                         else if ($DiffDays <= 30) 
                                                                         {
                                                                            $strColor = "blue";
                                                                         }
                                                                         else
                                                                         {
                                                                             $strColor = "green";
                                                                         }
                                                                         $SubStatus = $arrSubscriptionInfo[$intCntr]["subscription_status"];
                                                                         switch($SubStatus)
                                                                        {
                                                                           case 0: $SubStatus = "<span class=\"label label-sm label-warning\">Requestd</span>";
                                                                              break;
                                                                           case 1: $SubStatus = "<span class=\"label label-sm label-info\">Trial</span>";
                                                                              break;
                                                                           case 2: $SubStatus = "<span class=\"label label-sm label-success\">Subscribed</span>";
                                                                              break;
                                                                           case 3: $SubStatus = "<span class=\"label label-sm label-danger\">Expired</span>";
                                                                              break;
                                                                           default: break;
                                                                        }
                                                                    ?>
                                                                    <tr>
                                                                            <td><small class="<?php echo $strColor; ?>"><?php echo $PlanName; ?></small></td>
                                                                            <td><small class="<?php echo $strColor; ?>"><?php echo $SubStartDate; ?></small></td>
                                                                            <td><small class="<?php echo $strColor; ?>"><?php echo $SubEndDate; ?></small></td>
                                                                            <td class="hidden-480"><small><?php echo $SubStatus; ?></small></td>
                                                                    </tr>
                                                                    <?php } ?>
                                                            </tbody>
                                                    </table>
                                                    <p><i><a href="<?php echo $CLIENT_SITE_ROOT."reports/subscription.php"?>">Click to see more...</a></i></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="vspace-12-sm"></div>
                                    
                                    <div class="col-sm-6">
                                        <div class="widget-box">
                                            <div class="widget-header widget-header-flat widget-header-small">
                                                <h5 class="widget-title">
                                                    <i class="ace-icon fa fa-signal"></i>
                                                    Meeting Statistics
                                                </h5>
                                            </div>
                                            <div class="widget-body">
                                                <div class="widget-main">
<!--                                                  <div id="month-wise-graph" style="min-width: 310px; height: 400px; margin: 0 auto"></div>       -->
                                                    <div id="container" style="min-width: 310px; height: 273px; margin: 0 auto;"></div>       
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
<!--                                    <div class="col-sm-6">
                                            <div id="recent-box" class="widget-box transparent">
                                                <div class="widget-header">
                                                    <h4 class="widget-title lighter smaller">
                                                            <i class="ace-icon fa fa-rss orange"></i>FREQUENT CONTACTS
                                                    </h4>
                                                </div>
                                                <div class="widget-body">
                                                    <div class="widget-main padding-4">
                                                        <div class="tab-content padding-8">
                                                                <div class="tab-pane active" id="member-tab">
                                                                        <div class="clearfix">
                                                                            <?php  for($intCntr = 0; $intCntr < sizeof($arrFrequentInvitees); $intCntr++) { 
                                                                                
                                                                                $strContactNickName = $arrFrequentInvitees[$intCntr]["contact_nick_name"];
                                                                                $strContactEmailID = $arrFrequentInvitees[$intCntr]["contact_email_address"];
                                                                                ?>
                                                                                <div class="itemdiv memberdiv">
                                                                                        <div class="user"><img src="<?php echo IMG_PATH; ?>avatar2.png" alt="<?php echo $strContactEmailID; ?>" title="<?php echo $strContactEmailID; ?>"></div>
                                                                                        <div class="body">
                                                                                                <div class="name blue"><?php echo $strContactNickName; ?></div>
                                                                                                <div class="time"><i class="ace-icon fa fa-clock-o"></i> <span class="green">20 min</span></div>
                                                                                        </div>
                                                                                </div>

                                                                                <div class="itemdiv memberdiv">
                                                                                    <div class="user"><img src="../assets/avatars/avatar2.png" alt="Joe Doe's avatar"></div>
                                                                                    <div class="body">
                                                                                            <div class="name"><a href="#">Joe Doe</a></div>
                                                                                            <div class="time"><i class="ace-icon fa fa-clock-o"></i> <span class="green">10 min</span></div>						
                                                                                    </div>
                                                                                </div>

                                                                                <div class="itemdiv memberdiv">
                                                                                    <div class="user"><img src="../assets/avatars/avatar2.png" alt="Jim Doe's avatar"></div>
                                                                                    <div class="body">
                                                                                            <div class="name"><a href="#">Jim Doe</a></div>
                                                                                            <div class="time"><i class="ace-icon fa fa-clock-o"></i> <span class="green">10 min</span></div>						
                                                                                    </div>
                                                                                </div>
                                                                            <?php } ?>
                                                                        </div>

                                                                        <div class="space-4"></div>

                                                                        <div class="center">
                                                                                <i class="ace-icon fa fa-users fa-2x green middle"></i>&nbsp;<a class="btn btn-sm btn-white btn-info" href="<?php echo $SITE_ROOT.'contacts/' ?>">See all contacts &nbsp;<i class="ace-icon fa fa-arrow-right"></i></a>
                                                                        </div>
                                                                        <div class="hr hr-double hr8"></div>
                                                                </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                    </div>-->
                                    
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
        <!-- JAVA SCRIPT -->
       
        <script type="text/javascript" src="<?php echo CLIENT_JS_PATH; ?>highcharts.js"></script>
        <script type="text/javascript" src="<?php echo CLIENT_JS_PATH; ?>exporting.js"></script>
               

        <script type="text/javascript">
            jQuery(function ($) {
                $('#container').highcharts({
                    chart: {
                        type: 'column'
                    },
                    title: {
                        text: 'Monthly Average Meeting'
                    },
            //        subtitle: {
            //            text: 'Source: WorldClimate.com'
            //        },
                    xAxis: {
                        categories: [<?php echo $arrMonthArr; ?>],
                        crosshair: true
                    },
                    yAxis: {
                        min: 0,
                        title: {
                            text: 'Total (Meetings)'
                        }
                    },
                    tooltip: {
                        headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                        pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                            '<td style="padding:0"><b>{point.y:.0f}</b></td></tr>',
                        footerFormat: '</table>',
                        shared: true,
                        useHTML: true
                    },
                    plotOptions: {
                        column: {
                            pointPadding: 0.2,
                            borderWidth: 0
                        }
                    },
                    series: [{
                        name: 'Month',
                        data: [<?php echo $arrTotalMeetingArr; ?>],
                        color: "#2091cf"
                    }]
                });
//                $('.easy-pie-chart.percentage').each(function(){
//                        var $box = $(this).closest('.infobox');
//                        var barColor = $(this).data('color') || (!$box.hasClass('infobox-dark') ? $box.css('color') : 'rgba(255,255,255,0.95)');
//                        var trackColor = barColor == 'rgba(255,255,255,0.95)' ? 'rgba(255,255,255,0.25)' : '#E2E2E2';
//                        var size = parseInt($(this).data('size')) || 50;
//                        $(this).easyPieChart({
//                                barColor: barColor,
//                                trackColor: trackColor,
//                                scaleColor: false,
//                                lineCap: 'butt',
//                                lineWidth: parseInt(size/10),
//                                animate: /msie\s*(8|7|6)/.test(navigator.userAgent.toLowerCase()) ? false : 1000,
//                                size: size
//                        });
//                })
                                
                //flot chart resize plugin, somehow manipulates default browser resize event to optimize it!
                //but sometimes it brings up errors with normal resize event handlers
                $.resize.throttleWindow = false;

                var placeholder = $('#piechart-placeholder').css({'width': '90%', 'min-height': '150px'});
                var data = <?php echo json_encode($arrMeetingOverview); ?>;
                
                function drawPieChart(placeholder, data, position) {
                    $.plot(placeholder, data, {
                        series: {
                            pie: {
                                show: true,
                                tilt: 0.8,
                                highlight: {
                                    opacity: 0.25
                                },
                                stroke: {
                                    color: '#fff',
                                    width: 2
                                },
                                startAngle: 2
                            }
                        },
                        legend: {
                            show: true,
                            position: position || "ne",
                            labelBoxBorderColor: null,
                            margin: [-30, 15]
                        }
                        ,
                        grid: {
                            hoverable: true,
                            clickable: true
                        }
                    })
                }
                
                drawPieChart(placeholder, data);

                /**
                 we saved the drawing function and the data to redraw with different position later when switching to RTL mode dynamically
                 so that's not needed actually.
                 */
                placeholder.data('chart', data);
                placeholder.data('draw', drawPieChart);

                //pie chart tooltip example
                var $tooltip = $("<div class='tooltip top in'><div class='tooltip-inner'></div></div>").hide().appendTo('body');
                var previousPoint = null;

                placeholder.on('plothover', function (event, pos, item) {
                    if (item) {
                        if (previousPoint != item.seriesIndex) {
                            previousPoint = item.seriesIndex;
                            var tip = item.series['label'] + " : " + item.series['percent'] + '%';
                            $tooltip.show().children(0).text(tip);
                        }
                        $tooltip.css({top: pos.pageY + 10, left: pos.pageX + 10});
                    } else {
                        $tooltip.hide();
                        previousPoint = null;
                    }
                });
            });
        </script>

    </body>
</html>
