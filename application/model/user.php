<?php

namespace Care4Sure\Application\Model;

class User
{
    private int $userId;
    private string $firstName;
    private string $lastName;
    private string $username;
    private string $password;
    private bool $deleted;

    public function __construct()
    {
        $this->userId = 0;
        $this->firstName = '';
        $this->lastName = '';
        $this->username = '';
        $this->password = '';
        $this->deleted = false;
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

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;
        return $this;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;
        return $this;
    }

    public function getFullName(): string
    {
        return sprintf('%s %s', $this->firstName, $this->lastName);
    }

    public function getInitials(): string
    {
        return sprintf('%s%s', substr($this->firstName, 0, 1), substr($this->lastName, 0, 1));
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;
        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;
        return $this;
    }

    public function isDeleted(): bool
    {
        return $this->deleted;
    }

    public function setDeleted(bool $deleted): self
    {
        $this->deleted = $deleted;
        return $this;
    }

    public static function delete(int $userId): bool
    {
        $con = new \MysqlClass();
        $query = "UPDATE user SET Deleted = 1 WHERE userId = ? AND deleted = 0";
        $con->pushParam($userId);

        return $con->executeNoneQuery($query) > 0;
    }

    public static function getByUserId(int $userId): ?self
    {
        $con = new \MysqlClass();
        $query = "SELECT * FROM user WHERE userId = ? AND deleted = 0 LIMIT 1";
        $con->pushParam($userId);

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
        $query = "SELECT * FROM user WHERE deleted = 0";

        $result = $con->queryAllObject($query);

        $users = [];
        foreach ($result as $user)
        {
            $users[] = self::map($user);
        }

        return $users;
    }

    private static function map($user): self
    {
        return (new self())
            ->setUserId($user->userId)
            ->setFirstName($user->firstName)
            ->setLastName($user->lastName)
            ->setUsername($user->username)
            ->setPassword($user->password)
            ->setDeleted((bool) $user->deleted);
    }
}