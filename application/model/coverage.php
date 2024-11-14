<?php
namespace CAR4SURE\Application\Model;



/**
 * Coverage Model
 */
class Coverage
{
    private int $coverageId;
    private string $type;
    private int $limit;
    private int $deductible;
    private int $policyNo;

    public function __construct()
    {
        $this->coverageID = 0;
        $this->type = '';
        $this->limit = 0;
        $this->deductible = 0;
        $this->policyNo = 0;
    }
    public function getCoverageId(): int
    {
        return $this->coverageId;
    }

    /**
     * Set the value of coverageID
     * @param int $coverageID
     * @return self
     */
    public function setCoverageId(int $coverageID): self
    {
        $this->coverageID = $coverageID;
        return $this;
    }

    public function getType(): string
    {
        return $this->type;
    }

    /**
     * Set the value of type
     * @param int $type
     * @return self
     */
    public function setType(int $type): self
    {
        $this->type = $type;
        return $this;
    }

    public function getLimit(): int
    {
        return $this->limit;
    }

    /**
     * Set the value of limit
     * @param int $limit
     * @return self
     */
    public function setLimit(int $limit): self
    {
        $this->limit = $limit;
        return $this;
    }

    public function getDeductible(): int
    {
        return $this->deductible;
    }

    /**
     * Set the value of deductible
     * @param int $deductible
     * @return self
     */
    public function setDeductible(string $deductible): self
    {
        $this->deductible = $deductible;
        return $this;
    }
    public function getPolicyNo(): int
    {
        return $this->policyNo;
    }

    /**
     * Set the value of coverageID
     * @param int $coverageID
     * @return self
     */
    public function setPolicyNo(int $policyNo): self
    {
        $this->policyNo = $policyNo;
        return $this;
    }
  
    public function save(): bool
    {
        if ($this->coverageId == 0) {
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
            $this->coverageID = $con->getLastInsertId();
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
        $con->pushParam($this->coverageId);

        return $con->executeNoneQuery($query) > 0;
    }

    /**
     * Delete the coverage
     * @param int $coverageIDID
     * @return bool
     */
    public static function delete(int $coverageID): bool
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
    public static function getCoverageByCoverageId(int $coverageID): ?Coverage
    {
        $con = new \MysqlClass();
        $query = "SELECT * FROM coverage WHERE coverageID = ? AND Deleted = 0 LIMIT 1";
        $con->pushParam($coverageID);
        $result = $con->queryObject($query);

        if ($result) {
            return self::map($result);
        }

        return null;
    }

    /**
     * Get all coverages
     * @return array
     */
    public static function getAll(): array
    {
        $con = new \MysqlClass();
        $query = "SELECT * FROM coverage WHERE Deleted = 0";
        $result = [];

        $Coverages = $con->queryAllObject($query);

        if ($Coverages) {
            foreach ($Coverages as $row) {
                $result[] = self::map($row);
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
        $Coverages = self::getAll();
        $result = [];

        foreach ($Coverages as $Coverage) {
            $result[] = ["coverageID" => $Coverage->getcoverageID(), "name" => $Coverage->gettype()];
        }

        return $result;
    }

    private static function map($data): Coverage
    {
        return (new self())
            ->setcoverageID($data->coverageID)
            ->settype($data->type)
            ->setlimit($data->limit)
            ->setdeductible($data->deductible);
    }
}