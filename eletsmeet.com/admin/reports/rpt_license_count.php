<?php
require_once('../../includes/global.inc.php');
require_once('../includes/config.inc.php');
require_once(ADM_CLASSES_PATH . 'admin_error.inc.php');
require_once(DBS_PATH . 'DataHelper.php');
require_once(DBS_PATH . 'objDataHelper.php');
$ADM_CONST_MODULE = 'reports';
$ADM_CONST_PAGEID = 'license_count';
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
                    <div class="fL"><h3>License Count</h3></div>
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
                                             
                <div class="span12"> <hr>
                    <div>
                        <?php
                        if (!empty($arrClientList))
                        {
                        ?>
                            <table class="tblz01" width="100%" id="license-results" border="0">
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
                                            
                                 for ($intCntr = 0; $intCntr < sizeof($arrClientList); $intCntr++) { ?>
                                <tr style="background-color: #333333;">
                                    <td>&nbsp;</td>
                                    <td><?php echo $arrClientList[$intCntr]['partner_name']; ?></td>
                                    <td><?php echo $arrClientList[$intCntr]['client_name']; ?></td>
                                    <td>&nbsp;<?php echo $arrClientStatus[$arrClientList[$intCntr]['status']]; ?></td>       
                                </tr>
                                <?php
                                $strClient_id = $arrClientList[$intCntr]['client_id'];
                                 try
                                {
                                    $arrLicenseDetails = getLicenseDetailsByClient($strClient_id, $objDataHelper);
                                }
                                catch (Exception $a)
                                {
                                    throw new Exception("index.php : getLicenseDetailsByClient : Error in populating List." . $a->getMessage(), 541);
                                }
                                
                                try
                                {
                                    $totalLicenseAdded = getSumOfClientLicenseByType($strClient_id, '0', $objDataHelper);
                                    $totalLicenseConsumed = getSumOfClientLicenseByType($strClient_id, '1', $objDataHelper);
                                    $totalLicenseDisabled = getSumOfClientLicenseByType($strClient_id, '2', $objDataHelper);
                                }
                                catch (Exception $a)
                                {
                                    throw new Exception("index.php : getSumOfClientLicenseByType : Error in License." . $a->getMessage(), 541);
                                }
                                ?>
                                <tr>
                                    <td colspan="4">
                                    <?php if (!empty($arrLicenseDetails)) { ?>
                                    <table class="tblz01" width="100%" border="0">
                                        <tr>
                                            <td colspan="3">    
                                                <table class="tblz01" width="100%">
                                                <thead>
                                                    <tr style="font-weight: bold;">
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
                                    <?php }else{ ?>
                                    <table class="tblz01" width="100%" border="0">
                                        <tr><td><div class='alert alert-info'>Data not available.</div></td></tr>
                                    </table>
                                    <?php } ?>
                                     </td>
                                </tr>
                                <?php } ?>
                            </tbody>
                            </table>
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
                var pagerContactList = new Pager('license-results', 10, 'con');
                pagerContactList.init();
                pagerContactList.showPageNav('pagerContactList', 'pageNavPositionContacts');
                pagerContactList.showPage(1);
            }
        </script>   
    </body>
</html>

