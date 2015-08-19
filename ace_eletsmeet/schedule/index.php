<?php
require_once('../includes/global.inc.php');
require_once(CLASSES_PATH . 'error.inc.php');
require_once(DBS_PATH . 'DataHelper.php');
require_once(DBS_PATH . 'objDataHelper.php');
require_once(INCLUDES_PATH . 'cm_authfunc.inc.php');
$CONST_MODULE = 'schedule';
$CONST_PAGEID = 'Schedule Page';
require_once(INCLUDES_PATH . 'cm_authorize.inc.php');
require_once(INCLUDES_PATH . 'common_function.inc.php');
require_once(INCLUDES_PATH . 'schedule_function.inc.php');

$stat = "FALSE";

/* * * Check for Subsription * * */
try
{
    $arrSchPlan = isPlanExists($strCK_user_id, GM_DATE, $objDataHelper);
}
catch (Exception $e)
{
    throw new Exception("index.php : isSchedulePlanExists Failed : " . $e->getMessage(), 1125);
}
//print_r($arrSchPlan);

$gDtm = strtotime(date("Y-m-d", strtotime(GM_DATE)));
$eDtm = strtotime($arrSchPlan[0]["eGMT"]);

if (( $eDtm != " ") && ($eDtm >= $gDtm))
{
    $stat = "TRUE";
}
else
{
    $stat = "FALSE";
}

