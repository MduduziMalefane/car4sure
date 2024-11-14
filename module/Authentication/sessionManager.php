<?php


class SessionManager
{

    public $UserID = 0;
    public $UserName = '';
    public $Name = 'Guest';
    public $Surname = '';
    public $UserType = '';
    public $IsActive = false;
    public $Duration = 0;
    public $loggedIn = false;
    public $Expired = false;
    public $Valid = false;
    public $Balance = 0;
    public $BalanceDisplay = "";
    public $MemberCode = '';
    public $Image;
    public $IsSuper;
    public $IsAdmin;
    public $IsArticle;
    public $IsGroup;
    public $IsFinance;
    public $IsBoard;
    public $IsInvestor;
    public $IsAuditor;
    public $IsSupport;
    public $IsClient;
    public $IsGuest;
    /// Shopping Cart Section
    public $Cart;
    public $CartExpire;
    public $CartExpireInt;
    // Additional Access Params
    public $CanGenerateJoinCode;
    public $AcceptMessages;
    public $CanSendMessage;
    public $CanAccessGroup;
    public $GeneralAccess;
    public $GuestAccess;
    public $SupportAdmin;
    public $AuditAdmin;
    public $FinanceAdmin;
    public $GroupAdmin;
    public $ArticleAdmin;
    public $AdminAccess;
    public $ClientAccess;
    public $InvestorAdmin;
    public $SuperAccess;
    public $ProfileCode;

    public $error = "";

    function __construct()
    {

        $this->Reset();
    }

    public static function GetMemberByCode($MemberCode)
    {
        $SqlCon = new MySqlClass();
        $SqlCon->pushParam($MemberCode);

        $data = $SqlCon->queryObject(
            "
        SELECT 
        member.MemID 'UserID', 
        member.UserName, 
        member.FirstName 'Name', 
        member.Surname AS 'Surname', 
        member.Image,
        member.DisplayName, 
        member.Active AS 'IsActive', 
        member.Enabled AS 'IsEnabled', 
        member.Balance, 
        member.Points, 
        member.MemberCode, 
        membertype.TypeName AS 'UserType', 
        membertype.SuperAccess AS 'IsSuper', 
        membertype.GroupModeration AS 'IsGroup'
        
        FROM member
        INNER JOIN membertype ON member.MTID = membertype.MTID 
        WHERE member.MemberCode = ? 
        AND member.Enabled = 1 
        LIMIT 1;"
        );

        return $data;
    }

    public static function GetMemberByUserName($UserName)
    {
        //$status = false;
        $SqlCon = new MySqlClass();
        $SqlCon->pushParam($UserName);

        $data = $SqlCon->queryObject(
            "
        SELECT 
        member.MemID 'UserID', 
        member.UserName, 
        member.FirstName 'Name', 
        member.Surname AS 'Surname', 
        member.Image,
        member.DisplayName, 
        member.Active AS 'IsActive', 
        member.Enabled AS 'IsEnabled', 
        member.Balance, 
        member.Points, 
        member.MemberCode, 
        membertype.TypeName AS 'UserType', 
        membertype.SuperAccess AS 'IsSuper', 
        membertype.GroupModeration AS 'IsGroup'
        
        FROM member
        INNER JOIN membertype ON member.MTID = membertype.MTID 
        WHERE member.UserName = ? 
        AND member.Enabled = 1 
        LIMIT 1;"
        );

        return $data;
    }

