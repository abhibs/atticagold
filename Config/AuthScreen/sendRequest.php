<?php

	include("encryptAndDecrypt.php");

	define('AUTHBRIDGE_USERNAME', "username:production@atticagoldcompany.com");
	define('TOKEN', "India@2608");
	$header = array(AUTHBRIDGE_USERNAME, "Content-Type:application/json");
	
	function sendRequest($url, $body){  
		global $header;
		
		// ENCRYPT THE REQUEST BODY	
		$jsonData = json_encode($body);
		$iv = AesCipher::getIV(); // GENERATING RANDOM IV STRING
		$encrypted = AesCipher::encrypt(TOKEN, $iv, $jsonData);
		$encryptedData = ['requestData' => $encrypted];
		$jsonEncryptedData = json_encode($encryptedData);
		
		// SEND THE REQUEST
		$chs = curl_init($url);
		curl_setopt($chs, CURLOPT_HTTPHEADER, $header);
		curl_setopt($chs, CURLOPT_POSTFIELDS, $jsonEncryptedData);
		curl_setopt($chs, CURLOPT_RETURNTRANSFER, true);
		$res = curl_exec($chs);
		curl_close($chs);
		
		// DECRYPT THE RESPONSE RECEIVED
		$data = json_decode($res, true);
		$decrypted = AesCipher::decrypt(TOKEN, $data['responseData']);
		$decryptedData = json_decode($decrypted, true);
		
		return $decryptedData;
	}

?>