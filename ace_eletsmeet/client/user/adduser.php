<?php
require_once('../../includes/global.inc.php');
require_once('../includes/config.inc.php');
require_once(CLIENT_CLASSES_PATH . 'client_error.inc.php');
require_once(DBS_PATH . 'DataHelper.php');
require_once(DBS_PATH . 'objDataHelper.php');
require_once(CLIENT_INCLUDES_PATH . 'client_authfunc.inc.php');
$CLIENT_CONST_MODULE = 'cl_user';
$CLIENT_CONST_PAGEID = 'Add User';
require_once(CLIENT_INCLUDES_PATH . 'client_authorize.inc.php');
require_once(CLIENT_INCLUDES_PATH . 'client_db_function.inc.php');
require_once(CLIENT_INCLUDES_PATH . 'client_reports_function.inc.php');

    try
    {
        $arrCountryIddCode = getCountryDetails($objDataHelper);
    }
    catch (Exception $e)
    {
        throw new Exception("adduser.php : getCountryDetails Failed : " . $e->getMessage(), 541);
    } 
    
    //License Purchased
    $strOptLicenseType =0; 
    try
    {
        $arrTotalLicense = getSumOfClientLicenseByType($strSetClient_ID, $strOptLicenseType,$objDataHelper);
    }
    catch (Exception $a)
    {
        throw new Exception("index.php : getPlanDetails : Error in populating Plan Details." . $a->getMessage(), 541);
    }
    $strTotalLicense = $arrTotalLicense[0]['TotalLicense'];
    
    //License Consumed
    try
    {
        $arrTotalConsumedLicense = getTotalConsumedLicenseByClientId($strSetClient_ID, $objDataHelper);
    }
    catch (Exception $a)
    {
        throw new Exception("adduser.php : getTotalConsumedLicenseByClientId Failed." . $a->getMessage(), 541);
    }
    $strConsumedLicense = $arrTotalConsumedLicense[0]['ConsumedLicense'];

    //if((isset($_POST['txUserEmail'])) &&  (isset($_POST['txPassword'])) && (isset($_POST['txNick'])) && (isset($_POST['txCountry'])) )
    if((isset($_POST['txUserEmail'])) &&  (isset($_POST['txPassword'])) && (isset($_POST['txNick'])) )
    {
        $strEmail = trim($_POST['txUserEmail']);        
        $strPassword = md5(trim($_POST['txPassword']));
        $strNick = trim($_POST['txNick']);
        $strCountryName = trim($_POST['txCountry']);
        $strTimezone = trim($_POST['txTimezone']);
        $strIddCode = trim($_POST['txIddCode']);
        $strMobile = trim($_POST['txMobile']);

        if (strlen($strEmail) != 0)
        {
            try
            {
                $isEmailExists = isUserEmailExists($strEmail, $objDataHelper);
            }
            catch (Exception $a)
            {
                throw new Exception("addadduser.php : isUserEmailExists : Error in checking email address." . $a->getMessage(), 541);
            }
            
            if ($isEmailExists[0]['@STATUS'] == 1)
            {
                $stat = "0";
                $msg = "Email Address <b>" . $strEmail . "</b> already exists.";
            }
            else
            {
                    if ($strCountryName != '---')
                    {
                        $strCountryName = explode(SEPARATOR, $strCountryName);
                        $strCountryName = $strCountryName[1];
                    }
                
                    if (strlen(trim($strIddCode)) > 0)
                    {
                        $strIddCode = substr($strIddCode, 1);
                    }

                    if (strlen(trim($strTimezone)) > 0)
                    {
                        $strTimeZone = split(" ", $strTimezone);
                        $TimeZone = $strTimeZone[0];
                        $gmt = $strTimeZone[1];
                    }

                    try
                    {
                        $userId = getUserId($objDataHelper);
                    }
                    catch (Exception $a)
                    {
                        throw new Exception("adduser.php : getUserId : Error in getting User Id." . $a->getMessage(), 541);
                    }
                                       
                    $strUserName = $strEmail;
                    $strRole = "1";
                    $strLoginEnabled = "1";
                    $strCreatedOn = GM_DATE;
                    $strCreatedBy = $strSetClient_ID;
                    $strFirstName = "";
                    $strLastName = "";

                    try
                    {
                        $insUserDetails = insUserLoginDetails($userId, $strUserName, $strSetClient_ID, $strSetPartner_ID, $strPassword, $strEmail, $strRole, $strLoginEnabled, $strCreatedOn, $strCreatedBy, $objDataHelper);
                    }
                    catch (Exception $a)
                    {
                        throw new Exception("addsubscription.php : insOrderMaster : Error in adding order master." . $a->getMessage(), 613);
                    }
                   
                     if ($insUserDetails == 1)
                     {
                            try
                            {
                                $insUserDetails = insUserDetails($userId, $strNick, $strCountryName, $TimeZone, $gmt, $strIddCode, $strMobile, $objDataHelper);
                            }
                            catch (Exception $a)
                            {
                                throw new Exception("addsubscription.php : insOrderMaster : Error in adding order master." . $a->getMessage(), 613);
                            }
                         
                            $strLicense = 1;
                            $OperationType = 1;
                            $gmt_datetime = GM_DATE;

                            try
                            {
                                $insClientLicense = insClientLicenseDetails($strSetClient_ID, $strLicense, $OperationType, $gmt_datetime, $objDataHelper);
                            }
                            catch (Exception $a)
                            {
                                throw new Exception("addsubscription.php : insOrderMaster : Error in adding order master." . $a->getMessage(), 613);
                            }
                            $inslicensestatus = $insClientLicense[0]['@STATUS'];
                            if ($inslicensestatus == 1)
                            {
                                $stat = "1";
                                $msg = "User <b>"."$strNick"."</b> added Successfully.";
                            }
                            else
                            {
                                $stat = "0";
                                $msg = 'Error in Adding.';
                            }
                     }
                     else
                     {
                          $stat = "0";
                          $msg = 'Error in Adding User.';
                     }
            }
        }
         else
         {
            $stat = "-1";
            $msg = "Invalid Data";
         }
         $finalStat = $stat.SEPARATOR.$msg;
        echo $finalStat;
        exit;
    }
