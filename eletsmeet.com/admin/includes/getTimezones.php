<?php 
require_once('../../includes/global.inc.php');
require_once('config.inc.php');
require_once(ADM_CLASSES_PATH . 'admin_error.inc.php');
require_once(DBS_PATH . 'DataHelper.php');
require_once(DBS_PATH . 'objDataHelper.php');
$country_code = $_GET['cCode'];

if(isset($country_code)) 
{
    if (!is_object($objDataHelper))
    {
        throw new Exception("getTimezones.php : getCountryTimezone : DataHelper Object did not instantiate", 104);
    }
    
    try
    {
        $str1 = "";
        $strSqlStatement = "SELECT timezones, gmt FROM country_timezones WHERE country_code = '".trim($country_code)."' AND ct_status = '1' ORDER BY gmt";
        $arrList = $objDataHelper->fetchRecords("QR", $strSqlStatement);
        foreach($arrList as $key => $value)
        {
		$str1 = $str1.$value['timezones']." ".$value['gmt'].",";
        }
        echo $str1 = substr($str1,0,(strLen($str1)-1));
    }
    catch (Exception $e)
    {
        throw new Exception("getTimezones.php : Fetch Time zone Failed : " . $e->getMessage(), 1107);
    }
}
?>
