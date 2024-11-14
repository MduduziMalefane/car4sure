namespace TLC\Application\Model;

use PDO;
use TLC\Application\Database;

/**
 * Policyholder Model
 */
class Policytype
{
    private int $policyTypeID;
    private string $description;


    public function __construct()
    {
        $this->policyTypeID = 0;
        $this->description = '';

    }
    public function getpolicyHolderID(): int
    {
        return $this->id;
    }

    /**
     * Set the value of policyHolderID
     * @param int $policyHolderID
     * @return self
     */
    public function setpolicyHolderID(int $policyHolderID): self
    {
        $this->policyHolderID = $policyHolderID;
        return $this;
    }

    public function getdescription(): string
    {
        return $this->description;
    }

    /**
     * Set the value of description
     * @param int $description
     * @return self
     */
    public function setdescription(string $description): self
    {
        $this->description = $description;
        return $this;
    }
    public function save(): bool
    {
        if ($this->id == 0) {
            return $this->savePolicytype();
        } else {
            return $this->updatePolicytype();
        }
    }

    /**
     * Insert the bank
     * @return bool
     */
    private function savePolicytype(): bool
    {
        $con = new \MysqlClass();
        $query = "INSERT INTO policytype (description) VALUES (?, ?, ?);";
        $con->pushParam($this->description);
 
        if ($con->executeNoneQuery($query) > 0) {
            $this->policyTypeID = $con->getLastInsertID();
            return true;
        }

        return false;
    }

    /**
     * Update the policytype
     * @return bool
     */
    private function updatePolicytype(): bool
    {
        $con = new \MysqlClass();
        $query = "UPDATE policytype SET description = ? WHERE policyTypeID = ? AND Deleted = 0";
        $con->pushParam($this->description);

        return $con->executeNoneQuery($query) > 0;
    }

    /**
     * Delete the policytype
     * @param int $policyTypeID
     * @return bool
     */
    public static function delete(int $policyTypeID): bool
    {
        $con = new \MysqlClass();
        $query = "UPDATE policytype SET Deleted = 1 WHERE Id = ?";
        $con->pushParam($policyTypeID);

        return $con->executeNoneQuery($query) > 0;
    }

    /**
     * Get the policyType by policyTypeID
     * @param int $policyTypeID
     * @return policyType|null
     */
    public static function getpolicyTypeById(int $policyTypeID): ?policyType
    {
        $con = new \MysqlClass();
        $query = "SELECT * FROM policytype WHERE Id = ? AND Deleted = 0 LIMIT 1";
        $con->pushParam($policyTypeID);
        $result = $con->queryObject($query);

        if ($result) {
            return self::mapPolicytype($result);
        }

        return null;
    }

    /**
     * Get all policyType
     * @return array
     */
    public static function getAllPolicytype(): array
    {
        $con = new \MysqlClass();
        $query = "SELECT * FROM policytype WHERE Deleted = 0";
        $result = [];

        $Policytypes = $con->queryAllObject($query);

        if ($Policytypes) {
            foreach ($Policytypes as $row) {
                $result[] = self::mapPolicytype($row);
            }
        }

        return $result;
    }

    /**
     * Get the policytype display values
     * @return array
     */
    public static function getDisplay(): array
    {
        $banks = self::getAllPolicytype();
        $result = [];

        foreach ($Policytypes as $Policytype) {
            $result[] = ["policytypeID" => $Policytype->getpolicytypeID(), "name" => $policyType->getdescription()];
        }

        return $result;
    }

    private static function mapPolicytype($Policytype): Policytype
    {
        return (new self())
            ->setPolicytypeID($Policytype->policyTypeID)
            ->setfirstName($Policytype->description);
    }