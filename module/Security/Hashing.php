<?php

class Hashing
{

    public static function cGuid($value = '')
    {
        mt_srand(round(microtime(true) * 1000)); //optional for php 4.2.0 and up.
        $charid = strtoupper(md5(uniqid(rand(), true) . $value));
        $hyphen = chr(45); // "-"
        $uuid = substr($charid, 0, 8) . $hyphen
            . substr($charid, 8, 4) . $hyphen
            . substr($charid, 12, 4) . $hyphen
            . substr($charid, 16, 4) . $hyphen
            . substr($charid, 20, 12);

        return strtoupper($uuid);
    }

    public static function getGuid($hasBraces = false)
    {

        mt_srand(round(microtime(true) * 1000)); //optional for php 4.2.0 and up.
        $charid = strtoupper(md5(uniqid(rand(), true)));
        $hyphen = chr(45); // "-"
        $uuid = chr(123)
            . substr($charid, 0, 8) . $hyphen
            . substr($charid, 8, 4) . $hyphen
            . substr($charid, 12, 4) . $hyphen
            . substr($charid, 16, 4) . $hyphen
            . substr($charid, 20, 12)
            . chr(125);
        $out = $uuid;

        if (!$hasBraces) {
            $out = substr($out, 1, 36);
        }
        return strtoupper($out);
    }


    public static function hashData($value, $type = 'md5', $salt = null, $saltType = 'md5')
    {
        $hash = hash($type, $value);
        if ($salt != null) {
            $hash = hash($saltType, $hash . $salt);
        }
        return strtoupper($hash);
    }

    public static function systemHash($value, $hashUsed = null)
    {
        $hash = $hashUsed == null ? $GLOBALS["config"]->hashUsed : $hashUsed;
        $salt = $GLOBALS["config"]->hashHasSalt ? $GLOBALS["config"]->hashSaltKey : null;
        return self::hashData($value, $hash, $salt, $hash);
    }
}