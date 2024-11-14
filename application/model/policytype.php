<?php
namespace CAR4SURE\Application\Model;


/**
 * Policyholder Model
 */
class Policytype
{
    private int $policyTypeId;
    private string $description;
    private int $policyNo;


    public function __construct()
    {
        $this->policyTypeId = 0;
        $this->description = '';
        $this->policyNo = 0;

    }
   
    public function getPolicyTypeId(): int
    {
        return $this->policyTypeId;
    }

    /**
     * Set the value of policyHolderId
     * @param int $policyTypeId
     * @return self
     */
    public function setPolicyTypeId(int $policyTypeId): self
    {
        $this->policyTypeId = $policyTypeId;
        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * Set the value of description
     * @param int $description
     * @return self
     */
    public function setDescription(string $description): self
    {
        $this->description = $description;
        return $this;
    }
   
    public function getPolicyNo(): int
    {
        return $this->policyNo;
    }

    /**
     * Set the value of policyNo
     * @param int $policyNo
     * @return self
     */
    public function setPolicyNo(int $policyNo): self
    {
        $this->policyNo = $policyNo;
        return $this;
    }

    public function save(): bool
    {
        if ($this->policyTypeId == 0) {
            return $this->savePolicyType();
        } else {
            return $this->updatePolicyType();
        }
    }

    /**
     * Insert the bank
     * @return bool
     */
    private function savePolicyType(): bool
    {
        $con = new \MysqlClass();
        $query = "INSERT INTO policytype (description) VALUES (?, ?, ?);";
        $con->pushParam($this->description);
 
        if ($con->executeNoneQuery($query) > 0) {
            $this->policyTypeID = $con->getLastInsertId();
            return true;
        }

        return false;
    }

    /**
     * Update the policytype
     * @return bool
     */
    private function updatePolicyType(): bool
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
    public static function getPolicyTypeById(int $policyTypeID): ?policyType
    {
        $con = new \MysqlClass();
        $query = "SELECT * FROM policytype WHERE Id = ? AND Deleted = 0 LIMIT 1";
        $con->pushParam($policyTypeID);
        $result = $con->queryObject($query);

        if ($result) {
            return self::map($result);
        }

        return null;
    }

    /**
     * Get all policyType
     * @return array
     */
    public static function getAll(): array
    {
        $con = new \MysqlClass();
        $query = "SELECT * FROM policytype WHERE Deleted = 0";
        $result = [];

        $Policytypes = $con->queryAllObject($query);

        if ($Policytypes) {
            foreach ($Policytypes as $row) {
                $result[] = self::map($row);
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
        $policyTypes = self::getAll();
        $result = [];

        foreach ($policyTypes as $policyType) {
            $result[] = ["policytypeID" => $policyType->getpolicytypeID(), "name" => $policyType->getdescription()];
        }

        return $result;
    }

    private static function map($Policytype): Policytype
    {
        return (new self())
            ->setPolicyTypeId($Policytype->policyTypeId)
            ->setDescription($Policytype->description);
    }
}