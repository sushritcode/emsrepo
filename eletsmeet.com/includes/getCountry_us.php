<?php
/*
require_once('../includes/global.inc.php');
require_once(CLASSES_PATH.'error.inc.php');
require_once(DBS_PATH.'DataHelper.php');
require_once(DBS_PATH.'objDataHelper.php');
*/

require_once(INCLUDES_PATH.'country.inc.php');

// Get the Current IP Address of USER
$CurrentUserIP = $_SERVER["REMOTE_ADDR"];
//echo "<br/>";
//$CurrentUserIP = "61.16.182.2";		//India
//$CurrentUserIP = "125.252.225.166";		//Singapore
$CurrentUserIP = "208.69.179.160";		//USA

//echo "<br/>";

if(isset($CurrentUserIP)) 
{
    $arrCountryDetails = fetchCountryDetails($CurrentUserIP , $objDataHelper);
    //print_r($arrCountryDetails);
    $strIPCountryCode = $arrCountryDetails[0]['countryCode'];
    //$strIPCountryName = $arrCountryDetails[0]['countryName'];
    
    if (trim($strIPCountryCode) == 'IN')
    {
        $strCurrencySymbol= '<span class="RupeeForadian">`</span>';
    }
    else
    {
        $strCurrencySymbol= '&#36;';
    }
}