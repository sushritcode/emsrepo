<?php

require_once('../includes/global.inc.php');
require_once(CLASSES_PATH."error.inc.php");
require_once(INCLUDES_PATH."Utilities.php");
require_once(DBS_PATH."DataHelper.php");
require_once(DBS_PATH."objDataHelper.php");
require_once(INCLUDES_PATH."db_common_function.inc.php");
require_once(INCLUDES_PATH."cm_authfunc.inc.php");
$CONST_MODULE = 'meeting';
$CONST_PAGEID = 'Meeting';
require_once(INCLUDES_PATH."cm_authorize.inc.php");
require_once(INCLUDES_PATH."sch_function.inc.php");

try
{
   $meetingList = getMyMeetingList($strCk_email_address , $objDataHelper);
}
catch(Exception $e)
{
   throw new Exception("index.php : getMyMeetingList Failed : ".$e->getMessage() , 1126);
}


$sM .= "<div>
    <div class='mB10'><h4>Scheduled Meetings</h4></div>";   

if((is_array($meetingList)) && (sizeof($meetingList)) > 0)
{
   $sM .= "<table class='tblz01' width='100%' id='meetingResults'>
         <thead>
            <tr class='thead'>
               <td width='22%'>Meeting Title</td>
               <td width='19%'>Date time </td>
               <td width='12%'>Invitees</td>
               <td width='15%'>Moderator</td>
               <td width='15%'>&nbsp;</td>
            </tr>
         </thead>
         <tbody>";
   for($intCntr = 0; $intCntr < sizeof($meetingList); $intCntr++)
   {
      $mSchId = $meetingList[$intCntr]["schedule_id"];
      $mTitle = $meetingList[$intCntr]["meeting_title"];
      $sMTitle = implode(" " , array_splice(explode(" " , $mTitle) , 0 , 5));
      if (count(explode(" ",$mTitle)) > 5)
      {
	  $sMTitle = $sMTitle."...";
      }
      else if (strlen($aMTitle) > 20) {
	  $sMTitle = substr($sMTitle,0,20)."...";
      }
      $mDtm = $meetingList[$intCntr]["meeting_timestamp_local"];
      $mGtm = $meetingList[$intCntr]["meeting_timestamp_gmt"];
      $mCat = $meetingList[$intCntr]["invitation_creator"];
      $mStat = $meetingList[$intCntr]["schedule_status"];
      if($mCat == "C") $mModerator = $strCk_nick_name;
      else
      {
         try
         {
            $modDetails = moderatorDetails($mSchId , $objDataHelper);
            $mModerator = $modDetails[0]["invitee_nick_name"];
         }
         catch(Exception $e)
         {
            throw new Exception("index.php : moderatorDetails Failed : ".$e->getMessage() , 1126);
         }
      }
      $mInviteeCnt = $meetingList[$intCntr]["max_participants"];
      $mPassCode = md5($meetingList[$intCntr]["schedule_id"].":".$strCk_email_address.":".SECRET_KEY);

      $sM .= "<tr>
                  <td class='s15'><a class='cPointer' onclick=meetingDetails('".$mSchId."','".$mPassCode."')>".$sMTitle."</a></td>
                  <td><i class='icon-white icon-time'></i>&nbsp;".$mDtm."</td>
                  <td><b>".$mInviteeCnt."</b> Users</td>
                  <td>".$mModerator."</td>
                  <td class='aC'>";
      $gmtStart = date("Y-m-d H:i:s" , strtotime($mGtm."-".MEETING_START_GRACE_INTERVAL." min"));
      $gmtEnd = date("Y-m-d H:i:s" , strtotime($mGtm."+".MEETING_END_GRACE_INTERVAL." min"));
      if($mCat == "C")
      {
         if((GM_DATE > $gmtStart) && (GM_DATE <= $gmtEnd))
         {
            $sM .= "<a href=".$SITE_ROOT."schedule/start.php?startId=".$mSchId." target='_blank'><span class='label label-info' onclick='timeOut();'>Join Meeting</span></a>";
         }
         if ($mStat == "0") 
         {
            $sM .= "<div onclick=cancelConfirm('".$mSchId."')><span class='label label-warning' style='cursor:pointer'>Cancel</span></div>";
         }
      }
      else
      {
         if((GM_DATE > $gmtStart) && (GM_DATE <= $gmtEnd))
         {
            $sM .= "<a href=".$SITE_ROOT."schedule/start.php?startId=".$mSchId." target='_blank'><span class='label label-info'>Join Meeting</span></a>";
         }
         $sM .= "       <div><span class='label label-success' style='cursor:pointer' onclick=inviteeStatus('".$mSchId."',1)>Accept</span></div>
                              <div><span class='label' style='cursor:pointer;background-color:#D81830' onclick=inviteeStatus('".$mSchId."',2) >Decline</span></div>";
      }
      $sM .= "</td>
               </tr>";
   }
   $sM .= "</tbody>
      </table>
      <div class='pagination pagination-centered' id='pageNavPositionMeeting'></div>";
}
else
{

   $sM .= "<div class='alert alert-info'>No Meeting Schedule</div>";
}

$sM .= "</div>";

$sM .= " <script src='".JS_PATH."paging.js'></script>
<script type='text/javascript'>
var schlist = $meetingList;
if(schlist != '') 
{
    var pagerMeeting = new Pager('meetingResults', 5, 'sch');
    pagerMeeting.init();
    pagerMeeting.showPageNav('pagerMeeting', 'pageNavPositionMeeting');
    pagerMeeting.showPage(1);
}
</script>";

echo $sM;