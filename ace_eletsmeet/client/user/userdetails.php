<?php
require_once('../../includes/global.inc.php');
require_once('../includes/config.inc.php');
require_once(CLIENT_CLASSES_PATH . 'client_error.inc.php');
require_once(DBS_PATH . 'DataHelper.php');
require_once(DBS_PATH . 'objDataHelper.php');
require_once(CLIENT_INCLUDES_PATH . 'client_authfunc.inc.php');
$CLIENT_CONST_MODULE = 'cl_user';
$CLIENT_CONST_PAGEID = 'User Status';
require_once(CLIENT_INCLUDES_PATH . 'client_authorize.inc.php');
//require_once(CLIENT_INCLUDES_PATH . 'client_db_function.inc.php');
require_once(CLIENT_INCLUDES_PATH . 'client_reports_function.inc.php');


$strUserId = $_REQUEST["userId"];
$strUserName = $_REQUEST["uName"];
$GmtDatetime = GM_DATE;

try {
    $arrUserDetls = getUserDetailsByUserId($strUserId, $objDataHelper);
} catch (Exception $e) {
    throw new Exception("index.php : updInvitationStatus Failed : " . $e->getMessage(), 1126);
}
//print_r($arrUserDetls);

if ((is_array($arrUserDetls)) && (sizeof($arrUserDetls)) > 0) {
    $strDBUserId = trim($arrUserDetls[0]['user_id']);
    $strDBUserName = trim($arrUserDetls[0]['user_name']);
    $strDBUserEmail = trim($arrUserDetls[0]['email_address']);
    $strDBUserStatus = trim($arrUserDetls[0]['login_enabled']);
    switch ($strDBUserStatus) {
        case 0: $strDBUserStatus = "Pending";
            break;
        case 1: $strDBUserStatus = "Active";
            break;
        case 2: $strDBUserStatus = "Deative";
            break;
        case 3: $strDBUserStatus = "Deleted";
            break;
        default: break;
    }
    $strDBUserCreatedOn = trim($arrUserDetls[0]['created_on']);
    if (strlen($strDBUserCreatedOn) <= 0) 
    {
        $strDBUserCreatedOn = "---";
    }
    $strDBUserCreatedBy = trim($arrUserDetls[0]['created_by']);
    $strDBUserLastLoginDTM = trim($arrUserDetls[0]['user_last_login_dtm']);
    if (strlen($strDBUserLastLoginDTM) <= 0) 
    {
        $strDBUserLastLoginDTM = "---";
    }
    $strDBUserLastLoginIP = trim($arrUserDetls[0]['user_login_ip_address']);
    if (strlen($strDBUserLastLoginIP) <= 0) 
    {
        $strDBUserLastLoginIP = "---";
    }
    $strDBUserNickName = trim($arrUserDetls[0]['nick_name']);
    $strDBUserFirstName = trim($arrUserDetls[0]['first_name']);
    if (strlen($strDBUserFirstName) <= 0) 
    {
        $strDBUserFirstName = "---";
    }
    $strDBUserLastName = trim($arrUserDetls[0]['last_name']);
    if (strlen($strDBUserLastName) <= 0) 
    {
        $strDBUserLastName = "---";
    }
    $strDBUserSecondEmail = trim($arrUserDetls[0]['secondry_email']);
    if (strlen($strDBUserSecondEmail) <= 0) 
    {
        $strDBUserSecondEmail = "---";
    }
    $strDBUserCountryName = trim($arrUserDetls[0]['country_name']);
    $strDBUserTimezone = trim($arrUserDetls[0]['timezones']);
    $strDBUserGMT = trim($arrUserDetls[0]['gmt']);
    $strDBUserIDDCode = trim($arrUserDetls[0]['idd_code']);
    $strDBUserMobile = trim($arrUserDetls[0]['mobile_number']);
    if (strlen($strDBUserMobile) <= 0) 
    {
        $strDBUserMobile = "---";
    }
}
?>
<div class="well">
    <h4 class="smaller">User Details</h4>
    <hr>
    <?php if ((is_array($arrUserDetls)) && (sizeof($arrUserDetls)) > 0) { ?>
        <div class="profile-user-info">
            <div class="profile-info-row">
                <div class="profile-info-name"> Username :</div>
                <div class="profile-info-value">
                    <span><?php echo $strDBUserName; ?></span>
                </div>
            </div>
            
            <div class="profile-info-row">
                <div class="profile-info-name"> Email :</div>
                <div class="profile-info-value">
                    <span><?php echo $strDBUserEmail; ?></span>
                </div>
            </div>
            
            <div class="profile-info-row">
                <div class="profile-info-name"> Nickname :</div>
                <div class="profile-info-value">
                    <span><?php echo $strDBUserNickName; ?></span>
                </div>
            </div>
            
            <div class="profile-info-row">
                <div class="profile-info-name"> First Name :</div>
                <div class="profile-info-value">
                    <span><?php echo $strDBUserFirstName; ?></span>
                </div>
            </div>
            
            <div class="profile-info-row">
                <div class="profile-info-name"> Last Name :</div>
                <div class="profile-info-value">
                    <span><?php echo $strDBUserLastName; ?></span>
                </div>
            </div>
            
            <div class="profile-info-row">
                <div class="profile-info-name width-40"> Secondary Email :</div>
                <div class="profile-info-value">
                    <span><?php echo $strDBUserSecondEmail; ?></span>
                </div>
            </div>
            
            <div class="profile-info-row">
                <div class="profile-info-name"> Country Name :</div>
                <div class="profile-info-value">
                    <span><?php echo $strDBUserCountryName; ?></span>
                </div>
            </div>
            
            <div class="profile-info-row">
                <div class="profile-info-name"> Timezone :</div>
                <div class="profile-info-value">
                    <span><?php echo $strDBUserTimezone; ?></span>
                </div>
            </div>
            
            <div class="profile-info-row">
                <div class="profile-info-name"> GMT :</div>
                <div class="profile-info-value">
                    <span><?php echo $strDBUserGMT; ?></span>
                </div>
            </div>
            
             <div class="profile-info-row">
                <div class="profile-info-name"> Mobile :</div>
                <div class="profile-info-value">
                    <span><?php echo $strDBUserIDDCode." ".$strDBUserMobile; ?></span>
                </div>
            </div>
            
            <div class="profile-info-row">
                <div class="profile-info-name"> Created On :</div>
                <div class="profile-info-value">
                    <span><?php echo $strDBUserCreatedOn; ?></span>
                </div>
            </div>
            
            <div class="profile-info-row">
                <div class="profile-info-name"> Last Login Datetime :</div>
                <div class="profile-info-value">
                    <span><?php echo $strDBUserLastLoginDTM; ?></span>
                </div>
            </div>
            
            <div class="profile-info-row">
                <div class="profile-info-name"> Last Login IP Address :</div>
                <div class="profile-info-value">
                    <span><?php echo $strDBUserLastLoginIP; ?></span>
                </div>
            </div>
            
        </div>






    <?php } else { ?>
        <div class="alert alert-danger">No details found.</div>
    <?php } ?>
</div>

