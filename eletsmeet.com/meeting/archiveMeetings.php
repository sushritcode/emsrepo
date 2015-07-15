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
   $archiveList = getMyArchiveMeetings($strCk_email_address , $objDataHelper);
}
catch(Exception $e)
{
   throw new Exception("index.php : getMyArchiveMeetings Failed : ".$e->getMessage() , 1126);
}


$aM .= "<div>
<div class='mB10'><h4>Archive Meetings</h4></div>";   

if((is_array($archiveList)) && (sizeof($archiveList)) > 0)
{
   $aM .= "<table class='tblz01' width='100%' id='archiveResults'>
         <thead>
            <tr class='thead'>
               <td width='22%'>Meeting Title</td>
               <td width='19%'>Date time </td>
               <td width='12%'>Invitees</td>
               <td width='12%'>Attendees</td>
               <td width='15%'>Meeting Status</td>
            </tr>
         </thead>
         <tbody>";

   for($intCntr = 0; $intCntr < sizeof($archiveList); $intCntr++)
   {
      $aSchId = $archiveList[$intCntr]["schedule_id"];
      $arrInviteeList = getMeetingInviteeList($aSchId , $objDataHelper);
      $i = 0;
      foreach($arrInviteeList as $invKey => $invVal)
      {
         if($invVal["meeting_status"] == 1)
         {
            $i = $i + 1;
         }
      }

      $aTitle = $archiveList[$intCntr]["meeting_title"];
      $aMTitle = implode(" " , array_splice(explode(" " , $aTitle) , 0 , 5));
      if(count(explode(" " , $aTitle)) > 5)
      {
         $aMTitle = $aMTitle."...";
      }
      else if(strlen($aMTitle) > 20)
      {
         $aMTitle = substr($aMTitle , 0 , 20)."...";
      }
      $aDtm = $archiveList[$intCntr]["meeting_timestamp_local"];
      $aS = $archiveList[$intCntr]["schedule_status"];
      switch($aS)
      {
         case 0: $aStatus = "<span style='color:#C09853'>Scheduled</span>";
            break;
         case 1: $aStatus = "<span style='color:#3A87AD'>Started</span>";
            break;
         case 2: $aStatus = "<span style='color:#9CC032'>Completed</span>";
            break;
         case 3: $aStatus = "<span style='color:#F89406'>Cancelled</span>";
            break;
         case 4: $aStatus = "<span style='color:#D81830'>Overdue</span>";
            break;
         case 5: $aStatus = "<span style='color:#8B8B8B'>Error</span>";
            break;
         default: break;
      }
      $aInviteeCnt = $archiveList[$intCntr]["max_participants"];
      $aPassCode = md5($archiveList[$intCntr]["schedule_id"].":".$strCk_email_address.":".SECRET_KEY);

      $aM .= "<tr>
                  <td class=s15><a class=cPointer onclick=meetingDetails('$aSchId','$aPassCode')>$aMTitle</a></td>
                  <td><i class='icon-white icon-time'></i>&nbsp;$aDtm</td>
                  <td><b>$aInviteeCnt</b> Users</td>
		  <td><b>";
      if($aS == 2)
      {
         $aM .= $i." out of ".count($arrInviteeList)."</b></td>";
      }
      $aM .= "<td class=b>$aStatus</td>
               </tr>";

   }
      $aM .= "</tbody>
      </table>
      <div class='pagination pagination-centered' id='pageNavPositionArchive'></div>";

}
else
{
   $aM .= "<div class='alert alert-info'>No Meetings In Archive</div>";
}

$aM .= "</div>";

$aM .= "<script src='".JS_PATH."paging.js'></script>
<script type='text/javascript'>
var archlist = $archiveList;
if(archlist != '') 
{
     var pagerArchive = new Pager('archiveResults', 5, 'arc');
    pagerArchive.init();
    pagerArchive.showPageNav('pagerArchive', 'pageNavPositionArchive');
    pagerArchive.showPage(1);
}
</script>";

echo $aM;
