namespace TLC\Application\Model;

use PDO;
use TLC\Application\Database;

/**
 * Policyholder Model
 */
class User
{
    private int $userID;
    private string $firstName;
    private string $lastName;
    private int $age;
    private string $gender;
    private string $martialStatus;
   

    public function __construct()
    {
        $this->userID = 0;
        $this->firstName = '';
        $this->lastName = '';
        $this->gender = '';
        $this->martialStatus = '';
        $this->age = 0;
    }
    public function getuserID(): int
    {
        return $this->userID;
    }

    /**
     * Set the value of userID
     * @param int $userID
     * @return self
     */
    public function setuserID(int $userID): self
    {
        $this->userID = $userID;
        return $this;
    }

    public function getfirstName(): string
    {
        return $this->firstName;
    }

    /**
     * Set the value of firstName
     * @param int $firstName
     * @return self
     */
    public function setfirstName(string $firstName): self
    {
        $this->firstName = $firstName;
        return $this;
    }

    public function getlastName(): string
    {
        return $this->lastName;
    }

    /**
     * Set the value of lastName
     * @param int $lastName
     * @return self
     */
    public function setlastName(string $lastName): self
    {
        $this->lastName = $lastName;
        return $this;
    }

    public function getage(): int
    {
        return $this->age;
    }

    /**
     * Set the value of age
     * @param int $age
     * @return self
     */
    public function setage(string $age): self
    {
        $this->age = $age;
        return $this;
    }

    public function getgender(): string
    {
        return $this->gender;
    }

    /**
     * Set the value of gender
     * @param int $gender
     * @return self
     */
    public function setgender(string $gender): self
    {
        $this->gender = $gender;
        return $this;
    }

    public function getmartialStatus(): string
    {
        return $this->state;
    }

    /**
     * Set the value of state
     * @param int $martialStatus
     * @return self
     */
    public function setmartialStatus(string $martialStatus): self
    {
        $this->state = $martialStatus;
        return $this;
    }

    public function save(): bool
    {
        if ($this->userID == 0) {
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
            $this->userID = $con->getLastInsertID();
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
        $query = "UPDATE user SET firstName = ?, lastName = ?, age = ?, gender = ?, martialStatus = ? WHERE userID = ? AND Deleted = 0";
        $con->pushParam($this->firstName);
        $con->pushParam($this->lastName);
        $con->pushParam($this->age);
        $con->pushParam($this->gender);
        $con->pushParam($this->martialStatus);
        $con->pushParam($this->userID);

        return $con->executeNoneQuery($query) > 0;
    }

    /**
     * Delete the user
     * @param int $userID
     * @return bool
     */
    public static function delete(int $userID): bool
    {
        $con = new \MysqlClass();
        $query = "UPDATE user SET Deleted = 1 WHERE userID = ?";
        $con->pushParam($userID);

        return $con->executeNoneQuery($query) > 0;
    }

    /**
     * Get the policyholder by userID
     * @param int $userID
     * @return policyholder|null
     */
    public static function getuserrByuserID(int $userID): ?user
    {
        $con = new \MysqlClass();
        $query = "SELECT * FROM user WHERE userID = ? AND Deleted = 0 LIMIT 1";
        $con->pushParam($userID);
        $result = $con->queryObject($query);

        if ($result) {
            return self::mapUser($result);
        }

        return null;
    }

    /**
     * Get all users
     * @return array
     */
    public static function getAllusers(): array
    {
        $con = new \MysqlClass();
        $query = "SELECT * FROM user WHERE Deleted = 0";
        $result = [];

        $Policyholders = $con->queryAllObject($query);

        if ($users) {
            foreach ($users as $row) {
                $result[] = self::mapUser($row);
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
        $users = self::getAllUser();
        $result = [];

        foreach ($users as $user) {
            $result[] = ["userID" => $user->getuserID(), "name" => $user->getfirstame()];
        }

        return $result;
    }

    private static function mapUser($user): user
    {
        return (new self())
            ->setuserID($user->userID)
            ->setfirstName($user->firstName)
            ->setlastName($user->lastName)
            ->setage($user->age)
            ->setgender($user->gender)
            ->setmartialStatus($user->state)
    }