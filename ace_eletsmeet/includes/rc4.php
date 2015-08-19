<?php
/******************************************************************************
 *
 *	Copyright (c) 2004 Quadridge Technologies
 *	All rights reserved.
 *
 *	File: 
 *			RC4.php
 *
 *	Description: 
 *		This file needs to be included by files that need to support 
 *		mundu encryption. Mundu encryption is based on the RC4 stream 
 *		encryption by RSA Labs. However, we've built a custom layer over
 *		RC4 which makes sure that the encrypted data for the same contents
 *		is not constant. This is achieved by using a dynamic key to 
 *		encrypt data. Data with regards to the dynamicity of the key is 
 *		derived by using a custom algorithm.
 *
 *	History:
 *		17 April, 2004	Created by Sylbert Lobo
 *
 *	Standard List of Steps used to Encrypt  / Decrypt Data:
 *		-All data should be contained in a variable named DATA
 *		-This variable is automatically decrypted and internal variables
 *		 are innitialized
 *		-A static key is stored by the client and the server.
 *		-This key is hashed.
 *		-A dynamic integer is concatenated to the hashed key and the
 *		 resulting value is hashed again.
 *		-This value is used to encrypt data.
 *		-The dynamic integer is stored at a dynamic location
 *		 within the encrypted data.
 *		-The location is dynamically generated based on a custom algorithm 
 *		-The reverse of this process is followed for decryption
 *
 *****************************************************************************/


class RC4
{
	var $key = "a92538a309f9d9164a82917e31ef0026dca328ac3f12c87ff138e0a1d561f79e39dec82ae96ab2e1ceeefea6222bd65ff39d2956e831a9fff0eb41605a9363b1";
	
	/*******************************************************************
	 *	
	 * FUNCTION:
	 *		rc4_encrypt
	 *	
	 * DESCRIPTION:
	 *		This function is used to encrypt/decrypt data using the 
	 * 		RC4 encryption statndard
	 *	
	 * PARAMETERS:
	 *		$pwd	-	The key to use for encryption / decryption
	 *		$data	-	Data to be encrypted / decrypted
	 *	
	 * RETURNED:
	 *		Encrypted / Decrypted Data
	 *	
	 *******************************************************************/
	function rc4_encrypt($pwd, $data, $ispwdHex = 0)
	{
		if ($ispwdHex)
				$pwd = @pack('H*', $pwd); // valid input, please!

			$key[] = '';
			$box[] = '';
			$cipher = '';

			$pwd_length = strlen($pwd);
			$data_length = strlen($data);

			for ($i = 0; $i < 256; $i++)
			{
				$key[$i] = ord($pwd[$i % $pwd_length]);
				$box[$i] = $i;
			}
			for ($j = $i = 0; $i < 256; $i++)
			{
				$j = ($j + $box[$i] + $key[$i]) % 256;
				$tmp = $box[$i];
				$box[$i] = $box[$j];
				$box[$j] = $tmp;
			}
			for ($a = $j = $i = 0; $i < $data_length; $i++)
			{
				$a = ($a + 1) % 256;
				$j = ($j + $box[$a]) % 256;
				$tmp = $box[$a];
				$box[$a] = $box[$j];
				$box[$j] = $tmp;
				$k = $box[(($box[$a] + $box[$j]) % 256)];
				$cipher .= chr(ord($data[$i]) ^ $k);
			}
			return $cipher;
	}
	
	/*******************************************************************
	 *	
	 * FUNCTION:
	 *		rc4_decrypt
	 *	
	 * DESCRIPTION: 
	 *		This function is used to decrypt data using the 
	 * 		RC4 encryption statndard
	 *	
	 * PARAMETERS:  
	 *		$pwd	-	The key to use for decryption
	 *		$data	-	Data to be decrypted
	 *	
	 * RETURNED:    
	 *		Decrypted Data
	 *	
	 *******************************************************************/
	function rc4_decrypt ($pwd, $data, $ispwdHex = 0)
	{
		return $this->rc4_encrypt($pwd, $data, $ispwdHex);
	}
}
?>