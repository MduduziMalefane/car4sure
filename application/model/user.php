<?php
namespace CAR4SURE\Application\Model;


/**
 * User Model
 */
class User
{
    private int $userId;
    private string $firstName;
    private string $lastName;
    private int $age;
    private string $gender;
    private string $martialStatus;
   

    public function __construct()
    {
        $this->userId = 0;
        $this->firstName = '';
        $this->lastName = '';
        $this->gender = '';
        $this->martialStatus = '';
        $this->age = 0;
    }
    public function getuserId(): int
    {
        return $this->userId;
    }

    /**
     * Set the value of userId
     * @param int $userId
     * @return self
     */
    public function setuserId(int $userId): self
    {
        $this->userId = $userId;
        return $this;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * Set the value of firstName
     * @param int $firstName
     * @return self
     */
    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;
        return $this;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    /**
     * Set the value of lastName
     * @param int $lastName
     * @return self
     */
    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;
        return $this;
    }

    public function getAge(): int
    {
        return $this->age;
    }

    /**
     * Set the value of age
     * @param int $age
     * @return self
     */
    public function setAge(string $age): self
    {
        $this->age = $age;
        return $this;
    }

    public function getGender(): string
    {
        return $this->gender;
    }

    /**
     * Set the value of gender
     * @param int $gender
     * @return self
     */
    public function setGender(string $gender): self
    {
        $this->gender = $gender;
        return $this;
    }

    public function getMartialStatus(): string
    {
        return $this->martialStatus;
    }

    /**
     * Set the value of state
     * @param int $martialStatus
     * @return self
     */
    public function setMartialStatus(string $martialStatus): self
    {
        $this->state = $martialStatus;
        return $this;
    }

    public function save(): bool
    {
        if ($this->userId == 0) {
            return $this->saveUser();
        } else {
            return $this->updateUser();
        }
    }

    /**
     * Insert the user
     * @return bool
     */
    private function saveUser(): bool
    {
        $con = new \MysqlClass();
        $query = "INSERT INTO user (firstName, lastName, age, gender, martialStatus) VALUES (?, ?, ?);";
        $con->pushParam($this->firstName);
        $con->pushParam($this->lastName);
        $con->pushParam($this->age);
        $con->pushParam($this->gender);
        $con->pushParam($this->martialStatus);

        if ($con->executeNoneQuery($query) > 0) {
            $this->userId = $con->getLastInsertID();
            return true;
        }

        return false;
    }

    /**
     * Update the user
     * @return bool
     */
    private function updateuser(): bool
    {
        $con = new \MysqlClass();
        $query = "UPDATE user SET firstName = ?, lastName = ?, age = ?, gender = ?, martialStatus = ? WHERE userId = ? AND Deleted = 0";
        $con->pushParam($this->firstName);
        $con->pushParam($this->lastName);
        $con->pushParam($this->age);
        $con->pushParam($this->gender);
        $con->pushParam($this->martialStatus);
        $con->pushParam($this->userId);

        return $con->executeNoneQuery($query) > 0;
    }

    /**
     * Delete the user
     * @param int $userId
     * @return bool
     */
    public static function delete(int $userId): bool
    {
        $con = new \MysqlClass();
        $query = "UPDATE user SET Deleted = 1 WHERE userId = ?";
        $con->pushParam($userId);

        return $con->executeNoneQuery($query) > 0;
    }

    /**
     * Get the policyholder by userId
     * @param int $userId
     * @return policyholder|null
     */
    public static function getuserrByuserId(int $userId): ?user
    {
        $con = new \MysqlClass();
        $query = "SELECT * FROM user WHERE userId = ? AND Deleted = 0 LIMIT 1";
        $con->pushParam($userId);
        $result = $con->queryObject($query);

        if ($result) {
            return self::map($result);
        }

        return null;
    }

    /**
     * Get all users
     * @return array
     */
    public static function getAll(): array
    {
        $con = new \MysqlClass();
        $query = "SELECT * FROM user WHERE Deleted = 0";
        $result = [];

        $Policyholders = $con->queryAllObject($query);

        if ($result) {
            foreach ($result as $row) {
                $result[] = self::map($row);
            }
        }

        return $result;
    }

    /**
     * Get the user display values
     * @return array
     */
    public static function getDisplay(): array
    {
        $users = self::getAll();
        $result = [];

        foreach ($users as $user) {
            $result[] = ["userId" => $user->getuserId(), "name" => $user->getfirstame()];
        }

        return $result;
    }

    private static function map($user): user
    {
        return (new self())
            ->setuserId($user->userId)
            ->setfirstName($user->firstName)
            ->setlastName($user->lastName)
            ->setage($user->age)
            ->setgender($user->gender)
            ->setmartialStatus($user->state);
    }
}
