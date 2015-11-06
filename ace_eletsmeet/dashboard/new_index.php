<?php
require_once('../includes/global.inc.php');
require_once(CLASSES_PATH . 'error.inc.php');
require_once(DBS_PATH . 'DataHelper.php');
require_once(DBS_PATH . 'objDataHelper.php');
require_once(INCLUDES_PATH . 'cm_authfunc.inc.php');
$CONST_MODULE = 'dashboard';
$CONST_PAGEID = 'Dashboard Home';
require_once(INCLUDES_PATH . 'cm_authorize.inc.php');
require_once(INCLUDES_PATH . 'dashboard.inc.php');

try
{
    $arrPersonalContactCount = getPersonalContactCountByID($strCK_user_id, $objDataHelper);
}
catch (Exception $e)
{
    throw new Exception("index.php : getPersonalContactCountByID Failed : " . $e->getMessage(), 1125);
}

$strTotalPersonalContacts = $arrPersonalContactCount[0]['TotalContacts'];

try
{
    $arrHostMeetingCount = getTotalHostMeetingCountByID($strCK_user_id, $objDataHelper);
}
catch (Exception $e)
{
    throw new Exception("index.php : getTotalHostMeetingCountByID Failed : " . $e->getMessage(), 1125);
}

$strTotalHostMeetingCount = $arrHostMeetingCount[0]['TotalMeetingCreated'];

try
{
    $arrMeetingDuration = getTotalMeetingDurationByID($strCK_user_id, $objDataHelper);
}
catch (Exception $e)
{
    throw new Exception("index.php : getTotalHostMeetingCountByID Failed : " . $e->getMessage(), 1125);
}

$strTotalMeetingDuration = $arrMeetingDuration[0]['TotalDuration'];
if (strlen($strTotalMeetingDuration) <= 0) 
{
    $strTotalMeetingDuration = 0;
}

try
{
    $arrInviteMeetingCount = getTotalInviteMeetingCountByID($strCk_user_email_address, $objDataHelper);
}
catch (Exception $e)
{
    throw new Exception("index.php : getTotalHostMeetingCountByID Failed : " . $e->getMessage(), 1125);
}

$strTotalMeetingInviteCount = $arrInviteMeetingCount[0]['TotalMeetingInvite'];


try
{
    $arrDistinctInviteeCount = getTotalDistinctInviteeCountByID($strCK_user_id, $objDataHelper);
}
catch (Exception $e)
{
    throw new Exception("index.php : getTotalDistinctInviteeCountByID Failed : " . $e->getMessage(), 1125);
}
$strTotalDistinctInviteeCount = $arrDistinctInviteeCount[0]['DistinctInvitee'];

try
{
    $arrProfileCompletePercent = getProfileCompletePercentByID($strCK_user_id, $objDataHelper);
}
catch (Exception $e)
{
    throw new Exception("index.php : getProfileCompletePercentByID Failed : " . $e->getMessage(), 1125);
}
$strTotalProfileCompletePercent = $arrProfileCompletePercent[0]['ProfilePercentage'];


$noOfInvitees = 9;

try
{
    $arrFrequentInvitees = getFrequentInvitees($strCK_user_id, $noOfInvitees, $objDataHelper);
}
catch (Exception $e)
{
    throw new Exception("index.php : getFrequentInvitees Failed : " . $e->getMessage(), 1125);
}

try
{
    $arrMeetingOverview = getMeetingOverviewByID($strCk_user_email_address, $objDataHelper);
}
catch (Exception $e)
{
    throw new Exception("index.php : getTotalHostMeetingCountByID Failed : " . $e->getMessage(), 1125);
}

try
{
    $arrMinuteBaseMeetingGraph = getMinuteBaseMeetingGraphByID($strCK_user_id, $objDataHelper);
}
catch (Exception $e)
{
    throw new Exception("index.php : getMinuteBaseMeetingGraphByID Failed : " . $e->getMessage(), 1125);
}

//TotalMinute //DateOfMeeting