    public function LoadUser($sessionObject)
    {
        //$status = false;
        $SqlCon = new MySqlClass();
        $SqlCon->pushParam($sessionObject->UserName);
        $SqlCon->pushParam($sessionObject->OverAllHash);
        $SqlCon->pushParam($sessionObject->Token);

        $data = $SqlCon->queryObject(
            "
        SELECT 
        member.MemID 'UserID', 
        member.UserName, 
        member.FirstName 'Name', 
        member.Surname AS 'Surname', 
        member.Image,
        member.DisplayName, 
        member.Active AS 'IsActive', 
        member.Enabled AS 'IsEnabled', 
        member.Balance, 
        member.Points, 
        member.MemberCode, 
        membertype.TypeName AS 'UserType', 
        membertype.SuperAccess AS 'IsSuper', 
        membertype.AdminAccess AS 'IsAdmin', 
        membertype.ArticleModeration AS 'IsArticle', 
        membertype.GroupModeration AS 'IsGroup', 
        membertype.FinanceModeration AS 'IsFinance', 
        membertype.BoardAccess AS 'IsBoard', 
        membertype.InvestorAccess AS 'IsInvestor', 
        membertype.AuditorAccess AS 'IsAuditor',  
        membertype.SupportAccess AS 'IsSupport', 
        membertype.ClientAccess AS 'IsClient', 
        membertype.GuestAccess AS 'IsGuest',
        member.ProfileCode,
        member.CartData,
        member.CartExpire,
        member.CartExpireInt,
        MSID,
        AcceptMessages,
        CanSendMessage,
        CanAccessGroup,
        CanGenerateJoinCode
        
        
        FROM member
        INNER JOIN membertype ON member.MTID = membertype.MTID 
        INNER JOIN memberlogin ON memberlogin.MemID = member.MemID 
        WHERE member.UserName = ? 
        AND memberlogin.Hash = ? 
        AND memberlogin.Token = ? 
        AND member.Enabled = 1 
        AND memberlogin.Enabled = 1 
        AND memberlogin.LoginTime > NOW()
        LIMIT 1;"
        );

        $status = $data != null;
        if ($status)
        {
            $this->HandleUserInfo($data);
        }
        unset($data);
        return $status;
    }

    public function Login($LoginObject)
    {
        //$status = false;
        $SqlCon = new MySqlClass();

        $SqlCon->pushParam($LoginObject->Username, "username");
        $SqlCon->pushParam(\Hashing::systemHash($LoginObject->Password), "password");

        $query = "
                SELECT 
                member.MemID 'UserID', 
                member.UserName, 
                member.FirstName 'Name', 
                member.Surname AS 'Surname', 
                member.Image,
                member.DisplayName, 
                member.Active AS 'IsActive', 
                member.Enabled AS 'IsEnabled', 
                member.Balance, 
                member.Points, 
                member.MemberCode, 
                membertype.TypeName AS 'UserType', 
                membertype.SuperAccess AS 'IsSuper', 
                membertype.AdminAccess AS 'IsAdmin', 
                membertype.ArticleModeration AS 'IsArticle', 
                membertype.GroupModeration AS 'IsGroup', 
                membertype.FinanceModeration AS 'IsFinance', 
                membertype.BoardAccess AS 'IsBoard', 
                membertype.InvestorAccess AS 'IsInvestor', 
                membertype.AuditorAccess AS 'IsAuditor',  
                membertype.SupportAccess AS 'IsSupport', 
                membertype.ClientAccess AS 'IsClient', 
                membertype.GuestAccess AS 'IsGuest',
                member.ProfileCode,
                member.CartData,
                member.CartExpire,
                member.CartExpireInt,
                member.MSID,
                memberstatus.MemberStatusDesc,
                member.AcceptMessages,
                member.CanSendMessage,
                member.CanAccessGroup,
                member.CanGenerateJoinCode
                
                FROM member
                INNER JOIN membertype ON member.MTID = membertype.MTID 
                INNER JOIN memberstatus on memberstatus.MSID= member.MSID
                WHERE (member.UserName = :username OR member.EmailAddress = :username OR member.CellNo = :username) 
                AND member.Password = :password
                AND member.Enabled = 1 
                LIMIT 1;";

        $data = $SqlCon->queryObject($query);

        $status = $data != null;
        if ($status)
        {
            switch ($data->MSID)
            {

                case 1:
                case 2:
                case 4:
                case 5:
                    $this->error = $data->MemberStatusDesc;
                    $status = false;
                    break;

                case 3:
                    $this->HandleUserInfo($data);
                    break;
            }
        }
        else
        {
            $this->error = "Your user name / password is incorrect";
        }

        unset($data);
        return $status;
    }

