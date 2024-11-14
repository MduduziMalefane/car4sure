<?php

class ValidationClass
{

    public static function ValidateNormalText($value)
    {
        return trim($value) != '';
    }

    public static function ValidateText($value)
    {
        if (trim($value) == '' || !preg_match("/^[a-zA-Z ]*$/", $value)) {
            return false;
        }
        return true;
    }

    public static function ValidateAlphabet($value)
    {

        if (trim($value) == '' || !preg_match("/^[a-zA-Z]*$/", $value)) {
            return false;
        }
        return true;
    }

    public static function ValidateAlphaNumeric($value)
    {
        if (trim($value) == '' || !preg_match("/^[a-zA-Z0-9 ]*$/", $value)) {
            return false;
        }

        return true;
    }

    public static function ValidateFileName($value)
    {
        if (trim($value) == '' || !preg_match('/^[a-zA-Z0-9\-\.\(\)\[\]\!\@\#\%\&\+\_\{\}  ]*$/', $value)) {
            return false;
        }

        $len = strlen($value);
        if ($len > 0 && substr($value, 0, 1) == '.') {
            return false;
        }
        return true;
    }

    public static function ValidateGUID($value)
    {
        $res = false;
        if (strlen($value) == 36 && ValidationClass::ValidateAlphaNumeric(str_replace("-", "A", $value))) {
            $res = true;
        }
        return $res;
    }

    public static function ValidateSHA256($value)
    {
        $res = false;
        if (strlen($value) == 64 && ValidationClass::ValidateAlphaNumeric($value)) {
            $res = true;
        }
        return $res;
    }

    public static function ValidateMD5($value)
    {
        $res = false;
        if (strlen($value) == 32 && ValidationClass::ValidateAlphaNumeric($value)) {
            $res = true;
        }
        return $res;
    }

    public static function ValidateMonth($value)
    {
        if (!ValidationClass::ValidateNumber($value) || strlen(trim($value)) > 2) {
            return false;
        }

        $val = intval($value); //[0]=='0'? $value[1] :$value);

        if ($val != null && ($val > 0 && $val < 13)) {
            return true;
        }

        return false;
    }

    public static function ValidateDay($value)
    {
        if (!ValidationClass::ValidateNumber($value) || strlen(trim($value)) > 2) {
            return false;
        }

        $val = intval($value); //[0]=='0'? $value[1] :$value);

        if ($val != null && ($val > 0 && $val < 32)) {
            return true;
        }
        return false;
    }

    public static function ValidateTime($value)
    {

        $d = explode(":", $value);

        if (count($d) == 2 || count($d) == 3) {

            if (ValidationClass::ValidateNumber($d[0]) && ValidationClass::ValidateNumber($d[1]) && ($d[0] >= 0 && $d[0] < 24) && ($d[1] >= 0 && $d[1] < 59)) {

                if (count($d) == 3) {
                    if (ValidationClass::ValidateNumber($d[2]) && ($d[2] >= 0 && $d[2] < 24)) {
                        return true;
                    }
                } else {
                    return true;
                }
            }
        }

        return false;
        //return ValidationClass::ValidateDate1($value) || ValidationClass::ValidateDate2($value);
    }

    public static function ValidateDateTime($value)
    {
        $dateTime = explode(" ", $value);

        if (count($dateTime) == 2) {
            return ValidationClass::ValidateTime($dateTime[1]) && ValidationClass::ValidateDate($dateTime[0]);
        }

        return ValidationClass::ValidateDate($value);
    }

    public static function ValidateDate($value)
    {
        return ValidationClass::ValidateDate1($value) || ValidationClass::ValidateDate2($value);
    }

    public static function ValidateDate1($value)
    {
        $vl = explode("/", $value);

        if (count($vl) != 3 || !ValidationClass::ValidateFullNumber($vl[0]) || !ValidationClass::ValidateNumber($vl[1]) || !ValidationClass::ValidateNumber($vl[2])) {
            return false;
        }

        if (strlen($vl[0]) != 4 || !ValidationClass::ValidateMonth($vl[1]) || !ValidationClass::ValidateDay($vl[2])) {
            return false;
        }

        return true;
    }

    public static function ValidateDate2($value)
    {
        $vl = explode("-", $value);

        if (count($vl) != 3 || !ValidationClass::ValidateFullNumber($vl[0]) || !ValidationClass::ValidateNumber($vl[1]) || !ValidationClass::ValidateNumber($vl[2])) {
            return false;
        }

        if (strlen($vl[0]) != 4 || !ValidationClass::ValidateMonth($vl[1]) || !ValidationClass::ValidateDay($vl[2])) {
            return false;
        }

        return true;
    }