?>
<div class="well">
    <h4 class="smaller">Add User</h4>
    <hr>
    <div id="success-msg" class="alert alert-success errorDisplay"></div>
    <div id="add-user">
        <div id="error-msg" class="alert alert-danger errorDisplay"></div> 
         <?php if ($strTotalLicense > $strConsumedLicense) {?>
        <form name="frmAddUser" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>" class="form-horizontal" role="form">
            
                <div class="form-group required">
                        <label for="form-field-1" class="col-sm-4 control-label no-padding-right"> Email Address </label>
                        <div class="col-sm-8">
                                <input id="txtEmail" name="txtEmail" type="text" class="col-sm-10" placeholder="Email Address"  maxlength="100" value="<?php  echo $strEmail; ?>"/>
                        </div>
                </div>

                <div class="form-group required">
                        <label for="form-field-1" class="col-sm-4 control-label no-padding-right"> Password </label>
                        <div class="col-sm-8">
                                <input id="txtPassword" name="txtPassword" type="password" class="col-sm-10" placeholder="Password"  maxlength="15"/>
                        </div>
                </div>

                <div class="form-group required">
                        <label for="form-field-1" class="col-sm-4 control-label no-padding-right"> Confirm Password </label>
                        <div class="col-sm-8">
                                <input id="txtCnfPassword" name="txtCnfPassword" type="password" class="col-sm-10" placeholder="Confirm Password"  maxlength="15"/>
                        </div>
                </div>

                <div class="form-group required">
                        <label for="form-field-1" class="col-sm-4 control-label no-padding-right"> Nick Name </label>
                        <div class="col-sm-8">
                                <input id="txtNickName" name="txtNickName" type="text" class="col-sm-10" placeholder="Nick Name"  maxlength="25" value="<?php  echo $strNick; ?>"/>
                        </div>
                </div>

                <div class="form-group required">
                        <label for="form-field-1" class="col-sm-4 control-label no-padding-right"> Country </label>
                        <div class="col-sm-8">
                                <select name="CountryName" class="col-sm-10" id="CountryName" onchange="SetIddCode(this.value); addOption(this.value);">
                                    <?php
                                    if (!empty($arrCountryIddCode))
                                    {
                                        echo"<option value='---'>Select Country</option>";
                                        for ($intCount = 0; $intCount < sizeof($arrCountryIddCode); $intCount++)
                                        {
                                            if ($arrCountryIddCode[$intCount]['country_name'] == $strCountryName)
                                            {
                                                echo"<option value='" . $arrCountryIddCode[$intCount]['country_idd_code'] . SEPARATOR . $arrCountryIddCode[$intCount]['country_name'] . SEPARATOR . $arrCountryIddCode[$intCount]['country_code'] . "' selected>" . $arrCountryIddCode[$intCount]['country_name'] . "</option>";
                                            }
                                            else
                                            {
                                                echo"<option value='" . $arrCountryIddCode[$intCount]['country_idd_code'] . SEPARATOR . $arrCountryIddCode[$intCount]['country_name'] . SEPARATOR . $arrCountryIddCode[$intCount]['country_code'] . "'>" . $arrCountryIddCode[$intCount]['country_name'] . "</option>";
                                            }
                                        }
                                    }
                                    else
                                    {
                                        echo"<option value ='---'>Country Name List not available</option>";
                                    }
                                    ?>
                                </select>
                        </div>
                </div>

                <div class="form-group required">
                        <label for="form-field-1" class="col-sm-4 control-label no-padding-right"> Timezone </label>
                        <div class="col-sm-8">
                                <select name="TimeZone" class="col-sm-10" id="TimeZone">
                                    <option value='---'>Select Timezone</option>
                                    <?php
                                    if (isset($strTime_Zone))
                                    {
                                        echo"<option value='" . $strTime_Zone . "' selected>" . $strTime_Zone . "</option>";
                                    }
                                    ?>
                                </select>
                        </div>
                </div>
            
                <div class="form-group required">
                        <label for="form-field-1" class="col-sm-4 control-label no-padding-right"> Mobile Number </label>
                        <div class="col-sm-8">
                                <input id="txtIddCode" name="txtIddCode" type="text" class="col-sm-3" readonly="readonly" placeholder="Idd Code"  maxlength="5"  value="<?php  echo $strIddCode; ?>"/>
                                <input id="txtMobileNumber" name="txtMobileNumber" type="text" class="col-sm-5" placeholder="Mobile Number"  maxlength="10" value="<?php  echo $strMobile;?>"/>
                        </div>
                </div>

                 <div class="form-actions center">
                    <button class="btn btn-sm btn-yellow" type="button" id="btnAddUser" name="btnAddUser"> Add User </button>
                </div>
        </form>
        <?php }else{ ?>
            <div class="alert alert-danger">Sorry, You have consumed all your License's, For more license please contact sales@letsmeet.com</div>
        <?php }?>                   
     </div>