    function Recover($RecoverObject)
    {
        $SqlCon = new MySqlClass();

        $SqlCon->pushParam($RecoverObject->Username);
        $SqlCon->pushParam($RecoverObject->Username);
        $SqlCon->pushParam($RecoverObject->Username);

        $data = $SqlCon->queryObject(
            "SELECT 
                member.MemID 'UserID', 
                member.UserName, 
                member.FirstName 'Name', 
                member.Surname AS 'Surname',
                member.EmailAddress AS 'Email',
                member.Password
                
                FROM member
                INNER JOIN membertype ON member.MTID = membertype.MTID 
                WHERE (member.UserName = ? OR member.EmailAddress =? OR member.CellNo = ?) 
                AND member.Enabled = 1 
                LIMIT 1;"
        );

        $status = $data != null;
        if ($status)
        {

            $this->Name = $data->Name;
            $this->UserName = $data->UserName;
            $this->Surname = $data->Surname;
            $this->Password = $data->Password;

            if ($data->Email == '')
            {
                $this->Error = "Your account does not have a valid email address please contact support support@tlc-global.co.za";
                $status = false;
            }
        }

        return $status;
    }

    public static function ActivateExternal($UserInfo)
    {
        $con = new MysqlClass();
        $con->pushParam($UserInfo->MemID);
        $query = "UPDATE member SET Active = 1, ShareMemberCode = 1, ActiveDate = CURRENT_TIMESTAMP() WHERE MemID = ? ;";
        return $con->executeNoneQuery($query) > 0;
    }

    public function Activate()
    {
        $con = new MysqlClass();

        $con->pushParam($this->UserID);

        $query = "UPDATE member SET Active = 1, ShareMemberCode = 1, ActiveDate = CURRENT_TIMESTAMP() WHERE MemID = ? ;";

        return $con->executeNoneQuery($query) > 0;
    }

    public function Register($RegisterObject, $UserLevel, $RegPending)
    {
        $con = new MysqlClass();
        $con->pushParam($RegisterObject->nr);
        $con->pushParam($RegisterObject->sr);
        $con->pushParam($RegisterObject->UserName);
        $con->pushParam($RegisterObject->er);
        $con->pushParam($RegisterObject->cr);
        $con->pushParam(\Hashing::systemHash($RegisterObject->pr));

        $con->pushParam($RegisterObject->MemberCode);
        $con->pushParam($RegisterObject->rr);
        $con->pushParam($RegisterObject->nr . " " . $RegisterObject->sr);
        $con->pushParam(\Hashing::getGuid());

        $con->pushParam($RegPending == 1 ? 1 : 3);
        $con->pushParam($RegisterObject->gn);

        $query = "INSERT INTO member(
            FirstName,Surname,UserName,
            EmailAddress,CellNo,Password,
            Active,Enabled,Unlocked,MTID,Points,
            MemberCode,UsedRef,DisplayName,ProfileCode,MSID,Gender) VALUES
            (?,?,?,?,?,?,0,1,1,$UserLevel,0,?,?,?,?,?,?);";

