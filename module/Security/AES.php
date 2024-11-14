<?php

class AES
{

    //Set AES secret key
    private static $aes_key = '2SdTbCEXhkzft45'; // fill in the secret key agreed by the front end and back end here

    /**
     * Encryption
     * @ param string $STR data to be encrypted
     * @ return bool|string encrypted data
     */
    public static function encrypt($str)
    {
        $data = openssl_encrypt($str, 'AES-128-ECB', self::$aes_key, OPENSSL_RAW_DATA);
        $data = base64_encode($data);
        
        return $data;
    }
    
       /**
     * Encryption
     * @ param string $STR data to be encrypted
     * @ return bool|string encrypted data with user as key
     */
    public static function encrypt_user($str,$UserInfo)
    {
        $data = openssl_encrypt($str, 'AES-128-ECB', self::$aes_key."*(".$UserInfo->UserID.")%", OPENSSL_RAW_DATA);
        $data = base64_encode($data);
        return $data;
    }
    
    
    

    /**
     * Decryption
     * @ param string $STR data to decrypt
     * @ return string decrypted data
     */
    public static function decrypt($str)
    {
        $decrypted = openssl_decrypt(base64_decode($str), 'AES-128-ECB', self::$aes_key, OPENSSL_RAW_DATA);
        return $decrypted;
    }
    
     /**
     * Decryption
     * @ param string $STR data to decrypt
     * @ return string decrypted data
     */
    public static function decrypt_user($str,$UserInfo)
    {
        $decrypted = openssl_decrypt(base64_decode($str), 'AES-128-ECB', self::$aes_key."*(".$UserInfo->UserID.")%", OPENSSL_RAW_DATA);
        return $decrypted;
    }

}
