<?php
require_once('../includes/global.inc.php');
require_once(CLASSES_PATH."error.inc.php");
require_once(INCLUDES_PATH."Utilities.php");
require_once(DBS_PATH."DataHelper.php");
require_once(DBS_PATH."objDataHelper.php");
require_once(INCLUDES_PATH."db_common_function.inc.php");
require_once(INCLUDES_PATH."cm_authfunc.inc.php");
$CONST_MODULE = 'schedule';
$CONST_PAGEID = 'Schedule';
require_once(INCLUDES_PATH."cm_authorize.inc.php");
require_once(INCLUDES_PATH."sch_function.inc.php");

$stat = "FALSE";

/* * * Check for Subsription * * */
try
{
    $schPlan = isPlanExists($strCK_user_id, $objDataHelper);
}
catch (Exception $e)
{
    throw new Exception("index.php : isSchedulePlanExists Failed : ".$e->getMessage(), 1125);
}

$gDtm = strtotime(date("Y-m-d", strtotime(GM_DATE)));
$eDtm = strtotime($schPlan[0]["eGMT"]);

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
    /*     * * For Scheduled Plan Details * * */
    try
    {
        $sPlanDetails = scheduledPlans($strCK_user_id, $objDataHelper);
    }
    catch (Exception $e)
    {
        throw new Exception("index.php : scheduledPlans Failed : ".$e->getMessage(), 1125);
    }
    $arrDate = array();
    $uDt = date("d-m-Y", strtotime($sPlanDetails[0]['subscription_end_date_gmt']));
    $userPlan = $sPlanDetails[0]['subscription_id']."$:$".$uDt."$:$".$sPlanDetails[0]['number_of_invitee']."$:$".$sPlanDetails[0]['plan_type']."$:$".$sPlanDetails[0]['plan_name']."$:$".$sPlanDetails[0]['concurrent_sessions'];
    $uPlan = $sPlanDetails[0]['plan_name']."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Expires on ".$uDt."";
    $plansCount = sizeof($sPlanDetails);
    if ($plansCount > 1)
    {
        $activePlans .= "<select name='sPlan' id='sPlan' class='mT5'><option value=''>-- Select Plan --</option>";
        $i = 0;
        foreach ($sPlanDetails as $pKey => $pKeyArray)
        {

            $uPlnDt = date("d-m-Y", strtotime($pKeyArray['subscription_end_date_gmt']));
            $arrDate[$i] = strtotime($uPlnDt);
            $plnVal = $pKeyArray['subscription_id']."$:$".$uPlnDt."$:$".$pKeyArray['number_of_invitee']."$:$".$pKeyArray['plan_type']."$:$".$pKeyArray['plan_name']."$:$".$pKeyArray['concurrent_sessions'];
            $activePlans .= "<option value='".$plnVal."'>".$pKeyArray['plan_name']."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Expires on ".$uPlnDt."</option>";
            $i++;
        }
        $activePlans .= "</select>";
        $dt = '';
    }
    else
    {
        $arrDate[0] = strtotime($uDt);
        $dt = round(abs(strtotime(date("d-m-Y", strtotime(GM_DATE))) - strtotime($sPlanDetails[0]['subscription_end_date_gmt'])) / 86400);
    }
    $maxDate = date("d-m-Y", max($arrDate));
    /*     * * For listing the contact list as per group classification * * */
    try
    {
        $cGroup = getGroupList($strCK_user_id, $strCk_client_id, $objDataHelper);
    }
    catch (Exception $e)
    {
        throw new Exception("index.php : getGroupList Failed : ".$e->getMessage(), 1125);
    }

    try
    {
        $cList = getContactList($strCK_user_id, $strCk_client_id, $objDataHelper);
    }
    catch (Exception $e)
    {
        throw new Exception("index.php : getContactList Failed : ".$e->getMessage(), 1126);
    }

    foreach ($cGroup as $cKey => $cValue)
    {
        $contacts .= "<div style='float:left;width:90%;font-weight:bold;line-height:30px'>".$cValue['group_name']."</div>";
        foreach ($cList as $cKey => $cKeyArray)
        {
            if ($cKeyArray['contact_group_name'] == $cValue['group_name'])
            {
                if ($cKeyArray['contact_email_address'] !== $strCk_email_address)
                {
                    $contacts .= "<div style='float:left;width:90%'>".$cKeyArray['nick_name']."</div><div style='float:left;'><input type='checkbox' name='uData' value='".$cKeyArray['contact_email_address'].":".$cKeyArray['nick_name'].":".$cKeyArray['contact_idd_code'].":".$cKeyArray['contact_mobile_number']."' id='".$cKeyArray['contact_group_name'].":".$cKeyArray['contact_email_address']."' onclick=javascript:cCounter('".urlencode($cKeyArray['contact_group_name'])."','".$cKeyArray['contact_email_address']."','con')></div>";
                }
            }
        }
    }

    /*     * * For listing the scheduled meetings on the RHS * * */
    $graceInterval = MEETING_LIST_GRACE_INTERVAL;
    try
    {
        $meetingList = getMeetingListRHS($strCK_user_id, $graceInterval, $objDataHelper);
    }
    catch (Exception $e)
    {
        throw new Exception("index.php : getMeetingListRHS Failed : ".$e->getMessage(), 1127);
    }

    /*     * * For timezone ** */
    try
    {
        $timezoneList = getTimezoneList($objDataHelper);
    }
    catch (Exception $e)
    {
        throw new Exception("index.php : getTimezoneList Failed : ".$e->getMessage(), 1128);
    }

    try
    {
        $userTimezone = getUserTimezone($strCK_user_id, $strCk_email_address, $strCk_client_id, $objDataHelper);
    }
    catch (Exception $e)
    {
        throw new Exception("index.php : getUserTimezone Failed : ".$e->getMessage(), 1129);
    }

    $userTz = $userTimezone[0]['country_name']."$:$".$userTimezone[0]['timezones']."$:$".$userTimezone[0]['gmt'];
    $uTimezone = $userTimezone[0]['country_name']." - ".$userTimezone[0]['timezones'].", GMT".$userTimezone[0]['gmt'];
    $timezone .= "<select name='timezone' id='timezone' onChange='currentZone()'>";
    foreach ($timezoneList as $tKey => $tKeyArray)
    {
        $tzVal = $tKeyArray['country_name']."$:$".$tKeyArray['timezones']."$:$".$tKeyArray['gmt'];
        if ($userTz == $tzVal)
            $timezone .= "<option value='".$tzVal."' selected='selected'>".$tKeyArray['country_name']." - ".$tKeyArray['timezones']." ".$tKeyArray['gmt']."</option>";
        else
            $timezone .= "<option value='".$tzVal."'>".$tKeyArray['country_name']." - ".$tKeyArray['timezones']." ".$tKeyArray['gmt']."</option>";
    }
    $timezone .= "</select>";
}
else
{
    try
    {
        $expPlan = isPlanExpired($strCK_user_id, $objDataHelper);
    }
    catch (Exception $e)
    {
        throw new Exception("index.php : isPlanExpired Failed : ".$e->getMessage(), 1125);
    }

    $expPlanDtm = strtotime(trim($expPlan[0]["expGMT"]));
    
    if ( $expPlanDtm != "")
    {
       $errorMsg = "Your subscribed plan has expired. Please contact support to subscribe."; 
    }
    else
    {
       $errorMsg = "You are not subscribed to any plan. Please contact support to subscribe.";
    }
    
    /*if (is_array($schPlan) && (sizeof($schPlan) > 0))
    {
        if ($eDtm != "")
        {
            $errorMsg = "Your subscribed plan has expired. Please click <a href='".$SITE_ROOT."plans/'>here</a> to subscribe.";
        }
        else
        {
            $errorMsg = "You are not subscribed to any plan. Please contact administrator to subscribe.";
        }
    }*/
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang="en">

    <!-- Head content Area -->
    <head>
        <?php include (INCLUDES_PATH.'head.php'); ?>
        <script src="../js/jquery.js" type='text/javascript'></script>
        <script src="../js/ModalPopups.js" type="text/javascript"></script>
        <script>
            /*var maxPlanDate;*/
            function scheduleList() {
                $.ajax({
                    type: "GET",
                    url: "scheduleRHS.php",
                    cache: false,
                    data: "",
                    loading: $(".loading").html(""),
                    success:    function(html) {
                        $("#scheduleRHS").html(html);
                    }
                });
            }
            /*dateMaxLimit('S')*/
            /*function dateMaxLimit (ptype) {
              if(ptype == "S") {
                 maxDt = "<?php echo $dt; ?>";
              } else {
                 uplan = $("#sPlan").val();
                 pDetails = uplan.split("$:$");
                 pD = (pDetails[1].split("-")).reverse();
                 pDate = Date.parse(pD);
                 gDate = "<?php echo GM_DATE; ?>";
                 gDate = gDate.split(" ");
                 gmDate = Date.parse(gDate[0]);
                 dtm = Math.abs(gmDate-pDate);
                 maxDt = Math.ceil(dtm / (24 * 60 * 60 * 1000));
                 $("input:radio").removeAttr("checked");
                 $('#schdatetime').css({"display":"none"});
              }
              minDt = 0;
              maxPlanDate = maxDt;
              maxDt = 3;
              console.log(maxDt);
              $('#datetime').datepicker({"minDate":minDt , "maxDate":maxDt});
           }*/
        </script>
    </head>
    <!-- Head content Area -->

    <body onload="javascript:scheduleList();">

        <!-- Navigation Bar, After Login Menu &  Product Logo -->
        <?php include (INCLUDES_PATH.'navigation.php'); ?>
        <!-- Navigation Bar, After Login Menu &  Product Logo -->

        <!-- Main content Area -->
        <div class="container">

            <div class="row">
                
                <div class="span12">
                    <h2 id="sHeading">Schedule a New Meeting</h2>
                </div>
                                
                <?php
                if ($stat == "TRUE")
                { ?>
                    <div id="mainContentDiv" class="span8 brdrGy pR75">
                        <div id="error-msg" class="alert alert-error errorDisplay mT10"></div>
                        <hr>
                        <!-- Plan Details : Start -->
                        <div class="dGy mB10 fL" style="width:100%">
                            <?php if ($plansCount > 1)
                            { ?>
                                <div class="mB5">
                                    <div id="planShow" class="mT5 mL10">
                                        <label class="tBold fL mT10">Select Plan To Schedule a Meeting : </label>
                                        <span class="fL mL10"><?php echo $activePlans; ?></span>
                                    </div>
                                </div>
                                <?php
                            }
                            else
                            {
                                ?>
                                <div class="fL mR10 mL10 mT10">
                                    Your Plan :
                                </div>
                                <div class="fL mT5" id="curPlan">
                                    <input id='sPlan' name='<?php echo $userPlan; ?>' value='<?php echo $uPlan; ?>'  readonly="true" UNSELECTABLE="ON" contenteditable="false" style="width: 600px;"/>
                                </div>
                            <?php } ?>
                        </div>
                        <!-- Plan Details : End -->
                        <!-- Meeting Title : Start -->
                        <div class="lGy p10">
                            <label class="tBold"><span class="f18">1.</span> Call a Meeting</label>
                            <input placeholder="Type your Meeting Title here." id="sTitle" class="input span6 mB20" name="sTitle" type="text">
                        </div>
                        <!-- Meeting Title : End -->

                        <!-- Meeting Schedule : Start -->
                        <div class="dGy p10">
                            <label class="tBold"><span class="f18">2.</span> Set Time</label>
                            <label class="mT10"><span id="timezoneTitle">Your Timezone </span> : &nbsp;&nbsp;&nbsp;<span id='curZone'><?php echo $uTimezone; ?></span>&nbsp;&nbsp;&nbsp;&nbsp;<a id="uTz" class="cPointer" onclick="showTimezone();" title="Change Timezone for this Meeting">Change Timezone</a></label>
                            <div id="timezoneShow" class="timezone mT10">Select timezone : <?php
                        if (strlen($timezone) > 0)
                        {
                            echo $timezone;
                        }
                        else
                        {
                            echo "<div class='alert alert-info'>Timezone List Unavailable</div>";
                        }
                            ?>
                            </div>
                            <div class="mB5 h55 mT10">

                                <div class="pB5 mT5 w80 fleft">
                                    <label class="radio inline"><input type="radio" name="schedule-time" id="current-time"> Now </label>
                                </div>
                                <div class="cB"></div>
                                <div class="pB5 mT5 w80 fleft">
                                    <label class="radio inline pL30"><input type="radio" name="schedule-time" id="schedule-time"> Later </label>
                                </div>
                                <div id="schdatetime" class="w480 fleft" style="display: none;">
                                    <div class="fL mR15">
                                        <label>Meeting Date & Time (DD-MM-YYYY)</label>
                                        <div style="position:relative">
                                            <input type="text" placeholder="DD-MM-YYYY" class="span2" name="datetime" id="datetime" style="padding-right:26px" readonly="true" UNSELECTABLE="ON" contenteditable="false">
                                            <!--                                        <div class="calender" id="datetime"></div>-->
                                        </div>
                                    </div>
                                    <!-- Hours : Start -->
                                    <div class="fL ">
                                        <label>Hours</label>
                                        <select class="span1" id="hours" UNSELECTABLE="ON" contenteditable="false">
                                            <option selected="selected">HH</option>
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                            <option value="5">5</option>
                                            <option value="6">6</option>
                                            <option value="7">7</option>
                                            <option value="8">8</option>
                                            <option value="9">9</option>
                                            <option value="10">10</option>
                                            <option value="11">11</option>
                                            <option value="12">12</option>
                                        </select>
                                    </div>
                                    <!-- Hours : End -->
                                    <!-- Minutes : Start -->
                                    <div class="fL mR15">
                                        <label class="spaceML15">Minutes</label><strong>&nbsp;: </strong>
                                        <select class="span1" id ="minutes">
                                            <option selected="selected">MM</option>
                                            <option value="0">00</option>
                                            <option value="1">05</option>
                                            <option value="2">10</option>
                                            <option value="3">15</option>
                                            <option value="4">20</option>
                                            <option value="5">25</option>
                                            <option value="6">30</option>
                                            <option value="7">35</option>
                                            <option value="8">40</option>
                                            <option value="9">45</option>
                                            <option value="10">50</option>
                                            <option value="11">55</option>
                                        </select>
                                    </div>
                                    <!-- Minutes : End -->
                                    <!-- am/pm : Start -->
                                    <div class="fL">
                                        <label>am/pm</label>
                                        <select class="span1" id ="ampm">
                                            <option selected="selected">am/pm</option>
                                            <option value="0">am</option>
                                            <option value="1">pm</option>
                                        </select>
                                    </div>
                                    <!-- am/pm : End -->
                                </div>
                                <div class="cB"></div>
                            </div>
                            <div class="cB"></div>
                        </div>
                        <!-- Meeting Schedule : End -->

                        <!-- Invitees : Start -->
                        <div class="lGy p10">
                            <label for="multiSelect" class="control-label tBold"><span class="f18">3.</span> Select Invitees</label>
                            <div class="fL w670">

                                <table class="" width="100%" border="0px;" cellpadding="5px;" cellspacing="0" align="center">
                                    <tr class="" height="30px;">
                                        <td width="30%" class="tBold dGy" bgcolor="#8E8E8E">Contact List</td>
                                        <td width="1%" rowspan="2" bgcolor="#FFFFFF">&nbsp;</td>
                                        <td width="" class="tBold" bgcolor="#8E8E8E">Add Other Invitees</td>
                                        <td width="1%" rowspan="2" bgcolor="#FFFFFF">&nbsp;</td>
                                        <td width="30%" class="tBold" bgcolor="#8E8E8E">Invitees List</td>
                                    </tr>
                                    <tr>
                                        <!-- Invitees List : Start -->
                                        <td bgcolor="#E5E5E5">
                                            <form name="cDataForm">
                                                <div id="contactList" class="left-banner">
                                                    <?php
                                                    if (strlen($contacts) > 0)
                                                    {
                                                        echo $contacts;
                                                    }
                                                    else
                                                    {
                                                        echo "<div class='alert alert-info mR10'>No Contact List Available</div>";
                                                    }
                                                    ?>
                                                </div>
                                            </form>
                                            <input type="text" name="aSearch" id="aSearch" onkeyup="javascript:autoSuggest(this);" class="input-xmedium mT10" placeholder="Type here to Search Contact List.">
                                        </td>
                                        <!-- Invitees List : End -->

                                        <!-- Other Invitees : Start -->
                                        <td valign="top" bgcolor="#E5E5E5">
                                            <label>Name<span class="required">&nbsp;*</span></label>
                                            <input type="text" name="txtName" placeholder="Name" maxlength="50" class="w170" id="iNick">
                                            <label>Email Address<span class="required">&nbsp;*</span></label>
                                            <input type="text" name="txtEmail" placeholder="Email Address" maxlength="100" class="w170" id="iEmail">
                                            <input type="button" value="Add Invitee" class="btn btn-success" onclick="addOtherInvitee()">

                                        </td>
                                        <!-- Other Invitees : End -->

                                        <!-- Selected n added Invitees : Start -->
                                        <td valign="top" bgcolor="#E5E5E5">
                                            <div id="inviteesList" class="left-banner"></div>
                                        </td>
                                        <!-- Selected n added Invitees : End -->
                                    </tr>

                                </table>
                            </div>
                            <div class="cB"></div>
                        </div>
                        <!-- Invitees : End -->

                        <!-- Moderator : Start -->
                        <div class="lGy p10">
                            <label class="tBold"><span class="f18">4.</span> Select Meeting Moderator (Optional)</label>

                            <select class="span2" id ="moderator">
                                <option>Select Moderator</option>
                            </select>
                            <div class="cB"></div>
                        </div>
                        <!-- Moderator : End -->
                        <div class="pT20">
                            <div class="s22 b mB20"><span class="tColor">Total Invitees </span>: <span id="inviteesCount">0</span> Users</div>
                            <button href="#" class="btn btn-primary mR10" id="btnSchedule" onclick="btnSchedule('N');">Schedule</button>
                            <button href="#" class="btn btn-primary" onclick="clearData();">Cancel</button>
                        </div>
                        <div class="mB20"></div>
                    </div>
                <?php }else{ ?>
                <div id="mainContentDiv" class="span8 brdrGy pR75">
                        <hr>
                    <div class="alert alert-error mT10"><?php echo $errorMsg; ?></div>
                </div>
                <?php } ?>

                <!-- RHS : Start -->
                <div id="sRHSList" class="span3" style="padding-top: 18px;">

                    <?php //include (INCLUDES_PATH.'right_widget_account_status.php'); ?>

                    <div class='well'>
                        <h2 class="pL15">Meeting Schedule</h2>
                        <div class="alert alert-error errorDisplay mT10" id="error-msg-RHS"></div>
                        <ul class='meetingschedule' id="scheduleRHS">
                        </ul>
                    </div>

                </div>
                <!-- RHS : End -->
            </div>
        </div>
        <!-- Main content Area -->

        <!-- Footer content Area -->
        <?php include (INCLUDES_PATH.'footer.php'); ?>
        <!-- Footer content Area -->

        <!-- java script  -->
        <?php include (INCLUDES_PATH.'jsinclude.php'); ?>
        <!-- java script  -->

        <!-- java script  1-->
        <script src="<?php echo JS_PATH; ?>jquery-ui.min.js"></script>
        <script src="<?php echo JS_PATH; ?>jquery-ui-timepicker-addon.js"></script>

        <script type='text/javascript'>
            var SITE_ROOT = "<?php echo $SITE_ROOT; ?>";

            /*** Display Add New Invitee Div - RHS ***/
            function showAddInvitee (showId) {
                if (eval("document.getElementById('"+showId+"').style.display") == "none") {
                    eval("document.getElementById('"+showId+"').style.display = 'block'");
                } else {
                    eval("document.getElementById('"+showId+"').style.display = 'none'");
                }
            }

            function addNewInvitee (sId) {
                $("#error-msg-RHS").removeClass("alert-success").addClass("alert-error");
                var iNick = eval("document.getElementById(\'"+sId+":iAddNick\').value");
                var iEmail = eval("document.getElementById(\'"+sId+":iAddEmail\').value");
                if($.trim(iNick).length == 0) {
                    $("#error-msg-RHS").html("Please enter Nick Name");
                    $("#error-msg-RHS").css({"display":"block"});
                    return false;
                } else if($.trim(iEmail).length == 0) {
                    $("#error-msg-RHS").html("Please enter Email Address");
                    $("#error-msg-RHS").css({"display":"block"});
                    return false;
                } else if (!(/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(iEmail))) {
                    $("#error-msg-RHS").html("Please enter a valid Email Address");
                    $("#error-msg-RHS").css({"display":"block"});
                    return false;
                }

                $("#error-msg-RHS").html("");
                $("#error-msg-RHS").css({"display":"none"});

                $.ajax({
                    type: "GET",
                    url: "addInvitee.php",
                    cache: false,
                    data: "SCHID="+sId+"&NICK="+iNick+"&EMAIL="+iEmail+"&PRID=1",
                    loading:    $(".loading").html(""),
                    success:    function(html) {
                        sep = "<?php echo SEPARATOR; ?>";
                        html = html.split(sep);
                        if (html[0] == 1) {
                            eval("document.getElementById(\'"+sId+":iAddNick\').value = ''");
                            eval("document.getElementById(\'"+sId+":iAddNick\').placeholder = 'Nick Name' ");
                            eval("document.getElementById(\'"+sId+":iAddEmail\').value = '' ");
                            eval("document.getElementById(\'"+sId+":iAddEmail\').placeholder = 'Email Address' ");
                            showAddInvitee (sId);
                            scheduleList();
                            $("#error-msg-RHS").html(html[1]);
                            $("#error-msg-RHS").removeClass("alert-error").addClass("alert-success");
                            $("#error-msg-RHS").css({"display":"block"});
                        } else {
                            $("#error-msg-RHS").html(html[1]);
                            $("#error-msg-RHS").css({"display":"block"});
                            return false;
                        }
                    }
                });
            }

            /*** New Invitee ***/
            function addOtherInvitee () {
                var iNick = $("#iNick").val();
                var iEmail = $("#iEmail").val();
                if($.trim(iNick).length == 0) {
                    $("#error-msg").html("Please enter Invitee Nick Name");
                    $("#error-msg").css({"display":"block"});
                    return false;
                } else if($.trim(iEmail).length == 0) {
                    $("#error-msg").html("Please enter Invitee Email Address");
                    $("#error-msg").css({"display":"block"});
                    return false;
                } else if (!(/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(iEmail))) {
                    $("#error-msg").html("Please enter a valid Invitee Email Address");
                    $("#error-msg").css({"display":"block"});
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
                $("#error-msg").html("");
                $("#error-msg").css({"display":"none"});
            }

            /*** Show Timezone List ***/
            function showTimezone()
            {
                if(document.getElementById("timezoneShow").style.display == "block") {
                    document.getElementById("timezoneShow").style.display = "none";
                } else {
                    document.getElementById("timezoneShow").style.display = "block";
                }
            }

            function currentZone()
            {
                var tzone = $("#timezone").val();
                var sep = "$:$";
                tzone = tzone.split(sep);
                timeZone = tzone[0]+" - "+tzone[1]+", GMT"+tzone[2];
                $("#curZone").html(timeZone);
                $("#timezoneTitle").html("Meeting Timezone");
                showTimezone();
            }

            /*** Show Plan List ***/
            function showPlan()
            {
                if(document.getElementById("planShow").style.display == "block") {
                    document.getElementById("planShow").style.display = "none";
                } else {
                    document.getElementById("planShow").style.display = "block";
                }
            }

            function currentPlan()
            {
                var usrplan = $("#activePlans").val();
                var sep = "$:$";
                uplan = usrplan.split(sep);
                cplan = "<input id='sPlan' name='"+usrplan+"' value='"+uplan[4]+"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Expires on "+uplan[1]+"' readonly='true' UNSELECTABLE='ON' contenteditable='false'>";
                $("#curPlan").html(cplan);
                showPlan();
            }

            /*** IE - indexOf ***/
            if (!Array.prototype.indexOf) {
                Array.prototype.indexOf = function(elt /*, from*/)  {
                    var len = this.length >>> 0; var from = Number(arguments[1]) || 0;
                    from = (from < 0) ? Math.ceil(from) : Math.floor(from);
                    if (from < 0) from += len;
                    for (; from < len; from++) {
                        if (from in this && this[from] === elt) return from;
                    }
                    return -1;
                };
            }

            /*** Set Counters ***/
            var z = 0;
            var tCount = 0;
            var cInvitees = new Array();

            function cCounter(invitee,iEmail,eStat) {
                invitee = urldecode(invitee);
                inviteeID = invitee+":"+iEmail;
                inviteeStat = eval("document.getElementById('"+inviteeID+"').checked");
                inviteeVal = eval("document.getElementById('"+inviteeID+"').value");
                if (inviteeStat && eStat == "con") {
                    cAdd = iEmail; cAddIndex = cInvitees.indexOf(inviteeVal);  if(cAddIndex < 0) { cInvitees.push(inviteeVal); } addInviteeDiv(invitee, iEmail, inviteeVal, "con");
                } else if (!(inviteeStat) && eStat == "con") {
                    eval("document.getElementById('"+inviteeID+"').checked = false"); removeInvitee(invitee, iEmail, inviteeVal, "rem");
                } else if (eStat == "rem") {
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
                var parent = document.getElementById('inviteesList');
                parent.removeChild(child);
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
                oContact.innerHTML = oContact.innerHTML + "<div type=text id="+email+" class=oInvitees><input type=text name="+val+" value="+urlencode(vals[1])+"  readonly=readonly class=inputInviteesList><img src=<?php echo IMG_PATH; ?>closered.png alt=Delete id="+email+" onclick=removeInvitee('"+escape(id)+"','"+email+"','"+val+"','"+eType+"');  class=cPointer title=Remove Invitee /></div>";
                moderator(email, escape(vals[1]), "aM");
                iCounter();
            }

            function moderator(eMail, nName, mType) {
                uModerator = document.getElementById('moderator');
                if (mType == "aM") {
		    var newOp = document.createElement("option");
                    newOp.text = unescape(nName);
                    newOp.value = eMail;
                    uModerator.options.add(newOp);
                } else if (mType == "rM") {
                    for(i=0;i<(uModerator.length);i++)
                    {
                        if (uModerator.options[i].value == eMail)
                        {
                            uModerator.remove(i);
                        }
                    }
                } else if (mType == "dM") {
                    for(i=0;i<(uModerator.length);i++) {
                        uModerator.remove(i);
                    }
                    uModerator.innerHTML = "<option>Select Moderator</option>";
                }
            }

            /*** URL Encode - Decode ***/
            function urldecode (str) {
                return decodeURIComponent((str+'').replace(/\+/g,'%20'));
            }

            function urlencode (str) {
                str = (str + '').toString();
                return encodeURIComponent(str).replace(/!/g, '%21').replace(/'/g, '%27').replace(/\(/g, '%28').replace(/\)/g, '%29').replace(/\*/g, '%2A').replace(/%20/g, '&nbsp;');
            }

            /*** Auto Suggest ***/
            function autoSuggest(a) {
                var aWord = $.trim(a.value);
                if (aWord.length > 0) {
                    clID = "<?php echo $strCk_client_id; ?>";
                    email = "<?php echo $strCk_email_address ?>";
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
                                $("#contactList").html("<div class='alert alert-info mR10'>No Contact List Available</div>");
                            }
                        }
                    });
                } else {
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

            /*** Validate Schedule Meeting Details ***/
            function btnSchedule(stat) {
                $("#error-msg").html("");
                $("#error-msg").css({"display":"none"});
                var tzone = $("#timezone").val();
                var title = $("#sTitle").val();
                var mod = $("#moderator").val();

                pCount = "<?php echo $plansCount; ?>";
                if (pCount > 1)
                    uplan = $("#sPlan").val();
                else
                    uplan = $("#sPlan").attr("name");
                userPlan = uplan.split("$:$");
                if (uplan.length == 0) {
                    $("#error-msg").html("Please Select Plan");
                    $("#error-msg").css({"display":"block"});
                } else if($.trim(title).length == 0) {
                    $("#error-msg").html("Please type Meeting Title");
                    $("#error-msg").css({"display":"block"});
                }
                else if ($('#current-time:checked').val() != 'on' && $('#schedule-time:checked').val() != 'on') {
                    $("#error-msg").html("Please set Meeting Time");
                    $("#error-msg").css({"display":"block"});
                }
                else if (document.getElementById('inviteesCount').innerHTML < 1) {
                    $("#error-msg").html("Please select Invitees from Contact List or Add Other Invitees");
                    $("#error-msg").css({"display":"block"});
                }
                else {
                    if ($('#schedule-time:checked').val() == 'on') {
                        var syscurdate = '<?php echo date("d-m-Y"); ?>';
                        var syscurtime = '<?php echo date("H:i"); ?>';
                        var hrs = syscurtime.split(':')[0];
                        var mins = syscurtime.split(':')[1].split(' ')[0];
                        var ampm = syscurtime.split(':')[1].split(' ')[1];
                        var selctedhrs = $('#hours :selected').text();
                        var selctedmins = $('#minutes :selected').text();
                        var selctedampm = $('#ampm :selected').text();
                        var schDtm = $('#datetime').val() + " " + selctedhrs + ":" + selctedmins + selctedampm;
                        if (selctedampm == "pm") {
                            if (selctedhrs != 12) {
                                selctedhrs = parseInt(selctedhrs) + 12;
                            } else if (selctedhrs == 12) {
                                selctedhrs = 12;
                            }
                        } else if(selctedhrs == 12 && selctedampm == 'am') {
                            selctedhrs = '00';
                        }
                        var schType = "S";
                        if($.trim(schDtm).length == 0) {
                            $("#error-msg").html("Please select date");
                            $("#error-msg").css({"display":"block"});
                            return false;
                        } else if(selctedhrs == 'HH') {
                            $("#error-msg").html("Please select hours");
                            $("#error-msg").css({"display":"block"});
                            return false;
                        } else  if(selctedmins == 'MM') {
                            $("#error-msg").html("Please select minutes");
                            $("#error-msg").css({"display":"block"});
                            return false;
                        } else if(selctedampm == 'am/pm') {
                            $("#error-msg").html("Please select am/pm");
                            $("#error-msg").css({"display":"block"});
                            return false;
                        } else if(parseInt(hrs) == '00' && parseInt(selctedhrs) == '12' && selctedampm == 'am') {
                            $("#error-msg").html("Incorrect Time Selection");
                            $("#error-msg").css({"display":"block"});
                            return false;
                        } else if($.trim($('#datetime').val()) == syscurdate) {
                            if (Number(hrs) > Number(selctedhrs)) {
                                $("#error-msg").html("Schedule time cannot be less than current time");
                                $("#error-msg").css({"display":"block"});
                                return false;
                            } else if (Number(hrs) == Number(selctedhrs)) {
                                if(parseInt(mins) > parseInt(selctedmins)) {
                                    $("#error-msg").html("Schedule time cannot be less than current time");
                                    $("#error-msg").css({"display":"block"});
                                    return false;
                                }
                            }
                        }
                
			schDate = new Date($('#datetime').val().replace(/-/g, '/')).getTime();
                        plnDate = new Date(userPlan[1].replace(/-/g, '/')).getTime();

                        //if (schDate > plnDate) {
                        //    $("#error-msg").html("Schedule date exceeds Plan Expiry Date");
                        //    $("#error-msg").css({"display":"block"});
                        //    return false;
                        //}
                    } else {
                        schType = "N";
                        schDtm = "";
                    }

                    var title = $.trim(title);
                    var inviteescount = document.getElementById('inviteesCount').innerHTML;
                    var contactData = new Array();
                    var contactEmail = new Array();
                    var parent = document.getElementById('inviteesList');
                    for (i=0;i<parent.childNodes.length;i++) {
                        contactEmail = parent.childNodes[i].id;
                        var firstparent = eval("document.getElementById('"+contactEmail+"')");
                        contactData[i] = firstparent.childNodes[0].name;
                    }
                    inviteesList = contactData;
                    var maxLimit = userPlan[2];
                    if (parseInt(inviteescount) > parseInt(maxLimit) && parseInt(maxLimit) != 0) {
                        $("#error-msg").html("Your max limit for Invitees List is "+maxLimit);
                        $("#error-msg").css({"display":"block"});
                        return false;
                    }
                    $.ajax({
                        type: "GET",
                        url: "createSchedule.php",
                        cache: false,
                        data: "title="+title+"&schedule_dtm="+schDtm+"&inviteesList="+inviteesList+"&scheduleType="+schType+"&inviteesCnt="+inviteescount+"&tzone="+tzone+"&mod="+mod+"&uplan="+uplan+"&stat="+stat,
                        loading: $(".loading").html(""),
                        success:    function(html) {
                            sep = "<?php echo SEPARATOR; ?>"; html = html.split(sep);
                            if(html[0] == 1) {
                                clearData();
                                cInvitees = []; $("#mainContentDiv").html(html[1]);
                                scheduleList();
                            } else if(html[0] == 3) {
                                scheduleConfirm(html[1]);
                            } else if( (html[0] == 0) || (html[0] == 2) ){
                                $("#error-msg").html(html[1]);
                                $("#error-msg").css({"display":"block"});
                            }
                        }
                    });
                }
            }

            var globalId;
            function scheduleConfirm(msg) {
                globalId = "schBox";
                schStat = "Y";
                ModalPopups.Confirm(globalId,"Schedule Meeting","<div style='padding: 25px;'>"+msg+"</div>",  {
                    yesButtonText: "Yes", noButtonText: "No", onYes: "btnSchedule();closeSchedule()", onNo: "closeSchedule()" });
            }

            function closeSchedule()
            {
                ModalPopups.Close(globalId);
            }

            /*** Display Date Picker ***/
            $(document).ready(function ()
            {
                $("input:radio[name=schedule-time]").click(function()
                {
                    if ($('#schedule-time:checked').val() == 'on')
                    {
                        /*var mDate = maxPlanDate;*/
                        showDate();
                    } else {
                        $('#schdatetime').css({"display":"none"});
                    }
                });
                $('span.view').hover(function(e) {
                    var id = $(this).attr('id');
                    var height = $('#content_' + id).height() / 2;
                    height += height / 2;
                    $('#content_' + id).css({'left' : e.pageX + 30, 'top' : e.pageY - height}).show(); },
                function (e) {
                    var id = $(this).attr('id');
                    $('#content_' + id).css({'left' : 'auto', 'top' : 'auto'}).hide();
                });
            });

            function showDate()
            {
                mDate = '<?php echo $maxDate; ?>';
                var syscurdate = '<?php echo date("d-m-Y"); ?>';
                $('#datetime').val(syscurdate);
                $('#schdatetime').css({"display":"block"});
                $('#datetime').datepicker({
                    dateFormat: 'dd-mm-yy', stepMinute: 5, maxDate: mDate, minDate: 0, showOn: 'both', buttonImage: '<?php echo IMG_PATH; ?>calendar.gif', buttonText:'Calendar', buttonImageOnly: true
                });
            }

            /*** Cancel Meeting Pop Up ***/
            var globalSID;
            ModalPopups.SetDefaults( { yesButtonText: "Yes", noButtonText: "No", okButtonText: "OK", cancelButtonText: "Cancel" } );
            function cancelConfirm (sId) {
                globalSID = sId;
                ModalPopups.Confirm(sId,"Cancel Meeting","<div style='padding: 25px;'>Do you want to cancel the meeting ?</div>",  {
                    yesButtonText: "Yes", noButtonText: "No", onYes: "cancelMeeting()", onNo: "closeCancel()" });
            }

            function cancelMeeting()
            {
                pId = "<?php echo PRID; ?>";
                $.ajax({
                    type: "GET",
                    url: SITE_ROOT+"api/cancelschedule.php",
                    cache: false,
                    data: "SCID="+globalSID+"&PRID="+pId,
                    loading: $(".loading").html(""),
                    success: function(html) {
                        scheduleList();
                    }
                });
                closeCancel();
            }

            function closeCancel()
            {
                ModalPopups.Close(globalSID);
            }

            /*** CSS Change for Start Button ***/
            function changeStyle()
            {
                document.getElementById("sSchedule").disabled = true;
                document.getElementById("sSchedule").className = "btn";
            }

            /*** Clear Field Data ***/
            function clearData()
            {
                document.getElementById('sPlan').value = '';
                document.getElementById('sTitle').value = '';
                document.getElementById('sTitle').placeholder = 'Type your Meeting Title here.';
                document.getElementById('schedule-time').checked = false;
                document.getElementById('current-time').checked = false;
                document.getElementById('datetime').value = 'DD-MM-YYYY';
                document.getElementById('hours').value = 'HH';
                document.getElementById('minutes').value = 'MM';
                document.getElementById('ampm').value = 'am/pm';
                document.getElementById('schdatetime').style.display = 'none';
                document.getElementById('inviteesList').innerHTML = '';
                document.getElementById('aSearch').value = '';
                document.getElementById('aSearch').placeholder = 'Type here to Search Contact List.';
                document.getElementById('contactList').innerHTML = "<?php echo $contacts; ?>";
                document.getElementById('inviteesCount').innerHTML = 0;
                document.getElementById('iNick').value = '';
                document.getElementById('iNick').placeholder = 'Nick Name';
                document.getElementById('iEmail').value = '';
                document.getElementById('iEmail').placeholder = 'Email Address';
                moderator("", "", "dM");
                $('#error-msg').html('');
                $('#error-msg').css({'display':'none'});
            }

            function rhsmDetails(sId,pId)
            {
                sE = "<?php echo $strCk_email_address; ?>";
                $.ajax({
                    type: "GET",
                    url: "../meeting/meetingDetails.php",
                    cache: false,
                    data: "schId="+sId+"&pC="+pId+"&email="+sE+"&mT=s",
                    loading: $(".loading").html(""),
                    success:    function(html) {
                        $("#sHeading").html("Meeting Details");
                        $("#sSubHeading").html("&nbsp;");
                        $("#sRHSList").html("&nbsp;");
                        $("#mainContentDiv").html(html);
                        document.getElementById("mainContentDiv").className = "span12";
                    }
                });
            }
        </script>

        <!-- java script  1-->

    </body>
</html>
