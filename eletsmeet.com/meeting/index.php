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

$startGrace = 0;
?>
<!DOCTYPE html>
<html lang="en">
    <!-- Head content Area -->
    <head>
        <?php include (INCLUDES_PATH.'head.php'); ?>
        <script src="<?php echo JS_PATH; ?>jquery.js" type='text/javascript'></script>
        <script src="<?php echo JS_PATH; ?>ModalPopups.js" type="text/javascript"></script>
    </head>
    <!-- Head content Area -->

    <body onload="javascript:sMeetings();">

        <!-- Navigation Bar, After Login Menu &  Product Logo -->
        <?php include (INCLUDES_PATH.'navigation.php'); ?>
        <!-- Navigation Bar, After Login Menu &  Product Logo -->

        <div class="container">

            <div class="row">
                <div class="span12">
                    <h2 id="meetingHeading">My Meetings</h2>
                    <div class="alert alert-error errorDisplay mT10" id="mError"></div>
                </div>
                
                <div class="mB10">
                    <div class="fR"><a class="btn btn-primary" href="<?php echo $SITE_ROOT."schedule/"; ?>"><i class='icon-white icon-calendar'></i>&nbsp;Schedule a new meeting</a></div>
                    <div class="cB"></div>
                </div>
                
                <div id="mDetails" class="span12"><hr>

                    <div class="h20">&nbsp;</div>
                    <!-- Scheduled Meetings -->
                    <div id="sMeetings">
                        <?php include("scheduleMeetings.php"); ?>
                    </div>
                    <!-- Scheduled Meetings : End -->
                    <div class="h20 mB10">&nbsp;</div>
                    <!-- Archive Meetings : Start -->
                    <div id="aMeetings">
                        <?php include("archiveMeetings.php"); ?>
                    </div>
                    <!-- Archive Meetings : End -->

                </div>

                <!-- RHS : Start -->
<!--                <div id="sRHSList" class="span3">

                    <?php //include (INCLUDES_PATH.'right_widget_account_status.php'); ?>

                </div>-->
                <!-- RHS : End -->

            </div>
        </div>
        <!-- Footer content Area -->
        <?php include (INCLUDES_PATH.'footer.php'); ?>
        <!-- Footer content Area -->
        <!-- /container -->


        <!-- java script  -->
        <?php include (INCLUDES_PATH.'jsinclude.php'); ?>
        <script src="<?php echo JS_PATH; ?>show-popup.js"></script>
        <!-- java script  -->

        <script type="text/javascript">
            function meetingDetails(sId,pId) {
                sE = "<?php echo $strCk_email_address; ?>";
                $.ajax({
                    type: "GET",
                    url: "meetingDetails.php",
                    cache: false,
                    data: "schId="+sId+"&pC="+pId+"&email="+sE+"&mT=m",
                    loading: $(".loading").html(""),
                    success:    function(html) {
                        $("#meetingHeading").html("Meeting Details");
                        $("#mDetails").html(html);
                    }
                });
            }
            
            function timeOut() {
                setTimeout("sMeetings()",1000);
            }
            
            function sMeetings() {
                $.ajax({
                    type: "GET",
                    url: "scheduleMeetings.php",
                    cache: false,
                    data: "",
                    loading: $(".loading").html(""),
                    success:    function(html) {
                        aMeetings();
                        $("#sMeetings").html(html);
                    }
                });
                setTimeout("sMeetings()",180000);
            }
            
            function aMeetings() {
                $.ajax({
                    type: "GET",
                    url: "archiveMeetings.php",
                    cache: false,
                    data: "",
                    loading: $(".loading").html(""),
                    success:    function(html) {
                        $("#aMeetings").html(html);
                    }
                });
                setTimeout("aMeetings()",180000);
            }

            var SITE_ROOT = "<?php echo $SITE_ROOT; ?>";
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
                        sMeetings();
                        $("#mError").html("You have Canceled the meeting.");
                        $("#mError").removeClass("alert-success alert-error").addClass("label-warning");
                        $("#mError").css({"display":"block"});
                    }
                });
                closeCancel();
            }

            function closeCancel()
            {
                ModalPopups.Close(globalSID);
            }

            function inviteeStatus (sId,iStat)
            {
                $.ajax({
                    type: "GET",
                    url: SITE_ROOT+"meeting/inviteeStatus.php",
                    cache: false,
                    data: "sId="+sId+"&iStat="+iStat,
                    loading: $(".loading").html(""),
                    success: function(html) {
                        if (iStat == 1) {
                            $("#mError").html("You have Accepted the meeting request.");
                            $("#mError").removeClass("alert-error").addClass("alert-success");
                        }
                        else {
                            $("#mError").html("You have Declined the meeting request.");
                            $("#mError").removeClass("alert-success").addClass("alert-error");
                        }
                        $("#mError").css({"display":"block"});
                    }
                });
            }
            
            function recordingDetails(recordurl) {
                showPopup('#popupS', '#layer');
                 $.ajax({
                    type: "GET",
                    url: "recording.php",
                    cache: false,
                    data: "txtUrl="+recordurl,
                    loading: $(".loading").html(""),
                    success:    function(html) {
                        $("#RecordDetails").html(html);
                    }
                });
            }
            
             function closeDetails() {
                hidePopup('#popupS', '#layer');
            }

        </script>
    </body>
</html>