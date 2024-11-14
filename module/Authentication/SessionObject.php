<?php

class SessionObject
{

    public $UserName;
    public $TimeStamp;
    public $Token;
    public $OverAllHash;
    public $IsCookie;

    public function FromCookie()
    {

        $var = UtilityClass::Cookie($GLOBALS['config']->cookID);

        if (strlen($var) > 15) {
            $value = explode('|', $var);

            if (count($value) == 4) {
                $this->UserName = $value[0];
                $this->TimeStamp = $value[1];
                $this->Token = $value[2];
                $this->OverAllHash = $value[3];

                return true;
            }
        }

        return false;
    }

    public function FromSession()
    {
        $var = UtilityClass::Session($GLOBALS['config']->sessID);

        if (strlen($var) > 15) {
            $value = explode('|', $var);
            if (count($value) == 4) {
                $this->UserName = $value[0];
                $this->TimeStamp = $value[1];
                $this->Token = $value[2];
                $this->OverAllHash = $value[3];

                return true;
            }
        }
        return false;
    }

    public function GetSessionString()
    {
        return "$this->UserName|$this->TimeStamp|$this->Token|$this->OverAllHash";
    }

    public function SaveCookie()
    {
        UtilityClass::setCookie($GLOBALS['config']->cookID, $this->GetSessionString());
    }

    public function SaveSession()
    {

        UtilityClass::setSession($GLOBALS['config']->sessID, $this->GetSessionString());
    }

    function ClearSaved()
    {
        UtilityClass::setSession($GLOBALS['config']->sessID, "");
        UtilityClass::delCookie($GLOBALS['config']->cookID);
    }

    public function IsValid()
    {
        if (!ValidationClass::ValidateAlphaNumeric($this->UserName)) {
            return false;
        } else if (!ValidationClass::ValidateNumber($this->TimeStamp) || ($this->TimeStamp + $GLOBALS['config']->sessExpire) < time()) {
            return false;
        }

        $firstHash = \Hashing::systemHash($this->UserName . $GLOBALS['config']->sessKey . $this->TimeStamp, 'md5');
        $secondHash = \Hashing::systemHash($this->UserName . $firstHash . $this->TimeStamp);

        return $this->OverAllHash == $secondHash;
    }

    function GenerateSession($username)
    {
        $this->UserName = $username;
        $this->TimeStamp = time();
        $this->Token = \Hashing::systemHash($this->UserName . $GLOBALS['config']->sessKey . $this->TimeStamp, 'md5');
        $this->OverAllHash = \Hashing::systemHash($this->UserName . $this->Token . $this->TimeStamp);
    }
}