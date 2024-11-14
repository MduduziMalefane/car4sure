<?php

class RSAPub
{

    /**
     * @ param string $STR data to be encrypted
     * @param string $public_ Key public key
     * @return string
     */
    static public function encrypt($str, $public_key)
    {

        $encrypted = '';
        $pu_key = openssl_pkey_get_public($public_key);
        openssl_public_encrypt($str, $encrypted, $pu_key); // public key encryption

        $encrypted = base64_encode($encrypted);

        return $encrypted;
    }

    /**
     * Decryption
     * @ param string $STR data to decrypt
     * @param string $private_ Key private key
     * @return string
     */
    static public function decrypt($str, $private_key)
    {

        $decrypted = '';
        $pi_key = openssl_pkey_get_private($private_key);
        openssl_private_decrypt(base64_decode($str), $decrypted, $pi_key); // decrypt the private key

        return $decrypted;
    }

}

