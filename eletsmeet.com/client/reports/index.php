<?php
require_once('../../includes/global.inc.php');
require_once('../includes/config.inc.php');
require_once(CLIENT_CLASSES_PATH . 'client_error.inc.php');
require_once(DBS_PATH . 'DataHelper.php');
require_once(DBS_PATH . 'objDataHelper.php');
require_once(CLIENT_INCLUDES_PATH . 'client_authfunc.inc.php');
$CLIENT_CONST_MODULE = 'clreports';
$CLIENT_CONST_PAGEID = 'Meeting Info';
require_once(CLIENT_INCLUDES_PATH . 'client_authorize.inc.php');
require_once(CLIENT_INCLUDES_PATH . 'client_db_function.inc.php');

try
{
    try
    {
        $arrMeetingDuration = getMeetingDurationByClient($strSetClient_ID, $objDataHelper);
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
    <!-- Head content Area -->
    <head>
        <?php include (CLIENT_INCLUDES_PATH . 'head.php'); ?>
    </head>
    <!-- Head content Area -->

    <body>

        <!-- Navigation Bar, After Login Menu &  Product Logo -->
        <?php include (CLIENT_INCLUDES_PATH . 'navigation.php'); ?>    
        <!-- Navigation Bar, After Login Menu &  Product Logo -->

        <!-- Main content Area -->
        <div class="container">
            <!-- Main hero unit for a primary marketing message or call to action -->

            <!-- Middle content Area -->
            <div class="row">
                
                <div class="span12">
                    <div class="fL"><h3>Meeting Details</h3></div>
                </div>
                
                <div class="span12"><hr>
                    
                <div>
                    <?php
                    if (!empty($arrMeetingDuration))
                    {
                    ?>
                        <div style="overflow:auto;">
                        <table width="100%" id="user-results" class="tblz01">
                            <tbody>
                            <?php
                            try
                            {
                                $arrMeetingListByUser = getMeetingCountByUser($strSetClient_ID, $objDataHelper);
                            }
                            catch (Exception $a)
                            {
                                throw new Exception("index.php : getMeetingListByUserID : Error in populating List.".$a->getMessage(), 541);
                            }

                            for ($intCntr = 0; $intCntr < sizeof($arrMeetingDuration); $intCntr++)
                            {  
                            ?>
                                <tr style="background-color: #333333; line-height: 18px; font-weight: bold;">
                                    <td width="2%">&nbsp;</td>
                                    <td width="48%"><span style="color: #f5d328;">Total No. of Meetings&nbsp;:&nbsp;</span><?php echo $arrMeetingDuration[$intCntr]['TotalMeetings']; ?></td>
                                    <td width="48%"><span style="color: #f5d328;">Total Meeting Duration (in Mins)&nbsp;:&nbsp;</span><?php echo $arrMeetingDuration[$intCntr]['TotalDuration']; ?></td>                                              
                                </tr>
                                <tr>
                                    <td colspan=4" style="border-bottom: 0px;">
                                        <table class="tblz01" width="100%">
                                            <thead>
                                                <tr style="font-weight: bold;">
                                                    <td width="2%">&nbsp;</td>
                                                    <td width="58%">&nbsp;Name</td>
                                                    <td width="20%">&nbsp;Total No. of Meetings</td>
                                                    <td width="20%">&nbsp;Total Meeting Duration (in Mins)</td>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            <?php 
                                                $count=0;
                                                for ($intCntr1 = 0; $intCntr1 < sizeof($arrMeetingListByUser); $intCntr1++)
                                                {  
                                                    if($arrMeetingDuration[$intCntr]['client_id'] == $arrMeetingListByUser[$intCntr1]['client_id'])
                                                    {
                                                        $count++;
                                                        ?>
                                                            <tr>
                                                                <td>&nbsp;<?php echo $count; ?></td>
                                                                <td>&nbsp;<?php echo $arrMeetingListByUser[$intCntr1]['first_name']." ".$arrMeetingListByUser[$intCntr1]['last_name']; ?></td>
                                                                <td>&nbsp;<?php echo $arrMeetingListByUser[$intCntr1]['TotalMeetings']; ?></td>
                                                                <td>&nbsp;<?php echo $arrMeetingListByUser[$intCntr1]['TotalDuration']; ?></td>                                                                            
                                                            </tr>

                                                        <?php
                                                    }
                                                }  ?>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                             <?php } ?>
                            </tbody>
                        </table>
                        </div>
                    <?php }
                    else
                    {
                        echo "<div class='alert alert-info'>Data not available.</div>";
                    }
                    ?>
                    <div class="pagination pagination-centered" id="pageNavPositionContacts"></div> 
                </div>

                </div>
            </div>
            <!-- Middle content Area -->
        </div>
        <!-- Main content Area -->

        <!-- Footer content Area -->
        <?php include (CLIENT_INCLUDES_PATH . 'footer.php'); ?>
        <!-- Footer content Area -->

        <!-- java script  -->
        <?php include (CLIENT_INCLUDES_PATH . 'jsinclude.php'); ?>
        <!-- java script  -->

        <!-- java script  1-->
        <script src="<?php echo CLIENT_JS_PATH; ?>paging.js"></script>
        <!-- java script  1-->

        <script type="text/javascript">
            var userlist = '<?php echo $arrMeetingDuration ?>';
            if(userlist != '') 
            {
                var pagerContactList = new Pager('user-results', 10, 'con');
                pagerContactList.init();
                pagerContactList.showPageNav('pagerContactList', 'pageNavPositionContacts');
                pagerContactList.showPage(1);
            }
        </script>   
    </body>
</html>