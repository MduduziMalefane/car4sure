<?php
namespace CAR4SURE\Application\Model;



/**
 * License Model
 */
class License
{
    private int $licenseNo;
    private string $licenseState;
    private string $licenseStatus;
    private \DateTime $licenseEffectiveDate;
    private \DateTime $licenseExpirationDate;
    private string $licenseClass;
    private int $userId;


    public function __construct()
    {
        $this->licenseNo = 0;
        $this->licenseState = '';
        $this->licenseStatus = '';
        $this->licenseEffectiveDate = new \DateTime();
        $this->licenseExpirationDate = new \DateTime();
        $this->licenseClass = 0;
        $this->userId = 0;
    }
    public function getLicenseNo(): int
    {
        return $this->licenseNo;
    }

    /**
     * Set the value of licenseNo
     * @param int $licenseNo
     * @return self
     */
    public function setLicenseNo(int $licenseNo): self
    {
        $this->licenseNo = $licenseNo;
        return $this;
    }

    public function getLicenseState(): string
    {
        return $this->licenseState;
    }

    /**
     * Set the value of licenseState
     * @param int $licenseState
     * @return self
     */
    public function setLicenseState(string $licenseState): self
    {
        $this->licenseState = $licenseState;
        return $this;
    }

    public function getLicenseStatus(): string
    {
        return $this->licenseStatus;
    }

    /**
     * Set the value of licenseStatus
     * @param int $licenseStatus
     * @return self
     */
    public function setLicenseStatus(string $licenseStatus): self
    {
        $this->licenseStatus = $licenseStatus;
        return $this;
    }

    public function getLicenseEffectiveDate(): \DateTime
    {
        return $this->licenseEffectiveDate;
    }

    /**
     * Set the value of licenseEffectiveDate
     * @param int $licenseEffectiveDate
     * @return self
     */
    public function setLicenseEffectiveDate($licenseEffectiveDate): self
    {
        if (is_int($licenseEffectiveDate)) {
            $licenseEffectiveDate = (new \DateTime())->setTimestamp($licenseEffectiveDate);
        } elseif (!$licenseEffectiveDate instanceof \DateTime) {
            throw new \InvalidArgumentException("Expected integer or DateTime for licenseEffectiveDate.");
        }
    
        $this->licenseEffectiveDate = $licenseEffectiveDate;
        return $this;
    }

    public function getLicenseExpirationDate(): \DateTime
    {
        return $this->licenseExpirationDate;
    }

    /**
     * Set the value of licenseExpirationDate(
     * @param int $licenseExpirationDate(
     * @return self
     */
    public function setLicenseExpirationDate($licenseExpirationDate): self
{
    if (is_int($licenseExpirationDate)) {
        $licenseExpirationDate = (new \DateTime())->setTimestamp($licenseExpirationDate);
    } elseif (is_string($licenseExpirationDate)) {
        $licenseExpirationDate = new \DateTime($licenseExpirationDate);
    } elseif (!$licenseExpirationDate instanceof \DateTime) {
        throw new \InvalidArgumentException("Expected integer, string, or DateTime for licenseExpirationDate.");
    }

    $this->licenseExpirationDate = $licenseExpirationDate;
    return $this;
}

    public function getLicenseClass(): string
    {
        return $this->licenseClass;
    }

    /**
     * Set the value of licenseClass
     * @param int $licenseClass
     * @return self
     */
    public function setLicenseClass(string $licenseClass): self
    {
        $this->licenseClass = $licenseClass;
        return $this;
    }

    public function getUserID(): int
    {
        return $this->userId;
    }

    /**
     * Set the value of userID
     * @param int $userID
     * @return self
     */
    public function setUserId(int $userID): self
    {
        $this->userID = $userID;
        return $this;
    }

    public function save(): bool
    {
        if ($this->licenseNo == 0) {
            return $this->saveLicense();
        } else {
            return $this->updateLicense();
        }
    }

    /**
     * Insert the license
     * @return bool
     */
    private function saveLicense(): bool
    {
        $con = new \MysqlClass();
        $query = "INSERT INTO license (licenseState, licensestatus, licenseeffectivedate, licenseexpirationdate) VALUES (?, ?, ?);";
        $con->pushParam($this->licenseState);
        $con->pushParam($this->licenseStatus);
        $con->pushParam($this->licenseEffectiveDate);
        $con->pushParam($this->licenseExpirationDate);
        $con->pushParam($this->licenseClass);
       


        if ($con->executeNoneQuery($query) > 0) {
            $this->licenseNo = $con->getLastInsertID();
            return true;
        }

        return false;
    }

    /**
     * Update the license
     * @return bool
     */
    private function updateLicense(): bool
    {
        $con = new \MysqlClass();
        $query = "UPDATE license SET licenseState = ?, licenseStatus = ?, licenseEffectiveDate = ?, licenseExpirationDate = ?, licenseClass = ? WHERE licenseNo = ? AND Deleted = 0";
        $con->pushParam($this->licenseNo);
        $con->pushParam($this->licenseState);
        $con->pushParam($this->licenseStatus);
        $con->pushParam($this->licenseEffectiveDate);
        $con->pushParam($this->licenseExpirationDate);
        $con->pushParam($this->licenseClass);
        $con->pushParam($this->licenseNo);

        return $con->executeNoneQuery($query) > 0;
    }

    /**
     * Delete the license
     * @param int $licenseNo
     * @return bool
     */
    public static function delete(int $licenseNo): bool
    {
        $con = new \MysqlClass();
        $query = "UPDATE license SET Deleted = 1 WHERE licenseNo = ?";
        $con->pushParam($licenseNo);

        return $con->executeNoneQuery($query) > 0;
    }

    /**
     * Get the policyholder by licenseNo
     * @param int $licenseNo
     * @return license|null
     */
    public static function getLicenseByLicenseNo(int $licenseNo): ?license
    {
        $con = new \MysqlClass();
        $query = "SELECT * FROM license WHERE licenseNo = ? AND Deleted = 0 LIMIT 1";
        $con->pushParam($licenseNo);
        $result = $con->queryObject($query);

        if ($result) {
            return self::map($result);
        }

        return null;
    }

    /**
     * Get all licenses
     * @return array
     */
    public static function getAll(): array
    {
        $con = new \MysqlClass();
        $query = "SELECT * FROM license WHERE Deleted = 0";
        $result = [];

        $Licenses = $con->queryAllObject($query);

        if ($Licenses) {
            foreach ($Licenses as $row) {
                $result[] = self::map($row);
            }
        }

        return $result;
    }

    /**
     * Get the Licens display values
     * @return array
     */
    public static function getDisplay(): array
    {
        $licenses = self::getAll();
        $result = [];

        foreach ($licenses as $license) {
            $result[] = ["licenseNo" => $license->getlicense(), "name" => $license->getlicenseState()];
        }

        return $result;
    }

    private static function map($license): license
    {
        return (new self())
            ->setlicenseNo($license->licenseNo)
            ->setlicenseState($license->licenseState)
            ->setlicenseStatus($license->licenseStatus)
            ->setlicenseEffectiveDate($license->licenseEffectiveDate)
            ->setLicenseExpirationDate($license->licenseExpirationDate)
            ->setLicenseClass($license->licenseClass);
    }
}