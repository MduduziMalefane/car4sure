namespace TLC\Application\Model;

use PDO;
use TLC\Application\Database;

/**
 * Policyholder Model
 */
class License
{
    private int $licenseNo;
    private string $licenseState;
    private string $licenseStatus;
    private DateTime $licenseEffectiveDate;
    private DateTime $licenseExpirationDate;
    private string $licenseClass;


    public function __construct()
    {
        $this->licenseNo = 0;
        $this->licenseState = '';
        $this->licenseStatus = '';
        $this->licenseEffectiveDate = '';
        $this->licenseExpirationDate = '';
        $this->licenseClass = 0;
    }
    public function getlicenseNo(): int
    {
        return $this->licenseNo;
    }

    /**
     * Set the value of licenseNo
     * @param int $licenseNo
     * @return self
     */
    public function setlicenseNo(int $licenseNo): self
    {
        $this->licenseNo = $licenseNo;
        return $this;
    }

    public function getlicenseState(): string
    {
        return $this->licenseState;
    }

    /**
     * Set the value of licenseState
     * @param int $licenseState
     * @return self
     */
    public function setlicenseState(string $licenseState): self
    {
        $this->licenseState = $licenseState;
        return $this;
    }

    public function getlicenseStatus(): string
    {
        return $this->licenseStatus;
    }

    /**
     * Set the value of licenseStatus
     * @param int $licenseStatus
     * @return self
     */
    public function setlicenseStatus(string $licenseStatus): self
    {
        $this->licenseStatus = $licenseStatus;
        return $this;
    }

    public function getlicenseEffectiveDate(): new DateTime
    {
        return $this->licenseEffectiveDate;
    }

    /**
     * Set the value of licenseEffectiveDate
     * @param int $licenseEffectiveDate
     * @return self
     */
    public function setlicenseEffectiveDate(string $licenseEffectiveDate): self
    {
        $this->licenseEffectiveDate = $licenseEffectiveDate;
        return $this;
    }

    public function getlicenseExpirationDate(): string
    {
        return $this->licenseExpirationDate(;
    }

    /**
     * Set the value of licenseExpirationDate(
     * @param int $licenseExpirationDate(
     * @return self
     */
    public function setlicenseExpirationDate((string $licenseExpirationDate(): self
    {
        $this->licenseExpirationDate( = $licenseExpirationDate(;
        return $this;
    }

    public function getlicenseClass(): string
    {
        return $this->licenseClass;
    }

    /**
     * Set the value of licenseClass
     * @param int $licenseClass
     * @return self
     */
    public function setlicenseClass(string $licenseClass): self
    {
        $this->licenseClass = $licenseClass;
        return $this;
    }

  

    public function save(): bool
    {
        if ($this->id == 0) {
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

        return $con->executeNoneQuery_Safe($query) > 0;
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
    public static function getlicenseBylicenseNo(int $licenseNo): ?license
    {
        $con = new \MysqlClass();
        $query = "SELECT * FROM license WHERE licenseNo = ? AND Deleted = 0 LIMIT 1";
        $con->pushParam($licenseNo);
        $result = $con->queryObject($query);

        if ($result) {
            return self::mapLicense($result);
        }

        return null;
    }

    /**
     * Get all licenses
     * @return array
     */
    public static function getAllLicenses(): array
    {
        $con = new \MysqlClass();
        $query = "SELECT * FROM license WHERE Deleted = 0";
        $result = [];

        $Licenses = $con->queryAllObject($query);

        if ($Licenses) {
            foreach ($Licenses as $row) {
                $result[] = self::mapLicenses$row);
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
        $banks = self::getAllPolicyholder();
        $result = [];

        foreach ($Licenses as $licenses) {
            $result[] = ["licenseNo" => $license->getlicense(), "name" => $license->getlicenseState()];
        }

        return $result;
    }

    private static function mapLicense($license): license
    {
        return (new self())
            ->setlicenseNo($License->licenseNo)
            ->setfirstName($License->licenseState)
            ->setlastName($License->licenseStatus)
            ->setstreetName($License->licenseEffectiveDate)
            ->setcity($License->licenseExpirationDate)
            ->setstate($Policyholder->licenseClass);
    }