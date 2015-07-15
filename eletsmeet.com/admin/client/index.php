<?php

require_once('../../includes/global.inc.php');
require_once('../includes/config.inc.php');
require_once(ADM_CLASSES_PATH.'admin_error.inc.php');
require_once(DBS_PATH.'DataHelper.php');
require_once(DBS_PATH.'objDataHelper.php');
$ADM_CONST_MODULE = 'client';
$ADM_CONST_PAGEID = 'Client List';
require_once(ADM_INCLUDES_PATH.'adm_authfunc.inc.php');
require_once(ADM_INCLUDES_PATH.'adm_authorize.inc.php');
require_once(ADM_INCLUDES_PATH.'adm_db_common_function.inc.php');
require_once(ADM_INCLUDES_PATH.'client_function.inc.php');

try
{
    $strPartnerName = '';
    $strClientName = '';
    
    if (isset($_POST['btnSubmit']))
    {
        $strPartnerName = trim($_POST['txtPartnerName']);
        $strClientName = trim($_POST['txtClientName']);
        
        if (strlen($strPartnerName) != 0 || strlen($strClientName) != 0)
        {
            try
            {
                $arrClientList = getClientListbyName($strPartnerName, $strClientName, $objDataHelper);
            }
            catch (Exception $a)
            {
                throw new Exception("index.php : getClientListbyName : Error in populating Client List by client name.".$a->getMessage(), 541);
            }
        }
        else
        {
            try
            {
                $arrClientList = getClientList($objDataHelper);
            }
            catch (Exception $a)
            {
                throw new Exception("index.php : getClientList : Error in populating Client List.".$a->getMessage(), 541);
            }
        }
    }
    else
    {
        try
        {
            $arrClientList = getClientList($objDataHelper);
        }
        catch (Exception $a)
        {
            throw new Exception("index.php : getClientList : Error in populating Client List.".$a->getMessage(), 541);
        }
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
        <?php include (ADM_INCLUDES_PATH.'head.php'); ?>
    </head>
    <!-- Head content Area -->

    <body>

        <!-- Navigation Bar, After Login Menu &  Product Logo -->
        <?php include (ADM_INCLUDES_PATH.'navigation.php'); ?>    
        <!-- Navigation Bar, After Login Menu &  Product Logo -->

        <!-- Main content Area -->
        <div class="container">
            <!-- Main hero unit for a primary marketing message or call to action -->

            <!-- Middle content Area -->
            <div class="row">
                
                    <div class="span12">
                        <div class="fL"><h3>Client List</h3></div>
                        <div class="fR"><a class="btn btn-primary" href="<?php echo $ADMIN_SITE_ROOT; ?>client/addclient.php"><i class='icon-white icon-plus-sign'></i>&nbsp;Add Client</a></div>
                    </div>
                
                    <div class="span12"><hr>
                    
                    <div class="admSearchBox">
                        <form name="clientlist" method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
                            <div class="frm-fields tBold"><span class="frmText">Partner Name&nbsp;:&nbsp;</span>
                                <input type="text" name="txtPartnerName" value="<?php echo $strPartnerName; ?>" maxlength="50" class="span2" id=""></div>
                            <div class="frm-fields tBold"><span class="frmText">Client Name&nbsp;:&nbsp;</span>
                                <input type="text" name="txtClientName" value="<?php echo $strClientName; ?>" maxlength="50" class="span2" id=""></div>
                            <button name="btnSubmit" class="btn btn-primary" type="submit"><i class='icon-white icon-search'></i>&nbsp;Search</button>
                        </form>
                    </div>

                    <div>
                        <?php
                        if (($strPartnerName != '' || $strClientName != '') && empty($arrClientList))
                        {
                            echo "<div class='alert alert-info'>Data not available.</div>";
                        }
                        else
                        {
                            if (!empty($arrClientList))
                            {
                                ?>
                                <div class="mB20"><h4>Total Clients&nbsp;:&nbsp;<?php echo count($arrClientList);?></h4></div>
                                <table class="tblz01" width="100%" id="client-results">

                                    <thead>
                                        <tr class="thead">
                                            <td width="28%">Partner Name</td>
                                            <td width="35%">Client Name</td>
                                            <td width="12%">Registration Date</td>
                                            <td width="9%" align="center">License Info</td>
                                            <td width="12%" align="center">Subscription Info</td>
                                            <td width="">Status</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        for ($intCntr = 0; $intCntr < sizeof($arrClientList); $intCntr++)
                                        {
                                            //print_r($arrClientList);
                                            $clntStatus = $arrClientList[$intCntr]['status'];
                                            switch ($clntStatus)
                                            {
                                                case "0" :
                                                    //$status = "Pending";
                                                    $status = "<span style='color:blue;'>Pending</span>";
                                                    break;
                                                case "1" :
                                                    //$status = "Active";
                                                    $status = "<span style='color:greenyellow;'>Active</span>";
                                                    break;
                                                case "2" :
                                                    //$status = "Deactive";
                                                    $status = "<span style='color:orange;'>Deactive</span>";
                                                    break;
                                                case "3" :
                                                    //$status = "Deleted";
                                                    $status = "<span style='color:red;'>Deleted</span>";
                                                    break;
                                                default:
                                                    //$status = "Error in client status";
                                                     $status = "<span style='color:red;'>Error !</span>";
                                            }
                                            ?>
                                            <tr>
                                                <td><?php echo $arrClientList[$intCntr]['partner_name']; ?></td>
                                                <td><a href="#" class="cPointer" onclick="clientDetails('<?php echo  $arrClientList[$intCntr]['client_id'] ;?>', '<?php echo  $arrClientList[$intCntr]['client_name'] ;?>')" ><?php echo $arrClientList[$intCntr]['client_name']; ?></a></td>
                                                <?php if ($arrClientList[$intCntr]['client_creation_dtm'] != NULL)
                                                { ?>
                                                    <td><?php echo $arrClientList[$intCntr]['client_creation_dtm']; ?></td>
                                                <?php }
                                                else
                                                { ?>
                                                    <td><?php echo '---' ?></td>
                                                <?php } ?>
                                                 <td align="center"><a class="cPointer"  onclick="licenseDetails('<?php echo  $arrClientList[$intCntr]['client_id'] ;?>', '<?php echo  $arrClientList[$intCntr]['client_name'] ;?>')"><img src="<?php echo IMG_PATH; ?>license.png"  width="20" height="20" alt="License Info" title="License Info"></a></td>
                                                 <td align="center"><a class="cPointer"  onclick="subscriptionDetails('<?php echo  $arrClientList[$intCntr]['client_id'] ;?>', '<?php echo  $arrClientList[$intCntr]['client_name'] ;?>')"><img src="<?php echo IMG_PATH; ?>icon-info-blue.png"  width="20" height="20" alt="Subscription Info" title="Subscription Info"></a></td>
                                                 <td><?php echo $status; ?></td>
                                            </tr>
                                            <?php
                                        }
                                    }
                                    else
                                    {
                                        echo "<div class='alert alert-info'>Data not available.</div>";
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                        <div class="pagination pagination-centered" id="pageNavPositionContacts"></div> 
                    </div>
            <div id="layer"></div>
            <!-- Subscription Details Box -->
            <div id ="popupS" class="user-details" style="display:none">
                <div id="SubDetails"></div>
            </div>
            <!-- Subscription Details Box -->
                </div>
            </div>
            <!-- Middle content Area -->
        </div>
        <!-- Main content Area -->

        <!-- Footer content Area -->
<?php include (ADM_INCLUDES_PATH.'footer.php'); ?>
        <!-- Footer content Area -->

        <!-- java script  -->
        <?php include (ADM_INCLUDES_PATH.'jsinclude.php'); ?>
        <!-- java script  -->

        <!-- java script  -->
        <script src="<?php echo ADM_JS_PATH; ?>paging.js"></script>
        <script src="<?php echo ADM_JS_PATH; ?>show-popup.js"></script>
        <script type="text/javascript">
            var clientlist = '<?php echo $arrClientList; ?>';
            if(clientlist != '')
            {
                var pagerContactList = new Pager('client-results', 10, 'con');
                pagerContactList.init();
                pagerContactList.showPageNav('pagerContactList', 'pageNavPositionContacts');
                pagerContactList.showPage(1);
            }
        </script>
        <script type="text/javascript">
            function clientDetails(clId,clname ) {
                showPopup('#popupS', '#layer');
                $.ajax({
                    type: "GET",
                    url: "clientdetails.php",
                    cache: false,
                    data: "txtClientId="+clId+"&txtClientName="+clname,
                    loading: $(".loading").html(""),
                    success:    function(html) {
                        $("#SubDetails").html(html);
                    }
                }); }
            function licenseDetails(clId,clname ) {
                showPopup('#popupS', '#layer');
                $.ajax({
                    type: "GET",
                    url: "clientlicense.php",
                    cache: false,
                    data: "txtClientId="+clId+"&txtClientName="+clname,
                    loading: $(".loading").html(""),
                    success:    function(html) {
                        $("#SubDetails").html(html);
                    }
                }); }
             function subscriptionDetails(clId,clname ) {
                showPopup('#popupS', '#layer');
                $.ajax({
                    type: "GET",
                    url: "clientsubscription.php",
                    cache: false,
                    data: "txtClientId="+clId+"&txtClientName="+clname,
                    loading: $(".loading").html(""),
                    success:    function(html) {
                        $("#SubDetails").html(html);
                    }
                }); }
        </script>
        <!-- java script  -->
    </body>
</html>