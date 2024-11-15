<?php

namespace Care4Sure\Application\Model;

class PolicyCoverage
{
    private int $policyCoverageId;
    private int $policyNo;
    private int $coverageId;
    private float $limit;
    private float $deductible;
    private bool $deleted;

    public function __construct()
    {
        $this->policyCoverageId = 0;
        $this->policyNo = 0;
        $this->coverageId = 0;
        $this->limit = 0.00;
        $this->deductible = 0.00;
        $this->deleted = false;
    }

    public function getPolicyCoverageId(): int
    {
        return $this->policyCoverageId;
    }

    public function setPolicyCoverageId(int $policyCoverageId): self
    {
        $this->policyCoverageId = $policyCoverageId;
        return $this;
    }

    public function getPolicyNo(): int
    {
        return $this->policyNo;
    }

    public function setPolicyNo(int $policyNo): self
    {
        $this->policyNo = $policyNo;
        return $this;
    }

    public function getCoverageId(): int
    {
        return $this->coverageId;
    }

    public function setCoverageId(int $coverageId): self
    {
        $this->coverageId = $coverageId;
        return $this;
    }

    public function getLimit(): float
    {
        return $this->limit;
    }

    public function setLimit(float $limit): self
    {
        $this->limit = $limit;
        return $this;
    }

    public function getDeductible(): float
    {
        return $this->deductible;
    }

    public function setDeductible(float $deductible): self
    {
        $this->deductible = $deductible;
        return $this;
    }

    public function getDeleted(): bool
    {
        return $this->deleted;
    }

    public function setDeleted(bool $deleted): self
    {
        $this->deleted = $deleted;
        return $this;
    }

    public static function delete(int $policyCoverageId): bool
    {
        $con = new \MysqlClass();
        $query = "UPDATE policycoverage SET Deleted = 1 WHERE PolicyCoverageId = ? AND Deleted = 0";
        $con->pushParam($policyCoverageId);

        return $con->executeNoneQuery($query) > 0;
    }

    public static function getByPolicyCoverageId(int $policyCoverageId): ?self
    {
        $con = new \MysqlClass();
        $query = "SELECT * FROM policycoverage WHERE PolicyCoverageId = ? AND Deleted = 0 LIMIT 1";
        $con->pushParam($policyCoverageId);

        $result = $con->queryObject($query);

        if ($result)
        {
            return self::map($result);
        }

        return null;
    }

    public static function getAll(): array
    {
        $con = new \MysqlClass();
        $query = "SELECT * FROM policycoverage WHERE Deleted = 0";

        $result = $con->queryAllObject($query);

        $policyCoverages = [];
        foreach ($result as $policyCoverage)
        {
            $policyCoverages[] = self::map($policyCoverage);
        }

        return $policyCoverages;
    }

    public function save(): bool
    {
        if ($this->policyCoverageId == 0)
        {
            return $this->create();
        }
        else
        {
            return $this->update();
        }
    }

    private function create(): bool
    {
        $con = new \MysqlClass();
        $query = "INSERT INTO policycoverage (PolicyNo, CoverageId, Limit, Deductible, Deleted) VALUES (?, ?, ?, ?, ?)";

        $con->pushParam($this->policyNo);
        $con->pushParam($this->coverageId);
        $con->pushParam($this->limit);
        $con->pushParam($this->deductible);
        $con->pushParam((int) $this->deleted);

        if ($con->executeNoneQuery($query) > 0)
        {
            $this->policyCoverageId = $con->getLastInsertId();
            return true;
        }

        return false;
    }

    private function update(): bool
    {
        $con = new \MysqlClass();
        $query = "UPDATE policycoverage SET PolicyNo = ?, CoverageId = ?, Limit = ?, Deductible = ?, Deleted = ? WHERE PolicyCoverageId = ?";

        $con->pushParam($this->policyNo);
        $con->pushParam($this->coverageId);
        $con->pushParam($this->limit);
        $con->pushParam($this->deductible);
        $con->pushParam((int) $this->deleted);
        $con->pushParam($this->policyCoverageId);

        return $con->executeNoneQuery($query) > 0;
    }

    private static function map($policyCoverage): self
    {
        return (new self())
            ->setPolicyCoverageId($policyCoverage->PolicyCoverageId)
            ->setPolicyNo($policyCoverage->PolicyNo)
            ->setCoverageId($policyCoverage->CoverageId)
            ->setLimit($policyCoverage->Limit)
            ->setDeductible($policyCoverage->Deductible)
            ->setDeleted((bool) $policyCoverage->Deleted);
    }
}