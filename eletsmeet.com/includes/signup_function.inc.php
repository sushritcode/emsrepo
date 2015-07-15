<?php

function isUserEmailExists($email_address, $dataHelper)
{
    if (!is_object($dataHelper))
    {
        throw new Exception("signup_function.inc.php : isUserEmailExists : DataHelper Object did not instantiate", 104);
    }
    
    if (strlen(trim($email_address)) <= 0)
    {
        throw new Exception("signup_function.inc.php : isUserEmailExists : Missing Parameter email_address.", 141);
    }

    try
    {
      $dataHelper->setParam("'".$email_address."'","I");
      $dataHelper->setParam("STATUS","O");
      $arrIsEmailExists = $dataHelper->fetchRecords("SP",'IsUserEmailExists');
      $dataHelper->clearParams();
      return $arrIsEmailExists;
    }
    catch(Exception $e)
    {
      throw new Exception("signup_function.inc.php : isUserEmailExists : Failed : ".$e->getMessage(),145);
    }
}

function getUserId($dataHelper) {
    if (!is_object($dataHelper))
    {
	throw new Exception("signup_function.inc.php : getUserId : DataHelper Object did not instantiate", 104);
    }
    try {
	  $strSqlStatement = "SELECT MAX(user_id) FROM user_details";
	  $arrMaxId = $dataHelper->fetchRecords("QR", $strSqlStatement);
          $s1 =  $arrMaxId[0]['MAX(user_id)'];
	  $s2 = explode("usr",$s1);
	  $s3 = $s2[1]+1;
	  $s4 = strlen($s3);
	  switch ($s4) {
	      case 1: $userId = "usr000000".$s3; break;
	      case 2: $userId = "usr00000".$s3;  break;
	      case 3: $userId = "usr0000".$s3;   break;
	      case 4: $userId = "usr000".$s3;    break;
	      case 5: $userId = "usr00".$s3;     break;
	      case 6: $userId = "usr0".$s3;     break;
	      case 7: $userId = "usr".$s3;     break;
	      default: break;
	  }
    } catch (Exception $e)
    {
	throw new Exception("signup_function.inc.php : Get User Details Failed : " . $e->getMessage(), 1111);
    }
    return $userId;
}


function insUserDetails($user_id, $client_id, $partner_id, $email_address, $pwd, $nick_name, $first_name, $last_name, $country_name, $timezone, $gmt, $phone, $idd_code, $mobile, $reg_time, $is_admin, $status, $dataHelper)
{
    if (!is_object($dataHelper))
    {
        throw new Exception("signup_function.inc.php : insUserDetails : DataHelper Object did not instantiate", 104);
    }
    
    if (strlen(trim($user_id)) <= 0)
    {
        throw new Exception("signup_function.inc.php : insUserDetails : Missing Parameter user_id.", 142);
    }
   
    if (strlen(trim($client_id)) <= 0)
    {
        throw new Exception("signup_function.inc.php : insUserDetails : Missing Parameter client_id.", 142);
    }
    
    if (strlen(trim($partner_id)) <= 0)
    {
        throw new Exception("signup_function.inc.php : insUserDetails : Missing Parameter client_id.", 142);
    }
    
    if (strlen(trim($email_address)) <= 0)
    {
        throw new Exception("signup_function.inc.php : insUserDetails : Missing Parameter email_address.", 141);
    }
    
    if (strlen(trim($pwd)) <= 0)
    {
        throw new Exception("signup_function.inc.php : insUserDetails : Missing Parameter password.", 141);
    }
    
    if (strlen(trim($nick_name)) <= 0)
    {
        throw new Exception("signup_function.inc.php : insUserDetails : Missing Parameter nick_name.", 142);
    }
    
    if (strlen(trim($first_name)) <= 0)
    {
        throw new Exception("signup_function.inc.php : insUserDetails : Missing Parameter first_name.", 142);
    }
    
    if (strlen(trim($last_name)) <= 0)
    {
        throw new Exception("signup_function.inc.php : insUserDetails : Missing Parameter last_name.", 143);
    }
    
    if (strlen(trim($country_name)) <= 0)
    {
        throw new Exception("signup_function.inc.php : insUserDetails : Missing Parameter country_name.", 143);
    }
    
    if (strlen(trim($timezone)) <= 0)
    {
        throw new Exception("signup_function.inc.php : insUserDetails : Missing Parameter timezone.", 143);
    }
   
    if (strlen(trim($gmt)) <= 0)
    {
        throw new Exception("signup_function.inc.php : insUserDetails : Missing Parameter gmt.", 143);
    }
     
    if (strlen(trim($idd_code)) <= 0)
    {
        throw new Exception("signup_function.inc.php : insUserDetails : Missing Parameter idd_code.", 143);
    }
  
    if (strlen(trim($reg_time)) <= 0)
    {
        throw new Exception("signup_function.inc.php : insUserDetails : Missing Parameter reg_time.", 143);
    }
    if (strlen(trim($is_admin)) <= 0)
    {
        throw new Exception("signup_function.inc.php : insUserDetails : Missing Parameter is_admin.", 143);
    }
    try
    {
      $dataHelper->setParam("'".$user_id."'","I");
      $dataHelper->setParam("'".$client_id."'","I");
      $dataHelper->setParam("'".$partner_id."'","I");
      $dataHelper->setParam("'".$email_address."'","I");
      $dataHelper->setParam("'".$pwd."'","I");
      $dataHelper->setParam("'".$nick_name."'","I");
      $dataHelper->setParam("'".$first_name."'","I");
      $dataHelper->setParam("'".$last_name."'","I");
      $dataHelper->setParam("'".$country_name."'","I");
      $dataHelper->setParam("'".$timezone."'","I");
      $dataHelper->setParam("'".$gmt."'","I");
      $dataHelper->setParam("'".$phone."'","I");
      $dataHelper->setParam("'".$idd_code."'","I");
      $dataHelper->setParam("'".$mobile."'","I");
      $dataHelper->setParam("'".$reg_time."'","I");
      $dataHelper->setParam("'".$is_admin."'","I");
      $dataHelper->setParam("'".$status."'","I");
     
      $dataHelper->setParam("STATUS","O");
      $dataHelper->setParam("MESSAGE","O");
      $arrAddDetails = $dataHelper->putRecords("SP",'InsertUserDetails');
      $dataHelper->clearParams();
      return $arrAddDetails;
    }
    catch(Exception $e)
    {
      throw new Exception(" signup_function.inc.php : insUserDetails : Failed : ".$e->getMessage(),145);
    }
     
}

function updUserStatus($user_id, $dataHelper)
{
    if(!is_object($dataHelper))
    {
	throw new Exception("signup_function.inc.php : updUserStatus : DataHelper Object did not instantiate",104);
    }
    if (strlen(trim($user_id)) <= 0)
    {
        throw new Exception("signup_function.inc.php : updUserStatus : Missing Parameter user_id.", 142);
    }
    try
    {
       $strSqlQuery = "UPDATE user_details SET status ='1' WHERE user_id = '".trim($user_id)."'";
       $arrResult = $dataHelper->putRecords("QR",$strSqlQuery);
       return $arrResult;
    }
    catch(Exception $e)
    {
       throw new Exception("signup_function.inc.php : Error in updUserStatus.".$e->getMessage(),734);
    }
    
}

?>
