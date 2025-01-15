<?php

	/* **   ENCRYPTION AND DECRYPTION CODE   ** */
	define('OPENSSL_CIPHER_NAME', 'aes-128-cbc');
	define('CIPHER_KEY_LEN', 16); //128 bits
	class AesCipher {
		private static function fixKey($key) {
			if (strlen($key) < CIPHER_KEY_LEN) {
				//0 pad to len 16
				return str_pad("$key", CIPHER_KEY_LEN, "0");
			}
			if (strlen($key) > CIPHER_KEY_LEN) {
				//truncate to 16 bytes
				return substr($key, 0, CIPHER_KEY_LEN);
			}
			return $key;
		}
		static function getIV() {
			$iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length(OPENSSL_CIPHER_NAME));
			return $iv;
		}
		/**
			* Encrypt data using AES Cipher (CBC) with 128 bit key
			*
			* @param type $key - key should be 16 bytes long (128 bits)
			* @param type $iv - initialization vector
			* @param type $data - data to encrypt
			* @return encrypted data in base64 encoding with "iv" attached at end after a colon":"
		*/
		static function encrypt($key, $iv, $data) {
			$key=hash ( 'sha512' , $key,false );
			$key=substr($key,0,16);
			$encodedEncryptedData = base64_encode(openssl_encrypt($data, OPENSSL_CIPHER_NAME,AesCipher::fixKey($key), OPENSSL_RAW_DATA, $iv));
			$encodedIV = base64_encode($iv);
			$encryptedPayload = $encodedEncryptedData.":".$encodedIV;
			return $encryptedPayload;
		}
		/**
			* Decrypt data using AES Cipher (CBC) with 128 bit key
			*
			* @param type $key - key should be 16 bytes long (128 bits)
			* @param type $data - data to be decrypted in base64 encoding with iv attached at the end after a colon":"
			* @return decrypted data
		*/
		static function decrypt($key, $data) {
			$key=hash('sha512',$key,false );
			$key=substr($key,0,16);
			$parts = explode(':', $data); //Separate Encrypted data from iv.
			$encrypted = $parts[0];
			$iv = $parts[1];
			$decryptedData = openssl_decrypt(base64_decode($encrypted),OPENSSL_CIPHER_NAME,AesCipher::fixKey($key), OPENSSL_RAW_DATA, base64_decode($iv));
			return $decryptedData;
		}
	};

?>