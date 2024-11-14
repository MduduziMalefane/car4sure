<?php

class UtilityClass
{

    const TEXTMODE_NORMAL = 0;
    const TEXTMODE_UPPER = 1;
    const TEXTMODE_LOWER = 2;
    const TEXTMODE_CAP_FIRST_LETTER = 3;
    const TEXTMODE_CAP_EACH_WORD = 4;
    const TEXTMODE_ALPHANUM_ONLY = 5;

    public static function Session($sessionKey)
    {
        
        if (isset($_SESSION[$sessionKey])) {
            return trim($_SESSION[$sessionKey]);
        }

        return '';
    }

    public static function setSession($sessionKey, $value)
    {
        $_SESSION[$sessionKey] = trim($value);
    }

    public static function Cookie($cookieKey)
    {
        if (isset($_COOKIE[$cookieKey])) {
            return trim($_COOKIE[$cookieKey]);
        }
        return '';
    }

    public static function setCookie($cookieKey, $value)
    {
        @setcookie($cookieKey, trim($value), time() + $GLOBALS['config']->sessExpire, '/');
    }

    public static function delCookie($cookieKey)
    {
        @setcookie($cookieKey, '', 0, '/');
    }

    public static function Sanitize($value)
    {
        //filter_input($type, FILTER)
        $vv = str_replace("'", '`', $value);

        return $vv;
    }

    public static function Post($postKey)
    {
        if (isset($_POST[$postKey])) {
            return trim(UtilityClass::Sanitize($_POST[$postKey]));
        }
        return '';
    }

    public static function PostJson($postKey)
    {
        if (isset($_POST[$postKey])) {

            $result = @json_decode(trim($_POST[$postKey]));

            if ($result != null) {
                return $result;
            }
        }
        return new stdClass();
    }

    public static function Get($getKey)
    {
        if (isset($_GET[$getKey])) {
            return trim(UtilityClass::Sanitize($_GET[$getKey]));
        }
        return '';
    }

    public static function GetJson($getKey)
    {
        if (isset($_GET[$getKey])) {

            $result = @json_decode(trim($_GET[$getKey]));

            if ($result != null) {
                return $result;
            }
        }
        return new stdClass();
    }

    public static function safeClassObject(&$key, $name, $default = '')
    {
        if ($key != null) {
            if (!isset($key->$name)) {
                $key->$name = $default;
            }
        }
    }

    public static function removeSpaces($value)
    {
        return trim(str_replace(' ', '', $value));
    }

    public static function textValue($value, $textMode)
    {
        switch ($textMode) {

            case UtilityClass::TEXTMODE_UPPER:
                $value = strtoupper($value);
                break;

            case UtilityClass::TEXTMODE_LOWER:
                $value = strtolower($value);
                break;

            case UtilityClass::TEXTMODE_CAP_FIRST_LETTER:
                $value = UtilityClass::ToFirstUpper($value);
                break;

            case UtilityClass::TEXTMODE_CAP_EACH_WORD:
                $value = UtilityClass::ToWordUpper($value);
                break;

            case UtilityClass::TEXTMODE_ALPHANUM_ONLY:
                $value = UtilityClass::ToAlphaNum($value);
                break;
        }

        return trim($value);
    }

    public static function ToAlphaNum($value)
    {
        $temp = "";
        for ($i = 0; $i < strlen($value); $i++) {
            $char = $value[$i];
            if (($char >= 'A' && $char <= 'Z') || ($char >= 'a' && $char <= 'z') || ($char >= '0' && $char <= '9')) {
                $temp .= $char;
            }
        }

        return trim($temp);
    }

    public static function ToAlphaNumSpace($value)
    {
        $temp = "";
        for ($i = 0; $i < strlen($value); $i++) {
            $char = $value[$i];
            if (($char >= 'A' && $char <= 'Z') || ($char >= 'a' && $char <= 'z') || ($char >= '0' && $char <= '9') || $char == ' ') {
                $temp .= $char;
            }
        }

        return trim($temp);
    }

    public static function removeTagNewLine($value)
    {
        return UtilityClass::toNewLine(strip_tags($value));
    }

    public static function toNewLine($value)
    {
        $val = str_replace("\r\n", "<br/>", $value);
        return str_replace("\n", "<br/>", str_replace("\r", "<br/>", $val));
    }