    public static function ValidateTimeFull1($TimeValue)
    {

        $vl = explode(":", $TimeValue);

        if (count($vl) != 3 || !ValidationClass::ValidateHours($vl[0]) || !ValidationClass::ValidateMinutes($vl[1]) || !ValidationClass::ValidateSeconds($vl[2])) {
            return false;
        }

        return true;
    }

    public static function ValidateHours($HourValue)
    {
        if (ValidationClass::ValidateNumber($HourValue)) {
            $val = intval($HourValue);
            return $val >= 0 && $val <= 23;
        }
        return false;
    }

    public static function ValidateMinutes($MinuteValue)
    {
        if (ValidationClass::ValidateNumber($MinuteValue)) {
            $val = intval($MinuteValue);
            return $val >= 0 && $val <= 59;
        }
        return false;
    }

    public static function ValidateSeconds($SecondValue)
    {
        if (ValidationClass::ValidateNumber($SecondValue)) {
            $val = intval($SecondValue);
            return $val >= 0 && $val <= 59;
        }
        return false;
    }

    public static function ValidateNumber($value)
    {
        if (trim($value) == '' || !preg_match("/^[0-9]*$/", $value)) {
            //TraceLog("Error value: $value");
            return false;
        }

        return true;
    }

    public static function ValidateDecimal($value)
    {

        if (trim($value) == '') {
            return false;
        }
        if (!is_numeric($value)) {
            return false;
        }
        return true;
    }

    public static function ValidateNumberRange($value, $min, $max)
    {

        if (!ValidationClass::ValidateNumber($value) || $value < $min || $value > $max) {
            return false;
        }
        return true;
    }

    public static function ValidateDecimalRange($value, $min, $max)
    {
        if (!ValidationClass::ValidateDecimal($value) || $value < $min || $value > $max) {
            return false;
        }

        return true;
    }

    public static function ValidateFullNumber($text)
    {
        if ((empty($text) && $text != "0") || !preg_match("/^[1-9][0-9]*$/", $text)) {
            return false;
        }
        return true;
    }

    public static function ValidateEmail($value)
    {
        if (trim($value) == '' || !filter_var($value, FILTER_VALIDATE_EMAIL)) {
            return false;
        }

        return true;
    }

    public static function ValidateContactNumber($value)
    {
        $len = strlen($value);

        if ($len < 8 || $len > 15 || !ValidationClass::ValidateNumber($value)) {
            return false;
        }

        return true;
    }

    public static function isDomain($domain_name)
    {
        return (preg_match("/^([a-z\d](-*[a-z\d])*)(\.([a-z\d](-*[a-z\d])*))*$/i", $domain_name) //valid chars check
            && preg_match("/^.{1,253}$/", $domain_name) //overall length check
            && preg_match("/^[^\.]{1,63}(\.[^\.]{1,63})*$/", $domain_name)); //length of each label
    }

    public static function ValidateUserName($value)
    {
        $e = trim($value);

        if (empty($e) && $value != "0") {
            return false;
        }

        if (!ValidationClass::isLetter($e[0])) {
            return false;
        }

        $cnt = strlen($e);
        for ($i = 0; $i < $cnt; $i++) {
            $ee = $e[$i];
            if (!ValidationClass::isLetter($ee) && !ValidationClass::isDigit($ee) && $ee != '_') {
                return false;
            }
        }

        return true;
    }

    public static function isLetter($e)
    {
        return ($e >= 'a' && $e <= 'z') || ($e >= 'A' && $e <= 'Z');
    }

    public static function isDigit($e)
    {

        return ($e >= '0' && $e <= '9');
    }

    public static function InvalidRange($value, $min, $max, $CheckSpace = true)
    {
        if ($CheckSpace) {
            $len = strlen(str_replace(' ', '', $value));
        } else {
            $len = strlen($value);
        }

        return $len < $min || $len > $max;
    }

    public static function getIPAddress()
    {
        $ipaddress = '';
        if (getenv('HTTP_CLIENT_IP')) {
            $ipaddress = getenv('HTTP_CLIENT_IP');
        } else if (getenv('HTTP_X_FORWARDED_FOR')) {
            $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
        } else if (getenv('HTTP_X_FORWARDED')) {
            $ipaddress = getenv('HTTP_X_FORWARDED');
        } else if (getenv('HTTP_FORWARDED_FOR')) {
            $ipaddress = getenv('HTTP_FORWARDED_FOR');
        } else if (getenv('HTTP_FORWARDED')) {
            $ipaddress = getenv('HTTP_FORWARDED');
        } else if (getenv('REMOTE_ADDR')) {
            $ipaddress = getenv('REMOTE_ADDR');
        } else {
            $ipaddress = 'UNKNOWN';
        }
        return $ipaddress;
    }

}

