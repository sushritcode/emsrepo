<?php

require_once('../includes/global.inc.php');
require_once(CLASSES_PATH . "error.inc.php");
require_once(DBS_PATH . "DataHelper.php");
require_once(DBS_PATH . "objDataHelper.php");
require_once(INCLUDES_PATH . "db_common_function.inc.php");
require_once(INCLUDES_PATH . "cm_authfunc.inc.php");
$CONST_MODULE = 'schedule';
$CONST_PAGEID = 'Schedule';
require_once(INCLUDES_PATH . "cm_authorize.inc.php");
require_once(INCLUDES_PATH . "sch_function.inc.php");

/* * * For listing the scheduled meetings on the RHS * * */
$graceInterval = MEETING_LIST_GRACE_INTERVAL;
try
{
    $arrMeetingList = getMeetingListRHS($strCK_user_id, $graceInterval, $objDataHelper);
}
catch (Exception $e)
{
    throw new Exception("index.php : getMeetingListRHS Failed : " . $e->getMessage(), 1127);
}

if ((is_array($arrMeetingList)) && (sizeof($arrMeetingList)) > 0)
{
    foreach ($arrMeetingList as $cKey => $cVal)
    {
        $gmTime = $cVal['meeting_timestamp_gmt'];
        $localTime = $cVal['meeting_timestamp_local'];
        $timezone = $cVal['meeting_timezone'];
        //$meetingDtm = dateFormat($gmTime, $localTime, $timezone);
        $meetingDtm = date("D, F jS Y, h:i A", strtotime($localTime));

        $meetingTitle = $cVal['meeting_title'];
        $scheduleStatus = $cVal['schedule_status'];
        $scheduleId = $cVal['schedule_id'];
        $mPassCode = md5($scheduleId . ":" . $strCk_email_address . ":" . SECRET_KEY);
        $str .= "<li>";
        if ($scheduleStatus == 0)
        {
            $str .= "<div class='fR cPointer'><img src='" . IMG_PATH . "closered.png' onclick=cancelConfirm('" . $scheduleId . "') alt='Cancel Meeting' title='Cancel Meeting'></div>";
        }

        $trimMeetingTitle = implode(' ', array_splice(explode(' ', $meetingTitle), 0, 3)) . "...";
        if (strlen($trimMeetingTitle) > 15)
        {
            $trimMeetingTitle = substr($trimMeetingTitle, 0, 15) . "...";
        }

        $str .= "<div>
				<div class='fL w180'>
					<div class='s16'><span class='tColor cPointer' title='" . $meetingTitle . "' onclick=rhsmDetails('" . $scheduleId . "','" . $mPassCode . "')>" . $trimMeetingTitle . "</span></div>
					<div class='s10'><i class='icon-time'></i>&nbsp;" . $meetingDtm . "</div>
				</div>";
        if ($cVal['max_participants'] < DEFAULT_INVITEE_LIMIT)
        {
            $str .= "<div class='fR w40 aC'>
                                    <span class='icon_meeting cPointer' alt='Add Invitee' title='Add Invitee' onclick=showAddInvitee('" . $scheduleId . "')></span>
                          </div>
                          <div id='" . $scheduleId . "' class='mT10 fL' style='display:none'>
                                    <span>Nick Name <input type='text' class='span2' id='" . $scheduleId . ":iAddNick' placeholder='Nick Name'></span><br />
                                    <span>Email Address <input type='text' class='span2' id='" . $scheduleId . ":iAddEmail' placeholder='Email Address'></span><br />
                                    <span><input type='button' value='Add Invitee' class='btn btn-success' onclick=addNewInvitee('" . $scheduleId . "')></span>
                          </div>
                          <div class='cB'></div>";
        }
        else
        {
            $str .= "<div class='fR w40 aC'>&nbsp;</div><div class='cB'></div>";
        }
        $str .= "</div>
        </li><div class='pB5'></div>";
    }
}
else
{
    $str .= "<div class='alert alert-info mT10' >No Meeting Schedule</div>";
}
echo $str;
?>