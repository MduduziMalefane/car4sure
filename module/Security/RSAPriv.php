<?php

class RSAPriv
{

    static public function encrypt($str, $private_key)
    {

        $encrypted = '';
        $pi_key = openssl_pkey_get_private($private_key);
        openssl_private_encrypt($str, $encrypted, $pi_key); // private key encryption

        $encrypted = base64_encode($encrypted);

        return $encrypted;
    }

    static public function decrypt($str, $public_key)
    {

        $decrypted = '';
        $pu_key = openssl_pkey_get_public($public_key);
        openssl_public_decrypt(base64_decode($str), $decrypted, $pu_key); // public key decryption

        return $decrypted;
    }

}