namespace TLC\Application\Model;

use PDO;
use TLC\Application\Database;

/**
 * Policyholder Model
 */
class Coverage
{
    private int $coverageID;
    private string $type;
    private int $limit;
    private int $deductible;

    public function __construct()
    {
        $this->coverageID = 0;
        $this->type = '';
        $this->limit = '';
        $this->deductible = '';
    }
    public function getcoverageID(): int
    {
        return $this->coverageID;
    }

    /**
     * Set the value of coverageID
     * @param int $coverageID
     * @return self
     */
    public function setcoverageID(int $coverageID): self
    {
        $this->coverageID = $coverageID;
        return $this;
    }

    public function gettype(): string
    {
        return $this->type;
    }

    /**
     * Set the value of type
     * @param int $type
     * @return self
     */
    public function settype(int $type): self
    {
        $this->type = $type;
        return $this;
    }

    public function getlimit(): int
    {
        return $this->limit;
    }

    /**
     * Set the value of limit
     * @param int $limit
     * @return self
     */
    public function setlimit(int $limit): self
    {
        $this->limit = $limit;
        return $this;
    }

    public function getdeductible(): int
    {
        return $this->deductible;
    }

    /**
     * Set the value of deductible
     * @param int $deductible
     * @return self
     */
    public function setdeductible(string $deductible): self
    {
        $this->deductible = $deductible;
        return $this;
    }

  
    public function save(): bool
    {
        if ($this->id == 0) {
            return $this->saveCoverage();
        } else {
            return $this->updateCoverage();
        }
    }

    /**
     * Insert the bank
     * @return bool
     */
    private function saveCoverage(): bool
    {
        $con = new \MysqlClass();
        $query = "INSERT INTO coverage (type, limit, deductible) VALUES (?, ?, ?);";
        $con->pushParam($this->type);
        $con->pushParam($this->limit);
        $con->pushParam($this->deductible);

        if ($con->executeNoneQuery($query) > 0) {
            $this->coverageID = $con->getLastID();
            return true;
        }

        return false;
    }

    /**
     * Update the coverage
     * @return bool
     */
    private function updateCoverage(): bool
    {
        $con = new \MysqlClass();
        $query = "UPDATE coverage SET type = ?, limit = ?, deductible = ? WHERE coverageID = ? AND Deleted = 0";
        $con->pushParam($this->type);
        $con->pushParam($this->limit);
        $con->pushParam($this->deductible);
        $con->pushParam($this->coverageID);

        return $con->executeNoneQuery($query) > 0;
    }

    /**
     * Delete the coverage
     * @param int $coverageIDID
     * @return bool
     */
    public static function delete(int $covergaeID): bool
    {
        $con = new \MysqlClass();
        $query = "UPDATE coverage SET Deleted = 1 WHERE coverageID = ?";
        $con->pushParam($coverageID);

        return $con->executeNoneQuery($query) > 0;
    }

    /**
     * Get the coverage by coverageIDID
     * @param int $coverageIDID
     * @return coverage|null
     */
    public static function getcoveragerBycoverageID(int $coverageID): ?policyholder
    {
        $con = new \MysqlClass();
        $query = "SELECT * FROM coverage WHERE coverageID = ? AND Deleted = 0 LIMIT 1";
        $con->pushParam($coverageID);
        $result = $con->queryObject($query);

        if ($result) {
            return self::mapCoverage($result);
        }

        return null;
    }

    /**
     * Get all coverages
     * @return array
     */
    public static function getAllCoverages(): array
    {
        $con = new \MysqlClass();
        $query = "SELECT * FROM coverage WHERE Deleted = 0";
        $result = [];

        $Coverages = $con->queryAllObject($query);

        if ($Coverages) {
            foreach ($Coverages as $row) {
                $result[] = self::mapCoverage($row);
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
        $banks = self::getAllCoverage();
        $result = [];

        foreach ($Coverages as $Coverage) {
            $result[] = ["coverageID" => $Coverage->getcoverageID(), "name" => $coverage->gettype()];
        }

        return $result;
    }

    private static function mapCoverage($Coverage): Coverage
    {
        return (new self())
            ->setcoverageID($Coverage->coverageID)
            ->settype($Coverage->type)
            ->setlimit($Coverage->limit)
            ->setdeductible($Coverage->deductible);
    }