<?php

class RNG
{


    public static function random($length = 16)
    {
        $str = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $rand = '';
        for ($i = 0; $i < $length; $i++) {
            $rand .= $str[mt_rand(0, 61)];
        }
        return $rand;
    }

    public static function randomNum($length = 16)
    {
        $str = '0123456789';
        $rand = '';
        for ($i = 0; $i < $length; $i++) {
            $rand .= $str[mt_rand(0, 9)];
        }
        return $rand;
    }

    public static function randomLetter($length = 16)
    {
        $str = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $rand = '';
        for ($i = 0; $i < $length; $i++) {
            $rand .= $str[mt_rand(0, 51)];
        }
        return $rand;
    }

    public static function randomLowerLetter($length = 16)
    {
        $str = 'abcdefghijklmnopqrstuvwxyz';
        $rand = '';
        for ($i = 0; $i < $length; $i++) {
            $rand .= $str[mt_rand(0, 25)];
        }
        return $rand;
    }

    public static function randomUpperLetter($length = 16)
    {
        $str = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $rand = '';
        for ($i = 0; $i < $length; $i++) {
            $rand .= $str[mt_rand(0, 25)];
        }
        return $rand;
    }

    public static function randomSpecial($length = 16)
    {
        $str = '!@#$%^&*()_+-=';
        $rand = '';
        for ($i = 0; $i < $length; $i++) {
            $rand .= $str[mt_rand(0, 13)];
        }
        return $rand;
    }


}