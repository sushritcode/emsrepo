<?php
require_once "rc4.php";
require_once "global.inc.php";
class Utilities
{
	function hex2bin($data)
	{
		$len = strlen($data);
		return pack("H" . $len, $data);
	}

	function encryptData($data, $key, $status = "1")
	{
		if ($status == "1")
		{
			$key = $this->GenerateSecretKey($key);
		}
		$objRC4 = new RC4();
		$rc4Data = $objRC4->rc4_encrypt($key, $data);
		$encData = bin2hex($rc4Data);
		return $encData;
	}

	function decryptData($data, $key, $status = "1")
	{
		if ($status == "1")
		{
			$key = $this->GenerateSecretKey($key);
		}
		$objRC4 = new RC4();
		//$urldecoded_Data=urldecode($data);
		$rc4Data = $this->hex2bin($data);
		$decryptData = $objRC4->rc4_decrypt($key,$rc4Data);
		return $decryptData;
	}

	function GenerateSecretKey($key)
	{
		$key = SECRET_KEY.":".$key;
		return $key;
	}

	function CallScript($url, $flagHeader=0, $flagSSL=false)
	{	
		//The Generalized function to call remote url through CURL and get the Response.
		try
		{
			$curlHwd = curl_init();	//Also check if curl lib is installed?
			if(!$curlHwd)
			{
                                                            throw new Exception("CURL Initialization Error", 140);
                                                            exit;
			}
			curl_setopt($curlHwd, CURLOPT_URL, $url);
			curl_setopt($curlHwd, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($curlHwd, CURLOPT_HEADER, $flagHeader);
			curl_setopt($curlHwd, CURLOPT_SSL_VERIFYHOST, $flagSSL);
			$result = curl_exec($curlHwd);
			curl_close($curlHwd);
		}
		catch (Exception $e)
		{
			throw new Exception("message".$e->getMessage(), $e->getCode());
		}
		return $result;
	}


	function TestencryptData($data, $key)
	{
		$objRC4 = new RC4();
		$rc4Data = $objRC4->rc4_encrypt($key, $data);
		$encData = bin2hex($rc4Data);
		return $encData;
	}

	function TestdecryptData($data, $key)
	{
		$objRC4 = new RC4();
		//$urldecoded_Data=urldecode($data);
		$rc4Data = $this->hex2bin($data);
		$decryptData = $objRC4->rc4_decrypt($key,$rc4Data);
		return $decryptData;
	}

	/*------- Used for UAM Decrypt & Encrypt -------*/
	function GenerateSecretKeyUAM($key)
	{
		$key = SECRET_KEY_UAM.":".$key;
		return $key;
	}

	function encryptDataUAM($data, $key, $status = "1")
	{
		if ($status == "1")
		{
			 $key = $this->GenerateSecretKeyUAM($key);
		}
		$objRC4 = new RC4();
		$rc4Data = $objRC4->rc4_encrypt($key, $data);
		$encData = bin2hex($rc4Data);
		return $encData;
	}

	function decryptDataUAM($data, $key, $status = "1")
	{
		if ($status == "1")
		{
			$key = $this->GenerateSecretKeyUAM($key);
		}
		$objRC4 = new RC4();
		$rc4Data = $this->hex2bin($data);
		$decryptData = $objRC4->rc4_decrypt($key,$rc4Data);
		return $decryptData;
	}

	/*--------------*/

}
?>