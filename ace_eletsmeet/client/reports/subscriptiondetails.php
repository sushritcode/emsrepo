<?php
require_once('../../includes/global.inc.php');
require_once('../includes/config.inc.php');
require_once(CLIENT_CLASSES_PATH . 'client_error.inc.php');
require_once(CLIENT_DBS_PATH . 'DataHelper.php');
require_once(CLIENT_DBS_PATH . 'objDataHelper.php');
require_once(CLIENT_INCLUDES_PATH . 'client_authfunc.inc.php');
$CLIENT_CONST_MODULE = 'cl_reports';
$CLIENT_CONST_PAGEID = 'Meeting Report_1';
require_once(CLIENT_INCLUDES_PATH . 'client_authorize.inc.php');
require_once(CLIENT_INCLUDES_PATH . 'client_db_function.inc.php');
require_once(CLIENT_INCLUDES_PATH . 'client_reports_function.inc.php');
        
$strSubOrderId = trim($_REQUEST['OrdId']);
$strSubPlanId = trim($_REQUEST['PlId']);

try
{
    $arrSubAssignDtls = getSubscriptionAssignInfo($strSubOrderId, $objDataHelper);
}
catch (Exception $e)
{
    throw new Exception("index.php : getScheduleDetailsById Failed : " . $e->getMessage(), 1126);
}

//echo "<pre>";
//print_r($arrSubAssignDtls);
//echo "<pre>"; 

try
{
    $arrPlanDetails = getPlanDetailsByPlanId($strSubPlanId, $objDataHelper);
}
catch (Exception $e)
{
    throw new Exception("index.php : getScheduleDetailsById Failed : " . $e->getMessage(), 1126);
}
$Plan_Name = trim($arrPlanDetails[0]['plan_name']);
?>
<div class="well">
    <h4 class="smaller"><?php echo $Plan_Name; ?></h4>
    <hr>       
    <div>
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th class="center">*</th>
                    <th>Name</th>
                    <th>Subscription Start Date</th>
                    <th>Subscription End Date</th>
                    <th>Subscription Status</th>
                </tr>
            </thead>
            <tbody>
                <?php
                for ($intCntr = 0; $intCntr < sizeof($arrSubAssignDtls); $intCntr++)
                {
                    switch ($arrSubAssignDtls[$intCntr]['subscription_status'])
                    {
                        case "1" :
                            $strSubStatus = "<span class=\"label label-sm label-warning\">Trial</span>";
                            break;
                        case "2" :
                            $strSubStatus = "<span class=\"label label-sm label-success\">Subscribe</span>";
                            break;
                        case "3" :
                            $strSubStatus = "<span class=\"label label-sm label-danger\">Expired</span>";
                            break;
                        default:
                            $strSubStatus = "<span class=\"label label-sm label-grey\">Requested</span>";
                    }
                    ?>
                    <tr>
                        <td class="center"> <i class="ace-icon fa fa-user"></i> </td>
                        <td><?php echo trim($arrSubAssignDtls[$intCntr]['user_name']); ?></td>
                        <td><?php echo trim($arrSubAssignDtls[$intCntr]['subscription_start_date_local']);  ?></td>
                        <td><?php echo trim($arrSubAssignDtls[$intCntr]['subscription_end_date_local']);  ?></td>
                        <td><?php echo $strSubStatus; ?></td>
                    </tr>
                    <?php } ?>
            </tbody>
        </table>
    </div>
</div>
