namespace TLC\Application\Model;

use PDO;
use TLC\Application\Database;

/**
 * Policyholder Model
 */
class Policyholder
{
    private int $policyHolderID;
    private string $firstName;
    private string $lastName;
    private string $streetName;
    private string $city;
    private string $state;
    private int $zip;

    public function __construct()
    {
        $this->policyHolderID = 0;
        $this->firstName = '';
        $this->lastName = '';
        $this->city = '';
        $this->state = '';
        $this->zip = 0;
    }
    public function getpolicyHolderID(): int
    {
        return $this->id;
    }

    /**
     * Set the value of Id
     * @param int $Id
     * @return self
     */
    public function setpolicyHolderID(int $policyHolderID): self
    {
        $this->policyHolderID = $policyHolderID;
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

    public function getstreetName(): string
    {
        return $this->streetName;
    }

    /**
     * Set the value of streetName
     * @param int $streetName
     * @return self
     */
    public function setstreetName(string $streetName): self
    {
        $this->streetName = $streetName;
        return $this;
    }

    public function getcity(): string
    {
        return $this->city;
    }

    /**
     * Set the value of city
     * @param int $city
     * @return self
     */
    public function setcity(string $city): self
    {
        $this->city = $city;
        return $this;
    }

    public function getstate(): string
    {
        return $this->state;
    }

    /**
     * Set the value of state
     * @param int $state
     * @return self
     */
    public function setstate(string $state): self
    {
        $this->state = $state;
        return $this;
    }

    public function getzip(): int
    {
        return $this->firstName;
    }

    /**
     * Set the value of zip
     * @param int $zip
     * @return self
     */
    public function setzip(int $zip): self
    {
        $this->zip = $zip;
        return $this;
    }

    public function save(): bool
    {
        if ($this->id == 0) {
            return $this->savePolicyholder();
        } else {
            return $this->updatePolicyholder();
        }
    }

    /**
     * Insert the bank
     * @return bool
     */
    private function savePolicyholder(): bool
    {
        $con = new \MysqlClass();
        $query = "INSERT INTO policyholder (firstName, lastName, streetName, city, state, zip,) VALUES (?, ?, ?);";
        $con->pushParam($this->firstName);
        $con->pushParam($this->lastName);
        $con->pushParam($this->streetName);
        $con->pushParam($this->city);
        $con->pushParam($this->state);
        $con->pushParam($this->zip);


        if ($con->executeNoneQuery_Safe($query) > 0) {
            $this->id = $con->getLastInsertPolicyHolderID();
            return true;
        }

        return false;
    }

    /**
     * Update the policyholder
     * @return bool
     */
    private function updatePolicyholder(): bool
    {
        $con = new \MysqlClass();
        $query = "UPDATE bank SET firstName = ?, lastName = ?, streetName = ?, city = ?, state = ?, zip = ?, WHERE policyholderID = ? AND Deleted = 0";
        $con->pushParam($this->firstName);
        $con->pushParam($this->lastName);
        $con->pushParam($this->streetName);
        $con->pushParam($this->city);
        $con->pushParam($this->state);
        $con->pushParam($this->zip);
        $con->pushParam($this->policyholderID);

        return $con->executeNoneQuery_Safe($query) > 0;
    }

    /**
     * Delete the policyholder
     * @param int $policyHolderID
     * @return bool
     */
    public static function delete(int $policyHolderID): bool
    {
        $con = new \MysqlClass();
        $query = "UPDATE policyholder SET Deleted = 1 WHERE Id = ?";
        $con->pushParam($id);

        return $con->executeNoneQuery_Safe($query) > 0;
    }

    /**
     * Get the policyholder by policyholderID
     * @param int $policyHolderID
     * @return policyholder|null
     */
    public static function getpolicyholderById(int $policyHolderID): ?policyholder
    {
        $con = new \MysqlClass();
        $query = "SELECT * FROM policyholder WHERE Id = ? AND Deleted = 0 LIMIT 1";
        $con->pushParam($id);
        $result = $con->queryObject_Safe($query);

        if ($result) {
            return self::mapPolicyholder($result);
        }

        return null;
    }

    /**
     * Get all banks
     * @return array
     */
    public static function getAllPolicyholders(): array
    {
        $con = new \MysqlClass();
        $query = "SELECT * FROM policyholder WHERE Deleted = 0";
        $result = [];

        $Policyholders = $con->queryAllObject_Safe($query);

        if ($Policyholders) {
            foreach ($Policyholders as $row) {
                $result[] = self::mapPolicyholder($row);
            }
        }

        return $result;
    }

    /**
     * Get the bank display values
     * @return array
     */
    public static function getDisplay(): array
    {
        $banks = self::getAllPolicyholder();
        $result = [];

        foreach ($Policyholders as $Policyholder) {
            $result[] = ["policyholderID" => $Policyholder->getpolicyHolderID(), "name" => $bank->getfirstame()];
        }

        return $result;
    }

    private static function mapPolicyholder($Policyholder): Policyholder
    {
        return (new self())
            ->setpolicyHolderID($Policyholder->Id)
            ->setfirstName($Policyholder->firstName)
            ->setlastName($Policyholder->lastName)
            ->setstreetName($Policyholder->streetName)
            ->setcity($Policyholder->city)
            ->setstate($Policyholder->state)
            ->setzip($Policyholder->zip);
    }