if ($stat == "TRUE")
{
    /* For Scheduled Plan Details */
    try
    {
        //$sPlanDetails = scheduledPlans($strCK_user_id, $objDataHelper);
        $arrSchPlanDetails = scheduledPlans($strCK_user_id, GM_DATE, $objDataHelper);
    }
    catch (Exception $e)
    {
        throw new Exception("index.php : scheduledPlans Failed : " . $e->getMessage(), 1125);
    }
    //print_r($arrSchPlanDetails);

    $arrDate = array();
    $uDt = date("d-m-Y", strtotime($arrSchPlanDetails[0]['subscription_end_date_gmt']));

    $userPlan = $arrSchPlanDetails[0]['subscription_id'] . "$:$" . $uDt . "$:$" . $arrSchPlanDetails[0]['number_of_invitee'] . "$:$" . $arrSchPlanDetails[0]['plan_type'] . "$:$" . $arrSchPlanDetails[0]['plan_name'] . "$:$" . $arrSchPlanDetails[0]['concurrent_sessions'];

    $uPlan = $arrSchPlanDetails[0]['plan_name'] . "&nbsp;:&nbsp;Expires on " . $uDt . "";

    $plansCount = sizeof($arrSchPlanDetails);

    if ($plansCount > 1)
    {
        $activePlans .= "<select name='sPlan' id='sPlan' class='col-xs-12 col-sm-12'><option value=''>-- Select Plan --</option>";
        $i = 0;
        foreach ($arrSchPlanDetails as $pKey => $pKeyArray)
        {
            $uPlnDt = date("d-m-Y", strtotime($pKeyArray['subscription_end_date_gmt']));
            $arrDate[$i] = strtotime($uPlnDt);
            $plnVal = $pKeyArray['subscription_id'] . "$:$" . $uPlnDt . "$:$" . $pKeyArray['number_of_invitee'] . "$:$" . $pKeyArray['plan_type'] . "$:$" . $pKeyArray['plan_name'] . "$:$" . $pKeyArray['concurrent_sessions'];
            $activePlans .= "<option value='" . $plnVal . "'>" . $pKeyArray['plan_name'] . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Expires on " . $uPlnDt . "</option>";
            $i++;
        }
        $activePlans .= "</select>";
        $dt = '';
    }
    else
    {
        $arrDate[0] = strtotime($uDt);
        $dt = round(abs(strtotime(date("d-m-Y", strtotime(GM_DATE))) - strtotime($arrSchPlanDetails[0]['subscription_end_date_gmt'])) / 86400);
    }
    $maxDate = date("d-m-Y", max($arrDate));
    $curDate = date("d-m-Y");
    
    
    /*    For listing the contact list as per group classification */
    try
    {
        $cGroup = getCombineGroupList($strCK_user_id, $strCk_user_client_id, $objDataHelper);
    }
    catch (Exception $e)
    {
        throw new Exception("index.php : getGroupList Failed : ".$e->getMessage(), 1125);
    }

    try
    {
        $cList = getCombineContactList($strCK_user_id, $strCk_user_client_id, $objDataHelper);
    }
    catch (Exception $e)
    {
        throw new Exception("index.php : getContactList Failed : ".$e->getMessage(), 1126);
    }

            
    foreach ($cGroup as $cKey => $cValue)
    {
        //$contacts .= "<div style='float:left;width:90%;font-weight:bold;line-height:30px'>".$cValue['group_name']."</div>";
        //$contacts .= "<thead class='thin-border-bottom'><tr><th colspan='3'>".$cValue['group_name']."</th></tr></thead>";
        $contacts .= "<h5 class='smaller lighter green'><i class='ace-icon fa fa-users col-sm-1'></i>".$cValue['group_name']."</h5>";
        foreach ($cList as $cKey => $cKeyArray)
        {
            if ($cKeyArray['contact_group_name'] == $cValue['group_name'])
            {
                if ($cKeyArray['contact_email_address'] !== $strCk_user_email_address)
                {
                    //$contacts .= "<div style='float:left;width:90%'>".$cKeyArray['nick_name']."</div><div style='float:left;'><input type='checkbox' name='uData' value='".$cKeyArray['contact_email_address'].":".$cKeyArray['nick_name'].":".$cKeyArray['contact_idd_code'].":".$cKeyArray['contact_mobile_number']."' id='".$cKeyArray['contact_group_name'].":".$cKeyArray['contact_email_address']."' onclick=javascript:cCounter('".urlencode($cKeyArray['contact_group_name'])."','".$cKeyArray['contact_email_address']."','con')></div>";
                    //$contacts .="<tr><td><i class='ace-icon fa fa-user'></i></td><td> ".$cKeyArray['nick_name']."</td><td>".$cKeyArray['contact_email_address']."</td><td><input type='checkbox' id='id-disable-check' class=''></td></tr>";
                    $contacts .="<div class='input-group' style='padding-bottom: 1px;'><span class='input-group-addon'><i class='ace-icon fa fa-user'></i></span><label class='form-control'><small>".$cKeyArray['nick_name']."</small></label><span class='input-group-addon'><input type='checkbox' name='uData' class='ace' value='".$cKeyArray['contact_email_address'].":".$cKeyArray['nick_name'].":".$cKeyArray['contact_idd_code'].":".$cKeyArray['contact_mobile_number']."' id='".$cKeyArray['contact_group_name'].":".$cKeyArray['contact_email_address']."' onclick=javascript:cCounter('".urlencode($cKeyArray['contact_group_name'])."','".$cKeyArray['contact_email_address']."','con') /><span class='lbl'></span></span></div>";
                } 
            }
        }
    }
    

    /*  For Timezone  */
    try
    {
        $arrTimezoneList = getTimezoneList($objDataHelper);
    }
    catch (Exception $e)
    {
        throw new Exception("index.php : getTimezoneList Failed : " . $e->getMessage(), 1128);
    }

    try
    {
        $arrUserDetailsById = getUserDetailsByID($strCK_user_id, $objDataHelper);
    }
    catch (Exception $e)
    {
        throw new Exception("index.php : getUserDetailsByID Failed : " . $e->getMessage(), 1129);
    }

    $userTz = $arrUserDetailsById[0]['country_name'] . "$:$" . $arrUserDetailsById[0]['timezones'] . "$:$" . $arrUserDetailsById[0]['gmt'];
    $uTimezone = $arrUserDetailsById[0]['country_name'] . " - " . $arrUserDetailsById[0]['timezones'] . ", GMT" . $arrUserDetailsById[0]['gmt'];

    $timezone .= "<select name='timezone' id='timezone' class='col-xs-12 col-sm-12' onChange='currentZone()'><option value=''>-- Select Timezone --</option>";
    foreach ($arrTimezoneList as $tKey => $tKeyArray)
    {
        $tzVal = $tKeyArray['country_name'] . "$:$" . $tKeyArray['timezones'] . "$:$" . $tKeyArray['gmt'];
        if ($userTz == $tzVal)
            $timezone .= "<option value='" . $tzVal . "' selected='selected'>" . $tKeyArray['country_name'] . " - " . $tKeyArray['timezones'] . " " . $tKeyArray['gmt'] . "</option>";
        else
            $timezone .= "<option value='" . $tzVal . "'>" . $tKeyArray['country_name'] . " - " . $tKeyArray['timezones'] . " " . $tKeyArray['gmt'] . "</option>";
    }
    $timezone .= "</select>";
}
else
{
    try
    {
        $expPlan = isPlanExpired($strCK_user_id, GM_DATE, $objDataHelper);
    }
    catch (Exception $e)
    {
        throw new Exception("index.php : isPlanExpired Failed : " . $e->getMessage(), 1125);
    }

    $expPlanDtm = strtotime(trim($expPlan[0]["expGMT"]));

    if ($expPlanDtm != "")
    {
        $errorMsg = "Your subscribed plan has expired. Please contact support to subscribe.";
    }
    else
    {
        $errorMsg = "You are not subscribed to any plan. Please contact support to subscribe.";
    }
}
//echo $gDtm;
//echo "<hr>";
//echo $eDtm;
//echo "<hr>";
//echo $stat;
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <!-- HEAD CONTENT AREA -->
        <?php include (INCLUDES_PATH . 'head.php'); ?>
        <!-- HEAD CONTENT AREA -->

        <!-- CSS n JS CONTENT AREA -->
        <?php include (INCLUDES_PATH . 'css_include.php'); ?>
        <!-- CSS n JS CONTENT AREA -->
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
                                Schedule<small><i class="ace-icon fa fa-angle-double-right"></i>&nbsp;meeting &amp; appointment</small>
                            </h1>
                        </div>
                        <!-- PAGE HEADER -->

                        <div class="row">
                            <div class="col-xs-12" id="mainContentDiv">
                                <!-- PAGE CONTENT START -->


                                <?php if ($stat == "TRUE"){ ?>

<!--                                    <div class="alert alert-block alert-success">
                                        <strong >Welcome</strong>, Your subscribed plan has expired. Please contact support to subscribe.
                                    </div>-->

                                    <div class="alert alert-block alert-danger errorDisplay"  id="error-msg-sch"></div>

                                    <div role="form" class="form-horizontal">

                                        <div class="row">
                                            <div class="col-xs-12">
                                                <div class="well well-sm">
                                                    <div class="form-group required" id="schPlan">
                                                            <?php if ($plansCount > 1){ ?>
                                                                <label for="form-field-1" class="col-sm-2 control-label no-padding-right">  My Plan </label>
                                                                <div class="col-sm-5">
                                                                    <?php echo $activePlans; ?>
                                                                </div>
                                                            <?php }else{ ?>
                                                                <label for="form-field-1" class="col-sm-2 control-label no-padding-right">  My Plan </label>
                                                                <div class="col-sm-5">
                                                                    <input type="text" id="sPlan" class="col-xs-12 col-sm-12" readonly="true" contenteditable="false" value="<?php echo $uPlan; ?>" name="<?php echo $userPlan; ?>">
                                                                </div>
                                                            <?php } ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-xs-12">
                                                <div class="widget-box">
                                                    <div class="widget-header"><h4 class="widget-title">1. Call a Meeting</h4></div>
                                                    <div class="widget-body">
                                                        <div class="widget-main">

                                                            <div class="form-group required">
                                                                <label for="form-field-1-1" class="col-sm-2 control-label no-padding-right"> Meeting Title </label>
                                                                <div class="col-sm-9">
                                                                    <input placeholder="Type your Meeting Title" id="sTitle" class="form-control" name="sTitle" type="text">
                                                                </div>
                                                            </div>

                                                            <div class="space-4"></div>

                                                            <div class="form-group">
                                                                <label for="form-field-1-1" class="col-sm-2 control-label no-padding-right"> Meeting Agenda </label>
                                                                <div class="col-sm-9">
                                                                    <textarea placeholder="Type your Meeting Agenda" id="sAgenda" class="form-control limited" name="sAgenda" maxlength="100"></textarea>
                                                                </div>
                                                            </div>                                                    

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-xs-12">
                                                <div class="widget-box">
                                                    <div class="widget-header"><h4 class="widget-title">2. Set Time</h4></div>
                                                    <div class="widget-body">
                                                        <div class="widget-main">
                                                            
                                                            <div class="well well-sm">
                                                                    <span class="help-inline col-sm-7 no-padding-left">
                                                                        <label class="middle">
                                                                            <span id="timezoneTitle"> Your Timezone : </span> <span id='curZone'><?php echo $uTimezone; ?></span>
                                                                        </label>
                                                                    </span>
                                                                    <span class="help-inline">
                                                                        <label class="middle">
                                                                            <span class="lbl"><a  class="editable-click" id="uTz" onclick="showTimezone();" title="Change Timezone for this Meeting" alt="Change Timezone for this Meeting">Change Timezone</a> </span>
                                                                        </label>
                                                                    </span>
                                                                    <div class="space-8"></div>
                                                                    <?php if (count($arrTimezoneList) > 0){ ?>
                                                                    <div id="timezoneShow" class="form-group timezone">
                                                                        <label for="form-field-1-1" class="col-sm-2 control-label no-padding-right"> Select Timezone </label>
                                                                        <div class="col-sm-5">
                                                                            <?php echo $timezone; ?>
                                                                        </div>
                                                                    </div>
                                                                    <?php }else{ ?>
                                                                    <div class="alert alert-block alert-warning">
                                                                        <strong >Sorry</strong>, Timezone List Unavailable.
                                                                    </div>
                                                                    <?php } ?>                                                                                                    
                                                            </div>

                                                            <div class="space-4"></div>

                                                            <div class="well well-sm">
                                                                <div class="form-group">
                                                                    <div class="col-sm-12">
                                                                        <label class="col-sm-2 no-padding-right">
                                                                            <input type="radio" name="schedule-time" id="current-time" class="ace" value="now">
                                                                            <span class="lbl"> Now </span>
                                                                        </label>

                                                                        <label class="col-sm-2 no-padding-right">
                                                                            <input type="radio" name="schedule-time" id="later-time" class="ace" value="later">
                                                                            <span class="lbl"> Later </span>
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                <div class="space-8"></div>
                                                                <div class="form-group" id="schdatetime" style="display: none;">
                                                                    <div class="col-sm-4 no-padding-right">
                                                                        <label class="col-sm-3 no-padding-right">Date</label>
                                                                        <div class="input-group col-sm-7">
                                                                            <input type="text" name="datetime" data-date-format="dd-mm-yyyy" id="sch_date" class="form-control date-picker" readonly="true" contenteditable="false">
                                                                            <span class="input-group-addon">
                                                                                <i class="fa fa-calendar bigger-110"></i>
                                                                            </span>                                                                           
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-sm-4 no-padding-right">
                                                                        <label class="col-sm-3 no-padding-right">Time</label>
                                                                        <div class="input-group col-sm-7">
                                                                            <input id="sch_time" type="text" class="form-control" readonly="true" contenteditable="false"/>
                                                                            <span class="input-group-addon">
                                                                                <i class="fa fa-clock-o bigger-110"></i>
                                                                            </span>                                                                           
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-xs-12">
                                                
                                                <div class="widget-box">
                                                    <div class="widget-header"><h4 class="widget-title">3. Select Invitee</h4></div>
                                                    <div class="widget-body">
                                                        <div class="widget-main">
                                                            
                                                            <div class="row">
                                                                
                                                                <div class="col-xs-12 col-sm-4">
                                                                    <div class="widget-box widget-color-blue "  style="height: 366px;">
                                                                            <div class="widget-header">
                                                                                    <h4 class="widget-title lighter"> Contact List </h4>
                                                                            </div>
                                                                            <div class="widget-body">
                                                                                    <div class="widget-main no-padding">
                                                                                        <div class="input-group" style="padding-bottom: 10px;">
                                                                                                <span class="input-group-addon">
                                                                                                        <i class="ace-icon fa fa-search blue"></i>
                                                                                                </span>
                                                                                                <input type="text" name="aSearch" id="aSearch" onkeyup="javascript:autoSuggest(this);" class="form-control" placeholder="Type here to Search Contact List">
                                                                                        </div>
                                                                                        
                                                                                        
                                                                                        <form name="cDataForm">
                                                                                            <div id="contactList" class="" style="height: 281px; overflow-y:scroll;">
                                                                                               <?php if (strlen($contacts) > 0) { ?> 
                                                                                                       <?php echo $contacts; ?>
                                                                                               <?php }else{ ?>
                                                                                                   <div class="alert alert-block alert-info">
                                                                                                      <strong >Sorry</strong>, No Contact List Available.
                                                                                                    </div>
                                                                                               <?php } ?>
                                                                                            </div>
                                                                                        </form>
                                                                                        
                                                                                    </div>
                                                                            </div>
                                                                    </div>
                                                                </div>
                                                                
                                                                <div class="col-xs-12 col-sm-4">
                                                                    <div class="widget-box widget-color-orange" style="min-height: 366px;">
                                                                            <div class="widget-header">
                                                                                    <h4 class="widget-title">Add Other Invitee</h4>		
                                                                            </div>
                                                                            <div class="widget-body">
                                                                                    <div class="widget-main">
                                                                                            <div class="alert alert-block alert-danger errorDisplay"  id="error-msg-aoi"></div>
                                                                                            <div>
                                                                                                    <label for="form-field-8">Name </label>
                                                                                                    <input id="iNick" class="form-control" type="text" placeholder="Name" name="txtName" maxlength="50"/>
                                                                                            </div>
                                                                                            <hr>
                                                                                            <div>
                                                                                                    <label for="form-field-8">Email Address </label>
                                                                                                    <input id="iEmail" class="form-control" type="text" placeholder="Email Address" name="txtEmail" maxlength="100"/>
                                                                                            </div>
                                                                                            <div class="form-actions center">
                                                                                                    <button class="btn btn-sm btn-yellow" type="button" onclick="addOtherInvitee();">
                                                                                                            Add Invitee
                                                                                                    </button>
                                                                                            </div>
                                                                                    </div>
                                                                            </div>
                                                                    </div>
                                                                </div>
                                                                
                                                                <div class="col-xs-12 col-sm-4">
                                                                    <div class="widget-box widget-color-green" style="height: 366px;">
                                                                            <div class="widget-header">
                                                                                    <h4 class="widget-title lighter"> Invitee List </h4>
                                                                            </div>
                                                                            <div class="widget-body">
                                                                                <div class="widget-main">
                                                                                        <div id="inviteesList" class="" style="height: 281px; overflow-y:scroll;"></div>
                                                                                    </div>
                                                                            </div>
                                                                    </div>
                                                                </div>        
                                                                
                                                            </div>
                                                            
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                            </div>
                                         </div>
                                        
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <div class="widget-box">
                                                    <div class="widget-header"><h4 class="widget-title">4. Select Meeting Moderator (Optional)</h4></div>
                                                    <div class="widget-body">
                                                        <div class="widget-main">
                                                            <div class="form-group">
                                                                    <div class="col-sm-3">
                                                                             <select class="form-control" id ="moderator">
                                                                                  <option>-- Select Moderator -- </option>
                                                                            </select>
                                                                    </div>
                                                            </div>                                                   
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <div class="well">
                                                    <div class="infobox-green width-35">
                                                        <label><h1>Total Invitee : <span id="inviteesCount">0</span></h1></label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <div class="clearfix form-actions">
                                                    <div class="col-md-offset-4 col-md-12">
                                                            <button type="button" class="btn btn-info" onclick="btnSchedule('N');">
                                                                    <i class="ace-icon fa fa-check bigger-110"></i>
                                                                    Schedule
                                                            </button>
                                                            &nbsp; &nbsp; &nbsp;
                                                            <button type="reset" class="btn">
                                                                    <i class="ace-icon fa fa-undo bigger-110"></i>
                                                                    Reset
                                                            </button>
                                                    </div>
                                                  </div>
                                            </div>
                                        </div>                                        

                                    </div>
                                <?php }else{ ?>
                                    <div class="alert alert-block alert-danger">
                                        <strong >Sorry</strong>, Your subscribed plan has expired. Please contact support to subscribe.
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
        <script type="text/javascript" src="<?php echo JS_PATH; ?>bootstrap-datepicker.js"></script>
        <script type="text/javascript" src="<?php echo JS_PATH; ?>bootstrap-timepicker.js"></script>
        <script type="text/javascript" src="<?php echo JS_PATH; ?>schedule_custom.js"></script>
        <script type="text/javascript" src="<?php echo JS_PATH; ?>jquery.autosize.js"></script>
        <script type="text/javascript" src="<?php echo JS_PATH; ?>jquery.inputlimiter.1.3.1.js"></script>

    </body>
    <script type="text/javascript">
        $('[data-rel=tooltip]').tooltip({container:'body'});
        $('[data-rel=popover]').popover({container:'body'});

        $('textarea[class*=autosize]').autosize({append: "\n"});
        $('textarea.limited').inputlimiter({
                remText: '%n character%s remaining...',
                limitText: 'max allowed : %n.'
        });
                                
        $(document).ready(function () {
            $('input[type="radio"]').click(function () {
                if ($(this).attr("value") == "later")
                {
                    $('#schdatetime').css({"display": "block"});
                }
                else
                {
                    $('#schdatetime').css({"display": "none"});
                }
            });
        });

        //datepicker
        $('.date-picker').datepicker({
            autoclose: true,
            todayHighlight: true,
            endDate: '<?php echo $maxDate; ?>',
            startDate: '<?php echo $curDate; ?>'
        })

        //show datepicker when clicking on the icon
//    .next().on(ace.click_event, function(){
//            $(this).prev().focus();
//    });


        $('#sch_time').timepicker({
            minuteStep: 1,
            showSeconds: true,
            showMeridian: false,
            language: 'en',
            pick12HourFormat: false,
            showInputs: false,
            defaultTime:false
        })

//    .next().on(ace.click_event, function(){
//            $(this).prev().focus();
//    });

        // URL Encode - Decode
        function urldecode (str) {
            return decodeURIComponent((str+'').replace(/\+/g,'%20'));
        }

        function urlencode (str) {
            str = (str + '').toString();
            return encodeURIComponent(str).replace(/!/g, '%21').replace(/'/g, '%27').replace(/\(/g, '%28').replace(/\)/g, '%29').replace(/\*/g, '%2A').replace(/%20/g, '&nbsp;');
        }
         
         //Auto Suggest
            function autoSuggest(a) {
                var aWord = $.trim(a.value);
                if (aWord.length > 0) {
                    clID = "<?php echo $strCk_user_client_id; ?>";
                    email = "<?php echo $strCk_user_email_address ?>";
                    var arrLen = 0;
                    $.ajax ({
                        type: "GET",
                        url: "autoSuggest.php",
                        cache: false,
                        data: "email="+email+"&clID="+clID+"&aLetter="+aWord,
                        loading: $(".loading").html(""),
                        success: function(html) {
                            if(html.length > 0) {
                                $("#contactList").html(html);
                                if(document.cDataForm.uData[0]) {
                                    arrLen = document.cDataForm.uData.length;
                                } else {
                                    arrLen = 1;
                                }
                                if (arrLen == 1) {
                                    for (var a=0; a < cInvitees.length; a++) {
                                        if (document.cDataForm.uData.value == cInvitees[a]) {
                                            document.cDataForm.uData.checked = true;
                                        }
                                    }
                                } else {
                                    for (var i=0; i < (arrLen); i++) {
                                        for (var b=0; b < cInvitees.length; b++) {
                                            if (document.cDataForm.uData[i].value == cInvitees[b]) {
                                                document.cDataForm.uData[i].checked = true;
                                            }
                                        }
                                    }
                                }
                            } else {
                                //$("#contactList").html("<div class='alert alert-info mR10'>No Contact List Available</div>");
                                $("#contactList").html("<div class='alert alert-block alert-info'><strong >Sorry</strong>, No Contact List Available.</div>");
                            }
                        }
                    });
                } 
                else 
                {
                    cList = "<?php echo $contacts; ?>";
                    $("#contactList").html(cList);
                    for (var i=0; i < (document.cDataForm.uData.length); i++) {
                        for (var a=0; a < cInvitees.length; a++) {
                            if (document.cDataForm.uData[i].value == cInvitees[a]) {
                                document.cDataForm.uData[i].checked = true;
                            }
                        }
                    }
                }
            }
         
        // Set Counters
        var z = 0;
        var tCount = 0;
        var cInvitees = new Array();

        function cCounter(invitee,iEmail,eStat) {
            invitee = urldecode(invitee);
            inviteeID = invitee+":"+iEmail;
            inviteeStat = eval("document.getElementById('"+inviteeID+"').checked");
            inviteeVal = eval("document.getElementById('"+inviteeID+"').value");
            if (inviteeStat && eStat == "con") 
            {
                cAdd = iEmail; cAddIndex = cInvitees.indexOf(inviteeVal);  if(cAddIndex < 0) { cInvitees.push(inviteeVal); } addInviteeDiv(invitee, iEmail, inviteeVal, "con");
            } 
            else if (!(inviteeStat) && eStat == "con") 
            {
                eval("document.getElementById('"+inviteeID+"').checked = false"); 
                removeInvitee(invitee, iEmail, inviteeVal, "rem");
            } else if (eStat == "rem") 
            {
                eval("document.getElementById('"+inviteeID+"').checked = false");
            }
            iCounter();
        }

        function iCounter() {
            var outerDiv = document.getElementById('inviteesList');
            var oCount = outerDiv.getElementsByTagName('div').length;
            document.getElementById('inviteesCount').innerHTML = oCount;
        }

        function removeInvitee(e, eMail, eVal, et) {
            eVals = eVal.split(":");
            eVal = eVals[0]+":"+escape(eVals[1])+":"+eVals[2]+":"+eVals[3];
            var child = document.getElementById(eMail);
            var par = document.getElementById('inviteesList');
            if(par)
            par.removeChild(child);
            moderator(eMail, escape(eVals[1]), "rM");
            iCounter();
            cDel = eMail;
            cDelIndex = cInvitees.indexOf(eVal);
            cInvitees.splice(cDelIndex,1);
            if (et == "con") {
                cCounter(e,eMail,"rem");
            }
        }

        function addInviteeDiv(id, email, val, eType) {
            vals = val.split(":");
            val = vals[0]+":"+escape(vals[1])+":"+vals[2]+":"+vals[3];
            oContact = document.getElementById('inviteesList');
            //oContact.innerHTML = oContact.innerHTML + "<div type=text id="+email+" class=oInvitees><input type=text name="+val+" value="+urlencode(vals[1])+"  readonly=readonly class=inputInviteesList><img src=<?php echo IMG_PATH; ?>closered.png alt=Delete id="+email+" onclick=removeInvitee('"+escape(id)+"','"+email+"','"+val+"','"+eType+"');  class=cPointer title=Remove Invitee /></div>";
            oContact.innerHTML = oContact.innerHTML + "<div class='input-group' style='padding-bottom: 1px;' id="+email+" ><span class='input-group-addon'><i class='ace-icon fa fa-user'></i></span><input type=text name="+val+" value="+urlencode(vals[1])+"  readonly='readonly' class='form-control' style='font-size: small; background-color: #ffffff none repeat scroll 0 0 !important;'/><span class='input-group-addon'><img src='<?php echo IMG_PATH; ?>closered.png' id="+email+" onclick=removeInvitee('"+escape(id)+"','"+email+"','"+val+"','"+eType+"');  class='cPointer' title='Remove "+urlencode(vals[1])+"' alt='Remove "+urlencode(vals[1])+"'/><span class='lbl'></span></span></div>";                                                                                           
            moderator(email, escape(vals[1]), "aM");
            iCounter();
        }
        
         function moderator(eMail, nName, mType) {
                uModerator = document.getElementById('moderator');
                if (mType == "aM") 
                {
	  var newOp = document.createElement("option");
                    newOp.text = unescape(nName);
                    newOp.value = eMail;
                    uModerator.options.add(newOp);
                }
                else if (mType == "rM") 
                {
                    for(i=0;i<(uModerator.length);i++)
                    {
                        if (uModerator.options[i].value == eMail)
                        {
                            uModerator.remove(i);
                        }
                    }
                } 
                else if (mType == "dM") 
                {
                    for(i=0;i<(uModerator.length);i++) {
                        uModerator.remove(i);
                    }
                    uModerator.innerHTML = "<option>--Select Moderator--</option>";
                }
            }
            
            // Add Other Invitee
            function addOtherInvitee () {
                var iNick = $("#iNick").val();
                var iEmail = $("#iEmail").val();
                if($.trim(iNick).length == 0) {
                    $("#error-msg-aoi").html("Please enter Invitee Nick Name");
                    $("#error-msg-aoi").css({"display":"block"});
                    return false;
                } else if($.trim(iEmail).length == 0) {
                    $("#error-msg-aoi").html("Please enter Invitee Email Address");
                    $("#error-msg-aoi").css({"display":"block"});
                    return false;
                } else if (!(/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(iEmail))) {
                    $("#error-msg-aoi").html("Please enter a valid Invitee Email Address");
                    $("#error-msg-aoi").css({"display":"block"});
                    return false;
                }
                val = iEmail+":"+iNick+"::";
                eType = "rem";
                id = iEmail;
                addInviteeDiv(id, iEmail, val, eType);
                document.getElementById("iNick").value = "";
                document.getElementById("iEmail").value = "";
                document.getElementById("iNick").placeholder = "Nick Name";
                document.getElementById("iEmail").placeholder = "Email Address";
                $("#error-msg-aoi").html("");
                $("#error-msg-aoi").css({"display":"none"});
            }
            
             // Validate Schedule Meeting Details
            function btnSchedule(stat) {
                $("#error-msg-sch").html("");
                $("#error-msg-sch").css({"display":"none"});
                
                var title = $("#sTitle").val();
                var agenda = $("#sAgenda").val();
                var tzone = $("#timezone").val();
                var mod = $("#moderator").val();

                pCount = "<?php echo $plansCount; ?>";
                if (pCount > 1)
                {
                    uplan = $("#sPlan").val();
                }else{
                    uplan = $("#sPlan").attr("name");
                }
                userPlan = uplan.split("$:$");
                if (uplan.length == 0) 
                {
                    $("#error-msg-sch").html("Please Select Plan");
                    $("#error-msg-sch").css({"display":"block"});
                    var textbox = document.getElementById("sPlan");
                    textbox.focus();
                    //textbox.scrollIntoView(alignToTop);
                    textbox.scrollIntoView(true);

    
                    //$('#schPlan').addClass('has-error');
                    //$('#schPlan').removeClass('has-error');
                }
                else if($.trim(title).length == 0) 
                {
                    $("#error-msg-sch").html("Please type Meeting Title");
                    $("#error-msg-sch").css({"display":"block"});
                    var textbox = document.getElementById("sTitle");
                    textbox.focus();
                    textbox.scrollIntoView(true);
                }
                //else if ($('#current-time:checked').val() != 'on' && $('#later-time:checked').val() != 'on') 
                else if ($('#current-time:checked').val() != 'now' && $('#later-time:checked').val() != 'later') 
                {
                    $("#error-msg-sch").html("Please set Meeting Time");
                    $("#error-msg-sch").css({"display":"block"});
                    var textbox = document.getElementById("current-time");
                    textbox.focus();
                    textbox.scrollIntoView(true);
               }
//               else if ($('#later-time:checked').val() == 'later') 
//               {
//                   var schedule_date = document.getElementById('sch_date').value;
//                   var schedule_time = document.getElementById('sch_time').value;
//                     if (schedule_date == "") 
//                     {
//                          $("#error-msg-sch").html("Please select Date");
//                          $("#error-msg-sch").css({"display":"block"});
//                     }
//                      else if (schedule_time == "") 
//                     {
//                          $("#error-msg-sch").html("Please select Time");
//                          $("#error-msg-sch").css({"display":"block"});
//                     }
//               }
                else if (document.getElementById('inviteesCount').innerHTML < 1) 
                {
                    $("#error-msg-sch").html("Please select Invitee from Contact List or Add Other Invitee");
                    $("#error-msg-sch").css({"display":"block"});
                     var textbox = document.getElementById("contactList");
                     textbox.focus();
                    textbox.scrollIntoView(true);
                }
                else 
                {
                     if ($('#later-time:checked').val() == 'later') 
                     {
                            var schType = "L";
                            var schDtm = $('#sch_date').val() + " " +  $('#sch_time').val();
                            //alert(schDtm);
                     }
                     else
                     {
                            schType = "N";
                            schDtm = "";
                     }
                    var title = $.trim(title);
                    var agenda = $.trim(agenda);
                    var inviteescount = document.getElementById('inviteesCount').innerHTML;
                    var contactData = new Array();
                    var contactEmail = new Array();
                    var paren = document.getElementById('inviteesList');
                    for (i=0;i<paren.childNodes.length;i++) 
                    {
                        contactEmail = paren.childNodes[i].id;
                        var firstparent = eval("document.getElementById('"+contactEmail+"')");
                        contactData.push(firstparent.childNodes[1].name);
                    }
                    inviteesList = contactData;
                    var maxLimit = userPlan[2];
                    if (parseInt(inviteescount) > parseInt(maxLimit) && parseInt(maxLimit) != 0) 
                    {
                        $("#error-msg-sch").html("Your max limit for Invitees List is "+maxLimit);
                        $("#error-msg-sch").css({"display":"block"});
                        return false;
                    }
                    $.ajax({
                        type: "GET",
                        url: "createSchedule.php",
                        cache: false,
                        data: "title="+title+"&schedule_dtm="+schDtm+"&inviteesList="+inviteesList+"&scheduleType="+schType+"&inviteesCnt="+inviteescount+"&tzone="+tzone+"&mod="+mod+"&uplan="+uplan+"&agenda="+agenda+"&stat="+stat,
                        loading: $(".loading").html(""),
                        success:    function(html) {
                            sep = "<?php echo SEPARATOR; ?>"; html = html.split(sep);
                            if(html[0] == 1) 
                            {
                                //clearData();
                                cInvitees = []; $("#mainContentDiv").html(html[1]);
                            }
                            else if( (html[0] == 0) || (html[0] == 2) )
                            {
                                $("#error-msg-sch").html(html[1]);
                                $("#error-msg-sch").css({"display":"block"});
                            }
                        }
                    });
                }
            }
            
            function f_click( t )
            {
              at = t.getAttributeNode("href");
              window.open( at.value );
              t.removeAttributeNode( at );
              document.getElementsByTagName("a")[0].setAttribute("class", "disabled");
            }
            
    </script>
</html>