</div>

<script type='text/javascript'>
    $(document).ready(function ()
    {
        var countryname = $('#CountryName').val();
        var timezone = $('#TimeZone').val();
        if (countryname != '---' && timezone == '---')
        {
            addOption(countryname);
        }
    });
    
    function SetIddCode(iddcode)
    {
        iddcode = iddcode.split("|");
        if (document.frmAddUser.CountryName.value == '---')
        {
            document.frmAddUser.txtIddCode.value = '';
        }
        else
        {
            document.frmAddUser.txtIddCode.value = "+" + iddcode[0];
            if (document.frmAddUser.TimeZone)
                document.frmAddUser.TimeZone.value = iddcode[2] + " " + iddcode[3];
        }
    }
    
    var CLIENT_SITE_ROOT = "<?php echo $CLIENT_SITE_ROOT; ?>";
    
    function addOption(countrycode)
    {
        countrycode = countrycode.split("|");
        countrycode = countrycode[2];
        
        var httpxml;
        try {
            httpxml = new XMLHttpRequest();
        } 
        catch (e) 
        {
            try 
            {
                httpxml = new ActiveXObject("Msxml2.XMLHTTP");
            } 
            catch (e) 
            {
                try 
                {
                    httpxml = new ActiveXObject("Microsoft.XMLHTTP");
                } 
                catch (e) 
                {
                    //alert("Your browser does not support AJAX!");
                    return false;
                }
            }
        }

         function stateck() {
            if (httpxml.readyState == 4) 
            {
                var myarray = httpxml.responseText;
                if (myarray != "") {
                    arr = myarray.split(",");
                    for (j = document.getElementById('TimeZone').options.length - 1; j >= 0; j--) {
                        document.getElementById('TimeZone').remove(j);
                    }
                    var optn1 = document.createElement("OPTION");
                    optn1.text = "Select Timezone";
                    optn1.value = "---";
                    document.getElementById('TimeZone').options.add(optn1);
                    for (i = 0; i < arr.length; i++) {
                        timezone = arr[i];
                        var optn = document.createElement("OPTION");
                        optn.text = timezone;
                        optn.value = timezone;
                        document.getElementById('TimeZone').options.add(optn);
                    }
                } else {
                    for (j = document.getElementById('TimeZone').options.length - 1; j >= 0; j--) {
                        document.getElementById('TimeZone').remove(j);
                    }
                    var optn1 = document.createElement("OPTION");
                    optn1.text = "Timezone list not available";
                    optn1.value = "";
                    document.getElementById('TimeZone').options.add(optn1);
                    return false;
                }
            }
        }
                      
        var url = CLIENT_SITE_ROOT+'includes/getTimezones.php';
        url = url + "?cCode=" + countrycode;
        httpxml.onreadystatechange = stateck;
        httpxml.open("GET", url, true);
        httpxml.send(null);
        return false;
    }

    function PageRefresh( ) 
    {
        location.reload(true);
    }
            
    $(document).ready(function () {
    $('#error-msg').html('');

    $("#btnAddUser").click(function() {
                var clUserEmail   = $("#txtEmail").val();
                var clPassword    = $("#txtPassword").val();
                var clCnfPassword = $("#txtCnfPassword").val();
                var clNick        = $("#txtNickName").val();
                var clCountry     = $("#CountryName").val();
                var clTimezone    = $("#TimeZone").val();
                var clIddCode     = $("#txtIddCode").val();
                var clMobile      = $("#txtMobileNumber").val();
                
                if($.trim(clUserEmail).length == 0) 
                {
                    $("#error-msg").html("Please enter Email Address.");
                    $("#error-msg").css({"display":"block"});
                    var textbox = document.getElementById("txtEmail");
                    textbox.focus();
                    textbox.scrollIntoView(true);
                    return false;
                } 
                else if (!(/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(clUserEmail))) 
                {
                    $("#error-msg").html("Please enter a valid Email Address.");
                    $("#error-msg").css({"display":"block"});
                    var textbox = document.getElementById("txtEmail");
                    textbox.focus();
                    textbox.scrollIntoView(true);
                    return false;
                }
                else if($.trim(clPassword).length == 0) 
                {
                    $("#error-msg").html("Please enter Password.");
                    $("#error-msg").css({"display":"block"});
                    var textbox = document.getElementById("txtPassword");
                    textbox.focus();
                    textbox.scrollIntoView(true);
                    return false;
                } 
                //else if (!(/^[a-zA-Z0-9]{3,15}$/.test(clPassword)))
                else if (!(/^[a-zA-Z0-9]+$/.test(clPassword))) 
                {
                    $("#error-msg").html("Only alphabets and numbers allowed, No space and special characters allowed.");
                    $("#error-msg").css({"display":"block"});
                    var textbox = document.getElementById("txtPassword");
                    textbox.focus();
                    textbox.scrollIntoView(true);
                    return false;
                }
                else if($.trim(clPassword).length < 3)
                {
                    $("#error-msg").html("Password must contain at least 3 characters.");
                    $("#error-msg").css({"display":"block"});
                    var textbox = document.getElementById("txtPassword");
                    textbox.focus();
                    textbox.scrollIntoView(true);
                    return false;
                }
                else if($.trim(clCnfPassword).length == 0) 
                {
                    $("#error-msg").html("Please enter Confirm Password.");
                    $("#error-msg").css({"display":"block"});
                    var textbox = document.getElementById("txtCnfPassword");
                    textbox.focus();
                    textbox.scrollIntoView(true);
                    return false;
                }
                else if(clPassword != clCnfPassword) 
                { 
                    $("#error-msg").html("Password and Confirm Password do not match.");
                    $("#error-msg").css({"display":"block"});
                    var textbox = document.getElementById("txtCnfPassword");
                    textbox.focus();
                    textbox.scrollIntoView(true);
                    return false;
                }
               else if($.trim(clNick).length == 0) 
                {
                    $("#error-msg").html("Please enter Nick Name.");
                    $("#error-msg").css({"display":"block"});
                    var textbox = document.getElementById("txtNickName");
                    textbox.focus();
                    textbox.scrollIntoView(true);
                    return false;
                } 
                else if (!(/^[a-zA-Z]+$/.test(clNick))) 
                {
                    //$("#error-msg").html("Please enter a valid Nick Name");
                    $("#error-msg").html("Only alphabets allowed, No spaces, numbers and special characters allowed.");
                    $("#error-msg").css({"display":"block"});
                    var textbox = document.getElementById("txtNickName");
                    textbox.focus();
                    textbox.scrollIntoView(true);
                    return false;
                }
                else if($.trim(clNick).length < 3)
                {
                    $("#error-msg").html("Nick Name must contain at least 3 characters.");
                    $("#error-msg").css({"display":"block"});
                    var textbox = document.getElementById("txtNickName");
                    textbox.focus();
                    textbox.scrollIntoView(true);
                    return false;
                } 
                else if($.trim(clCountry) == "---") 
                {
                    $("#error-msg").html("Please select Country.");
                    $("#error-msg").css({"display":"block"});
                    var textbox = document.getElementById("CountryName");
                    textbox.focus();
                    textbox.scrollIntoView(true);
                    return false;
                } 
                else if($.trim(clTimezone) == "---") 
                {
                    $("#error-msg").html("Please select Timezone.");
                    $("#error-msg").css({"display":"block"});
                    var textbox = document.getElementById("TimeZone");
                    textbox.focus();
                    textbox.scrollIntoView(true);
                    return false;
                } 
                else if($.trim(clMobile).length == 0) 
                {
                    $("#error-msg").html("Please enter Mobile Number.");
                    $("#error-msg").css({"display":"block"});
                    var textbox = document.getElementById("txtMobileNumber");
                    textbox.focus();
                    textbox.scrollIntoView(true);
                    return false;
                } 
                else if (!(/^[0-9]+$/.test(clMobile))) 
                {
                    $("#error-msg").html("Only numbers allowed.");
                    $("#error-msg").css({"display":"block"});
                    var textbox = document.getElementById("txtMobileNumber"); 
                    textbox.focus();
                    textbox.scrollIntoView(true);
                    return false;
                }
                else if($.trim(clMobile).length < 6)
                {
                    $("#error-msg").html("Mobile Number must contain at least 6 characters.");
                    $("#error-msg").css({"display":"block"});
                    var textbox = document.getElementById("txtMobileNumber");
                    textbox.focus();
                    textbox.scrollIntoView(true);
                    return false;
                }
                else
                {

                    $.post("adduser.php", {txUserEmail: clUserEmail, txPassword: clPassword, txNick: clNick, txCountry: clCountry, txTimezone: clTimezone, txIddCode: clIddCode, txMobile: clMobile}, function (data)
                    {                        
                        var response=data;
                        $("#response").html(data);    
                        var sep  = "<?php echo SEPARATOR; ?>";
                        var html = response.split(sep);
                        if (html[0] == 1) 
                        {
                            $("#success-msg").html(html[1]);
                            $("#error-msg").removeClass("alert-error");
                            $("#success-msg").addClass("alert-success");
                            $("#success-msg").css({"display":"block"});
                            $("#add-user").addClass("errorDisplay");
                        } 
                        else 
                        {
                            $("#error-msg").html(html[1]);
                            $("#error-msg").css({"display":"block"});
                            return false;
                        }
                    });
                    return false;
                }
                $("#error-msg").html("");
                $("#error-msg").css({"display":"none"});
            });
});

</script>