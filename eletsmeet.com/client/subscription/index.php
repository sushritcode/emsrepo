<?php
require_once('../../includes/global.inc.php');
require_once('../includes/config.inc.php');
require_once(CLIENT_CLASSES_PATH . 'client_error.inc.php');
require_once(DBS_PATH . 'DataHelper.php');
require_once(DBS_PATH . 'objDataHelper.php');
require_once(CLIENT_INCLUDES_PATH . 'client_authfunc.inc.php');
$CLIENT_CONST_MODULE = 'clsubscription';
$CLIENT_CONST_PAGEID = 'Subscription Info';
require_once(CLIENT_INCLUDES_PATH . 'client_authorize.inc.php');
require_once(CLIENT_INCLUDES_PATH . 'client_db_function.inc.php');

try
{
    try
    {
        $arrLicenseDetails = getLicenseDetailsByClient($strSetClient_ID, $objDataHelper);
    }
    catch (Exception $a)
    {
        throw new Exception("index.php : getLicenseDetailsByClient : Error in populating List." . $a->getMessage(), 541);
    }
    //print_r($arrLicenseDetails);
    
    try
    {
        $arrPlanListByClient = getPlanDetailsByClientId($strSetClient_ID, $objDataHelper);
    }
    catch (Exception $a)
    {
        throw new Exception("index.php : getPlanDetailsByClientId : Error in populating List." . $a->getMessage(), 541);
    }
    //print_r($arrPlanListByClient);
    
    try
    {
        $totalLicenseAdded = getSumOfClientLicenseByType($strSetClient_ID, '0', $objDataHelper);
        $totalLicenseConsumed = getSumOfClientLicenseByType($strSetClient_ID, '1', $objDataHelper);
        $totalLicenseDisabled = getSumOfClientLicenseByType($strSetClient_ID, '2', $objDataHelper);
    }
    catch (Exception $a)
    {
        throw new Exception("index.php : getSumOfClientLicenseByType : Error in License." . $a->getMessage(), 541);
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
                    <div class="fL"><h3>License Information</h3></div>
                     <div class="fR span5">
                            <table width="50%" align="right" cellpadding="2">
                                     <tr> 
                                        <td width="5%" align="center" style="border-bottom: 0px;"><div style="background: green none repeat scroll 0% 0%; width: 15px; height: 15px;">&nbsp;</div></td>
                                        <td width="20%" align="left" style="border-bottom: 0px;"><b>Purchased</b></td> 
                                        <td width="5%" align="center" style="border-bottom: 0px;"><b>&nbsp;</b></td>
                                        <td width="5%" align="center" style="border-bottom: 0px;"><div style="background: red none repeat scroll 0% 0%; width: 15px; height: 15px;">&nbsp;</div></td>
                                        <td width="20%"   align="left" style="border-bottom: 0px;"><b>Consumed</b></td>
                                        <td width="5%" align="center" style="border-bottom: 0px;"><b>&nbsp;</b></td>
                                        <td width="5%" align="center" style="border-bottom: 0px;"><div style="background: blue none repeat scroll 0% 0%; width: 15px; height: 15px;">&nbsp;</div></td>
                                       <td width="20%" align="left" style="border-bottom: 0px;"><b>Disabled</b></td>
                                   </tr>
                            </table>
                     </div>
                </div>
                
                <div class="span12"><hr>
<!--                    <div>-->
                         <div style="overflow:auto; min-height: 200px;">
                            <table class="tblz01" width="100%" border="0">
                             <tr>
                                <td colspan="3">    
                                    <table class="tblz01" width="100%" id="license-results">
                                    <thead>
                                        <tr class="thead" >
                                            <td width="6%" style="border-top: 0px;">&nbsp;</td>
                                            <td width="47%" style="border-top: 0px;">Number Of License</td>
                                            <td width="47%" style="border-top: 0px;">License Date</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php   $arrOp = Array("green","red","blue"); ?>
                                         <?php for ($intCntr = 0; $intCntr < sizeof($arrLicenseDetails); $intCntr++) { ?>
                                        <tr>
                                            <td align="center"><b style="color:<?php echo $arrOp[$arrLicenseDetails[$intCntr]['operation_type']];?>">&FilledSmallSquare;</b></td>
                                            <td><?php echo $arrLicenseDetails[$intCntr]['no_of_license']; ?></td>
                                            <td><?php echo $arrLicenseDetails[$intCntr]['license_date']; ?></td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                    </table>
                                </td>
                             </tr>
                             <tr>
                                <td width="33%" align="left"><h4>Total Purchased Licenses&nbsp;:&nbsp;<span style="color: green;"><?php echo $totalLicenseAdded;?></span></h4></td>
                                <td width="33%" align="center"><h4>Total Active Licenses&nbsp;:&nbsp;<span style="color: red;"><?php echo $totalLicenseConsumed;?></span></h4></td>
                                <td width="34%" align="left"><h4>Total Deactive Licenses&nbsp;:&nbsp;<span style="color: blue;"><?php echo $totalLicenseDisabled;?></span></h4></td>       
                            </tr>
                            </table>
                            <div class="pagination pagination-centered" id="pageNavPositionLicense"></div> 
                        </div>
<!--                    </div>-->
                 </div>
                
<!--            </div>-->
            
<!--            <div class="span12 pB15"></div> -->
            
<!--            <div class="row">-->
                
                <div class="span12">
                    <div class="fL"><h3>Subscription Information</h3></div>
                </div>
                
                <div class="span12"><hr>
                    
                    <div style="overflow:auto; min-height: 250px;">
<!--                        <div style="overflow:auto;">-->
                        <table class="tblz01" width="100%" id="plan-results">
                            <thead>
                                <tr class="thead" >
                                    <tr class="thead">
                                        <td width="2%" style="border-top: 0px;">&nbsp;</td>
                                        <td style="border-top: 0px;">Plan Name</td>
                                        <td width="20%" style="border-top: 0px;">Start Date</td>
                                        <td width="20%" style="border-top: 0px;">End Date</td>
                                        <td width="20%" style="border-top: 0px;">Subscription Date</td>
                                    </tr>
                            </thead>
                            <tbody>
                                <?php for ($intCntr1 = 0; $intCntr1 < sizeof($arrPlanListByClient); $intCntr1++) { ?>
                                <tr>
                                    <td><b>&nbsp;<?php echo $intCntr1+1; ?></b></td>
                                    <td><?php echo $arrPlanListByClient[$intCntr1]['plan_name']; ?></td>
                                    <td><?php echo $arrPlanListByClient[$intCntr1]['subscription_start_date_gmt']; ?></td>
                                    <?php 
                                    $curDate = date("Y-m-d");
                                    $expDate = date("Y-m-d", strtotime($arrPlanListByClient[$intCntr1]['subscription_end_date_gmt']));

                                    $expDays = $arrPlanListByClient[$intCntr1]['diff_days'];

                                    if ($curDate > $expDate) 
                                   {
                                         echo "<td style='color: #f00;'>&nbsp;".$arrPlanListByClient[$intCntr1]['subscription_end_date_gmt']."</td>";
                                    }
                                    else
                                    {
                                        if ($expDays <= 5) 
                                        {
                                            echo "<td style='color: #E97B00;'>&nbsp;".$arrPlanListByClient[$intCntr1]['subscription_end_date_gmt']."</td>";
                                        }
                                        else
                                        {
                                            echo "<td style='color: green;'>&nbsp;".$arrPlanListByClient[$intCntr1]['subscription_end_date_gmt']."</td>";
                                        }
                                    }
                                   ?>
                                    <td><?php echo $arrPlanListByClient[0]['subscription_date']; ?></td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
<!--                        </div>-->
                        <div class="pagination pagination-centered" id="pageNavPositionPlan"></div> 
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
            var planlist = '<?php echo $arrPlanListByClient ?>';
            if(planlist != '') 
            {
                var pagerPlanList = new Pager('plan-results', 10, 'con');
                pagerPlanList.init();
                pagerPlanList.showPageNav('pagerPlanList', 'pageNavPositionPlan');
                pagerPlanList.showPage(1);
            };
        
            var licenselist = '<?php echo $arrLicenseDetails ?>';
            if(licenselist != '') 
            {
                var pagerLicenseList = new Pager('license-results', 10, 'con');
                pagerLicenseList.init();
                pagerLicenseList.showPageNav('pagerLicenseList', 'pageNavPositionLicense');
                pagerLicenseList.showPage(1);
            };
           </script>
    </body>
</html>
