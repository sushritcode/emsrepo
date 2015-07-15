<?php

function fetchCountryDetails($userIP , $dataHelper)
{
   try
   {
      if(!is_object($dataHelper))
      {
         throw new Exception("Error : Connecting to database" , 104);
      }
      $NumberIP = convertIpToNumber($userIP);
      $getCountryDetails = "SELECT * FROM ip2country WHERE ".$NumberIP." between begin_number and end_number";
      $arrCountry = $dataHelper->fetchRecords("QR" , $getCountryDetails);
      if (DEBUG_LOG == 1) error_log(date("Y-m-d H:i:s")." , ".$getCountryDetails."\n".$getCountryName."\n".$countryName."\n\n\n" , 3 , LOGS_PATH."functions/IP_Country/".date('Y-m-d').".log");
      //return $arrCountry[0]['countryName'];
      return $arrCountry;
   }
   catch(Exception $e)
   {
      throw new Exception($e->getMessage() , $e->getCode());
   }
}

function convertIpToNumber($userIP)
{
   try
   {
      $arrIP = explode("." , $userIP);
      $NumberIP = ( ( $arrIP[0] * ( 256 * 256 * 256 ) ) + ( $arrIP[1] * ( 256 * 256 ) ) + ( $arrIP[2] * ( 256 ) ) + ( $arrIP[3] * ( 1 ) ) );
      return $NumberIP;
   }
   catch(Exception $e)
   {
      throw new Exception($e->getMessage() , $e->getCode());
   }
}