for ($i = 0; $i < sizeof($arrMinuteBaseMeetingGraph); $i++)
{
    $arrDateArr .= "'".$arrMinuteBaseMeetingGraph[$i]['DateOfMeeting']."',";
}
$arrDateArr = substr($arrDateArr, 0, -1);

//print_r($arrDateArr);
        
for ($i = 0; $i < sizeof($arrMinuteBaseMeetingGraph); $i++)
{
    $arrMinuteArr .= $arrMinuteBaseMeetingGraph[$i]['TotalMinute'].",";
}
$arrMinuteArr = substr($arrMinuteArr, 0, -1);

 //print_r($arrMinuteArr);

//611 Total meeting in the month start//
	$arrMeetingCurrentMonth = getTotalMeetingCurrentMonth($strCK_user_id , $objDataHelper);
//611 Total meeting in the month end//

 
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <!-- HEAD CONTENT AREA -->
        <?php include (INCLUDES_PATH . 'head.php'); ?>
        <!-- HEAD CONTENT AREA -->

        <!-- CSS n JS CONTENT AREA -->
        <?php include (INCLUDES_PATH . 'css_include.php'); ?>    
        <link rel="stylesheet" href="../assets/css/fullcalendar.css" />
        
    </head>

    <body class="no-skin">

        <!-- TOP NAVIGATION BAR START -->
        <div id="navbar" class="navbar navbar-default">
            <?php include (INCLUDES_PATH . 'top_navigation.php'); ?>    
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
                <?php include (INCLUDES_PATH . 'sidebar_navigation.php'); ?>    
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
                                Dashboard<small><i class="ace-icon fa fa-angle-double-right"></i>&nbsp;overview &amp; stats</small>
                            </h1>
                        </div>
                        <!-- PAGE HEADER -->

                        <div class="row">
                            <div class="col-xs-12">
                                <!-- PAGE CONTENT START -->

                                <div class="center">
                                    <div class="row" >
                                            <div class="col-xs-6" style="border: 1px solid black;">
                                                    <div>
                                                            <!--span>.col-xs-6  611</span-->
                                                    </div>
                                            </div>

                                            <div class="col-xs-6" style="border: 1px solid red;">
                                                    <div>
                                                            <span>.col-xs-6</span>
                                                    </div>
                                                
                                                    <div id="calendar" class="fc fc-ltr fc-unthemed">
                                                        <div class="fc-toolbar">
                                                            <div class="fc-left">
                                                                <div class="fc-button-group">
                                                                    <button class="fc-prev-button fc-button fc-state-default fc-corner-left" type="button">
                                                                        <span class="fc-icon fc-icon-left-single-arrow"></span>
                                                                    </button>
                                                                    <button class="fc-next-button fc-button fc-state-default fc-corner-right" type="button">
                                                                        <span class="fc-icon fc-icon-right-single-arrow"></span>
                                                                    </button>
                                                                </div>
                                                                <button class="fc-today-button fc-button fc-state-default fc-corner-left fc-corner-right fc-state-disabled" type="button" disabled="disabled">today</button>
                                                            </div>
                                                            <div class="fc-right">
                                                                <div class="fc-button-group">
                                                                    <button class="fc-month-button fc-button fc-state-default fc-corner-left fc-state-active" type="button">month</button>
                                                                    <button class="fc-agendaWeek-button fc-button fc-state-default" type="button">week</button>
                                                                    <button class="fc-agendaDay-button fc-button fc-state-default fc-corner-right" type="button">day</button>
                                                                </div>
                                                            </div>
                                                            <div class="fc-center"><h2>November 2015</h2></div>
                                                            <div class="fc-clear"></div>
                                                        </div>
                                                        <div class="fc-view-container" style="">
                                                            <div class="fc-view fc-month-view fc-basic-view" style="">
                                                                <table>
                                                                    <thead>
                                                                        <tr>
                                                                            <td class="fc-widget-header">
                                                                                <div class="fc-row fc-widget-header">
                                                                                    <table>
                                                                                        <thead>
                                                                                            <tr>
                                                                                                <th class="fc-day-header fc-widget-header fc-sun">Sun</th>
                                                                                                <th class="fc-day-header fc-widget-header fc-mon">Mon</th>
                                                                                                <th class="fc-day-header fc-widget-header fc-tue">Tue</th>
                                                                                                <th class="fc-day-header fc-widget-header fc-wed">Wed</th>
                                                                                                <th class="fc-day-header fc-widget-header fc-thu">Thu</th>
                                                                                                <th class="fc-day-header fc-widget-header fc-fri">Fri</th>
                                                                                                <th class="fc-day-header fc-widget-header fc-sat">Sat</th>
                                                                                            </tr>
                                                                                        </thead>
                                                                                    </table>
                                                                                </div>
                                                                            </td>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <tr>
                                                                            <td class="fc-widget-content">
                                                                                <div class="fc-day-grid-container" style="">
                                                                                    <div class="fc-day-grid">
                                                                                        <div class="fc-row fc-week fc-widget-content" style="height: 116px;">
                                                                                            <div class="fc-bg">
                                                                                                <table>
                                                                                                    <tbody>
                                                                                                        <tr>
                                                                                                            <td data-date="2015-11-01" class="fc-day fc-widget-content fc-sun fc-past"></td>
                                                                                                            <td data-date="2015-11-02" class="fc-day fc-widget-content fc-mon fc-past"></td>
                                                                                                            <td data-date="2015-11-03" class="fc-day fc-widget-content fc-tue fc-past"></td>
                                                                                                            <td data-date="2015-11-04" class="fc-day fc-widget-content fc-wed fc-past"></td>
                                                                                                            <td data-date="2015-11-05" class="fc-day fc-widget-content fc-thu fc-past"></td>
                                                                                                            <td data-date="2015-11-06" class="fc-day fc-widget-content fc-fri fc-today fc-state-highlight"></td>
                                                                                                            <td data-date="2015-11-07" class="fc-day fc-widget-content fc-sat fc-future"></td>
                                                                                                        </tr>
                                                                                                    </tbody>
                                                                                                </table>
                                                                                            </div>
                                                                                            <div class="fc-content-skeleton">
                                                                                                <table>
                                                                                                    <thead>
                                                                                                        <tr>
                                                                                                            <td data-date="2015-11-01" class="fc-day-number fc-sun fc-past">1</td>
                                                                                                            <td data-date="2015-11-02" class="fc-day-number fc-mon fc-past">2</td>
                                                                                                            <td data-date="2015-11-03" class="fc-day-number fc-tue fc-past">3</td>
                                                                                                            <td data-date="2015-11-04" class="fc-day-number fc-wed fc-past">4</td>
                                                                                                            <td data-date="2015-11-05" class="fc-day-number fc-thu fc-past">5</td>
                                                                                                            <td data-date="2015-11-06" class="fc-day-number fc-fri fc-today fc-state-highlight">6</td>
                                                                                                            <td data-date="2015-11-07" class="fc-day-number fc-sat fc-future">7</td>
                                                                                                        </tr>
                                                                                                    </thead>
                                                                                                    <tbody>
                                                                                                        <tr>
                                                                                                            <td ></td>
                                                                                                            <td></td>
                                                                                                            <td></td>
                                                                                                            <td></td>
                                                                                                            <td></td>
                                                                                                            <td></td>
                                                                                                            <td></td>
                                                                                                        </tr>
                                                                                                    </tbody>
                                                                                                </table>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="fc-row fc-week fc-widget-content" style="height: 116px;">
                                                                                            <div class="fc-bg">
                                                                                                <table>
                                                                                                    <tbody>
                                                                                                        <tr>
                                                                                                            <td data-date="2015-11-08" class="fc-day fc-widget-content fc-sun fc-future"></td>
                                                                                                            <td data-date="2015-11-09" class="fc-day fc-widget-content fc-mon fc-future"></td>
                                                                                                            <td data-date="2015-11-10" class="fc-day fc-widget-content fc-tue fc-future"></td>
                                                                                                            <td data-date="2015-11-11" class="fc-day fc-widget-content fc-wed fc-future"></td>
                                                                                                            <td data-date="2015-11-12" class="fc-day fc-widget-content fc-thu fc-future"></td>
                                                                                                            <td data-date="2015-11-13" class="fc-day fc-widget-content fc-fri fc-future"></td>
                                                                                                            <td data-date="2015-11-14" class="fc-day fc-widget-content fc-sat fc-future"></td>
                                                                                                        </tr>
                                                                                                    </tbody>
                                                                                                </table>
                                                                                            </div>
                                                                                            <div class="fc-content-skeleton">
                                                                                                <table>
                                                                                                    <thead>
                                                                                                        <tr>
                                                                                                            <td data-date="2015-11-08" class="fc-day-number fc-sun fc-future">8</td>
                                                                                                            <td data-date="2015-11-09" class="fc-day-number fc-mon fc-future">9</td>
                                                                                                            <td data-date="2015-11-10" class="fc-day-number fc-tue fc-future">10</td>
                                                                                                            <td data-date="2015-11-11" class="fc-day-number fc-wed fc-future">11</td>
                                                                                                            <td data-date="2015-11-12" class="fc-day-number fc-thu fc-future">12</td>
                                                                                                            <td data-date="2015-11-13" class="fc-day-number fc-fri fc-future">13</td>
                                                                                                            <td data-date="2015-11-14" class="fc-day-number fc-sat fc-future">14</td>
                                                                                                        </tr>
                                                                                                    </thead>
                                                                                                    <tbody>
                                                                                                        <tr>
                                                                                                            <td></td>
                                                                                                            <td></td>
                                                                                                            <td></td>
                                                                                                            <td></td>
                                                                                                            <td></td>
                                                                                                            <td></td>
                                                                                                            <td></td>
                                                                                                        </tr>
                                                                                                    </tbody>
                                                                                                </table>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="fc-row fc-week fc-widget-content" style="height: 116px;">
                                                                                            <div class="fc-bg">
                                                                                                <table>
                                                                                                    <tbody>
                                                                                                        <tr>
                                                                                                            <td data-date="2015-11-15" class="fc-day fc-widget-content fc-sun fc-future"></td>
                                                                                                            <td data-date="2015-11-16" class="fc-day fc-widget-content fc-mon fc-future"></td>
                                                                                                            <td data-date="2015-11-17" class="fc-day fc-widget-content fc-tue fc-future"></td>
                                                                                                            <td data-date="2015-11-18" class="fc-day fc-widget-content fc-wed fc-future"></td>
                                                                                                            <td data-date="2015-11-19" class="fc-day fc-widget-content fc-thu fc-future"></td>
                                                                                                            <td data-date="2015-11-20" class="fc-day fc-widget-content fc-fri fc-future"></td>
                                                                                                            <td data-date="2015-11-21" class="fc-day fc-widget-content fc-sat fc-future"></td>
                                                                                                        </tr>
                                                                                                    </tbody>
                                                                                                </table>
                                                                                            </div>
                                                                                            <div class="fc-content-skeleton">
                                                                                                <table>
                                                                                                    <thead>
                                                                                                        <tr>
                                                                                                            <td data-date="2015-11-15" class="fc-day-number fc-sun fc-future">15</td>
                                                                                                            <td data-date="2015-11-16" class="fc-day-number fc-mon fc-future">16</td>
                                                                                                            <td data-date="2015-11-17" class="fc-day-number fc-tue fc-future">17</td>
                                                                                                            <td data-date="2015-11-18" class="fc-day-number fc-wed fc-future">18</td>
                                                                                                            <td data-date="2015-11-19" class="fc-day-number fc-thu fc-future">19</td>
                                                                                                            <td data-date="2015-11-20" class="fc-day-number fc-fri fc-future">20</td>
                                                                                                            <td data-date="2015-11-21" class="fc-day-number fc-sat fc-future">21</td>
                                                                                                        </tr>
                                                                                                    </thead>
                                                                                                    <tbody>
                                                                                                        <tr>
                                                                                                            <td></td>
                                                                                                            <td></td>
                                                                                                            <td></td>
                                                                                                            <td></td>
                                                                                                            <td></td>
                                                                                                            <td></td>
                                                                                                            <td></td>
                                                                                                        </tr>
                                                                                                    </tbody>
                                                                                                </table>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="fc-row fc-week fc-widget-content" style="height: 116px;">
                                                                                            <div class="fc-bg">
                                                                                                <table>
                                                                                                    <tbody>
                                                                                                        <tr>
                                                                                                            <td data-date="2015-11-22" class="fc-day fc-widget-content fc-sun fc-future"></td>
                                                                                                            <td data-date="2015-11-23" class="fc-day fc-widget-content fc-mon fc-future"></td>
                                                                                                            <td data-date="2015-11-24" class="fc-day fc-widget-content fc-tue fc-future"></td>
                                                                                                            <td data-date="2015-11-25" class="fc-day fc-widget-content fc-wed fc-future"></td>
                                                                                                            <td data-date="2015-11-26" class="fc-day fc-widget-content fc-thu fc-future"></td>
                                                                                                            <td data-date="2015-11-27" class="fc-day fc-widget-content fc-fri fc-future"></td>
                                                                                                            <td data-date="2015-11-28" class="fc-day fc-widget-content fc-sat fc-future"></td>
                                                                                                        </tr>
                                                                                                    </tbody>
                                                                                                </table>
                                                                                            </div>
                                                                                            <div class="fc-content-skeleton">
                                                                                                <table>
                                                                                                    <thead>
                                                                                                        <tr>
                                                                                                            <td data-date="2015-11-22" class="fc-day-number fc-sun fc-future">22</td>
                                                                                                            <td data-date="2015-11-23" class="fc-day-number fc-mon fc-future">23</td>
                                                                                                            <td data-date="2015-11-24" class="fc-day-number fc-tue fc-future">24</td>
                                                                                                            <td data-date="2015-11-25" class="fc-day-number fc-wed fc-future">25</td>
                                                                                                            <td data-date="2015-11-26" class="fc-day-number fc-thu fc-future">26</td>
                                                                                                            <td data-date="2015-11-27" class="fc-day-number fc-fri fc-future">27</td>
                                                                                                            <td data-date="2015-11-28" class="fc-day-number fc-sat fc-future">28</td>
                                                                                                        </tr>
                                                                                                    </thead>
                                                                                                    <tbody>
                                                                                                        <tr>
                                                                                                            <td></td>
                                                                                                            <td></td>
                                                                                                            <td></td>
                                                                                                            <td></td>
                                                                                                            <td></td>
                                                                                                            <td></td>
                                                                                                            <td></td>
                                                                                                        </tr>
                                                                                                    </tbody>
                                                                                                </table>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="fc-row fc-week fc-widget-content" style="height: 116px;">
                                                                                            <div class="fc-bg">
                                                                                                <table>
                                                                                                    <tbody>
                                                                                                        <tr>
                                                                                                            <td data-date="2015-11-29" class="fc-day fc-widget-content fc-sun fc-future"></td>
                                                                                                            <td data-date="2015-11-30" class="fc-day fc-widget-content fc-mon fc-future"></td>
                                                                                                            <td data-date="2015-12-01" class="fc-day fc-widget-content fc-tue fc-other-month fc-future"></td>
                                                                                                            <td data-date="2015-12-02" class="fc-day fc-widget-content fc-wed fc-other-month fc-future"></td>
                                                                                                            <td data-date="2015-12-03" class="fc-day fc-widget-content fc-thu fc-other-month fc-future"></td>
                                                                                                            <td data-date="2015-12-04" class="fc-day fc-widget-content fc-fri fc-other-month fc-future"></td>
                                                                                                            <td data-date="2015-12-05" class="fc-day fc-widget-content fc-sat fc-other-month fc-future"></td>
                                                                                                        </tr>
                                                                                                    </tbody>
                                                                                                </table>
                                                                                            </div>
                                                                                            <div class="fc-content-skeleton">
                                                                                                <table>
                                                                                                    <thead>
                                                                                                        <tr>
                                                                                                            <td data-date="2015-11-29" class="fc-day-number fc-sun fc-future">29</td>
                                                                                                            <td data-date="2015-11-30" class="fc-day-number fc-mon fc-future">30</td>
                                                                                                            <td data-date="2015-12-01" class="fc-day-number fc-tue fc-other-month fc-future">1</td>
                                                                                                            <td data-date="2015-12-02" class="fc-day-number fc-wed fc-other-month fc-future">2</td>
                                                                                                            <td data-date="2015-12-03" class="fc-day-number fc-thu fc-other-month fc-future">3</td>
                                                                                                            <td data-date="2015-12-04" class="fc-day-number fc-fri fc-other-month fc-future">4</td>
                                                                                                            <td data-date="2015-12-05" class="fc-day-number fc-sat fc-other-month fc-future">5</td>
                                                                                                        </tr>
                                                                                                    </thead>
                                                                                                    <tbody>
                                                                                                        <tr>
                                                                                                            <td></td>
                                                                                                            <td></td>
                                                                                                            <td></td>
                                                                                                            <td></td>
                                                                                                            <td></td>
                                                                                                            <td></td>
                                                                                                            <td></td>
                                                                                                        </tr>
                                                                                                    </tbody>
                                                                                                </table>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="fc-row fc-week fc-widget-content" style="height: 119px;">
                                                                                            <div class="fc-bg">
                                                                                                <table>
                                                                                                    <tbody>
                                                                                                        <tr>
                                                                                                            <td data-date="2015-12-06" class="fc-day fc-widget-content fc-sun fc-other-month fc-future"></td>
                                                                                                            <td data-date="2015-12-07" class="fc-day fc-widget-content fc-mon fc-other-month fc-future"></td>
                                                                                                            <td data-date="2015-12-08" class="fc-day fc-widget-content fc-tue fc-other-month fc-future"></td>
                                                                                                            <td data-date="2015-12-09" class="fc-day fc-widget-content fc-wed fc-other-month fc-future"></td>
                                                                                                            <td data-date="2015-12-10" class="fc-day fc-widget-content fc-thu fc-other-month fc-future"></td>
                                                                                                            <td data-date="2015-12-11" class="fc-day fc-widget-content fc-fri fc-other-month fc-future"></td>
                                                                                                            <td data-date="2015-12-12" class="fc-day fc-widget-content fc-sat fc-other-month fc-future"></td>
                                                                                                        </tr>
                                                                                                    </tbody>
                                                                                                </table>
                                                                                            </div>
                                                                                            <div class="fc-content-skeleton">
                                                                                                <table>
                                                                                                    <thead>
                                                                                                        <tr>
                                                                                                            <td data-date="2015-12-06" class="fc-day-number fc-sun fc-other-month fc-future">6</td>
                                                                                                            <td data-date="2015-12-07" class="fc-day-number fc-mon fc-other-month fc-future">7</td>
                                                                                                            <td data-date="2015-12-08" class="fc-day-number fc-tue fc-other-month fc-future">8</td>
                                                                                                            <td data-date="2015-12-09" class="fc-day-number fc-wed fc-other-month fc-future">9</td>
                                                                                                            <td data-date="2015-12-10" class="fc-day-number fc-thu fc-other-month fc-future">10</td>
                                                                                                            <td data-date="2015-12-11" class="fc-day-number fc-fri fc-other-month fc-future">11</td>
                                                                                                            <td data-date="2015-12-12" class="fc-day-number fc-sat fc-other-month fc-future">12</td>
                                                                                                        </tr>
                                                                                                    </thead>
                                                                                                    <tbody>
                                                                                                        <tr>
                                                                                                            <td></td>
                                                                                                            <td></td>
                                                                                                            <td></td>
                                                                                                            <td></td>
                                                                                                            <td></td>
                                                                                                            <td></td>
                                                                                                            <td></td>
                                                                                                        </tr>
                                                                                                    </tbody>
                                                                                                </table>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                
                                                
                                            </div>
                                    </div>
                                </div>
                                
