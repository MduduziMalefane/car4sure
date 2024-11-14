namespace TLC\Application\Model;

use PDO;
use TLC\Application\Database;

/**
 * Policyholder Model
 */
class Policy
{
    private int $policyNo;
    private string $policyStatus;
    private string $policyType;
    private DateTime $policyEffectiveDate;
    private DateTime $policyExpirationDate;
    private int $policyholderID;
    private int $deleted;
  

    public function __construct()
    {
        $this->policyNo = 0;
        $this->policyStatus = '';
        $this->policyEffectiveDate = new DateTime(); // Initialize to the current date and time
        $this->policyExpirationDate = new DateTime();
        $this->policyholderID = 0;
    }

    public function getpolicyNo(): int
    {
        return $this->policyNo;
    }

    public function setpolicyNo(int $policyNo): self
    {
        $this->id = $policyNo;
        return $this;
    }

    public function getpolicyStatus(): string
    {
        return $this->policyNo;
    }

    public function setpolicyStatus(string $policyStatus): self
    {
        $this->id = $policyStatus;
        return $this;
    }
    public function getpolicyEffectiveDate(): new DateTime
    {
        return $this->policyEffectiveDate;
    }

    public function setpolicyEffectiveDate(\ DateTime $policyEffectiveDate): self
    {
        $this->policyNo = $policyEffectiveDate;
        return $this;
    }
    public function getpolicyExpirationDate(): \DateTime
    {
        return $this->policyExpirationDate;
    }

    public function setpolicyExpirationDate(\DateTime $policyExpirationDate): self
    {
        $this->policyNo = $policyExpirationDate;
        return $this;
    }
    public function save(): bool
    {
        if ($this->policyNo == 0) {
            return $this->savePolicy();
        } else {
            return $this->updatePolicy();
        }
    }

    /**
     * Insert the policy
     * @return bool
     */
    private function savePolicy(): bool
    {
        $con = new \MysqlClass();
        $query = "INSERT INTO policy (policyStatus, policyEffectiveDate, policyExpirationDate ) VALUES (?, ?, ?);";
        $con->pushParam($this->policyStatus);
        $con->pushParam($this->policyEffectiveDate->format('Y-m-d H:i:s'));
        $con->pushParam($this->policyExpirationDate->format('Y-m-d H:i:s'));


        if ($con->executeNoneQuery($query) > 0) {
            $this->policyNo = $con->getLastInsertId();
            return true;
        }

        return false;
    }

    /**
     * Update the policy
     * @return bool
     */
    private function updatePolicy(): bool
    {
        $con = new \MysqlClass();
        $query = "UPDATE policy SET policyStatus = ?, policyEffectiveDate = ?, policyExpirationDate = ?, WHERE policyNo = ? AND Deleted = 0";
        $con->pushParam($this->policyStatus);
        $con->pushParam($this->policyEffectiveDate->format('Y-m-d H:i:s'));
        $con->pushParam($this->policyExpirationDate->format('Y-m-d H:i:s'));
        $con->pushParam($this->policyNo);

        return $con->executeNoneQuery($query) > 0;
    }

    /**
     * Delete the policy
     * @param int $policyNo
     * @return bool
     */
    public static function delete(int $policyNo): bool
    {
        $con = new \MysqlClass();
        $query = "UPDATE policy SET Deleted = 1 WHERE policyNo = ?";
        $con->pushParam($policyNo);

        return $con->executeNoneQuery($query) > 0;
    }

    /**
     * Get the policy by policyNo
     * @param int $policyNo
     * @return policy|null
     */
    public static function getpolicyByPolicyNo(int $policyNo): ?policy
    {
        $con = new \MysqlClass();
        $query = "SELECT * FROM policy WHERE Id = ? AND Deleted = 0 LIMIT 1";
        $con->pushParam($policyNo);
        $result = $con->queryObject($query);

        if ($result) {
            return self::mapPolicy($result);
        }

        return null;
    }

    /**
     * Get all policies
     * @return array
     */
    public static function getAllPolicies(): array
    {
        $con = new \MysqlClass();
        $query = "SELECT * FROM policy WHERE Deleted = 0";
        $result = [];

        $Policies = $con->queryAllObject_Safe($query);

        if ($Policies) {
            foreach ($Policies as $row) {
                $result[] = self::mapPolicy($row);
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
        $policies = self::getAllPolicy();
        $result = [];

        foreach ($Policies as $Policy) {
            $result[] = ["policyNo" => $Policy->getpolicyNo(), "name" => $policy->getpolicyStatus()];
        }

        return $result;
    }

    private static function mapPolicyNo($Policy): Policy
    {
        return (new self())
            ->setPolicyNo($Policy->PolicyNo)
            ->setpolicyStatus($Policy->firstName)
            ->setpolicyEffectiveDate($Policy->lastName)
            ->setpolicyExpirationDate($Policy->streetName);
          
    }