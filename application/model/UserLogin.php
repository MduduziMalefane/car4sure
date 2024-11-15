<?php

namespace Care4Sure\Application\Model;

class UserLogin
{
    private int $id;
    private \DateTime $loginDate;
    private string $hash;
    private string $token;
    private \DateTime $loginTime;
    private int $loginTimeInt;
    private string $device;
    private string $ipAddress;
    private bool $enabled;
    private bool $deleted;
    private int $userId;
    private int $userType;

    public function __construct()
    {
        $this->id = 0;
        $this->loginDate = new \DateTime();
        $this->hash = '';
        $this->token = '';
        $this->loginTime = new \DateTime();
        $this->loginTimeInt = 0;
        $this->device = '';
        $this->ipAddress = '';
        $this->enabled = true;
        $this->deleted = false;
        $this->userId = 0;
        $this->userType = 1;
    }

    // Getters and Setters

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function getLoginDate(): \DateTime
    {
        return $this->loginDate;
    }

    public function setLoginDate(\DateTime $loginDate): self
    {
        $this->loginDate = $loginDate;
        return $this;
    }

    public function getHash(): string
    {
        return $this->hash;
    }

    public function setHash(string $hash): self
    {
        $this->hash = $hash;
        return $this;
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function setToken(string $token): self
    {
        $this->token = $token;
        return $this;
    }

    public function getLoginTime(): \DateTime
    {
        return $this->loginTime;
    }

    public function setLoginTime(\DateTime $loginTime): self
    {
        $this->loginTime = $loginTime;
        return $this;
    }

    public function getLoginTimeInt(): int
    {
        return $this->loginTimeInt;
    }

    public function setLoginTimeInt(int $loginTimeInt): self
    {
        $this->loginTimeInt = $loginTimeInt;
        return $this;
    }

    public function getDevice(): string
    {
        return $this->device;
    }

    public function setDevice(string $device): self
    {
        $this->device = $device;
        return $this;
    }

    public function getIpAddress(): string
    {
        return $this->ipAddress;
    }

    public function setIpAddress(string $ipAddress): self
    {
        $this->ipAddress = $ipAddress;
        return $this;
    }

    public function getEnabled(): bool
    {
        return $this->enabled;
    }

    public function setEnabled(bool $enabled): self
    {
        $this->enabled = $enabled;
        return $this;
    }

    public function getDeleted(): bool
    {
        return $this->deleted;
    }

    public function setDeleted(bool $deleted): self
    {
        $this->deleted = $deleted;
        return $this;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function setUserId(int $userId): self
    {
        $this->userId = $userId;
        return $this;
    }

    public function getUserType(): int
    {
        return $this->userType;
    }

    public function setUserType(int $userType): self
    {
        $this->userType = $userType;
        return $this;
    }

    public function save(): bool
    {
        if ($this->id == 0)
        {
            return $this->create();
        }
        else
        {
            return $this->update();
        }
    }

    private function create(): bool
    {
        $con = new \MysqlClass();
        $query = "INSERT INTO userlogin (loginDate, hash, token, loginTime, loginTimeInt, device, ipAddress, enabled, deleted, userId, userType) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $con->pushParam($this->loginDate->format('Y-m-d H:i:s'));
        $con->pushParam($this->hash);
        $con->pushParam($this->token);
        $con->pushParam($this->loginTime->format('Y-m-d H:i:s'));
        $con->pushParam($this->loginTimeInt);
        $con->pushParam($this->device);
        $con->pushParam($this->ipAddress);
        $con->pushParam((int) $this->enabled);
        $con->pushParam((int) $this->deleted);
        $con->pushParam($this->userId);
        $con->pushParam($this->userType);

        if ($con->executeNoneQuery($query) > 0)
        {
            $this->id = $con->getLastInsertId();
            return true;
        }

        return false;
    }

    private function update(): bool
    {
        $con = new \MysqlClass();
        $query = "UPDATE userlogin SET loginDate = ?, hash = ?, token = ?, loginTime = ?, loginTimeInt = ?, device = ?, ipAddress = ?, enabled = ?, deleted = ?, userId = ?, userType = ? WHERE id = ?";

        $con->pushParam($this->loginDate->format('Y-m-d H:i:s'));
        $con->pushParam($this->hash);
        $con->pushParam($this->token);
        $con->pushParam($this->loginTime->format('Y-m-d H:i:s'));
        $con->pushParam($this->loginTimeInt);
        $con->pushParam($this->device);
        $con->pushParam($this->ipAddress);
        $con->pushParam((int) $this->enabled);
        $con->pushParam((int) $this->deleted);
        $con->pushParam($this->userId);
        $con->pushParam($this->userType);
        $con->pushParam($this->id);

        return $con->executeNoneQuery($query) > 0;
    }

    public static function delete(int $id): bool
    {
        $con = new \MysqlClass();
        $query = "UPDATE userlogin SET Deleted = 1 WHERE id = ? AND deleted = 0";
        $con->pushParam($id);

        return $con->executeNoneQuery($query) > 0;
    }

    public static function getById(int $id): ?self
    {
        $con = new \MysqlClass();
        $query = "SELECT * FROM userlogin WHERE id = ? AND deleted = 0 LIMIT 1";
        $con->pushParam($id);

        $result = $con->queryObject($query);

        if ($result)
        {
            return self::map($result);
        }

        return null;
    }

    public static function getAll(): array
    {
        $con = new \MysqlClass();
        $query = "SELECT * FROM userlogin WHERE deleted = 0";

        $result = $con->queryAllObject($query);

        $userLogins = [];
        foreach ($result as $userLogin)
        {
            $userLogins[] = self::map($userLogin);
        }

        return $userLogins;
    }

    private static function map($userLogin): self
    {
        return (new self())
            ->setId($userLogin->id)
            ->setLoginDate(new \DateTime($userLogin->loginDate))
            ->setHash($userLogin->hash)
            ->setToken($userLogin->token)
            ->setLoginTime(new \DateTime($userLogin->loginTime))
            ->setLoginTimeInt($userLogin->loginTimeInt)
            ->setDevice($userLogin->device)
            ->setIpAddress($userLogin->ipAddress)
            ->setEnabled((bool) $userLogin->enabled)
            ->setDeleted((bool) $userLogin->deleted)
            ->setUserId($userLogin->userId)
            ->setUserType($userLogin->userType);
    }
}