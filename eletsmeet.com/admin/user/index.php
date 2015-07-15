<?php
require_once('../../includes/global.inc.php');
require_once('../includes/config.inc.php');
require_once(ADM_CLASSES_PATH . 'admin_error.inc.php');
require_once(DBS_PATH . 'DataHelper.php');
require_once(DBS_PATH . 'objDataHelper.php');
$ADM_CONST_MODULE = 'user';
$ADM_CONST_PAGEID = 'User List';
require_once(ADM_INCLUDES_PATH . 'adm_authfunc.inc.php');
require_once(ADM_INCLUDES_PATH . 'adm_authorize.inc.php');
require_once(ADM_INCLUDES_PATH . 'user_function.inc.php');
require_once(ADM_INCLUDES_PATH . 'adm_db_common_function.inc.php');

try
{
    $strPartnerName = '';
    $strClientName = '';
    $strFirstName = '';
    
    if (isset($_POST['btnSubmit']))
    {
        $strPartnerName = trim($_POST['txtPartnerName']);
        $strClientName = trim($_POST['txtClientName']);
        $strFirstName = trim($_POST['txtFirstName']);
        
        if (strlen($strPartnerName) != 0 || strlen($strClientName) != 0 || strlen($strFirstName) != 0)
        {
            try
            {
                $arrUserList = getUserDetailsByClientName($strPartnerName, $strClientName, $strFirstName, $objDataHelper);
            }
            catch (Exception $a)
            {
                throw new Exception("index.php : getUserListbyName : Error in populating Client List by client name." . $a->getMessage(), 541);
            }
        }
        else
        {
            try
            {
                $arrUserList = getUserList($objDataHelper);
            }
            catch (Exception $a)
            {
                throw new Exception("index.php : getUserList : Error in populating User List." . $a->getMessage(), 541);
            }
        }
    }
    else
    {
        try
        {
            $arrUserList = getUserList($objDataHelper);
        }
        catch (Exception $a)
        {
            throw new Exception("index.php : getUserList : Error in populating User List." . $a->getMessage(), 541);
        }
        
    }

    try
    {
        $arrPlanDetails = getPlanDetails($objDataHelper);
    }
    catch (Exception $a)
    {
        throw new Exception("index.php : getPlanDetails : Error in populating Plan Details." . $a->getMessage(), 541);
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
                    <div class="fL"><h3>User List</h3></div>
                    <div class="fR"><a class="btn btn-primary" href="<?php echo $ADMIN_SITE_ROOT; ?>user/adduser.php"><i class='icon-white icon-plus-sign'></i>&nbsp;Add User</a></div>
                </div>                

                <div class="span12"><hr>
                    
                    <div class="admSearchBox">
                        <form name="userlist" method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
                            <div class="frm-fields tBold"><span class="frmText">Partner Name&nbsp;:&nbsp;</span>
                                <input type="text" name="txtPartnerName" value="<?php echo $strPartnerName; ?>" maxlength="50" class="span2" id=""></div>
                            <div class="frm-fields tBold"><span class="frmText">Client Name&nbsp;:&nbsp;</span>
                                <input type="text" name="txtClientName" value="<?php echo $strClientName; ?>" maxlength="50" class="span2" id=""></div>
                            <div class="frm-fields tBold"><span class="frmText">First Name&nbsp;:&nbsp;</span>
                                <input type="text" name="txtFirstName" value="<?php echo $strFirstName; ?>" maxlength="50" class="span2" id=""></div>
                            <button name="btnSubmit" class="btn btn-primary" type="submit"><i class='icon-white icon-search'></i>&nbsp;Search</button>
                        </form>
                    </div>
                    
                    <div class="cB"></div>
                    <div>
                        <?php
//                        if (($strPartnerName != '' || $strClientName != '' || $strFirstName != '') || empty($arrUserList))
//                        {
//                            echo "<div class='alert alert-info'>No user available.</div>";
//                        }
//                        else
//                        {
                            if (!empty($arrUserList))
                            //if (sizeof($arrUserList) > 0)
                            {
                                ?>
                                <div class="mB20"><h4>Total Users&nbsp;:&nbsp;<?php echo count($arrUserList);?></h4></div>
                                <div style="height:600px;overflow:auto;">
                                    <table class="tblz01" width="100%" id="user-results">

                                        <thead>
                                            <tr class="thead">
                                                <td width="10%">Nick Name</td>
                                                <td width="10%">First Name</td>
                                                <td width="10%">Last Name</td>
                                                <td width="10%">Email Address</td>
                                                <td width="10%">Country Name</td>
                                                <td width="9%">Mobile</td>
                                                <td width="12%">Client Name</td>
                                                <td width="4%">Status</td>
                                                <td width="5%">Action</td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            for ($intCntr = 0; $intCntr < sizeof($arrUserList); $intCntr++)
                                            {                                                
                                                $userId     = $arrUserList[$intCntr]['user_id'];
                                                $userStatus = $arrUserList[$intCntr]['status'];
                                                $userAdmin  = $arrUserList[$intCntr]['is_admin'];
                                                $firstName  = $arrUserList[$intCntr]['first_name'];
                                                $lastName   = $arrUserList[$intCntr]['last_name'];
                                                $emailId    = $arrUserList[$intCntr]['email_address'];
                                                        
                                                
                                                switch ($userStatus)
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
                                                        $status = "<span style='color:red;'>Error in partner status</span>";
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
                                              <!--  <td><?php //echo $arrUserList[$intCntr]['partner_name']; ?></td>
                                                    <td><?php //echo $arrUserList[$intCntr]['client_name']; ?></td>-->
                                                    <td><?php echo $arrUserList[$intCntr]['nick_name']; ?></td>
                                                    <td><?php echo $arrUserList[$intCntr]['first_name']; ?></td>
                                                    <?php if ($arrUserList[$intCntr]['last_name'] != NULL)
                                                    { ?>
                                                        <td><?php echo $arrUserList[$intCntr]['last_name']; ?></td> <?php
                                                    }
                                                    else
                                                    { ?>
                                                        <td><?php echo '--' ?></td><?php } ?>
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
                                                        <span style=\"display: none;\" class=\"partnername\">" . $arrUserList[$intCntr][partner_name] . "</span>
                                                        <span style=\"display: none;\" class=\"clientname\">" . $arrUserList[$intCntr][client_name] . "</span>
                                                        </div>"
                                                        ?>
                                                        </td>
                                                    <td><?php echo $arrUserList[$intCntr]['country_name'] ?></td>
                                                    <?php if ($arrUserList[$intCntr]['idd_code'] != NULL && $arrUserList[$intCntr]['mobile_number'] != NULL)
                                                    { ?>
                                                        <td><?php echo "+" . $arrUserList[$intCntr]['idd_code'] . "-" . $arrUserList[$intCntr]['mobile_number']; ?></td>
                                                        <?php
                                                    }
                                                    else
                                                    {
                                                        ?>
                                                        <td><?php echo '--' ?></td>
                                                    <?php } ?>
                                              <!--  <td><?php //echo $isAdmin ?></td>-->
                                                    <td><?php echo $arrUserList[$intCntr]['client_name']; ?></td>
                                                    <td><?php echo $status ?></td>
                                                    <td>
                                                        <form name="userform_<?php echo $userId ?>" style="margin: 5px 0px 5px 0px;" method="post" action="editcontact.php">
                                                            <?php if ($status == 'Active') { ?>
                                                                <span class="label label-warning" style="cursor:pointer;" onclick='javascript:editUserStatus("<?php echo $userId; ?>","<?php echo $firstName; ?>","<?php echo $lastName; ?>","<?php echo $emailId; ?>","disable")'>Disable</span>
                                                            <?php } else { ?>
                                                                <span class="label label-success" style="cursor:pointer;" onclick='javascript:editUserStatus("<?php echo $userId; ?>","<?php echo $firstName; ?>","<?php echo $lastName; ?>","<?php echo $emailId; ?>","enable")'>Enable</span>
                                                            <?php } ?>
                                                        </form>
                                                    </td>
                                                </tr>
                                                <?php
                                            }
                                        }
                                        else
                                        {
                                            echo "<div class='alert alert-info'>Data not available.</div>";
                                        }
//                                    }
                                    ?>
                                        <form name="frmUserList" method="post">
                                            <input type='hidden' name='txtFormName'>
                                            <input type='hidden' name='txtUserId'>
                                            <input type='hidden' name='txtFirstName'>
                                            <input type='hidden' name='txtLastName'>
                                            <input type='hidden' name='txtEmailId'>
                                        </form>
                                </tbody>
                            </table>
                        </div>
                        <div class="pagination pagination-centered" id="pageNavPositionContacts"></div> 
                    </div>

                </div>
            </div>

            <!-- User Details Box -->
            <div id="layer"></div>
            <div id ="popup" class="user-details" style="display:none">
                <img class="fR" id="close" border='0' title='Close' alt='Close' src='<?php echo ADM_IMG_PATH; ?>close_black.png'>
                <h3>User Details</h3><br/>
                <form name="userdtls" method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
                    <table class="userdtls" width="100%">
                        <tr><td class="tBold" width="50%">Nick Name<span class="colon">:&nbsp;</span></td><td id="nick_name" width="50%"></td></tr>
                        <tr><td class="pB20"></td></tr>
                        <tr><td class="tBold">First Name<span class="colon">:&nbsp;</span></td><td id="first_name"></td></tr>
                        <tr><td class="pB20"></td></tr>
                        <tr><td class="tBold">Last Name<span class="colon">:&nbsp;</span></td><td id="last_name"></td></tr>
                        <tr><td class="pB20"></td></tr>
                        <tr><td class="tBold">Email Address<span class="colon">:&nbsp;</span></td><td id="email_id"></td></tr>
                        <tr><td class="pB20"></td></tr>
                        <tr><td class="tBold">Country Name<span class="colon">:&nbsp;</span></td><td id="country_name"></td></tr>
                        <tr><td class="pB20"></td></tr>
                        <tr><td class="tBold">Timezone<span class="colon">:&nbsp;</span></td><td id="timezone"></td></tr>
                        <tr><td class="pB20"></td></tr>
                        <tr><td class="tBold">Mobile<span class="colon">:&nbsp;</span></td><td id="mobile"></td></tr>
                        <tr><td class="pB20"></td></tr>
                        <tr><td class="tBold">Registration DateTime<span class="colon">:&nbsp;</span></td><td id="registration_dtm"></td></tr>
                        <tr><td class="pB20"></td></tr>
                        <tr><td class="tBold">Partner Name<span class="colon">:&nbsp;</span></td><td id="partner_name"></td></tr>
                        <tr><td class="pB20"></td></tr>
                        <tr><td class="tBold">Client Name<span class="colon">:&nbsp;</span></td><td id="client_name"></td></tr>
                        
                    </table>
                </form>
                <hr>
                <h3>Assign Subscription</h3><br/>
                <div id="error-msg" class="alert alert-error" style="display: none;"></div><div id="success-msg" class="alert alert-success" style="display: none;"></div>
                <form name="addsubscptn" method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
                    <div class="frm-fields tBold">Password<span class="required">&nbsp;*</span><span class="colon">:&nbsp;&nbsp;</span></div>
                    <div><input type="password" name="txtPassword" maxlength="50" class="span3" id="txtPassword"></div>
                    <div class="frm-fields tBold">Plan Name<span class="required">&nbsp;*</span><span class="colon">:&nbsp;&nbsp;</span></div>
                    <?php
                    echo"<div><select name='txtPlanName' class='span3' id='txtPlanName' onchange='IsMultiple(this.value)'> ";
                    if (!empty($arrPlanDetails))
                    {
                        echo"<option value='---'>Select Plan</option>";
                        for ($intCount = 0; $intCount < sizeof($arrPlanDetails); $intCount++)
                        {
                            if ($arrPlanDetails[$intCount]['plan_cost_inr'] != '0.00')
                            {
                                echo"<option value='" . $arrPlanDetails[$intCount]['plan_id'] . SEPARATOR . $arrPlanDetails[$intCount]['plan_name'] . SEPARATOR . $arrPlanDetails[$intCount]['is_multiple'] . "'>" . $arrPlanDetails[$intCount]['plan_name'] . "&nbsp;&nbsp;(Rs." . $arrPlanDetails[$intCount]['plan_cost_inr'] . ")</option>";
                            }
                            else if ($arrPlanDetails[$intCount]['plan_cost_oth'] != '0.00')
                            {
                                echo"<option value='" . $arrPlanDetails[$intCount]['plan_id'] . SEPARATOR . $arrPlanDetails[$intCount]['plan_name'] . SEPARATOR . $arrPlanDetails[$intCount]['is_multiple'] . "'>" . $arrPlanDetails[$intCount]['plan_name'] . "&nbsp;&nbsp;($&nbsp;" . $arrPlanDetails[$intCount]['plan_cost_oth'] . ")</option>";
                            }
                            else
                            {
                                echo"<option value='" . $arrPlanDetails[$intCount]['plan_id'] . SEPARATOR . $arrPlanDetails[$intCount]['plan_name'] . SEPARATOR . $arrPlanDetails[$intCount]['is_multiple'] . "'>" . $arrPlanDetails[$intCount]['plan_name'] . "</option>";
                            }
                        }
                    }
                    else
                    {
                        echo"<option value ='---'>Plan Name List not available</option>";
                    }echo"
                                          </select></div>";
                    ?>
                    <input type="hidden" id="txtUserId" name="userid">
                    <input type="hidden" id="txtMonth" name="txtMonth" value="1">
                    <button class="btn btn-primary" id="btnSubscptn" name="btnSubscptn">Submit</button>

                </form><hr>

            </div>
            <!-- User Details Box -->

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
        <script src="<?php echo ADM_JS_PATH; ?>user.js"></script>
        <script src="<?php echo ADM_JS_PATH; ?>show-popup.js"></script>
        <!-- java script  1-->

        <script type="text/javascript">
            var userlist = '<?php echo $arrUserList ?>';
            if(userlist != '') 
            {
                var pagerContactList = new Pager('user-results', 10, 'con');
                pagerContactList.init();
                pagerContactList.showPageNav('pagerContactList', 'pageNavPositionContacts');
                pagerContactList.showPage(1);
            }
        </script>
        <script type='text/javascript'>
            $(document).ready(function () {
                $('.view').click(function() {
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
                    
                    if (nickname=='')
                        nickname  = '---';
                    if (firstname=='')
                        firstname  = '---';
                    if (lastname=='')
                        lastname  = '---';
                    if (emailid=='')
                        emailid  = '---';
                    if (countryname=='')
                        countryname  = '---';
                    if (timezone=='')
                        timezone  = '---';
                    if (iddcode=='')
                        iddcode  = '---';
                    if (mobile=='')
                        mobile  = '---';
                    if (registrationdtm=='')
                        registrationdtm  = '---';
                    
                    $('#txtUserId').val(userid);
                    $('#nick_name').text(nickname);
                    $('#first_name').text(firstname);
                    $('#last_name').text(lastname);
                    $('#email_id').text(emailid);
                    $('#country_name').text(countryname);
                    $('#timezone').text(timezone+" "+gmt);
                    $('#mobile').text("+"+iddcode+"-"+mobile);
                    $('#registration_dtm').text(registrationdtm);
                    $('#partner_name').text(partnername);
                    $('#client_name').text(clientname);
                    
                    
                    showPopup('#popup', '#layer');
                });
                
                $('#close').click(function() {
                    hidePopup('#popup', '#layer');
                    $('#success-msg').css({"display":"none"});
                    $('#txtPassword').val('');
                    $("#txtPlanName").val('---');
                });
                
                $('#btnSubscptn').click(function() {
                    var uid = $("#txtUserId").val();
                    var uname = $("#nick_name").text();
                    var pwd = $("#txtPassword").val();
                    var plan = $("#txtPlanName").val();
                    var month = $("#txtMonth").val();
                    var substr = plan.split('<?php echo SEPARATOR ?>');
                    var plan_id = substr[0];
                    var plan_name = substr[1];
                    if($.trim(pwd).length == 0) {
                        $('#success-msg').css({"display":"none"});
                        $('#error-msg').css({"display":"block"});
                        $('#error-msg').html("Please enter your Password.");
                        return false;
                    }
                    else if(plan == '---') {
                        $('#success-msg').css({"display":"none"});
                        $('#error-msg').css({"display":"block"});
                        $('#error-msg').html("Please select Plan Name.");
                        return false;
                    }
                    else {
                        $.post("addsubscription.php",{txtUserId:uid, txtPassword:pwd, txtPlanId:plan_id, txtMonth:month} ,function(data)
                        { 
                            var $response=$(data);
                            var oneval = $response.filter('#msg').text();console.log(oneval);
                            if(oneval == 'yes') {
                                $('#txtPassword').val('');
                                $("#txtPlanName").val('---');
                                $('#txtMonth').css({"display":"none"});
                                $('#success-msg').css({"display":"block"});
                                $('#error-msg').css({"display":"none"});
                                $('#success-msg').html('Plan <b><font color=#006699>"'+plan_name+'"</font></b> successfully assigned to user <b><font color=#006699>"'+uname+'"</font></b>.');
                            }
                            else if(oneval == 'Invalid') {
                                $('#error-msg').css({"display":"block"});
                                $('#error-msg').html('Incorrect Password. Please re-enter.');
                            }
                            else {
                                $('#error-msg').css({"display":"block"});
                                $('#error-msg').html('Some error occured, please try again later.');
                            }
                        });
                        return false;
                    }
                });
                
            });  
            
            function IsMultiple(plandtls) {  
                var is_multiple = plandtls.split('|')['2'];
                if(is_multiple == 'yes')
                {
                    document.getElementById('txtMonth').style.display = 'block';
                } else {
                    document.getElementById('txtMonth').style.display = 'none';
                }
            }
        </script>   
    </body>
</html>