    public static function safeValue(&$key, $name, $default = '', $textMode = 0, $replaceSpace = 0)
    {
        if ($key != null) {
            if (isset($key->$name)) {
                $key->$name = UtilityClass::textValue($key->$name, $textMode);
                $key->$name = $replaceSpace == 1 ? UtilityClass::removeSpaces($key->$name) : trim($key->$name);
            } else {
                $key->$name = $default;
            }
        }
    }

    public static function safeObject($value, $default = null)
    {
        if (isset($value)) {
            return $value;
        }
        return $default;
    }

    public static function ToFirstUpper($value)
    {
        $len = strlen($value);

        if ($len > 1) {
            return strtoupper($value[0]) . strtolower(substr($value, 1, $len - 1));
        }
        return $value;
    }

    public static function ToWordUpper($value)
    {

        $v = explode(' ', trim($value));

        $Final = "";
        $c = count($v);

        for ($i = 0; $i < $c; $i++) {
            $Final .= UtilityClass::ToFirstUpper($v[$i]) . " ";
        }

        return trim($Final);
    }

    public static function stringContains($value, $contain)
    {
        return (strpos($value, $contain) !== false);
    }

    public static function FileExtention($fileName)
    {
        $extension = explode('.', $fileName);
        $ext = $extension[sizeof($extension) - 1];

        return trim(strtolower($ext));
    }

    public static function DeleteFile($file)
    {
        if (file_exists($file)) {
            @unlink($file);
            return true;
        }

        return false;
    }

    public static function NoSpace($value)
    {
        return str_replace(" ", "", $value);
    }

    public static function mapArray(&$MapObject, $MapInput)
    {
        if (is_array(($MapInput))) {
            foreach ($MapInput as $key => $value) {
                if (isset($MapObject->$key)) {
                    $MapObject->$key = $value;
                }
            }
        }
    }

    public static function Deserialize($value, $defaultClass = null)
    {
        $IsValid = $value != null && strlen($value) > 0;
        if ($defaultClass != null && $IsValid) {
            try {
                UtilityClass::mapArray($defaultClass, json_decode($value, true));
                return $defaultClass;
            } catch (Exception $ex) {
                return $defaultClass;
            }
        } else if ($IsValid) {
            try {
                return json_decode($value);
            } catch (Exception $ex) {

            }
        }

        return null;
    }

    public static function JSONRequest()
    {
        try {
            return json_decode(UtilityClass::InputData());
        } catch (Exception $e) {

        }

        return null;
    }

    public static function InputData()
    {
        try {
            return file_get_contents('php://input');
        } catch (Exception $e) {

        }
        return "";
    }

    public static function RequestMethod()
    {
        return strtoupper(UtilityClass::safeObject($_SERVER["REQUEST_METHOD"], "GET"));
    }

    public static function GetLettersOnly($inputString)
    {
        $lettersOnly = preg_replace("/[^A-Za-z]/", "", $inputString);
        return $lettersOnly;
    }

    public static function GetFirstLetters($inputString, $length)
    {
        if (strlen($inputString) >= $length) {
            return $inputString;
        }

        return substr($inputString, 0, $length);
    }

    public static function GetFirstLettersOnly($inputString, $length)
    {

        $$inputString = UtilityClass::GetLettersOnly($inputString);
        if (strlen($$inputString) >= $length) {
            return $$inputString;
        }

        return substr($$inputString, 0, $length);
    }

    public static function ToDateTime($input): \DateTime
    {
        $inputDate = str_replace("/", "-", $input);

        if (ValidationClass::ValidateDate2($inputDate)) {
            return DateTime::createFromFormat('Y-m-d', $input);
        }

        return new DateTime();
    }

    public static function formatCurrancy($value, $symbol = "")
    {
        $main = $value;
        if (ValidationClass::ValidateDecimal($value)) {
            $main = round($main, 2);
            $ex = explode(".", $main); // $value();

            $xm = "";

            $skip = 0;
            $v1 = $ex[0];
            for ($a = strlen($v1) - 1; $a >= 0; $a--) {
                if ($skip == 3) {
                    $xm = ($v1[$a]) . " " . $xm;
                    $skip = 0;
                } else {
                    $xm = ($v1[$a]) . $xm;
                }

                $skip++;
            }

            //echo $value;
            if (count($ex) == 2) {
                $iVal = intval($ex[1]);
                $xm = $xm . "." . $iVal;
            } else {
                $xm = $xm . ".00";
            }
            $main = $xm;
        }


        return $symbol.$main;
    }

}