        return $con->executeNoneQuery($query) > 0;
    }

    public function GetMemberTree($UserID)
    {
        $con = new MysqlClass();

        $query = "SELECT ";
    }

    public function AddToTree($UserID, $ParentID, $TreeBranch)
    {
        $con = new MysqlClass();
    }

    public function Logout($sessionObject)
    {
        //$status = false;
        $SqlCon = new MySqlClass();
        $SqlCon->pushParam($this->UserID);
        $SqlCon->pushParam($sessionObject->OverAllHash);
        $SqlCon->pushParam($sessionObject->Token);

        $query = "UPDATE memberlogin SET Enabled = 0 WHERE MemID = ? "
            . "AND Hash = ? AND Token = ? AND Enabled = 1; ";

        return $SqlCon->executeNoneQuery($query) > 0;
    }

    public function UpdateImage()
    {

        $con = new MysqlClass();
        $con->pushParam($this->Image);
        $con->pushParam($this->UserID);

        $query = "UPDATE member SET Image = ? WHERE MemID = ?;";

        return $con->executeNoneQuery($query) > 0;
    }

    public function UpdatePassword($OldPassword, $NewPassword)
    {
    }

    public function SaveLogin()
    {

        $sessionObject = new SessionObject();
        $sessionObject->GenerateSession($this->UserName);

        //$sessionObject = new SessionObject();
        $this->Duration = $sessionObject->TimeStamp;

        $con = new MysqlClass();
        $con->pushParam($sessionObject->OverAllHash);
        $con->pushParam($sessionObject->Token);
        $con->pushParam(date('Y/m/d H:i:s', $sessionObject->TimeStamp + $GLOBALS['config']->sessExpire));
        $con->pushParam($sessionObject->TimeStamp + $GLOBALS['config']->sessExpire);
        $con->pushParam($this->UserID);
        $query = "INSERT INTO memberlogin(Hash,Token,LoginTime,LoginTimeInt,MemID) VALUES(?,?,?,?,?);";

        if ($con->executeNoneQuery($query) > 0)
        {
            //$this->SaveLogin($sessionObject);
            $sessionObject->SaveSession();
            $sessionObject->SaveCookie();
            return true;
        }

        return false;
    }

    private function HandleUserInfo($UserObject)
    {
        $this->UserID = $UserObject->UserID;
        $this->Name = $UserObject->Name;
        $this->UserName = $UserObject->UserName;
        $this->Surname = $UserObject->Surname;
        $this->UserType = $UserObject->UserType;

        $this->IsActive = $UserObject->IsActive;
        $this->Duration = 0;
        $this->loggedIn = true;
        $this->Valid = true;
        $this->Balance = $UserObject->Balance;
        $this->BalanceDisplay = UtilityClass::formatCurrancy($UserObject->Balance);
        $this->MemberCode = $UserObject->MemberCode;
        $this->Image = $UserObject->Image != '' ? $UserObject->Image : 'blankprofile';
        $this->ProfileCode = $UserObject->ProfileCode;

        $this->IsSuper = $UserObject->IsSuper;
        $this->IsAdmin = $UserObject->IsAdmin;
        $this->IsArticle = $UserObject->IsArticle;
        $this->IsGroup = $UserObject->IsGroup;
        $this->IsFinance = $UserObject->IsFinance;
        $this->IsBoard = $UserObject->IsBoard;
        $this->IsInvestor = $UserObject->IsInvestor;
        $this->IsAuditor = $UserObject->IsAuditor;
        $this->IsSupport = $UserObject->IsSupport;
        $this->IsClient = $UserObject->IsClient;
        $this->IsGuest = $UserObject->IsGuest;

        $this->CanGenerateJoinCode = $UserObject->CanGenerateJoinCode;
        $this->AcceptMessages = $UserObject->AcceptMessages;
        $this->CanSendMessage = $UserObject->CanSendMessage;
        $this->CanAccessGroup = $UserObject->CanAccessGroup;

        $this->InitRights();
        $this->InitCart($UserObject);
    }

    function InitCart($UserObject)
    {

        if ($UserObject != null && $UserObject->CartData != null && ($this->Cart = json_decode($UserObject->CartData)) != null)
        {
            $this->CartExpire = $UserObject->CartExpire;
            $this->CartExpireInt = $UserObject->CartExpireInt;
        }
        else
        {
            $this->CartExpire = "1990/01/01";
            $this->CartExpireInt = 0;
            $this->Cart = new stdClass();
            $this->Cart->Count = 0;
            $this->Cart->Items = [];
        }

        return $this->Cart != null;
    }

    function UpdateCart()
    {
        $con = new MysqlClass();

        $nextExpire = time() + $GLOBALS["config"]->cartExpire;
        $this->CartExpire = date("Y/m/d", $nextExpire);
        $this->CartExpireInt = $nextExpire;

        $con->pushParam(json_encode($this->Cart));
        $con->pushParam($this->CartExpire);
        $con->pushParam($this->CartExpireInt);
        $con->pushParam($this->UserID);
        $query = "UPDATE member SET CartData = ?, CartExpire = ?, CartExpireInt = ? WHERE MemID = ?;";
        return $con->executeNoneQuery($query) > 0;
    }

    function ClearCart()
    {
        $con = new MysqlClass();

        $this->CartExpire = date("Y/m/d h:i:s");
        $this->CartExpireInt = time();
        $this->Cart->Count = 0;
        $this->Cart->Items = [];

        $con->pushParam(json_encode($this->Cart));
        $con->pushParam($this->CartExpire);
        $con->pushParam($this->CartExpireInt);
        $con->pushParam($this->UserID);
        $query = "UPDATE member SET CartData = ?, CartExpire = ?, CartExpireInt = ? WHERE MemID = ?;";
        return $con->executeNoneQuery($query) > 0;
    }

    function Reset()
    {
        $this->UserID = 0;
        $this->Name = 'Guest';
        $this->UserName = '';
        $this->Surname = '';
        $this->UserType = '';

        $this->IsActive = false;
        $this->Duration = 0;
        $this->loggedIn = false;
        $this->Balance = 0;
        $this->MemberCode = '';
        $this->Image = '';

        $this->IsSuper = 0;
        $this->IsAdmin = 0;
        $this->IsArticle = 0;
        $this->IsGroup = 0;
        $this->IsFinance = 0;
        $this->IsBoard = 0;
        $this->IsInvestor = 0;
        $this->IsAuditor = 0;
        $this->IsSupport = 0;
        $this->IsClient = 0;
        $this->IsGuest = 0;
        $this->InitRights();
        $this->InitCart(null);
    }

    private function InitRights()
    {
        $this->ClientAccess = $this->IsSuper || $this->IsAdmin ||
            $this->IsClient ||
            $this->IsBoard;

        $this->SuperAccess = $this->IsSuper;

        $this->AdminAccess = $this->IsSuper ||
            $this->IsAdmin ||
            $this->IsBoard;

        $this->ArticleAdmin = $this->IsSuper ||
            $this->IsAdmin ||
            $this->IsBoard ||
            $this->IsArticle;

        $this->GroupAdmin = $this->IsSuper ||
            //$this->IsAdmin ||
            $this->IsBoard ||
            $this->IsGroup;

        $this->FinanceAdmin = $this->IsSuper ||
            $this->IsBoard ||
            $this->IsFinance;

        $this->AuditAdmin = $this->IsSuper ||
            $this->IsBoard ||
            $this->IsAuditor;

        $this->SupportAdmin = $this->IsSuper ||
            $this->IsAdmin ||
            $this->IsBoard ||
            $this->IsSupport;

        $this->InvestorAdmin = $this->IsSuper ||
            $this->IsBoard ||
            $this->IsInvestor;

        $this->GuestAccess = $this->IsSuper ||
            $this->IsGuest;

        $this->GeneralAccess = $this->IsSuper ||
            $this->IsAdmin ||
            $this->IsBoard ||
            $this->IsAuditor ||
            $this->IsFinance ||
            $this->IsGroup ||
            $this->IsArticle ||
            $this->IsSupport ||
            $this->IsInvestor;
    }
}
