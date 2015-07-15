<?php
require_once('../../includes/global.inc.php');
require_once('../includes/config.inc.php');
require_once(ADM_CLASSES_PATH . 'admin_error.inc.php');
require_once(DBS_PATH . 'DataHelper.php');
require_once(DBS_PATH . 'objDataHelper.php');
$ADM_CONST_MODULE = 'partner';
$ADM_CONST_PAGEID = 'Partner List';
require_once(ADM_INCLUDES_PATH . 'adm_authfunc.inc.php');
require_once(ADM_INCLUDES_PATH . 'adm_authorize.inc.php');
require_once(ADM_INCLUDES_PATH . 'adm_db_common_function.inc.php');

try
{
    try
    {
        $arrPartnerList = getPartnerList($objDataHelper);
    }
    catch (Exception $a)
    {
        throw new Exception("index.php : getPartnerList : Error in populating Partner List." . $a->getMessage(), 541);
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
                    <div class="fL"><h3>Partner List</h3></div>
                    <div class="fR"><a class="btn btn-primary" href="<?php echo $ADMIN_SITE_ROOT; ?>partner/addpartner.php"><i class='icon-white icon-plus-sign'></i>&nbsp;Add Partner</a></div>
                </div>
                              
                <div class="span12"><hr>
                    
                    <div>
                        <?php
                        if (empty($arrPartnerList))
                        {
                            echo "<div class='alert alert-info'>Data not available.</div>";
                        }
                        else
                        {
                            ?>
                        
                         <div class="mB20"><h4>Total Partners&nbsp;:&nbsp;<?php echo count($arrPartnerList);?></h4></div>
                            <table class="tblz01" width="100%" id="partner-results">

                                <thead>
                                    <tr class="thead">
                                        <td width="30%">Partner Name</td>
                                        <td width="30%">Email Address</td>
                                        <td width="30%">Registration Date</td>
                                        <td width="10%">Status</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    for ($intCntr = 0; $intCntr < sizeof($arrPartnerList); $intCntr++)
                                    {
                                        $prtnrStatus = $arrPartnerList[$intCntr]['status'];
                                        switch ($prtnrStatus)
                                        {
                                            case "0" :
                                                $status = "<span style='color:blue;'>Pending</span>";
                                                break;
                                            case "1" :
                                                $status = "<span style='color:greenyellow;'>Active</span>";
                                                break;
                                            case "2" :
                                                $status = "<span style='color:orange;'>Deactive</span>";
                                                break;
                                            case "3" :
                                                $status = "<span style='color:red;'>Deleted</span>";
                                                break;
                                            default:
                                                 $status = "<span style='color:red;'>Error !</span>";
                                        }
                                        ?>
                                        <tr>
                                            <td style="margin: 5px 0px 5px 0px;"><?php echo $arrPartnerList[$intCntr]['partner_name'] ?></td>
                                            <td><?php echo $arrPartnerList[$intCntr]['email_address'] ?></td>
                                            <td><?php echo $arrPartnerList[$intCntr]['partner_creation_dtm'] ?></td>
                                            <td><?php echo $status ?></td>
                                        </tr>
                                        <?php
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
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
        <?php include (ADM_INCLUDES_PATH.'jsinclude.php'); ?>
        <!-- java script  -->

        <!-- java script  1-->
        <script src="<?php echo ADM_JS_PATH; ?>paging.js"></script>
        
        <script type="text/javascript">
            var partnerlist = '<?php echo $arrPartnerList; ?>';
            if(partnerlist != '')
            {
                var pagerContactList = new Pager('partner-results', 10, 'con');
                pagerContactList.init();
                pagerContactList.showPageNav('pagerContactList', 'pageNavPositionContacts');
                pagerContactList.showPage(1);
            }
        </script>
        <!-- java script  1-->
    </body>
</html>