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

$strClientId = trim($_REQUEST['txtClientId']);
$strClientName = trim($_REQUEST['txtClientName']);

?>

<div>
    <img class="fR" id="close" border='0' title='Close' alt='Close' src='<?php echo ADM_IMG_PATH; ?>close_black.png'>
    <h3>Assign License</h3><br/>
    <div id="error-msg" class="alert alert-error" style="display: none;"></div>
    <div id="success-msg" class="alert alert-success" style="display: none;"></div>
    <div id="sub_form">
    <form name="addsubscptn" method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
        <div class="frm-fields tBold">Client Name<span class="colon">:&nbsp;&nbsp;</span></div>
        <div style="height: 18px;line-height: 18px; margin-bottom: 9px; padding: 4px;"><?php echo $strClientName; ?></div>
        <div class="mB10"></div>
        
        <div class="frm-fields tBold">Password<span class="required">&nbsp;*</span><span class="colon">:&nbsp;&nbsp;</span></div>
        <div><input type="password" name="txtPassword" maxlength="50" class="span3" id="txtPassword"></div>
        <div class="mB10"></div>
        
        <div class="frm-fields tBold">No. Of Licenses <span class="required">&nbsp;*</span><span class="colon">:&nbsp;&nbsp;</span></div>
        <div>
            <select id="txtLicenseCount" class='span3' name="txtLicenseCount" >
                echo"<option value='---'>Select No of Licenses</option>";
                <?php for($i=1;$i<=100;$i++){?>
                <option value="<?php echo $i;?>"><?php echo $i;?></option>
                <?php }?>
            </select>
        </div>
        <div class="mB10"></div>        
         <input type="hidden" id="txtClientId" name="txtClientId" value="<?php echo $strClientId; ?>">
         <input type="hidden" id="txtClientName" name="txtClientName" value="<?php echo $strClientName; ?>">
        <button class="btn btn-primary" id="btnSubscptn" name="btnSubscptn">Submit</button>
    </form>
    </div>
   
</div>

<script type='text/javascript'>
    $(document).ready(function () {
        $('#close').click(function () {
            hidePopup('#popupS', '#layer');
        });
    
    
    $('#btnSubscptn').click(function () {
                    var clid = $("#txtClientId").val();
                    var clname = $("#txtClientName").val();
                    var pwd = $("#txtPassword").val();
                    var license = $("#txtLicenseCount").val();

                   // var substr = plan.split('<?php echo SEPARATOR ?>');
                    //var plan_id = substr[0];
                    //var plan_name = substr[1];
                    if ($.trim(pwd).length == 0) {
                        $('#success-msg').css({"display": "none"});
                        $('#error-msg').css({"display": "block"});
                        $('#error-msg').html("Please enter your Password");
                        return false;
                    }
                    else if (license == '---') {
                        $('#success-msg').css({"display": "none"});
                        $('#error-msg').css({"display": "block"});
                        $('#error-msg').html("Please select No. Of Months");
                        return false;
                    }
                    else {
                        $.post("addlicense.php", {txtClientId: clid, txtPassword: pwd, txtLicense:license}, function (data)
                        {
                            var $response = $(data);
                            var oneval = $response.filter('#msg').text();
                            console.log(oneval);
                            if (oneval == 'yes') {
                                $('#txtPassword').val('');
                                $("#txtLicenseCount").val('---');
                                $('#success-msg').css({"display": "block"});
                                $('#error-msg').css({"display": "none"});
                                $('#success-msg').html('<b><font color=#006699>"' + license + '"</font></b> license successfully assigned to <b><font color=#006699>"' + clname + '"</font></b>.');
                                $('#sub_form').css({"display": "none"});
                            }
                            else if (oneval == 'Invalid') {
                                $('#txtPassword').val('');
                                //document.getElementById("txtLicenseCount").options[0].selected=true;
                                $("#txtLicenseCount").val('---');
                                $('#error-msg').css({"display": "block"});
                                $('#error-msg').html('Incorrect Password, Please re-enter');
                            }
                            else {
                                $('#txtPassword').val('');
                                $("#txtLicenseCount").val('---');
                                //document.getElementById("txtLicenseCount").options[0].selected=true;
                                $('#error-msg').css({"display": "block"});
                                $('#error-msg').html('Some error occured, Please try again later');
                            }
                        });
                        return false;
                    }
                });
    });
</script>   