<!--                                <div class="row">

                                    <div class="col-sm-6 infobox-container">
                                        
                                        <div class="infobox infobox-green">
                                            <div class="infobox-icon">
                                                <i class="ace-icon fa fa-phone"></i>
                                            </div>
                                            <div class="infobox-data">
                                                <span class="infobox-data-number"><?php echo $strTotalPersonalContacts; ?></span>
                                                <div class="infobox-content">Total No. of Contacts</div>
                                            </div>
                                        </div>

                                        <div class="infobox infobox-blue">
                                            <div class="infobox-icon">
                                                <i class="ace-icon fa fa-users"></i>
                                            </div>
                                            <div class="infobox-data">
                                                <span class="infobox-data-number"><?php echo $strTotalHostMeetingCount; ?></span>
                                                <div class="infobox-content small">Total Meeting Hosted</div>
                                            </div>
                                        </div>

                                        <div class="infobox infobox-red">
                                            <div class="infobox-icon">
                                                <i class="ace-icon fa fa-exchange"></i>
                                            </div>
                                            <div class="infobox-data">
                                                <span class="infobox-data-number"><?php echo $strTotalMeetingInviteCount; ?></span>
                                                <div class="infobox-content">Total Meeting Joined</div>
                                            </div>
                                        </div>

                                        <div class="infobox infobox-pink">
                                            <div class="infobox-icon">
                                                <i class="ace-icon fa fa-comments-o"></i>
                                            </div>
                                            <div class="infobox-data">
                                                <span class="infobox-data-number"><?php echo $strTotalMeetingDuration; ?></span>
                                                <div class="infobox-content small">Total Meeting Minutes</div>
                                            </div>
                                        </div>

                                        <div class="infobox infobox-orange">
                                            <div class="infobox-icon">
                                                <i class="ace-icon fa fa- fa-envelope-o"></i>
                                            </div>
                                            <div class="infobox-data">
                                                <span class="infobox-data-number"><?php echo $strTotalDistinctInviteeCount; ?></span>
                                                <div class="infobox-content small">Total Distinct Invitee</div>
                                            </div>
                                        </div>
                                        
                                        <div class="infobox infobox-blue3">
                                            <div class="infobox-icon">
                                                <i class="ace-icon fa fa- fa-user"></i>
                                            </div>
                                            <div class="infobox-data">
                                                <span class="infobox-data-number"><?php echo $strTotalProfileCompletePercent; ?> &percnt;</span>
                                                <div class="infobox-content small">Profile  Complete</div>
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
                                                    <div id="piechart-placeholder"></div>                                                
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>-->

