<?php

namespace CAR4SURE\Application\Model;

/**
 * PolicyHolder Model
 */
class PolicyHolder
{
    private int $policyHolderId;
    private string $firstName;
    private string $lastName;
    private string $streetName;
    private string $city;
    private string $state;
    private int $zip;

    public function __construct()
    {
        $this->policyHolderId = 0;
        $this->firstName = '';
        $this->lastName = '';
        $this->city = '';
        $this->state = '';
        $this->zip = 0;
    }
    public function getPolicyHolderId(): int
    {
        return $this->policyHolderId;
    }

    /**
     * Set the value of Id
     * @param int $Id
     * @return self
     */
    public function setPolicyHolderId(int $policyHolderId): self
    {
        $this->policyHolderId = $policyHolderId;
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

    public function getStreetName(): string
    {
        return $this->streetName;
    }

    /**
     * Set the value of streetName
     * @param int $streetName
     * @return self
     */
    public function setStreetName(string $streetName): self
    {
        $this->streetName = $streetName;
        return $this;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    /**
     * Set the value of city
     * @param int $city
     * @return self
     */
    public function setCity(string $city): self
    {
        $this->city = $city;
        return $this;
    }

    public function getState(): string
    {
        return $this->state;
    }

    /**
     * Set the value of state
     * @param int $state
     * @return self
     */
    public function setState(string $state): self
    {
        $this->state = $state;
        return $this;
    }

    public function getZip(): int
    {
        return $this->zip;
    }

    /**
     * Set the value of zip
     * @param int $zip
     * @return self
     */
    public function setZip(int $zip): self
    {
        $this->zip = $zip;
        return $this;
    }

    public function save(): bool
    {
        if ($this->policyHolderId == 0) {
            return $this->savePolicyholder();
        } else {
            return $this->updatePolicyholder();
        }
    }

    /**
     * Insert the bank
     * @return bool
     */
    private function savePolicyHolder(): bool
    {
        $con = new \MysqlClass();
        $query = "INSERT INTO policyholder (firstName, lastName, streetName, city, state, zip,) VALUES (?, ?, ?);";
        $con->pushParam($this->firstName);
        $con->pushParam($this->lastName);
        $con->pushParam($this->streetName);
        $con->pushParam($this->city);
        $con->pushParam($this->state);
        $con->pushParam($this->zip);


        if ($con->executeNoneQuery($query) > 0) {
            $this->policyHolderId = $con->getLastInsertId();
            return true;
        }

        return false;
    }

    /**
     * Update the policyholder
     * @return bool
     */
    private function updatePolicyHolder(): bool
    {
        $con = new \MysqlClass();
        $query = "UPDATE bank SET firstName = ?, lastName = ?, streetName = ?, city = ?, state = ?, zip = ?, WHERE policyholderID = ? AND Deleted = 0";
        $con->pushParam($this->firstName);
        $con->pushParam($this->lastName);
        $con->pushParam($this->streetName);
        $con->pushParam($this->city);
        $con->pushParam($this->state);
        $con->pushParam($this->zip);
        $con->pushParam($this->policyHolderId);

        return $con->executeNoneQuery($query) > 0;
    }

    /**
     * Delete the policyholder
     * @param int $policyHolderID
     * @return bool
     */
    public static function delete(int $policyHolderId): bool
    {
        $con = new \MysqlClass();
        $query = "UPDATE policyholder SET Deleted = 1 WHERE policyHolderID = ?";
        $con->pushParam($policyHolderId);

        return $con->executeNoneQuery($query) > 0;
    }

    /**
     * Get the policyholder by policyholderID
     * @param int $policyHolderID
     * @return policyholder|null
     */
    public static function getPolicyHolderById(int $policyHolderId): ?policyholder
    {
        $con = new \MysqlClass();
        $query = "SELECT * FROM policyholder WHERE policyHolderId = ? AND Deleted = 0 LIMIT 1";
        $con->pushParam($policyHolderId);
        $result = $con->queryObject($query);

        if ($result) {
            return self::map($result);
        }

        return null;
    }

    /**
     * Get all banks
     * @return array
     */
    public static function getAll(): array
    {
        $con = new \MysqlClass();
        $query = "SELECT * FROM policyholder WHERE Deleted = 0";
        $result = [];

        $Policyholders = $con->queryAllObject($query);

        if ($Policyholders) {
            foreach ($Policyholders as $row) {
                $result[] = self::map(policyHolder: $row);
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
        // getAllPolicyHolder
        $policyHolders = self::getAll();
        $result = [];

        foreach ($policyHolders as $policyHolder) {
            $result[] = ["policyholderId" => $policyHolder->getPolicyHolderId(), "name" => $policyHolder->getPolicyHolderId()];
        }

        return $result;
    }
     
    

    private static function map($policyHolder): PolicyHolder
    {
        return (new self())
            ->setPolicyHolderId($policyHolder->policyHolderID)
            ->setFirstName($policyHolder->firstName)
            ->setLastName($policyHolder->lastName)
            ->setStreetName($policyHolder->streetName)
            ->setCity($policyHolder->city)
            ->setState($policyHolder->state)
            ->setZip($policyHolder->zip);
    }
}