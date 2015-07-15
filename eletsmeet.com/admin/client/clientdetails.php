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

    try
    {
        $arrClientDetails = getClientDtlsById($strClientId,$objDataHelper);
    }
    catch (Exception $a)
    {
        throw new Exception("index.php : getPlanDetails : Error in populating Plan Details." . $a->getMessage(), 541);
    }
    //print_r($arrClientDetails);
    
    $strDBClient_Name = $arrClientDetails[0]['client_name'];
    $strDBClient_Logo_Url = $arrClientDetails[0]['client_logo_url'];
    $strDBClient_Email = $arrClientDetails[0]['client_email_address'];
    $strDBClient_LastLogin = $arrClientDetails[0]['client_lastlogin_dtm'];
    $strDBClient_LastLoginIP = $arrClientDetails[0]['client_login_ip_address'];
    $strDBClient_LMServer = $arrClientDetails[0]['rt_server_name'];   

?>

<div>
    <img class="fR" id="close" border='0' title='Close' alt='Close' src='<?php echo ADM_IMG_PATH; ?>close_black.png'>
    <h3>Client Details</h3><br/>
    
    <div id="dtls_form">
        <table class="" width="100%">
                <tr>
                    <td class="tBold" width="50%">Client Name<span class="colon">:&nbsp;&nbsp;</span></td>
                    <td><?php echo $strDBClient_Name; ?></td>
                </tr>
                
                <tr><td class="pB5"></td></tr>
                
                <tr>
                    <td class="tBold">Client Logo<span class="colon">:&nbsp;&nbsp;</span></td>
                    <td>
                        <?php if ($strDBClient_Logo_Url != NULL){ ?>
                            <img src="<?php echo $SITE_ROOT.'client/images/client_logo/'.$strDBClient_Logo_Url; ?>"  width="100" height="100"  vspace="0px" >
                        <?php }else{ ?>
                            <img src="<?php echo ADM_IMG_PATH.'not_available.jpg' ?>"  width="70" height="70"  vspace="0px" >
                        <?php }?>
                    </td>
                </tr>
                
                <tr><td class="pB5"></td></tr>
                
                <tr>
                    <td class="tBold">Client Email Address<span class="colon">:&nbsp;&nbsp;</span></td>
                    <td><?php echo $strDBClient_Email; ?></td>
                </tr>
                
                <tr><td class="pB5"></td></tr>
                
                <tr>
                    <td class="tBold">Client Last Login Datetime<span class="colon">:&nbsp;&nbsp;</span></td>
                    <td><?php echo $strDBClient_LastLogin; ?></td>
                </tr>
                
                <tr><td class="pB5"></td></tr>
                
                <tr>
                    <td class="tBold">Client Last Login IP<span class="colon">:&nbsp;&nbsp;</span></td>
                    <td><?php echo $strDBClient_LastLoginIP; ?></td>
                </tr>
                
                <tr><td class="pB5"></td></tr>
                
                <tr>
                    <td class="tBold">Client  LetsMeet Server<span class="colon">:&nbsp;&nbsp;</span></td>
                    <td><?php echo $strDBClient_LMServer; ?></td>
                </tr>
                 
        </table>
        
    </div>
   
</div>

<script type='text/javascript'>
    $(document).ready(function () {
        $('#close').click(function () {
            hidePopup('#popupS', '#layer');
        });
});
</script>   