<!--                                <div class="hr hr32 hr-dotted"></div>-->
                                
<!--                                <div class="row">
                                    
                                    <div class="col-sm-6">
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
                                    </div>
                                    
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
                                                  <div id="container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>               
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                </div>-->

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
                <?php include (INCLUDES_PATH . 'footer.php'); ?>  
            </div>
            <!-- FOOTER END -->

            <a href="#" id="btn-scroll-up" class="btn-scroll-up btn btn-sm btn-inverse">
                <i class="ace-icon fa fa-angle-double-up icon-only bigger-110"></i>
            </a>

        </div>
        <!-- MAIN CONTAINER END -->

        <!-- JAVA SCRIPT -->
        <?php include (INCLUDES_PATH . 'static_js_includes.php'); ?>  
        <?php include (INCLUDES_PATH . 'other_js_includes.php'); ?>  
        <!-- JAVA SCRIPT -->

        <script type="text/javascript" src="<?php echo JS_PATH; ?>highcharts.js"></script>
        <script type="text/javascript" src="<?php echo JS_PATH; ?>exporting.js"></script>

        <script type="text/javascript">
            jQuery(function ($) {
//                $('.easy-pie-chart.percentage').each(function () {
//                    var $box = $(this).closest('.infobox');
//                    var barColor = $(this).data('color') || (!$box.hasClass('infobox-dark') ? $box.css('color') : 'rgba(255,255,255,0.95)');
//                    var trackColor = barColor == 'rgba(255,255,255,0.95)' ? 'rgba(255,255,255,0.25)' : '#E2E2E2';
//                    var size = parseInt($(this).data('size')) || 50;
//                    $(this).easyPieChart({
//                        barColor: barColor,
//                        trackColor: trackColor,
//                        scaleColor: false,
//                        lineCap: 'butt',
//                        lineWidth: parseInt(size / 10),
//                        animate: /msie\s*(8|7|6)/.test(navigator.userAgent.toLowerCase()) ? false : 1000,
//                        size: size
//                    });
//                })

//                $('.sparkline').each(function () {
//                    var $box = $(this).closest('.infobox');
//                    var barColor = !$box.hasClass('infobox-dark') ? $box.css('color') : '#FFF';
//                    $(this).sparkline('html',
//                            {
//                                tagValuesAttribute: 'data-values',
//                                type: 'bar',
//                                barColor: barColor,
//                                chartRangeMin: $(this).data('min') || 0
//                            });
//                });


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
            //categories: ['2015-07-09','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov', 'Dec'],
            categories: [<?php echo $arrDateArr; ?>],
            crosshair: true
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Duration (minute)'
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
            name: 'Minute',
            //data: [49.9, 71.5, 106.4, 129.2, 144.0, 176.0, 135.6, 148.5, 216.4, 194.1, 95.6, 54.4],
            data: [<?php echo $arrMinuteArr; ?>],
            color: "#68BC31"
        }]
    });

                
                
            });



        </script>

    </body>
        <script type="text/javascript">
