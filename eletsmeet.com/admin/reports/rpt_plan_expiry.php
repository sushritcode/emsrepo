<?php
require_once('../../includes/global.inc.php');
require_once('../includes/config.inc.php');
require_once(ADM_CLASSES_PATH . 'admin_error.inc.php');
require_once(DBS_PATH . 'DataHelper.php');
require_once(DBS_PATH . 'objDataHelper.php');
$ADM_CONST_MODULE = 'reports';
$ADM_CONST_PAGEID = 'plan_expiry';
require_once(ADM_INCLUDES_PATH . 'adm_authfunc.inc.php');
require_once(ADM_INCLUDES_PATH . 'adm_authorize.inc.php');
require_once(ADM_INCLUDES_PATH . 'report_function.inc.php');

try
{
        if (isset($strAdminPartner_Id))
        {
            $partner_id = $strAdminPartner_Id;
        }
        else
        {
            $partner_id = "";
        }
        
         if (isset($strAdminClientl_Id))
        {
            $client_id = $strAdminClientl_Id;
        }
        else
        {
            $client_id = "";
        }
        
        try
        {
            $arrClientList = getClientListByPartner($partner_id, $objDataHelper);
        }
        catch (Exception $a)
        {
            throw new Exception("index.php : getLicenseDetailsByClient : Error in populating List." . $a->getMessage(), 541);
        }
         //print_r($arrClientList); 

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
        <?php include (ADM_INCLUDES_PATH . 'head.php'); ?>
    </head>
    <!-- Head content Area -->

    <body>

        <!-- Navigation Bar, After Login Menu &  Product Logo -->
        <?php include (ADM_INCLUDES_PATH . 'navigation.php'); ?>    
        <!-- Navigation Bar, After Login Menu &  Product Logo -->

        <!-- Main content Area -->
        <div class="container">
            <!-- Main hero unit for a primary marketing message or call to action -->

            <!-- Middle content Area -->
            <div class="row">
                
                <div class="span12">
                     <div class="fL"><h3>Plan Expiry</h3></div>
                </div>

                <div class="span12"><hr>
                    
                                <div>
                                <?php
                                if (!empty($arrClientList))
                                {
                                ?>
<!--                             <div class="s22 b mB20"><span class="color_0088CC">Total Licenses</span>&nbsp;:&nbsp;<?php echo $totalLicense;?></div>-->
                                    <div style="overflow:auto;">
                                    <table class="tblz01" width="100%" id="client-results">
                                        <thead>
                                            <tr class="thead" >
                                                <td width="2%" style="border-top: 0px;">&nbsp;</td>
                                                <td width="44%" style="border-top: 0px; color: #f5d328;">&nbsp;Partner Name</td>
                                                <td width="44%" style="border-top: 0px; color: #f5d328;">&nbsp;Client  Name</td>
                                                <td width="10%" style="border-top: 0px; color: #f5d328;">&nbsp;Client Status</td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                            $arrClientStatus[0]= "<span style='color:blue;'>Pending</span>";
                                            $arrClientStatus[1]= "<span style='color:greenyellow;'>Active</span>";
                                            $arrClientStatus[2]= "<span style='color:orange;'>Deactive</span>";
                                            $arrClientStatus[3]= "<span style='color:red;'>Deleted</span>";
                                                
                                        for ($intCntr = 0; $intCntr < sizeof($arrClientList); $intCntr++)
                                        {  
                                           $strClient_Id = $arrClientList[$intCntr]['client_id'];
                                            
                                            try
                                            {
                                                $arrPlanInformation = getClientPlanInformation($strClient_Id, $objDataHelper);
                                            }
                                            catch (Exception $a)
                                            {
                                                throw new Exception("index.php : getPlanInformation : Error in populating List.".$a->getMessage(), 541);
                                            }
                                        ?>
                                            <tr style="background-color: #333333;">
                                                <td>&nbsp;</td>
                                                <td><?php echo $arrClientList[$intCntr]['partner_name']; ?></td>
                                                <td><?php echo $arrClientList[$intCntr]['client_name']; ?></td>
                                                <td>&nbsp;<?php echo $arrClientStatus[$arrClientList[$intCntr]['status']]; ?></td>       
                                            </tr>
                                            <tr>
                                                <td colspan="4">
                                            <?php if (!empty($arrPlanInformation)) { ?>
                                            <tr>
                                                <td colspan=4" style="border-bottom: 0px solid;">
                                                    <table  class="tblz01" width="100%" border="0">
                                                        <thead>
                                                            <tr style="font-weight: bold;">
                                                                <td width="2%">&nbsp;</td>
                                                                <td width="58%">&nbsp;Plan Name</td>
                                                                <td width="20%">&nbsp;Plan Start Date</td>
                                                                <td width="20%">&nbsp;Plan End Date</td>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                        <?php 
                                                            for ($intCntr1 = 0; $intCntr1 < sizeof($arrPlanInformation); $intCntr1++) { ?> 
                                                                <tr>
                                                                    <td>&nbsp;<?php echo $intCntr1+1; ?></td>
                                                                    <td>&nbsp;<?php echo $arrPlanInformation[$intCntr1]['plan_name']; ?></td>
                                                                    <td>&nbsp;<?php echo $arrPlanInformation[$intCntr1]['subscription_start_date_gmt']; ?></td>
                                                                      <?php 
                                                                        $curDate = date("Y-m-d");
                                                                        $expDate = date("Y-m-d", strtotime($arrPlanInformation[$intCntr1]['subscription_end_date_gmt']));

                                                                        $expDays = $arrPlanInformation[$intCntr1]['diff_days'];
                                                                      
                                                                        if ($curDate > $expDate) 
                                                                       {
                                                                             echo "<td style='color: red;'>&nbsp;".$arrPlanInformation[$intCntr1]['subscription_end_date_gmt']."</td>";
                                                                        }
                                                                        else
                                                                        {
                                                                            if ($expDays <= 15) 
                                                                            {
                                                                                echo "<td style='color: orange;'>&nbsp;".$arrPlanInformation[$intCntr1]['subscription_end_date_gmt']."</td>";
                                                                            }
                                                                            else
                                                                            {
                                                                                echo "<td style='color: greenyellow;'>&nbsp;".$arrPlanInformation[$intCntr1]['subscription_end_date_gmt']."</td>";
                                                                            }
                                                                        }
                                                                       ?>
                                                                </tr>
                                                        <?php  }  ?>
                                                        </tbody>
                                                    </table>
                                                </td>
                                            </tr>
                                            <?php }else{ ?>
                                            <table class="tblz01" width="100%" border="0">
                                                <tr><td><div class='alert alert-info'>Data not available.</div></td></tr>
                                            </table>
                                         <?php } ?>
                                         </td></tr>
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
        <?php include (ADM_INCLUDES_PATH . 'footer.php'); ?>
        <!-- Footer content Area -->

        <!-- java script  -->
        <?php include (ADM_INCLUDES_PATH . 'jsinclude.php'); ?>
        <!-- java script  -->

        <!-- java script  1-->
        <script src="<?php echo ADM_JS_PATH; ?>paging.js"></script>
        <!-- java script  1-->

        <script type="text/javascript">
            var userlist = '<?php echo $arrClientList ?>';
            if(userlist != '') 
            {
                var pagerContactList = new Pager('client-results', 10, 'con');
                pagerContactList.init();
                pagerContactList.showPageNav('pagerContactList', 'pageNavPositionContacts');
                pagerContactList.showPage(1);
            }
        </script>   
    </body>
</html>

