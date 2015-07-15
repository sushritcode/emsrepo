<?php
require_once('../../includes/global.inc.php');
require_once('../includes/config.inc.php');
require_once(CLIENT_CLASSES_PATH . 'client_error.inc.php');
require_once(DBS_PATH . 'DataHelper.php');
require_once(DBS_PATH . 'objDataHelper.php');
require_once(CLIENT_INCLUDES_PATH . 'client_authfunc.inc.php');
$CLIENT_CONST_MODULE = 'cluser';
$CLIENT_CONST_PAGEID = 'User List';
require_once(CLIENT_INCLUDES_PATH . 'client_authorize.inc.php');
require_once(CLIENT_INCLUDES_PATH . 'client_db_function.inc.php');

try
{
    try
    {
        $arrUserList = getUserDetailsByClient($strSetPartner_ID, $strSetClient_ID, $objDataHelper);
    }
    catch (Exception $a)
    {
        throw new Exception("index.php : getUserDetailsByClient : Error in populating List." . $a->getMessage(), 541);
    }
    $strTotalUserCount = count($arrUserList);

     $strOptLicenseType =0;  //License Purchased
     try
    {
        $arrTotalLicense = getSumOfClientLicenseByType($strSetClient_ID, $strOptLicenseType,$objDataHelper);
    }
    catch (Exception $a)
    {
        throw new Exception("index.php : getPlanDetails : Error in populating Plan Details." . $a->getMessage(), 541);
    }
    
    $strTotalLicense = $arrTotalLicense[0]['TotalLicense'];
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
                    <div class="fL"><h3>User List</h3></div>
                    <div class="fR"><a class="btn btn-primary" href="<?php echo $CLIENT_SITE_ROOT; ?>user/adduser.php"><i class='icon-white icon-plus-sign'></i>&nbsp;Add User</a></div>
                </div>

                <div class="span12"><hr>

                    <div>
                    <?php
                    if (empty($arrUserList))
                    {
                        echo "<div class='alert alert-info'>Data not available.</div>";
                    }
                    else
                    { ?>    
                   
                        <div class="mB20"><h4>Total Users&nbsp;:&nbsp;<?php echo $strTotalUserCount;?></h4></div>
                        <div style="overflow:auto; min-height: 300px;">
                                <table class="tblz01" width="100%" id="user-results">
                                    <thead>
                                        <tr class="thead">
                                            <td width="10%">Nick Name</td>
                                            <td width="10%">Name</td>
                                            <td width="10%">Email Address</td>
                                            <td width="10%">Country Name</td>
                                            <td width="9%">Mobile</td>
                                            <td width="4%">Status</td>
                                            <td width="10%" align="center">Subscription Info</td>
                                            <td width="5%" align="center">Action</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php for ($intCntr = 0; $intCntr < sizeof($arrUserList); $intCntr++) {
                                            $userId = $arrUserList[$intCntr]['user_id'];
                                            $userStatus = $arrUserList[$intCntr]['status'];
                                            $userAdmin = $arrUserList[$intCntr]['is_admin'];
                                            $firstName = $arrUserList[$intCntr]['first_name'];
                                            $lastName = $arrUserList[$intCntr]['last_name'];
                                            $emailId = $arrUserList[$intCntr]['email_address'];

                                            switch ($userStatus)
                                            {
                                                case "0" :
                                                    $status = "Pending";
                                                    break;
                                                case "1" :
                                                    $status = "Active";
                                                    break;
                                                case "2" :
                                                    $status = "Deactive";
                                                    break;
                                                case "3" :
                                                    $status = "Deleted";
                                                    break;
                                                default:
                                                    $status = "--";
                                            }

                                            switch ($userAdmin)
                                            {
                                                case "0" :
                                                    $isAdmin = "No";
                                                    break;
                                                case "1" :
                                                    $isAdmin = "yes";
                                                    break;
                                                default:
                                                    $isAdmin = "--";
                                            }
                                            ?>
                                            <tr>
                                                <td><?php echo $arrUserList[$intCntr]['nick_name']; ?></td>
                                                <td><?php echo $arrUserList[$intCntr]['last_name']; ?> &nbsp;<?php echo $arrUserList[$intCntr]['first_name']; ?></td>
                                                <td class="view"><a class="cPointer cBk"><?php echo $arrUserList[$intCntr]['email_address']; ?></a>
                                                    <?php
                                                    echo "<div class='view'><span style=\"display: none;\" class=\"userid\">" . $arrUserList[$intCntr][user_id] . "</span>
                                                    <span style=\"display: none;\" class=\"nickname\">" . $arrUserList[$intCntr][nick_name] . "</span>
                                                    <span style=\"display: none;\" class=\"firstname\">" . $arrUserList[$intCntr][first_name] . "</span>   
                                                    <span style=\"display: none;\" class=\"lastname\">" . $arrUserList[$intCntr][last_name] . "</span>
                                                    <span style=\"display: none;\" class=\"emailaddress\">" . $arrUserList[$intCntr][email_address] . "</span>
                                                    <span style=\"display: none;\" class=\"countryname\">" . $arrUserList[$intCntr][country_name] . "</span>
                                                    <span style=\"display: none;\" class=\"timezones\">" . $arrUserList[$intCntr][timezones] . "</span>
                                                    <span style=\"display: none;\" class=\"gmt\">" . $arrUserList[$intCntr][gmt] . "</span>
                                                    <span style=\"display: none;\" class=\"iddcode\">" . $arrUserList[$intCntr][idd_code] . "</span>
                                                    <span style=\"display: none;\" class=\"mobile\">" . $arrUserList[$intCntr][mobile_number] . "</span>
                                                    <span style=\"display: none;\" class=\"registrationdtm\">" . $arrUserList[$intCntr][registration_dtm] . "</span>
                                                    </div>"
                                                    ?>
                                                </td>
                                                <td><?php echo $arrUserList[$intCntr]['country_name'] ?></td>
                                                <?php if ($arrUserList[$intCntr]['idd_code'] != NULL && $arrUserList[$intCntr]['mobile_number'] != NULL)
                                                {
                                                    ?>
                                                <td><?php echo "+" . $arrUserList[$intCntr]['idd_code'] . "-" . $arrUserList[$intCntr]['mobile_number']; ?></td>
                                                <?php }
                                                else
                                                { ?>
                                                <td><?php echo '--' ?></td>
                                                <?php } ?>
                                                <td><?php echo $status ?></td>
                                                <td align="center"><a class="cPointer"  onclick="subscriptionDetails('<?php echo $userId; ?>')"><img src="<?php echo CLIENT_IMG_PATH; ?>icon-info-blue.png"  width="20" height="20" alt="Subscription Info" title="Subscription Info"></a></td>
                                                <td align="center">
                                                    <form name="userform_<?php echo $userId ?>" style="margin: 5px 0px 5px 0px;" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                                                        <?php if ($status == 'Active')
                                                        { ?>
                                                            <span style="cursor:pointer;" onclick='javascript:editUserStatus("<?php echo $userId; ?>", "<?php echo $firstName; ?>", "<?php echo $lastName; ?>", "<?php echo $emailId; ?>", "disable")'><img src="<?php echo CLIENT_IMG_PATH; ?>icon-disable.png"  width="20" height="20" alt="Disable" title="Disable"></span>
                                                        <?php }
                                                        else
                                                        { ?>
                                                            <span style="cursor:pointer;" onclick='javascript:editUserStatus("<?php echo $userId; ?>", "<?php echo $firstName; ?>", "<?php echo $lastName; ?>", "<?php echo $emailId; ?>", "enable")'><img src="<?php echo CLIENT_IMG_PATH; ?>icon-enable.png"  width="20" height="20" alt="Enable" title="Enable"></span>
                                                        <?php } ?>
                                                    </form>
                                                </td>
                                            </tr>
                                            <?php } ?>
                                    </tbody>
                                </table>
                                <form name="frmUserList" method="post">
                                    <input type='hidden' name='txtAction'>
                                    <input type='hidden' name='txtUserId'>
                                    <input type='hidden' name='txtFirstName'>
                                    <input type='hidden' name='txtLastName'>
                                    <input type='hidden' name='txtEmailId'>
                                </form>
                        </div>
                        <div class="pagination pagination-centered" id="pageNavPositionContacts"></div> 
                    <?php } ?>
                    </div>
                </div>
            </div>
            
            <div id="layer"></div>
            <!-- Subscription Details Box -->
            <div id ="popupS" class="user-details" style="display:none">
                <div id="SubDetails"></div>
            </div>
            <!-- Subscription Details Box -->
            
            <!-- User Details Box -->
            <div id ="popup" class="user-details" style="display:none">
                <img class="fR" id="close" border='0' title='Close' alt='Close' src='<?php echo CLIENT_IMG_PATH; ?>close_black.png'>
                <h3>User Details</h3><br/>
                <table  width="100%">
                    <tr><td class="tBold" width="50%">Nick Name<span class="colon">:&nbsp;</span></td><td id="nick_name" width="50%"></td></tr>
                    <tr><td class="pB20"></td></tr>
                    <tr><td class="tBold">Email Address<span class="colon">:&nbsp;</span></td><td id="email_id"></td></tr>
                    <tr><td class="pB20"></td></tr>
                    <tr><td class="tBold">Timezone<span class="colon">:&nbsp;</span></td><td id="timezone"></td></tr>
                    <tr><td class="pB20"></td></tr>
                    <tr><td class="tBold">Registration DateTime<span class="colon">:&nbsp;</span></td><td id="registration_dtm"></td></tr>
                </table>                    
            </div>
            <!-- User Details Box -->

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
        <script src="<?php echo CLIENT_JS_PATH; ?>user.js"></script>
        <script src="<?php echo CLIENT_JS_PATH; ?>show-popup.js"></script>
        <!-- java script  1-->

        <script type="text/javascript">
                        var userlist = '<?php echo $arrUserList ?>';
                        if (userlist != '')
                        {
                            var pagerContactList = new Pager('user-results', 10, 'con');
                            pagerContactList.init();
                            pagerContactList.showPageNav('pagerContactList', 'pageNavPositionContacts');
                            pagerContactList.showPage(1);
                        }
        </script>
        
        <script type="text/javascript">
            function subscriptionDetails(uId) {
                 showPopup('#popupS', '#layer');
                $.ajax({
                    type: "GET",
                    url: "userdetails.php",
                    cache: false,
                    data: "txtUserId="+uId,
                    loading: $(".loading").html(""),
                    success:    function(html) {
                        $("#SubDetails").html(html);
                    }
                });
            }
        </script>
        
        <script type='text/javascript'>
            $(document).ready(function () {
                $('.view').click(function () {
                    var userid = $(this).find('.userid').html();
                    var nickname = $(this).find('.nickname').html();
                    var firstname = $(this).find('.firstname').html();
                    var lastname = $(this).find('.lastname').html();
                    var emailid = $(this).find('.emailaddress').html();
                    var countryname = $(this).find('.countryname').html();
                    var timezone = $(this).find('.timezones').html();
                    var gmt = $(this).find('.gmt').html();
                    var iddcode = $(this).find('.iddcode').html();
                    var mobile = $(this).find('.mobile').html();
                    var registrationdtm = $(this).find('.registrationdtm').html();
                    var partnername = $(this).find('.partnername').html();
                    var clientname = $(this).find('.clientname').html();

                    if (nickname == '')
                        nickname = '---';
                    if (firstname == '')
                        firstname = '---';
                    if (lastname == '')
                        lastname = '---';
                    if (emailid == '')
                        emailid = '---';
                    if (countryname == '')
                        countryname = '---';
                    if (timezone == '')
                        timezone = '---';
                    if (iddcode == '')
                        iddcode = '---';
                    if (mobile == '')
                        mobile = '---';
                    if (registrationdtm == '')
                        registrationdtm = '---';

                    $('#txtUserId').val(userid);
                    $('#nick_name').text(nickname);
                    $('#first_name').text(firstname);
                    $('#last_name').text(lastname);
                    $('#email_id').text(emailid);
                    $('#country_name').text(countryname);
                    $('#timezone').text(timezone + " " + gmt);
                    $('#mobile').text("+" + iddcode + "-" + mobile);
                    $('#registration_dtm').text(registrationdtm);
                    $('#partner_name').text(partnername);
                    $('#client_name').text(clientname);

                    showPopup('#popup', '#layer');
                });

                $('#close').click(function () {
                    hidePopup('#popup', '#layer');
                    $('#success-msg').css({"display": "none"});
                    $('#txtPassword').val('');
                    $("#txtPlanName").val('---');
                });
            });
        </script>   
    </body>
</html>