function __createEle(tag, cls, id, name)
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
function calenderEvent()
{

	 var eventData = <?php echo json_encode($arrMeetingCurrentMonth);?>;
	var eventData = [{"schedule_id":"563c6bc200f3d","meeting_title":"demo","schedule_status":"0","meeting_date":"2015-11-09","meeting_time":"07:11"},{"schedule_id":"563c70f445146","meeting_title":"calendar demo","schedule_status":"0","meeting_date":"2015-11-10","meeting_time":"07:11"},{"schedule_id":"561caf2b3225a","meeting_title":"Chat history testing","schedule_status":"0","meeting_date":"2015-11-13","meeting_time":"07:11"}]  ;
	for(var a=0;a < eventData.length;a++)
	{

		var ele  = document.getElementsByClassName("fc-bg");
		for(var i=0;i<ele.length;i++)
		{
			var  tdCnt = 0,trEle = ele[i].childNodes[1].tBodies[0].childNodes[1];

			for(var j=0;j< trEle.childNodes.length ;j++)
			{
				if(trEle.childNodes[j].tagName == "TD")
				{
					tdCnt= tdCnt + 1;
					if(trEle.childNodes[j].attributes["data-date"].value == eventData[a].meeting_date)
					{
						var tblTags = ele[i].parentNode.childNodes[3].childNodes[1];
						var t = createTag(tblTags.tBodies[0].rows[0].cells[tdCnt-1] , eventData[a]);


					}
				}
			}

		}
	} 
}
function createTag(ele,obj)
{
	ele.setAttribute("class", "fc-event-container");
	var eleA = __createEle("a");
	eleA.setAttribute("class","fc-day-grid-event fc-event fc-start fc-end label-info");
	eleA.innerHTML = "<div class='fc-content'><span class='fc-time'>"+obj.meeting_time+" </span><span class='fc-title'>"+obj.meeting_title+"</span></div>";
	ele.appendChild(eleA);
	return true;
}
	window.onload = function()
	{
		calenderEvent();
	};
	

	</script>
</html>
